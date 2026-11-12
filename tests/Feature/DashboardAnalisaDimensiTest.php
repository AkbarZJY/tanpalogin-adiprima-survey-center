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

    public function test_admin_monitoring_tab_lists_all_form_departments_with_counts(): void
    {
        $admin = User::create([
            'username' => 'admin_mon',
            'name' => 'Admin Monitoring',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $survey = Survey::create([
            'title' => 'Employee Engagement Survey',
            'slug' => 'engagement-survey-mon',
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

        SurveyResponse::create([
            'survey_id' => $survey->id,
            'survey_period_id' => $period->id,
            'name' => 'Resp HRGA 1',
            'department' => 'HRGA',
            'employment_status' => 'Tetap',
            'position' => 'Staf',
            'submitted_at' => now(),
        ]);

        SurveyResponse::create([
            'survey_id' => $survey->id,
            'survey_period_id' => $period->id,
            'name' => 'Resp HRGA 2',
            'department' => 'HRGA',
            'employment_status' => 'Kontrak',
            'position' => 'Operator',
            'submitted_at' => now(),
        ]);

        SurveyResponse::create([
            'survey_id' => $survey->id,
            'survey_period_id' => $period->id,
            'name' => 'Resp PM1',
            'department' => 'PM1',
            'employment_status' => 'Tetap',
            'position' => 'Karu',
            'submitted_at' => now(),
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.dashboard', ['survey_id' => $survey->id, 'tab' => 'rekap_perolehan']));

        $response->assertStatus(200);

        $expectedDepartments = [
            'HRGA', 'PPIC', 'QC', 'UTILITY', 'R&D',
            'PM1', 'PM2', 'PM3', 'TEKNIK', 'PP',
            'MRK', 'PCH', 'IT', 'FA', 'IA',
        ];

        $rekapData = collect($response->viewData('rekapData'))->keyBy('group');
        $this->assertEquals($expectedDepartments, $rekapData->keys()->all());
        $this->assertEquals(2, $rekapData['HRGA']['filled']);
        $this->assertEquals(1, $rekapData['PM1']['filled']);
        $this->assertEquals(0, $rekapData['IT']['filled']);
        $this->assertEquals(3, $response->viewData('totalFilled'));
    }

    public function test_admin_analisa_tab_supports_multi_checkbox_filters_for_department_employment_and_position(): void
    {
        $admin = User::create([
            'username' => 'admin_filter',
            'name' => 'Admin Filter',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $survey = Survey::create([
            'title' => 'Employee Engagement Survey',
            'slug' => 'engagement-survey-filter',
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

        $q1 = SurveyQuestion::create([
            'survey_id' => $survey->id,
            'section' => 'B',
            'question_number' => 1,
            'dimension' => 'Lingkungan Kerja',
            'indicator_title' => 'Kenyamanan Ruang',
            'question_text' => 'Kondisi ruang kerja',
            'question_type' => 'dual_rating',
            'order' => 1,
        ]);

        $r1 = SurveyResponse::create([
            'survey_id' => $survey->id,
            'survey_period_id' => $period->id,
            'name' => 'R1',
            'department' => 'IT',
            'employment_status' => 'Tetap',
            'position' => 'Staf',
            'submitted_at' => now(),
        ]);
        SurveyAnswer::create([
            'survey_response_id' => $r1->id,
            'question_id' => $q1->id,
            'expectation_score' => 4,
            'reality_score' => 4,
        ]);

        $r2 = SurveyResponse::create([
            'survey_id' => $survey->id,
            'survey_period_id' => $period->id,
            'name' => 'R2',
            'department' => 'HRGA',
            'employment_status' => 'Kontrak',
            'position' => 'Karu',
            'submitted_at' => now(),
        ]);
        SurveyAnswer::create([
            'survey_response_id' => $r2->id,
            'question_id' => $q1->id,
            'expectation_score' => 4,
            'reality_score' => 2,
        ]);

        $r3 = SurveyResponse::create([
            'survey_id' => $survey->id,
            'survey_period_id' => $period->id,
            'name' => 'R3',
            'department' => 'PM1',
            'employment_status' => 'Outsourcing',
            'position' => 'Operator',
            'submitted_at' => now(),
        ]);
        SurveyAnswer::create([
            'survey_response_id' => $r3->id,
            'question_id' => $q1->id,
            'expectation_score' => 2,
            'reality_score' => 1,
        ]);

        // 1. Single department filter (IT only -> Exp 4.00, Real 4.00)
        $resSingle = $this->actingAs($admin)->get(route('admin.dashboard', [
            'survey_id' => $survey->id,
            'tab' => 'analisa',
            'analisa_departments' => ['IT'],
        ]));
        $resSingle->assertStatus(200);
        $resSingle->assertSee('Filter Analisa Dimensi');
        $this->assertEquals(1, $resSingle->viewData('analisaRespondentCount'));
        $this->assertEquals(4.0, $resSingle->viewData('dimensionScores')['Lingkungan Kerja']);
        $this->assertEquals(4.0, $resSingle->viewData('dimensionExpectationScores')['Lingkungan Kerja']);

        // 2. Multiple selections across all 3 filters (IT + HRGA, Tetap + Kontrak, Staf + Karu -> R1 & R2 => Real avg 3.00, Exp avg 4.00)
        $resMulti = $this->actingAs($admin)->get(route('admin.dashboard', [
            'survey_id' => $survey->id,
            'tab' => 'analisa',
            'analisa_departments' => ['IT', 'HRGA'],
            'analisa_employment_statuses' => ['Tetap', 'Kontrak'],
            'analisa_positions' => ['Staf', 'Karu'],
        ]));
        $resMulti->assertStatus(200);
        $this->assertEquals(2, $resMulti->viewData('analisaRespondentCount'));
        $this->assertEquals(3.0, $resMulti->viewData('dimensionScores')['Lingkungan Kerja']);
        $this->assertEquals(4.0, $resMulti->viewData('dimensionExpectationScores')['Lingkungan Kerja']);
        $multiDetails = $resMulti->viewData('dimensionDetails');
        $this->assertEquals(3.0, $multiDetails['Lingkungan Kerja']['indicators'][0]['avg_real']);
        $this->assertEquals(2, $multiDetails['Lingkungan Kerja']['indicators'][0]['respondent_count']);

        // 3. Combination yielding 0 respondents
        $resEmpty = $this->actingAs($admin)->get(route('admin.dashboard', [
            'survey_id' => $survey->id,
            'tab' => 'analisa',
            'analisa_departments' => ['IT'],
            'analisa_positions' => ['Operator'],
        ]));
        $resEmpty->assertStatus(200);
        $resEmpty->assertSee('Tidak ada responden yang sesuai dengan kombinasi filter yang dipilih.');
        $this->assertEquals(0, $resEmpty->viewData('analisaRespondentCount'));
        $this->assertEquals(0, $resEmpty->viewData('dimensionScores')['Lingkungan Kerja']);
    }
}

