<?php
	require_once 'admin-util.php';
	
	session_start();
	
	// Check required fields
	if ((!isset($_REQUEST["user"])) || (!isset($_REQUEST["password"])))
	{
		header("Location: admin-admin-login.html");	
	}
	
	$upw = $_REQUEST['password'];
	$epw = md5($upw);

	db_connect(); // Conect to MySQL and select database

	$pw = db_get_user_password($_REQUEST["user"]);

	if ($epw  == $pw)
	{ // User is a valid administrator
		success(false);
	}
	else
	{ 
		if ($pw == "") // Check if Password is blank
		{
			debug_log("login.php: user " . $_REQUEST['user'] . " must change password");
			success(true); // login but they must reset their password
		}
		else
		{ // User is a NOT valid administrator
			echo build_fail_status("No Args");
			debug_log("Login of " . $_REQUEST['user'] . ' failed');
			echo build_fail_status("");
		}
	}
	
	function success($reset_password)
	{
		$msg = "";
		if ($reset_password)
			$msg = "reset password";
		session_regenerate_id(); // Get new Session ID
		$_SESSION = array(); // remove all old values
		$_SESSION["admin"] = true;
		debug_log("Login of " . $_REQUEST['user'] . ' successful');
		echo build_status('SUCCESS', $msg);;
	}
?>
