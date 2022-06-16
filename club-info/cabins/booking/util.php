<?php
	define('NUM_SHOW_DATES', '6');
	define('PHP_ROOT','/var/www/vhosts/kootenaymountaineeringclub.ca/httpdocs');

function transfer_cart($id) {
	db_connect();
	$FiscalYear = FiscalYearSelect ( );
	
	$cart_sql = "select * from cart where  sessionID = $id";
	$cart_lines = mysql_query($cart_sql);
	
}

function save_session ($id) {
	global $SAVEDSESSION;
	$savefile = "saved/" . $id . ".txt" ;
	file_put_contents($savefile,json_encode($SAVEDSESSION));
}

function get_session ($id) {
	global $SAVEDSESSION;
	$savefile = "saved/" . $id . ".txt" ;
	$SAVEDSESSION = json_decode(file_get_contents($savefile), true);
}

function display_stuff() {
	global $SAVEDSESSION;
	echo "<pre>ID : " . session_id() . "</pre>\n";
	echo "<pre>SESSION: \n" . print_r($_SESSION,TRUE) . "</pre>\n";
	echo "<pre>SAVED: \n" . print_r($SAVEDSESSION,TRUE) . "</pre>\n";
	echo "<pre>REQUEST: \n" . print_r($_REQUEST,TRUE) . "</pre>\n";
	echo "<pre>POST: \n" . print_r($_POST,TRUE) . "</pre>\n";
	echo "<hr>\n";
}

function dump_data($theFile, $theData) {
	$f = fopen($theFile, "a");
	fwrite($f, $theData);
	fclose($f);
}

function hutPicture($theHut) {
	$hutPictures = array (
	1 => "thumb-huckhut.jpg",
	2 => "thumb-copper.jpg",
	3 => "thumb-steed.jpg",
	4 => "thumb-grassy.jpg" );

	$linkHead = "/club-info/cabins/images/";
	
	return ($linkHead . $hutPictures[$theHut]);
}


function FiscalYearSelect ( )
	{
		$TZ = new DateTimeZone("America/Vancouver");
		
		$current_date = new DateTime("now",$TZ);
		$current_format = date_format($current_date, DATE_ATOM);
	
		$theDateAndTimeParts = explode("T", $current_format);
		$theDateParts = explode("-",$theDateAndTimeParts[0]);
	
		$currentYear = intval($theDateParts[0]);
		$currentMonth = intval($theDateParts[1]);
		$currentDay = intval($theDateParts[2]);
		
		if ($currentMonth < 10 )
			$currentMonth = "0" . $currentMonth ;
			
		if ($currentMonth > "10")
			$FiscalYear = $currentYear . "/" . (substr($currentYear,2,2) + 1) ;
		else
			$FiscalYear = ($currentYear - 1) . "/" . substr($currentYear,2,2);
		
		return($FiscalYear);
	}

function db_connect()
	{
      // Connect to Database
		$mysql = mysql_connect("localhost", "kmcwe_kmc",  "zlika9p") or die('Could not connect: ' . mysql_error());
		mysql_select_db("kmcweb_0_kmc") or die('Could not select database...');
	}
	
function db_get_huts()
	{
		$get_huts_sql = "SELECT HutID, HutName, Capacity, Price FROM Hut ORDER BY Sequence";
		$get_huts_res = mysql_query($get_huts_sql)
			or die("SELECT From Hut failed: " .mysql_error());
		return $get_huts_res;
	}
	
function db_get_bookings()
	{
		$get_sql = "SELECT h.HutName, b.NumPersons, b.BookedDate, b.LoggedBy, b.BookedByEmail, b.BookedByFirstName, b.BookedByLastName, b.Touched FROM BookedHutDay b INNER JOIN Hut h ON b.HutID = h.HutID ORDER BY b.BookedDate";
		$get_res = mysql_query($get_sql)
			or die("SELECT From BookedHutDay failed: " .mysql_error());
		return $get_res;
	}
	
	
function db_get_stats()
	{
		$get_sql = "SELECT * FROM Stats";
		$get_res = mysql_query($get_sql)
			or die("SELECT From Stats failed: " .mysql_error());
		return $get_res;
	}
	
function db_get_person_nights_booked_hut($hut_id)
	{
		$get_sql = "SELECT SUM(NumPersons) FROM BookedHutDay WHERE HutID = " . $hut_id;
		$get_res = mysql_query($get_sql)
			or die("SELECT SUM(NumPersons From BookedHutDay failed: " .mysql_error());
        $bookings = mysql_fetch_array($get_res);
        return $bookings[0];
    }
	
function db_get_person_nights_booked()
	{
		$get_sql = "SELECT SUM(NumPersons) FROM BookedHutDay";
		$get_res = mysql_query($get_sql)
			or die("SELECT SUM(NumPersons From BookedHutDay failed: " .mysql_error());
        $bookings = mysql_fetch_array($get_res);
        return $bookings[0];
    }
	
function db_get_hut($hut_id)
	{
		$get_hut_sql = "SELECT HutID, HutName, Capacity, Price, Picture FROM Hut WHERE HutID = " . $hut_id;
		$get_hut_res = mysql_query($get_hut_sql)
			or die("SELECT From Hut failed: " .mysql_error());
		/*
		printf("\n%d Huts returned from database...\n", mysql_num_rows($get_hut_res));
		*/
		return $get_hut_res;
	}
	
function db_get_hut_capacity($hut_id)
	{
		$get_hut_sql = "SELECT Capacity FROM Hut WHERE HutID = " . $hut_id;
		$get_hut_res = mysql_query($get_hut_sql)
			or die("SELECT From Hut failed: " .mysql_error());
        $hut = mysql_fetch_array($get_hut_res);
        $capacity = $hut['Capacity'];
        return $capacity;
	}
	
function db_get_cart()
	{
		$session_id = session_id();
		return db_get_cart_from_sessionid($session_id);
	}
	
function db_get_cart_from_sessionid($session_id)
	{
		$get_cart_sql = "SELECT ID, c.ID, c.HutID, h.HutName, c.num_persons, h.Price, c.BookedDate, c.Paid FROM Cart c INNER JOIN Hut h ON c.HutID = h.HutID WHERE c.sessionID = '" . $session_id . "' ORDER BY c.BookedDate";
		$get_cart_res = mysql_query($get_cart_sql)
			or die("SELECT From Cart failed: " .mysql_error());
		return $get_cart_res;
	}
	
function db_items_in_cart()
	{
		$session_id = session_id();
		$get_items_in_cart_sql = "SELECT SUM(num_persons) AS spn FROM Cart WHERE sessionID = '" . $session_id . "'";
		$get_items_in_cart_res = mysql_query($get_items_in_cart_sql)
			or die("SELECT From Cart (Count items) failed: " .mysql_error());
		if (mysql_num_rows($get_items_in_cart_res)== 0)
			return 0;
		$spn_array = mysql_fetch_array($get_items_in_cart_res);
		$spn = $spn_array['spn'];
		if (is_numeric($spn))
			return($spn);
		return 0;
	}
	
function db_add_to_cart($hut_id, $book_date, $num_persons)
	{
		$session_id = session_id();
		$insert_items_in_cart_sql = "INSERT INTO Cart (sessionID, HutID, BookedDate, num_persons) VALUES (";
		$insert_items_in_cart_sql .= "'" . $session_id . "', ";
		$insert_items_in_cart_sql .= $hut_id . ", ";
		$insert_items_in_cart_sql .= "'" . $book_date . "', ";
		$insert_items_in_cart_sql .= $num_persons . ")";
		
		$insert_items_in_cart_res = mysql_query($insert_items_in_cart_sql)
			or die("INSERT into Cart failed: " . mysql_error());
		db_touch_cart();
		db_inc_add2cart();
		return $insert_items_in_cart_sql;
	}
	
function db_delete_from_cart($id)
	{
		$delete_from_cart_sql = "DELETE FROM Cart WHERE id = " . $id;
		$delete_from_cart_res = mysql_query($delete_from_cart_sql)
			or die("DELETE from Cart failed: " . mysql_error());
		db_touch_cart();
	}
	
function db_delete_cart($id)
	{
		$delete_cart_sql = "DELETE FROM Cart WHERE sessionID = '" . $id . "'";
		$delete_cart_res = mysql_query($delete_cart_sql)
			or die("DELETE Cart failed: " . mysql_error());
	}
	
function db_add_nights_booked($session_id, $hut_id, $year, $book_date, $num_persons, $email, $first_name, $last_name, $added_by)
	{
		$add_hut_booked_sql = "INSERT into BookedHutDay (HutID, FiscalYear, BookedDate, NumPersons, SessionID, LoggedBy, BookedByEmail, BookedByFirstName, BookedByLastName) VALUES (";
		$add_hut_booked_sql .= $hut_id . ", ";
		$add_hut_booked_sql .= "'" . $year . "', ";
		$add_hut_booked_sql .= "'" . $book_date . "', ";
		$add_hut_booked_sql .= $num_persons . ", ";
		$add_hut_booked_sql .= "'" . $session_id . "', ";
		$add_hut_booked_sql .= "'" . $added_by . "', ";
		$add_hut_booked_sql .= "'" . $email . "', ";
		$add_hut_booked_sql .= "'" . $first_name . "', ";
		$add_hut_booked_sql .= "'" . $last_name . "')";

		$insert_hut_booked_res = mysql_query($add_hut_booked_sql)
			or die("INSERT into BookedHutDay failed: " . mysql_error() . " - " . $add_hut_booked_sql . "\n");
		db_inc_paid();
	}
	
function db_session_in_BookedHutDay($session)
	{
		$sql = "SELECT COUNT(*) AS Count FROM BookedHutDay WHERE SessionID = '" . $session . "'";
		$res = mysql_query($sql)
			or die("SELECT COUNT(*) FROM BookedHutDay failed: " .mysql_error());
        $ret = mysql_fetch_array($res);
        $count = $ret['Count'];
        return $count;	
	}
	
function db_update_BookedHutDay($session, $email, $first_name, $last_name)
	{
		$sql = "UPDATE BookedHutDay SET BookedByEmail = '";
		$sql .= $email . "', ";
		$sql .= "BookedByFirstName = '" . $first_name . "', ";
		$sql .= "BookedByLastName = '" . $last_name . "' WHERE SessionID = '" . $session . "'";
		$res = mysql_query($sql)
			or die("UPDATE  BookedHutDay (email etc) failed: " .mysql_error());
	}
	
function db_available_beds1($hut_id, $book_date, $capacity)
	{
		$get_booked_sql = "SELECT SUM(NumPersons) AS BookedBeds FROM BookedHutDay WHERE HutID = " . $hut_id . " AND BookedDate = '" . $book_date . "'";
		/*echo "..." . $get_booked_sql . "...";*/
		$get_booked_res = mysql_query($get_booked_sql)
			or die("SELECT From BookedHutDay failed: " .mysql_error());
		if (mysql_num_rows($get_booked_res)== 0)
			return 0;
		$booked_array = mysql_fetch_array($get_booked_res);
		$booked = $booked_array['BookedBeds'];
		$in_cart = 0;
		$get_in_cart_sql = "SELECT SUM(num_persons) AS InCartBeds FROM Cart Where HutID = " . $hut_id . " AND BookedDate = '" . $book_date . "' AND Paid = 'N'";
		$get_in_cart_res = mysql_query($get_in_cart_sql)
			or die("SELECT From Cart failed: " .mysql_error());
		if (mysql_num_rows($get_in_cart_res)> 0)
		{
			$in_cart_array = mysql_fetch_array($get_in_cart_res);
			$in_cart = $in_cart_array['InCartBeds'];
		}
		return($capacity - $booked - $in_cart);
	}
	
function db_available_beds2($hut_id, $book_date)
	{
		$capacity = db_get_hut_capacity($hut_id);
		return db_available_beds1($hut_id, $book_date, $capacity);
	}
	
function db_mark_cart_paid($session)
	{
		$update_sql = "UPDATE Cart SET Paid = 'Y' WHERE sessionID = '" . $session . "'";
		$cart_res = mysql_query($update_sql)
			or die("UPDATE  Cart (Paid) failed: " .mysql_error());
	}
	
function db_clean_cart()
	{
		$date = strtotime("-10 mins");
		$delete_sql = "DELETE FROM Cart WHERE AtCheckOut = 'N' AND Touched < '" . date("Y-m-d H:i:s", $date) . "'";
		$get_cart_res = mysql_query($delete_sql)
			or die("DELETE  Cart (Not at Checkout) failed: " .mysql_error());
		$date = strtotime("-45 mins");
		$delete_sql = "DELETE FROM Cart WHERE Touched < '" . date("Y-m-d H:i:s", $date) . "'";
		$get_cart_res = mysql_query($delete_sql)
			or die("DELETE  Cart (Older than 45 min) failed: " .mysql_error());
	}
	
function db_touch_cart()
	{
		$update_sql = "UPDATE Cart SET Touched = NOW() WHERE sessionID = '" . session_id() . "'";
		$cart_res = mysql_query($update_sql)
			or die("UPDATE  Cart (Touched) failed: " .mysql_error());
	}
	
function db__set_checkout()
	{
		$update_sql = "UPDATE Cart SET AtCheckOut = 'Y' WHERE sessionID = '" . session_id() . "'";
		$cart_res = mysql_query($update_sql)
			or die("UPDATE  Cart (set AtCheckOut) failed: " .mysql_error());
	}
	
function db__clear_checkout()
	{
		$update_sql = "UPDATE Cart SET AtCheckOut = 'N' WHERE sessionID = '" . session_id() . "'";
		$cart_res = mysql_query($update_sql)
			or die("UPDATE  Cart (clear AtCheckOut) failed: " .mysql_error());
	}
	
function db_inc_start()
	{
		$update_sql = "UPDATE Stats SET Start = Start + 1";
		$res = mysql_query($update_sql)
			or die("UPDATE  Stats (Start) failed: " .mysql_error());
	}
	
function db_inc_add2cart()
	{
		$update_sql = "UPDATE Stats SET AddToCart = AddToCart + 1";
		$res = mysql_query($update_sql)
			or die("UPDATE  Stats (AddToCart) failed: " .mysql_error());
	}
	
function db_inc_paid()
	{
		$update_sql = "UPDATE Stats SET Paid = Paid + 1";
		$res = mysql_query($update_sql)
			or die("UPDATE  Stats (Paid) failed: " .mysql_error());
	}
	
function db_inc_checkout()
	{
		$update_sql = "UPDATE Stats SET AtCheckOut = AtCheckOut + 1";
		$res = mysql_query($update_sql)
			or die("UPDATE  Stats (AtCheckOut) failed: " .mysql_error());
	}
	
function db_inc_ipn_received()
	{
		$update_sql = "UPDATE Stats SET IPN_Received = IPN_Received + 1";
		$res = mysql_query($update_sql)
			or die("UPDATE  Stats (IPN_Received) failed: " .mysql_error());
	}
	
function db_inc_rtn_received()
	{
		$update_sql = "UPDATE Stats SET RTN_Received = RTN_Received + 1";
		$res = mysql_query($update_sql)
			or die("UPDATE  Stats (RTN_Received) failed: " .mysql_error());
	}
	
function db_insert_testing($f1, $f2, $f3)
	{
		$add_sql = "INSERT into Testing (F1, F2, F3) VALUES (";
		$add_sql .= "'" . $f1 . "','" . $f2 . "','" . $f3 . "')";
		$insert_res = mysql_query($add_sql)
			or die("INSERT into Testing failed: " . mysql_error());
	}
	
function format_db_date($mysql_date,$format)
	{
		/*
		$mysql_date - The Date which should be formatted...
		$format - The format string.... 
		refer the Date function for format String
		*/
		$dateTime = strtotime($mysql_date);
		$formatted_date = date($format, $dateTime);
		return $formatted_date;
	}
	
function format_db_time_stamp($ts)
	{
		$fts = substr($ts,0,4); // Year
		$fts .= "-";
		$fts .= substr($ts,4,2); // Month
		$fts .= "-";
		$fts .= substr($ts,6,2); // Day
		$fts .= " ";
		$fts .= substr($ts,8,2); // Hour
		$fts .= ":";
		$fts .= substr($ts,10,2); // Min
		return $fts;
	} 

	
function html_kmc_logo()
	{
		$display_block = "<table CELLSPACING='0' CELLPADDING='0' BORDER='0' WIDTH='100%'><tr>";
		$display_block .= "<td  valign='top' height='0%' width='160'>";
		$display_block .= "<img SRC='../images/kmclogo.jpg' ALT='picture' BORDER='2' height='80' width='160' align='left' 'hspace='0' vspace='0'>";
		$display_block .= "</td>";
		$display_block .= "<td>";
		$display_block .= "<p>&nbsp;<a href='http://www.kootenaymountaineeringclub.ca/'>Return to Kootenay Mountaineering Club main page</a></p>";
		$display_block .= "</td></tr></table><br>";
		return $display_block;
	}
	
	
function paypalExpressCheckoutValues()
	{	
		$get_cart_res = db_get_cart();
		
		$item_list = "";
		$get_cart_res = db_get_cart();
		
		while ($cart_info = mysql_fetch_array($get_cart_res))
		{
			$id = $cart_info["ID"];
			$hut = $cart_info["HutName"];
			$num_persons = $cart_info["num_persons"];
			$price = $cart_info["Price"];
			$book_date = format_db_date($cart_info["BookedDate"], "d M Y");
			$date = date("Y-m-d", $book_date);
			$cost = $price * $num_persons;
			$total_cost = $total_cost + $cost;
			$total_person_nights = $total_person_nights + $num_persons;
			$item_list .= $hut . " on " . $book_date . " for " . $num_persons . (($num_persons == 1) ? " person. " : " people. ");
		}
		
		$express = "<input type='hidden' name='PAYMENTREQUEST_0_AMT' value='" . $total_cost . "'</input>\n" .
							"<input type='hidden' name='PAYMENTREQUEST_0_DESC' value='" . urlencode($item_list) . "'</input>\n" .
							"<input type='hidden' name='PAYMENTREQUEST_0_CUSTOM' value='" . session_id() . "'</input>\n";
									
		return $express;
	}

function html_cart($return)
		{
			$display_block = "";
			$get_cart_res = db_get_cart();
			
			$stuff = $return;
		
			if (mysql_num_rows($get_cart_res) < 1)
			{
				$display_block .= "<p>You have no huts booked.</p>";
			} else {
				$display_block .= "<h3>Your Selected Bookings</strong></h3>\r";
				$display_block .= "<table class='cabin-availability'>\r";
				$display_block .= "<tr>";
				$display_block .= "<th>Cabin</th><th>Night of</th><th>Number of persons</th><th>Cost</th>";
				if (! $LinkToPayPal ) $display_block .= "<th>Action</th>";
				$display_block .= "</tr>\r";
				$item_list = "| ";
			
				while ($cart_info = mysql_fetch_array($get_cart_res))
				{
					$id = $cart_info["ID"];
					$hut = $cart_info["HutName"];
					$num_persons = $cart_info["num_persons"];
					$price = $cart_info["Price"];
					$book_date = format_db_date($cart_info["BookedDate"], "d M Y");
					$date = date("Y-m-d", $book_date);
					$cost = $price * $num_persons;
					$total_cost = $total_cost + $cost;
					$total_person_nights = $total_person_nights + $num_persons;
					$display_block .= "<tr>";
					$display_block .= "<td>" . $hut ."</td>";
					$display_block .= "<td>" . $book_date . "</td>";
					$display_block .= "<td>" . sprintf("%s", $num_persons) . "</td>";
					$display_block .= "<td>$ " . sprintf("%.02f", $cost) . "</td>";
					
					$display_block .= "<td><a href='remove_from_cart.php?id=" . $id . $return . "'>Remove</td>";
					$display_block .= "</tr>\n";
					$item_list .= $hut . " on " . $book_date . " for " . $num_persons . (($num_persons == 1) ? " person. " : " people. | ");
				}
				
				$_SESSION["Description"] = $item_list;
				$_SESSION["Payment_Amount"] = $total_cost;
			
				$display_block .= "<tr>";
				$display_block .= "<td colspan='3'><strong>Total</strong></td>";
				$display_block .= "<td>$ " . sprintf("%.02f", $total_cost);
				$display_block .= "</td><td></td></tr>\n";
			
				$display_block .= "<td colspan='5'><h3><a href='checkout.php?link=yes'>Proceed to checkout</a></h3></td></table>\n\n";
			
			}
		
		if (isset($_SESSION["admin"])) $display_block .= "<p><strong><a href='admin-complimentary_made.php'>Book as Complimentary Nights</a></strong></p>";
		
		return $display_block;
	}
	
function html_cart_paypal() {

	$display_block = "";
	$get_cart_res = db_get_cart();
	
	$stuff = $return;

	$display_block .= "<h3>Your Selected Bookings</strong></h3>\r";
	$display_block .= "<table class='cabin-availability'>\r";
	$display_block .= "<tr>";
	$display_block .= "<th>Cabin</th><th>Night of</th><th>Number of persons</th><th>Cost</th>";
	$display_block .= "</tr>\r";
	$item_list = "";
			
	while ($cart_info = mysql_fetch_array($get_cart_res)) {
		$id = $cart_info["ID"];
		$hut = $cart_info["HutName"];
		$num_persons = $cart_info["num_persons"];
		$price = $cart_info["Price"];
		$book_date = format_db_date($cart_info["BookedDate"], "d M Y");
		$date = date("Y-m-d", $book_date);
		$cost = $price * $num_persons;
		$total_cost = $total_cost + $cost;
		$total_person_nights = $total_person_nights + $num_persons;
		$display_block .= "<tr>";
		$display_block .= "<td>" . $hut ."</td>";
		$display_block .= "<td>" . $book_date . "</td>";
		$display_block .= "<td>" . sprintf("%s", $num_persons) . "</td>";
		$display_block .= "<td>$ " . sprintf("%.02f", $cost) . "</td>";
		
		$display_block .= "</tr>\n";
		$item_list .= $hut . " on " . $book_date . " for " . $num_persons . (($num_persons == 1) ? " person. " : " people. ");
	}
				
	$_SESSION["Description"] = $item_list;
	$_SESSION["Payment_Amount"] = $total_cost;

	$display_block .= "<tr>";
	$display_block .= "<td colspan='3'><strong>Total</strong></td>";
	$display_block .= "<td>$ " . sprintf("%.02f", $total_cost);
	$display_block .= "</td></tr>\n";

	$display_block .= "</table>\n\n";
	$display_block .= "<p class='centered'><strong>Note: Items in your cart will be removed if not paid for within 10 minutes.</strong></p>";
		
	if (isset($_SESSION["admin"])) $display_block .= "<p><strong><a href='admin-complimentary_made.php'>Book as Complimentary Nights</a></strong></p>";
		
	return $display_block;
}

function makeBookedHuts ($first,$last,$email,$session,$FiscalYear) 

	{
		$get_cart_res = db_get_cart_from_sessionid($session);

			if (mysql_num_rows($get_cart_res) < 1) {
	
				exit;
			
			} else {
	
				while ($cart_info = mysql_fetch_array($get_cart_res)) {
				
					$id = $cart_info["ID"];
					db_delete_from_cart($id); // Delete from Cart
					$hut_id = $cart_info["HutID"];
					$hut = $cart_info["HutName"];
					$num_persons = $cart_info["num_persons"];
					$price = $cart_info["Price"];
					$date = $cart_info["BookedDate"];
					$book_date = format_db_date($cart_info["BookedDate"], "d M Y");
					
					$logged_by = "PayPalExpress";
						
					if (isset($_SESSION["admin"])) {
					
						$first = $_SESSION["f_name"];
						$last = $_SESSION["l_name"];
						$logged_by = "Complimentary";
					}
					
					db_add_nights_booked($session, $hut_id, $FiscalYear, $date, $num_persons, $email, $first, $last, $logged_by);
				}
			}
	}

?>
	
