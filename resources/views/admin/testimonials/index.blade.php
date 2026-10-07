@extends('layouts.admin.app')

@section('header_title', 'Moderasi Testimoni')

@section('content')
<div class="animate-fade-in-up pb-10">

    <!-- ========================================== -->
    <!-- HEADER HALAMAN -->
    <!-- ========================================== -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h2 class="text-[24px] font-black text-slate-900 tracking-tight">Moderasi Testimoni</h2>
            <p class="text-[13px] text-slate-500 font-medium mt-1">Verifikasi ulasan dan pengalaman jamaah sebelum ditampilkan ke publik.</p>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- CARD & TABEL DATA -->
    <!-- ========================================== -->
    <div class="card-admin overflow-hidden">
        
        <!-- Wadah Tabel Responsive -->
        <div class="overflow-x-auto no-scrollbar">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-white border-b border-slate-200 text-[11px] font-extrabold text-slate-400 uppercase tracking-widest">
                        <th class="p-4 sm:px-6">Profil Jamaah</th>
                        <th class="p-4 sm:px-6">Ulasan (Pesan)</th>
                        <th class="p-4 sm:px-6 text-center">Status</th>
                        <th class="p-4 sm:px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-slate-600 divide-y divide-slate-100">
                    @forelse($testimonials as $testi)
                        <tr class="hover:bg-slate-50/80 transition-colors group">
                            
                            <!-- Kolom Jamaah & Rating -->
                            <td class="p-4 sm:px-6 align-top">
                                <div class="flex items-start gap-3">
                                    <!-- Avatar Inisial -->
                                    <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center font-extrabold text-[14px] border border-slate-200 shrink-0 mt-0.5">
                                        {{ strtoupper(substr($testi->user->name ?? 'J', 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-[14px] text-slate-800">{{ $testi->user->name ?? 'Hamba Allah' }}</p>
                                        <p class="text-[11px] font-medium text-slate-400 mb-1.5">{{ $testi->created_at->format('d M Y') }}</p>
                                        
                                        <!-- Bintang Premium (Material Symbols) -->
                                        <div class="flex items-center gap-0.5">
                                            @for($i = 0; $i < 5; $i++)
                                                @if($i < $testi->rating)
                                                    <span class="material-symbols-outlined text-[14px] text-amber-400 [font-variation-settings:'FILL'_1]">star</span>
                                                @else
                                                    <span class="material-symbols-outlined text-[14px] text-slate-200 [font-variation-settings:'FILL'_1]">star</span>
                                                @endif
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </td>
                            
                            <!-- Kolom Ulasan -->
                            <td class="p-4 sm:px-6 align-top whitespace-normal min-w-[250px] max-w-md">
                                <div class="bg-slate-50/50 p-3.5 rounded-xl border border-slate-100 relative">
                                    <span class="material-symbols-outlined text-[24px] text-slate-200 absolute top-2 right-2 [font-variation-settings:'FILL'_1]">format_quote</span>
                                    <p class="text-[13px] text-slate-600 font-medium leading-relaxed italic relative z-10 pr-6">
                                        "{{ $testi->content }}"
                                    </p>
                                </div>
                            </td>
                            
                            <!-- Kolom Status -->
                            <td class="p-4 sm:px-6 align-top text-center">
                                @if($testi->is_approved)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md text-[10px] font-bold uppercase tracking-widest border bg-emerald-50 text-emerald-700 border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Tayang Publik
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md text-[10px] font-bold uppercase tracking-widest border bg-amber-50 text-amber-700 border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                        Menunggu ACC
                                    </span>
                                @endif
                            </td>
                            
                            <!-- Kolom Aksi -->
                            <td class="p-4 sm:px-6 align-top text-right">
                                <div class="flex items-center justify-end gap-2 mt-0.5">
                                    
                                    <!-- Tombol ACC (Muncul jika belum di-approve) -->
                                    @if(!$testi->is_approved)
                                        <form action="{{ route('admin.testimonials.approve', $testi->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" 
                                                    class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-200/50 hover:bg-emerald-500 hover:text-white hover:border-emerald-500 transition-all shadow-sm" 
                                                    title="ACC & Tampilkan di Web">
                                                <span class="material-symbols-outlined text-[16px] [font-variation-settings:'FILL'_1]">check_circle</span>
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Tombol Hapus -->
                                    <form action="{{ route('admin.testimonials.destroy', $testi->id) }}" method="POST" class="js-delete-testimonial" data-author-name="{{ $testi->user->name ?? 'Jamaah' }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center border border-rose-200/50 hover:bg-rose-500 hover:text-white hover:border-rose-500 transition-all shadow-sm" 
                                                title="Tolak & Hapus Ulasan">
                                            <span class="material-symbols-outlined text-[16px] [font-variation-settings:'FILL'_1]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <!-- Empty State -->
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-400">
                                    <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mb-3 border border-slate-100">
                                        <span class="material-symbols-outlined text-[32px]">rate_review</span>
                                    </div>
                                    <p class="text-[14px] font-bold text-slate-600 mb-1">Belum Ada Ulasan</p>
                                    <p class="text-[12px] font-medium text-slate-400 max-w-xs mx-auto">Saat ini belum ada jamaah yang mengirimkan testimoni perjalanan mereka.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
    </div>
</div>
<script>
    document.querySelectorAll('.js-delete-testimonial').forEach((form) => {
        form.addEventListener('submit', (event) => {
            const authorName = form.dataset.authorName || 'Jamaah';

            if (!window.confirm(`Peringatan: Yakin ingin menghapus ulasan dari ${authorName} secara permanen?`)) {
                event.preventDefault();
            }
        });
    });
</script>
@endsection
