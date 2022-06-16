<!DOCTYPE html>
<html>
<head>
	
	<?php
		include ("../safe/db-util.php") ;
		include (PHP_ROOT . "/includes/head-first.incl.html") ;
	?>

	<title>Membership Admin: Membership List of Private Name Members</title>
	
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
				
				$outfile = PHP_ROOT . "/club-info/documents/executive-files/KMC-Private-Names-List.txt";
				
				$namelist = fopen($outfile,"w");
				if (!$namelist ) die ("No file\n");
				
				fwrite($namelist,"KMC Members with a private name: " . $when . "\n\n");
				fwrite($namelist,"LastName\tFirstName\tNickName\tStreetAddress\tStreetAddress2\tCity\tProvince\tPostalCode\tEmail\tPhone\n");
				
				echo "<p>KMC Members " . $when . "</p>\n";
				echo "<pre>\n";
				echo "LastName\tFirstName\tNickName\tStreetAddress\tStreetAddress2\tCity\tProvince\tPostalCode\tEmail\tPhone\n";
				
				$sql = "SELECT DISTINCT(DistinctName) FROM kmcweb_0_kmc.Member where PrivateName = 1 ORDER BY DistinctName";

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
					
					$member = $memberinfo["LastName"] . "\t";
					$member .= $memberinfo["FirstName"] . "\t";
					$member .= $memberinfo["NickName"] . "\t";
					$member .= $memberinfo["StreetAddress"] . "\t";
					$member .= $memberinfo["StreetAddress2"] . "\t";
					$member .= $memberinfo["City"] . "\t";
					$member .= $memberinfo["Province"] . "\t";
					$member .= $memberinfo["PostalCode"] . "\t";
					$member .= $memberinfo["Email"] . "\t";
					$member .= $memberinfo["Phone"] . "\t";
					$member .= "\n";
					
					echo "$member";
					fwrite($namelist,$member);

				}
				echo "\n\n</pre>";
				echo "<p>" . $count . " members who do have a private name setting.</p>";
				
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