<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }} - Admin Girişi</title>

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 0;
        }
        
        .login-container {
            background-color: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 1.5rem;
        }
        
        .form-group {
            margin-bottom: 1rem;
        }
        
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        
        input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }
        
        button {
            background-color: #3b82f6;
            color: white;
            border: none;
            padding: 0.75rem 1rem;
            border-radius: 4px;
            font-size: 1rem;
            cursor: pointer;
            width: 100%;
            margin-top: 1rem;
        }
        
        button:hover {
            background-color: #2563eb;
        }
        
        .alert {
            padding: 0.75rem;
            margin-bottom: 1rem;
            border-radius: 4px;
        }
        
        .alert-danger {
            background-color: #fee2e2;
            color: #b91c1c;
            border: 1px solid #ef4444;
        }
        
        .alert-success {
            background-color: #d1fae5;
            color: #047857;
            border: 1px solid #10b981;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Admin Giriş</h1>
        
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form action="{{ route('admin.login.post') }}" method="post">
            @csrf
            <div class="form-group">
                <label for="email">E-posta:</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus>
            </div>
            <div class="form-group">
                <label for="password">Şifre:</label>
                <input type="password" name="password" id="password" required>
            </div>
            <div class="form-group">
                <label>
                    <input type="checkbox" name="remember"> Beni Hatırla
                </label>
            </div>
            <div class="form-group">
                <button type="submit">Giriş Yap</button>
            </div>
        </form>
    </div>
</body>
</html> 