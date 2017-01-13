<?php

include 'dbconnect.php';

function tokenizeUID($string)
{
	$tokens = [];
	while(strlen($string))
	{
		array_push($tokens, substr($string,0,16));
		$string = substr($string, 16);
	}
	return $tokens;
}

function getData()
{
	//Compile list of cases associated with username and database
	$cases = query("SELECT * FROM cases WHERE users LIKE ?",array($_SESSION['user']),true);
	//Get list of all uid's that need to be pulled from the database
	$evidence = query("SELECT * FROM evidence WHERE users LIKE ?",array($_SESSION['user']),true);
	//Return JSON string to requestee
	$return = [];
	$return['cases'] = $cases;
	$return['evidence'] = $evidence;
	echo json_encode($return);
	return;
}

function getUnfort()
{
	try {
		$response = query("SELECT * FROM evidence WHERE fortified=0 AND user=?", array($_SESSION['user']), true);
		if($response) echo json_encode($response);
		return;
	} catch(PDOException $e) {getError($e);}
}

function getUID()
{
	$hex = array("0","1","2","3","4","5","6","7","8","9",
				 "A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z",
				 "a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z");
	$uid;
	$status = true;
	while($status)
	{
		$status=false;
		$uid = "";
		while(strlen($uid)<16) {$uid .= $hex[rand(0,61)];}

		try {
			$reply = query("SELECT TOP 1 FROM quickreport WHERE uid=?", array($uid));
			if($reply) $status=true;
		} catch(PDOException $e) {getError($e);}
	}
	echo $uid;
	return;
}

if(isset($_POST['function']))
{
	switch($_POST['function'])
	{
		case 'get':
			getData();
			break;
		case 'unfort':
			getUnfort();
			break;
		case 'caseuid':
			getUID();
			break;
		default:
			echo "Undefined function: ".$_POST['function'];
			break;
	}
} else {
	echo "Unset function or table rules.";
}

?>