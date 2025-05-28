#!/bin/bash

# Останавливаем текущий процесс Horizon
php artisan horizon:terminate

# Даем время на завершение процессов
sleep 3

# Запускаем Horizon с новыми параметрами
php artisan horizon --sleep=1 --retry=5 --tries=3 --timeout=60 --backoff=5,10,20
