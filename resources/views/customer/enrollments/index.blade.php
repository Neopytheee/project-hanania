@extends('layouts.customer.app')

@section('title', 'Tabungan')

@section('content')
    @php
        $companyName = \App\Models\AppInformation::getValue('company_name', 'Hanania');
        $shortCompanyName = explode(' ', $companyName)[0];
    @endphp

    <style>
        @keyframes shimmer {
            0% { transform: translateX(-100%) skewX(-15deg); }
            100% { transform: translateX(200%) skewX(-15deg); }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-shimmer { animation: shimmer 2.5s infinite linear; }
        .animate-fade-in-up { animation: fadeInUp .5s ease-out forwards; }
        .tabungan-lift { transition: transform .28s ease, box-shadow .28s ease, border-color .28s ease; }
        .tabungan-lift:hover { transform: translateY(-3px); }
        .hide-scroll::-webkit-scrollbar { display: none; }
        .hide-scroll { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

    <div class="w-full min-h-screen bg-slate-50/50 pt-24 pb-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 animate-fade-in-up">

            <!-- HEADER -->
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-5 mb-7">
                <div>
                    <p class="text-[9px] uppercase tracking-[.2em] font-black text-hanania-purple">Portfolio Anda</p>
                    <h1 class="font-heading text-[30px] sm:text-[38px] font-black text-hanania-purple-dark tracking-tight mt-1">Tabungan Perjalanan</h1>
                    <p class="text-[13px] text-gray-500 mt-2 font-medium">Pantau setiap niat baik dan perjalanan Anda bersama {{ $shortCompanyName }}.</p>
                </div>
                <a href="{{ route('packages.index') }}" class="inline-flex items-center justify-center gap-2 bg-hanania-purple text-white px-5 py-3 rounded-xl text-[11px] font-black shadow-sm hover:bg-hanania-purple-dark transition-colors">
                    <span class="material-symbols-outlined text-[17px]">add</span>
                    Buka Rekening Baru
                </a>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-hanania-purple-light/35 text-hanania-purple-dark p-4 rounded-2xl text-[12px] font-bold border border-hanania-purple/10 flex items-center gap-3">
                    <span class="material-symbols-outlined text-hanania-gold">check_circle</span>
                    {{ session('success') }}
                </div>
            @endif

            @if($enrollments->isEmpty())
                <!-- EMPTY -->
                <section class="bg-white rounded-[2rem] p-8 sm:p-12 text-center border border-hanania-purple/10 shadow-lg relative overflow-hidden">
                    <div class="absolute right-[-40px] top-[-40px] w-36 h-36 rounded-full border-[20px] border-hanania-purple-light/30"></div>
                    <div class="relative z-10 max-w-lg mx-auto">
                        <div class="w-16 h-16 bg-hanania-purple-light/45 rounded-2xl flex items-center justify-center mx-auto mb-5 text-hanania-purple">
                            <span class="material-symbols-outlined text-[34px]">account_balance_wallet</span>
                        </div>
                        <p class="text-[9px] uppercase tracking-[.18em] font-black text-hanania-purple">Belum Ada Perjalanan</p>
                        <h2 class="font-heading text-[25px] font-black text-hanania-purple-dark mt-2">Mari mulai niat baik Anda</h2>
                        <p class="text-[13px] text-gray-500 mt-2 leading-relaxed">Belum ada rekening tabungan atau pendaftaran paket. Pilih perjalanan yang paling sesuai dengan rencana Anda.</p>
                        <a href="{{ route('packages.index') }}" class="inline-flex items-center gap-2 mt-6 btn-hanania-gold px-6 py-3.5 rounded-xl text-[12px] font-black">
                            Lihat Paket Umroh <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                        </a>
                    </div>
                </section>
            @else
                @php
                    $totalLunas = $enrollments->filter(fn($e) => $e->progress['persentase'] >= 100)->count();
                    $totalProses = $enrollments->count() - $totalLunas;
                    $featuredEnrollment = $enrollments->sortByDesc(fn($e) => $e->progress['persentase'])->first();
                @endphp

                <!-- SUMMARY -->
                <section class="grid lg:grid-cols-[1.25fr_.75fr] gap-5 mb-7">
                    <div class="relative overflow-hidden rounded-[2rem] bg-hanania-purple-dark text-white p-6 sm:p-8 shadow-xl">
                        <div class="absolute right-[-40px] bottom-[-55px] w-48 h-48 rounded-full border-[25px] border-hanania-purple-light/10"></div>
                        <div class="relative z-10">
                            <div class="flex items-center justify-between gap-4 mb-7">
                                <div>
                                    <p class="text-[9px] uppercase tracking-[.2em] font-black text-hanania-purple-light">Ringkasan Tabungan</p>
                                    <h2 class="font-heading text-[23px] sm:text-[27px] font-black mt-1">Perjalanan Paling Aktif</h2>
                                </div>
                                <span class="material-symbols-outlined text-hanania-gold text-[26px]">insights</span>
                            </div>

                            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-5">
                                <div class="min-w-0">
                                    <p class="text-[9px] uppercase tracking-[.16em] text-white/40 font-black">{{ $featuredEnrollment->status === 'saving' ? 'Sedang Menabung' : strtoupper($featuredEnrollment->status) }}</p>
                                    <p class="font-heading text-[19px] sm:text-[22px] font-black mt-1 line-clamp-2">{{ $featuredEnrollment->travelPackage->name }}</p>
                                    <p class="text-[11px] text-white/45 mt-1">Atas Nama {{ $featuredEnrollment->passenger_name }}</p>
                                </div>
                                <div class="shrink-0 sm:text-right">
                                    <p class="font-heading text-[34px] sm:text-[42px] font-black text-hanania-gold leading-none">{{ $featuredEnrollment->progress['persentase'] }}%</p>
                                    <p class="text-[9px] uppercase tracking-[.16em] text-white/40 mt-1">Progress</p>
                                </div>
                            </div>

                            <div class="mt-6 h-2.5 bg-white/10 rounded-full overflow-hidden">
                                <div class="h-full rounded-full bg-gradient-to-r from-hanania-gold-light to-hanania-gold relative" style="width: {{ $featuredEnrollment->progress['persentase'] }}%">
                                    @if($featuredEnrollment->progress['persentase'] < 100)
                                        <div class="absolute inset-0 bg-white/25 animate-shimmer"></div>
                                    @endif
                                </div>
                            </div>

                            <a href="{{ route('customer.enrollments.show', $featuredEnrollment->id) }}" class="mt-5 inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-[.14em] text-white/75 hover:text-hanania-gold transition-colors">
                                Lanjutkan Perjalanan <span class="material-symbols-outlined text-[15px]">arrow_forward</span>
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 lg:grid-cols-1 gap-3">
                        <div class="bg-white border border-hanania-purple/10 rounded-2xl p-4 shadow-sm flex flex-col justify-center">
                            <p class="text-[8px] uppercase tracking-[.16em] text-gray-400 font-black">Total Rekening</p>
                            <p class="font-heading text-[26px] font-black text-hanania-purple-dark mt-1">{{ $enrollments->count() }}</p>
                        </div>
                        <div class="bg-hanania-purple-light/35 border border-hanania-purple/10 rounded-2xl p-4 shadow-sm flex flex-col justify-center">
                            <p class="text-[8px] uppercase tracking-[.16em] text-hanania-purple/60 font-black">Sedang Menabung</p>
                            <p class="font-heading text-[26px] font-black text-hanania-purple-dark mt-1">{{ $totalProses }}</p>
                        </div>
                        <div class="bg-hanania-gold/10 border border-hanania-gold/20 rounded-2xl p-4 shadow-sm flex flex-col justify-center">
                            <p class="text-[8px] uppercase tracking-[.16em] text-hanania-gold font-black">Lunas / Berangkat</p>
                            <p class="font-heading text-[26px] font-black text-hanania-purple-dark mt-1">{{ $totalLunas }}</p>
                        </div>
                    </div>
                </section>

                <!-- LIST -->
                <section>
                    <div class="flex items-end justify-between gap-4 mb-4">
                        <div>
                            <p class="text-[9px] uppercase tracking-[.18em] font-black text-hanania-purple">Semua Rekening</p>
                            <h2 class="font-heading text-[24px] sm:text-[28px] font-black text-hanania-purple-dark mt-1">Perjalanan Anda</h2>
                        </div>
                    </div>

                    <div class="space-y-3">
                        @foreach($enrollments as $enrollment)
                            @php
                                $isLunas = $enrollment->progress['persentase'] >= 100;
                                $statusLabel = $isLunas ? 'LUNAS' : ($enrollment->status === 'saving' ? 'MENABUNG' : strtoupper($enrollment->status));
                                $statusBg = $isLunas ? 'bg-hanania-gold/15 text-hanania-purple-dark border-hanania-gold/20' : 'bg-hanania-purple-light/45 text-hanania-purple border-hanania-purple/10';
                            @endphp

                            <a href="{{ route('customer.enrollments.show', $enrollment->id) }}" class="group block bg-white border border-hanania-purple/10 rounded-[1.6rem] p-4 sm:p-5 shadow-sm tabungan-lift">
                                <div class="grid lg:grid-cols-[1fr_auto] gap-5 items-center">
                                    <div class="min-w-0">
                                        <div class="flex flex-wrap items-center gap-2 mb-2">
                                            <span class="text-[8px] font-mono font-black uppercase tracking-[.15em] text-gray-400">ID {{ $enrollment->enrollment_number }}</span>
                                            <span class="text-[8px] px-2.5 py-1 rounded-full border font-black uppercase tracking-[.13em] {{ $statusBg }}">{{ $statusLabel }}</span>
                                        </div>
                                        <h3 class="font-heading text-[18px] sm:text-[21px] font-black text-hanania-purple-dark line-clamp-2 group-hover:text-hanania-purple transition-colors">{{ $enrollment->travelPackage->name }}</h3>
                                        <p class="text-[10px] sm:text-[11px] text-gray-400 mt-1">Atas Nama <span class="font-bold text-gray-600">{{ $enrollment->passenger_name }}</span></p>

                                        <div class="mt-4 flex items-center gap-3">
                                            <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                                                <div class="h-full rounded-full bg-gradient-to-r from-hanania-gold-light to-hanania-gold relative" style="width: {{ $enrollment->progress['persentase'] }}%">
                                                    @if(!$isLunas)<div class="absolute inset-0 bg-white/25 animate-shimmer"></div>@endif
                                                </div>
                                            </div>
                                            <span class="font-heading text-[15px] font-black text-hanania-purple-dark shrink-0">{{ $enrollment->progress['persentase'] }}%</span>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between lg:justify-end gap-6 border-t lg:border-t-0 border-gray-100 pt-4 lg:pt-0">
                                        <div class="lg:text-right">
                                            <p class="text-[8px] uppercase tracking-[.14em] text-gray-400 font-black">Dibuka</p>
                                            <p class="text-[10px] font-bold text-gray-600 mt-1">{{ \Carbon\Carbon::parse($enrollment->created_at)->format('d M Y') }}</p>
                                        </div>
                                        <span class="w-10 h-10 rounded-full bg-hanania-purple-light/45 text-hanania-purple flex items-center justify-center group-hover:bg-hanania-purple group-hover:text-white transition-colors shrink-0">
                                            <span class="material-symbols-outlined text-[17px]">arrow_forward</span>
                                        </span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </section>

                <div class="mt-5 flex justify-center">
                    <a href="{{ route('packages.index') }}" class="inline-flex items-center gap-2 text-[10px] uppercase tracking-[.15em] font-black text-hanania-purple hover:text-hanania-gold transition-colors">
                        <span class="material-symbols-outlined text-[15px]">add</span>
                        Tambah Rekening Baru
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
