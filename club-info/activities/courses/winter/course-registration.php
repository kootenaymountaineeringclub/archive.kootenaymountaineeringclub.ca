<!DOCTYPE html>
<html>
	
	<?php 	define('PHP_ROOT','/var/www/vhosts/kootenaymountaineeringclub.ca/httpdocs'); ?>
	<?php   define('EMAIL_TO', 'timclint@gmail.com,peteroostlander@hotmail.com'); ?>  <!--  -->

<head>
	<?php include (PHP_ROOT . "/includes/head-first.incl.html") ; ?>

	<title>KMC Winter Course Registration</title>
	
	<?php include (PHP_ROOT . "/includes/head-2nd.incl.html") ; ?>
	<?php include (PHP_ROOT . "/club-info/membership/includes/MembershipBackgroundImage.incl.html") ; ?>

	<link rel="stylesheet" type="text/css" href="/css/membership-styles.css">
	<link rel="stylesheet" type="text/css" href="registration-form.css">

</head>
<body>

<div id="master">

	<header>

	<?php 	include (PHP_ROOT . "/club-info/includes/page-header-club.incl.html") ;
			include (PHP_ROOT . "/includes/header-contents.incl.html") ; ?>
	
	</header>

	<div id="content">

		<?php include ("../../../includes/club-trips.incl.html") ; ?>

		<section>
		
		<h2>KMC 2017-2018 Winter Courses</h2>
		
		<?php
			
			if ($_POST['intro-ski'] == ""
				 && $_POST['inter-ski'] == ""
				 && $_POST['ast1-snow'] == ""
				 && $_POST['rescue'] == ""
				 && $_POST['avi-terrain'] == ""
				 && $_POST['ast1-shoe'] == ""
				 && $_POST['ast2'] == "" ) {
				echo "<p>Please select at least one course.</p>";
			} else {
				
// 				$csv = '"intro-ski","inter-ski","ast1-snow","rescue","avi-terrain","ast1-shoe","ast2","preferred-dates","full-name","street","street2","city","prov","postcode","email","phone","age","experience","none","shovel","probe","transceiver","allergies","injuries","medications","food","contact-name","relationship","emerge-phone"' . "\r\n";
				
				$csv = '"' . $_POST['intro-ski'] . '",';
				$csv .= '"' . $_POST['inter-ski'] . '",';
				$csv .= '"' . $_POST['ast1-snow'] . '",';
				$csv .= '"' . $_POST['rescue'] . '",';
				$csv .= '"' . $_POST['avi-terrain'] . '",';
				$csv .= '"' . $_POST['ast1-shoe'] . '",';
				$csv .= '"' . $_POST['ast2'] . '",';
				$csv .= '"' . $_POST['preferred-dates'] . '",';
				$csv .= '"' . $_POST['full-name'] . '",';
				$csv .= '"' . $_POST['street'] . '",';
				$csv .= '"' . $_POST['street2'] . '",';
				$csv .= '"' . $_POST['city'] . '",';
				$csv .= '"' . $_POST['prov'] . '",';
				$csv .= '"' . $_POST['postcode'] . '",';
				$csv .= '"' . $_POST['email'] . '",';
				$csv .= '"' . $_POST['phone'] . '",';
				$csv .= '"' . $_POST['age'] . '",';
				$csv .= '"' . $_POST['experience'] . '",';
				$csv .= '"' . $_POST['none'] . '",';
				$csv .= '"' . $_POST['shovel'] . '",';
				$csv .= '"' . $_POST['probe'] . '",';
				$csv .= '"' . $_POST['transceiver'] . '",';
				$csv .= '"' . $_POST['allergies'] . '",';
				$csv .= '"' . $_POST['injuries'] . '",';
				$csv .= '"' . $_POST['medications'] . '",';
				$csv .= '"' . $_POST['food'] . '",';
				$csv .= '"' . $_POST['contact-name'] . '",';
				$csv .= '"' . $_POST['relationship'] . '",';
				$csv .= '"' . $_POST['emerge-phone'] . '"' . "\r\n";
				
				mail(EMAIL_TO,'Winter Course Registration',$csv);
				
		?>
			
				<p>Wonderful! Welcome. It will be a great course.</p>
				<p>As a reminder, you need to pay the required price for the course via an electronic money transfer to the club treasurer (treasurer@kootenaymountaineeringclub.ca). Closer to the course date the winter trips director will confirm and help arrange ride sharing and such.</p>
		
		<?php
			}
		?>
		
		</section>
	
	</div> <!-- end content -->

	<footer>

		<?php include ("../../../includes/club-trips.incl.html") ; ?>

	</footer>

</div> <!-- end master -->

</body>
</html>