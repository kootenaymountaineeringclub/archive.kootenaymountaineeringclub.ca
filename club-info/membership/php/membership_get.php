<!DOCTYPE html>
<html>
<head>
	<?php
		require_once 'util.php';
		include (PHP_ROOT . "/includes/head-first.incl.html") ;
	?>

	<title>Membership Renew</title>
	
	<?php include (PHP_ROOT . "/includes/head-2nd.incl.html") ; ?>
	
	<?php include (PHP_ROOT . "/js/header-pictures.js") ; ?>
	
	<link rel="stylesheet" type="text/css" href="/css/membership-styles.css">

	<script src="form-util.php"></script>

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
			<h2>Kootenay Mountaineering Club<br>Membership Renewal</h2>
			
<?php
	require_once 'util.php';
	
	$renewKey = rand(1000001,9999999);
	
	$oops_address = "tim@timclinton.ca";
	
	$email_given = $_POST['membemail'];
	
	
	db_connect();

	$sql = 'select MembershipID from Member where Email = "' . $email_given . '"';
	$res = mysql_query($sql);

	if (!$res)
	{
		die("db_FindMembers: SELECT  Member failed: SQL: " . $sql . " Error: " . mysql_error());
	}
		
	if ( mysql_num_rows($res) == 0 ) {
		echo "<p>No membership record was found for " . $email_given . "</p>\n";
		
	} else {
		$membarray = mysql_fetch_assoc($res);
		$membID = $membarray["MembershipID"];
		$sql = 'INSERT into memberRenewRequest (renewID, memberID) values ("' . $renewKey . '", ' . $membID . ')';
		$result = mysql_query($sql) ;
		if ( !$result ) $OOPS = ("Oops.  Error: " . mysql_error()) . "/n/n";

	  if ($OOPS)
		{
			echo "<p>Sadly, there has been a database error.</p>";
			echo "<p>The web site manager has been informed, and will contact you.</p> ";
			echo "<p>Apologies for the inconvenience and bother.</p>";
			
			$memberdirsend = "Renewal for " . $email_given . "\n\n";
			$memberdirsend .= "Membership ID: " . $membID . "\n\n";
			$memberdirsend .= $OOPS;
				
			mail($oops_address,'KMC Membership Goof',$memberdirsend);
	
		} else {
			
			$renew_mail = 'KMC Membership renewal: The link to your membership renewal information is https://kootenaymountaineeringclub.ca/club-info/membership/php/renew_membership.php?renewID=' . $renewKey . ' .';			
			mail($email_given,'KMC Renewal',$renew_mail);
			
			echo "<p>An email with a link to the membership renewal form has been sent to $email_given.";
	
		}
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
