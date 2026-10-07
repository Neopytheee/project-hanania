<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'enrollment_number',
        'unique_code',
        'customer_id',
        'passenger_name',
        'relationship',
        'travel_package_id',
        'estimated_price_snapshot',
        'final_price',
        'status',
        'enrolled_at',
        'funds_sufficient_at',
        'scheduled_at',
        'price_confirmed_at',
        'completed_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'estimated_price_snapshot' => 'decimal:2',
            'final_price' => 'decimal:2',
            'enrolled_at' => 'datetime',
            'funds_sufficient_at' => 'datetime',
            'scheduled_at' => 'datetime',
            'price_confirmed_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function travelPackage(): BelongsTo
    {
        return $this->belongsTo(TravelPackage::class);
    }

    public function paymentPlan(): HasOne
    {
        return $this->hasOne(PaymentPlan::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function groupMemberships(): HasMany
    {
        return $this->hasMany(GroupMembership::class);
    }

    public function cancellationRequests(): HasMany
    {
        return $this->hasMany(CancellationRequest::class);
    }

    /**
     * Group membership yang sedang aktif.
     */
    public function activeGroupMembership(): HasOne
    {
        return $this->hasOne(GroupMembership::class)
            ->where('status', 'active');
    }

    /**
     * Group aktif saat ini.
     */
    public function activeGroup()
    {
        return $this->hasOneThrough(
            Group::class,
            GroupMembership::class,
            'enrollment_id',
            'id',
            'id',
            'group_id'
        )->where('group_memberships.status', 'active');
    }

    /**
     * Total pembayaran yang sudah diverifikasi.
     *
     * Ini sengaja bukan field database.
     * Nilainya dihitung dari payment transactions.
     */
    public function verifiedPaymentTotal(): float
    {
        return (float) (
            $this->paymentPlan?->transactions()
                ->where('status', 'verified')
                ->sum(DB::raw('COALESCE(net_amount, amount)')) ?? 0
        );
    }

    /**
     * Sisa pembayaran berdasarkan target final.
     */
    public function outstandingAmount(): float
    {
        $target = $this->paymentPlan?->final_target_amount;

        if ($target === null) {
            return 0;
        }

        return max(
            (float) $target - $this->verifiedPaymentTotal(),
            0
        );
    }

    /**
     * Menerjemahkan status sistem bahasa Inggris ke bahasa Indonesia yang ramah jamaah
     */
    public function statusText(): string
    {
        $statuses = [
            'enrolled' => 'Pendaftaran Berhasil',
            'saving' => 'Aktif Menabung',
            'funds_sufficient' => 'Target Tercapai (Penyesuaian Jadwal)',
            'waiting_schedule' => 'Menunggu Penempatan Kloter',
            'scheduled' => 'Tergabung di Kloter Keberangkatan',
            'price_confirmed' => 'Harga Telah Disetujui',
            'payment_due' => 'Menunggu Pelunasan',
            'overdue' => 'Batas Pelunasan Terlewat',
            'fully_paid' => 'Pembayaran Lunas',
            'ready' => 'Siap Berangkat',
            'departed' => 'Sedang di Tanah Suci',
            'completed' => 'Ibadah Selesai (Alhamdulillah)',
            'cancelled' => 'Pendaftaran Dibatalkan',
        ];

        // Jika status ada di array, tampilkan bahasa Indonesianya.
        // Jika tidak ada, fallback ke bawaan sistem.
        return $statuses[$this->status] ?? strtoupper(str_replace('_', ' ', $this->status));
    }
}
