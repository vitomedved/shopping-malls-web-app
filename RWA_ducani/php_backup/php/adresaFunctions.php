<?php

function doesAdresaExist($adresaId)
{
	$ret = false;
	$link = connectToDB();
	if($link)
	{
		$query = "SELECT id_adresa FROM adresa WHERE id_adresa=".$adresaId;
		$result = mysqli_query($link, $query);
		if($result)
		{
			while($row = mysqli_fetch_array($result))
			{
				$ret = true;
			}
		}
		mysqli_close($link);
	}
	return $ret;
}

//vraca objekt adresa za odredeni ducan
function getAdresses($ducanId)
{
	$ret = array();
	$link = connectToDB();
	if($link)
	{
		$query = "SELECT * FROM adresa NATURAL JOIN adresa_has_ducan WHERE adresa_has_ducan.id_ducan=".$ducanId;
		$result = mysqli_query($link, $query);
		if($result)
		{
			while($row = mysqli_fetch_array($result))
			{
				$ret[] = new adresa($row['id_adresa'], $row['ulica'], $row['grad'], $row['postanski_broj'], $row['kucni_broj']);
			}
		}
		mysqli_close($link);
	}
	return $ret;
}

class adresa
{
	public $id, $ulica, $grad, $kucniBroj, $postanskiBroj;
	public function __construct($id, $ulica, $grad, $postanskiBroj, $kucniBroj)
	{
		$this->id = $id;
		$this->ulica = $ulica;
		$this->grad = $grad;
		$this->postanskiBroj = $postanskiBroj;
		$this->kucniBroj = $kucniBroj;
	}
}

?>