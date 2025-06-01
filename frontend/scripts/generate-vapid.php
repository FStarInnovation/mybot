<?php require __DIR__."/../vendor/autoload.php"; $k=Minishlink\WebPush\VAPID::createVapidKeys(); echo "VAPID_PUBLIC_KEY={$k["publicKey"]}
VAPID_PRIVATE_KEY={$k["privateKey"]}
";
