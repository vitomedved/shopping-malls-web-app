<?php

include 'connectionToDB.php';
include 'userFunctions.php';
include 'ducanFunctions.php';

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

$ocjena = getRating($_GET['id']);


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
