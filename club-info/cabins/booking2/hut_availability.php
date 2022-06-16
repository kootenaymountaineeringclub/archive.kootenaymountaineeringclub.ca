<?php
	session_start();
	global $SAVEDSESSION;
	include("booking_util.php");
	get_session(session_id());
	$_SESSION = $SAVEDSESSION;
	
	if ($_POST) {
		$_SESSION['id'] = session_id();
		$_SESSION['booker'] = $_POST['mail'];
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
?>

<html>
<head>
	<?php include("../../../includes/head-first.incl.html") ; ?>

	<title>KMC: The Bonnington Cabins Booking System</title>
	
	<?php include ("../../../includes/head-2nd.incl.html") ; ?>
	<?php include ("../includes/CabinBookingBackgroundImage.incl.html"); ?>

<body>

<div id="master">

<header>

	<?php include "../../includes/page-header-club.incl.html"; ?>
	<?php include "../../../includes/header-contents.incl.html"; ?>
		
</header>

<div id="content">

<?php include "../../includes/club-bonnington.incl.html" ; ?>
	
	<section>

<?php
	
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
	
	$HUTS = get_huts();
	$num_huts = count($HUTS);
	
	foreach ($HUTS as $hut) {

		$hut_id = $hut['HutID'];
		$capacity = $hut['Capacity'];
		$hut_name = $hut['HutName'] . " (sleeps " . sprintf("%d", $capacity) . ")";
		$hut_price = "$" . sprintf("%01.2f", $hut['Price']);
		echo "<tr>\n";
		echo "<td>";
		echo $hut_name;
		echo "</td>\n";
		echo "<td>";
		echo $hut_price;
		echo "</td>\n";
		
		for ($i=0; $i<NUM_SHOW_DATES; $i++) {
			
			$tobook = strtotime("+" . $i ." days", $start_date);
			$date_array = getdate($tobook); 
			$book_date = $date_array["year"] . "-" . $date_array["mon"] . "-" . $date_array["mday"];
			
			$beds_avail = hut_available_beds($hut_id, $book_date, $capacity);

			$href = "get_bed_nights.php?hut=";
			$href .= $hut_id;
			$href .= "&day=" . $date_array["mday"];
			$href .= "&month=" . $date_array["mon"];
			$href .= "&year=" . $date_array["year"];
			$href .= $remember;
			echo "<td>";
			if ($beds_avail < 1) echo " full";
			else echo $beds_avail . " <a href='" . $href. "'>book</a>";
			echo "</td>\n";
		}
		echo "</tr>\n";
    }

	echo "</table>\n</div>\n";
	$display_cart = html_cart($remember); // Do not include PayPal link
	echo "<p>" . $display_cart . "</p>";
	if (strlen($display_cart) > 50) echo "<p><a href='empty_cart.php?id=" . $session . "'>Empty cart and select other dates</a></p>\n";
	echo "</div>\n";
	

	$_SESSION['remember'] = $remember;
	$SAVEDSESSION = $_SESSION;
	save_session(session_id());
	//display_stuff();
		


?>

</section>
</div> <!-- end content -->

<footer>

<?php include html_footer_strip() ; ?>

</footer>

</div> <!-- end master -->

</body>
</html>
