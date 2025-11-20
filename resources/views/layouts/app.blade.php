<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <!-- Vite CSS -->
        @vite(['resources/css/app.scss', 'resources/js/app.js'])
    </head>
    <body>
        <div id="app">
            <!-- Your content -->
        </div>
    </body>
</html>
