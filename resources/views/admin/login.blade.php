<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Dog Market</title>
    <style>
        :root {
            --primary: #6366f1;
            --primary-hover: #4f46e5;
            --bg: #0f172a;
            --card-bg: #1e293b;
            --text: #f8fafc;
            --text-muted: #94a3b8;
        }
        
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: var(--bg);
            color: var(--text);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            overflow: hidden;
        }

        .login-card {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(20px);
            padding: 2.5rem;
            border-radius: 1.5rem;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.1);
            animation: fadeIn 0.8s ease-out;
        }

        h2 { text-align: center; margin-bottom: 2rem; font-weight: 700; letter-spacing: -0.5px; }

        .form-group { margin-bottom: 1.5rem; }
        
        label { display: block; margin-bottom: 0.5rem; color: var(--text-muted); font-size: 0.875rem; }
        
        input {
            width: 100%;
            padding: 0.75rem 1rem;
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 0.75rem;
            color: white;
            font-size: 1rem;
            transition: all 0.2s;
            box-sizing: border-box; /* Fix padding issue */
        }

        input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
        }

        button {
            width: 100%;
            padding: 0.875rem;
            background: linear-gradient(135deg, var(--primary), var(--primary-hover));
            color: white;
            border: none;
            border-radius: 0.75rem;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.1s, box-shadow 0.2s;
        }

        button:hover { transform: translateY(-1px); box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.3); }
        button:active { transform: translateY(0); }

        .error {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            text-align: center;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Background decor */
        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            z-index: -1;
            opacity: 0.5;
        }
        .orb-1 { width: 300px; height: 300px; background: #4f46e5; top: -50px; left: -50px; }
        .orb-2 { width: 250px; height: 250px; background: #ec4899; bottom: -50px; right: -50px; }
    </style>
</head>
<body>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    
    <div class="login-card">
        <h2>Admin Portal</h2>
        
        <form action="{{ route('admin.authenticate') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" required placeholder="admin@dogmarket.com">
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="••••••••">
            </div>

            @if ($errors->any())
                <div class="error">{{ $errors->first() }}</div>
            @endif

            <button type="submit">Access Dashboard</button>
        </form>
    </div>
</body>
</html>
