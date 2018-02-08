<?php
include_once 'php/header.php';
include_once 'php/connectionToDB.php';
include_once 'php/ducanFunctions.php';
include_once 'php/deleteFilter.php';

if (session_status() == PHP_SESSION_NONE)
{
    session_start();
}

if(isGuest() || getUserObject($_SESSION['userId'])->razinaOvlasti == 0)
{
	header("Location: /RWA_ducani/index.php");
}

if(isset($_GET['noviTip']))
{
	if(dodajTip($_GET['noviTip']))
	{
		header("Location: /RWA_ducani/listaFiltera.php");
	}
	else
	{
		echo 'its a no no';
	}
}

if(isset($_GET['novaVrsta']))
{
	if(dodajVrstu($_GET['novaVrsta']))
	{
		header("Location: /RWA_ducani/listaFiltera.php");
	}
	else
	{
		echo "rest in pepperoni";
	}
}

?>

<!--<form action='dodajDucan.php' method='get'>
	Ime filtera: 
	<input type='text' name='noviTip'>
	<input type='submit' value='Dodaj tip dućana'/><br>
</form>
<form action='dodajDucan.php' method='get'>
	Ime filtera: 
	<input type='text' name='novaVrsta'>
	<input type='submit' value='Dodaj vrstu dućana'/><br>
</form>-->

<div class="row">
	<section id="filter-Form">
	<div class="col-md-6">
		<form action='listaFiltera.php' method='get'>
				<label>Novi tip: </label>
				<input type='text' name='noviTip' 
				>
				<input type='submit' class="btn btn-xxl btn-yellow" value='Dodaj tip'>
		</form>
		<form action='listaFiltera.php' method='get'>
				<label>Nova vrsta: </label>
				<input type='text' name='novaVrsta' 
				>
				<input type='submit' class="btn btn-xxl btn-yellow" value='Dodaj vrstu'>
		</form>
	</div>	
	<div class="col-md-1">
	</div>
	</section>	
</div>

<?php

if(isset($_GET['delTip']))
{
	if(!deleteTip($_GET['delTip']))
	{
		echo '
		<br><div class="alert alert-warning">
		  <strong>Upozorenje!</strong> Ne mogu izbrisati filter koji je korišten kod nekog od dućana.
		</div>
		';
	}
	else
	{
		header("Location: /RWA_ducani/listaFiltera.php");
	}
}

if(isset($_GET['delVrsta']))
{
	if(!deleteVrsta($_GET['delVrsta']))
	{
		echo '
		<br><div class="alert alert-warning">
		  <strong>Upozorenje!</strong> Ne mogu izbrisati filter koji je korišten kod nekog od dućana.
		</div>
		';
	}
	else
	{
		header("Location: /RWA_ducani/listaFiltera.php");
	}
}

?>

<section id="tablica">
	<div class="table-responsive">
		<table class="table table-striped">
			<tr>
				<th>Vrsta filtera</th>
				<th>Ime filtera</th>
				<th>Izbriši</th>
			</tr>
	<?php

	$tipovi = getTipovi();
	$vrste = getVrste();
	foreach($vrste as $vrsta)
	{
		echo 	"<tr><td>Vrsta dućana
				 </td><td>".$vrsta.
				"</td><td><a href='listaFiltera.php?delVrsta=".$vrsta."'>Izbriši filter</a></td></tr>";
	}
	foreach($tipovi as $tip)
	{
		echo 	"<tr><td>Tip dućana
				 </td><td>".$tip.
				"</td><td><a href='listaFiltera.php?delTip=".$tip."'>Izbriši filter</a></td></tr>";
	}
	/*foreach($ducani as $ducan)
	{
		echo 	"<tr><td>"..
				"</td><td>"..
				"</td><td>"..
				"</td></tr>";
	}*/	
	?>
		</table>
	</div>
</section>

<?php
include_once 'php/footer.php';
?>