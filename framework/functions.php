<?php

include 'dbconnect.php';

function getData($conn)
{
	switch($_POST['table'])
	{
		case 'quickreport':
		{
			try {
				$sql = "SELECT * FROM quickreport WHERE officer=?";
				$stmt = $conn->prepare($sql);
				$stmt->execute(array($_SESSION['user']));
				$response = $stmt->fetchALL(PDO::FETCH_ASSOC);
				echo json_encode($response);
			} catch(PDOException $e) {getError($e);}
			break;
		}
		case 'evidence':
		{
			try {
				$sql = "SELECT * FROM evidence WHERE uid=?";
				$stmt = $conn->prepare($sql);
				$stmt->execute(array($_POST['uid']));
				$response = $stmt->fetch(PDO::FETCH_ASSOC);
				if($response) echo json_encode($response);
				else echo $_POST['uid'];
			} catch(PDOException $e) {getError($e);}
			break;
		}
	}
	
}

function getUID($conn)
{
	//GENERATE RANDOM NUMBER
	
}

if(isset($_POST['function'])&&isset($_POST['table']))
{
	switch($_POST['function'])
	{
		case 'get':
			getData($conn);
			break;
		case 'set':
			getUID($conn);
			break;
		default:
			echo "Undefined function: ".$_POST['function'];
			break;
	}
} else {
	echo "Unset function or table rules.";
}

?>