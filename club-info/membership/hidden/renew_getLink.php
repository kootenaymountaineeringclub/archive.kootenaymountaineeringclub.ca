<!DOCTYPE html>
<html>

<head>
	<?php
		include ("../safe/db-util.php") ;
		include (PHP_ROOT . "/includes/head-first.incl.html") ;
	?>

	<title>KMC: Club Info - Membership Renewal</title>
	
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
		
	<?php 
		require_once("util.php");
		
		if ($_POST['email'] !== "") {
			db_connect();
			$sql = 'SELECT MembershipID from Member where Email = "' . $_POST['email'] . '"';
			$res = mysql_query($sql);
			if (!$res) {
		 		echo "<p>SELECT failed: SQL: " . $sql . " Error: " . mysql_error() . "</p>";
		 	} elseif (mysql_num_rows($res) == 0) {
				 	echo "<p>No membership was found with that email address: " . $_POST['email'] . "</p>";
			} else {
				$rows = mysql_fetch_assoc($res);
				
				$memb_id = $rows['MembershipID'];
				$link = random_password();
				
				$sql = "INSERT into RenewLinks (MembershipID, Link) values (" . $memb_id . ",'" . $link . "')";
				$res = mysql_query($sql);
				
				$link_full = "https://www.kootenaymountaineeringclub.ca/club-info/membership/php/renew-membership-form.php?link=" . $link ;
				
				$mail_text = "Use this link to access your membership renewal process:\n\t" . $link_full . "\n";
				$mail_text .= "This is a one time only link.\n" ;
				mail($_POST['email'],'KMC Membership Renewal Link',$mail_text);
				echo "<p>A link has been emailed.</p>";
				
			}

		} else {
			echo "<p>Oops. An email address is needed to make this work.</p>";
		}
		
	?>	
		
	</section>
</div> <!-- end content -->

<footer>

	<?php include ("../includes/club-membership.incl.html") ; ?>	

</footer>

</div> <!-- end master -->

</body>
</html>