#!/bin/bash
# MyBot - Надежный скрипт деплоя фронтенда
# Этот скрипт:
# 1. Очищает старые файлы сборки и кэш
# 2. Выполняет полную пересборку фронтенда
# 3. Гарантирует синхронизацию всех статических файлов
# 4. Проверяет целостность файлов после сборки

set -e # Остановка при любой ошибке
TIMESTAMP=$(date +"%Y%m%d%H%M%S")
FRONTEND_DIR="frontend"
PUBLIC_DIR="public"
LOG_FILE="deploy_$TIMESTAMP.log"

echo "🚀 Начинаем процесс деплоя - $(date)" | tee -a "$LOG_FILE"
echo "=====================================" | tee -a "$LOG_FILE"

# Функция для логирования
log() {
  echo "$(date +"%H:%M:%S") - $1" | tee -a "$LOG_FILE"
}

# Проверка и создание бэкапа перед деплоем
create_backup() {
  log "📦 Создаем бэкап текущих файлов..."
  
  if [ -d "$PUBLIC_DIR/build" ]; then
    BACKUP_DIR="${PUBLIC_DIR}_backup_$TIMESTAMP"
    mkdir -p "$BACKUP_DIR"
    
    # Копируем только необходимые файлы
    cp "$PUBLIC_DIR/index.html" "$BACKUP_DIR/" 2>/dev/null || true
    cp -r "$PUBLIC_DIR/build" "$BACKUP_DIR/" 2>/dev/null || true
    
    log "✅ Бэкап сохранен в $BACKUP_DIR"
  else
    log "⚠️ Нет файлов сборки для бэкапа"
  fi
}

# Очистка старых файлов и кэша
clean_old_files() {
  log "🧹 Очищаем старые файлы сборки и кэш..."
  
  # Удаляем старые файлы сборки
  rm -rf "$FRONTEND_DIR/.svelte-kit" || true
  rm -rf "$FRONTEND_DIR/build" || true
  rm -rf "$PUBLIC_DIR/build" || true
  
  # Очищаем кэш npm по желанию (раскомментируй, если нужно)
  # npm cache clean --force
  
  log "✅ Очистка завершена"
}

# Установка зависимостей и сборка проекта
build_frontend() {
  log "🔨 Устанавливаем зависимости и собираем проект..."
  
  # Переходим в директорию фронтенда
  cd "$FRONTEND_DIR"
  
  # Проверка и установка зависимостей
  npm ci || npm install
  
  # Пересобираем проект
  npm run build
  
  # Возвращаемся в корневую директорию
  cd ..
  
  log "✅ Сборка фронтенда успешно завершена"
}

# Проверка целостности и согласованности файлов
verify_files() {
  log "🔍 Проверяем целостность файлов..."
  
  # Проверяем наличие index.html
  if [ ! -f "$PUBLIC_DIR/index.html" ]; then
    log "❌ Ошибка: index.html отсутствует!"
    exit 1
  fi
  
  # Проверяем наличие директории build с собранными файлами
  if [ ! -d "$PUBLIC_DIR/build" ]; then
    log "❌ Ошибка: директория build отсутствует!"
    exit 1
  fi
  
  # Проверяем, что index.html ссылается на актуальные файлы
  JS_FILES=$(find "$PUBLIC_DIR/build" -name "*.js" | wc -l)
  JS_REFS=$(grep -o '/build/[^"]*\.js' "$PUBLIC_DIR/index.html" | wc -l)
  
  log "📊 Найдено JS файлов: $JS_FILES, ссылок в index.html: $JS_REFS"
  
  if [ "$JS_REFS" -lt 1 ]; then
    log "⚠️ Предупреждение: index.html может не ссылаться на JS файлы корректно"
  fi
  
  # Проверяем ссылки в index.html на существующие файлы
  MISSING_FILES=0
  for REF in $(grep -o 'build/[^"]*\.\(js\|css\)' "$PUBLIC_DIR/index.html"); do
    if [ ! -f "$PUBLIC_DIR/$REF" ]; then
      log "❌ Ошибка: файл $REF указан в index.html, но отсутствует на диске!"
      MISSING_FILES=$((MISSING_FILES + 1))
    fi
  done
  
  if [ "$MISSING_FILES" -gt 0 ]; then
    log "❌ Ошибка: $MISSING_FILES файлов указаны в index.html, но отсутствуют на диске!"
    exit 1
  fi
  
  log "✅ Проверка целостности завершена успешно"
}

# Добавляем версионный параметр к URL в index.html
add_version_param() {
  log "🔄 Добавляем версионный параметр к URL в index.html..."
  
  # Создаём временный файл
  TMP_FILE=$(mktemp)
  
  # Заменяем ссылки на JS и CSS файлы, добавляя версионный параметр
  sed "s|\(\"/build/[^\"]*\.\(js\|css\)\)\"|\"\\1?v=$TIMESTAMP\"|g" "$PUBLIC_DIR/index.html" > "$TMP_FILE"
  
  # Проверяем, что файл не пустой
  if [ -s "$TMP_FILE" ]; then
    # Заменяем исходный файл
    mv "$TMP_FILE" "$PUBLIC_DIR/index.html"
    log "✅ Версионный параметр добавлен к URL"
  else
    log "❌ Ошибка при обновлении версионных параметров"
    rm "$TMP_FILE"
    exit 1
  fi
}

# Добавляем заголовки кэша в .htaccess
update_htaccess() {
  log "📄 Обновляем .htaccess для контроля кэша..."
  
  HTACCESS="$PUBLIC_DIR/.htaccess"
  
  # Создаем или обновляем .htaccess
  cat > "$HTACCESS" << EOL
# Кэширование и контроль версий
<IfModule mod_expires.c>
  ExpiresActive On
  
  # Устанавливаем значение по умолчанию для всех файлов
  ExpiresDefault "access plus 1 month"
  
  # HTML не кэшируется
  <FilesMatch "\.(html|htm)$">
    ExpiresDefault "access plus 0 seconds"
    Header set Cache-Control "no-store, no-cache, must-revalidate, max-age=0"
    Header set Pragma "no-cache"
  </FilesMatch>
  
  # JS и CSS файлы кэшируются, но с версионным параметром
  <FilesMatch "\.(js|css)$">
    ExpiresDefault "access plus 1 year"
    Header append Cache-Control "public, immutable"
  </FilesMatch>
  
  # Изображения и шрифты кэшируются надолго
  <FilesMatch "\.(jpg|jpeg|png|gif|ico|svg|woff|woff2|ttf|eot)$">
    ExpiresDefault "access plus 1 year"
    Header append Cache-Control "public, immutable"
  </FilesMatch>
</IfModule>

# Перенаправление для SPA
<IfModule mod_rewrite.c>
  RewriteEngine On
  RewriteBase /
  
  # Если файл или директория не существуют, перенаправляем на index.html
  RewriteCond %{REQUEST_FILENAME} !-f
  RewriteCond %{REQUEST_FILENAME} !-d
  RewriteRule . /index.html [L]
</IfModule>
EOL

  log "✅ .htaccess обновлен"
}

# Обновление service-worker.js для поддержки новых путей
update_service_worker() {
  log "👷 Обновляем service-worker.js для поддержки новых путей..."

  # Если есть service worker
  if [ -f "$PUBLIC_DIR/sw.js" ]; then
    # Добавляем timestamp для принудительного обновления
    sed -i "s/const TIMESTAMP = .*\;/const TIMESTAMP = '$TIMESTAMP'\;/g" "$PUBLIC_DIR/sw.js" 2>/dev/null || true
    log "✅ service-worker.js обновлен"
  else
    log "⚠️ service-worker.js не найден"
  fi
}

# Основной процесс деплоя
main() {
  create_backup
  clean_old_files
  build_frontend
  add_version_param
  update_htaccess
  update_service_worker
  verify_files
  
  log "🎉 Деплой успешно завершен! - $(date)"
  log "======================================"
  log "⚠️ ВАЖНО: Не забудьте проверить в браузере (в режиме инкогнито или после очистки кэша), что изменения появились в продакшене"
}

# Запуск основного процесса
main
