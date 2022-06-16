<?php
	session_start();
	include("util.php");
	
	$sessionkey = $_REQUEST["id"];
	
	db_connect();
	if (isset($_REQUEST["id"]))
	{
		unset($_SESSION);
		db_delete_cart($_REQUEST["id"]);
		// redirect to cart
		header("Location: start_dates.php");
		exit;
	} 
?>

<html>
	<head><title>Kootenay Moutaineering Huts Remove From Cart</title></head>"
	<body>
	<p>No Cart ID specified!</p>
	</body>
</html>
