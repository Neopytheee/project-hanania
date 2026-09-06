@extends('layouts.customer.app')

@section('title', 'Detail Tabungan')

@section('content')
    <style>
        @keyframes shimmer {
            0% { transform: translateX(-100%) skewX(-15deg); }
            100% { transform: translateX(200%) skewX(-15deg); }
        }

        .animate-shimmer { animation: shimmer 2.5s infinite linear; }

        .float-anim { animation: float 6s ease-in-out infinite; }
        @keyframes float {
            0% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
            100% { transform: translateY(0); }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(14px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade-in-up {
            animation: fadeInUp .55s cubic-bezier(.16,1,.3,1) forwards;
        }

        .soft-card {
            box-shadow: 0 10px 28px rgba(97,57,143,.06);
        }

        .lift {
            transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
        }

        .lift:hover { transform: translateY(-2px); }
    </style>

    @php
        $companyName = \App\Models\AppInformation::getValue('company_name', 'Hanania');
    @endphp

    <div class="w-full min-h-screen bg-slate-50/50 pt-24 pb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 animate-fade-in-up">

            <!-- ALERTS -->
            <div class="space-y-3 mb-5">
                @if(session('error'))
                    <div class="bg-red-50 text-red-800 p-4 rounded-2xl text-[13px] font-medium border border-red-200 flex gap-3 items-start shadow-sm">
                        <span class="material-symbols-outlined text-red-500 [font-variation-settings:'FILL'_1] text-[20px]">error</span>
                        <div class="flex-1">{{ session('error') }}</div>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="bg-red-50 text-red-800 p-4 rounded-2xl text-[13px] border border-red-200 flex gap-3 items-start shadow-sm">
                        <span class="material-symbols-outlined text-red-500 [font-variation-settings:'FILL'_1] text-[20px]">warning</span>
                        <ul class="list-disc pl-5 space-y-1 flex-1 font-medium">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('success'))
                    <div class="bg-emerald-50 text-emerald-800 p-4 rounded-2xl text-[13px] font-medium border border-emerald-200 flex gap-3 items-center shadow-sm">
                        <span class="material-symbols-outlined text-emerald-500 [font-variation-settings:'FILL'_1] text-[20px]">check_circle</span>
                        <div class="flex-1">{{ session('success') }}</div>
                    </div>
                @endif
            </div>

            <!-- TOP BAR -->
            <div class="flex items-center justify-between gap-4 mb-5">
                <a href="{{ route('customer.enrollments.index') }}"
                       class="inline-flex items-center gap-1.5 text-[11px] sm:text-[12px] font-bold text-hanania-purple-dark/70 hover:text-hanania-purple transition-colors mb-4">
                        <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                    <span class="hidden sm:inline">Kembali ke Tabungan</span>
                    <span class="sm:hidden">Kembali</span>
                </a>
                <span class="hidden sm:block text-[9px] uppercase tracking-[.18em] font-black text-gray-400">Detail Perjalanan</span>
            </div>

            <!-- HERO STATUS -->
            <section class="relative overflow-hidden rounded-[2rem] bg-hanania-purple-dark text-white border border-hanania-purple/10 shadow-xl mb-6">
                <div class="absolute right-0 top-0 w-1/2 h-full bg-hanania-purple/20"></div>
                <div class="absolute -right-16 -top-16 w-52 h-52 rounded-full border-[28px] border-hanania-purple-light/10"></div>
                <div class="absolute -bottom-20 right-1/4 w-48 h-48 rounded-full border border-hanania-gold/20"></div>

                <div class="relative z-10 p-6 sm:p-8 lg:p-9">
                    <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-7">
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2 mb-4">
                                <span class="inline-flex items-center gap-2 bg-white/10 border border-white/10 rounded-full px-3 py-1.5 text-[8px] sm:text-[9px] font-black uppercase tracking-[.16em] text-hanania-gold">
                                    <span class="w-1.5 h-1.5 rounded-full bg-hanania-gold animate-pulse"></span>
                                    {{ strtoupper($enrollment->statusText()) }}
                                </span>
                                <span class="text-[9px] text-white/35 text-white/50 font-bold uppercase tracking-[.14em]">ID {{ $enrollment->enrollment_number }}</span>
                            </div>

                            <p class="text-[9px] uppercase tracking-[.18em] text-white/55 font-black">Perjalanan Anda</p>
                            <h1 class="font-heading text-[28px] text-white sm:text-[38px] font-black leading-tight tracking-tight mt-1 max-w-3xl">
                                {{ $enrollment->travelPackage->name }}
                            </h1>

                            <div class="flex flex-wrap items-center gap-x-5 gap-y-2 mt-4 text-[11px] text-white/55">
                                <span class="inline-flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-[15px] text-hanania-gold">person</span>
                                    {{ $enrollment->passenger_name }}
                                </span>
                                <span class="inline-flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-[15px] text-hanania-gold">confirmation_number</span>
                                    {{ $enrollment->enrollment_number }}
                                </span>
                            </div>
                        </div>

                        <div class="shrink-0 lg:text-right">
                            <p class="text-[8px] uppercase tracking-[.18em] text-white/35 font-black">Progress Saat Ini</p>
                            <p class="font-heading text-[42px] sm:text-[52px] font-black leading-none text-hanania-gold mt-1">{{ $progress['persentase'] }}%</p>
                            <p class="text-[10px] text-white/40 mt-1">dari target perjalanan</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- MAIN GRID -->
            <div class="grid grid-cols-1 xl:grid-cols-[1fr_360px] gap-6 items-start">

                <div class="space-y-6">

                    <!-- PROGRESS / PRIMARY CARD -->
                    <section class="bg-white border border-hanania-purple/10 rounded-[1.9rem] p-6 sm:p-8 soft-card">
                        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-5">
                            <div>
                                <p class="text-[8px] uppercase tracking-[.18em] text-hanania-purple font-black">Keuangan Perjalanan</p>
                                <h2 class="font-heading text-[24px] sm:text-[28px] font-black text-hanania-purple-dark mt-1">Progres Tabungan</h2>
                            </div>

                            @if($progress['sisa_tagihan'] > 0)
                                <div class="sm:text-right">
                                    <p class="text-[8px] uppercase tracking-[.17em] text-gray-400 font-black">Sisa Target</p>
                                    <p class="font-heading text-[19px] font-black text-orange-500 mt-1">Rp {{ number_format($progress['sisa_tagihan'], 0, ',', '.') }}</p>
                                </div>
                            @else
                                <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-600 border border-emerald-200 rounded-full px-3 py-1.5 text-[10px] font-black">
                                    <span class="material-symbols-outlined text-[15px]">celebration</span>
                                    Lunas
                                </span>
                            @endif
                        </div>

                        <div class="grid sm:grid-cols-2 gap-3 mt-6">
                            <div class="rounded-2xl bg-hanania-purple-light/35 border border-hanania-purple/10 p-4">
                                <p class="text-[8px] uppercase tracking-[.16em] text-hanania-purple/55 font-black">Dana Terkumpul</p>
                                <p class="font-heading text-[22px] sm:text-[27px] font-black text-hanania-purple-dark mt-1">
                                    Rp {{ number_format($progress['total_dibayar'], 0, ',', '.') }}
                                </p>
                            </div>

                            <div class="rounded-2xl bg-gray-50 border border-gray-100 p-4">
                                <p class="text-[8px] uppercase tracking-[.16em] text-gray-400 font-black">
                                    {{ $enrollment->final_price ? 'Target Final (Fix)' : 'Estimasi Target' }}
                                </p>
                                <p class="font-heading text-[22px] sm:text-[27px] font-black text-hanania-purple-dark mt-1">
                                    Rp {{ number_format($progress['total_harga'], 0, ',', '.') }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-6">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-[9px] uppercase tracking-[.16em] text-gray-400 font-black">Progress Pembayaran</span>
                                <span class="text-[11px] font-black text-hanania-purple">{{ $progress['persentase'] }}%</span>
                            </div>

                            <div class="w-full bg-hanania-purple-light/50 border border-hanania-gold/10 rounded-full h-3.5 overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-1000 ease-out relative {{ $progress['persentase'] >= 100 ? 'bg-emerald-500' : 'bg-hanania-gold' }}" style="width: {{ $progress['persentase'] }}%">
                                    @if($progress['persentase'] > 8)
                                        <span class="absolute inset-0 flex items-center justify-end pr-2 text-[8px] font-black text-white">{{ $progress['persentase'] }}%</span>
                                    @endif
                                    @if($progress['persentase'] < 100)
                                        <div class="absolute inset-0 bg-white/25 -skew-x-12 animate-shimmer"></div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if($progress['sisa_tagihan'] > 0 && $enrollment->status !== 'completed')
                            <div class="mt-6 rounded-2xl bg-hanania-purple-light/30 border border-hanania-purple/10 px-4 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                <div>
                                    <p class="text-[11px] font-black text-hanania-purple-dark">Ingin menambah tabungan?</p>
                                    <p class="text-[10px] text-gray-500 mt-1">Setoran bisa dilakukan langsung dari halaman ini.</p>
                                </div>
                                <button type="button" onclick="openPaymentModal()" class="inline-flex items-center justify-center gap-2 btn-hanania-gold px-5 py-3 rounded-xl text-[12px] font-black shadow-sm hover:-translate-y-0.5 transition-transform">
                                    <span class="material-symbols-outlined text-[17px]">add_card</span>
                                    Setor Sekarang
                                </button>
                            </div>
                        @endif

                        @if(!$enrollment->final_price)
                            <div class="mt-5 flex gap-3 items-start rounded-2xl bg-hanania-purple-light/30 border border-hanania-gold/20 p-4">
                                <span class="material-symbols-outlined text-[18px] text-hanania-gold shrink-0">info</span>
                                <p class="text-[11px] sm:text-[12px] text-hanania-purple-dark/75 leading-relaxed font-medium"><strong>Catatan:</strong> target di atas merupakan estimasi harga. Harga final menyesuaikan kurs tiket dan hotel mendekati keberangkatan.</p>
                            </div>
                        @elseif($enrollment->final_price > $enrollment->estimated_price_snapshot)
                            <div class="mt-5 flex gap-3 items-start rounded-2xl bg-amber-50 border border-amber-200 p-4">
                                <span class="material-symbols-outlined text-[18px] text-amber-500 shrink-0">warning</span>
                                <p class="text-[11px] sm:text-[12px] text-amber-900 leading-relaxed font-medium"><strong>Penyesuaian Harga:</strong> estimasi awal (Rp {{ number_format($enrollment->estimated_price_snapshot, 0, ',', '.') }}) telah disesuaikan menjadi harga final mengikuti kurs terbaru.</p>
                            </div>
                        @else
                            <div class="mt-5 flex gap-3 items-start rounded-2xl bg-emerald-50 border border-emerald-200 p-4">
                                <span class="material-symbols-outlined text-[18px] text-emerald-500 shrink-0">verified</span>
                                <p class="text-[11px] sm:text-[12px] text-emerald-800 leading-relaxed font-medium">Harga final keberangkatan Anda telah dikunci (Fix). Silakan selesaikan sisa pembayaran.</p>
                            </div>
                        @endif
                    </section>

                    <!-- DEPARTURE -->
                    @php
                        $activeMembership = $enrollment->groupMemberships()->where('status', 'active')->first();
                        $departure = $activeMembership ? $activeMembership->group->departure : null;
                    @endphp

                    @if($departure)
                        <section class="bg-white border border-hanania-purple/10 rounded-[1.9rem] p-6 sm:p-8 soft-card">
                            <div class="flex items-start justify-between gap-4 mb-5">
                                <div>
                                    <p class="text-[8px] uppercase tracking-[.18em] text-hanania-purple font-black">Jadwal Perjalanan</p>
                                    <h2 class="font-heading text-[23px] sm:text-[27px] font-black text-hanania-purple-dark mt-1">Persiapan Keberangkatan</h2>
                                </div>
                                <span class="material-symbols-outlined text-[27px] text-hanania-gold">flight_takeoff</span>
                            </div>

                            <div class="rounded-2xl bg-hanania-purple-light/30 border border-hanania-purple/10 p-4 mb-4">
                                <p class="text-[8px] uppercase tracking-[.17em] text-hanania-purple/55 font-black">Kloter</p>
                                <p class="font-heading text-[17px] font-black text-hanania-purple-dark mt-1">{{ $departure->name }}</p>
                                <p class="text-[11px] text-gray-500 mt-1">{{ \Carbon\Carbon::parse($departure->departure_date)->format('d M Y') }}</p>
                            </div>

                            <div class="grid md:grid-cols-2 gap-3">
                                <div class="rounded-2xl bg-gray-50 border border-gray-100 p-4">
                                    <p class="text-[8px] uppercase tracking-[.16em] text-gray-400 font-black mb-2">Jadwal Manasik</p>
                                    @if($departure->manasik_date)
                                        <p class="text-[11px] sm:text-[12px] font-bold text-hanania-purple-dark flex items-start gap-2">
                                            <span class="material-symbols-outlined text-[15px] text-hanania-gold shrink-0">event</span>
                                            {{ \Carbon\Carbon::parse($departure->manasik_date)->format('l, d F Y - H:i') }} WIB
                                        </p>
                                        <p class="text-[10px] text-gray-500 mt-2 flex items-start gap-2">
                                            <span class="material-symbols-outlined text-[15px] text-hanania-gold shrink-0">location_on</span>
                                            {{ $departure->manasik_location ?? 'Lokasi menyusul' }}
                                        </p>
                                    @else
                                        <p class="text-[11px] text-gray-400 italic">Jadwal manasik belum ditentukan.</p>
                                    @endif
                                </div>

                                <div class="rounded-2xl bg-gray-50 border border-gray-100 p-4 flex flex-col justify-between">
                                    <div>
                                        <p class="text-[8px] uppercase tracking-[.16em] text-gray-400 font-black mb-2">Dokumen Perjalanan</p>
                                        @if($departure->itinerary_file)
                                            <p class="text-[10px] text-gray-500 mb-3">Itinerary keberangkatan tersedia.</p>
                                        @else
                                            <p class="text-[10px] text-gray-400 italic mb-3">Dokumen itinerary sedang dipersiapkan.</p>
                                        @endif
                                    </div>

                                    @if($departure->itinerary_file)
                                        <a href="{{ asset('storage/' . $departure->itinerary_file) }}" target="_blank" class="inline-flex items-center justify-center gap-2 bg-white border border-hanania-purple/10 text-hanania-purple rounded-xl py-3 px-4 text-[11px] font-black hover:bg-hanania-purple-light transition-colors">
                                            <span class="material-symbols-outlined text-[16px] text-hanania-gold">description</span>
                                            Unduh Itinerary
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </section>
                    @endif

                    <!-- TRANSACTION HISTORY -->
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

                    <section class="bg-white border border-hanania-purple/10 rounded-[1.9rem] p-6 sm:p-8 soft-card">
                        <div class="flex items-end justify-between gap-4 mb-5">
                            <div>
                                <p class="text-[8px] uppercase tracking-[.18em] text-hanania-purple font-black">Aktivitas Keuangan</p>
                                <h2 class="font-heading text-[23px] sm:text-[27px] font-black text-hanania-purple-dark mt-1">Riwayat Transaksi</h2>
                            </div>
                            <span class="material-symbols-outlined text-[26px] text-hanania-gold">history</span>
                        </div>

                        <div class="space-y-3">
                            @foreach($pendingMidtrans as $trx)
                                <div class="rounded-2xl border border-amber-200 bg-amber-50/60 p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                    <div>
                                        <p class="text-[12px] font-black text-hanania-purple-dark">{{ ucfirst(str_replace('_', ' ', $trx->type)) }}</p>
                                        <p class="text-[9px] text-gray-500 mt-1">{{ $trx->created_at->format('d M Y, H:i') }}</p>
                                    </div>
                                    <div class="sm:text-right">
                                        <p class="font-heading text-[16px] font-black text-hanania-purple">Rp {{ number_format($trx->amount, 0, ',', '.') }}</p>
                                        <span class="inline-flex mt-1.5 text-[8px] uppercase tracking-wider font-black text-amber-700 bg-amber-100 px-2.5 py-1 rounded-full">Menunggu Bayar</span>
                                    </div>
                                </div>
                            @endforeach

                            @foreach($pendingManual as $trx)
                                <div class="rounded-2xl border border-amber-200 bg-amber-50/60 p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                    <div>
                                        <p class="text-[12px] font-black text-hanania-purple-dark">{{ ucfirst(str_replace('_', ' ', $trx->type)) }}</p>
                                        <p class="text-[9px] text-gray-500 mt-1">{{ $trx->created_at->format('d M Y, H:i') }}</p>
                                    </div>
                                    <div class="sm:text-right">
                                        <p class="font-heading text-[16px] font-black text-hanania-purple">Rp {{ number_format($trx->amount, 0, ',', '.') }}</p>
                                        <span class="inline-flex mt-1.5 text-[8px] uppercase tracking-wider font-black text-amber-700 bg-amber-100 px-2.5 py-1 rounded-full">Verifikasi Admin</span>
                                    </div>
                                </div>
                            @endforeach

                            @forelse($historyTransactions as $trx)
                                <div class="rounded-2xl border border-hanania-purple/10 bg-hanania-purple-light/15 p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 group">
                                    <div>
                                        <p class="text-[12px] font-black text-hanania-purple-dark">{{ ucfirst(str_replace('_', ' ', $trx->type)) }}</p>
                                        <p class="text-[9px] text-gray-500 mt-1">{{ $trx->created_at->format('d M Y, H:i') }}</p>
                                    </div>
                                    <div class="flex items-center justify-between sm:justify-end gap-3">
                                        <div class="text-left sm:text-right">
                                            <p class="font-heading text-[16px] font-black text-hanania-purple">Rp {{ number_format($trx->amount, 0, ',', '.') }}</p>
                                            <span class="inline-flex mt-1.5 text-[8px] uppercase tracking-wider font-black px-2.5 py-1 rounded-full {{ $trx->status === 'verified' ? 'bg-emerald-50 text-emerald-600 border border-emerald-200' : 'bg-red-50 text-red-600 border border-red-200' }}">
                                                {{ $trx->status }}
                                            </span>
                                        </div>
                                        @if($trx->status === 'verified')
                                            <a href="{{ route('customer.receipts.download', $trx->id) }}" target="_blank" class="w-9 h-9 rounded-full bg-white border border-hanania-purple/10 text-hanania-purple flex items-center justify-center hover:bg-hanania-purple hover:text-white transition-colors" title="Unduh Kuitansi">
                                                <span class="material-symbols-outlined text-[17px]">download</span>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                @if($pendingMidtrans->isEmpty() && $pendingManual->isEmpty())
                                    <div class="rounded-2xl bg-hanania-purple-light/25 border border-dashed border-hanania-purple/20 p-8 text-center">
                                        <span class="material-symbols-outlined text-hanania-gold/60 text-[36px] mb-2">receipt_long</span>
                                        <p class="text-[11px] font-medium text-gray-500">Belum ada riwayat transaksi yang tercatat.</p>
                                    </div>
                                @endif
                            @endforelse
                        </div>
                    </section>
                </div>

                <!-- RIGHT COLUMN -->
                <aside class="xl:sticky xl:top-24 space-y-5">
                    @php
                        $totalWajib = 5;
                        $dokumenTerkumpul = $enrollment->documents ? $enrollment->documents->count() : 0;
                        $persenDokumen = $totalWajib > 0 ? min(100, round(($dokumenTerkumpul / $totalWajib) * 100)) : 0;
                    @endphp

                    <!-- DOCUMENTS -->
                    <section class="bg-white border border-hanania-purple/10 rounded-[1.8rem] p-5 sm:p-6 soft-card">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="w-10 h-10 rounded-xl bg-hanania-purple-light/50 flex items-center justify-center text-hanania-purple border border-hanania-purple/10">
                                <span class="material-symbols-outlined text-[20px] [font-variation-settings:'FILL'_1]">folder_special</span>
                            </div>
                            <div>
                                <p class="text-[8px] uppercase tracking-[.16em] text-hanania-purple font-black">Administrasi</p>
                                <h3 class="font-heading text-[18px] font-black text-hanania-purple-dark mt-0.5">Dokumen Pendaftar</h3>
                            </div>
                        </div>

                        <div class="rounded-2xl bg-hanania-purple-light/30 border border-hanania-purple/10 p-4">
                            <div class="flex justify-between items-center text-[9px] font-black uppercase tracking-[.14em] mb-3">
                                <span class="text-gray-400">Progres Dokumen</span>
                                <span class="text-hanania-purple">{{ $dokumenTerkumpul }} / {{ $totalWajib }}</span>
                            </div>
                            <div class="w-full bg-white border border-hanania-gold/20 rounded-full h-2.5 overflow-hidden">
                                <div class="bg-hanania-gold h-full rounded-full transition-all duration-700" style="width: {{ $persenDokumen }}%"></div>
                            </div>
                        </div>

                        <a href="{{ route('customer.documents.index', $enrollment->id) }}" class="mt-4 inline-flex items-center justify-center gap-2 w-full py-3 rounded-xl bg-white border border-hanania-gold/30 text-hanania-purple text-[11px] font-black hover:bg-hanania-purple-light transition-colors">
                            Kelola Dokumen
                            <span class="material-symbols-outlined text-[15px]">arrow_forward</span>
                        </a>
                    </section>

                    <!-- TESTIMONIAL -->
                    @if($enrollment->status === 'completed')
                        @php $hasTestimonial = \App\Models\Testimonial::where('user_id', auth()->id())->exists(); @endphp
                        @if(!$hasTestimonial)
                            <section class="bg-white border border-hanania-purple/10 rounded-[1.8rem] p-5 sm:p-6 soft-card">
                                <div class="mb-4">
                                    <p class="text-[8px] uppercase tracking-[.16em] text-hanania-purple font-black">Setelah Perjalanan</p>
                                    <h3 class="font-heading text-[18px] font-black text-hanania-purple-dark mt-1">Bagikan Pengalaman</h3>
                                </div>
                                <form action="{{ route('customer.testimonials.store') }}" method="POST" class="space-y-3.5">
                                    @csrf
                                    <select name="rating" required class="w-full px-4 py-3 bg-hanania-purple-light/30 border border-hanania-gold/30 rounded-xl text-[12px] font-bold text-hanania-purple-dark outline-none focus:ring-2 focus:ring-hanania-gold/50 appearance-none">
                                        <option value="5">⭐⭐⭐⭐⭐ (Sangat Puas)</option>
                                        <option value="4">⭐⭐⭐⭐ (Puas)</option>
                                        <option value="3">⭐⭐⭐ (Cukup)</option>
                                    </select>
                                    <textarea name="content" rows="4" required placeholder="Ceritakan kesan perjalanan ibadah Anda..." class="w-full px-4 py-3 bg-hanania-purple-light/30 border border-hanania-gold/30 rounded-xl text-[12px] font-medium text-hanania-purple-dark outline-none focus:ring-2 focus:ring-hanania-gold/50"></textarea>
                                    <button type="submit" class="w-full btn-hanania-gold py-3 rounded-xl font-black text-[11px]">Kirim Ulasan</button>
                                </form>
                            </section>
                        @else
                            <div class="bg-emerald-50 border border-emerald-200 rounded-[1.6rem] p-5 text-center">
                                <span class="material-symbols-outlined text-[29px] text-emerald-500 mb-1 [font-variation-settings:'FILL'_1]">volunteer_activism</span>
                                <p class="text-[11px] font-black text-emerald-800">Terima kasih atas ulasan Anda.</p>
                            </div>
                        @endif
                    @endif

                    <!-- CANCEL / TARIK DANA -->
                    @php
                        // Cek apakah ada pengajuan pembatalan/refund yang sedang aktif berjalan
                        $activeRequest = $enrollment->cancellationRequests()
                            ->whereIn('status', ['requested', 'under_review', 'refund_processing'])
                            ->first();
                    @endphp

                    @if($enrollment->status == 'completed')
                        <!-- Jika sudah selesai, tombol sembunyikan -->
                    @elseif($enrollment->status == 'cancelled')
                        <!-- Jika sudah dibatalkan total, sembunyikan tombol -->
                    @elseif($activeRequest)
                        <!-- Jika sedang ada pengajuan aktif, ubah jadi badge info -->
                        <div class="flex items-center justify-center gap-1.5 text-amber-600 font-bold text-[11px] bg-amber-50 px-4 py-2.5 rounded-full border border-amber-200 w-full shadow-sm">
                            <span class="material-symbols-outlined text-[16px]">hourglass_top</span>
                            Pengajuan Diproses Admin
                        </div>
                    @else
                        <!-- Jika belum ada pengajuan, tampilkan tombol utama -->
                        <a href="{{ route('customer.cancellations.create', $enrollment->id) }}" class="flex items-center justify-center gap-1.5 text-red-500 hover:text-white font-bold text-[11px] hover:bg-red-500 px-4 py-2.5 rounded-full transition-colors border border-red-100 bg-white w-full shadow-sm">
                            <span class="material-symbols-outlined text-[16px]">cancel</span>
                            Batalkan / Tarik Dana
                        </a>
                    @endif
                </aside>
            </div>
        </div>
    </div>

    <!-- PAYMENT MODAL -->
    @if($progress['sisa_tagihan'] > 0 && $enrollment->status !== 'completed')
        @php
            $bankName = \App\Models\AppInformation::getValue('bank_name', 'Nama Bank');
            $bankAccount = \App\Models\AppInformation::getValue('bank_account_number', 'xxxx-xxxx-xxxx');
            $bankAccountName = \App\Models\AppInformation::getValue('bank_account_name', 'Nama Pemilik Rekening');
        @endphp

        <div id="paymentModalOverlay" class="fixed inset-0 z-[9999] bg-hanania-purple-dark/60 backdrop-blur-md opacity-0 pointer-events-none transition-opacity duration-300 flex items-center justify-center p-4" onclick="closePaymentModal()">
            <div id="paymentModalCard" class="bg-white rounded-[1.9rem] w-full max-w-md p-5 sm:p-7 shadow-2xl relative border border-hanania-gold/30 transform scale-95 transition-transform duration-300 max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
                <button type="button" onclick="closePaymentModal()" class="absolute top-5 right-5 w-8 h-8 flex items-center justify-center bg-gray-50 rounded-full text-gray-400 hover:text-red-500 transition-colors z-10">
                    <span class="material-symbols-outlined text-[19px]">close</span>
                </button>

                <div class="w-11 h-11 bg-hanania-purple-light/50 rounded-xl flex items-center justify-center text-hanania-purple mb-4 border border-hanania-purple/10">
                    <span class="material-symbols-outlined text-[22px] [font-variation-settings:'FILL'_1]">account_balance_wallet</span>
                </div>

                <p class="text-[8px] uppercase tracking-[.17em] text-hanania-purple font-black">Setoran Tabungan</p>
                <h3 class="font-heading font-black text-[23px] text-hanania-purple-dark mt-1">Lanjutkan Pembayaran</h3>
                <p class="text-[11px] text-gray-500 mt-2">Sisa tagihan: <strong class="text-hanania-gold">Rp {{ number_format($progress['sisa_tagihan'], 0, ',', '.') }}</strong></p>

                <form action="{{ route('customer.payments.store', $enrollment->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5 mt-6">
                    @csrf

                    <div>
                        <label class="block text-[11px] font-black text-hanania-purple-dark mb-2">Nominal Setoran</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-hanania-purple-dark/50 font-black text-[14px]">Rp</div>
                            <input type="number" name="amount" required min="{{ $paymentRules['min_amount'] ?? 500000 }}" max="{{ $progress['sisa_tagihan'] }}"
                                   class="w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl text-[15px] font-black text-gray-900 focus:bg-white focus:border-hanania-purple focus:ring-2 focus:ring-hanania-purple/20 outline-none transition-all"
                                   placeholder="{{ number_format($paymentRules['min_amount'] ?? 500000, 0, ',', '.') }}">
                        </div>
                        <p class="text-[9px] text-gray-400 mt-1.5 flex items-center gap-1">
                            <span class="material-symbols-outlined text-[13px] text-hanania-gold">info</span>
                            {{ $paymentRules['label_saran'] ?? 'Minimal setoran Rp 500.000' }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-[11px] font-black text-hanania-purple-dark mb-2">Metode Pembayaran</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <label class="relative cursor-pointer">
                                <input type="radio" name="payment_method" value="midtrans" class="peer sr-only" required checked onchange="toggleManualUpload()">
                                <div class="p-3.5 rounded-xl border-2 border-gray-200 bg-white peer-checked:border-hanania-purple peer-checked:bg-hanania-purple-light/10 transition-all">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-9 h-9 bg-emerald-50 rounded-full flex items-center justify-center text-emerald-500 shrink-0">
                                            <span class="material-symbols-outlined text-[18px] [font-variation-settings:'FILL'_1]">bolt</span>
                                        </div>
                                        <div>
                                            <p class="text-[12px] font-black text-hanania-purple-dark">Otomatis</p>
                                            <p class="text-[9px] text-gray-500 font-medium mt-0.5">VA, QRIS, E-Wallet</p>
                                        </div>
                                    </div>
                                </div>
                            </label>

                            <label class="relative cursor-pointer">
                                <input type="radio" name="payment_method" value="manual_transfer" class="peer sr-only" required onchange="toggleManualUpload()">
                                <div class="p-3.5 rounded-xl border-2 border-gray-200 bg-white peer-checked:border-hanania-purple peer-checked:bg-hanania-purple-light/10 transition-all">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-9 h-9 bg-blue-50 rounded-full flex items-center justify-center text-blue-500 shrink-0">
                                            <span class="material-symbols-outlined text-[18px] [font-variation-settings:'FILL'_1]">account_balance</span>
                                        </div>
                                        <div>
                                            <p class="text-[12px] font-black text-hanania-purple-dark">Manual</p>
                                            <p class="text-[9px] text-gray-500 font-medium mt-0.5">Upload Bukti TF</p>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div id="manualUploadArea" class="hidden border-t border-gray-100 pt-4">
                        <div class="bg-hanania-purple-light/30 border border-hanania-purple/20 rounded-xl p-4 mb-4">
                            <p class="text-[9px] font-black text-hanania-purple-dark uppercase tracking-[.16em] mb-3 flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[15px] text-hanania-gold">account_balance</span>
                                Tujuan Transfer
                            </p>
                            <p class="text-[13px] font-black text-gray-800">{{ $bankName }}</p>
                            <p class="text-[10px] text-gray-500 mt-0.5">A.N: <span class="font-bold text-gray-700">{{ $bankAccountName }}</span></p>
                            <div class="bg-white border border-hanania-purple/20 rounded-lg p-2 flex justify-between items-center mt-3">
                                <span class="font-mono text-[12px] sm:text-[13px] font-black text-hanania-purple tracking-wider pl-2" id="rekNumber">{{ $bankAccount }}</span>
                                <button type="button" onclick="copyRekening()" class="text-[10px] font-black text-hanania-purple bg-hanania-purple-light hover:bg-hanania-purple hover:text-white px-2.5 py-1.5 rounded-md transition-all flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[13px]">content_copy</span>
                                    Salin
                                </button>
                            </div>
                        </div>

                        <label class="block text-[11px] font-black text-hanania-purple-dark mb-2">Upload Bukti Transfer</label>
                        <div class="relative border-2 border-dashed border-gray-300 rounded-xl p-4 text-center hover:border-hanania-purple transition-colors bg-gray-50/50">
                            <input type="file" name="proof_file" id="proofFileInput" accept="image/jpeg,image/png,image/jpg,application/pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            <span class="material-symbols-outlined text-gray-400 text-[26px] mb-1">cloud_upload</span>
                            <p class="text-[11px] font-black text-gray-700">Pilih File Bukti TF</p>
                            <p class="text-[9px] text-gray-500 mt-1">JPG, PNG, atau PDF (Maks. 5MB)</p>
                        </div>
                    </div>

                    <button type="submit" class="w-full btn-hanania-gold py-4 rounded-xl text-[13px] font-black shadow-md flex justify-center items-center gap-2 group">
                        Lanjutkan Pembayaran
                        <span class="material-symbols-outlined text-[17px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
                    </button>
                </form>
            </div>
        </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('paymentModalOverlay');
            if (modal) document.body.appendChild(modal);
        });

        function openPaymentModal() {
            const overlay = document.getElementById('paymentModalOverlay');
            const card = document.getElementById('paymentModalCard');
            if (!overlay || !card) return;

            overlay.classList.remove('opacity-0', 'pointer-events-none');
            overlay.classList.add('opacity-100');
            card.classList.remove('scale-95');
            card.classList.add('scale-100');
            document.body.classList.add('overflow-hidden');
        }

        function closePaymentModal() {
            const overlay = document.getElementById('paymentModalOverlay');
            const card = document.getElementById('paymentModalCard');
            if (!overlay || !card) return;

            overlay.classList.remove('opacity-100');
            overlay.classList.add('opacity-0', 'pointer-events-none');
            card.classList.remove('scale-100');
            card.classList.add('scale-95');
            document.body.classList.remove('overflow-hidden');
        }

        function toggleManualUpload() {
            const isManual = document.querySelector('input[name="payment_method"][value="manual_transfer"]').checked;
            const uploadArea = document.getElementById('manualUploadArea');
            const fileInput = document.getElementById('proofFileInput');
            if (!uploadArea || !fileInput) return;

            if (isManual) {
                uploadArea.classList.remove('hidden');
                fileInput.setAttribute('required', 'required');
            } else {
                uploadArea.classList.add('hidden');
                fileInput.removeAttribute('required');
                fileInput.value = '';
            }
        }

        function copyRekening() {
            const rekNumber = document.getElementById('rekNumber')?.innerText;
            if (!rekNumber) return;

            navigator.clipboard.writeText(rekNumber).then(() => {
                alert('Nomor Rekening berhasil disalin: ' + rekNumber);
            }).catch(err => {
                console.error('Gagal menyalin text', err);
            });
        }
    </script>
@endsection
