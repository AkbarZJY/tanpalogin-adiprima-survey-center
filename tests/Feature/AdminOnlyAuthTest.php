<?php

namespace Tests\Feature;

use App\Models\Survey;
use App\Models\SurveyQuestion;
use App\Models\SurveyResponse;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminOnlyAuthTest extends TestCase
{
    use RefreshDatabase;

    private function createActiveSurvey(): Survey
    {
        $survey = Survey::create([
            'title' => 'Employee Engagement Survey',
            'slug' => 'engagement-survey',
            'category' => 'Employee Engagement Survey',
            'description' => 'Survei keterikatan karyawan',
            'is_active' => true,
            'is_archived' => false,
            'rating_scale' => 4,
        ]);

        SurveyQuestion::create([
            'survey_id' => $survey->id,
            'section' => 'B',
            'question_number' => 1,
            'dimension' => 'Lingkungan Kerja',
            'indicator_title' => 'Kenyamanan',
            'question_text' => 'Bagaimana kenyamanan lingkungan kerja Anda?',
            'question_type' => 'dual_rating',
            'order' => 1,
        ]);

        return $survey;
    }

    public function test_guest_can_access_home_without_login(): void
    {
        $survey = $this->createActiveSurvey();

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee($survey->title);
        $response->assertSee('Admin Login');
    }

    public function test_guest_can_view_and_submit_survey_without_login(): void
    {
        $survey = $this->createActiveSurvey();
        $question = $survey->questions()->first();

        $formResponse = $this->get(route('survey.form', $survey->slug));
        $formResponse->assertStatus(200);
        $formResponse->assertSee($survey->title);

        $submitResponse = $this->post(route('survey.store', $survey->slug), [
            'name' => 'Budi Santoso',
            'gender' => 'Laki-laki',
            'age' => 30,
            'education' => 'S1',
            'employment_status' => 'Tetap',
            'tenure' => '1 - 5 tahun',
            'department' => 'IT',
            'position' => 'Staf',
            "expectation_{$question->id}" => 4,
            "reality_{$question->id}" => 3,
        ]);

        $submitResponse->assertRedirect(route('survey.success', ['slug' => $survey->slug]));

        $this->assertDatabaseHas('survey_responses', [
            'survey_id' => $survey->id,
            'user_id' => null,
            'name' => 'Budi Santoso',
            'department' => 'IT',
        ]);

        $savedResponse = SurveyResponse::first();
        $this->assertDatabaseHas('survey_answers', [
            'survey_response_id' => $savedResponse->id,
            'question_id' => $question->id,
            'expectation_score' => 4,
            'reality_score' => 3,
        ]);

        $this->get(route('survey.success', $survey->slug))->assertStatus(200);
    }

    public function test_guest_cannot_access_admin_routes(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
        $this->get(route('admin.surveys.index'))->assertRedirect(route('login'));
    }

    public function test_non_admin_user_cannot_login_or_access_admin_routes(): void
    {
        $employee = User::create([
            'username' => 'employee1',
            'name' => 'Karyawan Biasa',
            'password' => Hash::make('password'),
            'role' => 'employee',
        ]);

        $loginAttempt = $this->post('/login', [
            'login' => 'employee1',
            'password' => 'password',
        ]);

        $loginAttempt->assertSessionHasErrors('login');
        $this->assertGuest();

        // Even if authenticated as employee directly, AdminMiddleware blocks /admin/*
        $this->actingAs($employee)
            ->get(route('admin.dashboard'))
            ->assertRedirect(route('login'));
    }

    public function test_admin_can_login_and_access_admin_dashboard(): void
    {
        $admin = User::create([
            'username' => 'admin',
            'name' => 'Admin HRGA',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $loginResponse = $this->post('/login', [
            'login' => 'admin',
            'password' => 'password',
        ]);

        $loginResponse->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);

        $this->get(route('admin.dashboard'))->assertStatus(200);
    }

    public function test_outsourcing_requires_valid_outsourcing_company(): void
    {
        $survey = $this->createActiveSurvey();
        $question = $survey->questions()->first();

        $basePayload = [
            'name' => 'Siti Aminah',
            'gender' => 'Perempuan',
            'age' => 26,
            'education' => 'SMA/ SMK Sederjat',
            'employment_status' => 'Outsourcing',
            'tenure' => '1 - 5 tahun',
            'department' => 'PM1',
            'position' => 'Operator',
            "expectation_{$question->id}" => 4,
            "reality_{$question->id}" => 3,
        ];

        // Missing outsourcing_company fails validation
        $this->post(route('survey.store', $survey->slug), $basePayload)
            ->assertSessionHasErrors('outsourcing_company');

        // Invalid outsourcing_company fails validation
        $this->post(route('survey.store', $survey->slug), array_merge($basePayload, [
            'outsourcing_company' => 'XYZ',
        ]))->assertSessionHasErrors('outsourcing_company');

        // Valid outsourcing_company (KBM, BAP, or GCI) succeeds
        foreach (['KBM', 'BAP', 'GCI'] as $index => $company) {
            $name = "Siti Aminah {$index}";
            $this->post(route('survey.store', $survey->slug), array_merge($basePayload, [
                'name' => $name,
                'outsourcing_company' => $company,
            ]))->assertRedirect(route('survey.success', ['slug' => $survey->slug]));

            $this->assertDatabaseHas('survey_responses', [
                'survey_id' => $survey->id,
                'name' => $name,
                'employment_status' => 'Outsourcing',
                'outsourcing_company' => $company,
            ]);
        }

        // Non-outsourcing clears outsourcing_company to null
        $this->post(route('survey.store', $survey->slug), array_merge($basePayload, [
            'name' => 'Pegawai Tetap',
            'employment_status' => 'Tetap',
            'outsourcing_company' => 'KBM',
        ]))->assertRedirect(route('survey.success', ['slug' => $survey->slug]));

        $this->assertDatabaseHas('survey_responses', [
            'survey_id' => $survey->id,
            'name' => 'Pegawai Tetap',
            'employment_status' => 'Tetap',
            'outsourcing_company' => null,
        ]);
    }
}

