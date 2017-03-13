<?php

include('._-configurations._-/loader.php');

$token = $_GET['token'];

$query1 = $odb->prepare("SELECT * FROM `users` WHERE `status` = ?");
$query1->BindValue(1, $token);
$query1->execute();

if($query1->rowCount() == 1){
	$query2 = $odb->prepare("UPDATE `users` SET `status` = ? WHERE `status` = ?");
	$query2->BindValue(1, "ACTIVE");
	$query2->BindValue(2, $token);
	$query2->execute();
}

Header("Location: {$siteURL}login");
die();

?>