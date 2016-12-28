<?php

require_once 'session.php';
require_once 'dbconnect.php';

$return = query("SELECT * FROM users WHERE username=?", array($_SESSION['user']));

if($_POST['code']==$return['authcode']&&time()<$return['authexpire'])
{
	$_SESSION['login'] = 'true';
	$_SESSION['id'] = sha1(session_id());
	$sessionexp = time() + tDAY;
	update("UPDATE users SET authcode='', authexpire=0, sessid=?, sessionexp=? WHERE username=?", array($_SESSION['id'], $sessionexp, $_SESSION['user']));
	echo 'true';
	return;
}

?>