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
