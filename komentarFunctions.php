<?php

//provjerava ako je komentar od tog userId-a da nebi neko u url samo id komentara promijenil
function checkUserGetDucanId($commentId, $userId)
{
	$link = connectToDB();
	if($link)
	{
		$query = "SELECT id_korisnik, id_ducan FROM komentar WHERE id_komentar=".$commentId;
		$result = mysqli_query($link, $query);
		
		while($row = mysqli_fetch_array($result))
		{
			if($row['id_korisnik'] != $userId)
			{
				header("Location: /RWA_ducani/error.php");
			}
			return $row['id_ducan'];
		}
	}
	mysqli_close($link);
	header("Location: /RWA_ducani/error.php");
}

?>