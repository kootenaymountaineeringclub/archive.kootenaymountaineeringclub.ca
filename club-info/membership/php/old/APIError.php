<?php
/*************************************************
APIError.php

Displays error parameters.

Called by getExpressCheckoutDetails.php and startPayment.php.

*************************************************/

	session_start();
	require_once 'util.php';
	debug_log('In APIError.php');
	// We will delete the memberships as the payment did not complete
	db_connect(); // Connect to KMC Database
	if (!isset($_REQUEST['memberID']))
	{
		debug_log("APIError.php: memberID not set");
		die("APIError.php: memberID not set");
	}
	else
	{
		debug_log("cancel.php: memberID = " . $_REQUEST['memberID']);
		$id = $_REQUEST['memberID'];
		db_delete_members($id);
	}
	$resArray=$_SESSION['reshash']; 
?>

<html>
<head>
<title>PayPal Error Response</title>
<link href="sdk.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript">
	function doError() {
		window.opener.window.document.getElementById('KMC_Membership').doError();
		window.close();
	}
</script>
</head>

<body alink=#0000FF vlink=#0000FF>
	<br>
	<div id="header"><img src="http://www.kootenaymountaineering.bc.ca/images/kmclogo.jpg" border="0" class="logo" alt="Kootenay Mountaineering Club"></div>
	<br>
	<hr>
	<br>
<center>

<table width="700">
<tr>
		<td colspan="2" class="header">The PayPal API has returned an error!</td>
	</tr>

<?php  //it will print if any URL errors 
	if(isset($_SESSION['curl_error_no'])) { 
			$errorCode= $_SESSION['curl_error_no'] ;
			$errorMessage=$_SESSION['curl_error_msg'] ;	
			debug_log('APIError.php curl_error_no set' . $errorCode . $errorMessage);
			session_unset();	
?>

   
<tr>
		<td>Error Number:</td>
		<td><?= $errorCode ?></td>
	</tr>
	<tr>
		<td>Error Message:</td>
		<td><?= $errorMessage ?></td>
	</tr>
	
	</center>
	</table>
<?php } else {

/* If there is no URL Errors, Construct the HTML page with 
   Response Error parameters.   
   */

		debug_log('APIError.php ACK ' . $resArray['ACK']);
?>

		<td>Ack:</td>
		<td><?= $resArray['ACK'] ?></td>
	</tr>
	<tr>
		<td>Correlation ID:</td>
		<td><?= $resArray['CORRELATIONID'] ?></td>
	</tr>
	<tr>
		<td>Version:</td>
		<td><?= $resArray['VERSION']?></td>
	</tr>
<?php
	$count=0;
	while (isset($resArray["L_SHORTMESSAGE".$count])) {		
		  $errorCode    = $resArray["L_ERRORCODE".$count];
		  $shortMessage = $resArray["L_SHORTMESSAGE".$count];
		  $longMessage  = $resArray["L_LONGMESSAGE".$count]; 
		  $count=$count+1; 
?>
	<tr>
		<td>Error Number:</td>
		<td><?= $errorCode ?></td>
	</tr>
	<tr>
		<td>Short Message:</td>
		<td><?= $shortMessage ?></td>
	</tr>
	<tr>
		<td>Long Message:</td>
		<td><?= $longMessage ?></td>
	</tr>
	
<?php }//end while

}// end else
?>
</center>
	</table>
<br>
<a class="home"  id="CallsLink" href="javascript:doError()"><font color=blue><B>Return to main KMC browser<B><font></a>
</body>
</html>

