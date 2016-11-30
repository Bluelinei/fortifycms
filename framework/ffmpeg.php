<?php

$FF_DIR = ".\ffmpeg\bin\ffmpeg.exe";

if(!isset($_SESSION)) session_start();

function setDir($trdir) {if(!is_dir($trdir)) mkdir($trdir, 0777, true);}

function redactVideo($source, $start, $end, $output)
{

}

function captureFrame($source, $time, $output)
{
	$outpath = "uploads/".$_SESSION['agency']."/".$_SESSION['user']."/".$output;
	if(file_exists($outpath)) {echo $outpath; return;}
	setDir("uploads/".$_SESSION['agency']."/".$_SESSION['user']."/".$_POST['dir']);
	exec(".\\ffmpeg\\bin\\ffmpeg.exe -y -ss $time -i $source -frames:v 1 $outpath", $out);
	if(file_exists($outpath)) echo $outpath;
	else return;
}

if(isset($_POST['function']))
{
	switch($_POST['function'])
	{
		case 'redact':
		{
			redactVideo($_POST['source'], $_POST['start'], $_POST['end'], $_POST['output']);
			break;
		}
		case 'capture':
		{
			captureFrame($_POST['source'], $_POST['time'], $_POST['output']);
			break;
		}
		default:
		{
			$func = $_POST['function'];
			echo "FFMPEG: Undefined function '$func'";
			break;
		}
	}
}

?>