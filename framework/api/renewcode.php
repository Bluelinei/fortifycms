<?php

if(isset($_GET['user']))
{
	require_once '../dbconnect.php';
	require_once '../twilio.php';

	$authcodelen = 7;
	$return = query("SELECT * FROM users WHERE username=?", array($_GET['user']));

	$chars = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$exptime = time()+(tMINUTE*10);
	$code = "";
	while(strlen($code)<$authcodelen) {$code .= substr($chars,rand(0,strlen($chars)-1),1);}
	update("UPDATE users SET authcode=?, authexpire=? WHERE username=?", array($code, $exptime, $return['username']));
	sendMessage($return['phone'], "Fortify Authentication Code: ".$code);
	echo 1;
}

?>