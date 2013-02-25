<?php 	
	$EPGP_URL = 	"http://pastebin.com/raw.php?i=Ct0gatSH"; // Link to your pastebin (check ours for example)
	$REGION = 	"eu"; // EU or US or whatever
	$REALM = 	"Ravencrest"; // your guilds realm
	$GUILDNAME = 	"Prowess"; // your guildname
?>

<html>
<head>
<title><?php echo $GUILDNAME ?> EPGP Viewer (alpha)</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>
body {background-color:black;color:#F5F5F5;text-align:center;}
table {margin-left:auto;margin-right:auto;}
</style>
<?php
	// $types[Classes/Races][Type ID]
	$types = array(
		"Classes" => array(	1 => array("Warrior", "C79C6E", "icons/classes/Warrior.png"),
					2 => array("Paladin", "F58CBA", "icons/classes/Paladin.png"),
					3 => array("Hunter", "ABD473", "icons/classes/Hunter.png"),
					4 => array("Rogue", "FFF569", "icons/classes/Rogue.png"),
					5 => array("Priest", "FFFFFF", "icons/classes/Priest.png"),
					6 => array("Death Knight", "C41F3B", "icons/classes/Death Knight.png"),
					7 => array("Shaman", "0070DE", "icons/classes/Shaman.png"),
					8 => array("Mage", "69CCF0", "icons/classes/Mage.png"),
					9 => array("Warlock", "9482C9", "icons/classes/Warlock.png"),
					10 => array("Monk", "00FF96", "icons/classes/Monk.jpg"),
					11 => array("Druid", "FF7D0A", "icons/classes/Druid.png")),
		"Races" =>	array(	1 => array("Human"),
					2 => array("Orc"),
					3 => array("Dwarf"),
					4 => array("Nightelf"),
					5 => array("Undead"),
					6 => array("Tauren"),
					7 => array("Gnome"),
					8 => array("Troll"),
					9 => array("Goblin"),
					10 => array("Bloodelf"),
					11 => array("Draenei"),
					22 => array("Worgen"),
					25 => array("Pandaren"),
					26 => array("Pandaren"))
			 );
	
	// only reload epgp data if reload is called from the URL or if more than 1 day has passed since last update.
	$reload = false;
	if(!file_exists("epgp.cache") || filesize("epgp.cache") <= 0) {$reload = true;}
	if($_GET["reload"] || date('j') != date('j',filemtime("epgp.data"))) {
		$ch = curl_init($EPGP_URL);
		curl_setopt($ch, CURLOPT_TIMEOUT, 15);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$epgp_output = curl_exec($ch);
		curl_close($ch);
		$f = fopen("epgp.data", "w");
		fwrite($f,$epgp_output);
		fclose($f);
		echo "The list has been updated from Pastebin! :)";
		$reload = true;
	} else {
		$fileReader = file('epgp.data');
		$epgp_output = $fileReader[0];
	}

	$datapkg = json_decode($epgp_output);
	$roster = $datapkg->{'roster'};
	
	function arrayPrioSort($a, $b) {
		$pr_a = $a[1]/$a[2];
		$pr_b = $b[1]/$b[2];
		
		if ($pr_a > $pr_b) return -1; else if ($pr_a == $pr_b) return 0; else return 1;
	}
	
	usort($roster,"arrayPrioSort");
?>
</head>

<body>
	<h1><?php echo $GUILDNAME; ?>' EPGP Listings</h1>
	<table>
		<tr><th></th><th style="width:100px"></th><th style="width:70px" align="left">EP</th><th style="width:70px" align="left">GP</th><th style="width:70px" align="left">Priority</th></tr>
		<?php
			$cacheFile = "epgp.cache";
			$epgpCache = fopen("epgp.cache", "r");
			while(!feof($epgpCache)) {
				$line = fgets($epgpCache);
				echo $line;
			}
			fclose($epgpCache);
			
			if($reload) {
				// clearing file, prepping it for loadout
				$cachef = fopen("epgp.cache", "w");
				fwrite($cachef, "");
				fclose($cachef);
			for($i = 0; $i < count($roster); $i++) {
				$name 	= $roster[$i][0];
				$ep		= $roster[$i][1];
				$gp		= $roster[$i][2];
				$pr		= $ep/$gp;
				
				$ch = curl_init("http://" . $REGION . ".battle.net/api/wow/character/" . $REALM . "/" . $roster[$i][0]);
				curl_setopt($ch, CURLOPT_TIMEOUT, 3);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$cpkg = curl_exec($ch);
				curl_close($ch);
				$characterinfo = json_decode($cpkg);
				
				$class 	= $characterinfo->{'class'};
				$race	= $characterinfo->{'race'};
				$gender = $characterinfo->{'gender'}; // 0 = male , 1 = female
				if($gender==0) $gender="male"; else $gender="female";
				
				file_put_contents($cacheFile, '<tr><td><img src="icons/races/'.$gender.'/'.$types["Races"][$race][0].'.gif" title="'.$types["Races"][$race][0].'" />', FILE_APPEND);
				file_put_contents($cacheFile, ' <img src="' . $types["Classes"][$class][2] . '" height="18" width="18" title="'.$types["Classes"][$class][0].'" /></td>', FILE_APPEND);
			 	file_put_contents($cacheFile, '<td style="color:#' . $types["Classes"][$class][1] . '"><b>' . $name . '</b></td>', FILE_APPEND);
				file_put_contents($cacheFile, "<td>" . $ep . "</td>", FILE_APPEND);
				file_put_contents($cacheFile, "<td>" . $gp . "</td>", FILE_APPEND);
				file_put_contents($cacheFile, "<td>" . round($pr,2) . '</td><td><a href="http://' . $REGION . '.battle.net/wow/en/character/' . $REALM . '/' .$name.'/advanced" target="_blank" style="display:block;"><img src="http://fc09.deviantart.net/fs70/f/2011/045/6/0/wow_high_rez_icon_by_jocpoc-d39jgl5.png" height="18" width="18" title="Armory Link" /></a></td></tr>', FILE_APPEND);
			}
			}
		?>
	</table>
</body>
</html>
