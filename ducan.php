<?php

include 'connectionToDB.php';

session_start();

$ime = '';
$ocjena = 0;

//Provjerava ako je id ducana unesen i ako je ispravan, tj. ako postoji i odmah uzima ime ducana van
if(isset($_GET['id']) == false)
{
	header("Location: /RWA_ducani/index.php");
}
else
{
	$ducanId = $_GET['id'];
	
	$link = connectToDB();
	$query = "SELECT * FROM ducan";
	$result = mysqli_query($link, $query);
	if($result)
	{
		$exist = false;
		while($row = mysqli_fetch_array($result))
		{
			if($row['id_ducan'] == $ducanId)
			{
				$GLOBALS['ime'] = $row['ime'];
				$exist = true;
				break;
			}
		}
		if(!$exist)
		{
			header("Location: /RWA_ducani/index.php");
		}
	}
}


//sprema ocjenu korisnika za odredeni ducan ili ako je vec glasao, updejta mu ocjenu
if(isset($_POST['ocjena']))
{
	$canRate = checkRating($_SESSION['userId']);
	$novaOcjena = $_POST['ocjena'];
	$link = connectToDB();
	if($link)
	{
		if($canRate)
		{
			$query = "INSERT INTO `ocjena` (`id_ocjena`, `vrijednost`, `id_ducan`, `id_korisnik`) VALUES (NULL, '".$novaOcjena."', '".$_GET['id']."', '".$_SESSION['userId']."');";
			$result = mysqli_query($link, $query);
			if(!$result)
			{
				echo("not rated");
			}
			else
			{
				header("Location: /RWA_ducani/ducan.php?id=".$_GET['id']);
			}
		}
		else
			//inace znaci da je vec ocjenio pa updejtaj vrijednost
		{
			$query = "UPDATE ocjena SET vrijednost='".$novaOcjena."' WHERE id_korisnik='".$_SESSION['userId']."';";
			$result = mysqli_query($link, $query);
			if(!$result)
			{
				echo("not rated");
			}
			echo("Nova ocjena spremljena");
		}
		
	}
	mysqli_close($link);
}

//izracunava vrijednost ocjene i sprema tu vrijednost u $ocjena varijablu preko $GLOBALS['ocjena']
getRating();

?>

<h1>DUĆAN: <?php echo($GLOBALS['ime'].', OCJENA: '.$GLOBALS['ocjena']) ?></h1>
<form action='ducan.php?id=<?php echo($_GET['id']) ?>' method='post'>
	Ocjeni dućan:
	<select name='ocjena'>
		<option value='1'>1</option>
		<option value='2'>2</option>
		<option value='3'>3</option>
		<option value='4'>4</option>
		<option value='5'>5</option>
	</select>
	<input type='submit' value='Ocijeni'>
</form>
<a href='index.php'> Povratak na početnu stranicu</a>

<?php

function getRating()
{
	$sumaOcjena = 0.0;
	$count = 0.0;
	
	$query = "SELECT vrijednost FROM ocjena WHERE id_ducan=".$_GET['id'].";";
	
	$link = connectToDB();
	
	if($link)
	{
		$result = mysqli_query($link, $query);
		if($result)
		{
			while($row = mysqli_fetch_array($result))
			{
				$sumaOcjena += $row['vrijednost'];
				$count += 1.0;
			}
		}
		
		if($count > 0)
		{
			$GLOBALS['ocjena'] = round(($sumaOcjena / $count), 2);
		}
		else
		{
			$GLOBALS['ocjena'] = 0;
		}
	}
	mysqli_close($link);
}

//Provjerava ako je korisnik već glasao na ovaj dućan
function checkRating($userId)
{
	$query = "SELECT id_korisnik FROM ocjena WHERE id_ducan=".$_GET['id'].";";
	$retVal = true;
	$link = connectToDB();
	
	if($link)
	{
		$result = mysqli_query($link, $query);
		if($result)
		{
			while($row = mysqli_fetch_array($result))
			{
				if($row['id_korisnik'] == $userId)
				{
					$retVal = false;
					break;
				}
			}
		}
	}
	mysqli_close($link);
	return $retVal;
}

?>