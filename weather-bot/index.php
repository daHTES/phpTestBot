<?php

require_once __DIR__ . '/vendor/autoload.php';

use Telegram\Bot\Api;

$token = '6346496062:AAHnTTQ1WRLAXE0lMSMJPZxRs2tthkSS6nA';
$weather_token = '1635be458265b9ed567595547ec9eb84';
$weather_url = "https://api.openweathermap.org/data/2.5/weather?appid={$weather_token}&units=metric&lang=ru";

$telegram = new Api($token);

$updates = $telegram->getWebhookUpdates();

//file_put_contents(__DIR__ . '/logs.txt', print_r($updates, 1), FILE_APPEND);

$chat_id = $updates['message']['chat']['id'];
$text = $updates['message']['text'] ?? '';

if($text == '/start') {

    $response = $telegram->sendMessage([
        'chat_id' => $chat_id,
        'text' => "Привет, {$updates['message']['chat']['first_name']}!Вас приветствует бот синоптик. Говорю про погоду в любом городе. Для получения погоды геолокацию(доступно с омбильных устройств).\n Так же возможно указать город в формате: <b>Город</b> или в формате <b>Город, код страны</b>. \n Примеры: <b>London</b>, <b>London, uk</b>, <b>Киев</b>",
        'parse_mode' => 'HTML'
    ]);
} elseif (!empty($text)){
    $weather_url .= "&q={$text}";
    $res = json_encode(file_get_contents($weather_url));

} elseif (isset($updates['message']['location'])) {
    $weather_url .= "&lat={$updates['message']['location']['latitude']}&lon={$updates['message']['location']['longitude']}";
    $res = json_encode(file_get_contents($weather_url));
}


if(empty($res)){

    $response = $telegram->sendMessage([
        'chat_id' => $chat_id,
        'text' => "Укажите корректный формат!"
    ]);

} else {
    $temp = round($res->main->temp);
    $answer = "<u>Информация о погоде:</u>\n Город: <b>{$res->name}</b>\nСтрана:{$res->sys->country}\nПогода:<b>{$res->weather[0]->description}</b>\nТемпература: <b>$temp</b>";
    $response = $telegram->sendMessage([
        'chat_id' => $chat_id,
        'text' => $res->weather[0]->description,
        'parse_mode' => 'HTML',
    ]);
}


/*https://api.telegram.org/bot6346496062:AAHnTTQ1WRLAXE0lMSMJPZxRs2tthkSS6nA/setWebhook?url=https://testphpbotalex.000webhostapp.com/weather-bot/index.php*/



