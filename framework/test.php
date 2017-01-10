<?php

$len = 32;

if(isset($_GET['len'])) $len = intval($_GET['len']);

$hex = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
$uid = '';

while(strlen($uid)<$len) {$uid .= substr($hex,rand(0,strlen($hex)-1),1);}

echo $uid;

?>