<?php

require_once 'dbconnect.php';

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