<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification Code</title>
    <style>
        /* Reset & Basics */
        body { margin: 0; padding: 0; background-color: #f3f4f6; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #333333; }
        table { border-spacing: 0; width: 100%; }
        td { padding: 0; }
        img { border: 0; }
        
        /* Container */
        .wrapper { width: 100%; table-layout: fixed; background-color: #f3f4f6; padding-bottom: 40px; }
        .main { background-color: #ffffff; margin: 0 auto; width: 100%; max-width: 600px; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        
        /* Header */
        .header { background-color: #4F46E5; padding: 25px; text-align: center; }
        .header h1 { color: #ffffff; margin: 0; font-size: 24px; font-weight: 600; letter-spacing: 1px; }
        
        /* Content */
        .content { padding: 30px 40px; text-align: center; }
        .content h2 { margin-top: 0; color: #1f2937; font-size: 20px; font-weight: 700; }
        .content p { color: #6b7280; font-size: 16px; line-height: 1.5; margin-bottom: 20px; }
        
        /* OTP Box */
        .otp-box { margin: 30px 0; }
        .otp-code { 
            font-size: 32px; 
            font-weight: 800; 
            color: #4F46E5; 
            letter-spacing: 6px; 
            background-color: #eef2ff; 
            border: 1px dashed #4F46E5; 
            padding: 15px 30px; 
            border-radius: 8px; 
            display: inline-block;
        }
        
        /* Warning/Expiry */
        .expiry { font-size: 14px; color: #ef4444; font-weight: 500; margin-top: 10px; }
        
        /* Footer */
        .footer { background-color: #f9fafb; padding: 20px; text-align: center; border-top: 1px solid #e5e7eb; }
        .footer p { font-size: 12px; color: #9ca3af; margin: 5px 0; }
        .footer a { color: #4F46E5; text-decoration: none; }
    </style>
</head>
<body>

    <center class="wrapper">
        <table class="main">
            <tr>
                <td class="header">
                    <h1>EXAM PORTAL</h1>
                </td>
            </tr>

            <tr>
                <td class="content">
                    <h2>Verify Your Identity</h2>
                    <p>Hello Student,</p>
                    <p>We received a request to access your account on the <strong>Online Examination System</strong>. Use the code below to complete the verification process.</p>

                    <div class="otp-box">
                        <span class="otp-code">{{ $otp }}</span>
                    </div>

                    <p class="expiry">This code is valid for <strong>10 minutes</strong>.</p>
                    <p>If you did not request this code, please ignore this email or contact support immediately if you feel your account is compromised.</p>
                </td>
            </tr>

            <tr>
                <td class="footer">
                    <p>&copy; {{ date('Y') }} Exam System. All rights reserved.</p>
                    <p>
                        <a href="#">Help Center</a> • <a href="#">Privacy Policy</a>
                    </p>
                    <p>This is an automated message, please do not reply.</p>
                </td>
            </tr>
        </table>
    </center>

</body>
</html>