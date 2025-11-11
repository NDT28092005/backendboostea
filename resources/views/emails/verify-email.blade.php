<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Xác nhận Email - Cinemaster</title>
    <style>
        /* Reset cơ bản */
        body, html {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #0d1117; /* Xanh đen huyền bí */
            color: #ffffff;
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #121926; /* Xanh đen nhạt hơn để nổi chữ */
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.6);
            border: 1px solid #1c1f2a;
        }

        .header {
            background: linear-gradient(90deg, #0f1b33, #1c2a4a);
            padding: 20px;
            text-align: center;
            font-size: 28px;
            font-weight: bold;
            letter-spacing: 1px;
            color: #00bfff; /* xanh neon */
        }

        .body {
            padding: 30px 20px;
            text-align: center;
            line-height: 1.6;
            font-size: 16px;
            color: #c0c0c0;
        }

        .body h2 {
            color: #00bfff;
            margin-bottom: 15px;
        }

        .verify-button {
            display: inline-block;
            padding: 15px 30px;
            margin: 20px 0;
            background-color: #00bfff;
            color: #0d1117;
            text-decoration: none;
            font-weight: bold;
            border-radius: 50px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,191,255,0.5);
        }

        .verify-button:hover {
            background-color: #009acd;
            box-shadow: 0 6px 20px rgba(0,191,255,0.7);
        }

        .footer {
            background-color: #0d1117;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #555;
        }

        .footer a {
            color: #00bfff;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            Cinemaster
        </div>
        <div class="body">
            <h2>Chào bạn!</h2>
            <p>Cảm ơn bạn đã đăng ký tài khoản Cinemaster.</p>
            <p>Vui lòng nhấn nút bên dưới để xác nhận email và hoàn tất đăng ký:</p>
            <a class="verify-button" href="{{ $verifyUrl }}">Xác nhận Email</a>
            <p>Nếu bạn không thực hiện đăng ký này, vui lòng bỏ qua email này.</p>
        </div>
        <div class="footer">
            © 2025 Cinemaster. Mọi quyền được bảo lưu.<br>
            <a href="http://localhost:5173">Truy cập website Cinemaster</a>
        </div>
    </div>
</body>
</html>