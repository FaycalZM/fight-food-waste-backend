<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VolunteerAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'schedule_id',
        'task_type',
        'start_time',
        'end_time',
        'assignment_status',
    ];

    public function schedule()
    {
        return $this->belongsTo(VolunteerSchedule::class, 'schedule_id');
    }
}
