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
        .title {
            font-size: 20px;
            font-weight: 600;
            color: #2c3e50;
            margin: 0;
        }
        .content {
            padding: 30px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .info-table td {
            padding: 12px 0;
            border-bottom: 1px solid #e9ecef;
            vertical-align: top;
        }
        .info-table td:first-child {
            width: 120px;
            font-weight: 600;
            color: #495057;
        }
        .message-box {
            background-color: #f8f9fa;
            border-left: 3px solid #2c3e50;
            padding: 15px;
            margin: 15px 0;
        }
        .footer {
            padding: 20px 30px;
            background-color: #f8f9fa;
            border-top: 1px solid #e9ecef;
            font-size: 12px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 class="title">Pesan Kontak Baru</h1>
        </div>
        
        <div class="content">
            <p>Anda menerima pesan kontak baru melalui website:</p>
            
            <table class="info-table">
                <tr>
                    <td>Nama</td>
                    <td>{{ $submission->name }}</td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td>{{ $submission->email }}</td>
                </tr>
                <tr>
                    <td>Nomor HP</td>
                    <td>{{ $submission->phone }}</td>
                </tr>
                <tr>
                    <td>Subjek</td>
                    <td>{{ $submission->subject }}</td>
                </tr>
            </table>
            
            <p><strong>Pesan:</strong></p>
            <div class="message-box">
                {{ $submission->message }}
            </div>
        </div>
        
        <div class="footer">
            <p>Wikimedia Indonesia &copy; {{ date('Y') }}</p>
        </div>
    </div>
</body>
</html>