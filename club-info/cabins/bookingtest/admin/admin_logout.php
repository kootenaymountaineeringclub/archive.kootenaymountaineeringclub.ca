<?php
	// Incude the Utility functions
	include("admin_util.php");
	session_start();
	session_regenerate_id();
	$_SESSION = array(); // remove all old values
	header("Location: http:/kootenaymountaineeringclub.ca/club-info/cabins/");
	exit;
?>
