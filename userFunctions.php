<?php

include_once 'connectionToDB.php';

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

?>