<?php

//ime ocjena i tip
//session_start();
include 'connectionToDB.php';
include 'userFunctions.php';

session_start();

if(isAdmin($_SESSION['userId']) == false)
{
	header("Location: /RWA_ducani/index.php");
}

if(isset($_GET['imeDucana']) && isset($_GET['tipDucana']) && isset($_GET['vrstaDucana']))
{
	$exist = isDucanDuplicate($_GET['imeDucana']);
	
	if($exist)
	{
		echo("Taj dućan već postoji");
	}
	else
	{
		spremiDucan($_GET['imeDucana'], $_GET['tipDucana'], $_GET['vrstaDucana']);
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

<form action='dodajDucan.php'>
	Ime dućana: <input type='text' name='imeDucana' placeholder='Ime dućana' required><br>
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
	<input type='submit'><br>
</form>
<a href='index.php'> Povratak na početnu stranicu</a>

<?php

function spremiDucan($ime, $tip, $vrsta)
{
	$link = connectToDB();
	if($link)
	{
		$query = "INSERT INTO `ducan` (`id_ducan`, `ime`, `tip_ducana`, `vrsta_ducana`) VALUES (NULL, '".$ime."', '".$tip."', '".$vrsta."');";
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