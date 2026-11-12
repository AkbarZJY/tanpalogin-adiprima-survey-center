<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\SurveyResponse;
use App\Models\SurveyAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SurveyFormController extends Controller
{
    public function showForm($slug)
    {
        $survey = Survey::where('slug', $slug)
            ->with(['activePeriod', 'questions.dimensionModel', 'ratingScale'])
            ->firstOrFail();

        // Check if survey is archived or active
        if ($survey->is_archived) {
            return view('survey.inactive', [
                'survey' => $survey,
                'message' => 'Kuesioner ini telah diarsipkan dan ditutup. Terima kasih atas partisipasi Anda pada periode sebelumnya.',
            ]);
        }

        if (!$survey->is_active) {
            return view('survey.inactive', [
                'survey' => $survey,
                'message' => 'Kuesioner ini sedang dinonaktifkan oleh administrator.',
            ]);
        }

        $today = Carbon::today();
        if ($survey->start_date && $today->lt($survey->start_date)) {
            return view('survey.inactive', [
                'survey' => $survey,
                'message' => 'Kuesioner ini belum dimulai. Periode survei baru akan dibuka pada ' . $survey->start_date->isoFormat('D MMMM Y') . '.',
            ]);
        }

        if ($survey->end_date && $today->gt($survey->end_date)) {
            return view('survey.inactive', [
                'survey' => $survey,
                'message' => 'Masa aktif kuesioner ini telah berakhir pada ' . $survey->end_date->isoFormat('D MMMM Y') . '.',
            ]);
        }

        $period = $survey->getOrCreateCurrentPeriod();

        // User object (null jika guest / user biasa tanpa login)
        $user = Auth::user();

        // Check if this visitor already submitted (by IP + session for guests)
        $existingResponse = null;
        if ($user) {
            $existingResponse = SurveyResponse::where('survey_id', $survey->id)
                ->where('survey_period_id', $period->id)
                ->where('user_id', $user->id)
                ->first();
        }

        // Standard organizational demographics
        $departments = [
            'HRGA', 'PPIC', 'QC', 'UTILITY', 'R&D',
            'PM1', 'PM2', 'PM3', 'TEKNIK', 'PP',
            'MRK', 'PCH', 'IT', 'FA', 'IA'
        ];

        $positions = [
            'General Manager', 'Manager', 'Ast. Manager',
            'Supervisor', 'Ast. Supervisor', 'Staf', 'Karu', 'Operator'
        ];

        $tenures = [
            'kurang dari 1 tahun', '1 - 5 tahun', '6 - 10 tahun',
            '11 - 15 tahun', '16 - 20 tahun', '21 - 25 tahun', '> 25 tahun'
        ];

        $educations = [
            'SD', 'SMP Sederajat', 'SMA/ SMK Sederjat',
            'D1 - D2 - D3 - D4', 'S1', 'S2 ke atas'
        ];

        $outsourcingCompanies = ['KBM', 'BAP', 'GCI'];

        // Group questions by dimension and section
        $questions = $survey->questions->sortBy('order');
        $sectionBQuestions = $questions->where('section', 'B');
        $sectionCQuestions = $questions->where('section', 'C');
        $questionsByDimension = $questions->groupBy('dimensionName');

        return view('survey.form', compact(
            'survey', 'period', 'user', 'existingResponse',
            'departments', 'positions', 'tenures', 'educations', 'outsourcingCompanies',
            'questions', 'sectionBQuestions', 'sectionCQuestions', 'questionsByDimension'
        ));
    }

    public function store(Request $request, $slug)
    {
        $survey = Survey::where('slug', $slug)->with(['activePeriod', 'questions'])->firstOrFail();

        if (!$survey->isWithinActiveDate()) {
            return back()->with('error', 'Kuesioner tidak aktif atau telah melewati batas masa pengisian.');
        }

        $period = $survey->getOrCreateCurrentPeriod();

        // User (null jika guest)
        $user = Auth::user();

        // Validation for Demographics
        $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'required|string',
            'age' => 'required|numeric|min:17|max:80',
            'education' => 'required|string',
            'employment_status' => 'required|string',
            'outsourcing_company' => 'required_if:employment_status,Outsourcing|nullable|in:KBM,BAP,GCI',
            'tenure' => 'required|string',
            'department' => 'required|string',
            'position' => 'required|string',
        ], [
            'outsourcing_company.required_if' => 'Nama outsourcing wajib dipilih jika status kepegawaian adalah Outsourcing.',
            'outsourcing_company.in' => 'Nama outsourcing harus salah satu dari KBM, BAP, atau GCI.',
        ]);

        $isPermanent = strtolower($request->employment_status) === 'tetap';
        $leadershipPositions = ['General Manager', 'Manager', 'Ast. Manager', 'Supervisor', 'Ast. Supervisor', 'Karu'];
        $isLeadership = in_array($request->position, $leadershipPositions);

        // Determine unique identification for updateOrCreate
        if ($user) {
            // Logged-in user (admin yang mengisi): use user_id
            $responseMatch = [
                'survey_id' => $survey->id,
                'survey_period_id' => $period->id,
                'user_id' => $user->id,
            ];
        } else {
            // Guest user: use IP + name combination
            $responseMatch = [
                'survey_id' => $survey->id,
                'survey_period_id' => $period->id,
                'ip_address' => $request->ip(),
                'name' => $request->name,
            ];
        }

        // Save or update survey response header
        $response = SurveyResponse::updateOrCreate(
            $responseMatch,
            [
                'user_id' => $user?->id,
                'nik' => $user?->nik ?? $request->input('nik'),
                'name' => $request->name,
                'gender' => $request->gender,
                'age' => $request->age,
                'education' => $request->education,
                'employment_status' => $request->employment_status,
                'outsourcing_company' => $request->employment_status === 'Outsourcing' ? $request->outsourcing_company : null,
                'tenure' => $request->tenure,
                'department' => $request->department,
                'position' => $request->position,
                'started_at' => $request->started_at ? Carbon::parse($request->started_at) : Carbon::now(),
                'submitted_at' => Carbon::now(),
                'ip_address' => $request->ip(),
            ]
        );

        // Clear previous answers if re-submitting
        SurveyAnswer::where('survey_response_id', $response->id)->delete();

        // Dynamic processing for all questions in this survey
        foreach ($survey->questions as $question) {
            // Check criteria (Employment Status, Position, Gender)
            if ($question->applies_to_employment_status) {
                $targetEmp = strtolower(trim($question->applies_to_employment_status));
                $respEmp = strtolower(trim($request->employment_status));

                if ($targetEmp !== '' && !str_contains($targetEmp, 'semua')) {
                    $empMatch = false;
                    if (str_contains($targetEmp, 'outsourcing')) {
                        $empMatch = str_contains($respEmp, 'outsourcing');
                    } elseif (str_contains($targetEmp, 'kontrak')) {
                        $empMatch = str_contains($respEmp, 'kontrak') && !str_contains($respEmp, 'outsourcing');
                    } elseif (str_contains($targetEmp, 'tetap')) {
                        $empMatch = str_contains($respEmp, 'tetap');
                    } else {
                        $empMatch = ($targetEmp === $respEmp);
                    }

                    if (!$empMatch) {
                        continue;
                    }
                }
            }

            if ($question->applies_to_positions) {
                $targetPosStr = strtolower(trim($question->applies_to_positions));
                $respPos = strtolower(trim($request->position));

                if ($targetPosStr !== '' && !str_contains($targetPosStr, 'semua')) {
                    $targetPositions = array_filter(array_map('trim', explode(',', $targetPosStr)));
                    $posMatch = in_array($respPos, $targetPositions);

                    if (!$posMatch) {
                        $leadershipList = ['general manager', 'manager', 'ast. manager', 'supervisor', 'ast. supervisor', 'karu'];
                        foreach ($targetPositions as $tp) {
                            if (str_contains($tp, 'karu ke atas') || str_contains($tp, 'leadership')) {
                                if (in_array($respPos, $leadershipList)) {
                                    $posMatch = true;
                                    break;
                                }
                            }
                        }
                    }

                    if (!$posMatch) {
                        continue;
                    }
                }
            }

            if ($question->applies_to_gender) {
                $targetGender = strtolower(trim($question->applies_to_gender));
                $respGender = strtolower(trim($request->gender));

                if ($targetGender !== '' && !str_contains($targetGender, 'semua')) {
                    if ($targetGender !== $respGender) {
                        continue;
                    }
                }
            }

            // Handle Question Types
            if ($question->question_type === 'multiple_choice') {
                $choice = $request->input("choice_" . $question->id) ?? $request->input("mcq_" . $question->id);
                $reasonText = $request->input("reason_" . $question->id);
                $saveReason = ($question->require_reason_on_low_score && !empty($reasonText)) ? $reasonText : null;

                SurveyAnswer::create([
                    'survey_response_id' => $response->id,
                    'question_id' => $question->id,
                    'text_answer' => $choice,
                    'reason_text' => $saveReason,
                ]);
            } elseif ($question->question_type === 'single_rating') {
                $score = $request->input("single_rating_" . $question->id) ?? $request->input("general_" . $question->id) ?? $request->input("reality_" . $question->id);
                $reasonText = $request->input("reason_" . $question->id);

                $threshold = $question->low_score_threshold ?? 2;
                $saveReason = ($question->require_reason_on_low_score && $score <= $threshold) ? $reasonText : null;

                SurveyAnswer::create([
                    'survey_response_id' => $response->id,
                    'question_id' => $question->id,
                    'reality_score' => $score,
                    'reason_text' => $saveReason,
                ]);
            } elseif ($question->question_type === 'essay') {
                $textAnswer = $request->input("essay_" . $question->id) ?? $request->input("general_reason_" . $question->id) ?? $request->input("text_answer_" . $question->id);

                SurveyAnswer::create([
                    'survey_response_id' => $response->id,
                    'question_id' => $question->id,
                    'text_answer' => $textAnswer,
                ]);
            } else {
                // dual_rating or default Section B rating
                $expScore = $request->input("expectation_" . $question->id);
                $realScore = $request->input("reality_" . $question->id);
                $reasonText = $request->input("reason_" . $question->id);

                $threshold = $question->low_score_threshold ?? 2;
                $saveReason = ($question->require_reason_on_low_score && $realScore <= $threshold) ? $reasonText : null;

                SurveyAnswer::create([
                    'survey_response_id' => $response->id,
                    'question_id' => $question->id,
                    'expectation_score' => $expScore,
                    'reality_score' => $realScore,
                    'reason_text' => $saveReason,
                ]);
            }
        }

        return redirect()->route('survey.success', ['slug' => $survey->slug]);
    }

    public function success($slug)
    {
        $survey = Survey::where('slug', $slug)->firstOrFail();
        return view('survey.success', compact('survey'));
    }
}
