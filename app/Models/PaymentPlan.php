<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'enrollment_id',
        'minimum_initial_payment',
        'minimum_monthly_payment',
        'monthly_due_day',
        'estimated_target_amount',
        'final_target_amount',
        'due_date',
        'status',
        'finalized_at',
    ];

    protected function casts(): array
    {
        return [
            'minimum_initial_payment' => 'decimal:2',
            'minimum_monthly_payment' => 'decimal:2',
            'monthly_due_day' => 'integer',
            'estimated_target_amount' => 'decimal:2',
            'final_target_amount' => 'decimal:2',
            'due_date' => 'date',
            'finalized_at' => 'datetime',
        ];
    }

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(PaymentTransaction::class);
    }

    public function verifiedTransactions(): HasMany
    {
        return $this->hasMany(PaymentTransaction::class)
            ->where('status', 'verified');
    }
}