<?php

include_once 'adresaFunctions.php';

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

//dodaje uneseni komentar u bazu
function addComment($userId, $ducanId, $naslov, $sadrzaj)
{
	$retVal = false;
	$link = connectToDB();
	if($link)
	{
		$query = "INSERT INTO `komentar` (`id_komentar`, `naslov`, `sadrzaj`, `id_korisnik`, `id_ducan`) VALUES (NULL, '".$naslov."', '".$sadrzaj."', '".$userId."', '".$_GET['id']."');";
		$result = mysqli_query($link, $query);
		if($result)
		{
			echo("Komentar dodan");
			$retVal = true;
		}
	}
	mysqli_close($link);
	return $retVal;
}

//izlista sve komentare trenutnog ducana
function listComments($ducanId)
{
	$link = connectToDB();
	if($link)
	{
		$query = "SELECT ime, prezime, UNIX_TIMESTAMP(vrijeme) as timestamp, vrijeme, komentar.id_korisnik, naslov, sadrzaj, id_komentar FROM komentar LEFT JOIN podatak ON (komentar.id_korisnik = podatak.id_korisnik) WHERE id_ducan='".$ducanId."' ORDER BY timestamp DESC";
		$result = mysqli_query($link, $query);
		if($result)
		{
			while($row = mysqli_fetch_array($result))
			{
				echo("<a href='userProfil.php?id=".$row['id_korisnik']."' >Komentar by:".$row['ime']." ".$row['prezime'].", vrijeme: ".date('d.m.Y., H:i\h', strtotime($row['vrijeme']))."</a><br>
				<div>".$row['naslov']."<br>".$row['sadrzaj']."</div><br>");
				if(isset($_SESSION['loggedIn']) && ($_SESSION['loggedIn'] == true))
				{
					if(($_SESSION['user']->razinaOvlasti == 1) && ($row['id_korisnik'] == $_SESSION['user']->id))
					{
						//admin sam i moj je komentar
						echo("<a href='removeComment.php?commentId=".$row['id_komentar']."&ducanId=".$_GET['id']."'>REMOVE</a> | <a href='editComment.php?commentId=".$row['id_komentar']."&ducanId=".$_GET['id']."'>EDIT</a>");
					}
					else if($_SESSION['user']->razinaOvlasti == 1)
						//admin, a nije moj komentar
					{
						echo("<a href='removeComment.php?commentId=".$row['id_komentar']."&ducanId=".$_GET['id']."'>REMOVE</a>");
					}
					else if(($row['id_korisnik'] == $_SESSION['user']->id))
						//moj komentar, a nisam admin
					{
						echo("<a href='removeComment.php?commentId=".$row['id_komentar']."&ducanId=".$_GET['id']."'>REMOVE</a> | <a href='editComment.php?commentId=".$row['id_komentar']."&ducanId=".$_GET['id']."'>EDIT</a>");
					}
				}
				echo("<br><hr><br>");
				
			}
		}
	}
	mysqli_close($link);
}

//vraca sve ducane u polju objekata ducana
function getDucaniArray()
{
	$link = connectToDB();
	if($link)
	{
		$query = "SELECT * FROM ducan";
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
				$ret[] = new ducan($row['id_ducan'], $row['ime'], $row['tip_ducana'], $row['vrsta_ducana'], getRating($row['id_ducan']));
			}
			//echo("<a href='index.php'>Povratak na pocetnu</a>");
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
				$ret = new ducan($row['id_ducan'], $row['ime'], $row['tip_ducana'], $row['vrsta_ducana'], getRating($row['id_ducan']));
				$ret->adrese = getAdresses($row['id_ducan']);
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

//klasa ducana
class ducan
{
	public $id, $ime, $tip, $vrsta, $ocjena;
	public $adrese;
	
	public function __construct($id, $ime, $tip, $vrsta, $ocjena)
	{
		$this->id = $id;
		$this->ime = $ime;
		$this->tip = $tip;
		$this->vrsta = $vrsta;
		$this->ocjena = $ocjena;
	}
}

?>