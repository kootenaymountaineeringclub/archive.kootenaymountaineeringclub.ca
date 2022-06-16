<?php
require_once 'util.php';

	session_start ();
	debug_log("in adminSetYear.php"); 
	if (!isset($_REQUEST['year']))
	{
		debug_log("adminSetYear.php: year not set in REQUEST");
		die("adminSetYear.php: year not set in REQUEST");
	}
	db_connect();	
	db_update_year($_REQUEST['year']);
	echo build_success_status();
?>