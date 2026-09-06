@php
    $companyName = \App\Models\AppInformation::getValue('company_name', 'Hanania Travel');
    $logoPath = \App\Models\AppInformation::getValue('company_logo');
    $logoUrl = $logoPath ? asset('storage/' . $logoPath) : asset('images/HananiaNew4K.png');
@endphp

<!-- FIXED TOP -->
<div class="fixed w-full top-4 z-40 px-4 sm:px-6 lg:px-8 pointer-events-none">
    <nav class="max-w-7xl mx-auto bg-white/80 backdrop-blur-xl border border-hanania-purple/10 shadow-lg rounded-full px-4 sm:px-6 py-3 flex justify-between items-center pointer-events-auto transition-all relative">

        <!-- LOGO KIRI -->
        <div class="flex items-center shrink-0">
            <a href="{{ url('/') }}" class="flex items-center gap-2 sm:gap-3 group shrink-0">
                <img src="{{ $logoUrl }}" alt="Logo {{ $companyName }}" class="h-9 sm:h-11 w-auto object-contain transition-transform group-hover:scale-105">
            </a>
        </div>

        <!-- ================= MENU DESKTOP TENGAH ================= -->
        <div class="hidden lg:flex items-center gap-1 absolute left-1/2 transform -translate-x-1/2">
            @auth
                <a href="{{ url('/') }}" class="whitespace-nowrap px-3 xl:px-4 py-2 rounded-full text-[13px] font-bold transition-all {{ request()->is('/') || request()->is('') ? 'bg-hanania-purple-light text-hanania-purple shadow-sm' : 'text-gray-500 hover:text-hanania-purple hover:bg-hanania-purple-light/50' }}">Home</a>
                <a href="{{ route('customer.dashboard') }}" class="whitespace-nowrap px-3 xl:px-4 py-2 rounded-full text-[13px] font-bold transition-all {{ request()->routeIs('customer.dashboard') ? 'bg-hanania-purple-light text-hanania-purple shadow-sm' : 'text-gray-500 hover:text-hanania-purple hover:bg-hanania-purple-light/50' }}">Dashboard</a>
                <a href="{{ route('packages.index') }}" class="whitespace-nowrap px-3 xl:px-4 py-2 rounded-full text-[13px] font-bold transition-all {{ request()->routeIs('packages.*') ? 'bg-hanania-purple-light text-hanania-purple shadow-sm' : 'text-gray-500 hover:text-hanania-purple hover:bg-hanania-purple-light/50' }}">Paket</a>
                <a href="{{ route('customer.enrollments.index') }}" class="whitespace-nowrap px-3 xl:px-4 py-2 rounded-full text-[13px] font-bold transition-all {{ request()->routeIs('customer.enrollments.*') || request()->routeIs('customer.payments.*') ? 'bg-hanania-purple-light text-hanania-purple shadow-sm' : 'text-gray-500 hover:text-hanania-purple hover:bg-hanania-purple-light/50' }}">Tabungan saya</a>
                <a href="{{ route('customer.profile.index') }}" class="whitespace-nowrap px-3 xl:px-4 py-2 rounded-full text-[13px] font-bold transition-all {{ request()->routeIs('customer.profile.*') ? 'bg-hanania-purple-light text-hanania-purple shadow-sm' : 'text-gray-500 hover:text-hanania-purple hover:bg-hanania-purple-light/50' }}">Profil</a>
                <a href="{{ url('/') }}#galeri" class="whitespace-nowrap px-3 xl:px-4 py-2 rounded-full text-[13px] font-bold transition-all text-gray-500 hover:text-hanania-purple hover:bg-hanania-purple-light/50">Galeri</a>
                <a href="{{ route('about.index') }}" class="whitespace-nowrap px-3 xl:px-4 py-2 rounded-full text-[13px] font-bold transition-all {{ request()->routeIs('about.index*') ? 'bg-hanania-purple-light text-hanania-purple shadow-sm' : 'text-gray-500 hover:text-hanania-purple hover:bg-hanania-purple-light/50' }}">Tentang Kami</a>
            @else
                <a href="{{ url('/') }}" class="whitespace-nowrap px-3 xl:px-4 py-2 rounded-full text-[13px] font-bold transition-all text-gray-500 hover:text-hanania-purple hover:bg-hanania-purple-light/50">Beranda</a>
                <a href="{{ url('/') }}#paket" class="whitespace-nowrap px-3 xl:px-4 py-2 rounded-full text-[13px] font-bold transition-all text-gray-500 hover:text-hanania-purple hover:bg-hanania-purple-light/50">Paket</a>
                <a href="{{ url('/') }}#galeri" class="whitespace-nowrap px-3 xl:px-4 py-2 rounded-full text-[13px] font-bold transition-all text-gray-500 hover:text-hanania-purple hover:bg-hanania-purple-light/50">Galeri</a>
                <a href="{{ url('/') }}#fitur" class="whitespace-nowrap px-3 xl:px-4 py-2 rounded-full text-[13px] font-bold transition-all text-gray-500 hover:text-hanania-purple hover:bg-hanania-purple-light/50">Tentang Kami</a>
            @endauth 
        </div>

        <!-- BAGIAN KANAN (Notifikasi & Tombol Hamburger) -->
        <div class="flex items-center gap-2 sm:gap-3 shrink-0">
            @auth 
                <!-- Tombol Lonceng -->
                <div class="relative">
                    <button type="button" onclick="toggleNotifikasi(event)" class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-white border border-hanania-purple/10 flex items-center justify-center text-hanania-purple hover:bg-hanania-purple-light active:scale-90 transition-all shadow-sm relative z-50">
                        <span class="material-symbols-outlined text-[20px]">notifications</span>
                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <span class="absolute -top-1 -right-1 w-4 h-4 sm:w-5 sm:h-5 bg-red-500 border-2 border-white text-white text-[9px] sm:text-[10px] font-bold flex items-center justify-center rounded-full animate-pulse">
                                {{ auth()->user()->unreadNotifications->count() }}
                            </span>
                        @endif
                    </button>

                    <!-- Kotak Dropdown Notifikasi -->
                    <div id="dropdownNotifikasi" class="hidden absolute right-0 top-12 sm:top-14 mt-1 w-80 sm:w-96 bg-white rounded-2xl shadow-xl border border-gray-100 z-40 overflow-hidden">
                        <div class="px-5 py-3.5 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-sm text-hanania-purple-dark">Notifikasi Baru</span>
                                @if(auth()->user()->unreadNotifications->count() > 0)
                                    <span class="bg-hanania-purple-light text-hanania-purple text-[10px] font-bold px-2 py-0.5 rounded-full">
                                        {{ auth()->user()->unreadNotifications->count() }} Baru
                                    </span>
                                @endif
                            </div>
                            @if(auth()->user()->unreadNotifications->count() > 0)
                                <a href="{{ route('notifikasi.baca-semua') }}" class="text-[11px] font-bold text-hanania-purple hover:underline flex items-center gap-1 transition-all">
                                    <span class="material-symbols-outlined text-[14px]">done_all</span>
                                    Tandai semua dibaca
                                </a>
                            @endif
                        </div>
                        <div class="max-h-96 overflow-y-auto custom-scrollbar">
                            @forelse(auth()->user()->unreadNotifications()->take(5)->get() as $notification)
                                <a href="{{ route('notifikasi.baca', $notification->id) }}" class="block p-4 hover:bg-hanania-purple-light/30 border-b border-gray-50 transition-all">
                                    <div class="flex gap-4">
                                        <div class="w-10 h-10 rounded-full bg-hanania-purple/10 text-hanania-purple flex items-center justify-center shrink-0">
                                            <span class="material-symbols-outlined text-lg">campaign</span>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-bold text-gray-800 leading-tight">
                                                {{ $notification->data['title'] ?? 'Pemberitahuan' }}
                                            </p>
                                            <p class="text-xs text-gray-500 leading-normal mt-1.5">
                                                {{ $notification->data['message'] ?? '' }}
                                            </p>
                                            <p class="text-xs font-semibold text-gray-400 mt-2 flex items-center gap-1">
                                                <span class="material-symbols-outlined text-[13px]">schedule</span>
                                                {{ $notification->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="p-8 text-center text-gray-400 flex flex-col items-center justify-center gap-3">
                                    <span class="material-symbols-outlined text-4xl text-gray-200">notifications_paused</span>
                                    <span class="text-sm font-medium">Belum ada notifikasi.</span>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- TOMBOL LOGOUT DESKTOP -->
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

            <!-- TOMBOL HAMBURGER MENU (Klik Buka Sidebar) -->
            <button onclick="toggleSidebar()" class="lg:hidden w-9 h-9 rounded-full bg-white border border-hanania-purple/10 flex items-center justify-center text-hanania-purple hover:bg-hanania-purple-light active:scale-90 transition-all shadow-sm relative z-50">
                <span class="material-symbols-outlined text-[20px]">menu</span>
            </button>
        </div>

    </nav>
</div>