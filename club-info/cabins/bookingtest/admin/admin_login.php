<?php

	// is there an id and password? No -> back to login
	// are they a good admin? Yes -> to admin menu. No -> back to login
	
	include ("admin_util.php");
	session_start();
// Check required fields on form
	if ((!isset($_POST["username"])) || (!isset($_POST["password"])))
	{
		header("Location: AdminLogin.html");
		exit;
	}
	db_connect(); // Conect to MySQL and select database
	$result = db_admin_validate_user($_POST["username"], $_POST["password"]);
	if (mysql_num_rows($result) == 1)
	{ // User is a valid administrator
		db_inc_admin_login_sucessfull();
		session_regenerate_id(); // Get new Session ID
		$_SESSION = array(); // remove all old values
		$info = mysql_fetch_array($result);
		$_SESSION["admin"] = true;
		$_SESSION["f_name"] = $info["FirstName"];
		$_SESSION["l_name"] = $info["LastName"];
		$admin_name = stripslashes($info["FirstName"]) . " " . stripslashes($info["LastName"]);
		$_SESSION["admin_name"] = $admin_name;
		$_SESSION["admin_id"] = stripslashes($info["LoginID"]);
		header("Location: admin_menu.php");
		exit;
	}
	else
	{ // User is a NOT valid administrator
		db_inc_admin_login_failed();
		header("Location: AdminLogin.html");
		exit;
	}
?>
