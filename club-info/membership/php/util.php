<?php
	//define('PHP_ROOT','/Users/Shared/KMCWebNames');
	define('PHP_ROOT','/var/www/vhosts/kootenaymountaineeringclub.ca/httpdocs');
	define('CONFIG_INDIVIDUAL','38');
	define('CONFIG_COUPLE','61');
	define('CONFIG_NEWSLETTER','20');
	
	session_start();
	
	/*  Select clause for what membership year to use for current member lists */
	
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

	function db_connect()
	{
      // Connect to Database
		$mysql = mysql_connect("localhost", "kmcwe_kmc",  "zlika9p") or die('Could not connect: ' . mysql_error());
		mysql_select_db("kmcweb_0_kmc") or die('Could not select database...');
	}
	
	function get_memb_id()
	{
	 $sql = "SELECT FLOOR(100001 + (RAND() * 899999))";
	 $memb_id = db_select_single_row($sql);
	 return array_pop($memb_id);
	}

	function db_get_user_password($id)	
	{
		$sql = "SELECT Password FROM MemberAdmin where UserID = '" . $id . "'";
		$row = db_select_single_row($sql);
		return $row['Password'];
	}
	
	function db_insert_member1()
	{
		debug_log("db_insert_member1");
		$fname = $_REQUEST['firstName1'];
		$lname = $_REQUEST['lastName1'];
		$email = '';
		if (isset($_REQUEST['email1']))
			$email = $_REQUEST['email1'];
		return(db_insert_member(-1, $fname, $lname, $email, false));
	}

	function db_insert_member2($id)
	{
		debug_log("db_insert_member2");
		$fname = $_REQUEST['firstName2'];
		$lname = $_REQUEST['lastName2'];
		$email = '';
		if (isset($_REQUEST['email2']))
			$email = $_REQUEST['email2'];
		db_insert_member($id, $fname, $lname, $email, false);
	}
	
	function db_insert_child1($id)
	{
		debug_log("db_insert_child1");
		$fname = $_REQUEST['childFirstName1'];
		$lname = $_REQUEST['childLastName1'];
		db_insert_member($id, $fname, $lname, "", "true");
	}
	
	function db_insert_child2($id)
	{
		$fname = $_REQUEST['childFirstName2'];
		$lname = $_REQUEST['childLastName2'];
		db_insert_member($id, $fname, $lname, "", "true");
	}
	
	function db_insert_child3($id)
	{
		$fname = $_REQUEST['childFirstName3'];
		$lname = $_REQUEST['childLastName3'];
		db_insert_member($id, $fname, $lname, "", "true");
	}
	
	function db_insert_child4($id)
	{
		$fname = $_REQUEST['childFirstName4'];
		$lname = $_REQUEST['childLastName4'];
		db_insert_member($id, $fname, $lname, "", "true");
	}
	
	function db_insert_member($parentID, $fname, $lname, $email, $isChild)
	{
		debug_log("db_insert_member");
		$active = false;
		if (isset($_REQUEST['active']))
		{
			if ($_REQUEST['active'] == "true")
				$active = true;
		}
		$insert_sql = "INSERT INTO Member (Year, MembershipType, Enrollment, Child, Voting, FirstName, LastName, StreetAddress, City, Country "; // All NOT NULL 
		$values = $_REQUEST['year'];
		$values .= ", '" . $_REQUEST['membershipType'] . "'";
		$values .= ", '" . $_REQUEST['enrollment'] . "'";
		if (!$isChild)
		{
			$values .= ", 0"; // Child = false
			if ($_REQUEST['voting'] == "true")		
				$values .= ", 1";
			else
				$values .= ", 0";
		}
		else
		{
				$values .= ", 1"; // Child = true
				$values .= ", 0"; // Children don't vote
		}
		$values .= ", '" . $fname . "'";
		$values .= ", '" . $lname . "'";
		$values .= ", '" . $_REQUEST['streetAddr'] . "'";
		$values .= ", '" . $_REQUEST['city'] . "'";
		$values .= ", '" . $_REQUEST['country'] . "'";
		if (isset($_REQUEST['mailingAddr']))
		{
			$insert_sql .= ", MailingAddress";
			$values .= ", '" . $_REQUEST['mailingAddr'] . "'";
		}
		if (isset($_REQUEST['province']))
		{
			$insert_sql .= ", Province";
			$values .= ", '" . $_REQUEST['province'] . "'";
		}
		if (isset($_REQUEST['postalCode']))
		{
			$insert_sql .= ", PostalCode";
			$values .= ", '" . $_REQUEST['postalCode'] . "'";
		}
		if (isset($_REQUEST['phone']))
		{
			$insert_sql .= ", Phone";
			$values .= ", '" . $_REQUEST['phone'] . "'";
		}
		if (!$isChild)
		{
			if ($email != "")
			{
				$insert_sql .= ", Email";
				$values .= ", '" . $email . "'";
			}
			if (isset($_REQUEST['kmcMedia']))
			{
				$insert_sql .= ", KmcNewsletter";
				$values .= ", '" . $_REQUEST['kmcMedia'] . "'";
			}
			if (isset($_REQUEST['fmcbcMedia']))
			{
				$insert_sql .= ", FmcbcNewsletter";
				$values .= ", '" . $_REQUEST['fmcbcMedia'] . "'";
			}
		}
		if (isset($_REQUEST['privateName']) && ($_REQUEST['privateName'] == "true"))
		{
			$insert_sql .= ", PrivateName";
			$values .= ", 1";
		}
		if (isset($_REQUEST['privateCity']) && ($_REQUEST['privateCity'] == "true"))
		{
			$insert_sql .= ", PrivateCity";
			$values .= ", 1";
		}
		if (isset($_REQUEST['privatePhone']) && ($_REQUEST['privatePhone'] == "true"))
		{
			$insert_sql .= ", PrivatePhone";
			$values .= ", 1";
		}
		if (isset($_REQUEST['privateEmail']) && ($_REQUEST['privateEmail'] == "true"))
		{
			$insert_sql .= ", PrivateEmail";
			$values .= ", 1";
		}
		if ($active)
		{
			$insert_sql .= ", Active";
			$values .= ", 1";
		}
		if (isset($_REQUEST['paymentMethod']))
		{
			$insert_sql .= ", PaymentMethod";
			$values .= ", '" . $_REQUEST['paymentMethod'] . "'";
		}
		if (isset($_REQUEST['paymentStatus']))
		{
			$insert_sql .= ", PaymentStatus";
			$values .= ", '" . $_REQUEST['paymentStatus'] . "'";
		}
		if ($parentID != -1)
		{
			$insert_sql .= ", ParentID";
			$values .= ", " . $parentID;
		}
		else
		{
			$insert_sql .= ", amount";
			$values .= ", " . $_REQUEST['amount'];
		}
		$insert_sql .= ") VALUES (" . $values . ")";
		debug_log("db_insert_member; " . $insert_sql);
		$res = mysql_query($insert_sql);
		if (!$res)
		{
		 	debug_log("INSERT into Member failed: SQL: " . $insert_sql . " Error: " . mysql_error());
			die("INSERT into Member failed: SQL: " . $insert_sql . " Error: " . mysql_error());
		}
		// Update the Email List Server if Active with an email address
		if ($active)
		{
			if ($email != "")
			{
			 	add_email_to_listsvr($email);
			}
		}
		if ($parentID == -1)
		{
			$id = mysql_insert_id(); // get Membership ID
			return $id;
		}
	}
	
	function db_update_member()
	{
		debug_log("db_update_member");
		$id = $_REQUEST["id"];
		$active = true;
		$old_email = "";
		$new_email = "";
		$old_email = db_get_email($id);
		if (isset($_REQUEST['email1']))
			$new_email =$_REQUEST['email1'];
		if ($_REQUEST['active'] == 'false')
			$active = false;
		$update_sql = "UPDATE Member  SET "; 
		$update_sql .= "MemberSince = '" . $_REQUEST['yearSince'] . "'";
		$update_sql .= ", FirstName = '" . $_REQUEST['firstName1'] . "'";
		$update_sql .= ", LastName = '" . $_REQUEST['lastName1'] . "'";
		$update_sql .= ", StreetAddress = '" . $_REQUEST['streetAddr'] . "'";
		$update_sql .= ", City = '" . $_REQUEST['city'] . "'";
		$update_sql .= ", Country = '" . $_REQUEST['country'] . "'";
		$update_sql .= ", KmcNewsletter = '" . $_REQUEST['kmcMedia'] . "'";
		$update_sql .= ", FmcbcNewsletter = '" . $_REQUEST['fmcbcMedia'] . "'";
               // $update_sql .= "MemberSince = '" . $_REQUEST['yearSince'] . "'";
		if ($_REQUEST['privateName'] == "true")
			$update_sql .= ", PrivateName = 1";
		else
			$update_sql .= ", PrivateName = 0";
		if ($_REQUEST['privateCity'] == "true")
			$update_sql .= ", PrivateCity = 1";
		else
			$update_sql .= ", PrivateCity = 0";
		if ($_REQUEST['privatePhone'] == "true")
			$update_sql .= ", PrivatePhone = 1";
		else
			$update_sql .= ", PrivatePhone = 0";
		if ($_REQUEST['privateEmail'] == "true")
			$update_sql .= ", PrivateEmail = 1";
		else
			$update_sql .= ", PrivateEmail = 0";
		if (isset($_REQUEST['mailingAddr']))
			$update_sql .= ", MailingAddress = '" . $_REQUEST['mailingAddr'] . "'";
		else
			$update_sql .= ", MailingAddress = NULL";
		
		if (isset($_REQUEST['province']))
			$update_sql .= ", Province = '" . $_REQUEST['province'] . "'";
		else
			$update_sql .= ", Province = NULL";
		
		if (isset($_REQUEST['postalCode']))
			$update_sql .= ", PostalCode = '" . $_REQUEST['postalCode'] . "'";
		else
			$update_sql .= ", PostalCode = NULL";
		
		if (isset($_REQUEST['phone']))
			$update_sql .= ", Phone = '" . $_REQUEST['phone'] . "'";
		else
			$update_sql .= ", Phone = NULL";
		
		if (isset($_REQUEST['email1']))
			$update_sql .= ", Email = '" . $_REQUEST['email1'] . "'";
		else
			$update_sql .= ", Email = NULL";
		//if (isset($_REQUEST['active']))
		//	$update_sql .= ", Active = '" . $_REQUEST['active'] . "'";
		if (isset($_REQUEST['active']) && $_REQUEST['active']=='true')
			$update_sql .= ", Active = '1'";
                if (isset($_REQUEST['active']) && $_REQUEST['active']=='false')
			$update_sql .= ", Active = '0'";

if (isset($_REQUEST['paymentMethod']))
			$update_sql .= ", PaymentMethod = '" . $_REQUEST['paymentMethod'] . "'";
		if (isset($_REQUEST['paymentStatus']))
			$update_sql .= ", PaymentStatus = '" . $_REQUEST['paymentStatus']. "'";
		$update_sql .= " WHERE MemberID = " . $id;
		//debug_log("db_update_member: " . $update_sql);
		$res = mysql_query($update_sql);
		if (!$res)
		{
		 	debug_log("db_update_member: UPDATE  Member failed: SQL: " . $update_sql . " Error: " . mysql_error());
			die("db_update_member: UPDATE  Member failed: SQL: " . $update_sql . " Error: " . mysql_error());
		}
		// Update List Server
		remove_email_from_listsvr($old_email);
		if ($active)
		{
			add_email_to_listsvr($new_email); // Maybe active changed so add email as it won't be there if was in active
		}
	}
	
	function db_cancel_payment($id, $year, $stat)
	{
		debug_log("db_cancel_payment");
		$update_sql = "UPDATE Member  SET Active = 0, Amount = 0.00, "; 
		$update_sql .= "PaymentStatus = '" . $stat . "'";
		$update_sql .= " Where MemberID = " . $id . " AND Year = " . $year;
		debug_log("db_cancel_payment: " . $update_sql);
		$res = mysql_query($update_sql);
		if (!$res)
		{
		 	debug_log("db_cancel_payment: UPDATE  Member failed: SQL: " . $update_sql . " Error: " . mysql_error());
			die("db_cancel_payment: UPDATE  Member failed: SQL: " . $update_sql . " Error: " . mysql_error());
		}
	}
	
	function db_cancel_co_member_payment($id, $year, $stat)
	{
		debug_log("db_cancel_co_member_payment");
		$update_sql = "UPDATE Member  SET Active = 0, Amount = 0.00, "; 
		$update_sql .= "PaymentStatus = '" . $stat . "'";
		$update_sql .= " Where ParentID = " . $id . " AND Year = " . $year;
		debug_log("db_cancel_co_member_payment: " . $update_sql);
		$res = mysql_query($update_sql);
		if (!$res)
		{
		 	debug_log("db_cancel_co_member_payment: UPDATE  Member failed: SQL: " . $update_sql . " Error: " . mysql_error());
			die("db_cancel_co_member_payment: UPDATE  Member failed: SQL: " . $update_sql . " Error: " . mysql_error());
		}
	}
	
	function db_findMembers()
	{

		debug_log("db_findMembers");
		
		$YearSelect = MembershipYearSelect();
	
		$sql = "SELECT * FROM Member WHERE " . $YearSelect;
		if (isset($_REQUEST['firstName']))
			$sql .= " AND FirstName LIKE '%" . $_REQUEST['firstName'] . "%'";
		if (isset($_REQUEST['lastName']))
			$sql .= " AND LastName LIKE '%" . $_REQUEST['lastName'] . "%'";
		if (isset($_REQUEST['email']))
			$sql .= " AND Email LIKE '%" . $_REQUEST['email'] . "%'";
		if (isset($_REQUEST['phone']))
			$sql .= " AND Phone LIKE '%" . $_REQUEST['phone'] . "%'";
		$display_block = '<sql>' . $sql . '</sql>';
		debug_log("db_findMembers: " . $sql);
		$res = mysql_query($sql);
		if (!$res)
		{
		 	debug_log("db_FindMembers: SELECT  Member failed: SQL: " . $sql . " Error: " . mysql_error());
			die("db_FindMembers: SELECT  Member failed: SQL: " . $sql . " Error: " . mysql_error());
		}		
		for($x = 0 ; $x < mysql_num_rows($res) ; $x++)
		{ 
    		$row = mysql_fetch_assoc($res); 
    		$display_block .= "\t<member>\n"; 
    		$display_block .= "\t\t<id>" . $row['MemberID'] . "</id>\n"; 
		$display_block .= "\t\t<MemberSince>" . escapeXml($row['MemberSince']) . "</MemberSince>\n"; 
    		$display_block .= "\t\t<parentId>" . convertNull($row['ParentID']) . "</parentId>\n"; 
    		$display_block .= "\t\t<year>" . $row['Year'] . "</year>\n"; 
    		$display_block .= "\t\t<active>" . $row['Active'] . "</active>\n"; 
    		$display_block .= "\t\t<membershipType>" . escapeXml($row['MembershipType']) . "</membershipType>\n"; 
    		$display_block .= "\t\t<amount>" . escapeXml($row['Amount']) . "</amount>\n"; 
    		$display_block .= "\t\t<child>" . $row['Child'] . "</child>\n"; 
    		$display_block .= "\t\t<voting>" . escapeXml($row['Voting']) . "</voting>\n"; 
             
    		$display_block .= "\t\t<firstName>" . escapeXml($row['FirstName']) . "</firstName>\n"; 
    		$display_block .= "\t\t<lastName>" . escapeXml($row['LastName']) . "</lastName>\n"; 
    		$display_block .= "\t\t<phone>" . escapeXml($row['Phone']) . "</phone>\n"; 
    		$display_block .= "\t\t<email>" . escapeXml($row['Email']) . "</email>\n"; 
    		$display_block .= "\t\t<streetAddr>" . escapeXml($row['StreetAddress']) . "</streetAddr>\n"; 
    		$display_block .= "\t\t<mailingAddr>" . escapeXml($row['MailingAddress']) . "</mailingAddr>\n"; 
    		$display_block .= "\t\t<city>" . escapeXml($row['City']) . "</city>\n"; 
    		$display_block .= "\t\t<province>" . escapeXml($row['Province']) . "</province>\n"; 
    		$display_block .= "\t\t<postalCode>" . escapeXml($row['PostalCode']) . "</postalCode>\n"; 
    		$display_block .= "\t\t<country>" . escapeXml($row['Country']) . "</country>\n"; 
    		$display_block .= "\t\t<kmcMedia>" . escapeXml($row['KmcNewsletter']) . "</kmcMedia>\n"; 
    		$display_block .= "\t\t<fmcbcMedia>" . escapeXml($row['FmcbcNewsletter']) . "</fmcbcMedia>\n"; 
    		$display_block .= "\t\t<privateName>" . $row['PrivateName'] . "</privateName>\n"; 
    		$display_block .= "\t\t<privateEmail>" . $row['PrivateEmail'] . "</privateEmail>\n"; 
    		$display_block .= "\t\t<privatePhone>" . $row['PrivatePhone'] . "</privatePhone>\n"; 
    		$display_block .= "\t\t<privateCity>" . $row['PrivateCity'] . "</privateCity>\n"; 
    		$display_block .= "\t\t<paymentMethod>" . $row['PaymentMethod'] . "</paymentMethod>\n"; 
    		$display_block .= "\t\t<paymentStatus>" . $row['PaymentStatus'] . "</paymentStatus>\n"; 
    		$display_block .= "\t</member>\n"; 
    		debug_log($display_block);
		} 
		return $display_block;
	}
	
	function db_delete_members($id)
	{
		debug_log("db_delete_members: " . $id);
		$sql = "DELETE FROM Member WHERE MemberID = " . $id . " OR ParentID = " . $id;
		$res = mysql_query($sql);
		if (!$res)
		{
		 	debug_log("db_delete_members: DELETE failed: SQL: " . $sql . " Error: " . mysql_error());
			die("db_delete_members: DELETE failed: SQL: " . $sql . " Error: " . mysql_error());
		}		
	}
	
	function db_paypal_successful($id)
	{
		debug_log('db_paypal_successful: ' . $id);
		$sql = "UPDATE Member  SET Active = 1, PaymentStatus = 'PayPal Complete' WHERE MemberID = " . $id . " OR ParentID = " . $id;
		$res = mysql_query($sql);
		if (!$res)
		{
		 	debug_log("db_paypal_successful: DELETE failed: SQL: " . $sql . " Error: " . mysql_error());
			die("db_paypal_successful: DELETE failed: SQL: " . $sql . " Error: " . mysql_error());
		}	
		// Add the email address to List Server
		add_email_to_listsvr(db_get_email($id));
	}
	
	function db_clean_paypal_failures()
	{
		$sql = "DELETE FROM Member WHERE Active = 0 AND PaymentStatus IN ('PayPal Pending', 'PayPal Cancelled') AND Inserted < (DATE_SUB(NOW(), INTERVAL 30 MINUTE))";
		//debug_log('db_clean_paypal_failures: ' . $sql);
		$res = mysql_query($sql);
		if (!$res)
		{
		 	debug_log("db_clean_paypal_failures: DELETE failed: SQL: " . $sql . " Error: " . mysql_error());
			die("db_clean_paypal_failures: DELETE failed: SQL: " . $sql . " Error: " . mysql_error());
		}	
		debug_log("Deleted " . mysql_affected_rows() . " PayPal Pending / Cancelled rows");
	}
	
	function db_get_email($id)
	{
		$sql = "SELECT Email FROM Member WHERE MemberId = " . $id;
		$row = db_select_single_row($sql);
		return $row['Email'];
	}

	function escapeXml($text)
	{
//		debug_log("escapeXml: " . $text);
		return htmlspecialchars($text, ENT_QUOTES);
	}
	
	function convertNull($text)
	{
		if (strcasecmp($text,"null") == 0)
			return "";
		return $text;
	}
	
	function db_select_single_row($sql)
	{
		//debug_log("db_select_single_row: " . $sql);
		$res = mysql_query($sql);
		if (!$res)
		{
		 	debug_log("db_select_single_row: SELECT failed: SQL: " . $sql . " Error: " . mysql_error());
			die("db_select_single_row: SELECT failed: SQL: " . $sql . " Error: " . mysql_error());
		}		
		if (mysql_num_rows($res) == 0)
		{
		 	debug_log("db_select_single_row: SELECT returned 0 rows: SQL: " . $sql);
			die("db_select_single_row: SELECT returned 0 rows: SQL: " . $sql);
		}		
    	return(mysql_fetch_assoc($res)); 
	}
	
	function db_select_rows($sql)
	{
		debug_log("db_select_rows: " . $sql);
		$res = mysql_query($sql);
		if (!$res)
		{
		 	debug_log("db_select_rows: SELECT failed: SQL: " . $sql . " Error: " . mysql_error());
			die("db_select_rows: SELECT failed: SQL: " . $sql . " Error: " . mysql_error());
		}		
		if (mysql_num_rows($res) == 0)
		{
		 	debug_log("db_select_rows: SELECT returned 0 rows: SQL: " . $sql);
		}		
    	return($res); 
	}
	
	function db_update_year($year)
	{
		$update_sql = "UPDATE MemberConfig SET Year = " . $year . " WHERE Id = 1";
		$res = mysql_query($update_sql);
		if (!$res)
		{
		 	debug_log("db_update_year: UPDATE  Year failed: SQL: " . $update_sql . " Error: " . mysql_error());
			die("db_update_year: UPDATE  Year failed: SQL: " . $update_sql . " Error: " . mysql_error());
		}
	}
	
	function db_update_password($user, $pw1, $pw2, $force)
	{
		$update_sql = "UPDATE MemberAdmin SET Password = '" . $pw2 . "' WHERE UserId = '" . $user . "' AND Password = '" . $pw1 . "'";
		if ($force)
			$update_sql = "UPDATE MemberAdmin SET Password = '" . $pw2 . "' WHERE UserId = '" . $user . "'";
		debug_log("db_update_password: " . $update_sql);
		$res = mysql_query($update_sql);
		debug_log("db_update_password: Res: " . $res);
		if (!$res)
		{
		 	debug_log("db_update_password: UPDATE  Password failed: SQL: " . $update_sql . " Error: " . mysql_error());
			die("db_update_password: UPDATE  Password failed: SQL: " . $update_sql . " Error: " . mysql_error());
		}
		$rows = mysql_affected_rows();
		if ($rows != 1)
		{
		 	debug_log("db_update_password: UPDATE  Password failed: 0 rows affected: " . $update_sql);
			die("db_update_password: UPDATE  Password failed: 0 rows affected: " . $update_sql);
		}
	}
	
	function build_success_status()
	{
		return build_status('SUCCESS', '');
	}
	
	function build_fail_status($msg)
	{
		return build_status('FAIL', $msg);
	}
	
	function build_status($status, $msg)
	{
		$display_block = '<?xml version="1.0"?>';
		$display_block .= '<php_result><status>' . $status . '</status>';
		if ($msg != '')
		$display_block .= '<message>' . $msg . '</message>';
		$display_block .= '</php_result>';
		return $display_block;
	}
          
		
	function html_kmc_logo()
	{
		$display_block = "<table CELLSPACING='0' CELLPADDING='0' BORDER='0' WIDTH='100%'><tr>";
		$display_block .= "<td  valign='top' height='0%' width='160'>";
		$display_block .= "<img SRC='../images/kmclogo.jpg' ALT='picture' BORDER='0' height='80' width='160' align='left' 'hspace='0' vspace='0'>";
		$display_block .= "</td>";
		$display_block .= "<td>";
		$display_block .= "<p><a href='http://www.kootenaymountaineeringclub.ca/'>Return to Kootenay Mountaineering Club main page</a></p>";
		$display_block .= "</td></tr></table><br>";
		return $display_block;
	}
	
	function add_email_to_listsvr($email)
	{
		if ($email == "")
		return;
		$cmd = '/usr/local/bin/ezmlm-sub /home/kmc/MEMBERS ' . $email;
		debug_log("add_email_to_listsvr: " . $cmd);
		exec($cmd);
	}
	
	function remove_email_from_listsvr($email)
	{
		if ($email == "")
			return;
		$cmd = '/usr/local/bin/ezmlm-unsub /home/kmc/MEMBERS ' . $email;
		debug_log("remove_email_from_listsvr: " . $cmd);
		exec($cmd);
	}
	
?>
