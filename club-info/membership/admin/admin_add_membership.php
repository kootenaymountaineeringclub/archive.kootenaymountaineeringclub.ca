<?php 
	require ("admin-util.php");
	$oops_address = "tim@timclinton.ca";
?>

<!DOCTYPE html>
<html>
<head>

	<?php 
		include (PHP_ROOT . "/includes/head-first.incl.html") ;
	?>

	<title>Membership Admin: Add Membership</title>
	
	<?php include (PHP_ROOT . "/includes/head-2nd.incl.html") ; ?>
	
	<?php include (PHP_ROOT . "/js/header-pictures.js") ; ?>
		
	<link rel="stylesheet" type="text/css" href="/css/membership-styles.css">

</head>

<body>

<div id="master">

<header>

	<?php include ("../../includes/page-header-club.incl.html") ; ?>
	<?php include (PHP_ROOT . "/includes/header-contents.incl.html") ; ?>
	
</header>

<div id="content">
<?php include ("../includes/club-membership.incl.html") ; ?>
	<section>
		

<?php
		
	$InsertFields1 = "insert into `Member` (`MembershipID`,`Year`,`MembershipType`,`FirstName`,`NickName`,`LastName`,`DistinctName`,
	  						`StreetAddress`,`StreetAddress2`,`City`,`Province`,`PostalCode`,
	  						`Email`,`Phone`,`KmcNewsletter`,`FmcbcNewsletter`,
	  						`PrivateName`,`PaymentMethod`,`Amount`) values ";
	  			
	$InsertFields2 = "insert into `Member` (`MembershipID`,`Year`,`MembershipType`,`FirstName`,`NickName`,`LastName`,`DistinctName`,
	  					`StreetAddress`,`StreetAddress2`,`City`,`Province`,`PostalCode`,
	  					`Email`,`Phone`,`KmcNewsletter`,`FmcbcNewsletter`,`PrivateName`,`PaymentMethod`) values ";

	db_connect();
 						
	$MembID =	get_memb_id();
	$Year =  WhichMembershipYear();
	  
	$Cost = $_REQUEST['MembCost'];
	if ($_REQUEST['kmc']) $Cost += CONFIG_NEWSLETTER ;

	$DistinctName1 = $_REQUEST["LastName1"] . ", " . $_REQUEST["FirstName1"];
	$DistinctName2 = $_REQUEST["LastName2"] . ", " . $_REQUEST["FirstName2"];
		
	$InsertValues1 = "(" . $MembID . 
		", " . $Year .  ", '" . 
		$_REQUEST["MembType"] . "', '" . 
		$_REQUEST["FirstName1"] . "', '" . 
		$_REQUEST["Nickname1"] . "', '" . 
		$_REQUEST["LastName1"] . "', '" .
		$DistinctName1 . "', '" .
		$_REQUEST["Addr1"] . "', '" . 
		$_REQUEST["Addr2"] . "', '" . 
		$_REQUEST["City"] . "', '" . 
		$_REQUEST["Prov"] . "', '" . 
		$_REQUEST["Postal"] . "', '" . 
		$_REQUEST["Email1"] . "', '" . 
		$_REQUEST["Phone1"] . "', " . 
		(($_REQUEST["kmc"] == "") ? '0' : '1') . ", " . 
		(($_REQUEST["fmcbc"] == "") ? '0' : '1') . ", " . 
		$_REQUEST["private"] . 
		", 'Paper Application', " . 
		$Cost . ")";
			
	$MembNames = $_REQUEST["FirstName1"] . " " . $_REQUEST["LastName1"] ;

	if ( $_REQUEST["MembType"] == "Couple" ) {
	  	$InsertValues2 = "(" . 
	  	$MembID . ", " . $Year .  ", '" .  
	  	$_REQUEST["MembType"] . "', '" . 
			$_REQUEST["FirstName2"] . "', '" . 
			$_REQUEST["Nickname2"] . "', '" . 
			$_REQUEST["LastName2"] . "', '" . 
			$DistinctName2 . "', '" .
			$_REQUEST["Addr1"] . "', '" . 
			$_REQUEST["Addr2"] . "', '" . 
			$_REQUEST["City"] . "', '" . 
			$_REQUEST["Prov"] . "', '" . 
			$_REQUEST["Postal"] . "', '" . 
			(($_REQUEST["SharedEmail"] == "1") ? $_REQUEST["Email1"] : $_REQUEST["Email2"] ) . "', '" . 
			$_REQUEST["Phone2"] . "', " . 
			(($_REQUEST["kmc"] == "") ? '0' : '1') . ", " . 
			(($_REQUEST["fmcbc"] == "") ? '0' : '1') . ", " . 
			$_REQUEST["private"] . 
			", 'Paper Application')";
			
			$MembNames .= " and " . $_REQUEST["FirstName2"] . " " . $_REQUEST["LastName2"] ;

		}
			
		$query1 = $InsertFields1 . $InsertValues1;
		
		$result = mysql_query($query1) ;
		if ( !$result ) $OOPS = ("Oops.  Error: " . mysql_error()) . "\n\n";
		
		$query2 = $InsertFields2 . $InsertValues2;
		
		if ( $_REQUEST["MembType"] == "Couple") $result = mysql_query($query2);
		if ( !$result ) $OOPS .= ("Oops.  Error: " . mysql_error()) . "/n/n";
	  
	  if ($OOPS)
	  {
		  echo "<p>Sadly, there has been a database error.</p>";
		  echo "<p>The web site manager has been informed, and will contact you.</p> ";
			echo "<p>Apologies for the inconvenience and bother.</p>";

  		$memberdirsend = "Membership: " . $description . " for " . $MembNames . "\n\n";
  		$memberdirsend .= "Membership ID: " . $MembID . "\n\n";
  		$memberdirsend .= $OOP . "\n\n";
  		$memberdirsend .= $query1 . "\n\n";
  		$memberdirsend .= $query2 . "\n\n";
  		
			mail($oops_address,'KMC Membership',$memberdirsend);

	  }
	  else
	  {
  		$memberdirsend = "Membership: " . $description . " for " . $MembNames . "\n\n";
  		$memberdirsend .= "Membership ID: " . $MembID . "\n\n";  		
		mail($oops_address,'KMC Membership by paper',$memberdirsend);
  ?>
  		<div class="formPart">
	  		
	  		<?php
		  		echo "<p class=centered>" . $_REQUEST['MembType'] . " membership for " . $MembNames ;
		  		echo " created. Membership ID is " . $MembID . ".</p>";
	  		?>
	  		
  		</div>
<?php		}

		$logfile = fopen("logs/" . $MembID . ".txt" , 'w');
		fwrite ($logfile, "Session:\n" . print_r($_REQUEST,TRUE) . "\n");
		if ($OOPS)
		{
			fwrite ($logfile, "OOPS:\n" . $OOPS . "\n" );
			fwrite ($logfile, "Query1:\n" . $query1 . "\n" ) ;
			fwrite ($logfile, "Query1:\n" . $query2 . "\n" ) ;
		}
		fclose ($logfile);
?>
		<p class="centered"><a href="MembAdmin.php">Membership Admin Page</a></p>

	</div>
	
	</section>
</div> <!-- end content -->

<footer>

<?php include ("../includes/club-membership.incl.html") ; ?>

</footer>

</div> <!-- end master -->

</body>
</html>
