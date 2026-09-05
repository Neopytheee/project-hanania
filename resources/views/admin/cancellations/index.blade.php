@extends('layouts.admin.app')

@section('title', 'Manajemen Pembatalan & Refund')

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex justify-between items-end">
        <div>
            <h2 class="text-2xl font-black text-gray-800">Pengajuan Refund & Tarik Dana</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola permintaan penarikan dana dari jamaah dan unggah bukti transfer.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl font-bold flex items-center gap-2">
            <span class="material-symbols-outlined">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="py-4 px-6 text-xs font-black text-gray-500 uppercase tracking-wider">Jamaah & Paket</th>
                    <th class="py-4 px-6 text-xs font-black text-gray-500 uppercase tracking-wider">Alasan & Dokumen</th>
                    <th class="py-4 px-6 text-xs font-black text-gray-500 uppercase tracking-wider text-center">Status</th>
                    <th class="py-4 px-6 text-xs font-black text-gray-500 uppercase tracking-wider text-right">Aksi ACC</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($cancellations as $cancel)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="py-4 px-6 align-top">
                        <p class="font-bold text-gray-800 text-sm">{{ $cancel->enrollment->passenger_name }}</p>
                        <p class="text-[11px] text-gray-500 mb-1">Paket: {{ $cancel->enrollment->travelPackage->name }}</p>
                        <p class="text-[10px] text-gray-400">Diajukan: {{ $cancel->created_at->format('d M Y') }}</p>
                    </td>
                    <td class="py-4 px-6 align-top">
                        <span class="inline-block bg-gray-100 text-gray-600 text-[10px] font-bold px-2 py-0.5 rounded uppercase mb-1">{{ str_replace('_', ' ', $cancel->cancellation_type) }}</span>
                        <p class="text-xs text-gray-600 leading-relaxed whitespace-pre-line line-clamp-3 mb-2">{{ $cancel->reason }}</p>
                        @if($cancel->supporting_document)
                            <a href="{{ asset('storage/' . $cancel->supporting_document) }}" target="_blank" class="text-[11px] text-blue-600 font-bold hover:underline flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">attachment</span> Dokumen Jamaah
                            </a>
                        @endif
                    </td>
                    <td class="py-4 px-6 align-top text-center">
                        @if($cancel->status === 'refunded')
                            <span class="inline-flex items-center gap-1 bg-emerald-100 text-emerald-700 px-2.5 py-1 rounded-md text-[11px] font-extrabold uppercase">
                                <span class="material-symbols-outlined text-[14px]">check_circle</span> Selesai
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 bg-amber-100 text-amber-700 px-2.5 py-1 rounded-md text-[11px] font-extrabold uppercase animate-pulse">
                                <span class="material-symbols-outlined text-[14px]">pending</span> Menunggu ACC
                            </span>
                        @endif
                    </td>
                    <td class="py-4 px-6 align-top text-right">
                        @if($cancel->status !== 'refunded')
                            <!-- Tombol Trigger Modal ACC -->
                            <button onclick="document.getElementById('modal-acc-{{ $cancel->id }}').classList.remove('hidden')" class="bg-hanania-purple hover:bg-hanania-dark text-white px-3 py-1.5 rounded-lg text-xs font-bold transition shadow-sm">
                                Proses ACC
                            </button>

                            <!-- MODAL FORM ACC (Hidden by default) -->
                            <div id="modal-acc-{{ $cancel->id }}" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm text-left">
                                <div class="bg-white w-full max-w-md rounded-2xl p-6 shadow-xl relative">
                                    <button onclick="document.getElementById('modal-acc-{{ $cancel->id }}').classList.add('hidden')" class="absolute top-4 right-4 text-gray-400 hover:text-red-500">
                                        <span class="material-symbols-outlined">close</span>
                                    </button>
                                    
                                    <h3 class="text-lg font-black text-gray-800 mb-4 border-b pb-3">Proses Pencairan Dana</h3>
                                    
                                    <form action="{{ route('admin.cancellations.approve', $cancel->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                        @csrf
                                        <div>
                                            <label class="block text-xs font-bold text-gray-700 mb-1">Nominal yang Dicairkan (Rp)</label>
                                            <input type="number" name="refund_amount" required class="w-full border border-gray-300 px-3 py-2 rounded-lg text-sm focus:ring-2 focus:ring-hanania-purple outline-none">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-700 mb-1">Potongan Penalti / Admin (Rp) - Opsional</label>
                                            <input type="number" name="penalty_amount" value="0" class="w-full border border-gray-300 px-3 py-2 rounded-lg text-sm focus:ring-2 focus:ring-hanania-purple outline-none">
                                        </div>
                                        <div class="p-3 bg-blue-50 border border-blue-100 rounded-lg">
                                            <label class="block text-xs font-bold text-blue-800 mb-1">Upload Bukti Transfer Bank</label>
                                            <input type="file" name="admin_transfer_proof" required class="text-xs w-full">
                                        </div>
                                        <div class="p-3 bg-purple-50 border border-purple-100 rounded-lg">
                                            <label class="block text-xs font-bold text-purple-800 mb-1">Upload Surat ACC (PDF/JPG)</label>
                                            <input type="file" name="admin_acc_document" required class="text-xs w-full">
                                        </div>
                                        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 rounded-xl shadow-md transition">
                                            Konfirmasi & Transfer Saldo
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <span class="text-xs text-gray-400 font-medium italic">Telah Diproses</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-12 text-center text-gray-500">
                        <span class="material-symbols-outlined text-4xl mb-2 text-gray-300">inbox</span>
                        <p class="font-medium text-sm">Belum ada pengajuan refund/pembatalan.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection