<div class="bg-white border border-gray-200 rounded-xl p-5 mb-6 shadow-sm">
    <h4 class="font-bold text-gray-800 border-b border-gray-100 pb-3 mb-4">Setor Tabungan Baru</h4>
    
    @if($progress['sisa_tagihan'] > 0)
        <form action="{{ route('customer.payments.store', $enrollment->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-gray-700">Nominal Pembayaran (Rp)</label>
                <p class="text-xs text-hanania-purple mb-2">{{ $paymentRules['label_saran'] }}</p>
                <input type="number" name="amount" min="{{ $paymentRules['min_amount'] }}" max="{{ $progress['sisa_tagihan'] }}" placeholder="Contoh: {{ $paymentRules['min_amount'] }}" class="w-full p-3 border border-gray-300 rounded-lg text-sm focus:border-hanania-purple focus:ring-1 focus:ring-hanania-purple outline-none" required>
                <p class="text-[10px] text-gray-400 mt-1">*Maksimal setoran: Rp {{ number_format($progress['sisa_tagihan'], 0, ',', '.') }}</p>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Metode Pembayaran</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="border rounded-xl p-3 flex items-center gap-2 cursor-pointer hover:border-hanania-purple transition">
                        <input type="radio" name="payment_method" value="midtrans" checked onchange="togglePaymentMethod()">
                        <span class="text-xs font-bold text-gray-800">Otomatis (Midtrans)</span>
                    </label>
                    <label class="border rounded-xl p-3 flex items-center gap-2 cursor-pointer hover:border-hanania-purple transition">
                        <input type="radio" name="payment_method" value="manual_transfer" onchange="togglePaymentMethod()">
                        <span class="text-xs font-bold text-gray-800">Transfer Manual</span>
                    </label>
                </div>
            </div>

            <div id="manual-proof-section" class="hidden">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Unggah Bukti Transfer</label>
                <p class="text-[11px] text-gray-400 mb-2">Format: JPG, PNG, PDF (Maks. 5MB)</p>
                <input type="file" name="proof_file" id="proof-file-input" accept=".jpg,.jpeg,.png,.pdf" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-purple-50 file:text-hanania-purple hover:file:bg-purple-100">
            </div>

            <button type="submit" class="block w-full py-3 bg-hanania-purple text-white text-center rounded-lg font-bold hover:bg-purple-800 transition shadow-md mt-4">
                Proses Pembayaran
            </button>
        </form>
    @else
        <div class="bg-green-50 text-green-700 p-4 rounded-lg text-center text-sm font-bold border border-green-200">
            Semua tagihan sudah terbayar lunas. Anda tidak perlu menyetor lagi.
        </div>
    @endif
</div>

<script>
    function togglePaymentMethod() {
        const isManual = document.querySelector('input[value="manual_transfer"]').checked;
        const proofSection = document.getElementById('manual-proof-section');
        const proofInput = document.getElementById('proof-file-input');
        
        if (isManual) {
            proofSection.classList.remove('hidden');
            proofInput.setAttribute('required', 'required');
        } else {
            proofSection.classList.add('hidden');
            proofInput.removeAttribute('required');
            proofInput.value = "";
        }
    }
</script>