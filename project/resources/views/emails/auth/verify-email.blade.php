<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Подтверждение Email</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .logo { max-width: 150px; }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #6c00fa;
            color: white !important;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            margin: 20px 0;
        }
        .footer { margin-top: 30px; font-size: 12px; color: #777; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Подтверждение Email</h1>
        </div>

        <p>Здравствуйте, {{ $user->name }}!</p>

        <p>Благодарим вас за регистрацию в нашем сервисе. Для завершения регистрации, пожалуйста, подтвердите ваш email адрес:</p>

        <p>
            <a href="{{ $verificationUrl }}" class="button">Подтвердить Email</a>
        </p>

        <p>Если кнопка не работает, скопируйте и вставьте следующую ссылку в браузер:</p>
        <p><small>{{ $verificationUrl }}</small></p>

        <p>Если вы не регистрировались в нашем сервисе, пожалуйста, проигнорируйте это письмо.</p>

        <div class="footer">
            <p>© {{ date('Y') }} {{ config('app.name') }}. Все права защищены.</p>
        </div>
    </div>
</body>
</html>