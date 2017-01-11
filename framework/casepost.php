<?php

require_once 'dbconnect.php';

function updateEntry()
{
	try {
		update(	"UPDATE cases SET nickname=?, ref=?, type=?, tags=?, evidence=?, admin=?, users=?, data=? WHERE uid=?",
				array($_POST['nickname'],$_POST['reportnum'],$_POST['reporttype'],$_POST['reporttags'],$_POST['evidence'],$_POST['admin'],$_SESSION['user'],$_POST['data'],$_POST['uid']));
		echo "Updated case in database";
	} catch(PDOException $e) {getError($e);}
}

function newEntry()
{
	try {
		update(	"INSERT INTO cases (uid, nickname, ref, type, tags, evidence, admin, users, data) VALUES (?,?,?,?,?,?,?,?,?)",
				array($_POST['uid'],$_POST['nickname'],$_POST['reportnum'],$_POST['reporttype'],$_POST['reporttags'],$_POST['evidence'],$_POST['admin'],$_SESSION['user'],$_POST['data']));
		echo "Created new case in database";
	} catch(PDOException $e) {getError($e);}
}

try {
	$reply = query("SELECT * FROM cases WHERE uid=?",array($_POST['uid']), false, false);
	if($reply) updateEntry();
	else newEntry();
} catch(PDOException $e) {getError($e);}

?>