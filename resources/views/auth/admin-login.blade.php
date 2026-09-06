<!DOCTYPE html>
<html lang="id">
<head>
    @php
        $companyName = \App\Models\AppInformation::getValue('company_name', 'Hanania Travel');
    @endphp
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="theme-color" content="#61398F">
    <title>Admin Login - {{ $companyName }}</title>
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,1,0" />
    
    <!-- Load Tailwind via Vite -->
    @vite(['resources/css/admin.css', 'resources/js/app.js'])
</head>

<!-- KUNCI ANTI-SCROLL: lg:h-screen & lg:overflow-hidden disematkan di body -->
<body class="bg-slate-50 lg:bg-white min-h-screen lg:h-screen lg:overflow-hidden antialiased flex flex-col lg:flex-row selection:bg-hanania-purple selection:text-white">

    <!-- ========================================== -->
    <!-- SISI KIRI / ATAS: GAMBAR MASJIDIL HARAM & BRANDING -->
    <!-- ========================================== -->
    <div class="relative w-full h-[32vh] lg:h-full lg:w-[55%] overflow-hidden bg-slate-900 shrink-0">
        
        <!-- Gambar Latar Belakang (Unsplash: Masjidil Haram / Ka'bah) -->
        <img src="https://images.unsplash.com/photo-1580418827493-f2b22c0a76cb?q=80&w=2000&auto=format&fit=crop" 
             alt="Suasana Masjidil Haram" 
             class="absolute inset-0 w-full h-full object-cover object-center opacity-70 mix-blend-luminosity lg:hover:scale-105 transition-transform duration-[20s] ease-out">
        
        <!-- Gradasi Latar (Memadukan gambar dengan warna brand) -->
        <div class="absolute inset-0 bg-gradient-to-br from-hanania-purple-dark/95 via-hanania-purple/80 to-transparent mix-blend-multiply"></div>
        
        <!-- Gradasi Transisi (Menyatu dengan Slate 50 di HP, Gelap di PC) -->
        <div class="absolute inset-0 bg-gradient-to-t from-slate-50 via-slate-50/40 to-transparent lg:hidden"></div>
        <div class="hidden lg:block absolute inset-0 bg-gradient-to-t from-slate-900/80 via-transparent to-transparent"></div>

        <!-- Konten Latar (Teks & Quote) -->
        <div class="absolute inset-0 p-8 lg:p-12 flex flex-col justify-center lg:justify-between z-10 pb-16 lg:pb-12">
            <!-- Logo Brand (Tengah di HP, Kiri di PC) -->
            <div class="flex items-center justify-center lg:justify-start gap-2.5 text-white">
                <div class="w-10 h-10 bg-white/10 backdrop-blur-md rounded-xl flex items-center justify-center border border-white/20 shadow-lg">
                    <span class="material-symbols-outlined text-[24px] text-hanania-gold [font-variation-settings:'FILL'_1]">shield_person</span>
                </div>
                <span class="font-extrabold text-[20px] tracking-tight drop-shadow-md">{{ $companyName }}</span>
            </div>

            <!-- Teks Hero (Hanya Muncul di Layar PC agar HP tidak penuh) -->
            <div class="hidden lg:block">
                <span class="inline-block py-1.5 px-3 rounded-lg bg-white/10 backdrop-blur-md border border-white/20 text-hanania-gold text-[11px] font-extrabold uppercase tracking-widest mb-4">
                    Sistem Manajemen Terpadu
                </span>
                <h2 class="text-[40px] font-bold text-white mb-4 leading-[1.1] tracking-tight drop-shadow-md">
                    Kelola Perjalanan<br>Ibadah dengan Presisi.
                </h2>
                <p class="text-[15px] text-slate-200 font-medium max-w-md leading-relaxed drop-shadow-sm">
                    Akses kontrol penuh untuk memonitor pendaftaran, pembayaran, dan operasional keberangkatan jamaah secara *real-time*.
                </p>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- SISI KANAN / BAWAH: FORM LOGIN ADMIN -->
    <!-- ========================================== -->
    <!-- PENGAMAN SCROLL: lg:overflow-y-auto no-scrollbar memastikan form aman di layar pendek -->
    <div class="flex-1 w-full lg:w-[45%] lg:h-full flex items-start lg:items-center justify-center p-4 sm:p-8 lg:p-12 relative z-20 -mt-12 sm:-mt-16 lg:mt-0 lg:overflow-y-auto no-scrollbar">
        
        <!-- Wadah Form Utama -->
        <div class="w-full max-w-[400px] bg-white lg:bg-transparent rounded-3xl lg:rounded-none shadow-[0_10px_40px_rgba(0,0,0,0.08)] lg:shadow-none p-7 sm:p-10 lg:p-0 border border-slate-100 lg:border-none relative">
            
            <!-- Ornamen Halus (Hanya terlihat sedikit di background putih layar besar) -->
            <div class="hidden lg:block absolute top-0 right-0 w-[500px] h-[500px] bg-slate-50 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3 pointer-events-none -z-10"></div>

            <!-- Header Form Login -->
            <div class="mb-8 text-center lg:text-left mt-2 lg:mt-0">
                <span class="text-[10px] font-extrabold text-hanania-gold uppercase tracking-[0.15em] mb-2 block">Selamat Datang Kembali</span>
                <h3 class="text-[28px] lg:text-[32px] font-black text-slate-900 tracking-tight leading-none mb-2.5">Administrator</h3>
                <p class="text-[13px] text-slate-500 font-medium px-4 lg:px-0">Silakan masukkan kredensial Anda untuk masuk ke ruang kontrol (dashboard).</p>
            </div>
            
            <!-- Error Alert -->
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3.5 rounded-xl mb-6 flex items-start gap-3 shadow-sm">
                    <span class="material-symbols-outlined text-[20px] text-red-500 shrink-0">error</span>
                    <span class="text-[13px] font-bold leading-tight mt-0.5">{{ $errors->first() }}</span>
                </div>
            @endif

            <!-- Form Eksekusi -->
            <form action="{{ route('admin.login') }}" method="POST" class="space-y-5">
                @csrf
                
                <!-- Alamat Email Field -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Alamat Email</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-hanania-purple transition-colors">
                            <span class="material-symbols-outlined text-[20px]">mail</span>
                        </div>
                        <input type="email" name="email" value="{{ old('email') }}" 
                               class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-[14px] font-bold text-slate-900 placeholder-slate-400 focus:bg-white focus:border-hanania-purple focus:ring-4 focus:ring-hanania-purple/10 outline-none transition-all shadow-sm" 
                               placeholder="admin@hanania.com" required>
                    </div>
                </div>
                
                <!-- Kata Sandi Field -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wide">Kata Sandi</label>
                        <!-- Opsional: Link lupa password -->
                        <a href="#" class="text-[11px] font-bold text-hanania-purple hover:text-hanania-purple-dark transition-colors">Lupa sandi?</a>
                    </div>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-hanania-purple transition-colors">
                            <span class="material-symbols-outlined text-[20px]">lock</span>
                        </div>
                        <input type="password" name="password" 
                               class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-[14px] font-bold text-slate-900 placeholder-slate-400 focus:bg-white focus:border-hanania-purple focus:ring-4 focus:ring-hanania-purple/10 outline-none transition-all shadow-sm" 
                               placeholder="••••••••" required>
                    </div>
                </div>
                
                <!-- Submit Button -->
                <button type="submit" class="w-full btn-admin-primary py-4 mt-4 rounded-xl text-[14px] shadow-[0_4px_14px_rgba(97,57,143,0.25)] hover:shadow-[0_6px_20px_rgba(97,57,143,0.35)] transition-all group">
                    Masuk ke Sistem
                    <span class="material-symbols-outlined text-[18px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
                </button>
            </form>
            
            <!-- Footer Form -->
            <div class="mt-8 text-center border-t border-slate-100 pt-6">
                <p class="text-[11px] text-slate-400 font-bold tracking-wide uppercase">&copy; {{ date('Y') }} {{ $companyName }}</p>
            </div>
            
        </div>
    </div>

</body>
</html>