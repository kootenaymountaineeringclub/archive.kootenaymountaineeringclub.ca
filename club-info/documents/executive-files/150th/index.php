<?php

define('PHP_ROOT','/var/www/vhosts/kootenaymountaineeringclub.ca/httpdocs');
define('CLUB_ROOT', '/var/www/vhosts/kootenaymountaineeringclub.ca/httpdocs/club_info');
define('150_ROOT', '/var/www/vhosts/kootenaymountaineeringclub.ca/httpdocs/club_info/documents/executive-files/150th');

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

function fix($turn) {
	
	$db = connect();
	
	$sql = 'delete from ascents where turn = "' . $turn . '"';
	
	$query = mysqli_prepare($db,$sql);
	
	if ($query) {
		mysqli_stmt_execute($query);
	}
	else {
		echo "Oops" . PHP_EOL;
	}
	 
	mysqli_close($db);
}

function listclimbsforadmin() {
	$db = connect();
	$sql = "select mountain,mountainrange,name,whenclimbed,turn from ascents order by mountain,whenclimbed";
	$query = mysqli_prepare($db,$sql);
	mysqli_stmt_execute($query);
	mysqli_stmt_bind_result($query, $mountain, $range, $name, $whenclimbed, $turn);

	while (mysqli_stmt_fetch($query)) {
		echo "<tr>\r";
		echo "<td>" . $mountain . "</td>\r";
		echo "<td>" . $range . "</td>\r";
		echo "<td>" . $name . "</td>\r";
		echo "<td>" . $whenclimbed . "</td>\r";
		echo '<td><form action="index.php" method="post"><input name="turn" id="turn" type="text" value="' . $turn . '" /><input type="submit" value="Delete" /></form></td>';
		echo "\r</tr>\r";
	}
	
	mysqli_close($db);
}

?>

<!DOCTYPE html>
<html>
<head>
	<?php include (PHP_ROOT . "/includes/head-first.incl.html") ; ?>

	<title>150 Mountain Challenge Admin</title>
	
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

		<?php if ($_POST["turn"] != "") fix($_POST["turn"]); ?>
		
		<h2 class="centered">150 Summit Challenge Admin</h2>
		
		<h3 class="centered">The Mountains</h3>
		
		<table>
			<tr><td><strong>Mountain</strong></td>
			<td><strong>Range</strong></td>
			<td><strong>Who</strong></td>
			<td><strong>When</strong></td>
			<td><strong>Entered</strong></td>
			<td></td>			
			</tr>
			
			<?php listclimbsforadmin(); ?>
			
		</table>
		
		</section>
	
	</div> <!-- end content -->

	<footer>

		<?php include (PHP_ROOT . "/club-info/includes/club-documents.incl.html") ; ?>

	</footer>

</div> <!-- end master -->

</body>
</html>

