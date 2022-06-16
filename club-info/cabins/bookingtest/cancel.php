<?php 

	require_once ("paypal_functions.php"); 
	require ("util.php");

	include('header.php');
	
	db_connect(); 
	db_delete_cart($_SESSION['session']);
	
	$logfile = fopen("logs/" . $_SESSION['session'] . ".txt" , 'w');
	fwrite ($logfile, "Users canceled payment\n\n");
	fwrite ($logfile, print_r($_SESSION,TRUE) . "\n");
	fclose ($logfile);
	session_unset();   // free all session variables
	session_destroy(); //destroy session

?>
	
	<div class="announcement centre-figure-small">
		<p>You cancelled your payment.</p>
		<p>If you wish to restart the process, go back to <a href="select_dates.php">booking date selection page.</a></p>
	</div>
	
<?php
	include html_footer_strip() ;
?>
