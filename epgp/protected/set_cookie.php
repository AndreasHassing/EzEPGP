<?php
require("../connect.php");
require("../cookie_key.php");
require("../config.php");
require("whitelist.php");
include("../clearkey.php");

mysql_select_db("$database", $con);
$ip=$_SERVER['REMOTE_ADDR'];

//checking if YOU exist in the whitelist!
if (in_array($ip,$white))
{
	//Delete old key
	mysql_query("DELETE FROM ezep_key WHERE ID>0");
	setcookie($ip, $c, time()-3600);

	$insert_key="	INSERT INTO ezep_key (keylol, ip)
					VALUES ('$c', '$ip')
				";

	//Insert new key
	if (!mysql_query($insert_key,$con))
		{
		die('Error: ' . mysql_error());

	} else {
		setcookie($ip, $c,time()+$clear_at);
		setcookie("is_admin", "yarrhhhh", time()+$clear_at, "/");
		echo "You successfully synchronized yourself with the server! You can now push EPGP data.";
		}

} else {

	echo "I'm sorry, we're using a whitelist to avoid pirates and ninjas. Please talk to the webmaster of this website if you want to push EPGP data!";

}

mysql_close($con);
?>