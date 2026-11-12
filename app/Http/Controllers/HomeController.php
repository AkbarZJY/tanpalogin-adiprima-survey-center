<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $surveys = Survey::where('is_archived', false)
            ->where('is_active', true)
            ->with(['activePeriod', 'responses', 'categoryModel'])
            ->orderBy('id', 'desc')
            ->get()
            ->filter(function ($survey) {
                return $survey->isWithinActiveDate();
            });

        return view('home.index', compact('surveys'));
    }
}
