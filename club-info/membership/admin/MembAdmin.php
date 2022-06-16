<!DOCTYPE html>
<html>
<head>
	<?php
		include ("../safe/db-util.php") ;
		include (PHP_ROOT . "/includes/head-first.incl.html") ;
	?>


	<title>Membership Admin</title>
	
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
			
			<h2>Membership Admin</h2>
			
			<ul>
				<li><a href="admin_member_search.php">Search for a member</a> - <em>Editing available when found.</em></li>
				<li><a href="admin-new-membership-form.html">Add a new membership</a></li>
				<li><a href="admin_list_members.php">Current membership list</a> for the Executive</li>
				<li><a href="admin_list_for_members.php">Current membership list</a> for Members</li>
				<li><a href="admin_list_private_members.php">Members with a Private Name setting</a></li>
			</ul>
			
	<?php include ("numbers.php"); ?>
	<?php include ("ages.php"); ?> 
		
	</section>
	
	</div> <!-- end content -->

	<footer>

	<?php include ("../../includes/club-membership.incl.html") ; ?>

	</footer>

</div> <!-- end master -->

</body>
</html>