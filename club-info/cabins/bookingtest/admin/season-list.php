<?php include ("AdminHeader.php") ; ?>
		
	<h2>Cabin bookings for the current Winter Season</h2>
	
	<p class="centered"><a href='admin_menu.php'>Return to the administrators menu</a></p>
	
	<?php
	try {
		$dbconnect = new PDO("mysql:host=localhost;db-name=kmcweb_0_kmc","kmcwe_kmc","zlika9p");
		}
	catch (PDOexception $e) {
		echo $e->getMessage() . "\n";
		echo $e->getCode() . "\n";
		echo $e->getLine() . "\n";
		exit;
		}
	
	$sql = "select b.BookedDate,h.HutName,sum(b.NumPersons)
			from kmcweb_0_kmc.BookedHutDay b , kmcweb_0_kmc.Hut h
			where BookedDate between '2015-10-01' and '2016-05-30' and b.hutid = h.hutid
			group by b.BookedDate, b.HutID";
	
	$stuff = $dbconnect->prepare($sql);
	$stuff->execute();
	
	echo "<table class='narrow'>\n";
	echo "<tr><th>Date</th><th>Cabin</th><th>Booked Bunks</th></th></tr>\n";
	$bed_count = 0;
	$bookings = 0;
	
	foreach($dbconnect->query($sql) as $row) {
		$bed_count += $row[2];
		$booking_count += 1;
		echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td></tr>\n";
	
	}
	
	echo "</table>\n";
	echo "<p class='centered'>Bookings: " . $booking_count . "<br>\n";
	echo "Bunk Count: " . $bed_count . "</p>\n";
	?>

	<p class="centered"><a href='admin_menu.php'>Return to the administrators menu</a></p>

<?php include ("AdminFooter.php") ; ?>
