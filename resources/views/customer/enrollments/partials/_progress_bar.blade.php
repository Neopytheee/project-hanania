<div class="bg-white border border-gray-100 rounded-[24px] p-6 shadow-[0_4px_20px_rgba(0,0,0,0.03)]">
    <h4 class="font-extrabold text-gray-800 border-b border-gray-100 pb-3 mb-4">Progres Tabungan</h4>
    
    <div class="flex items-center gap-3 mb-5">
        <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600 border border-purple-100 shrink-0">
            <span class="material-symbols-outlined [font-variation-settings:'FILL'_1]">flight_takeoff</span>
        </div>
        <div>
            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider mb-0.5">Paket Pilihan</p>
            <h6 class="text-[14px] font-extrabold text-gray-800 leading-none">{{ $enrollment->travelPackage->name }}</h6>
        </div>
    </div>
    
    <div class="flex justify-between items-end mb-2">
        <div>
            <p class="text-[10px] text-gray-500 uppercase tracking-wider font-bold mb-0.5">Terkumpul</p>
            <p class="text-[15px] font-black text-emerald-600">Rp {{ number_format($progress['total_dibayar'], 0, ',', '.') }}</p>
        </div>
        <div class="text-right">
            @if($enrollment->final_price)
                <p class="text-[10px] text-purple-600 uppercase tracking-wider font-black mb-0.5 flex items-center justify-end gap-1">
                    <span class="material-symbols-outlined text-[12px]">verified</span> Target Final (Fix)
                </p>
            @else
                <p class="text-[10px] text-gray-500 uppercase tracking-wider font-bold mb-0.5 flex items-center justify-end gap-1">
                    <span class="material-symbols-outlined text-[12px]">schedule</span> Estimasi Target
                </p>
            @endif
            <p class="text-[15px] font-black text-gray-800">Rp {{ number_format($progress['total_harga'], 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Progress Bar UI M3 -->
    <div class="w-full bg-gray-100 rounded-full h-3.5 mb-3 overflow-hidden shadow-inner relative">
        <div class="absolute top-0 left-0 h-full rounded-full transition-all duration-1000 ease-out flex items-center justify-end px-2 {{ $progress['persentase'] >= 100 ? 'bg-emerald-500' : 'bg-gradient-to-r from-purple-600 to-indigo-500' }}" style="width: {{ $progress['persentase'] }}%">
            @if($progress['persentase'] > 10)
                <span class="text-[9px] font-extrabold text-white">{{ $progress['persentase'] }}%</span>
            @endif
            @if($progress['persentase'] < 100)
                <div class="absolute inset-0 bg-white/20 -skew-x-12 animate-[shimmer_2s_infinite]"></div>
            @endif
        </div>
    </div>

    <div class="text-right mb-2">
        @if($progress['sisa_tagihan'] > 0)
            <span class="text-[11px] font-extrabold text-red-600 bg-red-50 border border-red-100 px-2.5 py-1 rounded-lg">
                Sisa: Rp {{ number_format($progress['sisa_tagihan'], 0, ',', '.') }}
            </span>
        @else
            <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 text-[11px] font-extrabold px-3 py-1.5 rounded-lg border border-emerald-200">
                <span class="material-symbols-outlined text-[14px]">celebration</span> Alhamdulillah, Lunas!
            </span>
        @endif
    </div>

    <!-- ========================================== -->
    <!-- DISCLAIMER HARGA -->
    <!-- ========================================== -->
    <div class="mt-6 border-t border-gray-100 pt-5">
        @if(!$enrollment->final_price)
            <div class="bg-blue-50 border border-blue-100 p-3.5 rounded-[16px] flex gap-3 items-start">
                <span class="material-symbols-outlined text-blue-600 [font-variation-settings:'FILL'_1]">info</span>
                <p class="text-[11px] text-blue-800 leading-[1.6]">
                    <strong>Catatan Penting:</strong> Angka target di atas adalah <span class="font-bold">Estimasi Harga</span> saat Anda mendaftar. Harga final yang pasti akan disesuaikan dengan harga tiket pesawat dan hotel pada tahun keberangkatan.
                </p>
            </div>
        @elseif($enrollment->final_price > $enrollment->estimated_price_snapshot)
            <div class="bg-amber-50 border border-amber-200 p-3.5 rounded-[16px] flex gap-3 items-start">
                <span class="material-symbols-outlined text-amber-600 [font-variation-settings:'FILL'_1]">warning</span>
                <p class="text-[11px] text-amber-900 leading-[1.6]">
                    <strong>Penyesuaian Harga:</strong> Terdapat penyesuaian harga dari estimasi awal (Rp {{ number_format($enrollment->estimated_price_snapshot, 0, ',', '.') }}) menjadi harga final saat ini mengikuti kurs dan tiket pesawat terbaru.
                </p>
            </div>
        @else
            <div class="bg-emerald-50 border border-emerald-200 p-3.5 rounded-[16px] flex gap-3 items-start">
                <span class="material-symbols-outlined text-emerald-600 [font-variation-settings:'FILL'_1]">verified</span>
                <p class="text-[11px] text-emerald-800 leading-[1.6]">
                    Harga final keberangkatan Anda telah dikunci (Fix). Silakan selesaikan sisa pembayaran dan lengkapi dokumen perjalanan Anda.
                </p>
            </div>
        @endif
    </div>
</div>