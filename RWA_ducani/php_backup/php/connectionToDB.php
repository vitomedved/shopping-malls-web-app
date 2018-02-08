<?php 

function connectToDB()
{
	$connection = mysqli_connect('127.0.0.1', 'root', 'root', 'ducani_db');
	if(mysqli_connect_errno())
	{
		echo('DB not connected: '.mysqli_error($link));
		return false;
	}
	mysqli_select_db($connection, 'ducani_db');
	return $connection;
}

?>