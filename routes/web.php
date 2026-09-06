<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\TravelPackageController;
use App\Http\Controllers\Customer\EnrollmentController;
use App\Http\Controllers\Customer\PaymentController;
use App\Http\Controllers\Customer\DocumentController;
use App\Http\Controllers\Customer\ReceiptController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Customer\TestimonialController;
use App\Http\Controllers\Customer\CancellationController;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PaymentVerificationController;
use App\Http\Controllers\Admin\DepartureController;
use App\Http\Controllers\Admin\GroupController;
use App\Http\Controllers\Admin\DocumentVerificationController;
use App\Http\Controllers\Admin\EnrollmentController as AdminEnrollmentController;
use App\Http\Controllers\Admin\TravelPackageController as AdminTravelPackageController;
use App\Http\Controllers\Admin\AppInformationController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\TestimonialController as AdminTestimonialController;
use App\Http\Controllers\Admin\CancellationController as AdminCancellationController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\AdminManagementController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\AuthController;

// AREA AUTENTIKASI
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
    Route::get('/register/verify-otp', [AuthController::class, 'showOtpForm'])->name('register.otp.form');
    Route::post('/register/verify-otp', [AuthController::class, 'verifyOtp'])->name('register.otp.submit');
    Route::get('/admin/login', [AuthController::class, 'showAdminLoginForm'])->name('admin.login');
    Route::post('/admin/login', [AuthController::class, 'adminLogin']);
    Route::get('/forgot-password', [PasswordResetController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'edit'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'update'])->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// AREA PUBLIK
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/paket', [TravelPackageController::class, 'index'])->name('packages.index');
Route::get('/paket/{travelPackage}', [TravelPackageController::class, 'show'])->name('packages.show');
Route::view('/kontak', 'contact')->name('contact');
Route::post('/chatbot/send', [ChatbotController::class, 'sendMessage'])->name('chatbot.send');
Route::view('/syarat-ketentuan', 'pages.terms')->name('terms');
Route::view('/tentang', 'about.index')->name('about.index');
Route::get('/galeri', [HomeController::class, 'galeri'])->name('pages.gallery');

// RUTE GLOBAL AUTHENTICATED
Route::middleware('auth')->group(function () {
    // Pos Checkpoint Notifikasi
    Route::get('/notifikasi/{id}/baca', function ($id) {
        $notif = auth()->user()->notifications()->find($id);
        if ($notif) {
            $notif->markAsRead();
            return redirect($notif->data['url'] ?? url('/'));
        }
        return back();
    })->name('notifikasi.baca');

    // 🔔 Tandai Semua Notifikasi Dibaca
    Route::get('/notifikasi/baca-semua', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    })->name('notifikasi.baca-semua');
});

// AREA CUSTOMER
Route::middleware(['auth'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/tabungan', [EnrollmentController::class, 'index'])->name('enrollments.index');
    Route::post('/tabungan/mulai', [EnrollmentController::class, 'store'])->name('enrollments.store')->middleware('throttle:3,1');
    Route::get('/tabungan/{enrollment}', [EnrollmentController::class, 'show'])->name('enrollments.show');
    
    Route::post('/tabungan/{enrollment}/bayar', [PaymentController::class, 'store'])->name('payments.store');
    Route::get('/pembayaran/{transaction}/lanjutkan', [PaymentController::class, 'resume'])->name('payments.resume');

    Route::get('/tabungan/{enrollment}/refund', [CancellationController::class, 'create'])->name('cancellations.create');
    Route::post('/tabungan/{enrollment}/refund', [CancellationController::class, 'store'])->name('cancellations.store');
    
    Route::get('/enrollments/{enrollment}/dokumen', [DocumentController::class, 'index'])->name('documents.index');
    Route::post('/enrollments/{enrollment}/dokumen', [DocumentController::class, 'store'])->name('documents.store');
    Route::get('/dokumen/{id}/preview', [DocumentController::class, 'preview'])->name('documents.preview');

    Route::get('/transaksi/{transaction}/kuitansi', [ReceiptController::class, 'download'])->name('receipts.download');
    Route::post('/testimonial', [TestimonialController::class, 'store'])->name('testimonials.store');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/password', [ProfileController::class, 'editPassword'])->name('profile.password');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::get('/profile/bank', [ProfileController::class, 'editBankAccount'])->name('profile.bank.edit');
    Route::put('/profile/bank', [ProfileController::class, 'updateBankAccount'])->name('profile.bank.update');
});

// AREA ADMIN
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/verifikasi-pembayaran', [PaymentVerificationController::class, 'index'])->name('payments.index');
    Route::post('/verifikasi-pembayaran/{transaction}/verify', [PaymentVerificationController::class, 'verify'])->name('payments.verify');
    Route::post('/verifikasi-pembayaran/{transaction}/reject', [PaymentVerificationController::class, 'reject'])->name('payments.reject');

    Route::resource('keberangkatan', DepartureController::class)->names('departures')->parameters(['keberangkatan' => 'departure'])->except(['create', 'edit', 'update', 'destroy']);
    Route::post('/keberangkatan/{departure}/assign-group', [DepartureController::class, 'assignGroup'])->name('departures.assign-group');
    Route::post('/keberangkatan/{departure}/depart', [DepartureController::class, 'depart'])->name('departures.depart');
    Route::post('/keberangkatan/{departure}/complete', [DepartureController::class, 'complete'])->name('departures.complete');
    Route::put('/keberangkatan/{id}/persiapan', [DepartureController::class, 'updatePersiapan'])->name('departures.update-persiapan');
    Route::delete('/departures/{departure}/remove-group/{group}', [DepartureController::class, 'removeGroup'])->name('departures.remove-group');
    Route::delete('/departures/{departure}', [DepartureController::class, 'destroy'])->name('departures.destroy');
    Route::get('/keberangkatan/{departure}/rombongan', [GroupController::class, 'indexByDeparture'])->name('departures.groups.index');

    Route::get('/groups', [GroupController::class, 'index'])->name('groups.index');
    Route::post('/groups', [GroupController::class, 'store'])->name('groups.store');
    Route::get('/groups/{group}', [GroupController::class, 'show'])->name('groups.show');
    Route::post('/groups/{group}/assign', [GroupController::class, 'assign'])->name('groups.assign');
    Route::delete('/groups/memberships/{membership}', [GroupController::class, 'remove'])->name('groups.remove');
    Route::get('/groups/{group}/manifest', [GroupController::class, 'manifest'])->name('groups.manifest');

    Route::resource('paket-travel', AdminTravelPackageController::class)->names('travel_packages')->parameters(['paket-travel' => 'travelPackage']);

    Route::get('/verifikasi-dokumen', [DocumentVerificationController::class, 'index'])->name('documents.index');
    Route::post('/verifikasi-dokumen/{document}/approve', [DocumentVerificationController::class, 'approve'])->name('documents.approve');
    Route::post('/verifikasi-dokumen/{document}/reject', [DocumentVerificationController::class, 'reject'])->name('documents.reject');
    Route::get('/documents/{id}/preview', [DocumentVerificationController::class, 'preview'])->name('documents.preview');
    
    Route::get('/pendaftaran', [AdminEnrollmentController::class, 'index'])->name('enrollments.index');
    Route::get('/pendaftaran/{enrollment}', [AdminEnrollmentController::class, 'show'])->name('enrollments.show');

    Route::get('/app-information', [AppInformationController::class, 'index'])->name('informations.index');
    Route::put('/app-information', [AppInformationController::class, 'update'])->name('informations.update');

    Route::get('/galeri', [GalleryController::class, 'index'])->name('galleries.index');
    Route::post('/galeri/kategori', [GalleryController::class, 'storeCategory'])->name('galleries.category.store');
    Route::post('/galeri', [GalleryController::class, 'store'])->name('galleries.store');
    Route::delete('/galeri/{gallery}', [GalleryController::class, 'destroy'])->name('galleries.destroy');

    Route::get('/testimoni', [AdminTestimonialController::class, 'index'])->name('testimonials.index');
    Route::post('/testimoni/{testimonial}/approve', [AdminTestimonialController::class, 'approve'])->name('testimonials.approve');
    Route::delete('/testimoni/{testimonial}', [AdminTestimonialController::class, 'destroy'])->name('testimonials.destroy');

    Route::get('/refund', [AdminCancellationController::class, 'index'])->name('cancellations.index');
    Route::post('/refund/{cancellation}/approve', [AdminCancellationController::class, 'approve'])->name('cancellations.approve');

    Route::get('/transactions/history', [TransactionController::class, 'history'])->name('transactions.history');
    Route::get('/transactions/print', [TransactionController::class, 'print'])->name('transactions.print');

    Route::get('/management', [AdminManagementController::class, 'index'])->name('management.index');
    Route::post('/management', [AdminManagementController::class, 'store'])->name('management.store');
    Route::put('/management/{user}', [AdminManagementController::class, 'update'])->name('management.update');
    Route::delete('/management/{user}', [AdminManagementController::class, 'destroy'])->name('management.destroy');
    Route::post('/management/{user}/reset-password', [AdminManagementController::class, 'resetPassword'])->name('management.reset_password');
});