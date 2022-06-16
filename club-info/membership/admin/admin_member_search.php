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

	<?php include ("../../includes/club-membership.incl.html") ; ?>	
	
	<section>
			<p class="centered"><a href="MembAdmin.php">Membership Admin Page</a></p>

		
		<div class="formPart centered" id="type">
			
			<form method="post" action="member_search.php">
				<label for="memb_numb">Membership number is </label><input class="text"  type="text" id="memb_numb" name="memb_numb">
				<p> ... or ... </p>
				<label for="memb_name">Member last name is </label><input class="text"  type="text" id="memb_name" name="memb_name">
				
				<p><span><input type="submit" class="formButton" value="Search..."></span></p>
			</form>
			
		</div>
			<p class="centered"><a href="MembAdmin.php">Membership Admin Page</a></p>

		
	</section>
	
	</div>

	<footer>

	<?php include ("../../includes/club-membership.incl.html") ; ?>

	</footer>

</div> <!-- end master -->

</body>
</html>