@extends('layouts.admin.app')

@section('header_title', 'Data Pendaftaran')

@section('content')
<div class="max-w-7xl mx-auto animate-fade-in-up pb-12">
    
    <!-- HEADER -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h2 class="text-[24px] font-black text-slate-900 tracking-tight flex items-center gap-2">
                <span class="material-symbols-outlined text-hanania-purple text-[28px]">folder_shared</span> Master Data Pendaftaran
            </h2>
            <p class="text-[13px] text-slate-500 font-medium mt-1">Pantau seluruh pendaftaran jamaah, progres pembayaran, dan kelengkapan dokumen.</p>
        </div>
    </div>

    <!-- 💡 SMART FILTER BAR -->
    <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200 mb-6">
        <form action="{{ route('admin.enrollments.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
            
            <!-- Pencarian (Nama / No Daftar) -->
            <div class="w-full md:w-1/3">
                <label class="block text-[11px] font-extrabold text-slate-500 uppercase tracking-widest mb-2">Cari Jamaah / Pembayar</label>
                <div class="relative group">
                    <span class="material-symbols-outlined absolute left-3.5 top-2.5 text-[20px] text-slate-400">search</span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama atau no pendaftaran..." class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-[13px] font-bold text-slate-800 focus:bg-white focus:border-hanania-purple focus:ring-2 focus:ring-hanania-purple/20 outline-none transition-all">
                </div>
            </div>

            <!-- Filter Status -->
            <div class="w-full md:w-1/4">
                <label class="block text-[11px] font-extrabold text-slate-500 uppercase tracking-widest mb-2">Status Pendaftaran</label>
                <div class="relative group">
                    <select name="status" class="w-full pl-4 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-[13px] font-bold text-slate-800 focus:bg-white focus:border-hanania-purple focus:ring-2 focus:ring-hanania-purple/20 outline-none transition-all appearance-none cursor-pointer">
                        <option value="">Semua Status</option>
                        <option value="enrolled" {{ request('status') == 'enrolled' ? 'selected' : '' }}>Aktif (Belum Lunas)</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Lunas</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                        <span class="material-symbols-outlined text-[18px]">expand_more</span>
                    </div>
                </div>
            </div>

            <!-- Tombol Filter -->
            <div class="flex gap-2 w-full md:w-auto">
                <button type="submit" class="flex-1 md:flex-none bg-hanania-purple hover:bg-hanania-purple-dark text-white px-6 py-2.5 rounded-xl text-[13px] font-bold transition-all shadow-sm flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">filter_list</span> Filter
                </button>
                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('admin.enrollments.index') }}" class="flex-1 md:flex-none bg-slate-100 hover:bg-slate-200 text-slate-600 px-4 py-2.5 rounded-xl text-[13px] font-bold transition-all flex items-center justify-center" title="Reset Filter">
                        <span class="material-symbols-outlined text-[18px]">close</span>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- TABEL DATA (Enterprise Grade) -->
    <div class="card-admin overflow-hidden bg-white border border-slate-200 rounded-2xl shadow-sm">
        <div class="overflow-x-auto pb-2">
            <table class="w-full min-w-[1000px] text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-200 text-[11px] font-extrabold text-slate-500 uppercase tracking-widest">
                        <th class="p-4 sm:px-6">No. Pendaftaran</th>
                        <th class="p-4 sm:px-6">Jamaah & Akun</th>
                        <th class="p-4 sm:px-6">Paket Travel</th>
                        <th class="p-4 sm:px-6">Progres Keuangan</th>
                        <th class="p-4 sm:px-6 text-center">Dokumen</th>
                        <th class="p-4 sm:px-6 text-center">Status</th>
                        <th class="p-4 sm:px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-slate-600 divide-y divide-slate-100">
                    @forelse($enrollments as $enrollment)
                        <tr class="hover:bg-slate-50/80 transition-colors group">
                            
                            <!-- Nomor & Tanggal -->
                            <td class="p-4 sm:px-6 align-middle">
                                <span class="inline-flex px-2 py-1 rounded-md bg-hanania-purple/10 text-hanania-purple font-mono font-bold text-[12px] border border-hanania-purple/20 mb-1">
                                    {{ $enrollment->enrollment_number }}
                                </span>
                                <p class="text-[11px] font-medium text-slate-400">{{ $enrollment->created_at->format('d M Y') }}</p>
                            </td>

                            <!-- Jamaah & Akun -->
                            <td class="p-4 sm:px-6 align-middle">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center font-extrabold text-[14px] border border-slate-200 shrink-0">
                                        {{ strtoupper(substr($enrollment->passenger_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-[14px] text-slate-800 flex items-center gap-1.5">
                                            {{ $enrollment->passenger_name }}
                                            <span class="inline-flex px-1.5 py-0.5 rounded bg-slate-200 text-slate-600 text-[9px] uppercase tracking-wider font-extrabold border border-slate-300">
                                                {{ $enrollment->relationship }}
                                            </span>
                                        </p>
                                        <div class="flex items-center gap-1 text-[11px] font-medium text-slate-500 mt-1">
                                            <span class="material-symbols-outlined text-[13px]">person_check</span>
                                            Pembayar: <strong class="text-slate-700">{{ $enrollment->customer->name ?? 'Anonim' }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Paket -->
                            <td class="p-4 sm:px-6 align-middle">
                                <p class="text-[13px] font-bold text-slate-800">{{ $enrollment->travelPackage->name }}</p>
                            </td>

                            <!-- Keuangan -->
                            <td class="p-4 sm:px-6 align-middle">
                                @php $progress = $enrollment->progress_data; @endphp
                                <div class="w-full max-w-[150px]">
                                    <div class="flex justify-between items-end mb-1">
                                        <p class="text-[10px] font-bold {{ $progress['sisa_tagihan'] == 0 ? 'text-emerald-600' : 'text-slate-500' }}">
                                            {{ $progress['sisa_tagihan'] == 0 ? 'LUNAS 🎉' : 'Sisa Rp ' . number_format($progress['sisa_tagihan'], 0, ',', '.') }}
                                        </p>
                                        <p class="text-[10px] font-black {{ $progress['persentase'] >= 100 ? 'text-emerald-600' : 'text-hanania-purple' }}">{{ $progress['persentase'] }}%</p>
                                    </div>
                                    <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden border border-slate-200">
                                        <div class="h-1.5 rounded-full transition-all duration-1000 ease-out {{ $progress['persentase'] >= 100 ? 'bg-emerald-500' : 'bg-hanania-purple' }}" style="width: {{ $progress['persentase'] }}%"></div>
                                    </div>
                                </div>
                            </td>

                            <!-- Dokumen -->
                            <td class="p-4 sm:px-6 align-middle text-center">
                                @php $approvedDocs = $enrollment->documents ? $enrollment->documents->where('status', 'approved')->count() : 0; @endphp
                                @if($approvedDocs >= 5)
                                    <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 px-2 py-1 rounded text-[10px] font-black uppercase tracking-widest border border-emerald-200">
                                        Lengkap ({{ $approvedDocs }}/5)
                                    </span>
                                @elseif($approvedDocs > 0)
                                    <span class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 px-2 py-1 rounded text-[10px] font-black uppercase tracking-widest border border-amber-200">
                                        Kurang ({{ $approvedDocs }}/5)
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 bg-rose-50 text-rose-600 px-2 py-1 rounded text-[10px] font-black uppercase tracking-widest border border-rose-200">
                                        Kosong
                                    </span>
                                @endif
                            </td>

                            <!-- Status -->
                            <td class="p-4 sm:px-6 align-middle text-center">
                                <span class="inline-flex px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-widest border bg-slate-50 border-slate-200 text-slate-600 shadow-sm">
                                    {{ $enrollment->statusText() ?? $enrollment->status }}
                                </span>
                            </td>

                            <!-- Aksi -->
                            <td class="p-4 sm:px-6 align-middle text-right">
                                <a href="{{ route('admin.enrollments.show', $enrollment->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 text-blue-600 border border-blue-200 hover:bg-blue-600 hover:text-white hover:border-blue-600 transition-all shadow-sm" title="Lihat Detail">
                                    <span class="material-symbols-outlined text-[18px]">read_more</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-400">
                                    <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mb-3 border border-slate-100">
                                        <span class="material-symbols-outlined text-[32px]">folder_off</span>
                                    </div>
                                    <p class="text-[14px] font-bold text-slate-600 mb-1">Pencarian Tidak Ditemukan</p>
                                    <p class="text-[12px] font-medium text-slate-400">Coba gunakan kata kunci atau filter status yang lain.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Paginasi (Bila ada) -->
        @if(method_exists($enrollments, 'links'))
        <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
            {{ $enrollments->withQueryString()->links() }}
        </div>
        @endif

    </div>
</div>
@endsection