<?php
	session_start();
	
	include("util.php");

	global $SAVEDSESSION;
	get_session(session_id());
	$_SESSION = $SAVEDSESSION;
	
	$hut_id = $_REQUEST["hut"];
	$hut_name = $_REQUEST["hut_name"];
	$day = $_REQUEST["day"];
	$month = $_REQUEST["month"];
	$year = $_REQUEST["year"];
	$num_persons = $_REQUEST["num_persons"];
	
	$sd = $_REQUEST["sd"];
	$sm = $_REQUEST["sm"];
	$sy = $_REQUEST["sy"];
	
	$count = $_REQUEST["count"];
	$count += 1;
	$_SESSION['count'] += 1;
	$_SESSION['run-book'] += 1;
	
	$returnLink = "'hut_availability.php?sd=" . $sd . "&sm=" . $sm . "&sy=" . $sy . "&count=" . $count . "'";

	$booked_date = mktime(0,0,0,$month,$day,$year);
	$thedate = date("Y-m-d",$booked_date);
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
		<?php db_connect();
		
		//echo "<p>For add: " . $hut_id . " - " . date("Y-m-d", $booked_date) . " - " . $num_persons . "</p>";
		
		$sql = db_add_to_cart($hut_id, date("Y-m-d", $booked_date), $num_persons);

		$SAVEDSESSION = $_SESSION;
		save_session(session_id());
		
		echo "<p class='centered'>The $hut_name hut is reserved on $thedate for $num_persons " . ($num_persons == 1 ? " person" : " people") . ".</p>\n";
		echo "<p class='centered'><a href=$returnLink>Book more or list bookings before proceeding to checkout</a></p>\n";
		//display_stuff();
		?>
	</section>
</body>
</html>
