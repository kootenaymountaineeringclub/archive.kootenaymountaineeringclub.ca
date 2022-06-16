			<p class="requiredlabels">All fields labeled in red are required.</p>
			
			<form method="post" action="php/checkout.php">
				
			<div class="formPart" id="type">
				<p class="formHeader">Membership Type:</p>
				<div class="formItem">
					<label for="MembType">Individual : </label><input type="radio" id="MembType" name="MembType" value="Individual" %%Individual%% onclick="hide2()">
					<label for="MembType">Couple : </label><input type="radio" id="MembType" name="MembType" value="Couple" %%Couple%% onclick="show2()"><br>
					<p class="centered">Membership price : <span id="MembPrice">%%Price%%</span></p>
				</div>
			</div>
			
			<div class="formPart" id="name1">
				<p class="formHeader">Name:</p>
				<div class="formItem">
					<label class="required" for="FirstName1">First name : </label><input class="text"  type="text" id="FirstName1" name="FirstName1">%%FirstName1%%</input><br>
					<label for="NickName1">Initial or nickname? : </label><input class="text"  type="text" name="NickName1">%%NickName1%%</input><br>
					<label class="required" for="LastName1">Last name : </label><input class="text"  type="text" id="LastName1" name="LastName1">%%LastName1%%</input><br>
					<label class="required" for="Email1">Email address : </label><input class="text"  type="email" id="Email1" name="Email1">Email1<br>
					<label for="Phone1">Phone : </label><input class="text"  type="text" name="Phone1">%%Phone1%%<br>
				</div>
			</div>
			
			<div class="formPart formHidden" id="name2">
				<p class="formHeader">Name 2:</p>
				<div class="formItem">
					<label class="required" for="FirstName2">First name : </label><input class="text" type="text" id="FirstName2" name="FirstName2">%%FirstName2%%</input><br>
					<label for="NickName2">Initial or nickname? : </label><input class="text"  type="text" name="NickName2">%%NickName2%%</input><br>
					<label class="required" for="LastName2">Last name : </label><input class="text" type="text" id="LastName2" name="LastName2">%%LastName2%%</input><br>
					<label for="SharedEmail">Shared Email : </label><input type="checkbox" id="SharedEmail" name="SharedEmail" value="1"> or ...<br>
					<label for="Email2">Email address : </label><input class="text" type="email"  name="Email2">%%Email2%%</input><br>
					<label for="Phone2">Phone : </label><input class="text"  type="text" name="Phone2">%%Phone2%%</input><br>
				</div>
			</div>
			
			<div class="formPart" id="address">
				<p class="formHeader">Mailing Address:</p>
				<div class="formItem">
					<label id="LabelAddr1" for="Addr1">First line : </label><input class="text" type="text" id="Addr1" name="Addr1">%%Addr1%%</input><br>
					<label id="LabelAddr2" for="Addr2">2nd line (if needed) : </label><input class="text"  type="text" name="Addr2">%%Addr2%%</input><br>
					<label class="required" for="City">City : </label><input class="text" type="text" id="City" name="City">%%City%%</input><br>
					<label id="LabelProv" for="Prov">Province/State : </label><input class="text" type="text" id="Prov" name="Prov">%%Prov%%</input><br>
					<label id="LabelPostCode" for="PostCode">Postal code/Zip : </label><input class="text"  type="text" id="Postal" name="Postal">%%Postal%%</input><br>
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
				<!--#include virtual=Waiver.incl.html -->
				<span><input type="button" id="Agree" class="formButton" value="I Agree" onClick="go()"></span>
			</div>
			
			<div class="formPart formHidden" id="Go">
				<p class="centered">Before clicking this button, please make sure that at least your email address is correct. In fact, making sure that all of it is correct will make it all much smoother for both of us.</p>
				<span><input type="submit" class="formButton" value="Continue to confirmation and payment"></span>
			</div>
			</form>
