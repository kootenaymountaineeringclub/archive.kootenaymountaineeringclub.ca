<!DOCTYPE html>
<html>
	
	<?php 	define('PHP_ROOT','/var/www/vhosts/kootenaymountaineeringclub.ca/httpdocs');
			define('EMAIL_TO', 'timclint@gmail.com,peteroostlander@hotmail.com');
	?>  <!--   -->

<head>
	<?php include (PHP_ROOT . "/includes/head-first.incl.html") ; ?>

	<title>KMC Spring Course Registration</title>
	
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

		<?php include ("../../includes/club-trips.incl.html") ; ?>

		<section>
		
		<h2>KMC 2019 Spring Courses</h2>
		
		<?php
			
			if ($_POST['leader'] == ""
				 && $_POST['ice'] == ""
				 && $_POST['rescue'] == ""
				 && $_POST['map'] == "" 
				 && $_POST['rock1'] == ""
				 && $_POST['rock2'] == "" ) {
				echo "<p>Please select at least one course.</p>";
			} else {
				
				$csv = '';
				
				$csv .= '"' . $_POST['leader'] . '",';
				$csv .= '"' . $_POST['ice'] . '",';
				$csv .= '"' . $_POST['rescue'] . '",';
				$csv .= '"' . $_POST['map'] . '",';
				$csv .= '"' . $_POST['rock1'] . '",';
				$csv .= '"' . $_POST['rock2'] . '",';
				$csv .= '"' . $_POST['full-name'] . '",';
				$csv .= '"' . $_POST['street'] . '",';
				$csv .= '"' . $_POST['street2'] . '",';
				$csv .= '"' . $_POST['city'] . '",';
				$csv .= '"' . $_POST['prov'] . '",';
				$csv .= '"' . $_POST['postcode'] . '",';
				$csv .= '"' . $_POST['email'] . '",';
				$csv .= '"' . $_POST['phone'] . '",';
				$csv .= '"' . $_POST['age'] . '",';
				$csv .= '"' . str_replace("\n"," ",$_POST['experience']) . '",';
				$csv .= '"' . str_replace("\n"," ",$_POST['allergies']) . '",';
				$csv .= '"' . str_replace("\n"," ",$_POST['injuries']) . '",';
				$csv .= '"' . str_replace("\n"," ",$_POST['medications']) . '",';
				$csv .= '"' . str_replace("\n"," ",$_POST['food']) . '",';
				$csv .= '"' . $_POST['contact-name'] . '",';
				$csv .= '"' . $_POST['relationship'] . '",';
				$csv .= '"' . $_POST['emerge-phone'] . '"' . "\r\n";
				
				$file = fopen("CourseRegistrations.txt",'a');
				fwrite($file,$csv);
				fclose($file);
				
				if (mail(EMAIL_TO,'KMC 2019 Spring skills courses',$csv)) {
						
					echo "<p>Wonderful! Welcome. It will be a great course.</p>
			
					<p>As a reminder, you need to pay the required fee for the course via an electronic money transfer to the club treasurer (treasurer@kootenaymountaineeringclub.ca). Closer to the course date the course director will confirm your attendance; help arrange ride sharing and will provide further details.</p>";
				} else {
					echo "<p>Oops. No mail.</p>";	
				}
			}
		?>
		
		</section>
	
	</div> <!-- end content -->

	<footer>

		<?php include ("../../includes/club-trips.incl.html") ; ?>

	</footer>

</div> <!-- end master -->

</body>
</html>