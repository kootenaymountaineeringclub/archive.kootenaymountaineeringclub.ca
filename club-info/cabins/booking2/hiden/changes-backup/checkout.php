<!DOCTYPE html>
<html>
<head>
	<?php 
		include("util.php");
		include("date_util.php");
		session_start();
		include (PHP_ROOT . "/includes/head-first.incl.html") ;
	?>

	<title>KMC: The Bonnington Cabin Booking System</title>
	
	<?php include (PHP_ROOT . "/includes/head-2nd.incl.html") ; ?>
	<?php include ("../includes/CabinBookingBackgroundImage.incl.html") ; ?>

<body>

<div id="master">

<header>

	<?php include ("../../includes/page-header-club.incl.html") ;
				include (PHP_ROOT . "/includes/header-contents.incl.html") ; ?>
	
</header>

<div id="content">

<?php include ("../../includes/club-bonnington.incl.html") ; ?>
	
	<section>

<?php

	db_connect();

	$total_cost = 0;
	$total_person_nights = 0;
	$display_block = html_cart(true);
	
?>
	<?php echo $display_block; ?>


<script type="text/javascript">
 window.paypalCheckoutReady = function () {
     paypal.checkout.setup('cabins-facilitator_api1.kootenaymountaineering.bc.ca', {
         container: 'myContainer', //{String|HTMLElement|Array} where you want the PayPal button to reside
         environment: 'production' //or 'production' depending on your environment
     });
 };
 </script>
 <script src="https://www.paypalobjects.com/api/checkout.js" async></script>
 
	</section>
</div> <!-- end content -->

<footer>

<?php include html_footer_strip() ; ?>

</footer>

</div> <!-- end master -->

</body>
</html>
