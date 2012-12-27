<?php

//connect to the database
require "db_connection.php";
require "functions.php";

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
    
    //Get user info
    $findreputationme = mysql_query("SELECT user_id,reputation,profilepic,firstname,lastname FROM userinfo WHERE emailaddress = '$email'");
    $sessionfirst =  mysql_result($findreputationme,0,'firstname');
    $sessionlast =  mysql_result($findreputationme,0,'lastname');

    //Time
    $currenttime = time();
    
    //View
    $view = mysql_real_escape_string(htmlentities($_GET['view']));
    
?> 


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="https://www.w3.org/1999/xhtml">

<head>

 <meta name="Generator" content="EditPlus">
  <meta name="Author" content="PhotoRankr, PhotoRankr.com">
  <meta name="Keywords" content="photos, sharing photos, photo sharing, photography, photography club, sell photos, sell photography, where to sell my photography, good sites for selling photography, making money from photography, making money off photography, social networking, social network, social networks, where to sell my photos, good sites for selling photos, good site to sell photos, making money from photos">
  <meta name="Description" content="A gallery of the newest photography, photographers, and exhibits on PhotoRankr.">
     <meta name="viewport" content="width=1200" /> 

	 <link rel="stylesheet" type="text/css" href="css/style.css"/>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>
	<link rel="stylesheet" type="text/css" href="css/960grid.css"/>
	<link rel="stylesheet" type="text/css" href="css/reset.css"/> 
    <link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>
    
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery.wookmark.js"></script>            
    <link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>

    <title>The PhotoRankr Market</title>

<!--GOOGLE ANALYTICS CODE-->
<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-28031297-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'https://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>

</head>
<body style="overflow-x:hidden; background-color:rgb(244, 244, 244);">

<?php navbar(); ?>

   <!--big container-->
    <div id="container" class="container_24">
    
<div class="grid_16" style="margin-left:-50px;">
    
<?php
        
      if($view == 'maybe') {  
    
        if($_GET['action'] == 'remove') {
        
            $removedphoto = mysql_real_escape_string($_GET['pd']);
            $removephoto = mysql_query("DELETE FROM usersmaybe WHERE id = '$removedphoto' AND emailaddress = '$email'");
         
        }
        
        echo'<div class="grid_18 bigText" style="position:relative;top:-20px;">My Wish List</div>';

        $marketquery = mysql_query("SELECT * FROM usersmaybe WHERE emailaddress = '$email'");
                $numsavedinmarket = mysql_num_rows($marketquery);
                
                echo'<div id="thepics" style="width:740px;">
                     <div id="main">';
          
                for($iii=0; $iii<$numsavedinmarket; $iii++) {
                        $photo[$iii] = mysql_result($marketquery, $iii, "source");
                        $photo2[$iii] = str_replace("http://photorankr.com/userphotos/","../userphotos/medthumbs/", $photo[$iii]);
                        $photoid[$iii] = mysql_result($marketquery, $iii, "id");
                        $imageid[$iii] = mysql_result($marketquery, $iii, "imageid");
                        $caption = mysql_result($marketquery, $iii, "caption");
                        $caption = strlen($caption) > 30 ? substr($caption,0,27). " &#8230;" : $caption;
                        $price = mysql_result($marketquery, $iii, "price");

                        list($height,$width) = getimagesize($photo2[$iii]);
                        $widthnew = $width / 2.8;
                        $heightnew = $height / 2.8;
                
                echo'
                  <div class="fPic" id="',$views,'" style="float:left;height:240px;width:240px;padding-left:1px;padding-bottom:1px;overflow:hidden;">
                 
                <a href="fullsizemarket.php?imageid=',$imageid[$iii],'">
                <img onmousedown="return false" oncontextmenu="return false;"  style="height:240px;min-width:240px;"  alt="',$caption,'" src="',$photo2[$iii],'" height="',$heightls,'px" width="',$widthls,'px" /></a>
                
                <div style="height:30px;background-color:rgba(34,34,34,.8);width:240px;position:relative;top:-30px;padding:8px;">
                 <a style="color:white;font-size:14px;font-weight:300;" name="removed" href="cart.php?view=maybe&pd=',$photoid[$iii],'&action=remove#return">Click to Remove</button></a>
                 </div>
                 
                 </div>';
   
                }
        
        echo'</div>
             </div>';
        
}


    elseif($view == 'purchases') {  
    
    //add in code from confirmpaymentpaypal.php
    
    $tokengetexpress = addslashes($_REQUEST['token']);
    $identity = addslashes($_REQUEST['identities']);
    $paymentamount = addslashes($_REQUEST['paymentAmount']);
    
    echo $tokengetexpress .'ere';
    echo $identity .'ere';
    echo  $paymentamount.'ere';
 
$environment = 'live';	// or 'beta-sandbox' or 'live'
 
/**
 * Send HTTP POST Request
 *
 * @param	string	The API method name
 * @param	string	The POST Message fields in &name=value pair format
 * @return	array	Parsed HTTP Response body
 */
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
 
	// setting the curl parameters.
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);
 
	// Set the curl parameters.
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
		exit('$methodName_ failed: '.curl_error($ch).'('.curl_errno($ch).')');
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
 
/**
 * This example assumes that a token was obtained from the SetExpressCheckout API call.
 * This example also assumes that a payerID was obtained from the SetExpressCheckout API call
 * or from the GetExpressCheckoutDetails API call.
 */
// Set request-specific fields.
$payerID = urlencode($identity);
$token = urlencode($tokengetexpress);

	//$paymentamount = $httpParsedResponseAr['paymentAmount'];

 
$paymentType = urlencode("Authorization");			// or 'Sale' or 'Order'
//$paymentAmount = urlencode("2");
$currencyID = urlencode("USD");						// or other currency code ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')
 
// Add request-specific fields to the request string.

$nvpStr = "&TOKEN=$token&PAYERID=$payerID&PAYMENTACTION=$paymentType&AMT=$paymentamount&CURRENCYCODE=$currencyID";
 
// Execute the API operation; see the PPHttpPost function above.
$httpParsedResponseAr = PPHttpPost('DoExpressCheckoutPayment', $nvpStr);
 
if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
	exit('Express Checkout Payment Completed Successfully: '.print_r($httpParsedResponseAr, true));
} else  {
	exit('DoExpressCheckoutPayment failed: ' . print_r($httpParsedResponseAr, true));
}

        echo'<script>
            jQuery(document).ready(function(){
                jQuery("#hideshow").live("click", function(event) {        
                    jQuery("#creditcard").toggle();
                });
            });
        </script>';
                
        echo'<div class="grid_18 bigText" style="position:relative;top:-20px;">My Purchases</div>';

            $downloadquery = mysql_query("SELECT * FROM userdownloads WHERE emailaddress = '$email'");
            $numpurchased = mysql_num_rows($downloadquery);
            
            echo'<div id="thepics" style="width:740px;">
                 <div id="main">';
          
                for($iii=0; $iii<$numpurchased; $iii++) {
                
                        $photo[$iii] = mysql_result($downloadquery, $iii, "source");
                        $photo2[$iii] = str_replace("http://photorankr.com/","../", $photo[$iii]);
                        $photoid[$iii] = mysql_result($downloadquery, $iii, "id");
                        $imageid[$iii] = mysql_result($downloadquery, $iii, "imageid");
                        $captionquery =  mysql_query("SELECT caption FROM photos WHERE id = '$imageid[$iii]'");
                        $caption = mysql_result($captionquery, 0, "caption");
                        $caption = strlen($caption) > 20 ? substr($caption,0,17). " &#8230;" : $caption;

                        list($height,$width) = getimagesize($photo2[$iii]);
                        $widthnew = $width / 2.7;
                        $heightnew = $height / 2.7;
                
                echo'
                <form action="downloadphoto.php" method="POST" name="download">
                   <input type="hidden" name="image" value="',$photo[$iii],'">
                   <div class="fPic" id="',$views,'" style="float:left;height:240px;width:240px;padding-left:1px;padding-bottom:1px;overflow:hidden;">
                        <input type="image" style="height:240px;min-width:240px;"  alt="',$caption,'" src="',$photo[$iii],'" height="',$heightnew,'px" width="',$widthnew,'px" />
                   </div>
                </form>';
                   
                }
        
        echo'</div>
             </div>';
        
}
        
        
        elseif($view == '') {  
        
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
            
        
            echo'<div id="container" class="grid_18" style="width:800px;margin-top:20px;padding-left:20px;">';
            
            echo'<div class="grid_18 bigText" style="position:relative;top:-20px;">My Cart</div>';
            
            if(htmlentities($_GET['action']) == 'download') {
               
               $images = $_POST['downloadedimages'];
               $imagesid = $_POST['imagesid'];

               $numberimages = count($images);
    		
                for($i=0; $i < $numberimages; $i++) {

                    $images[$i] = mysql_real_escape_string($images[$i]);
                    $imagesid[$i] = mysql_real_escape_string($imagesid[$i]);
                    
                    $downloadcheck = mysql_query("SELECT * FROM userdownloads WHERE imageid = '$imagesid[$i]'");
                    $downloadcheckrows = mysql_num_rows($downloadcheck);
                    
                    if($downloadcheckrows < 1) {
                    
                        $stickintouserdownloads = mysql_query("INSERT INTO userdownloads (emailaddress,imageid,source) VALUES ('$email','$imagesid[$i]','$images[$i]')");
                        $deletephotofromcart = mysql_query("DELETE FROM userscart WHERE emailaddress = '$email' AND imageid = '$imagesid[$i]'");
                        $addsoldtonewsfeed = mysql_query("INSERT INTO newsfeed (firstname,lastname,emailaddress,type,source,time) VALUES ('$sessionfirst','$sessionlast,','$email','sold','$imagesid[$i]','$currenttime')");
                    
                        //Tell them download was successful
                        echo'<div style="font-size:16px;font-weight:200;margin-top:20px;margin-left:35px;"><img src="',$images[$i],'" height="40" width="40" />&nbsp;&nbsp;&nbsp;Photo Saved in Purchases </div>';
                    
                    }
                 
                }
                 
            }
         
         
    //PHOTO CART INFORMATION
    $imagequery = mysql_query("SELECT source,price FROM photos WHERE id = '$imageid'");
    $imagenewsource = mysql_result($imagequery,0,'source');
    $imagenewsource2 = str_replace("userphotos/", "$_SERVER[DOCUMENT_ROOT]/userphotos/",$imagenewsource);
    $imagenewsource3 = str_replace("$_SERVER[DOCUMENT_ROOT]/userphotos/", "http://photorankr.com/userphotos/",$imagenewsource2); 
    $imagenewprice = mysql_result($imagequery,0,'price'); 
    
    //ADD TO CART IN DB
    
        if($_SESSION['loggedin'] != 1) {
        echo'
        <div style="margin-top:70px;margin-left:260px;padding-bottom:150px;">
        <div style="text-align:center;font-size:18px;">Login Below or <a href="signup3.php">Register to Buy:</a></div><br />
        <form name="login_form" method="post" action="fullsizemarket.php?imageid=',$imageid,'&action=login">
        <div class="well" style="width:380px;padding-top:50px;padding-bottom:50px;padding-left:40px;">
        <span style="font-size:18px;font-family:helvetica, arial;margin-left:0px;">Email: </span><input type="text" style="width:200px;margin-left:40px;" name="emailaddress" /><br />
        <span style="font-size:18px;font-family:helvetica, arial;">Password: </span>&nbsp<input type="password" style="width:200px;" name="password"/><br >
        <input type="submit" class="btn btn-success" style="margin-left:250px;" value="sign in" id="loginButton"/>
        </div>
        </form>
        </div>';
        
        }
    
        elseif($_SESSION['loggedin'] == 1) {
       
        if($imageid) {
        $cartcheck = mysql_query("SELECT * FROM userscart WHERE imageid = '$imageid' && emailaddress = '$email'");
        $numincart = mysql_num_rows($cartcheck);
        if($numincart < 1) {
            $stickincart = mysql_query("INSERT INTO userscart (source,size,width,height,license,price,emailaddress,imageid) VALUES ('$imagenewsource3','$size','$width','$height','$licenses','$price','$email','$imageid')");
            }
        }
        
        $incart = mysql_query("SELECT * FROM userscart WHERE emailaddress = '$email' ORDER BY id ASC");
        $incartresults = mysql_num_rows($incart);
        
        for($iii=0; $iii < $incartresults; $iii++) {
            $imagesource[$iii] = mysql_result($incart,$iii,'source');
            $imageprice[$iii] = mysql_result($incart,$iii,'price');
            $imagecartid = mysql_result($incart,$iii,'imageid');
            $sourcelist[] .= $imagesource[$iii];
            $idlist[] .= $imagecartid;
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
            
            echo'
            <div class="span9">
            <a name="',$imagecartid,'" style="text-decoration:none;color:#333;" href="fullsizemarket.php?imageid=',$imagecartid,'">
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
        
        /* check if image already in db
        $found = strpos($cartidlist, $imageid);
        
        if($imageid && $found === false) {
        //New image displayed
        echo'
         <div class="span12">
            <a style="text-decoration:none;color:#333;" href="fullsizemarket.php?imageid=',$imageid,'">
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
            
        } */
        
        
    if($incartresults > 0) {

            echo'<div class="grid_18"><a name="added" style="color:black;text-decoration:none;" href="#"><div style="padding:15px;padding-right:200px;background-color:#ddd;width:180px;margin-left:25px;margin-top:50px;"><span style="font-size:22px;font-weight:200;">Confirm Payment</span></div></a>
        
        <div style="margin-left:20px;">
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
        </div>';
        
        /*-----------------SUBMIT YOUR PAYPAL PAYMENT-------------------*/

/** GetExpressCheckoutDetails NVP example; last modified 08MAY23.
 *
 *  Get information about an Express Checkout transaction. 
*/
 
$environment = 'live';	// or 'beta-sandbox' or 'live'
 
/**
 * Send HTTP POST Request
 *
 * @param	string	The API method name
 * @param	string	The POST Message fields in &name=value pair format
 * @return	array	Parsed HTTP Response body
 */
         
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
 
 	$methodName_="GetExpressCheckoutDetails";
	// Set the API operation, version, and API signature in the request.
	$nvpreq = "METHOD=$methodName_&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr_";
 
	// Set the request as a POST FIELD for curl.
	curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);
 
	// Get response from the server.
	$httpResponse = curl_exec($ch);
 
	if(!$httpResponse) {
		exit('$methodName_ failed: '.curl_error($ch).'('.curl_errno($ch).')');
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
 
/**
 * This example assumes that this is the return URL in the SetExpressCheckout API call.
 * The PayPal website redirects the user to this page with a token.
 */
 
// Obtain the token from PayPal.
if(!array_key_exists('token', $_REQUEST)) {
	exit('Token is not received.');
}
 
// Set request-specific fields.
$token = urlencode(htmlspecialchars($_REQUEST['token']));
 
// Add request-specific fields to the request string.
$nvpStr = "&TOKEN=$token";
 
// Execute the API operation; see the PPHttpPost function above.
$httpParsedResponseAr = PPHttpPost('GetExpressCheckoutDetails', $nvpStr);
 
if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
	// Extract the response details.
	$payerID = $httpParsedResponseAr['PAYERID'];
	$street1 = $httpParsedResponseAr["SHIPTOSTREET"];
	if(array_key_exists("SHIPTOSTREET2", $httpParsedResponseAr)) {
		$street2 = $httpParsedResponseAr["SHIPTOSTREET2"];
	}
	$city_name = $httpParsedResponseAr["SHIPTOCITY"];
	$state_province = $httpParsedResponseAr["SHIPTOSTATE"];
	$postal_code = $httpParsedResponseAr["SHIPTOZIP"];
	$country_code = $httpParsedResponseAr["SHIPTOCOUNTRYCODE"];



$incart = mysql_query("SELECT * FROM cart WHERE emailaddress = '$repemail' ORDER BY id DESC");
                 $incartresults = mysql_num_rows($incart);


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
 
 $totalprice = $totalprice + $imageprice;
    }      
 // echo $totalprice;
   // echo $incartresults;
   // echo $imageprice;

//$stringprice = (string) $totalprice;
//$paymentAmount = urlencode($stringprice); 
$stringprice = (string) $totalcharge;
$paymentAmount = urlencode($stringprice); 

    
echo'
  <div style="margin-top:100px;">  
    <span class="payment-errors" style="font-weight:bold;font-size:15px;"></span>
      <form action= "cart.php?view=purchases" method="POST">
        <div class="form-row" style="margin-left:25px;">           
            <input type="hidden" name="token" value="',$token,'">
            <input type="hidden" name="identities" value="',$payerID,'">
            <input type="hidden" name="paymentAmount" value="',$paymentAmount,'">
            <button type="submit" class="button submit btn btn-success" style="font-size:16px;font-weight:200;width:295px;height:40px;float:right;margin-top:-30px;">Submit Payment</button>
        </div>
     </form>';


} else  {
	exit('GetExpressCheckoutDetails failed: ' . print_r($httpParsedResponseAr, true));
}
        
        echo'</div><br />';
        
        }
    
    }
}
        
?>

</div>

<?php

    //Cart Statistics
    $incart = mysql_query("SELECT * FROM userscart WHERE emailaddress = '$email' ORDER BY id ASC");
    $incartresults = mysql_num_rows($incart);
    
    $marketquery = mysql_query("SELECT * FROM usersmaybe WHERE emailaddress = '$email'");
    $numsavedinmarket = mysql_num_rows($marketquery);
    
    $downloadquery = mysql_query("SELECT * FROM userdownloads WHERE emailaddress = '$email'");
    $numpurchased = mysql_num_rows($downloadquery);
    
    
    
    echo'
        <!--Quick Links to Cart-->
        
        <div class="grid_7 cartBox rounded shadow" style="position:fixed;margin-left:100px;margin-top:70px;">
             <div class="cartText"><a class="green" style="text-decoration:none;color:#333;'; if($view == '') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'" href="cart.php">My Cart (',$incartresults,')</a></div>
             <div class="cartText"><a class="green" style="text-decoration:none;color:#333;'; if($view == 'purchases') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'" href="cart.php?view=purchases">Purchases (',$numpurchased,')</a></div>
             <div class="cartText"><a class="green" style="text-decoration:none;color:#333;'; if($view == 'maybe') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'" href="cart.php?view=maybe">Wish List (',$numsavedinmarket,')</a></div>
        </div>   
        <br />
    </div>';

?>

 </div><!--end of grid 24-->
    
    </div><!--end of container-->
    
<?php footer(); ?>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script src="js/bootstrap-dropdown.js"></script>
    <script type="text/javascript">  
        $(document).ready(function () {  
            $('.dropdown-toggle').dropdown();  
        });  
</script>

</body>
</html>