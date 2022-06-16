<!DOCTYPE html>
<html>
<head>
	
	<?php
		include ("../safe/db-util.php") ;
		include (PHP_ROOT . "/includes/head-first.incl.html") ;
	?>

	<title>Membership Admin: List Memberships</title>
	
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
			<p class="centered"><a href="MembAdmin.php">Membership Admin Page</a></p>
			
			<?php
				date_default_timezone_set("America/Vancouver");
				
				$when = date("Y-m-d");
				
				$outfile = PHP_ROOT . "/club-info/documents/executive-files/KMC-Full-Membership-List.txt";
				
				$namelist = fopen($outfile,"w");
				if (!$namelist ) die ("No file\n");
				
				fwrite($namelist,"KMC Members: " . $when . "\n\n");
				fwrite($namelist,"Year\tDistinctName\tMembershipID\tMembershipType\tEmail\tPhone\tPrivateName\tStreetAddress\tStreetAddress2\tCity\tProvince\tPostalCode\tKMCNewsletter\tFMCBCNewsletter\n");
				
				echo "<p>KMC Members " . $when . "</p>\n";
				echo "<pre>\n";
				echo "Year\tDistinctName\tMembershipID\tMembershipType\tEmail\tPhone\tPrivateName\tStreetAddress\tStreetAddress2\tCity\tProvince\tPostalCode\tKMCNewsletter\tFMCBCNewsletter\n";
				
				$sql = "SELECT DISTINCT(DistinctName) FROM kmcweb_0_kmc.Member ORDER BY DistinctName";

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
				
				while ( $name = $stuff->fetch() ) {
					$count += 1;
					
					$sql = "SELECT * from kmcweb_0_kmc.Member where DistinctName = '" . $name["DistinctName"] . "'";
				
					$info = $conn->prepare($sql);
					$info->execute();
					
					$memberinfo = $info->fetch();

					$member = $memberinfo["Year"] . "\t";					
					$member .= $memberinfo["DistinctName"] . "\t";					
					$member .= $memberinfo["MembershipID"] . "\t";
					$member .= $memberinfo["MembershipType"] . "\t";
					$member .= $memberinfo["Email"] . "\t";
					$member .= $memberinfo["Phone"] . "\t";
					$member .= $memberinfo["PrivateName"] . "\t";
					$member .= $memberinfo["StreetAddress"] . "\t";
					$member .= $memberinfo["StreetAddress2"] . "\t";
					$member .= $memberinfo["City"] . "\t";
					$member .= $memberinfo["Province"] . "\t";
					$member .= $memberinfo["PostalCode"] . "\t";
					$member .= $memberinfo["KMCNewsletter"] . "\t";
					$member .= $memberinfo["FmcbcNewsletter"];
					$member .= "\n";
					
					echo "$member";
					fwrite($namelist,$member);

				}
				echo "\n\n</pre>";
				echo "<p>" . $count . " members.</p>";
				
				echo "<p>A text tab-delimited file is at <a href='/club-info/documents/executive-files/KMC-Full-Membership-List.txt'>KMC Full Membership List</a>. Login is the KMCexecutive login.</p>";
				
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