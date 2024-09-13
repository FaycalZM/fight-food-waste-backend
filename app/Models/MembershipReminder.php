<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembershipReminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reminder_date',
        'reminder_type',
    ];

    public function merchant()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
