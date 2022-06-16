<?php
	header("Content-type: text/xml");
	require_once 'util.php';
	session_start();
	debug_log('In adminStatistics.php');
	$year = $_REQUEST['year'];
	if ($year == "")
	{
		debug_log('adminStatistics.php: year not set');
		die('adminStatistics.php: year not set');
	}
	$last_year = (int)$year - 1;
	db_connect();
	debug_log('adminStatistics.php: Connected to database');
	$sql = "SELECT ";
	$sql .= "(SELECT COUNT(*) FROM Member WHERE Year = " . $year . ") AS tyMembers, "; 
	$sql .= "(SELECT COUNT(*) FROM Member WHERE Year = " . $last_year . ") AS lyMembers, "; 
	$sql .= "(SELECT COUNT(*) FROM Member WHERE Year = " . $year . " AND Enrollment = 'Online') AS tyOnline, ";
	$sql .= "(SELECT COUNT(*) FROM Member WHERE Year = " . $last_year . " AND Enrollment = 'Online') AS lyOnline, ";
	$sql .= "(SELECT COUNT(*) FROM Member WHERE Year = " . $year . " AND Enrollment = 'Mail') AS tyMail, ";
	$sql .= "(SELECT COUNT(*) FROM Member WHERE Year = " . $last_year . " AND Enrollment = 'Mail') AS lyMail, ";
	$sql .= "(SELECT COUNT(*) FROM Member WHERE Year = " . $year . " AND Active = 1) AS tyActive, ";
	$sql .= "(SELECT COUNT(*) FROM Member WHERE Year = " . $last_year . " AND Active = 1) AS lyActive, ";
	$sql .= "(SELECT COUNT(*) FROM Member WHERE Year = " . $year . " AND PaymentStatus = 'PayPal Pending') AS tyPPPending, ";
	$sql .= "(SELECT COUNT(*) FROM Member WHERE Year = " . $last_year . " AND PaymentStatus = 'PayPal Pending') AS lyPPPending, ";
	$sql .= "(SELECT COUNT(*) FROM Member WHERE Year = " . $year . " AND PaymentStatus = 'Cheque Returned') AS tyChequeReturned, ";
	$sql .= "(SELECT COUNT(*) FROM Member WHERE Year = " . $last_year . " AND PaymentStatus = 'Cheque Returned') AS lyChequeReturned, ";
	$sql .= "(SELECT COUNT(*) FROM Member WHERE Year = " . $year . " AND MembershipType = 'Single Adult' AND ParentID IS NULL) AS tySingle, ";
	$sql .= "(SELECT COUNT(*) FROM Member WHERE Year = " . $last_year . " AND MembershipType = 'Single Adult AND ParentID IS NULL') AS lySingle, ";
	$sql .= "(SELECT COUNT(*) FROM Member WHERE Year = " . $year . " AND MembershipType = 'Adult Couple' AND ParentID IS NULL) AS tyCouple, ";
	$sql .= "(SELECT COUNT(*) FROM Member WHERE Year = " . $last_year . " AND MembershipType = 'Adult Couple' AND ParentID IS NULL) AS lyCouple, ";
	$sql .= "(SELECT COUNT(*) FROM Member WHERE Year = " . $year . " AND MembershipType LIKE '%plus%'  AND ParentID IS NULL) AS tyWithChildren, ";
	$sql .= "(SELECT COUNT(*) FROM Member WHERE Year = " . $last_year . " AND MembershipType LIKE '%plus%' AND ParentID IS NULL) AS lyWithChildren, ";
	$sql .= "(SELECT COUNT(*) FROM Member WHERE Year = " . $year . " AND MembershipType LIKE '%Junior%') AS tyJunior, ";
	$sql .= "(SELECT COUNT(*) FROM Member WHERE Year = " . $last_year . " AND MembershipType LIKE '%Junior%') AS lyJunior, ";
	$sql .= "(SELECT COUNT(*) FROM Member WHERE Year = " . $year . " AND Voting = 1) AS tyVoting, ";
	$sql .= "(SELECT COUNT(*) FROM Member WHERE Year = " . $last_year . " AND Voting = 1) AS lyVoting, ";
	$sql .= "(SELECT COUNT(*) FROM Member WHERE Year = " . $year . " AND Child = 0) AS tyAdults, ";
	$sql .= "(SELECT COUNT(*) FROM Member WHERE Year = " . $last_year . " AND Child = 0) AS lyAdults, ";
	$sql .= "(SELECT COUNT(*) FROM Member WHERE Year = " . $year . " AND Child = 1) AS tyKids, ";
	$sql .= "(SELECT COUNT(*) FROM Member WHERE Year = " . $last_year . " AND Child = 1) AS lyKids, ";
	$sql .= "(SELECT SUM(Amount) FROM Member WHERE Year = " . $year . ") AS tyGrossAmount, ";
	$sql .= "(SELECT SUM(Amount) FROM Member WHERE Year = " . $last_year . ") AS lyGrossAmount";
	$row = db_select_single_row($sql);
	$display_block = '<?xml version="1.0"?>';
	$display_block .= '	<statistics>';
	$display_block .= '		<stat>';
	$display_block .= '			<name>Total Members</name>';
	$display_block .= '			<thisYear>' . $row["tyMembers"] .'</thisYear>';
	$display_block .= '			<prevYear>' . $row["lyMembers"] .'</prevYear>';
	$display_block .= '		</stat>';
	$display_block .= '		<stat>';
	$display_block .= '			<name>Active Members</name>';
	$display_block .= '			<thisYear>' . $row["tyActive"] . '</thisYear>';
	$display_block .= '			<prevYear>' . $row["lyActive"] . '</prevYear>';
	$display_block .= '		</stat>';
	$display_block .= '		<stat>';
	$display_block .= '			<name>Total Adults (over 19)</name>';
	$display_block .= '			<thisYear>' . $row["tyAdults"] . '</thisYear>';
	$display_block .= '			<prevYear>' . $row["lyAdults"] . '</prevYear>';
	$display_block .= '		</stat>';
	$display_block .= '		<stat>';
	$display_block .= '			<name>Total under 19</name>';
	$display_block .= '			<thisYear>' . $row["tyKids"] . '</thisYear>';
	$display_block .= '			<prevYear>' . $row["lyKids"] . '</prevYear>';
	$display_block .= '		</stat>';
	$display_block .= '		<stat>';
	$display_block .= '			<name>Voting Members</name>';
	$display_block .= '			<thisYear>' . $row["tyVoting"] . '</thisYear>';
	$display_block .= '			<prevYear>' . $row["lyVoting"] . '</prevYear>';
	$display_block .= '		</stat>';
	$display_block .= '		<stat>';
	$display_block .= '			<name>Single Adult Memberships</name>';
	$display_block .= '			<thisYear>' . $row["tySingle"] . '</thisYear>';
	$display_block .= '			<prevYear>' . $row["lySingle"] . '</prevYear>';
	$display_block .= '		</stat>';
	$display_block .= '		<stat>';
	$display_block .= '			<name>Couple Memberships</name>';
	$display_block .= '			<thisYear>' . $row["tyCouple"] . '</thisYear>';
	$display_block .= '			<prevYear>' . $row["lyCouple"] . '</prevYear>';
	$display_block .= '		</stat>';
	$display_block .= '		<stat>';
	$display_block .= '			<name>Junior Memberships</name>';
	$display_block .= '			<thisYear>' . $row["tyJunior"] . '</thisYear>';
	$display_block .= '			<prevYear>' . $row["lyJunior"] . '</prevYear>';
	$display_block .= '		</stat>';
	$display_block .= '		<stat>';
	$display_block .= '			<name>Memberships with Children</name>';
	$display_block .= '			<thisYear>' . $row["tyWithChildren"] . '</thisYear>';
	$display_block .= '			<prevYear>' . $row["lyWithChildren"] . '</prevYear>';
	$display_block .= '		</stat>';
	$display_block .= '		<stat>';
	$display_block .= '			<name>Enrolled online</name>';
	$display_block .= '			<thisYear>' . $row["tyOnline"] . '</thisYear>';
	$display_block .= '			<prevYear>' . $row["lyOnline"] . '</prevYear>';
	$display_block .= '		</stat>';
	$display_block .= '		<stat>';
	$display_block .= '			<name>Enrolled via mail</name>';
	$display_block .= '			<thisYear>' . $row["tyMail"] . '</thisYear>';
	$display_block .= '			<prevYear>' . $row["lyMail"] . '</prevYear>';
	$display_block .= '		</stat>';
	$display_block .= '		<stat>';
	$display_block .= '			<name>PayPal Pending</name>';
	$display_block .= '			<thisYear>' . $row["tyPPPending"] . '</thisYear>';
	$display_block .= '			<prevYear>' . $row["lyPPPending"] . '</prevYear>';
	$display_block .= '		</stat>';
	$display_block .= '		<stat>';
	$display_block .= '			<name>Cheque Returned</name>';
	$display_block .= '			<thisYear>' . $row["tyChequeReturned"] . '</thisYear>';
	$display_block .= '			<prevYear>' . $row["lyChequeReturned"] . '</prevYear>';
	$display_block .= '		</stat>';
	$display_block .= '		<stat>';
	$display_block .= '			<name>Gross Revenue</name>';
	$display_block .= '			<thisYear>$' . $row["tyGrossAmount"] . '</thisYear>';
	$display_block .= '			<prevYear>$' . $row["lyGrossAmount"] . '</prevYear>';
	$display_block .= '		</stat>';
	$display_block .= '	</statistics>';
	echo $display_block;
?>
