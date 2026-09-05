@extends('layouts.admin.app')

@section('header_title', 'Pengaturan Aplikasi')

@section('content')
<div class="max-w-4xl mx-auto animate-fade-in-up pb-10">
    
    <!-- ========================================== -->
    <!-- HEADER HALAMAN -->
    <!-- ========================================== -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h2 class="text-[24px] font-black text-slate-900 tracking-tight">Informasi & Pengaturan</h2>
            <p class="text-[13px] text-slate-500 font-medium mt-1">Kelola data legalitas, rekening utama, kontak, hingga sosial media perusahaan.</p>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- FORM CARD -->
    <!-- ========================================== -->
    <div class="card-admin p-6 sm:p-8">
        <form action="{{ route('admin.informations.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')

            <!-- ========================================== -->
            <!-- SECTION 1: IDENTITAS & LEGALITAS -->
            <!-- ========================================== -->
            <div>
                <h3 class="text-[14px] font-black text-slate-800 border-b border-slate-100 pb-3 mb-5 flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-hanania-purple/10 text-hanania-purple flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[18px]">domain</span>
                    </div>
                    Identitas & Legalitas
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Perusahaan -->
                    <div class="md:col-span-2">
                        <label class="block text-[11px] font-bold text-slate-700 mb-2 uppercase tracking-wide">Nama Perusahaan <span class="text-rose-500">*</span></label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-hanania-purple transition-colors">
                                <span class="material-symbols-outlined text-[20px]">corporate_fare</span>
                            </div>
                            <input type="text" name="company_name" value="{{ \App\Models\AppInformation::getValue('company_name') }}" class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-[14px] font-bold text-slate-800 focus:bg-white focus:border-hanania-purple focus:ring-4 focus:ring-hanania-purple/10 outline-none transition-all shadow-sm" placeholder="Contoh: PT Hanania Berkah Travel" required>
                        </div>
                    </div>

                    <!-- Tagline -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 mb-2 uppercase tracking-wide">Slogan / Tagline</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-hanania-purple transition-colors">
                                <span class="material-symbols-outlined text-[20px]">campaign</span>
                            </div>
                            <input type="text" name="company_tagline" value="{{ \App\Models\AppInformation::getValue('company_tagline') }}" class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-[14px] font-bold text-slate-800 focus:bg-white focus:border-hanania-purple focus:ring-4 focus:ring-hanania-purple/10 outline-none transition-all shadow-sm" placeholder="Contoh: Sahabat Perjalanan Ibadah Anda">
                        </div>
                    </div>

                    <!-- SK Kemenag -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 mb-2 uppercase tracking-wide">Nomor SK Kemenag</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-hanania-purple transition-colors">
                                <span class="material-symbols-outlined text-[20px]">verified</span>
                            </div>
                            <input type="text" name="sk_kemenag_number" value="{{ \App\Models\AppInformation::getValue('sk_kemenag_number') }}" class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-[14px] font-bold text-slate-800 focus:bg-white focus:border-hanania-purple focus:ring-4 focus:ring-hanania-purple/10 outline-none transition-all shadow-sm font-mono" placeholder="Contoh: SK Kemenag RI No. 123 Tahun 2024">
                        </div>
                    </div>

                    <!-- Logo Perusahaan -->
                    <div class="md:col-span-2">
                        <label class="block text-[11px] font-bold text-slate-700 mb-2 uppercase tracking-wide">Logo Utama Travel</label>
                        <div class="flex items-center gap-4 bg-slate-50 border border-slate-200 p-3 rounded-2xl">
                            <!-- Preview Kotak Logo -->
                            <div class="w-16 h-16 bg-white border border-slate-200 rounded-xl flex items-center justify-center overflow-hidden shrink-0 shadow-sm relative">
                                @php $logoPath = \App\Models\AppInformation::getValue('company_logo'); @endphp
                                @if($logoPath)
                                    <img src="{{ asset('storage/' . $logoPath) }}" class="w-full h-full object-contain p-2">
                                @else
                                    <span class="material-symbols-outlined text-slate-300 text-[24px]">image</span>
                                @endif
                            </div>
                            <!-- Input File -->
                            <div class="flex-1">
                                <input type="file" name="company_logo" accept="image/png, image/jpeg, image/jpg, image/webp" class="w-full text-[13px] text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-[12px] file:font-bold file:bg-hanania-purple/10 file:text-hanania-purple hover:file:bg-hanania-purple hover:file:text-white cursor-pointer transition-all">
                                <p class="text-[11px] text-slate-400 mt-1.5 font-medium">Format: JPG, PNG, WEBP (Latar Transparan). Maks 2MB.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- SECTION 2: REKENING BANK -->
            <!-- ========================================== -->
            <div>
                <h3 class="text-[14px] font-black text-slate-800 border-b border-slate-100 pb-3 mb-5 flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[18px]">account_balance</span>
                    </div>
                    Informasi Rekening Pembayaran
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Bank -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 mb-2 uppercase tracking-wide">Nama Bank <span class="text-rose-500">*</span></label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-emerald-500 transition-colors">
                                <span class="material-symbols-outlined text-[20px]">maps_home_work</span>
                            </div>
                            <input type="text" name="bank_name" value="{{ \App\Models\AppInformation::getValue('bank_name') }}" class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-[14px] font-bold text-slate-800 focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 outline-none transition-all shadow-sm" placeholder="Contoh: Bank Syariah Indonesia (BSI)" required>
                        </div>
                    </div>

                    <!-- Nomor Rekening -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 mb-2 uppercase tracking-wide">Nomor Rekening <span class="text-rose-500">*</span></label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-emerald-500 transition-colors">
                                <span class="material-symbols-outlined text-[20px]">pin</span>
                            </div>
                            <input type="text" name="bank_account_number" value="{{ \App\Models\AppInformation::getValue('bank_account_number') }}" class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-[14px] font-bold text-slate-800 focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 outline-none transition-all shadow-sm font-mono tracking-wider" placeholder="Contoh: 7123456789" required>
                        </div>
                    </div>

                    <!-- Atas Nama -->
                    <div class="md:col-span-2">
                        <label class="block text-[11px] font-bold text-slate-700 mb-2 uppercase tracking-wide">Atas Nama Rekening <span class="text-rose-500">*</span></label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-emerald-500 transition-colors">
                                <span class="material-symbols-outlined text-[20px]">person</span>
                            </div>
                            <input type="text" name="bank_account_name" value="{{ \App\Models\AppInformation::getValue('bank_account_name') }}" class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-[14px] font-bold text-slate-800 focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 outline-none transition-all shadow-sm" placeholder="Contoh: PT Hanania Berkah Travel" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- SECTION 3: KONTAK & LOKASI -->
            <!-- ========================================== -->
            <div>
                <h3 class="text-[14px] font-black text-slate-800 border-b border-slate-100 pb-3 mb-5 flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[18px]">contact_support</span>
                    </div>
                    Informasi Kontak & Lokasi
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- WhatsApp -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 mb-2 uppercase tracking-wide">WhatsApp Admin</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-emerald-500 transition-colors">
                                <span class="material-symbols-outlined text-[20px]">forum</span>
                            </div>
                            <input type="text" name="whatsapp_number" value="{{ \App\Models\AppInformation::getValue('whatsapp_number') }}" class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-[14px] font-bold text-slate-800 focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 outline-none transition-all shadow-sm" placeholder="Contoh: 6281234567890 (Gunakan 62)">
                        </div>
                    </div>

                    <!-- Telepon -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 mb-2 uppercase tracking-wide">Telepon Kantor</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-500 transition-colors">
                                <span class="material-symbols-outlined text-[20px]">call</span>
                            </div>
                            <input type="text" name="phone_number" value="{{ \App\Models\AppInformation::getValue('phone_number') }}" class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-[14px] font-bold text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all shadow-sm" placeholder="Contoh: 021-1234567">
                        </div>
                    </div>

                    <!-- Pesan WA -->
                    <div class="md:col-span-2">
                        <label class="block text-[11px] font-bold text-slate-700 mb-2 uppercase tracking-wide">Pesan Pembuka WhatsApp</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-emerald-500 transition-colors">
                                <span class="material-symbols-outlined text-[20px]">chat</span>
                            </div>
                            <input type="text" name="whatsapp_message" value="{{ \App\Models\AppInformation::getValue('whatsapp_message') }}" class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-[14px] font-bold text-slate-800 focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 outline-none transition-all shadow-sm" placeholder="Contoh: Assalamualaikum, saya ingin bertanya tentang paket Umroh...">
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="md:col-span-2">
                        <label class="block text-[11px] font-bold text-slate-700 mb-2 uppercase tracking-wide">Alamat Email Publik</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-amber-500 transition-colors">
                                <span class="material-symbols-outlined text-[20px]">mail</span>
                            </div>
                            <input type="email" name="email_address" value="{{ \App\Models\AppInformation::getValue('email_address') }}" class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-[14px] font-bold text-slate-800 focus:bg-white focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 outline-none transition-all shadow-sm" placeholder="Contoh: info@hanania.com">
                        </div>
                    </div>

                    <!-- Alamat -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 mb-2 uppercase tracking-wide">Alamat Lengkap Kantor</label>
                        <textarea name="office_address" rows="3" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl text-[13px] font-medium text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all shadow-sm" placeholder="Masukkan alamat lengkap dengan Kodepos">{{ \App\Models\AppInformation::getValue('office_address') }}</textarea>
                    </div>

                    <!-- Jam Operasional -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 mb-2 uppercase tracking-wide">Jam Operasional</label>
                        <textarea name="operational_hours" rows="3" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl text-[13px] font-medium text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all shadow-sm" placeholder="Contoh: Senin - Jumat: 08:00 - 17:00">{{ \App\Models\AppInformation::getValue('operational_hours') }}</textarea>
                    </div>

                    <!-- Maps Link -->
                    <div class="md:col-span-2">
                        <label class="block text-[11px] font-bold text-slate-700 mb-2 uppercase tracking-wide">Link Google Maps</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-500 transition-colors">
                                <span class="material-symbols-outlined text-[20px]">location_on</span>
                            </div>
                            <input type="url" name="google_maps_link" value="{{ \App\Models\AppInformation::getValue('google_maps_link') }}" class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-[14px] font-bold text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all shadow-sm" placeholder="Contoh: https://maps.google.com/?q=...">
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- SECTION 4: SOSIAL MEDIA -->
            <!-- ========================================== -->
            <div>
                <h3 class="text-[14px] font-black text-slate-800 border-b border-slate-100 pb-3 mb-5 flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-500 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[18px]">share</span>
                    </div>
                    Sosial Media <span class="text-slate-400 font-medium normal-case text-[12px]">(Opsional)</span>
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Instagram -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 mb-2 uppercase tracking-wide">Link Instagram</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-pink-500 transition-colors">
                                <span class="material-symbols-outlined text-[20px]">link</span>
                            </div>
                            <input type="url" name="instagram_link" value="{{ \App\Models\AppInformation::getValue('instagram_link') }}" class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-[14px] font-bold text-slate-800 focus:bg-white focus:border-pink-500 focus:ring-4 focus:ring-pink-500/10 outline-none transition-all shadow-sm" placeholder="https://instagram.com/...">
                        </div>
                    </div>

                    <!-- Facebook -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 mb-2 uppercase tracking-wide">Link Facebook</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-600 transition-colors">
                                <span class="material-symbols-outlined text-[20px]">link</span>
                            </div>
                            <input type="url" name="facebook_link" value="{{ \App\Models\AppInformation::getValue('facebook_link') }}" class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-[14px] font-bold text-slate-800 focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 outline-none transition-all shadow-sm" placeholder="https://facebook.com/...">
                        </div>
                    </div>

                    <!-- TikTok -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 mb-2 uppercase tracking-wide">Link TikTok</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-slate-900 transition-colors">
                                <span class="material-symbols-outlined text-[20px]">link</span>
                            </div>
                            <input type="url" name="tiktok_link" value="{{ \App\Models\AppInformation::getValue('tiktok_link') }}" class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-[14px] font-bold text-slate-800 focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-900/10 outline-none transition-all shadow-sm" placeholder="https://tiktok.com/@...">
                        </div>
                    </div>

                    <!-- YouTube -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 mb-2 uppercase tracking-wide">Link YouTube</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-red-500 transition-colors">
                                <span class="material-symbols-outlined text-[20px]">link</span>
                            </div>
                            <input type="url" name="youtube_link" value="{{ \App\Models\AppInformation::getValue('youtube_link') }}" class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-[14px] font-bold text-slate-800 focus:bg-white focus:border-red-500 focus:ring-4 focus:ring-red-500/10 outline-none transition-all shadow-sm" placeholder="https://youtube.com/...">
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- SUBMIT BUTTON -->
            <!-- ========================================== -->
            <div class="pt-6 mt-8 border-t border-slate-100 flex justify-end">
                <button type="submit" class="w-full sm:w-auto btn-admin-primary py-3.5 px-8 rounded-xl text-[14px] shadow-sm flex items-center justify-center gap-2 group">
                    <span class="material-symbols-outlined text-[20px] group-hover:rotate-12 transition-transform">cloud_upload</span> 
                    Simpan Semua Pengaturan
                </button>
            </div>

        </form>
    </div>
</div>
@endsection