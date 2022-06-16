<?php			// how could they get here with no date selected already? Other than people jumping the cue?
		session_start();
		// Incude the Utility functions
		include("util.php");
		if (empty($_SESSION["start_month"]))
		{
			header("Location: select_dates.php"); 
			exit;
		}
?>

<!DOCTYPE html>
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
        date_default_timezone_set( 'America/Los_Angeles');
		$today_array = getdate();
		$start_month = $_SESSION["start_month"];
		$start_day = $_SESSION["start_day"];
		$start_year = $_SESSION["start_year"];
		
		$start_month = substr("0" . $start_month, -2);
		$start_day = substr("0" . $start_day, -2);
	
		if (!checkdate($start_month, $start_day, $start_year)) {
			echo "<div class='announcement'>";
			echo "<p class='centered'>";
			echo $start_year . "-" . $start_month . "-" . $start_day;
			echo " is not a valid start date. <a href='select_dates.php'>Please try again</a></p></div>";
			exit();
		}

		$start_date = mktime(0,0,0,$start_month,$start_day,$start_year);

		// Connect to kmc_huts database
		
		db_connect();
		db__clear_checkout(); // Clear the At Checkout Page indicator in Cart
		db_clean_cart(); // Clean out timed out cart items

		echo "<div class='centered'>\n";
		echo "<div class='announcement'>\n";    
		echo "<h3>Cabin availability from date " . $start_year . "-" . $start_month . "-" . $start_day . "</h3>\n";
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
		for ($i=0; $i<NUM_SHOW_DATES; $i++)
		{
			echo "<td>Bunks Avail.?</td>\n";
		}
		echo "</tr>\n";
   
		// Get Hut Names and Capacity
		
		$get_huts_res = db_get_huts();
		$num_huts = mysql_num_rows($get_huts_res);
		
		while ($huts = mysql_fetch_array($get_huts_res))
		{
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
			for ($i=0; $i<NUM_SHOW_DATES; $i++)
			{
				$date = strtotime("+" . $i ." days", $start_date);
				$date_array = getdate($date); 
				$book_date = $date_array["year"] . "-" . $date_array["mon"] . "-" . $date_array["mday"];
				$hut_availability = db_available_beds1($hut_id, $book_date, $capacity);
				$href = "book_nights.php?hut=";
				$href .= $hut_id;
				$href .= "&day=" . $date_array["mday"];
				$href .= "&month=" . $date_array["mon"];
				$href .= "&year=" . $date_array["year"];
				echo "<td>" . $hut_availability;
				if ($hut_availability < 1) echo " full";
				else echo " <a href='" . $href. "'> book</a>";
				echo "</td>\n";
			}
			echo "</tr>\n";
        }

		echo "</table>\n</div>\n";
		$display_cart = html_cart(false); // Do not include PayPal link
		echo "<p>" . $display_cart . "</p>";
		if (strlen($display_cart) > 50) echo "<p><a href='select_dates.php'>Empty cart and reselect dates</a></p>\n";
		echo "</div>\n";
?>

</section>
</div> <!-- end content -->

<footer>

<?php include html_footer_strip() ; ?>

</footer>

</div> <!-- end master -->

</body>
</html>
