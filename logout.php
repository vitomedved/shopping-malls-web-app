<?php 

session_start();

session_unset();
session_destroy();
header("Location: /RWA_ducani/index.php");

?>