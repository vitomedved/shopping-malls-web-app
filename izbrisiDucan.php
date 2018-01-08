<?php

include_once 'connectionToDB.php';
include_once 'userClass.php'; 
include_once 'ducanFunctions.php';

if (session_status() == PHP_SESSION_NONE)
{
    session_start();
}

if($_SESSION['user']->razinaOvlasti == 0)
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

if(izbrisiDucan($_GET['id']))
{
	header("Location: /RWA_ducani/sviDucani.php");
}
else
{
	echo "error";
}

function izbrisiDucan($ducanId)
{
	$ret = false;
	$link = connectToDB();
	if($link)
	{
		$query = "DELETE FROM ducan WHERE id_ducan=".$ducanId;
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