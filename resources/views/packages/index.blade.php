@extends('layouts.customer.app')

@section('title', 'Katalog Paket')

@section('content')
@php
    $companyName = \App\Models\AppInformation::getValue('company_name', 'Hanania');
@endphp

<style>
    .animate-fade-in-up { animation: fadeInUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(16px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .catalog-hover {
        transition: transform .28s ease, box-shadow .28s ease, border-color .28s ease;
    }

    .catalog-hover:hover {
        transform: translateY(-4px);
    }

    .hide-scroll::-webkit-scrollbar { display: none; }
    .hide-scroll { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<div class="w-full min-h-screen font-sans">
    <div class="flex flex-col pt-24 pb-20 animate-fade-in-up max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- INTRO -->
        <section class="mb-9 sm:mb-10">
            <div class="grid lg:grid-cols-[1fr_auto] gap-6 items-end pb-7 border-b border-hanania-purple/10">
                <div class="max-w-3xl">
                    <div class="inline-flex items-center gap-2 text-hanania-gold font-black tracking-[0.14em] uppercase text-[9px] sm:text-[10px] mb-4">
                        <span class="w-2 h-2 rounded-full bg-hanania-gold"></span>
                        Pilihan Perjalanan
                    </div>

                    <h1 class="font-heading text-[34px] sm:text-[44px] lg:text-[52px] font-black text-hanania-purple-dark leading-[1.03] tracking-tight">
                        Temukan Perjalanan<br class="hidden sm:block">
                        <span class="text-hanania-gold">Menuju Baitullah.</span>
                    </h1>

                    <p class="text-gray-500 text-[13px] sm:text-[14px] leading-7 font-medium max-w-2xl mt-4">
                        Kurasi paket ibadah umroh dengan fasilitas premium dan pelayanan yang nyaman untuk menemani setiap langkah Anda bersama
                        <strong class="text-hanania-purple-dark">{{ $companyName }}</strong>.
                    </p>
                </div>

                <div class="hidden lg:flex items-center gap-3 text-hanania-purple/30">
                    <span class="material-symbols-outlined text-[62px] [font-variation-settings:'FILL'_1]">mosque</span>
                    <div class="w-12 h-px bg-hanania-gold/40"></div>
                </div>
            </div>
        </section>

        <!-- CATALOG -->
        <section>
            <div class="flex items-center justify-between gap-4 mb-4">
                <div>
                    <p class="text-[9px] font-black uppercase tracking-[.18em] text-hanania-purple">Katalog</p>
                    <h2 class="font-heading text-[22px] sm:text-[25px] font-black text-hanania-purple-dark mt-1">Paket Tersedia</h2>
                </div>
                <p class="hidden sm:block text-[10px] font-medium text-gray-400">Pilih perjalanan yang paling sesuai dengan rencana Anda</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-5">
                @forelse($packages as $package)
                    <article class="group bg-white rounded-[1.6rem] border border-hanania-purple/10 overflow-hidden shadow-lg catalog-hover">
                        <!-- IMAGE -->
                        <div class="relative h-48 sm:h-52 bg-gray-50 overflow-hidden">
                            @if($package->image)
                                <img src="{{ asset('storage/' . $package->image) }}" alt="{{ $package->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-hanania-purple/30 bg-hanania-purple-light/20">
                                    <span class="material-symbols-outlined text-5xl">image</span>
                                </div>
                            @endif

                            <div class="absolute top-3 left-3">
                                <span class="inline-flex items-center gap-1.5 bg-white/92 px-2.5 py-1.5 rounded-full text-[8px] font-black uppercase tracking-[.14em] text-hanania-purple-dark">
                                    <span class="w-1.5 h-1.5 rounded-full bg-hanania-gold"></span>
                                    Paket {{ $package->category }}
                                </span>
                            </div>

                            <div class="absolute right-3 bottom-3 bg-white/95 px-3.5 py-2.5 rounded-xl shadow-md text-right">
                                <span class="block text-[8px] uppercase tracking-[.16em] text-gray-400 font-bold">Mulai dari</span>
                                <span class="font-heading text-hanania-gold text-[17px] font-black leading-none">
                                    Rp {{ number_format($package->estimated_price, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        <!-- CONTENT -->
                        <div class="p-5 sm:p-6">
                            <h3 class="font-heading text-[18px] sm:text-[19px] font-black text-hanania-purple-dark leading-snug line-clamp-2 group-hover:text-hanania-purple transition-colors">
                                {{ $package->name }}
                            </h3>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-5">
                                <div class="flex items-center gap-2.5 rounded-xl bg-gray-50 border border-gray-100 px-3 py-2.5">
                                    <span class="w-8 h-8 shrink-0 rounded-lg bg-hanania-purple-light/45 text-hanania-purple flex items-center justify-center">
                                        <span class="material-symbols-outlined text-[15px] [font-variation-settings:'FILL'_1]">calendar_month</span>
                                    </span>
                                    <div class="min-w-0">
                                        <p class="text-[7px] uppercase tracking-[.14em] font-black text-gray-400">Durasi</p>
                                        <p class="text-[10px] sm:text-[11px] font-black text-hanania-purple-dark truncate">{{ $package->duration_days }} Hari</p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2.5 rounded-xl bg-gray-50 border border-gray-100 px-3 py-2.5">
                                    <span class="w-8 h-8 shrink-0 rounded-lg bg-hanania-purple-light/45 text-hanania-purple flex items-center justify-center">
                                        <span class="material-symbols-outlined text-[15px] [font-variation-settings:'FILL'_1]">airlines</span>
                                    </span>
                                    <div class="min-w-0">
                                        <p class="text-[7px] uppercase tracking-[.14em] font-black text-gray-400">Maskapai</p>
                                        <p class="text-[10px] sm:text-[11px] font-black text-hanania-purple-dark truncate">
                                            {{ !empty($package->airline) ? $package->airline : 'Menyusul' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="sm:col-span-2 flex items-center gap-2.5 rounded-xl bg-hanania-purple-light/22 border border-hanania-purple/10 px-3 py-2.5">
                                    <span class="w-8 h-8 shrink-0 rounded-lg bg-hanania-purple-light/45 text-hanania-purple flex items-center justify-center">
                                        <span class="material-symbols-outlined text-[15px] [font-variation-settings:'FILL'_1]">domain</span>
                                    </span>
                                    <div class="min-w-0">
                                        <p class="text-[7px] uppercase tracking-[.14em] font-black text-hanania-purple/55">Hotel</p>
                                        <p class="text-[10px] sm:text-[11px] font-black text-hanania-purple-dark truncate">
                                            {{ !empty($package->hotel_mekkah) ? $package->hotel_mekkah : 'Premium' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <a href="{{ route('packages.show', $package->id) }}" class="mt-5 w-full py-3 rounded-xl bg-hanania-purple text-white font-bold text-[12px] sm:text-[13px] flex items-center justify-center gap-2 hover:bg-hanania-purple-dark transition-colors">
                                Lihat Detail Paket
                                <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                            </a>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full rounded-[2rem] bg-white border border-hanania-purple/10 px-6 py-16 sm:py-20 text-center shadow-lg">
                        <div class="w-16 h-16 mx-auto rounded-2xl bg-hanania-purple-light/35 flex items-center justify-center text-hanania-purple mb-5">
                            <span class="material-symbols-outlined text-[30px]">inventory_2</span>
                        </div>
                        <h3 class="font-heading text-[22px] sm:text-[24px] font-black text-hanania-purple-dark mb-2">Katalog Sedang Disiapkan</h3>
                        <p class="text-gray-500 text-[12px] sm:text-[13px] font-medium max-w-md mx-auto leading-relaxed">
                            Nantikan penawaran paket perjalanan ibadah terbaik dari kami sebentar lagi.
                        </p>
                    </div>
                @endforelse
            </div>
        </section>

        <!-- FOOTER NOTE -->
        <div class="mt-10 pt-6 border-t border-hanania-purple/10 flex flex-col sm:flex-row items-center justify-between gap-2">
            <p class="text-[10px] text-gray-400 font-medium text-center sm:text-left">
                Pilih dengan nyaman, siapkan perjalanan dengan tenang bersama {{ $companyName }}.
            </p>
            <span class="text-[9px] uppercase tracking-[.16em] font-black text-hanania-purple/45">Baitullah Collection</span>
        </div>

    </div>
</div>
@endsection
