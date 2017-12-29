<?php 

//session_start();

include_once 'connectionToDB.php';

function getUserId()
{
	$link = connectToDB();

	if($link)
	{
		$query = "SELECT id_korisnik FROM korisnik WHERE email='".$_SESSION['email']."';";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_array($result))
		{
			return $row['id_korisnik'];
		}
	}
	else
	{
		echo('Ne mogu se povezati s bazom podataka.');
	}
	
}

?>