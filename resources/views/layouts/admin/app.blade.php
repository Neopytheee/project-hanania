@php
    // Panggil variabelnya di sini agar tidak error jika ini file komponen terpisah
    $companyName = \App\Models\AppInformation::getValue('company_name', 'Hanania Travel');
@endphp

<!DOCTYPE html>
<html lang="id" translate="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#ffffff">
    <title>@yield('header_title') - {{ \App\Models\AppInformation::getValue('company_name') ?? 'Hanania' }}</title>
    
    <!-- Google Font (Plus Jakarta Sans) & Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,1,0" />
    
    <!-- Load Vite Khusus Admin -->
    @vite(['resources/css/admin.css', 'resources/js/app.js'])

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-slate-50 min-h-screen flex text-slate-800 antialiased selection:bg-hanania-purple selection:text-white overflow-x-hidden">

    <!-- OVERLAY UNTUK MOBILE (Gelap transparan saat sidebar HP terbuka) -->
    <div id="sidebar-overlay" onclick="toggleSidebar()" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 hidden lg:hidden opacity-0 transition-opacity duration-300"></div>

    <!-- PANGGIL KOMPONEN SIDEBAR -->
    <!-- Pastikan class di sidebar.blade.php bosku ada -translate-x-full lg:translate-x-0 agar responsif -->
    <x-admin.sidebar />
    
    <!-- WRAPPER UTAMA -->
    <!-- lg:ml-[280px] memastikan konten didorong ke kanan hanya saat di Laptop/PC -->
    <div class="flex-1 lg:ml-[280px] flex flex-col min-h-screen transition-all duration-300 min-w-0">
        
        <!-- ========================================== -->
        <!-- NAVBAR ATAS (Header) -->
        <!-- ========================================== -->
        <nav class="bg-white border-b border-slate-200 px-4 sm:px-8 h-16 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] sticky top-0 z-30 flex justify-between items-center transition-all">
            
            <div class="flex items-center gap-3 sm:gap-4">
                <!-- Tombol Hamburger (Hanya muncul di HP) -->
                <button type="button" onclick="toggleSidebar()" class="lg:hidden w-10 h-10 flex items-center justify-center text-slate-500 hover:text-hanania-purple hover:bg-hanania-purple-light/20 rounded-xl transition-colors">
                    <span class="material-symbols-outlined text-[24px]">menu</span>
                </button>
                
                <!-- Judul Halaman Dinamis -->
                <h1 class="font-extrabold text-[16px] sm:text-[18px] text-slate-900 tracking-tight line-clamp-1">
                    @yield('header_title', 'Dashboard')
                </h1>
            </div>
            
            <!-- Profil Admin Kanan Atas -->
            <div class="flex items-center gap-4">

            <!-- Badge Profil -->
            <div class="pl-4 border-l border-slate-200">

                <div class="flex items-center gap-2">

                    <div class="text-[13px] font-bold text-hanania-purple bg-hanania-purple/5 px-3 sm:px-4 py-2 rounded-full border border-hanania-purple/10 flex items-center gap-2 cursor-pointer hover:bg-hanania-purple/10 transition-colors shadow-sm">

                <span class="material-symbols-outlined text-[18px] [font-variation-settings:'FILL'_1]">
                    account_circle
                </span>

                <span class="hidden sm:block truncate max-w-[120px]">
                    {{ auth()->user()->name ?? 'Administrator' }}
                </span>

            </div>


            <!-- Logout -->
            <form
                action="{{ route('logout') }}"
                method="POST"
                class="m-0"
            >
                @csrf

                <button
                    type="submit"
                    title="Keluar"
                    aria-label="Keluar"
                    class="w-10 h-10 flex items-center justify-center rounded-full text-slate-400 hover:text-red-500 hover:bg-red-50 transition-all active:scale-95"
                >
                    <span class="material-symbols-outlined text-[21px]">
                        power_settings_new
                    </span>
                </button>
            </form>

        </div>

    </div>

</div>
        </nav>

        <!-- ========================================== -->
        <!-- AREA KONTEN UTAMA (Tempat tabel & form dirender) -->
        <!-- ========================================== -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8 w-full max-w-[1400px] mx-auto">
            
            <!-- GLOBAL ALERT (Untuk nampilin pesan sukses/error dari Controller) -->
            @if(session('success'))
                <div class="mb-6 bg-emerald-50 border border-emerald-200 rounded-xl p-4 flex items-start gap-3 shadow-sm animate-fade-in-up">
                    <span class="material-symbols-outlined text-emerald-500 [font-variation-settings:'FILL'_1]">check_circle</span>
                    <p class="text-[13px] font-bold text-emerald-800 mt-0.5">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 flex items-start gap-3 shadow-sm animate-fade-in-up">
                    <span class="material-symbols-outlined text-red-500 [font-variation-settings:'FILL'_1]">error</span>
                    <p class="text-[13px] font-bold text-red-800 mt-0.5">{{ session('error') }}</p>
                </div>
            @endif

            <!-- Konten View Di-inject Di Sini -->
            @yield('content')
            
        </main>
        
        <!-- ========================================== -->
        <!-- FOOTER ADMIN -->
        <!-- ========================================== -->
        <footer class="border-t border-slate-200 bg-white/50 px-4 sm:px-8 py-4 text-center sm:text-left mt-auto flex flex-col sm:flex-row justify-between items-center gap-2">
            <p class="text-[11px] font-bold text-slate-400 tracking-wide">
                &copy; {{ date('Y') }} {{ $companyName }}. All rights reserved.
            </p>
            <p class="text-[11px] font-medium text-slate-400">
                Sistem Monitoring Tabungan <span class="text-hanania-gold font-bold">v1.0</span>
            </p>
        </footer>
        
    </div>

    <!-- ========================================== -->
    <!-- SCRIPT LOGIKA RESPONSIVE SIDEBAR -->
    <!-- ========================================== -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('admin-sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            
            if (sidebar.classList.contains('-translate-x-full')) {
                // Buka Sidebar
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                // Sedikit delay agar animasi transisi opacity jalan
                setTimeout(() => overlay.classList.remove('opacity-0'), 10);
            } else {
                // Tutup Sidebar
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('opacity-0');
                // Sembunyikan overlay setelah animasi selesai
                setTimeout(() => overlay.classList.add('hidden'), 300);
            }
        }
    </script>

    <x-chat-widget />
</body>
</html>