<?php		// select_dates.php
	include 'date_util.php' ;
	include  'util.php' ;
	
//	echo "<pre>" . print_r($_COOKIE,TRUE) . "</pre>";
//	echo "<pre>" . $_COOKIE['PHPSESSID'] . "</pre>";
	session_start();
	
	$_SESSION['session'] = session_id();
 
    $_SESSION["start_day"] = $_POST["start_day"];
    $_SESSION["start_month"] = $_POST["start_month"];
    $_SESSION["start_year"] = $_POST["start_year"];
    $_SESSION["num_persons"] = 1;
    
 //   echo "<pre>" . print_r($_SESSION,TRUE) . "</pre>";
 //   echo "<pre>" . print_r($_GET,TRUE) . "</pre>";
    
      // check to see if date is in the past

    date_default_timezone_set( 'America/Los_Angeles');
    
    $wanted = strtotime($_POST["start_year"] . '-' . $_POST["start_month"] . '-' . $_POST["start_day"]);
//    echo "<pre>" . $wanted . "</pre>";
//    echo "<pre>" . $_SERVER['REQUEST_TIME'] . "</pre>";
    if ($wanted > $_SERVER['REQUEST_TIME']) { echo "<pre>date is bigger</pre>"; }

	$goahead = "hut_availability.php?id=" . $_GET['session_id'] ;
	$goback = "past_date.php?id=" . $_GET['session_id'] ;
	
//	echo "<pre>" . $goahead . "  :  " . $goback . "</pre>";

	if ($wanted > $_SERVER['REQUEST_TIME']) {
		header("Location: " . $goahead);   // go on to pick a hut, date and number of bunks
	} else {
		header("Location: " . $goback);
    }

 ?>
