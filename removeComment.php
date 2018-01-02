<?php

include 'connectionToDB.php';

session_start();



if(!isset($_SESSION['loggedIn']) || ($_SESSION['loggedIn'] != true) || !isset($_GET['commentId']) || !isset($_GET['ducanId']))
{
	header("Location: /RWA_ducani/error.php");
}

$ducanId = checkUserGetDucanId($_GET['commentId'], $_SESSION['userId']);

deleteComment();

?>



<?php

function deleteComment()
{
	$link = connectToDB();
	if($link)
	{
		$query = "DELETE FROM `komentar` WHERE `komentar`.`id_komentar`=".$_GET['commentId'];
		$result = mysqli_query($link, $query);
		if(!$result)
		{
			header("Location: /RWA_ducani/error.php");
		}
		else
		{
			if (mysqli_affected_rows($link) > 0)
			{
				header("Location: /RWA_ducani/ducan.php?id=".$ducanId);
			}		
		}
	}
	
	header("Location: /RWA_ducani/error.php");
}

?>