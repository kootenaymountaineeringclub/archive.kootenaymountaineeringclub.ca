<?php
	header("Content-type: text/xml");
	require_once 'util.php';

	session_start ();
	debug_log('In adminChangePassword.php');
	$opw = md5($_REQUEST['password1']);
	$npw = md5($_REQUEST['password2']);
	$force = false;
	if (isset($_REQUEST['force']))
		$force = true;
	db_connect();
	db_update_password($_REQUEST['user'],$opw,$npw, $force);
	echo build_success_status();
?>