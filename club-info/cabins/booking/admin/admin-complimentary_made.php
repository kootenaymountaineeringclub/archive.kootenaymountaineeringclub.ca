<?php
	include "AdminHeader.php" ;
	
	session_start();
	$id = session_id();
	
	include("admin_util.php");

	global $SAVEDSESSION;
	get_session($id);
	$_SESSION = $SAVEDSESSION;
	
	admin_db_connect();

	$display_block = "";
	$total_cost = 0;

	$get_cart_res = db_get_cart_from_sessionid($id);
	
	$num_rows = mysql_num_rows($get_cart_res);


	if ( $num_rows == 0 ) {
	
		$display_block .= "<p>You have no items in your cart.</p>";
		
	} else {

		$diplay_block = "<table>";
		
		while ($cart_info = mysql_fetch_array($get_cart_res)) 
		{
			$id = $cart_info["ID"];
echo "<p>$id</p>";
			$hut_id = $cart_info["HutID"];
			$hut = $cart_info["HutName"];
			$num_persons = $cart_info["num_persons"];
			$price = $cart_info["Price"];
			$paid = $cart_info["Paid"];
			$date = $cart_info["BookedDate"];
			$book_date = format_db_date($cart_info["BookedDate"], "d M Y");
			$first_name = $_SESSION["f_name"];
			$last_name = $_SESSION["l_name"];
			$logged_by = "Complimentary";
			$email = "huts@kootenaymountaineeringclub.ca";
				
			$year = FiscalYearSelect ( );
				
			echo "<p>" . $year . "</p>";

			db_add_nights_booked($hut_id, $year, $date, $num_persons, $id, $logged_by, $email, $first_name, $last_name);
			
			$cost = $price * $num_persons;
			$total_cost = $total_cost + $cost;
			$display_block .= "<tr>\n";
			$display_block .= "<td>" . $hut ."</td>\n";
			$display_block .= "<td>" . $book_date . "</td>\n";
			$display_block .= "<td>" . sprintf("%s", $num_persons) . "</td>\n";
			$display_block .= "<td>$" . sprintf("%.02f", $cost) . "</td>\n";
			$display_block .= "</tr>\n";
			db_delete_from_cart($id); // Delete from Cart
		}
		
		$display_block .= "<tr>\n";
		$display_block .= "<td colspan='3'><b>Total Paid</b></td>\n";
		$display_block .= "<td>$" . sprintf("%.02f", $total_cost) .  "</td>\n";
		$display_block .= "</tr>\n";
		$display_block .= "</table>\n";

		$display_block .= "<p>Return to <a href='admin_menu.php?id=" . session_id() . "'>Admin Menu</a></p>" ;		
		
		db_clean_cart();
	}
//display_stuff();
?>

		<div class="announcement">
		<?php echo $display_block; ?>
		</div>
	</section>
</div> <!-- end content -->

<?php	include "AdminFooter.php"; ?>

