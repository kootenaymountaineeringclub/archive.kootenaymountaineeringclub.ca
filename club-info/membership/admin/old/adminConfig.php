<?php
	header("Content-type: text/xml");
	require_once 'util.php';

	session_start ();
	debug_log('In adminConfig.php');
	$admin = false;
	if (isset($_REQUEST['admin']))
		$admin = true;
	db_connect();
	if ($admin)
		$sql = "SELECT Year, SinglePrice, CouplePrice, JuniorPrice, ExtraPersonPrice FROM MemberConfig WHERE Id = 1";
	else
		$sql = "SELECT Year, SinglePrice, CouplePrice, JuniorPrice, ExtraPersonPrice, PriceNote, ResidencyNote, AgeNote, PayPalNote FROM MemberConfig WHERE Id = 1";
	$row = db_select_single_row($sql);
	$display_block = "<pricedata>\n";
	$display_block .= "\t<year>" . $row['Year'] . "</year>";
	$display_block .= "\t<single>" . $row['SinglePrice'] . "</single>\n";
	$display_block .= "\t<couple>" . $row['CouplePrice'] . "</couple>\n";
	$display_block .= "\t<junior>" . $row['JuniorPrice'] . "</junior>\n";
	$display_block .= "\t<extra>" . $row['ExtraPersonPrice'] . "</extra>\n";
	if (!$admin)
	{
		$display_block .= "\t<price_note>" . escapeXml($row['PriceNote']) . "</price_note>\n";
		$display_block .= "\t<residency_note>" . escapeXml($row['ResidencyNote']) . "</residency_note>\n";
		$display_block .= "\t<age_note>" . escapeXml($row['AgeNote']) . "</age_note>\n";
		$display_block .= "\t<paypal_note>" . escapeXml($row['PayPalNote']) . "</paypal_note>\n";
	}
	$display_block .= "</pricedata>\n";
	echo $display_block;
?>