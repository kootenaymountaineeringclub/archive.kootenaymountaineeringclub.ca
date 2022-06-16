<!DOCTYPE html>
<html>
<head>
	<?php
		// define('PHP_ROOT','/var/www/vhosts/kootenaymountaineeringclub.ca/httpdocs');
		define('PHP_ROOT','/USERS/Shared/KMCWebNames');
		include (PHP_ROOT . "/includes/head-first.incl.html") ;
	?>

	<title>Membership Admin: List Memberships</title>
	
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
		
		<?php
			
		date_default_timezone_set("America/Vancouver");
		$when = date("Y-m-d");
			
		echo "<p>ListBox subscriber list " . $when . "</p>\n";	
		echo "<p>Note: an attempt has been made to remove the email addresses of those who have unsubscribed via ListBox.</p>\n";
		
		$count_good = 0;
		$good_emails = "";
		
		$count_unsubed = 0;
		$unsubed_emails = "";
		
		try {
			$conn = new PDO("mysql:host=127.0.0.1;db-name=kmcweb_0_web","kmcwe_kmc","zlika9p");
		}
		catch (PDOexception $e) {
			echo $e->getMessage() . "\n";
			echo $e->getCode() . "\n";
			echo $e->getLine() . "\n";
			exit;
		}
		
		$sql = "SELECT DISTINCT(Email) FROM Member where Email is not null ORDER BY Email";

		try {
		$stuff = $conn->prepare($sql);
		$stuff->execute();
		}
		catch (PDOexception $e) {
			echo $e->getMessage() . "\n";
			echo $e->getCode() . "\n";
			echo $e->getLine() . "\n";			
		}
		
		while ( $distinct = $stuff->fetch() ) {
			
			print_r($distinct);
			
/*
			$sql = "SELECT email from listbox_unsubscribed where email = '" . $distinct['Email'] . "'";
			$info = $conn->prepare($sql);
			$info->execute();
			
			$unsubed = $info->fetch();
			print_r($unsubed);
			
			if ( $unsubed["email"] == $distinct['Email'] ) {
				echo "<p>In unsubed</p>";
				$unsubed_emails .= $distinct['Email'] . "\n";
				$count_unsubed += 1;
			}
			else {
				echo "<p>In good</p>";
				$good_emails .= $distinct['Email'] . "\n";
				$count_good += 1;
			}
*/
		}
		echo "<p>" . $count_good . " emails for ListBox.</p>";
		echo "<pre>\n" . $good_emails . "</pre>\n";
		
		echo "<p>" . $count_unsubed . " emails unsubscribed from ListBox.</p>";
		echo "<pre>\n" . $unsubed_emails . "</pre>\n";
		
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