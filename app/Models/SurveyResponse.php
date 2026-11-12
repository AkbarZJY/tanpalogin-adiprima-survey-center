<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'survey_id',
        'survey_period_id',
        'user_id',
        'nik',
        'name',
        'gender',
        'age',
        'education',
        'employment_status',
        'outsourcing_company',
        'tenure',
        'department',
        'position',
        'submitted_at',
        'started_at',
        'ip_address',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'started_at' => 'datetime',
    ];

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    public function period()
    {
        return $this->belongsTo(SurveyPeriod::class, 'survey_period_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function answers()
    {
        return $this->hasMany(SurveyAnswer::class, 'survey_response_id');
    }
}
