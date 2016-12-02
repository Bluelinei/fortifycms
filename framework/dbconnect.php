<?php

$hostname=	'localhost';
$port=		'3306';
$username=	'user';
$pass=		'';
$database=	'fortify';
$conn;

error_reporting(E_ALL);

include 'session.php';

function getError($e) {die($e->getMessage());}

try{
	$conn = new PDO("mysql:host=$hostname; port=$port; dbname=$database; charset=UTF8;", $username, $pass);
} catch(PDOException $e) {getError($e);}

function query($sql, $exarray=null, $fetchall=false, $fetchassoc=true)
{
	global $conn;
	try {
		$stmt = $conn->prepare($sql);
		$stmt->execute($exarray);
		if(!$fetchall)
		{
			if($fetchassoc) return $stmt->fetch(PDO::FETCH_ASSOC);
			else return $stmt->fetch();
		}
		else return $stmt->fetchAll(PDO::FETCH_ASSOC);
	} catch(PDOException $e) {getError($e);}
}

function update($sql, $exarray=null)
{
	global $conn;
	try {
		$stmt = $conn->prepare($sql);
		$stmt->execute($exarray);
		return;
	} catch(PDOException $e) {getError($e);}
}

function getEvidenceByUID($uid) {return query("SELECT * FROM evidence WHERE uid=?",array($uid));}

?>