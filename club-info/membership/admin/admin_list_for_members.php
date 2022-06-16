<!DOCTYPE html>
<html>
<head>
	
	<?php
		include ("../safe/db-util.php") ;
		include (PHP_ROOT . "/includes/head-first.incl.html") ;
	?>

	<title>Membership Admin: Membership List For Members</title>
	
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
				
				$outfile = PHP_ROOT . "/club-info/documents/member-files/member/KMC-Membership-List.txt";
				
				$namelist = fopen($outfile,"w");
				if (!$namelist ) die ("No file\n");
				
				fwrite($namelist,"KMC Members who do not have a private name setting: " . $when . "\n\n");
				fwrite($namelist,"DistinctName\tEmail\tPhone\tStreetAddress\tStreetAddress2\tCity\tProvince\tPostalCode\n");
				
				echo "<p>KMC Members " . $when . "</p>\n";
				echo "<pre>\n";
				echo "DistinctName\tEmail\tPhone\tStreetAddress\tStreetAddress2\tCity\tProvince\tPostalCode\n";
				
				$sql = "SELECT DISTINCT(DistinctName) FROM kmcweb_0_kmc.Member where PrivateName = 0 ORDER BY DistinctName";

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
				
				while ( $names = $stuff->fetch() ) {
					$count += 1;
					
					$sql = "SELECT * from kmcweb_0_kmc.Member where DistinctName = '" . $names["DistinctName"] . "'";
				
					$info = $conn->prepare($sql);
					$info->execute();
					
					$memberinfo = $info->fetch();
					
					$member = $memberinfo["DistinctName"] . "\t";
					$member .= $memberinfo["Email"] . "\t";
					$member .= $memberinfo["Phone"] . "\t";
					$member .= $memberinfo["StreetAddress"] . "\t";
					$member .= $memberinfo["StreetAddress2"] . "\t";
					$member .= $memberinfo["City"] . "\t";
					$member .= $memberinfo["Province"] . "\t";
					$member .= $memberinfo["PostalCode"] . "\t";
					$member .= "\n";
					
					echo "$member";
					fwrite($namelist,$member);

				}
				echo "\n\n</pre>";
				echo "<p>" . $count . " members who do not have a private name setting.</p>";
				
				echo "<p>You have just updated the Member's membership List. A tab-delimited text file was written to the <a href='/club-info/documents/member-files/'>members only document area</a>. Login is the KMCmember login.</p>";
				
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