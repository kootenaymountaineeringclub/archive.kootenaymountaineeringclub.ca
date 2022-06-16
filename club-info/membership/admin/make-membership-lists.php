	<?php
	include ("../safe/db-util.php") ;

	date_default_timezone_set("America/Vancouver");
	
	$when = date("Y-m-d");
	
	$outfile = PHP_ROOT . "/club-info/documents/executive-files/KMC-Full-Membership-List.txt";
	
	$namelist = fopen($outfile,"w");
	if (!$namelist ) die ("No file\n");
	
	fwrite($namelist,"KMC Members: " . $when . "\n\n");
	fwrite($namelist,"Year\tDistinctName\tMembershipID\tMembershipType\tEmail\tPhone\tPrivateName\tStreetAddress\tStreetAddress2\tCity\tProvince\tPostalCode\tKMCNewsletter\tFMCBCNewsletter\n");
	
	$sql = "SELECT DISTINCT(DistinctName) FROM kmcweb_0_kmc.Member ORDER BY DistinctName";

	try {
		$conn = new PDO("mysql:host=127.0.0.1;db-name=kmcweb_0_web","kmcwe_kmc","zlika9p");
	}
	catch (PDOexception $e) {
		exit;
	}
	
	$stuff = $conn->prepare($sql);
	$stuff->execute();
	$count = 0;
	
	while ( $name = $stuff->fetch() ) {
		$count += 1;
		
		$sql = "SELECT * from kmcweb_0_kmc.Member where DistinctName = '" . $name["DistinctName"] . "'";
	
		$info = $conn->prepare($sql);
		$info->execute();
		
		$memberinfo = $info->fetch();

		$member = $memberinfo["Year"] . "\t";					
		$member .= $memberinfo["DistinctName"] . "\t";					
		$member .= $memberinfo["MembershipID"] . "\t";
		$member .= $memberinfo["MembershipType"] . "\t";
		$member .= $memberinfo["Email"] . "\t";
		$member .= $memberinfo["Phone"] . "\t";
		$member .= $memberinfo["PrivateName"] . "\t";
		$member .= $memberinfo["StreetAddress"] . "\t";
		$member .= $memberinfo["StreetAddress2"] . "\t";
		$member .= $memberinfo["City"] . "\t";
		$member .= $memberinfo["Province"] . "\t";
		$member .= $memberinfo["PostalCode"] . "\t";
		$member .= $memberinfo["KMCNewsletter"] . "\t";
		$member .= $memberinfo["FmcbcNewsletter"];
		$member .= "\n";
		
		fwrite($namelist,$member);

	}
	
	$outfile = PHP_ROOT . "/club-info/documents/member-files/member/KMC-Membership-List.txt";
	
	$namelist = fopen($outfile,"w");
	if (!$namelist ) die ("No file\n");
	
	fwrite($namelist,"KMC Members who do not have a private name setting: " . $when . "\n\n");
	fwrite($namelist,"DistinctName\tEmail\tPhone\tStreetAddress\tStreetAddress2\tCity\tProvince\tPostalCode\n");
	
	$sql = "SELECT DISTINCT(DistinctName) FROM kmcweb_0_kmc.Member where PrivateName = 0 ORDER BY DistinctName";

	try {
		$conn = new PDO("mysql:host=127.0.0.1;db-name=kmcweb_0_web","kmcwe_kmc","zlika9p");
	}
	catch (PDOexception $e) {
		echo $e->getMessage() . "\n";
		echo $e->getCode() . "\n";
		echo $e->getLine() . "\n";
		exit;
	}
	
	$stuff = $conn->prepare($sql);
	$stuff->execute();
	$count = 0;
	
	while ( $names = $stuff->fetch() ) {
		$count += 1;
		
		$sql = "SELECT * from kmcweb_0_kmc.Member where DistinctName = '" . $names["DistinctName"] . "'";
	
		$info = $conn->prepare($sql);
		$info->execute();
		
		$memberinfo = $info->fetch();
		
		$member = $memberinfo["DistinctName"] . "\t";
		$member .= $memberinfo["Email"] . "\t";
		$member .= $memberinfo["Phone"] . "\t";
		$member .= $memberinfo["StreetAddress"] . "\t";
		$member .= $memberinfo["StreetAddress2"] . "\t";
		$member .= $memberinfo["City"] . "\t";
		$member .= $memberinfo["Province"] . "\t";
		$member .= $memberinfo["PostalCode"] . "\t";
		$member .= "\n";
		
		fwrite($namelist,$member);

	}
?>
