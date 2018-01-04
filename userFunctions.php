<?php

include_once 'userClass.php';
include_once 'connectionToDB.php';

if (session_status() == PHP_SESSION_NONE)
{
    session_start();
}


//vraca userId prema e-mailu ili -2/-1 ako je error
function getUserId($email)
{
	$link = connectToDB();
	$retVal = -1;
	if($link)
	{
		$query = "SELECT id_korisnik FROM korisnik WHERE email='".$email."';";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_array($result))
		{
			$retVal = $row['id_korisnik'];
		}
	}
	else
	{
		echo('Ne mogu se povezati s bazom podataka.');
		$retVal = -2;
	}
	mysqli_close($link);
	return $retVal;
}

//provjerava ako je korisnik admin, vraca true/false
function isAdmin($userId)
{
	$link = connectToDB();
	
	if($link)
	{
		$query = "SELECT razina_ovlasti FROM korisnik WHERE id_korisnik='".$userId."';";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_array($result))
		{
			if($row['razina_ovlasti'] == 1)
			{
				mysqli_close($link);
				return true;
			}
		}
	}
	mysqli_close($link);
	return false;
}

//provjerava ako je korisnik logiran, vraca true/false
function isGuest()
{
	if(!isset($_SESSION['loggedIn']) || ($_SESSION['loggedIn'] == false))
	{
		return true;
	}
	return false;
}

function getUser($email)
{
	$ret = new user();
	$link = connectToDB();
	if($link)
	{
		$query = "SELECT id_korisnik, email, razina_ovlasti, IFNULL(ime, 'Nije postavljeno') as ime, IFNULL(prezime, 'Nije postavljeno') as prezime, IFNULL(naj_ducan, 'Neodredeno') as najDucan FROM korisnik LEFT JOIN podatak USING (id_korisnik) WHERE email='".$email."';";
		$result = mysqli_query($link, $query);
		if($result)
		{
			while($row = mysqli_fetch_array($result))
			{
				//ret = new user($row['id'], $row['email'], $row['razinaOvlasti'], $row['ime'], $row['prezime'], $row['najDucan']);
				$ret->id = $row['id_korisnik'];
				$ret->email = $row['email'];
				$ret->razinaOvlasti = $row['razina_ovlasti'];
				$ret->ime = $row['ime'];
				$ret->prezime = $row['prezime'];
				$ret->najDucan = $row['najDucan'];
			}
		}
	}
	return $ret;
}

function logIn($email)
{
	$_SESSION['loggedIn'] = true;
	$_SESSION['user'] = getUser($email);
	echo $_SESSION['user']->id;
	arhivirajLogin($_SESSION['user']->id);
}

?>