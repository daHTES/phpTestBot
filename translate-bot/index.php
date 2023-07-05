<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/TelegramBot.php';

use \Dejurin\GoogleTranslateForFree;

$token = '5934105479:AAHCuz_sDeTF2Gy-2pNPzf_Fb3ycFctemyk';

$telegram = new TelegramBot($token);

$updates = $telegram->getWebhookUpdates();
file_put_contents(__DIR__ . '/logs.txt', print_r($updates, 1), FILE_APPEND);

$chat_id = $updates['message']['chat']['id'];
$text = $updates['message']['text'] ?? '';

if($text == '/start') {

    $data = get_chat_id($chat_id);
    if (empty($data)) {
        add_chat_id($chat_id, $updates['message']['chat']['first_name'], 'en');
        $check = 'en';
    } else {
        $check = $data['lang'];
    }

    $response = $telegram->sendMessage([
        'chat_id' => $chat_id,
        'text' => "Оставьте отмеченный язык для перевода с него или выберите другой",
        'reply_markup' => $telegram->replyKeyboardMarkup([
            'inline_keyboard' => get_keyboard($check),
        ])
    ]);
} elseif (isset($updates['callback_query']['message'])){

    foreach ($updates['callback_query']['message']['reply_markup']['inline_keyboard'][0] as $item){
        if($item['text'] == $updates['callback_query']['data']){

            update_chat($updates['callback_query']['message']['chat']['id'], $updates['callback_query']['data']);

            $response = $telegram->answerCallbackQuery([
                'callback_query_id' => $updates['callback_query']['id'],
//            'text' => "Язык перевода изменен на {$updates['callback_query']['data']}",
//            'show_alert' => false,
            ]);

            $response = $telegram->sendMessage([
                'chat_id' => $updates['callback_query']['message']['chat']['id'],
                'text' => "Можете вводить текст с выбраного языка",
                'reply_markup' => $telegram->replyKeyboardMarkup([
                    'inline_keyboard' => get_keyboard($updates['callback_query']['data']),
                ])
            ]);

            break;
        }
    }

    $response = $telegram->answerCallbackQuery([
        'callback_query_id' => $updates['callback_query']['id'],
          'text' => "Язык активен!",
          'show_alert' => false,
    ]);

} elseif (!empty($text)){

    $data = get_chat_id($chat_id);
    $source = ($data['lang'] == 'en') ? 'en' : 'ru';
    $target = ($data['lang'] == 'ru') ? 'en' : 'ru';
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

function get_keyboard($lang){
    return [
        [
            ['text' => $lang == 'en' ? 'en ☑': 'en', 'callback_data' => 'en'],
            ['text' => $lang == 'ru' ? 'ru ☑': 'ru', 'callback_data' => 'ru']
        ]
    ];
}

/*https://api.telegram.org/bot5934105479:AAHCuz_sDeTF2Gy-2pNPzf_Fb3ycFctemyk/setWebhook?url=https://testphpbotalex.000webhostapp.com/translate-bot/index.php*/



