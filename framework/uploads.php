<?php

include 'dbconnect.php';

function setDir($trdir) {if(!is_dir($trdir)) mkdir($trdir, 0777, true);}

$ds = DIRECTORY_SEPARATOR;
if(!isset($_FILES['file']))
{
	return;
}
$file = $_FILES['file'];

echo error_get_last();

try {
	$sql = "SELECT * FROM evidence WHERE checksum=?";
	$stmt = $conn->prepare($sql);
	$stmt->execute(array(sha1_file($file['tmp_name'])));
	$reply = $stmt->fetch(PDO::FETCH_ASSOC);
	if($reply)
	{
		echo $reply['filepath'];
		return;
	}
} catch(PDOException $e) {
	return;
}


$fn = $_POST['uid'].".".$_POST['ext'];
$targetDir = ".".$ds."uploads";
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

if(!file_exists($finalPath)) return;
echo $fn;

?>