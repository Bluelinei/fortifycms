<?php

$UPLOAD = true;

function setDir($trdir) {if(!is_dir($trdir)) mkdir($trdir, 0777, true);}

$ds = DIRECTORY_SEPARATOR;
if(!isset($_FILES['file']))
{
	return;
}
$file = $_FILES['file'];
error_reporting(E_ALL);

$fn = $_POST['uid'].".".$_POST['ext'];
$targetDir = ".".$ds."uploads";
setDir($targetDir);
$finalPath = $targetDir.$ds.$fn;
if($UPLOAD) move_uploaded_file($file['tmp_name'], $finalPath);
echo "Uploads.php: ".$fn;

?>