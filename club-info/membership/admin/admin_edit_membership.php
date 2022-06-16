<!DOCTYPE html>
<html>
<head>
	<?php
		include ("../safe/db-util.php") ;
		include (PHP_ROOT . "/includes/head-first.incl.html") ;
	?>

	<title>Membership Admin: Edit member information</title>
	
	<?php include (PHP_ROOT . "/includes/head-2nd.incl.html") ; ?>
	
	<?php include (PHP_ROOT . "/js/header-pictures.js") ; ?>
	
	<link rel="stylesheet" type="text/css" href="/css/membership-styles.css">
	<script src="../php/form-util.php"></script>

</head>
<body>

<div id="master">

	<header>

	<?php
		include ("../../includes/page-header-club.incl.html") ;
		include (PHP_ROOT . "/includes/header-contents.incl.html") ;
		require_once 'admin-util.php';
	?>
	
	</header>

	<div id="content">

	<?php 
		include ("../includes/club-membership.incl.html") ; 
	?>	
	
	<section>
			<p class="centered"><a href="MembAdmin.php">Membership Admin Page</a></p>


			<h2>Kootenay Mountaineering Club<br>Membership Form</h2>
			
			<p class="requiredlabels">All fields labeled in red are required.</p>
			
			<form method="post" action="admin_update_membership.php">
				
			<h2>Editing information for membership number
				
		<?php
			
			echo " " . $_POST['memb_numb'] . "</h2>" ;
			
			echo "\n<input type='text' id='memb_numb' name='memb_numb' class='formHidden' value=\"" . $_POST['memb_numb'] . "\" hidden>\n";
			echo "\n<input type='text' id='trans_id' name='trans_id' class='formHidden' value=\"" . $_POST['trans_id'] . "\" hidden>\n";
			
		?>	
			<div class="formPart" id="type">
			<p class="formHeader">Membership Type:</p>
			<div class="formItem">
				
				
		<?php
			
			$sql = "select TransactionID, MembershipID, Year, MembershipType, AgeRange, FirstName, NickName, LastName, DistinctName, StreetAddress, StreetAddress2, City, Province, PostalCode, Country, Email, Phone, KmcNewsletter, FmcbcNewsletter, PrivateName, Inserted from Member where MembershipID = " . $_POST["memb_numb"];
			
			db_connect();
			
			$res = mysql_query($sql);
			if (!$res)
			{
			 	debug_log("db_FindMembers: SELECT  Member failed: SQL: " . $sql . " Error: " . mysql_error());
				die("db_FindMembers: SELECT  Member failed: SQL: " . $sql . " Error: " . mysql_error());
			}
				
			if ( mysql_num_rows($res) == 0 ) {
				echo "<p>Nothing found for " . $_POST["numb"] . "</p>\n";
				
			} else {
				$count = mysql_num_rows($res);
				$name1 = mysql_fetch_assoc($res);
				if ($count > 1) $name2 = mysql_fetch_assoc($res);
		?>
		
				<p><label for='MembYear'>Membership Year : </label><input type='text' id='MembYear' name='MembYear' size='4' value="<?php echo $name1['Year']?>"></p>

				<label for='MembType'>Individual : </label><input type='radio' id='MembType' name='MembType' value='Individual'<?php if ($count == 1) echo ' checked' ?> onclick='hide2()'>

				<label for='MembType'>Couple : </label><input type='radio' id='MembType' name='MembType' value='Couple'<?php if ($count > 1) echo " checked" ?>  onclick='show2()'><br>

				<p class="centered">Membership price : <span id="MembPrice">
			
			<?php
				if ($count == 1) {
					echo CONFIG_INDIVIDUAL;
				} else { 
					echo CONFIG_COUPLE;
				}
			?>
			</span></p>
			</div>
			
				<div class="formPart" id="name1">

					<?php	$ageRangePopUp = '<select size=1 id="name1_age" name="name1_age"><option value="0">Age range?</option>' .
								'<option value="16">Under 19</option>' .
								'<option value="34">20 thru 39</option>' .
								'<option value="54">40 thru 59</option>' .
								'<option value="61">60 +</option></select>' ;
				
						$ageRangePopUp = str_replace($name1['AgeRange'] . '"' , $name1['AgeRange'] .'"' . ' selected' , $ageRangePopUp) ;
					?>

					<p class="formHeader">Name:<input type="text" hidden name="trans_id1" id="trans_id1" value="<?php echo $name1['TransactionID']?>"</p>
					<div class="formItem">
						<label class="required" for="FirstName1">First name : </label>
						<input class="text"  type="text" id="FirstName1" name="FirstName1" value="<?php echo $name1['FirstName']?>"><br>
						<label for="NickName1">Initial or nickname? : </label>
						<input class="text"  type="text" id="NickName1" name="NickName1" value="<?php echo $name1['NickName']?>"><br>
						<label class="required" for="LastName1">Last name : </label>
						<input class="text"  type="text" id="LastName1" name="LastName1" value="<?php echo $name1["LastName"]?>"><br>
						<label class="required" for="Email1">Email address : </label>
						<input class="text"  type="email" id="Email1" name="Email1" value="<?php echo $name1['Email']?>"><br>
						<label for="Phone1">Phone : </label>
						<input class="text"  type="text" id="Phone1" name="Phone1" value="<?php echo $name1['Phone']?>"><br>
						<label for="name1age">Age Range : </label> <?php echo $ageRangePopUp; ?><br>
					</div>
				</div>
			
				<div class="formPart<?php if ($count == 1) echo " formHidden" ?>" id="name2">
					<p class="formHeader">Name 2:<input type="text" hidden name="trans_id2" id="trans_id2" value="<?php echo $name2['TransactionID']?>"</p>
					<div class="formItem">
					<?php if ($count == 1) { ?>
						<label class="required" for="FirstName2">First name : </label>
						<input class="text" type="text" id="FirstName2" name="FirstName2"><br>
						<label for="NickName2">Initial or nickname? : </label>
						<input class="text"  type="text" id="NickName2" name="NickName2"><br>
						<label class="required" for="LastName2">Last name : </label>
						<input class="text" type="text" id="LastName2" name="LastName2"><br>
						<label for="SharedEmail">Shared Email : </label>
						<input type="checkbox" id="SharedEmail" name="SharedEmail" value="1"> or ...<br>
						<label for="Email2">Email address : </label>
						<input class="text" type="email" id="Email2" name="Email2"><br>
						<label for="Phone2">Phone : </label>
						<input class="text"  type="text" id="Phone2" name="Phone2"><br>
						<label for="name2age">Age Range : </label>
						<select size=1 id="name1age" name="name1age">
							<option value='0'>Age range?</option>
							<option value='19'>Under 19</option>
							<option value='39'>20 thru 39</option>
							<option value='59'>40 thru 59</option>
							<option value='60'>60 +</option>
						</select><br>
					
					<?php } else { 
					
						$age2RangePopUp = '<select size=1 id="name2_age" name="name2_age"><option value="0">Age range?</option>' .
							'<option value="16">Under 19</option>' .
							'<option value="34">20 thru 39</option>' .
							'<option value="54">40 thru 59</option>' .
							'<option value="61">60 +</option></select>' ;
				
						$age2RangePopUp = str_replace($name2['AgeRange'] . '"' , $name2['AgeRange'] .'"' . ' selected' , $age2RangePopUp) ; 	
					?>
					
						<label class="required" for="FirstName2">First name : </label>
						<input class="text" type="text" id="FirstName2" name="FirstName2" value="<?php echo $name2['FirstName']?>"><br>
						<label for="NickName2">Initial or nickname? : </label>
						<input class="text"  type="text" id="NickName2" name="NickName2" value="<?php echo $name2['NickName']?>"><br>
						<label class="required" for="LastName2">Last name : </label>
						<input class="text" type="text" id="LastName2" name="LastName2" value="<?php echo $name2['LastName']?>"><br>
						<label for="SharedEmail">Shared Email : </label>
						<input type="checkbox" id="SharedEmail" name="SharedEmail" value="1"> or ...<br>
						<label for="Email2">Email address : </label>
						<input class="text" type="email" id="Email2" name="Email2" value="<?php echo  $name2['Email']?>"><br>
						<label for="Phone2">Phone : </label>
						<input class="text"  type="text" id="Phone2" name="Phone2" value="<?php echo  $name2['Phone']?>"><br>
						<label for="name2age">Age Range : </label> <?php echo $age2RangePopUp ?><br>
				 <?php } ?>
						
				</div>
			</div>
			
			<div class="formPart" id="address">
				<p class="formHeader">Mailing Address:</p>
				<div class="formItem">
					<label id="LabelAddr1" for="Addr1">First line : </label>
					<input class="text" type="text" id="Addr1" name="Addr1" value="<?php echo $name1['StreetAddress']?>"><br>
					<label id="LabelAddr2" for="Addr2">2nd line (if needed) : </label>
					<input class="text"  type="text" id="Addr2" name="Addr2" value="<?php echo $name1['StreetAddress2']?>"><br>
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
				<?php
					$select = str_replace($name1['PrivateName'] . '"' , $name1['PrivateName'] . '" selected' , '<select size=1 name="private">
						<option value="0">It is OK to be on the 2 lists</option>
						<option value="1">Please keep name private, off the 2 lists</option>
					</select>');
					echo $select ; ?>
					
				</div>
				<hr>
				<p>FMCBC newsletters are available in electronic format and printed versions.</p>
				<div class="centered">
					FMCBC paper: <input type="checkbox" id="fmcbc" name="fmcbc" value="1" onchange="paperaddress()";>
					
				</div>
			</div>
			
			<div class="formPart" id="Go">
				<span><input type="submit" class="formButton" value="Send to database..."></span>
			</div>
			</form>	

		<?php } ?>
			<p class="centered"><a href="MembAdmin.php">Membership Admin Page</a></p>

	
	</section>
	
	</div>

	<footer>

	<?php include ("../../includes/club-membership.incl.html") ; ?>

	</footer>

</div> <!-- end master -->

</body>
</html>