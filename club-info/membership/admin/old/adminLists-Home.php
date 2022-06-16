<?php

	// setUp
	
	require_once 'constants.php';
	require_once 'util.php';

	// who could be doing the serving?
	
	$HomeServer = 'localhost';
	$KMCServer = 'kootenaymountaineeringclub.ca';

	// in this case, pick one
	
	// $serverName = $HomeServer;
	 $serverName = $KMCServer;
	
	// in either case, the files go into the same web directory
	
	$url = 'http://' . $serverName . '/club-info/documents/executive-files/' ;

	date_default_timezone_set('America/Los_Angeles');

	// where where could this be happening in terms of file system path?
	
	$HomePath = "/Users/Shared/KMCWeb/";
	$KMCPath = "/home/kmc/kootenaymountaineering.bc.ca/";
	
	// in this case, pick one
	
	// $WorkingPath = $HomePath;
  $WorkingPath = $KMCPath;
	
	// open all the files
	
	$fp_members = fopen( $WorkingPath . "club-info/documents/executive-files/members.csv", "w");
	$fp_contacts = fopen( $WorkingPath . "club-info/documents/executive-files/contacts.csv", "w");
	$fp_kmc_paper = fopen( $WorkingPath . "club-info/documents/executive-files/kmc_paper.csv", "w");
	$fp_kmc_electronic = fopen( $WorkingPath . "club-info/documents/executive-files/kmc_electronic.csv", "w");
	$fp_fmcbc_paper = fopen( $WorkingPath . "club-info/documents/executive-files/fmcbc_paper.csv", "w");
	$fp_fmcbc_electronic = fopen( $WorkingPath . "club-info/documents/executive-files/fmcbc_electronic.csv", "w");
	$fp_lstsrv = fopen( $WorkingPath . "club-info/documents/executive-files/lstsrv.txt", "w");
	$fp_emails = fopen( $WorkingPath . "club-info/documents/executive-files/emailaddr.txt", "w");

	// has this ever failed?

	if (!$fp_emails or !$fp_lstsrv or !$fp_fmcbc_electronic or !$fp_fmcbc_paper
		or !$fp_kmc_electronic or !$fp_kmc_paper or !$fp_contacts or !$fp_members )
	{
		debug_log("adminLists.php: Cannot open one of the files");
		die("adminLists.php: Cannot open one of the files");
	}

	// CSV field name rows
	
	$hdg_members = "Last Name, First Name, Year, Joined, Membership Type, Voting, Active, Private Name, Street Address, Mailing Address, City, Province, Postal Code, Country, Email, Phone, KMC Newsletter, FMCBC Newsletter, Private City, Private Email, Private Phone, Child, Payment Method, Payment Status, Amount, Member ID, Parent ID\n";
	fwrite($fp_members, $hdg_members);  

	$hdg_contacts = "Last Name, First Name, Street Address, Mailing Address, City, Province, Postal Code, Country, Email, Phone \n";
	fwrite($fp_contacts, $hdg_contacts);  

	$hdg_kmc_paper = "Last Name, First Name, Street Address, Mailing Address, City, Province, Postal Code, Country\n";
	fwrite($fp_kmc_paper, $hdg_kmc_paper);  

	$hdg_kmc_electronic = "Last Name, First Name, Email\n";
	fwrite($fp_kmc_electronic, $hdg_kmc_electronic);  

	$hdg_fmcbc_paper = "Last Name, First Name, Street Address, Mailing Address, City, Province, Postal Code, Country\n";
	fwrite($fp_fmcbc_paper, $hdg_fmcbc_paper);  

	$hdg_fmcbc_electronic = "Last Name, First Name, Email\n";
	fwrite($fp_fmcbc_electronic, $hdg_fmcbc_electronic);
	
	// get the membership year select from util.php
	
	$YearSelect = MembershipYearSelect();
	
	// connect to database in util.php
	
	db_connect();
	
	// the sql select
	
	$sql = "SELECT * FROM kmc.Member WHERE " . $YearSelect . " ORDER BY LastName, FirstName, Year";

	// do the selection
	
	$rows = db_select_rows($sql);
	
	// go through everything found
	
	for($x = 0 ; $x < mysql_num_rows($rows) ; $x++)
	{
		$row = mysql_fetch_assoc($rows);
		
		// if ($x == 0) print_r($row);

		$active = $row['Active'];

		write_member($row, $fp_members);
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

	// close all the files
	
	fclose($fp_members);
	fclose($fp_contacts);
	fclose($fp_kmc_paper);
	fclose($fp_kmc_electronic);
	fclose($fp_fmcbc_paper);
	fclose($fp_fmcbc_electronic);
	fclose($fp_lstsrv);
	fclose($fp_emails);

	// file writing functions 
	
 	function write_member($row, $fp)
	{
		$t = "";
		$t .= encode_csv($row['LastName']);
		$t .= ',' . encode_csv($row['FirstName']);
		$t .= ',' . $row['Year'];
		$t .= ',' . encode_csv($row['Inserted']);
		$t .= ',' . encode_csv($row['MembershipType']);
		$t .= ',' . bool_to_text($row['Voting']);
		$t .= ',' . bool_to_text($row['Active']);
		$t .= ',' . bool_to_text($row['PrivateName']);
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
		$t .= ',' . bool_to_text($row['PrivateCity']);
		$t .= ',' . bool_to_text($row['PrivateEmail']);
		$t .= ',' . bool_to_text($row['PrivatePhone']);
		$t .= ',' . bool_to_text($row['Child']);
		$t .= ',' . encode_csv($row['PaymentMethod']);
		$t .= ',' . encode_csv($row['PaymentStatus']);
		$t .= ',' . $row['Amount'];
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
			$t .= encode_csv($row['LastName']);
			$t .= ',' . encode_csv($row['FirstName']);
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
			$t .=  encode_csv($row['LastName']);
			$t .= ',' . encode_csv($row['FirstName']);
			$t .= ',' . encode_csv($row['Email']);			
			$t .= "\n";
			fwrite($fp, $t);  	
		}
	}
	
	function write_kmc_paper($row, $fp)
	{
		$t = "";
		$t .=  encode_csv($row['LastName']);
		$t .= ',' . encode_csv($row['FirstName']);
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
			$t .=  encode_csv($row['LastName']);
			$t .= ',' . encode_csv($row['FirstName']);
			$t .= ',' . encode_csv($row['Email']);
			
			$t .= "\n";
			fwrite($fp, $t);  	
		}
	}
	
	function write_fmcbc_paper($row, $fp)
	{
		$t = "";
		$t .=  encode_csv($row['LastName']);
		$t .= ',' . encode_csv($row['FirstName']);
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
	
?>
	
<!DOCTYPE html>
<html>
<head>
<!--#include virtual=/includes/head-first.incl.html -->

	<title>Membership Admin Lists</title>
	
<!--#include virtual=/includes/head-2nd.incl.html -->
	<link rel="stylesheet" type="text/css" href="/css/membership-styles.css">

</head>
<body>

<div id="master">

	<header>

		<!--#include virtual=/includes/header-contents.incl.html -->
	
	</header>

	<div id="content">

		<section>
		
			<h2>Membership Lists</h2>
			
			<p class="centered">Membership lists for the current date have been created.<br>
			List files are located on KICS server at our web directory /club-info/documents/executive-files/.</p>
			<hr>
			
<?php
	echo '<ul><li><a href="' . $url . 'members.csv">Full membership list</a>';
	echo  '</li>';
	echo  '<li><a href="' . $url . 'contacts.csv">Contacts list</a>';
	echo  '</li>';
	echo  '<li><a href="' . $url . 'kmc_paper.csv">KMC newsletter paper list</a>';
	echo  '</li>';
	echo  '<li><a href="' . $url . 'kmc_electronic.csv">KMC newsletter electronic list</a>';
	echo  '</li>';
	echo  '<li><a href="' . $url . 'fmcbc_paper.csv">FMCBC newsletter paper list</a>';
	echo  '</li>';
	echo  '<li><a href="' . $url . 'fmcbc_electronic.csv">FMCBC newsletter electronic list</a>';
	echo  '</li>';
	echo  '<li><a href="' . $url . 'lstsrv.txt">List server update file</a>';
	echo  '</li>';
	echo  '<li><a href="' . $url . 'emailaddr.txt">Email list for list server update</a>';
	echo  '</li></ul>';
?>

			
		</section>
	
	</div> <!-- end content -->

	<footer>
			<hr>
	</footer>

</div> <!-- end master -->

</body>
</html>