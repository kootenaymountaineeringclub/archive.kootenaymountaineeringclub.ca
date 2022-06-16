<?php
/********************************************************
GetExpressCheckoutDetails.php

This functionality is called after the buyer returns from
PayPal and has authorized the payment.

Finalizes the payment and displays the Payment Complete page

Called by  (indirectly via return URL) startPayment.php.

Calls APIError.php if things go wrong.

********************************************************/

require_once 'CallerService.php';
require_once 'util.php';
session_start();
debug_log('In getExpressCheckoutDetails.php 1');

$token = urlencode ( $_REQUEST ['token'] );
	$id = "-1";
	if (!isset($_REQUEST['memberID']))
	{
		debug_log("getExpressCheckoutDetails.php: memberID not set");
		die("getExpressCheckoutDetails.php: memberID not set");
	}
	else
	{
		debug_log("getExpressCheckoutDetails.php: memberID = " . $_REQUEST['memberID']);
		$id = $_REQUEST['memberID'];
	}

	/* Build a second API request to PayPal, using the token as the
			ID to get the details on the payment authorization
			*/
	$nvpstr = "&TOKEN=" . $token;
	
	/* Make the API call and store the results in an array.  If the
			call was a success, complete the payment.  If failed, show the error
	*/
	$resArray = hash_call ( "GetExpressCheckoutDetails", $nvpstr );
	$_SESSION ['reshash'] = $resArray;
	$ack = strtoupper ( $resArray ["ACK"] );
	if ($ack != "SUCCESS") {
		//Redirecting to APIError.php to display errors. //	$location = "APIError.php";
		header ( "Location: APIError.php?memberID="  . $id);
		exit();
	} 
	debug_log('In getExpressCheckoutDetails.php 2 ACK from GetExpressCheckoutDetails = SUCCESS');

/* Collect the necessary information to complete the
   authorization for the PayPal payment
   */

	$payerID = urlencode ($_REQUEST['PayerID']);
	debug_log("getExpressCheckoutDetails.php PayerID = " . $payerID);
	$paymentAmount = urlencode ($_REQUEST['paymentAmount']);
	debug_log("getExpressCheckoutDetails.php PaymentAmount = " . $paymentAmount);
	$serverName = urlencode ($_SERVER ['SERVER_NAME']);
	debug_log("getExpressCheckoutDetails.php ServerName = " . $serverName);
	if (!isset($_REQUEST['memberID']))
	{
		debug_log("getExpressCheckoutDetails.php: memberID not set");
	}
	else
	{
		debug_log("getExpressCheckoutDetails.php: memberID = " . $_REQUEST['memberID']);
		$id = $_REQUEST['memberID'];
	}

$nvpstr='&TOKEN='.$token.'&PAYERID='.$payerID.'&PAYMENTACTION=Sale&AMT='.$paymentAmount.'&CURRENCYCODE=CAD&IPADDRESS='.$serverName ;

 /* Make the call to PayPal to finalize payment
    If an error occured, show the resulting errors
    */
$resArray=hash_call("DoExpressCheckoutPayment",$nvpstr);
$_SESSION ['reshash'] = $resArray;

/* Display the API response back to the browser.
   If the response from PayPal was a success, display the response parameters'
   If the response was an error, display the errors received using APIError.php.
   */
$ack = strtoupper($resArray["ACK"]);

if($ack!="SUCCESS"){
	debug_log('getExpressCheckoutPayment.php ACK from DoExpressCheckoutPayment NOT SUCESS');
	header ( "Location: APIError.php?memberID="  . $id);
	exit();
}

/* Display the  API response back to the browser .
   If the response from PayPal was a success, display the response parameters
   Update the database to say things worked
   */
   db_connect();
   db_paypal_successful($id);

?>
<html>
<head>
    <title>PayPal Payment Complete</title>
    <link href="sdk.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript">
		function gotoflex() {
			window.opener.window.document.getElementById('KMC_Membership').paymentComplete(); 
			window.close();
		}
	</script>
</head>
<body>
	<br>
	<div id="header"><img src="http://www.kootenaymountaineering.bc.ca/images/kmclogo.jpg" border="0" class="logo" alt="Kootenay Mountaineering Club"></div>
	<br>
	<hr>
	<br>
	<center>
		<font size=2 color=black face=Verdana><b>Payment via PayPal Complete</b></font>
		<br><br>
		<b>Thank you for joining the Kootenay Mountaineering Club!</b><br><br>
    	<table width =400>
	        <tr>
                <td>Transaction ID:</td>
            	<td><b><?php echo $resArray['TRANSACTIONID'] ?></b></td>
        	</tr>
        	<tr>
            	<td >Amount:</td>
            	<td><b><?php echo "$" . $resArray['AMT'] ?></b></td>
        	</tr>
    	</table>
    	<a class="home" id="CallsLink" href="javascript:gotoflex()">Return to main KMC browser</a>
    </center>
</body>
</html>
