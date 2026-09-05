<!DOCTYPE html>
<html lang="id">

<head>

    @php
        $companyName = \App\Models\AppInformation::getValue(
            'company_name',
            'Hanania Travel'
        );
    @endphp

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
        Login Jamaah - {{ $companyName }}
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
        SPLIT LOGIN
    ========================================================== --}}
    <div class="flex h-screen w-full">


        {{-- =====================================================
            DESKTOP VISUAL SIDE
            Tetap hidden di mobile
        ====================================================== --}}
        <div class="hidden lg:block lg:w-[46%] xl:w-1/2 h-full relative overflow-hidden bg-hanania-purple-dark">

            {{-- IMAGE --}}
            <img
                src="https://images.unsplash.com/photo-1635829576353-1a14caec2f6f?w=1600&auto=format&fit=crop&q=85"
                alt="Suasana Masjid"
                class="absolute inset-0 w-full h-full object-cover"
            >


            {{-- DARK BRAND OVERLAY --}}
            <div class="absolute inset-0 bg-hanania-purple-dark/35"></div>

            <div class="absolute inset-0 bg-gradient-to-t from-hanania-purple-dark via-hanania-purple-dark/35 to-transparent"></div>

            <div class="absolute inset-0 bg-gradient-to-r from-hanania-purple-dark/65 via-transparent to-transparent"></div>


            {{-- CONTENT --}}
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
                        Selamat Datang Kembali
                    </span>

                </div>


                {{-- HEADING --}}
                <h1 class="font-heading text-4xl xl:text-5xl font-extrabold text-white leading-[1.05] tracking-tight max-w-xl">

                    Lanjutkan langkah Anda

                    <span class="block text-hanania-gold-light">
                        menuju Baitullah.
                    </span>

                </h1>


                {{-- DESCRIPTION --}}
                <p class="mt-5 text-sm xl:text-base text-white/70 leading-relaxed max-w-xl">
                    Pantau tabungan, lihat perkembangan perjalanan,
                    dan kelola persiapan ibadah Anda dari satu dashboard.
                </p>


                {{-- TRUST ITEMS --}}
                <div class="flex flex-wrap items-center gap-5 mt-7">

                    <div class="flex items-center gap-2 text-[11px] font-bold text-white/65">

                        <span class="material-symbols-outlined text-[18px] text-hanania-gold">
                            verified_user
                        </span>

                        Aman & Terpercaya

                    </div>


                    <div class="flex items-center gap-2 text-[11px] font-bold text-white/65">

                        <span class="material-symbols-outlined text-[18px] text-hanania-gold">
                            dashboard
                        </span>

                        Satu Dashboard

                    </div>


                    <div class="flex items-center gap-2 text-[11px] font-bold text-white/65">

                        <span class="material-symbols-outlined text-[18px] text-hanania-gold">
                            support_agent
                        </span>

                        Pendampingan

                    </div>

                </div>

            </div>

        </div>



        {{-- =====================================================
            FORM SIDE
            Mobile tetap full-screen seperti desain sebelumnya
        ====================================================== --}}
        <div class="w-full lg:w-[54%] xl:w-1/2 h-full overflow-y-auto bg-white">

            <div class="min-h-full flex items-center justify-center px-5 py-10 sm:px-8 lg:px-12 xl:px-20">

                <div class="w-full max-w-[440px]">


                    {{-- =================================================
                        MOBILE BRAND
                    ================================================== --}}
                    <div class="lg:hidden mb-9">

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
                                    Portal Jamaah
                                </p>

                            </div>

                        </div>

                    </div>



                    {{-- =================================================
                        HEADING
                    ================================================== --}}
                    <div class="mb-9">

                        <span class="inline-flex items-center gap-2 text-[10px] sm:text-[11px] uppercase tracking-[0.18em] font-extrabold text-hanania-gold">

                            <span class="w-1.5 h-1.5 rounded-full bg-hanania-gold"></span>

                            Akses Akun

                        </span>


                        <h2 class="mt-3 font-heading text-3xl sm:text-[38px] font-extrabold text-hanania-purple-dark leading-none tracking-tight">

                            Ahlan Wa Sahlan

                        </h2>


                        <p class="mt-4 text-sm sm:text-[15px] text-gray-500 leading-relaxed">
                            Para tamu Allah, silakan masuk ke akun Anda
                            untuk melanjutkan persiapan perjalanan.
                        </p>

                    </div>



                    {{-- =================================================
                        ERROR
                    ================================================== --}}
                    @if($errors->any())

                        <div class="mb-7 rounded-2xl border border-red-200 bg-red-50 px-4 py-4">

                            <div class="flex items-start gap-3">

                                <span class="material-symbols-outlined text-red-500 text-[21px] shrink-0">
                                    error
                                </span>

                                <div>

                                    <p class="text-sm font-bold text-red-800">
                                        Login belum berhasil
                                    </p>

                                    <p class="mt-1 text-xs sm:text-[13px] text-red-700 leading-relaxed">
                                        {{ $errors->first() }}
                                    </p>

                                </div>

                            </div>

                        </div>

                    @endif



                    {{-- =================================================
                        FORM
                    ================================================== --}}
                    <form
                        action="{{ route('login') }}"
                        method="POST"
                        class="space-y-5"
                    >

                        @csrf


                        {{-- EMAIL --}}
                        <div>

                            <label
                                for="email"
                                class="block mb-2 font-heading text-sm font-extrabold text-hanania-purple-dark"
                            >
                                Alamat Email
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
                                    placeholder="Masukkan email Anda"
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

                            <div class="flex items-center justify-between gap-3 mb-2">

                                <label
                                    for="password"
                                    class="font-heading text-sm font-extrabold text-hanania-purple-dark"
                                >
                                    Kata Sandi
                                </label>

                                <a
                                    href="{{ route('password.request') }}"
                                    class="text-xs font-bold text-hanania-purple hover:text-hanania-gold transition-colors"
                                >
                                    Lupa password?
                                </a>

                            </div>


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
                                    autocomplete="current-password"
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



                        {{-- LOGIN BUTTON --}}
                        <button
                            type="submit"
                            class="btn-hanania-gold w-full rounded-2xl py-4 text-[15px] sm:text-base mt-2 group"
                        >

                            <span>
                                Masuk Sekarang
                            </span>

                            <span class="material-symbols-outlined text-[20px] group-hover:translate-x-1 transition-transform">
                                arrow_forward
                            </span>

                        </button>

                    </form>



                    {{-- =================================================
                        REGISTER
                    ================================================== --}}
                    <div class="mt-8 pt-7 border-t border-hanania-purple/10">

                        <p class="text-center text-sm text-gray-500">

                            Belum punya akun?

                            <a
                                href="{{ route('register') }}"
                                class="ml-1 font-bold text-hanania-purple hover:text-hanania-gold transition-colors"
                            >
                                Buat akun baru
                            </a>

                        </p>

                    </div>



                    {{-- =================================================
                        MOBILE TRUST
                    ================================================== --}}
                    <div class="lg:hidden mt-8 flex items-center justify-center gap-4 text-[10px] font-bold text-gray-400">

                        <span class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[15px] text-hanania-gold">
                                verified_user
                            </span>
                            Aman
                        </span>

                        <span class="w-1 h-1 rounded-full bg-hanania-gold"></span>

                        <span class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[15px] text-hanania-gold">
                                support_agent
                            </span>
                            Siap Membantu
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>
</html>