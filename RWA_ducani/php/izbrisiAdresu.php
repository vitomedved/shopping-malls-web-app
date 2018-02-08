<?php

include_once 'connectionToDB.php';
include_once 'userClass.php'; 
include_once 'adresaFunctions.php';

if (session_status() == PHP_SESSION_NONE)
{
    session_start();
}

if($_SESSION['user']->razinaOvlasti == 0)
{
	header("Location: /RWA_ducani/index.php");
}

//ako nije postavljen id adrese, preusmjeri na index
if(isset($_GET['id']) == false)
{
	header("Location: /RWA_ducani/index.php");
}
//ako je postavljen id adrese, provjeri ako taj id postoji u bazi
else
{
	$adresaId = $_GET['id'];
	$exist = doesAdresaExist($adresaId);
	if(!$exist)
	{
		header("Location: /RWA_ducani/index.php");
	}
}

$deleted = izbrisiAdresu($_GET['id']);
if($deleted)
{
	echo 'obrisano';
	header("Location: /RWA_ducani/urediDucan.php?id=".$_GET['ducanId']);
}
else
{
	echo 'nije obrisano';
}


function izbrisiAdresu($adresaId)
{
	$ret = false;
	$link = connectToDB();
	if($link)
	{
		$query = "DELETE FROM adresa WHERE id_adresa=".$adresaId;
		$result = mysqli_query($link, $query);
		if($result)
		{
			$ret = true;
		}
		mysqli_close($link);
	}
	return $ret;
}

?>