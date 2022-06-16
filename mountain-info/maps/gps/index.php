<!DOCTYPE html>
<html>
<head>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width" initial-scale="1.0">

		<title>KMC: Mountain Info: GPS</title>
	<link rel="stylesheet" type="text/css" href="/css/html5reset.css">
	<link rel="stylesheet" type="text/css" href="/css/kmc-style.css">
	<link rel="stylesheet" type="text/css" href="/css/kmc-layout.css">
	
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

<nav id="text-style">
	<a class="current" href="/mountain-info/maps/">Maps &amp; GPS</a>
	<a href="/mountain-info/links.html">Links</a>
	<a href="/mountain-info/tech-tips.html">Tech&nbsp;Tips</a>
	<a href="/mountain-info/environment.html">Environment</a>
	<a href="/mountain-info/guidebooks.html">KMC hosted Guidebooks</a>
</nav>

<aside id="aside-left">
	<nav id="links">
	<ul>
		<li><a href="/mountain-info/maps/gps-info.html">Links for Map &amp; GPS information</a></li>
	</ul>
	<hr>
	<ul>
		<li><em>Members Only</em></li>
		<li><a href="/mountain-info/maps/gps/index.php">The Club's collection of gps tracks for favourite outings</a></li>
	</ul>
	</nav>
</aside>

<section id="section-right">
	
	<h2>KMC GPS Route Collection</h2>
	
	<?php
		require_once 'util.php';
		
		$routes = count_routes();	
		$files = count_files();	
		
		echo "<p class='centred'>The collection currently contains " . $routes . " routes and " . $files;
		echo ($files > 1) ? " files.</p>" : " file.</p>";
 	?>
	
	<p class="centred"><a href="new-route-form.html">Add a new route</a>.</p>
	
	<h3>Kootenay Routes</h3>
	
	<div class="centred">
	<?php
		list_routes();	
	?>
	</div>
	
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