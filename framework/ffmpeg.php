<?php

$FF_DIR = "./ffmpeg/bin/ffmpeg.exe";

function redactVideo($source, $start, $end, $output)
{

}

function captureFrame($source, $time, $output)
{
	shell_exec("$FF_DIR -y -ss $time -frames:v 1 $output");
}

if(isset($_POST['function']))
{
	echo "BULLSHIT!\n";
	switch($_POST['function'])
	{
		case 'redact':
		{
			redactVideo($_POST['source'], $_POST['start'], $_POST['end'], $_POST['output']);
		}
		case 'capture':
		{
			captureFrame($_POST['source'], $_POST['time'], $_POST['output']);
		}
		default:
		{
			$func = $_POST['function'];
			echo "Undefined function \'$func\'";
		}
	}
}

?>