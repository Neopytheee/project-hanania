<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi - {{ \App\Models\AppInformation::getValue('company_name') ?? 'Hanania' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            @page { margin: 1.5cm; size: A4 portrait; }
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .no-print { display: none !important; }
        }
        body { font-family: 'Arial', sans-serif; color: #000; background-color: #fff; }
    </style>
</head>
<body class="p-8 max-w-4xl mx-auto text-[13px]">
    
    <div class="mb-6 no-print">
        <button onclick="window.close()" class="px-4 py-2 bg-slate-800 text-white font-bold rounded-lg shadow text-xs">
            &larr; Tutup & Kembali
        </button>
    </div>

    <!-- KOP SURAT -->
    <div class="text-center border-b-2 border-black pb-4 mb-6 flex flex-col items-center">
        <!-- Logo (Jika Ada) -->
        @php $logoPath = \App\Models\AppInformation::getValue('company_logo'); @endphp
        @if($logoPath)
            <img src="{{ asset('storage/' . $logoPath) }}" alt="Logo" class="h-16 mb-2">
        @endif
        
        <h1 class="text-2xl font-black uppercase tracking-widest mb-1">
            {{ \App\Models\AppInformation::getValue('company_name') ?? 'Hanania' }}
        </h1>
        
        <p class="text-[11px] font-medium max-w-lg">
            {{ \App\Models\AppInformation::getValue('office_address') ?? 'Alamat belum diatur' }}<br>
            Telp: {{ \App\Models\AppInformation::getValue('phone_number') ?? '-' }} | WA: {{ \App\Models\AppInformation::getValue('whatsapp_number') ?? '-' }}
        </p>
        
        <p class="text-sm font-black mt-3 border-t border-dashed border-slate-300 pt-2 inline-block">
            LAPORAN RIWAYAT TRANSAKSI & PEMASUKAN
        </p>
    </div>

    <!-- INFORMASI FILTER -->
    <div class="mb-6">
        <table class="w-1/2 text-[12px]">
            <tr>
                <td class="py-1 font-bold w-32 uppercase text-slate-600">Periode</td>
                <td class="py-1 font-bold">: 
                    {{ !empty($filters['start_date']) ? \Carbon\Carbon::parse($filters['start_date'])->format('d M Y') . ' s/d ' . \Carbon\Carbon::parse($filters['end_date'])->format('d M Y') : 'Semua Waktu' }}
                </td>
            </tr>
            <tr>
                <td class="py-1 font-bold uppercase text-slate-600">Status Transaksi</td>
                <td class="py-1 font-bold">: 
                    @if(($filters['status'] ?? 'all') === 'verified') Sukses / Terverifikasi
                    @elseif(($filters['status'] ?? 'all') === 'pending') Pending (Menunggu)
                    @elseif(($filters['status'] ?? 'all') === 'rejected') Ditolak / Gagal
                    @else Semua Status
                    @endif
                </td>
            </tr>
            @if(!empty($filters['search']))
            <tr>
                <td class="py-1 font-bold uppercase text-slate-600">Pencarian</td>
                <td class="py-1 font-bold">: "{{ $filters['search'] }}"</td>
            </tr>
            @endif
        </table>
    </div>

    <!-- TABEL DATA -->
    <table class="w-full text-left border-collapse border border-slate-800">
        <thead>
            <tr class="bg-slate-100">
                <th class="border border-slate-800 py-2.5 px-3 font-extrabold text-center w-12">No</th>
                <th class="border border-slate-800 py-2.5 px-3 font-extrabold">Tanggal Pembayaran</th>
                <th class="border border-slate-800 py-2.5 px-3 font-extrabold">Nama Jamaah & Resi</th>
                <th class="border border-slate-800 py-2.5 px-3 font-extrabold text-center">Status</th>
                <th class="border border-slate-800 py-2.5 px-3 font-extrabold text-right">Nominal (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $totalAmount = 0; 
                $no = 1;
            @endphp
            
            @forelse($transactions as $trx)
                @if($trx->status === 'verified')
                    @php $totalAmount += $trx->amount; @endphp
                @endif
                
                <tr>
                    <td class="border border-slate-800 py-2 px-3 text-center">{{ $no++ }}</td>
                    <td class="border border-slate-800 py-2 px-3">
                        <span class="font-bold">{{ \Carbon\Carbon::parse($trx->created_at)->format('d M Y') }}</span><br>
                        <span class="text-[11px] text-slate-500">{{ \Carbon\Carbon::parse($trx->created_at)->format('H:i') }} WIB</span>
                    </td>
                    <td class="border border-slate-800 py-2 px-3">
                        <span class="font-bold">{{ $trx->paymentPlan->enrollment->passenger_name ?? 'Fulan' }}</span><br>
                        <span class="text-[11px] text-slate-500 font-mono">{{ $trx->transaction_number }}</span>
                    </td>
                    <td class="border border-slate-800 py-2 px-3 text-center uppercase text-[11px] font-bold">
                        {{ $trx->status }}
                    </td>
                    <td class="border border-slate-800 py-2 px-3 text-right font-bold">
                        {{ number_format($trx->amount, 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="border border-slate-800 py-6 text-center italic text-slate-500">
                        Tidak ada data transaksi pada filter ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
        
        @if(in_array($filters['status'] ?? 'all', ['all', 'verified']))
        <tfoot>
            <tr class="bg-slate-50">
                <td colspan="4" class="border border-slate-800 py-3 px-3 text-right font-black uppercase tracking-wider">Total Pemasukan (Sukses)</td>
                <td class="border border-slate-800 py-3 px-3 text-right font-black text-[14px]">
                    Rp {{ number_format($totalAmount, 0, ',', '.') }}
                </td>
            </tr>
        </tfoot>
        @endif
    </table>

    <!-- AREA TANDA TANGAN -->
    <div class="mt-12 flex justify-end">
        <div class="text-center w-64">
            <!-- Asumsi sementara pakai Tangerang Selatan, bisa diganti sesuai kota base bosku -->
            <p class="mb-20">Tangerang Selatan, {{ now()->format('d M Y') }}</p>
            <p class="font-black border-b border-black inline-block pb-1 min-w-[200px] uppercase">
                ( {{ Auth::user()->name ?? 'Administrator' }} )
            </p>
            <p class="mt-1 font-semibold">Finance / Admin {{ \App\Models\AppInformation::getValue('company_name') ?? 'Hanania' }}</p>
        </div>
    </div>

    <!-- SCRIPT AUTO PRINT -->
    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500); 
        }
    </script>
</body>
</html>