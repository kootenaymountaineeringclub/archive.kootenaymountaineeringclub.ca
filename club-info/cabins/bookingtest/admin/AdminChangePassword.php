<?php include ("AdminHeader.php"); ?>

<h3>Cabins Administration Password Change</h3>
<form method="post" action="admin_change_password.php">
	<table>
		<tr>
			<td>
				<strong>Old Password:</strong>
			</td>
			<td>
				<input type="password" name="password0" />
			</td>
		</tr>
		<tr>
			<td>
		    <strong>New Password:</strong>
			</td>
			<td>
				<input type="password" name="password1" />
			</td>
		</tr>
		<tr>
			<td>
		    <strong>Retype New Password:</strong>
			</td>
			<td>
				<input type="password" name="password2" />
			</td>
		</tr>
		<tr>
			<td colspan="2">
	    	<input type="submit" name="submit" value="Change Password" />
			</td>
		</tr>
	</table>
</form>

<p><a href='admin_menu.php'>Return to administrators menu</a></p>
<?php include("AdminFooter.php"); ?>
