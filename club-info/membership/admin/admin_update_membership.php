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
			
			if ($_POST['MembType'] == "Couple") $IsCouple = TRUE;
			
			$TransactionID1 = $_POST['trans_id1'];
			$MembershipID = $_POST['memb_numb'];
			$MembType = $_POST['MembType'];
			$MembYear = $_POST['MembYear'];
			$FirstName1 = $_POST['FirstName1'];
			$NickName1 = $_POST['NickName1'];
			$LastName1 = $_POST['LastName1'];
			$Email1 = $_POST['Email1'];
			$Phone1 = $_POST['Phone1'];
			$Age1 = $_POST['name1_age'];
			$Addr1 = $_POST['Addr1'];
			$Addr2 = $_POST['Addr2'];
			$City = $_POST['City'];
			$Province = $_POST['Province'];
			$PostalCode = $_POST['PostalCode'];
			
			if ($MembType == "Couple") {
				$TransactionID2 = $_POST['trans_id2'];
				$FirstName2 = $_POST['FirstName2'];
				$NickName2 = $_POST['NickName2'];
				$LastName2 = $_POST['LastName2'];
				$Email2 = $_POST['Email2'];
				$Phone2 = $_POST['Phone2'];
				$Age2 = $_POST['name2_age'];
			}
			
			$PrivateName = $_POST['private'];
			$KMCNewsletter = ($_POST['kmc'] == "" ? 0 : 1 );
			$FMCBCNewsletter = ($_POST['fmcbc'] == "" ? 0 : 1 );
			
			$Name1SQL = 'UPDATE Member set MembershipType = "' . $MembType . '", Year=' . $MembYear . ', AgeRange =' . $Age1 . ', FirstName = "' . $FirstName1 . '", NickName = "' . $NickName1 . '", LastName = "' . $LastName1 . '", DistinctName = "' . $LastName1 . ', ' . $FirstName1 . '", Email = "' . $Email1 . '", Phone = "' . $Phone1 . '", StreetAddress = "' . $Addr1 . '", StreetAddress2 = "' . $Addr2 . '", City = "' . $City . '", Province = "' . $Province . '", PostalCode = "' . $PostalCode . '", PrivateName = "' . $PrivateName . '", KmcNewsletter = "' . $KMCNewsletter . '", FmcbcNewsletter = "' . $FMCBCNewsletter . '" WHERE TransactionID = "' . $TransactionID1 . '"';
			
			if ($IsCouple) {
				$Name2SQL = 'UPDATE Member set MembershipType = "' . $MembType . '", Year=' . $MembYear . ', AgeRange = ' . $Age2 . ', FirstName = "' . $FirstName2 . '", NickName = "' . $NickName2 . '", LastName = "' . $LastName2 . '", DistinctName = "' . $LastName2 . ', ' . $FirstName2 . '", Email = "' . $Email2 . '", Phone = "' . $Phone2 . '", StreetAddress = "' . $Addr1 . '", StreetAddress2 = "' . $Addr2 . '", City = "' . $City . '", Province = "' . $Province . '", PostalCode = "' . $PostalCode . '", PrivateName = "' . $PrivateName . '", KmcNewsletter = "' . $KMCNewsletter . '", FmcbcNewsletter = "' . $FMCBCNewsletter . '" WHERE TransactionID = "' . $TransactionID2 . '"';
				
			}
			
		require_once 'admin-util.php';
	
		db_connect();
		
		$res = mysql_query($Name1SQL);
		if (!$res)
		{
			die("UPDATE  Member failed: SQL: " . $Name1SQL . " Error: " . mysql_error());
		}
		
		if ($IsCouple) {
			$res = mysql_query($Name2SQL);
			if (!$res)
			{
				die("UPDATE  Member failed: SQL: " . $Name2SQL . " Error: " . mysql_error());
			}	
		}
		
		echo "<p>Membership information updated.</p>\n" ;

		?>
	
	</section>
	
	</div>

	<footer>

	<?php include ("../../includes/club-membership.incl.html") ; ?>

	</footer>

</div> <!-- end master -->

</body>
</html>