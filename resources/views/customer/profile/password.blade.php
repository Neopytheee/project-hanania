@extends('layouts.customer.app')

@section('title', 'Ubah Password')

@section('content')
<div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-12 animate-fade-in-up">

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

    {{-- PAGE INTRO --}}
    <div class="mb-8">
        <span class="text-[11px] sm:text-xs font-extrabold uppercase tracking-[0.18em] text-hanania-gold">
            Keamanan Akun
        </span>

        <h1 class="mt-2 font-heading font-extrabold text-3xl sm:text-4xl text-hanania-purple-dark">
            Ubah Password
        </h1>

        <p class="mt-2 max-w-2xl text-sm sm:text-[15px] text-gray-500 leading-relaxed">
            Gunakan password yang kuat dan mudah Anda ingat untuk menjaga keamanan akun perjalanan Anda.
        </p>
    </div>

    {{-- ERROR --}}
    @if ($errors->any())
        <div class="mb-8 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 sm:px-6 sm:py-5">
            <div class="flex items-start gap-3">
                <span class="material-symbols-outlined text-red-500 text-[22px]">
                    error
                </span>

                <div class="flex-1">
                    <p class="font-heading font-bold text-sm text-red-800 mb-2">
                        Password belum dapat diperbarui
                    </p>

                    <ul class="space-y-1 text-sm text-red-700 leading-relaxed">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    {{-- MAIN CONTENT --}}
    <div class="grid grid-cols-1 lg:grid-cols-[320px_minmax(0,1fr)] gap-6 lg:gap-8">

        {{-- LEFT SECURITY PANEL --}}
        <div class="card-hanania overflow-hidden lg:sticky lg:top-28 self-start">

            <div class="bg-hanania-purple-dark px-6 py-7 sm:px-7 sm:py-8">

                <span class="inline-flex items-center gap-2 text-[11px] font-extrabold uppercase tracking-[0.16em] text-hanania-gold-light">
                    <span class="w-1.5 h-1.5 rounded-full bg-hanania-gold-light"></span>
                    Keamanan
                </span>

                <div class="mt-5 w-14 h-14 rounded-2xl bg-white/10 border border-white/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-white text-[30px]">
                        lock
                    </span>
                </div>

                <h2 class="mt-5 font-heading text-2xl font-extrabold text-white leading-tight">
                    Jaga akun tetap aman.
                </h2>

                <p class="mt-3 text-sm leading-relaxed text-white/75">
                    Password melindungi data profil dan informasi perjalanan Anda. Hindari menggunakan password yang sama di banyak akun.
                </p>
            </div>

            <div class="bg-white px-6 py-6 sm:px-7 sm:py-7">

                <div class="space-y-5">

                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-xl bg-hanania-purple-light flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-hanania-purple text-[19px]">
                                verified_user
                            </span>
                        </div>

                        <div>
                            <p class="font-heading font-bold text-sm text-hanania-purple-dark">
                                Gunakan kombinasi yang kuat
                            </p>

                            <p class="mt-1 text-xs sm:text-[13px] text-gray-500 leading-relaxed">
                                Campurkan huruf, angka, dan karakter agar lebih sulit ditebak.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-xl bg-hanania-purple-light flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-hanania-purple text-[19px]">
                                password
                            </span>
                        </div>

                        <div>
                            <p class="font-heading font-bold text-sm text-hanania-purple-dark">
                                Minimal 8 karakter
                            </p>

                            <p class="mt-1 text-xs sm:text-[13px] text-gray-500 leading-relaxed">
                                Jangan gunakan informasi yang mudah ditebak seperti nama atau tanggal lahir.
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- RIGHT FORM --}}
        <div class="card-hanania bg-white p-6 sm:p-8 lg:p-10">

            {{-- SECTION HEADING --}}
            <div class="mb-8">
                <span class="text-[11px] sm:text-xs font-extrabold uppercase tracking-[0.16em] text-hanania-purple">
                    Pengaturan Password
                </span>

                <h2 class="mt-2 font-heading text-2xl sm:text-[28px] font-extrabold text-hanania-purple-dark">
                    Buat password baru
                </h2>

                <p class="mt-2 text-sm text-gray-500 leading-relaxed">
                    Masukkan password lama untuk verifikasi, lalu tentukan password baru Anda.
                </p>
            </div>

            {{-- FORM --}}
            <form
                action="{{ route('customer.profile.password.update') }}"
                method="POST"
                class="space-y-8"
            >
                @csrf
                @method('PUT')

                {{-- CURRENT PASSWORD --}}
                <div>
                    <label
                        for="current_password"
                        class="block mb-3 font-heading text-sm font-extrabold text-hanania-purple-dark"
                    >
                        Password Saat Ini
                    </label>

                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-[21px]">
                            lock
                        </span>

                        <input
                            id="current_password"
                            type="password"
                            name="current_password"
                            required
                            placeholder="Masukkan password lama Anda"
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

                {{-- NEW PASSWORD --}}
                <div class="pt-2">

                    <div class="mb-4">
                        <label
                            for="new_password"
                            class="block font-heading text-sm font-extrabold text-hanania-purple-dark"
                        >
                            Password Baru
                        </label>

                        <p class="mt-1 text-xs sm:text-[13px] text-gray-500">
                            Gunakan minimal 8 karakter dengan kombinasi yang tidak mudah ditebak.
                        </p>
                    </div>

                    <div class="space-y-4">

                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-[21px]">
                                key
                            </span>

                            <input
                                id="new_password"
                                type="password"
                                name="new_password"
                                required
                                minlength="8"
                                placeholder="Masukkan password baru"
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

                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-[21px]">
                                password
                            </span>

                            <input
                                id="new_password_confirmation"
                                type="password"
                                name="new_password_confirmation"
                                required
                                minlength="8"
                                placeholder="Ulangi password baru"
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

                {{-- ACTION --}}
                <div class="pt-6 border-t border-hanania-purple/10">
                    <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-4">

                        <a
                            href="{{ route('customer.profile.index') }}"
                            class="inline-flex items-center justify-center gap-2 rounded-xl px-5 py-3 text-sm font-bold text-gray-500 hover:text-hanania-purple hover:bg-hanania-purple-light transition-all"
                        >
                            Batal
                        </a>

                        <button
                            type="submit"
                            class="btn-hanania-gold rounded-xl px-6 py-3.5 text-sm sm:text-[15px] shadow-md active:scale-[0.98]"
                        >
                            <span class="material-symbols-outlined text-[19px]">
                                lock_reset
                            </span>

                            Simpan Password
                        </button>

                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection