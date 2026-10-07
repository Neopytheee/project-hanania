@extends('layouts.customer.app')

@section('title', $article->title)

@section('content')
<div class="w-full flex flex-col gap-8 pt-28 pb-12 animate-fade-in-up max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <!-- Tombol Kembali -->
    <div class="mb-6 sm:mb-8">
        <a href="{{ route('customer.dashboard') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-hanania-purple hover:text-hanania-purple-dark transition-colors duration-300 group">
            <span class="material-symbols-outlined text-[18px] transition-transform duration-300 group-hover:-translate-x-1">arrow_back</span>
            Kembali ke Dashboard
        </a>
    </div>

    <!-- KOTAK UTAMA ARTIKEL (Menggunakan class .card-hanania dari CSS kustom) -->
    <article class="card-hanania p-6 sm:p-10 lg:p-12 relative overflow-hidden">
        
        <!-- Dekorasi Background Halus (Efek blur sudut atas) -->
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-hanania-purple-light rounded-full blur-3xl opacity-60 z-0 pointer-events-none"></div>
        
        <div class="relative z-10">
            <!-- Label / Kategori -->
            <div class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-hanania-purple-light text-hanania-purple-dark text-[10px] sm:text-xs font-bold uppercase tracking-widest rounded-lg mb-5">
                <span class="material-symbols-outlined text-[14px]">auto_awesome</span>
                Tips & Wawasan
            </div>
            
            <!-- Judul Artikel (Menggunakan class .font-heading) -->
            <h1 class="font-heading text-3xl sm:text-4xl lg:text-5xl font-extrabold text-hanania-purple-dark leading-tight break-words mb-4">
                {{ $article->title }}
            </h1>
            
            <!-- Meta Info (Tanggal Publikasi) -->
            <div class="flex items-center gap-2 text-xs sm:text-sm text-gray-500 font-medium mb-8">
                <span class="material-symbols-outlined text-[18px] text-hanania-gold">calendar_month</span>
                Diterbitkan pada {{ $article->created_at->format('d M Y') }}
            </div>

            <!-- Gambar Sampul -->
            @if($article->image)
                <div class="w-full h-64 sm:h-[400px] rounded-2xl overflow-hidden border border-hanania-purple/10 shadow-sm mb-10 group">
                    <img src="{{ asset('storage/' . $article->image) }}" 
                         alt="{{ $article->title }}" 
                         class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                </div>
            @endif

            <!-- Isi Konten Artikel -->
            <!-- KODE BARU -->
            <div class="text-gray-700 text-[14.5px] sm:text-[16px] leading-loose space-y-5 break-all font-sans">
                {!! nl2br(e($article->content)) !!}
            </div>
        </div>
        
        <!-- Footer Artikel & Call to Action -->
        <div class="mt-12 pt-8 border-t border-hanania-purple/10 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-hanania-purple-light flex items-center justify-center text-hanania-purple-dark">
                    <span class="material-symbols-outlined text-[20px]">share</span>
                </div>
                <span class="text-sm font-medium text-gray-500">Bagikan artikel ini</span>
            </div>
            
            <!-- Menggunakan class .btn-hanania-gold dari CSS kustom -->
            <a href="#" class="btn-hanania-gold px-6 py-3 rounded-xl text-sm w-full sm:w-auto">
                Eksplorasi Layanan <span class="material-symbols-outlined text-[18px]">explore</span>
            </a>
        </div>
    </article>

</div>
@endsection