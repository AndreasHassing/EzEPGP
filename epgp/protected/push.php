<?php
require("../config.php");
$its_cool = 0;
if ($safety == TRUE)
	{
		require("../connect.php");
		include("../clearkey.php");
		include("itscool.php");
		$its_cool = checkSec();
	} else if ($safety == FALSE) {
		$its_cool = 1;
	}
	
	
if ($its_cool == 1)
	{
		$form_data = '<html>
<head>
<script type="text/javascript">
function validateData()
{
	var x=document.forms["jsonForm"]["json_data"].value
	var firstpos=x.indexOf("{");
	var lastpos=x.lastIndexOf("}");
	if (firstpos!=0 || lastpos!=x.length-1)
	{
	alert("Hmm, that Json data seems wrong.\n\nI cant chew it!");
	return false;
	}
}
</script>
</head>
<body>

<form name="jsonForm" action="json2sql.php" onsubmit="return validateData();" method="post">
Json Data:<br />
<textarea rows="15" cols="40" name="json_data"></textarea>
<br /><input type="submit" />
</form>

</body>
</html>';
	echo $form_data;
	} else {
		echo "Sup dawg? I'm not sure you have access to these holy grounds.. Shoosh!";
	}


?>
