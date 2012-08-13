<?php

require "db_connection.php";
require "functionsnav.php";
require_once("stripe/lib/Stripe.php");


$code = $_REQUEST['code'];
$email = $_REQUEST['emailaddress'];

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
<link rel="stylesheet" type="text/css" href="css/download.css"/>
<link rel="stylesheet" type="text/css" href="css/reset.css"/>
<link rel="stylesheet" type="text/css" href="css/text.css"/>
<link rel="stylesheet" type="text/css" href="css/bootstrapNew.css"/>
<link rel="stylesheet" type="text/css" href="css/960_24.css"/>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript" src="bootstrap.js"></script>   
<script src="bootstrap-dropdown.js" type="text/javascript"></script>
<script src="bootstrap-collapse.js" type="text/javascript"></script>
<script type="text/javascript" src="https://js.stripe.com/v1/"></script>
<link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>

 <style type="text/css">
        .show { display: block;  }
        .hide { display: none; }
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

<body>

<?php

navbarnew();

echo'

<div class="container_24" style="padding-top:50px;"> <!--container begin-->
<br />

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
        
        <div class="grid_18"><a name="added" style="color:black;text-decoration:none;" href="#"><div style="font-size:24px;padding-left:250px;padding-top:20px;padding-bottom:20px;background-color:#ddd;width:260px;margin-left:-230px;margin-top:20px;"><span style="font-size:27px;font-weight:200;">Lifetime Subscription</span></div></a></div><br />
        
        <!--STRIPE PAYMENT FORM-->
       
        <div class="grid_18" style="margin-top:35px;">
        <div style="font-size:18px;font-weight:200;font-family:helvetica;padding-bottom:30px;margin-top:-10px;">You have succcessfully connected to PhotoRankr via Stripe
        <br /><br />Membership Cost: $30<br /><br />
        Includes Now: 
            <ul style="font-size:15px;"><br />
                <li>All future features</li>
                 <li> unlimited uploads</li>
                 <li>  unlimited entries to all campaigns</li>
                  <li>   unlimited entries to marketplace </li> 
            </ul>
	Coming Soon: 
 <ul style="font-size:15px;"><br />
                <li>Access to original files</li>
                 <li>iPhone Application</li>
                 <li> Custom Domain Name</li>
                  <li> Tin Eye API </li> 
            </ul>
        </div>
         <label class="creditcards" style="float:left;font-size:18px;">We accept:&nbsp;&nbsp;<img src="card.jpg" style="width:215px;height:25px;margin-top:0px;border-radius:2px;"/> </label> <br /><br /><br />
        
                <label style="float:left;margin-left:5px;font-size:18px;" class="creditcards">First Name <span style="font-size:15px;"></span>&nbsp;&nbsp;</label>
                <input style="float:left;font-size:18px;padding:8px;position:relative;top:-7px;width:60px;" type="text" size="4" autocomplete="off" class="card-cvc"/>
                <label style="float:left;margin-left:5px;font-size:18px;" class="creditcards">Last Name <span style="font-size:15px;"></span>&nbsp;&nbsp;</label>
                <input style="float:left;font-size:18px;padding:8px;position:relative;top:-7px;width:60px;" type="text" size="4" autocomplete="off" class="card-cvc"/>

         <label style="float:left;font-size:18px;" class="creditcards">Card Number:&nbsp;&nbsp;</label>
         <input style="float:left;font-size:18px;padding:8px;position:relative;top:-7px;" type="text" size="20" autocomplete="off" class="card-number" style;"/>
            
                <label style="float:left;margin-left:5px;font-size:18px;" class="creditcards">CVC <span style="font-size:15px;">(Verification #):</span>&nbsp;&nbsp;</label>
                <input style="float:left;font-size:18px;padding:8px;position:relative;top:-7px;width:60px;" type="text" size="4" autocomplete="off" class="card-cvc"/>
                <label style="float:left;font-size:18px;margin-left:5px;" class="creditcards" >Expiration <span style="font-size:15px;">(MM/YYYY):</span>&nbsp;&nbsp;</label>
                <input type="text" style="float:left;width:50px;padding:8px;position:relative;top:-7px;width:30px;font-size:18px;" class="card-expiry-month"/>
                <span style="float:left;font-size:30px;font-weight:100;">&nbsp;/&nbsp;</span>
                <input style="float:left;padding:8px;position:relative;top:-7px;width:60px;font-size:18px;" type="text" class="card-expiry-year"/>
               
  <br /><br /><br /><div></div>
        </div>



    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>  
    <script src="bootstrap.js" type="text/javascript"></script>

  
    <script>  
    $(function ()  
    { $("#learnit").popover();  
    });  
    </script>
    
</div>
</div>
</div>

   <button type="submit" class="button submit btn btn-success" style="font-size:16px;margin-left:45px;margin-top:5px;padding-top:10px;padding-bottom:10px;padding-right:55px;padding-left:55px;">Submit Payment</button>
  </div> 
          </form>
</div>
</div>';



if($_GET['charge'] == 1){

echo'<div class="grid_24" style="margin-top:35px;font-size:22px;font-weight:200;text-align:center;">Congrats! You are now a lifetime member of PhotoRankr!</div>';


//Stripe::setPubKey($stripepubkey);
  $signedupemail = $_POST['email'];
  echo $email;
$token = $_POST['stripeToken'];

Stripe::setApiKey("I4xWtNfGWVVGzVuOr6mrSYZ5nOrfMA9X");

//Working Subscription Code
$customer = Stripe_Customer::create(array(
  "card" => $token,
  "plan" => 1,
  "email" => $signedupemail)
);

}

?>

</body>
</html>