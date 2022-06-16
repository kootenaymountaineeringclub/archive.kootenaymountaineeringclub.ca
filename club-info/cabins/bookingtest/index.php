<?php
		session_start();
		$_SESSION = array(); // remove all old values
        header("Location: select_dates.php"); 
?>