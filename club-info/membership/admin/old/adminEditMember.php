<?php
require_once 'util.php';

	session_start ();
	debug_log("in adminEditMember.php"); 
	debug_log("adminEditMember.php: id: " . $_REQUEST['id']); 
	debug_log("adminEditMember.php: parentId: " . $_REQUEST['parentId']); 
	db_connect(); // Connect to KMC Database
	db_update_member();
	if (isset($_REQUEST['paymentStatus']))
	{
		$stat = $_REQUEST['paymentStatus'];
		$cancelled = false;
		if ($stat == "PayPay Cancelled")
			$cancelled = true;
		if ($stat == "Cheque Returned")
			$cancelled = true;
		if ($stat == "Payment Returned")
			$cancelled = true;
		if ($cancelled)
		{
			db_cancel_payment($_REQUEST['id'], $_REQUEST['year'],  $stat);
			db_cancel_co_member_payment($_REQUEST['id'], $_REQUEST['year'],  $stat);
			if (!($_REQUEST['parentId'] == "" || $_REQUEST['parentId'] == "null"))
			{
				db_cancel_payment($_REQUEST['parentId'], $_REQUEST['year'],  $stat);
				db_cancel_co_member_payment($_REQUEST['parentId'], $_REQUEST['year'],  $stat);
			}
		}
	}

	echo build_success_status();
?>
