<?php

include_once 'connectionToDB.php';
include_once 'userFunctions.php';


if(isGuest() || getUserObject($_SESSION['userId'])->razinaOvlasti == 0)
{
	header("Location: /RWA_ducani/index.php");
}

function deleteTip($tip)
{
	$ret = false;
	$link = connectToDB();
	if($link)
	{
		$query = "DELETE FROM filter_tip WHERE tip='".$tip."'";
		$result = mysqli_query($link, $query);
		if($result)
		{
			$ret = true;
		}
		mysqli_close($link);
	}
	return $ret;
}

function deleteVrsta($vrsta)
{
	$ret = false;
	$link = connectToDB();
	if($link)
	{
		$query = "DELETE FROM filter_vrsta WHERE vrsta='".$vrsta."'";
		$result = mysqli_query($link, $query);
		if($result)
		{
			$ret = true;
		}
		mysqli_close($link);
	}
	return $ret;
}


?>