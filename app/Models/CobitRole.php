<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CobitRole extends Model
{
    protected $fillable = [
        'code',
        'name',
        'short_name',
        'sequence',
    ];

    public function raciAssignments(): HasMany
    {
        return $this->hasMany(CobitRaciAssignment::class);
    }

    public function practices(): BelongsToMany
    {
        return $this->belongsToMany(CobitPractice::class, 'cobit_raci_assignments')
            ->withPivot('raci_level')
            ->withTimestamps();
    }
}
