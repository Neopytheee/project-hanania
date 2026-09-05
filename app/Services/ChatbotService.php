<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Testimonial;
use App\Models\Document;
use App\Models\CancellationRequest;

class ChatbotService
{
    public function processMessage(string $message, $user): string
    {
        $systemPrompt = $this->buildSystemPrompt($user);
        $history = session('chat_history', []);

        $history[] = [
            'role' => 'user',
            'parts' => [['text' => $message]]
        ];

        $reply = $this->callGeminiApi($systemPrompt, $history);

        $history[] = [
            'role' => 'model',
            'parts' => [['text' => $reply]]
        ];

        session(['chat_history' => $history]);

        return $reply;
    }

    private function buildSystemPrompt($user): string
    {
        $prompt = "Kamu adalah Hanania AI, asisten virtual cerdas dan ramah dari Hanania Travel. Jawablah dengan sopan, bernada islami, dan ringkas. Gunakan bold (**) untuk kata penting. Jangan berhalusinasi. Waktu saat ini: " . now()->format('d F Y H:i') . ".\n\n";

        // ==========================================
        // 🚀 INJEKSI DATA PAKET UMROH (KATALOG)
        // ==========================================
        // Ambil 3 paket terbaru/aktif dari database
        $paketTerbaru = \App\Models\TravelPackage::latest()->take(3)->get();
        
        if ($paketTerbaru->isNotEmpty()) {
            $prompt .= "--- REKOMENDASI PAKET UMROH TERBARU ---\n";
            foreach ($paketTerbaru as $paket) {
                // Asumsi ada kolom 'name' dan 'estimated_price' di tabel travel_packages
                $harga = number_format($paket->estimated_price ?? 0, 0, ',', '.');
                $prompt .= "- Paket: {$paket->name} | Estimasi Harga: Rp {$harga}\n";
            }
            
            // Masukkan link halaman katalog bosku (sesuaikan dengan nama route bosku)
            $linkKatalog = url('/katalog-paket'); 
            $prompt .= "Jika user menanyakan paket, sebutkan rekomendasi di atas secara natural dan berikan link ini agar mereka bisa melihat katalog lengkap: {$linkKatalog}\n\n";
        }
        // ==========================================
        // ROLE 1: TAMU (Guest)
        // ==========================================
        if (!$user) {
            $prompt .= "Konteks: Kamu berbicara dengan Tamu (Guest).\n";
            $prompt .= "Tugasmu: Berikan info umum travel dan arahkan mereka untuk mendaftar di web.\n";
            
            $testimoni = Testimonial::where('is_approved', true)->latest()->take(3)->get();
            if ($testimoni->isNotEmpty()) {
                $prompt .= "--- TESTIMONI JAMAAH KAMI ---\n";
                foreach ($testimoni as $testi) {
                    $bintang = str_repeat("⭐", $testi->rating);
                    $prompt .= "- \"{$testi->content}\" ({$bintang})\n";
                }
                $prompt .= "Gunakan testimoni di atas jika tamu ragu atau menanyakan ulasan.\n";
            }
        } 
        
        // ==========================================
        // ROLE 2: ADMIN / SUPER ADMIN
        // ==========================================
        elseif ($user->hasAnyRole(['Super Admin', 'Admin Operasional', 'Admin Keuangan'])) {
            $prompt .= "Konteks: Kamu berbicara dengan Staff/Admin (Nama: {$user->name}, Role: {$user->role}).\n";
            $prompt .= "--- TUGAS WAJIB HARI INI (MENUNGGU VERIFIKASI) ---\n";

            // 💰 JALUR KHUSUS KEUANGAN & SUPER ADMIN
            if ($user->hasAnyRole(['Super Admin', 'Admin Keuangan'])) {
                $pembayaranPending = \App\Models\PaymentTransaction::where('status', 'pending')->count();
                $prompt .= "- [KEUANGAN] Pembayaran Cicilan/DP: {$pembayaranPending} transaksi butuh ACC\n";
            }

            // 🧳 JALUR KHUSUS OPERASIONAL & SUPER ADMIN
            if ($user->hasAnyRole(['Super Admin', 'Admin Operasional'])) {
                $totalJamaah = \App\Models\User::where('role', 'customer')->count();
                $pendaftaranBaru = \App\Models\Enrollment::where('status', 'pending')->count() ?? 0;
                $dokumenPending = Document::where('status', 'pending')->count();
                $batalPending = CancellationRequest::where('status', 'pending')->count();

                $prompt .= "- [OPERASIONAL] Total Akun Jamaah di Sistem: {$totalJamaah} akun\n";
                $prompt .= "- [OPERASIONAL] Pendaftaran Baru: {$pendaftaranBaru} pendaftaran\n";
                $prompt .= "- [OPERASIONAL] Dokumen Jamaah (KTP/Paspor dll): {$dokumenPending} file butuh cek\n";
                $prompt .= "- [OPERASIONAL] Request Pembatalan/Refund: {$batalPending} request\n";
            }

            // 📋 5 DATA PENDAFTARAN TERBARU (Ditampilkan ke semua admin untuk konteks umum)
            $recentEnrollments = \App\Models\Enrollment::with('travelPackage')->latest()->take(5)->get();
            $detailPendaftaran = "";
            if($recentEnrollments->isNotEmpty()) {
                foreach($recentEnrollments as $enr) {
                    $namaPaket = $enr->travelPackage ? $enr->travelPackage->name : 'Belum pilih paket';
                    $tgl = $enr->created_at ? $enr->created_at->format('d M Y') : '-';
                    $status = $enr->statusText(); 
                    $detailPendaftaran .= "- Tgl: {$tgl} | Penumpang: {$enr->passenger_name} | Paket: {$namaPaket} | Status: {$status}\n";
                }
            } else {
                $detailPendaftaran = "- Belum ada data pendaftaran masuk.\n";
            }

            $prompt .= "--- 5 PENDAFTARAN TERBARU ---\n{$detailPendaftaran}\n";
            $prompt .= "Tugas Penting: Kamu adalah asisten AI manajerial. Sesuaikan jawabanmu dengan jabatan (Role) user yang sedang login. Ingatkan admin jika ada tugas yang 'pending' agar segera dicek. Jangan pernah menyebarkan data ini ke customer biasa.";
        } 
        
        // ==========================================
        // ROLE 3: JAMAAH (Customer)
        // ==========================================
        else {
            $prompt .= "Konteks: Kamu berbicara dengan Jamaah/Customer (Nama: {$user->name}).\n";
            $customer = \App\Models\Customer::where('user_id', $user->id)->first();
            
            if ($customer) {
                // Eager Load yang lebih efisien karena pakai relasi activeGroup buatan bosku
                $enrollments = \App\Models\Enrollment::with([
                    'travelPackage', 
                    'paymentPlan.transactions',
                    'documents',
                    'cancellationRequests',
                    'activeGroup' 
                ])->where('customer_id', $customer->id)->get();
                
                if ($enrollments->isNotEmpty()) {
                    $prompt .= "--- REKAP DATA JAMAAH ---\n";
                    $prompt .= "Nama Customer: {$customer->name}\n";
                    $prompt .= "Nomor Customer: {$customer->customer_number}\n\n";
                    
                    foreach ($enrollments as $index => $enr) {
                        $namaPaket = $enr->travelPackage ? $enr->travelPackage->name : 'Paket belum dipilih';
                        $urutan = $index + 1;
                        
                        // MENGGUNAKAN STATUS TEXT BAHASA INDONESIA BUATAN BOSKU
                        $statusIndo = $enr->statusText();

                        $prompt .= "[Pendaftaran Ke-{$urutan} | No. Registrasi: {$enr->enrollment_number}]\n";
                        $prompt .= "- Nama Penumpang: {$enr->passenger_name} (Hubungan: {$enr->relationship})\n";
                        $prompt .= "- Paket Pilihan: {$namaPaket}\n";
                        $prompt .= "- Status Pendaftaran: {$statusIndo}\n"; // Hasilnya: "Target Tercapai", "Lunas", dll.

                        // --- INFO KEUANGAN (MENGGUNAKAN HELPER MODEL) ---
                        $plan = $enr->paymentPlan;
                        if ($plan) {
                            $target = $plan->final_target_amount ?? $plan->estimated_target_amount ?? 0;
                            
                            // LANGSUNG PANGGIL FUNGSI SAKTI BOSKU!
                            $totalTerbayar = $enr->verifiedPaymentTotal(); 
                            $sisaTagihan = $enr->outstandingAmount();

                            $prompt .= "- Total Tagihan: Rp " . number_format($target, 0, ',', '.') . "\n";
                            $prompt .= "- Uang Masuk (Lunas): Rp " . number_format($totalTerbayar, 0, ',', '.') . "\n";
                            $prompt .= "- Sisa Tagihan: Rp " . number_format($sisaTagihan, 0, ',', '.') . "\n";
                        }

                        // --- INFO DOKUMEN ---
                        if ($enr->documents && $enr->documents->isNotEmpty()) {
                            $prompt .= "- Dokumen Terunggah:\n";
                            foreach ($enr->documents as $doc) {
                                $prompt .= "  * {$doc->document_type}: {$doc->status} " . ($doc->rejection_reason ? "(Ditolak: {$doc->rejection_reason})" : "") . "\n";
                            }
                        }

                        // --- INFO ROMBONGAN (Pakai activeGroup) ---
                        if ($enr->activeGroup) {
                            $prompt .= "- Rombongan/Grup: Bergabung di grup '{$enr->activeGroup->name}' (Kode: {$enr->activeGroup->code}).\n";
                        }

                        // --- INFO PEMBATALAN ---
                        if ($enr->cancellationRequests && $enr->cancellationRequests->isNotEmpty()) {
                            $cancel = $enr->cancellationRequests->sortByDesc('created_at')->first();
                            $prompt .= "- Peringatan: Ada pengajuan pembatalan ({$cancel->cancellation_type}) dengan status '{$cancel->status}'.\n";
                        }
                        
                        $prompt .= "\n";
                    }
                    $prompt .= "---------------------------------\n";
                    $prompt .= "Tugas Penting: Gunakan data di atas untuk menjawab detail dokumen, keuangan, atau rombongan jamaah. Jawab dengan awam, islami, dan ramah. Selalu panggil user dengan nama ({$customer->name}). DILARANG KERAS menggunakan istilah teknis database atau kode Inggris.";
                } else {
                    $prompt .= "Status: Jamaah belum mendaftar paket apapun.\n";
                }
            } else {
                 $prompt .= "Status: Jamaah belum melengkapi data profil.\n";
            }
        }

        return $prompt;
    }

    private function callGeminiApi(string $systemPrompt, array $history): string
    {
        $apiKey = env('GEMINI_API_KEY');
        // KITA PAKAI 1.5-FLASH YA BOSKU
        $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-3.5-flash-lite:generateContent?key=' . $apiKey;

        try {
            $response = Http::post($url, [
                'system_instruction' => [
                    'parts' => [['text' => $systemPrompt]]
                ],
                'contents' => $history 
            ]);

            if ($response->successful()) {
                return $response->json('candidates.0.content.parts.0.text');
            }

            Log::error('Gemini API Error: ' . $response->body());
            return "Waduh, satelit AI kami sedang maintenance sebentar. Coba beberapa saat lagi ya!";
            
        } catch (\Exception $e) {
            Log::error('Gemini API Exception: ' . $e->getMessage());
            return "Maaf, koneksi ke otak AI terputus. Pastikan koneksi internet stabil.";
        }
    }
}