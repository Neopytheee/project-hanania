@extends('layouts.admin.app')

@section('header_title', 'Manifes Rombongan')

@section('content')
<div class="max-w-7xl mx-auto animate-fade-in-up pb-12">
    
    <!-- HEADER -->
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.groups.index') }}" class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-slate-500 hover:text-hanania-purple transition-all border border-slate-200 shadow-sm shrink-0">
            <span class="material-symbols-outlined text-[20px]">arrow_back</span>
        </a>
        <div>
            <h2 class="text-[24px] font-black text-slate-900 tracking-tight flex items-center gap-2">Atur Jamaah Rombongan</h2>
            <p class="text-[13px] text-slate-500 font-medium mt-0.5">Kelola manifes penumpang di dalam grup ini.</p>
        </div>
    </div>

    <!-- ERROR ALERT -->
    @if($errors->any())
        <div class="bg-rose-50 border border-rose-200 p-5 rounded-2xl mb-6 shadow-sm flex items-start gap-3">
            <span class="material-symbols-outlined text-rose-500 mt-0.5">error</span>
            <div>
                <h4 class="text-[13px] font-bold text-rose-900 mb-1">Peringatan</h4>
                <ul class="list-disc pl-4 text-[12px] font-medium text-rose-700 space-y-0.5">
                    @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- ========================================== -->
        <!-- KIRI: WIDGET INFO & FORM TAMBAH -->
        <!-- ========================================== -->
        <div class="space-y-6 h-fit lg:sticky lg:top-24">
            
            <!-- WIDGET INFO GRUP (Premium Gradient) -->
            <div class="rounded-2xl shadow-lg p-6 relative overflow-hidden bg-gradient-to-br from-slate-900 via-hanania-purple to-purple-900 text-white border border-purple-800">
                <!-- Ornamen Latar -->
                <div class="absolute -right-4 -top-4 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
                <span class="material-symbols-outlined absolute -bottom-4 -right-2 text-[100px] text-white/5 rotate-12 pointer-events-none [font-variation-settings:'FILL'_1]">airport_shuttle</span>
                
                <div class="relative z-10">
                    <span class="inline-flex text-[10px] font-black uppercase tracking-widest bg-white/20 text-white px-2.5 py-1 rounded-md mb-3 backdrop-blur-sm border border-white/20">
                        {{ $group->code }}
                    </span>
                    <h3 class="font-black text-white text-[20px] leading-tight mb-5">{{ $group->name }}</h3>
                    
                    <div class="pt-4 border-t border-white/20 flex justify-between items-end">
                        <div>
                            <p class="text-[10px] uppercase font-bold text-purple-200 tracking-wider mb-0.5">Kapasitas Kursi</p>
                            <p class="font-black text-[20px]">{{ $group->activeMemberships->count() }} <span class="text-[14px] text-purple-300 font-bold">/ {{ $group->capacity }}</span></p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] uppercase font-bold text-purple-200 tracking-wider mb-1">Status Jadwal</p>
                            @if($group->departure_id)
                                <span class="inline-flex items-center justify-end gap-1 px-2 py-0.5 bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 rounded text-[11px] font-bold">
                                    <span class="material-symbols-outlined text-[14px]">event_available</span> Terjadwal
                                </span>
                            @else
                                <span class="inline-flex items-center justify-end gap-1 px-2 py-0.5 bg-amber-500/20 text-amber-300 border border-amber-400/30 rounded text-[11px] font-bold">
                                    <span class="material-symbols-outlined text-[14px]">pending_actions</span> Belum Ada
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- FORM TAMBAH JAMAAH -->
            <div class="card-admin p-6">
                <h3 class="font-black text-[15px] mb-4 text-slate-800 flex items-center gap-2 border-b border-slate-100 pb-3">
                    <span class="material-symbols-outlined text-hanania-purple text-[20px]">person_add</span> Masukkan Jamaah
                </h3>
                
                <form action="{{ route('admin.groups.assign', $group->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 mb-2 uppercase tracking-wide">Daftar Antrean (Lunas)</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 group-focus-within:text-hanania-purple transition-colors">
                                <span class="material-symbols-outlined text-[18px]">search</span>
                            </div>
                            <select name="enrollment_id" class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-[13px] font-bold text-slate-800 focus:bg-white focus:border-hanania-purple focus:ring-4 focus:ring-hanania-purple/10 outline-none transition-all appearance-none cursor-pointer" required>
                                <option value="" disabled selected>-- Pilih Jamaah Siap Berangkat --</option>
                                @forelse($eligibleEnrollments as $enr)
                                    <option value="{{ $enr->id }}">
                                        {{ $enr->passenger_name }} | {{ $enr->travelPackage->name }}
                                    </option>
                                @empty
                                    <option value="" disabled>-- Tidak ada jamaah di antrean --</option>
                                @endforelse
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                                <span class="material-symbols-outlined text-[18px]">expand_more</span>
                            </div>
                        </div>
                        <p class="text-[10px] text-slate-400 mt-2 font-medium leading-tight">Hanya menampilkan jamaah yang tabungannya lunas dan belum memiliki grup.</p>
                    </div>
                    
                    <button type="submit" class="w-full bg-amber-500 hover:bg-amber-600 text-white font-bold py-3 rounded-xl text-[13px] shadow-[0_4px_10px_rgba(245,158,11,0.3)] transition-all flex items-center justify-center gap-1.5 group">
                        <span class="material-symbols-outlined text-[18px] group-hover:-translate-y-0.5 transition-transform">how_to_reg</span>
                        Assign ke Rombongan
                    </button>
                </form>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- KANAN: TABEL MANIFES -->
        <!-- ========================================== -->
        <div class="lg:col-span-2 card-admin overflow-hidden flex flex-col h-full">
            <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h3 class="font-bold text-[15px] text-slate-800 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px] text-slate-400">format_list_numbered</span> Daftar Manifes Penumpang
                </h3>
            </div>
            
            <div class="overflow-x-auto no-scrollbar flex-1">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-white border-b border-slate-200 text-[11px] font-extrabold text-slate-400 uppercase tracking-widest">
                            <th class="p-4 sm:px-6">Data Penumpang</th>
                            <th class="p-4 sm:px-6">No. Pendaftaran</th>
                            <th class="p-4 sm:px-6">Tgl Masuk</th>
                            <th class="p-4 sm:px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-slate-600 divide-y divide-slate-100">
                        @forelse($group->activeMemberships as $member)
                            <tr class="hover:bg-slate-50/80 transition-colors group">
                                <!-- Data Jamaah -->
                                <td class="p-4 sm:px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center font-extrabold text-[12px] border border-slate-200 shrink-0">
                                            {{ strtoupper(substr($member->enrollment->passenger_name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-[13px] text-slate-800">{{ $member->enrollment->passenger_name }}</p>
                                            <div class="flex items-center gap-1 text-[11px] font-medium text-slate-500 mt-0.5">
                                                <span class="material-symbols-outlined text-[12px]">account_circle</span> Akun: {{ $member->enrollment->customer->name ?? '-' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                
                                <!-- No. Pendaftaran -->
                                <td class="p-4 sm:px-6">
                                    <span class="inline-flex px-2 py-1 rounded bg-slate-100 text-slate-600 font-mono font-bold text-[11px] border border-slate-200">
                                        {{ $member->enrollment->enrollment_number }}
                                    </span>
                                </td>
                                
                                <!-- Tgl Masuk -->
                                <td class="p-4 sm:px-6 text-[12px] font-bold text-slate-600">
                                    {{ \Carbon\Carbon::parse($member->joined_at)->format('d M Y') }}
                                </td>
                                
                                <!-- Aksi -->
                                <td class="p-4 sm:px-6 text-right">
                                    <form action="{{ route('admin.groups.remove', $member->id) }}" method="POST" onsubmit="return confirm('Peringatan: Yakin ingin mengeluarkan {{ $member->enrollment->passenger_name }} dari rombongan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-white border border-rose-200 text-rose-500 hover:bg-rose-500 hover:text-white hover:border-rose-500 transition-all shadow-sm" title="Keluarkan dari rombongan">
                                            <span class="material-symbols-outlined text-[16px]">person_remove</span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <!-- Empty State Table -->
                            <tr>
                                <td colspan="4" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center text-slate-400">
                                        <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mb-3 border border-slate-100">
                                            <span class="material-symbols-outlined text-[32px]">event_seat</span>
                                        </div>
                                        <p class="text-[14px] font-bold text-slate-600 mb-1">Kursi Masih Kosong</p>
                                        <p class="text-[12px] font-medium text-slate-400">Silakan pilih jamaah dari antrean di sebelah kiri.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
    </div>
</div>
@endsection