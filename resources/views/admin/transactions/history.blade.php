@extends('layouts.admin.app')

@section('header_title', 'Riwayat Transaksi')

@section('content')
<div class="max-w-7xl mx-auto animate-fade-in-up pb-12">
    
    <!-- HEADER -->
    <div class="mb-6">
        <h2 class="text-[24px] font-black text-slate-900 tracking-tight flex items-center gap-2">
            <span class="material-symbols-outlined text-hanania-purple text-[28px]">account_balance_wallet</span> Riwayat Transaksi & Pemasukan
        </h2>
        <p class="text-[13px] text-slate-500 font-medium mt-1">Rekap seluruh arus kas masuk dari setoran jamaah.</p>
    </div>

    <!-- SUMMARY CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Pemasukan Sukses -->
        <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 p-5 rounded-2xl text-white shadow-md relative overflow-hidden">
            <span class="material-symbols-outlined absolute -right-4 -bottom-4 text-[80px] text-white/10">payments</span>
            <p class="text-[11px] font-bold uppercase tracking-widest text-emerald-100 mb-1">Total Pemasukan (Sukses)</p>
            <h3 class="text-[22px] font-black">Rp {{ number_format($metrics['total_income'], 0, ',', '.') }}</h3>
            <p class="text-[11px] mt-2 bg-emerald-700/30 inline-block px-2 py-1 rounded-lg">{{ $metrics['count_success'] }} Transaksi</p>
        </div>

        <!-- Menunggu Verifikasi -->
        <div class="bg-white border border-slate-200 p-5 rounded-2xl shadow-sm relative overflow-hidden group hover:border-amber-300 transition-colors">
            <span class="material-symbols-outlined absolute -right-4 -bottom-4 text-[80px] text-slate-50 group-hover:text-amber-50 transition-colors">hourglass_empty</span>
            <p class="text-[11px] font-bold uppercase tracking-widest text-slate-500 mb-1">Total Pending</p>
            <h3 class="text-[22px] font-black text-slate-800">Rp {{ number_format($metrics['total_pending'], 0, ',', '.') }}</h3>
            <p class="text-[11px] mt-2 font-bold text-amber-500 flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">warning</span> Butuh di-ACC</p>
        </div>
    </div>

    <!-- FILTER SECTION -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm mb-6 flex flex-col lg:flex-row justify-between gap-4">
        
        <!-- Form Filter Utama -->
        <form action="{{ route('admin.transactions.history') }}" method="GET" class="flex flex-col sm:flex-row items-end gap-3 w-full lg:w-auto">
            <!-- Kolom Search -->
            <div class="w-full sm:w-auto flex-1 sm:min-w-[200px]">
                <label class="block text-[11px] font-bold text-slate-500 uppercase mb-2">Cari Nama / No. Resi</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-2.5 text-slate-400 text-[18px]">search</span>
                    <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Ketik pencarian..." class="w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-[13px] font-bold text-slate-800 outline-none focus:border-hanania-purple">
                </div>
            </div>
            <div class="w-full sm:w-auto flex-1">
                <label class="block text-[11px] font-bold text-slate-500 uppercase mb-2">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ $filters['start_date'] ?? '' }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-[13px] font-bold text-slate-800 outline-none focus:border-hanania-purple">
            </div>
            <div class="w-full sm:w-auto flex-1">
                <label class="block text-[11px] font-bold text-slate-500 uppercase mb-2">Tanggal Akhir</label>
                <input type="date" name="end_date" value="{{ $filters['end_date'] ?? '' }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-[13px] font-bold text-slate-800 outline-none focus:border-hanania-purple">
            </div>
            <div class="w-full sm:w-auto flex-1">
                <label class="block text-[11px] font-bold text-slate-500 uppercase mb-2">Status</label>
                <select name="status" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-[13px] font-bold text-slate-800 outline-none focus:border-hanania-purple">
                    <option value="all" {{ ($filters['status'] ?? 'all') === 'all' ? 'selected' : '' }}>Semua</option>
                    <option value="verified" {{ ($filters['status'] ?? '') === 'verified' ? 'selected' : '' }}>Sukses</option>
                    <option value="pending" {{ ($filters['status'] ?? '') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="rejected" {{ ($filters['status'] ?? '') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <button type="submit" class="w-full sm:w-auto btn-admin-primary px-5 py-2.5 rounded-xl shadow-sm text-[13px] flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-[18px]">search</span> Cari
            </button>
            <a href="{{ route('admin.transactions.history') }}" class="w-full sm:w-auto bg-slate-100 hover:bg-slate-200 text-slate-600 px-4 py-2.5 rounded-xl font-bold text-[13px] transition-colors text-center" title="Reset Filter">
                <span class="material-symbols-outlined text-[18px] mt-0.5">refresh</span>
            </a>
        </form>

        <!-- 💡 TOMBOL CETAK LAPORAN 💡 -->
        <!-- Membawa parameter URL yang sedang aktif agar data yang dicetak = data yang difilter -->
        <a href="{{ route('admin.transactions.print', request()->query()) }}" target="_blank" class="w-full lg:w-auto bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-bold text-[13px] transition-colors shadow-sm flex items-center justify-center gap-2 h-[42px] self-end">
            <span class="material-symbols-outlined text-[18px]">print</span> Cetak Laporan
        </a>
    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto no-scrollbar">
            <table class="w-full text-left text-[13px]">
                <thead class="bg-slate-50 border-b border-slate-200 text-[11px] uppercase tracking-wider text-slate-500 font-extrabold">
                    <tr>
                        <th class="py-4 px-6">Tgl & Waktu</th>
                        <th class="py-4 px-6">Jamaah / Referensi</th>
                        <th class="py-4 px-6">Nominal</th>
                        <th class="py-4 px-6">Metode</th>
                        <th class="py-4 px-6 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($transactions as $trx)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-4 px-6 whitespace-nowrap">
                                <p class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($trx->created_at)->format('d M Y') }}</p>
                                <p class="text-[11px] text-slate-500">{{ \Carbon\Carbon::parse($trx->created_at)->format('H:i') }} WIB</p>
                            </td>
                            <td class="py-4 px-6">
                                <!-- Asumsi relasi payment -> enrollment -> user/passenger_name -->
                                <p class="font-bold text-slate-800">{{ $trx->paymentPlan->enrollment->passenger_name ?? 'Fulan' }}</p>
                                <p class="text-[11px] text-slate-500">{{ $trx->payment_method ?? 'Transfer Bank' }}</p>
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap">
                                <p class="font-black text-emerald-600">Rp {{ number_format($trx->amount, 0, ',', '.') }}</p>
                            </td>
                            <td class="py-4 px-6">
                                <span class="bg-slate-100 text-slate-600 px-2.5 py-1 rounded-md text-[11px] font-bold border border-slate-200">
                                    {{ $trx->bank_name ?? \App\Models\AppInformation::getValue('bank_name') }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                @if($trx->status === 'verified')
                                    <span class="inline-flex text-[10px] bg-emerald-50 text-emerald-600 border border-emerald-200 font-black px-2 py-1 rounded uppercase tracking-widest">Sukses</span>
                                @elseif($trx->status === 'pending')
                                    <span class="inline-flex text-[10px] bg-amber-50 text-amber-600 border border-amber-200 font-black px-2 py-1 rounded uppercase tracking-widest">Pending</span>
                                @else
                                    <span class="inline-flex text-[10px] bg-rose-50 text-rose-600 border border-rose-200 font-black px-2 py-1 rounded uppercase tracking-widest">Gagal</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <span class="material-symbols-outlined text-[48px] text-slate-300 mb-2">receipt_long</span>
                                    <p class="text-[13px] font-bold text-slate-500">Belum ada transaksi ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div class="p-4 border-t border-slate-100 bg-slate-50">
            {{ $transactions->withQueryString()->links() }}
        </div>
    </div>

</div>
@endsection@extends('layouts.admin.app')

@section('header_title', 'Riwayat Transaksi')

@section('content')
<div class="max-w-7xl mx-auto animate-fade-in-up pb-12">
    
    <!-- HEADER -->
    <div class="mb-6">
        <h2 class="text-[24px] font-black text-slate-900 tracking-tight flex items-center gap-2">
            <span class="material-symbols-outlined text-hanania-purple text-[28px]">account_balance_wallet</span> Riwayat Transaksi & Pemasukan
        </h2>
        <p class="text-[13px] text-slate-500 font-medium mt-1">Rekap seluruh arus kas masuk dari setoran jamaah.</p>
    </div>

    <!-- SUMMARY CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Pemasukan Sukses -->
        <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 p-5 rounded-2xl text-white shadow-md relative overflow-hidden">
            <span class="material-symbols-outlined absolute -right-4 -bottom-4 text-[80px] text-white/10">payments</span>
            <p class="text-[11px] font-bold uppercase tracking-widest text-emerald-100 mb-1">Total Pemasukan (Sukses)</p>
            <h3 class="text-[22px] font-black">Rp {{ number_format($metrics['total_income'], 0, ',', '.') }}</h3>
            <p class="text-[11px] mt-2 bg-emerald-700/30 inline-block px-2 py-1 rounded-lg">{{ $metrics['count_success'] }} Transaksi</p>
        </div>

        <!-- Menunggu Verifikasi -->
        <div class="bg-white border border-slate-200 p-5 rounded-2xl shadow-sm relative overflow-hidden group hover:border-amber-300 transition-colors">
            <span class="material-symbols-outlined absolute -right-4 -bottom-4 text-[80px] text-slate-50 group-hover:text-amber-50 transition-colors">hourglass_empty</span>
            <p class="text-[11px] font-bold uppercase tracking-widest text-slate-500 mb-1">Total Pending</p>
            <h3 class="text-[22px] font-black text-slate-800">Rp {{ number_format($metrics['total_pending'], 0, ',', '.') }}</h3>
            <p class="text-[11px] mt-2 font-bold text-amber-500 flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">warning</span> Butuh di-ACC</p>
        </div>
    </div>

    <!-- FILTER SECTION -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm mb-6">
        <form action="{{ route('admin.transactions.history') }}" method="GET" class="flex flex-col sm:flex-row items-end gap-4">
            <div class="w-full sm:w-auto flex-1">
                <label class="block text-[11px] font-bold text-slate-500 uppercase mb-2">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ $filters['start_date'] }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-[13px] font-bold text-slate-800 outline-none focus:border-hanania-purple">
            </div>
            <div class="w-full sm:w-auto flex-1">
                <label class="block text-[11px] font-bold text-slate-500 uppercase mb-2">Tanggal Akhir</label>
                <input type="date" name="end_date" value="{{ $filters['end_date'] }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-[13px] font-bold text-slate-800 outline-none focus:border-hanania-purple">
            </div>
            <div class="w-full sm:w-auto flex-1">
                <label class="block text-[11px] font-bold text-slate-500 uppercase mb-2">Status</label>
                <select name="status" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-[13px] font-bold text-slate-800 outline-none focus:border-hanania-purple">
                    <option value="all" {{ $filters['status'] === 'all' ? 'selected' : '' }}>Semua Status</option>
                    <option value="verified" {{ $filters['status'] === 'verified' ? 'selected' : '' }}>Sukses / Verified</option>
                    <option value="pending" {{ $filters['status'] === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="rejected" {{ $filters['status'] === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <button type="submit" class="w-full sm:w-auto btn-admin-primary px-6 py-2.5 rounded-xl shadow-sm text-[13px] flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-[18px]">filter_list</span> Terapkan
            </button>
            <a href="{{ route('admin.transactions.history') }}" class="w-full sm:w-auto bg-slate-100 hover:bg-slate-200 text-slate-600 px-4 py-2.5 rounded-xl font-bold text-[13px] transition-colors text-center">Reset</a>
        </form>
    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto no-scrollbar">
            <table class="w-full text-left text-[13px]">
                <thead class="bg-slate-50 border-b border-slate-200 text-[11px] uppercase tracking-wider text-slate-500 font-extrabold">
                    <tr>
                        <th class="py-4 px-6">Tgl & Waktu</th>
                        <th class="py-4 px-6">Jamaah / Referensi</th>
                        <th class="py-4 px-6">Nominal</th>
                        <th class="py-4 px-6">Metode</th>
                        <th class="py-4 px-6 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($transactions as $trx)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-4 px-6 whitespace-nowrap">
                                <p class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($trx->created_at)->format('d M Y') }}</p>
                                <p class="text-[11px] text-slate-500">{{ \Carbon\Carbon::parse($trx->created_at)->format('H:i') }} WIB</p>
                            </td>
                            <td class="py-4 px-6">
                                <!-- Asumsi relasi payment -> enrollment -> user/passenger_name -->
                                <p class="font-bold text-slate-800">{{ $trx->enrollment->passenger_name ?? 'Fulan' }}</p>
                                <p class="text-[11px] text-slate-500">{{ $trx->payment_method ?? 'Transfer Bank' }}</p>
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap">
                                <p class="font-black text-emerald-600">Rp {{ number_format($trx->amount, 0, ',', '.') }}</p>
                            </td>
                            <td class="py-4 px-6">
                                <span class="bg-slate-100 text-slate-600 px-2.5 py-1 rounded-md text-[11px] font-bold border border-slate-200">
                                    {{ $trx->bank_name ?? 'BSI' }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                @if($trx->status === 'verified')
                                    <span class="inline-flex text-[10px] bg-emerald-50 text-emerald-600 border border-emerald-200 font-black px-2 py-1 rounded uppercase tracking-widest">Sukses</span>
                                @elseif($trx->status === 'pending')
                                    <span class="inline-flex text-[10px] bg-amber-50 text-amber-600 border border-amber-200 font-black px-2 py-1 rounded uppercase tracking-widest">Pending</span>
                                @else
                                    <span class="inline-flex text-[10px] bg-rose-50 text-rose-600 border border-rose-200 font-black px-2 py-1 rounded uppercase tracking-widest">Gagal</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <span class="material-symbols-outlined text-[48px] text-slate-300 mb-2">receipt_long</span>
                                    <p class="text-[13px] font-bold text-slate-500">Belum ada transaksi ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div class="p-4 border-t border-slate-100 bg-slate-50">
            {{ $transactions->withQueryString()->links() }}
        </div>
    </div>

</div>
@endsection