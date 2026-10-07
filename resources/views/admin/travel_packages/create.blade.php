@extends('layouts.admin.app')

@section('header_title', 'Tambah Paket Baru')

@section('content')
<div class="max-w-4xl mx-auto animate-fade-in-up pb-10">
    
    <!-- HEADER -->
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.travel_packages.index') }}" class="w-10 h-10 bg-white border border-slate-200 rounded-full flex items-center justify-center text-slate-500 hover:text-hanania-purple hover:bg-hanania-purple/5 transition-all shadow-sm shrink-0">
            <span class="material-symbols-outlined text-[20px]">arrow_back</span>
        </a>
        <div>
            <h2 class="text-[24px] font-black text-slate-900 tracking-tight">Buat Paket Travel</h2>
            <p class="text-[13px] text-slate-500 font-medium">Lengkapi formulir di bawah untuk membuat penawaran baru.</p>
        </div>
    </div>

    <!-- FORM CARD -->
    <div class="card-admin p-6 sm:p-8">
        <form action="{{ route('admin.travel_packages.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kategori Paket -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 mb-2 uppercase tracking-wide">Kategori Jenis Paket <span class="text-rose-500">*</span></label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-hanania-purple transition-colors">
                            <span class="material-symbols-outlined text-[20px]">category</span>
                        </div>
                        <select name="category" class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-[14px] font-bold text-slate-800 focus:bg-white focus:border-hanania-purple focus:ring-4 focus:ring-hanania-purple/10 outline-none transition-all appearance-none cursor-pointer" required>
                            <option value="UMROH" {{ old('category') == 'UMROH' ? 'selected' : '' }}>Umroh (Kode Otomatis: UMR-...)</option>
                            <option value="HAJI" {{ old('category') == 'HAJI' ? 'selected' : '' }}>Haji (Kode Otomatis: HJI-...)</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400">
                            <span class="material-symbols-outlined text-[20px]">expand_more</span>
                        </div>
                    </div>
                    @error('category') <p class="text-rose-500 text-[11px] mt-1 font-bold">{{ $message }}</p> @enderror
                </div>
                
                <!-- Durasi -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 mb-2 uppercase tracking-wide">Durasi (Hari) <span class="text-rose-500">*</span></label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-hanania-purple transition-colors">
                            <span class="material-symbols-outlined text-[20px]">schedule</span>
                        </div>
                        <input type="number" name="duration_days" value="{{ old('duration_days') }}" placeholder="Contoh: 9" class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-[14px] font-bold text-slate-800 focus:bg-white focus:border-hanania-purple focus:ring-4 focus:ring-hanania-purple/10 outline-none transition-all" required>
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400 font-bold text-[13px]">Hari</div>
                    </div>
                    @error('duration_days') <p class="text-rose-500 text-[11px] mt-1 font-bold">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Nama Paket -->
            <div>
                <label class="block text-[11px] font-bold text-slate-700 mb-2 uppercase tracking-wide">Nama Paket <span class="text-rose-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Paket Umroh Reguler Bintang 4" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-[14px] font-bold text-slate-800 focus:bg-white focus:border-hanania-purple focus:ring-4 focus:ring-hanania-purple/10 outline-none transition-all" required>
                @error('name') <p class="text-rose-500 text-[11px] mt-1 font-bold">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Maskapai -->
            <div>
                <label class="block text-[11px] font-bold text-slate-700 mb-2 uppercase tracking-wide">Maskapai <span class="text-slate-400 normal-case">(Opsional)</span></label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-hanania-purple transition-colors">
                        <span class="material-symbols-outlined text-[20px]">airlines</span>
                    </div>
                    <input type="text" name="airline" value="{{ old('airline') }}" placeholder="Contoh: Saudia Airlines / Garuda" class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-[14px] font-bold text-slate-800 focus:bg-white focus:border-hanania-purple focus:ring-4 focus:ring-hanania-purple/10 outline-none transition-all">
                </div>
            </div>

            <!-- Hotel Mekkah -->
            <div>
                <label class="block text-[11px] font-bold text-slate-700 mb-2 uppercase tracking-wide">Hotel Mekkah <span class="text-slate-400 normal-case">(Opsional)</span></label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-hanania-purple transition-colors">
                        <span class="material-symbols-outlined text-[20px]">domain</span>
                    </div>
                    <input type="text" name="hotel_mekkah" value="{{ old('hotel_mekkah') }}" placeholder="Contoh: Pullman Zamzam / Bintang 5" class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-[14px] font-bold text-slate-800 focus:bg-white focus:border-hanania-purple focus:ring-4 focus:ring-hanania-purple/10 outline-none transition-all">
                </div>
            </div>
        </div>

            <!-- Harga Target -->
            <div>
                <label class="block text-[11px] font-bold text-slate-700 mb-2 uppercase tracking-wide">Estimasi Harga (Target Tabungan) <span class="text-rose-500">*</span></label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 font-black text-[14px] group-focus-within:text-hanania-purple transition-colors">
                        Rp
                    </div>
                    <input type="number" name="estimated_price" value="{{ old('estimated_price') }}" placeholder="Contoh: 28500000" class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-[14px] font-bold text-slate-800 focus:bg-white focus:border-hanania-purple focus:ring-4 focus:ring-hanania-purple/10 outline-none transition-all" required>
                </div>
                <p class="text-[11px] text-slate-400 mt-1.5 font-medium">Ketik angka saja tanpa titik (Contoh: 28500000).</p>
                @error('estimated_price') <p class="text-rose-500 text-[11px] mt-1 font-bold">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Deskripsi -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 mb-2 uppercase tracking-wide">Deskripsi Paket <span class="text-rose-500">*</span></label>
                    <textarea name="description" rows="5" placeholder="Tuliskan deskripsi singkat mengenai paket perjalanan ini..." class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl text-[13px] font-medium text-slate-800 focus:bg-white focus:border-hanania-purple focus:ring-4 focus:ring-hanania-purple/10 outline-none transition-all" required>{{ old('description') }}</textarea>
                    @error('description') <p class="text-rose-500 text-[11px] mt-1 font-bold">{{ $message }}</p> @enderror
                </div>

                <!-- Fasilitas -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 mb-2 uppercase tracking-wide">Fasilitas / Benefit <span class="text-rose-500">*</span></label>
                    <textarea name="facilities" rows="5" placeholder="Contoh:&#10;- Tiket Pesawat PP Ekonomi&#10;- Hotel Bintang 4&#10;- Visa Umroh" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl text-[13px] font-medium text-slate-800 focus:bg-white focus:border-hanania-purple focus:ring-4 focus:ring-hanania-purple/10 outline-none transition-all leading-relaxed" required>{{ old('facilities') }}</textarea>
                    <p class="text-[11px] text-slate-400 mt-1.5 font-medium">Gunakan baris baru (Enter) untuk memisahkan setiap fasilitas.</p>
                    @error('facilities') <p class="text-rose-500 text-[11px] mt-1 font-bold">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Foto Brosur -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 mb-2 uppercase tracking-wide">Foto / Brosur Paket <span class="text-slate-400 normal-case">(Opsional)</span></label>
                    <input type="file" name="image" accept=".jpg,.jpeg,.png" class="w-full text-[13px] text-slate-500 file:mr-4 file:py-2.5 file:px-5 file:rounded-lg file:border-0 file:text-[12px] file:font-bold file:bg-hanania-purple/10 file:text-hanania-purple hover:file:bg-hanania-purple hover:file:text-white cursor-pointer transition-all border border-slate-200 rounded-xl bg-slate-50 p-1.5">
                    <p class="text-[11px] text-slate-400 mt-1.5 font-medium">Format: JPG/PNG. Maksimal 2MB.</p>
                    @error('image') <p class="text-rose-500 text-[11px] mt-1 font-bold">{{ $message }}</p> @enderror
                </div>

                <!-- Status Publikasi -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 mb-2 uppercase tracking-wide">Status Publikasi <span class="text-rose-500">*</span></label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-hanania-purple transition-colors">
                            <span class="material-symbols-outlined text-[20px]">visibility</span>
                        </div>
                        <select name="status" class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-[14px] font-bold text-slate-800 focus:bg-white focus:border-hanania-purple focus:ring-4 focus:ring-hanania-purple/10 outline-none transition-all appearance-none cursor-pointer" required>
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft (Simpan Konsep)</option>
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Aktif (Siap Dijual)</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400">
                            <span class="material-symbols-outlined text-[20px]">expand_more</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tombol Submit -->
            <div class="pt-4 mt-6 border-t border-slate-100 flex justify-end">
                <button type="submit" class="w-full sm:w-auto btn-admin-primary py-3 px-8 rounded-xl text-[14px] shadow-sm flex items-center justify-center gap-2 group">
                    <span class="material-symbols-outlined text-[20px]">save</span> Simpan Paket
                </button>
            </div>
        </form>
    </div>
</div>
@endsection