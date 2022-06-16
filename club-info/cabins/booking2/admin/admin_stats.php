<?php
	// Incude the Utility functions
	include "admin_util.php" ;
	session_start();
	
	global $SAVEDSESSION;
	get_session(session_id());
	$_SESSION = $SAVEDSESSION;
	
	// Connect to kmc_huts database
	
	admin_db_connect();
	
	if ($_SESSION["admin"] !== TRUE)
	{
		db_inc_admin_login_failed();
		header("Location: AdminLogin.html");
		exit;
	}

?>

<?php include ("AdminHeader.php") ;
	
display_stuff(); ?>	

<?php echo "<p class='centered'><a href='admin_menu.php?id=" . $_REQUEST['id'] . "'>Return to the administrators menu</a></p>" ; ?>
	
<?php
	echo "<table id='cabin-bookings'>\n";
	echo "<caption>Cabin Bookings (Person Nights)</caption>\n";
	
	$get_huts_res = db_get_huts();
	$num_huts = mysql_num_rows($get_huts_res);
	$booked_total = 0;
	while ($huts = mysql_fetch_array($get_huts_res))
	{
		echo "<tr>\n<td>" . $huts['HutName'] . "</td>";
		$booked = db_get_person_nights_booked_hut($huts['HutID']);
		$booked_total += $booked;
		echo "<td>" . $booked . "</td>\n</tr>\n";
	}
	echo "<tr>\n<td><b>All</b></td><td><b>" . $booked_total . "</b></td>\n</tr>\n";

	echo "</table>\n";
	
	$booked = db_get_person_nights_booked();
	$comp = db_get_person_nights_comp();
	
	echo "<p class='centered'>Paid person/nights: " . ($booked - $comp) . "<br>\n";
	echo "Complimentary person/nights: " . $comp . "</p>";


	echo "<table id='cabin-details'>\n";
	echo "<caption>Cabin Bookings (Details)</caption>\n";
	echo "<tr>\n";
	echo "<th id='cabin-name'>Cabin</th>\n";
	echo "<th id='night'>Night</th>\n";
	echo "<th id='persons'>#</th>\n";
	echo "<th id='booked-by'>Booked By</th>\n";
	echo "<th id='logged-by'>Method</th>\n";
	echo "</tr>\n";   
	
	// Get Hut Bookings
	
	$get_bookings_res = db_get_bookings_since_one_month(); // db_get_bookings(); // 
	
	while ($bookings = mysql_fetch_array($get_bookings_res))
		{
			$hut_name = $bookings['HutName'];
			$booked_by = $bookings['BookedByFirstName'] . " " . $bookings['BookedByLastName'] . " (" . $bookings['BookedByEmail'] . ")";
			echo "<tr>\n";
			echo "<td>";
			echo $hut_name;
			echo "</td>\n";
			echo "<td>";
			echo $bookings['BookedDate'];
			echo "</td>\n";
			echo "<td>";
			echo $bookings['NumPersons'];
			echo "</td>\n";
			echo "<td>";
			echo $booked_by;
			echo "</td>\n";
			echo "<td>";
			echo $bookings['LoggedBy'];
			echo "</td>\n";
			echo "</tr>\n";
		}

	echo "</table>\n";

// Output Page Visit Stats

	$get_stats_res = db_get_stats();
	$stats = mysql_fetch_array($get_stats_res);

	echo "<table id='statistics'>\n";
	echo "<caption>Page Visit Statistics</caption>\n";
	echo "<tr>\n";
	echo "<th>Select<br>Dates</th>\n";
	echo "<th>Add to<br>Cart</th>\n";
	echo "<th>At<br>Checkout</th>\n";
	echo "<th>IPN</th>\n";
	echo "<th>Auto<br>Return</th>\n";
	echo "<th>Booked</th>";
	echo "<th>Admin<br>Logins</th>\n";
	echo "<th>Admin<br>Logins<br>Failed</th>\n";
	echo "<th>Last<br>Update</th>\n";
	echo "</tr>\n"; 
	
	echo "<tr>\n<td>" .  $stats['Start'] . "</td>\n"; 
	echo "<td>" .  $stats['AddToCart'] . "</td>\n"; 
	echo "<td>" .  $stats['AtCheckOut'] . "</td>\n"; 
	echo "<td>" .  $stats['IPN_Received'] . "</td>\n"; 
	echo "<td>" .  $stats['RTN_Received'] . "</td>\n"; 
	echo "<td>" .  $stats['Paid'] . "</td>\n"; 
	echo "<td>" .  $stats['AdminLoginSucessfull'] . "</td>\n"; 
	echo "<td>" .  $stats['AdminLoginFailed'] . "</td>\n"; 
	echo "<td>" .  $stats['Touched'] . "</td>\n"; 
	
	echo "</tr>\n</table>\n";
//echo "<p>" . $_REQUEST['id'] . "</p>\n";
?>

</div> <!-- end content -->

<?php echo "<p class='centered'><a href='admin_menu.php?id=" . $_REQUEST['id'] . "'>Return to the administrators menu</a></p>" ; ?>

<?php include ("AdminFooter.php") ; ?>
