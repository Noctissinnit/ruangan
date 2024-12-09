<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invitation</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: linear-gradient(to bottom, #ffffff, #f9f9f9);
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }
        .email-header {
            background: linear-gradient(90deg, #4CAF50, #45a049);
            color: #ffffff;
            text-align: center;
            padding: 25px 20px;
        }
        .email-header img {
            max-width: 80px;
            margin-bottom: 15px;
        }
        .email-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }
        .email-body {
            padding: 30px 20px;
            color: #333333;
            line-height: 1.8;
            font-size: 16px;
        }
        .email-body p {
            margin: 12px 0;
        }
        .email-body .details {
            background-color: #f1f1f1;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
            font-size: 15px;
        }
        .email-body .details strong {
            color: #4CAF50;
        }
        .email-footer {
            background-color: #f9f9f9;
            text-align: center;
            padding: 20px;
            color: #888888;
            font-size: 14px;
        }
        .email-footer a {
            color: #4CAF50;
            text-decoration: none;
            font-weight: bold;
        }
        .btn {
            display: inline-block;
            background-color: #4CAF50;
            color: #ffffff;
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 6px;
            font-size: 16px;
            margin: 20px 0;
            font-weight: bold;
        }
        .btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <img src="https://i.ibb.co/9qBb3fp/logoykbs-1.png" alt="Logo">

            <h1>Undangan</h1>
        </div>

        <!-- Body -->
        <div class="email-body">
            <p>Hai <strong>{{ $user->name }}</strong>,</p>
            <p>
                Kamu diundang oleh <strong>{{ $booking->user->name }}</strong> untuk menghadiri 
                <strong>{{ $booking->description }}</strong> pada:
            </p>
            <div class="details">
                <p><strong>Tempat:</strong> {{ $booking->room->name }}</p>
                <p><strong>Tanggal:</strong> {{ $booking->date }}</p>
                <p><strong>Waktu:</strong> {{ $booking->start_time }} - {{ $booking->end_time }}</p>
            </div>
            <p>Harap konfirmasi kehadiranmu sesegera mungkin dengan mengklik tombol di bawah ini:</p>
            <a href="{{ route('approval.index', ['booking' => $booking, 'user' => $user]) }}" class="btn">Konfirmasi Kehadiran</a>
            <p>Terima kasih atas perhatianmu!</p>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p>&copy; 2024 AtmiCorp. Semua hak dilindungi.</p>
            <p><a href="#">Hubungi Kami</a></p>
        </div>
    </div>
</body>
</html>
