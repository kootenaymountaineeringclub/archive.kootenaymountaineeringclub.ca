<?php
	header("Content-type: text/xml");
	require_once 'util.php';
	session_start();
	debug_log('In findCoMembers.php');
/*
	if (!isset($_REQUEST['year']))
	{
		debug_log('findCoMembers.php: year not set');
		die('findCoMembers.php: year not set');
	}
	$year = $_REQUEST['year'];
*/
	db_connect();
	debug_log('findCoMembers.php: Connected to database');
	
	$YearSelect = MembershipYearSelect();
	
	$sql = "SELECT * FROM Member ";
	$where1 = $YearSelect . " AND Child = 0 AND ";
	$where2 = $YearSelect . " AND Child = 1 AND ";
	if ($_REQUEST["parentId"] != "")
	{
		$where1 .= " MemberID = " . $_REQUEST["parentId"]; // Couple
		$where2 .= " ParentID = " . $_REQUEST["parentId"]; // Children
	}
	else
	{
		$where1 .= " ParentID = " . $_REQUEST["memberId"]; // Couple
		$where2 .= " ParentID = " . $_REQUEST["memberId"]; // Children
	}
	$partnerRes = db_select_rows($sql . $where1);
	$display_block = '<?xml version="1.0"?>';
	$display_block .= '<php_result>';
	$display_block .= "<status>SUCCESS</status>\n";
	$display_block .= "<members>\n";
	if (mysql_num_rows($partnerRes) == 0)
	{
    	$display_block .= "\t<firstName2></firstName2>\n"; 
    	$display_block .= "\t<lastName2></lastName2>\n"; 
    	$display_block .= "\t<email2></email2>\n";
	}
	else
	{
    	$row = mysql_fetch_assoc($partnerRes); 
    	$display_block .= "\t<firstName2>" . escapeXml($row['FirstName']) . "</firstName2>\n"; 
    	$display_block .= "\t<lastName2>" . escapeXml($row['LastName']) . "</lastName2>\n"; 
    	$display_block .= "\t<email2>" . escapeXml($row['Email']) . "</email2>\n"; 	
    }
    if ($_REQUEST['numChildren'] != "0")
    {
		$kidsRes = db_select_rows($sql . $where2);
		for($x = 0 ; $x < mysql_num_rows($kidsRes) ; $x++) 
		{ 
			$row = mysql_fetch_assoc($kidsRes); 
			$n = strval($x+1);
			$display_block .= ("\t<childFirstName" . $n . ">" . escapeXml($row['FirstName']) . "</childFirstName" . $n . ">\n");
			$display_block .= ("\t<childLastName" . $n . ">" . escapeXml($row['LastName']) . "</childLastName" . $n . ">\n");
		}
	}
    $display_block .= "</members>\n";
    $display_block .= '</php_result>';
    //debug_log($display_block);
    echo $display_block;
?>
