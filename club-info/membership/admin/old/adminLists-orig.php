<?php
require_once 'constants.php';
require_once 'util.php';

	session_start ();
	debug_log('In adminLists.php');
	if (!isset($_REQUEST['year']))
	{
		debug_log('adminLists.php: year not set');
		die('adminLists.php: year not set');
	}
	$year = $_REQUEST['year'];
	$serverName = $_SERVER ['SERVER_NAME'];
	$serverPort = $_SERVER ['SERVER_PORT'];
	$url = 'http://' . $serverName . ':' . $serverPort . '/enrollment/data/' ;
	debug_log('adminLists.php: URL ' . $url);
			date_default_timezone_set('America/Los_Angeles');
	$fp_members = fopen("../data/members.csv", "w");
	if (!$fp_members)
	{
		debug_log("adminLists.php: Cannot open /data/members.csv file");
		die("adminLists.php: Cannot open /data/members.csv file");
	}
	$fp_contacts = fopen("../data/contacts.csv", "w");
	if (!$fp_contacts)
	{
		debug_log("adminLists.php: Cannot open /data/contacts.csv file");
		die("adminLists.php: Cannot open /data/contacts.csv file");
	}
	$fp_kmc_paper = fopen("../data/kmc_paper.csv", "w");
	if (!$fp_kmc_paper)
	{
		debug_log("adminLists.php: Cannot open /data/kmc_paper.csv file");
		die("adminLists.php: Cannot open /data/kmc_paper.csv file");
	}
	$fp_kmc_electronic = fopen("../data/kmc_electronic.csv", "w");
	if (!$fp_kmc_electronic)
	{
		debug_log("adminLists.php: Cannot open /data/kmc_electronic.csv file");
		die("adminLists.php: Cannot open /data/kmc_electronic.csv file");
	}
	$fp_fmcbc_paper = fopen("../data/fmcbc_paper.csv", "w");
	if (!$fp_fmcbc_paper)
	{
		debug_log("adminLists.php: Cannot open /data/fmcbc_paper.csv file");
		die("adminLists.php: Cannot open /data/fmcbc_paper.csv file");
	}
	$fp_fmcbc_electronic = fopen("../data/fmcbc_electronic.csv", "w");
	if (!$fp_fmcbc_electronic)
	{
		debug_log("adminLists.php: Cannot open /data/fmcbc_electronic.csv file");
		die("adminLists.php: Cannot open /data/fmcbc_electronic.csv file");
	}
	$fp_lstsrv = fopen("../data/lstsrv.txt", "w");
	if (!$fp_lstsrv)
	{
		debug_log("adminLists.php: Cannot open /data/lstsrv.txt file");
		die("adminLists.php: Cannot open /data/lstsrv.txt file");
	}
	$fp_emails = fopen("../data/emailaddr.txt", "w");
	if (!$fp_emails)
	{
		debug_log("adminLists.php: Cannot open /data/emailaddr.txt file");
		die("adminLists.php: Cannot open /data/emailaddr.txt file");
	}
	$hdg_members = "Active, MembershipType, First Name, Last Name, Street Address, Mailing Address, City, Province, Postal Code, Country, Email, Phone, Kmc Newsletter, PMCBC Newsletter, Private Name, Private City, Private Email, Private Phone, Voting, Child, Amount, Payment Type, Payment Status, Enrollment Type, Year, ID, Parent ID\n";
	fwrite($fp_members, $hdg_members);  
	$hdg_contacts = "First Name, Last Name, Street Address, Mailing Address, City, Province, Postal Code, Country, Email, Phone \n";
	fwrite($fp_contacts, $hdg_contacts);  
	$hdg_kmc_paper = "First Name, Last Name, Street Address, Mailing Address, City, Province, Postal Code, Country\n";
	fwrite($fp_kmc_paper, $hdg_kmc_paper);  
	$hdg_kmc_electronic = "First Name, Last Name, Email\n";
	fwrite($fp_kmc_electronic, $hdg_kmc_electronic);  
	$hdg_fmcbc_paper = "First Name, Last Name, Street Address, Mailing Address, City, Province, Postal Code, Country\n";
	fwrite($fp_fmcbc_paper, $hdg_fmcbc_paper);  
	$hdg_fmcbc_electronic = "First Name, Last Name, Email\n";
	fwrite($fp_fmcbc_electronic, $hdg_fmcbc_electronic);  
	db_connect();
	$sql = "SELECT * FROM Member WHERE Year = " . $year;
	$rows = db_select_rows($sql);
	for($x = 0 ; $x < mysql_num_rows($rows) ; $x++)
	{ 
		$row_members = '';
		$row = mysql_fetch_assoc($rows); 
		$active = $row['Active'];
		write_member($row, $fp_members);
		if ($active)
		{
			write_contact($row, $fp_contacts);
			if ($row['KmcNewsletter'] == 'Electronic')
				write_kmc_electronic($row, $fp_kmc_electronic);
			if ($row['KmcNewsletter'] == 'Paper')
				write_kmc_paper($row, $fp_kmc_paper);
			if ($row['FmcbcNewsletter'] == 'Electronic')
				write_fmcbc_electronic($row, $fp_fmcbc_electronic);
			if ($row['FmcbcNewsletter'] == 'Paper')
				write_fmcbc_paper($row, $fp_fmcbc_paper);
			write_lstsrv($row, $fp_lstsrv);
			write_emails($row, $fp_emails);
		}
	}
	fclose($fp_members);
	fclose($fp_contacts);
	fclose($fp_kmc_paper);
	fclose($fp_kmc_electronic);
	fclose($fp_fmcbc_paper);
	fclose($fp_fmcbc_electronic);
	fclose($fp_lstsrv);
	fclose($fp_emails);
	
	function write_member($row, $fp)
	{
		$t = "";
		$t .= bool_to_text($row['Active']);
		$t .= ',' . encode_csv($row['MembershipType']);
		$t .= ',' . encode_csv($row['FirstName']);
		$t .= ',' . encode_csv($row['LastName']);
		$t .= ',' . encode_csv($row['StreetAddress']);
		$t .= ',' . encode_csv($row['MailingAddress']);
		$t .= ',' . encode_csv($row['City']);
		$t .= ',' . encode_csv($row['Province']);
		$t .= ',' . encode_csv($row['PostalCode']);
		$t .= ',' . encode_csv($row['Country']);
		$t .= ',' . encode_csv($row['Email']);
		$t .= ',' . encode_csv($row['Phone']);
		$t .= ',' . encode_csv($row['KmcNewsletter']);
		$t .= ',' . encode_csv($row['FmcbcNewsletter']);
		$t .= ',' . bool_to_text($row['PrivateName']);
		$t .= ',' . bool_to_text($row['PrivateCity']);
		$t .= ',' . bool_to_text($row['PrivateEmail']);
		$t .= ',' . bool_to_text($row['PrivatePhone']);
		$t .= ',' . bool_to_text($row['Voting']);
		$t .= ',' . bool_to_text($row['Child']);
		$t .= ',' . $row['Amount'];
		$t .= ',' . encode_csv($row['PaymentType']);
		$t .= ',' . encode_csv($row['PaymentStatus']);
		$t .= ',' . encode_csv($row['Enrollment']);
		$t .= ',' . $row['Year'];
		$t .= ',' . $row['MemberID'];
		$t .= ',' . $row['ParentID'];

		$t .= "\n";
		fwrite($fp, $t);  		
	}
	
	function write_contact($row, $fp)
	{
		if ($row['PrivateName'] == 0)
		{
			$t = "";
			$t .= encode_csv($row['FirstName']);
			$t .= ',' . encode_csv($row['LastName']);
			if ($row['PrivateCity'] == 0)
			{
				$t .= ',' . encode_csv($row['StreetAddress']);
				$t .= ',' . encode_csv($row['MailingAddress']);
				$t .= ',' . encode_csv($row['City']);
				$t .= ',' . encode_csv($row['Province']);
				$t .= ',' . encode_csv($row['PostalCode']);
				$t .= ',' . encode_csv($row['Country']);
			}
			else
			{
				$t .= ',';
				$t .= ',';
				$t .= ',';
				$t .= ',';
				$t .= ',';
				$t .= ',';
			}
			
			if ($row['PrivateEmail'] == 0)
			{
				$t .= ',' . encode_csv($row['Email']);
			}
			else
			{
				$t .= ',';
			}
			if ($row['PrivatePhone'] == 0)
			{
				$t .= ',' . encode_csv($row['Phone']);
			}
				else
			{
				$t .= ',';
			}

		$t .= "\n";
			fwrite($fp, $t);  	
		}
	}
	
	function write_kmc_electronic($row, $fp)
	{
		if ($row['Email'] != "")
		{
			$t = "";
			$t .=  encode_csv($row['FirstName']);
			$t .= ',' . encode_csv($row['LastName']);
			$t .= ',' . encode_csv($row['Email']);			
			$t .= "\n";
			fwrite($fp, $t);  	
		}
	}
	
	function write_kmc_paper($row, $fp)
	{
		$t = "";
		$t .=  encode_csv($row['FirstName']);
		$t .= ',' . encode_csv($row['LastName']);
		$t .= ',' . encode_csv($row['StreetAddress']);
		$t .= ',' . encode_csv($row['MailingAddress']);
		$t .= ',' . encode_csv($row['City']);
		$t .= ',' . encode_csv($row['Province']);
		$t .= ',' . encode_csv($row['PostalCode']);
		$t .= ',' . encode_csv($row['Country']);
		
		$t .= "\n";
		fwrite($fp, $t);  		
	}
	
	function write_fmcbc_electronic($row, $fp)
	{
		if ($row['Email'] != "")
		{
			$t = "";
			$t .=  encode_csv($row['FirstName']);
			$t .= ',' . encode_csv($row['LastName']);
			$t .= ',' . encode_csv($row['Email']);
			
			$t .= "\n";
			fwrite($fp, $t);  	
		}
	}
	
	function write_fmcbc_paper($row, $fp)
	{
		$t = "";
		$t .=  encode_csv($row['FirstName']);
		$t .= ',' . encode_csv($row['LastName']);
		$t .= ',' . encode_csv($row['StreetAddress']);
		$t .= ',' . encode_csv($row['MailingAddress']);
		$t .= ',' . encode_csv($row['City']);
		$t .= ',' . encode_csv($row['Province']);
		$t .= ',' . encode_csv($row['PostalCode']);
		$t .= ',' . encode_csv($row['Country']);
		
		$t .= "\n";
		fwrite($fp, $t);  		
	}
	
	function write_lstsrv($row, $fp)
	{
		if ($row['Email'] != "")
		{
			$t = "";
			$t .=  'ezmlm-sub . ' . $row['Email'];
			
			$t .= "\n";
			fwrite($fp, $t);  
		}
	}
	
	function write_emails($row, $fp)
	{
		if ($row['Email'] != "")
		{
			$t = "";
			$t .=   $row['Email'];		
			$t .= "\n";
			fwrite($fp, $t);   
		}
	}
	
	function bool_to_text($b)
	{
		if ($b == 1)
			return 'true';
		return 'false';
	}
	
	function encode_csv($t)
	{
		if ($t == "")
			return $t;
		$c = trim($t);
		$c = str_replace('"', '', $c);
		if (!strpos($c, ','))
			return $c;
		return '"' . $c . '"';
	}
	$display_block = '<html>';
	$display_block .= '<head>';
	$display_block .= '<title>Membership Lists</title>';
	$display_block .= '</head>';
	$display_block .= '<body>';
	$display_block .= '<br>';
	$display_block .= '<div id="header"><img src="http://www.kootenaymountaineering.bc.ca/images/kmclogo.jpg" border="0" class="logo" alt="Kootenay 	Mountaineering Club"></div>';
	$display_block .= '<br>';
	$display_block .= '<hr>';
	$display_block .= '<br>';
	$display_block .= '<h1>Membership lists for ' . $year . ' have been created.</h1>';
	$display_block .= '<hr>';
	$display_block .= '<h2>List files are located on KICS server at our web directory /enrollment/data/.</h2>';
	$display_block .= '<a href="' . $url . 'members.csv">Full membership list</a>';
	$display_block .= '<br>';
	$display_block .= '<a href="' . $url . 'contacts.csv">Contacts list</a>';
	$display_block .= '<br>';
	$display_block .= '<a href="' . $url . 'kmc_paper.csv">KMC newsletter paper list</a>';
	$display_block .= '<br>';
	$display_block .= '<a href="' . $url . 'kmc_electronic.csv">KMC newsletter electronic list</a>';
	$display_block .= '<br>';
	$display_block .= '<a href="' . $url . 'fmcbc_paper.csv">FMCBC newsletter paper list</a>';
	$display_block .= '<br>';
	$display_block .= '<a href="' . $url . 'fmcbc_electronic.csv">FMCBC newsletter electronic list</a>';
	$display_block .= '<br>';
	$display_block .= '<a href="' . $url . 'lstsrv.txt">List server update file</a>';
	$display_block .= '<br>';
	$display_block .= '<a href="' . $url . 'emailaddr.txt">Email list for list server update</a>';
	$display_block .= '</body>';
	$display_block .= '</html>';
	echo $display_block;

?>
