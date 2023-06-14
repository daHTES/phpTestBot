<?php


class telegram{

    protected $token;
    protected $baseUrl = 'https://api.telegram.org/bot';

    public $update_id;

    public function __construct($token){
        $this->token = $token;
        $this->baseUrl .= $token . '/';
    }

    public function sendRequest($method, $params = []){
        $url = $this->baseUrl . $method;
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }
        return json_decode(file_get_contents($url));
    }

    public function getUpdates(){
        $params = [];
        if (!empty($this->update_id)) {
            $params = [
                'offset' => $this->update_id + 1,
            ];
        }
        $res = ($this->sendRequest('getUpdates', $params))->result;
        if (!empty($res)) {
            $this->update_id = $res[count($res) - 1]->update_id;
        }
        return $res;
    }

    public function sendMessage($chat_id, $text, $params = []){
        return $this->sendRequest('sendMessage', array_merge([
            'chat_id' => $chat_id,
            'text' => $text,
        ], $params));
    }

    public function sendPhoto($chat_id, $photo, $params = []){

        $url = $this->baseUrl . "sendPhoto";

        file_put_contents(__DIR__ . '/test.jpg', file_get_contents($photo));

        $postFields = array_merge([
            'chat_id' => $chat_id,
            'photo' => new CURLFile(realpath( __DIR__ . '/test.jpg'))
        ], $params);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HEADER, array("Content-Type:multipart/form-data"));
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postFields);
        $output = curl_exec($curl);

        return json_decode($output);

/*        return $this->sendRequest('sendPhoto', array_merge([
            'chat_id' => $chat_id,
            'photo' => $photo,
        ], $params));*/


    }


}