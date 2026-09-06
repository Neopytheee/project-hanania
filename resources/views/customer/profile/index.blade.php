@extends('layouts.customer.app')

@section('title', 'Profil Saya')

@section('content')
@php
    $companyName = \App\Models\AppInformation::getValue('company_name', 'Hanania Travel');
    $shortCompanyName = explode(' ', $companyName)[0];
@endphp

<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(14px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes profileFloat {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-5px); }
    }

    .animate-fade-in-up {
        animation: fadeInUp .55s cubic-bezier(.16,1,.3,1) forwards;
    }

    .profile-float { animation: profileFloat 5s ease-in-out infinite; }

    .profile-row {
        transition: transform .25s ease, background-color .25s ease, border-color .25s ease;
    }

    .profile-row:hover { transform: translateX(3px); }
</style>

<div class="w-full min-h-screen pt-24 pb-16 animate-fade-in-up">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- SUCCESS -->
        @if(session('success'))
            <div class="mb-6 bg-emerald-50 text-emerald-800 p-4 rounded-2xl font-bold border border-emerald-200 flex items-center gap-3 shadow-sm">
                <span class="material-symbols-outlined text-emerald-500 [font-variation-settings:'FILL'_1] text-[21px]">check_circle</span>
                <div class="flex-1 text-[12px] sm:text-[13px]">{{ session('success') }}</div>
            </div>
        @endif

        <!-- PAGE INTRO -->
        <div class="mb-7">
            <p class="text-[9px] font-black uppercase tracking-[.2em] text-hanania-purple">Akun Anda</p>
            <h1 class="font-heading text-[30px] sm:text-[38px] font-black tracking-tight text-hanania-purple-dark mt-1">Profil Saya</h1>
            <p class="text-[12px] sm:text-[13px] text-gray-500 mt-2 font-medium max-w-2xl">
                Kelola informasi pribadi, rekening bank, keamanan, dan bantuan akun Anda di {{ $shortCompanyName }}.
            </p>
        </div>

        <!-- PROFILE HERO -->
        <section class="relative overflow-hidden rounded-[2rem] bg-hanania-purple-dark text-white border border-hanania-purple/10 shadow-xl mb-8">
            <div class="absolute right-0 top-0 w-[38%] h-full bg-hanania-purple/25"></div>
            <div class="absolute -right-16 -top-16 w-56 h-56 rounded-full border-[28px] border-hanania-purple-light/10"></div>
            <div class="absolute -bottom-20 right-1/4 w-48 h-48 rounded-full border border-hanania-gold/20"></div>

            <div class="relative z-10 grid lg:grid-cols-[1fr_300px]">
                <div class="p-6 sm:p-8 lg:p-9">
                    <div class="flex flex-col sm:flex-row items-center sm:items-start gap-5 text-center sm:text-left">
                        
                        <!-- 📸 LOGIKA FOTO PROFIL DINAMIS -->
                        <div class="relative shrink-0 profile-float">
                            <div class="absolute -inset-2 rounded-full bg-hanania-gold/10 blur-md"></div>
                            
                            @if(isset($customer) && $customer->profile_image)
                                <img src="{{ asset('storage/' . $customer->profile_image) }}" alt="Foto Profil {{ $user->name }}" class="relative w-24 h-24 sm:w-28 sm:h-28 rounded-full object-cover border-4 border-white/15 shadow-lg bg-white">
                            @else
                                <div class="relative w-24 h-24 sm:w-28 sm:h-28 rounded-full bg-gradient-to-br from-hanania-gold-light to-hanania-gold text-white flex items-center justify-center text-[38px] sm:text-[44px] font-black border-4 border-white/15 shadow-lg">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif

                            <a href="{{ route('customer.profile.edit') }}" class="absolute bottom-0 right-0 w-8 h-8 bg-white text-hanania-purple rounded-full flex items-center justify-center shadow-lg border border-hanania-purple/10 hover:bg-hanania-purple hover:text-white transition-colors cursor-pointer">
                                <span class="material-symbols-outlined text-[16px]">edit</span>
                            </a>
                        </div>

                        <div class="min-w-0">
                            <p class="text-[9px] uppercase tracking-[.2em] font-black text-hanania-purple-light">Identitas Jamaah</p>
                            <h2 class="font-heading text-[28px] sm:text-[36px] font-black text-white tracking-tight mt-1 leading-tight">
                                {{ $user->name }}
                            </h2>

                            <div class="mt-4 grid sm:grid-cols-2 gap-x-7 gap-y-2.5">
                                <p class="flex items-center justify-center sm:justify-start gap-2 text-[11px] sm:text-[12px] text-white/65 font-medium">
                                    <span class="material-symbols-outlined text-[16px] text-hanania-gold">mail</span>
                                    <span class="truncate">{{ $user->email }}</span>
                                </p>
                                <p class="flex items-center justify-center sm:justify-start gap-2 text-[11px] sm:text-[12px] text-white/65 font-medium">
                                    <span class="material-symbols-outlined text-[16px] text-hanania-gold">call</span>
                                    {{ $customer->phone ?? 'Belum ada nomor HP' }}
                                </p>
                                <p class="sm:col-span-2 flex items-start justify-center sm:justify-start gap-2 text-[11px] sm:text-[12px] text-white/65 font-medium max-w-2xl">
                                    <span class="material-symbols-outlined text-[16px] text-hanania-gold shrink-0">location_on</span>
                                    <span class="leading-relaxed">{{ $customer->address ?? 'Alamat belum dilengkapi' }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-hanania-purple/30 border-t lg:border-t-0 lg:border-l border-white/10 p-6 sm:p-8 flex items-center">
                    <div>
                        <p class="text-[8px] uppercase tracking-[.18em] font-black text-white/40">Status Akun</p>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="w-2 h-2 rounded-full bg-hanania-gold animate-pulse"></span>
                            <p class="font-heading text-[22px] font-black text-white">Aktif</p>
                        </div>
                        <p class="text-[10px] text-white/45 leading-relaxed mt-2 max-w-[210px]">
                            Informasi profil dan akses akun Anda siap digunakan.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- SETTINGS -->
        <section>
            <div class="mb-4">
                <p class="text-[9px] font-black uppercase tracking-[.2em] text-hanania-purple">Pengaturan</p>
                <h2 class="font-heading text-[25px] sm:text-[29px] font-black text-hanania-purple-dark mt-1">Kelola Akun Anda</h2>
            </div>

            <div class="grid lg:grid-cols-2 gap-4">
                <!-- EDIT PROFILE -->
                <a href="{{ route('customer.profile.edit') }}" class="profile-row group bg-white rounded-[1.7rem] border border-hanania-purple/10 p-5 sm:p-6 shadow-sm hover:bg-hanania-purple-light/30 hover:border-hanania-gold/30 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-4 min-w-0">
                        <div class="w-12 h-12 rounded-2xl bg-hanania-purple-light flex items-center justify-center text-hanania-purple border border-hanania-purple/10 shrink-0 group-hover:bg-hanania-purple group-hover:text-white transition-colors">
                            <span class="material-symbols-outlined text-[23px]">manage_accounts</span>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[8px] uppercase tracking-[.16em] font-black text-hanania-purple">Profil</p>
                            <h3 class="font-heading text-[15px] sm:text-[16px] font-black text-hanania-purple-dark mt-0.5 group-hover:text-hanania-purple transition-colors">Edit Profil & Alamat</h3>
                            <p class="text-[10px] sm:text-[11px] text-gray-500 mt-1 leading-relaxed">Ubah nama, nomor WhatsApp, dan alamat domisili.</p>
                        </div>
                    </div>
                    <span class="material-symbols-outlined text-[18px] text-hanania-purple/35 group-hover:text-hanania-gold group-hover:translate-x-1 transition-all">arrow_forward</span>
                </a>

                <!-- 🏦 REKENING BANK REFUND (MENU BARU) -->
                <a href="{{ route('customer.profile.bank.edit') }}" class="profile-row group bg-white rounded-[1.7rem] border border-hanania-purple/10 p-5 sm:p-6 shadow-sm hover:bg-hanania-purple-light/30 hover:border-hanania-gold/30 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-4 min-w-0">
                        <div class="w-12 h-12 rounded-2xl bg-hanania-purple-light flex items-center justify-center text-hanania-purple border border-hanania-purple/10 shrink-0 group-hover:bg-hanania-purple group-hover:text-white transition-colors">
                            <span class="material-symbols-outlined text-[23px]">account_balance</span>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[8px] uppercase tracking-[.16em] font-black text-hanania-purple">Keuangan</p>
                            <h3 class="font-heading text-[15px] sm:text-[16px] font-black text-hanania-purple-dark mt-0.5 group-hover:text-hanania-purple transition-colors">Rekening Bank Refund</h3>
                            <p class="text-[10px] sm:text-[11px] text-gray-500 mt-1 leading-relaxed">Atur nomor rekening untuk keperluan pencairan dana.</p>
                        </div>
                    </div>
                    <span class="material-symbols-outlined text-[18px] text-hanania-purple/35 group-hover:text-hanania-gold group-hover:translate-x-1 transition-all">arrow_forward</span>
                </a>

                <!-- PASSWORD -->
                <a href="{{ route('customer.profile.password') }}" class="profile-row group bg-white rounded-[1.7rem] border border-hanania-purple/10 p-5 sm:p-6 shadow-sm hover:bg-hanania-purple-light/30 hover:border-hanania-gold/30 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-4 min-w-0">
                        <div class="w-12 h-12 rounded-2xl bg-hanania-purple-light flex items-center justify-center text-hanania-purple border border-hanania-purple/10 shrink-0 group-hover:bg-hanania-purple group-hover:text-white transition-colors">
                            <span class="material-symbols-outlined text-[23px]">lock_reset</span>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[8px] uppercase tracking-[.16em] font-black text-hanania-purple">Keamanan</p>
                            <h3 class="font-heading text-[15px] sm:text-[16px] font-black text-hanania-purple-dark mt-0.5 group-hover:text-hanania-purple transition-colors">Keamanan & Kata Sandi</h3>
                            <p class="text-[10px] sm:text-[11px] text-gray-500 mt-1 leading-relaxed">Perbarui kata sandi untuk menjaga keamanan akun.</p>
                        </div>
                    </div>
                    <span class="material-symbols-outlined text-[18px] text-hanania-purple/35 group-hover:text-hanania-gold group-hover:translate-x-1 transition-all">arrow_forward</span>
                </a>

                <!-- SUPPORT -->
                <a href="{{ route('contact') }}" class="profile-row group bg-white rounded-[1.7rem] border border-hanania-purple/10 p-5 sm:p-6 shadow-sm hover:bg-hanania-purple-light/30 hover:border-hanania-gold/30 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-4 min-w-0">
                        <div class="w-12 h-12 rounded-2xl bg-hanania-purple-light flex items-center justify-center text-hanania-purple border border-hanania-purple/10 shrink-0 group-hover:bg-hanania-purple group-hover:text-white transition-colors">
                            <span class="material-symbols-outlined text-[23px]">support_agent</span>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[8px] uppercase tracking-[.16em] font-black text-hanania-purple">Bantuan</p>
                            <h3 class="font-heading text-[15px] sm:text-[16px] font-black text-hanania-purple-dark mt-0.5 group-hover:text-hanania-purple transition-colors">Pusat Bantuan CS</h3>
                            <p class="text-[10px] sm:text-[11px] text-gray-500 mt-1 leading-relaxed">Hubungi admin {{ $shortCompanyName }} untuk bantuan.</p>
                        </div>
                    </div>
                    <span class="material-symbols-outlined text-[18px] text-hanania-purple/35 group-hover:text-hanania-gold group-hover:translate-x-1 transition-all">arrow_forward</span>
                </a>

                <!-- LOGOUT -->
                <form method="POST" action="{{ route('logout') }}" class="block lg:col-span-2">
                    @csrf
                    <button type="submit" class="profile-row group w-full h-full bg-white rounded-[1.7rem] border border-hanania-purple/10 p-5 sm:p-6 shadow-sm hover:bg-red-50 hover:border-red-200 flex items-center justify-between gap-4 text-left">
                        <div class="flex items-center gap-4 min-w-0">
                            <div class="w-12 h-12 rounded-2xl bg-hanania-purple-light flex items-center justify-center text-hanania-purple border border-hanania-purple/10 shrink-0 group-hover:bg-red-500 group-hover:text-white group-hover:border-red-500 transition-colors">
                                <span class="material-symbols-outlined text-[23px]">logout</span>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[8px] uppercase tracking-[.16em] font-black text-hanania-purple group-hover:text-red-500">Sesi</p>
                                <h3 class="font-heading text-[15px] sm:text-[16px] font-black text-hanania-purple-dark mt-0.5 group-hover:text-red-600 transition-colors">Keluar Aplikasi</h3>
                                <p class="text-[10px] sm:text-[11px] text-gray-500 mt-1 leading-relaxed">Akhiri sesi dan keluar dari akun Anda.</p>
                            </div>
                        </div>
                        <span class="material-symbols-outlined text-[18px] text-hanania-purple/35 group-hover:text-red-500 group-hover:translate-x-1 transition-all">arrow_forward</span>
                    </button>
                </form>
            </div>
        </section>

        <div class="mt-6 text-center">
            <span class="inline-flex items-center gap-1.5 text-[9px] text-gray-400 font-medium">
                <span class="material-symbols-outlined text-[13px] text-hanania-gold">verified_user</span>
                Profil dan keamanan akun Anda terlindungi.
            </span>
        </div>
    </div>
</div>
@endsection