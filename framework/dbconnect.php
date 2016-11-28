<?php

$hostname=	'localhost';
$port=		'3306';
$username=	'user';
$pass=		'VGUZzusFJwf2sZFF';
$database=	'fortify';
$conn;

error_reporting(E_ALL);

if(!isset($_SESSION)) session_start();

function getError($e) {die($e->getMessage());}

try{
	$conn = new PDO("mysql:host=$hostname; port=$port; dbname=$database; charset=UTF8;", $username, $pass);
} catch(PDOException $e) {getError($e);}

function query($sql, $exarray, $fetchall=false)
{
	global $conn;
	$stmt = $conn->prepare($sql);
	$stmt->execute($exarray);
	if(!$fetchall) return $stmt->fetch(PDO::FETCH_ASSOC);
	else return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getEvidenceByUID($uid) {return query("SELECT * FROM evidence WHERE uid=?",array($uid));}

?>