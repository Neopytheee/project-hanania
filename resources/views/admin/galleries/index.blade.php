@extends('layouts.admin.app')

@section('header_title', 'Manajemen Galeri')

@section('content')
<div class="max-w-7xl mx-auto animate-fade-in-up pb-12">
    
    <!-- HEADER -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h2 class="text-[24px] font-black text-slate-900 tracking-tight flex items-center gap-2">
                <span class="material-symbols-outlined text-hanania-purple text-[28px]">photo_library</span> Galeri & Dokumentasi
            </h2>
            <p class="text-[13px] text-slate-500 font-medium mt-1">Kelola album foto dan dokumentasi perjalanan ibadah jamaah.</p>
        </div>
    </div>

    <!-- ERROR ALERT (Jika Upload Gagal) -->
    @if($errors->any())
        <div class="bg-rose-50 border border-rose-200 p-4 rounded-2xl mb-6 shadow-sm flex items-start gap-3">
            <span class="material-symbols-outlined text-rose-500 mt-0.5">error</span>
            <div>
                <h4 class="text-[13px] font-bold text-rose-900 mb-1">Gagal menyimpan data!</h4>
                <ul class="list-disc pl-4 text-[12px] font-medium text-rose-700 space-y-0.5">
                    @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- KOLOM KIRI: FORM UPLOAD & KATEGORI -->
        <div class="space-y-6 lg:sticky lg:top-24 h-fit">
            
            <!-- FORM 1: Tambah Kategori -->
            <div class="card-admin p-5">
                <h3 class="text-[14px] font-black text-slate-800 mb-4 flex items-center gap-2 border-b border-slate-100 pb-3">
                    <span class="material-symbols-outlined text-hanania-purple text-[18px]">category</span> Tambah Kategori
                </h3>
                <form action="{{ route('admin.galleries.category.store') }}" method="POST">
                    @csrf
                    <label class="block text-[11px] font-bold text-slate-700 mb-2 uppercase tracking-wide">Nama Kategori Baru</label>
                    <div class="flex gap-2">
                        <input type="text" name="name" placeholder="Misal: Hotel Makkah" required class="flex-1 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-[13px] font-bold text-slate-800 focus:bg-white focus:border-hanania-purple focus:ring-4 focus:ring-hanania-purple/10 outline-none transition-all">
                        <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white px-5 py-2.5 rounded-xl font-bold text-[13px] transition-colors shadow-sm flex items-center justify-center shrink-0">
                            Buat
                        </button>
                    </div>
                </form>
            </div>

            <!-- FORM 2: Upload Foto -->
            <div class="card-admin p-5">
                <h3 class="text-[14px] font-black text-slate-800 mb-4 flex items-center gap-2 border-b border-slate-100 pb-3">
                    <span class="material-symbols-outlined text-hanania-purple text-[18px]">cloud_upload</span> Upload Foto Baru
                </h3>
                
                <form action="{{ route('admin.galleries.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    
                    <!-- Pilih Kategori -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 mb-2 uppercase tracking-wide">Pilih Kategori <span class="text-rose-500">*</span></label>
                        <div class="relative group">
                            <select name="gallery_category_id" required class="w-full pl-4 pr-10 py-3 bg-slate-50 border border-slate-200 rounded-xl text-[13px] font-bold text-slate-800 focus:bg-white focus:border-hanania-purple focus:ring-4 focus:ring-hanania-purple/10 outline-none transition-all appearance-none cursor-pointer">
                                <option value="" disabled selected>-- Pilih Kategori Album --</option>
                                @forelse($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @empty
                                    <option value="" disabled>Belum ada kategori 👆</option>
                                @endforelse
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400">
                                <span class="material-symbols-outlined text-[18px]">expand_more</span>
                            </div>
                        </div>
                    </div>

                    <!-- Judul Foto -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 mb-2 uppercase tracking-wide">Judul Foto <span class="text-rose-500">*</span></label>
                        <input type="text" name="title" required placeholder="Contoh: Jamaah tiba di Bandara" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-[13px] font-bold text-slate-800 focus:bg-white focus:border-hanania-purple focus:ring-4 focus:ring-hanania-purple/10 outline-none transition-all">
                    </div>

                    <!-- File Foto -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 mb-2 uppercase tracking-wide">File Foto <span class="text-rose-500">*</span></label>
                        <input type="file" name="image_path" required accept="image/*" class="w-full text-[13px] text-slate-500 file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:text-[12px] file:font-bold file:bg-hanania-purple/10 file:text-hanania-purple hover:file:bg-hanania-purple hover:file:text-white cursor-pointer transition-all border border-slate-200 rounded-xl bg-slate-50 p-1.5">
                    </div>

                    <button type="submit" class="w-full btn-admin-primary py-3.5 rounded-xl text-[13px] flex justify-center items-center gap-2 group mt-2">
                        <span class="material-symbols-outlined text-[20px] group-hover:-translate-y-1 transition-transform">upload</span> Upload ke Galeri
                    </button>
                </form>
            </div>
        </div>

        <!-- KOLOM KANAN: PREVIEW GRID FOTO -->
        <div class="lg:col-span-2 card-admin p-5 sm:p-6 h-fit">
            <h3 class="text-[15px] font-black text-slate-800 mb-5 flex items-center gap-2 border-b border-slate-100 pb-3">
                <span class="material-symbols-outlined text-slate-400 text-[20px]">grid_view</span> Koleksi Galeri
            </h3>

            @if($galleries->isEmpty())
                <div class="p-12 text-center flex flex-col items-center justify-center border-2 border-dashed border-slate-200 rounded-2xl bg-slate-50/50">
                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mb-3 shadow-sm">
                        <span class="material-symbols-outlined text-slate-300 text-[32px]">image_not_supported</span>
                    </div>
                    <p class="text-[14px] font-bold text-slate-600 mb-1">Galeri Kosong</p>
                    <p class="text-[12px] font-medium text-slate-400">Belum ada foto yang diupload. Mulai tambahkan foto dari form di samping.</p>
                </div>
            @else
                <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">
                    @foreach($galleries as $foto)
                        <div class="group relative rounded-2xl overflow-hidden bg-slate-100 border border-slate-200 shadow-sm hover:shadow-md transition-all aspect-square">
                            <!-- Image -->
                            <img src="{{ asset('storage/' . $foto->image_path) }}" alt="{{ $foto->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            
                            <!-- Kategori Badge -->
                            <div class="absolute top-2 left-2 z-20">
                                <span class="bg-black/60 backdrop-blur-md text-white text-[9px] font-black uppercase tracking-wider px-2 py-1 rounded-md border border-white/10 shadow-sm">
                                    {{ $foto->category->name }}
                                </span>
                            </div>

                            <!-- Gradient Bottom for Title -->
                            <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-slate-900/90 via-slate-900/40 to-transparent p-3 pt-8 z-10">
                                <p class="text-white text-[11px] font-bold truncate leading-tight" title="{{ $foto->title }}">{{ $foto->title }}</p>
                            </div>

                            <!-- Delete Overlay (Hover) -->
                            <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-[2px] opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center z-30">
                                <form action="{{ route('admin.galleries.destroy', $foto->id) }}" method="POST" onsubmit="return confirm('Peringatan: Yakin ingin menghapus foto {{ $foto->title }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-10 h-10 rounded-full bg-rose-500 text-white flex items-center justify-center hover:bg-rose-600 hover:scale-110 transition-all shadow-lg" title="Hapus Foto">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection