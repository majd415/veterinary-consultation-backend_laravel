<!DOCTYPE html>
<html>
<head>
    <title>{{ $type === 'reset' ? 'Password Reset' : 'Verification Code' }}</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; text-align: center; }
        .code { font-size: 32px; font-weight: bold; color: #007bff; text-align: center; letter-spacing: 5px; margin: 20px 0; }
        p { color: #555; line-height: 1.6; text-align: center; }
        .footer { margin-top: 30px; font-size: 12px; color: #999; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <h1>{{ $type === 'reset' ? 'Reset Your Password' : 'Verify Your Email' }}</h1>
        
        <p>Hello,</p>
        <p>Use the code below to {{ $type === 'reset' ? 'reset your password' : 'complete your registration' }}.</p>
        
        <div class="code">{{ $code }}</div>
        
        <p>This code will expire in 10 minutes.</p>
        
        <div class="footer">
            &copy; {{ date('Y') }} Dog Market. All rights reserved.
        </div>
    </div>
</body>
</html>
