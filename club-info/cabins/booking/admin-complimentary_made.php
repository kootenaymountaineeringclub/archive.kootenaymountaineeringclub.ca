<?php
	include("util.php");
	include("date_util.php");
	session_start();
	$session = session_id();
	db_connect();

	$display_block = "";
	$total_cost = 0;

	// record it all
	$file_name = $_POST["custom"] . ".txt";
	$file = fopen("logs/" . $file_name,"w");
	
	foreach($_POST as $key => $value){
		fwrite($file, $key." = ". $value."\r\n");
	}
	fclose($file);
	// end

	include ("header.php") ;

	$get_cart_res = db_get_cart_from_sessionid($session);

	if (mysql_num_rows($get_cart_res) < 1) {
	
		$display_block .= "<p>You have no items in your cart.</p>";
		
	} else {
	
		// Build Receipt
		// If not already Marked Paid by IPN_Post
		// Add record to BookedHutDay
		// Clear Cart

		$display_block .= "<table class='cabin-availability'>\n";
		$display_block .= "<tr>";
		$display_block .= "<th>Cabin</th><th>Night of</th><th>Persons</th><th>Cost</th>\n";
		$display_block .= "</tr>\n";
		
		while ($cart_info = mysql_fetch_array($get_cart_res)) {
		
			$id = $cart_info["ID"];
			db_delete_from_cart($id); // Delete from Cart
			$hut_id = $cart_info["HutID"];
			$hut = $cart_info["HutName"];
			$num_persons = $cart_info["num_persons"];
			$price = $cart_info["Price"];
			$paid = $cart_info["Paid"];
			$date = $cart_info["BookedDate"];
			$book_date = format_db_date($cart_info["BookedDate"], "d M Y");
			
			if (strcmp($paid, "N") == 0) { // Its NOT already marked as Paid
			
				$first_name = "Unknown";
				$last_name = "Unknown";
				$logged_by = "AutoReturn";
				
				if (isset($_SESSION["admin"])) {
				
					$first_name = $_SESSION["f_name"];
					$last_name = $_SESSION["l_name"];
					$logged_by = "Complimentary";
				}
				
				$year = FiscalYearSelect ( );
				
				echo "<p>" . $year . "</p>";
				
				db_add_nights_booked($session, $hut_id, $year, $date, $num_persons, "Unknown", $first_name, $last_name, $logged_by);
			}
			
			$cost = $price * $num_persons;
			$total_cost = $total_cost + $cost;
			$display_block .= "<tr>\n";
			$display_block .= "<td>" . $hut ."</td>\n";
			$display_block .= "<td>" . $book_date . "</td>\n";
			$display_block .= "<td>" . sprintf("%s", $num_persons) . "</td>\n";
			$display_block .= "<td>$" . sprintf("%.02f", $cost) . "</td>\n";
			$display_block .= "</tr>\n";
		}
		
		$display_block .= "<tr>\n";
		$display_block .= "<td colspan='3'><b>Total Paid</b></td>\n";
		$display_block .= "<td>$" . sprintf("%.02f", $total_cost) .  "</td>\n";
		$display_block .= "</tr>\n";
		$display_block .= "</table>\n";

		if (isset($_SESSION["admin"])) {		
			$display_block .= "<p>Return to <a href='admin/admin_menu.php'>Admin Menu</a></p>" ;		
		} else {
			$display_block .= "<p>You may <a href='select_dates.php'>select more dates</a> to book some more.</p>"; 
		}
		
		db_clean_cart();
	}
?>

		<div class="announcement">
		<?php echo $display_block; ?>
		</div>
	</section>
</div> <!-- end content -->

<footer>

<?php include ( "hut-list-incl.php") ; ?>

</footer>

</div> <!-- end master -->

</body>
</html>
