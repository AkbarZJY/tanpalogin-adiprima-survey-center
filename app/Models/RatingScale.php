<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RatingScale extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'min_score',
        'max_score',
        'options_json',
        'description',
        'is_default',
    ];

    protected $casts = [
        'min_score' => 'integer',
        'max_score' => 'integer',
        'options_json' => 'array',
        'is_default' => 'boolean',
    ];

    /**
     * Relationship to surveys using this scale.
     */
    public function surveys()
    {
        return $this->hasMany(Survey::class, 'rating_scale_id');
    }
}
