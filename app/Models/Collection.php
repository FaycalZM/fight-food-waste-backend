<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'scheduled_time',
        'route',
        'collection_status',
    ];

    public function merchant()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function products()
    {
        return $this->hasMany(CollectionProduct::class, 'collection_id');
    }

    public function report()
    {
        return $this->hasOne(CollectionReport::class, 'collection_id');
    }
}
