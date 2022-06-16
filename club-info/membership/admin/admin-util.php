<?php
	
	// define('PHP_ROOT','/var/www/vhosts/kootenaymountaineeringclub.ca/httpdocs');

	define('CURRENT_MEMBERS','SELECT DISTINCT(DistinctName) FROM kmcweb_0_kmc.Member ORDER BY DistinctName');
	define('PHP_ROOT','/var/www/vhosts/kootenaymountaineeringclub.ca/httpdocs');
	define('CONFIG_INDIVIDUAL','38');
	define('CONFIG_COUPLE','61');
	define('CONFIG_NEWSLETTER','20');
	define('RANGES', array( 0 => "NA" , 16 => "Under 19" , 34 => "20 - 39" , 54 => "40 - 59" , 61 => "60 or more" )) ;

//	define('PHP_ROOT','/USERS/Shared/KMCWebNames');

	function db_connect()
	{
      // Connect to Database
		$mysql = mysql_connect('localhost', 'kmcwe_kmc',  'zlika9p') or die('Could not connect: ' . mysql_error());
		mysql_select_db("kmcweb_0_kmc") or die('Could not select database...');
	}

	function db_get_user_password($id)
	{
		$sql = "SELECT Password FROM MemberAdmin where UserID = '" . $id . "'";
		$row = db_select_single_row($sql);
		return $row['Password'];
	}
	
	function db_select_single_row($sql)
	{
		$res = mysql_query($sql);
		if (!$res || (mysql_num_rows($res) == 0))
		{
			return(false);
		}		
		return(mysql_fetch_assoc($res)); 
	}
	
	function db_fetch_rows($sql)
	{
		$res = mysql_query($sql);
		$agelist = array();
		echo "<p>Rows: " . mysql_num_rows($res) . "</p>";
		while ($row = mysql_fetch_assoc($res)) {
			$agelist[$row['AgeRange']] = $row['COUNT(AgeRange)'];
		} 
		echo "<pre>" . print_r($agelist,TRUE) . "</pre>\n";
		return($agelist);
	}
	
	function get_memb_id()
	{
		 $sql = "SELECT FLOOR(100001 + (RAND() * 899999))";
		 $memb_id = db_select_single_row($sql);
		 return array_pop($memb_id);
	}

	function WhichMembershipYear()
	{
		$TZ = new DateTimeZone('America/Vancouver');
		$current_date = new DateTime("now",$TZ);

		$current_format = date_format($current_date, DATE_ATOM);
	
		$theDateAndTimeParts = explode("T", $current_format);
		$theDateParts = explode("-",$theDateAndTimeParts[0]);
	
		$currentYear = intval($theDateParts[0]);
		$currentMonth = intval($theDateParts[1]);
		
		if ($currentMonth > 9)
			$TheYear = ($currentYear + 1) ;
		elseif ($isBeforeLapseDate)
			$TheYear = ($currentYear - 1) ;
		else
			$TheYear = $currentYear ;
		
		return($TheYear);
	}
	
	

