<?php

require_once 'twilio-php-master/Twilio/autoload.php';

use Twilio\Rest\Client;

$CLIENT = new Client('AC6958445dac168debc9a022fdd0449b49', '25c667c39544680e011c0c253edbeca4');

function sendMessage($to, $body)
{
	if(!$to||!$body) return false;
	global $CLIENT;
	try {$message = $CLIENT->messages->create($to, array('from'=>'+14239331748', 'body'=>$body)); return 0;} catch(Exception $e) {return $e;}
}

?>