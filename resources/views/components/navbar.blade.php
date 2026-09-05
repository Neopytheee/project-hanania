@php
    $companyName = \App\Models\AppInformation::getValue('company_name', 'Hanania Travel');
    $logoPath = \App\Models\AppInformation::getValue('company_logo');
    $logoUrl = $logoPath ? asset('storage/' . $logoPath) : asset('images/HananiaNew4K.png');
@endphp

<!-- FIXED TOP DENGAN Z-40 AGAR TIDAK MENIMPA SIDEBAR -->
<div class="fixed w-full top-4 z-40 px-4 sm:px-6 lg:px-8 pointer-events-none">
    <nav class="max-w-7xl mx-auto bg-white/80 backdrop-blur-xl border border-hanania-purple/10 shadow-lg rounded-full px-4 sm:px-6 py-3 flex justify-between items-center pointer-events-auto transition-all relative">

        <!-- ================= BAGIAN KIRI ================= -->
        <div class="flex items-center shrink-0">
            <a href="{{ url('/') }}" class="flex items-center gap-2 sm:gap-3 group shrink-0">
                <img src="{{ $logoUrl }}" alt="Logo {{ $companyName }}" class="h-9 sm:h-11 w-auto object-contain transition-transform group-hover:scale-105">
            </a>
        </div>

        <!-- ================= BAGIAN TENGAH ================= -->
        <div class="hidden lg:flex items-center gap-1 xl:gap-2 absolute left-1/2 transform -translate-x-1/2">
            @auth
                <a href="{{ url('/') }}" class="px-4 py-2 rounded-full text-[13px] font-bold transition-all {{ request()->is('/') || request()->is('') ? 'bg-hanania-purple-light text-hanania-purple shadow-sm' : 'text-gray-500 hover:text-hanania-purple hover:bg-hanania-purple-light/50' }}">Home</a>
                <a href="{{ route('customer.dashboard') }}" class="px-4 py-2 rounded-full text-[13px] font-bold transition-all {{ request()->routeIs('customer.dashboard') ? 'bg-hanania-purple-light text-hanania-purple shadow-sm' : 'text-gray-500 hover:text-hanania-purple hover:bg-hanania-purple-light/50' }}">Dashboard</a>
                <a href="{{ route('packages.index') }}" class="px-4 py-2 rounded-full text-[13px] font-bold transition-all {{ request()->routeIs('packages.*') ? 'bg-hanania-purple-light text-hanania-purple shadow-sm' : 'text-gray-500 hover:text-hanania-purple hover:bg-hanania-purple-light/50' }}">Paket</a>
                <a href="{{ route('customer.enrollments.index') }}" class="px-4 py-2 rounded-full text-[13px] font-bold transition-all {{ request()->routeIs('customer.enrollments.*') || request()->routeIs('customer.payments.*') ? 'bg-hanania-purple-light text-hanania-purple shadow-sm' : 'text-gray-500 hover:text-hanania-purple hover:bg-hanania-purple-light/50' }}">Tabungan saya</a>
                <a href="{{ route('customer.profile.index') }}" class="px-4 py-2 rounded-full text-[13px] font-bold transition-all {{ request()->routeIs('customer.profile.*') ? 'bg-hanania-purple-light text-hanania-purple shadow-sm' : 'text-gray-500 hover:text-hanania-purple hover:bg-hanania-purple-light/50' }}">Profil</a>
                <a href="{{ route('about.index') }}" class="px-4 py-2 rounded-full text-[13px] font-bold transition-all {{ request()->routeIs('about.index*') ? 'bg-hanania-purple-light text-hanania-purple shadow-sm' : 'text-gray-500 hover:text-hanania-purple hover:bg-hanania-purple-light/50' }}">Tentang Kami</a>
            @else
                <a href="{{ url('/') }}" class="px-4 py-2 rounded-full text-[13px] font-bold transition-all text-gray-500 hover:text-hanania-purple hover:bg-hanania-purple-light/50">Beranda</a>
                <a href="{{ url('/') }}#paket" class="px-4 py-2 rounded-full text-[13px] font-bold transition-all text-gray-500 hover:text-hanania-purple hover:bg-hanania-purple-light/50">Paket</a>
                <a href="{{ url('/') }}#fitur" class="px-4 py-2 rounded-full text-[13px] font-bold transition-all text-gray-500 hover:text-hanania-purple hover:bg-hanania-purple-light/50">Tentang Kami</a>
                <a href="{{ url('/') }}#galeri" class="px-4 py-2 rounded-full text-[13px] font-bold transition-all text-gray-500 hover:text-hanania-purple hover:bg-hanania-purple-light/50">Galeri</a>
            @endauth 
        </div>

        <!-- ================= BAGIAN KANAN ================= -->
        <div class="flex items-center gap-3 shrink-0">
            @auth  
                <form method="POST" action="{{ route('logout') }}" class="hidden lg:block m-0 p-0">
                    @csrf
                    <button type="submit" class="w-10 h-10 rounded-full bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-all shadow-sm flex items-center justify-center" title="Keluar Akun">
                        <span class="material-symbols-outlined text-[18px]">logout</span>
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="hidden sm:flex btn-hanania-gold px-5 py-2.5 rounded-full text-[13px] items-center gap-2 shadow-md">
                    <span class="font-bold">Masuk</span>
                    <span class="material-symbols-outlined text-[18px]">login</span>
                </a>
            @endauth

            <button onclick="toggleSidebar()" class="lg:hidden w-9 h-9 rounded-full bg-white border border-hanania-purple/10 flex items-center justify-center text-hanania-purple hover:bg-hanania-purple-light active:scale-90 transition-all shadow-sm">
                <span class="material-symbols-outlined text-[20px]">menu</span>
            </button>
        </div>

    </nav>
</div>

<!-- OVERLAY GELAP -->
<div id="sidebar-overlay" onclick="toggleSidebar()" class="fixed inset-0 bg-hanania-purple-dark/30 backdrop-blur-sm z-[60] hidden transition-all duration-300 opacity-0 cursor-pointer lg:hidden"></div>

<!-- SIDEBAR MUNCUL DARI KANAN (Menggunakan h-[100dvh] agar akurat di HP) -->
<aside id="sidebar" class="fixed top-0 right-0 h-[100dvh] w-[260px] sm:w-[280px] bg-white border-l border-hanania-purple/10 z-[70] transform translate-x-full transition-transform duration-300 ease-out flex flex-col shadow-[-10px_0_30px_rgba(97,57,143,0.1)] lg:hidden">

    <!-- HEADER SIDEBAR -->
    <div class="h-16 flex items-center justify-between px-5 border-b border-hanania-purple/10 shrink-0 bg-white">
        <h3 class="font-heading font-black text-hanania-purple text-[16px] tracking-widest uppercase">Menu</h3>
        <button onclick="toggleSidebar()" class="w-8 h-8 flex items-center justify-center rounded-full bg-white border border-hanania-purple/10 text-hanania-purple-dark hover:text-hanania-purple hover:bg-hanania-purple-light active:scale-90 transition-all shadow-sm">
            <span class="material-symbols-outlined text-[18px]">close</span>
        </button>
    </div>

    <!-- MENU LINKS SIDEBAR (Yang bisa di-scroll hanya area ini) -->
    <nav class="flex-1 overflow-y-auto px-4 py-6 flex flex-col gap-2 custom-scrollbar">
        @auth
            <a href="{{ url('/') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 active:scale-[0.98] group {{ request()->is('/') || request()->is('') ? 'bg-hanania-purple-light text-hanania-purple font-bold shadow-sm border-r-4 border-hanania-gold' : 'text-hanania-purple-dark hover:bg-hanania-purple-light/60 hover:text-hanania-purple' }}">
                <span class="material-symbols-outlined {{ request()->is('/') ? '[font-variation-settings:\'FILL\'_1]' : 'group-hover:scale-110 transition-transform duration-300' }}">home</span>
                <span class="text-[14.5px] font-sans">Home</span>
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
            <a href="{{ route('customer.profile.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 active:scale-[0.98] group {{ request()->routeIs('customer.profile.*') ? 'bg-hanania-purple-light text-hanania-purple font-bold shadow-sm border-r-4 border-hanania-gold' : 'text-hanania-purple-dark hover:bg-hanania-purple-light/60 hover:text-hanania-purple' }}">
                <span class="material-symbols-outlined {{ request()->routeIs('customer.profile.*') ? '[font-variation-settings:\'FILL\'_1]' : 'group-hover:scale-110 transition-transform duration-300' }}">person</span>
                <span class="text-[14.5px] font-sans">Profil Anda</span>
            </a>
        @else
            <a href="{{ url('/') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 active:scale-[0.98] group text-hanania-purple-dark hover:bg-hanania-purple-light/60 hover:text-hanania-purple">
                <span class="material-symbols-outlined group-hover:scale-110 transition-transform duration-300">home</span>
                <span class="text-[14.5px] font-sans">Beranda</span>
            </a>
            <a href="{{ url('/') }}#paket" onclick="toggleSidebar()" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 active:scale-[0.98] group text-hanania-purple-dark hover:bg-hanania-purple-light/60 hover:text-hanania-purple">
                <span class="material-symbols-outlined group-hover:scale-110 transition-transform duration-300">travel_explore</span>
                <span class="text-[14.5px] font-sans">Paket</span>
            </a>
            <a href="{{ url('/') }}#fitur" onclick="toggleSidebar()" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 active:scale-[0.98] group text-hanania-purple-dark hover:bg-hanania-purple-light/60 hover:text-hanania-purple">
                <span class="material-symbols-outlined group-hover:scale-110 transition-transform duration-300">info</span>
                <span class="text-[14.5px] font-sans">Tentang Kami</span>
            </a>
            <a href="{{ url('/') }}#galeri" onclick="toggleSidebar()" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 active:scale-[0.98] group text-hanania-purple-dark hover:bg-hanania-purple-light/60 hover:text-hanania-purple">
                <span class="material-symbols-outlined group-hover:scale-110 transition-transform duration-300">photo_library</span>
                <span class="text-[14.5px] font-sans">Galeri</span>
            </a>
        @endauth
    </nav>

    <!-- TOMBOL AKSI BAWAH PATEN (shrink-0 mt-auto memastikan nyangkut di bawah) -->
    <div class="px-5 py-6 border-t border-hanania-purple/10 shrink-0 mt-auto bg-white">
        @auth
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-3.5 rounded-xl text-red-500 hover:bg-red-50 hover:text-red-600 font-semibold active:scale-[0.98] transition-all duration-200 group border border-transparent hover:border-red-100">
                    <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform duration-300">logout</span>
                    <span class="text-[14.5px] font-sans">Keluar Akun</span>
                </button>
            </form>
        @else
            <a href="{{ route('login') }}" class="w-full flex items-center justify-center gap-2 btn-hanania-gold py-3.5 rounded-xl font-bold active:scale-[0.98] transition-transform shadow-md">
                Masuk / Daftar
                <span class="material-symbols-outlined text-[18px]">login</span>
            </a>
        @endauth
    </div>
</aside>