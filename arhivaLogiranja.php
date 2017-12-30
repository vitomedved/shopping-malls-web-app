<?php

function arhivirajLogin($userId)
{
	date_default_timezone_set('Europe/Zagreb');
	$currTime = date('Y-m-d H:i:s');
	
	$link = connectToDB();
	if(!$link)
	{
		echo("Can't connect to database");
	}
	else
	{
		$query = "INSERT INTO `arhiva_logiranja` (`id_arhiva_logiranja`, `vrijeme`, `id_korisnik`) VALUES (NULL, '".$currTime."', '".$userId."');";
		$result = mysqli_query($link, $query);
		if(!$result)
		{
			echo("Error with query");
		}
		else
		{
			echo("All good");
		}
	}
}

?>