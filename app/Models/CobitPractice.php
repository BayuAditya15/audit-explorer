<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CobitPractice extends Model
{
    protected $fillable = [
        'cobit_objective_id',
        'practice_id',
        'name',
        'description',
    ];

    public function objective(): BelongsTo
    {
        return $this->belongsTo(CobitObjective::class, 'cobit_objective_id');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(CobitActivity::class);
    }

    public function raciAssignments(): HasMany
    {
        return $this->hasMany(CobitRaciAssignment::class);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(CobitRole::class, 'cobit_raci_assignments')
            ->withPivot('raci_level')
            ->withTimestamps();
    }
}