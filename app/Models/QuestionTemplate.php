<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'dimension_id',
        'section',
        'indicator_title',
        'question_text',
        'question_type',
        'rating_scale',
        'require_reason_on_low_score',
        'low_score_threshold',
        'options_json',
        'applies_to_employment_status',
        'applies_to_positions',
        'applies_to_gender',
        'order',
    ];

    protected $casts = [
        'require_reason_on_low_score' => 'boolean',
        'rating_scale' => 'integer',
        'low_score_threshold' => 'integer',
        'options_json' => 'array',
    ];

    public function dimension()
    {
        return $this->belongsTo(SurveyDimension::class, 'dimension_id');
    }

    public function surveyQuestions()
    {
        return $this->hasMany(SurveyQuestion::class, 'question_template_id');
    }
}
