<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Сброс пароля</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f7fafc; }
        .container { max-width: 600px; margin: 20px auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header { text-align: center; margin-bottom: 30px; }
        .logo { max-width: 150px; margin-bottom: 20px; }
        h1 { color: #2d3748; margin-bottom: 20px; }
        p { margin-bottom: 20px; }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #6c00fa;
            color: white !important;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
        }
        .button:hover {
            background-color: #6c00fa;
        }
        .footer { 
            margin-top: 30px; 
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            font-size: 12px; 
            color: #718096; 
            text-align: center;
        }
        .link {
            color: #4299e1;
            text-decoration: none;
            word-break: break-all;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Форвард авто</h1>
            <h2>Сброс пароля</h2>
        </div>

        <p>Здравствуйте!</p>

        <p>Мы получили запрос на сброс пароля для вашего аккаунта. Для установки нового пароля нажмите на кнопку ниже:</p>

        <p style="text-align: center;">
            <a href="{{ $resetUrl }}" class="button">Сбросить пароль</a>
        </p>

        <p>Если кнопка не работает, скопируйте и вставьте следующую ссылку в адресную строку браузера:</p>
        <p><a href="{{ $resetUrl }}" class="link">{{ $resetUrl }}</a></p>

        <p>Если вы не запрашивали сброс пароля, просто проигнорируйте это письмо. Ваш пароль останется без изменений.</p>

        <div class="footer">
            <p>© {{ date('Y') }} {{ config('app.name') }}. Все права защищены.</p>
            <p>Это письмо отправлено автоматически, пожалуйста, не отвечайте на него.</p>
        </div>
    </div>
</body>
</html>