@extends('layouts.customer.app')

@section('title', 'Dashboard')

@section('content')
    <style>
        @keyframes floatAnim {
            0%, 100% { transform: translateY(0) rotate(2deg); }
            50% { transform: translateY(-7px) rotate(0deg); }
        }

        .float-anim { animation: floatAnim 4.5s ease-in-out infinite; }
        .hide-scroll::-webkit-scrollbar { display: none; }
        .hide-scroll { -ms-overflow-style: none; scrollbar-width: none; }

        .soft-lift {
            transition: transform .3s ease, box-shadow .3s ease, border-color .3s ease;
        }

        .soft-lift:hover {
            transform: translateY(-4px);
        }

        @media (max-width: 639px) {
            .mobile-tight { letter-spacing: -.015em; }
            .mobile-compact-card {
                min-height: 0 !important;
            }
            .mobile-scroll-row > * {
                scroll-snap-align: start;
            }
        }
    </style>

    <div class="pt-24 pb-10 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @php
                date_default_timezone_set('Asia/Jakarta');
                $hour = date('H');

                $companyName = \App\Models\AppInformation::getValue('company_name', 'Hanania');
                $shortCompanyName = explode(' ', $companyName)[0];

                if ($hour < 11) {
                    $waktu = 'Pagi'; $icon = 'routine';
                } elseif ($hour < 15) {
                    $waktu = 'Siang'; $icon = 'light_mode';
                } elseif ($hour < 18) {
                    $waktu = 'Sore'; $icon = 'wb_twilight';
                } else {
                    $waktu = 'Malam'; $icon = 'dark_mode';
                }

                $rawName = optional(auth()->user()->customer)->name ?? auth()->user()->name ?? '';
                $fullName = trim($rawName) !== '' ? trim($rawName) : 'Sahabat ' . $shortCompanyName; 
                $namaPanggilan = explode(' ', $fullName)[0];
            @endphp

            <!-- HERO / EDITORIAL INTRO -->
            <section class="relative mb-10 overflow-hidden rounded-[2rem] bg-hanania-purple-dark text-white border border-hanania-purple/20">
                <div class="absolute right-0 top-0 w-1/2 h-full bg-hanania-purple/20"></div>
                <div class="absolute -right-24 -top-24 w-80 h-80 rounded-full border-[40px] border-hanania-purple-light/10"></div>
                <div class="absolute -right-5 bottom-[-90px] w-64 h-64 rounded-full border-[1px] border-hanania-gold/20"></div>
                <div class="absolute left-1/2 bottom-0 h-px w-40 bg-hanania-gold/30"></div>

                <div class="relative z-10 grid lg:grid-cols-[1.25fr_.75fr]">
                    <div class="p-5 sm:p-10 lg:p-12 flex flex-col justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-full bg-white/10 border border-white/10 flex items-center justify-center">
                                <span class="material-symbols-outlined text-[20px] [font-variation-settings:'FILL'_1]">{{ $icon }}</span>
                            </div>
                            <div>
                                <p class="text-[9px] font-black uppercase tracking-[.23em] text-hanania-purple-light">Eksklusif {{ $shortCompanyName }}</p>
                                <p class="text-[10px] text-white/45 mt-1">A companion for your sacred journey</p>
                            </div>
                        </div>

                        <div class="mt-7 sm:mt-12 lg:mt-0 max-w-2xl">
                            <p class="text-[12px] sm:text-[13px] text-white/60 mb-2">Assalamu'alaikum, Selamat {{ $waktu }}</p>
                            <h1 class="font-heading text-white/90 font-black text-[30px] sm:text-[56px] lg:text-[68px] tracking-[-.035em] leading-none mobile-tight">
                                {{ $namaPanggilan }}<span class="text-hanania-gold">.</span>
                            </h1>
                            <p class="mt-3 max-w-xl text-[11px] sm:text-[14px] leading-5 sm:leading-7 text-white/62">
                                Ahlan Wa Sahlan. Mari wujudkan niat suci menuju Baitullah bersama {{ $shortCompanyName }}.
                            </p>
                        </div>

                        <div class="flex items-center gap-3 pt-8">
                            <span class="w-8 h-px bg-hanania-gold"></span>
                            <span class="text-[9px] uppercase tracking-[.22em] text-white/45 font-bold">One intention · one journey</span>
                        </div>
                    </div>

                    <div class="relative min-h-[74px] sm:min-h-[250px] lg:min-h-0 bg-hanania-purple/35 border-t lg:border-t-0 lg:border-l border-white/10">
                        <div class="absolute inset-0 flex items-center justify-between lg:items-end lg:justify-end p-5 sm:p-10">
                            <div class="flex items-center gap-3 lg:block lg:text-right">
                                <p class="text-[9px] uppercase tracking-[.22em] text-white/35 font-black">Today</p>
                                <p class="font-heading text-[26px] sm:text-[40px] font-black leading-none text-hanania-gold lg:mt-2">{{ date('d') }}</p>
                                <p class="text-[10px] uppercase tracking-[.16em] text-white/55 font-bold">{{ date('M Y') }}</p>
                            </div>
                        </div>

                        <div class="absolute left-5 top-4 sm:left-10 sm:top-10 lg:hidden">
                            <div class="w-11 h-11 rounded-xl bg-white/8 border border-white/10 p-0.5">
                                <img
                                    alt="foto_profil"
                                    class="w-full h-full rounded-[.7rem] object-cover bg-white/10"
                                    src="{{ auth()->user()->foto_profil ? asset('storage/' . auth()->user()->foto_profil) : 'https://ui-avatars.com/api/?name='.urlencode($namaPanggilan).'&background=61398F&color=ffffff&bold=true' }}">
                            </div>
                        </div>

                        <div class="absolute right-8 top-8 sm:right-10 sm:top-10 w-2 h-2 rounded-full bg-hanania-gold animate-pulse"></div>
                    </div>
                </div>
            </section>

            <!-- ACTIVE JOURNEY -->
            @if(isset($activeEnrollments) && $activeEnrollments->count() > 0)
                <section class="mb-8">
                    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-2 mb-4">
                        <div class="editorial-line text-hanania-purple">
                            <span class="text-[9px] font-black uppercase tracking-[.18em]">Perjalanan Anda</span>
                            <h2 class="font-heading text-[24px] sm:text-[28px] font-black text-hanania-purple-dark mt-1">Sedang Berlangsung</h2>
                        </div>
                        <p class="text-[11px] font-medium text-gray-400 max-w-xs sm:text-right">
                            Satu langkah hari ini membawa Anda lebih dekat menuju Baitullah.
                        </p>
                    </div>
                    <div class="flex overflow-x-auto gap-4 pb-2 hide-scroll snap-x snap-mandatory">
                        @foreach($activeEnrollments as $enrollment)
                            <a href="{{ route('customer.enrollments.show', $enrollment->id) }}" class="relative block shrink-0 snap-center w-[88vw] sm:w-[500px] lg:w-[560px] rounded-[1.35rem] bg-white border border-hanania-purple/10 shadow-lg luxury-hover group">
                                <div class="absolute top-0 left-0 w-full h-1 bg-hanania-purple rounded-t-[1.6rem]"></div>
                                <div class="p-4 sm:p-6">
                                    <div class="flex items-center justify-between gap-3 mb-3">
                                        <span class="inline-flex items-center gap-2 rounded-full bg-hanania-gold text-white px-3 py-1.5 text-[9px] font-black uppercase tracking-[.15em]">
                                            <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                                            {{ $enrollment->statusText() ?? 'AKTIF' }}
                                        </span>
                                        <span class="inline-flex items-center gap-1 text-[9px] font-black uppercase tracking-[.14em] text-hanania-purple">
                                            Detail <span class="material-symbols-outlined text-[15px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
                                        </span>
                                    </div>

                                    <p class="text-[8px] text-gray-400 font-black uppercase tracking-[.18em]">Program</p>
                                    <h3 class="font-heading font-black text-[17px] sm:text-[25px] leading-tight text-hanania-purple-dark mt-1 line-clamp-2 group-hover:text-hanania-purple transition-colors">
                                        {{ $enrollment->travelPackage->name }}
                                    </h3>

                                    <div class="grid grid-cols-2 gap-2 mt-3">
                                        <span class="inline-flex items-center gap-1.5 rounded-xl bg-hanania-purple-light/30 border border-hanania-purple/10 px-2.5 py-2 text-[9px] sm:text-[10px] font-bold text-hanania-purple-dark">
                                            <span class="material-symbols-outlined text-[14px]">person</span>{{ $enrollment->passenger_name }}
                                        </span>
                                        <span class="inline-flex items-center gap-1.5 rounded-xl bg-gray-50 border border-gray-100 px-2.5 py-2 text-[9px] sm:text-[10px] font-mono font-bold text-gray-500">
                                            <span class="material-symbols-outlined text-[14px] text-hanania-purple/50">confirmation_number</span>{{ $enrollment->enrollment_number }}
                                        </span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </section>
            @else
                <!-- EMPTY JOURNEY -->
                <section class="mb-11 grid lg:grid-cols-[.9fr_1.1fr] overflow-hidden rounded-[2rem] bg-white border border-hanania-purple/10">
                    <div class="bg-hanania-purple-dark text-white p-7 sm:p-10 relative overflow-hidden">
                        <div class="absolute -right-16 -bottom-16 w-48 h-48 rounded-full border-[24px] border-hanania-purple-light/10"></div>
                        <div class="relative z-10">
                            <span class="text-[9px] font-black uppercase tracking-[.21em] text-hanania-purple-light">Begin Perjalanan Anda</span>
                            <h2 class="font-heading text-[29px] sm:text-[36px] font-black tracking-tight leading-tight mt-3">
                                Wujudkan Niat Suci ke Baitullah
                            </h2>
                            <p class="text-white/55 text-[12px] leading-relaxed mt-4 max-w-md">
                                Mulai langkah kebaikan Anda dengan sistem tabungan umroh yang ringan dan transparan bersama {{ $shortCompanyName }}.
                            </p>
                        </div>
                    </div>

                    <div class="p-7 sm:p-10 flex flex-col justify-center relative overflow-hidden">
                        <div class="absolute right-7 top-7 text-hanania-purple/10 float-anim">
                            <span class="material-symbols-outlined text-[70px] [font-variation-settings:'FILL'_1]">mosque</span>
                        </div>
                        <div class="relative z-10">
                            <p class="text-[9px] uppercase tracking-[.2em] text-hanania-purple font-black">Langkah Pertama</p>
                            <p class="font-heading text-[20px] sm:text-[24px] font-black text-hanania-purple-dark mt-2 max-w-md">
                                Temukan paket yang paling sesuai dengan niat dan rencana perjalanan Anda.
                            </p>
                            <a href="{{ route('packages.index') }}" class="inline-flex items-center gap-2 mt-6 bg-hanania-purple text-white px-6 py-3.5 rounded-xl text-[12px] font-black hover:bg-hanania-purple-dark transition-colors">
                                Lihat Katalog Umroh
                                <span class="material-symbols-outlined text-[17px]">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                </section>
            @endif

            <!-- EDITORIAL BENTO -->
            <div class="grid xl:grid-cols-[.85fr_1.15fr] gap-7 mb-11">

                <!-- SHALAT -->
                <section class="rounded-[2rem] bg-white border border-hanania-purple/10 p-6 sm:p-7">
                    <div class="flex items-start justify-between gap-4 mb-7">
                        <div>
                            <span class="text-[9px] font-black uppercase tracking-[.2em] text-hanania-purple">Daily Rhythm</span>
                            <h3 class="font-heading text-[22px] font-black text-hanania-purple-dark mt-1">Waktu Shalat</h3>
                        </div>
                        <div class="inline-flex items-center gap-1.5 bg-gray-50 border border-gray-100 rounded-full px-3 py-1.5">
                            <span class="material-symbols-outlined text-[13px] text-hanania-gold">location_on</span>
                            <span class="text-[9px] font-bold text-hanania-purple-dark">{{ \App\Models\AppInformation::getValue('default_location', 'Jakarta') }}</span>
                        </div>
                    </div>

                    <div class="space-y-2.5">
                        <div class="flex items-center justify-between px-3 py-3 rounded-2xl">
                            <span class="text-[10px] uppercase tracking-widest font-bold text-gray-400">Subuh</span>
                            <span class="text-[14px] font-black text-hanania-purple-dark">04:30</span>
                        </div>
                        <div class="flex items-center justify-between px-3 py-3.5 rounded-2xl bg-hanania-purple text-white shadow-lg">
                            <span class="text-[10px] uppercase tracking-widest font-black text-hanania-purple-light">Dzuhur</span>
                            <span class="text-[17px] font-black">11:55</span>
                        </div>
                        <div class="flex items-center justify-between px-3 py-3 rounded-2xl">
                            <span class="text-[10px] uppercase tracking-widest font-bold text-gray-400">Ashar</span>
                            <span class="text-[14px] font-black text-hanania-purple-dark">15:15</span>
                        </div>
                        <div class="flex items-center justify-between px-3 py-3 rounded-2xl">
                            <span class="text-[10px] uppercase tracking-widest font-bold text-gray-400">Maghrib</span>
                            <span class="text-[14px] font-black text-hanania-purple-dark">17:50</span>
                        </div>
                        <div class="flex items-center justify-between px-3 py-3 rounded-2xl">
                            <span class="text-[10px] uppercase tracking-widest font-bold text-gray-400">Isya</span>
                            <span class="text-[14px] font-black text-hanania-purple-dark">19:05</span>
                        </div>
                    </div>
                </section>

                <!-- BEKAL -->
                <section class="min-w-0">
                    <div class="flex items-end justify-between gap-4 mb-5">
                        <div>
                            <span class="text-[9px] font-black uppercase tracking-[.2em] text-hanania-purple">Prepare Well</span>
                            <h3 class="font-heading text-[22px] font-black text-hanania-purple-dark mt-1">Bekal Ibadah</h3>
                        </div>
                        <span class="hidden sm:block text-[10px] text-gray-400 font-medium">Materi ringkas untuk menemani persiapan</span>
                    </div>

                    <div class="grid grid-cols-3 gap-2.5">
                        <a href="#" class="group bg-hanania-purple-dark text-white rounded-[1.6rem] p-5 min-h-[185px] flex flex-col justify-between soft-lift">
                            <div class="w-10 h-10 rounded-full bg-white/10 border border-white/10 flex items-center justify-center">
                                <span class="material-symbols-outlined text-[20px] [font-variation-settings:'FILL'_1]">auto_stories</span>
                            </div>
                            <div>
                                <h4 class="font-heading text-[16px] font-black">Kumpulan Doa</h4>
                                <p class="text-[10px] text-white/50 leading-relaxed mt-1.5">Doa mustajab Tanah Suci</p>
                                <span class="material-symbols-outlined text-[16px] text-hanania-gold mt-4 group-hover:translate-x-1 transition-transform">arrow_forward</span>
                            </div>
                        </a>

                        <a href="#" class="group bg-hanania-purple-light/50 border border-hanania-purple/10 text-hanania-purple-dark rounded-[1.6rem] p-5 min-h-[185px] flex flex-col justify-between soft-lift">
                            <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center">
                                <span class="material-symbols-outlined text-[20px] [font-variation-settings:'FILL'_1]">directions_walk</span>
                            </div>
                            <div>
                                <h4 class="font-heading text-[16px] font-black">Tata Cara Sa'i</h4>
                                <p class="text-[10px] text-gray-500 leading-relaxed mt-1.5">Rukun dan panduan Sa'i</p>
                                <span class="material-symbols-outlined text-[16px] text-hanania-purple mt-4 group-hover:translate-x-1 transition-transform">arrow_forward</span>
                            </div>
                        </a>

                        <a href="#" class="group bg-white border border-hanania-purple/10 text-hanania-purple-dark rounded-[1.6rem] p-5 min-h-[185px] flex flex-col justify-between soft-lift">
                            <div class="w-10 h-10 rounded-full bg-hanania-purple-light/55 flex items-center justify-center text-hanania-purple">
                                <span class="material-symbols-outlined text-[20px] [font-variation-settings:'FILL'_1]">headphones</span>
                            </div>
                            <div>
                                <h4 class="font-heading text-[16px] font-black">Audio Manasik</h4>
                                <p class="text-[10px] text-gray-500 leading-relaxed mt-1.5">Dengarkan materi manasik</p>
                                <span class="material-symbols-outlined text-[16px] text-hanania-gold mt-4 group-hover:translate-x-1 transition-transform">arrow_forward</span>
                            </div>
                        </a>
                    </div>
                </section>
            </div>

            <!-- PACKAGE EDITORIAL -->
            <section class="mb-11">
                <div class="editorial-rule text-hanania-purple mb-7">
                    <span class="inline-flex items-center gap-3 bg-hanania-purple-light/30 pr-5">
                        <span class="font-heading font-black text-[24px] sm:text-[28px] text-hanania-purple-dark tracking-tight">Paket Pilihan</span>
                        <span class="text-[9px] uppercase tracking-[.2em] font-black text-hanania-purple">Curated for you</span>
                    </span>
                </div>

                <div class="space-y-4">
                    @foreach($travelPackages as $package)
                        <article class="group bg-white border border-hanania-purple/10 rounded-[1.8rem] p-3 sm:p-4 transition-all duration-300">
                            <div class="grid md:grid-cols-[220px_1fr_auto] gap-5 items-center">
                                <div class="h-40 md:h-36 rounded-[1.25rem] overflow-hidden bg-gray-50 relative">
                                    <img
                                        src="{{ $package->image ? asset('storage/' . $package->image) : 'https://images.unsplash.com/photo-1565552645632-d725f8bfc19a?q=80&w=600&auto=format&fit=crop' }}"
                                        alt="{{ $package->name }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                                    >
                                    <div class="absolute left-3 top-3">
                                        <span class="bg-white/90 text-hanania-purple-dark px-2.5 py-1 rounded-full text-[8px] font-black uppercase tracking-wider">
                                            Selected
                                        </span>
                                    </div>
                                </div>

                                <div class="min-w-0 px-1 md:px-0">
                                    <p class="text-[8px] uppercase tracking-[.2em] text-hanania-purple font-black mb-1">Hanania Package</p>
                                    <h4 class="font-heading font-black text-[20px] sm:text-[24px] text-hanania-purple-dark leading-tight line-clamp-2 group-hover:text-hanania-purple transition-colors">
                                        {{ $package->name }}
                                    </h4>

                                    <div class="flex flex-wrap items-center gap-2.5 mt-4">
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-gray-50 border border-gray-100 px-3 py-1.5 text-[9px] font-bold text-gray-500">
                                            <span class="material-symbols-outlined text-[13px] text-hanania-purple/50">schedule</span>
                                            {{ $package->duration_days ?? '9' }} Hari
                                        </span>
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-gray-50 border border-gray-100 px-3 py-1.5 text-[9px] font-bold text-gray-500">
                                            <span class="material-symbols-outlined text-[13px] text-hanania-gold">calendar_month</span>
                                            {{ isset($package->departure_date) ? \Carbon\Carbon::parse($package->departure_date)->format('M Y') : 'Jadwal Menyusul' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="md:text-right px-1 md:px-2">
                                    <p class="text-[8px] uppercase tracking-[.2em] text-gray-400 font-black">Mulai dari</p>
                                    <p class="font-heading text-[19px] font-black text-hanania-purple-dark mt-1">
                                        Rp {{ number_format($package->price ?? $package->estimated_price, 0, ',', '.') }}
                                    </p>

                                    <a href="{{ route('packages.show', $package->id) }}" class="inline-flex items-center justify-center gap-2 mt-4 rounded-xl bg-hanania-purple text-white px-5 py-3 text-[11px] font-black hover:bg-hanania-purple-dark transition-colors">
                                        Detail
                                        <span class="material-symbols-outlined text-[15px]">arrow_forward</span>
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="mt-5 text-center">
                    <a href="{{ route('packages.index') }}" class="inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-[.17em] text-hanania-purple hover:text-hanania-gold transition-colors">
                        Lihat Semua Paket
                        <span class="material-symbols-outlined text-[15px]">north_east</span>
                    </a>
                </div>
            </section>

            <!-- CLOSING EDITORIAL -->
            <section class="relative overflow-hidden rounded-[2.2rem] bg-hanania-purple-light/45 border border-hanania-purple/10 p-6 sm:p-10">
                <div class="absolute right-0 top-0 text-hanania-purple/10">
                    <span class="material-symbols-outlined text-[190px] [font-variation-settings:'FILL'_1]">menu_book</span>
                </div>

                <div class="relative z-10 grid lg:grid-cols-[1fr_auto] gap-7 items-end">
                    <div class="max-w-2xl">
                        <span class="text-hanania-purple text-[9px] font-black uppercase tracking-[.22em]">Inspirasi Harian</span>
                        <h3 class="font-heading text-[23px] sm:text-[36px] text-hanania-purple-dark font-black leading-[1.05] tracking-tight mt-2">
                            Mengenal Lebih Dekat<br class="hidden sm:block"> Sejarah Masjid Nabawi
                        </h3>
                        <p class="text-gray-500 text-[11px] sm:text-[13px] leading-relaxed max-w-xl mt-3">
                            Tingkatkan kerinduan Anda ke Tanah Suci dengan membaca sejarah tempat mustajab.
                        </p>
                    </div>

                    <a href="#" class="inline-flex items-center justify-center gap-2 bg-white text-hanania-purple border border-hanania-purple/10 px-6 py-3.5 rounded-xl font-black text-[11px] shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all">
                        Baca Artikel
                        <span class="material-symbols-outlined text-[17px]">auto_stories</span>
                    </a>
                </div>
            </section>

            <!-- MINI FOOTER -->
            <div class="py-7 text-center">
                <p class="text-[10px] text-gray-400 font-medium">
                    &copy; {{ date('Y') }} {{ $companyName }}. Hak Cipta Dilindungi.
                </p>
            </div>
        </div>
    </div>
@endsection