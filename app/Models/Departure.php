<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Departure extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'travel_package_id',
        'name',
        'departure_date',
        'quota',
        'estimated_price',
        'final_price',
        'status',
        'manasik_date',       // <-- Ditambahkan
        'manasik_location',   // <-- Ditambahkan
        'itinerary_file',     // <-- Ditambahkan
        'finalized_at',
        'finalized_by',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'departure_date' => 'date',
            'quota' => 'integer',
            'estimated_price' => 'decimal:2',
            'final_price' => 'decimal:2',
            'finalized_at' => 'datetime',
            'manasik_date' => 'datetime', // <-- Ditambahkan (agar otomatis jadi objek Carbon)
        ];
    }

    public function travelPackage(): BelongsTo
    {
        return $this->belongsTo(TravelPackage::class);
    }

    public function groups(): HasMany
    {
        return $this->hasMany(Group::class);
    }

    public function finalizedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'finalized_by');
    }
    public function canBeDeparted(): bool
    {
        return in_array($this->status, ['draft', 'open', 'filling', 'full', 'closed', 'ready']);
    }
}