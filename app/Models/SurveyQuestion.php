<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'survey_id',
        'dimension_id',
        'question_template_id',
        'section',
        'dimension',
        'question_number',
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

    protected static function booted()
    {
        static::deleting(function ($question) {
            $question->answers()->delete();
        });
    }

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    public function dimensionModel()
    {
        return $this->belongsTo(SurveyDimension::class, 'dimension_id');
    }

    public function template()
    {
        return $this->belongsTo(QuestionTemplate::class, 'question_template_id');
    }

    public function answers()
    {
        return $this->hasMany(SurveyAnswer::class, 'question_id');
    }

    /**
     * Helper to get dimension display name
     */
    public function getDimensionNameAttribute()
    {
        return $this->dimensionModel?->name ?? $this->dimension ?? 'General';
    }
}
