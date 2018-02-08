<?php

include_once 'connectionToDB.php';

if (session_status() == PHP_SESSION_NONE)
{
    session_start();
}

function checkNotifications($userId, $imeDucana, $timestamp)
{
	$ret = 0;
	$link = connectToDB();
	if($link)
	{
		$query = "SELECT count(id_komentar) as novih_komentara, id_korisnik FROM komentar NATURAL JOIN ducan WHERE ducan.ime='".$imeDucana."' AND UNIX_TIMESTAMP(vrijeme)>".$timestamp;
		$result = mysqli_query($link, $query);
		if($result)
		{
			while($row = mysqli_fetch_array($result))
			{
				if($row['id_korisnik'] == $userId)
				{
					continue;
				}
				$ret = $row['novih_komentara'];
			}
		}
		mysqli_close($link);
	}
	return $ret;
}

function lastLogged($userId)
{	
	$ret = 0;
	$link = connectToDB();
	if($link)
	{
		$query = "SELECT *, UNIX_TIMESTAMP(login) as timestamp FROM arhiva_logiranja WHERE id_korisnik=".$userId." ORDER BY timestamp DESC LIMIT 1 OFFSET 1";
		$result = mysqli_query($link, $query);
		if($result)
		{
			while($row = mysqli_fetch_array($result))
			{
				$ret = $row['timestamp'];
			}
		}
		mysqli_close($link);
	}
	return $ret;
}
?>