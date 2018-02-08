
<!DOCTYPE html>
<html lang="en">
  <head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Kupac Life: aplikacija za kupovinu</title>
    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <!-- Custom styles for this template -->
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    </head>

  <body>

<?php

include_once 'php/userFunctions.php';

function printRightNav()
{
	if(isset($_SESSION['loggedIn']) && ($_SESSION['loggedIn'] == true))
	{
		$user = getUserObject($_SESSION['userId']);
		echo'
		<li class="dropdown">
			<a class="dropdown-toggle" data-toggle="dropdown" href="#">
				';
				if($_SESSION['newNotifications'] != 0)
				{
					$ducanId = getDucanId($user->najDucan);
					echo "<i class='material-icons' style='font-size:15px;color:red'>priority_high</i>";
				}
				
				echo'
			Izbornik
				<span class="caret"></span>
				
			</a>
			<ul class="dropdown-menu">';
			
		if($_SESSION['newNotifications'] != 0)
		{
			$ducanId = getDucanId($user->najDucan);
			echo "<li><a href='ducan.php?id=".$ducanId."'>".$_SESSION['newNotifications']." novih komentara na vaš najdraži dućan </a></li>";
		}
		
		if(getUserObject($_SESSION['userId'])->razinaOvlasti)
		{
			echo "<li><a href='listaFiltera.php'>Lista svih filtera</a></li>
				  <li><a href='listaKorisnika.php'>Lista registriranih korisnika</a></li>
				  <li><a href='listaDucana.php'>Lista Dućana</a></li>";
		}
		
		echo '<li><a href="userProfil.php?id='.$_SESSION['userId'].'">Pogledaj profil</a></li>
			  <li><a href="podatak.php?id='.$_SESSION['userId'].'">Uredi profil</a></li>
			  <li><a href="php/deleteAccount.php">Izbrisi profil</a></li>
			  <li><a href="php/logout.php">Log out</a></li>
			</ul>
		  </li>';
		  /*<li><a href=''>Dobrodosli, ".$user->ime."</a></li>
			<li><a href='php/logout.php'>Log out</a></li>
			<li><a href='php/deleteAccount.php'>Izbrisi profil</a></li>
			<li><a href='podatak.php?id=".$_SESSION['userId']."'>Uredi profil</a></li>
			<li><a href='userProfil.php?id=".$_SESSION['userId']."'>Pogledaj profil</a></li>
			";*/
		
		if(getUserObject($_SESSION['userId'])->razinaOvlasti)
		{
			echo "<li><a href='dodajDucan.php'>Dodaj dućan</a></li>";
		}
	}
	else
	{
		echo"
			<li><a href='register.php'>Registracija</a></li>
			<li><a href='login.php'>Prijava</a></li>
			";
	}	
}

function getEndLink()
{
	$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

	$getpath = explode("/",$actual_link);
	
	return end($getpath);
}

if (session_status() == PHP_SESSION_NONE)
{
	session_start();
}

?>
<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container">
	<div class="navbar-header">
	  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
		<span class="sr-only">Toggle navigation</span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
	  </button>
	  <a class="navbar-brand em-text" href="index.php">Kupac Life</a>
	</div>
	<div id="navbar" class="collapse navbar-collapse">
	  <ul class="nav navbar-nav">
		<li <?php
				if(getEndLink() == 'index.php') echo "class='active'";
			?>><a href="index.php">Početna</a></li>
		<li <?php
				if(getEndLink() == 'sviDucani.php') echo "class='active'";
			?>><a href="sviDucani.php">Pretraži dućane</a></li>
		<li <?php
				if(getEndLink() == 'about.php') echo "class='active'";
			?>><a href="about.php">O nama</a></li>
		<!--<li <?php
				//if(getEndLink() == 'contact.php') echo "class='active'";
			?>><a href="contact.php">Kontakt</a></li>-->
	  </ul>
	  <ul class="nav navbar-nav navbar-right">
		<?php printRightNav(); ?>
	  </ul>
	</div><!--/.nav-collapse -->
  </div>
</nav>