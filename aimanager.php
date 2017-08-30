<?php
require_once __DIR__ . '/vendor/autoload.php';

use RecastAI\Client;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// Create a log channel
$log = new Logger('AI Manager');
$log->pushHandler(new StreamHandler('php://stdout', Logger::WARNING));

//f00c9114c22cebbecc7376e7f6f9b993
$request_token = "14dbc382a7fcd17d4df5e3a8a73a0176";
//$request_token = "ab4f0c7d1fabde3fe483e243167171bc";
$language_code = "th";

// add records to the log
// $log->warning('Foo');
// $log->error('Bar');

function ask_ai($text) {
    global $request_token;
    global $language_code;
    global $log;

    $client = new Client($request_token, $language_code);
    $log->warning('Text to be sent: ' . $text);

    $res = $client->request->converseText($text);
    $reply = $res->reply();  // Conversation object

    // $conver_token = $res->conversation_token;
    // $action = $res->action();

    return $res;
    //var_dump($reply);
}
?>