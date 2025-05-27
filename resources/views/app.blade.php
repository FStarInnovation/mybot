<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Farmabot</title>
    
    <!-- Ссылки на собранные SvelteKit ассеты -->
    <link rel="stylesheet" href="{{ asset('build/_app/immutable/assets/0.DdC7Ipbr.css') }}">
    <link rel="manifest" href="{{ asset('build/manifest.webmanifest') }}">
    
    <!-- PWA мета-теги -->
    <meta name="theme-color" content="#3b82f6">
    <link rel="apple-touch-icon" href="{{ asset('build/pwa-192x192.png') }}">
</head>
<body>
    <div id="app"></div>
    
    <!-- Подключение собранных SvelteKit скриптов -->
    <script src="{{ asset('build/_app/immutable/entry/app.CB_uRnhw.js') }}" type="module"></script>
    <script src="{{ asset('build/_app/immutable/entry/start.SZVHkOqH.js') }}" type="module"></script>
    
    <!-- Регистрация Service Worker для PWA -->
    <script src="{{ asset('build/registerSW.js') }}" type="module"></script>
</body>
</html>
