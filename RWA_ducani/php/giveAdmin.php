<?php

include_once 'userFunctions.php';

$me = getUserObject($_SESSION['userId']);

if(!isset($_GET['userId']) || isGuest() || !doesUserExist($_GET['userId']) || ($me->razinaOvlasti == 0))
{
	header("Location: /RWA_ducani/php/error.php");
}

if(updateRazinaOvlasti($_GET['userId'], 1))
{
header("Location: /RWA_ducani/userProfil.php?id=".$_GET['userId']);
	
}

?>