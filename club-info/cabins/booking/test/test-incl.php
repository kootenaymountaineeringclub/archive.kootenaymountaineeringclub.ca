<?php 
		session_start();
		$_SESSION['include-call'] += 1;
		$_SESSION['session_id'] = session_id();
		global $booking;
?>

