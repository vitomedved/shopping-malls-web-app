<?php

function podatakExists($userId)
{
	$retVal = false;
	$link = connectToDB();
	
	if($link)
	{
		$query = "SELECT id_korisnik FROM podatak WHERE id_korisnik=".$userId;
		$result = mysqli_query($link, $query);
		if($result)
		{
			while($row = mysqli_fetch_array($result))
			{
				$retVal = true;
			}
		}
		mysqli_close($link);
	}
	return $retVal;
}

function newPodatak($userId, $ime, $prezime, $najDucan)
{
	$retVal = false;
	$link = connectToDB();
	if($link)
	{
		$query = "INSERT INTO `podatak` (`id_podatak`, `ime`, `prezime`, `naj_ducan`, `id_korisnik`) VALUES (NULL, '".$ime."', '".$prezime."', '".$najDucan."', '".$userId."');";
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