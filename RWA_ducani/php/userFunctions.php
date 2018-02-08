<?php

include_once 'userClass.php';
include_once 'connectionToDB.php';
include_once 'ducanFunctions.php';

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

function doesUserExist($userId)
{
	$retVal = false;
	$link = connectToDB();
	$query = "SELECT id_korisnik FROM korisnik";
	$result = mysqli_query($link, $query);
	if($result)
	{
		while($row = mysqli_fetch_array($result))
		{
			if($row['id_korisnik'] == $userId)
			{
				$retVal = true;
			}
		}
	}
	mysqli_close($link);
	return $retVal;
}

function userExist($email, $pw)
{
	$ret = false;
	$link = connectToDB();
	if($link)
	{
		$query = 'SELECT email FROM korisnik';
		$result = mysqli_query($link, $query);
		if($result)
		{
			while($row = mysqli_fetch_array($result))
			{
				if($row['email'] == $email)
				{
					$ret = true;
				}
			}
		}
		mysqli_close($link);
	}
	return $ret;
}

function getIme($userId)
{
	$link = connectToDB();
	$retVal = '';
	if(!$link)
	{
		$err = 'Ne mogu se spojiti s bazom';
		return $err;
	}
	
	$query = "SELECT ime FROM podatak WHERE podatak.id_korisnik=".$userId.";";
	$result = mysqli_query($link, $query);
	if($result)
	{
		while($row = mysqli_fetch_array($result))
		{
			$retVal = $row['ime'];
		}
	}
	
	mysqli_close($link);
	return $retVal;
}

function getPrezime($userId)
{
	$link = connectToDB();
	$retVal = '';
	
	if(!$link)
	{
		$err = 'Ne mogu se spojiti s bazom';
		return $err;
	}
	
	$query = "SELECT prezime FROM podatak WHERE podatak.id_korisnik=".$userId.";";
	$result = mysqli_query($link, $query);
	if($result)
	{
		while($row = mysqli_fetch_array($result))
		{
			$retVal = $row['prezime'];
		}
	}
	mysqli_close($link);
	return $retVal;
}

function getNajDucan($userId)
{
	$link = connectToDB();
	$retVal = '';
	
	if(!$link)
	{
		$err = 'Ne mogu se spojiti s bazom';
		return $err;
	}
	
	$query = "SELECT naj_ducan FROM podatak WHERE podatak.id_korisnik=".$userId.";";
	$result = mysqli_query($link, $query);
	if($result)
	{
		while($row = mysqli_fetch_array($result))
		{
			$retVal = $row['naj_ducan'];
		}
	}
	mysqli_close($link);
	return $retVal;
}

function updateIme($userId, $ime)
{
	$link = connectToDB();
	if(!$link)
	{
		echo("Ne mogu se povezati s bazom");
		return false;
	}
	$query = "UPDATE podatak SET ime='".$ime."' WHERE id_korisnik='".$userId."';";
	$result = mysqli_query($link, $query);
	if(!$result)
	{
		echo("Error with query");
	}
	if (mysqli_affected_rows($link) > 0)
	{
		mysqli_close($link);
		return true;
	}
	mysqli_close($link);
	return false;
}

function updatePrezime($userId, $prezime)
{
	$link = connectToDB();
	if(!$link)
	{
		echo("Ne mogu se povezati s bazom");
		return false;
	}
	$query = "UPDATE podatak SET prezime='".$prezime."' WHERE id_korisnik='".$userId."';";
	$result = mysqli_query($link, $query);
	if(!$result)
	{
		echo("Error with query");
	}
	if (mysqli_affected_rows($link) > 0)
	{
		mysqli_close($link);
		return true;
	}
	mysqli_close($link);
	return false;
}

function updateNajDucan($userId, $najDucan)
{
	$link = connectToDB();
	if(!$link)
	{
		echo("Ne mogu se povezati s bazom");
		return false;
	}
	$query = "UPDATE podatak SET naj_ducan='".$najDucan."' WHERE id_korisnik='".$userId."';";
	$result = mysqli_query($link, $query);
	if(!$result)
	{
		echo("Error with query");
	}
	if (mysqli_affected_rows($link) > 0)
	{
		mysqli_close($link);
		return true;
	}
	mysqli_close($link);
	return false;
}

function updateRazinaOvlasti($userId, $razina)
{
	$link = connectToDB();
	if(!$link)
	{
		echo("Ne mogu se povezati s bazom");
		return false;
	}
	$query = "UPDATE `korisnik` SET `razina_ovlasti` = '".$razina."' WHERE `korisnik`.`id_korisnik` = ".$userId.";";
	$result = mysqli_query($link, $query);
	if(!$result)
	{
		echo("Error with query");
	}
	if (mysqli_affected_rows($link) > 0)
	{
		mysqli_close($link);
		return true;
	}
	mysqli_close($link);
	return false;
}

function updateAvatar($userId, $avatar)
{
	$link = connectToDB();
	if(!$link)
	{
		echo("Ne mogu se povezati s bazom");
		return false;
	}
	$query = "UPDATE `podatak` SET `avatar` = '".$avatar."' WHERE `podatak`.`id_korisnik` = ".$userId.";";
	$result = mysqli_query($link, $query);
	if(!$result)
	{
		echo("Error with query");
	}
	if (mysqli_affected_rows($link) > 0)
	{
		mysqli_close($link);
		return true;
	}
	mysqli_close($link);
	return false;
}

function newUser($email, $pw, $ime, $prezime)
{
	$link = connectToDB();
	if($link)
	{
		//$pw = md5($pw);
		$query = "INSERT INTO `korisnik` (`id_korisnik`, `email`, `password`, `razina_ovlasti`) VALUES (NULL, '".$email."', '".md5($pw)."', 0);";
		$result = mysqli_query($link, $query);
		if($result)
		{
			$userId = mysqli_insert_id($link);
			
			$query = "INSERT INTO `podatak` (`id_podatak`, `ime`, `prezime`, `naj_ducan`, `avatar`, `id_korisnik`) VALUES (NULL, '".$ime."', '".$prezime."', NULL, '', '".$userId."');";
			$result = mysqli_query($link, $query);
			if($result)
			{
				mysqli_close($link);
				logIn($_POST['email']);
				$ret = true;
			}
		}
		mysqli_close($link);
	}
	return $ret;
}

function checkCredentials($email, $pw)
{
	$retVal = false;
	$link = connectToDB();
	if($link)
	{
		$query = "SELECT email, password FROM korisnik WHERE email='".$email."';";
		$result = mysqli_query($link, $query);
		if($result)
		{
			while($row = mysqli_fetch_array($result))
			{
				//if($row['email'] == $email)
				//{
					if($row['password'] == md5($pw))
					{
						$retVal = true;
					}
				//}
			}
		}
		mysqli_close($link);
	}
	return $retVal;
}

function getUserObject($userId)
{
	$ret = NULL;
	$link = connectToDB();
	if($link)
	{
		$query = "SELECT id_korisnik, email, razina_ovlasti, IFNULL(ime, '') as ime, IFNULL(prezime, '') as prezime, IFNULL(naj_ducan, '') as naj_ducan, IFNULL(avatar, '') as avatar FROM korisnik LEFT JOIN podatak USING (id_korisnik) WHERE id_korisnik=".$userId;
		$result = mysqli_query($link, $query);
		if($result)
		{
			while($row = mysqli_fetch_array($result))
			{
				//$id, $email, $razinaOvlasti, $ime, $prezime, $najDucan;
				$ret = new user();
				$ret->id = $row['id_korisnik'];
				$ret->email = $row['email'];
				$ret->razinaOvlasti = $row['razina_ovlasti'];
				$ret->ime = $row['ime'];
				$ret->prezime = $row['prezime'];
				$ret->najDucan = $row['naj_ducan'];
				
				if(!empty($row['avatar']))
				{
					$ret->avatar = "images/users/".$row['id_korisnik']."/".$row['avatar'];
				}
			}
		}
		mysqli_close($link);
		return $ret;
	}
}

function getAllUsers($searchParameter='', $keyword='', $isAdmin=false)
{
	$ret = array();
	$link = connectToDB();
	if($link)
	{
		if(!strcmp($searchParameter, 'Ime'))
		{
			$query = "SELECT id_korisnik, email, razina_ovlasti, IFNULL(ime, '') as ime, IFNULL(prezime, '') as prezime, IFNULL(naj_ducan, '') as naj_ducan, IFNULL(avatar, '') as avatar FROM korisnik LEFT JOIN podatak USING (id_korisnik) WHERE ime LIKE '".$keyword."%' ORDER BY ime;";
		}
		else if(!strcmp($searchParameter, 'Prezime'))
		{
			$query = "SELECT id_korisnik, email, razina_ovlasti, IFNULL(ime, '') as ime, IFNULL(prezime, '') as prezime, IFNULL(naj_ducan, '') as naj_ducan, IFNULL(avatar, '') as avatar FROM korisnik LEFT JOIN podatak USING (id_korisnik) WHERE prezime LIKE '".$keyword."%' ORDER BY prezime;";
		}
		else if(!strcmp($searchParameter, 'Email'))
		{
			$query = "SELECT id_korisnik, email, razina_ovlasti, IFNULL(ime, '') as ime, IFNULL(prezime, '') as prezime, IFNULL(naj_ducan, '') as naj_ducan, IFNULL(avatar, '') as avatar FROM korisnik LEFT JOIN podatak USING (id_korisnik) WHERE email LIKE '".$keyword."%' ORDER BY email;";
		}
		else
		{
			$query = "SELECT id_korisnik, email, razina_ovlasti, IFNULL(ime, '') as ime, IFNULL(prezime, '') as prezime, IFNULL(naj_ducan, '') as naj_ducan, IFNULL(avatar, '') as avatar FROM korisnik LEFT JOIN podatak USING (id_korisnik) ORDER BY ime";
		}
		
		$result = mysqli_query($link, $query);
		if($result)
		{
			$i = 0;
			while($row = mysqli_fetch_array($result))
			{
				if($isAdmin)
				{
					if($row['razina_ovlasti'] == 0)
					{
						continue;
					}
				}
				//$id, $email, $razinaOvlasti, $ime, $prezime, $najDucan;
				$ret[] = new user();
				$ret[$i]->id = $row['id_korisnik'];
				$ret[$i]->email = $row['email'];
				$ret[$i]->razinaOvlasti = $row['razina_ovlasti'];
				$ret[$i]->ime = $row['ime'];
				$ret[$i]->prezime = $row['prezime'];
				$ret[$i]->najDucan = $row['naj_ducan'];
				
				if(!empty($row['avatar']))
				{
					$ret[$i]->avatar = "images/users/".$row['id_korisnik']."/".$row['avatar'];
				}
				$i += 1;
			}
		}
		mysqli_close($link);
		if($i == 0)
		{
			$ret = -1;
		}
		return $ret;
	}
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

/*function getUser($email)
{
	$ret = NULL;
	$link = connectToDB();
	if($link)
	{
		$query = "SELECT id_korisnik, email, razina_ovlasti, IFNULL(ime, 'Nije postavljeno') as ime, IFNULL(prezime, 'Nije postavljeno') as prezime, IFNULL(naj_ducan, 'Neodredeno') as najDucan FROM korisnik LEFT JOIN podatak USING (id_korisnik) WHERE email='".$email."';";
		$result = mysqli_query($link, $query);
		if($result)
		{
			while($row = mysqli_fetch_array($result))
			{
				$ret = new user($row['id_korisnik'], $row['email'], $row['razina_ovlasti'], $row['ime'], $row['prezime'], $row['najDucan']);
				//$ret = new user();
				//$ret->id = $row['id_korisnik'];
				//$ret->email = $row['email'];
				//$ret->razinaOvlasti = $row['razina_ovlasti'];
				//$ret->ime = $row['ime'];
				//$ret->prezime = $row['prezime'];
				//$ret->najDucan = $row['najDucan'];
			}
		}
	}
	return $ret;
}*/

function logIn($email)
{
	$_SESSION['loggedIn'] = true;
	$_SESSION['userId'] = getUserId($email);
	arhivirajLogin($_SESSION['userId']);
}

?>