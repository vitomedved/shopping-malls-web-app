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

echo "Pretraži po filteru/ima: 
	<form action='sviDucani.php'>
		Tip dućana: 
	<select name='filterTip'>
		<option value='none'>Odaberi tip...</option>
		<option value='odjeca'>Odjeća</option>
		<option value='pokloni'>Pokloni</option>
		<option value='sport'>Športska oprema</option>
		<option value='obuca'>Obuća</option>
		<option value='prehrana'>Prehrana</option>
		<option value='namjestaj'>Namještaj</option>
		<option value='igracke'>Igračke</option>
		<option value='tehnika'>Tehnička roba</option>
	</select><br>
	Vrsta dućana: 
	<select name='filterVrsta' value='trgovina'>
	<option value='none'>Odaberi vrstu...</option>
		<option value='tvornica'>Tvornička prodaja</option>
		<option value='supermarket'>Supermarket</option>
		<option value='trgovina'>Trgovina</option>
	</select><br>
	<input type='submit' value='Pretraži' />
	</form>
	<button onclick=\"location.href = 'sviDucani.php';\" >Izbriši filter pretraživanja</button>";

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
//isprintaj sve ducane
foreach($ducaniArray as $ducan)
{
	if($filter1 != '' && $filter2 != '')
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
	}
	
}
echo "<a href='index.php'>Povratak na pocetnu</a>";

function printDucan($ducan)
{
	echo "ime: ".$ducan->ime.", tip: ".$ducan->tip.", vrsta: ".$ducan->vrsta.", ocjena: ".$ducan->ocjena."<br>
		<a href='ducanProfil.php?id=".$ducan->id."'>Pogledaj dućan</a> | <a href='ducan.php?id=".$ducan->id."'>Pogledaj komentare</a>";
		
		if(isset($_SESSION['user']) && ($_SESSION['user']->razinaOvlasti == 1))
		{
			echo " | <a href='urediDucan.php?id=".$ducan->id."'>Uredi ducan</a> | <a href='izbrisiDucan.php?id=".$ducan->id."'>Izbriši dućan</a>";
		}
	
		echo "<br><hr>";
}
?>