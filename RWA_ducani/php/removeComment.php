<?php

include_once 'userClass.php';
include_once 'connectionToDB.php';
include_once 'userFunctions.php';
include_once 'komentarFunctions.php';

session_start();



if(isGuest() || !isset($_GET['commentId']) || !isset($_GET['ducanId']))
{
	header("Location: /RWA_ducani/error.php");
}

$isOwner = validateOwnership($_GET['commentId'], $_SESSION['user']->id);

if(!$isOwner)
{
	if($_SESSION['user']->razinaOvlasti == 0)
	{
		header("Location: /RWA_ducani/error.php");		
	}
}

$ducanId = $_GET['ducanId'];

$deleted = deleteComment($ducanId, $_GET['commentId']);

if($deleted)
{
	header("Location: /RWA_ducani/ducan.php?id=".$_GET['ducanId']);
}

?>