<?php
	define('NUM_SHOW_DATES', '6');
	define('PHP_ROOT','/var/www/vhosts/kootenaymountaineeringclub.ca/httpdocs');

function connect() {
	$db = mysqli_connect("localhost:3306","kmcwe_kmc","zlika9p","kmcweb_0_kmc");
	
	if (mysqli_connect_errno()) {
	    printf("Connect failed: %s\n", mysqli_connect_error());
	    exit();
	}
	return $db;
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
	
function get_huts() {

	$db = connect();
	$HUTS = array();

	$sql = "SELECT HutID, HutName, Capacity, Price FROM Hut ORDER BY Sequence";
	$which = 0;
	if ($result = mysqli_query($db, $sql)) {
	    while ($row = mysqli_fetch_array($result)) {
	        $HUTS[$which] = $row;
	        $which++;
        }
    }

	mysqli_free_result($result);
	mysqli_close($db);
	return $HUTS;
}
	
function get_hut($hut_id) {

	$db = connect();
	$HUT = array();

	$get_hut_sql = "SELECT HutID, HutName, Capacity, Price, Picture
		FROM Hut WHERE HutID = $hut_id";
	
	if ($result = mysqli_query($db,$get_hut_sql)) {
		
		$HUT = mysqli_fetch_array($result);
		
	} else {
		echo "<p>No result</p>\n";
	}

//	echo "<pre>" . print_r($HUT,true) . "</pre>\n";
	
	mysqli_free_result($result);
	mysqli_close($db);
	return $HUT;
}
	
function get_bookings($date) {

	$db = connect();
	$BOOKINGS = array();

	$sql = "SELECT h.HutName, h.HutID, h.Capacity, b.NumPersons, b.BookedDate, b.LoggedBy, b.BookedByEmail, b.BookedByFirstName, b.BookedByLastName, b.Touched FROM BookedHutDay b INNER JOIN Hut h ON b.HutID = h.HutID WHERE b.BookedDate = date('" . $date . "') ORDER BY h.Sequence";
	
//	echo "<pre>" . $sql . "</pre>\n";
	
	$start = 1;
	$which = 0;
	if ($result = mysqli_query($db, $sql)) {
	    while ($row = mysqli_fetch_array($result)) {
	//	    echo "<p>" . $row['HutName'] . " " . $row['BookedDate'] . " " . $row['NumPersons'] . "<p>";
	        $BOOKINGS[$which] = $row;
	        $which++;
        }
    }

	mysqli_free_result($result);
	mysqli_close($db);
	return $BOOKINGS;
}
	
function available_beds($book_date) {
	
	$db = connect();
	
	$AVAILABLE = array();
	
	$HUTS = get_huts();
	$hut_count = 1;
	
	foreach ($HUTS as $hut ) {
		
		// already booked
				
		$booked_sql = "SELECT SUM(NumPersons) AS BookedBeds FROM BookedHutDay WHERE HutID = "
			 . $hut['HutID'] . " AND BookedDate = '" . $book_date . "'";
		
		$result = mysqli_query($db,$booked_sql);
		
		if ($result) {
			$booked_array = mysqli_fetch_array($result);
			$booked = $booked_array['BookedBeds'];
		} else {
			$booked = 0;
		}
		
		// in process
		
		$in_cart = 0;
		
		$in_cart_sql = "SELECT SUM(num_persons) AS InCartBeds FROM Cart Where HutID = " . 
			$hut['HutID'] . " AND BookedDate = '" . date($book_date) . "'";
		
		$result = mysqli_query($db, $in_cart_sql);
			
		if ($result) {
			$in_cart_array = mysqli_fetch_array($result);
			$in_cart = $in_cart_array['InCartBeds'];
		}

		$avail = $hut['Capacity'] - ($booked + $in_cart);
		
		$AVAILABLE[$hut_count] = array( 'HutID' => $hut['HutID'], 'HutName' => $hut['HutName'], 'BedsAvailable' => $avail);
		$hut_count++;
	}
	
	echo "<pre>" . print_r($AVAILABLE,true) . "</pre>\n";
	
	mysqli_free_result($result);
	mysqli_close($db);
	return($AVAILABLE);

}

function hut_available_beds($hutID, $book_date, $numb_beds) {
	
	$db = connect();
	
	$AVAILABLE = array();
	
		
	// already booked
			
	$booked_sql = "SELECT SUM(NumPersons) AS BookedBeds FROM BookedHutDay WHERE HutID = "
		 . $hutID . " AND BookedDate = '" . $book_date . "'";
	
	$result = mysqli_query($db,$booked_sql);
	
	if ($result) {
		$booked_array = mysqli_fetch_array($result);
		$booked = $booked_array['BookedBeds'];
	} else {
		$booked = 0;
	}
	
	// in process
	
	$in_cart = 0;
	
	$in_cart_sql = "SELECT SUM(num_persons) AS InCartBeds FROM Cart Where HutID = " . 
		$hutID . " AND BookedDate = '" . date($book_date) . "'";
	
	$result = mysqli_query($db, $in_cart_sql);
		
	if ($result) {
		$in_cart_array = mysqli_fetch_array($result);
		$in_cart = $in_cart_array['InCartBeds'];
	}

	$avail = $numb_beds - ($booked + $in_cart);
	
	mysqli_free_result($result);
	mysqli_close($db);
	return($avail);

}

function add_to_cart($hut_id, $book_date, $num_persons) {
	
	echo "<p>$hut_id - $book_date - $num_persons</p>";
	$db = connect();
	$session_id = session_id();
	$sql = "INSERT INTO Cart (sessionID, HutID, BookedDate, num_persons) VALUES (";
	$sql .= "'" . $session_id . "', ";
	$sql .= $hut_id . ", ";
	$sql .= "'" . $book_date . "', ";
	$sql .= $num_persons . ")";
	
	$insert_items_in_cart_res = mysqli_query($db,$sql)
		or die("INSERT into Cart failed: " . mysql_error());

	return true;
}

function get_cart() {
	$session_id = session_id();
	$db = connect();
	
	$get_cart_sql = "SELECT ID, c.ID, c.HutID, h.HutName, c.num_persons, 
		h.Price, c.BookedDate, c.Paid 
		FROM Cart c INNER JOIN Hut h ON c.HutID = h.HutID 
		WHERE c.sessionID = '" . $session_id . "' ORDER BY c.BookedDate";
		
		$result = mysqli_query($get_cart_sql)
			or die("SELECT From Cart failed: " .mysql_error());
		return $result;
	}

	
function html_cart($return) {

	$display_block = "";
	$cart_res = get_cart();
	
	$stuff = $return;

	if (mysqli_num_rows($cart_res) < 1) {
		$display_block .= "<p>You have no huts booked.</p>";
	} else {
		$display_block .= "<h3>Your Selected Bookings</strong></h3>\r";
		$display_block .= "<table class='cabin-availability'>\r";
		$display_block .= "<tr>";
		$display_block .= "<th>Cabin</th><th>Night of</th><th>Number of persons</th><th>Cost</th>";
		if (! $LinkToPayPal ) $display_block .= "<th>Action</th>";
		$display_block .= "</tr>\r";
		$item_list = "| ";
	
		while ($cart_info = mysqli_fetch_array($cart_res)) {
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

