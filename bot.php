<?php
require "botengine.php";
$logFileName = "bot.log";

// file_put_contents("bot.log", "test\r\n");

function logvar($object){
	global $logFileName;
	ob_start();
	var_dump($object);
	$logmsg = ob_get_contents();
	ob_end_clean();
	//logToFile($logmsg, $logFileName);
	
	return $logmsg;
}

function sendMessage($replyToken,  $messages, $accessToken) {
	
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


$access_token = 'dIZf/b/ZabUO0IafFmPxBvcG9xPKQXtGZ6wClV70CCqTwV1TJDT1m58rdm3pko08nIimFRk5wmcElbc7mF9ZXkntG7goq5NDifdSJBkGLyReznHswZuhR77uOYc9ryJIVAfhouccWFwtKMIMucBXpQdB04t89/1O/w1cDnyilFU=';

// Get POST body content
$content = file_get_contents('php://input');

//logToFile(gettype($content), $logFileName);

$content_raw = logvar($content);

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

			// Build message to reply back
			$messages = [
				'type' => 'text',
				//'text' => $text
				'text' => getReply($text)//."\n\r".$content_raw
			];
			
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
			sleep(5);
			
			echo sendMessage($replyToken, $messages, $access_token);
		}
	}
}
echo "OK";
?>
