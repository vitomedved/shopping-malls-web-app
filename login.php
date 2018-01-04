<?php

include_once 'userClass.php';

if (session_status() == PHP_SESSION_NONE)
{
    session_start();
}

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
<a href='index.php'> Povratak na početnu stranicu</a>

<?php

include_once 'connectionToDB.php';
include_once 'userFunctions.php';
include_once 'arhivaLogiranja.php';

if(isset($_POST['email']) && isset($_POST['password']))
{
	$loggedIn = checkCredentials($_POST['email'], $_POST['password']);
	if($loggedIn == true)
	{
		echo("Uspješno logiranje!");
		//TODO: these 4 lines below are the same as 4 lines when user is registered
		//$_SESSION['loggedIn'] = true;
		//$_SESSION['email'] = $_POST['email'];
		//$_SESSION['userId'] = getUserId($_SESSION['email']);
		//arhivirajLogin($_SESSION['userId']);
		logIn($_POST['email']);
		header("Location: /RWA_ducani/index.php");
	}
	else
	{
		echo("Email i odgovarajuća šifa se ne podudaraju");
	}
}


function checkCredentials($email, $pw)
{
	$retVal = false;
	$link = connectToDB();
	if($link)
	{
		$query = "SELECT email, password FROM korisnik WHERE email='".$email."';";
		$result = mysqli_query($link, $query);
		if($result)
		{
			while($row = mysqli_fetch_array($result))
			{
				//if($row['email'] == $email)
				//{
					if($row['password'] == $pw)
					{
						$retVal = true;
					}
				//}
			}
		}
		mysqli_close($link);
	}
	return $retVal;
}

?>

