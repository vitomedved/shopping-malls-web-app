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
    include_once 'userClass.php';
	include_once 'userFunctions.php';
	include_once 'ducanFunctions.php';
	
	//session_start();
	
	if(isset($_SESSION['loggedIn']) && ($_SESSION['loggedIn'] == true))
	{
		echo("<a href='logout.php'>LOGOUT</a> or <a href='deleteAccount.php'>DELETE ACCOUNT</a>");
		echo("<br>Your email is: ".$_SESSION['user']->email.", and id: ".$_SESSION['user']->id);
		echo("<br><a href='podatak.php'> Click here to hange your data</a>, <a href='userProfil.php?id=".$_SESSION['user']->id."'>View profile</a><br>");
		if($_SESSION['user']->razinaOvlasti)
		{
			echo("Vi ste admin, <a href='dodajDucan.php'>dodaj dućan</a><br>");
			
		}
		else
		{
			echo("Vi ste pleb<br>");
		}
		if(isset($_SESSION['newNotifications']))
			echo $_SESSION['newNotifications']." novih komentara na vaš najdraži dućan!";
	}
	else
	{
		echo("<a href='login.php'> LOGIN </a> or <a href='register.php'>REGISTER</a>");
	}
	
	//GETS 4 TOP RATED STORES IDs
	$topRatedStores = getTopRatedStoreIDs();
	
	function getTopRatedStoreIDs()
	{
		$retArray = array();
		
		$link = connectToDB();
		if($link)
		{
			$query = "SELECT id_ducan, ime, tip_ducana, vrsta_ducana, IFNULL(SUM(vrijednost)/COUNT(vrijednost), 0) as prosjek FROM ducan LEFT JOIN ocjena USING (id_ducan) GROUP BY id_ducan ORDER BY prosjek DESC LIMIT 4";
			$result = mysqli_query($link, $query);
			if($result)
			{
				while($row = mysqli_fetch_array($result))
				{
					$retArray[] = new ducan($row['id_ducan'], $row['ime'], $row['tip_ducana'], $row['vrsta_ducana'], $row['prosjek']);
				}
			}
		}
		return $retArray;
	}
	
    ?>
	
        <header class="jumbotron">
            <div class="container">
                <h1>Dobrodošli, ocjenite dućan!</h1>
                <p>Pregledaj dućane</p>
                <p>
                    <a class="btn btn-lg btn-primary" href="sviDucani.php">Pretraži sve dućane</a>
                </p>
            </div>
        </header>

        <div class="text-center">
            <h3>Najbolje ocjenjeni dućani!</h3>
        </div>

        <div class="row text-center" style="display:flex; flex-wrap: wrap;">
            <?php
			
			foreach($topRatedStores as $ducan)
			{
				echo "<div class=\"col-md-3 col-sm-6\">
                <div class=\"card\">
                    <img class=\"img-fluid\" src=\"http://static.panoramio.com/photos/large/23030867.jpg\">
                    <div class=\"caption\">
                        <h5>Ime dućana: ".$ducan->ime.", ocjena: ".$ducan->ocjena."</h4>
                    </div>
                    <p>
                        <a href=\"ducan.php?id=".$ducan->id."\" class=\"btn btn-primary\">Pogledaj komentare</a>
                    </p>
					</div>
				</div>";
			}
			
			?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
</body>
</html>