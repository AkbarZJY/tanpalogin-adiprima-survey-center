<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'color',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    public function surveys()
    {
        return $this->hasMany(Survey::class, 'survey_category_id')->orderBy('id', 'desc');
    }

    public function dimensions()
    {
        return $this->hasMany(SurveyDimension::class, 'survey_category_id')->orderBy('order')->orderBy('name');
    }

    public function activeSurveys()
    {
        return $this->hasMany(Survey::class, 'survey_category_id')
            ->where('is_archived', false)
            ->where('is_active', true);
    }

    public function unarchivedSurveys()
    {
        return $this->hasMany(Survey::class, 'survey_category_id')
            ->where('is_archived', false);
    }

    public function archivedSurveys()
    {
        return $this->hasMany(Survey::class, 'survey_category_id')
            ->where('is_archived', true);
    }
}
