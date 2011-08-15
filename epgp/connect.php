<?php

$host = "localhost";
$user = "root";
$password = "pass";
$database = "ezepgp";

$con = mysql_connect($host,$user,$password);

if (!$con)
	{
		die('Could not connect: ' . mysql_error());
	}

?>