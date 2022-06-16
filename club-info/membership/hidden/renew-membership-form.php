<!DOCTYPE html>
<html>
<head>
	<?php
		require ("util.php");
		
		include (PHP_ROOT . "/includes/head-first.incl.html") ;
	?>

	<title>Membership Admin: Edit member information</title>
	
	<?php include (PHP_ROOT . "/includes/head-2nd.incl.html") ; ?>
	
	<?php include (PHP_ROOT . "/js/header-pictures.js") ; ?>
	
	<link rel="stylesheet" type="text/css" href="/css/membership-styles.css">
	<script src="form-util.php"></script>

</head>
<body>

<div id="master">

	<header>

	<?php
		include ("../../includes/page-header-club.incl.html") ;
		include (PHP_ROOT . "/includes/header-contents.incl.html") ;
	?>
	
	</header>

	<div id="content">

	<?php include ("../includes/club-membership.incl.html") ; ?>	
	
	<section>

	<?php
//		echo "<p>" . print_r($_POST) . "</p>";
//		echo "<p>" . print_r($_GET) . "</p>";
		
		$bogus = 1;
		$is_link = 0;
		$is_id = 0;
		
		$incoming = $_POST['numb'];
		
		if ($incoming == "") {
			$incoming = $_GET['link'];
			$is_link = 1;
		} else {
			$is_id = 1;
		}
			
//		echo "<p>" . $incoming . "</p>\n";
		
		if ( $is_link && (12 == strlen($incoming))) {
			$bogus = 0;
		} elseif ($is_id && is_numeric($incoming) && ($incoming > 100000) && ($incoming <= 999999)) {
			$bogus = 0;
		}
		
//		echo "<p>is link:" . $is_link . "</p>\n";
//		echo "<p>is id:" . $is_id . "</p>\n";
//		echo "<p>bogus:" . $bogus . "</p>\n";
		
		if ($bogus) {
			echo "<p>There seemed to be something wrong with the incoming request.</p>";
					die;
		}
		
		db_connect();
		
		if ($is_link) {
			$link = $incoming;
			$sql = "select MembershipID from RenewLinks where Link = '" . $incoming . "'";
			$res = mysql_query($sql);
			if ( mysql_num_rows($res) == 0 ) {
				echo "<p>Nothing found for that request.</p>\n";
						die;
			} else {
				$incoming = mysql_fetch_assoc($res);
//				print_r($incoming);
				$incoming = $incoming["MembershipID"];
			}
			// Delete the link
			$sql = "DELETE from RenewLinks where Link = '" . $link . "'";
			$res = mysql_query($sql);
		}
				
		$sql = "select TransactionID, MembershipID, Year, MembershipType, FirstName, NickName, LastName, DistinctName, StreetAddress, StreetAddress2, City, Province, PostalCode, Country, Email, Phone, KmcNewsletter, FmcbcNewsletter, PrivateName, Inserted from Member where MembershipID = " . $incoming ;
		
		$res = mysql_query($sql);
		if (!$res)
		{
			die("db_FindMembers: SELECT  Member failed: SQL: " . $sql . " Error: " . mysql_error());
		}
			
		if ( mysql_num_rows($res) == 0 ) {
			die("<p>Nothing found for " . $incoming . "</p>\n");
		} else {
			
			$count = mysql_num_rows($res);
		
			$name1 = mysql_fetch_assoc($res);
			if ($count > 1) $name2 = mysql_fetch_assoc($res);
		}
				
		
	?>
			
	<h2>Kootenay Mountaineering Club<br>Membership Renewal Form</h2>
	
	<p class="requiredlabels">All fields labeled in red are required.</p>
	
	<form method="post" action="checkout.php">
				
			<input type="text" hidden id="MembershipID" name="MembershipID" value="<?php echo $incoming;?>">
				
	<?php	echo "<h2>Membership renewal for membership number " . $incoming . "</h2>" ; ?>	
	
			<div class="formPart" id="type">
				<p class="formHeader">Membership Type:</p>
				<div class="formItem">
					<label for="MembType">Individual : </label><input type="radio" id="MembType" name="MembType" value="Individual"<?php
		
		
		if ($count == 1) echo " checked"?>  onclick="hide2()">
					<label for="MembType">Couple : </label><input type="radio" id="MembType" name="MembType" value="Couple"<?php
		if ($count > 1) echo " checked"?>  onclick="show2()"><br>
					<p class="centered">Membership price : <span id="MembPrice"><?php
		if ($count == 1) {
			echo CONFIG_INDIVIDUAL;
		} else { 
			echo CONFIG_COUPLE;
		} ;?></span></p>
				</div>
			</div>
			
			<div class="formPart" id="name1">
				<p class="formHeader">Name:<input type="text" hidden name="trans_id1" id="trans_id1" value="<?php echo $name1['TransactionID']?>"</p>
				<div class="formItem">
					<label class="required" for="FirstName1">First name : </label>
					<input class="text"  type="text" id="FirstName1" name="FirstName1" value="<?php echo $name1['FirstName']?>"><br>
					<label for="NickName1">Initial or nickname? : </label>
					<input class="text"  type="text" name="NickName1" value="<?php echo $name1['NickName']?>"><br>
					<label class="required" for="LastName1">Last name : </label>
					<input class="text"  type="text" id="LastName1" name="LastName1" value="<?php echo $name1["LastName"]?>"><br>
					<label class="required" for="Email1">Email address : </label>
					<input class="text"  type="email" id="Email1" name="Email1" value="<?php echo $name1['Email']?>"><br>
					<label for="Phone1">Phone : </label>
					<input class="text"  type="text" name="Phone1" value="<?php echo $name1['Phone']?>"><br>
				</div>
			</div>
			
			<div class="formPart<?php if ($count == 1) echo " formHidden" ?>" id="name2">
				<p class="formHeader">Name 2:<input type="text" hidden name="trans_id2" id="trans_id2" value="<?php echo $name2['TransactionID']?>"</p>
				<div class="formItem">
				<?php if ($count == 1) { ?>
					<label class="required" for="FirstName2">First name : </label>
					<input class="text" type="text" id="FirstName2" name="FirstName2"><br>
					<label for="NickName2">Initial or nickname? : </label>
					<input class="text"  type="text" name="NickName2"><br>
					<label class="required" for="LastName2">Last name : </label>
					<input class="text" type="text" id="LastName2" name="LastName2"><br>
					<label for="SharedEmail">Shared Email : </label>
					<input type="checkbox" id="SharedEmail" name="SharedEmail" value="1"> or ...<br>
					<label for="Email2">Email address : </label>
					<input class="text" type="email"  name="Email2"><br>
					<label for="Phone2">Phone : </label>
					<input class="text"  type="text" name="Phone2"><br>
					
				<?php } else { ?>
					<label class="required" for="FirstName2">First name : </label>
					<input class="text" type="text" id="FirstName2" name="FirstName2" value="<?php echo $name2['FirstName']?>"><br>
					<label for="NickName2">Initial or nickname? : </label>
					<input class="text"  type="text" name="NickName2" value="<?php echo $name2['NickName']?>"><br>
					<label class="required" for="LastName2">Last name : </label>
					<input class="text" type="text" id="LastName2" name="LastName2" value="<?php echo $name2['LastName']?>"><br>
					<label for="SharedEmail">Shared Email : </label>
					<input type="checkbox" id="SharedEmail" name="SharedEmail" value="1"> or ...<br>
					<label for="Email2">Email address : </label>
					<input class="text" type="email"  name="Email2" value="<?php echo  $name2['Email']?>"><br>
					<label for="Phone2">Phone : </label>
					<input class="text"  type="text" name="Phone2" value="<?php echo  $name2['Phone']?>"><br>
				 <?php } ?>
						
				</div>
			</div>
			
			<div class="formPart" id="address">
				<p class="formHeader">Mailing Address:</p>
				<div class="formItem">
					<label id="LabelAddr1" for="Addr1">First line : </label>
					<input class="text" type="text" id="Addr1" name="Addr1" value="<?php echo $name1['StreetAddress']?>"><br>
					<label id="LabelAddr2" for="Addr2">2nd line (if needed) : </label>
					<input class="text"  type="text" name="Addr2" value="<?php echo $name1['StreetAddress2']?>"><br>
					<label class="required" for="City">City : </label>
					<input class="text" type="text" id="City" name="City" value="<?php echo $name1['City']?>"><br>
					<label id="LabelProv" for="Province">Province/State : </label>
					<input class="text" type="text" id="Province" name="Province" value="<?php echo $name1['Province']?>"><br>
					<label id="LabelPostCode" for="PostCode">Postal code/Zip : </label>
					<input class="text"  type="text" id="PostalCode" name="PostalCode" value="<?php echo $name1['PostalCode']?>"><br>
				</div>
			</div>
			<div class="formPart" id="OptionSelect">
				<p class="formHeader">Options: Private name and Newsletters</p>
				<p>We are required to inform you that the club makes membership names available on two lists. Selecting a "Private Name" prevents your name from being on the membership list available to other members and the list that is sent to area sports equipment stores that offer a 10% discount to KMC members.</p>
				<div class="centered">
					<select size=1 name="private">
						<option value='0'>It is OK to be on the 2 lists</option>
						<option value='1'>Please keep name private, off the 2 lists</option>
					</select>
				</div>
				<hr>
				<p>The KMC newsletters and the FMCBC newsletters are available in electronic format and printed versions. The paper versions of the KMC newsletter will add $5 per issue ($20 for the 4 issues per year) to your membership cost.</p>
				<div class="centered">
					KMC paper:
						<input type="checkbox" id="kmc" name="kmc" value="1" onchange="kmcpaper(); paperaddress();">
						$<input type="text" id="newsmoney" name="newsmoney" size="4" disabled value="0.00">
						&nbsp;&nbsp;&nbsp;&nbsp;
										
					FMCBC paper: <input type="checkbox" id="fmcbc" name="fmcbc" value="1" onchange="paperaddress()";>
					
				</div>
			</div>
			
			<div class="formPart" id="waiverLink">
				<p class="formHeader">Waiver</p>
				<p>Click the waiver link below, read through it carefully, then click the "Agree" button at the bottom of the waiver.</p>
				
				<input type="button" name="WaiverButton" class="formButton" value="View the Waiver" onClick="showWaiver()">
				
				<p id="warning" class=" required centered formHidden">All fields labelled in red are the required minimum information.</p>
				<input type="hidden" id="Agreement" name="Agreement" value="xxx">
				<input type="hidden" id="MembCost" name="MembCost" value="38">
				<input type="hidden" id="Newsletter" name="Newsletter" value="0">
			</div>
			
			<div class="formPart formHidden" id="Waiver">
				<?php include("../Waiver.incl.html") ; ?>
				<span><input type="button" id="Agree" class="formButton" value="I Agree" onClick="go()"></span>
			</div>
			
			<div class="formPart formHidden" id="Go">
				<p class="centered">Before clicking this button, please make sure that at least your email address is correct. In fact, making sure that all of it is correct will make it all much smoother for both of us.</p>
				<span><input type="submit" class="formButton" value="Continue to confirmation and payment"></span>
			</div>
			</form>
		</section>
	
	</div> <!-- end content -->

	<footer>

	<!--#include virtual=includes/club-membership.incl.html -->

	</footer>

</div> <!-- end master -->

</body>
</html>