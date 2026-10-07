<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppInformation extends Model
{
    use HasFactory;

    // Nama tabel secara eksplisit (opsional tapi bagus untuk kepastian)
    protected $table = 'app_informations';

    protected $fillable = [
        'key',
        'value',
        'type',
    ];

    /**
     * Fungsi helper untuk memanggil value berdasarkan key dengan mudah
     * Contoh pakai: \App\Models\AppInformation::getValue('bank_name')
     */
    public static function getValue($key, $default = null)
    {
        $info = self::where('key', $key)->first();

        return $info ? $info->value : $default;
    }

    public static function isPaymentMidtransEnabled(): bool
    {
        return filter_var(self::getValue('payment_midtrans_enabled', '1'), FILTER_VALIDATE_BOOLEAN);
    }
}
