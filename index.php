<?php

require_once __DIR__ . '/vendor/autoload.php';
use Telegram\Bot\Api;


$telegram = new Api('5934105479:AAHCuz_sDeTF2Gy-2pNPzf_Fb3ycFctemyk');

$updates = $telegram->getWebhookUpdates();


