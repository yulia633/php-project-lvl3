<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Page analyzer - @yield('title')</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="csrf-param" content="_token" />
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <script src="{{ asset('js/app.js') }}"></script>
    </head>
    <body class="min-vh-100 d-flex flex-column">
        <header class="flex-shrink-0">
            @include('flash::message')
            <nav class="navbar navbar-expand-md navbar-dark bg-dark">
                <a class="navbar-brand" href="{{ route('welcome') }}">Анализатор страниц</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('welcome') }}">Главная</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " href="{{ route('urls.index') }}">Сайты</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <main class="flex-grow-1">
            @yield('content')
        </main>
        <footer class="border-top py-3 mt-5 flex-shrink-0">
            <div class="container-lg">
                <div class="text-center">
                    <a href="https://hexlet.io/pages/about" target="_blank">Hexlet</a>
                </div>
            </div>
        </footer>
    </body>
    <body>
</html>

