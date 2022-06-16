<?php
	include("util.php");
	session_start();
	
	db_connect();
	if (isset($_REQUEST["id"]))
	{
		db_delete_from_cart($_REQUEST["id"]);
		// redirect to cart
		header("Location: hut_availability.php?" . SID);
		exit;
	} 
?>
<html>
	<head><title>Kootenay Moutaineering Huts Remove From Cart</title></head>"
	<body>
	<p>No Cart ID specified!</p>
	</body>
</html>