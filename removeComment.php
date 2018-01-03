<?php

include 'connectionToDB.php';
include 'userFunctions.php';
include 'komentarFunctions.php';

session_start();



if(isGuest() || !isset($_GET['commentId']) || !isset($_GET['ducanId']))
{
	header("Location: /RWA_ducani/error.php");
}

$isOwner = validateOwnership($_GET['commentId'], $_SESSION['userId']);

if(!$isOwner)
{
	if(!isAdmin($_SESSION['userId']))
	{
		header("Location: /RWA_ducani/error.php");		
	}
}

$ducanId = $_GET['ducanId'];

$deleted = deleteComment($ducanId, $_GET['commentId']);

if($deleted)
{
	header("Location: /RWA_ducani/ducan.php?id=".$_GET['ducanId']);
}

?>



<?php

function deleteComment($ducanId, $commentId)
{
	$retVal = false;
	$link = connectToDB();
	if($link)
	{
		$query = "DELETE FROM `komentar` WHERE `komentar`.`id_komentar`=".$commentId;
		$result = mysqli_query($link, $query);
		if($result)
		{
			
			//header("Location: /RWA_ducani/error.php");
			if (mysqli_affected_rows($link) > 0)
			{
				$retVal = true;
				//header("Location: /RWA_ducani/ducan.php?id=".$ducanId);
			}		
		}
		//else
		//{
		//}
		mysqli_close($link);
	}
	return $retVal;
	
	//header("Location: /RWA_ducani/error.php");
}

?>