@extends('layouts.admin.app')

@section('header_title', 'Edit Paket Travel')

@section('content')
<div class="max-w-4xl mx-auto animate-fade-in-up pb-10">
    
    <!-- HEADER -->
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.travel_packages.index') }}" class="w-10 h-10 bg-white border border-slate-200 rounded-full flex items-center justify-center text-slate-500 hover:text-hanania-purple hover:bg-hanania-purple/5 transition-all shadow-sm shrink-0">
            <span class="material-symbols-outlined text-[20px]">arrow_back</span>
        </a>
        <div>
            <h2 class="text-[24px] font-black text-slate-900 tracking-tight">Edit Paket: {{ $travelPackage->code }}</h2>
            <p class="text-[13px] text-slate-500 font-medium">Lakukan penyesuaian pada paket {{ $travelPackage->name }}.</p>
        </div>
    </div>

    <!-- FORM CARD -->
    <div class="card-admin p-6 sm:p-8">
        <form action="{{ route('admin.travel_packages.update', $travelPackage->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kode Paket (Biasanya Statis/Edit Hati-hati) -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 mb-2 uppercase tracking-wide">Kode Paket <span class="text-rose-500">*</span></label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-hanania-purple transition-colors">
                            <span class="material-symbols-outlined text-[20px]">qr_code_2</span>
                        </div>
                        <input type="text" name="code" value="{{ old('code', $travelPackage->code) }}" class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-[14px] font-bold text-slate-800 focus:bg-white focus:border-hanania-purple focus:ring-4 focus:ring-hanania-purple/10 outline-none transition-all font-mono" required>
                    </div>
                    @error('code') <p class="text-rose-500 text-[11px] mt-1 font-bold">{{ $message }}</p> @enderror
                </div>
                
                <!-- Durasi -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 mb-2 uppercase tracking-wide">Durasi (Hari) <span class="text-rose-500">*</span></label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-hanania-purple transition-colors">
                            <span class="material-symbols-outlined text-[20px]">schedule</span>
                        </div>
                        <input type="number" name="duration_days" value="{{ old('duration_days', $travelPackage->duration_days) }}" class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-[14px] font-bold text-slate-800 focus:bg-white focus:border-hanania-purple focus:ring-4 focus:ring-hanania-purple/10 outline-none transition-all" required>
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400 font-bold text-[13px]">Hari</div>
                    </div>
                    @error('duration_days') <p class="text-rose-500 text-[11px] mt-1 font-bold">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Nama Paket -->
            <div>
                <label class="block text-[11px] font-bold text-slate-700 mb-2 uppercase tracking-wide">Nama Paket <span class="text-rose-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $travelPackage->name) }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-[14px] font-bold text-slate-800 focus:bg-white focus:border-hanania-purple focus:ring-4 focus:ring-hanania-purple/10 outline-none transition-all" required>
                @error('name') <p class="text-rose-500 text-[11px] mt-1 font-bold">{{ $message }}</p> @enderror
            </div>

            <!-- Harga Target -->
            <div>
                <label class="block text-[11px] font-bold text-slate-700 mb-2 uppercase tracking-wide">Estimasi Harga (Target Tabungan) <span class="text-rose-500">*</span></label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 font-black text-[14px] group-focus-within:text-hanania-purple transition-colors">
                        Rp
                    </div>
                    <input type="number" name="estimated_price" value="{{ old('estimated_price', $travelPackage->estimated_price) }}" class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-[14px] font-bold text-slate-800 focus:bg-white focus:border-hanania-purple focus:ring-4 focus:ring-hanania-purple/10 outline-none transition-all" required>
                </div>
                <p class="text-[11px] text-slate-400 mt-1.5 font-medium">Ketik angka saja tanpa titik (Contoh: 28500000).</p>
                @error('estimated_price') <p class="text-rose-500 text-[11px] mt-1 font-bold">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Deskripsi -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 mb-2 uppercase tracking-wide">Deskripsi Paket <span class="text-rose-500">*</span></label>
                    <textarea name="description" rows="5" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl text-[13px] font-medium text-slate-800 focus:bg-white focus:border-hanania-purple focus:ring-4 focus:ring-hanania-purple/10 outline-none transition-all" required>{{ old('description', $travelPackage->description) }}</textarea>
                    @error('description') <p class="text-rose-500 text-[11px] mt-1 font-bold">{{ $message }}</p> @enderror
                </div>

                <!-- Fasilitas -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 mb-2 uppercase tracking-wide">Fasilitas / Benefit <span class="text-rose-500">*</span></label>
                    <textarea name="facilities" rows="5" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl text-[13px] font-medium text-slate-800 focus:bg-white focus:border-hanania-purple focus:ring-4 focus:ring-hanania-purple/10 outline-none transition-all leading-relaxed" required>{{ old('facilities', $travelPackage->facilities) }}</textarea>
                    <p class="text-[11px] text-slate-400 mt-1.5 font-medium">Gunakan baris baru (Enter) untuk memisahkan setiap fasilitas.</p>
                    @error('facilities') <p class="text-rose-500 text-[11px] mt-1 font-bold">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                <!-- Foto Brosur -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 mb-2 uppercase tracking-wide">Foto / Brosur Paket</label>
                    
                    <!-- PREVIEW GAMBAR LAMA (Desain Estetik) -->
                    @if($travelPackage->image)
                        <div class="mb-4 bg-slate-50 border border-slate-200 rounded-xl p-3 flex gap-4 items-center">
                            <div class="w-20 h-20 rounded-lg overflow-hidden shrink-0 border border-slate-200">
                                <img src="{{ asset('storage/' . $travelPackage->image) }}" alt="Preview" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <p class="text-[12px] font-extrabold text-slate-800">Brosur Saat Ini</p>
                                <p class="text-[11px] font-medium text-slate-500 mt-0.5 leading-tight">Unggah file baru di bawah ini jika ingin mengganti brosur lama.</p>
                            </div>
                        </div>
                    @endif

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
                            <option value="draft" {{ old('status', $travelPackage->status) == 'draft' ? 'selected' : '' }}>Draft (Simpan Konsep)</option>
                            <option value="active" {{ old('status', $travelPackage->status) == 'active' ? 'selected' : '' }}>Aktif (Siap Dijual)</option>
                            <option value="inactive" {{ old('status', $travelPackage->status) == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                            <option value="archived" {{ old('status', $travelPackage->status) == 'archived' ? 'selected' : '' }}>Arsip (Paket Tutup)</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400">
                            <span class="material-symbols-outlined text-[20px]">expand_more</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tombol Submit -->
            <div class="pt-4 mt-6 border-t border-slate-100 flex justify-end">
                <button type="submit" class="w-full sm:w-auto bg-amber-500 hover:bg-amber-600 text-white font-bold py-3 px-8 rounded-xl text-[14px] shadow-sm flex items-center justify-center gap-2 transition-all group">
                    <span class="material-symbols-outlined text-[20px]">update</span> Perbarui Data Paket
                </button>
            </div>
        </form>
    </div>
</div>
@endsection