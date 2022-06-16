<?php
	include "AdminHeader.php" ;
	
      session_start();
       
      include("admin_util.php");
 
      global $SAVEDSESSION;
      get_session(session_id());
      $_SESSION = $SAVEDSESSION;
      
      $_SESSION['run-beds'] += 1;

	  $hut_id = $_REQUEST["hut"];
	  $day = $_REQUEST["day"];
	  $month = $_REQUEST["month"];
	  $year = $_REQUEST["year"];
	  
	  $sd = $_REQUEST["sd"];
	  $sm = $_REQUEST["sm"];
	  $sy = $_REQUEST["sy"];
	  $count =$_REQUEST["count"];
	  
//display_stuff();
	  
	  $booked_date = mktime(0,0,0,$month,$day,$year);
	  $booking = date("Y-m-d", $booked_date);
	  
//echo "<pre>$booking</pre>\n";
	  
	  admin_db_connect();

		$get_hut_res = db_get_hut($hut_id);
        $hut = mysql_fetch_array($get_hut_res);
//echo "<pre>" . print_r($hut,true) . "</pre>\n";
        $hut_name = $hut['HutName'];
        $available = db_available_beds2($hut['HutID'], $booking);
        $hutPicture = hutPicture($hut_id);
echo "<pre>$available</pre>\n";       
 		echo "<div class='announcement'>\n";
 		echo "<img src='" . $hutPicture . "' >";
        echo "<p>Please select the number of people for the night of $booking";
        echo " at the ";
        echo $hut_name;
        echo " hut.</p>\n";
        
	    echo "<form action='book_nights.php' method='POST'>\n";
		echo "<select name='num_persons'>\n";
        for ($i=1; $i<=$available; $i++)
        {
          echo "<option value='" . $i . "'";
          if ($num_persons == $i) echo " SELECTED";
          echo ">" . $i  . "</option>\n";
        }
        echo "</select>\n";
        
        echo "<input type='submit' value='Add to cart' />\n";
        
        echo "<input type='hidden' name='hut' value='" . $hut_id . "' />\n";
        echo "<input type='hidden' name='hut_name' value='" . $hut_name . "' />\n";
       echo "<input type='hidden' name='day' value='" . $day . "' />\n";
        echo "<input type='hidden' name='month' value='" . $month . "' />\n";
        echo "<input type='hidden' name='year' value='" . $year . "' />\n";
        echo "<input type='hidden' name='sd' value='" . $sd . "' />\n";
        echo "<input type='hidden' name='sm' value='" . $sm . "' />\n";
        echo "<input type='hidden' name='sy' value='" . $sy . "' />\n";
        echo "<input type='hidden' name='count' value='" . $count . "' />\n";
      
        echo "</form>\n";
        echo "</div>\n";
        $SAVEDSESSION = $_SESSION;
        save_session (session_id());
//		display_stuff();
	
	include "AdminFooter.php";

?>
