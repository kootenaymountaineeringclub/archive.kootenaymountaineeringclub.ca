<?php
	session_unset();
	session_start();

	include "date_util.php" ;
	include "booking_util.php" ;

	global $SAVEDSESSION;
	
	$_SESSION['id'] = $SAVEDSESSION['id'] = session_id();
	$_SESSION['count'] = $SAVEDSESSION['count'] = 0;
	$_SESSION['start'] = $SAVEDSESSION['start'] = 1;
	save_session(session_id());
	
//	db_connect();
//	db__clear_checkout(); // Clear the At Checkout Page indicator in Cart
//	db_clean_cart(); // Clean out timed out cart items
?>

<!DOCTYPE html>
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

	<?php 	include "WelcomeMessage.html" ; ?>

		<div class="announcement">
		<p>Please select the date you would like your trip to begin:</p>
		
	<?php      
	 	$url = "'";

        date_default_timezone_set('America/Los_Angeles');
        $today_array = getdate(time() + (24 * 60 * 60));
        echo "<form method='POST' action='hut_availability.php?count=0' >\n";
        echo "<p><input type='email' required id='mail' name='mail' width=30 placeholder='Your email address'></p>\n" ;
        echo day_select("sd", $today_array);
        echo month_select("sm", $today_array);
        echo year_select("sy", $today_array);
        echo "\n<input type='hidden' name='count' value=0>";
        echo "\n<input type='submit' name='submit' value='Show Availability'></form>\n";
        echo "<p><strong>Attention:</strong> This booking process requires that you have allowed 'cookies' to be placed in your web browser. If you have them turned off, please turn them on for the length of time it takes to  finish placing your hut booking. Thanks !!!</p>\n";
        echo "</form>\n";
        
        // display_stuff();
    ?>

	</div>
	</section>
</div> <!-- end content -->

<footer>

</footer>

</div> <!-- end master -->

</body>
</html>

