@php
    $pendingMidtrans = collect();
    $pendingManual = collect();
    $historyTransactions = collect();

    if ($enrollment->paymentPlan) {
        $pendingMidtrans = $enrollment->paymentPlan->transactions()->where('status', 'pending')->whereNotNull('snap_token')->orderBy('created_at', 'desc')->get();
        $pendingManual = $enrollment->paymentPlan->transactions()->where('status', 'pending')->whereNull('snap_token')->orderBy('created_at', 'desc')->get();
        $historyTransactions = $enrollment->paymentPlan->transactions()->whereIn('status', ['verified', 'rejected', 'expired'])->orderBy('created_at', 'desc')->get();
    }
@endphp

<!-- Pembayaran Diproses -->
@if($pendingMidtrans->count() > 0 || $pendingManual->count() > 0)
<div class="mb-6">
    <h4 class="font-extrabold text-gray-800 mb-3 px-1 flex items-center gap-1.5 text-[14px]">
        <span class="material-symbols-outlined text-amber-500 text-[18px]">hourglass_empty</span> Sedang Diproses
    </h4>
    <div class="space-y-3">
        @foreach($pendingMidtrans as $trx)
            <div class="bg-white border border-gray-100 p-4 rounded-[16px] shadow-sm flex justify-between items-center relative overflow-hidden">
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-amber-400"></div>
                <div class="pl-2">
                    <p class="text-[13px] font-extrabold text-gray-800">{{ ucfirst(str_replace('_', ' ', $trx->type)) }}</p>
                    <p class="text-[10px] text-gray-500 mt-0.5">Dibuat: {{ $trx->created_at->format('d M Y, H:i') }} (Otomatis)</p>
                </div>
                <div class="text-right">
                    <p class="text-[14px] font-black text-gray-800">Rp {{ number_format($trx->amount, 0, ',', '.') }}</p>
                    <p class="text-[9px] font-extrabold uppercase text-amber-600 mt-1 bg-amber-50 px-2 py-0.5 rounded inline-block animate-pulse">Menunggu Bayar</p>
                </div>
            </div>
        @endforeach

        @foreach($pendingManual as $trx)
            <div class="bg-white border border-gray-100 p-4 rounded-[16px] shadow-sm flex justify-between items-center relative overflow-hidden">
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-amber-400"></div>
                <div class="pl-2">
                    <p class="text-[13px] font-extrabold text-gray-800">{{ ucfirst(str_replace('_', ' ', $trx->type)) }}</p>
                    <p class="text-[10px] text-gray-500 mt-0.5">Upload: {{ $trx->created_at->format('d M Y, H:i') }} (Manual)</p>
                </div>
                <div class="text-right">
                    <p class="text-[14px] font-black text-gray-800">Rp {{ number_format($trx->amount, 0, ',', '.') }}</p>
                    <p class="text-[9px] font-extrabold uppercase text-amber-600 mt-1 bg-amber-50 px-2 py-0.5 rounded inline-block">Verifikasi Admin</p>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif

<!-- Riwayat Selesai -->
<div>
    <h4 class="font-extrabold text-gray-800 mb-3 px-1 flex items-center gap-1.5 text-[14px]">
        <span class="material-symbols-outlined text-gray-500 text-[18px]">history</span> Riwayat Transaksi
    </h4>
    <div class="space-y-3 pb-4">
        @forelse($historyTransactions as $trx)
            <div class="bg-white border border-gray-100 p-4 rounded-[16px] shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4 relative overflow-hidden transition-all hover:shadow-md">
                <div class="absolute left-0 top-0 bottom-0 w-1 {{ $trx->status === 'verified' ? 'bg-emerald-500' : 'bg-red-500' }}"></div>
                
                <div class="pl-2 flex-1">
                    <p class="text-[13px] font-extrabold text-gray-800">{{ ucfirst(str_replace('_', ' ', $trx->type)) }}</p>
                    <p class="text-[10px] text-gray-500 mt-0.5">{{ $trx->created_at->format('d M Y, H:i') }}</p>
                </div>
                
                <div class="flex items-center justify-between sm:justify-end gap-4 w-full sm:w-auto pl-2 sm:pl-0">
                    <div class="text-left sm:text-right">
                        <p class="text-[14px] font-black text-gray-800">Rp {{ number_format($trx->amount, 0, ',', '.') }}</p>
                        <span class="text-[9px] font-extrabold uppercase mt-1 px-2 py-0.5 rounded inline-block {{ $trx->status === 'verified' ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }}">
                            {{ $trx->status }}
                        </span>
                    </div>

                    @if($trx->status === 'verified')
                        <a href="{{ route('customer.receipts.download', $trx->id) }}" target="_blank" class="bg-gray-50 hover:bg-purple-50 text-gray-600 hover:text-purple-600 border border-gray-200 hover:border-purple-200 w-10 h-10 rounded-xl flex items-center justify-center transition-colors shrink-0" title="Download Kuitansi">
                            <span class="material-symbols-outlined text-[18px]">download</span>
                        </a>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-gray-50 border border-gray-100 border-dashed p-6 rounded-[16px] text-center">
                <span class="material-symbols-outlined text-gray-300 text-[32px] mb-2">receipt_long</span>
                <p class="text-[11px] font-bold text-gray-400">Belum ada riwayat transaksi yang selesai.</p>
            </div>
        @endforelse
    </div>
</div>