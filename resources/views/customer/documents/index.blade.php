@extends('layouts.customer.app')

@section('title', 'Upload Dokumen')

@section('content')
    @php
        $companyName = \App\Models\AppInformation::getValue('company_name', 'Hanania');
    @endphp

    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(14px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade-in-up { animation: fadeInUp .55s cubic-bezier(.16,1,.3,1) forwards; }
        .hide-scroll::-webkit-scrollbar { display: none; }
        .hide-scroll { -ms-overflow-style: none; scrollbar-width: none; }

        .doc-row {
            transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
        }

        .doc-row:hover {
            transform: translateY(-2px);
        }
    </style>

    <div class="w-full min-h-screen pt-24 pb-16">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 animate-fade-in-up">

            <!-- HEADER -->
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-7">
                <div>
                    <a href="{{ route('customer.enrollments.show', $enrollment->id) }}"
                       class="inline-flex items-center gap-1.5 text-[11px] sm:text-[12px] font-bold text-hanania-purple-dark/70 hover:text-hanania-purple transition-colors mb-4">
                        <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                        Kembali ke Tabungan
                    </a>
                    <p class="text-[9px] font-black uppercase tracking-[.18em] text-hanania-purple">Persiapan Perjalanan</p>
                    <h1 class="font-heading text-[29px] sm:text-[38px] font-black tracking-tight text-hanania-purple-dark mt-1">Dokumen Jamaah</h1>
                    <p class="text-[12px] sm:text-[13px] text-gray-500 font-medium mt-2 max-w-2xl">
                        Unggah dokumen perjalanan untuk <span class="font-bold text-hanania-purple-dark">{{ $enrollment->passenger_name }}</span>. Kami akan memeriksa setiap dokumen sebelum keberangkatan.
                    </p>
                </div>

                <div class="shrink-0 inline-flex items-center gap-2 bg-white border border-hanania-purple/10 rounded-full px-3.5 py-2 shadow-sm">
                    <span class="material-symbols-outlined text-[16px] text-hanania-gold [font-variation-settings:'FILL'_1]">verified_user</span>
                    <span class="text-[9px] sm:text-[10px] font-black uppercase tracking-[.14em] text-hanania-purple-dark">Data terlindungi</span>
                </div>
            </div>

            <!-- ALERT -->
            @if(session('success'))
                <div class="mb-6 bg-emerald-50 text-emerald-800 p-4 rounded-2xl text-[12px] sm:text-[13px] font-bold border border-emerald-200 flex items-center gap-3 shadow-sm">
                    <span class="material-symbols-outlined text-emerald-500 [font-variation-settings:'FILL'_1] text-[20px]">check_circle</span>
                    <div class="flex-1">{{ session('success') }}</div>
                </div>
            @endif

            <!-- INTRO / STATUS -->
            <section class="bg-hanania-purple-dark text-white rounded-[1.9rem] border border-hanania-purple/10 shadow-xl overflow-hidden mb-6">
                <div class="p-6 sm:p-8 grid lg:grid-cols-[1fr_auto] gap-6 items-center">
                    <div>
                        <p class="text-[8px] font-black uppercase tracking-[.2em] text-hanania-purple-light">Panduan Singkat</p>
                        <h2 class="font-heading text-white text-[21px] sm:text-[25px] font-black mt-2">Lengkapi dokumen Anda satu per satu.</h2>
                        <p class="text-[11px] sm:text-[12px] text-white/55 leading-relaxed max-w-2xl mt-2">
                            Pastikan foto atau scan terlihat jelas. Dokumen akan direview oleh Admin <span class="font-bold text-white">{{ $companyName }}</span> dan seluruhnya wajib berstatus <span class="text-hanania-gold font-bold">Disetujui</span> sebelum keberangkatan.
                        </p>
                    </div>

                    <div class="flex items-center gap-3 bg-white/5 border border-white/10 rounded-2xl px-4 py-3 shrink-0">
                        <div class="w-9 h-9 rounded-xl bg-hanania-purple/40 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[19px] text-hanania-gold">folder_open</span>
                        </div>
                        <div>
                            <p class="text-[8px] uppercase tracking-[.16em] text-white/40 font-black">Status Berkas</p>
                            <p class="text-[12px] font-black text-white">Siap diperiksa</p>
                        </div>
                    </div>
                </div>
            </section>

            @php
                $existingDocs = $enrollment->documents->keyBy('document_type');
                $docTypes = [
                    'ktp' => 'Kartu Tanda Penduduk (KTP)',
                    'kk' => 'Kartu Keluarga (KK)',
                    'passport_biodata' => 'Paspor (Halaman 2-3 / Biodata)',
                    'passport_endorsement' => 'Paspor (Halaman 4-5 / Pengesahan)',
                    'photo' => 'Pas Foto Resmi',
                    'other' => 'Buku Kuning / Dokumen Tambahan'
                ];
            @endphp

            <!-- DOCUMENT LIST -->
            <section class="bg-white rounded-[1.9rem] border border-hanania-purple/10 shadow-lg overflow-hidden">
                <div class="px-5 sm:px-7 py-5 border-b border-hanania-purple/10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <div>
                        <p class="text-[8px] uppercase tracking-[.18em] text-hanania-purple font-black">Daftar Persyaratan</p>
                        <h3 class="font-heading text-[20px] sm:text-[23px] font-black text-hanania-purple-dark mt-1">Dokumen Perjalanan</h3>
                    </div>
                    <p class="text-[10px] text-gray-400 font-medium">JPG, JPEG, PNG, atau PDF</p>
                </div>

                <div class="p-4 sm:p-6 space-y-3">
                    @foreach($docTypes as $typeCode => $typeLabel)
                        @php $doc = $existingDocs->get($typeCode); @endphp

                        <div class="doc-row rounded-[1.35rem] border {{ $doc && $doc->status === 'approved' ? 'border-emerald-200 bg-emerald-50/30' : ($doc && $doc->status === 'rejected' ? 'border-red-200 bg-red-50/20' : 'border-hanania-purple/10 bg-white') }} p-4 sm:p-5">
                            <div class="grid lg:grid-cols-[1fr_auto] gap-5 lg:items-center">
                                <div class="min-w-0">
                                    <div class="flex flex-wrap items-center gap-2 mb-2">
                                        <span class="w-8 h-8 rounded-xl bg-hanania-purple-light/55 text-hanania-purple flex items-center justify-center shrink-0">
                                            <span class="material-symbols-outlined text-[17px]">
                                                {{ $typeCode === 'photo' ? 'photo_camera' : ($typeCode === 'other' ? 'description' : 'badge') }}
                                            </span>
                                        </span>
                                        <h4 class="font-heading text-[15px] sm:text-[16px] font-black text-hanania-purple-dark">{{ $typeLabel }}</h4>

                                        @if(!$doc)
                                            <span class="bg-gray-100 text-gray-500 text-[8px] px-2.5 py-1 rounded-full uppercase font-black tracking-[.13em] border border-gray-200">Belum Ada</span>
                                        @elseif($doc->status === 'submitted')
                                            <span class="bg-amber-50 text-amber-600 text-[8px] px-2.5 py-1 rounded-full uppercase font-black tracking-[.13em] border border-amber-200">Review Admin</span>
                                        @elseif($doc->status === 'approved')
                                            <span class="bg-emerald-50 text-emerald-600 text-[8px] px-2.5 py-1 rounded-full uppercase font-black tracking-[.13em] border border-emerald-200">Disetujui</span>
                                        @elseif($doc->status === 'rejected')
                                            <span class="bg-red-50 text-red-500 text-[8px] px-2.5 py-1 rounded-full uppercase font-black tracking-[.13em] border border-red-200">Ditolak</span>
                                        @endif
                                    </div>

                                    @if($doc && $doc->status === 'rejected')
                                        <p class="text-[10px] sm:text-[11px] text-red-600 font-medium mt-2 bg-red-50 border border-red-100 px-3 py-2 rounded-xl inline-flex items-start gap-1.5">
                                            <span class="material-symbols-outlined text-[15px]">error</span>
                                            <span><strong>Alasan:</strong> {{ $doc->rejection_reason }}</span>
                                        </p>
                                    @endif

                                    @if($doc)
                                        <div class="mt-3">
                                            <a href="{{ route('customer.documents.preview', $doc->id) }}" target="_blank" class="inline-flex items-center gap-1.5 text-[10px] sm:text-[11px] font-black text-hanania-purple hover:text-white bg-hanania-purple-light/60 hover:bg-hanania-purple border border-hanania-purple/10 px-3.5 py-2 rounded-xl transition-colors">
                                                <span class="material-symbols-outlined text-[15px]">visibility</span>
                                                Lihat File
                                            </a>
                                        </div>
                                    @endif
                                </div>

                                @if((!$doc || $doc->status === 'rejected' || $doc->status === 'submitted') && $enrollment->status !== 'completed')
                                    <form action="{{ route('customer.documents.store', $enrollment->id) }}" method="POST" enctype="multipart/form-data" class="w-full lg:w-auto">
                                        @csrf
                                        <input type="hidden" name="document_type" value="{{ $typeCode }}">

                                        <div class="flex flex-col sm:flex-row lg:flex-col xl:flex-row gap-2">
                                            <label class="flex-1 lg:w-64 xl:w-64 cursor-pointer rounded-xl border border-dashed border-hanania-purple/20 bg-hanania-purple-light/25 px-3 py-2.5 hover:border-hanania-purple transition-colors">
                                                <input
                                                    type="file"
                                                    name="file_path"
                                                    accept=".jpg,.jpeg,.png,.pdf"
                                                    required
                                                    class="block w-full text-[10px] text-hanania-purple-dark/70 file:mr-2 file:py-2 file:px-3 file:border-0 file:rounded-lg file:bg-hanania-purple-light file:text-hanania-purple file:font-black file:text-[10px] cursor-pointer"
                                                >
                                            </label>

                                            <button type="submit" class="btn-hanania-gold py-3 px-4 rounded-xl text-[11px] shrink-0">
                                                <span class="material-symbols-outlined text-[16px]">cloud_upload</span>
                                                {{ $doc ? 'Re-Upload' : 'Upload' }}
                                            </button>
                                        </div>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

            <div class="mt-5 flex items-start gap-2.5 px-1">
                <span class="material-symbols-outlined text-[16px] text-hanania-gold mt-0.5">info</span>
                <p class="text-[10px] sm:text-[11px] text-gray-400 leading-relaxed">
                    Gunakan dokumen yang masih berlaku dan pastikan seluruh informasi dapat terbaca dengan jelas. Hindari foto yang buram, gelap, atau terpotong.
                </p>
            </div>
        </div>
    </div>
@endsection
