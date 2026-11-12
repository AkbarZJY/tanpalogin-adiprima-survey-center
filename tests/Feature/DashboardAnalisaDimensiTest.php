<?php

namespace Tests\Feature;

use App\Models\Survey;
use App\Models\SurveyAnswer;
use App\Models\SurveyPeriod;
use App\Models\SurveyQuestion;
use App\Models\SurveyResponse;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DashboardAnalisaDimensiTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_analisa_tab_computes_dimension_indicator_details_and_ipa_quadrants(): void
    {
        $admin = User::create([
            'username' => 'admin',
            'name' => 'Admin HRGA',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $survey = Survey::create([
            'title' => 'Employee Engagement Survey',
            'slug' => 'engagement-survey',
            'category' => 'Employee Engagement Survey',
            'is_active' => true,
            'is_archived' => false,
            'rating_scale' => 4,
        ]);

        $period = SurveyPeriod::create([
            'survey_id' => $survey->id,
            'period_name' => 'Periode 2026',
            'start_date' => now()->subDay(),
            'end_date' => now()->addMonth(),
            'is_active' => true,
        ]);

        // 4 indicators inside dimension "Komunikasi" so each falls in a distinct IPA quadrant
        // Mean Exp = (4 + 4 + 2 + 2) / 4 = 3.00
        // Mean Real = (2 + 4 + 2 + 4) / 4 = 3.00
        $q1 = SurveyQuestion::create([
            'survey_id' => $survey->id,
            'section' => 'B',
            'question_number' => 1,
            'dimension' => 'Komunikasi',
            'indicator_title' => 'Komunikasi Atasan',
            'question_text' => 'Keterbukaan komunikasi atasan',
            'question_type' => 'dual_rating',
            'order' => 1,
        ]);

        $q2 = SurveyQuestion::create([
            'survey_id' => $survey->id,
            'section' => 'B',
            'question_number' => 2,
            'dimension' => 'Komunikasi',
            'indicator_title' => 'Informasi Kebijakan',
            'question_text' => 'Kejelasan informasi kebijakan perusahaan',
            'question_type' => 'dual_rating',
            'order' => 2,
        ]);

        $q3 = SurveyQuestion::create([
            'survey_id' => $survey->id,
            'section' => 'B',
            'question_number' => 3,
            'dimension' => 'Komunikasi',
            'indicator_title' => 'Media Internal',
            'question_text' => 'Efektivitas papan pengumuman',
            'question_type' => 'dual_rating',
            'order' => 3,
        ]);

        $q4 = SurveyQuestion::create([
            'survey_id' => $survey->id,
            'section' => 'B',
            'question_number' => 4,
            'dimension' => 'Komunikasi',
            'indicator_title' => 'Rapat Rutin',
            'question_text' => 'Frekuensi briefing harian',
            'question_type' => 'dual_rating',
            'order' => 4,
        ]);

        $resp = SurveyResponse::create([
            'survey_id' => $survey->id,
            'survey_period_id' => $period->id,
            'name' => 'Responden Uji',
            'department' => 'IT',
            'position' => 'Staf',
            'submitted_at' => now(),
        ]);

        // Q1: Exp=4, Real=2 -> need_improvement
        SurveyAnswer::create([
            'survey_response_id' => $resp->id,
            'question_id' => $q1->id,
            'expectation_score' => 4,
            'reality_score' => 2,
        ]);
        // Q2: Exp=4, Real=4 -> maintain_quality
        SurveyAnswer::create([
            'survey_response_id' => $resp->id,
            'question_id' => $q2->id,
            'expectation_score' => 4,
            'reality_score' => 4,
        ]);
        // Q3: Exp=2, Real=2 -> low_priority
        SurveyAnswer::create([
            'survey_response_id' => $resp->id,
            'question_id' => $q3->id,
            'expectation_score' => 2,
            'reality_score' => 2,
        ]);
        // Q4: Exp=2, Real=4 -> possible_overkill
        SurveyAnswer::create([
            'survey_response_id' => $resp->id,
            'question_id' => $q4->id,
            'expectation_score' => 2,
            'reality_score' => 4,
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.dashboard', ['survey_id' => $survey->id, 'tab' => 'analisa']));

        $response->assertStatus(200);
        $response->assertSee('Tabel Rata-Rata per Dimensi');
        $response->assertSee('Matriks Kuadran IPA');
        $response->assertSee('dimensionDetailView');
        $response->assertSee('Komunikasi');

        $dimensionDetails = $response->viewData('dimensionDetails');
        $this->assertArrayHasKey('Komunikasi', $dimensionDetails);

        $indicators = collect($dimensionDetails['Komunikasi']['indicators'])->keyBy('question_number');
        $this->assertEquals('need_improvement', $indicators[1]['quadrant']);
        $this->assertEquals('maintain_quality', $indicators[2]['quadrant']);
        $this->assertEquals('low_priority', $indicators[3]['quadrant']);
        $this->assertEquals('possible_overkill', $indicators[4]['quadrant']);
    }
}

