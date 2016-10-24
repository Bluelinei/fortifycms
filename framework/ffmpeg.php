<?php

$FF_DIR = "./ffmpeg/bin/ffmpeg.exe";

function redactVideo($source, $start, $end)
{

}

function captureScreen($source, $time)
{

}

if(isset($_POST['function']))
{
	echo "BULLSHIT!\n";
	switch($_POST['function'])
	{
		case 'redact':
			redactVideo($_POST['source'], $_POST['start'], $_POST['end']);
		case 'capture':
			captureScreen($_POST['source'], $_POST['time']);
		default:
			echo "[!!!] $_POST['function'] is not a recognized function.";
	}
}

?>