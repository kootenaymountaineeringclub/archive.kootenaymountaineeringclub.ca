<?php
	include("util.php");
	db_connect();
	db_insert_testing("IPN_Post", "Start", "3");
	db_inc_ipn_received();
	$session = $_POST["custom"];
	$email = $_POST["payer_email"];
	$payment_status = $_POST["payment_status"];
	$txn_id = $_POST["txn_id"];
	$first_name = $_POST["first_name"];
	$last_name = $_POST["last_name"];
	db_insert_testing("CUSTOM", $session, "");
	db_insert_testing("PAYER_EMAIL", $email, "");
	db_insert_testing("PAYMENT_STATUS", $payment_status, "");
	db_insert_testing("NAME", $first_name, $last_name);
	//db_insert_testing("TXN_ID", $txn_id, "");

// read the post from PayPal system and add 'cmd'
$req = 'cmd=_notify-validate';

foreach ($_POST as $key => $value) {
$value = urlencode(stripslashes($value));
$req .= "&$key=$value";
}

$logfile = "logs/" . $session . "-IPN.txt";
$log = fopen($logfile,"w");
fwrite($log,$req . "\n");
fclose($log);

// post back to PayPal system to validate
$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
$fp = fsockopen ('www.paypal.com', 80, $errno, $errstr, 30);



if (!$fp) {
// HTTP ERROR
} else {
fputs ($fp, $header . $req);
while (!feof($fp)) {
$res = fgets ($fp, 1024);
if (strcmp ($res, "VERIFIED") == 0) {
// check the payment_status is Completed
// check that txn_id has not been previously processed
// check that receiver_email is your Primary PayPal email
// check that payment_amount/payment_currency are correct
// process payment
	if (strcmp ($payment_status, "Completed") == 0)
	{
		// First see if AutoReturn (payment_made.php) has completed the booking
		if (db_session_in_BookedHutDay($session))
		{
			db_update_BookedHutDay($session, $email, $first_name, $last_name);
		}
		else
		{
			$get_cart_res = db_get_cart_from_sessionid($session);
			
			if (mysql_num_rows($get_cart_res) > 0)
			{
				while ($cart_info = mysql_fetch_array($get_cart_res))
				{
					$hut_id = $cart_info["HutID"];
					$id = $cart_info["ID"];
					$num_persons = $cart_info["num_persons"];
					$date = $cart_info["BookedDate"];
					db_add_nights_booked($session, $hut_id, $date, $num_persons, $email, $first_name, $last_name, "IPN");
				}
				db_mark_cart_paid($session);
			}
		}
		db_clean_cart();
	}


// echo the response
echo "The response from IPN was: <p><b>" . $res . "</b></p>";

//loop through the $_POST array and log all vars to the database.
/*
	foreach($_POST as $key => $value)
	{
		db_insert_testing($key, $value, "X");
	}
*/

}
else if (strcmp ($res, "INVALID") == 0) {
// log for manual investigation

// echo the response
echo "The response from IPN was: <b>" .$res ."</b>";

  }

}
fclose ($fp);
}
?>


