<?php // ретранслятор для работы Бота через удаленный сервер

define('WEBHOOK_URL', 'https://verytec.ru/telegramm/webhook.php');
define('CLIENT_IPS',['81.177.6.70','217.107.34.136','217.107.219.24','217.107.219.174','217.107.219.218']);

if(!$content = file_get_contents('php://input'))
	get_error('no input');
if(!$r = json_decode($content, true))
 	get_error('json_decode('.$content.') is empty');

// Reseave commands from Webhook IP's
if(in_array($_SERVER['REMOTE_ADDRESS'],CLIENT_IPS)){
	if(empty($r['token']) && empty($r['url']))
        get_error('Token or/and Url is empty');

    define('BOT_TOKEN', $r['token']);
	define('API_URL', $r['url'].BOT_TOKEN.'/');

    // Set or delete webhook, url = '' or [WEBHOOK_URL]
	if(isset($r['request']['name'])){
		if($r['request']['name']=='setWebhook'){
			if(isset($r['request']['value']))
				api_request_send('setWebhook', ['url' => $r['request']['value']], API_URL);
		}
		else
            api_request_send($r['request']['name'], $r['request']['value']??[], API_URL);
	}
	exit('{"result":false,"error":"end request section"}');
}

// Translate to Webhook
send_data($content);

function api_request($method,$parameters=[]){
	if(!is_string($method)){
		get_error('Method name must be a string');
        return false;
	}
	if(!is_array($parameters)){
		get_error('Parameters must be an array');
        return false;
	}

	$parameters['method'] = $method;

	header('Content-Type: application/json');
	echo json_encode($parameters);
	return true;
}

function api_request_send($method,$parameters=[],$api_url){
	if(!is_string($method)){
		get_error('Method name must be a string');
        return false;
	}
	if(!is_array($parameters)){
		get_error('Parameters must be an array');
        return false;
	}
	//$parameters['method'] = $method;
    send_data($parameters,$api_url.$method);
}

function get_error($string){
	exit('{"result":false,"error":"'.addslashes($string).'"}');
}

function send_data($content,$url=false){
    $options = [
    	'http' => [
        	'header'  => "Content-type: application/json\r\n",
        	'method'  => 'POST',
        	'content' => $content
		]
	];
	$context  = stream_context_create($options);
	exit(file_get_contents($url?:WEBHOOK_URL,false,$context));
}

?>