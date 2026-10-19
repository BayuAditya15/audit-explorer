<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CobitRaciAssignment extends Model
{
    protected $fillable = [
        'cobit_practice_id',
        'cobit_role_id',
        'raci_level',
    ];

    public function practice(): BelongsTo
    {
        return $this->belongsTo(CobitPractice::class, 'cobit_practice_id');
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(CobitRole::class, 'cobit_role_id');
    }
}
