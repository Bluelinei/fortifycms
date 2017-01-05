<?php

require_once 'dbconnect.php';
require_once 'twilio.php';

function login($conn)
{
	$authcodelen = 7;
	$return = query("SELECT * FROM users WHERE username=? AND password=?", array($_POST['user'], $_POST['pass']));

	if($return)
	{
		$_SESSION['user'] = $return['username'];
		$_SESSION['name'] = $return['name'];
		$_SESSION['agency'] = $return['agency'];
		if(time()>=$return['sessionexp']&&$return['2fa']==1)
		{
			//do 2fa
			$chars = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$exptime = time()+(tMINUTE*10);
			$code = "";
			while(strlen($code)<$authcodelen) {$code .= substr($chars,rand(0,strlen($chars)-1),1);}
			update("UPDATE users SET authcode=?, authexpire=? WHERE username=?", array($code, $exptime, $return['username']));
			sendMessage($return['phone'], "Fortify Authentication Code: ".$code);
			echo '2fa';
		}
		else {
			//create session login
			$_SESSION['login'] = 'true';
			$_SESSION['id'] = sha1(session_id());
			update("UPDATE users SET sessid=?, sessionexp=? WHERE username=?", array($_SESSION['id'], time()+(tHOUR*12), $_SESSION['user']));
			echo 'login';
		}
	}
	else echo false;
}

function logout()
{
	update("UPDATE users SET sessid='' WHERE username=?", array($_SESSION['user']));
	session_unset();
	session_destroy();
}


switch($_POST['func'])
{
	case "login":
	{
		login($conn);
		break;
	}
	case "logout":
	{
		logout();
		break;
	}
}

?>