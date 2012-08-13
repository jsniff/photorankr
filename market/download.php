<?php

//connect to the database
require "db_connection.php";
require "functionscampaigns3.php";
require_once("stripe/lib/Stripe.php");

    // if login form has been submitted
    if (htmlentities($_GET['action']) == "login") { 
        login();
    }
    else if(htmlentities($_GET['action']) == "logout") { 
        logout();
    }

    //start the session
    session_start();

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

   <script type="text/javascript" src="https://js.stripe.com/v1/"></script>
        <!-- jQuery is used only for this example; it isn't required to use Stripe -->
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
        <script type="text/javascript">
            // this identifies your website in the createToken call below
            Stripe.setPublishableKey('pk_NuzRruZd0ks8VMufKgWZtecdiIqFK');

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

			<script type="text/javascript">
  $(function() {
  // Setup drop down menu
  $(".dropdown-toggle").dropdown();
 
  // Fix input element click problem
  $(".dropdown input, .dropdown label").click(function(e) {
    e.stopPropagation();
  });
});
</script>

</head>

<body style="background-color:rgb(238,239,243);">

<?php navbarsweet(); ?>

<?php

//STRIPE DOWNLOAD SYSTEM ALL RIGHT HERE 

$price = $_POST['price'];
$customeremail = $_POST['owner'];
$caption = $_POST['caption'];
$imageID = $_POST['imageID'];
$campaignnumber = $_POST['campaignnumber'];
$width = $_POST['width'];
$height = $_POST['height'];
$image = $_POST['image'];
$imagenew = str_replace("userphotos/","userphotos/bigphotos/", $image);
$imagename = str_replace("userphotos/","", $image);

if($_REQUEST['charge'] != 1) {
 
echo'

<div class="container_24" style="padding-top:80px;"> <!--container begin-->
<div class="grid_21 push_1 download1">
<div style="font-size:22px;text-align:center;">Download a watermark-free, high resolution copy below:</div>
<br />
<div class="grid_8">
<div class="grid_8 form">
 <div class="grid_8 title">
  <h1 class="titleh" style="text-shadow: 0.05em 0.05em 0.05em #665"> Secure payment with Stripe </h1>
 <div class="grid_7" style="margin-left:5px;background-color:rgb(243,245,246);padding:10px;border-radius:10px;">

        <!-- to display errors returned by createToken -->
        <span class="payment-errors" style="font-weight:bold;font-size:15px;"></span>

    <form action="',htmlentities($_SERVER['PHP_SELF']),'?charge=1" method="POST" id="payment-form">
    <div class="form-row" style="margin-left:25px;">
            
<input type="hidden" name="price" value="',$price,'">
<input type="hidden" name="image" value="',$image,'">
<input type="hidden" name="caption" value="',$caption,'">
<input type="hidden" name="imageID" value="',$imageID,'">
<input type="hidden" name="campaignnumber" value="',$campaignnumber,'">
<input type="hidden" name="owneremail" value="',$customeremail,'">

                <label class="creditcards" style="margin-bottom:10px;">Card Number. We accept:<img src="graphics/card.jpg" style="width:215px;height:25px;margin-top:4px;border-radius:2px;"/> </label> 
                <input type="text" size="20" autocomplete="off" class="card-number" style;"/>
            </div>
            <div class="form-row" style="margin-left:25px;">
                <label class="creditcards">CVC (Verification #)</label>
                <input type="text" size="4" autocomplete="off" class="card-cvc"/>
            </div>
            <div class="form-row" style="margin-left:25px;">
                <label class="creditcards" >Expiration (MM/YYYY)</label>
                <input type="text" style="width:50px" size="2" class="card-expiry-month"/>
                <span style="font-size: 22px"> / </span>
                <input type="text" style="width:100px" size="4" class="card-expiry-year"/>
           <div class="">  <h1 class="creditcards1"> Your information is passed through Stripe\'s secure API. We never see it. </h1>   
           
    
    <a href="#" id="learnit" rel="popover" data-content="All payment information is sent directly through Stripe\'s secure API and never touches our servers. Your information is never collected and is securely processed with Stripe. Visit Stripe\'s website to learn more." data-original-title="Secure Payments With Stripe">Learn More</a> 
    </div>     
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

   <button type="submit" class="button submit btn btn-success" style="font-size:16px;margin-left:45px;margin-top:22px;padding-top:15px;padding-bottom:15px;padding-right:55px;padding-left:55px;">Submit Payment</button>
  </div> 
          </form>


<div class="grid_8 push_3 image" style="width:300px;"><img src="',$image,'" style="width:300px;height:300px;"/> 
</div>
<div class="grid_6 push_4 info" style="margin-top:25px;"> 
<h1 class="field"> Price: USD $',$price,'.00</h1> 
<h3 class="field"> Photo: "',$caption,'" </h3>
<h3 class="field"> Image ID: "',$imageID,'"</h3>
</div>
</div>
</div>';

} 

//STRIPE ACCOUNT CHARGING AND DOWNLOAD PHOTO SYSTEM HERE

if($_GET['charge'] == 1) {

$owneremail = $_POST['owneremail'];
$image = $_POST['image'];
$caption = $_POST['caption'];
$imageID = $_POST['imageID'];

    //INDICATE WINNING PHOTO AND EMAIL
    $winnerquery = "UPDATE campaigns SET winnerphoto = '$imageID', winneremail = '$owneremail' WHERE id = '$campaignnumber'";
    $winnerqueryrun = mysql_query($winnerquery);
    
    //ADD TO BUYER'S DOWNLOAD LIST
    $buyeremail = $_SESSION['email'];
    $one = '1';
    $downquery = mysql_query("UPDATE campaignphotos SET downloaded = '$one' WHERE id = '$imageID'");
    
    //SEND NOTIFICATION TO USERS ENTERED IN THIS CAMPAIGN THAT IT ENDED
    $endnot = mysql_query("SELECT * FROM campaignphotos");
    $numusers = mysql_num_rows($endnot);
    
    for($iii=0; $iii < $numusers; $iii++) {
        $useremail = mysql_result($endnot,$iii,'emailaddress');
        $match=strpos($prevlist, $useremail);
        if($match !== false) {
            continue;
        }
        $prevlist = $prevlist . " " . $useremail;
        }
        
        //Send notification
        $type = 'campaignended';
        $endnotnewsfeed = mysql_query("INSERT INTO newsfeed (type,source,caption,campaignentree,campaignwinner) VALUES ('$type','$campaignnumber','$caption','$prevlist','$customeremail')");

        
    

	//PRICE CHANGE INTO CENTS FOR STRIPE
    $newprice = $price * 100;
    
// set your secret key: remember to change this to your live secret key in production
// see your keys here https://manage.stripe.com/account
Stripe::setApiKey("I4xWtNfGWVVGzVuOr6mrSYZ5nOrfMA9X");

// get the credit card details submitted by the form
$token = $_POST['stripeToken'];

// create the charge on Stripe's servers - this will charge the user's card
$charge = Stripe_Charge::create(array(
  "amount" => $newprice, // amount in cents, again
  "currency" => "usd",
  "card" => $token,
  "customer" => $customeremail,
  "description" => 'image number: ' . $imageID . ' campaign number ' . $campaignnumber . ' owner email ' . $owneremail)
);

echo '
<div style="font-size:20px;text-align:center;margin-top:70px;">Thank you for your purchase.</div><br />
<div class="grid_12 push_12 download1" style="margin-top:10px;margin-left:480px;>
<div class="grid_8 push_3 image" style="width:300px;"><img src="',$image,'" style="width:300px;height:300px;"/> 


<form name="download_form" method="post" action="downloadphoto.php">
<input type="hidden" name="image" value="', $image, '">
<input type="hidden" name="label" value="', $caption, '">
<input type="hidden" name="imageID" value="', $imageID, '">
<input type="hidden" name="customeremail" value="', $customeremail, '">
<div class="grid_24" style="margin-top:30px;">
<button type="submit" name="submit" value="download" class="btn btn-warning" style="width:295px;height:40px;">DOWNLOAD PHOTO</button>
</div>
</form>

</div>';

}

?>        
  
</div><!--container end-->
<!--Footer begin-->                
             
           
                  
<div class="grid_24" style="background-color:none;width:100%;height:30px;margin-top:15px;text-align:center;padding-top:10px;padding-bottom:0px; background-color:none;text-decoration:none;">
    <p style="text-decoration:none;">
        PhotoRankr&nbsp;&copy;&nbsp;2012&nbsp;
                                    <a href="http://photorankr.com/about.php">About</a>&nbsp;&nbsp;
                                        
                                        <a href="http://photorankr.com/terms.php">Terms</a>&nbsp;&nbsp;
                                      <a href="http://photorankr.com/privacy.php">Privacy</a>&nbsp;&nbsp;
<a href="http://photorankr.com/help.php">Help<a>&nbsp;&nbsp;
<a href="http://photorankr.com/contact.php">Contact&nbsp;Us<a>
                                      </p>
                   
                        </div>
                    </div>
                </div> <!--container end-->


                
                
                <!--Footer end--> 
        
  
  </div>
  
</div> <!--Container End-->  
 
</body>
</html>
 