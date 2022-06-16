<?php
	include "AdminHeader.php" ;
	
	include("admin_util.php");
//	include("util.php");
	include("date_util.php");
	
	$id = $_REQUEST['id'];
	
	session_start();
	global $SAVEDSESSION;
	get_session(session_id());
	$_SESSION = $SAVEDSESSION;
	
	if ($_POST) {
		$_SESSION['id'] = session_id();
		$_SESSION['sy'] = $_POST['sy'];
		$_SESSION['sm'] = $_POST['sm'];
		$_SESSION['sd'] = $_POST['sd'];
		$_SESSION['count'] = 0;
		$_SESSION['run-beds'] = 0;
		$_SESSION['run-avail'] = 0;
		$_SESSION['run-book'] = 0;
		$_SESSION['start'] = 0;
	}
	
	$_SESSION['run-avail'] += 1;
	
	admin_db_connect();

	$session = session_id();

    date_default_timezone_set( 'America/Los_Angeles');
    
	$sd = $_REQUEST["sd"];
	$sm = $_REQUEST["sm"];
	$sy = $_REQUEST["sy"];
	$count = $_REQUEST["count"];

	$when = "$sy-$sm-$sd" ;
		
	$wanted = mktime(0,0,0,$sm,$sd,$sy);
	$test = date("Y-m-d",$wanted);
	$today = $_SERVER['REQUEST_TIME'];
	$test2 = date("Y-m-d",$today);
	// wanted must be later than today
	$thediff = $wanted - $today;

      // check to see if date is in the past
	
	if ($thediff < 2400) {
		echo "<div class='announcement'>";
		echo "<p class='centered'>";
		echo "$when is in the past. No can do. <a href='start_dates.php'>Please try again</a></p></div>";
		exit();
	}
	
	$start_date = mktime(0,0,0,$sm,$sd,$sy);
	$remember = "&sd=" . $sd . "&sm=" . $sm . "&sy=" . $sy . "&count=" . $count ;

	echo "<div class='centered'>\n";
	echo "<div class='announcement'>\n";    
	echo "<h3>Cabin availability from date " . $sy . "-" . $sm . "-" . $sd . "</h3>\n";
	echo "<table class='cabin-availability'>\n";
	echo "<tr>\n";
	echo "<th>Cabin</th>\n";
	echo "<th>$ per night</th>\n";
	for ($i=0; $i<NUM_SHOW_DATES; $i++)
	{
		$date = strtotime("+" . $i ." days", $start_date);
		echo "<th>" . date("M d", $date) . "</th>\n";
	}
	echo "</tr>\n";
    
    echo "<tr>";
    echo "<td></td><td></td>\n";

	for ($i=0; $i<NUM_SHOW_DATES; $i++) {
		echo "<td>Bunks Avail.?</td>\n";
	}
	echo "</tr>\n";

	// Get Hut Names and Capacity
	
	$get_huts_res = db_get_huts();
	$num_huts = mysql_num_rows($get_huts_res);
	
	while ($huts = mysql_fetch_array($get_huts_res)) {
		$hut_id = $huts['HutID'];
		$capacity = $huts['Capacity'];
		$hut_name = $huts['HutName'] . " (sleeps " . sprintf("%d", $capacity) . ")";
		$hut_price = "$" . sprintf("%01.2f", $huts['Price']);
		echo "<tr>\n";
		echo "<td>";
		echo $hut_name;
		echo "</td>\n";
		echo "<td>";
		echo $hut_price;
		echo "</td>\n";
		for ($i=0; $i<NUM_SHOW_DATES; $i++) {
			$book = strtotime("+" . $i ." days", $start_date);
			$date_array = getdate($book); 
			$book_date = $date_array["year"] . "-" . $date_array["mon"] . "-" . $date_array["mday"];
			$hut_availability = db_available_beds1($hut_id, $book_date, $capacity);
			$href = "get_bed_nights.php?hut=";
			$href .= $hut_id;
			$href .= "&day=" . $date_array["mday"];
			$href .= "&month=" . $date_array["mon"];
			$href .= "&year=" . $date_array["year"];
			$href .= $remember;
			echo "<td>" . $hut_availability;
			if ($hut_availability < 1) echo " full";
			else echo " <a href='" . $href. "'>book</a>";
			echo "</td>\n";
		}
		echo "</tr>\n";
    }

	echo "</table>\n</div>\n";

	$display_cart = html_cart($remember, $session); // Do not include PayPal link
	echo $display_cart;
	echo "</div>\n";
	

	$_SESSION['remember'] = $remember;
	$SAVEDSESSION = $_SESSION;
	save_session(session_id());

//display_stuff();
	
	include "AdminFooter.php";

?>
