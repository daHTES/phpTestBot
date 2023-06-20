<?php

require_once __DIR__ . '/vendor/autoload.php';
use Telegram\Bot\Api;

$telegram = new Api('5934105479:AAHCuz_sDeTF2Gy-2pNPzf_Fb3ycFctemyk');

$updates = $telegram->getWebhookUpdates();

//file_put_contents(__DIR__ . '/logs.txt', print_r($updates, 1), FILE_APPEND);

$chat_id = $updates['message']['chat']['id'];
$text = $updates['message']['text'];

if($text == 'photo'){
    $response = $telegram->sendPhoto([
        'chat_id' => $chat_id,
        'photo' => 'mongol.png',
        'caption' => 'Ваше фото!'
    ]);

}elseif ($text == '/start') {
    $response = $telegram->sendMessage([
        'chat_id' => $chat_id,
        'text' => 'Вы тапнули на команду START, решил поюзать мои возможности?',
    ]);

}elseif ($text == '/help') {
    $response = $telegram->sendMessage([
        'chat_id' => $chat_id,
        'text' => "Вы тапнули на команду HELP, я могу вам помочь!." . PHP_EOL ."<a href='https://ukr.net'>News</a>",
        'parse_mode' => 'HTML'
    ]);

}elseif($text == 'file') {
    $response = $telegram->sendMessage([
        'chat_id' => $chat_id,
        'text' => 'Сейчас будет файл...',
    ]);

    $response = $telegram->sendDocument([
        'chat_id' => $chat_id,
        'document' => 'info.xlsx',
        'caption' => 'Word Document'
    ]);
}elseif($text == 'video') {
    $response = $telegram->sendVideo([
        'chat_id' => $chat_id,
        'video' => 'kolomoyskiy.mp4',
    ]);
}else{
    $response = $telegram->sendMessage([
        'chat_id' => $chat_id,
        'text' => "Привет, <b>{$updates['message']['from']['first_name']}</b>>! Вы написали: 
<i>{$text}</i>",
        'parse_mode' => 'HTML',
    ]);
}



//file_put_contents(__DIR__ . '/logs.txt', print_r($response, 1), FILE_APPEND);



