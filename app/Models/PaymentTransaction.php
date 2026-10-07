<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_plan_id',
        'saving_period_id',
        'transaction_number',
        'type',
        'amount',
        'payment_method',
        'midtrans_channel',
        'fee_amount',
        'fee_rate',
        'net_amount',
        'reference_number',
        'proof_file',
        'status',
        'paid_at',
        'verified_at',
        'verified_by',
        'rejection_reason',
        'snap_token',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'fee_amount' => 'decimal:2',
            'fee_rate' => 'decimal:6',
            'net_amount' => 'decimal:2',
            'paid_at' => 'datetime',
            'verified_at' => 'datetime',
        ];
    }

    public function paymentPlan(): BelongsTo
    {
        return $this->belongsTo(PaymentPlan::class);
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
