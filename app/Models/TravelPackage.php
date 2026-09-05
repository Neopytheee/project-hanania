<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TravelPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'facilities',
        'image',
        'estimated_price',
        'duration_days',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'estimated_price' => 'decimal:2',
            'duration_days' => 'integer',
        ];
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function departures(): HasMany
    {
        return $this->hasMany(Departure::class);
    }
}