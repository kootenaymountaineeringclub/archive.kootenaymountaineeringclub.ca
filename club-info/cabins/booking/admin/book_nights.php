<?php
//	include "AdminHeader.php" ;
	
	session_start();
	
	include("admin_util.php");

	global $SAVEDSESSION;
	get_session(session_id());
	$_SESSION = $SAVEDSESSION;
	
	$hut_id = $_REQUEST["hut"];
	$hut_name = $_REQUEST["hut_name"];
	$day = $_REQUEST["day"];
	$month = $_REQUEST["month"];
	$year = $_REQUEST["year"];
	$num_persons = $_REQUEST["num_persons"];
	
	$sd = $_REQUEST["sd"];
	$sm = $_REQUEST["sm"];
	$sy = $_REQUEST["sy"];
	
	$count = $_REQUEST["count"];
	$count += 1;
	$_SESSION['count'] += 1;
	$_SESSION['run-book'] += 1;
	
	$returnLink = "hut_availability.php?sd=" . $sd . "&sm=" . $sm . "&sy=" . $sy . "&count=" . $count;

	$booked_date = mktime(0,0,0,$month,$day,$year);
	$thedate = date("Y-m-d",$booked_date);

	admin_db_connect();
		
//echo "<p>For add: " . $hut_id . " - " . date("Y-m-d", $booked_date) . " - " . $num_persons . "</p>";
		
		$sql = db_add_to_cart($hut_id, date("Y-m-d", $booked_date), $num_persons);

		$SAVEDSESSION = $_SESSION;
		save_session(session_id());

		header("Location: " . $returnLink);
		
?>
