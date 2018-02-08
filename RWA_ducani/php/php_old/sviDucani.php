<?php include_once 'php/header.php'; ?>

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

				include_once 'userClass.php';

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
					echo "<h3>";
					echo "Ime dućana: ".$ducan->ime."</br></h3>".
					"<img class='store-img' src='" . $ducan->urlSlike ."' >
					<h5> Tip dućana: ".$ducan->tip.
					"</br> Vrsta dućana: ".$ducan->vrsta.
					"</br> Srednja ocjena: ".$ducan->ocjena.
					"</br><a href='ducanProfil.php?id=".$ducan->id."'>Pogledaj dućan</a> | <a href='ducan.php?id=".$ducan->id."'>Pogledaj komentare</a>";
					

					echo " | <a href='urediDucan.php?id=".$ducan->id."'>Uredi ducan</a>";

					echo "</h5>";
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
