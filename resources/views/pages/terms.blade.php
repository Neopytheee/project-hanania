@extends('layouts.customer.app')

@section('title', 'Syarat & Ketentuan Tabungan Umroh')

@section('content')
@php
    $phoneNumber = \App\Models\AppInformation::getValue('phone_number', '');
    $whatsappNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
    if (str_starts_with($whatsappNumber, '0')) {
        $whatsappNumber = '62'.substr($whatsappNumber, 1);
    }
@endphp
<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(14px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up {
        animation: fadeInUp .55s cubic-bezier(.16,1,.3,1) forwards;
    }
</style>

<div class="w-full min-h-screen pt-24 pb-16 bg-gray-50/50 animate-fade-in-up">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- PAGE HEADER --}}
        <div class="text-center mb-10 sm:mb-14">
            <span class="inline-flex items-center gap-2 text-[11px] sm:text-xs font-extrabold uppercase tracking-[0.18em] text-hanania-gold mb-3">
                <span class="w-1.5 h-1.5 rounded-full bg-hanania-gold"></span>
                Program Tabungan Umroh
            </span>
            <h1 class="font-heading font-black text-3xl sm:text-[42px] text-hanania-purple-dark leading-tight">
                Syarat & Ketentuan
            </h1>
            <p class="mt-4 text-sm sm:text-[15px] text-gray-500 max-w-2xl mx-auto leading-relaxed">
                Harap baca dengan saksama informasi dan kebijakan berikut sebelum bergabung dengan Program Tabungan Umroh PT. Rumah Hanania Sejahtera.
            </p>
        </div>

        {{-- MAIN CONTENT CARDS --}}
        <div class="space-y-6 sm:space-y-8">

            {{-- SECTION 1: Persyaratan & Pendaftaran --}}
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-hanania-purple/10 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-start gap-4 sm:gap-5">
                    <div class="w-12 h-12 rounded-2xl bg-hanania-purple-light flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-hanania-purple text-[24px]">app_registration</span>
                    </div>
                    <div>
                        <h3 class="font-heading font-extrabold text-lg sm:text-xl text-hanania-purple-dark mb-3">
                            Persyaratan & Pendaftaran
                        </h3>
                        <ul class="space-y-2.5 text-sm sm:text-[15px] text-gray-600 leading-relaxed list-disc list-outside ml-4 marker:text-hanania-gold">
                            <li>Calon jamaah harus memiliki tekad kuat untuk ibadah umroh dan menyiapkan berkas administrasi.</li>
                            <li>Wajib mengisi tautan pendaftaran serta mengunggah dokumen identitas (KTP) dan Surat Pernyataan.</li>
                            <li>Program ini dikelola secara transparan, <strong>Bebas RIBA</strong>, tanpa biaya administrasi bulanan, dan menawarkan gratis konsultasi Umroh/Haji.</li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- SECTION 2: Ketentuan Setoran --}}
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-hanania-purple/10 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden">
                <div class="absolute right-0 top-0 w-32 h-32 bg-hanania-gold/5 rounded-bl-[100px] -z-10"></div>
                <div class="flex items-start gap-4 sm:gap-5">
                    <div class="w-12 h-12 rounded-2xl bg-orange-50 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-hanania-gold text-[24px]">account_balance_wallet</span>
                    </div>
                    <div>
                        <h3 class="font-heading font-extrabold text-lg sm:text-xl text-hanania-purple-dark mb-3">
                            Ketentuan Setoran Tabungan
                        </h3>
                        <ul class="space-y-2.5 text-sm sm:text-[15px] text-gray-600 leading-relaxed list-disc list-outside ml-4 marker:text-hanania-gold">
                            <li>Jamaah diwajibkan melakukan setoran awal sebesar <strong>Rp 1.000.000</strong> ditambah nominal kode unik jamaah.</li>
                            <li>Setoran tabungan rutin selanjutnya sangat ringan, yakni minimal <strong>Rp 500.000 per bulan</strong>.</li>
                            <li>Setoran rutin harus dibayarkan paling lambat pada <strong>tanggal 25</strong> setiap bulannya.</li>
                            <li>Setiap kali transfer, jamaah wajib menyertakan kode unik (yang didapat setelah mendaftar). Transfer hanya sah jika ditujukan ke rekening resmi atas nama <strong>PT. Rumah Hanania Sejahtera</strong>.</li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- SECTION 3: Keberangkatan --}}
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-hanania-purple/10 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-start gap-4 sm:gap-5">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-emerald-600 text-[24px]">flight_takeoff</span>
                    </div>
                    <div>
                        <h3 class="font-heading font-extrabold text-lg sm:text-xl text-hanania-purple-dark mb-3">
                            Keberangkatan & Penyelenggara
                        </h3>
                        <ul class="space-y-2.5 text-sm sm:text-[15px] text-gray-600 leading-relaxed list-disc list-outside ml-4 marker:text-hanania-gold">
                            <li>Jika total tabungan peserta sudah mencukupi, jamaah akan segera diinformasikan untuk keberangkatan umroh sesuai dengan harga paket umroh yang berlaku pada saat itu.</li>
                            <li>Program ini memberikan keunggulan berupa fleksibilitas harga, jadwal keberangkatan, dan pilihan paket umroh.</li>
                            <li>Penentuan travel/penyelenggara ibadah umroh ditentukan sepenuhnya oleh <strong>PT. Rumah Hanania Sejahtera</strong>.</li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- SECTION 4: Pengunduran Diri --}}
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-red-100 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-start gap-4 sm:gap-5">
                    <div class="w-12 h-12 rounded-2xl bg-red-50 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-red-500 text-[24px]">cancel</span>
                    </div>
                    <div>
                        <h3 class="font-heading font-extrabold text-lg sm:text-xl text-red-800 mb-3">
                            Kebijakan Pengunduran Diri
                        </h3>
                        <ul class="space-y-2.5 text-sm sm:text-[15px] text-gray-600 leading-relaxed list-disc list-outside ml-4 marker:text-red-400">
                            <li>Peserta yang menarik dana tabungan umroh akan dianggap secara otomatis mengundurkan diri dari program ini.</li>
                            <li>Bagi peserta yang mengundurkan diri, maka akan dikenakan pemotongan <strong>biaya administrasi</strong> sesuai ketentuan perusahaan.</li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>

        {{-- FOOTER CONTACT --}}
        <div class="mt-12 text-center">
            <div class="inline-flex flex-col sm:flex-row items-center justify-center gap-3 bg-white border border-hanania-purple/10 px-6 py-4 rounded-full shadow-sm">
                <span class="text-sm font-bold text-gray-500">Butuh informasi lebih lanjut?</span>
                <div class="h-1 w-1 bg-gray-300 rounded-full hidden sm:block"></div>
                <a href="{{ rtrim(config('services.whatsapp.web_url'), '/') }}/{{ $whatsappNumber }}" target="_blank" class="inline-flex items-center gap-2 text-hanania-gold font-extrabold hover:text-hanania-purple transition-colors">
                    <span class="material-symbols-outlined text-[20px]">chat</span>
                    {{ $phoneNumber }}
                </a>
            </div>
        </div>

    </div>
</div>
@endsection