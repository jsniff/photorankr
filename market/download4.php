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
    $repemail = $_SESSION['repemail'];

 
         $incart = mysql_query("SELECT * FROM cart WHERE emailaddress = '$repemail' ORDER BY id DESC");
         $incartresults = mysql_num_rows($incart);
                
         for($iii=0; $iii < $incartresults; $iii++) {
             $imagesource[$iii] = mysql_result($incart,$iii,'source');
             $imageprice[$iii] = mysql_result($incart,$iii,'price');
             $imagecartid = mysql_result($incart,$iii,'imageid');
             $emailquery = mysql_query("SELECT emailaddress FROM photos WHERE id = '$imagecartid'");
             $photogemail = mysql_result($emailquery,0,'emailaddress');
             $totalcartprice = $imagecartid+$totalcartprice;
             $cartidlist = $cartidlist.",".$imagecartid;
             list($width, $height)=getimagesize($imagesource[$iii]);
             $width = $width/4;
             $height = $height/4;
            


    $currentnots = "SELECT * FROM userinfo WHERE emailaddress = '$photogemail'";
$currentnotsquery = mysql_query($currentnots);
$currentnotsresult = mysql_result($currentnotsquery, 0, "notifications");


$emailtrial = "tyler.sniff@gmail.com";

 $getstripeinfo = "SELECT * FROM userinfo WHERE emailaddress = '$photogemail'";
$striperesult = mysql_query($getstripeinfo); 
$stripepubkey = mysql_result($striperesult, 0, 'pubkey');
echo $stripepubkey;
echo "whatup";


$getstripeinfo = "SELECT * FROM userinfo WHERE emailaddress = '$photogemail'";
$striperesult = mysql_query($getstripeinfo); 
$stripekey = mysql_result($striperesult, 0, 'token');
echo $stripekey;

}

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
         Stripe.setPublishableKey(key);

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

<!--DROPDOWNS-->
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

<body>
<?php navbarsweet(); ?>
<div class="container">

<div class="grid_24" style="margin-top:50px;">


<?php
    
    //PHOTO CART INFORMATION
    $imageid = htmlentities($_GET['imageid']);
    $pricephoto = htmlentities($_GET['price']);
    $imagequery = mysql_query("SELECT * FROM photos WHERE id = '$imageid'");
    $imagenewsource = mysql_result($imagequery,0,'source');
    $imagenewsource2 = str_replace("userphotos/", "$_SERVER[DOCUMENT_ROOT]/userphotos/",$imagenewsource);
    $imagenewsource3 = str_replace("$_SERVER[DOCUMENT_ROOT]/userphotos/", "http://photorankr.com/userphotos/",$imagenewsource2); 
    $imagenewprice = mysql_result($imagequery,0,'price');

    //ADD TO CART IN DB
    
        if($_SESSION['loggedin'] != 2) {
        echo'
        <div style="margin-top:70px;margin-left:260px;padding-bottom:150px;">
        <div style="text-align:center;font-size:18px;">Login Below or <a href="campaignnewuser.php">Register to Buy:</a></div><br />
        <form name="login_form" method="post" action="fullsize2.php?imageid=',$imageid,'&action=login">
        <div class="well" style="width:380px;padding-top:50px;padding-bottom:50px;padding-left:40px;">
        <span style="font-size:18px;font-family:helvetica, arial;margin-left:0px;">Email: </span><input type="text" style="width:200px;margin-left:40px;" name="emailaddress" /><br />
        <span style="font-size:18px;font-family:helvetica, arial;">Password: </span>&nbsp<input type="password" style="width:200px;" name="password"/><br >
        <input type="submit" class="btn btn-success" style="margin-left:250px;" value="sign in" id="loginButton"/>
        </div>
        </form>
        </div>';
        
        }
    
        elseif($_SESSION['loggedin'] == 2) {
       
        echo'<div style="font-size:24px;padding-left:250px;padding-top:20px;padding-bottom:20px;background-color:#ddd;width:150px;margin-left:-230px;margin-top:30px;"><span style="font-size:27px;font-weight:200;">Your Cart</span></div><br />';
        
        if($imageid) {
        $cartcheck = mysql_query("SELECT * FROM cart WHERE imageid = '$imageid' ORDER BY id DESC");
        $numincart = mysql_num_rows($cartcheck);
        if($numincart < 1) {
            $stickincart = mysql_query("INSERT INTO cart (source,emailaddress,imageid,price) VALUES ('$imagenewsource3','$repemail','$imageid', '$pricephoto')");
            }
        }
        
        $incart = mysql_query("SELECT * FROM cart WHERE emailaddress = '$repemail' ORDER BY id DESC");
        $incartresults = mysql_num_rows($incart);
                
        for($iii=0; $iii < $incartresults; $iii++) {
            $imagesource[$iii] = mysql_result($incart,$iii,'source');
            $imageprice[$iii] = mysql_result($incart,$iii,'price');
            $imagecartid = mysql_result($incart,$iii,'imageid');
            $emailquery = mysql_query("SELECT emailaddress FROM photos WHERE id = '$imagecartid'");
            $photogemail = mysql_result($emailquery,0,'emailaddress');
            $totalcartprice = $imagecartid+$totalcartprice;
            $cartidlist = $cartidlist.",".$imagecartid;
            list($width, $height)=getimagesize($imagesource[$iii]);
            $width = $width/4;
            $height = $height/4;
            
 $getstripeinfo = "SELECT * FROM userinfo WHERE emailaddress = '$photogemail'";
$striperesult = mysql_query($getstripeinfo); 
$stripepubkey = mysql_result($striperesult, 0, 'pubkey');



$getstripeinfo = "SELECT * FROM userinfo WHERE emailaddress = '$photogemail'";
$striperesult = mysql_query($getstripeinfo); 
$stripekey = mysql_result($striperesult, 0, 'token');


            echo'
            <div class="span12">
            <a style="text-decoration:none;color:#333;" href="fullsize2.php?imageid=',$imagecartid,'">
            <table class="table">
            <thead>
            <tr>
            <th>Photo</th>
            <th>Size</th>
            <th>Image ID</th>
            <th>License</th>
            <th>Price</th>  
            </tr>
            </thead>
            <tbody>
            <tr>
            <td><div style="min-width:400px;height:<?php echo $height; ?>px;width:<?php echo $width; ?>px;"><img onmousedown="return false" oncontextmenu="return false;" src="',$imagesource[$iii],'" height=',$height,' width=',$width,' /></div></td>
            <td>Medium ',$photogemail,'</td>
            <td>',$imagecartid,'</td>
            <td>',$stripepubkey,'</td>
            <td>',$stripekey,'</td>
             <td>Royalty Free</td>
            <td>$',$imageprice[$iii],'</td>
            </tr>
            <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            </tr>
            </tbody>
            </table>
            </a>
            </div>';

        }
        
        //check if image already in db
        $found = strpos($cartidlist, $imageid);
        
        if($imageid && $found === false) {
        //New image displayed
        echo'
         <div class="span12">
            <a style="text-decoration:none;color:#333;" href="fullsize2.php?imageid=',$imageid,'">
            <table class="table">
            <thead>
            <tr>
            <th>Photo</th>
            <th>Size</th>
            <th>Image ID</th>
            <th>License</th>
            <th>Price</th>  
            </tr>
            </thead>
            <tbody>
            <tr>
            <td><div style="width:400px;"><img onmousedown="return false" oncontextmenu="return false;" style="height:25%;" src="',$imagenewsource3,'" /></div></td>
            <td>Medium</td>
            <td>',$imageid,'</td>
            <td>Royalty Free</td>
            <td>$',$imagenewprice,'</td>
            </tr>
            <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            </tr>
            </tbody>
            </table>
            </a>
            </div>
            
            <div><a class="btn btn-success" href="',$_SERVER['HTTP_REFERER'],'">Continue Shopping</a>
            </div>';
        }
        
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
<input type="hidden" name="firstname" value="',$firstname,'">
<input type="hidden" name="lastname" value="',$lastname,'">
<input type="hidden" name="image" value="',$image,'">
<input type="hidden" name="label" value="',$label,'">
<input type="hidden" name="imageID" value="',$imageID,'">
<input type="hidden" name="customeremail" value="',$customeremail,'">


                <label class="creditcards" style="margin-bottom:10px;">Card Number. We accept:<img src="card.jpg" style="width:215px;height:25px;margin-top:4px;border-radius:2px;"/> </label> 
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
</div>
</div>';

        
        
        
        
 } //end if logged in
 



if($_GET['charge'] == 1){

    for($iii=0; $iii < 4; $iii++) {

      echo'<script type="text/javascript">
         var key = "<? print $stripepubkey; ?>";
            Stripe.setPublishableKey(key);
            var mytext = "Hello again";
  document.write(mytext);
 </script>';


 }














//Stripe::setPubKey($stripepubkey);

Stripe::setApiKey($stripekey);

//for($iii=0; $iii < 3; $iii++) {

$token = $_POST['stripeToken'];
//Stripe::setApiKey($stripekey);
$newprice = 20000;
$photorankrfee = $newprice*.3;

// create the charge on Stripe's servers - this will charge the user's card
  $charge = Stripe_Charge::create(array(
    "amount" => $newprice, // amount in cents, again
   "currency" => "usd",
  "card" => $token
   )
  );

}

//}










?>


</div>


</div>
</body>
</html>