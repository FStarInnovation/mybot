    /**
     * Возвращает URL первого файла, подходящего под шаблон.
     */
    function svelte_asset(string $pattern): string
    {
        $matches = glob(public_path($pattern));
        if (!$matches) {
            return '';
        }
        // Берём первый найденный файл
        $path = $matches[0];
        // Превращаем абсолютный путь в относительный к public
        $relative = ltrim(str_replace(public_path(), '', $path), '/');
        return asset($relative);
    }
@endphp
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Farmabot</title>
    
    <!-- Базовый URL для SvelteKit -->
    <base href="/">
    
    <!-- Ссылки на собранные SvelteKit ассеты -->
    <link rel="stylesheet" href="{{ svelte_asset('build/_app/immutable/assets/*.css') }}">
    <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
    
    <!-- PWA мета-теги -->
    <meta name="theme-color" content="#3b82f6">
    <link rel="apple-touch-icon" href="{{ asset('pwa-192x192.png') }}">
</head>
<body>
    <div id="app"></div>
    
    <!-- Подключение собранных SvelteKit скриптов -->
    <script src="{{ svelte_asset('build/_app/immutable/entry/app.*.js') }}" type="module"></script>
    <script src="{{ svelte_asset('build/_app/immutable/entry/start.*.js') }}" type="module"></script>
    
    <!-- Регистрация Service Worker для PWA -->
    <script src="{{ asset('registerSW.js') }}" type="module"></script>
</body>
</html>
