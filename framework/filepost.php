<?php

$hostname= 	'192.168.1.27';
$port= 		'3306';
$username=	'user';
$pass=		'';
$database=	'fortify';

function getError($e) {die("CASEPOST: ".$e->getMessage());}

try{
	$conn = new PDO("mysql:host=$hostname; port=$port; dbname=$database; charset=UTF8;", $username, $pass);
} catch(PDOException $e) {echo getError($e);}

function updateEntry($conn)
{
	try {
		$sql = "UPDATE evidence SET nickname=?, filepath=?, type=?, uploaddate=?, caseindex=?, fortified=?, officer=? WHERE uid=?";
		$stmt = $conn->prepare($sql);
		$stmt->execute(array($_POST['nickname'],$_POST['file_path'],$_POST['file_type'],$_POST['upload_date'],$_POST['case_index'],$_POST['state'],$_POST['officer'],$_POST['uid']));
		echo "Updated evidence in database";
	} catch(PDOException $e) {echo getError($e);}
}

function newEntry($conn)
{
	try {
		$sql = "INSERT INTO evidence (uid, nickname, filepath, type, uploaddate, caseindex, fortified, officer) VALUES (?,?,?,?,?,?,?,?)";
		$stmt = $conn->prepare($sql);
		$stmt->execute(array($_POST['uid'],$_POST['nickname'],$_POST['file_path'],$_POST['file_type'],$_POST['upload_date'],$_POST['case_index'],$_POST['state'],$_POST['officer']));
		echo "Uploaded new evidence to database";
	} catch(PDOException $e) {echo getError($e);}
}

try {
	$ifexist = "SELECT EXISTS(SELECT 1 FROM evidence WHERE uid=?)";
	$stmt = $conn->prepare($ifexist);
	$stmt->execute(array($_POST['uid']));
	$reply = $stmt->fetch();
	if($reply[0]!="0") updateEntry($conn);
	else newEntry($conn);
} catch(PDOException $e) {echo getError($e);}

?>