<?php

include 'dbconnect.php';

$ds = DIRECTORY_SEPARATOR;

$uploadPath = ".".$ds."uploads".$ds.$_SESSION['agency'].$ds.$_SESSION['user'].$ds;

function updateEntry($conn)
{
	global $uploadPath;
	try {
		$sql = "UPDATE evidence SET nickname=?, filepath=?, type=?, uploaddate=?, caseindex=?, fortified=?, officer=?, checksum=? WHERE uid=?";
		$stmt = $conn->prepare($sql);
		$stmt->execute(array($_POST['nickname'],$_POST['file_path'],$_POST['file_type'],$_POST['upload_date'],$_POST['case_index'],$_POST['state'],$_SESSION['user'], sha1_file($uploadPath.$_POST['file_path']),$_POST['uid']));
		echo "Updated evidence in database";
	} catch(PDOException $e) {echo getError($e);}
}

function newEntry($conn)
{
	global $uploadPath;
	try {
		$sql = "INSERT INTO evidence (uid, nickname, filepath, type, uploaddate, caseindex, fortified, officer, checksum) VALUES (?,?,?,?,?,?,?,?,?)";
		$stmt = $conn->prepare($sql);
		$stmt->execute(array($_POST['uid'],$_POST['nickname'],$_POST['file_path'],$_POST['file_type'],$_POST['upload_date'],$_POST['case_index'],$_POST['state'],$_SESSION['user'],sha1_file($uploadPath.$_POST['file_path'])));
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