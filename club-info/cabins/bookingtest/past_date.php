<!DOCTYPE html>
<html>
<head>
<!--#include virtual=/includes/head-first.incl.html -->

	<title>KMC: The Bonnington Cabins Booking System</title>
	
<!--#include virtual=/includes/head-2nd.incl.html -->

<body>

<div id="master">

<header>

	<!--#include virtual=../../includes/page-header-club.incl.html -->
	<!--#include virtual=/includes/header-contents.incl.html -->
	
</header>

<div id="content">

	<!--#include virtual=../../includes/club-bonnington.incl.html -->
	
	<section>
	<div class="announcement">

<?php
// post_date.php
	include("date_util.php");
	include ("util.php");

	session_start();

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
