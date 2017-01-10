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
			if(dbExists($_POST['agency']))
			{
				$response = [];
				$response['STATUS_HEADER'] = 1;
				$response['STATUS_MESSAGE'] = 'Agency already exists';
				echo json_encode($response);
				return;
			}
			try {
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