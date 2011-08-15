<?php
require("../config.php");
$its_cool = 0;
require("../connect.php");
if ($safety == TRUE)
{
	include("../clearkey.php");
	include("itscool.php");
	$its_cool = checkSec();
} else if ($safety == FALSE)
	{ $its_cool = 1; }

if($its_cool == 1) {
	mysql_select_db("$database", $con);

	$ip=$_SERVER['REMOTE_ADDR'];
	$json_data = mysql_real_escape_string($_POST['json_data']);

	$json2sql = "	INSERT INTO ezep_json (json_data, submit_ip)
					VALUES
					('$json_data', '$ip')
				";
				
	if (!mysql_query($json2sql,$con))
		{
			die('<br />Error: ' . mysql_error());
		} echo "Your EPGP Data has been successfully soaked by your database. Thumbs up!";

mysql_close($con);

} else {
	echo "At the wrong place, at the wrong time, eh? Nice try.";
}
?>