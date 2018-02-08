<?php

//provjerava ako je komentar od tog userId-a da nebi neko u url samo id komentara promijenil
function validateOwnership($commentId, $userId)
{
	$retVal = false;
	$link = connectToDB();
	if($link)
	{
		$query = "SELECT id_korisnik, id_ducan, naslov FROM komentar WHERE id_komentar=".$commentId;
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_array($result))
		{
			if($row['id_korisnik'] == $userId)
			{
				$retVal = true;
				//echo $row['naslov'];
				//header("Location: /RWA_ducani/error.php");
			}
			//return $row['id_ducan'];
		}
		mysqli_close($link);
	}
	return $retVal;
}

?>