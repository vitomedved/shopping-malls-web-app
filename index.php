<!DOCTYPE html>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Ocjenjivanje dućana</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
</head>
<body>
    <div class="container">
    <?php
    
    //include "menu.html";
	include 'userFunctions.php';
	
	session_start();
	
	if(isset($_SESSION['loggedIn']) && ($_SESSION['loggedIn'] == true))
	{
		echo("<a href='logout.php'>LOGOUT</a> or <a href='deleteAccount.php'>DELETE ACCOUNT</a>");
		echo("<br>Your email is: ".$_SESSION['email'].", and id: ".$_SESSION['userId']);
		echo("<br><a href='podatak.php'> Click here to hange your data</a><br>");
		if(isAdmin($_SESSION['userId']))
		{
			echo("Vi ste admin, <a href='dodajDucan.php'>dodaj dućan</a><br>");
		}
		else
		{
			echo("Vi ste pleb");
		}
	}
	else
	{
		echo("<a href='login.php'> LOGIN </a> or <a href='register.php'>REGISTER</a>");
	}
	
    ?>
	
        <header class="jumbotron">
            <div class="container">
                <h1>Dobrodošli, ocjenite dućan!</h1>
                <p>Pregledaj dućane</p>
                <p>
                    <a class="btn btn-lg btn-primary" href="ducani.php">Pretraži sve dućane</a>
                </p>
            </div>
        </header>

        <div class="text-center">
            <h3>Najbolje ocjenjeni dućani!</h3>
        </div>

        <div class="row text-center" style="display:flex; flex-wrap: wrap;">
            <div class="col-md-3 col-sm-6">
                <div class="card">
                    <img class="img-fluid" src="http://static.panoramio.com/photos/large/23030867.jpg">
                    <div class="caption">
                        <h4>Ime dućana</h4>
                    </div>
                    <p>
                        <a href="ducan.php?id=1" class="btn btn-primary">Pogledaj komentare</a>
                    </p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card">
                    <img class="img-fluid" src="http://static.panoramio.com/photos/large/23030867.jpg">
                    <div class="caption">
                        <h4>Ime dućana</h4>
                    </div>
                    <p>
                        <a href="#" class="btn btn-primary">Pogledaj komentare</a>
                    </p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card">
                    <img class="img-fluid" src="http://static.panoramio.com/photos/large/23030867.jpg">
                    <div class="caption">
                        <h4>Ime dućana</h4>
                    </div>
                    <p>
                        <a href="#" class="btn btn-primary">Pogledaj komentare</a>
                    </p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card">
                    <img class="img-fluid" src="http://static.panoramio.com/photos/large/23030867.jpg">
                    <div class="caption">
                        <h4>Ime dućana</h4>
                    </div>
                    <p>
                        <a href="#" class="btn btn-primary">Pogledaj komentare</a>
                    </p>
                </div>
            </div>

        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
</body>
</html>