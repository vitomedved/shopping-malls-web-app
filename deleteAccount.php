<?php

include 'connectionToDB.php';

session_start();

$link = connectToDB();

if(!$link)
{
	echo("Ne mogu se spojiti s bazom podataka.");
}
else
{
	$query = "DELETE FROM `korisnik` WHERE `korisnik`.`id_korisnik` = ".$_SESSION['userId']."";
	$result = mysqli_query($link, $query);
	if(!$result)
	{
		echo("Error with query");
	}
	else
	{
		echo("Account deleted, gg wp");
		session_unset();
		session_destroy();
	}
	mysqli_close($link);
}

header("Location: /RWA_ducani/index.php");

?>