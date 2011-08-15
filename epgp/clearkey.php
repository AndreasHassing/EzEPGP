<?php
//Do not access this file in your browser. It's only used to clear the key in
//the database, when it's lifespan has been reached.

error_reporting(0); //let's not give dem' hackers anything to work with.
mysql_select_db("$database", $con);

//selecting the key from the database
$query = mysql_query("SELECT date FROM ezep_key WHERE id > 0",$con);

//converting, how long ago the key was made, to seconds
$time = time() - strtotime(mysql_result($query,0));

//Deleting key if it's too old!
if ($time > $clear_at)
{
	mysql_query("DELETE FROM ezep_key WHERE ID>0");
}
?>