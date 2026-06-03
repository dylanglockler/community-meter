<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyResponse extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'water_charge_range',
        'household_size',
        'separate_charge',
        'charge_calculation',
        'charge_increased',
        'shown_records',
        'home_ownership',
        'home_age',
        'residency_duration',
        'additional_comments',
        'contact_email',
        'submitted_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];
}
