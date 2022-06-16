<?php
	
define('PHP_ROOT','/var/www/vhosts/kootenaymountaineeringclub.ca/httpdocs');
//define('PHP_ROOT','/USERS/Shared/KMCWebNames');

function connect_to_db() {
	try {
		$db_conn = new PDO("mysql:host=127.0.0.1;db-name=kmcweb_0_web","kmcwe_kmc","zlika9p");
		}
	catch (PDOexception $e) {
		echo $e->getMessage() . "\n";
		echo $e->getCode() . "\n";
		echo $e->getLine() . "\n";
		exit;
		}
	return($db_conn);
}

?>
