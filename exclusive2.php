<?php

//connect to the database
require "db_connection.php";
require "functionsnav.php";
require_once("stripe/lib/Stripe.php");




//start the session
session_start();

    // if login form has been submitted
    if (htmlentities($_GET['action']) == "login") { 
        login();
    }
    else if(htmlentities($_GET['action']) == "logout") { 
        logout();
    }
    
    $email = $_SESSION['email'];

if (!$email) {
    header("Location: http://photorankr.com/signup.php");
}


$code = $_REQUEST['code'];


$post_data = array(
    'code' => $code,
    'client_id' => 'ca_07cmwOi7PRZWc7S0trIrH5wxJ0qlkMT6',
     'scope' => 'admin',
   'grant_type'=> 'authorization_code'
);


//echo "working2";
///nod
//$postfields = array('field1'=>'value1', 'field2'=>'value2');
//echo "working3";
$ch = curl_init();
//echo "working3";
curl_setopt($ch, CURLOPT_URL, 'https://connect.stripe.com/oauth/token');
//echo "working3";
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//echo "working4";
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
//echo "working5";
curl_setopt($ch, CURLOPT_POST, 1);
//echo "working6";
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
//echo "working7";
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_HTTPHEADER,array('Authorization: Bearer '. 'jpdzMPMCFihJ43mXpa5I89wrtHDDxtlE'));
$result = curl_exec($ch);
$parsedresult = json_decode($result,true);
//echo $result;
$tokentype = $parsedresult["token_type"];
$livemode = $parsedresult["livemode"];
$refreshtoken = $parsedresult["refresh_token"];
$stripeuserid = $parsedresult["stripe_user_id"];
$stripepublishablekey = $parsedresult["stripe_publishable_key"];
$stripescope = $parsedresult["scope"];
$stripeaccesstoken = $parsedresult["access_token"];
//$result = post_request('https://connect.stripe.com/oauth/authorize', $post_data);

//curl -u $stripeaccesstoken: https://api.stripe.com/v1/account

//$updatestripe=("UPDATE userinfo SET pubkey = '$stripepublishablekey', token = '$stripeaccesstoken' WHERE emailaddress='$email'");
//$infoupdateresult=mysql_query($updatestripe);

// $post_data = array(
// 'ACCESS_TOKEN'=>$stripeaccesstoken
// )
$accesstoken= strval($stripeaccesstoken);
$typeofstripe = gettype($accesstoken);
//echo $accesstoken;
//echo $typeofstripe;

$post_data = array(
    'access_token'=> $accesstoken
);


$ch = curl_init('https://api.stripe.com/v1/account');

// set URL and other appropriate options
$options = array(        
  CURLOPT_USERPWD=> $accesstoken
);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


//I think the issue with this maybe that it's curl_setopt_array and not curl_setopt
curl_setopt_array($ch, $options);

$result2= curl_exec($ch);
$parsedresult2 = json_decode($result2, true);


$updatestripe=("UPDATE userinfo SET pubkey = '$stripepublishablekey', token = '$stripeaccesstoken' WHERE emailaddress='$emailretrieve'");
mysql_query($updatestripe);

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
"http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta name="Contact Us"></meta>
	<link rel="stylesheet" type="text/css" href="css/bootstrapNew.css" />
		<link rel="stylesheet" href="960_24.css" type="text/css" />
		<link rel="stylesheet" href="css/style.css" type="text/css" />
		<link rel="stylesheet" href="text2.css" type="text/css" />
		<link rel="stylesheet" type="text/css" href="css/all.css"/>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript" src="bootstrap.js"></script>   
<script src="bootstrap-dropdown.js" type="text/javascript"></script>
<script src="bootstrap-collapse.js" type="text/javascript"></script>
<script type="text/javascript" src="https://js.stripe.com/v1/"></script>
<link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>

 <style type="text/css">
        .show { display: block;  }
        .hide { display: none; }

.btn-signup 
			{
  				background-color: hsl(101, 55%, 52%) !important;
  				background-repeat: repeat-x;
  				filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#a9de90", endColorstr="#6bc741");
  				background-image: -khtml-gradient(linear, left top, left bottom, from(#a9de90), to(#6bc741));
  				background-image: -moz-linear-gradient(top, #a9de90, #6bc741);
  				background-image: -ms-linear-gradient(top, #a9de90, #6bc741);
  				background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #a9de90), color-stop(100%, #6bc741));
  				background-image: -webkit-linear-gradient(top, #a9de90, #6bc741);
  				background-image: -o-linear-gradient(top, #a9de90, #6bc741);
 				background-image: linear-gradient(#a9de90, #6bc741);
  				border-color: #6bc741 #6bc741 hsl(101, 55%, 47%);
  				color: #fff !important;
  				text-shadow: 0 1px 1px rgba(102, 102, 102, 0.88);
  				-webkit-font-smoothing: antialiased;
  				padding:1em 2em 1em 2em;
  				font-weight: 500;

}
		.btn-explore {
 			 background-color: hsl(0, 0%, 31%) !important;
  			background-repeat: repeat-x;
  			filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#828282", endColorstr="#4f4f4f");
  			background-image: -khtml-gradient(linear, left top, left bottom, from(#828282), to(#4f4f4f));
  			background-image: -moz-linear-gradient(top, #828282, #4f4f4f);
  			background-image: -ms-linear-gradient(top, #828282, #4f4f4f);
  			background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #828282), color-stop(100%, #4f4f4f));
  			background-image: -webkit-linear-gradient(top, #828282, #4f4f4f);
  			background-image: -o-linear-gradient(top, #828282, #4f4f4f);
  			background-image: linear-gradient(#828282, #4f4f4f);
 			border-color: #4f4f4f #4f4f4f hsl(0, 0%, 26%);
 			color: #fff !important;
  			text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.33);
  			-webkit-font-smoothing: antialiased;
  			padding:1em 2em 1em 2em;
}
.btn-go {
    background-color: hsl(207, 55%, 46%) !important;
  background-repeat: repeat-x;
  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#68a3d3", endColorstr="#347bb5");
  background-image: -khtml-gradient(linear, left top, left bottom, from(#68a3d3), to(#347bb5));
  background-image: -moz-linear-gradient(top, #68a3d3, #347bb5);
  background-image: -ms-linear-gradient(top, #68a3d3, #347bb5);
  background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #68a3d3), color-stop(100%, #347bb5));
  background-image: -webkit-linear-gradient(top, #68a3d3, #347bb5);
  background-image: -o-linear-gradient(top, #68a3d3, #347bb5);
  background-image: linear-gradient(#68a3d3, #347bb5);
  border-color: #347bb5 #347bb5 hsl(207, 55%, 42%);
  color: #fff !important;
  text-shadow: 0 1px 1px rgba(255, 255, 255, 0.26);
  -webkit-font-smoothing: antialiased;
  padding:.5em 1.6em .5em 1.6em;
  font-weight:700;
  font-size:14px;
  margin-left: .1em;
}



 </style>


<!--GOOGLE ANALYTICS CODE-->
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-28031297-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>

<!--STRIPE SCRIPTS-->
    <script type="text/javascript" src="https://js.stripe.com/v1/"></script>
        <!-- jQuery is used only for this example; it isn't required to use Stripe -->
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
        <script type="text/javascript">
            // this identifies your website in the createToken call below
           // Stripe.setPublishableKey('pk_NuzRruZd0ks8VMufKgWZtecdiIqFK');
    
       var key = "<? print $stripepubkey; ?>";
          //  Stripe.setPublishableKey('pk_wyF8CPirmy3KmAv7lmf5gKwV5bElr');
            //document.write("workplease");
            //document.write(key);
//echo $stripepubkey;
           Stripe.setPublishableKey('pk_NuzRruZd0ks8VMufKgWZtecdiIqFK');

            // Stripe.setPublishableKey('pk_wyF8CPirmy3KmAv7lmf5gKwV5bElr ');
            
            function stripeResponseHandler(status, response) {
                if (response.error) {
                    // re-enable the submit button
                    $('.submit-button').removeAttr("disabled");
                    // show the errors on the form
                    $(".payment-errors").html(response.error.message);
                } else {
                    var form$ = $("#payment-form");
                    // token contains id, last4, and card type
                    var token = response['id'];
                    // insert the token into the form so it gets submitted to the server
                    form$.append("<input type='hidden' name='stripeToken' value='" + token + "' />");
                    // and submit
                    form$.get(0).submit();
                }
            }

            $(document).ready(function() {
                $("#payment-form").submit(function(event) {
                    // disable the submit button to prevent repeated clicks
                    $('.submit-button').attr("disabled", "disabled");
                    // createToken returns immediately - the supplied callback submits the form if there are no errors
                    Stripe.createToken({
                        number: $('.card-number').val(),
                        cvc: $('.card-cvc').val(),
                        exp_month: $('.card-expiry-month').val(),
                        exp_year: $('.card-expiry-year').val()
                    }, stripeResponseHandler);
                    return false; // submit from callback
                });
            });

            if (window.location.protocol === 'file:') {
                alert("stripe.js does not work when included in pages served over file:// URLs. Try serving this page over a webserver. Contact support@stripe.com if you need assistance.");
            }
        </script>

	</head>
<body style="background-color:rgb(245,245,245);">
<?php navbarnew(); 


echo'
<div id="topbar" style="height:100px;padding-top:30px;font-size:22px;font-family:helvetica;font-weight:100;"><div style="text-align:center;padding-left:10px;padding-right:10px;">PhotoRankr is on the verge of even bigger and better things.  &nbsp;We appreciate your support and loyalty in our early days.  &nbsp;As a thank you, we are offering you a lifetime of access to the highest upcoming subscription level for a low, one-time fee.  &nbsp;As we grow, we want to help you grow as a photographer.</div>
	</div>	
<div class="container_24">
	<div class="grid_22 push_1" id="formshell">
		<div id="container">
		<div class="grid_11" id="form">
		<!--<form action="none" method="">-->
			<h1 class="description"> Lifetime Subscription Offer </h1>
			<fieldset id="formfields">


<div style="margin-top:-30px;font-size:18px;font-weight:200;font-family:helvetica;">
	    <br />Lifetime Membership Cost: $30 USD<br /><br />
        Includes: 
            <ul style="font-size:15px;"><br />
                <li>All future features</li>
                 <li>Unlimited uploads</li>
                 <li>Unlimited entries to all Campaigns</li>
                 <li>Unlimited entries to Marketplace</li> 
            </ul>
	Coming Soon: 
 <ul style="font-size:15px;"><br />
                <li>Access to original files</li>
                <li>iPhone Application</li>
                <li>Custom Domain Name</li>
                <li>Tin Eye Image-Tracking API </li> 
            </ul>



        </div>

		
			</fieldset>
				
	</div>	


	<div class="grid_9">

    <div style="text-align:center;margin-left:50px;margin-top:180px;">
        <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
        <input type="hidden" name="cmd" value="_s-xclick">
        <input type="hidden" name="hosted_button_id" value="9BKSNXAEJDY28">
        <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
        <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
        </form>
    </div>

	</div>

<!-- to display errors returned by createToken -->
<span class="payment-errors" style="font-weight:bold;font-size:15px;"></span>

<form action="',htmlentities($_SERVER['PHP_SELF']),'?charge=1" method="POST" id="payment-form">
<div class="form-row" style="margin-left:25px;">
            
<input type="hidden" name="price" value="',$price,'">
<input type="hidden" name="firstname" value="',$firstname,'">
<input type="hidden" name="lastname" value="',$lastname,'">
<input type="hidden" name="image" value="',$image,'">
<input type="hidden" name="label" value="',$label,'">
<input type="hidden" name="imageID" value="',$imageID,'">
<input type="hidden" name="customeremail" value="',$customeremail,'">
<input type="hidden" name="email" value="',$emailretrieve,'">  


			</fieldset>	
			</div>
		
		</div>
	</div>
	<!--<div class="grid_22" style="text-align:center;">
	<button class="btn btn-signup"/>Become a PhotoRankr Beta Tester</button>
		<p style="font-size:9px;"> (You get access to cool things)</p>-->
</div>

</div>

	</br>
	<div class="container_24" style="float:center;margin-top:-100px;">';


if($_GET['charge'] == 1){

echo'<div class="grid_24" style="margin-top:50px;font-size:22px;font-weight:200;text-align:center;">Congrats! You are now a lifetime member of PhotoRankr!</div>';


//Stripe::setPubKey($stripepubkey);
  $signedupemail = $_POST['email'];
  //echo $email;
$token = $_POST['stripeToken'];

Stripe::setApiKey("I4xWtNfGWVVGzVuOr6mrSYZ5nOrfMA9X");

//Working Subscription Code
$customer = Stripe_Customer::create(array(
  "card" => $token,
  "plan" => 1,
  "email" => $email)
);

}
footer()
?>
	</div>





	</body>
</html>	
