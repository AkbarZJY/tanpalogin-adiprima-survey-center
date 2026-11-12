<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuestionTemplate;
use App\Models\SurveyDimension;
use App\Models\SurveyCategory;
use Illuminate\Http\Request;

class QuestionBankController extends Controller
{
    public function index(Request $request)
    {
        $categoryFilter = $request->get('category_id');
        $dimensionFilter = $request->get('dimension_id');
        $typeFilter = $request->get('question_type');
        $search = $request->get('search');

        $query = QuestionTemplate::with(['dimension.category'])->orderBy('order')->orderBy('id');

        if ($categoryFilter) {
            $query->whereHas('dimension', function ($q) use ($categoryFilter) {
                $q->where('survey_category_id', $categoryFilter);
            });
        }

        if ($dimensionFilter) {
            $query->where('dimension_id', $dimensionFilter);
        }

        if ($typeFilter) {
            $query->where('question_type', $typeFilter);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('question_text', 'like', "%{$search}%")
                  ->orWhere('indicator_title', 'like', "%{$search}%");
            });
        }

        $templates = $query->paginate(15)->withQueryString();
        $categories = SurveyCategory::with(['dimensions' => function ($q) {
            $q->orderBy('order')->orderBy('name');
        }])->orderBy('order')->orderBy('name')->get();
        
        $dimensions = SurveyDimension::with('category')->orderBy('order')->orderBy('name')->get();

        return view('admin.question_bank.index', compact('templates', 'categories', 'dimensions', 'categoryFilter', 'dimensionFilter', 'typeFilter', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'dimension_id' => 'nullable|exists:survey_dimensions,id',
            'section' => 'nullable|string|max:10',
            'indicator_title' => 'nullable|string|max:255',
            'question_text' => 'required|string',
            'question_type' => 'required|string|in:dual_rating,single_rating,multiple_choice,essay',
            'rating_scale' => 'nullable|numeric|in:4,5,10',
            'require_reason_on_low_score' => 'nullable',
            'low_score_threshold' => 'nullable|numeric|min:1|max:10',
            'applies_to_employment_status' => 'nullable|string|max:100',
            'applies_to_positions' => 'nullable',
            'applies_to_gender' => 'nullable|string|max:50',
            'order' => 'nullable|numeric',
        ]);

        $optionsJson = null;
        if ($request->question_type === 'multiple_choice') {
            if ($request->has('options') && is_array($request->options)) {
                $options = array_filter(array_map('trim', $request->options), fn($val) => $val !== '');
                $optionsJson = !empty($options) ? array_values($options) : null;
            } elseif ($request->options_text) {
                $options = array_filter(array_map('trim', explode("\n", $request->options_text)), fn($val) => $val !== '');
                $optionsJson = !empty($options) ? array_values($options) : null;
            }
        }

        $positions = null;
        if ($request->has('applies_to_positions')) {
            if (is_array($request->applies_to_positions)) {
                $filtered = array_filter($request->applies_to_positions, fn($p) => !empty($p) && $p !== '__all__');
                $positions = !empty($filtered) ? implode(', ', $filtered) : null;
            } elseif ($request->applies_to_positions !== '__all__' && !empty($request->applies_to_positions)) {
                $positions = $request->applies_to_positions;
            }
        }

        $ratingScale = in_array($request->question_type, ['dual_rating', 'single_rating']) 
            ? (int)($request->rating_scale ?? 4) 
            : 4;

        QuestionTemplate::create([
            'dimension_id' => $request->dimension_id,
            'section' => $request->section ?: 'B',
            'indicator_title' => $request->indicator_title,
            'question_text' => $request->question_text,
            'question_type' => $request->question_type,
            'rating_scale' => $ratingScale,
            'require_reason_on_low_score' => $request->has('require_reason_on_low_score') || $request->boolean('require_reason_on_low_score'),
            'low_score_threshold' => $request->low_score_threshold ?? 2,
            'options_json' => $optionsJson,
            'applies_to_employment_status' => $request->applies_to_employment_status,
            'applies_to_positions' => $positions,
            'applies_to_gender' => $request->applies_to_gender,
            'order' => $request->order ?? 0,
        ]);

        return redirect()->route('admin.question-bank.index')->with('success', 'Template pertanyaan berhasil ditambahkan ke Bank Soal.');
    }

    public function update(Request $request, $id)
    {
        $template = QuestionTemplate::findOrFail($id);

        $request->validate([
            'dimension_id' => 'nullable|exists:survey_dimensions,id',
            'section' => 'nullable|string|max:10',
            'indicator_title' => 'nullable|string|max:255',
            'question_text' => 'required|string',
            'question_type' => 'required|string|in:dual_rating,single_rating,multiple_choice,essay',
            'rating_scale' => 'nullable|numeric|in:4,5,10',
            'require_reason_on_low_score' => 'nullable',
            'low_score_threshold' => 'nullable|numeric|min:1|max:10',
            'applies_to_employment_status' => 'nullable|string|max:100',
            'applies_to_positions' => 'nullable',
            'applies_to_gender' => 'nullable|string|max:50',
            'order' => 'nullable|numeric',
        ]);

        $optionsJson = $template->options_json;
        if ($request->question_type === 'multiple_choice') {
            if ($request->has('options') && is_array($request->options)) {
                $options = array_filter(array_map('trim', $request->options), fn($val) => $val !== '');
                $optionsJson = !empty($options) ? array_values($options) : null;
            } elseif ($request->options_text) {
                $options = array_filter(array_map('trim', explode("\n", $request->options_text)), fn($val) => $val !== '');
                $optionsJson = !empty($options) ? array_values($options) : null;
            }
        } else {
            $optionsJson = null;
        }

        $positions = null;
        if ($request->has('applies_to_positions')) {
            if (is_array($request->applies_to_positions)) {
                $filtered = array_filter($request->applies_to_positions, fn($p) => !empty($p) && $p !== '__all__');
                $positions = !empty($filtered) ? implode(', ', $filtered) : null;
            } elseif ($request->applies_to_positions !== '__all__' && !empty($request->applies_to_positions)) {
                $positions = $request->applies_to_positions;
            }
        }

        $ratingScale = in_array($request->question_type, ['dual_rating', 'single_rating']) 
            ? (int)($request->rating_scale ?? $template->rating_scale ?? 4) 
            : 4;

        $template->update([
            'dimension_id' => $request->dimension_id,
            'section' => $request->section ?: ($template->section ?: 'B'),
            'indicator_title' => $request->indicator_title,
            'question_text' => $request->question_text,
            'question_type' => $request->question_type,
            'rating_scale' => $ratingScale,
            'require_reason_on_low_score' => $request->has('require_reason_on_low_score') || $request->boolean('require_reason_on_low_score'),
            'low_score_threshold' => $request->low_score_threshold ?? 2,
            'options_json' => $optionsJson,
            'applies_to_employment_status' => $request->applies_to_employment_status,
            'applies_to_positions' => $positions,
            'applies_to_gender' => $request->applies_to_gender,
            'order' => $request->order ?? 0,
        ]);

        return redirect()->route('admin.question-bank.index')->with('success', 'Template pertanyaan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $template = QuestionTemplate::findOrFail($id);
        $template->delete();

        return redirect()->route('admin.question-bank.index')->with('success', 'Template pertanyaan berhasil dihapus dari Bank Soal.');
    }
}
