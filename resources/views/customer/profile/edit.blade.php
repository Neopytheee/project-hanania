@extends('layouts.customer.app')

@section('title', 'Edit Profil')

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
        <div class="flex flex-col gap-2">
            <span class="text-[11px] sm:text-xs font-extrabold uppercase tracking-[0.18em] text-hanania-gold">
                Pengaturan Akun
            </span>

            <h1 class="font-heading font-extrabold text-3xl sm:text-4xl text-hanania-purple-dark">
                Edit Profil
            </h1>

            <p class="text-sm sm:text-[15px] text-gray-500 max-w-2xl leading-relaxed">
                Perbarui informasi kontak dan foto profil Anda agar data perjalanan dan komunikasi tetap akurat.
            </p>
        </div>
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
                        Periksa kembali data Anda
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

    {{-- MAIN PROFILE EDIT --}}
    <div class="grid grid-cols-1 lg:grid-cols-[320px_minmax(0,1fr)] gap-6 lg:gap-8">

        {{-- LEFT PROFILE PANEL --}}
        <div class="card-hanania overflow-hidden lg:sticky lg:top-28 self-start">

            {{-- PURPLE HEADER --}}
            <div class="bg-hanania-purple-dark px-6 py-7 sm:px-7 sm:py-8">
                <span class="inline-flex items-center gap-2 text-[11px] font-extrabold uppercase tracking-[0.16em] text-hanania-gold-light">
                    <span class="w-1.5 h-1.5 rounded-full bg-hanania-gold-light"></span>
                    Profil Anda
                </span>

                <h2 class="mt-3 font-heading text-2xl font-extrabold text-white leading-tight">
                    Pastikan data Anda tetap terbaru.
                </h2>

                <p class="mt-3 text-sm leading-relaxed text-white/75">
                    Informasi ini akan digunakan untuk kebutuhan komunikasi dan dokumen perjalanan Anda.
                </p>
            </div>

            {{-- PROFILE CONTENT --}}
            <div class="bg-white px-6 py-6 sm:px-7 sm:py-7">

                {{-- AVATAR --}}
                <div class="flex items-center gap-4">
                    <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full overflow-hidden bg-hanania-purple-light border-4 border-white shadow-lg ring-1 ring-hanania-purple/10 flex items-center justify-center shrink-0">

                        <!-- PERBAIKAN: Gunakan profile_image -->
                        @if(isset($customer->profile_image) && $customer->profile_image)
                            <img
                                src="{{ asset('storage/' . $customer->profile_image) }}"
                                alt="Foto Profil"
                                class="w-full h-full object-cover"
                            >
                        @else
                            <span class="material-symbols-outlined text-hanania-purple text-[42px] sm:text-[48px]">
                                person
                            </span>
                        @endif

                    </div>

                    <div class="min-w-0">
                        <p class="text-[11px] uppercase tracking-[0.12em] font-extrabold text-gray-400">
                            Foto Profil
                        </p>

                        <h3 class="mt-1 font-heading font-extrabold text-lg text-hanania-purple-dark truncate">
                            {{ $customer->name ?? 'Pengguna Hanania' }}
                        </h3>

                        @if(isset($customer->email) && $customer->email)
                            <p class="mt-1 text-xs sm:text-[13px] text-gray-500 break-all">
                                {{ $customer->email }}
                            </p>
                        @endif
                    </div>
                </div>

                {{-- INFO --}}
                <div class="mt-7 pt-6 border-t border-hanania-purple/10">
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-xl bg-hanania-purple-light flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-hanania-purple text-[19px]">
                                verified_user
                            </span>
                        </div>

                        <div>
                            <p class="font-heading font-bold text-sm text-hanania-purple-dark">
                                Data pribadi aman
                            </p>

                            <p class="mt-1 text-xs sm:text-[13px] text-gray-500 leading-relaxed">
                                Gunakan nomor WhatsApp aktif agar informasi perjalanan lebih mudah diterima.
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
                    Informasi Kontak
                </span>

                <h2 class="mt-2 font-heading text-2xl sm:text-[28px] font-extrabold text-hanania-purple-dark">
                    Perbarui data profil
                </h2>

                <p class="mt-2 text-sm text-gray-500 leading-relaxed">
                    Silakan pastikan nomor HP dan alamat yang Anda masukkan sudah benar.
                </p>
            </div>

            <form
                action="{{ route('customer.profile.update') }}"
                method="POST"
                enctype="multipart/form-data"
                class="space-y-8"
            >
                @csrf
                @method('PUT')

                {{-- PHOTO --}}
                <div>
                    <div class="mb-3">
                        <label class="block font-heading text-sm font-extrabold text-hanania-purple-dark">
                            Foto Profil
                        </label>

                        <p class="mt-1 text-xs sm:text-[13px] text-gray-500">
                            Foto bersifat opsional. Gunakan gambar yang jelas dan mudah dikenali.
                        </p>
                    </div>

                    <div class="rounded-2xl border border-hanania-purple/10 bg-hanania-purple-light/40 p-4 sm:p-5">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-4">

                            <div class="w-16 h-16 rounded-2xl overflow-hidden bg-white border border-hanania-purple/10 shadow-sm flex items-center justify-center shrink-0">

                                <!-- PERBAIKAN: Gunakan foto_profil -->
                                @if(isset($customer->foto_profil) && $customer->profile_image)
                                    <img
                                        src="{{ asset('storage/' . $customer->profile_image) }}"
                                        alt="Foto Profil"
                                        class="w-full h-full object-cover"
                                    >
                                @else
                                    <span class="material-symbols-outlined text-hanania-purple text-[30px]">
                                        person
                                    </span>
                                @endif

                            </div>

                            <div class="flex-1 min-w-0">
                                <!-- PERBAIKAN: name="foto_profil" -->
                                <input
                                    type="file"
                                    name="profile_image"
                                    accept="image/png, image/jpeg, image/jpg"
                                    class="w-full text-sm text-gray-500
                                           file:mr-3
                                           file:rounded-xl
                                           file:border-0
                                           file:bg-hanania-purple
                                           file:px-4
                                           file:py-2.5
                                           file:text-sm
                                           file:font-bold
                                           file:text-white
                                           hover:file:bg-hanania-purple-dark
                                           cursor-pointer
                                           focus:outline-none"
                                >

                                <p class="mt-2 text-xs text-gray-500">
                                    JPG, JPEG, atau PNG · Maksimal 2MB
                                </p>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- PHONE --}}
                <div>
                    <label
                        for="phone"
                        class="block mb-3 font-heading text-sm font-extrabold text-hanania-purple-dark"
                    >
                        Nomor HP / WhatsApp
                    </label>

                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-[21px]">
                            call
                        </span>

                        <input
                            id="phone"
                            type="text"
                            name="phone"
                            value="{{ old('phone', $customer->phone ?? '') }}"
                            placeholder="081234567890"
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

                {{-- ADDRESS --}}
                <div>
                    <label
                        for="address"
                        class="block mb-3 font-heading text-sm font-extrabold text-hanania-purple-dark"
                    >
                        Alamat Domisili
                    </label>

                    <textarea
                        id="address"
                        name="address"
                        rows="5"
                        required
                        placeholder="Masukkan alamat lengkap pengiriman dokumen..."
                        class="w-full rounded-2xl border border-hanania-purple/15 bg-gray-50/70
                               px-4 py-4
                               text-[15px] font-semibold text-hanania-purple-dark
                               placeholder:text-gray-400
                               leading-relaxed
                               outline-none transition-all
                               resize-y
                               focus:border-hanania-purple
                               focus:bg-white
                               focus:ring-4 focus:ring-hanania-purple-light"
                    >{{ old('address', $customer->address ?? '') }}</textarea>

                    <p class="mt-2 text-xs text-gray-500">
                        Gunakan alamat yang lengkap untuk kebutuhan pengiriman dokumen.
                    </p>
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
                                save
                            </span>

                            Simpan Perubahan
                        </button>

                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection