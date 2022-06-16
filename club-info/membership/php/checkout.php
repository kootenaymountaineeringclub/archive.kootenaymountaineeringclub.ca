<?php
	session_start();
?>

<!DOCTYPE html>
<html>
<head>
	<?php 
		include("util.php");
		include (PHP_ROOT . "/includes/head-first.incl.html") ;
	?>

	<title>KMC: Kootenay Mountaineering Membership</title>
	
	<?php include (PHP_ROOT . "/includes/head-2nd.incl.html") ; ?>
	<?php include ("../includes/MembershipBackgroundImage.incl.html") ; ?>
	
	<link rel="stylesheet" type="text/css" href="/css/membership-styles.css">

<body>

<div id="master">

<header>

	<?php include ("../../includes/page-header-club.incl.html") ;
		include (PHP_ROOT . "/includes/header-contents.incl.html") ; ?>
	
</header>


<div id="content">

<?php include ("../includes/club-membership.incl.html") ; ?>
	
	<section>
	<div class="formPart" id="name1">
		<?php
			db_connect();
			$_SESSION = $_POST;
			if ($_POST['memb_numb']) {
				$_SESSION["MembID"] = $_POST['memb_numb'];				
			} else {
				$_SESSION["MembID"] = get_memb_id();
			}
			$_SESSION["MembCost"] = $_POST["MembCost"];
			$total_cost = $_POST["MembCost"];
			$_SESSION["name1_age"] = $_POST["name1_age"];
			
			$paydescription = "KMC " . $_POST["MembType"] . ' membership for $' . $_POST["MembCost"];
			
			$htmldescription = $paydescription . '. PayPal will invoice you $' . $total_cost . '.';
			
			$membNames = $_SESSION["FirstName1"] . " " . $_SESSION["LastName1"];
			
			if ($_SESSION["MembType"] == "Couple")
			{
				$membNames .= " and " . $_SESSION["FirstName2"] . " " . $_SESSION["LastName2"];
				$_SESSION["name2_age"] = $_POST["name2_age"];
			}
			
			$_SESSION['membNames'] = $membNames ;
			
			echo "<p class='centered'>Clicking the PayPal button below will send you to PayPal to pay for a " . $htmldescription . "</p>\n";
			echo "<p class='centered'>The membership is in the name of " . $membNames . "</p>\n";
			
			$_SESSION["paydescription"] = $paydescription;
			$savefile = "saved/" . $_SESSION["MembID"] . ".txt" ;

			file_put_contents($savefile,json_encode($_SESSION));
?>

<p class="centered">You may cancel the PayPal transaction by simply closing the PayPal window.</p>
		
	<div id="paypal-button" class="centered"></div>
<script src="https://www.paypalobjects.com/api/checkout.js"></script>
<script>
paypal.Button.render({
  commit: true, //makes total amount appear in PayPal window
  env: 'production',
  client: {
   // sandbox: 'AU3Vc6dVmDjp9-A-i1QO0Kd2cxn27wKgvV-xQY6yYcb1oAJLb3LPFbDSmKpCkOSpvV8lu-Rgg_hypnt4', //Abby's Sandbox ID
   sandbox: 'AUGgqTdywZOhAKbSfBMbx3n_K10bXwth75ozIPcS7N8-j6VlW5SgVgnxeFRnNn7ixfBQWVK3a6GLtwYc', // cabins-facilitator
	production:  'AWaNtOp3RS98WvcKDchP28eVKTHrIrLV4DL9S2ScB63wj-8tyjca6yQq0bx2z29Y_b6PNgUsHwh4Pu3h' //KMC ID **Need to test
  },
  payment: function (data, actions) {
    return actions.payment.create({
      payment: {
        transactions: [
          {
            amount: {
              total: '<?php echo $total_cost; ?>',
              currency: 'CAD'
            },
            description: '<?php echo $paydescription; ?>'
          }
        ],
		  
		  redirect_urls: {
          return_url: "https://kootenaymountaineeringclub.ca/club-info/membership/php/return-good.php?MembID=<?php echo $_SESSION['MembID']?>",
          cancel_url: 'https://kootenaymountaineeringclub.ca/club-info/membership/php/cancel.php'
        }
      },
      experience: {
        input_fields: {
          no_shipping: 1
        }
      }
    });
  },
	
  onAuthorize: function (data, actions) {
    return actions.payment.execute()
      .then(function () {
		actions.redirect();
       });
  },
	
  onCancel: function(data, actions) {
    actions.redirect();
    }
	
}, '#paypal-button');

</script>	

</section>
</div> <!-- end content -->

<footer>

<?php include ("../includes/club-membership.incl.html") ; ?>

</footer>

</div> <!-- end master -->

</body>
</html>
