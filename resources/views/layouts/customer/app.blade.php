<!DOCTYPE html>
<html lang="id" translate="no">
<head>
    @php
        // Tarik nama perusahaan dari database
        $companyName = \App\Models\AppInformation::getValue('company_name', 'Hanania');
    @endphp
    
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="theme-color" content="#4c1d95">
    
    <!-- Judul Dinamis -->
    <title>@yield('title', $companyName)</title>
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Load Tailwind v4 via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-surface-container-low text-on-surface min-h-screen text-[14px] leading-relaxed font-['Plus_Jakarta_Sans'] antialiased selection:bg-hanania-purple selection:text-white">
    
    <!-- 1. SIDEBAR HANYA DIMUNCULKAN JIKA USER LOGIN -->
    @auth
        @include('components.sidebar')
    @endauth

    <!-- 2. WRAPPER UTAMA (PADDING DINAMIS) -->
    <!-- Jika user login, beri padding kiri 280px. Jika tamu, biarkan full-width -->
    <div class="flex flex-col min-h-screen transition-all duration-300 w-full">
        
        <!-- MEMANGGIL KOMPONEN TOPBAR -->
        @include('components.navbar')

        <main class="flex-1 min-w-0 w-full max-w-7xl mx-auto relative animate-fade-in-up px-4 sm:px-6 lg:px-8 py-6 lg:py-8 pb-20 lg:pb-12">
            @yield('content')
        </main>
        
    </div>

    <!-- 3. JAVASCRIPT SIDEBAR HANYA DIMUAT JIKA LOGIN -->
    @auth
    <script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        
        // Bersihkan class kiri jika masih ada sisa
        sidebar.classList.remove('-translate-x-full');
        
        // Cek apakah sidebar sedang tersembunyi di kanan
        if (sidebar.classList.contains('translate-x-full') || sidebar.classList.contains('hidden')) {
            // BUKA SIDEBAR
            sidebar.classList.remove('hidden'); // Jaga-jaga jika ada class hidden
            sidebar.classList.remove('translate-x-full');
            sidebar.classList.add('translate-x-0');
            
            // Munculkan Overlay
            overlay.classList.remove('hidden');
            setTimeout(() => {
                overlay.classList.remove('opacity-0');
                overlay.classList.add('opacity-100');
            }, 10);
        } else {
            // TUTUP SIDEBAR
            sidebar.classList.remove('translate-x-0');
            sidebar.classList.add('translate-x-full');
            
            // Sembunyikan Overlay
            overlay.classList.remove('opacity-100');
            overlay.classList.add('opacity-0');
            
            setTimeout(() => {
                overlay.classList.add('hidden');
            }, 300);
        }
    }
</script>
    @endauth

    <!-- STYLES KHUSUS LAYOUT -->
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up { animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        @keyframes wiggle {
            0%, 100% { transform: rotate(-10deg); }
            50% { transform: rotate(10deg); }
        }
        .animate-wiggle { animation: wiggle 0.3s ease-in-out infinite; }
        
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { 
            background-color: transparent; border-radius: 10px; transition: background-color 0.3s;
        }
        .custom-scrollbar:hover::-webkit-scrollbar-thumb { background-color: var(--color-outline-variant); }
    </style>

    <x-chat-widget />
</body>
</html>