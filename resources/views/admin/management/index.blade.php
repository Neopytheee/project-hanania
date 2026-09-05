@extends('layouts.admin.app')

@section('header_title', 'Manajemen Admin & Hak Akses')

@section('content')
<div class="max-w-7xl mx-auto animate-fade-in-up pb-12">
    
    <!-- HEADER -->
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-[24px] font-black text-slate-900 tracking-tight flex items-center gap-2">
                <span class="material-symbols-outlined text-hanania-purple text-[28px]">admin_panel_settings</span> Manajemen Staff
            </h2>
            <p class="text-[13px] text-slate-500 font-medium mt-1">Atur akun bawahan dan tentukan batasan hak akses mereka di sistem.</p>
        </div>
    </div>

    <!-- ALERT MESSAGES -->
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-2xl mb-6 font-bold flex items-center gap-2 shadow-sm">
            <span class="material-symbols-outlined">check_circle</span> {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-rose-50 border border-rose-200 p-4 rounded-2xl mb-6 shadow-sm flex items-start gap-3">
            <span class="material-symbols-outlined text-rose-500 mt-0.5">error</span>
            <div>
                <h4 class="text-[13px] font-bold text-rose-900 mb-1">Gagal memproses data!</h4>
                <ul class="list-disc pl-4 text-[12px] font-medium text-rose-700 space-y-0.5">
                    @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- ========================================== -->
        <!-- KOLOM KIRI: FORM TAMBAH STAFF BARU -->
        <!-- ========================================== -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200 h-fit lg:sticky lg:top-24">
            <h3 class="text-[15px] font-black text-slate-800 border-b border-slate-100 pb-3 mb-5 flex items-center gap-2">
                <span class="material-symbols-outlined text-hanania-purple text-[20px]">person_add</span> Tambah Staff Baru
            </h3>
            <form action="{{ route('admin.management.store') }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 mb-2 uppercase tracking-wide">Alamat Email <span class="text-rose-500">*</span></label>
                    <div class="relative group">
                        <span class="material-symbols-outlined absolute left-4 top-3.5 text-[18px] text-slate-400">mail</span>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="staff@hananiatravel.com" class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-[13px] font-bold text-slate-800 focus:bg-white focus:border-hanania-purple focus:ring-4 focus:ring-hanania-purple/10 outline-none transition-all" required>
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-700 mb-2 uppercase tracking-wide">Password (Min. 6 Karakter) <span class="text-rose-500">*</span></label>
                    <div class="relative group">
                        <span class="material-symbols-outlined absolute left-4 top-3.5 text-[18px] text-slate-400">lock</span>
                        <input type="password" name="password" placeholder="••••••••" class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-[13px] font-bold text-slate-800 focus:bg-white focus:border-hanania-purple focus:ring-4 focus:ring-hanania-purple/10 outline-none transition-all" required>
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-700 mb-2 uppercase tracking-wide">Jabatan / Kekuatan <span class="text-rose-500">*</span></label>
                    <div class="relative group">
                        <select name="role_name" class="w-full pl-4 pr-10 py-3 bg-slate-50 border border-slate-200 rounded-xl text-[13px] font-bold text-slate-800 focus:bg-white focus:border-hanania-purple focus:ring-4 focus:ring-hanania-purple/10 outline-none transition-all appearance-none cursor-pointer" required>
                            <option value="" disabled selected>-- Pilih Jabatan --</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400">
                            <span class="material-symbols-outlined text-[18px]">expand_more</span>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full bg-hanania-purple hover:bg-hanania-purple-dark text-white font-bold py-3.5 rounded-xl text-[13px] flex justify-center items-center gap-2 transition-all shadow-md mt-2">
                    <span class="material-symbols-outlined text-[20px]">add_circle</span> Daftarkan Staff
                </button>
                                    <label class="block text-[11px] font-bold text-slate-700 mb-2 text-center uppercase tracking-wide">Password Default (hanania123) <span class="text-rose-500">*</span></label>
            </form>
        </div>

        <!-- ========================================== -->
        <!-- KOLOM KANAN: DAFTAR STAFF & ADMIN -->
        <!-- ========================================== -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto no-scrollbar">
                    <table class="w-full text-left text-[13px]">
                        <thead class="bg-slate-50 border-b border-slate-200 text-[11px] uppercase tracking-wider text-slate-500 font-extrabold">
                            <tr>
                                <th class="py-4 px-6">Akun Staff</th>
                                <th class="py-4 px-6">Jabatan Saat Ini</th>
                                <th class="py-4 px-6 text-center">Status</th>
                                <th class="py-4 px-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($admins as $admin)
                                <tr class="hover:bg-slate-50/50 transition-colors {{ $admin->status !== 'active' ? 'opacity-60 bg-slate-50' : '' }}">
                                    <!-- INFO AKUN -->
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-hanania-purple/10 text-hanania-purple border border-hanania-purple/20 flex items-center justify-center font-black text-[16px] uppercase shrink-0">
                                                {{ substr($admin->email, 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="font-bold text-slate-800">{{ $admin->email }}</p>
                                                @if(auth()->id() === $admin->id)
                                                    <span class="text-[9px] font-black text-amber-500 uppercase tracking-widest mt-0.5 inline-block">Anda Sendiri (Sedang Login)</span>
                                                @else
                                                    <p class="text-[11px] text-slate-400">Join: {{ $admin->created_at->format('d M Y') }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    <!-- JABATAN & GANTI JABATAN -->
                                    <td class="py-4 px-6">
                                        @if($admin->status === 'active')
                                            <!-- Jika bukan dirinya sendiri, tampilkan form dropdown ganti jabatan -->
                                            @if(auth()->id() !== $admin->id)
                                            <form action="{{ route('admin.management.update', $admin->id) }}" method="POST" class="flex items-center gap-2">
                                                @csrf
                                                @method('PUT')
                                                <select name="role_name" class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-[12px] font-bold text-slate-700 outline-none focus:border-hanania-purple shadow-sm cursor-pointer" onchange="this.form.submit()">
                                                    @foreach($roles as $role)
                                                        <option value="{{ $role->name }}" {{ $admin->hasRole($role->name) ? 'selected' : '' }}>
                                                            {{ $role->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </form>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 bg-hanania-gold/20 text-yellow-800 border border-hanania-gold/50 px-3 py-1.5 rounded-lg text-[11px] font-black uppercase tracking-wider">
                                                    <span class="material-symbols-outlined text-[14px]">local_police</span> {{ $admin->roles->first()->name ?? 'Belum ada jabatan' }}
                                                </span>
                                            @endif
                                        @else
                                            <span class="text-[11px] font-bold text-slate-400 italic">Akses Dicabut</span>
                                        @endif
                                    </td>

                                    <!-- STATUS -->
                                    <td class="py-4 px-6 text-center">
                                        @if($admin->status === 'active')
                                            <span class="inline-flex items-center justify-center w-6 h-6 bg-emerald-100 text-emerald-600 rounded-full border border-emerald-200" title="Aktif">
                                                <span class="material-symbols-outlined text-[14px]">check</span>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center justify-center w-6 h-6 bg-rose-100 text-rose-600 rounded-full border border-rose-200" title="Dinonaktifkan">
                                                <span class="material-symbols-outlined text-[14px]">block</span>
                                            </span>
                                        @endif
                                    </td>

                                    <!-- AKSI -->
                                    <td class="py-4 px-6 text-right">
                                        @if(auth()->id() !== $admin->id && $admin->status === 'active')
                                        <div class="flex items-center justify-end gap-2">
                                            
                                            <!-- Tombol Reset Password VIP -->
                                            <form action="{{ route('admin.management.reset_password', $admin->id) }}" method="POST" onsubmit="return confirm('Yakin ingin mereset password akun ini menjadi: hanania123 ?');">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center justify-center bg-white border border-amber-200 hover:bg-amber-50 text-amber-500 hover:text-amber-600 px-3 py-2 rounded-xl transition-all shadow-sm group" title="Reset Password (hanania123)">
                                                    <span class="material-symbols-outlined text-[16px] group-hover:rotate-180 transition-transform duration-500">lock_reset</span>
                                                </button>
                                            </form>

                                            <!-- Tombol Cabut Akses (Yang sudah ada) -->
                                            <form action="{{ route('admin.management.destroy', $admin->id) }}" method="POST" onsubmit="return confirm('YAKIN INGIN MENCABUT AKSES STAFF INI?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center justify-center bg-white border border-rose-200 hover:bg-rose-50 text-rose-500 hover:text-rose-600 px-3 py-2 rounded-xl transition-all shadow-sm group" title="Cabut Akses">
                                                    <span class="material-symbols-outlined text-[16px] group-hover:scale-110 transition-transform">person_off</span>
                                                </button>
                                            </form>
                                        </div>
                                        @else
                                            <span class="text-slate-300 material-symbols-outlined text-[20px]" title="Tidak ada aksi">remove</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-12 text-center text-slate-500 font-medium">Belum ada akun admin yang terdaftar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection