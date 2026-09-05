@extends('layouts.customer.app')

@section('title', 'Proses Pembayaran')

@section('content')
    <!-- 🪄 SINKRONISASI: Diletakkan presisi di TENGAH layar -->
    <div class="flex flex-col items-center justify-center min-h-[75vh] w-full px-4 animate-fade-in-up">
        
        <div class="bg-white p-6 md:p-8 rounded-[24px] shadow-sm border border-outline-variant max-w-md w-full text-center">
            
            <!-- Animasi Loading / Ikon Dompet -->
            <div class="w-16 h-16 bg-purple-100 text-hanania-purple rounded-full flex items-center justify-center mx-auto mb-5 shadow-inner relative">
                <span class="text-3xl absolute animate-ping opacity-20">💳</span>
                <span class="text-3xl relative animate-bounce">💳</span>
            </div>

            <h2 class="text-gray-500 text-[11px] font-extrabold uppercase tracking-widest mb-1.5">Tagihan Berhasil Dibuat</h2>
            <p class="text-[34px] font-black text-hanania-purple mb-6 drop-shadow-sm">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</p>
            
            <!-- Security Trust Badge -->
            <div class="bg-surface-container-high p-4 rounded-xl border border-outline-variant/50 flex items-start gap-3 mb-6 text-left">
                <span class="text-emerald-600 text-lg mt-0.5 material-symbols-outlined [font-variation-settings:'FILL'_1]">lock</span>
                <p class="text-[12px] text-gray-600 leading-relaxed font-medium">
                    Pembayaran diproses secara aman dan terenkripsi melalui <strong>Midtrans</strong>. Membuka sistem pembayaran...
                </p>
            </div>

            <!-- Tombol Pemicu Midtrans (Berjaga-jaga jika pop-up ke-block browser) -->
            <button id="pay-button" class="w-full bg-hanania-purple text-white text-[14px] font-extrabold py-3.5 rounded-xl hover:bg-hanania-dark transition shadow-[0_4px_14px_rgba(76,29,149,0.3)] active:scale-[0.98] mb-4">
                Buka Pop-up Pembayaran
            </button>
            
            <a href="{{ route('customer.enrollments.show', $enrollment->id) }}" class="inline-flex items-center gap-1 text-[12px] font-bold text-gray-400 hover:text-gray-600 transition">
                <span class="material-symbols-outlined text-[16px]">arrow_back</span> Kembali ke Detail Tabungan
            </a>
        </div>
        
    </div>

    <!-- Script Midtrans Snap (Pastikan Client Key di .env sudah benar) -->
    <!-- Jika di Production, ganti "sandbox" menjadi "production" di URL ini -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            var payButton = document.getElementById('pay-button');
            var token = '{{ $transaction->snap_token }}';

            // Fungsi untuk memanggil pop-up Midtrans
            function triggerMidtrans() {
                if (!token || token.trim() === '') {
                    alert("Sistem sedang memproses token. Silakan refresh halaman.");
                    return;
                }

                snap.pay(token, {
                    onSuccess: function(result) {
                        // Jika sukses bayar, kembali ke halaman tabungan
                        window.location.href = "{{ route('customer.enrollments.show', $enrollment->id) }}";
                    },
                    onPending: function(result) {
                        // Jika pilih bayar nanti (seperti VA atau Minimarket)
                        window.location.href = "{{ route('customer.enrollments.show', $enrollment->id) }}";
                    },
                    onError: function(result) {
                        alert("Maaf, pembayaran gagal diproses.");
                        window.location.href = "{{ route('customer.enrollments.show', $enrollment->id) }}";
                    },
                    onClose: function() {
                        // Jika jamaah menutup pop-up sebelum bayar
                        // Tidak diarahkan balik secara paksa supaya bisa klik tombol "Buka Pop-up" lagi
                    }
                });
            }

            // OTOMATIS JALANKAN POP-UP saat halaman selesai dimuat
            setTimeout(function() {
                triggerMidtrans();
            }, 500); // Jeda setengah detik agar mulus

            // Event listener untuk tombol manual (jika jamaah menutup pop-up dan ingin buka lagi)
            if(payButton) {
                payButton.addEventListener('click', function () {
                    triggerMidtrans();
                });
            }
        });
    </script>
@endsection