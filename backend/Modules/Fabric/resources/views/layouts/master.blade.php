<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Модуль тканей - {{ config('app.name', 'Laravel') }}</title>

    <meta name="description" content="{{ $description ?? '' }}">
    <meta name="keywords" content="{{ $keywords ?? '' }}">
    <meta name="author" content="{{ $author ?? '' }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />


    @vite(['resources/css/app.css', 'resources/js/app.js', 'Modules/Fabric/Resources/assets/sass/app.scss'])
    @livewireStyles
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="#">Модуль тканей</a>
            <div class="navbar-nav">
                <a class="nav-link" href="{{ route('fabrics.index') }}">Ткани</a>
                <a class="nav-link" href="{{ route('catalogs.index') }}">Каталоги</a>
                <a class="nav-link" href="{{ route('colors.index') }}">Цвета</a>
            </div>
        </div>
    </nav>

    <div class="container">
        @yield('content')
    </div>

    @livewireScripts
</body>
