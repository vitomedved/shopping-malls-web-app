<?php

include_once 'php/header.php';

include_once 'php/userClass.php';
include_once 'php/connectionToDB.php';
include_once 'php/komentarFunctions.php';
include_once 'php/userFunctions.php';

if(isGuest() || !isset($_GET['commentId']) || !isset($_GET['ducanId']))
{
	header("Location: /RWA_ducani/error.php");
}

$ducanId = $_GET['ducanId'];
$commentId = $_GET['commentId'];

$isOwner = validateOwnership($commentId, $_SESSION['userId']);
if(!$isOwner)
{
	header("Location: /RWA_ducani/error.php");
}

$naslov = getNaslov($commentId);
$sadrzaj = getSadrzaj($commentId);

//getCommentData($_GET['commentId']);

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
	
	$edited = editComment($commentId, $_SESSION['userId'], $naslov, $sadrzaj);
	if($edited)
	{
		header("Location: /RWA_ducani/ducan.php?id=".$_GET['ducanId']);
	}
	else
	{
		echo "error with editing, linija 44, editComment.php";
	}
}

?>


<form action='editComment.php?commentId=<?php echo($_GET['commentId']) ?>&ducanId=<?php echo($_GET['ducanId']) ?>' method='post'>
	<div class="col-md-12">
		<label>Naslov</label>
		<input name='naslov' type="text" class="form-control" id="exampleInputName1" placeholder="Naslov Komentara" value='<?php echo $naslov ?>' <?php if(isGuest()) echo 'disabled'; ?>>
		<label>Komentar</label>
		<textarea name='sadrzaj' class="form-control" rows="5" id="comment" placeholder="Komentar" <?php if(isGuest()) echo 'disabled'; ?>><?php echo $sadrzaj ?></textarea><br>
		<input type='submit' class="btn btn-xxl btn-yellow" <?php if(isGuest()) echo 'disabled'; ?> value='Spremi'>
	</div>
</form>

<?php

/*function getCommentData($commentId)
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
}*/

//getter za naslov
function getNaslov($commentId)
{
	$retVal = 'error';
	$link = connectToDB();
	if($link)
	{
		$query = "SELECT naslov FROM komentar WHERE id_komentar='".$commentId."';";
		$result = mysqli_query($link, $query);
		if($result)
		{
			while($row = mysqli_fetch_array($result))
			{
				$retVal = $row['naslov'];
			}
		}
		mysqli_close($link);
	}
	return $retVal;
}

//getter za sadrzaj
function getSadrzaj($commentId)
{
	$retVal = 'error';
	$link = connectToDB();
	if($link)
	{
		$query = "SELECT sadrzaj FROM komentar WHERE id_komentar='".$commentId."';";
		$result = mysqli_query($link, $query);
		if($result)
		{
			while($row = mysqli_fetch_array($result))
			{
				$retVal = $row['sadrzaj'];
			}
		}
	}
	mysqli_close($link);
	return $retVal;	
}

//edits selected comment, returns true/false
function editComment($commentId, $userId, $naslov, $sadrzaj)
{
	$retVal = false;
	$link = connectToDB();
	if($link)
	{	
		date_default_timezone_set('Europe/Zagreb');
		$query = "UPDATE komentar SET naslov='".$naslov."', sadrzaj='".$sadrzaj."', vrijeme='".date('Y-m-d H:i:s')."' WHERE id_komentar='".$commentId."';";
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