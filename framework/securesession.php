<?php

require_once 'dbconnect.php';

function forceLogin()
{
	header("Location: https://".$_SERVER['SERVER_NAME']."/fortify/login.php");
	die();
}

$DATABASE = $_SESSION['agency'];

if(!isset($_SESSION['login'])||!isset($_SESSION['user'])) forceLogin();

$return = query("SELECT * FROM users WHERE username=?",array($_SESSION['user']));

if(!isset($_SESSION['id'])||$_SESSION['id']!=$return['sessid']||time()>=$return['sessexpire'])
{
	update("UPDATE users SET sessionexp=0, sessid='' WHERE username=?", array($_SESSION['user']));
	forceLogin();
}

?>