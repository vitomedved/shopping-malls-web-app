<?php 

//session_start();

include_once 'connectionToDB.php';

if((isset($_SESSION['loggedIn']) == false) || ($_SESSION['loggedIn'] == false))
{
	//header("Location: /RWA_ducani/index.php");
}

function getUserId()
{
	$link = connectToDB();
	$retVal = -1;
	if($link)
	{
		$query = "SELECT id_korisnik FROM korisnik WHERE email='".$_SESSION['email']."';";
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

function isAdmin()
{
	$link = connectToDB();
	
	if($link)
	{
		$query = "SELECT razina_ovlasti FROM korisnik WHERE id_korisnik='".$_SESSION['userId']."';";
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

?>