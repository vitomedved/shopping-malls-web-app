<?php

include_once 'connectionToDB.php';
include_once 'userFunctions.php';

if (session_status() == PHP_SESSION_NONE)
{
    session_start();
}

//ako nije postavljen id ducana, preusmjeri na index
if(isset($_GET['id']) == false)
{
	header("Location: /RWA_ducani/index.php");
}
//ako je postavljen index ducana, provjeri ako taj id postoji u bazi
else
{
	$ducanId = $_GET['id'];
	$exist = doesUserExist($_GET['id']);
	if(!$exist)
	{
		header("Location: /RWA_ducani/index.php");
	}
}

$user = getUserObject($_GET['id']);

echo "Ime: ".$user->ime.", prezime: ".$user->prezime.", naj ducan: ".$user->najDucan.", <a href='mailto:".$user->email."'>pošalji e-mail (".$user->email.")</a>";
echo "<br><a href='index.php'>Povratak na index</a>";

?>