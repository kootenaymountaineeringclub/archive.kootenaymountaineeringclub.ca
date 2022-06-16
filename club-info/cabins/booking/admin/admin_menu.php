<?php
	// Incude the Utility functions
	include("admin_util.php");
	session_start();
	
	global $SAVEDSESSION;
	get_session($_REQUEST['id']);
	$_SESSION = $SAVEDSESSION;

	// Connect to kmc_huts database
	
	admin_db_connect();
	
	if ($_SESSION["admin"] !== TRUE)
	{
		db_inc_admin_login_failed();
		header("Location: AdminLogin.html");
		exit;
	}

?>

<?php include("AdminHeader.php"); ?>
	
<p>Bienvenue <?php echo $_SESSION["f_name"] ?>!</p>

<?php 
	$stat_link = "admin_stats.php?id=" . $_SESSION['id'] ;
	$season_link = "season-list.php?id=" . $_SESSION['id'] ;
	$start_link = "start_dates.php?&id=" . $_SESSION['id'] ;
	$pass_link = "AdminChangePassword.php?&id=" . $_SESSION['id'] ;
	$log_link = "admin_logout.php?id=" . $_SESSION['id'] ;

?>

<div id="admin-menu">
	<ul>
		<li><a href="<?php echo $stat_link ?>">Bookings Statistics</a></li>
		<li><a href="<?php echo $season_link ?>">Ongoing Season Summary</a></li>
		<li><a href="<?php echo $start_link ?>">Book Complimentary Nights</a></li>
<!--
		<li>Year by year comparison (not yet)</li>
		<li><a href="<?php echo $pass_link ?>">Change your password</a></li>
		<li><a href="<?php echo $log_link ?>">Sign out</a></li>
-->
	</ul>
</div>

<?php include ("AdminFooter.php") ; ?>
