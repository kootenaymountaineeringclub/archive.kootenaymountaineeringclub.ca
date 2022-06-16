<?php 
	/*
	* Call to GetExpressCheckoutDetails and DoExpressCheckoutPayment APIs
	*/

	require_once ("paypal_functions.php");
	require ("util.php");

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
			/*
			* Add check here to verify if the payment amount stored in session is the same as the one returned from GetExpressCheckoutDetails API call
			* Checks whether the session has been compromised
			*/
			if($_SESSION["Payment_Amount"] != $totalAmt || $_SESSION["currencyCodeType"] != $currencyCode)
			exit("Parameters in session do not match those in PayPal API calls");
			} 
			else  
			{
			//Display a user friendly Error on the page using any of the following error information returned by PayPal
			$ErrorCode = urldecode($resArrayGetExpressCheckout["L_ERRORCODE0"]);
			$ErrorShortMsg = urldecode($resArrayGetExpressCheckout["L_SHORTMESSAGE0"]);
			$ErrorLongMsg = urldecode($resArrayGetExpressCheckout["L_LONGMESSAGE0"]);
			$ErrorSeverityCode = urldecode($resArrayGetExpressCheckout["L_SEVERITYCODE0"]);

			echo "GetExpressCheckoutDetails API call failed. ";
			echo "Detailed Error Message: " . $ErrorLongMsg;
			echo "Short Error Message: " . $ErrorShortMsg;
			echo "Error Code: " . $ErrorCode;
			echo "Error Severity Code: " . $ErrorSeverityCode;
		}
	}
	/* Review block start */
	
	if(!USERACTION_FLAG && !isset($_SESSION['EXPRESS_MARK'])){
	if(isset($_POST['shipping_method']))
		$new_shipping = $_POST['shipping_method']; //need to change this value, just for testing
		if($shippingAmt > 0){
			$finalPaymentAmount = ($totalAmt + $new_shipping) - $_SESSION['shippingAmt'];
			$_SESSION['shippingAmt'] = $new_shipping;
		}
	}
	
	/* Review block end */

	/*
	* Calls the DoExpressCheckoutPayment API call
	*/
	$resArrayDoExpressCheckout = ConfirmPayment ( $finalPaymentAmount );
	$ackDoExpressCheckout = strtoupper($resArrayDoExpressCheckout["ACK"]);
	
?>

<!DOCTYPE html>
<html>
<head>
	<?php 
		include("util.php");
		include (PHP_ROOT . "/includes/head-first.incl.html") ;
		$report_address = "tim@timclinton.ca";
	?>

	<title>KMC: Kootenay Mountaineering Membership</title>
	
	<?php include (PHP_ROOT . "/includes/head-2nd.incl.html") ; ?>
	<?php include ("../includes/MembershipBackgroundImage.incl.html") ; ?>
	
	<link rel="stylesheet" type="text/css" href="/css/membership-styles.css">


<body>

<div id="master">

<header>

	<?php include ("../../includes/page-header-club.incl.html") ;
				include (PHP_ROOT . "/includes/header-contents.incl.html") ; ?>
	
</header>

<div id="content">

<?php include ("../includes/club-membership.incl.html") ; ?>
	
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
		
		<?php $description = $_SESSION['description']); ?>
    			<!-- Display the Transaction Details-->
    			<div class="info announcement">
    			<h3> <?php echo($firstName . " " . $lastName); ?>, welcome to the club.</h3>
    			<p>Your <?php echo $description ?> is complete.</p>
    			</div>
    
    <?php
	    	$address = "";
	    	if ( $_SESSION['Email1'] != "" ) $address = $_SESSION['Email1'];

				if ( $_SESSION['Email2'] != "" ) {
					if ($address != "") $address .= ", ";
					$address .= $_SESSION['Email2'];
				}
				$_SESSION['dbEmailTo'] = $address;
					
				$tosend = "Kootenay Mountaineering Club Membership\n\n" .
				$tosend .= "Thank you. PayPal should also send you a receipt.\nFor questions, etc. please send email to membership@kootenaymountaineeringclub.ca";
				mail($address,'Bonninton Cabin Booking',$tosend);
				
	  		$membersend = "Membership: " . $description . "\n\n";
	  		$membersend .= "Membership ID: " . $_SESSION['MembID'] . "\n\n";
	  		
				mail($report_address,'KMC Membership',$membersend);
					
				// db_connect(); 
    		// makeBookedHuts ($firstName,$lastName,$address,$_SESSION['session']);
    		
    		$logfile = fopen("logs/" . $_SESSION['MembID'] . ".txt" , 'w');
    		fwrite ($logfile, "Get Express Checkout:\n" . print_r($resArrayGetExpressCheckout,TRUE) . "\n\n");
    		fwrite ($logfile, "Do Express Checkout:\n" . print_r($resArrayDoExpressCheckout,TRUE) . "\n\n");
				fwrite ($logfile, "Session:\n" . print_r($_SESSION,TRUE) . "\n");
    		fclose ($logfile);
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
			<!--<div class="hero-unit">
    			 Display the Transaction Details
    			<h4> There is a Funding Failure in your account. You can modify your funding sources to fix it and make purchase later. </h4>
    			Payment Status:-->
    			<?php  //echo($resArrayDoExpressCheckout["PAYMENTINFO_0_PAYMENTSTATUS"]);
						RedirectToPayPal ( $resArray["TOKEN"] );
    			?>
    			<!--<h3> Click <a href='https://www.sandbox.paypal.com/'>here </a> to go to PayPal site.</h3> <!--Change to live PayPal site for production-->
    		<!--</div>-->
			<?php
		}
		else
		{
			echo "DoExpressCheckout API call failed. ";
			echo "Detailed Error Message: " . $ErrorLongMsg;
			echo "Short Error Message: " . $ErrorShortMsg;
			echo "Error Code: " . $ErrorCode;
			echo "Error Severity Code: " . $ErrorSeverityCode;
		}
	}		
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
