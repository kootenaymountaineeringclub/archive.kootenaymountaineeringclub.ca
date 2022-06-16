<?php

   require_once("paypal_functions.php");
   require("util.php");
  
   //Call to SetExpressCheckout using the shopping parameters collected from the shopping form on index.php and few from config.php 
   
   if ($_SESSION["PROCESS"] == "NEW1" || $_SESSION["PROCESS"] == "NEW2" ) {
	    $returnURL = "https://kootenaymountaineeringclub.ca/club-info/membership/php/lightboxreturn.php";
	} else {
	   $returnURL = "https://kootenaymountaineeringclub.ca/club-info/membership/php/renewreturn.php";
	}
   $cancelURL = "https://kootenaymountaineeringclub.ca/club-info/membership/php/cancel.php";
   
   $_SESSION["RETURN_URL"] = $returnURL;
   
   if(isset($_POST["PAYMENTREQUEST_0_ITEMAMT"]))
		$_POST["L_PAYMENTREQUEST_0_AMT0"] = $_POST["PAYMENTREQUEST_0_ITEMAMT"];
  
   if(!isset($_POST['Confirm']) && isset($_POST['checkout'])){

		if($_REQUEST["checkout"] || isset($_SESSION['checkout'])) {
			$_SESSION['checkout'] = $_POST['checkout'];
		}
	}

	$_SESSION['post_value'] = $_POST;
	
	//Assign the Return and Cancel to the Session object for ExpressCheckout Mark
//		$returnURL = RETURN_URL_MARK;
	$_SESSION['post_value']['RETURN_URL'] = $returnURL;
	$_SESSION['post_value']['CANCEL_URL'] = $cancelURL;
	$_SESSION['EXPRESS_MARK'] = 'ECMark';

?>

	<input type='checkbox' id='paypal_payment_option' checked="true">
    <script src="//www.paypalobjects.com/api/checkout.js" async></script>

    <script type="text/javascript">
	      window.paypalCheckoutReady = function () {
	          paypal.checkout.setup("<?php echo($merchantID); ?>", {
	              button: 'placeOrderBtn',
	              environment: "<?php echo($env); ?>",
	              condition: function () {
	                    return document.getElementById('paypal_payment_option').checked === true;
	            }
	          }
		}
		
    </script>
    
<?php

   $resArray = CallShortcutExpressCheckout ($_POST, $returnURL, $cancelURL);
   
	$logfile = fopen("logs/" . $_SESSION['MembID'] . ".txt" , 'w');
	fwrite ($logfile, "POST:\n" . print_r($_POST,TRUE) . "\n\n");
	fwrite ($logfile, "SESSION:\n" . print_r($_SESSION,TRUE) . "\n\n");
	fwrite ($logfile, "RESARRAY:\n" . print_r($resArray,TRUE) . "\n\n");
	fclose ($logfile);

   $ack = strtoupper($resArray["ACK"]);
   if($ack=="SUCCESS" || $ack=="SUCCESSWITHWARNING")  //if SetExpressCheckout API call is successful
   {
		RedirectToPayPal ( $resArray["TOKEN"] );
   } 
   else  
   {
	   	//Display a user friendly Error on the page using any of the following error information returned by PayPal
	   	$ErrorCode = urldecode($resArray["L_ERRORCODE0"]);
	   	$ErrorShortMsg = urldecode($resArray["L_SHORTMESSAGE0"]);
	   	$ErrorLongMsg = urldecode($resArray["L_LONGMESSAGE0"]);
	   	$ErrorSeverityCode = urldecode($resArray["L_SEVERITYCODE0"]);
	   	
	   	echo "SetExpressCheckout API call failed. ";
	   	echo "Detailed Error Message: " . $ErrorLongMsg;
	   	echo "Short Error Message: " . $ErrorShortMsg;
	   	echo "Error Code: " . $ErrorCode;
	   	echo "Error Severity Code: " . $ErrorSeverityCode;
   }
?>
