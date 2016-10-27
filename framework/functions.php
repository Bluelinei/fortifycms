<?php

$hostname=	'192.168.1.27';
$port=		'3306';
$username=	'user';
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
	//GENERATE RANDOM NUMBER
	$hex = array("0","1","2","3","4","5","6","7","8","9",
				 "A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z",
				 "a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z");
	$genuid;
	$status = true;
	while($status)
	{
		$status=false;
		$genuid = "";
		while(strlen($genuid)<16) {$genuid .= $hex[rand(0,61)];}

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