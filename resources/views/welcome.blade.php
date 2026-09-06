@php
    $companyName = \App\Models\AppInformation::getValue(
        'company_name',
        'Hanania x Ventour'
    );

    $tagline = \App\Models\AppInformation::getValue(
        'company_tagline',
        'Premium Umroh'
    );

    $logoPath = \App\Models\AppInformation::getValue(
        'company_logo'
    );

    $defaultLogo = asset('images/HananiaNew4K.png');

    $logoUrl = $logoPath
        ? asset('storage/' . $logoPath)
        : $defaultLogo;
@endphp

<!DOCTYPE html>
<html lang="id" translate="no" class="scroll-smooth">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="theme-color"
        content="#61398F"
    >

    <title>
        {{ $companyName }} - {{ $tagline }}
    </title>

    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,1,0"
    >

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >
</head>


<body class="antialiased text-hanania-purple-dark bg-hanania-purple-light flex flex-col min-h-screen selection:bg-hanania-gold selection:text-white">

    @auth
    @endauth
    @include('components.sidebar')

    @include('components.navbar')


    {{-- =========================================================
        HERO
    ========================================================== --}}
    <section class="relative w-full min-h-[680px] lg:min-h-[780px] flex items-end overflow-hidden bg-hanania-purple-dark">

        {{-- CAROUSEL --}}
        <div class="absolute inset-0">

            <div
                id="slider-container"
                class="flex w-full h-full overflow-x-auto snap-x-mandatory scroll-smooth"
                style="scrollbar-width:none;"
            >

                {{-- SLIDE 1 --}}
                <div class="relative w-full h-full shrink-0 snap-center">

                    <img
                        src="https://images.unsplash.com/photo-1565552645632-d725f8bfc19a?q=80&w=2200&auto=format&fit=crop"
                        alt="Masjidil Haram, Makkah"
                        class="w-full h-full object-cover"
                        loading="eager"
                    >

                    <div class="absolute inset-0 bg-hanania-purple-dark/35"></div>

                    <div class="absolute inset-0 bg-gradient-to-t from-hanania-purple-dark via-hanania-purple-dark/35 to-transparent"></div>

                </div>


                {{-- SLIDE 2 --}}
                <div class="relative w-full h-full shrink-0 snap-center">

                    <img
                        src="https://images.unsplash.com/photo-1591604129939-f1efa4d9f7fa?q=80&w=2200&auto=format&fit=crop"
                        alt="Masjid Nabawi, Madinah"
                        class="w-full h-full object-cover"
                        loading="lazy"
                    >

                    <div class="absolute inset-0 bg-hanania-purple-dark/35"></div>

                    <div class="absolute inset-0 bg-gradient-to-t from-hanania-purple-dark via-hanania-purple-dark/35 to-transparent"></div>

                </div>


                {{-- SLIDE 3 --}}
                <div class="relative w-full h-full shrink-0 snap-center">

                    <img
                        src="https://images.unsplash.com/photo-1519817650390-64a93db51149?q=80&w=2200&auto=format&fit=crop"
                        alt="Suasana perjalanan ibadah"
                        class="w-full h-full object-cover"
                        loading="lazy"
                    >

                    <div class="absolute inset-0 bg-hanania-purple-dark/35"></div>

                    <div class="absolute inset-0 bg-gradient-to-t from-hanania-purple-dark via-hanania-purple-dark/35 to-transparent"></div>

                </div>


                {{-- SLIDE 4 --}}
                <div class="relative w-full h-full shrink-0 snap-center">

                    <img
                        src="https://images.unsplash.com/photo-1601142634808-38923eb7c560?q=80&w=2200&auto=format&fit=crop"
                        alt="Suasana Tanah Suci"
                        class="w-full h-full object-cover"
                        loading="lazy"
                    >

                    <div class="absolute inset-0 bg-hanania-purple-dark/35"></div>

                    <div class="absolute inset-0 bg-gradient-to-t from-hanania-purple-dark via-hanania-purple-dark/35 to-transparent"></div>

                </div>


                {{-- SLIDE 5 --}}
                <div class="relative w-full h-full shrink-0 snap-center">

                    <img
                        src="https://images.unsplash.com/photo-1542601098-8fc114e148e2?q=80&w=2200&auto=format&fit=crop"
                        alt="Perjalanan menuju Tanah Suci"
                        class="w-full h-full object-cover"
                        loading="lazy"
                    >

                    <div class="absolute inset-0 bg-hanania-purple-dark/35"></div>

                    <div class="absolute inset-0 bg-gradient-to-t from-hanania-purple-dark via-hanania-purple-dark/35 to-transparent"></div>

                </div>

            </div>

        </div>


        {{-- HERO CONTENT --}}
        <div class="relative z-20 w-full pb-16 sm:pb-20 lg:pb-24">

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                <div class="max-w-4xl">

                    <div class="flex items-center gap-3 mb-6">

                        <span class="h-px w-10 bg-hanania-gold"></span>

                        <span class="text-[10px] sm:text-xs uppercase tracking-[0.2em] font-extrabold text-hanania-gold-light">
                            {{ $companyName }}
                        </span>

                    </div>


                    <h1 class="font-heading text-[44px] sm:text-[60px] lg:text-[78px] font-black leading-[0.95] tracking-[-0.04em] text-white">

                        Wujudkan niat

                        <span class="block text-hanania-gold-light">
                            menuju Baitullah.
                        </span>

                    </h1>


                    <p class="mt-6 max-w-2xl text-sm sm:text-base lg:text-lg leading-relaxed text-white/75 font-medium">
                        {{ $tagline }}.
                        Mulai dari langkah kecil hari ini,
                        siapkan perjalanan dengan sistem yang lebih
                        terencana dan transparan.
                    </p>


                    {{-- CTA --}}
                    <div class="mt-8 flex flex-col sm:flex-row gap-3">

                        <a
                            href="#paket"
                            class="btn-hanania-gold px-7 py-3.5 rounded-full text-sm sm:text-base"
                        >
                            Mulai Menabung

                            <span class="material-symbols-outlined text-[18px]">
                                arrow_forward
                            </span>
                        </a>


                        <a
                            href="#fitur"
                            class="inline-flex items-center justify-center gap-2 px-7 py-3.5 rounded-full border border-white/20 bg-white/10 text-white font-bold text-sm sm:text-base backdrop-blur-sm hover:bg-white/15 transition-all"
                        >
                            Pelajari Sistem

                            <span class="material-symbols-outlined text-[18px]">
                                arrow_downward
                            </span>
                        </a>

                    </div>


                    {{-- TRUST --}}
                    <div class="mt-8 flex flex-wrap items-center gap-x-6 gap-y-3">

                        <div class="flex items-center gap-2 text-white/65 text-[11px] font-bold">
                            <span class="material-symbols-outlined text-[17px] text-hanania-gold">
                                verified_user
                            </span>
                            Terpercaya
                        </div>

                        <div class="flex items-center gap-2 text-white/65 text-[11px] font-bold">
                            <span class="material-symbols-outlined text-[17px] text-hanania-gold">
                                savings
                            </span>
                            Fleksibel
                        </div>

                        <div class="flex items-center gap-2 text-white/65 text-[11px] font-bold">
                            <span class="material-symbols-outlined text-[17px] text-hanania-gold">
                                flight_takeoff
                            </span>
                            Siap Berangkat
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- SLIDER CONTROL --}}
        <div class="absolute z-30 right-4 sm:right-8 bottom-6 sm:bottom-8 flex items-center gap-3">

            <button
                id="btn-prev"
                aria-label="Slide sebelumnya"
                class="w-11 h-11 rounded-full border border-white/20 bg-white/10 backdrop-blur-sm text-white flex items-center justify-center hover:bg-white hover:text-hanania-purple transition-all"
            >
                <span class="material-symbols-outlined">
                    chevron_left
                </span>
            </button>


            <div class="flex items-center gap-1.5 bg-white/10 backdrop-blur-sm border border-white/10 px-3 py-2 rounded-full">

                <div class="slider-dot w-6 h-1.5 rounded-full bg-hanania-gold"></div>

                <div class="slider-dot w-1.5 h-1.5 rounded-full bg-white/50"></div>

                <div class="slider-dot w-1.5 h-1.5 rounded-full bg-white/50"></div>

                <div class="slider-dot w-1.5 h-1.5 rounded-full bg-white/50"></div>

                <div class="slider-dot w-1.5 h-1.5 rounded-full bg-white/50"></div>

            </div>


            <button
                id="btn-next"
                aria-label="Slide berikutnya"
                class="w-11 h-11 rounded-full border border-white/20 bg-white/10 backdrop-blur-sm text-white flex items-center justify-center hover:bg-white hover:text-hanania-purple transition-all"
            >
                <span class="material-symbols-outlined">
                    chevron_right
                </span>
            </button>

        </div>

    </section>


    {{-- =========================================================
        JOURNEY INTRO
    ========================================================== --}}
    <section class="bg-white border-b border-hanania-purple/10">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-3">

                <div class="py-8 md:py-9 md:pr-10 md:border-r border-hanania-purple/10">

                    <span class="text-[10px] uppercase tracking-[0.18em] font-extrabold text-hanania-gold">
                        01 · Niat
                    </span>

                    <h3 class="mt-2 font-heading text-lg font-extrabold text-hanania-purple-dark">
                        Mulai dengan ringan
                    </h3>

                    <p class="mt-1 text-[12px] text-gray-500 leading-relaxed">
                        Langkah awal yang lebih mudah untuk mulai mempersiapkan ibadah.
                    </p>

                </div>


                <div class="py-8 md:py-9 md:px-10 md:border-r border-hanania-purple/10">

                    <span class="text-[10px] uppercase tracking-[0.18em] font-extrabold text-hanania-gold">
                        02 · Simpan
                    </span>

                    <h3 class="mt-2 font-heading text-lg font-extrabold text-hanania-purple-dark">
                        Sesuai kemampuan
                    </h3>

                    <p class="mt-1 text-[12px] text-gray-500 leading-relaxed">
                        Atur ritme persiapan dana sesuai kondisi Anda.
                    </p>

                </div>


                <div class="py-8 md:py-9 md:pl-10">

                    <span class="text-[10px] uppercase tracking-[0.18em] font-extrabold text-hanania-gold">
                        03 · Berangkat
                    </span>

                    <h3 class="mt-2 font-heading text-lg font-extrabold text-hanania-purple-dark">
                        Menuju Baitullah
                    </h3>

                    <p class="mt-1 text-[12px] text-gray-500 leading-relaxed">
                        Kami mendampingi hingga langkah perjalanan berikutnya.
                    </p>

                </div>

            </div>

        </div>

    </section>

    {{-- =========================================================
        PACKAGES
    ========================================================== --}}
    <section
        id="paket"
        class="bg-white py-20 sm:py-24"
    >

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-5 mb-10">

                <div>

                    <span class="text-[10px] sm:text-[11px] uppercase tracking-[0.18em] font-extrabold text-hanania-purple">
                        Pilihan Perjalanan
                    </span>

                    <h2 class="mt-2 font-heading text-3xl sm:text-4xl font-extrabold text-hanania-purple-dark">
                        Pilih perjalanan Anda.
                    </h2>

                    <p class="mt-2 max-w-xl text-sm text-gray-500 leading-relaxed">
                        Temukan paket yang sesuai dengan kebutuhan dan rencana ibadah Anda.
                    </p>

                </div>


                <a
                    href="{{ route('packages.index') }}"
                    class="inline-flex items-center gap-2 rounded-full border border-hanania-purple/15 px-5 py-3 text-sm font-bold text-hanania-purple hover:bg-hanania-purple-light transition-colors self-start md:self-auto"
                >
                    Lihat Semua

                    <span class="material-symbols-outlined text-[17px]">
                        arrow_outward
                    </span>
                </a>

            </div>


            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                @forelse($featuredPackages ?? [] as $package)

                    <article class="group bg-white rounded-[26px] border border-hanania-purple/10 overflow-hidden shadow-sm hover:-translate-y-1 hover:shadow-xl transition-all duration-300">

                        {{-- IMAGE --}}
                        <div class="relative h-60 overflow-hidden bg-hanania-purple-light">

                            @if($package->image)

                                <img
                                    src="{{ asset('storage/' . $package->image) }}"
                                    alt="{{ $package->name }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                                >

                            @else

                                <div class="w-full h-full flex items-center justify-center text-hanania-purple/30">
                                    <span class="material-symbols-outlined text-5xl">
                                        image
                                    </span>
                                </div>

                            @endif


                            {{-- IMAGE OVERLAY --}}
                            <div class="absolute inset-x-0 bottom-0 h-28 bg-gradient-to-t from-hanania-purple-dark/80 to-transparent"></div>


                            {{-- BADGE --}}
                            <div class="absolute left-4 top-4">

                                <span class="inline-flex items-center gap-1.5 rounded-full bg-white/95 px-3 py-1.5 text-[9px] font-extrabold uppercase tracking-wider text-hanania-purple-dark">
                                    <span class="w-1.5 h-1.5 rounded-full bg-hanania-gold"></span>
                                    Pilihan
                                </span>

                            </div>


                            {{-- PRICE --}}
                            <div class="absolute right-4 bottom-4 text-right">

                                <span class="block text-[9px] uppercase tracking-widest text-white/60 font-bold">
                                    Estimasi
                                </span>

                                <span class="font-heading text-lg font-extrabold text-white">
                                    Rp {{ number_format($package->estimated_price, 0, ',', '.') }}
                                </span>

                            </div>

                        </div>


                        {{-- CONTENT --}}
                        <div class="p-6">

                            <h3 class="font-heading text-xl font-extrabold text-hanania-purple-dark leading-snug line-clamp-2 group-hover:text-hanania-purple transition-colors">
                                {{ $package->name }}
                            </h3>


                            <div class="mt-5 space-y-2">

                                <div class="flex items-center justify-between rounded-xl bg-hanania-purple-light/40 px-3.5 py-3">

                                    <span class="text-xs text-gray-500 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-[16px] text-hanania-purple">
                                            calendar_month
                                        </span>

                                        Durasi
                                    </span>

                                    <span class="text-xs font-bold text-hanania-purple-dark">
                                        {{ $package->duration_days }} Hari
                                    </span>

                                </div>


                                <div class="flex items-center justify-between rounded-xl bg-hanania-purple-light/40 px-3.5 py-3">

                                    <span class="text-xs text-gray-500 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-[16px] text-hanania-purple">
                                            airlines
                                        </span>

                                        Maskapai
                                    </span>

                                    <span class="text-xs font-bold text-hanania-purple-dark truncate max-w-[55%]">
                                        {{ $package->airline ?? 'Menyusul' }}
                                    </span>

                                </div>


                                <div class="flex items-center justify-between rounded-xl bg-hanania-purple-light/40 px-3.5 py-3">

                                    <span class="text-xs text-gray-500 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-[16px] text-hanania-purple">
                                            domain
                                        </span>

                                        Hotel
                                    </span>

                                    <span class="text-xs font-bold text-hanania-purple-dark truncate max-w-[55%]">
                                        {{ $package->hotel_mekkah ?? 'Premium' }}
                                    </span>

                                </div>

                            </div>


                            <a
                                href="{{ route('packages.show', $package->id) }}"
                                class="mt-5 w-full inline-flex items-center justify-center gap-2 rounded-xl bg-hanania-purple text-white py-3.5 text-sm font-bold hover:bg-hanania-purple-dark transition-colors"
                            >
                                Lihat Detail

                                <span class="material-symbols-outlined text-[17px]">
                                    arrow_forward
                                </span>
                            </a>

                        </div>

                    </article>

                @empty

                    <div class="col-span-full py-20 text-center rounded-[26px] border border-dashed border-hanania-purple/20 bg-hanania-purple-light/30">

                        <div class="w-16 h-16 mx-auto rounded-full bg-white flex items-center justify-center text-hanania-purple/40 mb-4 shadow-sm">

                            <span class="material-symbols-outlined text-4xl">
                                inventory_2
                            </span>

                        </div>

                        <h3 class="font-heading text-xl font-extrabold text-hanania-purple-dark">
                            Katalog Sedang Disiapkan
                        </h3>

                        <p class="mt-2 text-sm text-gray-500">
                            Nantikan paket perjalanan terbaik dari kami.
                        </p>

                    </div>

                @endforelse

            </div>

        </div>

    </section>

    {{-- =========================================================
        FEATURES
    ========================================================== --}}
    <section
        id="fitur"
        class="bg-hanania-purple-light/40 py-20 sm:py-24"
    >

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- HEADING --}}
            <div class="max-w-2xl mb-10">

                <span class="text-[10px] sm:text-[11px] uppercase tracking-[0.18em] font-extrabold text-hanania-purple">
                    Mengapa {{ $companyName }}?
                </span>

                <h2 class="mt-2 font-heading text-3xl sm:text-4xl font-extrabold text-hanania-purple-dark tracking-tight">
                    Dibuat sederhana.
                    Dipersiapkan dengan serius.
                </h2>

                <p class="mt-3 text-sm sm:text-base text-gray-500 leading-relaxed">
                    Sistem yang dirancang untuk membuat perjalanan menuju Baitullah
                    lebih mudah dipahami sejak awal, selengkapnya dapat dibaca di halaman <a href="{{ route('terms') }}" class="text-hanania-purple font-bold hover:underline">Syarat & Ketentuan</a>.
                </p>

            </div>


            <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">

                {{-- PRIMARY FEATURE --}}
                <div class="lg:col-span-7 rounded-[28px] bg-hanania-purple-dark p-7 sm:p-9 lg:p-10 text-white min-h-[350px] flex flex-col justify-between">

                    <div class="flex justify-between items-start">

                        <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[27px] text-hanania-gold-light">
                                savings
                            </span>
                        </div>

                        <span class="text-[10px] uppercase tracking-[0.18em] font-bold text-white/45">
                            Fleksibel
                        </span>

                    </div>


                    <div>

                        <h3 class="font-heading text-3xl sm:text-4xl font-extrabold text-white leading-tight">
                            Setoran yang mengikuti
                            <span class="text-hanania-gold-light">
                                kemampuan Anda.
                            </span>
                        </h3>

                        <p class="mt-4 text-sm sm:text-[15px] text-white/70 leading-relaxed max-w-2xl">
                            Mulai dengan DP awal, kemudian lanjutkan persiapan
                            sesuai ritme yang nyaman bagi Anda.
                        </p>

                    </div>

                </div>


                {{-- SECONDARY FEATURES --}}
                <div class="lg:col-span-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-5">

                    <div class="card-hanania p-6 flex flex-col justify-between min-h-[165px]">

                        <div class="w-11 h-11 rounded-2xl bg-hanania-purple-light flex items-center justify-center">
                            <span class="material-symbols-outlined text-hanania-purple text-[24px]">
                                admin_panel_settings
                            </span>
                        </div>

                        <div class="mt-6">
                            <h3 class="font-heading text-xl font-extrabold text-hanania-purple-dark">
                                Aman & Terpantau
                            </h3>

                            <p class="mt-1 text-[13px] text-gray-500 leading-relaxed">
                                Administrasi dan perkembangan perjalanan lebih mudah dipantau.
                            </p>
                        </div>

                    </div>


                    <div class="card-hanania p-6 flex flex-col justify-between min-h-[165px]">

                        <div class="w-11 h-11 rounded-2xl bg-hanania-purple-light flex items-center justify-center">
                            <span class="material-symbols-outlined text-hanania-purple text-[24px]">
                                dashboard
                            </span>
                        </div>

                        <div class="mt-6">
                            <h3 class="font-heading text-xl font-extrabold text-hanania-purple-dark">
                                Satu Dashboard
                            </h3>

                            <p class="mt-1 text-[13px] text-gray-500 leading-relaxed">
                                Lihat progres, transaksi, dan informasi perjalanan di satu tempat.
                            </p>
                        </div>

                    </div>

                </div>


                {{-- DASHBOARD CTA --}}
                <div class="lg:col-span-12 rounded-[28px] bg-white border border-hanania-purple/10 p-6 sm:p-8 flex flex-col md:flex-row md:items-center justify-between gap-5">

                    <div>

                        <span class="text-[10px] uppercase tracking-[0.18em] font-extrabold text-hanania-gold">
                            Sistem Digital
                        </span>

                        <h3 class="mt-2 font-heading text-2xl sm:text-3xl font-extrabold text-hanania-purple-dark">
                            Semua perkembangan di ujung jari.
                        </h3>

                        <p class="mt-2 text-sm text-gray-500 leading-relaxed max-w-2xl">
                            Pantau target, transaksi, dan informasi perjalanan tanpa harus membuka banyak dokumen.
                        </p>

                    </div>


                    <a
                        href="{{ route('customer.dashboard') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-full bg-hanania-purple px-6 py-3.5 text-sm font-bold text-white hover:bg-hanania-purple-dark transition-colors shrink-0"
                    >
                        Masuk Dashboard

                        <span class="material-symbols-outlined text-[18px]">
                            arrow_forward
                        </span>
                    </a>

                </div>

            </div>

        </div>

    </section>


    {{-- =========================================================
        TESTIMONIALS
    ========================================================== --}}
    <section class="bg-hanania-purple-light/40 py-20 sm:py-24">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="max-w-2xl mb-10">

                <span class="text-[10px] sm:text-[11px] uppercase tracking-[0.18em] font-extrabold text-hanania-purple">
                    Cerita Jamaah
                </span>

                <h2 class="mt-2 font-heading text-3xl sm:text-4xl font-extrabold text-hanania-purple-dark">
                    Niat yang akhirnya menjadi perjalanan.
                </h2>

                <p class="mt-3 text-sm sm:text-base text-gray-500 leading-relaxed">
                    Cerita dari jamaah yang mempercayakan proses persiapannya bersama {{ $companyName }}.
                </p>

            </div>


            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">

                @forelse($testimonials ?? [] as $testi)

                    <article class="bg-white rounded-[26px] border border-hanania-purple/10 p-6 sm:p-7 shadow-sm flex flex-col">

                        <div class="flex items-center justify-between">

                            <div class="flex text-hanania-gold">

                                @for($i = 0; $i < $testi->rating; $i++)

                                    <span class="material-symbols-outlined text-[17px]">
                                        star
                                    </span>

                                @endfor

                            </div>


                            <span class="material-symbols-outlined text-[34px] text-hanania-gold/15">
                                format_quote
                            </span>

                        </div>


                        <p class="mt-5 text-sm text-gray-600 leading-relaxed italic flex-grow">
                            “{{ $testi->content }}”
                        </p>


                        <div class="mt-7 pt-5 border-t border-hanania-purple/10 flex items-center gap-3">

                            @php
                                $enrollment = \App\Models\Enrollment::where(
                                    'customer_id',
                                    optional($testi->user->customer)->id
                                )
                                ->where('status', 'completed')
                                ->latest()
                                ->first();

                                $namaJamaah =
                                    optional($enrollment)->passenger_name
                                    ?? $testi->user->name
                                    ?? 'Sahabat Hanania';

                                $namaPaket =
                                    optional(optional($enrollment)->travelPackage)->name
                                    ?? 'Jamaah ' . $companyName;

                                $inisial = strtoupper(
                                    substr($namaJamaah, 0, 1)
                                );
                            @endphp


                            <div class="w-11 h-11 rounded-full bg-hanania-purple-light flex items-center justify-center text-sm font-extrabold text-hanania-purple shrink-0">
                                {{ $inisial }}
                            </div>


                            <div class="min-w-0">

                                <h4 class="font-heading text-sm font-extrabold text-hanania-purple-dark truncate">
                                    {{ $namaJamaah }}
                                </h4>

                                <p class="mt-0.5 text-[10px] text-gray-500 font-semibold truncate">
                                    {{ $namaPaket }}
                                </p>

                            </div>

                        </div>

                    </article>

                @empty

                    <div class="col-span-full py-16 text-center rounded-[26px] border border-dashed border-hanania-purple/20 bg-white">

                        <div class="w-16 h-16 mx-auto rounded-full bg-hanania-purple-light flex items-center justify-center text-hanania-purple/40 mb-4">

                            <span class="material-symbols-outlined text-4xl">
                                reviews
                            </span>

                        </div>

                        <h3 class="font-heading text-xl font-extrabold text-hanania-purple-dark">
                            Belum Ada Ulasan
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Ulasan dari jamaah akan segera hadir di sini.
                        </p>

                    </div>

                @endforelse

            </div>

        </div>

    </section>


    {{-- =========================================================
        GALLERY
    ========================================================== --}}
    <section id="galeri" class="bg-white py-20 sm:py-24">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-5 mb-10">
                <div>
                    <span class="text-[10px] sm:text-[11px] uppercase tracking-[0.18em] font-extrabold text-hanania-purple">
                        Jejak Perjalanan
                    </span>
                    <h2 class="mt-2 font-heading text-3xl sm:text-4xl font-extrabold text-hanania-purple-dark">
                        Momen bersama jamaah.
                    </h2>
                    <p class="mt-2 text-sm text-gray-500 max-w-xl leading-relaxed">
                        Potongan cerita perjalanan di Tanah Suci, tempat niat berubah menjadi kenangan.
                    </p>
                </div>

                <!-- TOMBOL LIHAT SEMUA GALERI -->
                <a href="{{ route('pages.gallery') }}" class="inline-flex items-center gap-2 rounded-full border border-hanania-purple/15 px-5 py-3 text-sm font-bold text-hanania-purple hover:bg-hanania-purple-light transition-colors self-start md:self-auto">
                    Lihat Semua
                    <span class="material-symbols-outlined text-[17px]">arrow_outward</span>
                </a>
            </div>


            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4">

                @forelse($galleries ?? [] as $foto)

                    <div class="group relative overflow-hidden rounded-[22px] aspect-[4/5] bg-hanania-purple-light">

                        <img
                            src="{{ asset('storage/' . $foto->image_path) }}"
                            alt="{{ $foto->title }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                        >


                        <div class="absolute inset-0 bg-gradient-to-t from-hanania-purple-dark/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>


                        <div class="absolute left-4 right-4 bottom-4 translate-y-2 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all">

                            <p class="font-heading font-extrabold text-sm text-white truncate">
                                {{ $foto->title }}
                            </p>

                            @if(isset($foto->category))

                                <p class="mt-0.5 text-[10px] text-white/65 font-medium">
                                    {{ $foto->category->name }}
                                </p>

                            @endif

                        </div>

                    </div>

                @empty

                    <div class="col-span-full py-20 text-center rounded-[26px] border border-dashed border-hanania-purple/20 bg-hanania-purple-light/30">

                        <div class="w-16 h-16 mx-auto rounded-full bg-white flex items-center justify-center text-hanania-purple/40 mb-4 shadow-sm">

                            <span class="material-symbols-outlined text-4xl">
                                photo_library
                            </span>

                        </div>

                        <h3 class="font-heading text-xl font-extrabold text-hanania-purple-dark">
                            Galeri Masih Kosong
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Foto perjalanan akan segera diperbarui.
                        </p>

                    </div>

                @endforelse

            </div>

        </div>

    </section>


    {{-- =========================================================
        CTA
    ========================================================== --}}
    <section class="bg-hanania-purple-dark py-20 sm:py-24 border-t border-hanania-gold/20">

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">

            <span class="text-[10px] uppercase tracking-[0.2em] font-extrabold text-hanania-gold-light">
                Langkah Berikutnya
            </span>

            <h2 class="mt-3 font-heading text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white leading-tight">
                Niat baik tidak harus menunggu.
            </h2>

            <p class="mt-5 max-w-2xl mx-auto text-sm sm:text-base text-white/70 leading-relaxed">
                Pilih perjalanan yang sesuai, mulai persiapannya,
                dan izinkan kami menemani langkah Anda menuju Baitullah.
            </p>

            <a
                href="{{ route('packages.index') }}"
                class="mt-8 inline-flex items-center gap-2.5 btn-hanania-gold px-7 sm:px-9 py-4 rounded-full text-sm sm:text-base"
            >
                Lihat Paket Umroh

                <span class="material-symbols-outlined">
                    arrow_forward
                </span>
            </a>

        </div>

    </section>
    


    {{-- =========================================================
        FOOTER
    ========================================================== --}}
    <footer class="bg-hanania-purple-dark text-white">

        <div class="max-w-7xl border-t border-white/10 mx-auto px-4 sm:px-6 lg:px-8 py-14 sm:py-16">

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-10 lg:gap-8">

                {{-- BRAND --}}
                <div class="lg:col-span-5">

                    <div class="flex items-center gap-3 mb-5">

                        <img
                            src="{{ $logoUrl }}"
                            alt="Logo {{ $companyName }}"
                            class="h-9 w-auto object-contain brightness-0 invert"
                        >
                    </div>


                    <p class="max-w-md text-sm text-white/60 leading-relaxed">
                        <span class="text-white font-bold">
                            {{ $companyName }}
                        </span>
                        hadir untuk membantu Anda mempersiapkan perjalanan
                        ibadah menuju Baitullah dengan lebih terencana, transparan, dan nyaman.
                        Kami mendampingi dari langkah awal hingga persiapan keberangkatan.
                    </p>


                    @if(\App\Models\AppInformation::getValue('sk_kemenag_number'))

                        <div class="mt-5 inline-flex items-center gap-2 rounded-xl bg-white/5 border border-white/10 px-3.5 py-2.5">

                            <span class="material-symbols-outlined text-hanania-gold text-[18px]">
                                verified_user
                            </span>

                            <span class="text-[10px] font-bold text-white/65">
                                {{ \App\Models\AppInformation::getValue('sk_kemenag_number') }}
                            </span>

                        </div>

                    @endif

                </div>


                {{-- SERVICES --}}
                <div class="lg:col-span-2 lg:col-start-7">

                    <h4 class="text-[11px] uppercase tracking-[0.16em] font-extrabold text-white mb-5">
                        Layanan
                    </h4>

                    <ul class="space-y-3 text-sm text-white/55">

                        <li>
                            <a href="#" class="hover:text-hanania-gold transition-colors">
                                Tabungan Umroh
                            </a>
                        </li>

                        <li>
                            <a href="#" class="hover:text-hanania-gold transition-colors">
                                Umroh Reguler
                            </a>
                        </li>

                        <li>
                            <a href="#" class="hover:text-hanania-gold transition-colors">
                                Umroh Plus Turki
                            </a>
                        </li>

                        <li>
                            <a href="#" class="hover:text-hanania-gold transition-colors">
                                Haji Furoda
                            </a>
                        </li>

                    </ul>

                </div>


                {{-- COMPANY --}}
                <div class="lg:col-span-2">

                    <h4 class="text-[11px] uppercase tracking-[0.16em] font-extrabold text-white mb-5">
                        Perusahaan
                    </h4>

                    <ul class="space-y-3 text-sm text-white/55">

                        <li>
                            <a href="{{ route('about.index') }}" class="hover:text-hanania-gold transition-colors">
                                Tentang Kami
                            </a>
                        </li>

                        <li>
                            <a href="#" class="hover:text-hanania-gold transition-colors">
                                Legalitas
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('terms') }}" class="hover:text-hanania-gold transition-colors">
                                Syarat & Ketentuan
                            </a>
                        </li>

                    </ul>

                </div>


                {{-- CONTACT --}}
                <div class="lg:col-span-3">

                    <h4 class="text-[11px] uppercase tracking-[0.16em] font-extrabold text-white mb-5">
                        Hubungi Kami
                    </h4>

                    <div class="space-y-4">

                        <div class="flex items-start gap-3">

                            <span class="material-symbols-outlined text-hanania-gold text-[18px]">
                                location_on
                            </span>

                            <span class="text-sm text-white/55 leading-relaxed">
                                {{ \App\Models\AppInformation::getValue(
                                    'office_address',
                                    'Jl. Raya Puncak KM 77, Cisarua, Bogor, Jawa Barat, Indonesia'
                                ) }}
                            </span>

                        </div>


                        <div class="flex items-center gap-3">

                            <span class="material-symbols-outlined text-hanania-gold text-[18px]">
                                call
                            </span>

                            <span class="text-sm text-white/55">
                                {{ \App\Models\AppInformation::getValue(
                                    'phone_number',
                                    '+62 812 3456 7890'
                                ) }}
                            </span>

                        </div>

                    </div>

                </div>

            </div>


            <div class="mt-12 pt-6 border-t border-white/10 flex flex-col md:flex-row items-center justify-between gap-4">

                <p class="text-[11px] text-white/35">
                    &copy; {{ date('Y') }} {{ $companyName }}. Hak Cipta Dilindungi.
                </p>


                <div class="flex items-center gap-4 text-white/25">

                    <span class="material-symbols-outlined text-[21px]">
                        payments
                    </span>

                    <span class="material-symbols-outlined text-[21px]">
                        account_balance
                    </span>

                    <span class="material-symbols-outlined text-[21px]">
                        verified_user
                    </span>

                </div>

            </div>

        </div>

    </footer>


    {{-- SIDEBAR --}}
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');

            if (!sidebar || !overlay) return;

            if (sidebar.classList.contains('translate-x-full')) {

                sidebar.classList.remove('translate-x-full');
                sidebar.classList.add('translate-x-0');

                overlay.classList.remove('hidden');

                setTimeout(() => {
                    overlay.classList.remove('opacity-0');
                    overlay.classList.add('opacity-100');
                }, 10);

            } else {

                sidebar.classList.remove('translate-x-0');
                sidebar.classList.add('translate-x-full');

                overlay.classList.remove('opacity-100');
                overlay.classList.add('opacity-0');

                setTimeout(() => {
                    overlay.classList.add('hidden');
                }, 300);

            }
        }
    </script>


    {{-- CAROUSEL --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const container = document.getElementById('slider-container');
            const prevBtn = document.getElementById('btn-prev');
            const nextBtn = document.getElementById('btn-next');
            const dots = document.querySelectorAll('.slider-dot');

            if (!container || !prevBtn || !nextBtn || !dots.length) {
                return;
            }

            let currentIndex = 0;

            const totalSlides = 5;

            let autoplay = setInterval(() => {
                updateSlider(
                    (currentIndex + 1) % totalSlides
                );
            }, 5000);


            function syncDots(index) {

                dots.forEach((dot, i) => {

                    if (i === index) {

                        dot.classList.remove(
                            'w-1.5',
                            'bg-white/50'
                        );

                        dot.classList.add(
                            'w-6',
                            'bg-hanania-gold'
                        );

                    } else {

                        dot.classList.remove(
                            'w-6',
                            'bg-hanania-gold'
                        );

                        dot.classList.add(
                            'w-1.5',
                            'bg-white/50'
                        );

                    }

                });

            }


            function restartAutoplay() {

                clearInterval(autoplay);

                autoplay = setInterval(() => {

                    updateSlider(
                        (currentIndex + 1) % totalSlides
                    );

                }, 5000);

            }


            function updateSlider(index) {

                const slideWidth =
                    container.clientWidth;

                container.scrollTo({
                    left: slideWidth * index,
                    behavior: 'smooth'
                });

                currentIndex = index;

                syncDots(index);

                restartAutoplay();

            }


            nextBtn.addEventListener(
                'click',
                () => updateSlider(
                    (currentIndex + 1) % totalSlides
                )
            );


            prevBtn.addEventListener(
                'click',
                () => updateSlider(
                    (currentIndex - 1 + totalSlides) % totalSlides
                )
            );


            dots.forEach((dot, index) => {

                dot.addEventListener(
                    'click',
                    () => updateSlider(index)
                );

            });


            container.addEventListener(
                'mouseenter',
                () => clearInterval(autoplay)
            );


            container.addEventListener(
                'mouseleave',
                restartAutoplay
            );

        });
    </script>


    <x-chat-widget />

</body>
</html>