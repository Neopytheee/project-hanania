<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    use HasFactory;

    protected $table = 'groups';

    protected $fillable = [
        'departure_id',
        'code',
        'name',
        'capacity',
        'status',
        'finalized_at',
    ];

    protected function casts(): array
    {
        return [
            'capacity' => 'integer',
            'finalized_at' => 'datetime',
        ];
    }

    public function departure(): BelongsTo
    {
        return $this->belongsTo(Departure::class);
    }

    public function memberships(): HasMany
    {
        return $this->hasMany(GroupMembership::class);
    }

    /**
     * Hanya anggota aktif.
     */
    public function activeMemberships(): HasMany
    {
        return $this->hasMany(GroupMembership::class)
            ->where('status', 'active');
    }

    /**
     * Jumlah anggota aktif.
     */
    public function activeMemberCount(): int
    {
        return $this->activeMemberships()->count();
    }

    /**
     * Sisa kapasitas group.
     */
    public function remainingCapacity(): int
    {
        return max(
            $this->capacity - $this->activeMemberCount(),
            0
        );
    }
}