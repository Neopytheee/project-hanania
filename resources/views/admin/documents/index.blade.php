@extends('layouts.admin.app')

@section('header_title', 'Verifikasi Dokumen')

@section('content')
<div class="animate-fade-in-up pb-12">
    
    <!-- HEADER -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h2 class="text-[24px] font-black text-slate-900 tracking-tight flex items-center gap-2">
                <span class="material-symbols-outlined text-hanania-purple text-[28px]">fact_check</span> Verifikasi Dokumen
            </h2>
            <p class="text-[13px] text-slate-500 font-medium mt-1">Cek kelengkapan dan validitas dokumen persyaratan jamaah (KTP, Paspor, dll).</p>
        </div>
    </div>

    <!-- TABEL DATA -->
    <div class="card-admin overflow-hidden">
        <div class="overflow-x-auto no-scrollbar">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-200 text-[11px] font-extrabold text-slate-400 uppercase tracking-widest">
                        <th class="p-4 sm:px-6">Waktu Upload</th>
                        <th class="p-4 sm:px-6">Data Jamaah (Akun)</th>
                        <th class="p-4 sm:px-6">Jenis Dokumen</th>
                        <th class="p-4 sm:px-6 text-center">Berkas</th>
                        <th class="p-4 sm:px-6 text-right">Aksi Verifikasi</th>
                    </tr>
                </thead>
                <tbody class="text-slate-600 divide-y divide-slate-100">
                    @forelse($pendingDocuments as $doc)
                        <tr class="hover:bg-slate-50/80 transition-colors group">
                            
                            <!-- Waktu -->
                            <td class="p-4 sm:px-6 align-middle">
                                <p class="font-bold text-[13px] text-slate-800">{{ $doc->uploaded_at->format('d M Y') }}</p>
                                <p class="text-[11px] font-medium text-slate-400 mt-0.5">{{ $doc->uploaded_at->format('H:i') }} WIB</p>
                            </td>

                            <!-- Jamaah -->
                            <td class="p-4 sm:px-6 align-middle">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center font-extrabold text-[14px] border border-slate-200 shrink-0">
                                        {{ strtoupper(substr($doc->enrollment->customer->name ?? 'J', 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-[14px] text-slate-800">{{ $doc->enrollment->customer->name ?? 'Hamba Allah' }}</p>
                                        <div class="flex items-center gap-1 text-[11px] font-medium text-slate-500 mt-0.5">
                                            <span class="inline-flex px-1.5 py-0.5 rounded bg-slate-200 text-slate-600 font-mono font-bold text-[9px]">{{ $doc->enrollment->enrollment_number }}</span>
                                            <span class="truncate max-w-[150px] ml-1" title="{{ $doc->enrollment->travelPackage->name }}">{{ $doc->enrollment->travelPackage->name }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Jenis Dokumen -->
                            <td class="p-4 sm:px-6 align-middle">
                                <span class="inline-flex items-center gap-1.5 bg-hanania-purple/10 text-hanania-purple border border-hanania-purple/20 font-black px-3 py-1.5 rounded-lg text-[10px] uppercase tracking-widest">
                                    <span class="material-symbols-outlined text-[14px]">description</span>
                                    {{ str_replace('_', ' ', $doc->document_type) }}
                                </span>
                            </td>

                            <!-- Buka File -->
                            <td class="p-4 sm:px-6 align-middle text-center">
                                <a href="{{ route('admin.documents.preview', $doc->id) }}" target="_blank" class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-slate-100 text-slate-600 hover:bg-blue-500 hover:text-white border border-slate-200 hover:border-blue-500 transition-all shadow-sm" title="Buka / Lihat File">
                                    <span class="material-symbols-outlined text-[18px]">visibility</span>
                                </a>
                            </td>

                            <!-- Aksi -->
                            <td class="p-4 sm:px-6 align-middle text-right">
                                <div class="flex items-center justify-end gap-2">
                                    
                                    <!-- Tombol Setuju -->
                                    <form action="{{ route('admin.documents.approve', $doc->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" onclick="return confirm('Sahkan dokumen {{ str_replace('_', ' ', $doc->document_type) }} milik {{ $doc->enrollment->customer->name ?? 'Jamaah' }}?')" class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2.5 rounded-xl text-[12px] font-bold transition-all shadow-[0_4px_10px_rgba(16,185,129,0.3)] flex items-center justify-center gap-1.5">
                                            <span class="material-symbols-outlined text-[16px]">check_circle</span> Sahkan
                                        </button>
                                    </form>

                                    <!-- Tombol Tolak (Panggil Modal) -->
                                    <button type="button" onclick="openRejectDocModal('{{ route('admin.documents.reject', $doc->id) }}', '{{ $doc->enrollment->customer->name ?? 'Jamaah' }}', '{{ str_replace('_', ' ', $doc->document_type) }}')" class="bg-white border border-rose-200 text-rose-600 hover:bg-rose-50 hover:border-rose-300 px-4 py-2.5 rounded-xl text-[12px] font-bold transition-all shadow-sm flex items-center justify-center gap-1.5">
                                        <span class="material-symbols-outlined text-[16px]">cancel</span> Tolak
                                    </button>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-400">
                                    <div class="w-20 h-20 bg-emerald-50 rounded-full flex items-center justify-center mb-4 border-4 border-white shadow-sm">
                                        <span class="material-symbols-outlined text-[40px] text-emerald-400">task_alt</span>
                                    </div>
                                    <h3 class="text-[16px] font-black text-slate-700 mb-1">Semua Beres!</h3>
                                    <p class="text-[13px] font-medium text-slate-500">Tidak ada dokumen jamaah yang menunggu verifikasi saat ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- CUSTOM MODAL TOLAK DOKUMEN -->
<!-- ========================================== -->
<div id="rejectDocModal" class="fixed inset-0 z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeRejectDocModal()"></div>
    
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md mx-4 relative z-10 transform scale-95 transition-transform duration-300 overflow-hidden" id="rejectDocModalContent">
        <div class="bg-rose-50 px-6 py-5 border-b border-rose-100 flex items-center gap-3">
            <div class="w-10 h-10 bg-rose-100 rounded-full flex items-center justify-center text-rose-600 shrink-0">
                <span class="material-symbols-outlined [font-variation-settings:'FILL'_1]">warning</span>
            </div>
            <div>
                <h3 class="text-[16px] font-black text-rose-900 leading-tight">Tolak Dokumen</h3>
                <p class="text-[11px] font-bold text-rose-600 uppercase tracking-widest mt-0.5 truncate max-w-[250px]" id="rejectDocModalSub">Nama - Dokumen</p>
            </div>
        </div>

        <form id="rejectDocForm" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-[12px] font-bold text-slate-700 mb-2 uppercase tracking-wide">Alasan Penolakan Berkas <span class="text-rose-500">*</span></label>
                <textarea name="reason" rows="3" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl text-[13px] font-medium text-slate-800 focus:bg-white focus:border-rose-400 focus:ring-4 focus:ring-rose-400/10 outline-none transition-all" placeholder="Contoh: Foto KTP blur/terpotong, file tidak bisa dibaca, dll." required></textarea>
                <p class="text-[11px] text-slate-400 mt-2 font-medium">Jamaah akan menerima pesan ini agar mereka dapat mengunggah ulang dokumen yang benar.</p>
            </div>
            
            <div class="pt-4 flex gap-3">
                <button type="button" onclick="closeRejectDocModal()" class="flex-1 py-3 bg-white border border-slate-200 text-slate-600 font-bold rounded-xl text-[13px] hover:bg-slate-50 transition-colors">
                    Batal
                </button>
                <button type="submit" class="flex-1 py-3 bg-rose-600 text-white font-bold rounded-xl text-[13px] shadow-[0_4px_14px_rgba(225,29,72,0.3)] hover:bg-rose-700 hover:shadow-[0_6px_20px_rgba(225,29,72,0.4)] transition-all">
                    Tolak Dokumen
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openRejectDocModal(actionUrl, passengerName, docType) {
        const modal = document.getElementById('rejectDocModal');
        const modalContent = document.getElementById('rejectDocModalContent');
        const rejectForm = document.getElementById('rejectDocForm');
        const subtitle = document.getElementById('rejectDocModalSub');
        
        rejectForm.action = actionUrl;
        subtitle.innerText = passengerName + ' - ' + docType.toUpperCase();
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modalContent.classList.remove('scale-95');
            modalContent.classList.add('scale-100');
        }, 10);
    }

    function closeRejectDocModal() {
        const modal = document.getElementById('rejectDocModal');
        const modalContent = document.getElementById('rejectDocModalContent');
        
        modal.classList.add('opacity-0');
        modalContent.classList.remove('scale-100');
        modalContent.classList.add('scale-95');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            document.getElementById('rejectDocForm').reset();
        }, 300);
    }
</script>
@endsection