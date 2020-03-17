<?php

// Put this file in your site

ini_set('allow_url_fopen', true);
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', './php_errors.log');
register_shutdown_function('fatal_handler_min');
function fatal_handler_min(){
	file_put_contents('./php_errors.log',date('d.m.y H:i:s ').print_r(error_get_last(),1).PHP_EOL,FILE_APPEND);
}

define('DEBUG', false);
define('BOT_TOKEN', '[bot_token]');
define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');
define('WEBHOOK_URL', 'https://[your_site]/webhook.php');
define('RETRANSLATOR_URL', 'https://[your_retranslator].appspot.com'); // empty if none
define('REMOTE_CONTROL', !false); // controlls with GET commands for testing
define('REMOTE_KEY', '[secret_key]'); // if Retranslator use it

// REMOTE_CONTROL
if(!empty($_GET)){
	if(!REMOTE_CONTROL)
		exit('REMOTE_CONTROL is OFF');
	$parameters = $_GET;
	$parameters['api_url'] = API_URL;
	$parameters['remote_key'] = REMOTE_KEY;
	send_data(json_encode($parameters));
}

header('Content-Type: text/json; charset=utf-8');
mb_internal_encoding('UTF-8');
mb_regex_encoding('UTF-8');

$content = file_get_contents("php://input");
if(!$update = json_decode($content, true))
	get_error('json_decode('.$content.') is empty'.PHP_EOL);

if(isset($update['message'])){
  	processMessage($update["message"]);
}

function processMessage($message){
	if(isset($message['text'])){
		// incoming text message
		$text = $message['text'];

		if(strpos($text, '/start') === 0){
			send_data(['method' => 'sendMessage', 'chat_id' => $message['chat']['id'], 'text' => 'Hi']);
		}
		elseif(strpos($text, '/stop') === 0){
	  		// stop now
		}
		elseif(strpos($text, '/exit') === 0){
			send_data(['methood' => 'sendMessage', 'chat_id' => $message['chat']['id'], /*"reply_to_message_id" => $message['message_id'],*/ 'text' => 'Bye']);
		}
		else{
			send_data(['method' => 'sendMessage', 'chat_id' => $message['chat']['id'], /*"reply_to_message_id" => $message['message_id'],*/ 'text' => 'ok']);
		}
  	}
	else{
		send_data(['method' => 'sendMessage', 'chat_id' => $message['chat']['id'], 'text' => 'Only text please.']);
  	}
}

function send_data($content,$url=false){
	if(is_array($content)){
		$content['api_url'] = API_URL;
		$content['remote_key'] = REMOTE_KEY;
		$content = json_encode($content);
	}

	if(!DEBUG)
		echo $content; // as result for Telegram
	else{
		$debug = json_decode($content,1);
		$debug['api_url'] = isset($debug['api_url'])?str_replace(BOT_TOKEN,'BOT_TOKEN_HIDDEN',$debug['api_url']):'';
		$debug['remote_key'] = isset($debug['remote_key'])?'REMOTE_KEY_HIDDEN':'';
		echo '<pre>CONTENT: '.print_r($debug,1).PHP_EOL;
	}
	$options = [
		'http' => [
			'header'  => "Content-type: application/json\r\n",
			'method'  => 'POST',
			'content' => $content,
			'timeout' => 30
		]
	];
	$context  = stream_context_create($options);
	$url = $url?:(RETRANSLATOR_URL?:API_URL);

	if(DEBUG)
		echo 'URL: '.$url.PHP_EOL.PHP_EOL;

	if(!$content = file_get_contents($url,false,$context))
		get_error('empty content from '.explode(BOT_TOKEN,$url)[0]);

	if(DEBUG)
		echo 'RESULT: '.print_r(json_decode($content,1),1).PHP_EOL;

	exit();
}

function get_error($message){
	error_log(date('d.m.y H:i:s ').$message.PHP_EOL,3,'./php_errors.log');
	exit($message);
}

?>
