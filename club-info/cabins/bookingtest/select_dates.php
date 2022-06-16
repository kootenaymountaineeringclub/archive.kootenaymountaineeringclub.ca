<?php		// select_dates.php
	include "date_util.php" ;
	include  "util.php" ;

	session_start();
	
	if (!isset($_SESSION['session'])) $_SESSION['session'] = session_id();
 
	if (isset($_POST["start_day"]))   // bounced back here after selecting a date the first time around
     {
        $_SESSION["start_day"] = $_POST["start_day"];
        $_SESSION["start_month"] = $_POST["start_month"];
        $_SESSION["start_year"] = $_POST["start_year"];
        $_SESSION["num_persons"] = 1;   // prime the pump for after picking first date and hut???.
      // check to see if date is in the past
      	if (is_date_passed($_POST["start_year"], $_POST["start_month"], $_POST["start_day"]))
      	{
        	header("Location: past_date.php"); 
      	}
     	else
      	{
        	header("Location: hut_availability.php");   // go on to pick a hut, date and number of bunks
        }
        exit;
     } else {   // otherwise, pick a date.
?>

<!DOCTYPE html>

<html>
<head>

	<?php include("../../../includes/head-first.incl.html") ; ?>

	<title>KMC: The Bonnington Cabins Booking System</title>
	
	<?php include ("../../../includes/head-2nd.incl.html") ; ?>
	<?php include ("../includes/CabinBookingBackgroundImage.incl.html"); ?>

</head>

<body>

<div id="master">

<header>

	<?php include "../../includes/page-header-club.incl.html"; ?>
	<?php include "../../../includes/header-contents.incl.html"; ?>
	
</header>

<div id="content">

<?php include "../../includes/club-bonnington.incl.html" ; ?>
	
	<section>

		<?php include "WelcomeMessage.html" ; ?>


		<div class="announcement">
		<p>Please select the date you would like your trip to begin:</p>
		
		
<?php      
		session_regenerate_id();
		// See if it is an administrator
		$admin_welcome = "";
		if (isset($_SESSION["admin"]))
		{
			echo "<p>Welcome " . $_SESSION["admin_name"] . ". You may book complimentary nights.</p>\n";
		}
		else
		{
			$_SESSION = array(); // remove all old values
		}
		
        date_default_timezone_set( 'America/Los_Angeles');
        $today_array = getdate();
        echo "<form method='POST' action='select_dates.php'>\n";
        echo day_select("start_day", $today_array);
        echo month_select("start_month", $today_array);
        echo year_select("start_year", $today_array);
        echo "\n<input type='submit' name='submit' value='Show Availability'></form>\n";
        echo "<p><strong>Attention:</strong> This booking process requires that you have allowed 'cookies' to be placed in your web browser. If you have them turned off, please turn them on for the length of time it takes to  finish placing your hut booking. Thanks !!!</p>\n";
    }
?>

	</div>
	</section>
</div> <!-- end content -->

<footer>

<?php include ("hut-list.incl") ; ?>

</footer>

</div> <!-- end master -->

</body>
</html>

