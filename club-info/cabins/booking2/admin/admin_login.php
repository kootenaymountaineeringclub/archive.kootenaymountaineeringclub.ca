<?php include "AdminHeader.php" ; ?>
	

<?php
	session_start();
	
	include "admin_util.php" ;

	global $SAVEDSESSION;
	$SAVEDSESSION = array();

	// is there an id and password? No -> back to login
	// are they a good admin? Yes -> to admin menu. No -> back to login
	
// Check required fields on form
	if ((!isset($_POST["username"])) || (!isset($_POST["password"])))
	{
		header("Location: AdminLogin.html");
		exit;
	}
	
	admin_db_connect(); // Conect to MySQL and select database
	
	$result = db_admin_validate_user($_POST["username"], $_POST["password"]);
	
	if (mysql_num_rows($result) == 1)
	{ // User is a valid administrator
		db_inc_admin_login_sucessfull();
		session_regenerate_id(); // Get new Session ID
		$_SESSION = array(); // remove all old values
		$_SESSION['id'] = session_id();
		$info = mysql_fetch_array($result);
		$_SESSION["admin"] = true;
		$_SESSION["f_name"] = $info["FirstName"];
		$_SESSION["l_name"] = $info["LastName"];
		$admin_name = stripslashes($info["FirstName"]) . " " . stripslashes($info["LastName"]);
		$_SESSION["admin_name"] = $admin_name;
		$_SESSION["admin_id"] = stripslashes($info["LoginID"]);
		
		$SAVEDSESSION = $_SESSION ;
		
		save_session(session_id());
	}
	else
	{ // User is a NOT valid administrator
		db_inc_admin_login_failed();
		header("Location: AdminLogin.html");
		exit;
	}
	
//	display_stuff();
	
?>


<p>Bienvenue <?php echo $_SESSION["f_name"] ?>!</p>

<?php 
	$stat_link = "admin_stats.php?id=" . $_SESSION['id'] ;
	$season_link = "season-list.php?id=" . $_SESSION['id'] ;
	$start_link = "start_dates.php?id=" . $_SESSION['id'] ;
	$pass_link = "AdminChangePassword.php?id=" . $_SESSION['id'] ;
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
