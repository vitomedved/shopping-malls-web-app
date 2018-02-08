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

				include_once 'php/userClass.php';
				//include_once 'php/connectionToDB.php';
				include_once 'php/ducanFunctions.php';
				include_once 'php/userFunctions.php';
				
				$user = new user();
				
				if(isset($_SESSION['userId']))
				{
					$user = getUserObject($_SESSION['userId']);
				}
				
				//default page
				$page = 0;
				
				//max po stranici
				$max = 6;
				
				
				$filter1 = '';
				$filter2 = '';
				
				if(isset($_GET['filterTip']) || isset($_GET['filterVrsta']))
				{
					$tip = $_GET['filterTip'];
					$vrsta = $_GET['filterVrsta'];
					if($tip != 'none')
					{
						$filter1 = $_GET['filterTip'];
					}
					if($vrsta != 'none')
					{
						$filter2 = $_GET['filterVrsta'];
					}
				}
				
				
				//ukupno zapisa
				$total = getNumOfResults($filter1, $filter2);
				
				//koliko bude to ukupno stranica
				$pages = ceil($total / $max);
				
				if(!isset($_GET['page']) || $_GET['page'] == 1)
				{
					$page = 0;
				}
				else if($_GET['page'] <= $pages)
				{
					$page = ($_GET['page'] * $max) - $max;
				}
				else
				{
					header("Location: /RWA_ducani/sviDucani.php");
				}
				
				$ducaniArray = getDucaniArray($page, $max, $filter1, $filter2);
				
				//$ducan = new ducan(-1, '', '', '', -1, '');
				
				echo "Pretraži po filteru/ima: 
					<form class='form-inline' action='sviDucani.php'>	
					<div class='form-group'>
						Tip dućana:
						<select name='filterTip'>";
							/*<option value='none'>Odaberi tip...</option>
							<option value='odjeca'>Odjeća</option>
							<option value='pokloni'>Pokloni</option>
							<option value='sport'>Športska oprema</option>
							<option value='obuca'>Obuća</option>
							<option value='prehrana'>Prehrana</option>
							<option value='namjestaj'>Namještaj</option>
							<option value='igracke'>Igračke</option>
							<option value='tehnika'>Tehnička roba</option>*/
							foreach(/*$ducan->tipovi*/ getTipovi() as $key => $t)
							{
								if(isset($_GET['filterTip']) && $_GET['filterTip'] == $key)
								{
									echo "<option value='".$key."' selected>".$t."</option>";
								}
								else
								{
									echo "<option value='".$key."'>".$t."</option>";
								}
							}
							
							
				echo	"</select>
					</div>
					<div class='form-group'>
						Vrsta dućana: 
						<select name='filterVrsta' value='trgovina'>";
							/*<option value='none'>Odaberi vrstu...</option>
							<option value='tvornica'>Tvornička prodaja</option>
							<option value='supermarket'>Supermarket</option>
							<option value='trgovina'>Trgovina</option>*/
							
							foreach(/*$ducan->vrste*/getVrste() as $key => $t)
							{
								if(isset($_GET['filterVrsta']) && $_GET['filterVrsta'] == $key)
								{
									echo "<option value='".$key."' selected>".$t."</option>";
								}
								else
								{
									echo "<option value='".$key."'>".$t."</option>";
								}
							}
							
				echo	"</select>
					</div>
					<input class='btn btn-xxl btn-yellow' type='submit' value='Pretraži' />";
					if(isset($_GET['page']))
					{
						echo "<input type='hidden' name='page' value='".$_GET['page']."'>";
					}
				echo	"				
					</form>
					<button class='btn btn-xxl btn-yellow' onclick=\"location.href = 'sviDucani.php';\" >Izbriši filter pretraživanja</button><br>";
				

				echo "<div class = 'col-md-12 col-centered'>";	
				foreach($ducaniArray as $ducan)
				{
					printDucan($ducan);
					//echo getVrste()[$ducan->vrsta];
					/*if($filter1 != '' && $filter2 != '')
					{
						if($ducan->tip == $filter1 && $ducan->vrsta == $filter2)
						{
							printDucan($ducan);
						}
					}
					else if($filter1 != '')
					{
						if($ducan->tip == $filter1)
						{
							printDucan($ducan);
						}
					}
					else if($filter2 != '')
					{
						if($ducan->vrsta == $filter2)
						{
							printDucan($ducan);
						}
					}
					else
					{
						printDucan($ducan);
					}*/
				}
				echo "</div>";
				
				//isprintaj sve ducane
				/*foreach($ducaniArray as $ducan)
				{
					echo "<div class='col-md-4'>";
					echo "<h3>";
					echo "<a href='ducan.php?id=".$ducan->id."'>".$ducan->ime."</a></br></h3>".
					"<img class='store-img' src='" . $ducan->urlSlike ."' >
					<h5> Tip dućana: ".$ducan->tip.
					"</br> Vrsta dućana: ".$ducan->vrsta.
					"</br> Srednja ocjena: ".$ducan->ocjena.
					"</br><a href='ducan.php?id=".$ducan->id."'>Pogledaj komentare</a>";
					
					if($user->razinaOvlasti == 1)
					{
						echo " | <a href='urediDucan.php?id=".$ducan->id."'>Uredi ducan</a>";
					}
					echo "</h5>";
					echo "<br>";
					echo "</div>";
				}*/				
				?>
        </div>
		<div class='row'>
			<div class='col-md-5'>
			</div>
			<div class='col-md-2'>

			    <nav aria-label="...">	
				  <ul class="pagination pagination-lg">
				<?php
				
				for($i = 1; $i <= $pages; $i++)
				{
					echo '<li class="page-item"> <a class="page-link" href="sviDucani.php?page=' . $i;
					
					if(!empty($tip) && !empty($vrsta))
					{
						echo "&filterTip=".$filter1."&filterVrsta=".$filter2;
					}
					else if(!empty($tip))
					{
						echo "&filterTip=".$filter1."&filterVrsta=none";
					}
					else if(!empty($vrsta))
					{
						echo "&filterTip=none&filterVrsta=".$filter2;
					}					
					
					echo '">'.$i.'</a></li>';
				}
				
				?>
					  </ul>
				</nav>		
			</div>
		</div>
      </div>
    </section>

    <section id="php">
      
    </section>
 
 <?php include_once 'php/footer.php';?>