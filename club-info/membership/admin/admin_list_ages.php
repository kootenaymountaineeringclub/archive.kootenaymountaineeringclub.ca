<!DOCTYPE html>
<html>
<head>
	<?php
		define('PHP_ROOT','/var/www/vhosts/kootenaymountaineeringclub.ca/httpdocs');
		include (PHP_ROOT . "/includes/head-first.incl.html") ;
	?>

	<title>Membership Admin: List Membership Ages</title>
	
	<?php include (PHP_ROOT . "/includes/head-2nd.incl.html") ;
				include (PHP_ROOT . "/js/header-pictures.js") ;
	?>
	
	<link rel="stylesheet" type="text/css" href="/css/membership-styles.css">

</head>
<body>

<div id="master">

	<header>

	<?php
		include ("../../includes/page-header-club.incl.html") ;
		include (PHP_ROOT . "/includes/header-contents.incl.html") ;
	?>
	
	</header>

	<div id="content">

	<?php include ("../../includes/club-membership.incl.html") ; ?>	
	
	<section>
		
		<p class="centered"><a href="MembAdmin.php">Membership Admin Page</a></p>
		
		<p>Membership Age Range Stats</p>
		
		<?php
			
		$AgeRanges = array( 0 => "NA" , 16 => "Under 19" , 34 => "20 - 39" , 54 => "40 - 59" , 61 => "60 or more" ) ;
		
		$link = mysqli_connect("localhost", "kmcwe_kmc", "zlika9p", "kmcweb_0_web");
		
		if (!$link) {
		    die('Connect Error (' . mysqli_connect_error() . ') ');
		}
		
		echo "<table>\n<thead><tr><td>Age Range</td><td>Count</td></tr></thead>\n";
		
		$result = mysqli_query($link, 'SELECT AgeRange,COUNT(AgeRange) from Member GROUP BY AgeRange ORDER by AgeRange');
		
		if ($result) {
			
			$ages =	mysqli_fetch_all($result);
			
			foreach($ages as $range => $numb) {
			 	echo "<tr><td>" . $AgeRanges[$range] . "</td><td>" . $numb . "</td></tr>\n" ;
			}
		}	
		
		echo "</table>\n";	
	?>		
		
		<p class="centered"><a href="MembAdmin.php">Membership Admin Page</a></p>

	</section>
	
	</div>

	<footer>

	<?php include ("../../includes/club-membership.incl.html") ; ?>

	</footer>

</div> <!-- end master -->

</body>
</html>