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
$log->warning("Original content: " . $content);

$boturl = 'https://agentfortune.herokuapp.com/line/webhook';
$qualifier_url = "https://agentfortune.herokuapp.com/qualifier/";
// $data = [
// 	'replyToken' => $replyToken,
// 	'messages' => [$messages],
// ];

$events = json_decode($content, true);

if (!is_null($events['events'])) {	
	foreach ($events['events'] as $event) {
		$headers = array('Content-Type: application/json');
		
		$ch = curl_init($boturl);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		$result = curl_exec($ch);
		curl_close($ch);


		// if ($event['type'] == 'postback') {
		// 	// Extract data data and make request call to the endpoint
		// 	$postback_data = $event['postback']['data'];
		// 	$postback_url = $qualifier_url . $postback_data;
			
		// 	$log->warning("Postback URL: " . $postback_url);

		// 	// Get cURL resource
		// 	$curl = curl_init();
		// 	// Set some options - we are passing in a useragent too here
		// 	curl_setopt_array($curl, array(
		// 		CURLOPT_RETURNTRANSFER => 1,
		// 		CURLOPT_URL => $postback_url,
		// 		CURLOPT_USERAGENT => 'Chatbot line webhook'
		// 	));
		// 	// Send the request & save response to $resp
		// 	$resp = curl_exec($curl);
		// 	// Close request to clear up some resources
		// 	curl_close($curl);
		// }
		// elseif ($event['type'] == 'message' && $event['message']['type'] == 'text') {
		// 	//$post = json_encode($data);
		// 	$headers = array('Content-Type: application/json');

		// 	$ch = curl_init($boturl);
		// 	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		// 	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// 	curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
		// 	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		// 	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		// 	$result = curl_exec($ch);
		// 	curl_close($ch);
		// }
		// else{
		// 	//$post = json_encode($data);
		// 	$headers = array('Content-Type: application/json');

		// 	$ch = curl_init($boturl);
		// 	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		// 	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// 	curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
		// 	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		// 	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		// 	$result = curl_exec($ch);
		// 	curl_close($ch);
		// }
	}
}



//logToFile(gettype($content), $logFileName);
//$log->warning("Original message: " . $content);

//$content_raw = logvar($content);

//error_log(gettype($content), 3, "bot.log", "");
//logvar($content);	

if(false){
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

				$log->warning("Raw: " . json_encode($co->raw));

				if($co->action->done){
					// Perform custom action
					// Can be checked from slug and done
					$log->warning("Perform the action from conversation response after the intent is done");

					// implement json response from api

				}else
				{
					$log->warning("Unfinished conversation.");
				}

				// TODO: session handling
				// 

				if($co->intents[0]->slug == "greetings")
				{

				}

				$reply_text = $co->reply() . "\n Intent: " . $co->intents[0]->slug . "\n Completed: " . $co->action->done . "\n Token: " . $co->conversation_token . "\n Timestamp: " . $co->timestamp;
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
}

echo "OK";
?>
