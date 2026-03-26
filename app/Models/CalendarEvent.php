<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalendarEvent extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'type',
        'start_date',
        'end_date',
        'color',
        'description',
        'notify_2_days',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'notify_2_days' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}