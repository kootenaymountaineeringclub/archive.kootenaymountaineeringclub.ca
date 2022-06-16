var $CONFIG_INDIVIDUAL = "38.00";
var $CONFIG_COUPLE = "61.00";
var $CONFIG_NEWSLETTER = "20.00";
var $CONFIG_NO_MONEY = "0.00"

function hide2()
	{ $("#name2").addClass("formHidden");
		$("#MembPrice").text($CONFIG_INDIVIDUAL)
		$("#MembCost").val($CONFIG_INDIVIDUAL);
	}
	
function show2()
	{
		$("#name2").removeClass("formHidden") ;
		$("#MembPrice").text($CONFIG_COUPLE) ;
		$("#MembCost").val($CONFIG_COUPLE) ;
	}
	
function showWaiver()
	{
		$go = "YES";
		
		if ( document.getElementById("FirstName1").value == ""
			|| document.getElementById("LastName1").value == ""
			|| document.getElementById("Email1").value == "") { $go = "NO"; }
			
		if ( document.getElementById("City").value == "" ) { $go = "NO"; }
		
		if ( $("#MembPrice").text() == $CONFIG_COUPLE )
		{
			if ( document.getElementById("FirstName2").value == ""
				|| document.getElementById("LastName2").value == "" ) { $go = "NO"; }
		}
		
		if ( document.getElementById("kmc").checked || document.getElementById("fmcbc").checked )
		{
			if ( document.getElementById("Addr1").value == ""
				|| document.getElementById("Prov").value == ""
				|| document.getElementById("Postal").value == "" ) { $go = "NO"; }	
		}
		
		if ( $go == "YES" )
		{
			$("#warning").addClass("formHidden");
			$("#Waiver").removeClass("formHidden");
		} else {
			$("#warning").removeClass("formHidden");
		}
	}
	
function go($event)
	{
		$("#Agreement").val("Agreed") ;
		$("#Go").removeClass("formHidden")		
	}
	
function kmcpaper()
	{
		if ($("#newsmoney").val() == $CONFIG_NO_MONEY)
		{
			$("#newsmoney").val($CONFIG_NEWSLETTER);
			$("#Newsletter").val($CONFIG_NEWSLETTER);
		} else {
			$("#newsmoney").val($CONFIG_NO_MONEY);
			$("#Newsletter").val($CONFIG_NO_MONEY);
		}
	}
	
function paperaddress()
{
	if ( document.getElementById("kmc").checked || document.getElementById("fmcbc").checked )
	{
			$("#LabelAddr1").addClass("required");		
			$("#LabelProv").addClass("required");	
			$("#LabelPostCode").addClass("required");	
	} else {
			$("#LabelAddr1").removeClass("required");
			$("#LabelProv").removeClass("required");
			$("#LabelPostCode").removeClass("required");
	}
}
