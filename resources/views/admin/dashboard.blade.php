@extends('layouts.admin.app')

@section('header_title', 'Dashboard Utama')

@section('content')
@php
    // Tarik nama perusahaan dari database
    $companyName = \App\Models\AppInformation::getValue('company_name', 'Hanania Travel');
@endphp

<div class="animate-fade-in-up">
    <!-- ========================================== -->
    <!-- HEADER DASHBOARD -->
    <!-- ========================================== -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h2 class="text-[24px] lg:text-[28px] font-black text-slate-900 tracking-tight leading-none mb-1">Ringkasan Operasional</h2>
            <p class="text-[13px] text-slate-500 font-medium">Pantau aktivitas pendaftaran dan pembayaran {{ $companyName }} hari ini.</p>
        </div>
        <div class="bg-white border border-slate-200 px-4 py-2 rounded-xl shadow-sm flex items-center gap-2 shrink-0">
            <span class="material-symbols-outlined text-[18px] text-hanania-purple">calendar_month</span>
            <p class="text-[13px] font-bold text-slate-700">{{ now()->format('l, d F Y') }}</p>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- ALERT PRIORITAS (Muncul Jika Ada Tugas Pending) -->
    <!-- ========================================== -->
    @php
        $totalPendingWork = $pendingPayments + $pendingDocuments + ($pendingCancellations ?? 0) + ($pendingTestimonials ?? 0);
    @endphp

    @if($totalPendingWork > 0)
        <div class="bg-rose-50 border border-rose-200 p-5 lg:p-6 rounded-[20px] mb-8 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-5 shadow-sm relative overflow-hidden">
            <!-- Aksen Background Latar -->
            <div class="absolute top-0 right-0 w-40 h-40 bg-rose-100/50 rounded-full blur-3xl -translate-y-1/2 translate-x-1/4 pointer-events-none"></div>

            <div class="flex items-start sm:items-center gap-4 relative z-10">
                <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center border-2 border-rose-100 shrink-0 shadow-sm">
                    <span class="material-symbols-outlined text-[24px] text-rose-500 [font-variation-settings:'FILL'_1]">warning</span>
                </div>
                <div>
                    <h4 class="font-extrabold text-rose-900 text-[15px] mb-1">Perhatian! Ada Tugas Menunggu</h4>
                    <div class="text-[12px] text-rose-700 font-medium flex flex-wrap gap-x-3 gap-y-1">
                        @if($pendingPayments > 0) <span class="flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> <b>{{ $pendingPayments }}</b> Pembayaran</span> @endif
                        @if($pendingDocuments > 0) <span class="flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> <b>{{ $pendingDocuments }}</b> Dokumen</span> @endif
                        @if(($pendingCancellations ?? 0) > 0) <span class="flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> <b>{{ $pendingCancellations }}</b> Refund</span> @endif
                        @if(($pendingTestimonials ?? 0) > 0) <span class="flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> <b>{{ $pendingTestimonials }}</b> Testimoni</span> @endif
                    </div>
                </div>
            </div>
            
            <!-- Tombol Pintas Aksi -->
            <div class="flex flex-wrap gap-2 relative z-10">
                @if($pendingPayments > 0)
                    <a href="{{ route('admin.payments.index') }}" class="bg-rose-600 hover:bg-rose-700 text-white text-[12px] font-bold px-4 py-2.5 rounded-xl shadow-sm transition-colors flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px]">receipt_long</span> Cek Pembayaran
                    </a>
                @endif
                @if($pendingDocuments > 0)
                    <a href="{{ route('admin.documents.index') }}" class="bg-orange-500 hover:bg-orange-600 text-white text-[12px] font-bold px-4 py-2.5 rounded-xl shadow-sm transition-colors flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px]">fact_check</span> Cek Dokumen
                    </a>
                @endif
            </div>
        </div>
    @endif

    <!-- ========================================== -->
    <!-- ROW 1: 4 KARTU STATISTIK UTAMA -->
    <!-- ========================================== -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <!-- Card 1: Revenue -->
        <div class="card-admin p-5 sm:p-6 relative overflow-hidden group">
            <div class="flex items-center gap-4 mb-3 relative z-10">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100 transition-transform group-hover:scale-110">
                    <span class="material-symbols-outlined text-[20px] [font-variation-settings:'FILL'_1]">account_balance_wallet</span>
                </div>
                <p class="text-[10px] text-slate-400 font-extrabold uppercase tracking-widest">Total Dana Masuk</p>
            </div>
            <p class="text-[24px] lg:text-[28px] font-black text-slate-900 tracking-tight relative z-10">
                Rp {{ number_format($totalRevenue / 1000000, 1, ',', '.') }} <span class="text-[14px] text-slate-400 font-bold">Juta</span>
            </p>
        </div>

        <!-- Card 2: Jamaah Lunas -->
        <div class="card-admin p-5 sm:p-6 relative overflow-hidden group">
            <div class="flex items-center gap-4 mb-3 relative z-10">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-100 transition-transform group-hover:scale-110">
                    <span class="material-symbols-outlined text-[20px] [font-variation-settings:'FILL'_1]">flight_takeoff</span>
                </div>
                <p class="text-[10px] text-slate-400 font-extrabold uppercase tracking-widest">Siap Terbang (Lunas)</p>
            </div>
            <p class="text-[24px] lg:text-[28px] font-black text-slate-900 tracking-tight relative z-10">
                {{ $jamaahLunas }} <span class="text-[14px] text-slate-400 font-bold">Orang</span>
            </p>
        </div>

        <!-- Card 3: Aktif Menabung -->
        <div class="card-admin p-5 sm:p-6 relative overflow-hidden group">
            <div class="flex items-center gap-4 mb-3 relative z-10">
                <div class="w-10 h-10 rounded-xl bg-hanania-purple/10 text-hanania-purple flex items-center justify-center border border-hanania-purple/20 transition-transform group-hover:scale-110">
                    <span class="material-symbols-outlined text-[20px] [font-variation-settings:'FILL'_1]">hourglass_bottom</span>
                </div>
                <p class="text-[10px] text-slate-400 font-extrabold uppercase tracking-widest">Aktif Menabung</p>
            </div>
            <p class="text-[24px] lg:text-[28px] font-black text-slate-900 tracking-tight relative z-10">
                {{ $activeSaving }} <span class="text-[14px] text-slate-400 font-bold">Orang</span>
            </p>
        </div>

        <!-- Card 4: Total Customer -->
        <div class="card-admin p-5 sm:p-6 relative overflow-hidden group">
            <div class="flex items-center gap-4 mb-3 relative z-10">
                <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center border border-slate-200 transition-transform group-hover:scale-110">
                    <span class="material-symbols-outlined text-[20px] [font-variation-settings:'FILL'_1]">groups</span>
                </div>
                <p class="text-[10px] text-slate-400 font-extrabold uppercase tracking-widest">Total Database</p>
            </div>
            <p class="text-[24px] lg:text-[28px] font-black text-slate-900 tracking-tight relative z-10">
                {{ $totalCustomers }} <span class="text-[14px] text-slate-400 font-bold">Akun</span>
            </p>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- ROW 2: 2 TABEL (KIRI KANAN) -->
    <!-- ========================================== -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 pb-10">
        
        <!-- KIRI: Pendaftar Terbaru -->
        <div class="card-admin overflow-hidden flex flex-col h-full">
            <div class="px-5 sm:px-6 py-4 flex justify-between items-center border-b border-slate-100 bg-slate-50/50">
                <h3 class="font-bold text-[15px] text-slate-800 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px] text-hanania-purple">person_add</span> Pendaftar Terbaru
                </h3>
                <a href="{{ route('admin.enrollments.index') }}" class="text-[11px] font-bold text-hanania-purple hover:text-hanania-purple-dark transition-colors flex items-center gap-1">
                    Lihat Semua <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                </a>
            </div>
            <div class="p-0 flex-1">
                @forelse($recentEnrollments as $enr)
                    <div class="px-5 sm:px-6 py-4 flex justify-between items-center border-b border-slate-50 last:border-0 hover:bg-slate-50/80 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-hanania-purple/10 text-hanania-purple flex items-center justify-center font-bold text-[14px] border border-hanania-purple/20 shrink-0">
                                {{ strtoupper(substr($enr->passenger_name ?? $enr->customer->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-[13px] font-bold text-slate-800 line-clamp-1">{{ $enr->passenger_name ?? $enr->customer->name }}</p>
                                <p class="text-[11px] font-medium text-slate-500">{{ $enr->travelPackage->name }}</p>
                            </div>
                        </div>
                        <div class="text-right shrink-0">
                            <span class="inline-flex items-center px-2 py-1 rounded-md text-[9px] font-bold uppercase tracking-wide border {{ $enr->status === 'funds_sufficient' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-amber-50 text-amber-700 border-amber-200' }}">
                                {{ $enr->statusText() }}
                            </span>
                            <p class="text-[10px] font-medium text-slate-400 mt-1.5">{{ $enr->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center flex flex-col items-center justify-center h-full">
                        <span class="material-symbols-outlined text-[40px] text-slate-200 mb-2">inbox</span>
                        <p class="text-[13px] text-slate-400 font-medium">Belum ada pendaftaran baru.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- KANAN: Jadwal Keberangkatan -->
        <div class="card-admin overflow-hidden flex flex-col h-full">
            <div class="px-5 sm:px-6 py-4 flex justify-between items-center border-b border-slate-100 bg-slate-50/50">
                <h3 class="font-bold text-[15px] text-slate-800 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px] text-hanania-purple">airplane_ticket</span> Jadwal Terdekat
                </h3>
                <a href="{{ route('admin.departures.index') }}" class="text-[11px] font-bold text-hanania-purple hover:text-hanania-purple-dark transition-colors flex items-center gap-1">
                    Kelola Jadwal <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                </a>
            </div>
            <div class="p-0 flex-1">
                @forelse($upcomingDepartures as $dep)
                    <div class="px-5 sm:px-6 py-4 flex justify-between items-center border-b border-slate-50 last:border-0 hover:bg-slate-50/80 transition-colors">
                        <div>
                            <p class="text-[13px] font-bold text-slate-800">{{ $dep->name }}</p>
                            <p class="text-[11px] font-medium text-slate-500">{{ $dep->travelPackage->name }}</p>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="text-[13px] font-black text-slate-700 mb-1">
                                {{ \Carbon\Carbon::parse($dep->departure_date)->format('d M Y') }}
                            </p>
                            <a href="{{ route('admin.departures.groups.index', $dep->id) }}" class="inline-flex items-center gap-1 text-[10px] bg-slate-100 text-slate-600 px-2.5 py-1 rounded-md font-bold hover:bg-slate-200 transition-colors border border-slate-200">
                                <span class="material-symbols-outlined text-[12px]">groups</span> Atur Rombongan
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center flex flex-col items-center justify-center h-full">
                        <span class="material-symbols-outlined text-[40px] text-slate-200 mb-2">event_busy</span>
                        <p class="text-[13px] text-slate-400 font-medium">Belum ada jadwal keberangkatan.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection