<?php
require_once 'CallerService.php';
require_once 'constants.php';
require_once 'util.php';

	session_start ();

	$serverName = $_SERVER ['SERVER_NAME'];
	$serverPort = $_SERVER ['SERVER_PORT'];
	$url = dirname ( 'http://' . $serverName . ':' . $serverPort . $_SERVER ['REQUEST_URI'] );
	
	$paymentAmount = $_REQUEST ['amount'];
	$desc = urlencode($_REQUEST['desc']);
	debug_log("in startPayment.php"); 
	// First put a row in datbase.
	// We will delete it if the payment does not complete
	// We will update it if the payment completes successfully (make Active & change PaymentStatus to 'PayPal Complete,)
	db_connect(); // Connect to KMC Database
	$id = db_insert_member1();
	if (isset($_REQUEST['lastName2']))
		db_insert_member2($id);
	
	/* The returnURL is the location where buyers return when a
			payment has been succesfully authorized.
			The cancelURL is the location buyers are sent to when they hit the
			cancel button during authorization of payment during the PayPal flow
			*/
	
	$returnURL = urlencode ( $url . '/getExpressCheckoutDetails.php?paymentAmount=' . $paymentAmount . '&memberID=' .$id);
	$cancelURL = urlencode ( $url . '/cancel.php?memberID=' .$id);
	
	/* Construct the parameter string that describes the PayPal payment
			the varialbes were set in the web form, and the resulting string
			is stored in $nvpstr
			*/
	
	$nvpstr = "&Amt=" . $paymentAmount . "&PAYMENTACTION=Sale" . "&ReturnUrl=" . $returnURL . "&CANCELURL=" . $cancelURL . "&CURRENCYCODE=CAD";
	
	$nvpstr = $nvpstr . "&EMAIL=" . $_REQUEST["email1"]. "&DESC=" . $desc . "&NOSHIPPING=1" . "&LOCALECODE=CA";
	$nvpstr = $nvpstr . "&HDRIMG=http://www.kootenaymountaineering.bc.ca/images/kmclogo.jpg";
	debug_log("in startPayment.php 2 " . $nvpstr);
	
	
	/* Make the call to PayPal to set the Express Checkout token
			If the API call succeded, then redirect the buyer to PayPal
			to begin to authorize payment.  If an error occured, show the
			resulting errors
	*/
			
	$resArray = hash_call ( "SetExpressCheckout", $nvpstr );
	$_SESSION ['reshash'] = $resArray;
	
	$ack = strtoupper ( $resArray ["ACK"] );
	
	if ($ack == "SUCCESS") {
	debug_log("in startPayment.php 3 ACK = SUCCESS");
		// Redirect to paypal.com here
		$token = urldecode ( $resArray ["TOKEN"] );
		$payPalURL = PAYPAL_URL . $token . "&useraction=commit";
		header ( "Location: " . $payPalURL);
	} else {
	debug_log("in startPayment.php 4 ACK != SUCCESS"); 
		//Redirecting to APIError.php to display errors. 
		header ( "Location: APIError.php?memberID=" . $id);
	}
?>
