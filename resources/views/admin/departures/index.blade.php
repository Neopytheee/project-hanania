@extends('layouts.admin.app')

@section('header_title', 'Jadwal Keberangkatan')

@section('content')
<div class="max-w-7xl mx-auto animate-fade-in-up pb-12">
    
    <!-- HEADER -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h2 class="text-[24px] font-black text-slate-900 tracking-tight flex items-center gap-2">
                <span class="material-symbols-outlined text-hanania-purple text-[28px]">flight_takeoff</span> Jadwal Keberangkatan
            </h2>
            <p class="text-[13px] text-slate-500 font-medium mt-1">Buat kloter penerbangan dan atur kuota keberangkatan jamaah.</p>
        </div>
    </div>

    <!-- ERROR ALERT -->
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
        
        <!-- ========================================== -->
        <!-- KIRI: FORM JADWAL BARU -->
        <!-- ========================================== -->
        <div class="card-admin p-6 lg:sticky lg:top-24 h-fit">
            <h3 class="text-[15px] font-black text-slate-800 border-b border-slate-100 pb-3 mb-5 flex items-center gap-2">
                <span class="material-symbols-outlined text-hanania-purple text-[20px]">add_task</span> Buat Jadwal Baru
            </h3>
            <form action="{{ route('admin.departures.store') }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 mb-2 uppercase tracking-wide">Pilih Paket Travel <span class="text-rose-500">*</span></label>
                    <div class="relative group">
                        <select name="travel_package_id" class="w-full pl-4 pr-10 py-3 bg-slate-50 border border-slate-200 rounded-xl text-[13px] font-bold text-slate-800 focus:bg-white focus:border-hanania-purple focus:ring-4 focus:ring-hanania-purple/10 outline-none transition-all appearance-none cursor-pointer" required>
                            <option value="" disabled selected>-- Pilih Paket --</option>
                            @foreach($packages as $pkg)
                                <option value="{{ $pkg->id }}">{{ $pkg->name }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400">
                            <span class="material-symbols-outlined text-[18px]">expand_more</span>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-700 mb-2 uppercase tracking-wide">Nama Jadwal / Kloter <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" placeholder="Misal: Kloter 1 Muharram" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-[13px] font-bold text-slate-800 focus:bg-white focus:border-hanania-purple focus:ring-4 focus:ring-hanania-purple/10 outline-none transition-all" required>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-700 mb-2 uppercase tracking-wide">Tgl. Berangkat <span class="text-rose-500">*</span></label>
                    <input type="date" name="departure_date" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-[13px] font-bold text-slate-800 focus:bg-white focus:border-hanania-purple focus:ring-4 focus:ring-hanania-purple/10 outline-none transition-all cursor-pointer" required>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 mb-2 uppercase tracking-wide">Kuota <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <input type="number" name="quota" min="1" placeholder="45" class="w-full pl-4 pr-10 py-3 bg-slate-50 border border-slate-200 rounded-xl text-[13px] font-bold text-slate-800 focus:bg-white focus:border-hanania-purple focus:ring-4 focus:ring-hanania-purple/10 outline-none transition-all" required>
                            <span class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-[11px] font-bold text-slate-400">PAX</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 mb-2 uppercase tracking-wide">Est. Harga <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-[12px] font-bold text-slate-400">Rp</span>
                            <input type="number" name="estimated_price" min="0" placeholder="30000000" class="w-full pl-8 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-[13px] font-bold text-slate-800 focus:bg-white focus:border-hanania-purple focus:ring-4 focus:ring-hanania-purple/10 outline-none transition-all" required>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full btn-admin-primary py-3.5 rounded-xl text-[13px] flex justify-center items-center gap-2 group mt-2 shadow-md">
                    <span class="material-symbols-outlined text-[20px] group-hover:scale-110 transition-transform">event_available</span> Simpan Jadwal
                </button>
            </form>
        </div>

        <!-- ========================================== -->
        <!-- KANAN: DAFTAR JADWAL (BOARDING PASS STYLE) -->
        <!-- ========================================== -->
        <div class="lg:col-span-2 space-y-4">
            @forelse($departures as $dep)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 hover:border-hanania-purple/50 transition-all flex flex-col sm:flex-row relative overflow-hidden group">
                    
                    <!-- Aksen Garis Kiri -->
                    <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-hanania-purple"></div>

                    <!-- Kiri: Info Utama Kloter -->
                    <div class="p-5 sm:p-6 flex-1 flex flex-col justify-center pl-7">
                        <div class="flex items-center gap-2 mb-2.5">
                            <span class="inline-flex items-center justify-center bg-slate-100 text-slate-600 font-black text-[10px] px-2.5 py-1 rounded-md uppercase tracking-widest border border-slate-200">
                                {{ $dep->code }}
                            </span>
                            @if($dep->status === 'scheduled')
                                <span class="inline-flex items-center gap-1 text-[9px] font-black uppercase tracking-widest text-amber-600 bg-amber-50 px-2 py-1 rounded-md border border-amber-200">Menunggu</span>
                            @elseif($dep->status === 'departed')
                                <span class="inline-flex items-center gap-1 text-[9px] font-black uppercase tracking-widest text-blue-600 bg-blue-50 px-2 py-1 rounded-md border border-blue-200">Take Off (Berangkat)</span>
                            @elseif($dep->status === 'completed')
                                <span class="inline-flex items-center gap-1 text-[9px] font-black uppercase tracking-widest text-emerald-600 bg-emerald-50 px-2 py-1 rounded-md border border-emerald-200">Selesai</span>
                            @endif
                        </div>

                        <h4 class="font-black text-[18px] text-slate-800 leading-tight mb-2">{{ $dep->name }}</h4>
                        <p class="text-[12px] font-bold text-slate-500 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px]">luggage</span> {{ $dep->travelPackage->name }}
                        </p>
                    </div>

                    <!-- Tengah: Efek Sobekan Tiket (Dashed Line) -->
                    <div class="hidden sm:flex flex-col justify-between items-center relative w-8">
                        <div class="w-6 h-6 rounded-full bg-slate-50 absolute -top-3 border-b border-slate-200"></div>
                        <div class="h-full border-l-2 border-dashed border-slate-200 my-4"></div>
                        <div class="w-6 h-6 rounded-full bg-slate-50 absolute -bottom-3 border-t border-slate-200"></div>
                    </div>

                    <!-- Kanan: Waktu, Kuota & Aksi -->
                    <div class="p-5 sm:p-6 sm:w-[240px] bg-slate-50/50 flex flex-col justify-between border-t sm:border-t-0 sm:border-l border-slate-100">
                        <div class="mb-5 sm:mb-0">
                            <!-- Info Penerbangan -->
                            <div class="mb-3">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Tgl Penerbangan</p>
                                <p class="font-black text-[14px] text-blue-600 flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-[18px]">calendar_month</span>
                                    {{ \Carbon\Carbon::parse($dep->departure_date)->format('d M Y') }}
                                </p>
                            </div>
                            
                            <!-- Info Kuota -->
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Kuota Tersedia</p>
                                <p class="font-black text-[14px] text-slate-700 flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-[18px] text-amber-500">airline_seat_recline_normal</span>
                                    {{ $dep->quota }} <span class="text-[11px] text-slate-500 font-bold uppercase">Kursi</span>
                                </p>
                            </div>
                        </div>

                        <!-- BAGIAN TOMBOL AKSI -->
                        <div class="flex items-center gap-2 mt-4 sm:mt-0">
                            <!-- Tombol Kelola -->
                            <a href="{{ route('admin.departures.show', $dep->id) }}" class="flex-1 inline-flex items-center justify-center gap-2 bg-white border border-slate-200 hover:border-hanania-purple hover:bg-hanania-purple text-slate-600 hover:text-white text-[12px] font-bold px-4 py-2.5 rounded-xl transition-all shadow-sm group/btn">
                                Kelola Kloter <span class="material-symbols-outlined text-[16px] group-hover/btn:translate-x-1 transition-transform">arrow_forward</span>
                            </a>
                            
                            <!-- Tombol Hapus (Hanya muncul jika jadwal masih draft/kosong atau belum berangkat) -->
                            @if(!in_array($dep->status, ['departed', 'completed']))
                            <form action="{{ route('admin.departures.destroy', $dep->id) }}" method="POST" onsubmit="return confirm('YAKIN INGIN MENGHAPUS JADWAL INI?\n\nSemua rombongan di dalamnya akan dikeluarkan secara otomatis.');" class="flex-none">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center justify-center bg-white border border-rose-200 hover:bg-rose-50 text-rose-500 hover:text-rose-600 px-3 py-2.5 rounded-xl transition-all shadow-sm" title="Hapus Jadwal">
                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                    
                </div>
            @empty
                <!-- Empty State Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-12 text-center flex flex-col items-center justify-center">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4 border border-slate-100">
                        <span class="material-symbols-outlined text-slate-300 text-[40px]">event_busy</span>
                    </div>
                    <h4 class="font-black text-[16px] text-slate-700 mb-1">Belum Ada Jadwal Keberangkatan</h4>
                    <p class="text-[13px] text-slate-500 font-medium max-w-sm">Buat jadwal penerbangan (kloter) pertama Anda melalui form di sebelah kiri.</p>
                </div>
            @endforelse
        </div>

    </div>
</div>
@endsection