<?php

require_once __DIR__ . '/vendor/autoload.php';

use Telegram\Bot\Api;
use \Dejurin\GoogleTranslateForFree;

$token = '6356787845:AAGckXO4TM8ULWM71Gv0IT6QWia2GmKJ-NI';

$telegram = new Api($token);

$updates = $telegram->getWebhookUpdates();
//file_put_contents(__DIR__ . '/logs.txt', print_r($updates, 1), FILE_APPEND);
$chat_id = $updates['message']['chat']['id'];
$text = $updates['message']['text'] ?? '';

if($text == '/start') {

    $response = $telegram->sendMessage([
        'chat_id' => $chat_id,
        'text' => "Вас приветствует бот переводчик. Буду помогать вам в помощи перевода с en-ru || ru-en"
    ]);
} elseif (!empty($text)){
    if(preg_match('#[a-z]+#i', $text)){

        $source = 'en';
        $target = 'ru';
    } else {
        $source = 'ru';
        $target = 'en';
    }

    $attempts = 5;

    $tr = new GoogleTranslateForFree();
    $result = $tr->translate($source, $target, $text, $attempts);

    if($result){

        $response = $telegram->sendMessage([
            'chat_id' => $chat_id,
            'text' => $result,
        ]);

    } else {

        $response = $telegram->sendMessage([
            'chat_id' => $chat_id,
            'text' => "Ошибка перевода!",
        ]);
    }
}


/*https://api.telegram.org/bot6356787845:AAGckXO4TM8ULWM71Gv0IT6QWia2GmKJ-NI/setWebhook?url=https://testphpbotalex.000webhostapp.com/translate-bot2/index.php*/



