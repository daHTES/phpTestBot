<?php

require_once 'telegram.php';

$telegram_bot = new telegram('5934105479:AAHCuz_sDeTF2Gy-2pNPzf_Fb3ycFctemyk');

while(true){

    $updates = $telegram_bot->getUpdates();

    if(!empty($updates)){

        file_put_contents(__DIR__ . '/logs.txt', print_r($updates, 1), FILE_APPEND);


        foreach($updates as $update){

            echo $update->message->text . PHP_EOL;

            if($update->message->text == 'photo'){

                $telegram_bot->sendPhoto(
                    $update->message->chat->id,
                    //'https://picsum.photos/id/237/200/300',
                    'test.jpg',
                    ['caption' => 'Твоя бывшая']
                );
            }else {

                $telegram_bot->sendMessage(
                    $update->message->chat->id,
                    "Привет, *{$update->message->from->first_name}*! Вы написали: _{$update->message->text}_", ['parse_mode' => 'Markdown']);
            }



        }
    }
    sleep(3);
}


