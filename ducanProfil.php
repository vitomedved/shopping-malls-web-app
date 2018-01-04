<?php

include_once 'ducanFunctions.php';
include_once 'connectionToDB.php';

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
	$exist = doesDucanExist($_GET['id']);
	if(!$exist)
	{
		header("Location: /RWA_ducani/index.php");
	}
}

$ducan = getDucan($_GET['id']);

echo 'Ducan: '.$ducan->ime.', Tip: '.$ducan->tip.', vrsta: '.$ducan->vrsta.', ocjena: '.$ducan->ocjena;

echo "<br><br><a href='sviDucani.php'>Povratak na sve ducane</a> "


?>