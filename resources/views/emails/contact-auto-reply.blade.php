<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.8;
            color: #2c3e50;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 650px;
            margin: 40px auto;
            background: #ffffff;
            border: 1px solid #ddd;
        }
        .header {
            padding: 30px;
            border-bottom: 3px solid #2c3e50;
        }
        .logo {
            font-size: 20px;
            font-weight: 600;
            color: #2c3e50;
            margin: 0;
        }
        .content {
            padding: 30px;
        }
        .content p {
            margin: 0 0 15px 0;
        }
        .footer {
            padding: 20px 30px;
            background-color: #f8f9fa;
            border-top: 1px solid #e9ecef;
            font-size: 12px;
            color: #6c757d;
        }
        .signature {
            margin-top: 25px;
            padding-top: 15px;
            border-top: 1px solid #e9ecef;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 class="logo">Wikimedia Indonesia</h1>
        </div>
        
        <div class="content">
            <p>Kepada Yth. <strong>{{ $submission->name }}</strong>,</p>
            
            <p>{{ $autoReplyMessage }}</p>
            
            <div class="signature">
                <p><strong>Wikimedia Indonesia</strong></p>
            </div>
        </div>
        
        <div class="footer">
            <p>Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p>
        </div>
    </div>
</body>
</html>