<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Question Templates (Question Bank Master)
        Schema::create('question_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dimension_id')->nullable()->constrained('survey_dimensions')->nullOnDelete();
            $table->string('indicator_title')->nullable();
            $table->text('question_text');
            $table->string('question_type', 50)->default('dual_rating'); // dual_rating, single_rating, multiple_choice, essay
            $table->integer('rating_scale')->default(4); // 4, 5, 10
            $table->boolean('require_reason_on_low_score')->default(false);
            $table->integer('low_score_threshold')->default(2);
            $table->json('options_json')->nullable();
            $table->string('applies_to_employment_status', 100)->nullable();
            $table->string('applies_to_positions')->nullable();
            $table->string('applies_to_gender', 50)->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // 2. Survey Questions (Questions attached to specific Surveys)
        Schema::create('survey_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survey_id')->constrained('surveys')->onDelete('cascade');
            $table->foreignId('dimension_id')->nullable()->constrained('survey_dimensions')->nullOnDelete();
            $table->foreignId('question_template_id')->nullable()->constrained('question_templates')->nullOnDelete();
            $table->string('section', 10); // A, B, C
            $table->string('dimension')->nullable();
            $table->integer('question_number');
            $table->string('indicator_title')->nullable();
            $table->text('question_text');
            $table->string('question_type', 50)->default('dual_rating');
            $table->integer('rating_scale')->default(4);
            $table->boolean('require_reason_on_low_score')->default(false);
            $table->integer('low_score_threshold')->default(2);
            $table->json('options_json')->nullable();
            $table->string('applies_to_employment_status', 100)->nullable();
            $table->string('applies_to_positions')->nullable();
            $table->string('applies_to_gender', 50)->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_questions');
        Schema::dropIfExists('question_templates');
    }
};
