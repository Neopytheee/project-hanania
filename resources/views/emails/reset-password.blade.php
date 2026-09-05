<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reset Kata Sandi</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8fafc; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 40px auto; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .header { background-color: #61398F; padding: 30px 20px; text-align: center; }
        .header h1 { color: #ffffff; margin: 0; font-size: 24px; letter-spacing: 1px; }
        .content { padding: 40px 30px; color: #333333; line-height: 1.6; }
        .content p { margin-bottom: 20px; font-size: 15px; }
        .btn-container { text-align: center; margin: 35px 0; }
        .btn { background-color: #CBA358; color: #ffffff; padding: 14px 30px; text-decoration: none; border-radius: 50px; font-weight: bold; font-size: 16px; display: inline-block; }
        .footer { background-color: #f1f5f9; padding: 20px; text-align: center; font-size: 12px; color: #64748b; border-top: 1px solid #e2e8f0; }
        .raw-link { word-break: break-all; font-size: 12px; color: #61398F; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>HANANIA TRAVEL</h1>
        </div>
        
        <div class="content">
            <p><strong>Assalamu'alaikum, Jamaah Hanania!</strong></p>
            
            <p>Kami menerima permintaan untuk mereset kata sandi akun Anda. Jika Anda memang meminta reset ini, silakan klik tombol di bawah untuk membuat kata sandi baru:</p>
            
            <div class="btn-container">
                <a href="{{ $url }}" class="btn">Reset Kata Sandi Sekarang</a>
            </div>
            
            <p><em>Tautan reset kata sandi ini hanya berlaku selama 60 menit ke depan.</em></p>
            
            <p>Jika Anda tidak pernah meminta reset kata sandi, abaikan saja email ini. Akun Anda tetap aman bersama kami.</p>
            
            <p>Wassalamu'alaikum,<br><strong>Tim Hanania Travel</strong></p>
            
            <hr style="border:none; border-top: 1px dashed #cbd5e1; margin: 30px 0;">
            
            <p style="font-size: 12px; color: #64748b;">
                Jika Anda kesulitan mengklik tombol "Reset Kata Sandi", salin dan tempel URL di bawah ini ke browser web Anda:<br>
                <a href="{{ $url }}" class="raw-link">{{ $url }}</a>
            </p>
        </div>
        
        <div class="footer">
            &copy; {{ date('Y') }} Hanania Travel. Hak Cipta Dilindungi.<br>
            Jl. Raya Puncak KM 77, Cisarua, Bogor.
        </div>
    </div>
</body>
</html>