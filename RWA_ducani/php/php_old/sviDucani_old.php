<!DOCTYPE html>
<html lang="en">
  <head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Kupac Life: Kontakt</title>
    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet">

    </head>

  <body>

    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand em-text" href="#">Kupac Life</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li><a href="index.html">Početna</a></li>
            <li class="active"><a href="contact.html">Pretraži dućane</a></li>
            <li><a href="about.html">O nama</a></li>
            <li><a href="contact.html">Kontakt</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li><a href="#contact">Registracija</a></li>
            <li><a href="#contact">Prijava</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <section id="title-bar">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <h1>Pretraga po dućanima</h1>
          </div>
        </div>
      </div>     
    </section>

    <section id="contact">
      <div id="container">
        <div class="row">
            <?php

				include 'userClass.php';

				if (session_status() == PHP_SESSION_NONE)
				{
				    session_start();
				}

				include 'connectionToDB.php';
				//include 'userFunctions.php';
				include 'ducanFunctions.php';

				$ducaniArray = getDucaniArray();

				//isprintaj sve ducane
				foreach($ducaniArray as $ducan)
				{
					echo "<div class='col-md-4'>";
					echo "<h2>";
					echo "Ime dućana: ".$ducan->ime."</br></h2><h3> Tip dućana: ".$ducan->tip."</br> Vrsta dućana: ".$ducan->vrsta."</br> Srednja ocjena: ".$ducan->ocjena."</br>
					<a href='ducanProfil.php?id=".$ducan->id."'>Pogledaj dućan</a> | <a href='ducan.php?id=".$ducan->id."'>Pogledaj komentare</a>";
					
					if(isset($_SESSION['user']) && ($_SESSION['user']->razinaOvlasti == 1))
					{
						echo " | <a href='urediDucan.php?id=".$ducan->id."'>Uredi ducan</a>";
					}
					echo "</h3>";
					echo "<br>";
					echo "</div>";
				}
				?>
        </div>
      </div>
    </section>

    <section id="php">
      
    </section>
    
    <footer>
      <div class="container">
        <div class="row">
          <div class="col-md-6">
            <ul>
              <li><a href="#">Početna</a></li>
              <li><a href="#">O nama</a></li>
              <li><a href="#">Kontakt</a></li>
            </ul>
          </div>
          <div>
            <p>Copyright &copy; 2018; Makar & Medved</p>
          </div>
        </div>

      </div>
    </footer>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
    <script src="/js/bootstrap.js"></script>
  </body>
</html>
