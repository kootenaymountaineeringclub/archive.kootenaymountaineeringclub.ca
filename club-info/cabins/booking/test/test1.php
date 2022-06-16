<?php
	include('test-incl.php');
	global $booking;
	if (! $booking['id']) $booking = Array();
	$_SESSION['id1'] = session_id();
	$_SESSION['what'] = "Something";
	$booking['id'] = session_id();
	$booking['call-count'] = 1;
	
	header("Location: test2.php?test=1&id=" . session_id());
?>
<html>
<head></head>
<body>
	<h1>TEST 1</h1>
</body>
</html>
