<?php 

include 'connectionToDB.php';

session_start();

//samo ako je logiran moze tu
if((isset($_SESSION['loggedIn']) == false) || ($_SESSION['loggedIn'] == false))
{
	header("Location: /RWA_ducani/index.php");
}

$ime = getIme();
$prezime = getPrezime();
$najDucan= getNajDucan();

if(isset($_GET['imeKorisnika']) || isset($_GET['prezimeKorisnika']) || isset($_GET['najDucan']))
{
	spremiPodatke($_GET['imeKorisnika'], $_GET['prezimeKorisnika'], $_GET['najDucan']);
}

?>

<form action='podatak.php' method='get'>
	Ime: <input type='text' name='imeKorisnika' placeholder='Ime' value='<?php echo($ime) ?>'><br>
	Prezime: <input type='text' name='prezimeKorisnika' placeholder='Prezime' value='<?php echo($prezime) ?>'><br>
	Najdraži dućani: <input type='text' name='najDucan' placeholder='Najdraži dućan/i' value='<?php echo($najDucan) ?>'><br>
	<input type='submit'>
</form>

<a href='index.php'> Povratak na početnu stranicu</a>

<?php

function getIme()
{
	$link = connectToDB();
	$retVal = '';
	if(!$link)
	{
		$err = 'Ne mogu se spojiti s bazom';
		return $err;
	}
	
	$query = "SELECT ime FROM podatak WHERE podatak.id_korisnik=".$_SESSION['userId'].";";
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

function getPrezime()
{
	$link = connectToDB();
	$retVal = '';
	
	if(!$link)
	{
		$err = 'Ne mogu se spojiti s bazom';
		return $err;
	}
	
	$query = "SELECT prezime FROM podatak WHERE podatak.id_korisnik=".$_SESSION['userId'].";";
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

function getNajDucan()
{
	$link = connectToDB();
	$retVal = '';
	
	if(!$link)
	{
		$err = 'Ne mogu se spojiti s bazom';
		return $err;
	}
	
	$query = "SELECT naj_ducan FROM podatak WHERE podatak.id_korisnik=".$_SESSION['userId'].";";
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

function spremiPodatke($ime, $prezime, $najDucan)
{
	$link = connectToDB();

	if(!$link)
	{
		echo('Ne mogu se spojiti s bazom');
		return false;
	}
	
	$query = "DELETE FROM `podatak` WHERE `podatak`.`id_korisnik` = ".$_SESSION['userId'].";";
	$result = mysqli_query($link, $query);
	if(!$result)
	{
		mysqli_close($link);
		return false;
	}
	$query = "INSERT INTO `podatak` (`id_podatak`, `ime`, `prezime`, `naj_ducan`, `id_korisnik`) VALUES (NULL, '".$ime."', '".$prezime."', '".$najDucan."', '".$_SESSION['userId']."');";
	$result = mysqli_query($link, $query);
	if(!$result)
	{
		mysqli_close($link);
		return false;
	}
	mysqli_close($link);
	header("Location: /RWA_ducani/podatak.php");
	return true;	
}

?>