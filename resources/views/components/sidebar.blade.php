<!-- OVERLAY GELAP -->
<div id="sidebar-overlay" onclick="toggleSidebar()" class="fixed inset-0 bg-hanania-purple-dark/30 backdrop-blur-sm z-[60] hidden transition-all duration-300 opacity-0 cursor-pointer lg:hidden"></div>

<!-- SIDEBAR MUNCUL DARI KANAN (Paten Mentok Bawah) -->
<aside id="sidebar" class="fixed top-0 bottom-0 right-0 w-[260px] sm:w-[280px] bg-white border-l border-hanania-purple/10 z-[70] transform translate-x-full transition-transform duration-300 ease-out flex flex-col shadow-[-10px_0_30px_rgba(97,57,143,0.1)] lg:hidden">

    <!-- HEADER SIDEBAR -->
    <div class="h-16 lg:h-20 flex items-center justify-between px-5 lg:px-6 border-b border-hanania-purple/10 shrink-0 bg-white">
        <h3 class="font-heading font-black text-hanania-purple text-[16px] tracking-widest uppercase">Menu</h3>
        <button onclick="toggleSidebar()" class="w-8 h-8 flex items-center justify-center rounded-full bg-white border border-hanania-purple/10 text-hanania-purple-dark hover:text-hanania-purple hover:bg-hanania-purple-light active:scale-90 transition-all shadow-sm">
            <span class="material-symbols-outlined text-[18px]">close</span>
        </button>
    </div>

    <!-- MENU LINKS SIDEBAR -->
    <nav class="flex-1 overflow-y-auto px-4 py-6 flex flex-col gap-2 custom-scrollbar">
        @auth
            <a href="{{ url('/') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 active:scale-[0.98] group {{ request()->is('/') || request()->is('') ? 'bg-hanania-purple-light text-hanania-purple font-bold shadow-sm border-r-4 border-hanania-gold' : 'text-hanania-purple-dark hover:bg-hanania-purple-light/60 hover:text-hanania-purple' }}">
                <span class="material-symbols-outlined {{ request()->is('/') ? '[font-variation-settings:\'FILL\'_1]' : 'group-hover:scale-110 transition-transform duration-300' }}">home</span>
                <span class="text-[14.5px] font-sans">Beranda</span>
            </a>
            <a href="{{ route('customer.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 active:scale-[0.98] group {{ request()->routeIs('customer.dashboard') ? 'bg-hanania-purple-light text-hanania-purple font-bold shadow-sm border-r-4 border-hanania-gold' : 'text-hanania-purple-dark hover:bg-hanania-purple-light/60 hover:text-hanania-purple' }}">
                <span class="material-symbols-outlined {{ request()->routeIs('customer.dashboard') ? '[font-variation-settings:\'FILL\'_1]' : 'group-hover:scale-110 transition-transform duration-300' }}">dashboard</span>
                <span class="text-[14.5px] font-sans">Dashboard</span>
            </a>
            <a href="{{ route('packages.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 active:scale-[0.98] group {{ request()->routeIs('packages.*') ? 'bg-hanania-purple-light text-hanania-purple font-bold shadow-sm border-r-4 border-hanania-gold' : 'text-hanania-purple-dark hover:bg-hanania-purple-light/60 hover:text-hanania-purple' }}">
                <span class="material-symbols-outlined {{ request()->routeIs('packages.*') ? '[font-variation-settings:\'FILL\'_1]' : 'group-hover:scale-110 transition-transform duration-300' }}">travel_explore</span>
                <span class="text-[14.5px] font-sans">Paket</span>
            </a>
            <a href="{{ route('customer.enrollments.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 active:scale-[0.98] group {{ request()->routeIs('customer.enrollments.*') || request()->routeIs('customer.payments.*') ? 'bg-hanania-purple-light text-hanania-purple font-bold shadow-sm border-r-4 border-hanania-gold' : 'text-hanania-purple-dark hover:bg-hanania-purple-light/60 hover:text-hanania-purple' }}">
                <span class="material-symbols-outlined {{ request()->routeIs('customer.enrollments.*') || request()->routeIs('customer.payments.*') ? '[font-variation-settings:\'FILL\'_1]' : 'group-hover:scale-110 transition-transform duration-300' }}">account_balance_wallet</span>
                <span class="text-[14.5px] font-sans">Tabungan Ibadah</span>
            </a>
            <a href="{{ route('about.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 active:scale-[0.98] group {{ request()->routeIs('about.*') ? 'bg-hanania-purple-light text-hanania-purple font-bold shadow-sm border-r-4 border-hanania-gold' : 'text-hanania-purple-dark hover:bg-hanania-purple-light/60 hover:text-hanania-purple' }}">
                <span class="material-symbols-outlined {{ request()->routeIs('about.*') ? '[font-variation-settings:\'FILL\'_1]' : 'group-hover:scale-110 transition-transform duration-300' }}">info</span>
                <span class="text-[14.5px] font-sans">Tentang Kami</span>
            </a>
            <a href="#galeri" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 active:scale-[0.98] group {{ request()->routeIs('about.*') ? 'bg-hanania-purple-light text-hanania-purple font-bold shadow-sm border-r-4 border-hanania-gold' : 'text-hanania-purple-dark hover:bg-hanania-purple-light/60 hover:text-hanania-purple' }}">
                <span class="material-symbols-outlined {{ request()->routeIs('about.*') ? '[font-variation-settings:\'FILL\'_1]' : 'group-hover:scale-110 transition-transform duration-300' }}">photo_library</span>
                <span class="text-[14.5px] font-sans">Galeri</span>
            </a>
            <a href="{{ route('customer.profile.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 active:scale-[0.98] group {{ request()->routeIs('customer.profile.*') ? 'bg-hanania-purple-light text-hanania-purple font-bold shadow-sm border-r-4 border-hanania-gold' : 'text-hanania-purple-dark hover:bg-hanania-purple-light/60 hover:text-hanania-purple' }}">
                <span class="material-symbols-outlined {{ request()->routeIs('customer.profile.*') ? '[font-variation-settings:\'FILL\'_1]' : 'group-hover:scale-110 transition-transform duration-300' }}">person</span>
                <span class="text-[14.5px] font-sans">Profil Anda</span>
            </a>
        @else
            <!-- Menu Tamu (Guest) -->
            <a href="{{ url('/') }}" onclick="toggleSidebar()" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 active:scale-[0.98] group text-hanania-purple-dark hover:bg-hanania-purple-light/60 hover:text-hanania-purple">
                <span class="material-symbols-outlined group-hover:scale-110 transition-transform duration-300">home</span>
                <span class="text-[14.5px] font-sans">Beranda</span>
            </a>
            <a href="{{ url('/') }}#paket" onclick="toggleSidebar()" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 active:scale-[0.98] group text-hanania-purple-dark hover:bg-hanania-purple-light/60 hover:text-hanania-purple">
                <span class="material-symbols-outlined group-hover:scale-110 transition-transform duration-300">travel_explore</span>
                <span class="text-[14.5px] font-sans">Paket</span>
            </a>
            <a href="{{ url('/') }}#galeri" onclick="toggleSidebar()" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 active:scale-[0.98] group text-hanania-purple-dark hover:bg-hanania-purple-light/60 hover:text-hanania-purple">
                <span class="material-symbols-outlined group-hover:scale-110 transition-transform duration-300">photo_library</span>
                <span class="text-[14.5px] font-sans">Galeri</span>
            </a>
            <a href="{{ url('/') }}#fitur" onclick="toggleSidebar()" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 active:scale-[0.98] group text-hanania-purple-dark hover:bg-hanania-purple-light/60 hover:text-hanania-purple">
                <span class="material-symbols-outlined group-hover:scale-110 transition-transform duration-300">info</span>
                <span class="text-[14.5px] font-sans">Tentang Kami</span>
            </a>
        @endauth
    </nav>

    <!-- TOMBOL AKSI BAWAH PATEN -->
    <div class="px-3 py-2 border-t border-hanania-purple/10 shrink-0 mt-auto bg-white">
        @auth
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 rounded-xl text-red-500 hover:bg-red-50 hover:text-red-600 font-semibold active:scale-[0.98] transition-all duration-200 group border border-transparent hover:border-red-100">
                    <span class="material-symbols-outlined text-[20px] group-hover:translate-x-1 transition-transform duration-300">logout</span>
                    <span class="text-[14px] font-sans">Keluar Akun</span>
                </button>
            </form>
        @else
            <a href="{{ route('login') }}" class="w-full flex items-center justify-center gap-2 btn-hanania-gold py-2.5 rounded-xl text-[13px] font-bold active:scale-[0.98] transition-transform shadow-sm m-1">
                Masuk / Daftar
                <span class="material-symbols-outlined text-[18px]">login</span>
            </a>
        @endauth
    </div>
</aside>

<!-- SCRIPT JS GABUNGAN -->
<script>
    // FUNGSI UNTUK SIDEBAR
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        
        if (sidebar && overlay) {
            if (sidebar.classList.contains('translate-x-full')) {
                sidebar.classList.remove('translate-x-full');
                overlay.classList.remove('hidden', 'opacity-0');
                overlay.classList.add('opacity-100');
            } else {
                sidebar.classList.add('translate-x-full');
                overlay.classList.remove('opacity-100');
                overlay.classList.add('opacity-0');
                setTimeout(() => {
                    overlay.classList.add('hidden');
                }, 300); 
            }
        } else {
            console.error("Elemen sidebar tidak ditemukan. Pastikan file sidebar.blade.php sudah di-include di layout utama.");
        }
    }

    // FUNGSI UNTUK NOTIFIKASI
    function toggleNotifikasi(event) {
        event.stopPropagation();
        const dropdown = document.getElementById('dropdownNotifikasi');
        if (dropdown) dropdown.classList.toggle('hidden');
    }

    window.addEventListener('click', function(e) {
        const dropdown = document.getElementById('dropdownNotifikasi');
        if (dropdown && !dropdown.classList.contains('hidden')) {
            if (!dropdown.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        }
    });
</script>