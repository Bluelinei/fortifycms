<?php

$hostname= 	'68.169.178.232';
$port= 		'3306';
$username=	'user';
$pass=		'';
$database=	'fortify';

if(!isset($_SESSION)) session_start();

function getError($e) {die("LOGIN: ".$e->getMessage());}

try{
	$conn = new PDO("mysql:host=$hostname; port=$port; dbname=$database; charset=UTF8;", $username, $pass);
} catch(PDOException $e) {echo getError($e);}

function login($conn)
{
	$sql = "SELECT name FROM users WHERE username=? AND password=?";
	$stmt = $conn->prepare($sql);
	$stmt->execute(array($_POST['user'], $_POST['pass']));
	$return = $stmt->fetch();

	if($return)
	{
		$_SESSION['user'] = $_POST['user'];
		$_SESSION['name'] = $return['name'];
		echo true;
	}
	else echo false;
}

function logout()
{
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