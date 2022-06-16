<?php
	
	define('PHP_ROOT','/var/www/vhosts/kootenaymountaineeringclub.ca/httpdocs');

	function db_connect()
	{
      // Connect to Database
		$mysql = mysql_connect("localhost", "kmcwe_kmc",  "zlika9p") or die('Could not connect: ' . mysql_error());
		mysql_select_db("kmcweb_0_kmc") or die('Could not select database...');
	}
	
	function MembershipYearSelect ()
	{
		$TZ = new DateTimeZone('America/Vancouver');
		$current_date = new DateTime("now",$TZ);

		$current_format = date_format($current_date, DATE_ATOM);
	
		$theDateAndTimeParts = explode("T", $current_format);
		$theDateParts = explode("-",$theDateAndTimeParts[0]);
	
		$currentYear = intval($theDateParts[0]);
		$currentMonth = intval($theDateParts[1]);
		$currentDay = intval($theDateParts[2]);
		$lapseDate = 200;  // February 9th of any year. If not past that date, they get benefits
		$isBeforeLapseDate = (intval($theDateParts[1] . $theDateParts[2])) < $lapseDate;
	
		if ($currentMonth > 9)
			$SQLWhere = "(Year = $currentYear || Year = " . ($currentYear + 1) . ")" ;
		elseif ($isBeforeLapseDate)
			$SQLWhere = "(Year = $currentYear || Year = " . ($currentYear - 1) . ")" ;
		else
			$SQLWhere = "Year = $currentYear";
		
		return($SQLWhere);
	}

		
	function db_admin_validate_user($id, $pw)
	{
		$sql = "SELECT * FROM Administrator where LoginID = '" . $id . "' AND Password = '" . $pw ."'";
		$res = mysql_query($sql)
			or die("SELECT From Administrator failed: " .mysql_error());
		return $res;
	}
	function db_get_password($id)
	{
		$sql = "SELECT Password FROM Administrator Where LoginID = '" . $id . "'";
		$res = mysql_query($sql)
			or die("SELECT From Administrator failed: " .mysql_error());
		$pw = mysql_fetch_array($res);
		return $pw["Password"];
	}
	
	function admin_check_logged_in()
	{
		if (isset($_SESSION["admin"]))
			return true;
		return false;
	}
	
	function db_update_password($id,$pw)
	{
		$update_sql = "UPDATE Administrator SET Password = '" . $pw . "' WHERE LoginID = '" . $id . "'";
		$res = mysql_query($update_sql)
			or die("UPDATE  Stats (Administrator) failed: " .mysql_error());
	}
	
	function db_get_huts()
	{
		$get_huts_sql = "SELECT HutID, HutName, Capacity, Price FROM Hut ORDER BY Sequence";
		$get_huts_res = mysql_query($get_huts_sql)
			or die("SELECT From Hut failed: " .mysql_error());
		/*
		printf("\n%d Huts returned from database...\n", mysql_num_rows($get_huts_res));
		*/
		return $get_huts_res;
	}

	function db_get_bookings_since_one_month() 
	{
	 	$TZ = new DateTimeZone('America/Vancouver');
	 	$now = new DateTime("now",$TZ);
		$then = date_sub($now,new DateInterval("P1M"));
		
		$get_sql = 'SELECT h.HutName, b.NumPersons, b.BookedDate, b.LoggedBy, b.BookedByEmail, b.BookedByFirstName, b.BookedByLastName, b.Touched FROM BookedHutDay b INNER JOIN Hut h ON b.HutID = h.HutID WHERE b.BookedDate > date("' . date_format($then,'Y-m-d') . '") ORDER BY b.BookedDate DESC';
		
//        echo "<p>" . $get_sql . "</p>\n";
        
		$get_res = mysql_query($get_sql)
			or die("SELECT From BookedHutDay failed: " .mysql_error());
		return $get_res;
	}

	function db_get_bookings()
	{
		$get_sql = "SELECT h.HutName, b.NumPersons, b.BookedDate, b.LoggedBy, b.BookedByEmail, b.BookedByFirstName, b.BookedByLastName, b.Touched FROM BookedHutDay b INNER JOIN Hut h ON b.HutID = h.HutID ORDER BY b.BookedDate DESC";
		$get_res = mysql_query($get_sql) or die("SELECT From BookedHutDay failed: " .mysql_error());
		return $get_res;
	}
	
	function db_get_stats()
	{
		$get_sql = "SELECT * FROM Stats";
		$get_res = mysql_query($get_sql)
			or die("SELECT From Stats failed: " .mysql_error());
		return $get_res;
	}
	
	function db_get_person_nights_booked_hut($hut_id)
	{
		$get_sql = "SELECT SUM(NumPersons) FROM BookedHutDay WHERE HutID = " . $hut_id;
		$get_res = mysql_query($get_sql)
			or die("SELECT SUM(NumPersons From BookedHutDay failed: " .mysql_error());
        $bookings = mysql_fetch_array($get_res);
        return $bookings[0];
    }
	
	function db_get_person_nights_comp()
	{
		$get_sql = "SELECT SUM(NumPersons) FROM BookedHutDay WHERE LoggedBy = 'Complimentary'";
		$get_res = mysql_query($get_sql)
			or die("SELECT SUM(NumPersons From BookedHutDay failed: " .mysql_error());
        $bookings = mysql_fetch_array($get_res);
        return $bookings[0];
    }
    
    function db_get_person_nights_booked()
    {
 		$get_sql = "SELECT SUM(NumPersons) FROM BookedHutDay";
		$get_res = mysql_query($get_sql)
			or die("SELECT SUM(NumPersons From BookedHutDay failed: " .mysql_error());
        $bookings = mysql_fetch_array($get_res);
        return $bookings[0];
   }
	
	function db_get_hut($hut_id)
	{
		$get_hut_sql = "SELECT HutID, HutName, Capacity, Price, Picture FROM Hut WHERE HutID = " . $hut_id;
		$get_hut_res = mysql_query($get_hut_sql)
			or die("SELECT From Hut failed: " .mysql_error());
		/*
		printf("\n%d Huts returned from database...\n", mysql_num_rows($get_hut_res));
		*/
		return $get_hut_res;
	}
	
	function db_inc_admin_login_sucessfull()
	{
		$update_sql = "UPDATE Stats SET AdminLoginSucessfull = AdminLoginSucessfull + 1";
		$res = mysql_query($update_sql)
			or die("UPDATE  Stats (AdminLoginSucessfull) failed: " .mysql_error());
	}
	
	function db_inc_admin_login_failed()
	{
		$update_sql = "UPDATE Stats SET AdminLoginFailed = AdminLoginFailed + 1";
		$res = mysql_query($update_sql)
			or die("UPDATE  Stats (AdminLoginFailed) failed: " .mysql_error());
	}
	
	function format_db_date($mysql_date,$format)
	{
		/*
		$mysql_date - The Date which should be formatted...
		$format - The format string.... 
		refer the Date function for format String
		*/
		$dateTime = strtotime($mysql_date);
		$formatted_date = date($format, $dateTime);
		return $formatted_date;
	}
	
	function format_db_time_stamp($ts)
	{
		$fts = substr($ts,0,4); // Year
		$fts .= "-";
		$fts .= substr($ts,4,2); // Month
		$fts .= "-";
		$fts .= substr($ts,6,2); // Day
		$fts .= " ";
		$fts .= substr($ts,8,2); // Hour
		$fts .= ":";
		$fts .= substr($ts,10,2); // Min
		return $fts;
	} 

?>