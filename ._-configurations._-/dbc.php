<?php

$config = array(
	'host'		=> 'localhost',
	'username' 	=> 'nope',
	'password' 	=> 'nope',
	'dbname' 	=> 'nope'
);

try {
	$odb = new PDO('mysql:host=' . $config['host'] . ';dbname=' . $config['dbname'], $config['username'], $config['password']);
	$odb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
	echo '<center><b>MySQL Connection Error.</b></center>';
	exit;
}
?>