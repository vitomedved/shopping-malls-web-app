
	<?php include_once 'php/header.php';?>
  
	
  
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
            <h4>Odlućite gdje kupovati, s lakoćom pretražite iskustva drugih te donesite objektivne odluke o tome gdje (i još važnije, gdje ne!) potrošiti svoj novac.</h4>
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
			
      echo "<div class=\"row\" >";
			foreach($topRatedDucani as $ducan)
			{
			echo "
				<div class=\"col-md-3\">
				    <img style='height:100%;' src='".$ducan->urlSlike."' class='demo'>
				</div>";
			}
      echo "</div>";

      foreach($topRatedDucani as $ducan)
      {
      echo "
        <div class=\"col-md-3\">
            <a href='ducan.php?id=".$ducan->id."'><h2 class='em-text-center'>".$ducan->ime."</h2></a>
            <h4 class='sm-text-center'>Prosječna ocjena: ".$ducan->ocjena." (".getNumOfComments($ducan->id).")</h4>
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

<?php include_once 'php/footer.php'; ?>
