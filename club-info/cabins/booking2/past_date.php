<!DOCTYPE html>
<?php
	include "date_util.php" ;
	include "util.php" ;
?>
<html>
<head>
	
	<?php include("../../../includes/head-first.incl.html") ; ?>

	<title>KMC: The Bonnington Cabins Booking System</title>
	
	<?php include ("../../../includes/head-2nd.incl.html") ; ?>
	<?php include ("../includes/CabinBookingBackgroundImage.incl.html"); ?>

<body>

<div id="master">

<header>

	<?php include "../../includes/page-header-club.incl.html"; ?>
	<?php include "../../../includes/header-contents.incl.html"; ?>
	
</header>

<div id="content">

<?php include "../../includes/club-bonnington.incl.html" ; ?>
	
	<section>
	<div class="announcement">

<?php
	
	session_start();
 //   echo "<pre>" . print_r($_SESSION,TRUE) . "</pre>";

    date_default_timezone_set( 'America/Los_Angeles');
	$start_month = $_SESSION["start_month"];
	$start_day = $_SESSION["start_day"];
	$start_year = $_SESSION["start_year"];

	$past_date = mktime(0,0,0,$start_month,$start_day,$start_year);
	$msg = '<p>The date selected (' . date("d M Y", $past_date) . ') for your trip to begin is in the past!</p>';
	echo $msg;
?> 

	<p>We have real trouble doing that! Please return to <a href="select_dates.php">the initial date selection page</a> to try again.</p>
	</div>
	</section>
</div> <!-- end content -->

<footer>

<?php include html_footer_strip() ; ?>

</footer>

</div> <!-- end master -->

</body>
</html>
