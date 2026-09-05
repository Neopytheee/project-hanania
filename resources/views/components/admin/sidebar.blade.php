<!-- SIDEBAR ADMIN (Slate Theme - No Scrollbar) -->
<aside id="admin-sidebar" class="fixed top-0 left-0 h-screen w-[280px] bg-white border-r border-slate-200 z-50 flex flex-col shadow-sm transition-transform duration-300 -translate-x-full lg:translate-x-0">
    
    <!-- Logo & Header -->
    <div class="h-16 flex items-center gap-3 px-6 border-b border-slate-200 shrink-0">
        <div class="w-9 h-9 bg-hanania-purple rounded-xl flex items-center justify-center text-hanania-gold shadow-sm border border-hanania-purple-dark/20">
            <span class="material-symbols-outlined text-[20px] [font-variation-settings:'FILL'_1]">shield_person</span>
        </div>
        <div>
            <h1 class="text-[17px] font-extrabold text-slate-900 tracking-tight leading-none">
                {{ \App\Models\AppInformation::getValue('company_name') ?? 'Hanania' }}
            </h1>
            <p class="text-[9px] font-bold text-hanania-gold tracking-[0.15em] uppercase mt-0.5">Admin Panel</p>
        </div>
    </div>

    <nav class="flex-1 overflow-y-auto px-4 py-6 flex flex-col gap-6 [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none]">
        
        <!-- 1. MAIN MENU (Semua Role Bisa Lihat) -->
        <div>
            <p class="px-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-2">Main Menu</p>
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all group {{ request()->routeIs('admin.dashboard') ? 'bg-hanania-purple/10 text-hanania-purple font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                <span class="material-symbols-outlined {{ request()->routeIs('admin.dashboard') ? '[font-variation-settings:\'FILL\'_1]' : 'group-hover:scale-110 transition-transform' }}">dashboard</span>
                <span class="text-[13px]">Dashboard</span>
            </a>
        </div>

        <!-- ========================================== -->
        <!-- AREA ADMIN OPERASIONAL & SUPER ADMIN -->
        <!-- ========================================== -->
        @hasanyrole('Super Admin|Admin Operasional')
        
        <!-- 2. OPERASIONAL JAMAAH -->
        <div>
            <p class="px-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-2">Operasional Jamaah</p>
            
            <a href="{{ route('admin.enrollments.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all group {{ request()->routeIs('admin.enrollments.*') ? 'bg-hanania-purple/10 text-hanania-purple font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                <span class="material-symbols-outlined {{ request()->routeIs('admin.enrollments.*') ? '[font-variation-settings:\'FILL\'_1]' : 'group-hover:scale-110 transition-transform' }}">menu_book</span>
                <span class="text-[13px]">Data Pendaftaran</span>
            </a>

            <a href="{{ route('admin.documents.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all group {{ request()->routeIs('admin.documents.*') ? 'bg-hanania-purple/10 text-hanania-purple font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                <span class="material-symbols-outlined {{ request()->routeIs('admin.documents.*') ? '[font-variation-settings:\'FILL\'_1]' : 'group-hover:scale-110 transition-transform' }}">fact_check</span>
                <span class="text-[13px]">Verifikasi Dokumen</span>
            </a>

            <a href="{{ route('admin.testimonials.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all group {{ request()->routeIs('admin.testimonials.*') ? 'bg-hanania-purple/10 text-hanania-purple font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                <span class="material-symbols-outlined {{ request()->routeIs('admin.testimonials.*') ? '[font-variation-settings:\'FILL\'_1]' : 'group-hover:scale-110 transition-transform' }}">rate_review</span>
                <span class="text-[13px]">Verifikasi Testimoni</span>
            </a>
        </div>

        <!-- 3. MANAJEMEN PERJALANAN -->
        <div>
            <p class="px-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-2">Manajemen Perjalanan</p>
            
            <a href="{{ route('admin.travel_packages.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all group {{ request()->routeIs('admin.travel_packages.*') ? 'bg-hanania-purple/10 text-hanania-purple font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                <span class="material-symbols-outlined {{ request()->routeIs('admin.travel_packages.*') ? '[font-variation-settings:\'FILL\'_1]' : 'group-hover:scale-110 transition-transform' }}">inventory_2</span>
                <span class="text-[13px]">Paket Travel</span>
            </a>

            <a href="{{ route('admin.groups.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all group {{ request()->routeIs('admin.groups.*') ? 'bg-hanania-purple/10 text-hanania-purple font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                <span class="material-symbols-outlined {{ request()->routeIs('admin.groups.*') ? '[font-variation-settings:\'FILL\'_1]' : 'group-hover:scale-110 transition-transform' }}">groups</span>
                <span class="text-[13px]">Data Rombongan</span>
            </a>

            <a href="{{ route('admin.departures.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all group {{ request()->routeIs('admin.departures.*') ? 'bg-hanania-purple/10 text-hanania-purple font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                <span class="material-symbols-outlined {{ request()->routeIs('admin.departures.*') ? '[font-variation-settings:\'FILL\'_1]' : 'group-hover:scale-110 transition-transform' }}">flight_takeoff</span>
                <span class="text-[13px]">Jadwal Keberangkatan</span>
            </a>
        </div>

        <!-- 4. GALERI -->
        <div>
            <p class="px-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-2">Media</p>
            
            <a href="{{ route('admin.galleries.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all group {{ request()->routeIs('admin.galleries.*') ? 'bg-hanania-purple/10 text-hanania-purple font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                <span class="material-symbols-outlined {{ request()->routeIs('admin.galleries.*') ? '[font-variation-settings:\'FILL\'_1]' : 'group-hover:scale-110 transition-transform' }}">image</span>
                <span class="text-[13px]">Galeri Kenangan</span>
            </a>
        </div>
        
        @endhasanyrole


        <!-- ========================================== -->
        <!-- AREA ADMIN KEUANGAN & SUPER ADMIN -->
        <!-- ========================================== -->
        @hasanyrole('Super Admin|Admin Keuangan')
        
        <!-- 5. LAPORAN & KEUANGAN -->
        <div>
            <p class="px-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-2">Keuangan & Transaksi</p>
            
            <a href="{{ route('admin.payments.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all group {{ request()->routeIs('admin.payments.*') ? 'bg-hanania-purple/10 text-hanania-purple font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                <span class="material-symbols-outlined {{ request()->routeIs('admin.payments.*') ? '[font-variation-settings:\'FILL\'_1]' : 'group-hover:scale-110 transition-transform' }}">receipt_long</span>
                <span class="text-[13px]">Verifikasi Pembayaran</span>
            </a>

            <a href="{{ route('admin.cancellations.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all group {{ request()->routeIs('admin.cancellations.*') ? 'bg-hanania-purple/10 text-hanania-purple font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                <span class="material-symbols-outlined {{ request()->routeIs('admin.cancellations.*') ? '[font-variation-settings:\'FILL\'_1]' : 'group-hover:scale-110 transition-transform' }}">cancel</span>
                <span class="text-[13px]">Pembatalan & Refund</span>
            </a>

            <a href="{{ route('admin.transactions.history') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all group {{ request()->routeIs('admin.transactions.*') ? 'bg-hanania-purple/10 text-hanania-purple font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                <span class="material-symbols-outlined {{ request()->routeIs('admin.transactions.*') ? '[font-variation-settings:\'FILL\'_1]' : 'group-hover:scale-110 transition-transform' }}">account_balance_wallet</span>
                <span class="text-[13px]">Riwayat Transaksi</span>
            </a>
        </div>
        
        @endhasanyrole


        <!-- ========================================== -->
        <!-- AREA KHUSUS DEWA (SUPER ADMIN) -->
        <!-- ========================================== -->
        @hasrole('Super Admin')
        
        <!-- 6. KARYAWAN & SISTEM -->
        <div>
            <p class="px-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-2">Administrator</p>
            
            <a href="{{ route('admin.management.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all group {{ request()->routeIs('admin.management.*') ? 'bg-hanania-purple/10 text-hanania-purple font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                <span class="material-symbols-outlined {{ request()->routeIs('admin.management.*') ? '[font-variation-settings:\'FILL\'_1]' : 'group-hover:scale-110 transition-transform' }}">admin_panel_settings</span>
                <span class="text-[13px]">Manajemen Staff</span>
            </a>
            
            <a href="{{ route('admin.informations.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all group {{ request()->routeIs('admin.informations.*') ? 'bg-hanania-purple/10 text-hanania-purple font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                <span class="material-symbols-outlined {{ request()->routeIs('admin.informations.*') ? '[font-variation-settings:\'FILL\'_1]' : 'group-hover:scale-110 transition-transform' }}">settings_suggest</span>
                <span class="text-[13px]">Pengaturan Aplikasi</span>
            </a>
        </div>
        
        @endhasrole

    </nav>

    <!-- Logout Button -->
    <div class="px-4 py-4 border-t border-slate-200 bg-slate-50/50">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-red-500 hover:bg-red-50 hover:text-red-600 font-bold active:scale-95 transition-all group">
                <span class="material-symbols-outlined group-hover:-translate-x-1 transition-transform">logout</span>
                <span class="text-[13px]">Keluar</span>
            </button>
        </form>
    </div>
</aside>