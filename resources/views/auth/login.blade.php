<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Дурдонаҳо</title>

        <meta name="robots" content="none"/>
        <meta name="googlebot" content="noindex, nofollow" />
        <meta name="yandex" content="none">

        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;1,300;1,400&display=swap" rel="stylesheet">

        <style>
            *, ::before, ::after {
                box-sizing: border-box
            }
            body {
                display: flex;
                align-items: center;
                justify-content: center;
                min-height: 100vh;
                padding: 12px;
                margin: 0;
                background-color: #F3F4F6;
                font-family: Roboto, sans-serif;
                font-size: 14px
            }
            .form {
                background-color: white;
                border-color: rgba(229, 231, 235, 1);
                border-radius: 0.5rem;
                padding: 30px;
                box-shadow: 0 0 10px #0000001c;
                max-width: 100%
            }
            .logo {
                margin-bottom: 16px;
                width: 200px;
            }
            .form-group {
                display: flex;
                flex-direction: column;
                gap: 10px;
                margin-bottom: 15px;
            }
            .label {
                color: grey;
            }
            .input {
                max-width: 100%;
                width: 400px;
                padding: 10px;
                border: 1px solid #c3c3c3;
                border-radius: 5px;
            }
            .input--error {
                border-color: red;
            }
            .error-message {
                color: red;
                margin-top: 0;
            }
            .submit {
                display: flex;
                padding: 8px 20px;
                margin-left: auto;
                font-family: Roboto, sans-serif;
                font-size: 12px;
                color: white;
                text-transform: uppercase;
                background-color: #1F2937;
                border-radius: 5px;
                cursor: pointer;
            }

        </style>

    </head>
    <body>

        <form class="form" method="POST" action="/login">
            @csrf

            <a href="/" target="_blank">
                <img class="logo" src="{{ asset('img/main/logo.svg') }}">
            </a>

            @if($errors->any())
                <p class="error-message">Неверный логин или пароль !</p>
            @endif

            <!-- Email Address -->
            <div class="form-group">
                <label class="label" for="email">Почтаи электронӣ</label>
                <input class="input {{ $errors->any() ? 'input--error' : '' }}" id="email" type="email" name="email" value="{{ old('email') }}" required autofocus />
            </div>

            <!-- Password -->
            <div class="form-group">
                <label class="label" for="password">Рамз</label>
                <input class="input {{ $errors->any() ? 'input--error' : '' }}" id="password" type="password" name="password" required/>
            </div>

            <!-- Remember Me -->
            <div class="form-group">
                <label class="label" for="remember_me">
                    <input id="remember_me" type="checkbox" name="remember" checked>
                    <span>Дар хотир доштан</span>
                </label>
            </div>

            <button class="submit">Вуруд</button>
        </form>

    </body>
</html>
