<?php


namespace rollun\utils;


use rollun\dic\InsideConstruct;
use Zend\Http\Client;

class TelegramClient
{

    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $token;

    /**
     * TelegramClient constructor.
     * @param $token
     * @param Client|null $client
     * @throws \ReflectionException
     */
    public function __construct($token, Client $client = null)
    {
        InsideConstruct::setConstructParams(["client" => Client::class]);
        $this->client = $client;
        $this->token = $token;
    }

    /**
     * @param $text
     * @param $chatId
     * @param null $parseMode
     * @return bool
     */
    public function write($text, $chatId, $parseMode = null) {
        $content = [
            "chat_id" => $chatId,
            "text" => $text
        ];
        if(isset($parseMode)) {
            $content["parse_mode"] = $parseMode;
        }
        $rawContent = json_encode($content);
        $client = clone $this->client;
        $client->setHeaders([
            "Content-Type" => "application/json",
            "Content-Length"=> strlen($rawContent)
        ]);
        $client->setUri("https://api.telegram.org/bot{$this->token}/sendMessage");
        $client->setMethod("POST");
        $client->setRawBody($rawContent);
        $response = $client->send();
        return $response->isSuccess();
    }

    /**
     * @return array
     */
    public function getUpdates() {
        $client = clone $this->client;
        $client->setUri("https://api.telegram.org/bot{$this->token}/getUpdates");
        $response = $client->send();
        $updates = json_decode($response->getBody(), true);
        return $updates;
    }
}