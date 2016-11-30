<?php

include 'dbconnect.php';

function getData($conn)
{
	switch($_POST['table'])
	{
		case 'quickreport':
		{
			try {
				$response = query("SELECT * FROM quickreport WHERE officer=?", array($_SESSION['user']), true);
				if($response) echo json_encode($response);
			} catch(PDOException $e) {getError($e);}
			break;
		}
		case 'evidence':
		{
			try {
				$response = query("SELECT * FROM evidence WHERE uid=? AND user=?", array($_POST['uid'], $_SESSION['user']));
				if($response) echo json_encode($response);
			} catch(PDOException $e) {getError($e);}
			break;
		}
	}
}

function getUnfort($conn)
{
	try {
		$response = query("SELECT * FROM evidence WHERE fortified=0 AND user=?", array($_SESSION['user']), true);
		if($response) echo json_encode($response);
		return;
	} catch(PDOException $e) {getError($e);}
}

if(isset($_POST['function'])&&isset($_POST['table']))
{
	switch($_POST['function'])
	{
		case 'get':
			getData($conn);
			break;
		case 'unfort':
			getUnfort($conn);
			break;
		default:
			echo "Undefined function: ".$_POST['function'];
			break;
	}
} else {
	echo "Unset function or table rules.";
}

?>