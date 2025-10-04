<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background: #f9fafb;
            padding: 20px;
            border: 1px solid #e5e7eb;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>✅ Pesan Anda Telah Diterima</h2>
        </div>
        
        <div class="content">
            <p>Halo <strong>{{ $submission->name }}</strong>,</p>
            
            <p>{{ $autoReplyMessage }}</p>
            
            <p><strong>Wikimedia Indonesia</strong></p>
        </div>
    </div>
</body>
</html>