<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RatingScale;
use Illuminate\Http\Request;

class RatingScaleController extends Controller
{
    /**
     * Display a listing of rating scales.
     */
    public function index()
    {
        $ratingScales = RatingScale::withCount('surveys')
            ->orderByDesc('is_default')
            ->orderBy('max_score')
            ->orderBy('name')
            ->get();

        return view('admin.rating_scales.index', compact('ratingScales'));
    }

    /**
     * Store a newly created rating scale in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'min_score' => 'required|integer|min:1',
            'max_score' => 'required|integer|min:2|max:10|gte:min_score',
            'description' => 'nullable|string',
            'options' => 'required|array',
            'options.*' => 'required|string|max:255',
        ], [
            'name.required' => 'Nama skala rating wajib diisi.',
            'max_score.gte' => 'Skala maksimal harus lebih besar dari skala minimal.',
            'options.required' => 'Label deskripsi tiap skala wajib diisi.',
            'options.*.required' => 'Setiap nilai skala harus memiliki teks label deskripsi.',
        ]);

        $minScore = (int) $request->min_score;
        $maxScore = (int) $request->max_score;

        $optionsJson = [];
        for ($i = $minScore; $i <= $maxScore; $i++) {
            $label = $request->input("options.{$i}", "Nilai {$i}");
            $optionsJson[] = [
                'score' => $i,
                'label' => trim($label),
            ];
        }

        $isDefault = $request->boolean('is_default');
        if ($isDefault) {
            RatingScale::where('is_default', true)->update(['is_default' => false]);
        }

        RatingScale::create([
            'name' => $request->name,
            'min_score' => $minScore,
            'max_score' => $maxScore,
            'options_json' => $optionsJson,
            'description' => $request->description,
            'is_default' => $isDefault,
        ]);

        return redirect()->route('admin.rating-scales.index')
            ->with('success', 'Master Skala Rating berhasil ditambahkan.');
    }

    /**
     * Update the specified rating scale in storage.
     */
    public function update(Request $request, RatingScale $ratingScale)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'min_score' => 'required|integer|min:1',
            'max_score' => 'required|integer|min:2|max:10|gte:min_score',
            'description' => 'nullable|string',
            'options' => 'required|array',
            'options.*' => 'required|string|max:255',
        ], [
            'name.required' => 'Nama skala rating wajib diisi.',
            'max_score.gte' => 'Skala maksimal harus lebih besar dari skala minimal.',
            'options.required' => 'Label deskripsi tiap skala wajib diisi.',
            'options.*.required' => 'Setiap nilai skala harus memiliki teks label deskripsi.',
        ]);

        $minScore = (int) $request->min_score;
        $maxScore = (int) $request->max_score;

        $optionsJson = [];
        for ($i = $minScore; $i <= $maxScore; $i++) {
            $label = $request->input("options.{$i}", "Nilai {$i}");
            $optionsJson[] = [
                'score' => $i,
                'label' => trim($label),
            ];
        }

        $isDefault = $request->boolean('is_default');
        if ($isDefault) {
            RatingScale::where('id', '!=', $ratingScale->id)->where('is_default', true)->update(['is_default' => false]);
        }

        $ratingScale->update([
            'name' => $request->name,
            'min_score' => $minScore,
            'max_score' => $maxScore,
            'options_json' => $optionsJson,
            'description' => $request->description,
            'is_default' => $isDefault,
        ]);

        // Also sync survey rating_scale integer cache for surveys using this scale
        $ratingScale->surveys()->update(['rating_scale' => $maxScore]);

        return redirect()->route('admin.rating-scales.index')
            ->with('success', 'Master Skala Rating berhasil diperbarui.');
    }

    /**
     * Remove the specified rating scale from storage.
     */
    public function destroy(RatingScale $ratingScale)
    {
        if ($ratingScale->surveys()->exists()) {
            return redirect()->route('admin.rating-scales.index')
                ->with('error', 'Skala ini sedang digunakan oleh ' . $ratingScale->surveys()->count() . ' kuesioner aktif dan tidak dapat dihapus.');
        }

        $ratingScale->delete();

        return redirect()->route('admin.rating-scales.index')
            ->with('success', 'Master Skala Rating berhasil dihapus.');
    }
}
