
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
            <li class="active"><a href="#">Početna</a></li>
            <li><a href="#about">O nama</a></li>
            <li><a href="#contact">Kontakt</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li><a href="#contact">Registracija</a></li>
            <li><a href="#contact">Prijava</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div class="jumbotron">
      <div class="container">
        <p></p>
        <div class="row">
          <div class="col-md-6">
            <h1>Kupac <span class="em-text">life</span></h1>
            <p>Iskoristi tuđa iskustva kako bi donio ispravnu odluku o svojoj idućoj kupnji ili pomogni drugima da odluče gdje potrošiti svoj novac.</p>
          </div>
          <div class="col-md-6">
          </div>
        </div>
      </div>
    </div>

    <section id="middle">
      <div id="container">
        <div class="row">
          <div class="col-md-4">
            <img src="img/demo1.jpg" class="demo">
            <h2 class="em-text">Kupuj</h2>
            <h4>Odlučite gdje kupovati, s lakoćom pretražite iskustva drugih te donesite objektivne odluke o tome gdje (i još važnije, gdje ne!) potrošiti svoj novac.</h4>
          </div>
          <div class="col-md-4">
            <img src="img/demo2.png" class="demo">
            <h2 class="em-text">Doživi</h2>
            <h4>Kako je prošla tvoja kupnja? Slaže li se tvoje iskustvo sa iskustvima ostalih? Što bi vi htjeli vidjeti u dućanima kako bi vaše iskustvo kupovanja bilo što ugodnije?</h4>
          </div>
          <div class="col-md-4">
            <img src="img/demo3.jpg" class="demo">
            <h2 class="em-text">Ocijeni</h2>
            <h4>Želite da se vaš glas čuje? Odlično, dajte do znanja svima kako je vaša kupnja prošla te ostavite trajan dojam na trgovinu.</h4>
          </div>
        </div>
      </div>
    </section>

    <section id="php">
      <div class='container'>
		<div class='row'>
			<?php
			include_once 'php/connectionToDB.php';
			include_once 'php/ducanFunctions.php';
			
			$topRatedDucani = getTopRatedStores();
			
			foreach($topRatedDucani as $ducan)
			{
			echo "
				<div class=\"col-md-3\">
				<img style='height:100%;' src='".$ducan->urlSlike."' class='demo'>
				<h2 class='em-text'>".$ducan->ime."</h2>
				<h4>Prosječna ocjena: ".$ducan->ocjena."</h4>
				</div>";
			}
			?>
          
		</div>
	  </div>
    </section>

    <section id="feature">
      <div class="container">
        <div class="row">
          <div class="col-md-6">
          <h2>Naši imperativi</h2>
          <ul>
            <li> <i class="glyphicon glyphicon-ok
"></i> Pouzdanost </li>
            <li> <i class="glyphicon glyphicon-ok
"></i> Preglednost</li>
            <li> <i class="glyphicon glyphicon-ok
"></i> Brzina</li>
            <li> <i class="glyphicon glyphicon-ok
"></i> Obostrana korist</li>
            <li> <i class="glyphicon glyphicon-ok
"></i> Brz i lagan pristup informacijama</li>
          </ul>
          </div>

          <div class="col-md-4 col-md-offset-2">
           <img class="big-logo" src="img/logo.png">
          </div>
        </div>
      </div>
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
