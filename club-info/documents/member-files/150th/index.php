<?php

define('PHP_ROOT','/var/www/vhosts/kootenaymountaineeringclub.ca/httpdocs');
define('CLUB_ROOT', '/var/www/vhosts/kootenaymountaineeringclub.ca/httpdocs/club_info');
define('150_ROOT', '/var/www/vhosts/kootenaymountaineeringclub.ca/httpdocs/club_info/documents/member-files/150th');

function connect() {
	$db = mysqli_connect("localhost","kmcwe_kmc150","CAoyo2ABtJWG","kmcweb_0_kmc150");
	
	if (!$db) {
		echo "Oops" . PHP_EOL;
		echo "number: " . mysqli_connect_errno() . PHP_EOL;
		echo "error: " . mysqli_connect_error() . PHP_EOL;
		exit;
	}
	return $db;
}

function record($record) {
	
	$db = connect();
	
	$sql = $record;
	
	$query = mysqli_prepare($db,$sql);
	
	if ($query) {
		mysqli_stmt_execute($query);
	}
	else {
		echo "Oops" . PHP_EOL;
	}
	 
	mysqli_close($db);
}

function listclimbs() {
	$db = connect();
	$sql = "select mountain,mountainrange,name,whenclimbed from ascents order by mountain,whenclimbed";
	$query = mysqli_prepare($db,$sql);
	mysqli_stmt_execute($query);
	mysqli_stmt_bind_result($query, $mountain, $range, $name, $whenclimbed);

	while (mysqli_stmt_fetch($query)) {
		echo "<tr>\r";
		echo "<td>" . $mountain . "</td>\r";
		echo "<td>" . $range . "</td>\r";
		echo "<td>" . $name . "</td>\r";
		echo "<td>" . $whenclimbed . "</td>\r";
		echo "\r</tr>\r";
	}
	mysqli_close($db);
}

function submitclimb() {
	$db = connect();
	$sql = "insert into ascents (mountain,mountainrange,name,whenclimbed) values( ";
	$sql .= '"' . $_POST["mountain"] . '",';
	$sql .= '"' . $_POST["mountainrange"] . '",';
	$sql .= '"' . $_POST["name"] . '",';
	$sql .= '"' . $_POST["whenclimbed"] . '")';

	$query = mysqli_prepare($db,$sql);
	mysqli_stmt_execute($query);
	mysqli_close($db);	
}

?>

<!DOCTYPE html>
<html>
<head>
	<?php include (PHP_ROOT . "/includes/head-first.incl.html") ; ?>

	<title>KMC: Canada's 150th and Mountains</title>
	
	<?php include (PHP_ROOT . "/includes/head-2nd.incl.html") ; ?>
	<?php include (PHP_ROOT . "/js/header-pictures.js") ; ?>
	
</head>
<body>

<div id="master">

	<header>

	<?php include (PHP_ROOT . "/club-info/includes/page-header-club.incl.html") ; ?>
	<?php include (PHP_ROOT . "/includes/header-contents.incl.html") ; ?>
	
	</header>

	<div id="content">
		<?php include (PHP_ROOT . "/club-info/includes/club-documents.incl.html") ; ?>

		<section>
		<h2>Canada's 150th Anniversary and Mountains!</h2>
		<h3>The KMC 150 Summit Challenge</h3>
		
		<p>The objective of the KMC 150 Summit Challenge was to celebrate Canada's 150th birthday by KMC members combining efforts to climb 150 different Canadian mountains from January 1, 2017 until the end of 2017. All 'named' mountains in Canada were eligible to be climbed as part of the challenge.  There had to be at least one KMC member who reached the summit.</p>

		<hr>
				
		<h2>Mountains summitted...</h2>
		
		<table>
			<tr><td><strong>Mountain</strong></td><td><strong>Range</strong></td><td><strong>Who</strong></td><td><strong>When</strong></td></tr>
			<?php listclimbs(); ?>	
		</table>
		
		</section>
	
	</div> <!-- end content -->

	<footer>

		<?php include (PHP_ROOT . "/club-info/includes/club-documents.incl.html") ; ?>

	</footer>

</div> <!-- end master -->

</body>
</html>

