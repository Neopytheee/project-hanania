<!DOCTYPE html>
<html lang="id">

<head>

    @php
        $companyName = \App\Models\AppInformation::getValue(
            'company_name',
            'Company Name'
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
        Verifikasi OTP - {{ $companyName }}
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
                        Verifikasi Akun
                    </p>

                </div>

            </div>

        </div>



        {{-- =====================================================
            CARD
        ====================================================== --}}
        <div class="bg-white rounded-[28px] border border-hanania-purple/10 shadow-xl overflow-hidden">


            {{-- HEADER --}}
            <div class="bg-hanania-purple-dark px-6 sm:px-8 py-8 text-center">

                <div class="mx-auto w-14 h-14 rounded-2xl bg-white/10 border border-white/10 flex items-center justify-center mb-5">

                    <span class="material-symbols-outlined text-[30px] text-hanania-gold-light">
                        mark_email_read
                    </span>

                </div>


                <span class="text-[10px] uppercase tracking-[0.18em] font-extrabold text-hanania-gold-light">
                    Langkah Verifikasi
                </span>


                <h1 class="mt-2 font-heading text-2xl sm:text-[30px] font-extrabold text-white leading-tight">
                    Verifikasi Email
                </h1>


                <p class="mt-3 text-sm text-white/70 leading-relaxed max-w-sm mx-auto">
                    Masukkan kode OTP 6 digit yang dikirim ke email Anda
                    untuk mengaktifkan akun.
                </p>

            </div>



            {{-- FORM AREA --}}
            <div class="p-6 sm:p-8">


                {{-- EMAIL INFO --}}
                <div class="rounded-2xl bg-hanania-purple-light border border-hanania-purple/10 px-4 py-4 mb-7">

                    <div class="flex items-start gap-3">

                        <span class="material-symbols-outlined text-hanania-purple text-[20px] shrink-0 mt-0.5">
                            mail
                        </span>

                        <div class="min-w-0">

                            <p class="text-[10px] uppercase tracking-[0.14em] font-extrabold text-hanania-purple">
                                Kode dikirim ke
                            </p>

                            <p class="mt-1 text-sm font-bold text-hanania-purple-dark break-all">
                                {{ $email }}
                            </p>

                        </div>

                    </div>

                </div>



                {{-- FORM --}}
                <form
                    action="{{ route('register.otp.submit') }}"
                    method="POST"
                >

                    @csrf


                    {{-- Hidden Email --}}
                    <input
                        type="hidden"
                        name="email"
                        value="{{ $email }}"
                    >


                    {{-- OTP --}}
                    <div>

                        <label
                            for="otp"
                            class="block mb-3 text-center font-heading text-sm font-extrabold text-hanania-purple-dark"
                        >
                            Masukkan 6 Digit Kode OTP
                        </label>


                        <div class="relative">

                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-[21px] pointer-events-none">
                                password
                            </span>

                            <input
                                id="otp"
                                type="text"
                                name="otp"
                                value="{{ old('otp') }}"
                                required
                                maxlength="6"
                                pattern="[0-9]{6}"
                                inputmode="numeric"
                                autocomplete="one-time-code"
                                placeholder="000000"
                                class="w-full rounded-2xl border border-hanania-purple/15 bg-gray-50/70
                                       pl-12 pr-4 py-4
                                       text-center tracking-[0.45em]
                                       text-xl sm:text-2xl font-extrabold
                                       text-hanania-purple-dark
                                       placeholder:text-gray-300
                                       outline-none transition-all
                                       focus:border-hanania-purple
                                       focus:bg-white
                                       focus:ring-4 focus:ring-hanania-purple-light"
                            >

                        </div>


                        @error('otp')

                            <div class="mt-3 flex items-center justify-center gap-1.5 text-xs font-bold text-red-500">

                                <span class="material-symbols-outlined text-[15px]">
                                    error
                                </span>

                                {{ $message }}

                            </div>

                        @enderror

                    </div>



                    {{-- SUBMIT --}}
                    <button
                        type="submit"
                        class="btn-hanania-gold w-full rounded-2xl py-4 text-sm sm:text-[15px] mt-6 group"
                    >

                        <span>
                            Verifikasi & Aktifkan Akun
                        </span>

                        <span class="material-symbols-outlined text-[20px] group-hover:translate-x-1 transition-transform">
                            arrow_forward
                        </span>

                    </button>

                </form>



                {{-- INFO --}}
                <div class="mt-7 pt-6 border-t border-hanania-purple/10">

                    <div class="flex items-start gap-3">

                        <span class="material-symbols-outlined text-hanania-gold text-[18px] mt-0.5">
                            info
                        </span>

                        <p class="text-xs text-gray-500 leading-relaxed">
                            Periksa folder inbox atau spam pada email Anda.
                            Masukkan kode sesuai yang diterima.
                        </p>

                    </div>

                </div>

            </div>

        </div>



        {{-- FOOTER --}}
        <p class="text-center text-xs text-gray-400 mt-6">
            Akun Anda akan aktif setelah kode berhasil diverifikasi.
        </p>

    </main>

</body>

</html>