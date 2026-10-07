@extends('layouts.admin.app')

@section('header_title', 'Manajemen Rombongan')

@section('content')
<div class="max-w-7xl mx-auto animate-fade-in-up pb-12">
    
    <!-- HEADER -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h2 class="text-[24px] font-black text-slate-900 tracking-tight flex items-center gap-2">
                <span class="material-symbols-outlined text-hanania-purple text-[28px]">groups</span> Manajemen Rombongan
            </h2>
            <p class="text-[13px] text-slate-500 font-medium mt-1">Kelola grup, alokasi bus, dan pantau kapasitas jamaah yang siap berangkat.</p>
        </div>
    </div>

    <!-- ERROR ALERT (Jika validasi gagal) -->
    @if($errors->any())
        <div class="bg-rose-50 border border-rose-200 p-5 rounded-2xl mb-6 shadow-sm flex items-start gap-3">
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
        
        <!-- ========================================== -->
        <!-- KIRI: FORM BUAT ROMBONGAN -->
        <!-- ========================================== -->
        <div class="card-admin p-6 lg:sticky lg:top-24 h-fit">
            <h3 class="text-[15px] font-black text-slate-800 border-b border-slate-100 pb-3 mb-5 flex items-center gap-2">
                <span class="material-symbols-outlined text-[20px] text-hanania-purple">add_box</span> Buat Grup Baru
            </h3>
            
            <form action="{{ route('admin.groups.store') }}" method="POST" class="space-y-5">
                @csrf
                <!-- Kode Rombongan -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 mb-2 uppercase tracking-wide">Kode Rombongan <span class="text-rose-500">*</span></label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-hanania-purple transition-colors">
                            <span class="material-symbols-outlined text-[18px]">qr_code_2</span>
                        </div>
                        <input type="text" name="code" value="{{ old('code') }}" placeholder="Misal: BUS-001" class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-[13px] font-bold text-slate-800 focus:bg-white focus:border-hanania-purple focus:ring-4 focus:ring-hanania-purple/10 outline-none transition-all" required>
                    </div>
                </div>

                <!-- Nama Rombongan -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 mb-2 uppercase tracking-wide">Nama Grup = Nama Paket <span class="text-rose-500">*</span></label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-hanania-purple transition-colors">
                            <span class="material-symbols-outlined text-[18px]">diversity_3</span>
                        </div>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Misal: Grup Umroh 2027" class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-[13px] font-bold text-slate-800 focus:bg-white focus:border-hanania-purple focus:ring-4 focus:ring-hanania-purple/10 outline-none transition-all" required>
                    </div>
                </div>

                <!-- Kapasitas -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 mb-2 uppercase tracking-wide">Kapasitas Maksimal <span class="text-rose-500">*</span></label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-hanania-purple transition-colors">
                            <span class="material-symbols-outlined text-[18px]">airline_seat_recline_normal</span>
                        </div>
                        <input type="number" name="capacity" min="1" value="{{ old('capacity', 0) }}" class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-[13px] font-bold text-slate-800 focus:bg-white focus:border-hanania-purple focus:ring-4 focus:ring-hanania-purple/10 outline-none transition-all" required>
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400 font-bold text-[12px]">Kursi</div>
                    </div>
                </div>

                <button type="submit" class="w-full btn-admin-primary py-3.5 rounded-xl text-[13px] flex justify-center items-center gap-2 group mt-2">
                    <span class="material-symbols-outlined text-[20px] group-hover:scale-110 transition-transform">add_circle</span> Simpan Rombongan
                </button>
            </form>
        </div>

        <!-- ========================================== -->
        <!-- KANAN: DAFTAR ROMBONGAN -->
        <!-- ========================================== -->
        <div class="lg:col-span-2 space-y-4">
            @forelse($groups as $group)
                @php
                    $filled = $group->activeMemberships->count();
                    $capacity = $group->capacity > 0 ? $group->capacity : 1;
                    $percentage = min(100, round(($filled / $capacity) * 100));
                    $isFull = $filled >= $capacity;
                @endphp
                <div class="card-admin p-5 sm:p-6 hover:border-hanania-purple/30 transition-all flex flex-col sm:flex-row justify-between gap-5 group">
                    <div class="flex-1 w-full">
                        
                        <!-- Badges Status -->
                        <div class="flex flex-wrap items-center gap-2 mb-2">
                            <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-widest bg-slate-100 text-slate-600 border border-slate-200">
                                {{ $group->code }}
                            </span>
                            @if($group->departure_id)
                                <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded text-[10px] font-bold border border-emerald-200">
                                    <span class="material-symbols-outlined text-[12px]">flight_takeoff</span> Terjadwal
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 px-2 py-0.5 rounded text-[10px] font-bold border border-amber-200 animate-pulse">
                                    <span class="material-symbols-outlined text-[12px]">schedule</span> Belum Ada Jadwal
                                </span>
                            @endif
                        </div>
                        
                        <h4 class="font-black text-[16px] text-slate-800 leading-tight mb-3">{{ $group->name }}</h4>
                        
                        <!-- Capacity Progress Bar -->
                        <div class="w-full max-w-sm">
                            <div class="flex justify-between items-end mb-1">
                                <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wide">Keterisian Bus</p>
                                <p class="text-[12px] font-black {{ $isFull ? 'text-emerald-600' : 'text-hanania-purple' }}">
                                    {{ $filled }} <span class="text-slate-400 font-medium">/ {{ $group->capacity }}</span>
                                </p>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden border border-slate-200">
                                <div class="h-2 rounded-full transition-all duration-1000 ease-out {{ $isFull ? 'bg-emerald-500' : 'bg-hanania-purple' }}" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>

                        <!-- Jadwal Info -->
                        @if($group->departure_id)
                            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center gap-1.5 text-[11px] font-medium text-slate-500">
                                <span class="material-symbols-outlined text-[14px]">event</span>
                                Jadwal: <strong class="text-slate-700">{{ $group->departure->name }} ({{ \Carbon\Carbon::parse($group->departure->departure_date)->format('d M Y') }})</strong>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Tombol Aksi -->
                    <div class="shrink-0 flex items-center sm:items-start justify-end sm:justify-start">
                        <a href="{{ route('admin.groups.show', $group->id) }}" class="inline-flex items-center gap-1.5 bg-hanania-purple/10 text-hanania-purple hover:bg-hanania-purple hover:text-white px-5 py-2.5 rounded-xl text-[13px] font-bold transition-colors border border-hanania-purple/20 hover:border-hanania-purple shadow-sm">
                            <span class="material-symbols-outlined text-[18px]">group_add</span> Atur Jamaah
                        </a>
                    </div>
                </div>
            @empty
                <div class="card-admin p-12 text-center flex flex-col items-center justify-center">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4 border border-slate-100">
                        <span class="material-symbols-outlined text-slate-300 text-[40px]">directions_bus</span>
                    </div>
                    <h4 class="font-black text-[15px] text-slate-700 mb-1">Belum Ada Rombongan</h4>
                    <p class="text-[13px] text-slate-500 font-medium max-w-sm">Buat grup/rombongan baru menggunakan form di sebelah kiri untuk mulai mengatur tempat duduk jamaah.</p>
                </div>
            @endforelse

            <!-- Pagination -->
            <div class="mt-6">
                {{ $groups->links() ?? '' }}
            </div>
        </div>

    </div>
</div>
@endsection