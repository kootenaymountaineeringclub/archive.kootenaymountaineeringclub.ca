<?php
	include("util.php");
	include("date_util.php");
	$oops_address = "tim@timclinton.ca";
?>
<!DOCTYPE html>
<html>
<head>
	<?php include (PHP_ROOT . "/includes/head-first.incl.html") ; ?>
	<?php include("../../../includes/head-first.incl.html") ; ?>

	<title>KMC: The Bonnington Cabin Booking System</title>
	
	<?php include (PHP_ROOT . "/includes/head-2nd.incl.html") ; ?>
	<?php include ("../includes/CabinBookingBackgroundImage.incl.html") ; ?>
</head>

<body>

<div id="master">

<header>

	<?php include ("../../includes/page-header-club.incl.html") ;
				include (PHP_ROOT . "/includes/header-contents.incl.html") ; ?>
	
</header>

<div id="content">

<?php include ("../../includes/club-bonnington.incl.html") ; ?>
	
<section>

<?php 
	
	global $SAVEDSESSION;
	$id = $_REQUEST['id'];
	get_session ($id);
	$_SESSION = $SAVEDSESSION;
	
	$OOPS = "";
	
	$BookedByEmail = $SAVEDSESSION['booker'];
	$Description = $SAVEDSESSION['Description'];
	$SessionID = $_REQUEST['id'];
	
	$fiscal_year = FiscalYearSelect ( );

	$link = mysqli_connect("127.0.0.1", "kmcwe_kmc", "zlika9p", "kmcweb_0_kmc");
	
	if (!$link) {
	    $OOPS .= "Debugging error: " . mysqli_connect_error() . "</p>\n";
	} else {
		$BookingStartDate = date('Y-m-d',mktime(0,0,0,$SAVEDSESSION['sm'],$SAVEDSESSION['sd'],$SAVEDSESSION['sy']));
		$ReservationSQL = "INSERT INTO HutReservations (SessionID, FiscalYear, " .
		"BookingStartDate, BookerEmail, PP_PaymentID, PP_Token, PP_PayerID, Description) values (" .
		"'" . $SessionID . "', '" . $fiscal_year . "', '" . $BookingStartDate . "', " .
		"'" . $SAVEDSESSION['booker'] . "', '" . $_REQUEST['paymentId'] . "', '" . $_REQUEST['token'] . 
		"', '" . $_REQUEST['PayerID'] . "', '" . $SAVEDSESSION['Description'] . "')" ;
		
		if (! mysqli_query($link, $ReservationSQL) === TRUE) {
		    $OOPS .= "<p>No reservation record created: " . mysql_error($link) . "</p>\n";
		}

		$sql = "SELECT HutID, BookedDate, num_persons FROM Cart where sessionID = '" . $SessionID . "'";
		
		if ($result = mysqli_query($link,$sql)) {
			$insert_count = 0;
			$row_count = 0;
			
			while ( $row = mysqli_fetch_array($result)) {
				$row_count += 1;
				$HutID = $row["HutID"];
				$BookedDate = $row["BookedDate"];
				$num_persons = $row["num_persons"];
				
				$HutDateSQL = "INSERT INTO BookedHutDay (HutID, FiscalYear, BookedDate, NumPersons, SessionID, " .
				"LoggedBy, BookedByEmail, BookedByFirstName, BookedByLastName) values (" .
				"'" . $HutID . "', '" . $fiscal_year . "', '" . $BookedDate . "', '" . $num_persons . "', '" .
				$SessionID . "', 'PHP', '" . $SAVEDSESSION['booker'] . "', 'On PayPal', 'On PayPal')";
				
				if (! mysqli_query($link, $HutDateSQL) === TRUE) {
					$OOPS .= "<p>Hut record not created: " . mysqli_error($link) . "</p>\n";
				} else {
					$insert_count += 1;
				}
			}
			if ($row_count !== $insert_count) {
				$OOPS .= "<p>Insert mismatch!</p>";
			} else {
				$DeleteSQL = "DELETE FROM Cart where sessionID = '" . $SessionID . "'";
				if (! mysqli_query($link, $DeleteSQL) === TRUE) $OOPS .= "<p>Cart records not deleted</p>";
			}
		} else {
			$OOPS .= "<p>No results: " . mysqli_error($link) . "</p>\n";
		}
		
		if ($OOPS) {
			echo "<hr /><p>OOPS:</p>" . $OOPS . "<hr />\n";
			echo "<p>There have been errors. Sorry.</p>";
			echo "<p>Because we arrived at this point in the program it is obvious that the PayPal payment went well.</p>";
			
			$oopssend = "This was a  booking with session_id of " . $SessionID . "\n\n";
			$oopssend .= "Booking details: " . $SAVEDSESSION["description"] . "\n\n";
			$oopssend .= $OOPS;
			
			mail($oops_address,'KMC Membership Ooops',$memberdirsend);
		
			echo "<p>The webmanager has been notified with the same error message you see above. It will be fixed and you will be contacted.</p>";
		} else {
			echo "<p>Your hut booking has been completed. Please copy and paste the text below into a program from which it can be printed. You will need to carry it with you as proof of booking in case someone is in the hut without a booking receipt such as this.</p>";
			echo "<hr />";
			echo "<pre>\n\n\n";
			echo $SAVEDSESSION['booker'] . " has booked these huts:\n";
			echo $SAVEDSESSION['Description'] . "\n";
			echo "The unique identifier of the booking is: " . $_REQUEST['id'] . "\n\n\n";
			echo "</pre>\n";
			echo "<hr />\n";
			echo "<p>Thank you from the club.</p>\n";
		}
		
		/* free result set */
		mysqli_stmt_close($result);
		
		/* close connection */
		mysqli_close($link);
	}
?>

</section>
</div> <!-- end content -->

<footer>

</footer>

</div>
<!-- end master -->

</body>
</html>
