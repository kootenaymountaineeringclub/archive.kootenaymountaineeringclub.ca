<!DOCTYPE html>
<html>
<head>
	
	<?php
		include ("../safe/db-util.php") ;
		include (PHP_ROOT . "/includes/head-first.incl.html") ;
	?>

	<title>Membership Admin: Unrenewed 2015 Members List</title>
	
	<?php include (PHP_ROOT . "/includes/head-2nd.incl.html") ; ?>
	
	<?php include (PHP_ROOT . "/js/header-pictures.js") ; ?>
	
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

	<?php include ("../includes/club-membership.incl.html") ; ?>

		<section>
			<p class="centered"><a href="MembAdmin.html">Membership Admin Page</a></p>
			
			<?php
				date_default_timezone_set("America/Vancouver");
				
				$when = date("Y-m-d");
				
				echo "<p>Unrenewed 2015 KMC Members : " . $when . "</p>\n";
				echo "<pre>\n";
				
				$sql = "SELECT DistinctName,Email FROM kmcweb_0_kmc.Member where Year = '2015' ORDER BY DistinctName";

				try {
					$conn = new PDO("mysql:host=127.0.0.1;db-name=kmcweb_0_web","kmcwe_kmc","zlika9p");
				}
				catch (PDOexception $e) {
					echo $e->getMessage() . "\n";
					echo $e->getCode() . "\n";
					echo $e->getLine() . "\n";
					exit;
				}
				
				$stuff = $conn->prepare($sql);
				$stuff->execute();
				$count = 0;
				
				echo "<table class='narrow'>\n" ;
				while ( $name = $stuff->fetch() ) {
					$count += 1;
					
					echo "<tr><td>" . $name['DistinctName'] . "</td><td>" . $name['Email'] . "</td></tr>\n" ;
					

				}
				echo "</table>\n</pre>";
				echo "<p>" . $count . " members who have not yet renewed their membership.</p>";
				
			?>

		<p class="centered"><a href="MembAdmin.html">Membership Admin Page</a></p>
			
		</section>
	
	</div>

	<footer>

	<?php include ("../../includes/club-membership.incl.html") ; ?>

	</footer>

</div> <!-- end master -->

</body>
</html>