@extends('layouts.admin.app')

@section('header_title', 'Manajemen Paket Travel')

@section('content')
<div class="animate-fade-in-up">
    
    <!-- ========================================== -->
    <!-- HEADER HALAMAN -->
    <!-- ========================================== -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h2 class="text-[24px] font-black text-slate-900 tracking-tight">Katalog Paket</h2>
            <p class="text-[13px] text-slate-500 font-medium mt-1">Kelola daftar paket perjalanan umroh dan haji Anda.</p>
        </div>
        
        <!-- Tombol Tambah (Menggunakan btn-admin-primary) -->
        <a href="{{ route('admin.travel_packages.create') }}" class="btn-admin-primary shadow-sm group">
            <span class="material-symbols-outlined text-[20px] group-hover:rotate-90 transition-transform duration-300">add</span>
            Tambah Paket Baru
        </a>
    </div>

    <!-- ========================================== -->
    <!-- CARD & TABEL DATA -->
    <!-- ========================================== -->
    <div class="card-admin overflow-hidden">
        
        <!-- Toolbar Tabel (Search & Filter) -->
        <div class="p-4 sm:p-5 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row justify-between items-center gap-4">
            <!-- Search Bar -->
            <div class="relative w-full sm:w-80 group">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-hanania-purple transition-colors">
                    <span class="material-symbols-outlined text-[18px]">search</span>
                </div>
                <input type="text" 
                       class="w-full pl-10 pr-4 py-2 bg-white border border-slate-200 rounded-lg text-[13px] text-slate-900 placeholder-slate-400 focus:outline-none focus:border-hanania-purple focus:ring-1 focus:ring-hanania-purple transition-all shadow-sm" 
                       placeholder="Cari kode atau nama paket...">
            </div>
            
            <!-- Tombol Filter -->
            <button type="button" class="w-full sm:w-auto flex items-center justify-center gap-2 bg-white border border-slate-200 text-slate-600 px-4 py-2 rounded-lg text-[13px] font-bold hover:bg-slate-50 hover:text-hanania-purple transition-colors shadow-sm">
                <span class="material-symbols-outlined text-[18px]">filter_list</span> Filter
            </button>
        </div>

        <!-- Wadah Tabel Responsive -->
        <div class="overflow-x-auto no-scrollbar">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-white border-b border-slate-200 text-[11px] font-extrabold text-slate-400 uppercase tracking-widest">
                        <th class="p-4 sm:px-6">Kode</th>
                        <th class="p-4 sm:px-6">Nama Paket</th>
                        <th class="p-4 sm:px-6 text-center">Durasi</th>
                        <th class="p-4 sm:px-6">Harga Estimasi</th>
                        <th class="p-4 sm:px-6 text-center">Status</th>
                        <th class="p-4 sm:px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-slate-600 divide-y divide-slate-100">
                    @forelse($packages as $paket)
                        <tr class="hover:bg-slate-50/80 transition-colors group">
                            <!-- Kolom Kode -->
                            <td class="p-4 sm:px-6">
                                <span class="inline-flex px-2.5 py-1 rounded-md bg-hanania-purple/10 text-hanania-purple font-mono font-bold text-[12px] border border-hanania-purple/20">
                                    {{ $paket->code }}
                                </span>
                            </td>
                            
                            <!-- Kolom Nama -->
                            <td class="p-4 sm:px-6">
                                <p class="font-bold text-[14px] text-slate-800">{{ $paket->name }}</p>
                            </td>
                            
                            <!-- Kolom Durasi -->
                            <td class="p-4 sm:px-6 text-center">
                                <div class="inline-flex items-center gap-1.5 text-slate-600 bg-slate-100 px-3 py-1 rounded-full text-[12px] font-bold border border-slate-200">
                                    <span class="material-symbols-outlined text-[14px]">schedule</span> {{ $paket->duration_days }} Hari
                                </div>
                            </td>
                            
                            <!-- Kolom Harga -->
                            <td class="p-4 sm:px-6">
                                <p class="font-black text-[14px] text-slate-800">Rp {{ number_format($paket->estimated_price, 0, ',', '.') }}</p>
                            </td>
                            
                            <!-- Kolom Status -->
                            <td class="p-4 sm:px-6 text-center">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md text-[10px] font-bold uppercase tracking-widest border {{ $paket->status === 'active' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-slate-100 text-slate-500 border-slate-200' }}">
                                    @if($paket->status === 'active')
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    @else
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                    @endif
                                    {{ $paket->status }}
                                </span>
                            </td>
                            
                            <!-- Kolom Aksi -->
                            <td class="p-4 sm:px-6 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- Tombol Edit (Icon Only) -->
                                    <a href="{{ route('admin.travel_packages.edit', $paket->id) }}" 
                                       class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center border border-amber-200/50 hover:bg-amber-500 hover:text-white hover:border-amber-500 transition-all shadow-sm"
                                       title="Edit Paket">
                                        <span class="material-symbols-outlined text-[16px] [font-variation-settings:'FILL'_1]">edit</span>
                                    </a>
                                    
                                    <!-- Tombol Hapus (Icon Only) -->
                                    <form action="{{ route('admin.travel_packages.destroy', $paket->id) }}" method="POST" class="js-delete-package inline-block" data-package-code="{{ $paket->code }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center border border-rose-200/50 hover:bg-rose-500 hover:text-white hover:border-rose-500 transition-all shadow-sm"
                                                title="Hapus Paket">
                                            <span class="material-symbols-outlined text-[16px] [font-variation-settings:'FILL'_1]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <!-- Empty State Elegan -->
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-400">
                                    <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mb-3 border border-slate-100">
                                        <span class="material-symbols-outlined text-[32px]">inventory_2</span>
                                    </div>
                                    <p class="text-[14px] font-bold text-slate-600 mb-1">Belum Ada Paket Travel</p>
                                    <p class="text-[12px] font-medium text-slate-400 max-w-xs mx-auto">Silakan klik tombol "Tambah Paket Baru" di atas untuk mulai membuat penawaran perjalanan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination Area (Opsional untuk masa depan) -->
        @if($packages instanceof \Illuminate\Pagination\LengthAwarePaginator && $packages->hasPages())
            <div class="p-4 sm:p-5 border-t border-slate-100 bg-slate-50/50">
                {{ $packages->links() }}
            </div>
        @endif
        
    </div>
</div>
<script>
    document.querySelectorAll('.js-delete-package').forEach((form) => {
        form.addEventListener('submit', (event) => {
            const packageCode = form.dataset.packageCode || 'ini';

            if (!window.confirm(`Peringatan: Yakin ingin menghapus paket ${packageCode}?`)) {
                event.preventDefault();
            }
        });
    });
</script>
@endsection
