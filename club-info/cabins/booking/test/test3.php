<html>
<head></head>
<body>
	<h3>Test 3</h3>
	
	<?php
	include('test-incl.php');
		global $booking;
		echo "<pre>id : " . session_id() . "</pre>\n";
		echo "<pre>request : " . print_r($_REQUEST,TRUE) . "</pre>\n";
		echo "<pre>session : " . print_r($_SESSION,TRUE) . "</pre>\n";
		
		$booking["test3"] = 'yep3';
		echo "<pre>booking : " . print_r($booking,TRUE) . "</pre>\n";
		$_REQUEST['test'] += 1;
		echo "<form action='test3.php?test=" . $_REQUEST["test"] . "'><input type='submit' value='go to 3'></form>";
		
	?>
</body>
</html>
