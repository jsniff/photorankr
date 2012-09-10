<?php

$size = mysql_real_escape_string($_POST['size']);

if(!$size) {
    $size = 'Large';
} 

$width = mysql_real_escape_string($_POST['width']);

if(!$width) {
    $width = mysql_real_escape_string($_POST['originalwidth']);
}

$height = mysql_real_escape_string($_POST['height']);

if(!$height) {
    $height = mysql_real_escape_string($_POST['originalheight']);
}

$price = mysql_real_escape_string($_POST['price']);

if(!$price) {
    $price = mysql_real_escape_string($_POST['originalprice']);
}

$imageid = mysql_real_escape_string($_POST['imageid']);

$multiseat = mysql_real_escape_string($_POST['multiseat']);
$unlimited = mysql_real_escape_string($_POST['unlimited']);
$resale = mysql_real_escape_string($_POST['resale']);
$electronic = mysql_real_escape_string($_POST['electronic']);

if($multiseat == 'checked') {
    $licenses = ' Multi-Seat,';
    $price += 20;
}
if($unlimited == 'checked') {
    $licenses = $licenses . ' Unlimited Reproduction / Print Runs,';
    $price += 35;
}
if($resale  == 'checked') {
    $licenses = $licenses . ' Items for Resale,';
    $price += 35;
}
if($electronic == 'checked') {
    $licenses = $licenses . ' Electronic Use,';
    $price += 35;
}

if(!$licenses) {
    $licenses = 'Standard Use';
}


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
            


//     $currentnots = "SELECT * FROM userinfo WHERE emailaddress = '$photogemail'";
// $currentnotsquery = mysql_query($currentnots);
// $currentnotsresult = mysql_result($currentnotsquery, 0, "notifications");


// $emailtrial = "tyler.sniff@gmail.com";

//  $getstripeinfo = "SELECT * FROM userinfo WHERE emailaddress = '$photogemail'";
// $striperesult = mysql_query($getstripeinfo); 
// $stripepubkey = mysql_result($striperesult, 0, 'pubkey');
// echo $stripepubkey;
// echo "whatup";


// $getstripeinfo = "SELECT * FROM userinfo WHERE emailaddress = '$photogemail'";
// $striperesult = mysql_query($getstripeinfo); 
// $stripekey = mysql_result($striperesult, 0, 'token');
// echo $stripekey;

}


 //REMOVE PHOTO QUERY
    if(htmlentities($_GET['action']) == "removed") { 
        $removeid = $_GET['imageid'];
        $querycheck = mysql_query("SELECT emailaddress FROM cart WHERE imageid = '$removeid'");
        $emailcheck = mysql_result($querycheck,0,'emailaddress');
        if($repemail == $emailcheck) {
            $removequery = mysql_query("DELETE FROM cart WHERE imageid = '$removeid' AND emailaddress = '$repemail'");
        }
    }
           

?>



<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
"http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Your Cart</title>
<meta name="Your Cart"></meta>
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
       
        echo'<div style="padding:15px;padding-right:200px;background-color:#ddd;width:130px;margin-left:25px;margin-top:20px;"><span style="font-size:27px;font-weight:200;">Your Cart</span></div><br />';
        
        if($imageid) {
        $cartcheck = mysql_query("SELECT * FROM cart WHERE imageid = '$imageid' && emailaddress = '$repemail' ORDER BY id DESC");
        $numincart = mysql_num_rows($cartcheck);
        if($numincart < 1) {
            $stickincart = mysql_query("INSERT INTO cart (source,size,width,height,license,price,emailaddress,imageid) VALUES ('$imagenewsource3','$size','$width','$height','$licenses','$price','$repemail','$imageid')");
            }
        }
        
        $incart = mysql_query("SELECT * FROM cart WHERE emailaddress = '$repemail' ORDER BY id DESC");
        $incartresults = mysql_num_rows($incart);
                
        for($iii=0; $iii < $incartresults; $iii++) {
            $imagesource[$iii] = mysql_result($incart,$iii,'source');
            $imageprice[$iii] = mysql_result($incart,$iii,'price');
            $imagecartid = mysql_result($incart,$iii,'imageid');
            $imagelicenses = mysql_result($incart,$iii,'license');
            $standard = strpos($imagelicenses,'Standard');
            if($standard === false) { 
                $imagelicenses = substr($imagelicenses, 0, -1); 
            }
            $imagesize = mysql_result($incart,$iii,'size');
            $emailquery = mysql_query("SELECT emailaddress FROM photos WHERE id = '$imagecartid'");
            $photogemail = mysql_result($emailquery,0,'emailaddress');
            $totalcharge = $totalcharge + $imageprice[$iii];
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
            <th>License(s)</th>
            <th>Price</th>  
            </tr>
            </thead>
            <tbody>
            
            <tr>
            <td><div style="min-width:400px;height:<?php echo $height; ?>px;width:<?php echo $width; ?>px;"><img onmousedown="return false" oncontextmenu="return false;" src="',$imagesource[$iii],'" height=',$height,' width=',$width,' /><br /><br />
           <!-- <div style="text-align:left;"><a style="color:#aaa;font-size:12px;" href="download2.php?imageid=',$imagecartid,'&action=removed">Remove from cart</a></div>--></div>
            </td>
            <td style="width:140px;">',$imagesize,'</td>
            <td style="width:140px;">',$imagelicenses,'</td>
            <td style="width:140px;">$',$imageprice[$iii],'</td>
            </tr>

            </tbody>
            </table>
            </a>
            </div>';

        }
        
        /*check if image already in db
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
            </div>';
        } 
           */
        
    
 //TOTAL CHARGES           
    if($incartresults > 0) {
    
         echo'
        <div><a class="btn btn-success" style="margin-left:30px;" href="',$_SERVER['HTTP_REFERER'],'">Continue Shopping</a>
        </div>';
        
            echo'<div class="grid_18"><a name="added" style="color:black;text-decoration:none;" href="#"><div style="padding:15px;padding-right:200px;background-color:#ddd;width:130px;margin-left:25px;margin-top:50px;"><span style="font-size:22px;font-weight:200;">Payment</span></div></a>
        
        <div class="span12">
        <table class="table">
            <thead>
            <tr>
            <th># Photos</th>
            <th>Total Price</th>
            </thead>
            
            <tbody>
        
            <tr>
            <td style="width:760px;">',$incartresults,'</td>
            <td>$',$totalcharge,'</td>
            </tr>
        
            </tbody>
            </table>
        </div>
        
        </div><br />';
        
    }

if($totalcharge > 0) {

echo'
    
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

 <div class="grid_20" style="margin-top:35px;">
         <label class="creditcards" style="float:left;font-size:16px;">We accept:&nbsp;&nbsp;<img src="card.jpg" style="width:215px;height:25px;margin-top:0px;border-radius:2px;"/> </label> <br /><br /><br />
       
  <label style="float:left;font-size:16px;" class="creditcards">First Name:&nbsp;&nbsp;</label>
         <input style="float:left;font-size:15px;padding:6px;position:relative;top:-7px;width:170px;" type="text" name="firstname" size="20" autocomplete="off" class="card-number" style;"/>

  <label style="float:left;font-size:16px;" class="creditcards">Last Name:&nbsp;&nbsp;</label>
         <input style="float:left;font-size:15px;padding:6px;position:relative;top:-7px;width:170px;" type="text" name="lastname" size="20" autocomplete="off" class="card-number" style;"/>

           <label style="float:left;font-size:16px;" class="creditcards">Credit Card Type:&nbsp;&nbsp;</label>
         <input style="float:left;font-size:15px;padding:6px;position:relative;top:-7px;width:170px;" type="text" name = "cardtype" size="20" autocomplete="off" class="card-number" style;"/>

         <label style="float:left;font-size:16px;" class="creditcards">Card Number:&nbsp;&nbsp;</label>
         <input style="float:left;font-size:15px;padding:6px;position:relative;top:-7px;width:170px;" type="text" name = "cardnumber" size="20" autocomplete="off" class="card-number" style;"/>
            
                <label style="float:left;padding-left:10px;font-size:16px;" class="creditcards">CVC <span style="font-size:15px;">(Verification #):</span>&nbsp;&nbsp;</label>
                <input style="float:left;font-size:16px;padding:6px;position:relative;top:-7px;width:40px;" type="text" name = "cv2number" size="4" autocomplete="off" class="card-cvc"/>
                
                <label style="float:left;padding-left:10px;font-size:16px;" class="creditcards" >Expiration: <span style="font-size:15px;"></span>&nbsp;&nbsp;</label>
                <input type="text" style="float:left;width:50px;padding:6px;position:relative;top:-7px;width:30px;font-size:16px;" name = "expdatemonth" class="card-expiry-month"/>
                <span style="float:left;font-size:30px;font-weight:100;margin-top:-10px;">&nbsp;/&nbsp;</span>
                <input style="float:left;padding:6px;position:relative;top:-7px;width:60px;font-size:16px;" type="text" name = "year" class="card-expiry-year"/><br /><br /><br />
               
           <label style="float:left;font-size:16px;" class="creditcards">Address:&nbsp;&nbsp;</label>
         <input style="float:left;font-size:15px;padding:6px;position:relative;top:-7px;width:170px;" type="text" name = "address" size="20" autocomplete="off" class="card-number" style;"/>

           <label style="float:left;font-size:16px;" class="creditcards">City:&nbsp;&nbsp;</label>
         <input style="float:left;font-size:15px;padding:6px;position:relative;top:-7px;width:170px;" type="text" name = "city" size="20" autocomplete="off" class="card-number" style;"/>


 <label style="float:left;font-size:16px;" class="creditcards">State:&nbsp;&nbsp;</label>
         <input style="float:left;font-size:15px;padding:6px;position:relative;top:-7px;width:170px;" type="text" name = "state" size="20" autocomplete="off" class="card-number" style;"/>


 <label style="float:left;font-size:16px;" class="creditcards">Zip:&nbsp;&nbsp;</label>
         <input style="float:left;font-size:15px;padding:6px;position:relative;top:-7px;width:170px;" type="text" name = "zipcode" size="20" autocomplete="off" class="card-number" style;"/>


 <label style="float:left;font-size:16px;" class="creditcards">Country:&nbsp;&nbsp;</label>
         <input style="float:left;font-size:15px;padding:6px;position:relative;top:-7px;width:170px;" type="text" name = "country" size="20" autocomplete="off" class="card-number" style;"/>

   <button type="submit" class="button submit btn btn-success" style="font-size:16px;float:left;margin-top:5px;padding-top:10px;padding-bottom:10px;padding-right:40px;padding-left:40px;font-weight:200;">Submit Payment</button>
   </form>
   <br /><br /><br /><div></div>
        </div>'; 
}



elseif($totalcharge == 0 && $incartresults > 0) {
         
         echo'
            <form name="download_form" method="post" action="myprofile.php?view=store&option=cart&action=download">';
          
            foreach($sourcelist as $value) {
                echo '<input type="hidden" name="downloadedimages[]" value="'. $value. '">';
            }
            
            foreach($idlist as $value) {
                echo '<input type="hidden" name="imagesid[]" value="'. $value. '">';
            }
            
            echo'
            <button type="submit" name="submit" value="download" class="button submit btn btn-success"  style="font-size:16px;font-weight:200;width:295px;height:40px;">Download Free</button>
            </form>';
         
    }
    
if($incartresults == 0) {

    echo'<div style="margin-left:380px;font-size:25px;margin-top:180px;font-family:helvetica neue,helvetica,arial;font-weight:200;">Your cart is empty</div>';

}

                        
 } //end if logged in


if($_GET['charge'] == 1){



 
$environment = 'live';  // or 'beta-sandbox' or 'live'
 
function PPHttpPost($methodName_, $nvpStr_) {
        global $environment;
 
    // Set up your API credentials, PayPal end point, and API version.
 $API_UserName = urlencode('photorankr_api2.photorankr.com');
    $API_Password = urlencode('GDXGAJQZK7DFFRFY');
    $API_Signature = urlencode('AIloodktrq1eS0t7zyszxtmBoLm6Ah08o2sBNi3Yd6Fc8C1lQYOTKa1y');
    $API_Endpoint = "https://api-3t.paypal.com/nvp";
    if("sandbox" === $environment || "beta-sandbox" === $environment) {
        $API_Endpoint = "https://api-3t.$environment.paypal.com/nvp";
    }
    $version = urlencode('51.0');
 
    // Set the curl parameters.
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
 
    // Turn off the server and peer verification (TrustManager Concept).
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
 
    // Set the API operation, version, and API signature in the request.
    $nvpreq = "METHOD=$methodName_&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr_";
 
    // Set the request as a POST FIELD for curl.
    curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);
 
    // Get response from the server.
    $httpResponse = curl_exec($ch);
 
    if(!$httpResponse) {
        exit("$methodName_ failed: ".curl_error($ch).'('.curl_errno($ch).')');
    }
 
    // Extract the response details.
    $httpResponseAr = explode("&", $httpResponse);
 
    $httpParsedResponseAr = array();
    foreach ($httpResponseAr as $i => $value) {
        $tmpAr = explode("=", $value);
        if(sizeof($tmpAr) > 1) {
            $httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
        }
    }
 
    if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
        exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
    }
 
    return $httpParsedResponseAr;
}

     $paymentType = urlencode('Authorization');             // or 'Sale'

 $firstName = urlencode($_POST["firstname"]);
 $lastName = urlencode($_POST["lastname"]);
 $creditCardType = urlencode($_POST["cardtype"]);
$creditCardNumber = urlencode($_POST["cardnumber"]);
 $expDateMonth = $_POST["expdatemonth"];
// // Month must be padded with leading zero
 $padDateMonth = urlencode(str_pad($expDateMonth, 2, '0', STR_PAD_LEFT));
 
 $expDateYear = urlencode($_POST["year"]);
$cvv2Number = urlencode($_POST["cv2number"]);
 $address1 = urlencode($_POST["address"]);
//$address2 = urlencode('Princeton University');
$city = urlencode($_POST["city"]);
 $state = urlencode($_POST["state"]);
 $zip = urlencode($_POST["zipcode"]);
 $country = urlencode($_POST["country"]);                // US or other valid country code
  $amount = $totalcharge;
$currencyID = urlencode('USD'); 



// Add request-specific fields to the request string.
$nvpStr =   "&PAYMENTACTION=$paymentType&AMT=$amount&CREDITCARDTYPE=$creditCardType&ACCT=$creditCardNumber".
            "&EXPDATE=$padDateMonth$expDateYear&CVV2=$cvv2Number&FIRSTNAME=$firstName&LASTNAME=$lastName".
            "&STREET=$address1&CITY=$city&STATE=$state&ZIP=$zip&COUNTRYCODE=$country&CURRENCYCODE=$currencyID";
 
// Execute the API operation; see the PPHttpPost function above.
$httpParsedResponseAr = PPHttpPost('DoDirectPayment', $nvpStr);
 


if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
for($iii=0; $iii < $incartresults; $iii++) {
$imagesource = mysql_result($incart,$iii,'source');
$imageprice = mysql_result($incart,$iii,'price');
$imagecartid = mysql_result($incart,$iii,'imageid');
$imagewidth = mysql_result($incart,$iii,'width');
$imageheight = mysql_result($incart,$iii,'height');
$emailquery = mysql_query("SELECT emailaddress FROM photos WHERE id = '$imagecartid'");

$photogemail = mysql_result($emailquery,0,'emailaddress');
$stickintouserdownloads = mysql_query("INSERT INTO buyerdownloads (emailaddress,imageid,source,width,height) VALUES ('$repemail','$imagecartid','$imagesource','$imagewidth','$imageheight')");
 $deletephotofromcart = mysql_query("DELETE FROM cart WHERE emailaddress = '$repemail' AND imageid = '$imagecartid'");
    }      


exit('Payment Successful');

}


else  {
    exit('Payment Unsuccesful');
}


//         var form$ = $("#payment-form");

//<meta http-equiv="refresh" content="0;url=http://photorankr.com/account.php?view=download">    
}           


?>


</div>
</div>


</body>
</html>