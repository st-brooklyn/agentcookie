<?php
use RecastAI\CLient;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// Create a log channel
$log = new Logger('AI Manager');
$log->pushHandler(new StreamHandler('php://stderr', Logger::WARNING));

$request_token = "ab4f0c7d1fabde3fe483e243167171bc";
$language_code = "th";

// add records to the log
// $log->warning('Foo');
// $log->error('Bar');

function ask_ai($text) {
    global $request_token;
    global $language_code;

    $client = new Client($request_token, $language_code);

    $res = $client->request->converseText($text);
    $reply = $res->reply();

    $conver_token = $res->conversation_token;
    $action = $res->action();

    return $reply;
    //var_dump($reply);
}
?>