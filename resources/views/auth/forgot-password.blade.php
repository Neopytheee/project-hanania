<!DOCTYPE html>
<html lang="id">
<head>
    @php
        $companyName = \App\Models\AppInformation::getValue('company_name', 'Hanania');
    @endphp
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - {{ $companyName }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,1,0" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-hanania-purple-light min-h-screen flex items-center justify-center p-4 selection:bg-hanania-gold selection:text-white font-['Plus_Jakarta_Sans']">

    <main class="w-full max-w-md">
        <!-- Card -->
        <div class="bg-white rounded-[28px] border border-hanania-purple/10 shadow-xl overflow-hidden">
            
            <!-- Header Purple -->
            <div class="bg-hanania-purple-dark px-6 py-8 text-center">
                <div class="mx-auto w-14 h-14 rounded-2xl bg-white/10 border border-white/10 flex items-center justify-center mb-4">
                    <span class="material-symbols-outlined text-[29px] text-hanania-gold-light">lock_reset</span>
                </div>
                <h1 class="font-heading text-2xl font-extrabold text-white leading-tight">Lupa Password?</h1>
                <p class="mt-2 text-[13px] text-white/70 px-4 leading-relaxed">Masukkan email terdaftar. Kami akan mengirimkan instruksi untuk mereset password Anda.</p>
            </div>

            <!-- Form Area -->
            <div class="p-6 sm:p-8">
                @if (session('status'))
                    <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-start gap-3 text-emerald-700">
                        <span class="material-symbols-outlined text-[20px]">mark_email_read</span>
                        <p class="text-[12px] font-bold mt-0.5">{{ session('status') }}</p>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block mb-2 font-heading text-sm font-extrabold text-hanania-purple-dark">Alamat Email</label>
                        <div class="relative group">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-hanania-purple transition-colors text-[20px] pointer-events-none">mail</span>
                            <input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="Contoh: user@email.com" class="w-full rounded-2xl border border-hanania-purple/15 bg-gray-50/70 pl-12 pr-4 py-4 text-[14px] font-semibold text-hanania-purple-dark outline-none transition-all focus:border-hanania-purple focus:bg-white focus:ring-4 focus:ring-hanania-purple-light">
                        </div>
                        @error('email')
                            <p class="mt-2 flex items-center gap-1.5 text-xs font-bold text-red-500"><span class="material-symbols-outlined text-[15px]">error</span> {{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="btn-hanania-gold w-full rounded-2xl py-4 text-sm mt-2 group flex justify-center items-center gap-2">
                        Kirim Link Reset
                        <span class="material-symbols-outlined text-[20px] group-hover:translate-x-1 transition-transform">send</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Back Login -->
        <div class="text-center mt-6">
            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-sm font-bold text-hanania-purple hover:text-hanania-purple-dark transition-colors">
                <span class="material-symbols-outlined text-[17px]">arrow_back</span> Kembali ke Login
            </a>
        </div>
    </main>

</body>
</html>