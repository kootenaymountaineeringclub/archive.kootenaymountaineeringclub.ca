<?php 
	require ("util.php");
	$report_address = "membership@kootenaymountaineeringclub.ca,tim@timclinton.ca,abigail.c.wilson@gmail.com";
	$oops_address = "tim@timclinton.ca,abigail.c.wilson@gmail.com";
?>

<!DOCTYPE html>
<html>
<head>

	<?php include (PHP_ROOT . "/includes/head-first.incl.html") ; ?>

	<title>KMC: Kootenay Mountaineering Membership</title>
	
	<?php include (PHP_ROOT . "/includes/head-2nd.incl.html") ; ?>
	<?php include ("../includes/MembershipBackgroundImage.incl.html") ; ?>
	
	<link rel="stylesheet" type="text/css" href="/css/membership-styles.css">

</head>

<body>

<div id="master">

<header>

	<?php include ("../../includes/page-header-club.incl.html") ;
		include (PHP_ROOT . "/includes/header-contents.incl.html") ; ?>
	
</header>

<div id="content">

<?php include ("../includes/club-membership.incl.html") ; ?>

<section>

<?php
	db_connect();
	
	$savefile = "saved/" . $_REQUEST['MembID'] . ".txt";
	$SAVEDSESSION = json_decode(file_get_contents($savefile), true);

	$MembProcess = $SAVEDSESSION["PROCESS"];
	
	$OOPS = "";
	$membtype = "new";
	if ($MembProcess == "RENEW1" || $MembProcess == "RENEW2") {
		$membtype = "renewal";
		$sql = "INSERT INTO MemberArchive (MembershipID,Year,MembershipType,AgeRange,FirstName,NickName,LastName,DistinctName,StreetAddress,StreetAddress2,City,Province,PostalCode,Country,Email,Phone) select MembershipID,Year,MembershipType,AgeRange,FirstName,NickName,LastName,DistinctName,StreetAddress,StreetAddress2,City,Province,PostalCode,Country,Email,Phone from Member where MembershipID = " . $SAVEDSESSION['MembID'];
		$result = mysql_query($sql);
		if(!$result) $OOPS .= "Unable to archive " . $SAVEDSESSION['MembID'] . mysql_error() . "\n";
		$sql = "delete from Member where MembershipID = " . $SAVEDSESSION['MembID'];
		$result = mysql_query($sql);
		if(!$result) $OOPS .= "Unable to delete last year " . $SAVEDSESSION['MembID'] . mysql_error() . "\n";
	}


	$totalAmt =  $SAVEDSESSION["MembCost"];
	$description =  $SAVEDSESSION["paydescription"];
				    		
	$InsertFields1 = "insert into `Member` (`MembershipID`,`Year`,`MembershipType`,`AgeRange`,
		`FirstName`,`NickName`,`LastName`,`DistinctName`,
		`StreetAddress`,`StreetAddress2`,`City`,`Province`,`PostalCode`,
		`Email`,`Phone`,`KmcNewsletter`,`FmcbcNewsletter`,
		`PrivateName`,`Amount`) values ";
					
	$InsertFields2 = "insert into `Member` (`MembershipID`,`Year`,`MembershipType`,`AgeRange`,
		`FirstName`,`NickName`,`LastName`,`DistinctName`,
		`StreetAddress`,`StreetAddress2`,`City`,`Province`,`PostalCode`,
		`Email`,`Phone`,`KmcNewsletter`,`FmcbcNewsletter`,`PrivateName`) values ";
	  

	$DistinctName1 = $SAVEDSESSION["LastName1"] . ", " . $SAVEDSESSION["FirstName1"];
	
	$YEAR = WhichMembershipYear();
	
	$InsertValues1 = "(" . $SAVEDSESSION["MembID"] . ", '" . $YEAR . "', '" . 
		$SAVEDSESSION["MembType"] . "', '" . 
		$SAVEDSESSION['name1_age'] . "', '" .
		$SAVEDSESSION["FirstName1"] . "', '" . 
		$SAVEDSESSION["Nickname1"] . "', '" . 
		$SAVEDSESSION["LastName1"] . "', '" .
		$DistinctName1 . "', '" .
		$SAVEDSESSION["Addr1"] . "', '" . 
		$SAVEDSESSION["Addr2"] . "', '" . 
		$SAVEDSESSION["City"] . "', '" . 
		$SAVEDSESSION["Prov"] . "', '" . 
		$SAVEDSESSION["Postal"] . "', '" . 
		$SAVEDSESSION["Email1"] . "', '" . 
		$SAVEDSESSION["Phone1"] . "', " . 
		(($SAVEDSESSION["kmc"] == "") ? '0' : '1') . ", " . 
		(($SAVEDSESSION["fmcbc"] == "") ? '0' : '1') . ", " . 
		$SAVEDSESSION["private"] . ", " . 
		$totalAmt . ")";

	if ( $SAVEDSESSION["MembType"] == "Couple" ) {
  
		$DistinctName2 = $SAVEDSESSION["LastName2"] . ", " . $SAVEDSESSION["FirstName2"];
	  
	  	$InsertValues2 = "('" . $SAVEDSESSION["MembID"] . "', '" . $YEAR . "', '" . 
	  		$SAVEDSESSION["MembType"] . "', '" . 
			$SAVEDSESSION["name2_age"] . "', '" .
			$SAVEDSESSION["FirstName2"] . "', '" . 
			$SAVEDSESSION["Nickname2"] . "', '" . 
			$SAVEDSESSION["LastName2"] . "', '" . 
			$DistinctName2 . "', '" .
			$SAVEDSESSION["Addr1"] . "', '" . 
			$SAVEDSESSION["Addr2"] . "', '" . 
			$SAVEDSESSION["City"] . "', '" . 
			$SAVEDSESSION["Prov"] . "', '" . 
			$SAVEDSESSION["Postal"] . "', '" . 
			(($SAVEDSESSION["SharedEmail"] == "1") ? $SAVEDSESSION["Email1"] : $SAVEDSESSION["Email2"] ) . "', '" . 
			$SAVEDSESSION["Phone2"] . "', " . 
			(($SAVEDSESSION["kmc"] == "") ? '0' : '1') . ", " . 
			(($SAVEDSESSION["fmcbc"] == "") ? '0' : '1') . ", " . 
			$SAVEDSESSION["private"] . ")";
  	}
	  											
	$query = $InsertFields1 . $InsertValues1;
	
	$result = mysql_query($query) ;
	
	if ( !$result ) $OOPS .= ("Oops.  Error: " . mysql_error()) . "\n\n" . $query . "\n\n";
	
	if ( $SAVEDSESSION["MembType"] == "Couple") {
	
		$query2 = $InsertFields2 . $InsertValues2;
	
		$result = mysql_query($query2);
		if ( !$result ) $OOPS .= ("Oops.  Error: " . mysql_error()) . "\n\n" . $query2 . "\n\n";
  	}
	  	
  	if ($OOPS) {
		echo "<p>Sadly, there has been a database error.</p>";
		echo "<p>The web site manager has been informed, and will contact you. If we got to this point, it means that PayPal has received your money, and your membership will be honoured.</p> ";
		echo "<p>Apologies for the inconvenience and bother.</p>";
		
		$memberdirsend = "This was a " . $membtype . " membership application.\n\n";
		$memberdirsend .= "Membership: " . $description . " for " . $SAVEDSESSION["membNames"] . "\n\n";
		$memberdirsend .= "Membership ID: " . $SAVEDSESSION['MembID'] . "\n\n";
		$memberdirsend .= $OOPS;
		
		mail($oops_address,'KMC Membership Ooops',$memberdirsend);

	} else { ?>
	
			<!-- Display the Transaction Details-->
	<div class="info announcement">
	<h3> <?php echo($SAVEDSESSION["membNames"]); ?>, welcome to the club.</h3>
	<p>Your <?php echo $SAVEDSESSION["MembType"] ?> Membership with the KMC is complete. Your membership covers the year <?php echo $YEAR ?>.</p>
				
			<p>Documents accessible only to Club Members are at: https://kootenaymountaineeringclub.ca/club-info/documents/member-files/ with the login ID of KMCmember and password of 0ld-5l0ry (both case sensitive, password has 2 zeros, not Ohs, and 2 els, not eyes).</p>
				
  			</div>

<?php
			$address = "";
	    	if ( $SAVEDSESSION['Email1'] != "" ) $address = $SAVEDSESSION['Email1'];
	
			if ( $SAVEDSESSION['Email2'] != "" ) {
				if ($address != "") $address .= ", ";
				$address .= $SAVEDSESSION['Email2'];
			}
				
			$SAVEDSESSION['MembEmailTo'] = $address;
				
			$tosend = "Kootenay Mountaineering Club Membership complete.\n\n" ;
			$tosend .= $description . " for " . $SAVEDSESSION["membNames"] . "\n\n";
			$tosend .= "Thank you. PayPal should also send you a receipt.\nFor questions regarding your membership, etc. please send email to membership@kootenaymountaineeringclub.ca\n\n";
	
			$msg = file_get_contents('../safe/technical-tips-short.txt');
	
			mail( $address,'KMC Membership',$tosend . $msg );
				
			$memberdirsend = "This was a ". $membtype . " membership application.\n\n";
	  		$memberdirsend .= "Membership: " . $description . " for " . $SAVEDSESSION["membNames"] . "\n\n";
	  		$memberdirsend .= "Email: " . $SAVEDSESSION["MembEmailTo"] . "\n\n";
	  		
			mail($report_address,'KMC Membership',$memberdirsend);
			
			exec ('../admin/make-membership-lists.php');
//			mail('tim@timclinton.ca','Membership Lists','Lists done.');
		}
	?>
		
	</section>
</div> <!-- end content -->

<footer>

<?php include ("../includes/club-membership.incl.html") ; ?>

</footer>

</div> <!-- end master -->

</body>
</html>
