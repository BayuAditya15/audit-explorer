<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CobitActivity extends Model
{
    protected $fillable = [
        'cobit_practice_id',
        'sequence',
        'description',
    ];

    public function practice(): BelongsTo
    {
        return $this->belongsTo(CobitPractice::class, 'cobit_practice_id');
    }
}