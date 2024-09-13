<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistributionReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'distribution_id',
        'report',
        'pdf_link',
    ];

    public function distribution()
    {
        return $this->belongsTo(Distribution::class, 'distribution_id');
    }
}
