<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Survey;
use App\Models\SurveyCategory;
use App\Models\SurveyPeriod;
use App\Models\SurveyDimension;
use App\Models\QuestionTemplate;
use App\Models\SurveyQuestion;
use App\Models\RatingScale;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SurveyManagementController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'active'); // 'active' or 'archived'
        $categoryFilter = $request->get('category');

        $activeCount = Survey::unarchived()->count();
        $archivedCount = Survey::archived()->count();

        $query = ($tab === 'archived') ? Survey::archived() : Survey::unarchived();

        $query->withCount(['questions', 'responses'])
            ->with(['activePeriod', 'categoryModel'])
            ->orderBy('is_active', 'desc')
            ->orderBy('id', 'desc');

        if ($categoryFilter && $categoryFilter !== 'all') {
            $query->where(function ($q) use ($categoryFilter) {
                $q->where('category', $categoryFilter)
                  ->orWhereHas('categoryModel', function ($sub) use ($categoryFilter) {
                      $sub->where('slug', $categoryFilter)->orWhere('name', $categoryFilter);
                  });
            });
        }

        $surveys = $query->get();
        $categories = SurveyCategory::orderBy('order')->orderBy('name')->get();

        return view('admin.surveys.index', compact('surveys', 'tab', 'activeCount', 'archivedCount', 'categories', 'categoryFilter'));
    }

    public function create()
    {
        $categories = SurveyCategory::where('is_active', true)->orderBy('order')->orderBy('name')->get();
        $ratingScales = RatingScale::orderByDesc('is_default')->orderBy('max_score')->orderBy('name')->get();
        return view('admin.surveys.create', compact('categories', 'ratingScales'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'rating_scale_id' => 'required|exists:rating_scales,id',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'nullable|boolean',
        ], [
            'rating_scale_id.required' => 'Pilihan skala rating untuk kuesioner ini wajib ditentukan.',
        ]);

        $ratingScale = RatingScale::findOrFail($request->rating_scale_id);

        $slug = Str::slug($request->title);
        $count = Survey::where('slug', 'like', "{$slug}%")->count();
        if ($count > 0) {
            $slug .= '-' . ($count + 1);
        }

        // Find or create SurveyCategory
        $categoryModel = SurveyCategory::firstOrCreate(
            ['name' => $request->category],
            [
                'slug' => Str::slug($request->category),
                'icon' => $request->icon ?? 'bi-clipboard-data',
                'is_active' => true,
            ]
        );

        $survey = Survey::create([
            'title' => $request->title,
            'slug' => $slug,
            'category' => $request->category,
            'survey_category_id' => $categoryModel->id,
            'rating_scale_id' => $ratingScale->id,
            'rating_scale' => $ratingScale->max_score,
            'description' => $request->description,
            'icon' => $request->icon ?? 'bi-clipboard-data',
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $request->has('is_active'),
            'is_archived' => false,
        ]);

        // Create initial survey period
        SurveyPeriod::create([
            'survey_id' => $survey->id,
            'period_name' => 'Periode ' . date('Y'),
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.surveys.questions', $survey->id)->with('success', 'Survei berhasil dibuat. Sekarang Anda dapat menambahkan pertanyaan ke kuesioner ini.');
    }

    public function edit($id)
    {
        $survey = Survey::with(['activePeriod', 'categoryModel', 'ratingScale'])->findOrFail($id);
        $categories = SurveyCategory::where('is_active', true)->orderBy('order')->orderBy('name')->get();
        $ratingScales = RatingScale::orderByDesc('is_default')->orderBy('max_score')->orderBy('name')->get();
        return view('admin.surveys.edit', compact('survey', 'categories', 'ratingScales'));
    }

    public function update(Request $request, $id)
    {
        $survey = Survey::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'rating_scale_id' => 'required|exists:rating_scales,id',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'nullable|boolean',
        ], [
            'rating_scale_id.required' => 'Pilihan skala rating untuk kuesioner ini wajib ditentukan.',
        ]);

        $ratingScale = RatingScale::findOrFail($request->rating_scale_id);

        $categoryModel = SurveyCategory::firstOrCreate(
            ['name' => $request->category],
            [
                'slug' => Str::slug($request->category),
                'icon' => $request->icon ?? 'bi-clipboard-data',
                'is_active' => true,
            ]
        );

        $survey->update([
            'title' => $request->title,
            'category' => $request->category,
            'survey_category_id' => $categoryModel->id,
            'rating_scale_id' => $ratingScale->id,
            'rating_scale' => $ratingScale->max_score,
            'description' => $request->description,
            'icon' => $request->icon ?? $survey->icon,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $request->has('is_active'),
        ]);

        // Sync rating questions in survey to this uniform scale
        $survey->questions()->whereIn('question_type', ['single_rating', 'dual_rating'])->update([
            'rating_scale' => $ratingScale->max_score
        ]);

        // Update active period
        $period = $survey->activePeriod;
        if ($period) {
            $period->update([
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'is_active' => $request->has('is_active'),
            ]);
        } else {
            SurveyPeriod::create([
                'survey_id' => $survey->id,
                'period_name' => 'Periode ' . date('Y'),
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'is_active' => $request->has('is_active'),
            ]);
        }

        return redirect()->route('admin.surveys.index')->with('success', 'Data survei dan masa aktif berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $survey = Survey::findOrFail($id);
        $title = $survey->title;
        $survey->delete();

        return redirect()->route('admin.surveys.index')->with('success', "Survei '{$title}' dan seluruh datanya berhasil dihapus.");
    }

    public function toggleStatus($id)
    {
        $survey = Survey::findOrFail($id);
        $survey->is_active = !$survey->is_active;
        $survey->save();

        if ($survey->activePeriod) {
            $survey->activePeriod->is_active = $survey->is_active;
            $survey->activePeriod->save();
        }

        return back()->with('success', 'Status aktif survei berhasil diubah.');
    }

    public function archive($id)
    {
        $survey = Survey::findOrFail($id);
        $survey->is_archived = true;
        $survey->is_active = false;
        $survey->save();

        if ($survey->activePeriod) {
            $survey->activePeriod->is_active = false;
            $survey->activePeriod->save();
        }

        return redirect()->route('admin.surveys.index', ['tab' => 'archived'])
            ->with('success', "Survei '{$survey->title}' berhasil dipindahkan ke Arsip. Survei ditutup dan tidak lagi muncul di halaman beranda.");
    }

    public function unarchive($id)
    {
        $survey = Survey::findOrFail($id);
        $survey->is_archived = false;
        $survey->is_active = true;
        $survey->save();

        if ($survey->activePeriod) {
            $survey->activePeriod->is_active = true;
            $survey->activePeriod->save();
        }

        return redirect()->route('admin.surveys.index', ['tab' => 'active'])
            ->with('success', "Survei '{$survey->title}' berhasil dipulihkan dari arsip dan kembali aktif.");
    }

    /**
     * Question Builder for this Survey
     */
    public function questions($id)
    {
        $survey = Survey::with(['questions.dimensionModel', 'questions.template', 'activePeriod', 'categoryModel', 'ratingScale'])->findOrFail($id);
        
        $categories = SurveyCategory::with(['dimensions' => function ($q) {
            $q->orderBy('order')->orderBy('name');
        }])->orderBy('order')->orderBy('name')->get();

        // Dimensions for this survey's category (or all dimensions if none)
        if ($survey->survey_category_id) {
            $dimensions = SurveyDimension::where('survey_category_id', $survey->survey_category_id)
                ->orderBy('order')
                ->get();
        } else {
            $dimensions = SurveyDimension::orderBy('order')->get();
        }

        $allDimensions = SurveyDimension::with('category')->orderBy('order')->get();
        $bankTemplates = QuestionTemplate::with(['dimension.category'])->orderBy('order')->get();

        // Existing questions mapped by dimension
        $questions = $survey->questions->sortBy('order');

        return view('admin.surveys.questions', compact('survey', 'dimensions', 'allDimensions', 'categories', 'bankTemplates', 'questions'));
    }

    /**
     * Add single question directly
     */
    public function storeQuestion(Request $request, $surveyId)
    {
        $survey = Survey::findOrFail($surveyId);

        $request->validate([
            'dimension_id' => 'nullable|exists:survey_dimensions,id',
            'section' => 'required|string|max:10',
            'question_number' => [
                'nullable',
                'numeric',
                Rule::unique('survey_questions')->where(function ($query) use ($survey, $request) {
                    return $query->where('survey_id', $survey->id)->where('section', $request->section);
                }),
            ],
            'indicator_title' => 'nullable|string|max:255',
            'question_text' => 'required|string',
            'question_type' => 'required|string|in:dual_rating,single_rating,multiple_choice,essay',
            'rating_scale' => 'nullable|numeric',
            'require_reason_on_low_score' => 'nullable|boolean',
            'low_score_threshold' => 'nullable|numeric|min:1|max:10',
            'applies_to_employment_status' => 'nullable|string|max:100',
            'applies_to_positions' => 'nullable',
            'applies_to_gender' => 'nullable|string|max:50',
            'order' => 'nullable|numeric',
        ], [
            'question_number.unique' => 'Nomor soal :input pada Bagian ' . $request->section . ' sudah terisi. Silakan pilih nomor soal yang tersedia.',
        ]);

        $positions = null;
        if ($request->has('applies_to_positions')) {
            if (is_array($request->applies_to_positions)) {
                $filtered = array_filter($request->applies_to_positions, fn($p) => !empty($p) && $p !== '__all__');
                $positions = !empty($filtered) ? implode(', ', $filtered) : null;
            } elseif ($request->applies_to_positions !== '__all__' && !empty($request->applies_to_positions)) {
                $positions = $request->applies_to_positions;
            }
        }

        $dimension = $request->dimension_id ? SurveyDimension::find($request->dimension_id) : null;
        $maxNum = SurveyQuestion::where('survey_id', $survey->id)->where('section', $request->section)->max('question_number') ?? 0;
        $qNumber = $request->filled('question_number') ? (int)$request->question_number : ($maxNum + 1);
        $maxOrder = SurveyQuestion::where('survey_id', $survey->id)->max('order') ?? 0;

        $optionsJson = null;
        if ($request->question_type === 'multiple_choice') {
            if ($request->has('options') && is_array($request->options)) {
                $options = array_filter(array_map('trim', $request->options), fn($val) => $val !== '');
                $optionsJson = array_values($options);
            } elseif ($request->options_text) {
                $options = array_filter(array_map('trim', explode("\n", $request->options_text)), fn($val) => $val !== '');
                $optionsJson = array_values($options);
            }
        }

        SurveyQuestion::create([
            'survey_id' => $survey->id,
            'dimension_id' => $request->dimension_id,
            'dimension' => $dimension ? $dimension->name : 'General',
            'section' => $request->section,
            'question_number' => $qNumber,
            'indicator_title' => $request->indicator_title,
            'question_text' => $request->question_text,
            'question_type' => $request->question_type,
            'rating_scale' => $survey->rating_scale ?? 4,
            'require_reason_on_low_score' => $request->has('require_reason_on_low_score'),
            'low_score_threshold' => $request->low_score_threshold ?? 2,
            'options_json' => $optionsJson,
            'applies_to_employment_status' => $request->applies_to_employment_status,
            'applies_to_positions' => $positions,
            'applies_to_gender' => $request->applies_to_gender,
            'order' => $request->order ?? ($maxOrder + 1),
        ]);

        return back()->with('success', 'Pertanyaan baru berhasil ditambahkan ke survei.');
    }

    /**
     * Import multiple selected questions from Question Bank Template
     */
    public function importFromBank(Request $request, $surveyId)
    {
        $survey = Survey::findOrFail($surveyId);

        $request->validate([
            'template_ids' => 'required|array',
            'template_ids.*' => 'exists:question_templates,id',
            'section' => 'nullable|string|max:10',
        ]);

        $templates = QuestionTemplate::whereIn('id', $request->template_ids)->with('dimension')->get();
        $isAuto = empty($request->section) || $request->section === 'auto';
        $maxOrder = SurveyQuestion::where('survey_id', $survey->id)->max('order') ?? 0;

        // Keep track of max question number per section for sequential numbering
        $sectionNumbers = [];
        $sections = ['A', 'B', 'C', 'D'];
        foreach ($sections as $sec) {
            $sectionNumbers[$sec] = SurveyQuestion::where('survey_id', $survey->id)
                ->where('section', $sec)
                ->max('question_number') ?? 0;
        }

        $count = 0;
        foreach ($templates as $template) {
            $targetSection = $isAuto ? ($template->section ?: 'B') : $request->section;

            if (!isset($sectionNumbers[$targetSection])) {
                $sectionNumbers[$targetSection] = SurveyQuestion::where('survey_id', $survey->id)
                    ->where('section', $targetSection)
                    ->max('question_number') ?? 0;
            }

            $sectionNumbers[$targetSection]++;
            $maxOrder++;

            SurveyQuestion::create([
                'survey_id' => $survey->id,
                'dimension_id' => $template->dimension_id,
                'question_template_id' => $template->id,
                'dimension' => $template->dimension?->name ?? 'General',
                'section' => $targetSection,
                'question_number' => $sectionNumbers[$targetSection],
                'indicator_title' => $template->indicator_title,
                'question_text' => $template->question_text,
                'question_type' => $template->question_type,
                'rating_scale' => $survey->rating_scale ?? 4,
                'require_reason_on_low_score' => $template->require_reason_on_low_score,
                'low_score_threshold' => $template->low_score_threshold,
                'options_json' => $template->options_json,
                'applies_to_employment_status' => $template->applies_to_employment_status,
                'applies_to_positions' => $template->applies_to_positions,
                'applies_to_gender' => $template->applies_to_gender,
                'order' => $maxOrder,
            ]);
            $count++;
        }

        return back()->with('success', "Berhasil menambahkan {$count} pertanyaan dari Bank Template ke kuesioner.");
    }

    /**
     * Update question in survey
     */
    public function updateQuestion(Request $request, $surveyId, $questionId)
    {
        $survey = Survey::findOrFail($surveyId);
        $question = SurveyQuestion::where('survey_id', $survey->id)->findOrFail($questionId);

        $request->validate([
            'dimension_id' => 'nullable|exists:survey_dimensions,id',
            'section' => 'required|string|max:10',
            'question_number' => [
                'required',
                'numeric',
                Rule::unique('survey_questions')->where(function ($query) use ($survey, $request) {
                    return $query->where('survey_id', $survey->id)->where('section', $request->section);
                })->ignore($questionId),
            ],
            'indicator_title' => 'nullable|string|max:255',
            'question_text' => 'required|string',
            'question_type' => 'required|string|in:dual_rating,single_rating,multiple_choice,essay',
            'rating_scale' => 'nullable|numeric',
            'require_reason_on_low_score' => 'nullable|boolean',
            'low_score_threshold' => 'nullable|numeric|min:1|max:10',
            'applies_to_employment_status' => 'nullable|string|max:100',
            'applies_to_positions' => 'nullable',
            'applies_to_gender' => 'nullable|string|max:50',
            'order' => 'nullable|numeric',
        ], [
            'question_number.unique' => 'Nomor soal :input pada Bagian ' . $request->section . ' sudah terisi oleh butir pertanyaan lain.',
        ]);

        $positions = null;
        if ($request->has('applies_to_positions')) {
            if (is_array($request->applies_to_positions)) {
                $filtered = array_filter($request->applies_to_positions, fn($p) => !empty($p) && $p !== '__all__');
                $positions = !empty($filtered) ? implode(', ', $filtered) : null;
            } elseif ($request->applies_to_positions !== '__all__' && !empty($request->applies_to_positions)) {
                $positions = $request->applies_to_positions;
            }
        }

        $dimension = $request->dimension_id ? SurveyDimension::find($request->dimension_id) : null;

        $optionsJson = $question->options_json;
        if ($request->question_type === 'multiple_choice') {
            if ($request->has('options') && is_array($request->options)) {
                $options = array_filter(array_map('trim', $request->options), fn($val) => $val !== '');
                $optionsJson = array_values($options);
            } elseif ($request->options_text) {
                $options = array_filter(array_map('trim', explode("\n", $request->options_text)), fn($val) => $val !== '');
                $optionsJson = array_values($options);
            }
        }

        $question->update([
            'dimension_id' => $request->dimension_id,
            'dimension' => $dimension ? $dimension->name : $question->dimension,
            'section' => $request->section,
            'question_number' => $request->question_number,
            'indicator_title' => $request->indicator_title,
            'question_text' => $request->question_text,
            'question_type' => $request->question_type,
            'rating_scale' => $survey->rating_scale ?? 4,
            'require_reason_on_low_score' => $request->has('require_reason_on_low_score'),
            'low_score_threshold' => $request->low_score_threshold ?? 2,
            'options_json' => $optionsJson,
            'applies_to_employment_status' => $request->applies_to_employment_status,
            'applies_to_positions' => $positions,
            'applies_to_gender' => $request->applies_to_gender,
            'order' => $request->order ?? $question->order,
        ]);

        return back()->with('success', 'Pertanyaan berhasil diperbarui.');
    }

    /**
     * Remove question from survey
     */
    public function destroyQuestion($surveyId, $questionId)
    {
        $survey = Survey::findOrFail($surveyId);
        $question = SurveyQuestion::where('survey_id', $survey->id)->findOrFail($questionId);
        
        $question->answers()->delete();
        $question->delete();

        return back()->with('success', 'Pertanyaan berhasil dihapus dari survei.');
    }
}
