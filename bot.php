<?php
require_once __DIR__ . '/vendor/autoload.php';
require "botengine.php";
require "aimanager.php";

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// Create a log channel
$log = new Logger('Main Bot');
$log->pushHandler(new StreamHandler('php://stdout', Logger::WARNING));
$bot_version = '001';

// $log->error('Bar');

function pushMessage($userId, $messages, $accessToken) {
	global $log;

	$url = "https://api.line.me/v2/bot/message/push";
	$data = [
		'to' => $userId,
		'messages' => [$messages]
	];

	$post = json_encode($data);
	$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $accessToken);

	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	$result = curl_exec($ch);
	curl_close($ch);

	$log->warning($result . "\r\n");

	return $result . "\r\n";
}

function sendMessage($replyToken, $messages, $accessToken) {	
	// Make a POST Request to Messaging API to reply to sender
	$url = 'https://api.line.me/v2/bot/message/reply';
	$data = [
		'replyToken' => $replyToken,
		'messages' => [$messages],
	];
				
	$post = json_encode($data);
	$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $accessToken);

	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	$result = curl_exec($ch);
	curl_close($ch);

	return $result . "\r\n";
}

function create_text_message($text)
{
	$messages = [
				'type' => 'text',
				//'text' => $text
				'text' => $text//."\n\r".$content_raw
	];

	return $messages;
}

$log->warning('Version: ' . $bot_version);

$access_token = 'dIZf/b/ZabUO0IafFmPxBvcG9xPKQXtGZ6wClV70CCqTwV1TJDT1m58rdm3pko08nIimFRk5wmcElbc7mF9ZXkntG7goq5NDifdSJBkGLyReznHswZuhR77uOYc9ryJIVAfhouccWFwtKMIMucBXpQdB04t89/1O/w1cDnyilFU=';

// Get POST body content
$content = file_get_contents('php://input');

//logToFile(gettype($content), $logFileName);
$log->warning("Original message: " . $content);

//$content_raw = logvar($content);

//error_log(gettype($content), 3, "bot.log", "");
//logvar($content);	

// Parse JSON
$events = json_decode($content, true);
// Validate parsed JSON data
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {
		// Reply only when message sent is in 'text' format
		if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
			// Get text sent
			$text = $event['message']['text'];
			// Get replyToken
			$replyToken = $event['replyToken'];
			$userId = $event["source"]["userId"];

			$log->warning("User ID: " . $userId);

			// Make a request to recast.ai
			//$reply_text = "Your user ID: " . $userId;
			//conversation_object
			$co = ask_ai($text);

			//$log->warning("Raw: " . $co[0]);

			$reply_text = $co->reply() . "\n Intent: " . $co->intents->slug; // . "\n Completed: " . $co->action["done"] . "\n Token: " . $co->conversation_token . "\n Timestamp: " . $co->timestamp;
			//$reply_text = ask_ai($text);
			$log->warning("Reply text: " . $reply_text);

			// Build message to reply back
			$messages = create_text_message($reply_text);

			$push_result = pushMessage($userId, $messages, $access_token);

			$log->warning("Push result: " . $push_result);
			/*
			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
			];			
			
			$post = json_encode($data);
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$result = curl_exec($ch);
			curl_close($ch);

			echo $result . "\r\n";
			*/
			//sleep(5);
			
			//echo sendMessage($replyToken, $messages, $access_token);
			//echo sendMessage($replyToken, $messages, $access_token);
		}
	}
}
echo "OK";
?>