<?php 
	session_start();
	include("util.php");
	include("date_util.php");
	global $SAVEDSESSION;
	get_session (session_id());
	$_SESSION = $SAVEDSESSION;
?>

<!DOCTYPE html>
<html>
<head>
	<?php include (PHP_ROOT . "/includes/head-first.incl.html") ; ?>
	<title>KMC: The Bonnington Cabin Booking System</title>
	
	<?php include (PHP_ROOT . "/includes/head-2nd.incl.html") ; ?>
	<?php include ("../includes/CabinBookingBackgroundImage.incl.html") ; ?>
</head>
<body>

<div id="master">

<header>

	<?php include ("../../includes/page-header-club.incl.html") ;
				include (PHP_ROOT . "/includes/header-contents.incl.html") ; ?>
	
</header>

<div id="content">

<?php include ("../../includes/club-bonnington.incl.html") ; ?>
	
	<section>


<?php
	db_connect();
	$_SESSION['id'] = session_id();
	$total_cost = 0;
	$total_person_nights = 0;
	$display_block = html_cart_paypal();
	echo $display_block; 

	echo "<p class='centered'>Clicking the PayPal button below will send you to PayPal to pay for " . $_SESSION['Description'] . "</p>\n";
	$SAVEDSESSION = $_SESSION;
	$savefile = "saved/" . session_id() . ".txt" ;
	save_session (session_id());
?>

<p class="centered">You may cancel the PayPal transaction by simply closing the PayPal window.</p>
		
<div id="paypal-button" class="centered"></div>
<script src="https://www.paypalobjects.com/api/checkout.js"></script>
<script>
paypal.Button.render({
  commit: true, //makes total amount appear in PayPal window
  env: 'production',
  client: {
    sandbox: 'AUGgqTdywZOhAKbSfBMbx3n_K10bXwth75ozIPcS7N8-j6VlW5SgVgnxeFRnNn7ixfBQWVK3a6GLtwYc', // cabins-facilitator
	production:  'AWaNtOp3RS98WvcKDchP28eVKTHrIrLV4DL9S2ScB63wj-8tyjca6yQq0bx2z29Y_b6PNgUsHwh4Pu3h' //cabins
  },
  payment: function (data, actions) {
    return actions.payment.create({
      payment: {
        transactions: [
          {
            amount: {
              total: '<?php echo $_SESSION['Payment_Amount']; ?>',
              currency: 'CAD'
            },
            description: <?php echo "'" . $_SESSION['Description'] . "'" ?>
          }
        ],
		  
		  redirect_urls: {
          return_url: "https://kootenaymountaineeringclub.ca/club-info/cabins/booking/return-good.php?id=<?php echo session_id()?>",
          cancel_url: "https://kootenaymountaineeringclub.ca/club-info/cabins/booking/cancel.php?id=<?php echo session_id()?>"
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

<?php include html_footer_strip() ; ?>

</footer>

</div>
<!-- end master -->

</body>
</html>
