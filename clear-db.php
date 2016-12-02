<?php

$conn = new PDO("mysql:host=localhost; port=3306; dbname=fortify; charset=UTF8;", "root", "");
if($conn)
{
	$stmt = $conn->prepare("DELETE FROM evidence; DELETE FROM quickreport");
	$stmt->execute();
}

header("Location: index.php");

?>