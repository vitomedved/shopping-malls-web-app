<?php

//include_once 'connectionToDB.php';
include_once 'userClass.php'; 
include_once 'ducanFunctions.php';
include_once 'userFunctions.php';
include_once 'fileFunctions.php';

if (session_status() == PHP_SESSION_NONE)
{
    session_start();
}

$user = getUserObject($_SESSION['userId']);

if($user->razinaOvlasti == 0)
{
	header("Location: /RWA_ducani/index.php");
}

//ako nije postavljen id ducana, preusmjeri na index
if(isset($_GET['id']) == false)
{
	header("Location: /RWA_ducani/index.php");
}
//ako je postavljen index ducana, provjeri ako taj id postoji u bazi
else
{
	$exist = doesDucanExist($_GET['id']);
	if(!$exist)
	{
		header("Location: /RWA_ducani/index.php");
	}
}

$ducan = getDucan($_GET['id']);

//$dir = "images/".$ducan->ime;

if(izbrisiDucan($_GET['id']))
{
	//if(!empty($ducan->urlSlike))
	//{
		unlink($ducan->urlSlike);
	//}
	deleteDirectory($dir);
	header("Location: /RWA_ducani/sviDucani.php");
}
else
{
	echo "error";
}

?>