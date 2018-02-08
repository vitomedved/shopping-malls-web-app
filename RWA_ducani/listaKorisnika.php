<?php

include_once 'php/header.php';

if (session_status() == PHP_SESSION_NONE)
{
    session_start();
}

if(isGuest() || getUserObject($_SESSION['userId'])->razinaOvlasti == 0)
{
	header("Location: /RWA_ducani/index.php");
}

$searchParameterArray = array('Ime', 'Prezime', 'Email');

?>
<div class="row">
	<section id="filter-Form">
		<div class="col-md-6">
			<form action='listaKorisnika.php' method='get'>
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
				<input type='checkbox' name='admin'
					<?php
					
					if(isset($_GET['admin']))
					{
						echo "checked";
					}
					
					?>
				> Admin*</input>
				<input type='submit' class="btn btn-xxl btn-yellow" value='Pretraga' style="margin-left: 10px;">
			</form>
		</div>
		<div class="col-md-2">
			<button class="btn btn-xxl btn-yellow" onclick="location.href = 'listaKorisnika.php';" >Izbriši filter pretraživanja</button>
		</div>
	</section>
</div>

<section id="tablica">
		<div class="table-responsive">
  			<table class="table table-striped">
  				<tr>
  					<th>Ime</th>
  					<th>Prezime</th>
  					<th>E-mail</th>
  					<th>Profil</th>
  					<th>Administratorske privilegije</th>
  				</tr>

	<?php

	$searchParameter = '';
	$keyword = '';
	$isAdmin = false;

	if(isset($_GET['keyword']))
	{
		$admin = false;
		if(isset($_GET['admin']))
		{
			$admin = true;
		}
		$searchParameter = $_GET['searchBy'];
		$keyword = $_GET['keyword'];
		$isAdmin = $admin;
	}

	$users = getAllUsers($searchParameter, $keyword, $isAdmin);

	if($users == -1)
	{
		echo "<br>Nema korisnika za traženi filtar.";
	}
	else
	{
		foreach($users as $user)
		{
			echo 	"<tr><td>".$user->ime.
					"</td><td>".$user->prezime.
					"</td><td>".$user->email.
					"</td><td> <a href='userProfil.php?id="
					.$user->id.
					"'>Pogledaj profil</a>";
			if($user->razinaOvlasti == 1)
			{
				echo "</td><td>DA</td></tr>";
			}else{
				echo "</td><td>NE</td></tr>";
			}
		}	
	}
	?>
	</table>
	</div>
	</section>
<?php
include_once 'php/footer.php';
?>