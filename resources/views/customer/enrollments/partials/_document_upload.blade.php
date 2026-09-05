<!-- 5. RINGKASAN DOKUMEN (SUMMARY CARD) -->
        @php
            $totalWajib = 5;
            $dokumenTerkumpul = $enrollment->documents ? $enrollment->documents->count() : 0;
            $persenDokumen = $totalWajib > 0 ? min(100, round(($dokumenTerkumpul / $totalWajib) * 100)) : 0;
        @endphp

        <div class="bg-white border border-gray-100 rounded-[24px] p-6 mb-6 shadow-[0_4px_20px_rgba(0,0,0,0.03)]">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 border border-amber-100 shrink-0">
                    <span class="material-symbols-outlined [font-variation-settings:'FILL'_1]">folder_special</span>
                </div>
                <div>
                    <h4 class="font-extrabold text-[15px] text-gray-800 leading-tight">Persyaratan Keberangkatan</h4>
                    <p class="text-[11px] text-gray-500">KTP, Paspor, dan dokumen lainnya</p>
                </div>
            </div>

            <div class="mb-4">
                <div class="flex justify-between text-[11px] font-bold mb-1.5">
                    <span class="text-gray-500">Kelengkapan</span>
                    <span class="text-amber-600">{{ $dokumenTerkumpul }} dari {{ $totalWajib }} File</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden shadow-inner">
                    <div class="bg-amber-500 h-full rounded-full transition-all duration-1000" style="width: {{ $persenDokumen }}%"></div>
                </div>
            </div>

            <a href="{{ route('customer.documents.index', $enrollment->id) }}" class="block w-full py-3 bg-amber-50 text-amber-700 text-[13px] text-center rounded-xl font-extrabold hover:bg-amber-100 transition border border-amber-200">
                Lengkapi Dokumen Sekarang &rarr;
            </a>
        </div>