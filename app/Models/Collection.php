<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_ids',
        'scheduled_time',
        'volunteers_count',
        'route',
        'collection_status',
    ];

    public function products()
    {
        return $this->hasMany(CollectionProduct::class, 'collection_id');
    }

    public function report()
    {
        return $this->hasOne(CollectionReport::class, 'collection_id');
    }
}
