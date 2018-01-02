<?php

include 'connectionToDB.php';
include 'komentarFunctions.php';

session_start();

if(!isset($_SESSION['loggedIn']) || ($_SESSION['loggedIn'] != true) || !isset($_GET['commentId']) || !isset($_GET['ducanId']))
{
	header("Location: /RWA_ducani/error.php");
}
//TODO ONLY ALLOW USER TO EDIT, ADMIN CAN'T EDIT
$ducanId = checkUserGetDucanId($_GET['commentId'], $_SESSION['userId']);

$naslov = '';
$sadrzaj = '';

getData($_GET['commentId']);
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
	{	date_default_timezone_set('Europe/Zagreb');
		$query = "UPDATE komentar SET naslov='".$naslov."', sadrzaj='".$sadrzaj."', id_korisnik='".$_SESSION['userId']."', id_ducan='".$_GET['ducanId']."', vrijeme='".date('Y-m-d H:i:s')."' WHERE id_komentar='".$_GET['commentId']."';";
		$result = mysqli_query($link, $query);
		if(!$result)
		{
			echo("Komentar nije editan, idk why");
		}
	}
	
	mysqli_close($link);
	header("Location: /RWA_ducani/ducan.php?id=".$_GET['ducanId']);
}
echo $_GET['ducanId'];

?>

<form action='editComment.php?commentId=<?php echo($_GET['commentId']) ?>&ducanId=<?php echo($_GET['ducanId']) ?>' method='post'>
	<br>
	<input type='text' name='naslov' placeholder='Naslov komentara (neobavezno)' value='<?php echo $naslov ?>'><br>
	<textarea name='sadrzaj' placeholder='Ovdje piši svoj komentar' required><?php echo $sadrzaj ?></textarea><br>
	<input type='submit'>
</form>

<?php

function getData($commentId)
{
	$link = connectToDB();
	if($link)
	{
		$query = "SELECT naslov, sadrzaj FROM komentar WHERE id_komentar='".$commentId."';";
		$result = mysqli_query($link, $query);
		if($result)
		{
			while($row = mysqli_fetch_array($result))
			{
				$GLOBALS['naslov'] = $row['naslov'];
				$GLOBALS['sadrzaj'] = $row['sadrzaj'];
			}
		}
	}
	mysqli_close($link);
}

?>