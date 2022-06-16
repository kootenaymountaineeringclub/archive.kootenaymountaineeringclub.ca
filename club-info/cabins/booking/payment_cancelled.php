<?php
  include("util.php");
  include("date_util.php");
  session_start();
  
  // Unset all of the session variables.
  $_SESSION = array();

  // If it's desired to kill the session, also delete the session cookie.
  // Note: This will destroy the session, and not just the session data!
  if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-42000, '/');
  }
  // Finally, destroy the session.
  session_destroy();
  
  $session = $_REQUEST["PHPSESSID"];
  db_connect();
  db_delete_cart($session);
  $display_block = "<p>Payment cancelled. No bookings made.</p>";
  $display_block .="<p>You may return to <a href='select_dates.php'>date selection</a> and try again.</p>";
  
  $cancel_log = fopen("admin/checkout.log","a");
  fwrite($cancel_log,$session . "\t" . "payment_cancelled\r\n");
  fclose($cancel_log);

?>

<!DOCTYPE html>
<html>
<head>
<!--#include virtual=/includes/head-first.incl.html -->

	<title>KMC: The Bonnington Cabins Booking System</title>
	
<!--#include virtual=/includes/head-2nd.incl.html -->

<body>

<div id="master">

<header>

	<!--#include virtual=../../includes/page-header-club.incl.html -->
	<!--#include virtual=/includes/header-contents.incl.html -->
	
</header>

<div id="content">

	<!--#include virtual=../../includes/club-bonnington.incl.html -->
	
	<section>
		<div class="announcement">
			<?php echo print_r($_REQUEST); ?>
		    <?php echo $display_block; ?>
		</div>
	</section>
</div> <!-- end content -->

<footer>

<!--#include virtual=/includes/kics-blurb.incl.html -->

</footer>

</div> <!-- end master -->

</body>
</html>
