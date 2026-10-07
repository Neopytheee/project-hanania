<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="theme-color" content="#61398F">

    <title>Kontak Kami - Hanania</title>

    <!-- Fonts & Icons -->
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet"
    >

    <!-- Hanania App -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased min-h-screen overflow-x-hidden bg-hanania-purple-light/40">

    @php
        $waNumber = \App\Models\AppInformation::getValue(
            'whatsapp_number',
            '6281234567890'
        );

        $waMessage = \App\Models\AppInformation::getValue(
            'whatsapp_message',
            'Assalamu\'alaikum Admin Hanania, saya ingin bertanya seputar paket Umroh.'
        );

        $phoneNumber = \App\Models\AppInformation::getValue(
            'phone_number',
            '+6281234567890'
        );

        $emailAddress = \App\Models\AppInformation::getValue(
            'email_address',
            'info@hananiaumroh.com'
        );

        $officeAddress = \App\Models\AppInformation::getValue(
            'office_address',
            "Gedung Hanania Tower, Lt. 5\nJl. Jend. Sudirman No. 123\nJakarta Selatan, 12190"
        );

        $operationalHours = \App\Models\AppInformation::getValue(
            'operational_hours',
            "Senin - Jumat: 08:00 - 17:00 WIB\nSabtu: 09:00 - 13:00 WIB"
        );

        $googleMapsLink = \App\Models\AppInformation::getValue(
            'google_maps_link',
            config('services.google_maps.default_url')
        );
    @endphp

    <div class="min-h-screen">

        {{-- =========================================================
             TOP HEADER
        ========================================================== --}}
        <header class="sticky top-0 z-40 bg-white/90 backdrop-blur-xl border-b border-hanania-purple/10">

            <div class="max-w-5xl mx-auto px-4 sm:px-6">

                <div class="h-[72px] flex items-center justify-between">

                    {{-- TITLE --}}
                    <div class="absolute left-1/2 -translate-x-1/2 text-center">
                        <p class="text-[10px] sm:text-[11px] uppercase tracking-[0.18em] font-extrabold text-hanania-gold">
                            Hanania
                        </p>

                        <h1 class="font-heading text-base sm:text-lg font-extrabold text-hanania-purple-dark">
                            Pusat Bantuan
                        </h1>
                    </div>

                    {{-- SPACER --}}
                    <div class="w-9 sm:w-20"></div>

                </div>
            </div>
        </header>


        {{-- =========================================================
             MAIN
        ========================================================== --}}
        <main class="max-w-5xl mx-auto px-4 sm:px-6 py-8 sm:py-10 lg:py-12">

            {{-- HERO --}}
            <section class="grid grid-cols-1 lg:grid-cols-[1.1fr_0.9fr] gap-6 lg:gap-8 items-stretch mb-10">

                {{-- HERO COPY --}}
                <div class="flex flex-col justify-center px-1 sm:px-2 lg:px-4">
                        
                {{-- BACK --}}
                <div class="mb-8">
                    <a href="{{ route('customer.profile.index') }}"
                    class="inline-flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-hanania-purple transition-colors">
                        <span class="material-symbols-outlined text-[19px]">
                            arrow_back
                        </span>
                        Kembali ke Profil
                    </a>
                </div>

                    <span class="inline-flex items-center gap-2 w-fit text-[10px] sm:text-[11px] uppercase tracking-[0.18em] font-extrabold text-hanania-gold">
                        <span class="w-1.5 h-1.5 rounded-full bg-hanania-gold"></span>
                        Layanan Hanania
                    </span>

                    <h2 class="mt-3 font-heading font-extrabold text-3xl sm:text-4xl lg:text-[44px] leading-[1.08] text-hanania-purple-dark">
                        Kami siap membantu<br class="hidden sm:block">
                        perjalanan ibadah Anda.
                    </h2>

                    <p class="mt-4 max-w-xl text-sm sm:text-[15px] text-gray-500 leading-relaxed">
                        Butuh informasi paket, jadwal keberangkatan, atau bantuan pendaftaran?
                        Hubungi tim Hanania melalui channel yang paling nyaman untuk Anda.
                    </p>

                    <div class="mt-6 flex flex-wrap gap-3">

                        <span class="inline-flex items-center gap-2 rounded-full bg-white px-3.5 py-2 text-xs font-bold text-hanania-purple-dark border border-hanania-purple/10 shadow-sm">
                            <span class="material-symbols-outlined text-[17px] text-hanania-gold">
                                support_agent
                            </span>
                            Tim Siap Membantu
                        </span>

                        <span class="inline-flex items-center gap-2 rounded-full bg-white px-3.5 py-2 text-xs font-bold text-hanania-purple-dark border border-hanania-purple/10 shadow-sm">
                            <span class="material-symbols-outlined text-[17px] text-hanania-gold">
                                schedule
                            </span>
                            Jam Operasional
                        </span>

                    </div>
                </div>


                {{-- AI CARD --}}
                <a
                    href="#"
                    class="group relative overflow-hidden rounded-[28px] bg-hanania-purple-dark p-6 sm:p-7 lg:p-8 shadow-xl border border-hanania-gold/20 hover:-translate-y-1 hover:shadow-2xl transition-all"
                >

                    <div class="absolute -right-16 -top-16 w-44 h-44 rounded-full bg-hanania-gold/10 blur-3xl pointer-events-none"></div>
                    <div class="absolute -left-16 -bottom-16 w-40 h-40 rounded-full bg-white/5 blur-3xl pointer-events-none"></div>

                    <div class="relative z-10 h-full flex flex-col justify-between">

                        <div class="flex items-start justify-between gap-4">

                            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-hanania-gold flex items-center justify-center shadow-lg shrink-0">
                                <span class="material-symbols-outlined text-white text-[28px]">
                                    smart_toy
                                </span>
                            </div>

                            <span class="inline-flex items-center gap-1.5 rounded-full bg-white/10 border border-white/10 px-3 py-1.5 text-[10px] font-extrabold uppercase tracking-wider text-white/80">
                                <span class="w-1.5 h-1.5 rounded-full bg-hanania-gold-light"></span>
                                24/7
                            </span>

                        </div>

                        <div class="mt-8">

                            <p class="text-[10px] uppercase tracking-[0.18em] font-extrabold text-hanania-gold-light">
                                Asisten Digital
                            </p>

                            <h3 class="mt-2 font-heading text-2xl font-extrabold text-white">
                                Hanania AI
                            </h3>

                            <p class="mt-2 text-sm leading-relaxed text-white/75 max-w-md">
                                Tanya seputar program, jadwal, fasilitas, dan proses pendaftaran secara instan.
                            </p>

                            <span class="mt-5 inline-flex items-center gap-2 rounded-xl bg-white px-4 py-3 text-sm font-extrabold text-hanania-purple-dark group-hover:bg-hanania-gold group-hover:text-white transition-colors">
                                Mulai Percakapan
                                <span class="material-symbols-outlined text-[18px] group-hover:translate-x-0.5 transition-transform">
                                    arrow_forward
                                </span>
                            </span>

                        </div>

                    </div>
                </a>

            </section>


            {{-- =========================================================
                 CONTACT CHANNELS
            ========================================================== --}}
            <section class="mb-10">

                <div class="mb-5">

                    <span class="text-[10px] sm:text-[11px] uppercase tracking-[0.18em] font-extrabold text-hanania-gold">
                        Pilih Cara Terbaik
                    </span>

                    <h3 class="mt-1.5 font-heading text-2xl font-extrabold text-hanania-purple-dark">
                        Hubungi Hanania
                    </h3>

                </div>


                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    {{-- WHATSAPP --}}
                    <a
                        href="{{ rtrim(config('services.whatsapp.web_url'), '/') }}/{{ $waNumber }}?text={{ urlencode($waMessage) }}"
                        target="_blank"
                        class="group card-hanania p-5 hover:-translate-y-1"
                    >
                        <div class="flex items-start justify-between gap-4">

                            <div class="w-11 h-11 rounded-2xl bg-hanania-purple-light flex items-center justify-center shrink-0 group-hover:bg-hanania-purple transition-colors">
                                <span class="material-symbols-outlined text-hanania-purple text-[22px] group-hover:text-white transition-colors">
                                    chat
                                </span>
                            </div>

                            <span class="material-symbols-outlined text-gray-300 group-hover:text-hanania-purple group-hover:translate-x-0.5 transition-all">
                                arrow_outward
                            </span>

                        </div>

                        <div class="mt-5">
                            <p class="font-heading text-base font-extrabold text-hanania-purple-dark">
                                WhatsApp
                            </p>

                            <p class="mt-1 text-xs sm:text-[13px] text-gray-500 leading-relaxed">
                                Respons cepat melalui chat.
                            </p>
                        </div>

                        <div class="mt-4 pt-4 border-t border-hanania-purple/10">
                            <span class="text-xs font-bold text-hanania-purple">
                                Chat dengan Admin
                            </span>
                        </div>
                    </a>


                    {{-- PHONE --}}
                    <a
                        href="tel:{{ $phoneNumber }}"
                        class="group card-hanania p-5 hover:-translate-y-1"
                    >
                        <div class="flex items-start justify-between gap-4">

                            <div class="w-11 h-11 rounded-2xl bg-hanania-purple-light flex items-center justify-center shrink-0 group-hover:bg-hanania-purple transition-colors">
                                <span class="material-symbols-outlined text-hanania-purple text-[22px] group-hover:text-white transition-colors">
                                    phone_in_talk
                                </span>
                            </div>

                            <span class="material-symbols-outlined text-gray-300 group-hover:text-hanania-purple group-hover:translate-x-0.5 transition-all">
                                arrow_outward
                            </span>

                        </div>

                        <div class="mt-5">
                            <p class="font-heading text-base font-extrabold text-hanania-purple-dark">
                                Telepon
                            </p>

                            <p class="mt-1 text-xs sm:text-[13px] text-gray-500 leading-relaxed break-all">
                                {{ $phoneNumber }}
                            </p>
                        </div>

                        <div class="mt-4 pt-4 border-t border-hanania-purple/10">
                            <span class="text-xs font-bold text-hanania-purple">
                                Hubungi Sekarang
                            </span>
                        </div>
                    </a>


                    {{-- EMAIL --}}
                    <a
                        href="mailto:{{ $emailAddress }}?subject=Pertanyaan%20Layanan%20Hanania"
                        class="group card-hanania p-5 hover:-translate-y-1"
                    >
                        <div class="flex items-start justify-between gap-4">

                            <div class="w-11 h-11 rounded-2xl bg-hanania-purple-light flex items-center justify-center shrink-0 group-hover:bg-hanania-purple transition-colors">
                                <span class="material-symbols-outlined text-hanania-purple text-[22px] group-hover:text-white transition-colors">
                                    mail
                                </span>
                            </div>

                            <span class="material-symbols-outlined text-gray-300 group-hover:text-hanania-purple group-hover:translate-x-0.5 transition-all">
                                arrow_outward
                            </span>

                        </div>

                        <div class="mt-5">
                            <p class="font-heading text-base font-extrabold text-hanania-purple-dark">
                                Email
                            </p>

                            <p class="mt-1 text-xs sm:text-[13px] text-gray-500 leading-relaxed break-all">
                                {{ $emailAddress }}
                            </p>
                        </div>

                        <div class="mt-4 pt-4 border-t border-hanania-purple/10">
                            <span class="text-xs font-bold text-hanania-purple">
                                Kirim Pertanyaan
                            </span>
                        </div>
                    </a>

                </div>

            </section>


            {{-- =========================================================
                 OFFICE INFORMATION
            ========================================================== --}}
            <section>

                <div class="mb-5">

                    <span class="text-[10px] sm:text-[11px] uppercase tracking-[0.18em] font-extrabold text-hanania-gold">
                        Kunjungi Kami
                    </span>

                    <h3 class="mt-1.5 font-heading text-2xl font-extrabold text-hanania-purple-dark">
                        Informasi Kantor
                    </h3>

                </div>


                <div class="grid grid-cols-1 lg:grid-cols-[0.9fr_1.1fr] gap-5">

                    {{-- OFFICE DETAILS --}}
                    <div class="card-hanania p-6 sm:p-7">

                        {{-- ADDRESS --}}
                        <div class="flex items-start gap-4">

                            <div class="w-11 h-11 rounded-2xl bg-hanania-purple-light flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-hanania-purple text-[21px]">
                                    location_on
                                </span>
                            </div>

                            <div class="min-w-0">

                                <p class="text-[10px] uppercase tracking-[0.16em] font-extrabold text-hanania-gold">
                                    Alamat Pusat
                                </p>

                                <p class="mt-2 text-sm text-hanania-purple-dark leading-relaxed font-medium">
                                    {!! nl2br(e($officeAddress)) !!}
                                </p>

                            </div>

                        </div>


                        <div class="h-px bg-hanania-purple/10 my-6"></div>


                        {{-- OPERATIONAL --}}
                        <div class="flex items-start gap-4">

                            <div class="w-11 h-11 rounded-2xl bg-hanania-purple-light flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-hanania-purple text-[21px]">
                                    schedule
                                </span>
                            </div>

                            <div class="min-w-0">

                                <p class="text-[10px] uppercase tracking-[0.16em] font-extrabold text-hanania-gold">
                                    Jam Operasional
                                </p>

                                <p class="mt-2 text-sm text-hanania-purple-dark leading-[1.7] font-medium">
                                    {!! nl2br(e($operationalHours)) !!}
                                </p>

                                <span class="inline-flex mt-3 rounded-lg bg-red-50 px-2.5 py-1 text-[11px] font-bold text-red-600 border border-red-100">
                                    Minggu & Libur Nasional: Tutup
                                </span>

                            </div>

                        </div>

                    </div>


                    {{-- MAP --}}
                    <a
                        href="{{ $googleMapsLink }}"
                        target="_blank"
                        class="group relative min-h-[280px] lg:min-h-full overflow-hidden rounded-[24px] border border-hanania-purple/10 bg-gray-200 shadow-sm"
                    >

                        <img
                            src="https://images.unsplash.com/photo-1524661135-423995f22d0b?q=80&w=1200&auto=format&fit=crop"
                            alt="Peta Lokasi Kantor"
                            class="absolute inset-0 w-full h-full object-cover grayscale group-hover:grayscale-0 group-hover:scale-105 transition-all duration-700"
                        >

                        <div class="absolute inset-0 bg-hanania-purple-dark/20 group-hover:bg-hanania-purple-dark/10 transition-colors"></div>

                        <div class="absolute inset-x-0 bottom-0 p-5 sm:p-6">

                            <div class="flex items-end justify-between gap-4">

                                <div>
                                    <p class="text-[10px] uppercase tracking-[0.16em] font-extrabold text-white/70">
                                        Lokasi Kantor
                                    </p>

                                    <p class="mt-1 font-heading text-lg font-extrabold text-white">
                                        Hanania
                                    </p>
                                </div>

                                <span class="btn-hanania-gold rounded-xl px-4 py-3 text-xs sm:text-sm shrink-0">
                                    <span class="material-symbols-outlined text-[17px]">
                                        map
                                    </span>
                                    Buka Maps
                                </span>

                            </div>

                        </div>

                    </a>

                </div>

            </section>

        </main>

    </div>

</body>
</html>