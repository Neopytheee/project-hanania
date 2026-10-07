<?php

namespace App\Http\Controllers;

use App\Mail\RegisterOtpMail;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    // ==========================================
    // TAMPILAN FORM (YANG TADI HILANG KITA BALIKIN)
    // ==========================================
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showAdminLoginForm()
    {
        return view('auth.admin-login');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // ==========================================
    // PROSES LOGIN CUSTOMER
    // ==========================================
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if (Auth::user()->role === 'customer') {
                return redirect()->route('customer.dashboard');
            }

            Auth::logout();

            return back()->withErrors(['email' => 'Silakan gunakan halaman login khusus Admin.'])->onlyInput('email');
        }

        return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
    }

    // ==========================================
    // PROSES LOGIN ADMIN
    // ==========================================
    public function adminLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            Auth::logout();

            return back()->withErrors(['email' => 'Tidak ada akses.'])->onlyInput('email');
        }

        return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
    }

    // ==========================================
    // TAHAP 1: PROSES REGISTER (Kirim OTP & Simpan ke Cache)
    // ==========================================
    public function register(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'name' => 'required|string|max:255',
            'nik' => 'required|string|size:16',
            'phone' => 'required|string|max:20',
            'birth_date' => 'required|date',
            'gender' => 'required|in:male,female',
            'address' => 'required|string',
            'emergency_contact_name' => 'required|string',
            'emergency_contact_phone' => 'required|string',
            'bank_name' => 'nullable|string|max:100',
            'bank_account_number' => 'nullable|string|max:50',
            'bank_account_name' => 'nullable|string|max:255',
        ]);

        // Generate OTP
        $otp = random_int(100000, 999999);
        $validated['otp'] = $otp;

        // Simpan ke Cache selama 10 Menit
        Cache::put('register_otp_'.$request->email, $validated, now()->addMinutes(10));

        Mail::to($request->email)->send(new RegisterOtpMail($otp));

        return redirect()->route('register.otp.form', ['email' => $request->email])
            ->with('success', 'Alhamdulillah, pendaftaran tahap 1 berhasil! Silakan cek email Anda untuk kode OTP.');
    }

    // ==========================================
    // TAHAP 2: TAMPILAN FORM INPUT OTP
    // ==========================================
    public function showOtpForm(Request $request)
    {
        $email = $request->query('email');

        if (! $email || ! Cache::has('register_otp_'.$email)) {
            return redirect()->route('register')
                ->withErrors('Sesi pendaftaran tidak valid atau sudah kedaluwarsa. Silakan daftar ulang.');
        }

        return view('auth.verify-otp', compact('email'));
    }

    // ==========================================
    // TAHAP 3: VERIFIKASI OTP & SIMPAN KE DATABASE
    // ==========================================
    public function verifyOtp(Request $request, AuthService $authService)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|numeric',
        ]);

        $cachedData = Cache::get('register_otp_'.$request->email);

        if (! $cachedData || $cachedData['otp'] != $request->otp) {
            return back()->withErrors(['otp' => 'Kode OTP salah! Silakan periksa kembali email Anda.']);
        }

        // Masukkan ke Database melalui Service
        $user = $authService->registerCustomer($cachedData);

        // Hapus Cache
        Cache::forget('register_otp_'.$request->email);

        // Login & Redirect
        Auth::login($user);

        return redirect()->route('packages.index')
            ->with('success', 'Verifikasi berhasil! Akun Anda telah aktif, silakan pilih paket tabungan.');
    }

    // ==========================================
    // PROSES LOGOUT
    // ==========================================
    public function logout(Request $request)
    {
        $role = null;
        if (Auth::check()) {
            $role = Auth::user()->role;
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($role === 'admin') {
            return redirect('/admin/login');
        }

        return redirect('/login');
    }
}
