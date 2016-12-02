<?php

include 'dbconnect.php';

function login($conn)
{
	$sql = "SELECT * FROM users WHERE username=? AND password=?";
	$stmt = $conn->prepare($sql);
	$stmt->execute(array($_POST['user'], $_POST['pass']));
	$return = $stmt->fetch();

	if($return)
	{
		$_SESSION['user'] = $_POST['user'];
		$_SESSION['name'] = $return['name'];
		$_SESSION['agency'] = $return['agency'];
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