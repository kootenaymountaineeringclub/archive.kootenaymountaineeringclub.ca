<html>
	<body>
<?php
	session_unset();
	session_start();

	include "date_util.php" ;
	include "booking_util.php" ;


	global $SAVEDSESSION;
	
	$_SESSION['id'] = $SAVEDSESSION['id'] = session_id();
	$_SESSION['count'] = $SAVEDSESSION['count'] = 0;
	$_SESSION['start'] = $SAVEDSESSION['start'] = 1;
	$_SESSION['link'] = $SAVEDSESSION['link'] = $link;
	
	save_session(session_id());
	
//	db__clear_checkout(); // Clear the At Checkout Page indicator in Cart
//	db_clean_cart(); // Clean out timed out cart items

	$return = add_to_cart(4, '2020-03-26', 4);
	if ($return) {
		echo "Youpie!";
	} else {
		echo "Nope";
	}
?>

</body>
</html?