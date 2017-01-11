<?php

include 'dbconnect.php';

$data = json_decode($_POST['data'],true);

echo $_POST['data'];
die();

function updateEntry()
{
	global $data;
	try {
		update(	"UPDATE evidence SET nickname=?, caseindex=?, checksum=?, data=? WHERE uid=?",
				array($_POST['nickname'],$_POST['case_index'],sha1_file($data['file_path']),$_POST['uid']),$_POST['data']);
		echo "Updated evidence in database ".$_POST['case_index'];
		return;
	} catch(PDOException $e) {echo getError($e);}
}

function newEntry()
{
	global $data;
	return;
	try {
		update(	"INSERT INTO evidence (uid, nickname, caseindex, checksum, data) VALUES (?,?,?,?,?)",
				array($_POST['uid'],$_POST['nickname'],$_POST['case_index'],sha1_file($data['file_path']), $_POST['data']));
		echo "Uploaded new evidence to database: ".$_POST['case_index'];
		return;
	} catch(PDOException $e) {echo getError($e);}
}

try {
	$reply = query("SELECT * FROM evidence WHERE uid=?", array($_POST['uid']), false, false);
	if($reply) updateEntry();
	else newEntry();
} catch(PDOException $e) {echo getError($e);}

?>