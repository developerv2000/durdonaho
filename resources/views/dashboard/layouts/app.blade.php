<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Админка — Дурдонаҳо</title>

    {{-- Noindex remove on production --}}
    <meta name="robots" content="none" />
    <meta name="googlebot" content="noindex, nofollow" />
    <meta name="yandex" content="none">

    {{-- Roboto Google fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;1,100;1,300;1,400;1,500;1,700&display=swap">

    {{-- Material Icons --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Material+Icons">

    {{-- Bootstrap 5.1.3 --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    {{-- Selectize --}}
    <link rel="stylesheet" href="{{ asset('plugins/selectize/selectize.css') }}">

    {{-- Simditor v2.3.28 --}}
    <link rel="stylesheet" href="{{ asset('plugins/simditor/simditor.css') }}">

    {{-- Normalize CSS --}}
    <link rel="stylesheet" href="{{ asset('css/normalize.css') }}">

    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>

<body>
    @include('dashboard.layouts.header')
    @include('dashboard.layouts.aside')

    <main class="main" id="main">
        @include('dashboard.layouts.errors')
        @include('dashboard.layouts.new-quote-notification')
        @yield('main')
    </main>

    {{-- JQuery --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    {{-- Boostrap 5.1.3 --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    {{-- Selectize --}}
    <script src="{{ asset('plugins/selectize/selectize.min.js') }}"></script>
    
    {{-- Simditor v2.3.28 --}}
    <script src="{{ asset('plugins/simditor/module.js') }}"></script>
    <script src="{{ asset('plugins/simditor/hotkeys.js') }}"></script>
    <script src="{{ asset('plugins/simditor/uploader.js') }}"></script>
    <script src="{{ asset('plugins/simditor/simditor.js') }}"></script>

    <script src="{{ asset('js/dashboard.js') }}"></script>
</body>

</html>
