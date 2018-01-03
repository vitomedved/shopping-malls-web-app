<?php

include 'connectionToDB.php';
include 'userFunctions.php';

session_start();

$ime = '';
$ocjena = 0;

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

//ako smo tu ducan postoji pa parsiraj njegovo ime
$ime = getDucanName($_GET['id']);


//sprema ocjenu korisnika za odredeni ducan ili ako je vec glasao, updejta mu ocjenu
if(isset($_POST['ocjena']))
{
	$canRate = ratedOnThisStore($_SESSION['userId'], $_GET['id']);
	$novaOcjena = $_POST['ocjena'];
	$updated = false;
	$added = false;
	
	if($canRate)
	{
		//Dodaje rating (userId, ducanId, $ocjena)
		$added = newRating($_SESSION['userId'], $_GET['id'], $novaOcjena);
	}
	else
		//inace znaci da je vec ocjenio pa updejtaj vrijednost
	{
		$updated = updateRating($_SESSION['userId'], $_GET['id'], $novaOcjena);
	}
	
	//ako je ocjena dodana ili updejtana, preusmjeri na stranicu ducana
	if($added || $updated)
	{
		header("Location: /RWA_ducani/ducan.php?id=".$_GET['id']);
	}
}

$ocjena = getRating();


//sprema komentar u bazu
if(isset($_POST['sadrzaj']))
{
	$commentAdded = false;
	$sadrzaj = $_POST['sadrzaj'];
	if(isset($_POST['naslov']))
	{
		$naslov = $_POST['naslov'];
	}
	else
	{
		$naslov = '';
	}
	
	$commentAdded = addComment($_SESSION['userId'], $_GET['id'], $naslov, $sadrzaj);
	if($commentAdded)
	{
		header("Location: /RWA_ducani/ducan.php?id=".$_GET['id']);
	}
	else
	{
		echo("Ne mogu dodati komentar, linija 81, ducan.php");
	}
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

listComments($_GET['id']);

?>

<form action='ducan.php?id=<?php echo($_GET['id']) ?>' method='post'>
	<br>
	<input type='text' name='naslov' placeholder='Naslov komentara (neobavezno)' <?php if(isGuest()) echo 'disabled'; ?>><br>
	<textarea name='sadrzaj' placeholder='Ovdje piši svoj komentar' required <?php if(isGuest()) echo 'disabled'; ?>></textarea><br>
	<input type='submit'>
	<?php if(isGuest()) echo "<a href='login.php'>Logiraj se</a>"; ?>
</form>


<a href='index.php'> Povratak na početnu stranicu</a>

<?php

//vraca prosjecnu ocjenu ducana. ako je greska vratit ce -1 (ili 0?)
function getRating()
{
	$sumaOcjena = 0;
	$count = 0.0;
	$retVal = -1;
	
	$link = connectToDB();
	
	$query = "SELECT vrijednost FROM ocjena WHERE id_ducan=".$_GET['id'].";";
	
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
			$retVal = round(($sumaOcjena / $count), 2);
		}
		else
		{
			$retVal = 0;
		}
	}
	mysqli_close($link);
	return $retVal;
}

//Provjerava ako je korisnik već glasao na odredeni ducan
//vraca true/false
function ratedOnThisStore($userId, $ducanId)
{
	$query = "SELECT id_korisnik FROM ocjena WHERE id_ducan=".$ducanId.";";
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

//Getter za ime ducana
function getDucanName($ducanId)
{
	$link = connectToDB();
	$query = "SELECT ime, id_ducan FROM ducan";
	$result = mysqli_query($link, $query);
	if($result)
	{
		while($row = mysqli_fetch_array($result))
		{
			if($row['id_ducan'] == $ducanId)
			{
				$ime = $row['ime'];
				mysqli_close($link);
				return $ime;
			}
		}
	}
	mysqli_close($link);
	return 'Greska u trazenju imena: linija 45, ducan.php';
}

//Provjerava postoji li ducan s tim id-om u bazi
//vraca true/false
function doesDucanExist($ducanId)
{
	$link = connectToDB();
	$query = "SELECT id_ducan FROM ducan";
	$result = mysqli_query($link, $query);
	if($result)
	{
		while($row = mysqli_fetch_array($result))
		{
			if($row['id_ducan'] == $ducanId)
			{
				mysqli_close($link);
				return true;
			}
		}
	}
	mysqli_close($link);
	return false;
}

//updejta novu ocjenu korisnika u bazu
function updateRating($userId, $ducanId, $ocjena)
{
	$link = connectToDB();
	if($link)
	{
		$query = "UPDATE ocjena SET vrijednost='".$ocjena."' WHERE id_korisnik='".$userId."' AND id_ducan='".$ducanId."';";
		$result = mysqli_query($link, $query);
		if(!$result)
		{
			echo("not rated");
			mysqli_close($link);
			return false;
		}
		mysqli_close($link);
		echo("Nova ocjena spremljena");
		return true;
	}
	return false;
}

//dodaje ocjenu korisnika za ducan u bazu
function newRating($userId, $ducanId, $ocjena)
{
	$link = connectToDB();
	if($link)
	{
		$query = "INSERT INTO `ocjena` (`id_ocjena`, `vrijednost`, `id_ducan`, `id_korisnik`) VALUES (NULL, '".$ocjena."', '".$ducanId."', '".$userId."');";
		$result = mysqli_query($link, $query);
		if(!$result)
		{
			echo("not rated");
			return false;
		}
		else
		{
			return true;
		}		
	}
	return false;
}

//dodaje uneseni komentar u bazu
function addComment($userId, $ducanId, $naslov, $sadrzaj)
{
	$retVal = false;
	$link = connectToDB();
	if($link)
	{
		$query = "INSERT INTO `komentar` (`id_komentar`, `naslov`, `sadrzaj`, `id_korisnik`, `id_ducan`) VALUES (NULL, '".$naslov."', '".$sadrzaj."', '".$_SESSION['userId']."', '".$_GET['id']."');";
		$result = mysqli_query($link, $query);
		if($result)
		{
			echo("Komentar dodan");
			$retVal = true;
		}
	}
	mysqli_close($link);
	return $retVal;
}

//izlista sve komentare trenutnog ducana
function listComments($ducanId)
{
	$link = connectToDB();
	if($link)
	{
		$query = "SELECT ime, prezime, UNIX_TIMESTAMP(vrijeme) as timestamp, vrijeme, komentar.id_korisnik, naslov, sadrzaj, id_komentar FROM komentar LEFT JOIN podatak ON (komentar.id_korisnik = podatak.id_korisnik) WHERE id_ducan='".$ducanId."' ORDER BY timestamp DESC";
		$result = mysqli_query($link, $query);
		if($result)
		{
			while($row = mysqli_fetch_array($result))
			{
				echo("Komentar by: ".$row['ime']." ".$row['prezime'].", vrijeme: ".date('d.m.Y., H:i\h', strtotime($row['vrijeme']))."<br>
				<div>".$row['naslov']."<br>".$row['sadrzaj']."</div><br>");
				if(isset($_SESSION['loggedIn']) && ($_SESSION['loggedIn'] == true))
				{
					if(isAdmin($_SESSION['userId']) && ($row['id_korisnik'] == $_SESSION['userId']))
					{
						//admin sam i moj je komentar
						echo("<a href='removeComment.php?commentId=".$row['id_komentar']."&ducanId=".$_GET['id']."'>REMOVE</a> | <a href='editComment.php?commentId=".$row['id_komentar']."&ducanId=".$_GET['id']."'>EDIT</a>");
					}
					else if(isAdmin($_SESSION['userId']))
						//admin, a nije moj komentar
					{
						echo("<a href='editComment.php?commentId=".$row['id_komentar']."&ducanId=".$_GET['id']."'>EDIT</a>");
					}
					else if(($row['id_korisnik'] == $_SESSION['userId']))
						//moj komentar, a nisam admin
					{
						echo("<a href='removeComment.php?commentId=".$row['id_komentar']."&ducanId=".$_GET['id']."'>REMOVE</a> | <a href='editComment.php?commentId=".$row['id_komentar']."&ducanId=".$_GET['id']."'>EDIT</a>");
					}
				}
				echo("<br><hr><br>");
				
			}
		}
	}
	mysqli_close($link);
}


?>