<?php

require_once 'dbconnect.php';

function forceLogin()
{
	header("Location: login.php");
	die();
}

if(!isset($_SESSION['login'])||!isset($_SESSION['user'])) forceLogin();

$return = query("SELECT * FROM users WHERE username=?",array($_SESSION['user']));

if(!isset($_SESSION['id'])||$_SESSION['id']!=$return['sessid']||time()>=$return['sessionexp'])
{
	update("UPDATE users SET sessionexp=0, sessid='' WHERE username=?", array($_SESSION['user']));
	forceLogin();
}

?>