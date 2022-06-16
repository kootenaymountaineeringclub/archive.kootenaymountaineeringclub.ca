<?php
	
	require_once 'admin-util.php';

	db_connect();
	
	echo("<p>");
	echo "Current membership year: " . WhichMembershipYear();
	echo("<br>\n");
	
	$sql = "SELECT count(DISTINCT(MembershipID)) from Member";
	$memb_count = db_select_single_row($sql);
	echo "Memberships: " . array_pop($memb_count) . "<br>\n";
	
	$sql = "SELECT count(MembershipType) from Member where MembershipType = 'Individual'";
	$memb_count = db_select_single_row($sql);
	echo "Individual Memberships: " . array_pop($memb_count) . "<br>\n";
	
	$sql = "SELECT count(MembershipType) from Member where MembershipType = 'Couple'";
	$memb_count = db_select_single_row($sql);
	echo "Couple  Memberships: " . (array_pop($memb_count) / 2) . "<br>\n";
	
	$sql = "SELECT count(TransactionID) from Member";
	$memb_count = db_select_single_row($sql);
	echo "Total Members: " . array_pop($memb_count) . "</p>\n";
	
?>