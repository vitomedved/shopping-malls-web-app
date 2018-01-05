<?php 

include_once 'userClass.php';
include_once 'connectionToDB.php';
include_once 'userFunctions.php';
include_once 'ducanFunctions.php';

if (session_status() == PHP_SESSION_NONE)
{
    session_start();
}

//samo ako je logiran moze tu
if(isGuest())
{
	header("Location: /RWA_ducani/index.php");
}

$_SESSION['user']->ime = getIme($_SESSION['user']->id);
$_SESSION['user']->prezime = getPrezime($_SESSION['user']->id);
$_SESSION['user']->najDucan = getNajDucan($_SESSION['user']->id);

$podatakPostoji = podatakExists($_SESSION['user']->id);

if(!$podatakPostoji)
{
	$added = newPodatak($_SESSION['user']->id, $_SESSION['user']->ime, $_SESSION['user']->prezime, $_SESSION['user']->najDucan);
	if($added)
	{
		header("Location: /RWA_ducani/podatak.php");
	}
	else
	{
		echo("you're fucked");
	}
}



if(isset($_GET['imeKorisnika']))
{
	$ret = updateIme($_SESSION['user']->id, $_GET['imeKorisnika']);
	if($ret)
	{
		header("Location: /RWA_ducani/podatak.php");
	}
	//echo 'ime nije dodano: linija 26, podatak.php';
	
}

if(isset($_GET['prezimeKorisnika']))
{
	$ret = updatePrezime($_SESSION['user']->id, $_GET['prezimeKorisnika']);
	if($ret)
	{
		header("Location: /RWA_ducani/podatak.php");
	}
	//echo 'prezime nije dodano: linija 35, podatak.php';
}

if(isset($_GET['najDucan']))
{
	$ret = updateNajDucan($_SESSION['user']->id, $_GET['najDucan']);
	if($ret)
	{
		header("Location: /RWA_ducani/podatak.php");
	}
	//echo 'naj ducan nije dodan: linija 44, podatak.php';
}

?>

<form action='podatak.php' method='get'>
	Ime: <input type='text' name='imeKorisnika' placeholder='Ime' value='<?php echo($_SESSION['user']->ime) ?>'><br>
	Prezime: <input type='text' name='prezimeKorisnika' placeholder='Prezime' value='<?php echo $_SESSION['user']->prezime ?>'><br>
	Najdraži dućan: <!--<input type='text' name='najDucan' placeholder='Najdraži dućan/i' value='<?php echo $_SESSION['user']->najDucan ?>'><br>-->
	<select name='najDucan'>
		<?php
		//uzme sve ducane
		$ducaniArray = getDucaniArray();
		//print options of stores
		foreach($ducaniArray as $ducan)
		{
			echo "<option value='".$ducan->ime."'>".$ducan->ime."</option>";
		}
		
		?>
	</select><br>
	<input type='submit'>
</form>

<a href='index.php'> Povratak na početnu stranicu</a>

<?php

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


/*function spremiPodatke($ime, $prezime, $najDucan)
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
}*/

function podatakExists($userId)
{
	$retVal = false;
	$link = connectToDB();
	
	if($link)
	{
		$query = "SELECT id_korisnik FROM podatak WHERE id_korisnik=".$userId;
		$result = mysqli_query($link, $query);
		if($result)
		{
			while($row = mysqli_fetch_array($result))
			{
				$retVal = true;
			}
		}
		mysqli_close($link);
	}
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

function newPodatak($userId, $ime, $prezime, $najDucan)
{
	$retVal = false;
	$link = connectToDB();
	if($link)
	{
		$query = "INSERT INTO `podatak` (`id_podatak`, `ime`, `prezime`, `naj_ducan`, `id_korisnik`) VALUES (NULL, '".$ime."', '".$prezime."', '".$najDucan."', '".$userId."');";
		$result = mysqli_query($link, $query);
		if($result)
		{
			$retVal = true;
		}
		mysqli_close($link);
	}
	return $retVal;
}

?>