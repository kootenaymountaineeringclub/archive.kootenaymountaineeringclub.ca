<!DOCTYPE html>
<html>
<head>
	<?php include("util.php");
				include (PHP_ROOT . "/includes/head-first.incl.html") ; ?>

	<title>Kootenay Mountaineering Club Membership Form</title>
	
	<?php include (PHP_ROOT . "/includes/head-2nd.incl.html") ; ?>
	<?php include ("../includes/MembershipBackgroundImage.incl.html") ; ?>
	
	<link rel="stylesheet" type="text/css" href="/css/membership-styles.css">


	<link rel="stylesheet" type="text/css" href="/css/membership-styles.css">
	
	<header>
	
		<?php include ("../../includes/page-header-club.incl.html") ;
					include (PHP_ROOT . "/includes/header-contents.incl.html") ; ?>
		
	</header>
	
	<div id="content">
	
	<?php include ("../includes/club-membership.incl.html") ;
	
  	$logfile = fopen("logs/" . $_SESSION['MembID'] . ".cancel.txt" , 'w');

		fwrite ($logfile, "Get Express Checkout:\n" . print_r($resArrayGetExpressCheckout,TRUE) . "\n\n");
		fwrite ($logfile, "Do Express Checkout:\n" . print_r($resArrayDoExpressCheckout,TRUE) . "\n\n");
		fwrite ($logfile, "Session:\n" . print_r($_SESSION,TRUE) . "\n");
		fclose ($logfile);
		session_unset();   // free all session variables
		session_destroy(); //destroy session
		?>
 	
	<section>

			<h2>Kootenay Mountaineering Club Membership was cancelled at PayPal</h2>

			<div class="centered">
			<p>You're welcome to try again.</p>
			<p><a href="../membership-form.html">KMC Membership Form</a></p>
			
			<p>If you did not just intentionally cancel the PayPal transaction, please email <a href="mailto:website@kootenaymountaineeringclub.ca">the KMC website manager</a> with a complete description of what happened that did not work for you.</p>
			
			<p>Thank you.</p>
			
			</div>
			
	</section>
</div> <!-- end content -->

<footer>

<?php include ("../includes/club-membership.incl.html") ; ?>

</footer>

</div> <!-- end master -->

</body>
</html>
