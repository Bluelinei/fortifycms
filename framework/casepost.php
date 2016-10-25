<?php

$hostname = '192.168.1.24';
$port = '3306';
$username='zhwatts';
$pass='';
$database = 'fortify';

$uid = $_POST['uid'];
$nickname = $_POST['nickname'];
$reportnum = $_POST['reportnum'];
$reportloc = $_POST['reportloc'];
$reporttype = $_POST['reporttype'];
$reporttags = $_POST['reporttags'];
$evidence = $_POST['evidence'];
$admin = $_POST['admin'];

$sqlrequest;

function updateEntry();

function newEntry();

try {
	$conn = new PDO("mysql:host=$hostname; port=$port; dbname=$database; charset=UTF8;", $username, $pass);
	$ifexist = 'SELECT EXISTS(SELECT 1 FROM quickreport WHERE uid=$uid';
	$conn->exec($ifexist);
	if($conn) updateEntry();
	else newEntry();
	echo "CASEPOST: Successfully posted to database.";
} catch(PDOException $e) {
	die("CASEPOST: (".$sqlrequest.") ".$e->getMessage());
}

?>