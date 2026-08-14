<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reset Password SIAKAD</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9fafb;
            color: #374151;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .header {
            background-color: #2da76b;
            color: #ffffff;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            letter-spacing: 1px;
        }
        .header p {
            margin: 5px 0 0 0;
            font-size: 14px;
            opacity: 0.9;
        }
        .content {
            padding: 40px 30px;
            line-height: 1.6;
        }
        .btn-container {
            text-align: center;
            margin: 30px 0;
        }
        .btn {
            display: inline-block;
            background-color: #2da76b;
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 28px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 16px;
        }
        .btn:hover {
            background-color: #289562;
        }
        .footer {
            background-color: #f3f4f6;
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
        }
        .link-text {
            word-break: break-all;
            color: #2da76b;
            font-size: 13px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>SIAKAD</h1>
        <p>Yayasan Al-Hijrah Jember</p>
    </div>
    
    <div class="content">
        <p>Halo,</p>
        <p>Anda menerima email ini karena kami menerima permintaan untuk mereset kata sandi (password) akun Anda.</p>
        
        <div class="btn-container">
            <a href="{{ url('http://localhost:5173/reset-password?token=' . $token . '&email=' . urlencode($email)) }}" class="btn">Reset Password</a>
        </div>
        
        <p>Tautan reset password ini akan <strong>kadaluarsa dalam 60 menit</strong>.</p>
        <p>Jika Anda tidak pernah meminta untuk mereset password, Anda dapat mengabaikan pesan ini. Akun Anda akan tetap aman.</p>
        <br>
        <p>Terima kasih,<br>Sistem Informasi Akademik (SIAKAD)</p>

        <hr style="border: none; border-top: 1px solid #e5e7eb; margin: 30px 0;">
        
        <p style="font-size: 12px; color: #9ca3af;">Jika Anda kesulitan menekan tombol "Reset Password", salin dan tempel URL berikut ke peramban web Anda:</p>
        <p class="link-text">{{ url('http://localhost:5173/reset-password?token=' . $token . '&email=' . urlencode($email)) }}</p>
    </div>

    <div class="footer">
        &copy; {{ date('Y') }} PT Prakarsa Kreatif Indonesia. Semua Hak Cipta Dilindungi.
    </div>
</div>

</body>
</html>
