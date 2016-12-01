<?php

include 'dbconnect.php';

function updateEntry($conn)
{
	try {
		update(	"UPDATE evidence SET nickname=?, filepath=?, type=?, uploaddate=?, caseindex=?, fortified=?, user=?, checksum=? WHERE uid=?",
				array($_POST['nickname'],$_POST['file_path'],$_POST['file_type'],$_POST['upload_date'],$_POST['case_index'],$_POST['fortified'],$_SESSION['user'], sha1_file($_POST['file_path']),$_POST['uid']));
		echo "Updated evidence in database ".$_POST['case_index'];
		return;
	} catch(PDOException $e) {echo getError($e);}
}

function newEntry($conn)
{
	try {
		update(	"INSERT INTO evidence (uid, nickname, filepath, type, uploaddate, lastmodified, caseindex, fortified, user, checksum) VALUES (?,?,?,?,?,?,?,?,?)",
				array($_POST['uid'],$_POST['nickname'],$_POST['file_path'],$_POST['file_type'],$_POST['upload_date'],$_POST['lastmodified'],$_POST['case_index'],$_POST['fortified'],$_SESSION['user'],sha1_file($_POST['file_path'])));
		echo "Uploaded new evidence to database: ".$_POST['case_index'];
		return;
	} catch(PDOException $e) {echo getError($e);}
}

try {
	$reply = query("SELECT EXISTS(SELECT 1 FROM evidence WHERE uid=?)", array($_POST['uid']), false, false);
	if($reply[0]!=0) updateEntry($conn);
	else newEntry($conn);
} catch(PDOException $e) {echo getError($e);}

?>