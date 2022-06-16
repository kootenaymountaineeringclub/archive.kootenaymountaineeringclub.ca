<!DOCTYPE html>
<html>
<head>
	
	<?php
		include ("../safe/db-util.php") ;
		include (PHP_ROOT . "/includes/head-first.incl.html") ;
	?>

	<title>Membership Admin: New Member List</title>
	
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
				
				echo "<p>New KMC Members " . $when . "</p>\n";	
				echo "<p>Note: because some people type their names differently from year to year there is no method of truly comparing one year to the next. There will be a few names here who we know for sure were members for many years.</p>\n";
				echo "<pre>\n";
				
				try {
					$conn = new PDO("mysql:host=127.0.0.1;db-name=kmcweb_0_web","kmcwe_kmc","zlika9p");
				}
				catch (PDOexception $e) {
					echo $e->getMessage() . "\n";
					echo $e->getCode() . "\n";
					echo $e->getLine() . "\n";
					exit;
				}
				
				$sql = "SELECT DISTINCT(DistinctName) FROM kmcweb_0_kmc.Member where Year = 2016 ORDER BY DistinctName";

				$stuff = $conn->prepare($sql);
				$stuff->execute();
				$count = 0;
				
				while ( $name = $stuff->fetch() ) {
					
					$who = $name['DistinctName'];
					$sql = "SELECT DistinctName from kmcweb_0_kmc.Member where DistinctName = '" . $who . "' and year = 2015";
				
					$info = $conn->prepare($sql);
					$info->execute();
					
					$memberinfo = $info->fetch();
					
					if ( ! isset ($memberinfo["DistinctName"])) {
						echo $who . "\n";
						$count += 1;
					}
				}
				echo "\n\n</pre>";
				echo "<p>" . $count . " members who did not have a 2015 membership.</p>";
				
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