<?php

include_once 'adresaFunctions.php';
include_once 'komentarFunctions.php';

function getTopRatedStores()
	{
		$retArray = array();
		
		$link = connectToDB();
		if($link)
		{
			$query = "SELECT slika, id_ducan, ime, tip_ducana, vrsta_ducana, IFNULL(SUM(vrijednost)/COUNT(vrijednost), 0) as prosjek FROM ducan LEFT JOIN ocjena USING (id_ducan) GROUP BY id_ducan ORDER BY prosjek DESC LIMIT 4";
			$result = mysqli_query($link, $query);
			if($result)
			{
				while($row = mysqli_fetch_array($result))
				{
					$retArray[] = new ducan($row['id_ducan'], $row['ime'], $row['tip_ducana'], $row['vrsta_ducana'], round($row['prosjek'], 2), $row['slika']);
				}
			}
		}
		return $retArray;
	}

//vraca prosjecnu ocjenu ducana. ako je greska vratit ce -1 (ili 0?)
function getRating($ducanId)
{
	$sumaOcjena = 0;
	$count = 0.0;
	$retVal = -1;
	
	$link = connectToDB();
	
	$query = "SELECT vrijednost FROM ocjena WHERE id_ducan=".$ducanId.";";
	
	if($link)
	{
		$result = mysqli_query($link, $query);
		if($result)
		{
			while($row = mysqli_fetch_array($result))
			{
				$sumaOcjena += $row['vrijednost'];
				$count += 1.0;
			}
		}
		
		if($count > 0)
		{
			$retVal = round(($sumaOcjena / $count), 2);
		}
		else
		{
			$retVal = 0;
		}
	}
	mysqli_close($link);
	return $retVal;
}

//Provjerava ako je korisnik već glasao na odredeni ducan
//vraca true/false
function ratedOnThisStore($userId, $ducanId)
{
	$query = "SELECT id_korisnik FROM ocjena WHERE id_ducan=".$ducanId.";";
	$retVal = true;
	$link = connectToDB();
	
	if($link)
	{
		$result = mysqli_query($link, $query);
		if($result)
		{
			while($row = mysqli_fetch_array($result))
			{
				if($row['id_korisnik'] == $userId)
				{
					$retVal = false;
					break;
				}
			}
		}
	}
	mysqli_close($link);
	return $retVal;
}

//Getter za ime ducana
function getDucanName($ducanId)
{
	$link = connectToDB();
	$query = "SELECT ime, id_ducan FROM ducan";
	$result = mysqli_query($link, $query);
	if($result)
	{
		while($row = mysqli_fetch_array($result))
		{
			if($row['id_ducan'] == $ducanId)
			{
				$ime = $row['ime'];
				mysqli_close($link);
				return $ime;
			}
		}
	}
	mysqli_close($link);
	return 'Greska u trazenju imena: linija 45, ducan.php';
}

//Provjerava postoji li ducan s tim id-om u bazi
//vraca true/false
function doesDucanExist($ducanId)
{
	$link = connectToDB();
	$query = "SELECT id_ducan FROM ducan";
	$result = mysqli_query($link, $query);
	if($result)
	{
		while($row = mysqli_fetch_array($result))
		{
			if($row['id_ducan'] == $ducanId)
			{
				mysqli_close($link);
				return true;
			}
		}
	}
	mysqli_close($link);
	return false;
}

//updejta novu ocjenu korisnika u bazu
function updateRating($userId, $ducanId, $ocjena)
{
	$link = connectToDB();
	if($link)
	{
		$query = "UPDATE ocjena SET vrijednost='".$ocjena."' WHERE id_korisnik='".$userId."' AND id_ducan='".$ducanId."';";
		$result = mysqli_query($link, $query);
		if(!$result)
		{
			echo("not rated");
			mysqli_close($link);
			return false;
		}
		mysqli_close($link);
		echo("Nova ocjena spremljena");
		return true;
	}
	return false;
}

//dodaje ocjenu korisnika za ducan u bazu
function newRating($userId, $ducanId, $ocjena)
{
	$link = connectToDB();
	if($link)
	{
		$query = "INSERT INTO `ocjena` (`id_ocjena`, `vrijednost`, `id_ducan`, `id_korisnik`) VALUES (NULL, '".$ocjena."', '".$ducanId."', '".$userId."');";
		$result = mysqli_query($link, $query);
		if(!$result)
		{
			echo("not rated");
			return false;
		}
		else
		{
			return true;
		}		
	}
	return false;
}

//vraca sve ducane u polju objekata ducana
function getDucaniArray($pageNum=1, $maxOnPage=1000, $tip='', $vrsta='')
{
	$link = connectToDB();
	if($link)
	{
		if(!empty($tip) && !empty($vrsta))
		{
			$query = "SELECT * FROM ducan WHERE tip_ducana='".$tip."' AND vrsta_ducana='".$vrsta."' limit ".$pageNum.", ".$maxOnPage;
		}
		else if(!empty($tip))
		{
			$query = "SELECT * FROM ducan WHERE tip_ducana='".$tip."' limit ".$pageNum.", ".$maxOnPage;
		}
		else if(!empty($vrsta))
		{
			$query = "SELECT * FROM ducan WHERE vrsta_ducana='".$vrsta."' limit ".$pageNum.", ".$maxOnPage;
		}
		else
		{
			$query = "SELECT * FROM ducan limit ".$pageNum.", ".$maxOnPage;
		}		
		$result = mysqli_query($link, $query);
		
		$ret = array();
		
		if($result)
		{
			while($row = mysqli_fetch_array($result))
			{
				/*echo("
				<div>
					ime ducana: ".$row['ime']."<br>
					tip ducana: ".$row['tip_ducana']."<br>
					vrsta ducana: ".$row['vrsta_ducana']."<br>
					".$GLOBALS['editOrShow']."
					<br><hr>
				</div>
				");*/
				$ret[] = new ducan($row['id_ducan'], $row['ime'], $row['tip_ducana'], $row['vrsta_ducana'], getRating($row['id_ducan']), $row['slika']);
			}
			//echo("<a href='index.php'>Povratak na pocetnu</a>");
		}
		mysqli_close($link);
		return $ret;
	}
}

//by search parameters
function getAllDucani($searchParameter='', $keyword='')
{
	$link = connectToDB();
	if($link)
	{
		if(!strcmp($searchParameter, 'Ime'))
		{
			$query = "SELECT * FROM ducan WHERE ime LIKE '".$keyword."%' ORDER BY ime;";
		}
		else if(!strcmp($searchParameter, 'Tip'))
		{
			$query = "SELECT * FROM ducan WHERE tip_ducana LIKE '".$keyword."%' ORDER BY tip_ducana";
		}
		else if(!strcmp($searchParameter, 'Vrsta'))
		{
			$query = "SELECT * FROM ducan WHERE vrsta_ducana LIKE '".$keyword."%' ORDER BY vrsta_ducana";
		}
		else if(!strcmp($searchParameter, 'Ocjena'))
		{
			$query = "SELECT slika, id_ducan, ime, tip_ducana, vrsta_ducana, IFNULL(SUM(vrijednost)/COUNT(vrijednost), 0) as prosjek FROM ducan LEFT JOIN ocjena USING (id_ducan) GROUP BY id_ducan ORDER BY prosjek DESC";
		}
		else if(!strcmp($searchParameter, 'Komentari'))
		{
			$query = "SELECT *, COUNT(id_komentar) as cnt FROM ducan JOIN komentar USING(id_ducan) GROUP BY id_ducan ORDER BY cnt DESC";
		}
		else
		{
			$query = "SELECT * FROM ducan";
		}
		
		$result = mysqli_query($link, $query);
		
		$ret = false;
		
		if($result)
		{
			$ret = array();
			$i = 0;
			while($row = mysqli_fetch_array($result))
			{
				$ret[] = new ducan($row['id_ducan'], $row['ime'], $row['tip_ducana'], $row['vrsta_ducana'], getRating($row['id_ducan']), $row['slika']);
				$i++;
			}
			if($i == 0)
			{
				mysqli_close($link);
				return false;
			}
		}
		mysqli_close($link);
		return $ret;
	}
}

function getDucan($ducanId)
{
	
	$link = connectToDB();
	if($link)
	{
		$query = "SELECT * FROM ducan WHERE id_ducan=".$ducanId;
		$result = mysqli_query($link, $query);
		$ret = NULL;
		
		if($result)
		{
			while($row = mysqli_fetch_array($result))
			{
				$ret = new ducan($row['id_ducan'], $row['ime'], $row['tip_ducana'], $row['vrsta_ducana'], getRating($row['id_ducan']), $row['slika']);
				$ret->adrese = getAdresses($row['id_ducan']);
			}
		}
		mysqli_close($link);
		return $ret;
	}
}

function getNumOfResults($tip, $vrsta)
{
	$link = connectToDB();
	if($link)
	{
		if(!empty($tip) && !empty($vrsta))
		{
			$query = "SELECT COUNT(*) as cnt FROM ducan WHERE tip_ducana='".$tip."' AND vrsta_ducana='".$vrsta."';";
		}
		else if(!empty($tip))
		{
			$query = "SELECT COUNT(*) as cnt FROM ducan WHERE tip_ducana='".$tip."';";
		}
		else if(!empty($vrsta))
		{
			$query = "SELECT COUNT(*) as cnt FROM ducan WHERE vrsta_ducana='".$vrsta."';";
		}
		else
		{
			$query = "SELECT COUNT(*) as cnt FROM ducan";
		}
		$result = mysqli_query($link, $query);
		$ret = NULL;
		
		if($result)
		{
			while($row = mysqli_fetch_array($result))
			{
				$ret = $row['cnt'];
			}
		}
		mysqli_close($link);
		return $ret;
	}
}

function getNumOfComments($ducanId)
{
	$link = connectToDB();
	if($link)
	{
		$query = "SELECT COUNT(*) as cnt FROM ocjena WHERE id_ducan=".$ducanId;
		$result = mysqli_query($link, $query);
		$ret = 0;
		
		if($result)
		{
			while($row = mysqli_fetch_array($result))
			{
				$ret = $row['cnt'];
			}
		}
		mysqli_close($link);
		return $ret;
	}
}

function getDucanId($name)
{
	$link = connectToDB();
	if($link)
	{
		$query = "SELECT id_ducan FROM ducan WHERE ime='".$name."';";
		$result = mysqli_query($link, $query);
		$ret = -1;
		
		if($result)
		{
			while($row = mysqli_fetch_array($result))
			{
				$ret = $row['id_ducan'];
			}
		}
		mysqli_close($link);
		return $ret;
	}
}

function updateImeDucana($ducanId, $ime)
{
	$link = connectToDB();
	if($link)
	{
		$ret = false;
		$query = "UPDATE ducan SET ime='".$ime."' WHERE id_ducan=".$ducanId;
		$result = mysqli_query($link, $query);
		if($result)
		{
			$ret = true;
		}
		mysqli_close($link);
	}
	return $ret;
}

function updateTip($ducanId, $tip)
{
	$link = connectToDB();
	if($link)
	{
		$ret = false;
		$query = "UPDATE ducan SET tip_ducana='".$tip."' WHERE id_ducan=".$ducanId;
		$result = mysqli_query($link, $query);
		if($result)
		{
			$ret = true;
		}
		mysqli_close($link);
	}
	return $ret;
}
function updateVrsta($ducanId, $vrsta)
{
	$link = connectToDB();
	if($link)
	{
		$ret = false;
		$query = "UPDATE ducan SET vrsta_ducana='".$vrsta."' WHERE id_ducan=".$ducanId;
		$result = mysqli_query($link, $query);
		if($result)
		{
			$ret = true;
		}
		mysqli_close($link);
	}
	return $ret;
}

function spremiDucan($ime, $tip, $vrsta, $slika)
{
	$link = connectToDB();
	if($link)
	{
		$query = "INSERT INTO `ducan` (`id_ducan`, `ime`, `tip_ducana`, `vrsta_ducana`, slika) VALUES (NULL, '".$ime."', '".$tip."', '".$vrsta."', '".$slika."');";
		$result = mysqli_query($link, $query);
		if(!$result)
		{
			echo("Dućan nije dodan, error");
			mysqli_close($link);
			return false;
		}
	}
	mysqli_close($link);
	header("Location: /RWA_ducani/index.php");
}

function updateSlika($ducanId, $slika)
{
	//$_FILES['image']['name']
	$link = connectToDB();
	if($link)
	{
		$query = "UPDATE `ducan` SET `slika` = '".$slika."' WHERE `ducan`.`id_ducan` = ".$ducanId.";";
		$result = mysqli_query($link, $query);
		if(!$result)
		{
			echo("Slika nije updejtana");
			mysqli_close($link);
			return false;
		}
	}
	mysqli_close($link);
	header("Location: /RWA_ducani/urediDucan.php?id=".$ducanId);
}

function isDucanDuplicate($ducanIme)
{
	$retVal = false;
	$link = connectToDB();
	$query = "SELECT ime FROM ducan WHERE ime='".$ducanIme."';";
	$result = mysqli_query($link, $query);
	if($result)
	{
		while($row = mysqli_fetch_array($result))
		{
			if($row['ime'] == $ducanIme)
			{
				$retVal = true;
			}
		}
	}
	mysqli_close($link);
	return $retVal;
}

function izbrisiDucan($ducanId)
{
	$ret = false;
	$link = connectToDB();
	if($link)
	{
		$query = "DELETE FROM ducan WHERE id_ducan=".$ducanId;
		$result = mysqli_query($link, $query);
		if($result)
		{
			$ret = true;
		}
		mysqli_close($link);
	}
	return $ret;
}

function printDucan($ducan)
{
	echo "<div class='col-md-3 col-md-offset-1'>";
	echo "<a href='ducan.php?id=".$ducan->id."'>"
		."<h3 class='em-text'>".$ducan->ime.
	"</h3></a></br>".
	"<img class='store-img' src='" . $ducan->urlSlike ."' >
	<h5> Tip dućana: "./*$ducan->tipovi*/getTipovi()[$ducan->tip].
	"</br> Vrsta dućana: "./*$ducan->vrste*/getVrste()[$ducan->vrsta].
	"</br> Srednja ocjena: ".$ducan->ocjena."/5".
	"(".getNumOfComments($ducan->id).")".
	"</br> Ukupno komentara: ".getNumOfCommentsForDucan($ducan->id).
	"</br><a href='ducan.php?id=".$ducan->id."'>Pogledaj komentare</a>";
	
	if($GLOBALS['user']->razinaOvlasti == 1)
	{
		echo " | <a href='urediDucan.php?id=".$ducan->id."'>Uredi ducan</a>";
	}
	echo "</h5>";
	echo "<br>";
	echo "</div>";
}

function getTipovi()
{
	$ret = array("none" => "Odaberi tip...");
	$link = connectToDB();
	if($link)
	{
		$query = "SELECT * from filter_tip";
		$result = mysqli_query($link, $query);
		if($result)
		{
			while($row = mysqli_fetch_array($result))
			{
				$ret[$row['tip']] = $row['tip'];
			}
		}
		mysqli_close($link);
	}
	return $ret;
}

function getVrste()
{
	$ret = array("none" => "Odaberi vrstu...");
	$link = connectToDB();
	if($link)
	{
		$query = "SELECT * from filter_vrsta";
		$result = mysqli_query($link, $query);
		if($result)
		{
			while($row = mysqli_fetch_array($result))
			{
				$ret[$row['vrsta']] = $row['vrsta'];
			}
		}
		mysqli_close($link);
	}
	return $ret;
}

function dodajTip($tip)
{
	$link = connectToDB();
	if($link)
	{
		$query = "INSERT INTO `filter_tip` (`tip`) VALUES ('".$tip."');";
		$result = mysqli_query($link, $query);
		if(!$result)
		{
			//echo("not rated");
			return false;
		}
		else
		{
			return true;
		}		
	}
	return false;
}

function dodajVrstu($vrsta)
{
	$link = connectToDB();
	if($link)
	{
		$query = "INSERT INTO `filter_vrsta` (`vrsta`) VALUES ('".$vrsta."');";
		$result = mysqli_query($link, $query);
		if(!$result)
		{
			//echo("Ta vrsta već postoji");
			return false;
		}
		else
		{
			return true;
		}		
	}
	return false;
}

//klasa ducana
class ducan
{
	/*public $tipovi = array(
								'' => 'Odaberi tip...',
								'odjeca' => 'Odjeća',
								'pokloni' => 'Pokloni',
								'sport' => 'Sport',
								'obuca' => 'Obuća',
								'prehrana' => 'Prehrana',
								'namjestaj' => 'Namještaj',
								'igracke' => 'Igračke',
								'tehnika' => 'Tehnika'
								);
								
	public $vrste = array(
								'' => 'Odaberi vrstu...',
								'tvornica' => 'Tvornička prodaja',
								'supermarket' => 'Supermarket',
								'trgovina' => 'Trgovina'
								);*/
	
	public $id, $ime, $tip, $vrsta, $ocjena, $urlSlike;
	public $adrese;
	
	public function __construct($id, $ime, $tip, $vrsta, $ocjena, $urlSlike)
	{
		$this->id = $id;
		$this->ime = $ime;
		$this->tip = $tip;
		$this->vrsta = $vrsta;
		$this->ocjena = $ocjena;
		$this->urlSlike = "images/ducani/".$ime."/".$urlSlike;
	}
}

?>