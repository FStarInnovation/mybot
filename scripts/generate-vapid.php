<?php
require __DIR__.'/../vendor/autoload.php';
use Minishlink\WebPush\VAPID;
$k = VAPID::createVapidKeys();
foreach ($k as $key => $val) echo 'VAPID_'.strtoupper($key).'='.$val.PHP_EOL;
