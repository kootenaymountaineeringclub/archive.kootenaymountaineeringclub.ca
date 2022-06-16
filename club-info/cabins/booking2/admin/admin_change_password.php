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
	$pw = db_get_password($_SESSION["admin_id"]);
	$old_pw = $_REQUEST["password0"];
	$new_pw1 = $_REQUEST["password1"];
	$new_pw2 = $_REQUEST["password2"];

	include("AdminHeader.html");

	$display_block = "<p>Welcome " . $_SESSION["admin_name"] . "</p>";
	// echo $_SESSION["admin_id"] . $pw . " " . $new_pw1 . " " . $new_pw2;
	if (strcmp($pw, $old_pw) != 0)
	{
		echo "<p>Password incorrect</p>";
	}
	else
	{
		if (strcmp($new_pw1, $new_pw2) == 0)
		{
			db_update_password($_SESSION["admin_id"], $new_pw1);
			echo "p>Password has been changed.</p>";
		}
		else
		{
			echo "<p>First new password not equal to second new password</p>";
		}

	}

	include ( "AdminFooter.php");
	
 ?>
