<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VolunteerSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'volunteer_id',
        'schedule_day',
        'schedule_status',
    ];

    public function volunteer()
    {
        return $this->belongsTo(Volunteer::class, 'volunteer_id');
    }

    public function assignments()
    {
        return $this->hasMany(VolunteerAssignment::class, 'schedule_id');
    }
}
