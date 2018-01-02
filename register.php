<?php 

include_once 'connectionToDB.php';
include_once 'arhivaLogiranja.php';
include_once 'userFunctions.php';


session_start();

$email = '';

if(isset($_POST['email']))
{
	$email = $_POST['email'];
}

?>

<form action='register.php' method='post'>
	Email: <input type='email' name='email' required value=<?php echo($email)?>> <br>
	Password: <input type='password' name='password' required> <br>
	Repeat password: <input type='password' name='repeat_password' required> <br>
	<input type='submit'>
</form>
<a href='index.php'> Povratak na početnu stranicu</a>

<?php

if(isset($_POST['email']) && isset($_POST['password']) && isset($_POST['repeat_password']))
{
	$email = $_POST['email'];
	$good_pw = check_pw($_POST['password'], $_POST['repeat_password']);
	if($good_pw)
	{
		$link = connectToDB();
		if($link)
		{
			$registered = register($link, $_POST['email'], $_POST['password']);
			mysqli_close($link);
		}
		else
		{
			echo('Can\'t connect to database.');
		}
	}
	else
	{
		echo('Passwords do not match');
	}
}


function check_pw($pw1, $pw2)
{
	if($pw1 == $pw2)
	{
		return true;
	}
	return false;
}

function register($link, $email, $pw)
{
	$query = 'SELECT email FROM korisnik';
	$result = mysqli_query($link, $query);
	if(!$result)
	{
		echo('error with query');
	}
	else
	{
		while($row = mysqli_fetch_array($result))
		{
			if($row['email'] == $email)
			{
				echo('This email is already registered.');
				return false;
			}
		}
	}
	$query = "INSERT INTO `korisnik` (`id_korisnik`, `email`, `password`, `razina_ovlasti`) VALUES (NULL, '".$email."', '".$pw."', 0);";
	$result = mysqli_query($link, $query);
	if(!$result)
	{
		echo('Error with registring');
	}
	else
	{
		//echo("Successfully registered, <a href='login.php'>log in</a>");
		echo("Automatsko logiranje, bit ćete preusmjereni na početnu stranicu");
		//TODO: these 4 lines below are the same as 4 lines when user is logged in
		$_SESSION['loggedIn'] = true;
		$_SESSION['email'] = $_POST['email'];
		$_SESSION['userId'] = getUserId();
		arhivirajLogin($_SESSION['userId']);
		header("Location: /RWA_ducani/index.php");
	}
}

?>


