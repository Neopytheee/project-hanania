@extends('layouts.admin.app')

@section('header_title', 'Detail & Pemberangkatan')

@section('content')
<div class="max-w-7xl mx-auto animate-fade-in-up pb-12">
    
    <!-- 💡 PERBAIKAN: Dibungkus dengan flex agar bersebelahan sejajar -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-5">
        <a href="{{ route('admin.departures.index') }}" class="text-[12px] font-bold text-slate-500 hover:text-hanania-purple inline-flex items-center gap-1 transition-colors">
            <span class="material-symbols-outlined text-[16px]">arrow_back</span> Kembali ke Daftar Jadwal
        </a>

        <!-- Hapus Jadwal (Hanya muncul jika belum terbang) -->
        @if(!in_array($departure->status, ['departed', 'completed']))
        <form action="{{ route('admin.departures.destroy', $departure->id) }}" method="POST" onsubmit="return confirm('YAKIN INGIN MENGHAPUS JADWAL INI?\n\nSemua rombongan di dalamnya akan dikeluarkan secara otomatis dan kembali menganggur.');">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-[11px] font-bold text-rose-600 hover:text-rose-700 bg-rose-50 border border-rose-200 hover:bg-rose-100 px-4 py-2 rounded-lg transition-colors flex items-center gap-1.5 shadow-sm">
                <span class="material-symbols-outlined text-[14px]">delete</span> Hapus Jadwal
            </button>
        </form>
        @endif
    </div>

    <!-- ========================================== -->
    <!-- HEADER CARD (BOARDING PASS STYLE) -->
    <!-- ========================================== -->
    <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-hanania-dark text-white p-6 sm:p-8 rounded-3xl shadow-xl mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 relative overflow-hidden border border-slate-700">
        <!-- Ornamen Visual -->
        <div class="absolute -right-10 -top-20 w-64 h-64 bg-hanania-purple/20 rounded-full blur-3xl pointer-events-none"></div>
        <span class="material-symbols-outlined absolute right-1/4 top-4 text-[120px] text-white/5 -rotate-12 pointer-events-none [font-variation-settings:'FILL'_1]">airplane_ticket</span>
        
        <!-- Info Kiri -->
        <div class="z-10">
            <span class="inline-block text-[10px] uppercase bg-amber-500 text-amber-950 px-3 py-1 rounded-md font-black tracking-widest shadow-sm mb-3">
                {{ $departure->code }}
            </span>
            <h2 class="text-[26px] font-black leading-tight mb-2">{{ $departure->name }}</h2>
            <div class="flex flex-wrap items-center gap-3 text-[12px] font-medium text-slate-300">
                <span class="flex items-center gap-1 bg-white/10 px-2.5 py-1 rounded-lg backdrop-blur-sm border border-white/10"><span class="material-symbols-outlined text-[14px]">luggage</span> {{ $departure->travelPackage->name }}</span>
                <span class="flex items-center gap-1 bg-white/10 px-2.5 py-1 rounded-lg backdrop-blur-sm border border-white/10"><span class="material-symbols-outlined text-[14px]">event</span> {{ \Carbon\Carbon::parse($departure->departure_date)->format('d M Y') }}</span>
            </div>
        </div>

        <!-- Tombol Eksekusi Besar -->
        <div class="z-10 w-full md:w-auto">
            @php
                $totalCapacity = $departure->groups->sum('capacity');
                $totalJamaah = $departure->groups->sum(function($g) { return $g->activeMemberships->count(); });
            @endphp

            @if($departure->canBeDeparted())
                @if($totalJamaah == 0)
                    <button type="button" onclick="alert('⛔ Jadwal masih kosong. Tarik rombongan terlebih dahulu!')" class="w-full md:w-auto bg-slate-700/50 text-slate-400 font-bold px-6 py-3.5 rounded-xl shadow-inner flex items-center justify-center gap-2 cursor-not-allowed border border-slate-600/50 backdrop-blur-sm">
                        <span class="material-symbols-outlined">flight_takeoff</span> Tunggu Jamaah
                    </button>
                @else
                    @php
                        $confirmMsg = $totalJamaah < $totalCapacity 
                            ? "⚠️ PERINGATAN: Kuota BELUM PENUH (".$totalJamaah."/".$totalCapacity." kursi).\n\nYakin ingin tetap memberangkatkan jadwal ini?" 
                            : "Bismillah. Kuota penuh (".$totalJamaah." Jamaah).\n\nYakin ingin memberangkatkan jadwal ini sekarang?";
                    @endphp
                    <form action="{{ route('admin.departures.depart', $departure->id) }}" method="POST" class="js-confirm-departure w-full" data-confirm-message="{{ $confirmMsg }}">
                        @csrf
                        <button type="submit" class="w-full md:w-auto bg-emerald-500 hover:bg-emerald-400 text-slate-900 font-black px-8 py-3.5 rounded-xl shadow-[0_0_20px_rgba(16,185,129,0.4)] flex items-center justify-center gap-2 transition-all transform hover:scale-105 border-2 border-emerald-300 animate-pulse">
                            <span class="material-symbols-outlined [font-variation-settings:'FILL'_1]">flight_takeoff</span> TAKE OFF SEKARANG!
                        </button>
                    </form>
                @endif
            @elseif($departure->status === 'departed')
                <form action="{{ route('admin.departures.complete', $departure->id) }}" method="POST" onsubmit="return confirm('Alhamdulillah. Konfirmasi bahwa seluruh jamaah telah kembali dengan selamat?');" class="w-full">
                    @csrf
                    <button type="submit" class="w-full md:w-auto bg-blue-500 hover:bg-blue-400 text-white font-black px-8 py-3.5 rounded-xl shadow-[0_0_20px_rgba(59,130,246,0.5)] flex items-center justify-center gap-2 transition-all transform hover:scale-105 border-2 border-blue-300">
                        <span class="material-symbols-outlined [font-variation-settings:'FILL'_1]">flight_land</span> KLOTER KEMBALI (SELESAI)
                    </button>
                </form>
            @elseif($departure->status === 'completed')
                <div class="bg-slate-800/80 text-emerald-400 font-black px-6 py-3.5 rounded-xl border border-emerald-500/30 flex items-center justify-center gap-2 shadow-inner backdrop-blur-sm">
                    <span class="material-symbols-outlined [font-variation-settings:'FILL'_1]">verified</span> ALHAMDULILLAH, SELESAI
                </div>
            @endif
        </div>
    </div>

    <!-- ERROR ALERT -->
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-2xl mb-6 font-bold flex items-center gap-2 shadow-sm"><span class="material-symbols-outlined">check_circle</span> {{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="bg-rose-50 border border-rose-200 p-4 rounded-2xl mb-6 shadow-sm flex items-start gap-3">
            <span class="material-symbols-outlined text-rose-500 mt-0.5">error</span>
            <div>
                <h4 class="text-[13px] font-bold text-rose-900 mb-1">Terjadi Kesalahan!</h4>
                <ul class="list-disc pl-4 text-[12px] font-medium text-rose-700 space-y-0.5">
                    @foreach($errors->all() as $error) 
                        <li>{{ $error }}</li> 
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- KOLOM KIRI: SETTING -->
        <div class="space-y-6 h-fit">
            
            <!-- TARIK ROMBONGAN -->
            <div class="card-admin p-5 sm:p-6 border-t-4 border-t-hanania-purple">
                <h3 class="font-black text-[14px] mb-4 text-slate-800 border-b border-slate-100 pb-3 flex items-center gap-2">
                    <span class="material-symbols-outlined text-hanania-purple">bus_alert</span> Masukkan Rombongan
                </h3>
                <form action="{{ route('admin.departures.assign-group', $departure->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Pilih Grup / Bus Nganggur</label>
                        <div class="relative group">
                            <select name="group_id" class="w-full pl-4 pr-10 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:border-hanania-purple focus:ring-4 focus:ring-hanania-purple/10 transition-all font-bold text-slate-800 text-[13px] appearance-none cursor-pointer" required>
                                <option value="" disabled selected>-- Pilih Rombongan --</option>
                                @forelse($availableGroups as $g)
                                    <option value="{{ $g->id }}">{{ $g->code }} - {{ $g->name }} ({{ $g->activeMemberships->count() }} Org)</option>
                                @empty
                                    <option value="" disabled>-- Semua grup sudah terjadwal --</option>
                                @endforelse
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400">
                                <span class="material-symbols-outlined text-[18px]">expand_more</span>
                            </div>
                        </div>
                        <p class="text-[10px] text-slate-400 mt-2 font-medium leading-tight">Harga final jamaah di grup yang ditarik akan terkunci otomatis.</p>
                    </div>
                    <button type="submit" class="w-full btn-admin-primary py-3 rounded-xl shadow-sm flex items-center justify-center gap-2 text-[13px]">
                        Tambahkan ke Jadwal
                    </button>
                </form>
            </div>

            <!-- INFO MANASIK -->
            <div class="card-admin p-5 sm:p-6 border-t-4 border-t-amber-400">
                <h3 class="font-black text-[14px] mb-4 text-slate-800 border-b border-slate-100 pb-3 flex items-center gap-2">
                    <span class="material-symbols-outlined text-amber-500">mosque</span> Info Manasik
                </h3>
                <form action="{{ route('admin.departures.update-persiapan', $departure->id) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase mb-2">Waktu Pelaksanaan</label>
                        <input type="datetime-local" name="manasik_date" value="{{ $departure->manasik_date ? \Carbon\Carbon::parse($departure->manasik_date)->format('Y-m-d\TH:i') : '' }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-[13px] font-bold outline-none focus:border-amber-400 focus:ring-4 focus:ring-amber-400/10 cursor-pointer">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase mb-2">Lokasi / Gedung</label>
                        <input type="text" name="manasik_location" value="{{ $departure->manasik_location }}" placeholder="Contoh: Hotel Aston" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-[13px] font-bold outline-none focus:border-amber-400 focus:ring-4 focus:ring-amber-400/10">
                    </div>
                    <button type="submit" class="w-full bg-amber-400 hover:bg-amber-500 text-amber-950 font-black py-3 rounded-xl transition-colors shadow-sm text-[13px]">
                        Simpan Info Manasik
                    </button>
                </form>
            </div>

            <!-- DOKUMEN ITINERARY -->
            <div class="card-admin p-5 sm:p-6 border-t-4 border-t-emerald-500">
                <h3 class="font-black text-[14px] mb-4 text-slate-800 border-b border-slate-100 pb-3 flex items-center gap-2">
                    <span class="material-symbols-outlined text-emerald-500">description</span> Dokumen Itinerary
                </h3>
                
                <!-- Status File Saat Ini -->
                @if($departure->itinerary_file)
                    <div class="mb-4 bg-emerald-50/50 border border-emerald-200 rounded-xl p-3 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <span class="material-symbols-outlined text-emerald-600 shrink-0">task</span>
                            <span class="text-[11px] font-bold text-emerald-900 truncate">Itinerary tersedia</span>
                        </div>
                        <a href="{{ asset('storage/' . $departure->itinerary_file) }}" target="_blank" class="text-[11px] bg-white border border-emerald-300 text-emerald-700 hover:bg-emerald-50 font-bold px-3 py-1.5 rounded-lg shrink-0 transition-colors shadow-sm">
                            Lihat
                        </a>
                    </div>
                @else
                    <p class="text-[11px] text-slate-400 italic mb-4">Belum ada file itinerary yang diunggah untuk kloter ini.</p>
                @endif

                <form action="{{ route('admin.departures.update-itinerary', $departure->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase mb-2">Unggah / Ganti File (PDF / Doc)</label>
                        <input type="file" name="itinerary_file" accept=".pdf,.doc,.docx" class="w-full text-[12px] text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-[11px] file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer transition-all border border-slate-200 rounded-xl bg-slate-50 p-1.5" required>
                        <p class="text-[10px] text-slate-400 mt-1.5 font-medium">Format: PDF/DOC/DOCX. Maksimal 5MB.</p>
                    </div>
                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-black py-3 rounded-xl transition-colors shadow-sm text-[13px] flex items-center justify-center gap-1.5">
                        <span class="material-symbols-outlined text-[18px]">upload_file</span> Simpan Itinerary
                    </button>
                </form>
            </div>
            
        </div>

        <!-- KOLOM KANAN: DAFTAR ROMBONGAN -->
        <div class="md:col-span-2 space-y-6">
            <h3 class="font-black text-[16px] text-slate-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-slate-400">format_list_bulleted</span> Rombongan Terdaftar
            </h3>
            
            @forelse($departure->groups as $group)
                <div class="card-admin p-0 overflow-hidden">
                    <!-- Group Header -->
                    <div class="bg-slate-50/50 p-5 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div>
                            <span class="inline-block text-[10px] font-black uppercase tracking-wider bg-hanania-purple/10 text-hanania-purple border border-hanania-purple/20 px-2 py-0.5 rounded-md mb-1.5">{{ $group->code }}</span>
                            <h4 class="font-black text-[16px] text-slate-800 leading-tight">{{ $group->name }}</h4>
                            <p class="text-[11px] text-slate-500 font-bold mt-1">Kapasitas: {{ $group->activeMemberships->count() }} / {{ $group->capacity }} Jamaah</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.groups.show', $group->id) }}" target="_blank" class="text-[11px] bg-white border border-slate-200 text-slate-600 hover:bg-slate-100 font-bold px-4 py-2 rounded-lg flex items-center gap-1.5 transition shadow-sm">
                                Kelola Grup <span class="material-symbols-outlined text-[14px]">open_in_new</span>
                            </a>
                            
                            <!-- TOMBOL KELUARKAN GRUP -->
                            <form action="{{ route('admin.departures.remove-group', [$departure->id, $group->id]) }}" method="POST" onsubmit="return confirm('Keluarkan rombongan ini dari jadwal?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-[11px] bg-rose-50 border border-rose-200 text-rose-600 hover:bg-rose-100 font-bold px-4 py-2 rounded-lg flex items-center gap-1.5 transition shadow-sm" title="Keluarkan Grup">
                                    <span class="material-symbols-outlined text-[14px]">logout</span> Keluarkan
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Jamaah List -->
                    <div class="overflow-x-auto no-scrollbar">
                        <table class="w-full text-left text-[13px]">
                            <tbody class="divide-y divide-slate-100">
                                @forelse($group->activeMemberships as $member)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="py-3.5 px-5">
                                            <p class="font-bold text-slate-800">{{ $member->enrollment->passenger_name }}</p>
                                        </td>
                                        <td class="py-3.5 px-5 text-center">
                                            <span class="text-[10px] font-mono font-bold text-slate-600 bg-slate-100 border border-slate-200 px-2 py-1 rounded">
                                                {{ $member->enrollment->enrollment_number }}
                                            </span>
                                        </td>
                                        <td class="py-3.5 px-5 text-right">
                                            @if($member->enrollment->status === 'scheduled')
                                                <span class="inline-flex text-[9px] bg-emerald-50 text-emerald-600 border border-emerald-200 font-black px-2 py-1 rounded uppercase tracking-widest">Siap Terbang</span>
                                            @else
                                                <span class="inline-flex text-[9px] bg-amber-50 text-amber-600 border border-amber-200 font-black px-2 py-1 rounded uppercase tracking-widest">{{ $member->enrollment->statusText() }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="py-8 text-center text-[12px] text-slate-400 font-medium">Grup ini masih kosong, belum ada jamaah dimasukkan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @empty
                <div class="card-admin p-12 text-center flex flex-col items-center justify-center">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4 border border-slate-100">
                        <span class="material-symbols-outlined text-slate-300 text-[40px]">group_off</span>
                    </div>
                    <h4 class="font-black text-[15px] text-slate-700 mb-1">Pesawat Masih Kosong</h4>
                    <p class="text-[13px] text-slate-500 font-medium max-w-sm">Jadwal ini belum memiliki rombongan. Silakan tarik grup yang tersedia dari kolom sebelah kiri.</p>
                </div>
            @endforelse
        </div>

    </div>
</div>
<script>
    document.querySelectorAll('.js-confirm-departure').forEach((form) => {
        form.addEventListener('submit', (event) => {
            if (!window.confirm(form.dataset.confirmMessage)) {
                event.preventDefault();
            }
        });
    });
</script>
@endsection
