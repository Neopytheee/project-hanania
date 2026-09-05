<!-- OVERLAY GELAP -->
<div id="sidebar-overlay" onclick="toggleSidebar()" class="fixed inset-0 bg-hanania-purple-dark/30 backdrop-blur-sm z-[60] hidden transition-all duration-300 opacity-0 cursor-pointer lg:hidden"></div>

<!-- SIDEBAR MUNCUL DARI KANAN (Border kiri diubah jadi purple/10) -->
<aside id="sidebar" class="fixed top-0 right-0 h-screen w-[260px] sm:w-[280px] bg-white border-l border-hanania-purple/10 z-[70] transform translate-x-full transition-transform duration-300 ease-out flex flex-col shadow-[-10px_0_30px_rgba(97,57,143,0.1)] lg:hidden">
    
    <!-- HEADER SIDEBAR -->
    <div class="h-16 lg:h-20 flex items-center justify-between px-5 lg:px-6 border-b border-hanania-purple/10 shrink-0 bg-white">
        <h3 class="font-heading font-black text-hanania-purple text-[16px] tracking-widest uppercase">Menu</h3>
        
        <!-- Tombol Close -->
        <button onclick="toggleSidebar()" class="w-8 h-8 flex items-center justify-center rounded-full bg-white border border-hanania-purple/10 text-hanania-purple-dark hover:text-hanania-purple hover:bg-hanania-purple-light active:scale-90 transition-all shadow-sm">
            <span class="material-symbols-outlined text-[18px]">close</span>
        </button>
    </div>

    <!-- Menu Links Sidebar (Aksen EMAS HANYA dipertahankan untuk menu aktif!) -->
    <nav class="flex-1 overflow-y-auto px-4 py-6 flex flex-col gap-2 custom-scrollbar">
        <!-- 1. Home -->
        <a href="{{ url('/') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 active:scale-[0.98] group {{ request()->is('/') || request()->is('/') ? 'bg-hanania-purple-light text-hanania-purple font-bold shadow-sm border-r-4 border-hanania-gold' : 'text-hanania-purple-dark hover:bg-hanania-purple-light/60 hover:text-hanania-purple' }}">
            <span class="material-symbols-outlined {{ request()->is('/') || request()->is('/') ? '[font-variation-settings:\'FILL\'_1]' : 'group-hover:scale-110 transition-transform duration-300' }}">home</span>
            <span class="text-[14.5px] font-sans">Home</span>
        </a>

        <!-- 2. Dashboard -->
        <a href="{{ route('customer.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 active:scale-[0.98] group {{ request()->routeIs('customer.dashboard') ? 'bg-hanania-purple-light text-hanania-purple font-bold shadow-sm border-r-4 border-hanania-gold' : 'text-hanania-purple-dark hover:bg-hanania-purple-light/60 hover:text-hanania-purple' }}">
            <span class="material-symbols-outlined {{ request()->routeIs('customer.dashboard') ? '[font-variation-settings:\'FILL\'_1]' : 'group-hover:scale-110 transition-transform duration-300' }}">dashboard</span>
            <span class="text-[14.5px] font-sans">Dashboard</span>
        </a>

        <!-- 3. Katalog Paket -->
        <a href="{{ route('packages.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 active:scale-[0.98] group {{ request()->routeIs('packages.*') ? 'bg-hanania-purple-light text-hanania-purple font-bold shadow-sm border-r-4 border-hanania-gold' : 'text-hanania-purple-dark hover:bg-hanania-purple-light/60 hover:text-hanania-purple' }}">
            <span class="material-symbols-outlined {{ request()->routeIs('packages.*') ? '[font-variation-settings:\'FILL\'_1]' : 'group-hover:scale-110 transition-transform duration-300' }}">travel_explore</span>
            <span class="text-[14.5px] font-sans">Paket</span>
        </a>

        <!-- 4. Tabungan Ibadah -->
        <a href="{{ route('customer.enrollments.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 active:scale-[0.98] group {{ request()->routeIs('customer.enrollments.*') || request()->routeIs('customer.payments.*') ? 'bg-hanania-purple-light text-hanania-purple font-bold shadow-sm border-r-4 border-hanania-gold' : 'text-hanania-purple-dark hover:bg-hanania-purple-light/60 hover:text-hanania-purple' }}">
            <span class="material-symbols-outlined {{ request()->routeIs('customer.enrollments.*') || request()->routeIs('customer.payments.*') ? '[font-variation-settings:\'FILL\'_1]' : 'group-hover:scale-110 transition-transform duration-300' }}">account_balance_wallet</span>
            <span class="text-[14.5px] font-sans">Tabungan Ibadah</span>
        </a>

        <!-- 5. Tentang Kami (About Us) -->
        <a href="{{ route('about.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 active:scale-[0.98] group {{ request()->routeIs('about.*') ? 'bg-hanania-purple-light text-hanania-purple font-bold shadow-sm border-r-4 border-hanania-gold' : 'text-hanania-purple-dark hover:bg-hanania-purple-light/60 hover:text-hanania-purple' }}">
            <span class="material-symbols-outlined {{ request()->routeIs('about.*') ? '[font-variation-settings:\'FILL\'_1]' : 'group-hover:scale-110 transition-transform duration-300' }}">info</span>
            <span class="text-[14.5px] font-sans">Tentang Kami</span>
        </a>

        <!-- 6. Profil Anda -->
        <a href="{{ route('customer.profile.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 active:scale-[0.98] group {{ request()->routeIs('customer.profile.*') ? 'bg-hanania-purple-light text-hanania-purple font-bold shadow-sm border-r-4 border-hanania-gold' : 'text-hanania-purple-dark hover:bg-hanania-purple-light/60 hover:text-hanania-purple' }}">
            <span class="material-symbols-outlined {{ request()->routeIs('customer.profile.*') ? '[font-variation-settings:\'FILL\'_1]' : 'group-hover:scale-110 transition-transform duration-300' }}">person</span>
            <span class="text-[14.5px] font-sans">Profil Anda</span>
        </a>
    </nav>

    <!-- Logout Button Sidebar -->
    <div class="px-4 py-5 border-t border-hanania-purple/10">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-red-500 hover:bg-red-50 hover:text-red-600 font-semibold active:scale-[0.98] transition-all duration-200 group border border-transparent hover:border-red-100">
                <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform duration-300">logout</span>
                <span class="text-[14.5px] font-sans">Keluar Akun</span>
            </button>
        </form>
    </div>
</aside>