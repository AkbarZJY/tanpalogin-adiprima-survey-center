<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyDimension extends Model
{
    use HasFactory;

    protected $fillable = [
        'survey_category_id',
        'name',
        'code',
        'description',
        'color',
        'order',
    ];

    public function category()
    {
        return $this->belongsTo(SurveyCategory::class, 'survey_category_id');
    }

    public function scopeForCategory($query, $categoryId)
    {
        return $query->where('survey_category_id', $categoryId);
    }

    public function questions()
    {
        return $this->hasMany(SurveyQuestion::class, 'dimension_id');
    }

    public function questionTemplates()
    {
        return $this->hasMany(QuestionTemplate::class, 'dimension_id');
    }
}
