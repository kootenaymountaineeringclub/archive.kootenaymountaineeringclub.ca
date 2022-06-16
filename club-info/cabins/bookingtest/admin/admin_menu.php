<?php
	// Incude the Utility functions
	include("admin_util.php");
	session_start();
	
	// Connect to kmc_huts database
	
	db_connect();
	
	if (!admin_check_logged_in())
	{
		db_inc_admin_login_failed();
		header("Location: AdminLogin.html");
		exit;
	}

?>

<?php include("AdminHeader.php"); ?>
	
<p>Bienvenue <?php echo $_SESSION["f_name"] ?>!</p>

<div id="admin-menu">
	<ul>
		<li><a href='admin_stats.php'>Bookings Statistics</a></li>
		<li><a href='season-list.php'>Ongoing Season Summary</a></li>
		<li>Year by year comparison (not yet)</li>
		<li><a href='../select_dates.php'>Book Complimentary Nights</a></li>
		<li><a href='AdminChangePassword.php'>Change your password</a></li>
		<li><a href='admin_logout.php'>Sign out</a></li>
	</ul>
</div>

<?php include ("AdminFooter.php") ; ?>
