<?php

include 'connectionToDB.php';
include 'userFunctions.php';

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


//sprema komentar u bazu
if(isset($_POST['sadrzaj']))
{
	if(isset($_POST['naslov']))
	{
		$naslov = $_POST['naslov'];
	}
	else
	{
		$naslov = '';
	}
	$sadrzaj = $_POST['sadrzaj'];
	
	$link = connectToDB();
	if($link)
	{
		$query = "INSERT INTO `komentar` (`id_komentar`, `naslov`, `sadrzaj`, `id_korisnik`, `id_ducan`) VALUES (NULL, '".$naslov."', '".$sadrzaj."', '".$_SESSION['userId']."', '".$_GET['id']."');";
		$result = mysqli_query($link, $query);
		if(!$result)
		{
			echo("Komentar nije dodan, idk why");
		}
	}
	
	mysqli_close($link);
	header("Location: /RWA_ducani/ducan.php?id=".$_GET['id']);
}

?>

<h1>DUĆAN: <?php echo($GLOBALS['ime'].', OCJENA: '.$GLOBALS['ocjena']) ?></h1>
<h3>tu neku slikicu zabacit dinamicki</h3>
<form action='ducan.php?id=<?php echo($_GET['id']) ?>' method='post'>
	Ocjeni dućan:
	<select name='ocjena' <?php if(isGuest()) echo 'disabled'; ?>>
		<option value='1'>1</option>
		<option value='2'>2</option>
		<option value='3'>3</option>
		<option value='4'>4</option>
		<option value='5'>5</option>
	</select>
	<input type='submit' value='Ocijeni' <?php if(isGuest()) echo 'disabled'; ?>>
	
	<?php if(isGuest()) echo "<a href='login.php'>Logiraj se</a>"; ?>
</form>

<h2>Komentari predivno uređeni:</h2>
<br><hr><br>

<?php

listComments();

?>

<form action='ducan.php?id=<?php echo($_GET['id']) ?>' method='post'>
	<br>
	<input type='text' name='naslov' placeholder='Naslov komentara (neobavezno)' <?php if(isGuest()) echo 'disabled'; ?>><br>
	<textarea name='sadrzaj' placeholder='Ovdje piši svoj komentar' required <?php if(isGuest()) echo 'disabled'; ?>></textarea><br>
	<input type='submit' <?php echo isGuest() ?>>
	<?php if(isGuest()) echo "<a href='login.php'>Logiraj se</a>"; ?>
</form>


<a href='index.php'> Povratak na početnu stranicu</a>

<?php

//uzima iz baze prosjecni rejting za ducan
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

function listComments()
{
	$link = connectToDB();
	if($link)
	{
		$query = "SELECT * FROM komentar NATURAL JOIN podatak";
		$result = mysqli_query($link, $query);
		if($result)
		{
			while($row = mysqli_fetch_array($result))
			{
				echo("Komentar by: ".$row['ime']." ".$row['prezime'].", vrijeme: ".date('d.m.Y., H:i\h', strtotime($row['vrijeme']))."<br>
				<div>".$row['naslov']."<br>".$row['sadrzaj']."</div><br>");
				if(isAdmin() || ($row['id_korisnik'] == $_SESSION['userId']))
				{
					echo("<a href='editComment.php?commentId=".$row['id_komentar']."'>EDIT</a> | <a href='removeComment.php?commentId=".$row['id_komentar']."&ducanId=".$_GET['id']."'>REMOVE</a><br><hr><br>");
				}
			}
		}
	}
	mysqli_close($link);
}

function isGuest()
{
	if(!isset($_SESSION['loggedIn']) || ($_SESSION['loggedIn'] == false))
	{
		return true;
	}
	return false;
}

?>