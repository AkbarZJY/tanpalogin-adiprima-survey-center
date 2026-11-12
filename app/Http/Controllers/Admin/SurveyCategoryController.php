<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SurveyCategory;
use App\Models\Survey;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SurveyCategoryController extends Controller
{
    public function index()
    {
        $categories = SurveyCategory::withCount([
            'surveys',
            'surveys as active_surveys_count' => function ($q) {
                $q->where('is_archived', false)->where('is_active', true);
            },
            'surveys as archived_surveys_count' => function ($q) {
                $q->where('is_archived', true);
            }
        ])
        ->orderBy('order')
        ->orderBy('name')
        ->get();

        return view('admin.survey_categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:30',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $slug = Str::slug($request->name);
        $count = SurveyCategory::where('slug', 'like', "{$slug}%")->count();
        if ($count > 0) {
            $slug .= '-' . ($count + 1);
        }

        $category = SurveyCategory::create([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'icon' => $request->icon ?? 'bi-clipboard-data',
            'color' => $request->color ?? '#2563EB',
            'order' => $request->order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.survey-categories.index')
            ->with('success', 'Kategori survei "' . $category->name . '" berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $category = SurveyCategory::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:30',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $oldName = $category->name;
        $category->update([
            'name' => $request->name,
            'description' => $request->description,
            'icon' => $request->icon ?? $category->icon,
            'color' => $request->color ?? $category->color,
            'order' => $request->order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        // Keep existing survey string category in sync if name changed
        if ($oldName !== $request->name) {
            Survey::where('survey_category_id', $category->id)->update(['category' => $request->name]);
            Survey::where('category', $oldName)->update(['category' => $request->name]);
        }

        return redirect()->route('admin.survey-categories.index')
            ->with('success', 'Kategori survei "' . $category->name . '" berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $category = SurveyCategory::findOrFail($id);

        // Check if there are surveys still linked
        $surveysCount = Survey::where('survey_category_id', $category->id)
            ->orWhere('category', $category->name)
            ->count();

        if ($surveysCount > 0) {
            // Unassign or keep as legacy string
            Survey::where('survey_category_id', $category->id)->update(['survey_category_id' => null]);
        }

        $categoryName = $category->name;
        $category->delete();

        return redirect()->route('admin.survey-categories.index')
            ->with('success', 'Kategori survei "' . $categoryName . '" berhasil dihapus.');
    }

    public function toggleStatus($id)
    {
        $category = SurveyCategory::findOrFail($id);
        $category->is_active = !$category->is_active;
        $category->save();

        $statusText = $category->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Status kategori '{$category->name}' berhasil {$statusText}.");
    }
}
