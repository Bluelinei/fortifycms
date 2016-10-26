<?php

$hostname=	'localhost';
$port=		'3306';
$username=	'root';
$pass=		'';
$database=	'fortify';

error_reporting(E_ALL);

function getError($e) {die("CASEPOST: ".$e->getMessage());}

try{
	$conn = new PDO("mysql:host=$hostname; port=$port; dbname=$database; charset=UTF8;", $username, $pass);
} catch(PDOException $e) {getError($e);}

function getData($conn)
{

}

function getUID($conn)
{
	$hostname=	'localhost';
	$port=		'3306';
	$username=	'root';
	$pass=		'';
	$database=	'fortify';
	//GENERATE RANDOM NUMBER
	$hex = array("0","1","2","3","4","5","6","7","8","9","A","B","C","D","E","F");
	$genuid;
	$status = true;
	while($status)
	{
		$status=false;
		$genuid = "";
		while(strlen($genuid)<16)
		{
			$genuid .= $hex[rand(0,15)];
		}

		try {
			$ifexist = "SELECT EXISTS(SELECT 1 FROM ? WHERE uid=?";
			$stmt = $conn->prepare($ifexist);
			$stmt->execute(array($_POST['table'], $genuid));
			if($stmt->fetch()) $status=true;
		} catch(PDOException $e) {getError($e);}
	}
	echo $genuid;
}

if(isset($_POST['function'])&&isset($_POST['table']))
{
	switch($_POST['function'])
	{
		case 'get':
			getData($conn);
			break;
		case 'getuid':
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