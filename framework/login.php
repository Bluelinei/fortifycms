<?php

$hostname= 	'68.169.178.232';
$port= 		'3306';
$username=	'user';
$pass=		'';
$database=	'fortify';

function getError($e) {die("LOGIN: ".$e->getMessage());}

try{
	$conn = new PDO("mysql:host=$hostname; port=$port; dbname=$database; charset=UTF8;", $username, $pass);
} catch(PDOException $e) {echo getError($e);}

$sql = "SELECT 1 FROM users WHERE username=? AND password=?";
$stmt = $conn->prepare($sql);
$stmt->execute(array($_POST['username'], $_POST['password']));

if($stmt->fetch()) echo true;
else echo false;

?>