<?php

$hostname= 	'192.168.1.27';
$port= 		'3306';
$username=	'root';
$pass=		'';
$database=	'fortify';

$uid= 			$_POST['uid'];
$nickname= 		$_POST['nickname'];
$file_path= 	$_POST['file_path'];
$file_type= 	$_POST['file_type'];
$upload_date= 	$_POST['upload_date'];
$case_index= 	$_POST['case_index'];
$state=			$_POST['state'];
$officer=		$_POST['officer'];

$sqlrequest;

try {
	$conn = new PDO("mysql:host=$hostname; port=$port; dbname=$database; charset=UTF8;", $username, $pass);
	$sqlrequest = "INSERT INTO evidence (uid, nickname, filepath, type, uploaddate, caseindex, state, officer) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
	$statement = $conn->prepare($sqlrequest);
	$statement->execute(array($uid, $nickname, $file_path, $file_type, $upload_date, $case_index, $state, $officer));
	echo "FILEPOST: Successfully posted to database.";
} catch(PDOException $e) {
	die("FILEPOST: ".$sqlrequest."<br>".$e->getMessage());
}

?>