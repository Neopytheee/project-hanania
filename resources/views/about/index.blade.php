@extends('layouts.customer.app')

@section('title', 'Tentang Kami')

@section('content')

    @php
        $companyName = \App\Models\AppInformation::getValue(
            'company_name',
            'Hanania'
        );

        $tagline = \App\Models\AppInformation::getValue(
            'company_tagline',
            'Wujudkan Niat Tanpa Beban'
        );

        $skKemenag = \App\Models\AppInformation::getValue(
            'sk_kemenag_number',
            'U.355 Tahun 2026'
        );

        $address = \App\Models\AppInformation::getValue(
            'office_address',
            'Jl. Raya Puncak KM 77, Bogor'
        );

        $email = \App\Models\AppInformation::getValue(
            'email_address',
            'info@hananiatravel.com'
        );

        $phone = \App\Models\AppInformation::getValue(
            'phone_number',
            '+62 812 3456 7890'
        );
    @endphp


    <div class="w-full min-h-screen bg-slate-50/50 pt-24 pb-16">


        {{-- =========================================================
             HERO
        ========================================================== --}}
        <section class="relative overflow-hidden rounded-[32px] bg-hanania-purple-dark mb-10">

            {{-- Subtle decorative shape --}}
            <div class="absolute -right-24 -top-24 w-72 h-72 rounded-full bg-hanania-gold/10 blur-3xl pointer-events-none"></div>
            <div class="absolute -left-24 -bottom-24 w-72 h-72 rounded-full bg-white/5 blur-3xl pointer-events-none"></div>

            <div class="relative z-10 grid grid-cols-1 lg:grid-cols-[1.3fr_0.7fr] gap-8 lg:gap-12 items-center p-7 sm:p-10 lg:p-14">

                {{-- HERO CONTENT --}}
                <div>

                    <span class="inline-flex items-center gap-2 text-[10px] sm:text-[11px] uppercase tracking-[0.18em] font-extrabold text-hanania-gold-light">
                        <span class="w-1.5 h-1.5 rounded-full bg-hanania-gold-light"></span>
                        Tentang {{ $companyName }}
                    </span>

                    <h1 class="mt-4 font-heading font-extrabold text-3xl sm:text-4xl lg:text-5xl text-white leading-[1.08] tracking-tight">
                        Mewujudkan niat ibadah
                        <span class="block text-hanania-gold-light">
                            dengan lebih terencana.
                        </span>
                    </h1>

                    <p class="mt-5 max-w-2xl text-sm sm:text-[15px] lg:text-base leading-relaxed text-white/75">
                        {{ $tagline }}.
                        {{ $companyName }} hadir untuk membantu perjalanan menuju Baitullah
                        menjadi lebih terencana, transparan, dan mudah dipersiapkan.
                    </p>

                    <div class="mt-7 flex flex-wrap gap-3">

                        <div class="inline-flex items-center gap-2 rounded-full bg-white/10 border border-white/10 px-4 py-2.5 text-xs font-bold text-white">
                            <span class="material-symbols-outlined text-[17px] text-hanania-gold-light">
                                verified
                            </span>
                            Terpercaya
                        </div>

                        <div class="inline-flex items-center gap-2 rounded-full bg-white/10 border border-white/10 px-4 py-2.5 text-xs font-bold text-white">
                            <span class="material-symbols-outlined text-[17px] text-hanania-gold-light">
                                visibility
                            </span>
                            Transparan
                        </div>

                        <div class="inline-flex items-center gap-2 rounded-full bg-white/10 border border-white/10 px-4 py-2.5 text-xs font-bold text-white">
                            <span class="material-symbols-outlined text-[17px] text-hanania-gold-light">
                                favorite
                            </span>
                            Pendampingan
                        </div>

                    </div>

                </div>


                {{-- HERO INFO --}}
                <div class="lg:justify-self-end w-full max-w-sm">

                    <div class="rounded-[24px] bg-white p-6 sm:p-7 shadow-xl">

                        <span class="text-[10px] uppercase tracking-[0.18em] font-extrabold text-hanania-gold">
                            Legalitas
                        </span>

                        <div class="mt-4 flex items-center gap-4">

                            <div class="w-14 h-14 rounded-2xl bg-hanania-purple-light flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-hanania-purple text-[30px]">
                                    verified_user
                                </span>
                            </div>

                            <div>
                                <p class="text-xs font-bold text-gray-500">
                                    Izin Kemenag RI
                                </p>

                                <p class="mt-1 font-heading text-xl font-extrabold text-hanania-purple-dark">
                                    {{ $skKemenag }}
                                </p>
                            </div>

                        </div>

                        <div class="mt-5 pt-5 border-t border-hanania-purple/10">
                            <p class="text-xs sm:text-[13px] text-gray-500 leading-relaxed">
                                {{ $companyName }} berkomitmen memberikan layanan yang
                                jelas, aman, dan bertanggung jawab dalam mendampingi
                                persiapan perjalanan ibadah.
                            </p>
                        </div>

                    </div>

                </div>

            </div>
        </section>


        {{-- =========================================================
             HOW IT WORKS
        ========================================================== --}}
        <section class="mb-12">

            <div class="max-w-2xl mb-6">

                <span class="text-[10px] sm:text-[11px] uppercase tracking-[0.18em] font-extrabold text-hanania-gold">
                    Cara Kami Membantu
                </span>

                <h2 class="mt-2 font-heading text-2xl sm:text-3xl font-extrabold text-hanania-purple-dark">
                    Perjalanan dimulai dari langkah sederhana.
                </h2>

                <p class="mt-2 text-sm text-gray-500 leading-relaxed">
                    Kami merancang proses yang mudah dipahami sehingga Anda dapat
                    mempersiapkan ibadah tanpa merasa terbebani.
                </p>

            </div>


            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                {{-- STEP 1 --}}
                <div class="card-hanania p-6">

                    <div class="flex items-center justify-between">

                        <div class="w-11 h-11 rounded-2xl bg-hanania-purple flex items-center justify-center text-white">
                            <span class="font-heading font-extrabold text-lg">
                                01
                            </span>
                        </div>

                        <span class="material-symbols-outlined text-hanania-gold text-[26px]">
                            savings
                        </span>

                    </div>

                    <h3 class="mt-6 font-heading text-lg font-extrabold text-hanania-purple-dark">
                        Pilih & Mulai
                    </h3>

                    <p class="mt-2 text-sm text-gray-500 leading-relaxed">
                        Pilih estimasi perjalanan yang sesuai dengan kemampuan Anda,
                        lalu mulai mempersiapkan dana secara bertahap.
                    </p>

                </div>


                {{-- STEP 2 --}}
                <div class="card-hanania p-6">

                    <div class="flex items-center justify-between">

                        <div class="w-11 h-11 rounded-2xl bg-hanania-purple flex items-center justify-center text-white">
                            <span class="font-heading font-extrabold text-lg">
                                02
                            </span>
                        </div>

                        <span class="material-symbols-outlined text-hanania-gold text-[26px]">
                            trending_up
                        </span>

                    </div>

                    <h3 class="mt-6 font-heading text-lg font-extrabold text-hanania-purple-dark">
                        Pantau Perkembangan
                    </h3>

                    <p class="mt-2 text-sm text-gray-500 leading-relaxed">
                        Lihat perkembangan persiapan Anda secara berkala melalui
                        sistem yang mudah dipantau.
                    </p>

                </div>


                {{-- STEP 3 --}}
                <div class="card-hanania p-6">

                    <div class="flex items-center justify-between">

                        <div class="w-11 h-11 rounded-2xl bg-hanania-gold flex items-center justify-center text-white">
                            <span class="font-heading font-extrabold text-lg">
                                03
                            </span>
                        </div>

                        <span class="material-symbols-outlined text-hanania-gold text-[26px]">
                            flight_takeoff
                        </span>

                    </div>

                    <h3 class="mt-6 font-heading text-lg font-extrabold text-hanania-purple-dark">
                        Siap Berangkat
                    </h3>

                    <p class="mt-2 text-sm text-gray-500 leading-relaxed">
                        Setelah persiapan selesai, Anda dapat melanjutkan proses
                        keberangkatan bersama mitra travel resmi.
                    </p>

                </div>

            </div>
        </section>


        {{-- =========================================================
             TRUST STATS
        ========================================================== --}}
        <section class="mb-12">

            <div class="rounded-[28px] bg-hanania-purple-light p-6 sm:p-8 border border-hanania-purple/10">

                <div class="grid grid-cols-2 lg:grid-cols-4 divide-x divide-y lg:divide-y-0 divide-hanania-purple/10">

                    <div class="px-4 py-4 text-center lg:px-6">
                        <p class="font-heading text-3xl sm:text-4xl font-extrabold text-hanania-purple-dark">
                            100%
                        </p>
                        <p class="mt-2 text-[10px] sm:text-[11px] uppercase tracking-[0.14em] font-bold text-gray-500">
                            Dana Terlindungi
                        </p>
                    </div>

                    <div class="px-4 py-4 text-center lg:px-6">
                        <p class="font-heading text-3xl sm:text-4xl font-extrabold text-hanania-purple-dark">
                            Rp 0
                        </p>
                        <p class="mt-2 text-[10px] sm:text-[11px] uppercase tracking-[0.14em] font-bold text-gray-500">
                            Potongan Admin
                        </p>
                    </div>

                    <div class="px-4 py-4 text-center lg:px-6">
                        <p class="font-heading text-3xl sm:text-4xl font-extrabold text-hanania-purple-dark">
                            1.250+
                        </p>
                        <p class="mt-2 text-[10px] sm:text-[11px] uppercase tracking-[0.14em] font-bold text-gray-500">
                            Jamaah Menabung
                        </p>
                    </div>

                    <div class="px-4 py-4 text-center lg:px-6">
                        <p class="font-heading text-3xl sm:text-4xl font-extrabold text-hanania-purple-dark">
                            5+
                        </p>
                        <p class="mt-2 text-[10px] sm:text-[11px] uppercase tracking-[0.14em] font-bold text-gray-500">
                            Mitra Travel
                        </p>
                    </div>

                </div>

            </div>
        </section>


        {{-- =========================================================
             VISI MISI + WHY US
        ========================================================== --}}
        <section class="mb-12">

            <div class="mb-6">

                <span class="text-[10px] sm:text-[11px] uppercase tracking-[0.18em] font-extrabold text-hanania-gold">
                    Nilai Kami
                </span>

                <h2 class="mt-2 font-heading text-2xl sm:text-3xl font-extrabold text-hanania-purple-dark">
                    Dibangun untuk kepercayaan jangka panjang.
                </h2>

            </div>


            <div class="grid grid-cols-1 lg:grid-cols-[1.1fr_0.9fr] gap-6">

                {{-- VISI MISI --}}
                <div class="card-hanania p-6 sm:p-8">

                    <div class="flex items-start justify-between gap-5">

                        <div>
                            <p class="text-[10px] uppercase tracking-[0.16em] font-extrabold text-hanania-gold">
                                Visi & Misi
                            </p>

                            <h3 class="mt-2 font-heading text-2xl font-extrabold text-hanania-purple-dark">
                                Memberikan kesempatan untuk mempersiapkan ibadah dengan lebih baik.
                            </h3>
                        </div>

                        <span class="material-symbols-outlined text-hanania-purple text-[30px] hidden sm:block">
                            auto_awesome
                        </span>

                    </div>

                    <p class="mt-5 text-sm text-gray-500 leading-relaxed">
                        Menjadi ekosistem perencanaan perjalanan ibadah terpercaya
                        di Indonesia yang membantu masyarakat mempersiapkan
                        perjalanan menuju Baitullah secara lebih terencana,
                        transparan, dan bertanggung jawab.
                    </p>

                    <div class="mt-7 space-y-4">

                        <div class="flex items-start gap-3">

                            <span class="material-symbols-outlined text-hanania-gold text-[20px] mt-0.5 shrink-0">
                                check_circle
                            </span>

                            <p class="text-sm text-hanania-purple-dark leading-relaxed font-medium">
                                Menyediakan fasilitas persiapan ibadah yang fleksibel.
                            </p>

                        </div>

                        <div class="flex items-start gap-3">

                            <span class="material-symbols-outlined text-hanania-gold text-[20px] mt-0.5 shrink-0">
                                check_circle
                            </span>

                            <p class="text-sm text-hanania-purple-dark leading-relaxed font-medium">
                                Mengedepankan transparansi harga dan riwayat transaksi.
                            </p>

                        </div>

                        <div class="flex items-start gap-3">

                            <span class="material-symbols-outlined text-hanania-gold text-[20px] mt-0.5 shrink-0">
                                check_circle
                            </span>

                            <p class="text-sm text-hanania-purple-dark leading-relaxed font-medium">
                                Berkolaborasi dengan biro perjalanan resmi dan terpercaya.
                            </p>

                        </div>

                    </div>

                </div>


                {{-- WHY US --}}
                <div class="rounded-[28px] bg-hanania-purple-dark p-6 sm:p-8">

                    <span class="text-[10px] uppercase tracking-[0.16em] font-extrabold text-hanania-gold-light">
                        Kenapa Kami
                    </span>

                    <h3 class="mt-2 font-heading text-2xl font-extrabold text-white">
                        Pendekatan yang sederhana, layanan yang serius.
                    </h3>

                    <div class="mt-7 space-y-6">

                        <div class="flex items-start gap-4">

                            <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-hanania-gold-light">
                                    verified_user
                                </span>
                            </div>

                            <div>
                                <h4 class="font-heading font-bold text-sm text-white">
                                    Aman & Terpercaya
                                </h4>

                                <p class="mt-1 text-xs sm:text-[13px] text-white/65 leading-relaxed">
                                    Mengutamakan keamanan dan transparansi dalam setiap proses.
                                </p>
                            </div>

                        </div>


                        <div class="flex items-start gap-4">

                            <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-hanania-gold-light">
                                    tune
                                </span>
                            </div>

                            <div>
                                <h4 class="font-heading font-bold text-sm text-white">
                                    Fleksibel
                                </h4>

                                <p class="mt-1 text-xs sm:text-[13px] text-white/65 leading-relaxed">
                                    Perencanaan dapat disesuaikan dengan kemampuan dan kebutuhan Anda.
                                </p>
                            </div>

                        </div>


                        <div class="flex items-start gap-4">

                            <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-hanania-gold-light">
                                    handshake
                                </span>
                            </div>

                            <div>
                                <h4 class="font-heading font-bold text-sm text-white">
                                    Mitra Resmi
                                </h4>

                                <p class="mt-1 text-xs sm:text-[13px] text-white/65 leading-relaxed">
                                    Terhubung dengan jaringan biro perjalanan yang sesuai untuk kebutuhan perjalanan Anda.
                                </p>
                            </div>

                        </div>

                    </div>

                </div>

            </div>
        </section>


        {{-- =========================================================
             TESTIMONIAL
        ========================================================== --}}
        <section class="mb-12">

            <div class="mb-6">

                <span class="text-[10px] sm:text-[11px] uppercase tracking-[0.18em] font-extrabold text-hanania-gold">
                    Cerita Jamaah
                </span>

                <h2 class="mt-2 font-heading text-2xl sm:text-3xl font-extrabold text-hanania-purple-dark">
                    Mereka yang sedang memperjuangkan Baitullah.
                </h2>

            </div>


            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- TESTIMONIAL 1 --}}
                <div class="card-hanania p-6 sm:p-7 relative overflow-hidden">

                    <span class="material-symbols-outlined absolute top-5 right-5 text-5xl text-hanania-gold/10">
                        format_quote
                    </span>

                    <p class="relative z-10 text-sm text-gray-600 leading-relaxed italic">
                        "Awalnya pesimis bisa umroh karena gaji UMR. Tapi lewat Hanania,
                        saya iseng nabung 20 ribu sehari. Nggak kerasa 2 tahun berlalu
                        dan alhamdulillah bulan depan saya berangkat!"
                    </p>

                    <div class="mt-6 flex items-center gap-3">

                        <div class="w-11 h-11 rounded-full bg-hanania-purple-light flex items-center justify-center text-sm font-extrabold text-hanania-purple">
                            R
                        </div>

                        <div>
                            <h4 class="font-heading text-sm font-extrabold text-hanania-purple-dark">
                                Ibu Rahmawati
                            </h4>

                            <p class="mt-0.5 text-[11px] font-bold text-hanania-gold">
                                Pegawai Swasta · Nabung 2 Tahun
                            </p>
                        </div>

                    </div>

                </div>


                {{-- TESTIMONIAL 2 --}}
                <div class="card-hanania p-6 sm:p-7 relative overflow-hidden">

                    <span class="material-symbols-outlined absolute top-5 right-5 text-5xl text-hanania-gold/10">
                        format_quote
                    </span>

                    <p class="relative z-10 text-sm text-gray-600 leading-relaxed italic">
                        "Sistem dashboard-nya transparan banget. Saya bisa mengecek
                        sisa target dan langsung dibantu proses keberangkatan saat
                        dana sudah lunas. Pelayanannya terasa sangat personal."
                    </p>

                    <div class="mt-6 flex items-center gap-3">

                        <div class="w-11 h-11 rounded-full bg-hanania-purple-light flex items-center justify-center text-sm font-extrabold text-hanania-purple">
                            A
                        </div>

                        <div>
                            <h4 class="font-heading text-sm font-extrabold text-hanania-purple-dark">
                                Bapak Anton
                            </h4>

                            <p class="mt-0.5 text-[11px] font-bold text-hanania-gold">
                                Wiraswasta · Nabung 1 Tahun
                            </p>
                        </div>

                    </div>

                </div>

            </div>
        </section>


        {{-- =========================================================
             LEGALITY + CONTACT
        ========================================================== --}}
        <section class="mb-12">

            <div class="mb-6">

                <span class="text-[10px] sm:text-[11px] uppercase tracking-[0.18em] font-extrabold text-hanania-gold">
                    Informasi Perusahaan
                </span>

                <h2 class="mt-2 font-heading text-2xl sm:text-3xl font-extrabold text-hanania-purple-dark">
                    Kenali kami lebih dekat.
                </h2>

            </div>


            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- LEGALITY --}}
                <div class="card-hanania p-6 sm:p-8">

                    <div class="flex items-center gap-3 mb-6">

                        <div class="w-11 h-11 rounded-2xl bg-hanania-purple-light flex items-center justify-center">
                            <span class="material-symbols-outlined text-hanania-purple text-[23px]">
                                gavel
                            </span>
                        </div>

                        <div>
                            <p class="text-[10px] uppercase tracking-[0.16em] font-extrabold text-hanania-gold">
                                Legalitas
                            </p>

                            <h3 class="font-heading text-lg font-extrabold text-hanania-purple-dark">
                                Perusahaan Terdaftar
                            </h3>
                        </div>

                    </div>

                    <div class="rounded-2xl bg-hanania-purple-light p-5">

                        <p class="text-[10px] uppercase tracking-[0.14em] font-extrabold text-hanania-purple">
                            Izin Kemenag RI
                        </p>

                        <p class="mt-2 font-heading text-xl font-extrabold text-hanania-purple-dark">
                            {{ $skKemenag }}
                        </p>

                    </div>

                    <p class="mt-5 text-sm text-gray-500 leading-relaxed">
                        {{ $companyName }} beroperasi sebagai jembatan yang membantu
                        jamaah mempersiapkan perjalanan ibadah bersama biro perjalanan
                        resmi yang sesuai dengan kebutuhan.
                    </p>

                </div>


                {{-- CONTACT --}}
                <div class="rounded-[28px] bg-hanania-purple-dark p-6 sm:p-8">

                    <div class="flex items-center gap-3 mb-7">

                        <div class="w-11 h-11 rounded-2xl bg-white/10 flex items-center justify-center">
                            <span class="material-symbols-outlined text-hanania-gold-light text-[23px]">
                                headset_mic
                            </span>
                        </div>

                        <div>
                            <p class="text-[10px] uppercase tracking-[0.16em] font-extrabold text-hanania-gold-light">
                                Hubungi Kami
                            </p>

                            <h3 class="font-heading text-lg font-extrabold text-white">
                                Tim Hanania
                            </h3>
                        </div>

                    </div>


                    <div class="space-y-5">

                        {{-- ADDRESS --}}
                        <div class="flex items-start gap-3">

                            <span class="material-symbols-outlined text-hanania-gold-light text-[19px] mt-0.5">
                                location_on
                            </span>

                            <div>
                                <p class="text-[10px] uppercase tracking-[0.14em] font-bold text-white/50">
                                    Kantor Pusat
                                </p>

                                <p class="mt-1 text-sm text-white/85 leading-relaxed">
                                    {{ $address }}
                                </p>
                            </div>

                        </div>


                        {{-- PHONE --}}
                        <div class="flex items-start gap-3">

                            <span class="material-symbols-outlined text-hanania-gold-light text-[19px] mt-0.5">
                                call
                            </span>

                            <div>
                                <p class="text-[10px] uppercase tracking-[0.14em] font-bold text-white/50">
                                    Layanan Pelanggan
                                </p>

                                <p class="mt-1 text-sm font-bold text-white">
                                    {{ $phone }}
                                </p>
                            </div>

                        </div>


                        {{-- EMAIL --}}
                        <div class="flex items-start gap-3">

                            <span class="material-symbols-outlined text-hanania-gold-light text-[19px] mt-0.5">
                                mail
                            </span>

                            <div>
                                <p class="text-[10px] uppercase tracking-[0.14em] font-bold text-white/50">
                                    Email
                                </p>

                                <p class="mt-1 text-sm font-bold text-white break-all">
                                    {{ $email }}
                                </p>
                            </div>

                        </div>

                    </div>

                </div>

            </div>
        </section>


        {{-- =========================================================
             ECOSYSTEM
        ========================================================== --}}
        <section>

            <div class="card-hanania p-6 sm:p-8">

                <div class="text-center max-w-xl mx-auto">

                    <span class="text-[10px] sm:text-[11px] uppercase tracking-[0.18em] font-extrabold text-hanania-gold">
                        Ekosistem
                    </span>

                    <h2 class="mt-2 font-heading text-xl sm:text-2xl font-extrabold text-hanania-purple-dark">
                        Dibangun bersama mitra terpercaya.
                    </h2>

                    <p class="mt-2 text-sm text-gray-500 leading-relaxed">
                        Kami percaya perjalanan ibadah yang baik lahir dari
                        kolaborasi dengan pihak-pihak yang memiliki komitmen yang sama.
                    </p>

                </div>


                <div class="mt-8 grid grid-cols-1 sm:grid-cols-3 gap-4">

                    <div class="rounded-2xl bg-gray-50 border border-gray-100 p-5 text-center">
                        <span class="material-symbols-outlined text-[32px] text-hanania-purple">
                            account_balance
                        </span>

                        <p class="mt-3 font-heading font-bold text-sm text-hanania-purple-dark">
                            Bank Syariah
                        </p>
                    </div>


                    <div class="rounded-2xl bg-gray-50 border border-gray-100 p-5 text-center">
                        <span class="material-symbols-outlined text-[32px] text-hanania-purple">
                            travel_explore
                        </span>

                        <p class="mt-3 font-heading font-bold text-sm text-hanania-purple-dark">
                            Travel PPIU Resmi
                        </p>
                    </div>


                    <div class="rounded-2xl bg-gray-50 border border-gray-100 p-5 text-center">
                        <span class="material-symbols-outlined text-[32px] text-hanania-purple">
                            verified
                        </span>

                        <p class="mt-3 font-heading font-bold text-sm text-hanania-purple-dark">
                            AMPHURI
                        </p>
                    </div>

                </div>

            </div>

        </section>

    </div>

@endsection