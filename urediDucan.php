<?php

include_once 'adresaFunctions.php';
include_once 'ducanFunctions.php';
include_once 'connectionToDB.php';

if (session_status() == PHP_SESSION_NONE)
{
    session_start();
}

//ako nije postavljen id ducana, preusmjeri na index
if(isset($_GET['id']) == false)
{
	header("Location: /RWA_ducani/index.php");
}
//ako je postavljen index ducana, provjeri ako taj id postoji u bazi
else
{
	$ducanId = $_GET['id'];
	$exist = doesDucanExist($_GET['id']);
	if(!$exist)
	{
		header("Location: /RWA_ducani/index.php");
	}
}

$ducan = getDucan($_GET['id']);

if(isset($_GET['grad']) && isset($_GET['postanskiBroj']) && isset($_GET['kucniBroj']) && isset($_GET['ulica']))
{
	echo 'asd';
	$added = newAdresa($_GET['id'], $_GET['ulica'], $_GET['grad'], $_GET['postanskiBroj'], $_GET['kucniBroj']);
	if($added)
	{
		echo 'spremljeno';
		header("Location: /RWA_ducani/urediDucan.php?id=".$_GET['id']);
	}
	else
	{
		echo 'nije spremljeno';	
	}
}

if(isset($_GET['imeDucana']))
{
	updateIme($_GET['id'], $_GET['imeDucana']);
}

if(isset($_GET['tipDucana']))
{
	updateTip($_GET['id'], $_GET['tipDucana']);
}

if(isset($_GET['vrstaDucana']))
{
	updateVrsta($_GET['id'], $_GET['vrstaDucana']);
}

$ducan = getDucan($_GET['id']);

?>

<form action='urediDucan.php?id=<?php echo $_GET['id']; ?>' method='get'>
	OSNOVNI PODACI:<br>
	Ime dućana: <input type='text' name='imeDucana' placeholder='Ime' value='<?php echo($ducan->ime) ?>'><br>
	Tip dućana prema artiklu: 
	<select name='tipDucana' required>
		<option value='odjeca'>Odjeća</option>
		<option value='pokloni'>Pokloni</option>
		<option value='sport'>Športska oprema</option>
		<option value='obuca'>Obuća</option>
		<option value='prehrana'>Prehrana</option>
		<option value='namjestaj'>Namještaj</option>
		<option value='igracke'>Igračke</option>
		<option value='tehnika'>Tehnička roba</option>
	</select><?php echo 'trenutno postavljeno: '.$ducan->tip ?><br>
	Vrsta dućana prema veličini: 
	<select name='vrstaDucana' value='trgovina' required>
		<option value='tvornica'>Tvornička prodaja</option>
		<option value='supermarket'>Supermarket</option>
		<option value='trgovina'>Trgovina</option>
	</select><?php echo 'trenutno postavljeno: '.$ducan->vrsta ?><br><br>
<input type='text' name='id' value='<?php echo $_GET['id']; ?>' hidden>	
	<input type='submit'><br>
</form>

<form action='urediDucan.php?id=<?php echo $_GET['id']; ?>' method='get'><br>
	DODAJ NOVU ADRESU:<br>
	Grad: <input type='text' name='grad' required><br>
	Poštanski broj: <input type='text' name='postanskiBroj' required><br>
	Ulica: <input type='text' name='ulica' required><br>
	Kućni broj: <input type='text' name='kucniBroj' required><br>
	<input type='text' name='id' value='<?php echo $_GET['id']; ?>' hidden>
	<input type='submit'>
</form>

<?php

echo 'Postojeće adrese:<br>';
foreach($ducan->adrese as $adresa)
{
	echo 'grad: '.$adresa->grad.', ulica: '.$adresa->ulica.', kucni broj: '.$adresa->kucniBroj." <a href='izbrisiAdresu.php?id=".$adresa->id."&ducanId=".$_GET['id']."'>Izbrisi ovu adresu</a><br>";
}

?>

<a href='index.php'> <br>Povratak na početnu stranicu</a>

<?php

/*function doesAdresaExist($ducanId)
{
	$ret = false;
	$link = connectToDB();
	if($link)
	{
		$query = "SELECT id_ducan FROM adresa_has_ducan WHERE id_ducan=".$ducanId;
		$result = mysqli_query($link, $query);
		if($result)
		{
			while($row = mysqli_fetch_array($result))
			{
				$ret = true;
				break;
			}
		}
		mysqli_close($link);
	}
	return $ret;
}*/

//napravi novi podatak u tablici adresa i automatski u adresa_has_ducan stavi odgovarajucu vezu izmedu ducana i adrese
function newAdresa($ducanId, $ulica, $grad, $postanski, $kucni)
{
	$retVal = false;
	$link = connectToDB();
	if($link)
	{
		$query = "INSERT INTO `adresa` (`id_adresa`, `ulica`, `grad`, `postanski_broj`, `kucni_broj`) VALUES (NULL, '".$ulica."', '".$grad."', ".$postanski.", ".$kucni.");";
		$result = mysqli_query($link, $query);
		if($result)
		{
			$adresaId = mysqli_insert_id($link);
			$query = "INSERT INTO adresa_has_ducan (id_adresa, id_ducan) VALUES (".$adresaId.", ".$ducanId.");";
			$result = mysqli_query($link, $query);
			if($result)
			{
				$retVal = true;
			}
		}
		mysqli_close($link);
	}
	return $retVal;
}

/* PATTERN
	$link = connectToDB();
	if($link)
	{
		$query = "";
		$result = mysqli_query($link, $query);
		if($result)
		{
			while($row = mysqli_fetch_array($result))
			{
				
			}
		}
		mysqli_close($link);
	}
*/


?>

