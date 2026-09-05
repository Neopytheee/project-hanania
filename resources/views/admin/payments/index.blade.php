@extends('layouts.admin.app')

@section('header_title', 'Verifikasi Pembayaran')

@section('content')
<div class="animate-fade-in-up pb-10">

    <!-- ========================================== -->
    <!-- HEADER HALAMAN -->
    <!-- ========================================== -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h2 class="text-[24px] font-black text-slate-900 tracking-tight">Antrean Verifikasi Dana</h2>
            <p class="text-[13px] text-slate-500 font-medium mt-1">Cek keabsahan bukti transfer jamaah dan sahkan setoran mereka.</p>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- TABEL DATA (Enterprise Grade) -->
    <!-- ========================================== -->
    <div class="card-admin overflow-hidden">
        <div class="overflow-x-auto no-scrollbar">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-200 text-[11px] font-extrabold text-slate-400 uppercase tracking-widest">
                        <th class="p-4 sm:px-6">Waktu Transaksi</th>
                        <th class="p-4 sm:px-6">Data Jamaah (Penumpang)</th>
                        <th class="p-4 sm:px-6">Tujuan Pembayaran</th>
                        <th class="p-4 sm:px-6">Nominal & Bukti</th>
                        <th class="p-4 sm:px-6 text-right">Aksi Verifikasi</th>
                    </tr>
                </thead>
                <tbody class="text-slate-600 divide-y divide-slate-100">
                    @forelse($pendingTransactions as $trx)
                        @php
                            $enrollment = optional(optional($trx->paymentPlan)->enrollment);
                            $customer = optional($enrollment->customer);
                            $package = optional($enrollment->travelPackage);
                            $passengerName = $enrollment->passenger_name ?? 'Penumpang Anonim';
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition-colors group">
                            
                            <!-- Waktu Transaksi -->
                            <td class="p-4 sm:px-6 align-top">
                                <p class="font-bold text-[13px] text-slate-800">{{ $trx->created_at->format('d M Y') }}</p>
                                <p class="text-[11px] font-medium text-slate-400 mt-0.5">{{ $trx->created_at->format('H:i') }} WIB</p>
                            </td>
                            
                            <!-- Data Jamaah -->
                            <td class="p-4 sm:px-6 align-top">
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 rounded-full bg-hanania-purple/10 text-hanania-purple flex items-center justify-center font-extrabold text-[14px] border border-hanania-purple/20 shrink-0 mt-0.5">
                                        {{ strtoupper(substr($passengerName, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-[14px] text-slate-800 flex items-center gap-1.5">
                                            {{ $passengerName }}
                                            @if($enrollment->relationship && $enrollment->relationship !== 'Diri Sendiri')
                                                <span class="inline-flex px-1.5 py-0.5 rounded bg-slate-100 text-slate-500 text-[9px] uppercase tracking-wider font-extrabold border border-slate-200">
                                                    {{ $enrollment->relationship }}
                                                </span>
                                            @endif
                                        </p>
                                        <div class="flex items-center gap-1 text-[11px] font-medium text-slate-500 mt-1">
                                            <span class="material-symbols-outlined text-[14px]">account_circle</span>
                                            Akun: {{ $customer->name ?? '-' }}
                                        </div>
                                        <p class="text-[10px] text-slate-400 font-mono mt-0.5">ID: {{ $enrollment->enrollment_number ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>

                            <!-- Tujuan Pembayaran -->
                            <td class="p-4 sm:px-6 align-top whitespace-normal min-w-[200px]">
                                <p class="font-bold text-[13px] text-slate-800 leading-tight mb-1">{{ $package->name ?? 'Paket Tidak Ditemukan' }}</p>
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-[9px] font-extrabold uppercase tracking-widest {{ $trx->type === 'initial_deposit' ? 'bg-hanania-gold/20 text-yellow-800' : 'bg-blue-50 text-blue-700' }}">
                                    <span class="material-symbols-outlined text-[12px]">{{ $trx->type === 'initial_deposit' ? 'play_circle' : 'fast_forward' }}</span>
                                    {{ $trx->type === 'initial_deposit' ? 'Setoran DP' : 'Setoran Lanjutan' }}
                                </span>
                            </td>

                            <!-- Nominal & Bukti -->
                            <td class="p-4 sm:px-6 align-top">
                                <p class="font-black text-[15px] text-emerald-600 mb-2 drop-shadow-sm">
                                    Rp {{ number_format($trx->amount, 0, ',', '.') }}
                                </p>
                                <a href="{{ asset('storage/' . $trx->proof_file) }}" target="_blank" class="inline-flex items-center gap-1.5 bg-slate-100 text-slate-600 hover:bg-hanania-purple hover:text-white px-3 py-1.5 rounded-lg text-[11px] font-bold transition-all border border-slate-200 w-max shadow-sm group/btn">
                                    <span class="material-symbols-outlined text-[16px] group-hover/btn:scale-110 transition-transform">receipt_long</span> Cek Struk
                                </a>
                            </td>
                            
                            <!-- Aksi -->
                            <td class="p-4 sm:px-6 align-top text-right">
                                <div class="flex flex-col sm:flex-row items-end sm:items-center justify-end gap-2">
                                    
                                    <!-- Tombol Sah (Approve) -->
                                    <form action="{{ route('admin.payments.verify', $trx->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" onclick="return confirm('Sahkan setoran sebesar Rp {{ number_format($trx->amount, 0, ',', '.') }} dari {{ $passengerName }}?')" class="w-full sm:w-auto bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 rounded-xl text-[12px] font-bold transition-all shadow-[0_4px_10px_rgba(16,185,129,0.3)] flex items-center justify-center gap-1.5">
                                            <span class="material-symbols-outlined text-[16px]">verified</span> Sahkan
                                        </button>
                                    </form>

                                    <!-- Tombol Tolak (Reject) memanggil Custom Modal -->
                                    <button type="button" onclick="openRejectModal('{{ route('admin.payments.reject', $trx->id) }}', '{{ $passengerName }}', 'Rp {{ number_format($trx->amount, 0, ',', '.') }}')" class="w-full sm:w-auto bg-white border border-rose-200 text-rose-600 hover:bg-rose-50 hover:border-rose-300 px-4 py-2 rounded-xl text-[12px] font-bold transition-all shadow-sm flex items-center justify-center gap-1.5">
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
                                    <p class="text-[13px] font-medium text-slate-500">Tidak ada setoran yang menunggu verifikasi saat ini.</p>
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
<!-- CUSTOM MODAL TOLAK PEMBAYARAN -->
<!-- ========================================== -->
<div id="rejectModal" class="fixed inset-0 z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
    
    <!-- Backdrop Blur -->
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeRejectModal()"></div>
    
    <!-- Modal Content -->
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md mx-4 relative z-10 transform scale-95 transition-transform duration-300 overflow-hidden" id="rejectModalContent">
        
        <!-- Header Modal -->
        <div class="bg-rose-50 px-6 py-5 border-b border-rose-100 flex items-center gap-3">
            <div class="w-10 h-10 bg-rose-100 rounded-full flex items-center justify-center text-rose-600 shrink-0">
                <span class="material-symbols-outlined [font-variation-settings:'FILL'_1]">warning</span>
            </div>
            <div>
                <h3 class="text-[16px] font-black text-rose-900 leading-tight">Tolak Setoran</h3>
                <p class="text-[11px] font-bold text-rose-600 uppercase tracking-widest mt-0.5" id="rejectModalSub">Jamaah</p>
            </div>
        </div>

        <!-- Form Penolakan -->
        <form id="rejectForm" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-[12px] font-bold text-slate-700 mb-2 uppercase tracking-wide">Alasan Penolakan <span class="text-rose-500">*</span></label>
                <textarea name="reason" rows="3" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl text-[13px] font-medium text-slate-800 focus:bg-white focus:border-rose-400 focus:ring-4 focus:ring-rose-400/10 outline-none transition-all" placeholder="Contoh: Bukti transfer buram, nominal tidak sesuai, dll." required></textarea>
                <p class="text-[11px] text-slate-400 mt-2 font-medium">Alasan ini akan ditampilkan kepada jamaah agar mereka bisa mengunggah ulang bukti yang benar.</p>
            </div>
            
            <div class="pt-4 flex gap-3">
                <button type="button" onclick="closeRejectModal()" class="flex-1 py-3 bg-white border border-slate-200 text-slate-600 font-bold rounded-xl text-[13px] hover:bg-slate-50 transition-colors">
                    Batal
                </button>
                <button type="submit" class="flex-1 py-3 bg-rose-600 text-white font-bold rounded-xl text-[13px] shadow-[0_4px_14px_rgba(225,29,72,0.3)] hover:bg-rose-700 hover:shadow-[0_6px_20px_rgba(225,29,72,0.4)] transition-all">
                    Kirim Penolakan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Script Logika Modal -->
<script>
    function openRejectModal(actionUrl, passengerName, nominal) {
        const modal = document.getElementById('rejectModal');
        const modalContent = document.getElementById('rejectModalContent');
        const rejectForm = document.getElementById('rejectForm');
        const subtitle = document.getElementById('rejectModalSub');
        
        // Atur URL form dan teks info
        rejectForm.action = actionUrl;
        subtitle.innerText = passengerName + ' - ' + nominal;
        
        // Tampilkan Modal dengan Animasi Transisi
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modalContent.classList.remove('scale-95');
            modalContent.classList.add('scale-100');
        }, 10);
    }

    function closeRejectModal() {
        const modal = document.getElementById('rejectModal');
        const modalContent = document.getElementById('rejectModalContent');
        
        // Mainkan Animasi Tutup
        modal.classList.add('opacity-0');
        modalContent.classList.remove('scale-100');
        modalContent.classList.add('scale-95');
        
        // Sembunyikan setelah animasi selesai
        setTimeout(() => {
            modal.classList.add('hidden');
            document.getElementById('rejectForm').reset(); // Bersihkan textarea
        }, 300);
    }
</script>
@endsection