<?php

include 'dbconnect.php';

function setDir($trdir) {if(!is_dir($trdir)) mkdir($trdir, 0777, true);}

$ds = '/';
if(!isset($_FILES['file']))
{
	return;
}
$file = $_FILES['file'];

echo error_get_last();

function generateObject()
{
	global $file;
	global $ds;
	//Get UID
	$hex = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
	$uid;
	$status = true;
	while($status)
	{
		$status=false;
		$uid = "";
		while(strlen($uid)<16) {$uid .= substr($hex,rand(0,strlen($hex)-1),1);}

		try {
			$reply = query("SELECT TOP 1 FROM evidence WHERE uid=?", array($uid));
			if($reply) $status=true;
		} catch(PDOException $e) {getError($e);}
	}
	//Save/Format File
	$fn;
	$targetDir = "uploads".$ds.$_SESSION['agency'].$ds.$_SESSION['user'];
	switch($_POST['filetype'])
	{
		case "VIDEO": {$targetDir .= $ds."video"; break;}
		case "AUDIO": {$targetDir .= $ds."audio"; break;}
		case "TEXT": {$targetDir .= $ds."text"; break;}
		case "IMAGE": {$targetDir .= $ds."images"; break;}
		default: {$targetDir .= $ds."docs"; break;}
	}
	$targetDir .= $ds.date("Y").$ds.date("M");
	setDir($targetDir);
	$filename;
	$finalPath;
	$checksum = "";
	if($_POST['filetype']=="VIDEO"&&pathinfo($file['name'], PATHINFO_EXTENSION)!="MP4")
	{
		$filename = $uid.".MP4";
		$finalPath = $targetDir.$ds.$filename;
		$checksum = sha1_file($file['tmp_name']);
		$bitrate = shell_exec('.\\ffmpeg\\bin\\ffprobe -i '. $file['tmp_name'] .' -show_entries format=bit_rate -v quiet -of csv="p=0"');
		$br = ($bitrate>4096000?4096:floor($bitrate/1000));
		exec(".\\ffmpeg\\bin\\ffmpeg.exe -y -i ". $file['tmp_name'] ." -b:v ".$br."k -vcodec libx264 -acodec aac ". $finalPath, $out);
	}
	else
	{
		$filename = $uid.".".pathinfo($file['name'], PATHINFO_EXTENSION);
		$finalPath = $targetDir.$ds.$filename;
		move_uploaded_file($file['tmp_name'], $finalPath);
	}
	//Set Database
	if(!file_exists($finalPath))
	{
		echo "File could not be uploaded!";
		return;
	}
	query(	"INSERT INTO evidence (uid, filepath, fortified, caseindex, uploaddate, lastmodified, nickname, type, user, checksum) VALUES (?,?,?,?,?,?,?,?,?,?)",
			array($uid, $finalPath, "0", "", time(), $_POST['lastModified'], "", $_POST['filetype'], $_SESSION['user'], ($checksum?$checksum:sha1_file($finalPath)))	);
	//Return json object
	echo json_encode(getEvidenceByUID($uid));
	return;
}

//********** CHECK IF FILE ALREADY EXISTS ON SERVER **********

try {
	$response = query("SELECT * FROM evidence WHERE checksum=? AND lastmodified=?", array(sha1_file($file['tmp_name']), $_POST['lastModified']), true);
	if($response)
	{
		foreach($response as $reply) //Iterate through all replies, in case multiple files have the same checksum for whatever reason.
		{
			system(".\\dfc\\dfc.exe ".$file['tmp_name']." ".$reply['filepath'], $out);
			if($out==0) continue;
			else if($out<0) //Executable Error
			{
				$output = [];
				$output[0] = "Executable Error";
				$output[1] = $out;
				echo json_encode($output);
				return;
			}
			else if($out>0) //It does exist on the server
			{
				echo json_encode($reply);
				return;
			}
		}
		generateObject(); //It doesn't exist on the server (false positive)
	}
	else generateObject(); //It doesn't exist on the server
} catch(PDOException $e) {
	$output = [];
	$output[0] = $e->getMessage();
	$output[1] = -3;
	echo json_encode($output);
	return;
}

//********** IF IT DOES, RETRIEVE DATA OF FILE AND SEND AS JSON OBJECT **********
// See function generateObject()

//********** IF NOT, GET UID, CREATE NEW ENTRY AND RETURN BACK THE JSON OF THE NEW OBJECT AFTER POSTING IT TO THE DATABASE **********
/*
$fn = $_POST['uid'].".".$_POST['ext'];

setDir($targetDir);
$finalPath = $targetDir.$ds.$fn;

if($_POST['isvid']&&$_POST['ext']!='mp4')
{
	$fn = $_POST['uid'].".mp4";
	$finalPath = $targetDir.$ds.$fn;
	$bitrate = shell_exec('.\\ffmpeg\\bin\\ffprobe -i '. $file['tmp_name'] .' -show_entries format=bit_rate -v quiet -of csv="p=0"');
	$br = ($bitrate>4096000?4096:floor($bitrate/1000));
	exec(".\\ffmpeg\\bin\\ffmpeg.exe -y -i ". $file['tmp_name'] ." -b:v ".$br."k -vcodec libx264 -acodec aac ". $finalPath, $out);
} else {move_uploaded_file($file['tmp_name'], $finalPath);}

$output = [];
$output[0] = $finalPath;

if(!file_exists($finalPath))
{
	$output[1] = 2;
	echo json_encode($output);
	return;
}
$output[1] = 0;
echo json_encode($output);
return;
*/
?>