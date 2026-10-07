@extends('layouts.admin.app')

@section('header_title', 'Kelola Artikel & Tips')

@section('content')
<div class="max-w-6xl mx-auto animate-fade-in-up pb-10">
    
    <!-- HEADER HALAMAN -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h2 class="text-[24px] font-black text-slate-900 tracking-tight">Kelola Artikel & Tips Perjalanan</h2>
            <p class="text-[13px] text-slate-500 font-medium mt-1">Terbitkan panduan, wawasan, dan informasi penting yang akan tampil di dashboard jamaah.</p>
        </div>
    </div>

    <!-- NOTIFIKASI KBERHASILAN -->
    @if(session('success'))
        <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-[13px] font-bold flex items-center gap-3 shadow-sm">
            <span class="material-symbols-outlined text-[20px] text-emerald-600">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- FORM UPLOAD / TAMBAH ARTIKEL -->
        <div class="lg:col-span-5">
            <div class="card-admin p-6 sm:p-7 sticky top-6">
                <h3 class="text-[15px] font-black text-slate-800 border-b border-slate-100 pb-3 mb-5 flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-hanania-purple/10 text-hanania-purple flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[18px]">add_box</span>
                    </div>
                    Tambah Artikel Baru
                </h3>

                <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <!-- Judul Artikel -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Judul Artikel <span class="text-rose-500">*</span></label>
                        <input type="text" name="title" value="{{ old('title') }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-[13px] font-bold text-slate-800 focus:bg-white focus:border-hanania-purple focus:ring-4 focus:ring-hanania-purple/10 outline-none transition-all" placeholder="Contoh: Tips Menjaga Stamina Saat di Tanah Suci" required>
                        @error('title') <span class="text-rose-500 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Ringkasan Singkat -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Ringkasan (Excerpt)</label>
                        <textarea name="excerpt" rows="2" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-[12px] font-medium text-slate-800 focus:bg-white focus:border-hanania-purple focus:ring-4 focus:ring-hanania-purple/10 outline-none transition-all" placeholder="Ringkasan singkat yang tampil di card dashboard...">{{ old('excerpt') }}</textarea>
                        @error('excerpt') <span class="text-rose-500 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Isi Lengkap Artikel -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Isi Konten <span class="text-rose-500">*</span></label>
                        <textarea name="content" rows="6" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-[12px] font-medium text-slate-800 focus:bg-white focus:border-hanania-purple focus:ring-4 focus:ring-hanania-purple/10 outline-none transition-all" placeholder="Tulis isi artikel lengkap di sini..." required>{{ old('content') }}</textarea>
                        @error('content') <span class="text-rose-500 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Gambar Sampul -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Gambar Sampul (Banner)</label>
                        <input type="file" name="image" accept="image/png, image/jpeg, image/jpg" class="w-full text-[12px] text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-[11px] file:font-bold file:bg-hanania-purple/10 file:text-hanania-purple hover:file:bg-hanania-purple hover:file:text-white cursor-pointer transition-all bg-slate-50 border border-slate-200 rounded-xl">
                        <p class="text-[10px] text-slate-400 mt-1 font-medium">Format: JPG, PNG. Maksimal 2MB.</p>
                        @error('image') <span class="text-rose-500 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Tombol Simpan -->
                    <button type="submit" class="w-full btn-admin-primary py-3 rounded-xl text-[13px] shadow-sm flex items-center justify-center gap-2 mt-2">
                        <span class="material-symbols-outlined text-[18px]">publish</span>
                        Terbitkan Artikel
                    </button>
                </form>
            </div>
        </div>

        <!-- DAFTAR ARTIKEL YANG SUDAH DIBUAT -->
        <div class="lg:col-span-7">
            <div class="card-admin p-6 sm:p-7">
                <h3 class="text-[15px] font-black text-slate-800 border-b border-slate-100 pb-3 mb-5 flex items-center justify-between">
                    <span class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-[18px]">list_alt</span>
                        </div>
                        Daftar Artikel Terbit
                    </span>
                    <span class="text-[11px] bg-slate-100 text-slate-600 px-2.5 py-1 rounded-full font-bold">
                        {{ $articles->total() }} Artikel
                    </span>
                </h3>

                <div class="space-y-4">
                    @forelse($articles as $article)
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-4 rounded-2xl border border-slate-100 bg-slate-50/50 hover:bg-white hover:border-slate-200 transition-all shadow-sm">
                            <div class="flex items-center gap-4">
                                <!-- Thumbnail -->
                                <div class="w-16 h-16 rounded-xl overflow-hidden bg-slate-200 shrink-0 border border-slate-200">
                                    @if($article->image)
                                        <img src="{{ asset('storage/' . $article->image) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-400">
                                            <span class="material-symbols-outlined text-2xl">image</span>
                                        </div>
                                    @endif
                                </div>
                                <!-- Title & Date -->
                                <div>
                                    <h4 class="font-heading text-[14px] font-black text-slate-900 leading-snug line-clamp-1">{{ $article->title }}</h4>
                                    <p class="text-[11px] text-slate-500 mt-0.5 line-clamp-1">{{ $article->excerpt ?? 'Tidak ada ringkasan.' }}</p>
                                    <span class="inline-block mt-1 text-[10px] text-slate-400 font-medium">
                                        Diterbitkan: {{ $article->created_at->format('d M Y') }}
                                    </span>
                                </div>
                            </div>

                            <!-- Tombol Hapus -->
                            <form action="{{ route('admin.articles.destroy', $article->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus artikel ini?')" class="shrink-0 self-end sm:self-center">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-9 h-9 rounded-xl bg-rose-50 text-rose-500 flex items-center justify-center hover:bg-rose-500 hover:text-white transition-all shadow-sm" title="Hapus Artikel">
                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                </button>
                            </form>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <div class="w-14 h-14 mx-auto rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mb-3">
                                <span class="material-symbols-outlined text-3xl">article</span>
                            </div>
                            <h4 class="font-heading text-sm font-bold text-slate-700">Belum Ada Artikel</h4>
                            <p class="text-xs text-slate-400 mt-1">Gunakan form di samping untuk menerbitkan artikel pertama Anda.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($articles->hasPages())
                    <div class="mt-6 pt-4 border-t border-slate-100">
                        {{ $articles->links() }}
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection