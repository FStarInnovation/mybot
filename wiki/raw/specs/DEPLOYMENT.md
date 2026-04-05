# Процесс деплоя SvelteKit + Laravel

## Архитектура

- **Frontend**: SvelteKit с `adapter-static`, собирается в `public/` директорию Laravel
- **Backend**: Laravel, обслуживает API и статические файлы
- **Деплой**: Laravel Cloud с автоматическим деплоем из GitHub

## Процесс сборки фронтенда

1. Настройка `svelte.config.js`:
   ```js
   adapter: adapter({
     // Генерируем index.html в корне public/
     pages: '../public',
     // Размещаем ассеты в public/ (adapter создаст _app/ автоматически)
     assets: '../public',
     fallback: 'index.html'
   })
   ```

2. Сборка:
   ```bash
   cd frontend
   npm run build
   ```

3. Проверка:
   - В `public/` должен быть файл `index.html`
   - В `public/_app/immutable/` должны быть JS/CSS ассеты
   - Структура не должна содержать вложенных `_app/_app/`

4. Фиксация изменений:
   ```bash
   cd ..  # вернуться в корень проекта
   git add -A
   git commit -m "feat(frontend): обновление сборки SvelteKit"
   git push
   ```

## Настройка Laravel Cloud

1. **Health Check**:
   - В `cloud.yaml` настроен health check на `/health`
   - В `routes/web.php` добавлен маршрут `/health`
   - Создан файл `public/health.php` для прямого доступа

2. **Nginx конфигурация** (в `cloud.yaml`):
   ```yaml
   nginx_inline: |
     # Разрешаем eval в CSP для SvelteKit
     add_header Content-Security-Policy "default-src 'self'; script-src 'self' 'unsafe-eval'; style-src 'self' 'unsafe-inline'; img-src 'self' data:;";
     
     # Кэширование статических ресурсов
     location /_app/ {
         expires 30d;
         add_header Cache-Control "public, max-age=2592000";
         try_files $uri =404;
     }
     
     # SPA fallback: статика → index.html → Laravel
     location / {
        try_files $uri $uri/ /index.html /index.php?$query_string;
     }
   ```

## Проверка деплоя

1. Дождитесь статуса **Running** в Laravel Cloud
2. Проверьте доступность:
   ```bash
   curl -I https://mybot-main-3ztkqf.laravel.cloud/health
   curl -I https://mybot-main-3ztkqf.laravel.cloud/_app/immutable/entry/start.*.js
   curl -I https://mybot-main-3ztkqf.laravel.cloud/chat
   ```
   Все запросы должны возвращать `200 OK`

3. Откройте в браузере `/chat` и проверьте консоль на наличие ошибок

## Устранение неполадок

1. **404 на статические ассеты**:
   - Проверьте, что файлы существуют в `public/_app/`
   - Убедитесь, что в `.gitignore` нет правил, игнорирующих `public/_app/`
   - Проверьте nginx конфигурацию и права доступа

2. **Health Check 404**:
   - Проверьте наличие маршрута `/health` в `routes/web.php`
   - Проверьте наличие файла `public/health.php`
   - Убедитесь, что в `cloud.yaml` указан правильный путь

3. **CSP блокирует SvelteKit**:
   - Добавьте `'unsafe-eval'` в `script-src` и `'unsafe-inline'` в `style-src`
   - Проверьте консоль браузера на наличие ошибок CSP

4. **Бесконечная загрузка SPA**:
   - Проверьте, что API эндпоинты доступны и возвращают корректные данные
   - Проверьте консоль браузера на наличие ошибок fetch/XHR
