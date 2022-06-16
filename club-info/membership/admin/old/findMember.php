<?php
	header("Content-type: text/xml");
	require_once 'util.php';
	session_start();
	debug_log('In findMember.php');
	db_connect();
	debug_log('findMember.php: Connected to database');
	$display_block = '<?xml version="1.0"?>';
	$display_block .= '<php_result>';
	$display_block .= '<status>SUCCESS</status>';
	$display_block .= db_findMembers();
	$display_block .= '</php_result>';
	echo $display_block;
?>
