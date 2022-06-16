<html>
<head></head>
<body>
	<h3>Test 2</h3>
	
	<?php
	include('test-incl.php');
	global $booking;
		$_SESSION['TEST1'] = TRUE;
		
		echo "<pre>id : " . session_id() . "</pre>\n";
		echo "<pre>request : " . print_r($_REQUEST,TRUE) . "</pre>\n";
		echo "<pre>session : " . print_r($_SESSION,TRUE) . "</pre>\n";
		
		$booking["test"] = $_REQUEST["test"];
		$booking["test2"] = 'yep2';
		echo "<pre>booking : " . print_r($booking,TRUE) . "</pre>\n";
		
		echo '<form action="test3.php?test=1"><input type="submit" value="go to 3"></form>';
		
	?>
</body>
</html>
