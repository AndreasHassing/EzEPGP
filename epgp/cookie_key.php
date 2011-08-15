<?php
// You dared enter my landscape of security? Fine, here's some help.

//Your ip address and the key
$my_ip = $_SERVER['REMOTE_ADDR'];
$key = hash('whirlpool',$my_ip);

/* 	The seed is a randomly generated number (random.org), that is the base
	of calculations in the fLPC. You can change this to whatever you like.
	Numers only, of course. 												*/
$seed = "43694641012625397345304926129679662";

// SWITCH OF RANDOMNESS! IS IT ON OR OFF PIRATES!? You can change this to either TRUE or FALSE
$switch = TRUE;

if ($switch == TRUE)
	{$sey = $key . $seed;}
else
	{$sey = $seed . $key;}

//fucking large pirate cannon!
$c = hash('sha512',$sey);

?>