<?php

include_once 'userClass.php';
include_once 'connectionToDB.php';


session_start();

$link = connectToDB();

if(!$link)
{
	echo("Ne mogu se spojiti s bazom podataka.");
}
else
{
	$query = "DELETE FROM korisnik WHERE id_korisnik=".$_SESSION['user']->id;
	$result = mysqli_query($link, $query);
	if(!$result)
	{
		echo("Error with query");
	}
	else
	{
		mysqli_close($link);
		echo("Account deleted, gg wp");
		header("Location: /RWA_ducani/logout.php");
	}
	mysqli_close($link);
}


?>