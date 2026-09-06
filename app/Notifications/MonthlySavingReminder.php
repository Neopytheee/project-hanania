<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Enrollment;

class MonthlySavingReminder extends Notification 
// implements ShouldQueue
{
    use Queueable;

    protected $enrollment;

    public function __construct(Enrollment $enrollment)
    {
        $this->enrollment = $enrollment;
    }

    // 💡 SAKLAR JALUR NOTIFIKASI: Kita pakai Email & Database (Lonceng)
    public function via(object $notifiable): array
    {
        return ['mail', 'database']; 
    }

    // 📧 KONTEN UNTUK EMAIL (Dospem pasti suka lihat ini)
    public function toMail(object $notifiable): MailMessage
    {
        $nama = $this->enrollment->passenger_name ?? 'Jamaah';
        $paket = $this->enrollment->travelPackage->name ?? 'Umroh';
        $url = route('customer.enrollments.show', $this->enrollment->id);

return (new MailMessage)
                // 1. Judul Email yang muncul di kotak masuk Gmail
                ->subject('Waktunya Menabung Umroh!')
                
                // 2. Kata Sapaan di bagian atas
                ->greeting('Assalamu\'alaikum, ' . $notifiable->name . '!')
                
                // 3. Paragraf / Baris Isi Pesan (bisa ditambah banyak sesuai kebutuhan)
                ->line('Alhamdulillah, hari ini sudah tanggal 25. Semoga Bpk/Ibu dalam keadaan sehat dan rezekinya senantiasa dilancarkan. 🙏')
                ->line('Sekadar mengingatkan untuk menyisihkan sebagian rezeki bulan ini ke Tabungan ' . $this->enrollment->travelPackage->name . ' di Hanania.')
                
                // 4. Tombol Utama (Teks tombol & Link tujuannya)
                ->action('Cek Tabungan & Setor Sekarang', route('customer.enrollments.show', $this->enrollment->id))
                
                // 5. Paragraf tambahan di bawah tombol
                ->line('Yuk, selangkah lebih dekat menuju Baitullah!')
                
                // 6. Salam Penutup
                ->salutation("Salam hangat,\nManajemen Hanania Travel");
    }

    // 🔔 KONTEN UNTUK DATABASE (Muncul di lonceng notif UI website)
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'reminder',
            'title' => 'Pengingat Tabungan',
            'message' => 'Hari ini tanggal 25, saatnya menyisihkan sebagian rezeki untuk tabungan keberangkatan Anda.',
            'enrollment_id' => $this->enrollment->id,
            'url' => route('customer.enrollments.show', $this->enrollment->id)
        ];
    }
}