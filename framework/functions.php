<?php

$hostname = '192.168.1.24';
$port = '3306';
$username='zhwatts';
$pass='';
$database = 'fortify';

error_reporting(E_ALL);

function getData()
{

}

function checkUID()
{
	try{
		$hostname = '192.168.1.24';
		$port = '3306';
		$username='zhwatts';
		$pass='';
		$database = 'fortify';
		$conn = new PDO("mysql:host=$hostname; port=$port; dbname=$database; charset=UTF8;", $username, $pass);
		$ifexist = "SELECT EXISTS(SELECT 1 FROM ". $_POST['table'] ." WHERE uid=".$_POST['uid'];
		$stmt = $conn->prepare($ifexist);
		$stmt->execute();
		$result = $stmt->fetch();
		echo $result;
	} catch(PDOException $e) {
		echo "ERROR: ".$e->getMessage();
	}
}

if(isset($_POST['function'])&&isset($_POST['table']))
{
	switch($_POST['function'])
	{
		case 'get':
			getData();
			break;
		case 'checkuid':
			checkUID();
			break;
		default:
			echo "Undefined function: ".$_POST['function'];
			break;
	}
}

?>