<?php

require '..\framework\dbconnect.php';

if(isset($_POST['function']))
{
	switch($_POST['function'])
	{
		case 'getuser':
		{
			$reply = query("SELECT * FROM users WHERE username=?", array($_POST['user']));
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
	}
}

?>