<?php

include_once 'userClass.php';
include_once 'connectionToDB.php';
include_once 'userFunctions.php';

if (session_status() == PHP_SESSION_NONE)
{
    session_start();
}

//nije admin
if($_SESSION['user']->razinaOvlasti == 0)
{
	header("Location: /RWA_ducani/index.php");
}

if(isset($_POST['imeDucana']) && isset($_POST['tipDucana']) && isset($_POST['vrstaDucana']) && isset($_FILES['image']))
{
	$exist = isDucanDuplicate($_POST['imeDucana']);
	
	//slika
	$_FILES['image']['name'] = explode(' ', $_FILES['image']['name']);
	$_FILES['image']['name'] = implode('_', $_FILES['image']['name']);
	
	$target = "images/";
	if(!file_exists($target))
	{
		mkdir($target);
	}
	
	$target .= $_POST['imeDucana']."/";
	if(!file_exists($target))
	{
		mkdir($target);
	}
	
	if(!file_exists($target.$_FILES['image']['name']))
	{
		move_uploaded_file($_FILES['image']['tmp_name'], $target.$_FILES['image']['name']);
	}
	//
	
	if($exist)
	{
		echo("Taj dućan već postoji");
	}
	else
	{
		spremiDucan($_POST['imeDucana'], $_POST['tipDucana'], $_POST['vrstaDucana'], $_FILES['image']['name']);
	}
}

function isDucanDuplicate($ducanIme)
{
	$retVal = false;
	$link = connectToDB();
	$query = "SELECT ime FROM ducan WHERE ime='".$ducanIme."';";
	$result = mysqli_query($link, $query);
	if($result)
	{
		while($row = mysqli_fetch_array($result))
		{
			if($row['ime'] == $ducanIme)
			{
				$retVal = true;
			}
		}
	}
	mysqli_close($link);
	return $retVal;
}

?>

<form action='dodajDucan.php' method='post' enctype="multipart/form-data">
	Ime dućana: <input type='text' name='imeDucana' placeholder='Ime dućana' required /><br>
	Odaberi tip dućana prema artiklu: 
	<select name='tipDucana' required>
		<option value='odjeca'>Odjeća</option>
		<option value='pokloni'>Pokloni</option>
		<option value='sport'>Športska oprema</option>
		<option value='obuca'>Obuća</option>
		<option value='prehrana'>Prehrana</option>
		<option value='namjestaj'>Namještaj</option>
		<option value='igracke'>Igračke</option>
		<option value='tehnika'>Tehnička roba</option>
	</select><br>
	Odarebi tip dućana prema veličini: 
	<select name='vrstaDucana' value='trgovina' required>
		<option value='tvornica'>Tvornička prodaja</option>
		<option value='supermarket'>Supermarket</option>
		<option value='trgovina'>Trgovina</option>
	</select><br>
	<input type='file' name='image' required /><br>
	<input type='submit' /><br>
</form>
<a href='index.php'> Povratak na početnu stranicu</a>

<?php

function spremiDucan($ime, $tip, $vrsta, $slika)
{
	$link = connectToDB();
	if($link)
	{
		$query = "INSERT INTO `ducan` (`id_ducan`, `ime`, `tip_ducana`, `vrsta_ducana`, slika) VALUES (NULL, '".$ime."', '".$tip."', '".$vrsta."', '".$slika."');";
		$result = mysqli_query($link, $query);
		if(!$result)
		{
			echo("Dućan nije dodan, error");
			mysqli_close($link);
			return false;
		}
	}
	mysqli_close($link);
	header("Location: /RWA_ducani/index.php");
}

?>