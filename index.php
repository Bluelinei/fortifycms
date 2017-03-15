<!--HOMEPAGE REDIRECT-->

<?php

if(!isset($_SESSION)) session_start();

if(!isset($_SESSION['user']))
{
  header('Location: /fortify/login.php');
  die();
}
else
{
  header('Location: /fortify/casebuilder.php');
  die();
}

?>