<?php

include_once 'php/header.php';
include_once 'php/ducanFunctions.php';

if (session_status() == PHP_SESSION_NONE)
{
    session_start();
}

if(isGuest() || getUserObject($_SESSION['userId'])->razinaOvlasti == 0)
{
	header("Location: /RWA_ducani/index.php");
}

$searchParameterArray = array('Ime', 'Tip', 'Vrsta');

?>
<div class="row">
	<section id="filter-Form">
	<div class="col-md-6">
		<form action='listaDucana.php' method='get'>
				<div class="col-md-2">
					<h4>Pretraži po: </h4>
				</div>

				<div class="col-md-4">
					<select class="form-control btn-new" name='searchBy'>
						<!--<option>Ime</option>
						<option>Prezime</option>
						<option>Email</option>-->
						<?php
						
						foreach($searchParameterArray as $opt)
						{
							if(isset($_GET['searchBy']) && ($opt == $_GET['searchBy']))
							{
								echo "<option selected>".$opt."</option>";
							}
							else
							{
								echo "<option>".$opt."</option>";
							}
						}
						if($_GET['searchBy'] && !strcmp($_GET['searchBy'], "Ocjena"))
						{
							echo "<option selected>Ocjena</option>";
							
						}
						else
						{
							echo "<option>Ocjena</option>";
						}
						
						if($_GET['searchBy'] && !strcmp($_GET['searchBy'], "Komentari"))
						{
							echo "<option selected>Komentari</option>";
						}
						else
						{
							echo "<option>Komentari</option>";
						}
						?>
					</select>
				</div>
				<input type='text' class="myInput" name='keyword' 
					<?php
						if(isset($_GET['keyword']))
						{
							echo "value='".$_GET['keyword']."'";
						}
					?>	
				>
				<input type='submit' class="btn btn-xxl btn-yellow" value='Pretraga'>
		</form>
	</div>	
	<div class="col-md-1">
			<button class='btn btn-xxl btn-yellow' onclick="location.href = 'listaDucana.php';" >Izbriši filter pretraživanja</button>
	</div>
	</section>	
</div>
	<section id="tablica">
		<div class="table-responsive">
  			<table class="table table-striped">
  				<tr>
  					<th>Ime</th>
  					<th>Tip dućana</th>
  					<th>Vrsta dućana</th>
  					<th>Ocjena</th>
  					<th>Ukupno komentara</th>
  					<th>Pregledaj</th>
  					<th>Uredi</th>
  				</tr>
		<?php

		$searchParameter = '';
		$keyword = '';

		if(isset($_GET['keyword']))
		{
			$searchParameter = $_GET['searchBy'];
			$keyword = $_GET['keyword'];
		}

		$ducani = getAllDucani($searchParameter, $keyword);

		if(!$ducani)
		{
			echo "<br>Nema korisnika za traženi filtar.";
		}
		else
		{
			foreach($ducani as $ducan)
			{
				$brKomentara = getNumOfCommentsForDucan($ducan->id);
				echo 	"<tr><td>".$ducan->ime.
						"</td><td>".$ducan->tip.
						"</td><td>".$ducan->vrsta.
						"</td><td>".$ducan->ocjena.
						"</td><td>".$brKomentara.
						"</td><td><a href='ducan.php?id=".$ducan->id.
						"'>Polukni samo</a> </td><td> <a href='urediDucan.php?id=".$ducan->id.
						"'>Uredi</a>".
						"</tr>";
			}	
		}
		?>
			</table>
		</div>
	</section>

<?php
include_once 'php/footer.php';
?>