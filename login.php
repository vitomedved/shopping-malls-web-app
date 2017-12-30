<?php
session_start();

if(isset($_SESSION['loggedIn']) && ($_SESSION['loggedIn'] == true))
{
	header("Location: /RWA_ducani/index.php");
}

$email = '';

if(isset($_POST['email']))
{
	$email = $_POST['email'];
}

?>
<form action='login.php' method='post'>
	Email: <input type='email' name='email' required value=<?php echo($email)?>> <br>
	Password: <input type='password' name='password' required> <br>
	<input type='submit'>
</form>

<?php

include_once 'connectionToDB.php';
include_once 'userFunctions.php';
include_once 'arhivaLogiranja.php';

$link = connectToDB();

if($link == false)
{
	echo("Can't connect to DB.");
}

if(isset($_POST['email']) && isset($_POST['password']))
{
	$loggedIn = checkCredentials($link, $_POST['email'], $_POST['password']);
	if($loggedIn == true)
	{
		echo("Uspješno logiranje!");
		//TODO: these 4 lines below are the same as 4 lines when user is registered
		$_SESSION['loggedIn'] = true;
		$_SESSION['email'] = $_POST['email'];
		$_SESSION['userId'] = getUserId();
		arhivirajLogin($_SESSION['userId']);
		header("Location: /RWA_ducani/index.php");
	}
	else
	{
		echo("Email i odgovarajuća šifa se ne podudaraju");
	}
}

function checkCredentials($link, $email, $pw)
{
	$query = 'SELECT email, password FROM korisnik';
	$result = mysqli_query($link, $query);
	if(!$result)
	{
		echo("Upit nije dobar.");
	}
	else
	{
		while($row = mysqli_fetch_array($result))
		{
			if($row['email'] == $email)
			{
				if($row['password'] == $pw)
				{
					return true;
				}
				return false;
			}
		}
	}
}

?>

