<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        {{--fontsawesome--}}
        <script src="https://kit.fontawesome.com/6b8dd96fea.js" crossorigin="anonymous"></script>
        <!-- Styles -->
    @livewireStyles
       <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <style>
            [x-cloak] { display: none; }
        </style>
    </head>

    <body class="font-sans antialiased">
        
        @include('layouts.partials.admin.alerts')
        @include('layouts.partials.admin.navigation')
        @include('layouts.partials.admin.sidebar')

        
        <div class="p-4 sm:ml-64">
            <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700 mt-14">
                {{ $slot }}
            </div>
        </div>
  


        @stack('modals')

        @livewireScripts
    </body>
</html>
