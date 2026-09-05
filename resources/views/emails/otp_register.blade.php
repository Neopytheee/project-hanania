<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Verifikasi Akun - Hanania Travel</title>
</head>

<body style="margin:0; padding:0; background-color:#F5EEFC; font-family:Arial, Helvetica, sans-serif; color:#4A2B6E;">

    <table
        role="presentation"
        width="100%"
        cellspacing="0"
        cellpadding="0"
        border="0"
        style="width:100%; margin:0; padding:0; background-color:#F5EEFC;"
    >
        <tr>
            <td align="center" style="padding:32px 16px;">

                <table
                    role="presentation"
                    width="100%"
                    cellspacing="0"
                    cellpadding="0"
                    border="0"
                    style="width:100%; max-width:600px; background-color:#ffffff; border-radius:20px; overflow:hidden;"
                >

                    {{-- =========================================
                        HEADER
                    ========================================== --}}
                    <tr>
                        <td
                            align="center"
                            style="background-color:#4A2B6E; padding:34px 24px 30px;"
                        >

                            <div
                                style="
                                    width:56px;
                                    height:56px;
                                    margin:0 auto 16px;
                                    background-color:#61398F;
                                    border-radius:16px;
                                    text-align:center;
                                    line-height:56px;
                                "
                            >
                                <span
                                    style="
                                        color:#DFBE77;
                                        font-size:26px;
                                        font-weight:bold;
                                    "
                                >
                                    ✦
                                </span>
                            </div>

                            <h1
                                style="
                                    margin:0;
                                    color:#ffffff;
                                    font-size:24px;
                                    line-height:32px;
                                    font-weight:700;
                                "
                            >
                                Verifikasi Akun
                            </h1>

                            <p
                                style="
                                    margin:8px 0 0;
                                    color:#ffffff;
                                    opacity:0.75;
                                    font-size:13px;
                                    line-height:20px;
                                "
                            >
                                Langkah terakhir untuk mengaktifkan akun Anda
                            </p>

                        </td>
                    </tr>


                    {{-- =========================================
                        CONTENT
                    ========================================== --}}
                    <tr>
                        <td style="padding:36px 28px 32px;">

                            <p
                                style="
                                    margin:0 0 18px;
                                    color:#4A2B6E;
                                    font-size:16px;
                                    line-height:26px;
                                "
                            >
                                Assalamu'alaikum Wr. Wb.
                            </p>


                            <p
                                style="
                                    margin:0 0 14px;
                                    color:#4A2B6E;
                                    font-size:14px;
                                    line-height:23px;
                                "
                            >
                                Terima kasih telah mendaftar di
                                <strong style="color:#61398F;">
                                    Hanania Travel
                                </strong>.
                            </p>


                            <p
                                style="
                                    margin:0 0 24px;
                                    color:#666666;
                                    font-size:14px;
                                    line-height:23px;
                                "
                            >
                                Untuk menyelesaikan proses pendaftaran dan
                                mengaktifkan akun Anda, masukkan 6 digit kode
                                OTP berikut pada halaman verifikasi.
                            </p>


                            {{-- OTP BOX --}}
                            <table
                                role="presentation"
                                width="100%"
                                cellspacing="0"
                                cellpadding="0"
                                border="0"
                                style="margin:0 0 24px;"
                            >
                                <tr>
                                    <td
                                        align="center"
                                        style="
                                            background-color:#F5EEFC;
                                            border:1px solid #E7DAF2;
                                            border-radius:16px;
                                            padding:24px 16px;
                                        "
                                    >

                                        <p
                                            style="
                                                margin:0 0 8px;
                                                color:#61398F;
                                                font-size:10px;
                                                font-weight:bold;
                                                letter-spacing:2px;
                                                text-transform:uppercase;
                                            "
                                        >
                                            Kode Verifikasi
                                        </p>

                                        <div
                                            style="
                                                color:#4A2B6E;
                                                font-size:34px;
                                                line-height:42px;
                                                font-weight:700;
                                                letter-spacing:10px;
                                            "
                                        >
                                            {{ $otp }}
                                        </div>

                                    </td>
                                </tr>
                            </table>


                            {{-- WARNING --}}
                            <table
                                role="presentation"
                                width="100%"
                                cellspacing="0"
                                cellpadding="0"
                                border="0"
                                style="margin-bottom:24px;"
                            >
                                <tr>
                                    <td
                                        style="
                                            border-left:4px solid #CBA358;
                                            background-color:#FBF8FF;
                                            padding:14px 16px;
                                        "
                                    >

                                        <p
                                            style="
                                                margin:0;
                                                color:#5A5A5A;
                                                font-size:12px;
                                                line-height:20px;
                                            "
                                        >
                                            <strong style="color:#4A2B6E;">
                                                Penting:
                                            </strong>
                                            Kode ini hanya berlaku selama
                                            <strong>10 menit</strong>.
                                            Jangan berikan kode ini kepada
                                            siapapun, termasuk pihak travel.
                                        </p>

                                    </td>
                                </tr>
                            </table>


                            <p
                                style="
                                    margin:0 0 6px;
                                    color:#666666;
                                    font-size:14px;
                                    line-height:23px;
                                "
                            >
                                Jika Anda tidak merasa membuat akun,
                                abaikan email ini.
                            </p>


                            <p
                                style="
                                    margin:24px 0 0;
                                    color:#4A2B6E;
                                    font-size:14px;
                                    line-height:23px;
                                "
                            >
                                Wassalamu'alaikum Wr. Wb.
                                <br>
                                <strong>
                                    Tim Hanania Travel
                                </strong>
                            </p>

                        </td>
                    </tr>


                    {{-- =========================================
                        FOOTER
                    ========================================== --}}
                    <tr>
                        <td
                            align="center"
                            style="
                                border-top:1px solid #EEE7F5;
                                padding:20px 24px;
                                background-color:#FFFFFF;
                            "
                        >

                            <p
                                style="
                                    margin:0;
                                    color:#999999;
                                    font-size:11px;
                                    line-height:18px;
                                "
                            >
                                Email ini dikirim secara otomatis.
                                Mohon tidak membalas email ini.
                            </p>

                            <p
                                style="
                                    margin:6px 0 0;
                                    color:#CBA358;
                                    font-size:11px;
                                    font-weight:bold;
                                "
                            >
                                {{ $companyName ?? 'Hanania Travel' }}
                            </p>

                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>

</html>