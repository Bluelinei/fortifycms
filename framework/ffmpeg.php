<?php

$FF_DIR = ".\ffmpeg\bin\ffmpeg.exe";

function setDir($trdir) {if(!is_dir($trdir)) mkdir($trdir, 0777, true);}

function redactVideo($source, $start, $end, $output)
{

}

function captureFrame($source, $time, $output)
{
	exec(".\\ffmpeg\\bin\\ffmpeg.exe -y -ss 00:00:00 -i $source -frames:v 1 $output", $out);
	echo "Thumbnail generated in $output";
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
			echo "Undefined function '$func'";
			break;
		}
	}
}

?>