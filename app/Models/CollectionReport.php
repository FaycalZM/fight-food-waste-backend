<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectionReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'collection_id',
        'report',
        'pdf_link',
    ];

    public function collection()
    {
        return $this->belongsTo(Collection::class, 'collection_id');
    }
}
