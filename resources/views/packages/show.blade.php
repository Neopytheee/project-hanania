@extends('layouts.customer.app')

@section('title', 'Detail Paket')

@section('content')
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(18px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up { animation: fadeInUp .65s cubic-bezier(.16,1,.3,1) forwards; }
        .hide-scroll::-webkit-scrollbar { display: none; }
        .hide-scroll { -ms-overflow-style: none; scrollbar-width: none; }
        .premium-hover { transition: transform .28s ease, box-shadow .28s ease, border-color .28s ease; }
        .premium-hover:hover { transform: translateY(-3px); }
        .section-kicker { letter-spacing: .18em; }
    </style>

    @php
        $companyName = \App\Models\AppInformation::getValue('company_name', 'Hanania');
    @endphp

    <div class="w-full min-h-screen bg-slate-50/50 pt-24 pb-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 animate-fade-in-up">

            <!-- TOP NAV -->
            <div class="flex items-center justify-between gap-4 mb-5">
                <a href="{{ route('packages.index') }}" class="inline-flex items-center gap-2 bg-white border border-hanania-purple/10 text-hanania-purple-dark px-3.5 py-2.5 rounded-xl shadow-sm hover:text-hanania-purple transition-colors text-[12px] sm:text-[13px] font-bold">
                    <span class="material-symbols-outlined text-[17px]">arrow_back</span>
                    <span class="hidden sm:inline">Kembali ke Katalog</span>
                    <span class="sm:hidden">Kembali</span>
                </a>
                <span class="hidden sm:block text-[9px] font-black uppercase section-kicker text-gray-400">Detail Paket</span>
            </div>

            <!-- HERO: VISUAL + INFORMATION -->
            <section class="overflow-hidden rounded-[2rem] bg-white border border-hanania-purple/10 shadow-xl">
                <div class="grid lg:grid-cols-[1.15fr_.85fr]">
                    <div class="relative min-h-[320px] sm:min-h-[400px] lg:min-h-[500px]">
                        @if($travelPackage->image)
                            <a href="{{ asset('storage/' . $travelPackage->image) }}" target="_blank" class="block h-full">
                                <img src="{{ asset('storage/' . $travelPackage->image) }}" alt="{{ $travelPackage->name }}" class="w-full h-full object-cover">
                            </a>
                        @else
                            <div class="w-full h-full min-h-[320px] flex items-center justify-center bg-hanania-purple-light/25 text-hanania-purple/30">
                                <span class="material-symbols-outlined text-[80px]">mosque</span>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-hanania-purple-dark/90 via-hanania-purple-dark/20 to-transparent"></div>

                        <div class="absolute left-5 right-5 bottom-5 sm:left-8 sm:right-8 sm:bottom-8 text-white">
                            <div class="flex flex-wrap gap-2 mb-3">
                                <span class="bg-hanania-gold text-white px-3 py-1.5 rounded-full text-[8px] sm:text-[9px] font-black uppercase tracking-[.14em]">Premium Package</span>
                                <span class="bg-white/10 backdrop-blur-md border border-white/15 text-white px-3 py-1.5 rounded-full text-[8px] sm:text-[9px] font-black uppercase tracking-[.14em]">{{ $travelPackage->duration_days }} Hari</span>
                            </div>
                            <h1 class="font-heading text-[31px] sm:text-[45px] lg:text-[50px] font-black leading-[1.02] tracking-tight">{{ $travelPackage->name }}</h1>
                        </div>
                    </div>

                    <div class="bg-hanania-purple-dark text-white p-6 sm:p-8 lg:p-10 flex flex-col justify-between">
                        <div>
                            <p class="text-[8px] uppercase section-kicker text-white/40 font-black">Investasi Perjalanan</p>
                            <p class="font-heading text-[34px] sm:text-[42px] font-black text-hanania-gold leading-none mt-2">Rp {{ number_format($travelPackage->estimated_price, 0, ',', '.') }}</p>
                            <p class="text-[10px] text-white/45 mt-2">Estimasi harga paket</p>
                        </div>

                        <div class="mt-8 lg:mt-0">
                            <div class="rounded-2xl bg-white/5 border border-white/10 p-4">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[17px] text-hanania-gold [font-variation-settings:'FILL'_1]">verified</span>
                                    <span class="text-[11px] font-black">Tabungan Aman & Fleksibel</span>
                                </div>
                                <p class="text-[10px] text-white/45 mt-2 leading-relaxed">Mulai dari niat yang baik. Proses dibuat sederhana supaya Anda bisa fokus pada persiapannya.</p>
                            </div>

                            <a href="#daftar" class="mt-3 w-full inline-flex items-center justify-center gap-2 btn-hanania-gold py-3.5 rounded-xl text-[12px] font-black">
                                Mulai Niat Suci
                                <span class="material-symbols-outlined text-[17px]">arrow_downward</span>
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            <!-- QUICK FACTS -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mt-4 mb-8">
                <div class="bg-white border border-hanania-purple/10 rounded-2xl p-4 shadow-sm">
                    <span class="material-symbols-outlined text-hanania-purple text-[19px]">calendar_month</span>
                    <p class="text-[8px] uppercase section-kicker text-gray-400 font-black mt-3">Durasi</p>
                    <p class="font-heading text-[16px] font-black text-hanania-purple-dark mt-1">{{ $travelPackage->duration_days }} Hari</p>
                </div>
                <div class="bg-white border border-hanania-purple/10 rounded-2xl p-4 shadow-sm">
                    <span class="material-symbols-outlined text-hanania-purple text-[19px]">airlines</span>
                    <p class="text-[8px] uppercase section-kicker text-gray-400 font-black mt-3">Maskapai</p>
                    <p class="font-heading text-[16px] font-black text-hanania-purple-dark mt-1 truncate">{{ $travelPackage->airline ?? 'Menyusul' }}</p>
                </div>
                <div class="bg-white border border-hanania-purple/10 rounded-2xl p-4 shadow-sm">
                    <span class="material-symbols-outlined text-hanania-purple text-[19px]">domain</span>
                    <p class="text-[8px] uppercase section-kicker text-gray-400 font-black mt-3">Hotel Mekkah</p>
                    <p class="font-heading text-[16px] font-black text-hanania-purple-dark mt-1 truncate">{{ $travelPackage->hotel_mekkah ?? 'Premium' }}</p>
                </div>
                <div class="bg-hanania-purple-light/45 border border-hanania-purple/10 rounded-2xl p-4 shadow-sm">
                    <span class="material-symbols-outlined text-hanania-purple text-[19px]">verified</span>
                    <p class="text-[8px] uppercase section-kicker text-hanania-purple/55 font-black mt-3">Paket</p>
                    <p class="font-heading text-[16px] font-black text-hanania-purple-dark mt-1">Premium</p>
                </div>
            </div>

            <!-- MAIN -->
            <div class="grid lg:grid-cols-[1fr_390px] gap-6 items-start">
                <main class="space-y-6">

                    <!-- ABOUT -->
                    <section class="bg-white border border-hanania-purple/10 rounded-[1.8rem] p-6 sm:p-8 shadow-sm">
                        <div class="flex items-start justify-between gap-4 mb-5">
                            <div>
                                <p class="text-[8px] uppercase section-kicker text-hanania-purple font-black">Tentang Program</p>
                                <h2 class="font-heading text-[24px] sm:text-[28px] font-black text-hanania-purple-dark mt-1">Rasakan perjalanan yang lebih tenang</h2>
                            </div>
                            <span class="material-symbols-outlined text-hanania-purple-light text-[28px]">auto_stories</span>
                        </div>
                        <p class="text-[13px] sm:text-[14px] text-gray-600 leading-7 font-medium whitespace-pre-line">{{ $travelPackage->description ?? 'Belum ada deskripsi rinci untuk paket ini.' }}</p>
                    </section>

                    <!-- FACILITIES -->
                    <section class="bg-white border border-hanania-purple/10 rounded-[1.8rem] p-6 sm:p-8 shadow-sm">
                        <div class="mb-6">
                            <p class="text-[8px] uppercase section-kicker text-hanania-purple font-black">Yang Anda Dapatkan</p>
                            <h2 class="font-heading text-[24px] sm:text-[28px] font-black text-hanania-purple-dark mt-1">Fasilitas Termasuk</h2>
                        </div>
                        @if($travelPackage->facilities)
                            <ul class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach(explode("\n", $travelPackage->facilities) as $facility)
                                    @if(trim($facility) !== '')
                                        <li class="flex items-start gap-3 bg-hanania-purple-light/25 border border-hanania-purple/10 rounded-2xl px-4 py-3.5">
                                            <span class="material-symbols-outlined text-hanania-gold text-[18px] shrink-0 mt-0.5 [font-variation-settings:'FILL'_1]">check_circle</span>
                                            <span class="text-[12px] sm:text-[13px] text-hanania-purple-dark font-semibold leading-relaxed">{{ trim($facility) }}</span>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        @else
                            <p class="text-[13px] text-gray-400 italic">Rincian fasilitas akan diperbarui segera.</p>
                        @endif
                    </section>

                    <!-- TESTIMONIALS -->
                    @if(isset($testimonials) && $testimonials->count() > 0)
                        <section class="bg-hanania-purple-light/35 border border-hanania-purple/10 rounded-[1.8rem] p-6 sm:p-8">
                            <div class="mb-6">
                                <p class="text-[8px] uppercase section-kicker text-hanania-purple font-black">Pengalaman Jamaah</p>
                                <h2 class="font-heading text-[24px] sm:text-[28px] font-black text-hanania-purple-dark mt-1">Cerita dari mereka</h2>
                            </div>
                            <div class="flex gap-4 overflow-x-auto hide-scroll snap-x snap-mandatory pb-1">
                                @foreach($testimonials as $testi)
                                    <article class="shrink-0 snap-start w-[82vw] sm:w-[330px] rounded-[1.4rem] bg-white border border-hanania-purple/10 p-5 shadow-sm">
                                        <div class="flex text-hanania-gold text-[14px] mb-3">
                                            @for($i = 0; $i < $testi->rating; $i++)
                                                <span class="material-symbols-outlined [font-variation-settings:'FILL'_1]">star</span>
                                            @endfor
                                        </div>
                                        <p class="text-[12px] sm:text-[13px] leading-relaxed text-gray-600 font-medium italic">"{{ $testi->content }}"</p>
                                        <div class="mt-5 pt-4 border-t border-gray-100">
                                            <p class="font-black text-[11px] text-hanania-purple-dark">{{ optional($testi->user)->name ?? 'Jamaah' }}</p>
                                            <p class="text-[9px] text-gray-400 mt-0.5">Jamaah {{ $companyName }}</p>
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        </section>
                    @endif
                </main>

                <!-- BOOKING / FORM -->
                <aside id="daftar" class="lg:sticky lg:top-24">
                    <div class="bg-white border border-hanania-purple/10 rounded-[1.9rem] shadow-xl overflow-hidden">
                        <div class="p-6 sm:p-7">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="text-[8px] uppercase section-kicker text-hanania-purple font-black">Langkah Selanjutnya</p>
                                    <h3 class="font-heading text-[24px] font-black text-hanania-purple-dark mt-1">Daftarkan Jamaah</h3>
                                </div>
                                <span class="w-10 h-10 rounded-xl bg-hanania-purple-light/55 text-hanania-purple flex items-center justify-center">
                                    <span class="material-symbols-outlined text-[19px]">edit_note</span>
                                </span>
                            </div>

                            <p class="text-[11px] text-gray-500 leading-relaxed mt-3">Pilih siapa yang akan didaftarkan. Hanya beberapa langkah untuk memulai.</p>
                        </div>

                        <div class="px-6 pb-6 sm:px-7 sm:pb-7">
                            <form action="{{ route('customer.enrollments.store') }}" method="POST" class="space-y-4">
                                @csrf
                                <input type="hidden" name="travel_package_id" value="{{ $travelPackage->id }}">

                                <div>
                                    <label class="block text-[11px] font-black text-hanania-purple-dark mb-1.5">Pendaftar / Jamaah</label>
                                    <div class="relative">
                                        <select name="relationship" id="relationshipSelect" class="w-full px-4 py-3.5 bg-slate-50 border border-gray-200 rounded-xl text-[12px] font-semibold text-gray-800 outline-none focus:bg-white focus:border-hanania-purple transition-all appearance-none cursor-pointer" onchange="togglePassengerInput(this)">
                                            <option value="Diri Sendiri">Diri Sendiri (Pemilik Akun)</option>
                                            <option value="Istri / Suami">Istri / Suami</option>
                                            <option value="Anak">Anak</option>
                                            <option value="Orang Tua">Orang Tua</option>
                                            <option value="Lainnya">Lainnya (Keluarga)</option>
                                        </select>
                                        <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-[18px]">expand_more</span>
                                    </div>
                                </div>

                                <div id="passengerInputContainer" class="hidden">
                                    <label class="block text-[11px] font-black text-hanania-purple-dark mb-1.5">Nama Lengkap Jamaah</label>
                                    <input type="text" name="passenger_name" placeholder="Sesuai KTP / Paspor" class="w-full px-4 py-3.5 bg-slate-50 border border-gray-200 rounded-xl text-[12px] font-semibold text-gray-800 outline-none focus:bg-white focus:border-hanania-purple transition-all">
                                    <p class="text-[10px] text-gray-400 mt-1.5 flex items-center gap-1"><span class="material-symbols-outlined text-[13px]">info</span> Untuk keperluan manifest & visa.</p>
                                </div>

                                <button type="submit" class="w-full btn-hanania-gold py-4 rounded-xl text-[13px] font-black flex items-center justify-center gap-2 group shadow-md hover:-translate-y-0.5 transition-transform">
                                    Buka Rekening Sekarang
                                    <span class="material-symbols-outlined text-[17px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
                                </button>

                                <div class="flex items-center justify-center gap-1.5 text-[9px] text-gray-400 font-semibold pt-1">
                                    <span class="material-symbols-outlined text-[13px]">lock</span>
                                    Data aman & terenkripsi
                                </div>
                            </form>
                        </div>
                    </div>
                </aside>
            </div>

            <!-- ASSURANCE -->
            <div class="mt-6 grid sm:grid-cols-3 gap-3">
                <div class="flex items-center gap-3 bg-white border border-hanania-purple/10 rounded-2xl px-4 py-3 shadow-sm">
                    <span class="material-symbols-outlined text-hanania-purple">verified_user</span>
                    <div><p class="text-[10px] font-black text-hanania-purple-dark">Transaksi Aman</p><p class="text-[9px] text-gray-400 mt-0.5">Data dijaga dengan aman</p></div>
                </div>
                <div class="flex items-center gap-3 bg-white border border-hanania-purple/10 rounded-2xl px-4 py-3 shadow-sm">
                    <span class="material-symbols-outlined text-hanania-gold">support_agent</span>
                    <div><p class="text-[10px] font-black text-hanania-purple-dark">Dibantu Tim Hanania</p><p class="text-[9px] text-gray-400 mt-0.5">Siap menjawab kebutuhan Anda</p></div>
                </div>
                <div class="flex items-center gap-3 bg-white border border-hanania-purple/10 rounded-2xl px-4 py-3 shadow-sm">
                    <span class="material-symbols-outlined text-hanania-purple">favorite</span>
                    <div><p class="text-[10px] font-black text-hanania-purple-dark">Fokus Ibadah</p><p class="text-[9px] text-gray-400 mt-0.5">Kami bantu sederhanakan persiapan</p></div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function togglePassengerInput(select) {
        const container = document.getElementById('passengerInputContainer');
        const inputField = container.querySelector('input[name="passenger_name"]');

        if (select.value === 'Diri Sendiri') {
            container.classList.add('hidden');
            inputField.removeAttribute('required');
            inputField.value = '';
        } else {
            container.classList.remove('hidden');
            inputField.setAttribute('required', 'required');
        }
    }
    </script>
@endsection
