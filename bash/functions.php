<?php

require '..\framework\dbconnect.php';

function dbExists($dbname)
{
	$conn = new PDO("sqlsrv:Server=CHAIR-FORCE-ONE\SQLEXPRESS;", 'W5eMCKJbykm4fHfQdP4vi7XHFoDi7Wgx', 'ai2CZKqBNqF8sFniNybCl2GILqrDzQ1g');
	$stmt = $conn->prepare("SELECT * FROM master.dbo.sysdatabases WHERE name=?");
	$stmt->execute(array($dbname));
	if($stmt->fetch()) return 1;
	else return 0;
}

$statecode = array("AL"=>100,"AK"=>200,"AZ"=>300,"AR"=>400,"CA"=>500,"CO"=>600,"CT"=>700,"DE"=>800,"DC"=>900,"FL"=>1000,"GA"=>1100,"HI"=>1200,"ID"=>1300,"IL"=>1400,"IN"=>1500,"IA"=>1600,"KS"=>1700,"KY"=>1800,"LA"=>1900,"ME"=>2000,"MD"=>2100,"MA"=>2200,"MI"=>2300,"MN"=>2400,"MS"=>2500,"MO"=>2600,"MT"=>2700,"NE"=>2800,"NV"=>2900,"NH"=>3000,"NJ"=>3100,"NM"=>3200,"NY"=>3300,"NC"=>3400,"ND"=>3500,"OH"=>3600,"OK"=>3700,"OR"=>3800,"PA"=>3900,"RI"=>4000,"SC"=>4100,"SD"=>4200,"TN"=>4300,"TX"=>4400,"UT",=>4500"VT"=>4600,"VA"=>4700,"WA"=>4800,"WV"=>4900,"WI"=>5000,"WY"=>5100);

if(isset($_POST['function']))
{
	switch($_POST['function'])
	{
		case 'getuser':
		{
			$reply = query($_POST['agency'], "SELECT * FROM users WHERE username=?", array($_POST['user']));
			if($reply)
			{
				$reply['STATUS_HEADER'] = 0;
			}
			else
			{
				$reply['STATUS_HEADER'] = 1;
				$reply['STATUS_MESSAGE'] = "User does not exist.";
			}
			echo json_encode($reply);
			return;
		}
		case 'newagency':
		{
			$id = $statecode[strtoupper($_POST['stateabr'])]+1000;
			if(dbExists($_POST['agency']))
			{
				$response = [];
				$response['STATUS_HEADER'] = 1;
				$response['STATUS_MESSAGE'] = 'Agency already exists';
				echo json_encode($response);
				return;
			}
			try {
				//$DATABASE = 'agencylookup';
				//update("INSERT INTO agencies (id, dbname, name) VALUES (?,?,?)",array($id, ));
				$database = $_POST['agency'];
				$conn = new PDO("sqlsrv:Server=CHAIR-FORCE-ONE\SQLEXPRESS;", 'W5eMCKJbykm4fHfQdP4vi7XHFoDi7Wgx', 'ai2CZKqBNqF8sFniNybCl2GILqrDzQ1g');
				$stmt = $conn->prepare("CREATE DATABASE $database;");
				$stmt->execute();
				$stmt = $conn->prepare("USE $database; CREATE TABLE users (username VARCHAR(32) NOT NULL PRIMARY KEY,password VARCHAR(128),passwordsalt VARCHAR(128),name VARCHAR(64),agency VARCHAR(64),phone VARCHAR(16),authenticate TINYINT,sessid VARCHAR(128),sessexpire INT,authcode VARCHAR(8),authexpire INT); CREATE TABLE evidence(uid VARCHAR(16) NOT NULL PRIMARY KEY,checksum VARCHAR(128),type VARCHAR(64),nickname VARCHAR(64),caseindex TEXT,data TEXT); CREATE TABLE cases(uid VARCHAR(16) NOT NULL PRIMARY KEY,nickname VARCHAR(64),ref VARCHAR(64),type VARCHAR(64),admin TINYINT,users TEXT,tags TEXT,evidence TEXT);");
				$stmt->execute();
				if(dbExists($database))
				{
					$response = [];
					$response['STATUS_HEADER'] = 0;
					$response['STATUS_MESSAGE'] = 'Agency created successfully';
					echo json_encode($response);
					return;
				}
				else
				{
					$response = [];
					$response['STATUS_HEADER'] = 2;
					$response['STATUS_MESSAGE'] = 'Error creating agency: '.$conn->errorInfo();
					echo json_encode($response);
					return;
				}
			} catch(PDOException $e) {
				$response = [];
				$response['STATUS_HEADER'] = 3;
				$response['STATUS_MESSAGE'] = $e->getMessage();
				echo json_encode($response);
				return;
			}
		}
		case 'newuser':
		{
			if(!dbExists($_POST['agency']))
			{
				$response = [];
				$agency = $_POST['agency'];
				$response['STATUS_HEADER'] = 1;
				$response['STATUS_MESSAGE'] = "Agency $agency does not exist";
				echo json_encode($response);
				return;
			}
			if(query("SELECT * FROM users WHERE username=?", array($_POST['username'])))
			{
				$response = ['STATUS_HEADER','STATUS_MESSAGE'];
				$user = $_POST['username'];
				$response['STATUS_HEADER'] = 2;
				$response['STATUS_MESSAGE'] = "User $user already exists";
				echo json_encode($response);
				return;
			}
			$salt = hash('sha512',time());
			$password = hash('sha512', $salt.$_POST['password']);
			$username = $_POST['username'];
			$agency = $_POST['agency'];

			$reply = query("INSERT INTO users (username,password,passwordsalt,agency) VALUES (?,?,?,?)",array($username, $password, $salt, $agency));

			if(query("SELECT * FROM users WHERE username=?", array($_POST['username'])))
			{
				$response = [];
				$user = $_POST['username'];
				$response['STATUS_HEADER'] = 0;
				$response['STATUS_MESSAGE'] = "User $user successfully created";
				echo json_encode($response);
				return;
			}
			else
			{
				$response = [];
				$user = $_POST['username'];
				$response['STATUS_HEADER'] = 3;
				$response['STATUS_MESSAGE'] = "Error in creating user $user";
				echo json_encode($response);
				return;
			}
		}
	}
}

?>