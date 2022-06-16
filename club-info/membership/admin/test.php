<html>
<head>
	<title>Email list create</title>
</head>
<body>

<p>Running email list creation...</p>

<?php


try {
	$dbconnect = new PDO("mysql:host=localhost;db-name=kmcweb_0_web","kmcwe_kmc","zlika9p");
	}
catch (PDOexception $e) {
	echo $e->getMessage() . "\n";
	echo $e->getCode() . "\n";
	echo $e->getLine() . "\n";
	exit;
	}

date_default_timezone_set('America/Vancouver');

$when = date('Y-m-d');

$livelist = "Kootenay Mountaineering Email List on " . $when . "\r\n\r\n";
$sql = "SELECT DISTINCT(Email) FROM kmcweb_0_kmc.Member WHERE Email is not null ORDER BY Email";

$stuff = $dbconnect->prepare($sql);
$stuff->execute();

while ( $names = $stuff->fetch() ) {
	$newsql = "SELECT email from listbox_unsubscribed where email = '" . $names['email'] . "'";
	$unsubed = $dbconnect->prepare($newsql);
	$unsubed->execute();
	$found = false;
	while ($unsub = $unsubed->fetch()) {
		$unsub_list .= $unsub['email'] . "\n";
		$found = true;
	}
	
	if ($found) $livelist .= $names['Email'] . "\r\n";
}

echo "<p>Livelist</p><pre>";
echo $livelist ;
echo "</pre>";
echo "<p>Unsubed</p><pre>";
echo $unsub_list ;
echo "</pre>";
?>

</body>
</html>
