<?php 
	/*
	* Call to GetExpressCheckoutDetails and DoExpressCheckoutPayment APIs
	*/

	require_once ("paypal_functions.php");
	require ("util.php");
	$report_address = "membership@kootenaymountaineeringclub.ca,tim@timclinton.ca";
	$oops_address = "tim@timclinton.ca";
?>

<!DOCTYPE html>
<html>
<head>

	<?php 
		include (PHP_ROOT . "/includes/head-first.incl.html") ; ?>

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

<?php

	/*
	* The paymentAmount is the total value of the shopping cart(in real apps), here it was set 
    * in paypalfunctions.php in a session variable 
	*/
	
	$finalPaymentAmount =  $_SESSION["MembCost"];

	// Check to see if the Request object contains a variable named 'token'	or Session object contains a variable named TOKEN 
	$token = "";
	
	if (isset($_REQUEST['token']))
	{
		$token = $_REQUEST['token'];
	} else if(isset($_SESSION['TOKEN']))
	{
		$token = $_SESSION['TOKEN'];
	}
	
	// If the Request object contains the variable 'token' then it means that the user is coming from PayPal site.	
	if ( $token != "" )
	{
		/*
		* Calls the GetExpressCheckoutDetails API call
		*/
		$resArrayGetExpressCheckout = GetShippingDetails( $token );
		$ackGetExpressCheckout = strtoupper($resArrayGetExpressCheckout["ACK"]);
		
		if( $ackGetExpressCheckout == "SUCCESS" || $ackGetExpressCheckout == "SUCESSWITHWARNING") 
		{
			/*
			* The information that is returned by the GetExpressCheckoutDetails call should be integrated by the partner into his Order Review 
			* page		
			*/
			$email 				= $resArrayGetExpressCheckout["EMAIL"]; // ' Email address of payer.
			$payerId 			= $resArrayGetExpressCheckout["PAYERID"]; // ' Unique PayPal customer account identification number.
			$payerStatus		= $resArrayGetExpressCheckout["PAYERSTATUS"]; // ' Status of payer. Character length and limitations: 10 single-byte alphabetic characters.
			$firstName			= $resArrayGetExpressCheckout["FIRSTNAME"]; // ' Payer's first name.
			$lastName			= $resArrayGetExpressCheckout["LASTNAME"]; // ' Payer's last name.
			$cntryCode			= $resArrayGetExpressCheckout["COUNTRYCODE"]; // ' Payer's country of residence in the form of ISO standard 3166 two-character country codes.
			$shipToName			= $resArrayGetExpressCheckout["PAYMENTREQUEST_0_SHIPTONAME"]; // ' Person's name 
			$totalAmt   		= $resArrayGetExpressCheckout["PAYMENTREQUEST_0_AMT"]; // ' Total Amount to be paid by buyer
			$currencyCode       = $resArrayGetExpressCheckout["CURRENCYCODE"]; // 'Currency being used 
			

//
//			if($_SESSION["Payment_Amount"] != $totalAmt || $_SESSION["currencyCodeType"] != $currencyCode) {
//				exit("Parameters in session do not match those in PayPal API calls");
//			} 
//			else  
//			{
//				//Display a user friendly Error on the page using any of the following error information returned by PayPal
//				$ErrorCode = urldecode($resArrayGetExpressCheckout["L_ERRORCODE0"]);
//				$ErrorShortMsg = urldecode($resArrayGetExpressCheckout["L_SHORTMESSAGE0"]);
//				$ErrorLongMsg = urldecode($resArrayGetExpressCheckout["L_LONGMESSAGE0"]);
//				$ErrorSeverityCode = urldecode($resArrayGetExpressCheckout["L_SEVERITYCODE0"]);
//	
//				echo "GetExpressCheckoutDetails API call failed. ";
//				echo "Detailed Error Message: " . $ErrorLongMsg;
//				echo "Short Error Message: " . $ErrorShortMsg;
//				echo "Error Code: " . $ErrorCode;
//				echo "Error Severity Code: " . $ErrorSeverityCode;
//			}

		}
	}

	/*
	* Calls the DoExpressCheckoutPayment API call
	*/
	$resArrayDoExpressCheckout = ConfirmPayment ( $finalPaymentAmount );
	$ackDoExpressCheckout = strtoupper($resArrayDoExpressCheckout["ACK"]);
	
?>

	<section>
	<div class="formPart">

<?php	
	
	if( $ackDoExpressCheckout == "SUCCESS" || $ackDoExpressCheckout == "SUCCESSWITHWARNING" )
	{
		$transactionId		= $resArrayDoExpressCheckout["PAYMENTINFO_0_TRANSACTIONID"]; // ' Unique transaction ID of the payment. Note:  If the PaymentAction of the request was Authorization or Order, this value is your AuthorizationID for use with the Authorization & Capture APIs. 
		$transactionType 	= $resArrayDoExpressCheckout["PAYMENTINFO_0_TRANSACTIONTYPE"]; //' The type of transaction Possible values: l  cart l  express-checkout 
		$paymentType		= $resArrayDoExpressCheckout["PAYMENTINFO_0_PAYMENTTYPE"];  //' Indicates whether the payment is instant or delayed. Possible values: l  none l  echeck l  instant 
		$orderTime 			= $resArrayDoExpressCheckout["PAYMENTINFO_0_ORDERTIME"];  //' Time/date stamp of payment
		$amt				= $resArrayDoExpressCheckout["PAYMENTINFO_0_AMT"];  //' The final amount charged, including any shipping and taxes from your Merchant Profile.
		$currencyCode		= $resArrayDoExpressCheckout["PAYMENTINFO_0_CURRENCYCODE"];  //' A three-character currency code for one of the currencies listed in PayPay-Supported Transactional Currencies. Default: USD. 
		/*
		* Status of the payment: 
		* Completed: The payment has been completed, and the funds have been added successfully to your account balance.
		* Pending: The payment is pending. See the PendingReason element for more information. 
		*/
		
		$paymentStatus	= $resArrayDoExpressCheckout["PAYMENTINFO_0_PAYMENTSTATUS"]; 

		/*
		* The reason the payment is pending 
		*/
		$pendingReason	= $resArrayDoExpressCheckout["PAYMENTINFO_0_PENDINGREASON"];  

		/*
		* The reason for a reversal if TransactionType is reversal 
		*/
		$reasonCode		= $resArrayDoExpressCheckout["PAYMENTINFO_0_REASONCODE"];
		?>
		
		<?php $description = $_SESSION['paydescription']; ?>

    <?php
				//  Update member to database here.
		$YEAR = WhichMembershipYear();
		$DistinctName1 = $_SESSION["LastName1"] . ", " . $_SESSION["FirstName1"];
		$DistinctName2 = $_SESSION["LastName2"] . ", " . $_SESSION["FirstName2"];
		
		db_connect();
		
		if ($_SESSION['PROCESS'] == "RENEW1" || $_SESSION['PROCESS'] == "RENEW2") {
			$sql1 = "UPDATE Member set Year=" . $YEAR . 
				", MembershipType='" . $_SESSION['MembType'] .
				"', AgeRange=" . $_SESSION['name1_age'] .
				", FirstName='" . $_SESSION['FirstName1'] .
				"', NickName='" . $_SESSION['NickName1'] .
				"', LastName='" . $_SESSION['LastName1'] .
				"', DistinctName='" . $DistinctName1 .
				"', StreetAddress='" . $_SESSION['Addr1'] .
				"', StreetAddress2='" . $_SESSION['Addr2'] .
				"', City='" . $_SESSION['City'] .
				"', Province='" . $_SESSION['Prov'] .
				"', PostalCode='" . $_SESSION['Postal'] .
				"', Email='" . $_SESSION['Email1'] .
				"', Phone='" . $_SESSION['Phone1'] .
				"', KmcNewsletter='" . $_SESSION['kmc'] .
				"', FmcbcNewsletter='" . $_SESSION['fmcbc'] .
				"', PrivateName=" . $_SESSION['private'] .
				", Amount=" . $totalAmt .
				" WHERE TransactionID=" . $_SESSION['trans_id'] ;
					
		}
		
		if ($_SESSION['PROCESS'] == "RENEW2") {
			if ($_SESSION['trans_id2'] == "") {
		
				$InsertFields2 = "insert into `Member` (`MembershipID`,`Year`,`MembershipType,`AgeRange`,`FirstName`,`NickName`,`LastName`,`DistinctName`,`StreetAddress`,`StreetAddress2`,`City`,`Province`,`PostalCode`, `Email`,`Phone`,`PrivateName`) values ";
		  
			  	$InsertValues2 = "('" . $_SESSION["MembID"] . "', " . $YEAR . ", '" . $_SESSION["MembType"] . "', '" . 
					$_SESSION["name2_age"] . ", '" .
					$_SESSION["FirstName2"] . "', '" . $_SESSION["Nickname2"] . "', " . 
					$_SESSION["LastName2"] . "', '" . $DistinctName2 . "', '" .
					$_SESSION["Addr1"] . "', '" . $_SESSION["Addr2"] . "', '" . 
					$_SESSION["City"] . "', '" . $_SESSION["Prov"] . "', '" . $_SESSION["Postal"] . "', '" . 
					(($_SESSION["SharedEmail"] == "1") ? $_SESSION["Email1"] : $_SESSION["Email2"] ) . "', '" . 
					$_SESSION["Phone2"] . ", " . 
					$_SESSION["private"] . ")";
		
					$sql2 = $InsertFields2 . $InsertValues2 ;
			} else {
					$sql2 = "UPDATE Member set Year=" . $YEAR . 
						", MembershipType='" . $_SESSION['MembType'] .
						"', AgeRange=" . $_SESSION['name2_age'] .
						", FirstName='" . $_SESSION['FirstName2'] .
						"', NickName='" . $_SESSION['NickName2'] .
						"', LastName='" . $_SESSION['Lastname2'] .
						"', DistinctName='" . $DistinctName2 .
						"', StreetAddress='" . $_SESSION['Addr1'] .
						"', StreetAddress2='" . $_SESSION['Addr2'] .
						"', City='" . $_SESSION['City'] .
						"', Province='" . $_SESSION['Prov'] .
						"', PostalCode='" . $_SESSION['Postal'] .
						"', Email='" . $_SESSION['Email2'] .
						"', Phone='" . $_SESSION['Phone2'] .
						"', KmcNewsletter='" . $_SESSION['kmc'] .
						"', FmcbcNewsletter='" . $_SESSION['fmcbc'] .
						"', PrivateName=" . $_SESSION['private'] .
						", Amount=" . 0 .
						" WHERE TransactionID=" . $_SESSION['trans_id2'] ;
			}
			
		}
		
	  if ( $_SESSION["MembType"] == "Individual" ||  $_SESSION["MembType"] == "Couple") 
	  														
		$result = mysql_query($sql1) ;
		if ( !$result ) $OOPS = ("Oops.  Error: " . mysql_error()) . "\n\n" . $sql1 . "\n\n";
		
		if ( $_SESSION["MembType"] == "Couple") $result = mysql_query($sql2);
		if ( !$result ) $OOPS .= ("Oops.  Error: " . mysql_error()) . "\n\n" . $sql2 . "\n\n";
	  
	  if ($OOPS)
	  {
		  echo "<p>Sadly, there has been a database error.</p>";
		  echo "<p>The web site manager has been informed, and will contact you. If we got to this point, it means that PayPal has received your money, and your membership will be honoured.</p> ";
		 echo "<p>Apologies for the inconvenience and bother.</p>";

  		$memberdirsend = "Renew Membership: " . $description . " for " . $_SESSION["membNames"] . "\n\n";
  		$memberdirsend .= $OOPS;
  		
		mail($oops_address,'KMC Membership Goof',$memberdirsend);

	  } else
	  {	  	// Delete renewal request record
	  		$sql = "DELETE from memberRenewRequest WHERE memberID =" . $_SESSION["memb_numb"];
	  		$result = mysql_query($sql);
  ?>
 			<!-- Display the Transaction Details-->
  			<div class="info announcement">
  			<h3> <?php echo($_SESSION["membNames"]); ?>, welcome to the club.</h3>
  			<p>Your <?php echo $_SESSION["MembType"] ?> Membership with the KMC is complete. Your membership covers the year <?php echo $YEAR ?>.</p>
				
				<p>Documents accessible only to Club Members are at: https://kootenaymountaineeringclub.ca/club-info/documents/member-files/ with the login ID of KMCmember and password of giml1g0at (both case sensitive).</p>
				
  			</div>

<?php	  $address = "";
	    	if ( $_SESSION['Email1'] != "" ) $address = $_SESSION['Email1'];

				if ( $_SESSION['Email2'] != "" ) {
					if ($address != "") $address .= ", ";
					$address .= $_SESSION['Email2'];
				}
				
				$_SESSION['MembEmailTo'] = $address;
					
				$tosend = "Kootenay Mountaineering Club Membership complete.\n\n" ;
				$tosend .= $description . " for " . $_SESSION["membNames"] . "\n\n";
				$tosend .= "Thank you. PayPal should also send you a receipt.\nFor questions regarding your membership, etc. please send email to membership@kootenaymountaineeringclub.ca\n\n";

				$msg = file_get_contents('../safe/technical-tips-short.txt');
		
				mail( $address,'KMC Membership',$tosend . $msg );
				
			}

     		session_unset();   // free all session variables
				session_destroy(); //destroy session
			?>
		
		<?php
	}
	else  
	{
		//Display a user friendly Error on the page using any of the following error information returned by PayPal

		$ErrorCode = urldecode($resArrayDoExpressCheckout["L_ERRORCODE0"]);
		$ErrorShortMsg = urldecode($resArrayDoExpressCheckout["L_SHORTMESSAGE0"]);
		$ErrorLongMsg = urldecode($resArrayDoExpressCheckout["L_LONGMESSAGE0"]);
		$ErrorSeverityCode = urldecode($resArrayDoExpressCheckout["L_SEVERITYCODE0"]);

		if($ErrorCode == 10486)  //Transaction could not be completed error because of Funding failure. Should redirect user to PayPal to manage their funds.
		{
			?>
    			<?php  //echo($resArrayDoExpressCheckout["PAYMENTINFO_0_PAYMENTSTATUS"]);
						RedirectToPayPal ( $resArray["TOKEN"] );
    			?>
			<?php
		}
		else
		{
			echo "DoExpressCheckout API call failed. ";
			echo "Detailed Error Message: " . $ErrorLongMsg . "\n";
			echo "Short Error Message: " . $ErrorShortMsg . "\n";
			echo "Error Code: " . $ErrorCode;
			echo "Error Severity Code: " . $ErrorSeverityCode;
		}
	}

	$logfile = fopen("logs/" . $_SESSION['MembID'] . ".txt" , 'w');
	fwrite ($logfile, "Get Express Checkout:\n" . print_r($resArrayGetExpressCheckout,TRUE) . "\n\n");
	fwrite ($logfile, "Do Express Checkout:\n" . print_r($resArrayDoExpressCheckout,TRUE) . "\n\n");
	fwrite ($logfile, "Session:\n" . print_r($_SESSION,TRUE) . "\n");
	if ($OOPS) fwrite ($logfile, "OOPS:\n" . $OOPS . "\n");
	fclose ($logfile);
		
?>

	</div>
	
	</section>
</div> <!-- end content -->

<footer>

<?php include ("../includes/club-membership.incl.html") ; ?>

</footer>

</div> <!-- end master -->

</body>
</html>
