<?php

require_once 'dbconnect.php';

if(isset($_POST['get'])&&isset($_POST['uid']))
{
	switch($_POST['get'])
	{
		case 'case':
		{
			$case = query("SELECT * FROM cases WHERE (users LIKE ?) AND uid=?", array($_SESSION['user'], $_POST['uid']));
			$var = '\'%'.$_POST['uid'].'%\'';
			$evidence = query("SELECT * FROM evidence WHERE (users LIKE ?) AND (caseindex LIKE $var)",array($_SESSION['user']), true);
			$return = [];
			$return['case'] = $case;
			$return['evidence'] = $evidence;
			echo json_encode($return);
			return;
		}
		case 'evidence':
		{
			$ev = query("SELECT * FROM evidence WHERE (users LIKE ?) AND uid=?", array($_SESSION['user'], $_POST['uid']));
			echo json_encode($ev);
			return;
		}
	}
}

$terms = explode(" ",$_POST['query']);
$cases = [];

for($i=0; $i<sizeof($terms); $i++)
{
	$var = '\'%'.$terms[$i].'%\'';
	$search = query("SELECT * FROM cases WHERE
					(nickname LIKE $var OR
					ref LIKE $var OR
					type LIKE $var OR
					tags LIKE $var OR
					data LIKE $var) AND
					users LIKE ?",array($_SESSION['user']), true);
	$cases = array_merge($cases, $search);
	$cases = array_unique($cases, SORT_REGULAR);
}

if($_POST['searchtype']=='auto'&&sizeof($cases)>20) return;

if($cases) echo json_encode($cases);
return;

?>