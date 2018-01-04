<?php

include 'userClass.php';

if (session_status() == PHP_SESSION_NONE)
{
    session_start();
}

include 'connectionToDB.php';
//include 'userFunctions.php';
include 'ducanFunctions.php';

$ducaniArray = getDucaniArray();

//isprintaj sve ducane
foreach($ducaniArray as $ducan)
{
	echo "ime: ".$ducan->ime.", tip: ".$ducan->tip.", vrsta: ".$ducan->vrsta.", ocjena: ".$ducan->ocjena."<br>
	<a href=''>Pogledaj dućan</a> | <a href='ducan.php?id=".$ducan->id."'>Pogledaj komentare</a>";
	
	if($_SESSION['user']->razinaOvlasti == 1)
	{
		echo " | <a href=''>Uredi ducan</a>";
	}
	
	echo "<br><hr>";
}
echo "<a href='index.php'>Povratak na pocetnu</a>";

?>