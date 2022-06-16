<?php
	session_start();
	include("util.php");
	
	$sd = $_REQUEST["sd"];
	$sm = $_REQUEST["sm"];
	$sy = $_REQUEST["sy"];
	$count = $_REQUEST["count"] - 1;
	
	$returnLink = "hut_availability.php?sy=" . $sy . "&sm=" . $sm . "&sd=" . $sd . "&count=" . $count ;
	
	db_connect();
	if (isset($_REQUEST["id"]))
	{
		db_delete_from_cart($_REQUEST["id"]);
	} 
?>

<html>
<head>
	<?php include("../../../includes/head-first.incl.html") ; ?>

	<title>KMC: The Bonnington Cabins Booking System</title>
	
	<?php include ("../../../includes/head-2nd.incl.html") ; ?>
	<?php include ("../includes/CabinBookingBackgroundImage.incl.html"); ?>
</head>

<body>
<div id="master">
<header>

	<?php include "../../includes/page-header-club.incl.html"; ?>
	<?php include "../../../includes/header-contents.incl.html"; ?>
		
</header>
	
<div id="content">

<?php include "../../includes/club-bonnington.incl.html" ; ?>
	
	<section>
	<?php echo "<p>Your booking was removed.</p>\n";
		  echo "<p><a href=$returnLink>Book more or list bookings before proceeding to checkout</a></p>"\n;
		  display_stuff();
	?>
	
</body>
</html>
