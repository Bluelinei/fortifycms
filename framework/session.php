<?php

if(!isset($_SESSION))
{
	session_start();
	session_write_close();
}

?>