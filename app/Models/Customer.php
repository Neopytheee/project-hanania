<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
    'user_id',
    'customer_number',
    'name',
    'nik',
    'phone',
    'email',
    'birth_date',
    'gender',
    'address',
    'emergency_contact_name',
    'emergency_contact_phone',
    'profile_image',
    'status',
    'bank_name',           // 🏦 Tambahan Baru
    'bank_account_number', // 🏦 Tambahan Baru
    'bank_account_name',   // 🏦 Tambahan Baru
];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }
}