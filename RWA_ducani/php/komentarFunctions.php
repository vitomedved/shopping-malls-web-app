<?php

//provjerava ako je komentar od tog userId-a da nebi neko u url samo id komentara promijenil
function validateOwnership($commentId, $userId)
{
	$retVal = false;
	$link = connectToDB();
	if($link)
	{
		$query = "SELECT id_korisnik, id_ducan, naslov FROM komentar WHERE id_komentar=".$commentId;
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_array($result))
		{
			if($row['id_korisnik'] == $userId)
			{
				$retVal = true;
				//echo $row['naslov'];
				//header("Location: /RWA_ducani/error.php");
			}
			//return $row['id_ducan'];
		}
		mysqli_close($link);
	}
	return $retVal;
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
function listComments($ducanId, $start, $numOfResults)
{
	if(isset($_SESSION['userId']))
	{
		$user = getUserObject($_SESSION['userId']);		
	}
	$link = connectToDB();
	if($link)
	{
		//$query = "SELECT * FROM ducan limit ".$start.", ".$numOfResults;
		
		$query = "SELECT ime, prezime, UNIX_TIMESTAMP(vrijeme) as timestamp, vrijeme, komentar.id_korisnik, naslov, sadrzaj, id_komentar FROM komentar LEFT JOIN podatak ON (komentar.id_korisnik = podatak.id_korisnik) WHERE id_ducan='".$ducanId."' ORDER BY timestamp DESC LIMIT ".$start.", ".$numOfResults;
		$result = mysqli_query($link, $query);
		if($result)
		{
			while($row = mysqli_fetch_array($result))
			{
				if(!$row['ime'] && !$row['prezime'])
				{
					$ime = 'Bez imena';
					$prezime = chr(8);
				}
				else
				{
					$ime = $row['ime'];
					$prezime = $row['prezime'];
				}
				echo("
				<h4><a href='userProfil.php?id=".$row['id_korisnik']."' >".$ime." ".$prezime.", vrijeme: ".date('d.m.Y., H:i\h', strtotime($row['vrijeme']))."</a></h4>

				<h4>".$row['naslov']."</h4> <br> ".$row['sadrzaj']."");
				if(isset($_SESSION['loggedIn']) && ($_SESSION['loggedIn'] == true))
				{
					if(($user->razinaOvlasti == 1) && ($row['id_korisnik'] == $user->id))
					{
						//admin sam i moj je komentar
						echo("<br><br><a href='php/removeComment.php?commentId=".$row['id_komentar']."&ducanId=".$_GET['id']."'>REMOVE</a> | <a href='editComment.php?commentId=".$row['id_komentar']."&ducanId=".$_GET['id']."'>EDIT</a>");
					}
					else if($user->razinaOvlasti == 1)
						//admin, a nije moj komentar
					{
						echo("<br><br><a href='php/removeComment.php?commentId=".$row['id_komentar']."&ducanId=".$_GET['id']."'>REMOVE</a>");
					}
					else if(($row['id_korisnik'] == $user->id))
						//moj komentar, a nisam admin
					{
						echo("<br><br><a href='removeComment.php?commentId=".$row['id_komentar']."&ducanId=".$_GET['id']."'>REMOVE</a> | <a href='editComment.php?commentId=".$row['id_komentar']."&ducanId=".$_GET['id']."'>EDIT</a>");
					}
				}
				echo("<br><hr><br>");
				
			}
		}
	}
	mysqli_close($link);
}

function deleteComment($ducanId, $commentId)
{
	$retVal = false;
	$link = connectToDB();
	if($link)
	{
		$query = "DELETE FROM `komentar` WHERE `komentar`.`id_komentar`=".$commentId;
		$result = mysqli_query($link, $query);
		if($result)
		{
			
			//header("Location: /RWA_ducani/error.php");
			if (mysqli_affected_rows($link) > 0)
			{
				$retVal = true;
				//header("Location: /RWA_ducani/ducan.php?id=".$ducanId);
			}		
		}
		//else
		//{
		//}
		mysqli_close($link);
	}
	return $retVal;
	
	//header("Location: /RWA_ducani/error.php");
}

function listUserComments($userId, $start, $numOfResults)
{
	if(isset($_SESSION['userId']))
	{
		$user = getUserObject($_SESSION['userId']);		
	}
	$link = connectToDB();
	if($link)
	{
		$query = "SELECT id_komentar, UNIX_TIMESTAMP(vrijeme) as timestamp, vrijeme, id_korisnik, naslov, sadrzaj, id_ducan FROM komentar WHERE id_korisnik='".$userId."' ORDER BY timestamp DESC LIMIT ".$start.", ".$numOfResults;
		$result = mysqli_query($link, $query);
		if($result)
		{
			$cnt = 0;
			while($row = mysqli_fetch_array($result))
			{
				$ducan = getDucan($row['id_ducan']);
				echo("
				<h4>Za dućan: ".$ducan->ime."</h4>
				<h5>".$row['naslov']."</h5>
				<h5>".$row['sadrzaj']."</h5>");
				if(isset($_SESSION['loggedIn']) && ($_SESSION['loggedIn'] == true))
				{
					if(($user->razinaOvlasti == 1) && ($row['id_korisnik'] == $user->id))
					{
						//admin sam i moj je komentar
						echo("<br><br><a href='php/removeComment.php?commentId=".$row['id_komentar']."&ducanId=".$_GET['id']."'>REMOVE</a> | <a href='editComment.php?commentId=".$row['id_komentar']."&ducanId=".$_GET['id']."'>EDIT</a>");
					}
					else if($user->razinaOvlasti == 1)
						//admin, a nije moj komentar
					{
						echo("<br><br><a href='php/removeComment.php?commentId=".$row['id_komentar']."&ducanId=".$_GET['id']."'>REMOVE</a>");
					}
					else if(($row['id_korisnik'] == $user->id))
						//moj komentar, a nisam admin
					{
						echo("<br><br><a href='removeComment.php?commentId=".$row['id_komentar']."&ducanId=".$_GET['id']."'>REMOVE</a> | <a href='editComment.php?commentId=".$row['id_komentar']."&ducanId=".$_GET['id']."'>EDIT</a>");
					}
				}
				echo("<br><hr><br>");
				$cnt++;
			}
			if($cnt == 0)
			{
				echo "Korisnik još nije dodao komentare";
			}
		}
	}
	mysqli_close($link);
}

function getNumOfCommentsForDucan($ducanId)
{
	$link = connectToDB();
	if($link)
	{
		$query = "SELECT COUNT(*) as cnt FROM komentar WHERE id_ducan =".$ducanId;
		$result = mysqli_query($link, $query);
		$ret = -1;
		
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

function getNumOfCommentsForUser($userId)
{
	$link = connectToDB();
	if($link)
	{
		$query = "SELECT COUNT(*) as cnt FROM komentar WHERE id_korisnik=".$userId;
		$result = mysqli_query($link, $query);
		$ret = -1;
		
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

?>