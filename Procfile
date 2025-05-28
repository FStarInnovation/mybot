web: php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
worker: php artisan queue:work upstash --sleep=1 --tries=3 --timeout=60 --backoff=5,10,20
horizon: php artisan horizon --sleep=1 --retry=5 --tries=3 --timeout=60
