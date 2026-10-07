<!DOCTYPE html>
<html lang="id">
<head>
    @php
        // Tarik semua identitas perusahaan dari Database
        $companyName = \App\Models\AppInformation::getValue('company_name', 'PT. Hanania');
        $tagline = \App\Models\AppInformation::getValue('company_tagline', 'Spesialis Perjalanan Umroh, Haji & Wisata Muslim');
        $address = \App\Models\AppInformation::getValue('office_address', 'Jl. Raya Umroh No. 88, Jakarta Selatan');
        $phone = \App\Models\AppInformation::getValue('phone_number', '(021) 555-8899');
        $location = \App\Models\AppInformation::getValue('default_location', 'Jakarta');
    @endphp

    <meta charset="UTF-8">
    <title>Kuitansi Pembayaran - {{ $companyName }}</title>
    <style>
        body {
            font-family: 'Helvetica, Arial, sans-serif;
            color: #333;
            font-size: 14px;
            line-height: 1.5;
            margin: 0;
            padding: 20px;
        }
        .header {
            border-bottom: 2px solid #5b21b6;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header table {
            width: 100%;
        }
        .company-name {
            font-size: 22px;
            font-weight: bold;
            color: #5b21b6;
            margin: 0;
            text-transform: uppercase;
        }
        .company-tagline {
            font-size: 11px;
            color: #666;
            margin: 0;
        }
        .invoice-title {
            text-align: right;
        }
        .invoice-title h2 {
            margin: 0;
            color: #333;
            font-size: 20px;
        }
        .invoice-title p {
            margin: 2px 0 0;
            font-size: 12px;
            color: #666;
        }
        .badge-sah {
            background-color: #d1fae5;
            color: #065f46;
            padding: 5px 12px;
            font-weight: bold;
            font-size: 11px;
            border-radius: 4px;
            text-transform: uppercase;
        }
        .section-title {
            font-weight: bold;
            font-size: 13px;
            color: #5b21b6;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 5px;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        .info-table, .detail-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 5px 0;
            vertical-align: top;
        }
        .detail-table th, .detail-table td {
            border: 1px solid #e5e7eb;
            padding: 10px;
            text-align: left;
        }
        .detail-table th {
            background-color: #f9fafb;
            color: #374151;
            font-size: 12px;
            text-transform: uppercase;
        }
        .amount-box {
            background-color: #f3f4f6;
            border: 1px dashed #d1d5db;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 30px;
        }
        .footer {
            margin-top: 40px;
            width: 100%;
        }
        .footer td {
            text-align: center;
            vertical-align: top;
        }
        .signature-space {
            height: 60px;
        }
    </style>
</head>
<body>

    <!-- Header Perusahaan -->
    <div class="header">
        <table>
            <tr>
                <td>
                    <h2 class="company-name">{{ $companyName }}</h2>
                    <p class="company-tagline">{{ $tagline }}</p>
                    <p class="company-tagline">{{ $address }} | Telp: {{ $phone }}</p>
                </td>
                <td class="invoice-title">
                    <h2>KUITANSI RESMI</h2>
                    <!-- 💡 PERBAIKAN 1: Memanggil nomor transaksi yang sudah kita buat -->
                    <p>No. Ref: <strong>{{ $transaction->transaction_number }}</strong></p>
                    <p>Tanggal: {{ $transaction->created_at->format('d M Y, H:i') }}</p>
                </td>
            </tr>
        </table>
    </div>

    <!-- Informasi Pembayaran dari Siapa -->
    <div class="section-title">Informasi Jamaah & Pendaftaran</div>
    <table class="info-table">
        <tr>
            <td style="width: 150px;">Nama Jamaah</td>
            <td style="width: 10px;">:</td>
            <td>
                <!-- 💡 PERBAIKAN 2: Menggunakan passenger_name, dan menambahkan info Penyetor -->
                <strong>{{ $transaction->paymentPlan->enrollment->passenger_name }}</strong><br>
                <span style="font-size: 11px; color: #666;">(Akun Penyetor: {{ $transaction->paymentPlan->enrollment->customer->name }})</span>
            </td>
            <td style="width: 120px;">No. Pendaftaran</td>
            <td style="width: 10px;">:</td>
            <td>
                <!-- 💡 Info: Ini akan otomatis menampilkan HJI-... atau UMR-... sesuai kodingan kita tadi -->
                <strong>{{ $transaction->paymentPlan->enrollment->enrollment_number }}</strong>
            </td>
        </tr>
        <tr>
            <td>Paket Travel</td>
            <td>:</td>
            <td>{{ $transaction->paymentPlan->enrollment->travelPackage->name }}</td>
            <td>Status Transaksi</td>
            <td>:</td>
            <td><span class="badge-sah">LUNAS / SAH</span></td>
        </tr>
    </table>

    <!-- Rincian Nominal -->
    <div class="section-title" style="margin-top: 25px;">Rincian Transaksi</div>
    <table class="detail-table">
        <thead>
            <tr>
                <th>Jenis Pembayaran</th>
                <th>Metode</th>
                <th style="text-align: right;">Jumlah (IDR)</th>
            </tr>
        </thead>
        <tbody>
            @php
                $creditedAmount = (float) ($transaction->net_amount ?? $transaction->amount ?? 0);
                $feeAmount = (float) ($transaction->fee_amount ?? 0);
                $chargedAmount = $creditedAmount + $feeAmount;
            @endphp
            <tr>
                <td>
                    <strong>{{ strtoupper(str_replace('_', ' ', $transaction->type)) }}</strong><br>
                    <span style="font-size: 11px; color: #666;">Pembayaran tabungan umroh/haji via sistem {{ $companyName }}</span>
                </td>
                <td>{{ strtoupper(str_replace('_', ' ', $transaction->payment_method)) }}</td>
                <td style="text-align: right; font-weight: bold;">Rp {{ number_format($chargedAmount, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Box Terbilang / Total -->
    <div class="amount-box">
        <table>
            <tr>
                <td><strong>Terbilang:</strong></td>
                <td style="text-align: right; font-size: 16px; font-weight: bold; color: #5b21b6;">
                    Rp {{ number_format($chargedAmount, 0, ',', '.') }}
                </td>
            </tr>
        </table>
    </div>

    <!-- Tanda Tangan / Footer -->
    <table class="footer">
        <tr>
            <td style="text-align: left; width: 60%; color: #666; font-size: 11px;">
                <p>Catatan:</p>
                <p>1. Kuitansi ini adalah bukti sah pembayaran yang dikeluarkan oleh {{ $companyName }}.</p>
                <p>2. Dana yang sudah disetor tidak dapat ditarik kembali kecuali mengikuti ketentuan pembatalan.</p>
            </td>
            <td style="text-align: center; width: 40%;">
                <p>{{ $location }}, {{ now()->format('d M Y') }}</p>
                <p><strong>Finance Department</strong></p>
                <div class="signature-space"></div>
                <p><strong>( _____________________ )</strong></p>
            </td>
        </tr>
    </table>

</body>
</html>