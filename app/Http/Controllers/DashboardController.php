<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\SurveyPeriod;
use App\Models\SurveyResponse;
use App\Models\SurveyAnswer;
use App\Models\SurveyQuestion;
use App\Models\SurveyDimension;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Fetch categories from SurveyCategory model with fallback to defaults
        $dbCategoryModels = \App\Models\SurveyCategory::orderBy('order')->orderBy('name')->pluck('name')->all();
        $standardCategories = !empty($dbCategoryModels) ? $dbCategoryModels : [
            'Survey Budaya Kerja',
            'Employee Engagement Survey',
            'Customer Satisfaction Survey',
        ];

        $allSurveys = Survey::withCount(['responses', 'questions'])
            ->orderBy('is_active', 'desc')
            ->orderBy('start_date', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        // Get all unique categories present in surveys or standard list
        $surveyCategoryStrings = $allSurveys->pluck('category')->filter()->unique()->values()->all();
        $allCategories = array_values(array_unique(array_merge($standardCategories, $surveyCategoryStrings)));

        // Group surveys by category
        $surveysByCategory = $allSurveys->groupBy('category');

        // Resolve Active Survey and Selected Category
        $surveyId = $request->get('survey_id');
        $categoryParam = $request->get('category');

        if ($surveyId) {
            $activeSurvey = Survey::find($surveyId);
            $selectedCategory = $activeSurvey ? $activeSurvey->category : ($categoryParam ?? $allCategories[0]);
        } elseif ($categoryParam) {
            $selectedCategory = $categoryParam;
            // Get active or first survey in this category
            $activeSurvey = Survey::where('category', $selectedCategory)->where('is_active', true)->latest()->first()
                ?? Survey::where('category', $selectedCategory)->latest()->first();
        } else {
            // Default to Employee Engagement Survey 2026 or first active survey
            $activeSurvey = Survey::where('slug', 'engagement-survey')->first()
                ?? Survey::where('is_active', true)->first()
                ?? Survey::first();
            $selectedCategory = $activeSurvey ? $activeSurvey->category : $allCategories[0];
        }

        if (!$activeSurvey) {
            return view('admin.dashboard.empty', [
                'allSurveys' => $allSurveys,
                'allCategories' => $allCategories,
                'surveysByCategory' => $surveysByCategory,
                'selectedCategory' => $selectedCategory,
            ]);
        }

        $period = $activeSurvey->activePeriod;

        $tab = $request->get('tab', 'raw_data'); // raw_data, rekap_perolehan, analisa, descriptive_reasons
        $search = $request->get('search');
        $departmentFilter = $request->get('department');
        $positionFilter = $request->get('position');

        // 2. Raw Data Query
        $responsesQuery = SurveyResponse::where('survey_id', $activeSurvey->id)
            ->with(['answers.question', 'user'])
            ->orderBy('submitted_at', 'desc');

        if ($search) {
            $responsesQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%");
            });
        }

        if ($departmentFilter) {
            $responsesQuery->where('department', $departmentFilter);
        }

        if ($positionFilter) {
            $responsesQuery->where('position', $positionFilter);
        }

        $responses = $responsesQuery->paginate(10)->withQueryString();

        // 3. Questions list for headers and analytics
        $questions = SurveyQuestion::where('survey_id', $activeSurvey->id)
            ->with('dimensionModel')
            ->orderBy('order')
            ->orderBy('id')
            ->get();

        // 4. Rekap Perolehan (Department Participation Monitoring)
        $departmentTargets = [
            'Produksi' => ['target' => 150, 'depts' => ['PM1', 'PM2', 'PM3', 'TEKNIK', 'UTILITY']],
            'Marketing' => ['target' => 30, 'depts' => ['MRK', 'PPIC']],
            'Finance' => ['target' => 20, 'depts' => ['FA', 'PCH', 'IA']],
            'HRGA' => ['target' => 15, 'depts' => ['HRGA', 'PP']],
            'IT & Tech' => ['target' => 10, 'depts' => ['IT', 'R&D', 'QC']],
        ];

        $rekapData = [];
        $totalTarget = 225;
        $totalFilled = SurveyResponse::where('survey_id', $activeSurvey->id)->count();

        foreach ($departmentTargets as $groupName => $info) {
            $count = SurveyResponse::where('survey_id', $activeSurvey->id)
                ->whereIn('department', $info['depts'])
                ->count();
            $target = $info['target'];
            $pct = $target > 0 ? round(($count / $target) * 100) : 0;
            $rekapData[] = [
                'group' => $groupName,
                'filled' => $count,
                'target' => $target,
                'progress' => $pct,
            ];
        }

        $overallProgressPct = $totalTarget > 0 ? round(($totalFilled / $totalTarget) * 100, 1) : 0;

        // 5. Dynamic Dimension Analytics
        // Extract distinct dimensions from this survey's questions
        $surveyDimensions = $questions->pluck('dimensionName')->unique()->filter()->values()->all();
        if (empty($surveyDimensions)) {
            $surveyDimensions = ['General'];
        }

        $dimensionScores = [];
        $dimensionExpectationScores = [];
        $dimensionGaps = [];

        foreach ($surveyDimensions as $dim) {
            $stats = DB::table('survey_answers')
                ->join('survey_questions', 'survey_answers.question_id', '=', 'survey_questions.id')
                ->join('survey_responses', 'survey_answers.survey_response_id', '=', 'survey_responses.id')
                ->where('survey_responses.survey_id', $activeSurvey->id)
                ->where(function ($q) use ($dim) {
                    $q->where('survey_questions.dimension', $dim)
                      ->orWhereExists(function ($sub) use ($dim) {
                          $sub->select(DB::raw(1))
                              ->from('survey_dimensions')
                              ->whereColumn('survey_dimensions.id', 'survey_questions.dimension_id')
                              ->where('survey_dimensions.name', $dim);
                      });
                })
                ->selectRaw('
                    AVG(CASE WHEN reality_score >= 1 THEN reality_score ELSE NULL END) as avg_real,
                    AVG(CASE WHEN expectation_score >= 1 THEN expectation_score ELSE NULL END) as avg_exp
                ')
                ->first();

            $realScore = round($stats->avg_real ?? 0, 2);
            $expScore = round($stats->avg_exp ?? 0, 2);

            $dimensionScores[$dim] = $realScore;
            $dimensionExpectationScores[$dim] = $expScore;
            $dimensionGaps[$dim] = round($realScore - $expScore, 2);
        }

        // Skor Rata-Rata Seluruh Dimensi (murni dari data valid >= 1, abaikan skor 0 / < 1)
        $validRealDims = array_filter($dimensionScores, fn($s) => $s >= 1);
        $overallRealScore = !empty($validRealDims) ? round(array_sum($validRealDims) / count($validRealDims), 2) : 0;

        $validExpDims = array_filter($dimensionExpectationScores, fn($s) => $s >= 1);
        $overallExpScore = !empty($validExpDims) ? round(array_sum($validExpDims) / count($validExpDims), 2) : 0;

        $overallGap = round($overallRealScore - $overallExpScore, 2);
        $overallEngagementScore = $overallRealScore;

        $surveyScaleMax = $activeSurvey->ratingScale?->max_scale ?? $activeSurvey->rating_scale ?? 4;

        // Highest and Lowest Dimension
        $highestDimension = '-';
        $highestScore = 0;
        $lowestDimension = '-';
        $lowestScore = 999;

        foreach ($dimensionScores as $dim => $score) {
            if ($score >= $highestScore && $score >= 1) {
                $highestScore = $score;
                $highestDimension = $dim;
            }
            if ($score <= $lowestScore && $score >= 1) {
                $lowestScore = $score;
                $lowestDimension = $dim;
            }
        }
        if ($lowestScore === 999) {
            $lowestScore = 0;
        }

        // 5b. Per-Indicator (Question) Analytics grouped by Dimension for Drill-Down & IPA Matrix
        $questionStats = DB::table('survey_answers')
            ->join('survey_responses', 'survey_answers.survey_response_id', '=', 'survey_responses.id')
            ->where('survey_responses.survey_id', $activeSurvey->id)
            ->groupBy('survey_answers.question_id')
            ->selectRaw('
                survey_answers.question_id,
                AVG(CASE WHEN reality_score >= 1 THEN reality_score ELSE NULL END) as avg_real,
                AVG(CASE WHEN expectation_score >= 1 THEN expectation_score ELSE NULL END) as avg_exp,
                COUNT(DISTINCT CASE WHEN reality_score >= 1 OR expectation_score >= 1 THEN survey_responses.id ELSE NULL END) as respondent_count
            ')
            ->get()
            ->keyBy('question_id');

        $classifyIpaQuadrant = function (?float $exp, ?float $real, float $meanExp, float $meanReal): ?string {
            if ($exp === null || $real === null || $exp < 1 || $real < 1 || $meanExp < 1 || $meanReal < 1) {
                return null;
            }
            if ($exp >= $meanExp && $real < $meanReal) {
                return 'need_improvement';
            }
            if ($exp >= $meanExp && $real >= $meanReal) {
                return 'maintain_quality';
            }
            if ($exp < $meanExp && $real < $meanReal) {
                return 'low_priority';
            }
            return 'possible_overkill';
        };

        $dimensionIpaOverview = [];
        foreach ($surveyDimensions as $dim) {
            $exp = $dimensionExpectationScores[$dim] ?? 0;
            $real = $dimensionScores[$dim] ?? 0;
            $dimensionIpaOverview[$dim] = $classifyIpaQuadrant(
                $exp >= 1 ? $exp : null,
                $real >= 1 ? $real : null,
                $overallExpScore,
                $overallRealScore
            );
        }

        $dimensionDetails = [];
        foreach ($surveyDimensions as $dim) {
            $dimQuestions = $questions->filter(function ($q) use ($dim, $surveyDimensions) {
                $qDim = $q->dimensionName;
                if ($dim === 'General' && empty($qDim) && count($surveyDimensions) === 1) {
                    return true;
                }
                return $qDim === $dim;
            })->values();

            $items = [];
            $validExps = [];
            $validReals = [];

            foreach ($dimQuestions as $q) {
                $stat = $questionStats->get($q->id);
                $avgExp = ($stat && $stat->avg_exp !== null && (float) $stat->avg_exp >= 1) ? round((float) $stat->avg_exp, 2) : null;
                $avgReal = ($stat && $stat->avg_real !== null && (float) $stat->avg_real >= 1) ? round((float) $stat->avg_real, 2) : null;
                $gap = ($avgExp !== null && $avgReal !== null) ? round($avgReal - $avgExp, 2) : null;

                if ($avgExp !== null) {
                    $validExps[] = $avgExp;
                }
                if ($avgReal !== null) {
                    $validReals[] = $avgReal;
                }

                $items[] = [
                    'id' => $q->id,
                    'question_number' => $q->question_number,
                    'code' => 'Q' . $q->question_number,
                    'indicator_title' => $q->indicator_title ?: ('Pertanyaan ' . $q->question_number),
                    'question_text' => $q->question_text,
                    'question_type' => $q->question_type ?? ($q->section === 'B' ? 'dual_rating' : 'single_rating'),
                    'avg_exp' => $avgExp,
                    'avg_real' => $avgReal,
                    'gap' => $gap,
                    'respondent_count' => $stat ? (int) $stat->respondent_count : 0,
                    'quadrant' => null,
                ];
            }

            $dimMeanExp = !empty($validExps)
                ? round(array_sum($validExps) / count($validExps), 2)
                : ($dimensionExpectationScores[$dim] ?? 0);
            $dimMeanReal = !empty($validReals)
                ? round(array_sum($validReals) / count($validReals), 2)
                : ($dimensionScores[$dim] ?? 0);

            foreach ($items as &$item) {
                $item['quadrant'] = $classifyIpaQuadrant($item['avg_exp'], $item['avg_real'], $dimMeanExp, $dimMeanReal);
            }
            unset($item);

            $dimensionDetails[$dim] = [
                'name' => $dim,
                'avg_exp' => $dimensionExpectationScores[$dim] ?? 0,
                'avg_real' => $dimensionScores[$dim] ?? 0,
                'gap' => $dimensionGaps[$dim] ?? 0,
                'ipa_mean_exp' => $dimMeanExp,
                'ipa_mean_real' => $dimMeanReal,
                'indicators' => $items,
            ];
        }

        // 6. Descriptive Answers / Low-score reasons collection
        $lowScoreReasons = SurveyAnswer::whereHas('response', function ($q) use ($activeSurvey) {
                $q->where('survey_id', $activeSurvey->id);
            })
            ->whereNotNull('reason_text')
            ->where('reason_text', '!=', '')
            ->with(['question', 'response'])
            ->latest()
            ->take(50)
            ->get();

        $essayAnswers = SurveyAnswer::whereHas('response', function ($q) use ($activeSurvey) {
                $q->where('survey_id', $activeSurvey->id);
            })
            ->whereNotNull('text_answer')
            ->where('text_answer', '!=', '')
            ->with(['question', 'response'])
            ->latest()
            ->take(50)
            ->get();

        // Group all feedback answers by question with keyword/feedback frequency totals
        $allFeedbackAnswers = SurveyAnswer::whereHas('response', function ($q) use ($activeSurvey) {
                $q->where('survey_id', $activeSurvey->id);
            })
            ->where(function ($q) {
                $q->where(function ($sub) {
                    $sub->whereNotNull('reason_text')->where('reason_text', '!=', '');
                })->orWhere(function ($sub) {
                    $sub->whereNotNull('text_answer')->where('text_answer', '!=', '');
                });
            })
            ->with(['question.dimensionModel', 'response'])
            ->orderBy('id', 'desc')
            ->get();

        $groupedByQuestion = $allFeedbackAnswers->groupBy('question_id');
        $feedbackByQuestion = [];

        foreach ($questions as $q) {
            $qAnswers = $groupedByQuestion->get($q->id, collect());
            if ($qAnswers->isEmpty()) {
                continue;
            }

            // Group text items by normalized phrase to count totals
            $textCounts = [];
            foreach ($qAnswers as $ans) {
                $rawText = trim($ans->reason_text ?: $ans->text_answer);
                if ($rawText === '') continue;

                $normKey = mb_strtolower(preg_replace('/\s+/', ' ', $rawText));
                
                if (!isset($textCounts[$normKey])) {
                    $textCounts[$normKey] = [
                        'sample_text' => $rawText,
                        'total' => 0,
                        'departments' => [],
                        'responses' => [],
                        'scores' => [],
                    ];
                }

                $textCounts[$normKey]['total']++;
                if ($ans->response?->department && !in_array($ans->response->department, $textCounts[$normKey]['departments'])) {
                    $textCounts[$normKey]['departments'][] = $ans->response->department;
                }
                if ($ans->reality_score !== null) {
                    $textCounts[$normKey]['scores'][] = $ans->reality_score;
                }
                $textCounts[$normKey]['responses'][] = [
                    'name' => $ans->response?->name ?? 'Responden',
                    'dept' => $ans->response?->department ?? '-',
                    'pos' => $ans->response?->position ?? '-',
                    'reality' => $ans->reality_score,
                    'expectation' => $ans->expectation_score,
                    'text' => $rawText,
                    'submitted_at' => $ans->response?->submitted_at ?? $ans->created_at,
                ];
            }

            // Sort by highest frequency count
            uasort($textCounts, function ($a, $b) {
                return $b['total'] <=> $a['total'];
            });

            // Extract keyword frequency breakdown
            $stopWords = [
                'dan', 'yang', 'di', 'ke', 'dari', 'ini', 'itu', 'untuk', 'pada', 'adalah', 
                'dengan', 'saya', 'kami', 'kita', 'ada', 'bisa', 'karena', 'agar', 'atau', 
                'sudah', 'belum', 'lebih', 'harus', 'mohon', 'tolong', 'sangat', 'juga', 
                'akan', 'nya', 'yg', 'dlm', 'dg', 'krn', 'tdk', 'tidak', 'agak', 'masih',
                'oleh', 'atas', 'serta', 'bagi', 'saat', 'para', 'banyak', 'kurang'
            ];
            $keywordFreq = [];
            foreach ($qAnswers as $ans) {
                $text = trim($ans->reason_text ?: $ans->text_answer);
                $cleanWords = preg_split('/[\s,\.\?!:;\(\)\[\]"\'\/]+/', mb_strtolower($text));
                foreach ($cleanWords as $w) {
                    $w = trim($w);
                    if (mb_strlen($w) >= 3 && !in_array($w, $stopWords) && !is_numeric($w)) {
                        $keywordFreq[$w] = ($keywordFreq[$w] ?? 0) + 1;
                    }
                }
            }
            arsort($keywordFreq);
            $topKeywords = array_slice($keywordFreq, 0, 8, true);

            $feedbackByQuestion[] = [
                'question' => $q,
                'total_feedback' => $qAnswers->count(),
                'grouped_items' => array_values($textCounts),
                'top_keywords' => $topKeywords,
            ];
        }

        // 7. Filter Options
        $allDepartments = [
            'HRGA', 'PPIC', 'QC', 'UTILITY', 'R&D',
            'PM1', 'PM2', 'PM3', 'TEKNIK', 'PP',
            'MRK', 'PCH', 'IT', 'FA', 'IA'
        ];
        $allPositions = [
            'General Manager', 'Manager', 'Ast. Manager',
            'Supervisor', 'Ast. Supervisor', 'Staf', 'Karu', 'Operator'
        ];

        return view('admin.dashboard.index', compact(
            'allSurveys', 'allCategories', 'selectedCategory', 'surveysByCategory',
            'activeSurvey', 'period', 'tab', 'search', 'departmentFilter', 'positionFilter',
            'responses', 'questions', 'rekapData', 'totalTarget', 'totalFilled', 'overallProgressPct',
            'surveyDimensions', 'dimensionScores', 'dimensionExpectationScores', 'dimensionGaps',
            'dimensionDetails', 'dimensionIpaOverview',
            'overallEngagementScore', 'overallRealScore', 'overallExpScore', 'overallGap', 'surveyScaleMax',
            'highestDimension', 'highestScore', 'lowestDimension', 'lowestScore',
            'lowScoreReasons', 'essayAnswers', 'feedbackByQuestion', 'allDepartments', 'allPositions'
        ));
    }

    public function exportXlsx(Request $request)
    {
        $surveyId = $request->get('survey_id');
        if ($surveyId) {
            $activeSurvey = Survey::findOrFail($surveyId);
        } else {
            $activeSurvey = Survey::where('slug', 'engagement-survey')->first() ?? Survey::firstOrFail();
        }

        $questions = SurveyQuestion::where('survey_id', $activeSurvey->id)
            ->orderBy('order')
            ->orderBy('id')
            ->get();

        $responses = SurveyResponse::where('survey_id', $activeSurvey->id)
            ->with(['answers.question'])
            ->orderBy('submitted_at', 'desc')
            ->get();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Raw Data');

        // Header columns
        $headers = [
            'No',
            'NIK',
            'Nama Lengkap',
            'Jenis Kelamin',
            'Usia',
            'Pendidikan',
            'Status Kepegawaian',
            'Lama Bekerja',
            'Departemen',
            'Jabatan',
            'Mulai Dikerjakan',
            'Selesai Dikerjakan',
            'Lama Pengerjaan (Menit)',
        ];

        foreach ($questions as $q) {
            if ($q->question_type === 'dual_rating' || (empty($q->question_type) && $q->section === 'B')) {
                $headers[] = 'Q' . $q->question_number . ' - ' . ($q->indicator_title ?? $q->dimension ?? 'Soal') . ' (Harapan)';
                $headers[] = 'Q' . $q->question_number . ' - ' . ($q->indicator_title ?? $q->dimension ?? 'Soal') . ' (Kenyataan)';
                $headers[] = 'Q' . $q->question_number . ' (Alasan Nilai Rendah)';
            } elseif ($q->question_type === 'essay') {
                $headers[] = 'Q' . $q->question_number . ' - ' . ($q->indicator_title ?? 'Uraian');
            } elseif ($q->question_type === 'multiple_choice') {
                $headers[] = 'Q' . $q->question_number . ' - ' . ($q->indicator_title ?? $q->dimension ?? 'Pilihan Ganda');
                if ($q->require_reason_on_low_score) {
                    $headers[] = 'Q' . $q->question_number . ' (Keterangan / Alasan)';
                }
            } else {
                $headers[] = 'Q' . $q->question_number . ' - ' . ($q->indicator_title ?? $q->dimension ?? 'Skor');
                if ($q->require_reason_on_low_score) {
                    $headers[] = 'Q' . $q->question_number . ' (Alasan Nilai Rendah)';
                }
            }
        }

        // Write Header Row
        $sheet->fromArray($headers, null, 'A1');

        // Style Header Row
        $highestColumn = $sheet->getHighestColumn();
        $headerRange = 'A1:' . $highestColumn . '1';
        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 10,
                'name' => 'Segoe UI',
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '0C2B64'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(30);

        // Write Data Rows
        $rowIndex = 2;
        $no = 1;
        foreach ($responses as $resp) {
            $answersMap = $resp->answers->keyBy('question_id');

            $startedAt = $resp->started_at ?? $resp->created_at;
            $submittedAt = $resp->submitted_at ?? $resp->updated_at;

            $durationMinutes = '-';
            if ($startedAt && $submittedAt) {
                $diffSec = $startedAt->diffInSeconds($submittedAt);
                $minVal = round($diffSec / 60, 1);
                $durationMinutes = $minVal < 1 ? '< 1' : $minVal;
            }

            $row = [
                $no++,
                $resp->nik ?? '-',
                $resp->name,
                $resp->gender ?? '-',
                $resp->age ?? '-',
                $resp->education ?? '-',
                $resp->employment_status ? ($resp->employment_status . ($resp->outsourcing_company ? ' (' . $resp->outsourcing_company . ')' : '')) : '-',
                $resp->tenure ?? '-',
                $resp->department ?? '-',
                $resp->position ?? '-',
                $startedAt ? $startedAt->format('d/m/Y ; H:i') : '-',
                $submittedAt ? $submittedAt->format('d/m/Y ; H:i') : '-',
                $durationMinutes,
            ];

            foreach ($questions as $q) {
                $ans = $answersMap->get($q->id);
                if ($q->question_type === 'dual_rating' || (empty($q->question_type) && $q->section === 'B')) {
                    $row[] = $ans ? ($ans->expectation_score ?? '-') : '-';
                    $row[] = $ans ? ($ans->reality_score ?? '-') : '-';
                    $row[] = $ans ? ($ans->reason_text ?? '-') : '-';
                } elseif ($q->question_type === 'essay') {
                    $row[] = $ans ? ($ans->text_answer ?? '-') : '-';
                } elseif ($q->question_type === 'multiple_choice') {
                    $row[] = $ans ? ($ans->text_answer ?? '-') : '-';
                    if ($q->require_reason_on_low_score) {
                        $row[] = $ans ? ($ans->reason_text ?? '-') : '-';
                    }
                } else {
                    $row[] = $ans ? ($ans->reality_score ?? $ans->text_answer ?? '-') : '-';
                    if ($q->require_reason_on_low_score) {
                        $row[] = $ans ? ($ans->reason_text ?? '-') : '-';
                    }
                }
            }

            $sheet->fromArray($row, null, 'A' . $rowIndex);
            $rowIndex++;
        }

        $lastDataRow = max(2, $rowIndex - 1);
        $fullDataRange = 'A1:' . $highestColumn . $lastDataRow;

        // Apply Borders & Font for whole table
        $sheet->getStyle($fullDataRange)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => 'CBD5E1'],
                ],
            ],
            'font' => [
                'name' => 'Segoe UI',
                'size' => 9.5,
            ],
        ]);

        // Auto-fit Column Widths
        foreach (range(1, count($headers)) as $colIndex) {
            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
            $sheet->getColumnDimension($colLetter)->setAutoSize(true);
        }

        // Freeze top header row
        $sheet->freezePane('A2');

        $fileName = "Raw_Data_" . Str::slug($activeSurvey->title) . "_" . date('Ymd_His') . ".xlsx";

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Cache-Control' => 'max-age=0',
        ]);
    }
}
