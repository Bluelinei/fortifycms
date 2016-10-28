<?php

$hostname=	'68.169.178.232';
$port= 		'3306';
$username=	'user';
$pass=		'';
$database= 	'fortify';

function getError($e) {die("CASEPOST: ".$e->getMessage());}

try{
	$conn = new PDO("mysql:host=$hostname; port=$port; dbname=$database; charset=UTF8;", $username, $pass);
} catch(PDOException $e) {getError($e);}

function updateEntry($conn)
{
	try {
		$sql = "UPDATE quickreport SET nickname=?, casenum=?, location=?, type=?, tags=?, evidence=?, admin=?, officer=? WHERE uid=?";
		$stmt = $conn->prepare($sql);
		$stmt->execute(array($_POST['nickname'],$_POST['reportnum'],$_POST['reportloc'],$_POST['reporttype'],$_POST['reporttags'],$_POST['evidence'],$_POST['admin'],$_POST['officer'],$_POST['uid']));
		echo "Updated case in database";
	} catch(PDOException $e) {getError($e);}
}

function newEntry($conn)
{
	try {
		$sql = "INSERT INTO quickreport (uid, nickname, casenum, location, type, tags, evidence, admin, officer) VALUES (?,?,?,?,?,?,?,?,?)";
		$stmt = $conn->prepare($sql);
		$stmt->execute(array($_POST['uid'],$_POST['nickname'],$_POST['reportnum'],$_POST['reportloc'],$_POST['reporttype'],$_POST['reporttags'],$_POST['evidence'],$_POST['admin'],$_POST['officer']));
		echo "Created new case in database";
	} catch(PDOException $e) {getError($e);}
}

try {
	$ifexist = "SELECT EXISTS(SELECT 1 FROM quickreport WHERE uid=?)";
	$stmt = $conn->prepare($ifexist);
	$stmt->execute(array($_POST['uid']));
	$reply = $stmt->fetch();
	if($reply[0]!="0") updateEntry($conn);
	else newEntry($conn);
} catch(PDOException $e) {getError($e);}

?>