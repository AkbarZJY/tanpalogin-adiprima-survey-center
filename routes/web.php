<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SurveyFormController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\SurveyManagementController;
use App\Http\Controllers\Admin\SurveyCategoryController;
use App\Http\Controllers\Admin\DimensionController;
use App\Http\Controllers\Admin\QuestionBankController;
use App\Http\Controllers\Admin\RatingScaleController;

// ────────────────────────────────────────────────────────────
// Auth Routes (Login/Logout — hanya untuk Admin)
// ────────────────────────────────────────────────────────────
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ────────────────────────────────────────────────────────────
// Public Routes (User biasa TIDAK perlu login)
// ────────────────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index']);

// Survey Form Routes (Participant side — tanpa login)
Route::get('/survey/{slug}/form', [SurveyFormController::class, 'showForm'])->name('survey.form');
Route::post('/survey/{slug}/form', [SurveyFormController::class, 'store'])->name('survey.store');
Route::get('/survey/{slug}/success', [SurveyFormController::class, 'success'])->name('survey.success');

// ────────────────────────────────────────────────────────────
// Admin Routes (Harus login + role admin)
// ────────────────────────────────────────────────────────────
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Analytics Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/export', [DashboardController::class, 'exportXlsx'])->name('dashboard.export');

    // Survey Categories CRUD
    Route::get('/survey-categories', [SurveyCategoryController::class, 'index'])->name('survey-categories.index');
    Route::post('/survey-categories', [SurveyCategoryController::class, 'store'])->name('survey-categories.store');
    Route::put('/survey-categories/{id}', [SurveyCategoryController::class, 'update'])->name('survey-categories.update');
    Route::delete('/survey-categories/{id}', [SurveyCategoryController::class, 'destroy'])->name('survey-categories.destroy');
    Route::post('/survey-categories/{id}/toggle', [SurveyCategoryController::class, 'toggleStatus'])->name('survey-categories.toggle');

    // Survey Management, Active & Archive
    Route::get('/surveys', [SurveyManagementController::class, 'index'])->name('surveys.index');
    Route::get('/surveys/create', [SurveyManagementController::class, 'create'])->name('surveys.create');
    Route::post('/surveys', [SurveyManagementController::class, 'store'])->name('surveys.store');
    Route::get('/surveys/{id}/edit', [SurveyManagementController::class, 'edit'])->name('surveys.edit');
    Route::put('/surveys/{id}', [SurveyManagementController::class, 'update'])->name('surveys.update');
    Route::delete('/surveys/{id}', [SurveyManagementController::class, 'destroy'])->name('surveys.destroy');
    Route::post('/surveys/{id}/toggle', [SurveyManagementController::class, 'toggleStatus'])->name('surveys.toggle');
    Route::post('/surveys/{id}/archive', [SurveyManagementController::class, 'archive'])->name('surveys.archive');
    Route::post('/surveys/{id}/unarchive', [SurveyManagementController::class, 'unarchive'])->name('surveys.unarchive');

    // Survey Question Builder (add/edit/import/delete questions per survey)
    Route::get('/surveys/{id}/questions', [SurveyManagementController::class, 'questions'])->name('surveys.questions');
    Route::post('/surveys/{id}/questions', [SurveyManagementController::class, 'storeQuestion'])->name('surveys.questions.store');
    Route::post('/surveys/{id}/questions/import', [SurveyManagementController::class, 'importFromBank'])->name('surveys.questions.import');
    Route::put('/surveys/{id}/questions/{questionId}', [SurveyManagementController::class, 'updateQuestion'])->name('surveys.questions.update');
    Route::delete('/surveys/{id}/questions/{questionId}', [SurveyManagementController::class, 'destroyQuestion'])->name('surveys.questions.destroy');

    // Dimensions / Question Categories Management
    Route::get('/dimensions', [DimensionController::class, 'index'])->name('dimensions.index');
    Route::post('/dimensions', [DimensionController::class, 'store'])->name('dimensions.store');
    Route::put('/dimensions/{id}', [DimensionController::class, 'update'])->name('dimensions.update');
    Route::delete('/dimensions/{id}', [DimensionController::class, 'destroy'])->name('dimensions.destroy');

    // Question Bank / Templates Management
    Route::get('/question-bank', [QuestionBankController::class, 'index'])->name('question-bank.index');
    Route::post('/question-bank', [QuestionBankController::class, 'store'])->name('question-bank.store');
    Route::put('/question-bank/{id}', [QuestionBankController::class, 'update'])->name('question-bank.update');
    Route::delete('/question-bank/{id}', [QuestionBankController::class, 'destroy'])->name('question-bank.destroy');

    // Rating Scales (Master Skala)
    Route::resource('rating-scales', RatingScaleController::class)->except(['create', 'show', 'edit']);
});
