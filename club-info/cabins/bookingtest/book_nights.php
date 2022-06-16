<?php
      include("util.php");
      session_start();

	  $hut_id = $_REQUEST["hut"];
	  $day = $_REQUEST["day"];
	  $month = $_REQUEST["month"];
	  $year = $_REQUEST["year"];
	  $booked_date = mktime(0,0,0,$month,$day,$year);
 
      // Connect to kmc_huts database
  		
	  db_connect();
      
      if (isset($_POST["hut"]))			// hut is a hidden POST field, as opposed to REQUEST field
      {   // going back for another date
        $num_persons = $_POST["num_persons"];
        $_SESSION["num_persons"] = $num_persons;
        
        db_add_to_cart($hut_id, date("Y-m-d",$booked_date), $num_persons);
        
        header("Location: hut_availability.php");
        exit;
      } else {  // ask number of bunks desired
?>

<!DOCTYPE html>
<html>
<head>
<!--#include virtual=/includes/head-first.incl.html -->

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
        $num_persons = $_SESSION["num_persons"];
		$get_hut_res = db_get_hut($hut_id);
        $hut = mysql_fetch_array($get_hut_res);
        $hut_name = $hut['HutName'];
        $hut_picture = $hut['Picture'];
        $available = db_available_beds2($hut_id, date("Y-m-d",$booked_date));
        
 		echo "<div class='announcement'>\n";
        echo "<p>Please select the number of people for the night of ";
		    echo date("d M Y", $booked_date);
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
        echo "<input type='hidden' name='day' value='" . $day . "' />\n";
        echo "<input type='hidden' name='month' value='" . $month . "' />\n";
        echo "<input type='hidden' name='year' value='" . $year . "' />\n";
        
        echo "</form>\n";
        echo "</div>\n";
      }
?>

</section>
</div> <!-- end content -->

<footer>

<?php include html_footer_strip() ; ?>

</footer>

</div> <!-- end master -->

</body>
</html>

