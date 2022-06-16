<!DOCTYPE html>
<html>
<head>
	<?php
		include ("../safe/db-util.php") ;
		include (PHP_ROOT . "/includes/head-first.incl.html") ;
	?>

	<title>Membership Admin: List Memberships</title>
	
	<?php include (PHP_ROOT . "/includes/head-2nd.incl.html") ; ?>
	
	<?php include (PHP_ROOT . "/js/header-pictures.js") ; ?>
	
	<link rel="stylesheet" type="text/css" href="/css/membership-styles.css">

</head>
<body>

<div id="master">

	<header>

	<?php
		include ("../../includes/page-header-club.incl.html") ;
		include (PHP_ROOT . "/includes/header-contents.incl.html") ;
	?>
	
	</header>

	<div id="content">

	<?php include ("../includes/club-membership.incl.html") ; ?>	
	
	<section>

		<p class="centered"><a href="MembAdmin.php">Membership Admin Page</a></p>
	
	<?php

		$name = "";
		$numb = "";
		
		if ($_POST["memb_name"] !== "") $name = $_POST["memb_name"];
		if ($_POST["memb_numb"] !== "") $numb = $_POST["memb_numb"];
		
		if ($numb) {
			$sql = "select TransactionID, MembershipID, Year, MembershipType, AgeRange, FirstName, NickName, LastName, DistinctName, StreetAddress, StreetAddress2, City, Province, PostalCode, Country, Email, Phone, KmcNewsletter, FmcbcNewsletter, PrivateName, Inserted from Member where MembershipID = " . $numb;
		} else {
			$sql = "select TransactionID, MembershipID, Year, MembershipType, AgeRange, FirstName, NickName, LastName, DistinctName, StreetAddress, StreetAddress2, City, Province, PostalCode, Country, Email, Phone, KmcNewsletter, FmcbcNewsletter, PrivateName, Inserted from Member where LastName = '" . $name . "' order by MembershipID";
		}
		$term = (($name) ? $name : $numb);
	
	//	echo "<p>" . $sql . "</p>\n";
	
		require_once 'admin-util.php';
	
		db_connect();
		
		$res = mysql_query($sql);
		if (!$res)
		{
		 	debug_log("db_FindMembers: SELECT  Member failed: SQL: " . $sql . " Error: " . mysql_error());
			die("db_FindMembers: SELECT  Member failed: SQL: " . $sql . " Error: " . mysql_error());
		}
			
		if ( mysql_num_rows($res) == 0 ) {
			echo "<p>Nothing found for " . $term . "</p>\n";
			
		} else {
			
			for($x = 0 ; $x < mysql_num_rows($res) ; $x++)
			{ 
				echo "<table>\n";
		
			   	$name = mysql_fetch_assoc($res);
			   	
				$member = "<tr><td>Inserted</td><td>" . $name["Inserted"] . "</td></tr>\n";
				$member .= "<tr><td>Transaction ID</td><td>" . $name["TransactionID"] . "</td></tr>\n";
				$member .= "<tr><td>Membership ID</td><td>" . $name["MembershipID"];
				$member .= '<form method="POST" action="admin_edit_membership.php">';
				$member .= '<input type="submit" value="  EDIT  ">';
				$member .= '<input type="text" hidden id="memb_numb" name="memb_numb" value="' . $name['MembershipID'] . '"> &nbsp;&nbsp;';
				$member .= '<input type="text" hidden id="trans_id" name="trans_id" value="' . $name['TransactionID'] . '">';
				$member .= '</form>';
				
				$member .= "</td></tr>\n" ;
				$member .= "<tr><td>Membership Type</td><td>" . $name["MembershipType"] . "</td></tr>\n";
				$member .= "<tr><td>Private Name</td><td>" . (($name["PrivateName"]) ? "Yes" : "No") . "</td></tr>\n";
				$member .= "<tr><td>Last Name</td><td>" . $name["LastName"] . "</td></tr>\n";
				$member .= "<tr><td>First Name</td><td>" . $name["FirstName"] . "</td></tr>\n";
				$member .= "<tr><td>Nickname</td><td>" . $name["NickName"] . "</td></tr>\n";
				$member .= "<tr><td>Address 1</td><td>" . $name["StreetAddress"] . "</td></tr>\n";
				$member .= "<tr><td>Address 2</td><td>" . $name["StreetAddress2"] . "</td></tr>\n";
				$member .= "<tr><td>City</td><td>" . $name["City"] . "</td></tr>\n";
				$member .= "<tr><td>Province</td><td>" . $name["Province"] . "</td></tr>\n";
				$member .= "<tr><td>Post Code</td><td>" . $name["PostalCode"] . "</td></tr>\n";
				$member .= "<tr><td>Email</td><td>" . $name["Email"] . "</td></tr>\n";
				$member .= "<tr><td>Phone</td><td>" . $name["Phone"] . "</td></tr>\n";
				$member .= "<tr><td>Age Range</td><td>" . $name["AgeRange"] . "</td></tr>\n" ;
				$member .= "<tr><td>KMC Newsletter</td><td>" . (($name["KMCNewsletter"]) ? "Yes" : "No") . "</td></tr>\n";
				$member .= "<td>FMCBC Newsletter</td><td>" . (($name["FmcbcNewsletter"]) ? "Yes" : "No") . "</td></tr>\n";
				
				echo ($member);
				echo "</table>\n";
			}
		}
		
	?>

	</section>
	
	</div>

	<footer>

	<?php include ("../../includes/club-membership.incl.html") ; ?>

	</footer>

</div> <!-- end master -->

</body>
</html>