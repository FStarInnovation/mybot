<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Farmabot</title>

    <base href="/">

    <link rel="stylesheet" href="{{ svelte_asset('build/_app/immutable/assets/*.css') }}">
    <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">

    <meta name="theme-color" content="#3b82f6">
    <link rel="apple-touch-icon" href="{{ asset('pwa-192x192.png') }}">
</head>
<body>
    <div id="app"></div>

    <script src="{{ svelte_asset('build/_app/immutable/entry/app.*.js') }}" type="module"></script>
    <script src="{{ svelte_asset('build/_app/immutable/entry/start.*.js') }}" type="module"></script>
    <script src="{{ asset('registerSW.js') }}" type="module"></script>
</body>
</html>
