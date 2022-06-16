<?php
	include "AdminHeader.php" ;

	include 'date_util.php' ;
	//include 'util.php' ;
	include 'admin_util.php' ;
	
	$id = $_REQUEST['id'];
	
	global $SAVEDSESSION;
	get_session($id);

	//display_stuff();
	
	$_SESSION['count'] = $SAVEDSESSION['count'] = 0;
	$_SESSION['start'] = $SAVEDSESSION['start'] = 1;
	save_session($id);

	admin_db_connect();
	db__clear_checkout(); // Clear the At Checkout Page indicator in Cart
	db_clean_cart(); // Clean out timed out cart items
//	echo "<p>After clear</p>";	
	
?>

		<div class="announcement">
		<p>Please select the date you would like the comp booking to begin:</p>
		
	<?php      
	 	$url = "'";

        date_default_timezone_set('America/Los_Angeles');
        $today_array = getdate(time() + (24 * 60 * 60));
        echo "<form method='POST' action='hut_availability.php?count=0&id=" . $id . "'>\n";
        echo day_select("sd", $today_array);
        echo month_select("sm", $today_array);
        echo year_select("sy", $today_array);
        echo "\n<input type='hidden' name='count' value=0>";
        echo "\n<input type='submit' name='submit' value='Show Availability'></form>\n";
        echo "</form>\n";
        
 //       display_stuff();
	
	include "AdminFooter.php";

    ?>
