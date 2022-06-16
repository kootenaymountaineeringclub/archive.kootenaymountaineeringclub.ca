<?php
      session_start();
       
      include("util.php");
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
	  
	  $booked_date = mktime(0,0,0,$month,$day,$year);
	  $booking = date("Y-m-d", $booked_date);
	  db_connect();
 ?>

<!DOCTYPE html>
<html>
	
<head>
	<?php include ("/includes/head-first.incl.html") ?>

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
 		$get_hut_res = db_get_hut($hut_id);
        $hut = mysql_fetch_array($get_hut_res);
        $hut_name = $hut['HutName'];
        $available = db_available_beds2($hut_id, $booking);
        $hutPicture = hutPicture($hut_id);
        
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
?>

</section>
</div> <!-- end content -->

<footer>

<?php include html_footer_strip() ; ?>

</footer>

</div> <!-- end master -->

</body>
</html>

