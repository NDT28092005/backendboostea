<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận Email - Boostea</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }

        .email-header {
            background: linear-gradient(135deg, #5D7B6F 0%, #A4C3A2 100%);
            padding: 50px 20px;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .email-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .email-header::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -5%;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
        }

        .logo {
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 10px;
            position: relative;
            z-index: 1;
        }

        .tagline {
            font-size: 16px;
            opacity: 0.95;
            position: relative;
            z-index: 1;
        }

        .email-body {
            padding: 50px 40px;
            text-align: center;
            line-height: 1.8;
        }

        .email-body h2 {
            color: #5D7B6F;
            font-size: 28px;
            margin-bottom: 20px;
            font-weight: 700;
        }

        .email-body p {
            color: #666;
            font-size: 16px;
            margin-bottom: 20px;
        }

        .welcome-box {
            background: linear-gradient(135deg, #EAE7D6 0%, #D7F9FA 100%);
            border-left: 4px solid #5D7B6F;
            padding: 25px;
            margin: 30px 0;
            border-radius: 8px;
            text-align: left;
        }

        .welcome-box p {
            margin: 0;
            color: #333;
            font-weight: 500;
        }

        .verify-button {
            display: inline-block;
            padding: 18px 45px;
            margin: 30px 0;
            background: linear-gradient(135deg, #5D7B6F 0%, #A4C3A2 100%);
            color: white;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            border-radius: 50px;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(93, 123, 111, 0.3);
        }

        .verify-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(93, 123, 111, 0.4);
        }

        .info-box {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin: 30px 0;
            text-align: left;
        }

        .info-box p {
            margin: 10px 0;
            font-size: 14px;
            color: #666;
        }

        .info-box strong {
            color: #5D7B6F;
        }

        .divider {
            height: 1px;
            background: linear-gradient(to right, transparent, #c4d7d0, transparent);
            margin: 30px 0;
        }

        .email-footer {
            background-color: #f8f9fa;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e0e0e0;
        }

        .email-footer p {
            color: #999;
            font-size: 14px;
            margin: 5px 0;
        }

        .email-footer .brand {
            color: #5D7B6F;
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 10px;
        }

        .email-footer a {
            color: #5D7B6F;
            text-decoration: none;
            font-weight: 500;
        }

        .email-footer a:hover {
            text-decoration: underline;
        }

        .social-links {
            margin: 20px 0;
        }

        .social-links a {
            display: inline-block;
            margin: 0 10px;
            color: #5D7B6F;
            text-decoration: none;
        }

        @media only screen and (max-width: 600px) {
            .email-body {
                padding: 30px 20px;
            }

            .email-header {
                padding: 40px 20px;
            }

            .logo {
                font-size: 28px;
            }

            .email-body h2 {
                font-size: 24px;
            }

            .verify-button {
                padding: 15px 35px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <div class="logo">🍃 Boostea</div>
            <div class="tagline">Trà Trái Cây Sấy Thăng Hoa</div>
        </div>
        
        <div class="email-body">
            <h2>Xin chào!</h2>
            <p>Cảm ơn bạn đã đăng ký tài khoản tại <strong>Boostea</strong>!</p>
            
            <div class="welcome-box">
                <p>🎉 Chúng tôi rất vui khi được chào đón bạn đến với cộng đồng những người yêu thích trà trái cây sấy thăng hoa tự nhiên và tốt cho sức khỏe.</p>
            </div>
            
            <p>Để hoàn tất đăng ký và bắt đầu trải nghiệm mua sắm tại Boostea, vui lòng xác nhận địa chỉ email của bạn bằng cách nhấn nút bên dưới:</p>
            
            <a href="{{ $verifyUrl }}" class="verify-button">Xác Nhận Email</a>
            
            <div class="info-box">
                <p><strong>💡 Lưu ý:</strong></p>
                <p>• Link xác nhận sẽ hết hạn sau 24 giờ</p>
                <p>• Nếu nút không hoạt động, bạn có thể copy và dán link sau vào trình duyệt:</p>
                <p style="word-break: break-all; color: #5D7B6F; font-size: 12px; margin-top: 10px;">{{ $verifyUrl }}</p>
            </div>
            
            <div class="divider"></div>
            
            <p style="font-size: 14px; color: #999;">
                Nếu bạn không thực hiện đăng ký này, vui lòng bỏ qua email này. 
                Tài khoản sẽ không được kích hoạt nếu email không được xác nhận.
            </p>
        </div>
        
        <div class="email-footer">
            <p class="brand">Boostea</p>
            <p>Trà Trái Cây Sấy Thăng Hoa</p>
            <p>Email: support@boostea.vn | Hotline: 0946403788</p>
            <div class="social-links">
                <a href="#">Facebook</a> | 
                <a href="#">Instagram</a>
            </div>
            <p style="margin-top: 20px; font-size: 12px;">
                © 2025 Boostea. All rights reserved.
            </p>
            <p style="font-size: 12px;">
                <a href="http://localhost:5173">Truy cập website Boostea</a>
            </p>
        </div>
    </div>
</body>
</html>