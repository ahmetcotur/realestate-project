<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Danışman İletişim Talebi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .header {
            background: #3b82f6;
            color: white;
            padding: 15px;
            text-align: center;
            border-radius: 5px 5px 0 0;
            margin-bottom: 20px;
        }
        .content {
            padding: 15px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 14px;
            color: #666;
        }
        .info-item {
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .label {
            font-weight: bold;
        }
        .agent-info {
            background: #f3f4f6;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .agent-name {
            font-size: 18px;
            font-weight: bold;
            color: #3b82f6;
        }
        .agent-title {
            color: #555;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Danışman İletişim Talebi</h1>
        </div>
        
        <div class="content">
            <p>Aşağıdaki danışman için iletişim talebi gönderildi:</p>
            
            <div class="agent-info">
                <div class="agent-name">{{ $agent->name }}</div>
                <div class="agent-title">{{ $agent->title }}</div>
            </div>
            
            <div class="info-item">
                <span class="label">Gönderen:</span> {{ $contact->name }}
            </div>
            
            <div class="info-item">
                <span class="label">E-posta:</span> {{ $contact->email }}
            </div>
            
            @if($contact->phone)
            <div class="info-item">
                <span class="label">Telefon:</span> {{ $contact->phone }}
            </div>
            @endif
            
            <div class="info-item">
                <span class="label">Mesaj:</span><br>
                {{ $contact->message }}
            </div>
            
            <div class="info-item">
                <span class="label">Gönderilme Tarihi:</span> {{ $contact->created_at->format('d.m.Y H:i') }}
            </div>
            
            <p style="margin-top: 20px;">Lütfen en kısa sürede müşteri ile iletişime geçiniz.</p>
        </div>
        
        <div class="footer">
            <p>Bu e-posta, Remax Pupa Emlak web sitesi üzerinden gönderilmiştir.</p>
        </div>
    </div>
</body>
</html> 