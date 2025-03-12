<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Emlak İlanı Hakkında Bilgi Talebi</title>
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
        .property-info {
            background: #f3f4f6;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .property-title {
            font-size: 18px;
            font-weight: bold;
            color: #3b82f6;
        }
        .property-price {
            font-weight: bold;
            margin-top: 10px;
        }
        .property-link {
            display: inline-block;
            margin-top: 15px;
            color: #fff;
            background: #3b82f6;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Emlak İlanı Hakkında Bilgi Talebi</h1>
        </div>
        
        <div class="content">
            <p>Aşağıdaki emlak ilanı hakkında bilgi talebi gönderildi:</p>
            
            <div class="property-info">
                <div class="property-title">{{ $property->title_tr }}</div>
                <div>{{ $property->location }}</div>
                <div class="property-price">{{ number_format($property->price, 0, ',', '.') }} {{ $property->currency }}</div>
                <a href="{{ route('properties.show', $property->slug) }}" class="property-link">İlanı Görüntüle</a>
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