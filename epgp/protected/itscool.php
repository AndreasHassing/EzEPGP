<?php
function checkSec()
{
	include("../connect.php");
	
	//preparing cookie detection
	$ip=$_SERVER['REMOTE_ADDR'];
	$cookie_name = str_replace(".","_",$ip);
	
	// for debugging the cookie
	/*echo $cookie_name . "<br />";
	echo $_COOKIE["$cookie_name"] . "<br />"
	print_r($_COOKIE);*/
	
	//detecting cookie
	if (isset($_COOKIE["$cookie_name"]))
		{
			// cookie is set, but does it match?
			$cukiez = $_COOKIE["$cookie_name"];
			if(!mysql_select_db("$database",$con))
				{
					die('Error (selecting db): ' . mysql_error());
				}
			$query = "SELECT keylol FROM ezep_key WHERE id>0";
			$result = mysql_query($query,$con);
			if (mysql_result($result,0) == $cukiez)
			{	
				//you have a key! SUP!? whaelcum home.
				return 1;
			
			// invalid key? k, go die.
			} else {
				return 0;
			}
		// no key at all? k, baibai
		} else {
			return 0;
		}
}
?>