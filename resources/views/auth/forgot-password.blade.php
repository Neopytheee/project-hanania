<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Hanania Travel</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,1,0" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4 selection:bg-hanania-purple selection:text-white font-['Plus_Jakarta_Sans']">

    <div class="w-full max-w-md bg-white p-8 rounded-3xl shadow-lg border border-slate-100">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-hanania-purple/10 text-hanania-purple rounded-2xl flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-[32px]">lock_reset</span>
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900">Lupa Password?</h1>
            <p class="text-[13px] font-medium text-slate-500 mt-2">Masukkan email yang terdaftar. Kami akan mengirimkan instruksi untuk mereset password Anda.</p>
        </div>

        <!-- Alert Sukses Kirim Email -->
        @if (session('status'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 flex items-start gap-3 text-emerald-700">
                <span class="material-symbols-outlined text-[20px]">mark_email_read</span>
                <p class="text-[12px] font-bold mt-0.5">{{ session('status') }}</p>
            </div>
        @endif

        <!-- Form Request Email -->
        <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
            @csrf
            
            <div>
                <label class="block text-[11px] font-extrabold text-slate-500 uppercase tracking-widest mb-2">Alamat Email</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-4 top-3 text-slate-400 text-[20px]">mail</span>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="Contoh: admin@hanania.test" class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-[14px] font-bold text-slate-800 focus:bg-white focus:border-hanania-purple focus:ring-2 focus:ring-hanania-purple/20 outline-none transition-all">
                </div>
                @error('email')
                    <p class="text-[11px] font-bold text-red-500 mt-2 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">error</span> {{ $message }}
                    </p>
                @enderror
            </div>

            <button type="submit" class="w-full bg-hanania-purple hover:bg-hanania-purple-dark text-white py-3.5 rounded-xl font-bold text-[14px] transition-all shadow-md active:scale-95 flex items-center justify-center gap-2">
                Kirim Link Reset <span class="material-symbols-outlined text-[18px]">send</span>
            </button>
        </form>

        <div class="mt-8 text-center">
            <a href="{{ route('login') }}" class="inline-flex items-center gap-1 text-[13px] font-bold text-slate-500 hover:text-hanania-purple transition-colors">
                <span class="material-symbols-outlined text-[16px]">arrow_back</span> Kembali ke Login
            </a>
        </div>
    </div>

</body>
</html>