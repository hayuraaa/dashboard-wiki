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
        .info-row {
            margin: 10px 0;
            padding: 10px;
            background: white;
            border-radius: 4px;
        }
        .label {
            font-weight: bold;
            color: #667eea;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>📬 Pesan Kontak Baru</h2>
        </div>
        
        <div class="content">
            <div class="info-row">
                <span class="label">Nama:</span> {{ $submission->name }}
            </div>
            
            <div class="info-row">
                <span class="label">Email:</span> {{ $submission->email }}
            </div>
            
            <div class="info-row">
                <span class="label">Nomor HP:</span> {{ $submission->phone }}
            </div>
            
            <div class="info-row">
                <span class="label">Subjek:</span> {{ $submission->subject }}
            </div>
            
            <div class="info-row">
                <span class="label">Pesan:</span><br>
                {{ $submission->message }}
            </div>
        </div>
    </div>
</body>
</html>