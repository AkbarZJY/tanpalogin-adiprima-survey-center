<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SurveyDimension;
use App\Models\SurveyCategory;
use Illuminate\Http\Request;

use Illuminate\Validation\Rule;

class DimensionController extends Controller
{
    public function index(Request $request)
    {
        $categoryFilter = $request->get('category'); // category id or slug

        $categories = SurveyCategory::withCount('dimensions')
            ->orderBy('order')
            ->orderBy('name')
            ->get();

        $query = SurveyDimension::with(['category'])
            ->withCount(['questions', 'questionTemplates'])
            ->orderBy('order')
            ->orderBy('id');

        if ($categoryFilter && $categoryFilter !== 'all') {
            if (is_numeric($categoryFilter)) {
                $query->where('survey_category_id', $categoryFilter);
            } else {
                $query->whereHas('category', function ($q) use ($categoryFilter) {
                    $q->where('slug', $categoryFilter);
                });
            }
        }

        $dimensions = $query->get();
        $allDimensionsData = SurveyDimension::select('id', 'survey_category_id', 'code', 'order', 'name')->get();

        return view('admin.dimensions.index', compact('dimensions', 'categories', 'categoryFilter', 'allDimensionsData'));
    }

    public function store(Request $request)
    {
        $code = !empty($request->code) ? strtoupper(trim($request->code)) : null;
        $request->merge(['code' => $code]);

        $request->validate([
            'survey_category_id' => 'required|exists:survey_categories,id',
            'name' => 'required|string|max:255',
            'code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('survey_dimensions')->where(function ($query) use ($request) {
                    return $query->where('survey_category_id', $request->survey_category_id);
                }),
            ],
            'description' => 'nullable|string',
            'color' => 'required|string|max:30',
            'order' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('survey_dimensions')->where(function ($query) use ($request) {
                    return $query->where('survey_category_id', $request->survey_category_id);
                }),
            ],
        ], [
            'code.required' => 'Kode singkat dimensi wajib diisi atau dipilih.',
            'code.unique' => 'Kode singkat ":input" sudah digunakan oleh dimensi lain dalam kategori survei ini.',
            'order.required' => 'Urutan dimensi wajib ditentukan.',
            'order.unique' => 'Urutan nomor :input sudah digunakan oleh dimensi lain dalam kategori survei ini. Silakan pilih nomor urut yang berbeda.',
        ]);

        SurveyDimension::create([
            'survey_category_id' => $request->survey_category_id,
            'name' => $request->name,
            'code' => $code,
            'description' => $request->description,
            'color' => $request->color ?? '#2563EB',
            'order' => $request->order ?? 1,
        ]);

        return redirect()->route('admin.dimensions.index', ['category' => $request->survey_category_id])
            ->with('success', 'Dimensi pertanyaan berhasil ditambahkan ke kategori yang dipilih.');
    }

    public function update(Request $request, $id)
    {
        $dimension = SurveyDimension::findOrFail($id);
        $code = !empty($request->code) ? strtoupper(trim($request->code)) : null;
        $request->merge(['code' => $code]);

        $request->validate([
            'survey_category_id' => 'required|exists:survey_categories,id',
            'name' => 'required|string|max:255',
            'code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('survey_dimensions')->where(function ($query) use ($request) {
                    return $query->where('survey_category_id', $request->survey_category_id);
                })->ignore($id),
            ],
            'description' => 'nullable|string',
            'color' => 'required|string|max:30',
            'order' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('survey_dimensions')->where(function ($query) use ($request) {
                    return $query->where('survey_category_id', $request->survey_category_id);
                })->ignore($id),
            ],
        ], [
            'code.required' => 'Kode singkat dimensi wajib diisi atau dipilih.',
            'code.unique' => 'Kode singkat ":input" sudah digunakan oleh dimensi lain dalam kategori survei ini.',
            'order.required' => 'Urutan dimensi wajib ditentukan.',
            'order.unique' => 'Urutan nomor :input sudah digunakan oleh dimensi lain dalam kategori survei ini. Silakan pilih nomor urut yang berbeda.',
        ]);

        $dimension->update([
            'survey_category_id' => $request->survey_category_id,
            'name' => $request->name,
            'code' => $code,
            'description' => $request->description,
            'color' => $request->color,
            'order' => $request->order ?? 1,
        ]);

        return redirect()->route('admin.dimensions.index', ['category' => $request->survey_category_id])
            ->with('success', 'Dimensi pertanyaan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $dimension = SurveyDimension::findOrFail($id);
        $catId = $dimension->survey_category_id;
        $dimension->delete();

        return redirect()->route('admin.dimensions.index', ['category' => $catId])
            ->with('success', 'Dimensi berhasil dihapus.');
    }
}
