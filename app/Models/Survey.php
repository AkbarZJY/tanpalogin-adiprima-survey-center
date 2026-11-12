<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Survey extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'category',
        'survey_category_id',
        'rating_scale_id',
        'rating_scale',
        'description',
        'icon',
        'start_date',
        'end_date',
        'is_active',
        'is_archived',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_archived' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function categoryModel()
    {
        return $this->belongsTo(SurveyCategory::class, 'survey_category_id');
    }

    public function ratingScale()
    {
        return $this->belongsTo(RatingScale::class, 'rating_scale_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_archived', false)->where('is_active', true);
    }

    public function scopeArchived($query)
    {
        return $query->where('is_archived', true);
    }

    public function scopeUnarchived($query)
    {
        return $query->where('is_archived', false);
    }

    public function periods()
    {
        return $this->hasMany(SurveyPeriod::class);
    }

    public function activePeriod()
    {
        return $this->hasOne(SurveyPeriod::class)->where('is_active', true)->latestOfMany();
    }

    public function questions()
    {
        return $this->hasMany(SurveyQuestion::class)->orderBy('order');
    }

    public function responses()
    {
        return $this->hasMany(SurveyResponse::class);
    }

    /**
     * Check if the survey is within active date range
     */
    public function isWithinActiveDate(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $today = Carbon::today();

        if ($this->start_date && $today->lt($this->start_date)) {
            return false;
        }

        if ($this->end_date && $today->gt($this->end_date)) {
            return false;
        }

        return true;
    }

    /**
     * Get active period or fallback created from survey's dates
     */
    public function getOrCreateCurrentPeriod(): SurveyPeriod
    {
        $period = $this->activePeriod;
        if ($period) {
            return $period;
        }

        return SurveyPeriod::create([
            'survey_id' => $this->id,
            'period_name' => 'Periode ' . date('Y'),
            'start_date' => $this->start_date ?? Carbon::today(),
            'end_date' => $this->end_date ?? Carbon::today()->addMonth(),
            'is_active' => true,
        ]);
    }
}
