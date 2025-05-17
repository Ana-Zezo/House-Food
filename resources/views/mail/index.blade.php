<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Your OTP Code - House-Food</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            background-color: #fff8f0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }

        .email-wrapper {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .email-header {
            background-color: #ff6b35;
            padding: 20px;
            text-align: center;
        }

        .email-header img {
            max-width: 120px;
        }

        .email-body {
            padding: 30px;
        }

        h2 {
            color: #2d3436;
        }

        p {
            font-size: 16px;
            color: #444;
            line-height: 1.6;
        }

        .otp-box {
            background-color: #fff0e0;
            color: #d63031;
            font-size: 28px;
            font-weight: bold;
            text-align: center;
            padding: 15px;
            margin: 20px 0;
            border-radius: 10px;
            letter-spacing: 4px;
        }

        .footer {
            background-color: #f7e8dd;
            padding: 15px;
            text-align: center;
            font-size: 12px;
            color: #888;
        }

        @media screen and (max-width: 600px) {
            .email-body {
                padding: 20px;
            }

            .otp-box {
                font-size: 24px;
            }
        }
    </style>
</head>

<body>
    <div class="email-wrapper">
        <div class="email-header">
            <img src="{{ asset('logo.jpg') }}" alt="House Food Logo">
        </div>
        <div class="email-body">
            <h2>Welcome to House-Food!</h2>
            <p>We're thrilled to have you on board. To complete your registration, please use the OTP below to verify
                your account.</p>
            <div class="otp-box">{{ $otp }}</div>
            <p><strong>Note:</strong> This OTP will expire shortly. For your security, never share your OTP with anyone.
            </p>
            <p>If you did not initiate this registration, you can safely ignore this email.</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} House-Food Egypt. All rights reserved.
        </div>
    </div>
</body>

</html>
