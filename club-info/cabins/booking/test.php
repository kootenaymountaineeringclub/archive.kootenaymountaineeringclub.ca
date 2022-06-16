<html>
	<body>
<?php
	session_unset();
	session_start();

	include "date_util.php" ;
	include "util.php" ;

function connect() {
	$db = mysqli_connect("localhost:3306","kmcwe_kmc","zlika9p","kmcweb_0_kmc");
	
	if (!$db) {
		echo "Oops" . PHP_EOL;
		echo "number: " . mysqli_connect_errno() . PHP_EOL;
		echo "error: " . mysqli_connect_error() . PHP_EOL;
		exit;
	}
	return $db;
}


	global $SAVEDSESSION;
	$link = connect();

	
	$_SESSION['id'] = $SAVEDSESSION['id'] = session_id();
	$_SESSION['count'] = $SAVEDSESSION['count'] = 0;
	$_SESSION['start'] = $SAVEDSESSION['start'] = 1;
	$_SESSION['link'] = $SAVEDSESSION['link'] = $link;
	
	save_session(session_id());
	
//	db__clear_checkout(); // Clear the At Checkout Page indicator in Cart
//	db_clean_cart(); // Clean out timed out cart items

		echo "<pre>" . print_r($_SESSION,true) . "\n";
		echo print_r($SAVEDSESSION,true) . "</pre>\n";
?>
	</body>
</html?