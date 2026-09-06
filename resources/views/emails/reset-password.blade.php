<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Kata Sandi - Hanania</title>
    <style>
        /* Reset & Base Murni Putih */
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #FFFFFF; margin: 0; padding: 0; -webkit-font-smoothing: antialiased; color: #333333; }
        
        /* Container Tanpa Border/Box Luar */
        .container { max-width: 600px; margin: 0 auto; padding: 40px 20px; background-color: #FFFFFF; }
        
        /* Header Minimalis */
        .header { text-align: center; padding-bottom: 25px; border-bottom: 2px solid #CBA358; margin-bottom: 35px; }
        .header h1 { color: #61398F; margin: 0; font-size: 28px; letter-spacing: 1px; font-weight: 900; text-transform: uppercase; }
        
        /* Typography yang Clean */
        .content { line-height: 1.8; font-size: 15px; }
        .content h2 { color: #61398F; font-size: 20px; margin-top: 0; margin-bottom: 25px; font-weight: 700; }
        .content p { margin-bottom: 20px; }
        
        .highlight { color: #61398F; font-weight: 700; }
        
        /* CTA Button (Gold Murni) */
        .btn-container { text-align: center; margin: 45px 0; }
        .btn { background-color: #CBA358; color: #ffffff; padding: 16px 40px; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 16px; display: inline-block; letter-spacing: 0.5px; box-shadow: 0 4px 15px rgba(203,163,88,0.3); }
        
        /* Footer Minimalis */
        .divider { border: none; border-top: 1px solid rgba(97,57,143,0.15); margin: 40px 0 25px 0; }
        
        .footer { text-align: center; font-size: 13px; color: #61398F; opacity: 0.8; line-height: 1.6; }
        .raw-link { word-break: break-all; font-size: 12px; color: #CBA358; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        
        <!-- HEADER (Logo Text Hanania Ungu, Garis Emas) -->
        <div class="header">
            <h1>HANANIA</h1>
        </div>
        
        <div class="content">
            <h2>Assalamu'alaikum, Jamaah Hanania!</h2>
            
            <p>Kami menerima permintaan untuk mereset kata sandi akun Anda. Jika Anda memang meminta pengaturan ulang ini, silakan klik tombol di bawah untuk membuat kata sandi yang baru:</p>
            
            <!-- TOMBOL AKSI (Emas) -->
            <div class="btn-container">
                <a href="{{ $url }}" class="btn">Reset Kata Sandi</a>
            </div>
            
            <p>Tautan reset kata sandi ini bersifat rahasia dan hanya berlaku selama <span class="highlight">60 menit</span> ke depan.</p>
            
            <p>Jika Anda merasa tidak pernah meminta reset kata sandi, mohon abaikan email ini. Keamanan akun Anda tetap terjaga bersama kami.</p>
            
            <p style="margin-top: 30px;">
                Wassalamu'alaikum,<br>
                <span class="highlight">Tim Hanania</span>
            </p>
            
            <!-- GARIS BAWAH -->
            <hr class="divider">
            
            <p style="font-size: 12px; text-align: center;">
                Jika tombol di atas tidak berfungsi, salin dan tempel URL berikut ke browser Anda:<br>
                <a href="{{ $url }}" class="raw-link">{{ $url }}</a>
            </p>
        </div>
        
        <!-- FOOTER -->
        <div class="footer">
            <strong>&copy; {{ date('Y') }} Hanania.</strong> Hak Cipta Dilindungi.<br>
            Jl. Raya Puncak KM 77, Cisarua, Bogor, Jawa Barat.
        </div>
        
    </div>
</body>
</html>