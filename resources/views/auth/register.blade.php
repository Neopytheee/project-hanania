@php
    $companyName = \App\Models\AppInformation::getValue(
        'company_name',
        'Hanania Travel'
    );
@endphp

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="utf-8">

    <meta
        content="width=device-width, initial-scale=1.0"
        name="viewport"
    >

    <meta
        name="theme-color"
        content="#61398F"
    >

    <title>
        Daftar Akun - {{ $companyName }}
    </title>


    {{-- Fonts & Icons --}}
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet"
    >


    {{-- Hanania App --}}
    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

</head>


<body class="antialiased selection:bg-hanania-gold selection:text-white overflow-hidden bg-hanania-purple-light">

    {{-- =========================================================
        SPLIT REGISTER
    ========================================================== --}}
    <div class="flex h-screen w-full">


        {{-- =====================================================
            FORM SIDE
            MOBILE TETAP FULL WIDTH
        ====================================================== --}}
        <div class="w-full lg:w-[56%] xl:w-1/2 h-full overflow-y-auto bg-white">

            <div class="min-h-full px-5 py-8 sm:px-8 sm:py-10 lg:px-12 xl:px-16">

                <div class="w-full max-w-[620px] mx-auto">


                    {{-- =================================================
                        MOBILE BRAND
                    ================================================== --}}
                    <div class="lg:hidden mb-8">

                        <div class="flex items-center gap-3">

                            <div class="w-11 h-11 rounded-2xl bg-hanania-purple-light flex items-center justify-center border border-hanania-purple/10">

                                <span class="material-symbols-outlined text-[24px] text-hanania-purple">
                                    mosque
                                </span>

                            </div>

                            <div>

                                <p class="font-heading text-lg font-extrabold text-hanania-purple-dark">
                                    {{ $companyName }}
                                </p>

                                <p class="text-[10px] uppercase tracking-[0.18em] text-gray-400 font-bold">
                                    Pendaftaran Jamaah
                                </p>

                            </div>

                        </div>

                    </div>



                    {{-- =================================================
                        HEADER
                    ================================================== --}}
                    <div class="mb-8 sm:mb-10 text-center lg:text-left">

                        <span class="inline-flex items-center gap-2 text-[10px] sm:text-[11px] uppercase tracking-[0.18em] font-extrabold text-hanania-gold">

                            <span class="w-1.5 h-1.5 rounded-full bg-hanania-gold"></span>

                            Langkah Awal

                        </span>


                        <h1 class="mt-3 font-heading text-3xl sm:text-[38px] font-extrabold text-hanania-purple-dark leading-none tracking-tight">

                            Pendaftaran Jamaah

                        </h1>


                        <p class="mt-4 text-sm sm:text-[15px] text-gray-500 leading-relaxed max-w-2xl">
                            Lengkapi data diri Anda sesuai KTP asli agar proses
                            administrasi dan manifest keberangkatan dapat diproses dengan benar.
                        </p>

                    </div>



                    {{-- =================================================
                        ERROR
                    ================================================== --}}
                    @if($errors->any())

                        <div class="mb-8 rounded-2xl border border-red-200 bg-red-50 px-4 py-4 sm:px-5 sm:py-5">

                            <div class="flex items-start gap-3">

                                <span class="material-symbols-outlined text-red-500 text-[21px] shrink-0">
                                    error
                                </span>

                                <div class="flex-1">

                                    <p class="font-heading text-sm font-extrabold text-red-800">
                                        Periksa kembali data Anda
                                    </p>

                                    <ul class="mt-2 space-y-1 text-xs sm:text-[13px] text-red-700 leading-relaxed">
                                        @foreach($errors->all() as $error)
                                            <li>
                                                {{ $error }}
                                            </li>
                                        @endforeach
                                    </ul>

                                </div>

                            </div>

                        </div>

                    @endif



                    {{-- =================================================
                        FORM
                    ================================================== --}}
                    <form
                        action="{{ route('register') }}"
                        method="POST"
                        class="space-y-8"
                    >
                        @csrf


                        {{-- =================================================
                            ACCOUNT SECTION
                        ================================================== --}}
                        <section>

                            <div class="mb-5">

                                <div class="flex items-center gap-3">

                                    <div class="w-10 h-10 rounded-xl bg-hanania-purple-light flex items-center justify-center shrink-0">

                                        <span class="material-symbols-outlined text-[21px] text-hanania-purple">
                                            account_circle
                                        </span>

                                    </div>

                                    <div>

                                        <p class="text-[10px] uppercase tracking-[0.16em] font-extrabold text-hanania-gold">
                                            01
                                        </p>

                                        <h2 class="font-heading text-lg font-extrabold text-hanania-purple-dark">
                                            Informasi Akun
                                        </h2>

                                    </div>

                                </div>

                            </div>


                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                                {{-- EMAIL --}}
                                <div>

                                    <label
                                        for="email"
                                        class="block mb-2 font-heading text-sm font-extrabold text-hanania-purple-dark"
                                    >
                                        Email Aktif
                                    </label>

                                    <div class="relative group">

                                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-hanania-purple transition-colors text-[20px] pointer-events-none">
                                            mail
                                        </span>

                                        <input
                                            id="email"
                                            type="email"
                                            name="email"
                                            value="{{ old('email') }}"
                                            placeholder="email@anda.com"
                                            required
                                            autocomplete="email"
                                            class="w-full rounded-2xl border border-hanania-purple/15 bg-gray-50/70
                                                   pl-12 pr-4 py-4
                                                   text-[15px] font-semibold text-hanania-purple-dark
                                                   placeholder:text-gray-400
                                                   outline-none transition-all
                                                   focus:border-hanania-purple
                                                   focus:bg-white
                                                   focus:ring-4 focus:ring-hanania-purple-light"
                                        >

                                    </div>

                                </div>


                                {{-- PASSWORD --}}
                                <div>

                                    <label
                                        for="password"
                                        class="block mb-2 font-heading text-sm font-extrabold text-hanania-purple-dark"
                                    >
                                        Kata Sandi

                                        <span class="text-gray-400 font-medium text-xs">
                                            (min. 8 karakter)
                                        </span>
                                    </label>

                                    <div class="relative group">

                                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-hanania-purple transition-colors text-[20px] pointer-events-none">
                                            lock
                                        </span>

                                        <input
                                            id="password"
                                            type="password"
                                            name="password"
                                            placeholder="Masukkan kata sandi"
                                            required
                                            minlength="8"
                                            autocomplete="new-password"
                                            class="w-full rounded-2xl border border-hanania-purple/15 bg-gray-50/70
                                                   pl-12 pr-4 py-4
                                                   text-[15px] font-semibold text-hanania-purple-dark
                                                   placeholder:text-gray-400
                                                   outline-none transition-all
                                                   focus:border-hanania-purple
                                                   focus:bg-white
                                                   focus:ring-4 focus:ring-hanania-purple-light"
                                        >

                                    </div>

                                </div>

                            </div>

                        </section>



                        {{-- DIVIDER --}}
                        <div class="h-px bg-hanania-purple/10"></div>



                        {{-- =================================================
                            PERSONAL DATA
                        ================================================== --}}
                        <section>

                            <div class="mb-5">

                                <div class="flex items-center gap-3">

                                    <div class="w-10 h-10 rounded-xl bg-hanania-purple-light flex items-center justify-center shrink-0">

                                        <span class="material-symbols-outlined text-[21px] text-hanania-purple">
                                            badge
                                        </span>

                                    </div>

                                    <div>

                                        <p class="text-[10px] uppercase tracking-[0.16em] font-extrabold text-hanania-gold">
                                            02
                                        </p>

                                        <h2 class="font-heading text-lg font-extrabold text-hanania-purple-dark">
                                            Biodata Diri
                                        </h2>

                                    </div>

                                </div>

                            </div>


                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                                {{-- NAME --}}
                                <div class="md:col-span-2">

                                    <label
                                        for="name"
                                        class="block mb-2 font-heading text-sm font-extrabold text-hanania-purple-dark"
                                    >
                                        Nama Lengkap
                                    </label>

                                    <p class="mb-2 text-xs text-gray-400">
                                        Sesuai dengan KTP
                                    </p>

                                    <div class="relative group">

                                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-hanania-purple transition-colors text-[20px] pointer-events-none">
                                            person
                                        </span>

                                        <input
                                            id="name"
                                            type="text"
                                            name="name"
                                            value="{{ old('name') }}"
                                            placeholder="Nama lengkap Anda"
                                            required
                                            autocomplete="name"
                                            class="w-full rounded-2xl border border-hanania-purple/15 bg-gray-50/70
                                                   pl-12 pr-4 py-4
                                                   text-[15px] font-semibold text-hanania-purple-dark
                                                   placeholder:text-gray-400
                                                   outline-none transition-all
                                                   focus:border-hanania-purple
                                                   focus:bg-white
                                                   focus:ring-4 focus:ring-hanania-purple-light"
                                        >

                                    </div>

                                </div>


                                {{-- NIK --}}
                                <div>

                                    <label
                                        for="nik"
                                        class="block mb-2 font-heading text-sm font-extrabold text-hanania-purple-dark"
                                    >
                                        NIK
                                    </label>

                                    <p class="mb-2 text-xs text-gray-400">
                                        16 digit sesuai KTP
                                    </p>

                                    <div class="relative group">

                                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-hanania-purple transition-colors text-[20px] pointer-events-none">
                                            branding_watermark
                                        </span>

                                        <input
                                            id="nik"
                                            type="number"
                                            name="nik"
                                            value="{{ old('nik') }}"
                                            placeholder="16 digit NIK"
                                            required
                                            class="w-full rounded-2xl border border-hanania-purple/15 bg-gray-50/70
                                                   pl-12 pr-4 py-4
                                                   text-[15px] font-semibold text-hanania-purple-dark
                                                   placeholder:text-gray-400
                                                   outline-none transition-all
                                                   focus:border-hanania-purple
                                                   focus:bg-white
                                                   focus:ring-4 focus:ring-hanania-purple-light"
                                        >

                                    </div>

                                </div>


                                {{-- PHONE --}}
                                <div>

                                    <label
                                        for="phone"
                                        class="block mb-2 font-heading text-sm font-extrabold text-hanania-purple-dark"
                                    >
                                        No. WhatsApp
                                    </label>

                                    <p class="mb-2 text-xs text-gray-400">
                                        Pastikan nomor aktif
                                    </p>

                                    <div class="relative group">

                                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-hanania-purple transition-colors text-[20px] pointer-events-none">
                                            call
                                        </span>

                                        <input
                                            id="phone"
                                            type="number"
                                            name="phone"
                                            value="{{ old('phone') }}"
                                            placeholder="0812xxxxxx"
                                            required
                                            autocomplete="tel"
                                            class="w-full rounded-2xl border border-hanania-purple/15 bg-gray-50/70
                                                   pl-12 pr-4 py-4
                                                   text-[15px] font-semibold text-hanania-purple-dark
                                                   placeholder:text-gray-400
                                                   outline-none transition-all
                                                   focus:border-hanania-purple
                                                   focus:bg-white
                                                   focus:ring-4 focus:ring-hanania-purple-light"
                                        >

                                    </div>

                                </div>


                                {{-- GENDER --}}
                                <div>

                                    <label
                                        for="gender"
                                        class="block mb-2 font-heading text-sm font-extrabold text-hanania-purple-dark"
                                    >
                                        Jenis Kelamin
                                    </label>

                                    <p class="mb-2 text-xs text-gray-400">
                                        Pilih sesuai identitas
                                    </p>

                                    <div class="relative group">

                                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-hanania-purple transition-colors text-[20px] pointer-events-none">
                                            wc
                                        </span>

                                        <select
                                            id="gender"
                                            name="gender"
                                            required
                                            class="w-full appearance-none rounded-2xl border border-hanania-purple/15 bg-gray-50/70
                                                   pl-12 pr-10 py-4
                                                   text-[15px] font-semibold text-hanania-purple-dark
                                                   outline-none transition-all cursor-pointer
                                                   focus:border-hanania-purple
                                                   focus:bg-white
                                                   focus:ring-4 focus:ring-hanania-purple-light"
                                        >

                                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>
                                                Laki-laki
                                            </option>

                                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>
                                                Perempuan
                                            </option>

                                        </select>

                                        <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-[19px] pointer-events-none">
                                            expand_more
                                        </span>

                                    </div>

                                </div>


                                {{-- BIRTH DATE --}}
                                <div>

                                    <label
                                        for="birth_date"
                                        class="block mb-2 font-heading text-sm font-extrabold text-hanania-purple-dark"
                                    >
                                        Tanggal Lahir
                                    </label>

                                    <p class="mb-2 text-xs text-gray-400">
                                        Sesuai identitas
                                    </p>

                                    <div class="relative group">

                                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-hanania-purple transition-colors text-[20px] pointer-events-none">
                                            calendar_month
                                        </span>

                                        <input
                                            id="birth_date"
                                            type="date"
                                            name="birth_date"
                                            value="{{ old('birth_date') }}"
                                            required
                                            class="w-full rounded-2xl border border-hanania-purple/15 bg-gray-50/70
                                                   pl-12 pr-4 py-4
                                                   text-[15px] font-semibold text-hanania-purple-dark
                                                   outline-none transition-all
                                                   focus:border-hanania-purple
                                                   focus:bg-white
                                                   focus:ring-4 focus:ring-hanania-purple-light"
                                        >

                                    </div>

                                </div>


                                {{-- ADDRESS --}}
                                <div class="md:col-span-2">

                                    <label
                                        for="address"
                                        class="block mb-2 font-heading text-sm font-extrabold text-hanania-purple-dark"
                                    >
                                        Alamat Lengkap
                                    </label>

                                    <p class="mb-2 text-xs text-gray-400">
                                        Alamat domisili saat ini
                                    </p>

                                    <div class="relative group">

                                        <span class="material-symbols-outlined absolute top-4 left-4 text-gray-400 group-focus-within:text-hanania-purple transition-colors text-[20px] pointer-events-none">
                                            location_on
                                        </span>

                                        <textarea
                                            id="address"
                                            name="address"
                                            rows="4"
                                            required
                                            placeholder="Masukkan alamat lengkap Anda..."
                                            class="w-full rounded-2xl border border-hanania-purple/15 bg-gray-50/70
                                                   pl-12 pr-4 py-4
                                                   text-[15px] font-semibold text-hanania-purple-dark
                                                   placeholder:text-gray-400
                                                   leading-relaxed
                                                   outline-none transition-all resize-y
                                                   focus:border-hanania-purple
                                                   focus:bg-white
                                                   focus:ring-4 focus:ring-hanania-purple-light"
                                        >{{ old('address') }}</textarea>

                                    </div>

                                </div>

                            </div>

                        </section>



                        {{-- DIVIDER --}}
                        <div class="h-px bg-hanania-purple/10"></div>



                        {{-- =================================================
                            EMERGENCY CONTACT
                        ================================================== --}}
                        <section>

                            <div class="mb-5">

                                <div class="flex items-center gap-3">

                                    <div class="w-10 h-10 rounded-xl bg-hanania-purple-light flex items-center justify-center shrink-0">

                                        <span class="material-symbols-outlined text-[21px] text-hanania-purple">
                                            emergency
                                        </span>

                                    </div>

                                    <div>

                                        <p class="text-[10px] uppercase tracking-[0.16em] font-extrabold text-hanania-gold">
                                            03
                                        </p>

                                        <h2 class="font-heading text-lg font-extrabold text-hanania-purple-dark">
                                            Kontak Darurat
                                        </h2>

                                    </div>

                                </div>

                                <p class="mt-3 text-xs sm:text-[13px] text-gray-500 leading-relaxed">
                                    Data ini digunakan sebagai kontak yang dapat dihubungi
                                    apabila terdapat kebutuhan penting selama proses perjalanan.
                                </p>

                            </div>


                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                                {{-- EMERGENCY NAME --}}
                                <div>

                                    <label
                                        for="emergency_contact_name"
                                        class="block mb-2 font-heading text-sm font-extrabold text-hanania-purple-dark"
                                    >
                                        Nama Kontak Darurat
                                    </label>

                                    <div class="relative group">

                                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-hanania-purple transition-colors text-[20px] pointer-events-none">
                                            group
                                        </span>

                                        <input
                                            id="emergency_contact_name"
                                            type="text"
                                            name="emergency_contact_name"
                                            value="{{ old('emergency_contact_name') }}"
                                            placeholder="Nama keluarga / kerabat"
                                            required
                                            class="w-full rounded-2xl border border-hanania-purple/15 bg-gray-50/70
                                                   pl-12 pr-4 py-4
                                                   text-[15px] font-semibold text-hanania-purple-dark
                                                   placeholder:text-gray-400
                                                   outline-none transition-all
                                                   focus:border-hanania-purple
                                                   focus:bg-white
                                                   focus:ring-4 focus:ring-hanania-purple-light"
                                        >

                                    </div>

                                </div>


                                {{-- EMERGENCY PHONE --}}
                                <div>

                                    <label
                                        for="emergency_contact_phone"
                                        class="block mb-2 font-heading text-sm font-extrabold text-hanania-purple-dark"
                                    >
                                        No. HP Darurat
                                    </label>

                                    <div class="relative group">

                                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-hanania-purple transition-colors text-[20px] pointer-events-none">
                                            phone_in_talk
                                        </span>

                                        <input
                                            id="emergency_contact_phone"
                                            type="number"
                                            name="emergency_contact_phone"
                                            value="{{ old('emergency_contact_phone') }}"
                                            placeholder="0812xxxxxx"
                                            required
                                            class="w-full rounded-2xl border border-hanania-purple/15 bg-gray-50/70
                                                   pl-12 pr-4 py-4
                                                   text-[15px] font-semibold text-hanania-purple-dark
                                                   placeholder:text-gray-400
                                                   outline-none transition-all
                                                   focus:border-hanania-purple
                                                   focus:bg-white
                                                   focus:ring-4 focus:ring-hanania-purple-light"
                                        >

                                    </div>

                                </div>

                            </div>

                        </section>



                        {{-- SUBMIT --}}
                        <div class="pt-2">

                            <button
                                type="submit"
                                class="btn-hanania-gold w-full rounded-2xl py-4 text-[15px] sm:text-base group"
                            >

                                <span>
                                    Selesaikan Pendaftaran
                                </span>

                                <span class="material-symbols-outlined text-[20px] group-hover:translate-x-1 transition-transform">
                                    arrow_forward
                                </span>

                            </button>


                            <p class="mt-5 text-center text-sm text-gray-500">

                                Sudah memiliki akun?

                                <a
                                    href="{{ route('login') }}"
                                    class="ml-1 font-bold text-hanania-purple hover:text-hanania-gold transition-colors"
                                >
                                    Masuk di sini
                                </a>

                            </p>

                        </div>

                    </form>

                </div>

            </div>

        </div>



        {{-- =====================================================
            DESKTOP VISUAL SIDE
            HIDDEN DI MOBILE
        ====================================================== --}}
        <div class="hidden lg:block lg:w-[44%] xl:w-1/2 h-full relative overflow-hidden bg-hanania-purple-dark">

            <img
                src="https://images.unsplash.com/photo-1565552645632-d725f8bfc19a?q=80&w=1600&auto=format&fit=crop"
                alt="Suasana Makkah"
                class="absolute inset-0 w-full h-full object-cover"
            >


            {{-- OVERLAY --}}
            <div class="absolute inset-0 bg-hanania-purple-dark/35"></div>

            <div class="absolute inset-0 bg-gradient-to-t from-hanania-purple-dark via-hanania-purple-dark/30 to-transparent"></div>

            <div class="absolute inset-0 bg-gradient-to-l from-hanania-purple-dark/65 via-transparent to-transparent"></div>


            {{-- VISUAL CONTENT --}}
            <div class="absolute inset-x-0 bottom-0 p-10 xl:p-14 z-10">

                {{-- BRAND --}}
                <div class="flex items-center gap-3 mb-10">

                    <div class="w-11 h-11 rounded-2xl bg-white/10 border border-white/15 backdrop-blur-sm flex items-center justify-center">

                        <span class="material-symbols-outlined text-[24px] text-hanania-gold-light">
                            mosque
                        </span>

                    </div>

                    <div>

                        <p class="font-heading text-lg font-extrabold text-white">
                            {{ $companyName }}
                        </p>

                        <p class="text-[10px] uppercase tracking-[0.18em] text-white/55 font-bold">
                            Portal Jamaah
                        </p>

                    </div>

                </div>


                {{-- EYEBROW --}}
                <div class="flex items-center gap-3 mb-5">

                    <span class="h-px w-9 bg-hanania-gold"></span>

                    <span class="text-[10px] uppercase tracking-[0.2em] font-extrabold text-hanania-gold-light">
                        Langkah Pertama
                    </span>

                </div>


                {{-- HEADING --}}
                <h2 class="font-heading text-4xl xl:text-5xl font-extrabold text-white leading-[1.05] tracking-tight max-w-xl">

                    Persiapkan perjalanan

                    <span class="block text-hanania-gold-light">
                        menuju Baitullah.
                    </span>

                </h2>


                {{-- DESCRIPTION --}}
                <p class="mt-5 text-sm xl:text-base text-white/70 leading-relaxed max-w-xl">
                    Lengkapi data Anda sekali untuk memudahkan proses
                    administrasi dan pendampingan perjalanan bersama {{ $companyName }}.
                </p>


                {{-- TRUST --}}
                <div class="mt-7 flex flex-wrap items-center gap-5">

                    <div class="flex items-center gap-2 text-[11px] font-bold text-white/65">

                        <span class="material-symbols-outlined text-[18px] text-hanania-gold">
                            verified_user
                        </span>

                        Data Tercatat

                    </div>


                    <div class="flex items-center gap-2 text-[11px] font-bold text-white/65">

                        <span class="material-symbols-outlined text-[18px] text-hanania-gold">
                            security
                        </span>

                        Aman

                    </div>


                    <div class="flex items-center gap-2 text-[11px] font-bold text-white/65">

                        <span class="material-symbols-outlined text-[18px] text-hanania-gold">
                            support_agent
                        </span>

                        Didampingi

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>