@extends('layouts.customer.app')

@section('title', 'Pengajuan Pembatalan & Refund')

@section('content')
@php
    // Tarik identitas perusahaan & nomor kontak dari database
    $companyName = \App\Models\AppInformation::getValue('company_name', 'Hanania');
    $shortCompanyName = explode(' ', $companyName)[0];
    
    // Tarik nomor telepon, hilangkan karakter non-angka, lalu pastikan berawalan 62 untuk API WhatsApp
    $rawNomor = \App\Models\AppInformation::getValue('phone_number', '6281234567890');
    $waNomor = preg_replace('/[^0-9]/', '', $rawNomor);
    if (str_starts_with($waNomor, '0')) {
        $waNomor = '62' . substr($waNomor, 1);
    }
@endphp

<div class="w-full flex flex-col gap-8 pt-28 pb-12 animate-fade-in-up max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <a href="{{ route('customer.enrollments.show', $enrollment->id) }}" class="inline-flex items-center gap-1.5 text-[13.5px] font-bold text-gray-500 hover:text-red-600 hover:bg-red-50 transition-all w-max bg-surface-container-lowest px-4 py-2 rounded-xl shadow-sm border border-outline-variant/60 active:scale-95">
        <span class="material-symbols-outlined text-[18px]">arrow_back</span> Batal / Kembali
    </a>

    <div class="bg-gradient-to-br from-red-600 to-rose-900 rounded-[28px] p-8 shadow-lg text-white relative overflow-hidden">
        <div class="absolute -right-10 -top-10 text-[150px] opacity-10 font-black material-symbols-outlined">money_off</div>
        <div class="relative z-10">
            <h3 class="text-[24px] md:text-[28px] font-black text-white tracking-tight leading-tight mb-2">Pengajuan Pembatalan & Refund</h3>
            <p class="text-red-100 text-[14px]">Tabungan: <strong>{{ $enrollment->travelPackage->name }}</strong> (Atas Nama: {{ $enrollment->passenger_name }})</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 text-emerald-800 p-4.5 rounded-[20px] font-semibold border border-emerald-200 flex gap-3">
            <span class="material-symbols-outlined text-emerald-500">check_circle</span>
            <div class="flex-1 mt-0.5">{{ session('success') }}</div>
        </div>
    @endif

    @if($existingRequest)
        @if($existingRequest->status === 'refunded')
            <!-- STATUS: SUDAH CAIR -->
            <div class="bg-emerald-50 border border-emerald-200 rounded-[24px] p-8 text-center shadow-sm">
                <span class="material-symbols-outlined text-[56px] text-emerald-500 mb-4 [font-variation-settings:'FILL'_1]">task_alt</span>
                <h4 class="font-black text-emerald-900 text-xl mb-2">Dana Berhasil Dicairkan</h4>
                <p class="text-emerald-700 text-sm max-w-lg mx-auto mb-6">Pengajuan Anda sebesar <strong>Rp {{ number_format($existingRequest->refund_amount, 0, ',', '.') }}</strong> telah berhasil ditransfer ke rekening Anda pada {{ $existingRequest->reviewed_at ? $existingRequest->reviewed_at->format('d M Y') : '' }}.</p>
                
                <div class="flex flex-col sm:flex-row justify-center gap-3">
                    @if($existingRequest->admin_transfer_proof)
                    <a href="{{ asset('storage/' . $existingRequest->admin_transfer_proof) }}" target="_blank" class="inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-3 rounded-xl font-bold transition">
                        <span class="material-symbols-outlined text-[18px]">receipt</span> Lihat Bukti Transfer
                    </a>
                    @endif
                    
                    @if($existingRequest->admin_acc_document)
                    <a href="{{ asset('storage/' . $existingRequest->admin_acc_document) }}" target="_blank" class="inline-flex items-center justify-center gap-2 bg-white text-emerald-700 border border-emerald-300 hover:bg-emerald-100 px-5 py-3 rounded-xl font-bold transition">
                        <span class="material-symbols-outlined text-[18px]">description</span> Surat Persetujuan
                    </a>
                    @endif
                </div>
            </div>
        @else
            <!-- STATUS: MENUNGGU PROSES -->
            <div class="bg-surface-container-lowest border border-outline-variant/60 rounded-[24px] p-8 text-center shadow-sm">
                <span class="material-symbols-outlined text-[48px] text-amber-500 mb-4 [font-variation-settings:'FILL'_1]">pending_actions</span>
                <h4 class="font-black text-gray-800 text-xl mb-2">Pengajuan Sedang Diproses</h4>
                <p class="text-gray-500 text-sm max-w-lg mx-auto">Anda sudah mengirimkan permintaan pada <strong>{{ $existingRequest->created_at->format('d M Y') }}</strong>. Saat ini statusnya adalah <span class="bg-amber-100 text-amber-700 px-2 py-0.5 rounded font-bold uppercase text-[11px]">{{ str_replace('_', ' ', $existingRequest->status) }}</span>. Mohon menunggu informasi dari Admin.</p>
            </div>
        @endif
    @else
        <!-- AREA KONTEN (FORM / WA) -->
        <div class="bg-surface-container-lowest border border-outline-variant/60 rounded-[24px] p-6 md:p-8 shadow-[0_4px_20px_rgba(0,0,0,0.03)]">
            
            @if(isset($departure))
                <!-- JIKA SUDAH MASUK KLOTER: FORM HILANG, GANTI TOMBOL WA -->
                <div class="bg-red-50 border-2 border-red-200 p-8 rounded-[24px] text-center shadow-sm">
                    <span class="material-symbols-outlined text-[56px] text-red-500 mb-4 [font-variation-settings:'FILL'_1]">support_agent</span>
                    <h4 class="font-black text-red-900 text-[20px] mb-2">Pembatalan Khusus (Hubungi CS)</h4>
                    <p class="text-red-800 text-[14px] max-w-lg mx-auto mb-6 leading-relaxed">
                        Bismillah. Anda telah terdaftar pada kloter keberangkatan <strong>{{ \Carbon\Carbon::parse($departure->departure_date)->format('d F Y') }}</strong>.<br><br>
                        <!-- DINAMIS: Menggunakan nama perusahaan -->
                        Karena data dan dokumen Anda telah diproses ke pihak maskapai serta <strong>Konsorsium {{ $companyName }}</strong>, proses pembatalan/penarikan dana tidak dapat dilakukan secara otomatis melalui sistem.
                    </p>
                    
                    @php
                        // DINAMIS: Sapaan Admin menggunakan nama depan perusahaan
                        $waPesan = "Assalamu'alaikum Admin " . $shortCompanyName . ". Saya ingin konsultasi mengenai pembatalan / penarikan dana tabungan untuk jadwal keberangkatan tanggal " . \Carbon\Carbon::parse($departure->departure_date)->format('d M Y') . ". Atas nama jamaah: " . $enrollment->passenger_name . " (No. Tabungan: " . $enrollment->enrollment_number . ").";
                    @endphp

                    <a href="https://wa.me/{{ $waNomor }}?text={{ rawurlencode($waPesan) }}" target="_blank" class="inline-flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white px-8 py-3.5 rounded-xl font-black transition-all shadow-lg active:scale-95">
                        <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
                        Konsultasi ke Admin
                    </a>
                </div>
            @else
                <!-- FORM PENGAJUAN (JIKA BELUM MASUK KLOTER) -->
                <div class="bg-amber-50 border border-amber-200 p-4 rounded-xl flex gap-3 mb-8">
                    <span class="material-symbols-outlined text-amber-600">warning</span>
                    <p class="text-[13px] text-amber-800 font-medium"><strong>Perhatian:</strong> Dana yang dikembalikan (Refund) akan dihitung berdasarkan Syarat & Ketentuan travel (potongan biaya administrasi / perlengkapan jika sudah diberikan).</p>
                </div>

                <form action="{{ route('customer.cancellations.store', $enrollment->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label class="block text-[13.5px] font-bold text-gray-700 mb-2">Alasan Pembatalan</label>
                        <div class="relative">
                            <select name="cancellation_type" required class="w-full px-5 py-4 bg-surface-container-high border border-outline-variant rounded-xl text-[14px] font-bold text-gray-700 focus:border-red-500 focus:ring-2 focus:ring-red-200 outline-none appearance-none">
                                <option value="">-- Pilih Kategori Alasan --</option>
                                <option value="medical">Masalah Kesehatan / Sakit</option>
                                <option value="death">Meninggal Dunia</option>
                                <option value="force_majeure">Force Majeure (Bencana/Keadaan Memaksa)</option>
                                <option value="voluntary_withdrawal">Pengunduran Diri Sepihak (Alasan Pribadi)</option>
                                <option value="other">Lainnya</option>
                            </select>
                            <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">expand_more</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[13.5px] font-bold text-gray-700 mb-2">Tarik Dana Sebagian (Opsional)</label>
                        <div class="relative group">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-extrabold text-[16px] group-focus-within:text-red-500 transition-colors">Rp</span>
                            <input type="number" name="requested_amount" placeholder="Contoh: 5000000 (Kosongkan jika batal 100%)" class="w-full pl-12 pr-4 py-4 bg-surface-container-high border border-outline-variant rounded-xl text-[14px] font-bold text-gray-800 focus:bg-white focus:border-red-500 focus:ring-4 focus:ring-red-100 outline-none transition-all">
                        </div>
                        <p class="text-[11.5px] text-gray-500 mt-2 font-medium flex items-center gap-1.5"><span class="material-symbols-outlined text-[16px] text-amber-500">info</span> Isi nominal jika Anda hanya ingin menarik sebagian dana karena urusan mendesak (tidak membatalkan keberangkatan).</p>
                    </div>

                    <div>
                        <label class="block text-[13.5px] font-bold text-gray-700 mb-2">Detail Alasan</label>
                        <textarea name="reason" rows="4" required placeholder="Jelaskan alasan pengajuan Anda secara detail..." class="w-full px-5 py-4 bg-surface-container-high border border-outline-variant rounded-xl text-[14px] text-gray-700 focus:border-red-500 focus:ring-2 focus:ring-red-200 outline-none"></textarea>
                    </div>

                    <div>
                        <label class="block text-[13.5px] font-bold text-gray-700 mb-2">Dokumen Pendukung (Opsional)</label>
                        <p class="text-[11.5px] text-gray-500 mb-2">Unggah surat keterangan sakit dari RS, akta kematian, atau bukti pendukung lainnya jika ada (PDF/JPG/PNG).</p>
                        <input type="file" name="supporting_document" accept=".jpg,.jpeg,.png,.pdf" class="w-full text-[12px] text-gray-600 file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:font-bold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 cursor-pointer border border-outline-variant p-1 rounded-xl">
                    </div>

                    <div class="pt-4 border-t border-outline-variant/60">
                        <button type="submit" class="w-full py-4 bg-red-600 hover:bg-red-700 text-white font-black text-[15px] rounded-xl transition shadow-lg flex items-center justify-center gap-2" onclick="return confirm('Apakah Anda yakin ingin mengajukan pembatalan / penarikan dana ini?')">
                            <span class="material-symbols-outlined">send</span> Ajukan Refund Sekarang
                        </button>
                    </div>
                </form>
            @endif
        </div>
    @endif
</div>
@endsection