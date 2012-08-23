<?php

//connect to the database
require "db_connection.php";
require "functionscampaigns3.php";
//require_once("stripe/lib/Stripe.php");

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
            $imageprice = mysql_result($incart,$iii,'price');
            $imagecartid = mysql_result($incart,$iii,'imageid');
            $emailquery = mysql_query("SELECT emailaddress FROM photos WHERE id = '$imagecartid'");
            $photogemail = mysql_result($emailquery,0,'emailaddress');
            $totalcartprice = $imagecartid+$totalcartprice;
            $cartidlist = $cartidlist.",".$imagecartid;
            list($width, $height)=getimagesize($imagesource[$iii]);
            $width = $width/4;
            $height = $height/4;
            
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
            <td>Royalty Free</td>
            <td>$',$imageprice,'</td>
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
        
        echo'<div id="topbar" style="height:100px;padding-top:30px;font-size:22px;font-family:helvetica;font-weight:100;"><div style="text-align:center;padding-left:10px;padding-right:10px;">PhotoRankr is on the verge of even bigger and better things.  &nbsp;We appreciate your support and loyalty in our early days.  &nbsp;As a thank you, we are offering you a lifetime of access to the highest upcoming subscription level for a low, one-time fee.  &nbsp;As we grow, we want to help you grow as a photographer.</div>
    </div>  
<div class="container_24">
    <div class="grid_22 push_1" id="formshell">
        <div id="container">
        <div class="grid_11" id="form">
        <!--<form action="none" method="">-->
            <h1 class="description"> Lifetime Subscription Offer</br> ',$daysleft,' Days, ',$hoursleft,' Hours Left</h1>
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
        <h1 class="description" style="text-align:center;"> Secure Payment with Stripe </h1>
        <div class="upload">
            <fieldset id="formfields">
    <div style="color:black;background-color:white;width:350px;font-family:helvetica;font-weight:200;height;200px;">

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

<div style="text-align:left;">
                <label class="creditcards" style="margin-bottom:10px;">Card Number. We accept:<img src="card.jpg" style="width:215px;height:25px;margin-top:4px;border-radius:2px;"/> </label> 
                <input type="text" size="20" autocomplete="off" class="card-number" style;"/>
            </div>
              <div class="form-row" style="text-align:left;">
                <label class="creditcards">First Name</label>
                <input type="text" size="4" autocomplete="off" class="card-name"/>
            </div>
            <div class="form-row" style="text-align:left;">
                <label class="creditcards">Last Name</label>
                <input type="text" size="4" autocomplete="off" class="card-name"/>
            </div>
            <div class="form-row" style="text-align:left;">
                <label class="creditcards">CVC (Verification #)</label>
                <input type="text" size="4" autocomplete="off" class="card-cvc"/>
            </div>
            <div class="form-row" style="text-align:left;">
                <label class="creditcards" >Expiration (MM/YYYY)</label>
                <input type="text" style="width:50px" size="2" class="card-expiry-month"/>
                <span style="font-size: 22px"> / </span>
                <input type="text" style="width:100px" size="4" class="card-expiry-year"/>
              </div>
           <div class="">  
   <button type="submit" class="button submit btn btn-success" style="text-align:center;font-size:16px;margin-top:5px;padding-top:10px;padding-bottom:10px;padding-right:55px;padding-left:50px;">Submit Payment</button>

</div>
           

    </div>     
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
        
        
        
        
        
        
 } //end if logged in
 

?>


</div>

</div>

</br>
	<div class="container_24" style="float:center;margin-top:-175px;">
	<?php footer(); ?>
	</div>

</body>

</html>