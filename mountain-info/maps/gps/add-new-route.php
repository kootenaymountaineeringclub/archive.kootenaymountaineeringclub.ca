<?php
		require_once ("util.php");
?>

<!DOCTYPE html>
<html>
<head>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width" initial-scale="1.0">

		<title>KMC: Mountain Info: New GPS Route</title>
	<link rel="stylesheet" type="text/css" href="/css/html5reset.css">
	<link rel="stylesheet" type="text/css" href="/css/kmc-style.css">
	<link rel="stylesheet" type="text/css" href="/css/kmc-layout.css">
	<link rel="stylesheet" type="text/css" href="css/gps-form.css">
	
	<meta name="keywords" content="mountaineering, kootenay, climb, hike, outdoor, mountain, ski, camp, wilderness, alpine, club">
	<meta name="description" content="The Kootenay Mountaineering Club promotes an interest in mountaineering skills, fellowship, and conservation of the natural values in the mountains.">

	<!--[if IE]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<script src="/js/jquery.min.js"></script>
	<script>
		background_images = new Array();
		background_images = [
			["BrendaHaley-BastileRidge.jpg","Brenda Haley"],
			["BrendaHaley-BeyondBastile.jpg","Brenda Haley"],
			["BrendaHaley-Glacier.jpg","Brenda Haley"],
			["BrendaHaley-Peak.jpg","Brenda Haley"],
			["BrendaHaley-Waterfall.jpg","Brenda Haley"],
			["DanDerby-Dominion.jpg","Dan Derby"],
			["DanDerby-FaithHopeCharity.jpg","Dan Derby"],
			["DanDerby-KokaneeGlacier.jpg","Dan Derby"],
			["DanDerby-KokaneeGlacier2.jpg","Dan Derby"],
			["DanDerby-TemporaryLower.jpg","Dan Derby"],
			["DanDerby-TemporaryUpper.jpg","Dan Derby"],
			["DanRichardson-Skyline1.jpg","Dan Richardson"],
			["DanRichardson-Skyline2.jpg","Dan Richardson"],
			["DanRichardson-Glow.jpg","Dan Richardson"],
			["DanRichardson-Shadow.jpg","Dan Richardson"],			
			["DianeWhite-BeyondKokanee.jpg","Diane White"],
			["DianeWhite-BeyondLoki.jpg","Diane White"],
			["DianeWhite-KokaneeBackside.jpg","Diane White"],
			["DianeWhite-LepsoeBasinHills.jpg","Diane White"],
			["DianeWhite-LepsoeBasinSkyline.jpg","Diane White"],
			["DianeWhite-LokiRidge.jpg","Diane White"],
			["DianeWhite-Pillows.jpg","Diane White"],
			["DianeWhite-Silohettes.jpg","Diane White"],
			["DianeWhite-Trees.jpg","Diane White"],
			["DianeWhite-Trees2.jpg","Diane White"],
			["RobRichardson0-1.jpg","Rob Richardson"],
			["RobRichardson1-1.jpg","Rob Richardson"],
			["RobRichardson4-1.jpg","Rob Richardson"],
			["RobRichardson5-1.jpg","Rob Richardson"],
			["RobRichardson5-2.jpg","Rob Richardson"],
			["RobRichardson6-1.jpg","Rob Richardson"],
			["RobRichardson7-1.jpg","Rob Richardson"],
			["PhilBest-EveningRidge.jpg","Phil Best"],
		];
		
		numb_images = background_images.length;
		chosen_image = Math.floor((Math.random() * numb_images) + 1);
		document.write( '<style>header {background-image: url(/images/header/' + background_images[chosen_image][0] + ');}</style>\n');
		document.write( '<style>header #photographer cite:after {content: "' + background_images[chosen_image][1] + '";}</style>\n');
</script>



</head>
<body>

<div id="master">

<header>

	<nav id="header-nav" class="linearBg">
		<a class="nav-link" href="/index.html">Home</a>
		<a class="nav-link" href="/club-info/index.html">Club Info</a>
		<a class="nav-link current" href="/mountain-info/index.html">Mountain Info</a>	
	</nav>

	<img class="logo" src="/images/GimliLogo.png" alt="The Club Logo">

	<h1>The Kootenay Mountaineering Club</h1>
	<p>A world of adventure in our own backyard</p>
	<p id="photographer"><cite>photo by </cite></p>

</header>

<div id="content">

<!--#include virtual=../../includes/mountain-gps.incl.html -->

<aside id="aside-left">
	<nav id="links">
	<ul>
		<li><a href="../gps-info.html">Links for Map &amp; GPS information</a></li>
	</ul>
	<hr>
	<ul>
		<li><em>Members Only</em></li>
		<li><a href="index.php">The Club's collection of gps tracks for favourite outings</a></li>
	</ul>
	</nav>
</aside>

<section id="section-right">
	
<?php
// files?

	$gps_key = get_gps_key();
	$gps_dir = $gps_key . "/";

	$name1 = "";
	$name2 = "";
	$name3 = "";
	
	if (!is_file("files/" . $gps_dir)) shell_exec(mkdir("files/" . $gps_dir));
	
	if ($_FILES['uploadfile1']['tmp_name'] != "") {
		$name1 = preg_replace("/[^A-Z0-9._-]/i", "_", $_FILES['uploadfile1']['name']);
		$from = $_FILES['uploadfile1']['tmp_name'];
		$to = UPDIR . $gps_dir . $name1;
//		echo "<p>Moving " . $from . " to " . $to . "</p >\n";
		$success = move_uploaded_file($from, $to);
		if (!$success) echo "<p> Ooops. Problem with name1 $name1</p>";
	}
	
	if ($_FILES['uploadfile2']['tmp_name'] != "") {
		$name2 = preg_replace("/[^A-Z0-9._-]/i", "_", $_FILES['uploadfile2']['name']);
		$from = $_FILES['uploadfile2']['tmp_name'];
		$to = UPDIR . $gps_dir . $name2;
//		echo "<p>Moving " . $from . " to " . $to . "</p>\n";
		$success = move_uploaded_file($from, $to);
		if (!$success) echo "<p> Ooops. Problem with name2 $name2</p>";
	}
	
	if ($_FILES['uploadfile3']['tmp_name'] != "") {
		$name3 = preg_replace("/[^A-Z0-9._-]/i", "_", $_FILES['uploadfile3']['name']);
		$from = $_FILES['uploadfile3']['tmp_name'];
		$to = UPDIR . $gps_dir . $name3;
//		echo "<p>Moving " . $from . " to " . $to . "</p>\n";
		$success = move_uploaded_file($from, $to);
		if (!$success) echo "<p> Ooops. Problem with name3 $name3</p>";
	}

	if ( ($name1 == "") and ($name2 == "") and ($name3 == "") ) {
		echo "<p>If there are no GPS files included, there's not much point in recording it all. Click your back button to try again. Thanks</p>";
			exit;
	}
	
//	echo "<pre>" . print_r($_POST,TRUE) . "</pre>";
	
	$route_grade = $_POST["rate_ates"] . "-" . $_POST["rate_effort"] . "-" . $_POST["rate_snow"] . "-" . $_POST["rate_hike"] . "-" . $_POST["rate_bike"] ;
	
// load to db
	$tofind = array("/ \"/","/\" /","/^\"/","/\"$/","/\'/");
	$toreplace = array(' “','” ','“','”','’');
	$routeLevel = preg_replace($tofind, $toreplace, $_POST['route-level']); //     
	$routeAccess = preg_replace($tofind, $toreplace, $_POST['route-access']); //     
	$routeDescription = preg_replace($tofind, $toreplace, $_POST['route-description']); //
	$fileNote1 = preg_replace($tofind, $toreplace, $_POST['file1-note']);
	$fileNote2 = preg_replace($tofind, $toreplace, $_POST['file2-note']);
	$fileNote3 = preg_replace($tofind, $toreplace, $_POST['file3-note']);

//	echo "<pre>" . $routeLevel . "\n\n" . $routeAccess . "\n\n" . $routeDescription . "</pre>";
//	exit;

	$gps_sql = "INSERT INTO gps ( RouteKey, RouteName, RouteSubmitter, RouteRange, RouteSubRange, RouteClubGrade, RouteLevel, RouteAccess, RouteDescription ) 
		VALUES (" . $gps_key . ", '" . $_POST['route-name'] . "', '" . $_POST['submitor-email'] . "', '" . $_POST['route-range'] . "', '" . $_POST['route-subrange'] . "', '" .  $route_grade . "', '" . $routeLevel . "', '" . $routeAccess . "', '" . $routeDescription . "')";
	
	$result = record($gps_sql);
	
	if ($result) {
		if ($name1) {
			$file_gps = "INSERT INTO files(RouteKey, FileName, FileNote) VALUES (" . $gps_key . ', "' . $name1 . ', "' . $fileNote1 . '")';
			record($file_gps);
		}
		if ($name2) {
			$file_gps = "INSERT INTO files(RouteKey, FileName, FileNote) VALUES (" . $gps_key . ', "' . $name2 . ', "' . $fileNote2. '")';
			record($file_gps);
		}
		if ($name3) {
			$file_gps = "INSERT INTO files(RouteKey, FileName, FileNote) VALUES (" . $gps_key . ', "' . $name3 . ', "' . $fileNote3 . '")';
			record($file_gps);
		}
	}
	
	?>
	
<!-- Route recorded -->
	
	<table>
		<tr><td>Name of route:</td><td><?php echo($_POST['route-name']) ?></td></tr>
		<tr><td>Submitted by:</td><td><?php echo($_POST['submitor-email']) ?></td></tr>
		<tr><td>Range</td><td><?php echo($_POST['route-range']) ?></td></tr>
		<tr><td>Range</td><td><?php echo($_POST['route-subrange']) ?></td></tr>
		<tr><td>Club Rating</td><td><?php echo($route_grade) ?></td></tr>
		<tr><td>Complexity</td><td><?php echo($routeLevel) ?></td></tr>
		<tr><td>Access</td><td><?php echo($routeAccess) ?></td></tr>
		<tr><td>Description</td><td><?php echo($routeDescription) ?></td></tr>
	</table>
	
	<p>File(s) uploaded:
		<ul><?php 
			if ($name1) echo "<li>" . $name1 . "</li>";
			if ($name2) echo "<li>" . $name2 . "</li>";
			if ($name2) echo "<li>" . $name3 . "</li>"; ?>
		</ul></p>
		
</section>
	
</div> <!-- end content -->

<footer>

<nav id="text-style" class="linearBg">
	<a class="current" href="/mountain-info/maps/">Maps &amp; GPS</a>
	<a href="../links.html">Links</a>
	<a href="../tech-tips.html">Tech&nbsp;Tips</a>
	<a href="../environment.html">Environment</a>
	<a href="../guidebooks.html">KMC hosted Guidebooks</a>
</nav>



</footer>

</div> <!-- end master -->

</body>
</html>	