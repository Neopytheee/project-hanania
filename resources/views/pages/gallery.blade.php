@extends('layouts.customer.app') 

@section('title', 'Galeri Perjalanan - ' . \App\Models\AppInformation::getValue('company_name', 'Hanania Travel'))

@section('content')
@php
    $companyName = \App\Models\AppInformation::getValue('company_name', 'Hanania Travel');
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
</style>

<div class="w-full min-h-screen font-sans">
    <div class="flex flex-col pt-24 pb-20 animate-fade-in-up max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- INTRO -->
        <section class="mb-9 sm:mb-10">
            <div class="grid lg:grid-cols-[1fr_auto] gap-6 items-end pb-7 border-b border-hanania-purple/10">
                <div class="max-w-3xl">
                    <div class="inline-flex items-center gap-2 text-hanania-gold font-black tracking-[0.14em] uppercase text-[9px] sm:text-[10px] mb-4">
                        <span class="w-2 h-2 rounded-full bg-hanania-gold"></span>
                        Jejak Perjalanan
                    </div>

                    <h1 class="font-heading text-[34px] sm:text-[44px] lg:text-[52px] font-black text-hanania-purple-dark leading-[1.03] tracking-tight">
                        Galeri Perjalanan<br class="hidden sm:block">
                        <span class="text-hanania-gold">Bersama Hanania.</span>
                    </h1>

                    <p class="text-gray-500 text-[13px] sm:text-[14px] leading-7 font-medium max-w-2xl mt-4">
                        Kumpulan momen indah dan cerita jamaah kami di Tanah Suci. Tempat niat suci berubah menjadi kenangan abadi bersama
                        <strong class="text-hanania-purple-dark">{{ $companyName }}</strong>.
                    </p>
                </div>

                <div class="hidden lg:flex items-center gap-3 text-hanania-purple/30">
                    <span class="material-symbols-outlined text-[62px] [font-variation-settings:'FILL'_1]">photo_library</span>
                    <div class="w-12 h-px bg-hanania-gold/40"></div>
                </div>
            </div>
        </section>

        <!-- CATALOG GALERI -->
        <section>
            <div class="flex items-center justify-between gap-4 mb-4">
                <div>
                    <p class="text-[9px] font-black uppercase tracking-[.18em] text-hanania-purple">Koleksi</p>
                    <h2 class="font-heading text-[22px] sm:text-[25px] font-black text-hanania-purple-dark mt-1">Foto Jamaah</h2>
                </div>
                <p class="hidden sm:block text-[10px] font-medium text-gray-400">Momen indah di Baitullah</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-5">
                @forelse($galleries ?? [] as $foto)
                    <article class="group bg-white rounded-[1.6rem] border border-hanania-purple/10 overflow-hidden shadow-lg catalog-hover aspect-[4/5] relative">
                        <img 
                            src="{{ asset('storage/' . $foto->image_path) }}" 
                            alt="{{ $foto->title }}" 
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                            loading="lazy"
                        >
                        
                        {{-- Overlay Gelap saat di-hover --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-hanania-purple-dark/90 via-hanania-purple-dark/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                        {{-- Info Foto --}}
                        <div class="absolute left-4 right-4 bottom-4 translate-y-4 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300">
                            <p class="font-heading font-extrabold text-sm sm:text-base text-white truncate drop-shadow-md">
                                {{ $foto->title }}
                            </p>
                            @if(isset($foto->category))
                                <p class="mt-1 text-[10px] sm:text-[11px] text-hanania-gold font-bold uppercase tracking-wider drop-shadow-md">
                                    {{ $foto->category->name }}
                                </p>
                            @endif
                        </div>
                    </article>
                @empty
                    <div class="col-span-full rounded-[2rem] bg-white border border-hanania-purple/10 px-6 py-16 sm:py-20 text-center shadow-lg">
                        <div class="w-16 h-16 mx-auto rounded-2xl bg-hanania-purple-light/35 flex items-center justify-center text-hanania-purple mb-5">
                            <span class="material-symbols-outlined text-[30px]">photo_library</span>
                        </div>
                        <h3 class="font-heading text-[22px] sm:text-[24px] font-black text-hanania-purple-dark mb-2">Galeri Masih Kosong</h3>
                        <p class="text-gray-500 text-[12px] sm:text-[13px] font-medium max-w-md mx-auto leading-relaxed">
                            Foto momen perjalanan jamaah akan segera diperbarui di sini.
                        </p>
                    </div>
                @endforelse
            </div>

            {{-- PAGINATION --}}
            @if(isset($galleries) && $galleries->hasPages())
                <div class="mt-12 flex justify-center">
                    {{ $galleries->links() }}
                </div>
            @endif
        </section>

        <!-- FOOTER NOTE -->
        <div class="mt-10 pt-6 border-t border-hanania-purple/10 flex flex-col sm:flex-row items-center justify-between gap-2">
            <p class="text-[10px] text-gray-400 font-medium text-center sm:text-left">
                Semoga langkah kita senantiasa dimudahkan menuju Baitullah.
            </p>
            <span class="text-[9px] uppercase tracking-[.16em] font-black text-hanania-purple/45">Baitullah Collection</span>
        </div>

    </div>
</div>
@endsection