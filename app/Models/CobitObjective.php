<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class CobitObjective extends Model
{
    protected $fillable = [
        'objective_id',
        'area',
        'domain',
        'name',
        'description',
        'purpose_statement',
        'new_in_cobit_2019',
        'new_in_cobit_5',
    ];

    protected function casts(): array
    {
        return [
            'new_in_cobit_2019' => 'boolean',
            'new_in_cobit_5' => 'boolean',
        ];
    }

    public function practices(): HasMany
    {
        return $this->hasMany(CobitPractice::class);
    }

    public function activities(): HasManyThrough
    {
        return $this->hasManyThrough(CobitActivity::class, CobitPractice::class);
    }
}