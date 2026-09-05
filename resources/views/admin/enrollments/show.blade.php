@extends('layouts.admin.app')

@section('header_title', 'Detail Pendaftaran')

@section('content')
<div class="max-w-7xl mx-auto animate-fade-in-up pb-12">
    
    <!-- HEADER -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.enrollments.index') }}" class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-slate-500 hover:text-hanania-purple transition-all border border-slate-200 shadow-sm shrink-0">
                <span class="material-symbols-outlined text-[20px]">arrow_back</span>
            </a>
            <div>
                <h2 class="text-[24px] font-black text-slate-900 tracking-tight flex items-center gap-2">Detail #{{ $enrollment->enrollment_number }}</h2>
                <p class="text-[13px] text-slate-500 font-medium mt-0.5">Tinjauan lengkap data penumpang, dokumen, dan keuangan.</p>
            </div>
        </div>
        
        <!-- Status Badge -->
        <div class="px-4 py-2 rounded-xl bg-white border border-slate-200 shadow-sm flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
            <span class="text-[11px] font-black uppercase tracking-widest text-slate-700">Status: {{ $enrollment->statusText() }}</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- ========================================== -->
        <!-- KOLOM KIRI: Profil, Paket & Dokumen -->
        <!-- ========================================== -->
        <div class="space-y-6">
            
            <!-- Card Profil -->
            <div class="card-admin p-5 sm:p-6 relative overflow-hidden">
                <h4 class="text-[14px] font-black text-slate-800 border-b border-slate-100 pb-3 mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-hanania-purple text-[18px]">account_box</span> Info Jamaah & Akun
                </h4>
                
                <div class="space-y-4">
                    <!-- Info Penumpang -->
                    <div class="bg-gradient-to-br from-hanania-purple/10 to-transparent p-4 rounded-xl border border-hanania-purple/20 relative">
                        <span class="material-symbols-outlined absolute right-2 bottom-2 text-[40px] text-hanania-purple/10 pointer-events-none">flight_takeoff</span>
                        <p class="text-[10px] uppercase font-bold text-hanania-purple tracking-wider mb-1">Jamaah Yang Berangkat</p>
                        <p class="font-black text-[16px] text-slate-800 leading-tight mb-1.5">{{ $enrollment->passenger_name }}</p>
                        <span class="inline-block text-[9px] font-black uppercase tracking-widest bg-hanania-purple text-white px-2 py-0.5 rounded shadow-sm">
                            Hubungan: {{ $enrollment->relationship }}
                        </span>
                    </div>

                    <!-- Info Akun -->
                    <div class="space-y-3 pt-2">
                        <div>
                            <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Akun Pendaftar / Pembiayaan</p>
                            <p class="text-[13px] font-bold text-slate-800 flex items-center gap-1.5 mt-0.5"><span class="material-symbols-outlined text-[16px] text-slate-400">person</span> {{ $enrollment->customer->name ?? 'Tanpa Nama' }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">WhatsApp</p>
                                <p class="text-[12px] font-bold text-slate-800 flex items-center gap-1 mt-0.5"><span class="material-symbols-outlined text-[14px] text-slate-400">call</span> {{ $enrollment->customer->phone ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Email</p>
                                <p class="text-[12px] font-bold text-slate-800 flex items-center gap-1 mt-0.5 truncate" title="{{ $enrollment->customer->email ?? '-' }}"><span class="material-symbols-outlined text-[14px] text-slate-400">mail</span> {{ $enrollment->customer->email ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Paket -->
            <div class="card-admin p-5 sm:p-6">
                <h4 class="text-[14px] font-black text-slate-800 border-b border-slate-100 pb-3 mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-amber-500 text-[18px]">luggage</span> Paket Pilihan
                </h4>
                
                <p class="font-black text-hanania-purple text-[15px] leading-tight mb-1">{{ $enrollment->travelPackage->name }}</p>
                <div class="inline-flex items-center gap-1 bg-slate-100 px-2.5 py-1 rounded text-[11px] font-bold text-slate-600 mb-4 border border-slate-200">
                    <span class="material-symbols-outlined text-[14px]">schedule</span> {{ $enrollment->travelPackage->duration_days }} Hari
                </div>
                
                <div class="bg-slate-50 p-3 rounded-xl border border-slate-200">
                    <p class="text-[10px] text-slate-500 uppercase font-bold tracking-wider mb-0.5">
                        {{ $enrollment->final_price ? 'Target Harga Final (Fix)' : 'Target Estimasi (Awal)' }}
                    </p>
                    <p class="font-black text-slate-800 text-[18px]">Rp {{ number_format($progress['total_harga'], 0, ',', '.') }}</p>
                </div>
            </div>
            
            <!-- Dokumen Checklist -->
            <div class="card-admin p-5 sm:p-6">
                <h4 class="text-[14px] font-black text-slate-800 border-b border-slate-100 pb-3 mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-blue-500 text-[18px]">verified_user</span> Kelengkapan Dokumen
                </h4>
                <div class="space-y-2">
                    @foreach(['ktp'=>'KTP', 'kk'=>'Kartu Keluarga (KK)', 'passport'=>'Paspor', 'photo'=>'Pas Foto', 'other'=>'Meningitis/Vaksin'] as $key => $label)
                        @php $doc = $enrollment->documents->where('document_type', $key)->first(); @endphp
                        <div class="flex justify-between items-center bg-slate-50 px-3 py-2.5 rounded-lg border border-slate-100">
                            <span class="text-[12px] font-bold text-slate-700 flex items-center gap-1.5">
                                @if($doc && $doc->status === 'approved')
                                    <span class="material-symbols-outlined text-[16px] text-emerald-500 [font-variation-settings:'FILL'_1]">check_circle</span>
                                @else
                                    <span class="material-symbols-outlined text-[16px] text-slate-300">radio_button_unchecked</span>
                                @endif
                                {{ $label }}
                            </span>
                            
                            @if(!$doc)
                                <span class="text-[9px] bg-slate-200 text-slate-500 px-2 py-0.5 rounded font-black uppercase tracking-widest border border-slate-300">KOSONG</span>
                            @elseif($doc->status === 'approved')
                                <a href="{{ route('admin.documents.preview', $doc->id) ?? asset('storage/' . $doc->file_path) }}" target="_blank" class="text-[9px] bg-emerald-100 text-emerald-700 border border-emerald-200 px-2 py-0.5 rounded font-black uppercase tracking-widest hover:bg-emerald-500 hover:text-white transition-colors">SAH (LIHAT)</a>
                            @elseif($doc->status === 'submitted')
                                <span class="text-[9px] bg-amber-100 text-amber-700 border border-amber-200 px-2 py-0.5 rounded font-black uppercase tracking-widest animate-pulse">MENUNGGU</span>
                            @else
                                <span class="text-[9px] bg-rose-100 text-rose-600 border border-rose-200 px-2 py-0.5 rounded font-black uppercase tracking-widest">DITOLAK</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- KOLOM KANAN: Keuangan & Transaksi -->
        <!-- ========================================== -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Progres Tabungan (Big Card) -->
            <div class="card-admin p-6 sm:p-8 relative overflow-hidden">
                <div class="absolute -right-4 -top-4 w-32 h-32 bg-emerald-50 rounded-full blur-3xl pointer-events-none"></div>
                
                <h4 class="text-[14px] font-black text-slate-800 mb-6 flex items-center gap-2 relative z-10">
                    <span class="material-symbols-outlined text-emerald-500 text-[20px]">account_balance_wallet</span> Status Keuangan
                </h4>
                
                <div class="grid grid-cols-2 gap-4 mb-6 relative z-10">
                    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
                        <p class="text-[10px] text-slate-400 uppercase tracking-widest font-black mb-1">Total Terbayar</p>
                        <p class="text-[20px] font-black text-emerald-600">Rp {{ number_format($progress['total_dibayar'], 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-center">
                        <p class="text-[10px] text-slate-400 uppercase tracking-widest font-black mb-1">Sisa Tagihan</p>
                        @if($progress['sisa_tagihan'] > 0)
                            <p class="text-[20px] font-black text-rose-500">Rp {{ number_format($progress['sisa_tagihan'], 0, ',', '.') }}</p>
                        @else
                            <div class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-600 border border-emerald-200 px-3 py-1.5 rounded-lg w-fit">
                                <span class="material-symbols-outlined text-[18px]">verified</span>
                                <span class="text-[12px] font-black uppercase tracking-widest">LUNAS Penuh</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="relative z-10">
                    <div class="flex justify-between items-end mb-2">
                        <p class="text-[12px] font-bold text-slate-700">Progres Pembayaran</p>
                        <p class="text-[14px] font-black text-hanania-purple">{{ $progress['persentase'] }}% Tercapai</p>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden border border-slate-200 shadow-inner">
                        <div class="h-3 rounded-full transition-all duration-1000 ease-out {{ $progress['persentase'] >= 100 ? 'bg-emerald-500' : 'bg-hanania-purple' }}" style="width: {{ $progress['persentase'] }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Riwayat Transaksi -->
            <div class="card-admin overflow-hidden flex flex-col">
                <div class="p-5 sm:p-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                    <h4 class="text-[14px] font-black text-slate-800 flex items-center gap-2">
                        <span class="material-symbols-outlined text-slate-400 text-[20px]">receipt_long</span> Riwayat Setoran
                    </h4>
                </div>
                
                <div class="overflow-x-auto no-scrollbar flex-1">
                    <table class="w-full text-left text-sm border-collapse whitespace-nowrap">
                        <thead>
                            <tr class="bg-white border-b border-slate-200 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                <th class="p-4">Tanggal</th>
                                <th class="p-4">Jenis</th>
                                <th class="p-4 text-center">Metode</th>
                                <th class="p-4 text-right">Nominal</th>
                                <th class="p-4 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-600">
                            @forelse($enrollment->paymentPlan->transactions as $trx)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="p-4 align-middle">
                                        <p class="text-[12px] font-bold text-slate-800">{{ $trx->created_at->format('d M Y') }}</p>
                                        <p class="text-[10px] font-medium text-slate-400">{{ $trx->created_at->format('H:i') }}</p>
                                    </td>
                                    <td class="p-4 align-middle">
                                        <span class="text-[11px] font-bold text-slate-700 bg-slate-100 px-2 py-1 rounded border border-slate-200">
                                            {{ ucfirst(str_replace('_', ' ', $trx->type)) }}
                                        </span>
                                    </td>
                                    <td class="p-4 align-middle text-center">
                                        <span class="text-[10px] font-mono font-black text-slate-500 uppercase tracking-wider">
                                            {{ str_replace('_', ' ', $trx->payment_method) }}
                                        </span>
                                    </td>
                                    <td class="p-4 align-middle text-right">
                                        <p class="font-black text-[13px] text-emerald-600">Rp {{ number_format($trx->amount, 0, ',', '.') }}</p>
                                    </td>
                                    <td class="p-4 align-middle text-center">
                                        @if($trx->status === 'verified')
                                            <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 px-2 py-1 rounded text-[9px] font-black uppercase tracking-widest border border-emerald-200">
                                                <span class="material-symbols-outlined text-[12px]">check</span> Sah
                                            </span>
                                        @elseif($trx->status === 'pending')
                                            <span class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 px-2 py-1 rounded text-[9px] font-black uppercase tracking-widest border border-amber-200 animate-pulse">
                                                <span class="material-symbols-outlined text-[12px]">schedule</span> Pending
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 bg-rose-50 text-rose-600 px-2 py-1 rounded text-[9px] font-black uppercase tracking-widest border border-rose-200">
                                                <span class="material-symbols-outlined text-[12px]">close</span> {{ $trx->status }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-12 text-center">
                                        <div class="flex flex-col items-center justify-center text-slate-400">
                                            <span class="material-symbols-outlined text-[32px] mb-2 opacity-50">money_off</span>
                                            <p class="text-[13px] font-bold text-slate-500">Belum Ada Transaksi</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection