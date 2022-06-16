<html>
<body>
	<p>Howdy</p>
<?php
	require "util.php";
	
	$fiscalYear = FiscalYearSelect ( );

	global $SAVEDSESSION;
	$id = $_REQUEST['id'];
	get_session ($id);
	$SAVEDSESSION;
	
	echo "<pre>SAVED: \n" . print_r($SAVEDSESSION,TRUE) . "</pre>\n";
	
	$link = mysqli_connect("127.0.0.1", "kmcwe_kmc", "zlika9p", "kmcweb_0_kmc");
	
	if (!$link) {
	    echo "Error: Unable to connect to MySQL." . PHP_EOL;
	    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
	    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
	    exit();
	}
	
	$sql = "SELECT HutID, BookedDate, num_persons FROM Cart ORDER by BookedDate";
	
	if ($result = mysqli_query($link,$sql)) {
		
		while ( $row = mysqli_fetch_array($result)) {
			echo "<p>" . $row["HutID"] . ": " . $row["BookedDate"] . " - " .  $row["num_persons"] . "</p>\n";
			}
	} else {
		echo "<p>No results: " . mysqli_error($link) . "</p>\n";
	}
	
	/* free result set */
	mysqli_stmt_close($stmt);
	
	/* close connection */
	mysqli_close($link);
	?>
</body>
</html>
