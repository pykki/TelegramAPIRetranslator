<?php // Telegram API retranslator, if host-provider blocked

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

define('DEBUG', false);
define('WEBHOOK_URL', 'https://verytec.ru/telegramm/webhook.php');
define('CLIENT_IPS',['81.177.6.70','217.107.34.136','217.107.219.24','217.107.219.174','217.107.219.218']);

if(!$content = file_get_contents('php://input'))
	get_error('no input');
if(!$r = json_decode($content, true))
 	get_error('json_decode('.$content.') is empty');

if(DEBUG)
	echo '<pre>Debug is ON'.PHP_EOL.'Client IP: '.$_SERVER['REMOTE_ADDR'].PHP_EOL.print_r($r,1).PHP_EOL;

// Reseave commands from Webhook IP's
if(in_array($_SERVER['REMOTE_ADDR'],CLIENT_IPS)){
	if(empty($r['api_url']))
        get_error('api_url is empty');

	define('API_URL', $r['api_url']);
	unset($r['api_url']);

    // /[method] (simple manual requests)
	if(current($r)==''){
		$method = key($r);
		array_shift($r);
        api_request_send($method, $r, API_URL);
	}
	/*
	// For alternative post data; Set or delete webhook, url = '' or [WEBHOOK_URL]
	if(isset($r['request']['name'])){
		if($r['request']['name']=='setWebhook'){
			if(isset($r['request']['value']))
				api_request_send('setWebhook', ['url' => $r['request']['value']], API_URL);
		}
		else
            api_request_send($r['request']['name'], $r['request']['value']??[], API_URL);
	}
	*/
	exit('{"result":false,"error":"end request section"}');
}

if(DEBUG)
	die('Debug: end request section');

// Translate to Webhook
send_data($content);

function api_request($method,$parameters=[]){
	if(!is_string($method))
		get_error('Method name must be a string');
	if(!is_array($parameters))
		get_error('Parameters must be an array');

	$parameters['method'] = $method;

	header('Content-Type: application/json');
	echo json_encode($parameters);
	return true;
}

function api_request_send($method,$parameters=[],$api_url){
	if(!is_string($method))
		get_error('Method name must be a string');
	if(!is_array($parameters))
		get_error('Parameters must be an array');

	$url = $api_url.$method.($parameters?'?'.http_build_query($parameters):'');

	if(DEBUG)
		exit('Fake get data from: '.$url);

	exit(file_get_contents($url));

	// Alternative POST data
	$parameters['method'] = $method;
    send_data($parameters,$api_url.$method);
}

function get_error($string){
	exit('{"result":false,"error":"'.addslashes($string).'"}');
}

function send_data($content,$url=false){
	$url = $url?:WEBHOOK_URL;

	if(DEBUG)
		exit('Fake send data: '.print_r($content,1).' to: '.$url);

    $options = [
    	'http' => [
        	'header'  => "Content-type: application/json\r\n",
        	'method'  => 'POST',
        	'content' => $content
		]
	];
	$context  = stream_context_create($options);
	exit(file_get_contents($url,false,$context));
}

?>