<?php

require_once 'session.php';

$HOSTNAME=	'CHAIR-FORCE-ONE\SQLEXPRESS';
$PORT=		'3306';
$USERNAME=	'W5eMCKJbykm4fHfQdP4vi7XHFoDi7Wgx'; //W5eMCKJbykm4fHfQdP4vi7XHFoDi7Wgx
$PASSWORD=	'ai2CZKqBNqF8sFniNybCl2GILqrDzQ1g'; //ai2CZKqBNqF8sFniNybCl2GILqrDzQ1g
$DATABASE;

if(isset($_SESSION['agency'])) $DATABASE = $_SESSION['agency'];

error_reporting(E_ALL);

//Definitions
define("tMINUTE",60);
define("tHOUR",3600);
define("tDAY",86400);
define("tMONTH",2592000);
define("tYEAR",31536000);

function getError($e) {die($e->getMessage());}

/*try{
	$conn = new PDO("sqlsrv:Server=$hostname; Database=$database;", $username, $pass);
} catch(PDOException $e) {getError($e);}*/

function query($sql, $exarray=null, $fetchall=false, $fetchassoc=true)
{
	global $USERNAME;
	global $PASSWORD;
	global $HOSTNAME;
	global $DATABASE;
	try {
		$conn = new PDO("sqlsrv:Server=$HOSTNAME; Database=$DATABASE;", $USERNAME, $PASSWORD);
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
	global $USERNAME;
	global $PASSWORD;
	global $HOSTNAME;
	global $DATABASE;
	try {
		$conn = new PDO("sqlsrv:Server=$HOSTNAME; Database=$DATABASE;", $USERNAME, $PASSWORD);
		$stmt = $conn->prepare($sql);
		$stmt->execute($exarray);
		return;
	} catch(PDOException $e) {getError($e);}
}

function getEvidenceByUID($uid) {return query("SELECT * FROM evidence WHERE uid=?",array($uid));}

?>
