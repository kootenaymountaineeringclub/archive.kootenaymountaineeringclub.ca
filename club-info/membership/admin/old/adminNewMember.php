<?php
require_once 'util.php';

	session_start ();
	debug_log("in adminNewMember.php"); 
	db_connect(); // Connect to KMC Database
	$id = db_insert_member1();
	if (isset($_REQUEST['lastName2']))
		db_insert_member2($id);
	if (isset($_REQUEST['childLastName1']))
		db_insert_child1($id);
	if (isset($_REQUEST['childLastName2']))
		db_insert_child2($id);
	if (isset($_REQUEST['childLastName3']))
		db_insert_child3($id);
	if (isset($_REQUEST['childLastName4']))
		db_insert_child4($id);
	echo build_success_status();
?>
