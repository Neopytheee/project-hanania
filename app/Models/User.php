<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'email',
        'password',
        'role',
        'status',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ==========================================
    // RELASI DATABASE
    // ==========================================

    public function customer(): HasOne
    {
        return $this->hasOne(Customer::class);
    }

    public function verifiedPayments(): HasMany
    {
        return $this->hasMany(
            PaymentTransaction::class,
            'verified_by'
        );
    }

    public function reviewedDocuments(): HasMany
    {
        return $this->hasMany(
            Document::class,
            'reviewed_by'
        );
    }

    public function assignedGroupMemberships(): HasMany
    {
        return $this->hasMany(
            GroupMembership::class,
            'assigned_by'
        );
    }

    public function reviewedCancellations(): HasMany
    {
        return $this->hasMany(
            CancellationRequest::class,
            'reviewed_by'
        );
    }

    public function finalizedDepartures(): HasMany
    {
        return $this->hasMany(
            Departure::class,
            'finalized_by'
        );
    }

    // ==========================================
    // JURUS CUSTOM UNTUK EMAIL NOTIFIKASI
    // ==========================================

    /**
     * Mengambil nama dari tabel Customer agar bisa dipanggil dengan $user->name di email
     */
    public function getNameAttribute()
    {
        return $this->customer ? $this->customer->name : 'Jamaah Hanania';
    }

    /**
     * Override (Timpa) fungsi bawaan Laravel agar pakai template email buatan kita
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\CustomResetPassword($token));
    }
}