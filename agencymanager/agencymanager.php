<?php

require_once '../framework/dbconnect.php';

function getAgencies()
{
	global $DATABASE;
	$DATABASE = 'agentlookup';
	$agencies = query("SELECT * FROM agencies", null, true, true);
	if($agencies)
	{
		$users = [];
		for($i=0; $i<count($agencies); $i++)
		{
			$DATABASE = $agencies[$i]['dbname'];
			$users[$DATABASE] = query("SELECT username, name, agency, phone, authenticate FROM users", null, true, true);
		}
		$data = [];
		$data['STATUS_HEADER'] = 0;
		$data['agencies'] = $agencies;
		$data['users'] = $users;
		echo json_encode($data);
		return;
	}
	else
	{
		$data = [];
		$data['STATUS_HEADER'] = 1;
		echo json_encode($data);
		return;
	}
}

switch($_POST['function'])
{
	case 'agencies':
	{
		getAgencies();
		break;
	}
}

?>