<?php 

include_once 'userClass.php';
include_once 'connectionToDB.php';

if (session_status() == PHP_SESSION_NONE)
{
    session_start();
}

$idLogiranja = getIdLogiranja($_SESSION['userId']);

if(setLogoutTimestamp($idLogiranja))
{
	echo "spremljeno";
}
else
{
	echo 'nije spremljeno';
}

function getIdLogiranja($userId)
{
	$ret = 0;
	$link = connectToDB();
	if($link)
	{
		$query = "SELECT id_arhiva_logiranja FROM arhiva_logiranja WHERE id_korisnik=".$userId." ORDER BY UNIX_TIMESTAMP(login) DESC LIMIT 1";
		$result = mysqli_query($link, $query);
		if($result)
		{
			while($row = mysqli_fetch_array($result))
			{
				$ret = $row['id_arhiva_logiranja'];
			}
		}
		mysqli_close($link);
	}
	return $ret;
}

function setLogoutTimestamp($idLogiranja)
{
	$ret = false;
	$link = connectToDB();
	if($link)
	{
		date_default_timezone_set('Europe/Zagreb');
		$currTime = date('Y-m-d H:i:s');
		echo($currTime);
		$query = "UPDATE arhiva_logiranja SET logout='".$currTime."' WHERE arhiva_logiranja.id_arhiva_logiranja=".$idLogiranja;
		$result = mysqli_query($link, $query);
		if($result)
		{
			$ret = true;
		}
		mysqli_close($link);
	}
	return $ret;
}




session_unset();
session_destroy();
header("Location: /RWA_ducani/index.php");

?>