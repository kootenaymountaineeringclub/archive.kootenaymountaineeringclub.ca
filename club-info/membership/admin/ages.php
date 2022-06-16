<?php
	
	$Range = array( 0 => "NA" , 16 => "Under 19" , 34 => "20 - 39" , 54 => "40 - 59" , 61 => "60 or more" ) ;

	$mysql = mysql_connect('localhost', 'kmcwe_kmc',  'zlika9p') or die('Could not connect: ' . mysql_error());
	
	mysql_select_db("kmcweb_0_kmc", $mysql) or die('Could not select database...');
	
	$agelist = array();
	$sql = "SELECT AgeRange,COUNT(AgeRange) as AgeCount FROM Member GROUP BY AgeRange ORDER BY AgeRange";
	
	$res = mysql_query($sql, $mysql);

	$rowcount = mysql_num_rows($res);
	
	for ( $i = 0 ; $i < $rowcount ; $i++) {
		$row = mysql_fetch_assoc($res);
		$agelist[$row['AgeRange']] = $row['AgeCount'];
	} 
	
	echo "<h3>Membership Age Distribution</h3>";
	
	echo "<table class='narrow'>\n<thead><tr><td>Age Range</td><td>Count</td></tr></thead>\n";

	foreach ($agelist as $age => $numb) {
		echo "<tr><td>" . $Range[$age] . "</td><td>" . $numb . "</td></tr>\n";
	}
	echo "</table>\n";


?>
