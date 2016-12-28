<?php

require_once 'dbconnect.php';

function updateEntry($conn)
{
	try {
		update(	"UPDATE quickreport SET nickname=?, casenum=?, location=?, type=?, tags=?, evidence=?, admin=?, officer=? WHERE uid=?",
				array($_POST['nickname'],$_POST['reportnum'],$_POST['reportloc'],$_POST['reporttype'],$_POST['reporttags'],$_POST['evidence'],$_POST['admin'],$_SESSION['user'],$_POST['uid']));
		echo "Updated case in database";
	} catch(PDOException $e) {getError($e);}
}

function newEntry($conn)
{
	try {
		update(	"INSERT INTO quickreport (uid, nickname, casenum, location, type, tags, evidence, admin, officer) VALUES (?,?,?,?,?,?,?,?,?)",
				array($_POST['uid'],$_POST['nickname'],$_POST['reportnum'],$_POST['reportloc'],$_POST['reporttype'],$_POST['reporttags'],$_POST['evidence'],$_POST['admin'],$_SESSION['user']));
		echo "Created new case in database";
	} catch(PDOException $e) {getError($e);}
}

try {
	$reply = query("SELECT EXISTS(SELECT 1 FROM quickreport WHERE uid=?)",array($_POST['uid']), false, false);
	if($reply[0]!="0") updateEntry($conn);
	else newEntry($conn);
} catch(PDOException $e) {getError($e);}

?>