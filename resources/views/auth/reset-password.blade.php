<!DOCTYPE html>
<html lang="id">

<head>

    @php
        $companyName = \App\Models\AppInformation::getValue(
            'company_name',
            'Hanania'
        );
    @endphp

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
        Buat Password Baru - {{ $companyName }}
    </title>


    {{-- Fonts & Icons --}}
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,1,0"
        rel="stylesheet"
    >


    {{-- Hanania App --}}
    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

</head>


<body class="min-h-screen bg-hanania-purple-light flex items-center justify-center px-4 py-8 sm:px-6">

    {{-- =========================================================
        PAGE
    ========================================================== --}}
    <main class="w-full max-w-md">


        {{-- =====================================================
            BRAND
        ====================================================== --}}
        <div class="flex justify-center mb-6">

            <div class="inline-flex items-center gap-3">

                <div class="w-11 h-11 rounded-2xl bg-white border border-hanania-purple/10 shadow-sm flex items-center justify-center">

                    <span class="material-symbols-outlined text-[24px] text-hanania-purple">
                        mosque
                    </span>

                </div>


                <div class="text-left">

                    <p class="font-heading text-lg font-extrabold text-hanania-purple-dark leading-tight">
                        {{ $companyName }}
                    </p>

                    <p class="text-[10px] uppercase tracking-[0.18em] text-gray-400 font-bold mt-0.5">
                        Keamanan Akun
                    </p>

                </div>

            </div>

        </div>



        {{-- =====================================================
            CARD
        ====================================================== --}}
        <div class="bg-white rounded-[28px] border border-hanania-purple/10 shadow-xl overflow-hidden">


            {{-- HEADER PURPLE --}}
            <div class="bg-hanania-purple-dark px-6 sm:px-8 py-7 text-center">

                <div class="mx-auto w-14 h-14 rounded-2xl bg-white/10 border border-white/10 flex items-center justify-center mb-4">

                    <span class="material-symbols-outlined text-[29px] text-hanania-gold-light">
                        lock_reset
                    </span>

                </div>


                <span class="text-[10px] uppercase tracking-[0.18em] font-extrabold text-hanania-gold-light">
                    Atur Ulang Akses
                </span>


                <h1 class="mt-2 font-heading text-2xl sm:text-[30px] font-extrabold text-white leading-tight">
                    Buat Password Baru
                </h1>


                <p class="mt-2 text-sm text-white/70 leading-relaxed max-w-sm mx-auto">
                    Buat password baru untuk mengamankan kembali akun Anda.
                </p>

            </div>



            {{-- FORM AREA --}}
            <div class="p-6 sm:p-8">


                {{-- INFO --}}
                <div class="mb-7 rounded-2xl bg-hanania-purple-light border border-hanania-purple/10 p-4">

                    <div class="flex items-start gap-3">

                        <span class="material-symbols-outlined text-hanania-purple text-[20px] mt-0.5 shrink-0">
                            verified_user
                        </span>

                        <p class="text-xs sm:text-[13px] text-hanania-purple-dark/75 leading-relaxed">
                            Gunakan password yang kuat, unik, dan mudah Anda ingat.
                            Minimal 8 karakter disarankan.
                        </p>

                    </div>

                </div>



                {{-- FORM --}}
                <form
                    method="POST"
                    action="{{ route('password.update') }}"
                    class="space-y-5"
                >

                    @csrf


                    {{-- TOKEN --}}
                    <input
                        type="hidden"
                        name="token"
                        value="{{ $token }}"
                    >



                    {{-- EMAIL --}}
                    <div>

                        <label
                            for="email"
                            class="block mb-2 font-heading text-sm font-extrabold text-hanania-purple-dark"
                        >
                            Alamat Email
                        </label>


                        <div class="relative">

                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-[20px] pointer-events-none">
                                mail
                            </span>


                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ $email ?? old('email') }}"
                                readonly
                                class="w-full rounded-2xl border border-gray-200 bg-gray-50
                                       pl-12 pr-4 py-4
                                       text-[14px] sm:text-[15px] font-semibold text-gray-500
                                       cursor-not-allowed outline-none"
                            >

                        </div>


                        @error('email')

                            <p class="mt-2 flex items-center gap-1.5 text-xs font-bold text-red-500">

                                <span class="material-symbols-outlined text-[15px]">
                                    error
                                </span>

                                {{ $message }}

                            </p>

                        @enderror

                    </div>



                    {{-- PASSWORD --}}
                    <div>

                        <label
                            for="password"
                            class="block mb-2 font-heading text-sm font-extrabold text-hanania-purple-dark"
                        >
                            Password Baru
                        </label>


                        <div class="relative group">

                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-hanania-purple transition-colors text-[20px] pointer-events-none">
                                lock
                            </span>


                            <input
                                id="password"
                                type="password"
                                name="password"
                                required
                                autofocus
                                minlength="8"
                                autocomplete="new-password"
                                placeholder="Minimal 8 karakter"
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


                        @error('password')

                            <p class="mt-2 flex items-center gap-1.5 text-xs font-bold text-red-500">

                                <span class="material-symbols-outlined text-[15px]">
                                    error
                                </span>

                                {{ $message }}

                            </p>

                        @enderror

                    </div>



                    {{-- CONFIRM PASSWORD --}}
                    <div>

                        <label
                            for="password_confirmation"
                            class="block mb-2 font-heading text-sm font-extrabold text-hanania-purple-dark"
                        >
                            Ulangi Password Baru
                        </label>


                        <div class="relative group">

                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-hanania-purple transition-colors text-[20px] pointer-events-none">
                                password
                            </span>


                            <input
                                id="password_confirmation"
                                type="password"
                                name="password_confirmation"
                                required
                                minlength="8"
                                autocomplete="new-password"
                                placeholder="Masukkan ulang password"
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



                    {{-- SUBMIT --}}
                    <button
                        type="submit"
                        class="btn-hanania-gold w-full rounded-2xl py-4 text-sm sm:text-[15px] mt-2 group"
                    >

                        <span>
                            Simpan Password
                        </span>

                        <span class="material-symbols-outlined text-[20px] group-hover:translate-x-1 transition-transform">
                            arrow_forward
                        </span>

                    </button>

                </form>



                {{-- FOOT NOTE --}}
                <div class="mt-7 pt-6 border-t border-hanania-purple/10 text-center">

                    <div class="inline-flex items-center gap-2 text-xs text-gray-400">

                        <span class="material-symbols-outlined text-[16px] text-hanania-gold">
                            security
                        </span>

                        Akun Anda tetap terlindungi bersama {{ $companyName }}

                    </div>

                </div>

            </div>

        </div>



        {{-- BACK LOGIN --}}
        <div class="text-center mt-6">

            <a
                href="{{ route('login') }}"
                class="inline-flex items-center gap-2 text-sm font-bold text-hanania-purple hover:text-hanania-purple-dark transition-colors"
            >

                <span class="material-symbols-outlined text-[17px]">
                    arrow_back
                </span>

                Kembali ke Login

            </a>

        </div>

    </main>

</body>

</html>