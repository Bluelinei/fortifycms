<?php

require_once 'dbconnect.php';
require_once 'twilio.php';

function login()
{
	global $DATABASE;
	global $HOSTNAME;
	global $USERNAME;
	global $PASSWORD;
	try {
		$conn = new PDO("sqlsrv:Server=$HOSTNAME; Database=agentlookup;", $USERNAME, $PASSWORD);
		$stmt = $conn->prepare("SELECT * FROM agencies WHERE id=?");
		$stmt->execute(array($_POST['agentid']));
		$DATABASE = $stmt->fetch()['dbname'];
	} catch(PDOException $e) {
		echo $e->getMessage();
		return;
	}
	$authcodelen = 7;
	$return = query("SELECT * FROM users WHERE username=?", array($_POST['user']));

	if($return['password']==hash('sha512', $return['passwordsalt'].$_POST['pass']))
	{
		$_SESSION['user'] = $return['username'];
		$_SESSION['name'] = $return['name'];
		$_SESSION['agency'] = $return['agency'];
		if(time()>=$return['sessexpire']&&$return['authenticate']==1)
		{
			//do 2fa
			$chars = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$exptime = time()+(tMINUTE*10);
			$code = "";
			while(strlen($code)<$authcodelen) {$code .= substr($chars,rand(0,strlen($chars)-1),1);}
			update("UPDATE users SET authcode=?, authexpire=? WHERE username=?", array($code, $exptime, $return['username']));
			$val = sendMessage($return['phone'], "Fortify Authentication Code: ".$code);
			if($val)
			{
				echo $val;
				return;
			}
			echo '2fa';
		}
		else {
			//create session login
			$_SESSION['login'] = 'true';
			$_SESSION['id'] = sha1(session_id());
			update("UPDATE users SET sessid=?, sessexpire=? WHERE username=?", array($_SESSION['id'], time()+(tHOUR*12), $_SESSION['user']));
			echo 'login';
		}
	}
	else {echo false;}
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
		login();
		break;
	}
	case "logout":
	{
		logout();
		break;
	}
}

?>