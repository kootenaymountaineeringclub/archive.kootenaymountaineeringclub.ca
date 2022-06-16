<?php

define('PHP_ROOT','/var/www/vhosts/kootenaymountaineeringclub.ca/httpdocs');
define('CLUB_ROOT', '/var/www/vhosts/kootenaymountaineeringclub.ca/httpdocs/club-info');
define('GPS_ROOT', '/var/www/vhosts/kootenaymountaineeringclub.ca/httpdocs/mountain-info/maps');
define('UPDIR','files/');

$gradeText = array(
		'A1' => 'Simple',
		'A2' => 'Challenging',
		'A3' => 'Complex',
		'A' => 'Easy',
		'B' => 'Moderate',
		'C' => 'Strenuous',
		'D' => 'Very Strenuous',
		'E' => 'Extended',
		'S1' => 'Track',
		'S2' => 'Off-track',
		'S3' => 'Back-country',
		'S4' => 'Advanced',
		'H1' => 'Hike',
		'H2' => 'Scramble',
		'H3' => 'Perhaps some exposure',
		'H4' => 'Climb',
		'H5' =>	'Multi-pitch',
		'B1' => 'All bikes',
		'B2' => 'Hybrid or mountain bike',
		'B3' => 'Mountain biking skills required');

function connect() {
	$db = mysqli_connect("localhost","kmcwe_gps","n2}VHz=Ce679nG*a","kmcweb_0_GPS");
	
	if (!$db) {
		echo "Oops" . PHP_EOL;
		echo "number: " . mysqli_connect_errno() . PHP_EOL;
		echo "error: " . mysqli_connect_error() . PHP_EOL;
		exit;
	}
	return $db;
}

function get_gps_key() {
	$db = connect();
	$sql = "SELECT FLOOR(10000001 + (RAND() * 89999999))";
	$query = mysqli_prepare($db,$sql);
	mysqli_stmt_bind_result($query, $gps_key);
	mysqli_stmt_execute($query);	
	mysqli_stmt_fetch($query);
	mysqli_stmt_close($query);
	mysqli_close($db);
	return($gps_key);
}

function count_routes() {
	$db = connect();
	
	$sql = "select count(RouteKey) from gps";
	$query = mysqli_prepare($db,$sql);
	mysqli_stmt_bind_result($query, $routecount);
	mysqli_stmt_execute($query);
	mysqli_stmt_fetch($query);
	mysqli_stmt_close($query);
	mysqli_close($db);
	
//	echo "<pre>Route count: " . $routecount . "</pre>\n";
	
	return($routecount);	
}

function count_files() {
	$db = connect();
	
	$sql = "select count(FileKey) from files";
	$query = mysqli_prepare($db,$sql);
	mysqli_stmt_bind_result($query, $filecount);
	mysqli_stmt_execute($query);
	mysqli_stmt_fetch($query);
	mysqli_stmt_close($query);
	mysqli_close($db);
	
//	echo "<pre>File count: " . $filecount . "</pre>\n";
	
	return($filecount);	
}

function list_routes() {
	$db = connect();
	
	$sql = "select RouteKey,RouteName from gps order by RouteName";
	$query = mysqli_prepare($db,$sql);
	mysqli_stmt_bind_result($query, $key, $name);
	mysqli_stmt_execute($query);
	echo "<table>\n";
	while (mysqli_stmt_fetch($query)) {
		echo '<tr><td><a href="display-route.php?id=' . $key . '">' . $name . "</a></td></tr>\n";
	}
	echo "</table>\n";
	mysqli_stmt_close($query);
	mysqli_close($db);
}

function get_counts() {
	$db = connect();
	
	$sql = "select count(RouteKey) from gps";
	$query = mysqli_prepare($db,$sql);
	mysqli_stmt_bind_result($query, $routecount);
	mysqli_stmt_execute($query);
	
	mysqli_stmt_fetch($query);
	
	$sql = "select count(FileKey) from files";
	$query = mysqli_prepare($db,$sql);
	mysqli_stmt_bind_result($query, $filecount);
	mysqli_stmt_execute($query);
	
	mysqli_stmt_fetch($query);
		
	mysqli_stmt_close($query);
	mysqli_close($db);
	return (array($routecount,$filecount));	
}

function record($record) {
	
//	echo "<pre>" . $record . "</pre>";
	
	$db = connect();
	
	$query = mysqli_prepare($db,$record);
	
	$result = FALSE;
	
	if ($query) {
		mysqli_stmt_execute($query);
		$result = TRUE;
	}
	else {
		echo "Oops: " . $record ;
	}
	 
	mysqli_stmt_close($query);
	mysqli_close($db);
	return($result);
}

function display_route($key) {
	
	$gradeText = array(
		'A1' => 'Simple',
		'A2' => 'Challenging',
		'A3' => 'Complex',
		'A' => 'Easy',
		'B' => 'Moderate',
		'C' => 'Strenuous',
		'D' => 'Very Strenuous',
		'E' => 'Extended',
		'S1' => 'Track',
		'S2' => 'Off-track',
		'S3' => 'Back-country',
		'S4' => 'Advanced',
		'H1' => 'Hike',
		'H2' => 'Scramble',
		'H3' => 'Perhaps some exposure',
		'H4' => 'Climb',
		'H5' =>	'Multi-pitch',
		'B1' => 'All bikes',
		'B2' => 'Hybrid or mountain bike',
		'B3' => 'Mountain biking skills required');

	$db = connect();
	
	$sql = "select RouteName,RouteRange,RouteSubRange,RouteClubGrade,RouteLevel,RouteAccess,RouteDescription from gps where RouteKey = " . $key;
	$query = mysqli_prepare($db,$sql);
	mysqli_stmt_bind_result($query, $name,$range,$subrange,$grade,$level,$access,$description);
	mysqli_stmt_execute($query);
	
	mysqli_stmt_fetch($query);
	
	$grade_list = explode('-',$grade);
	$gradeView = print_r($grade_list,TRUE);
	$gradearray = print_r($gradeText,TRUE);
	$grade_text = "";

//	echo "<pre>" . $gradeView . " - " . $gradearray . " - " . $gradeText['A1'] . "\n";

	for ($step = 0 ; $step < 5 ; $step++) {
		if ($grade_list[$step] !== "") $grade_text .= $grade_list[$step] . " : " . $gradeText[$grade_list[$step]] . "<br />\n";
	}
		
	echo "<h3>" . $name . "</h3>\n";
	echo "<table>\n";
	echo "<tr><td>Track Range</td><td>" . $range . "</td></tr>\n";
	echo "<tr><td>Track Sub Range</td><td>" . $subrange . "</td></tr>\n";
	echo "<tr><td>Track Club Grading</td><td>" . $grade_text . "</td></tr>\n";
	echo "<tr><td>Track Grade Notes</td><td>" . $level . "</td></tr>\n";
	echo "<tr><td>Track Access</td><td>" . $access . "</td></tr>\n";
	echo "<tr><td>Track Description</td><td>" . $description . "</td></tr>\n";
	echo "</table>\n";
	
	mysqli_stmt_close($query);
	mysqli_close($db);
}

function list_files($key) {
	$db = connect();
	
	$sql = "select FileName,FileNote from files where RouteKey = " . $key;

//	echo "<pre>" . $sql . "</pre>\n";
	
	$query = mysqli_prepare($db,$sql);
	$result = mysqli_stmt_bind_result($query, $filename, $filenote);
	$clue = (!$result) ? "<p>Oops. Could not bind filename and note</p>" : "" ;
	if ($clue) echo $clue;
	
	mysqli_stmt_execute($query);
	
	echo "<h3>Available files:</h3>\n";
	echo "<p class='centred'><em>Right click or control click to download</em></p>\n";
	echo "<table>\n";
	
	while (mysqli_stmt_fetch($query)) {
		echo '<tr><td><a href="https://kootenaymountaineeringclub.ca/mountain-info/maps/gps/files/' . $key . "/" . $filename . '">' . $filename . "</td><td>" . $filenote . "</td><tr>\n" ;
	}
	
	echo "</table>\n";
	
	mysqli_stmt_close($query2);
	mysqli_close($db);

}
?>
