<?php 

include_once 'connectionToDB.php';
include_once 'arhivaLogiranja.php';
include_once 'userFunctions.php';
//include 'login.php';


if (session_status() == PHP_SESSION_NONE)
{
    session_start();
}

$email = '';

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
	if($_POST['password'] == $_POST['repeat_password'])
	{
		$registered = userExist($_POST['email'], $_POST['password']);
		if($registered)
		{
			echo('This email is already registered.');
		}
		else
		{
			if(newUser($_POST['email'], $_POST['password']))
			{
				header("Location: /RWA_ducani/index.php");
			}
			else
				echo("Ne mogu te registrirati");
		}
	}
	else
	{
		echo('Passwords do not match');
	}
}


/*function check_pw($pw1, $pw2)
{
	if($pw1 == $pw2)
	{
		return true;
	}
	return false;
}*/

function userExist($email, $pw)
{
	$ret = false;
	$link = connectToDB();
	if($link)
	{
		$query = 'SELECT email FROM korisnik';
		$result = mysqli_query($link, $query);
		if($result)
		{
			while($row = mysqli_fetch_array($result))
			{
				if($row['email'] == $email)
				{
					$ret = true;
				}
			}
		}
		mysqli_close($link);
	}
	return $ret;
}

function newUser($email, $pw)
{
	$link = connectToDB();
	if($link)
	{
		$query = "INSERT INTO `korisnik` (`id_korisnik`, `email`, `password`, `razina_ovlasti`) VALUES (NULL, '".$email."', '".$pw."', 0);";
		$result = mysqli_query($link, $query);
		if($result)
		{
			mysqli_close($link);
			logIn($_POST['email']);
			$ret = true;
		}
		mysqli_close($link);
	}
	return $ret;
}

?>


