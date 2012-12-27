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
    
    //Image ID
    $imageid = mysql_real_escape_string(htmlentities($_GET['imageid']));
    $imageinfo = mysql_query("SELECT price,caption,source FROM photos WHERE id = $imageid");
    
    $wishprice = mysql_result($imageinfo,0,'price');
    $wishcaption = mysql_result($imageinfo,0,'caption'); 
    $wishsource = mysql_result($imageinfo,0,'source');
    $wishsource = "https://photorankr.com/".$wishsource;
    
    //View
    $view = mysql_real_escape_string(htmlentities($_GET['view']));
    
    //Cart Statistics
    $incart = mysql_query("SELECT * FROM userscart WHERE emailaddress = '$email' ORDER BY id ASC");
    $incartresults = mysql_num_rows($incart);
    
    $marketquery = mysql_query("SELECT * FROM usersmaybe WHERE emailaddress = '$email'");
    $numsavedinmarket = mysql_num_rows($marketquery);
    
    $downloadquery = mysql_query("SELECT * FROM userdownloads WHERE emailaddress = '$email'");
    $numpurchased = mysql_num_rows($downloadquery);
    
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
    
<div class="grid_24" style="width:1120px;margin-left:-40px;overflow:hidden;">
    
    <!------------------------TOP BAR------------------------>
    <div class="topCartBar">
    <?php
        if($view == 'maybe') {  
            echo'<header>My Wishlist</header>';
        }
        elseif($view == 'purchases') {
            echo'<header>My Purchases</header>';
        }
        else {
            echo'<header>My Cart</header>';
        }
    ?>
        <ul>
            <li> <a style="color:#333;text-decoration:none;" href="cart.php"><img style="width:20px;" src="graphics/cart_b.png" /> Cart <span style="font-size:16px;">(<?php echo $incartresults; ?>)</span> </a> </li>
            <li> <a style="color:#333;text-decoration:none;" href="cart.php?view=maybe"> <img style="width:20px;" src="graphics/cloud.png" /> Wishlist <span style="font-size:16px;">(<?php echo $numsavedinmarket; ?>)&nbsp;</span></a></li>
            <li> <a style="color:#333;text-decoration:none;" href="cart.php?view=purchases"><img style="width:16px;" src="graphics/file 2.png" /> Purchases <span style="font-size:16px;">(<?php echo $numpurchased; ?>)</span>&nbsp;</a></li>
        </ul>
    </div>
    
    
<div id="container" class="grid_16" style="width:660px;margin-top:20px;padding-left:0px;margin-left:-50px;">
     
<?php
        
      if($view == 'maybe') {  
    
        //Delete photo from wishlist
        if(htmlentities($_GET['action']) == 'removed') {
            $removephoto = mysql_query("DELETE FROM usersmaybe WHERE imageid = '$imageid' AND emailaddress = '$email'");
        }
        
        //Move photo to cart
        if(htmlentities($_GET['action']) == 'cart') {
            $deletequery = mysql_query("DELETE FROM usersmaybe WHERE emailaddress = '$email' AND imageid = '$imageid'");
            $wishlistquery = mysql_query("INSERT INTO userscart (source, caption, size, price, emailaddress, imageid) VALUES ('$wishsource','$wishcaption','Large','$wishprice','$email','$imageid')");
        
        }
        
        $marketquery = mysql_query("SELECT * FROM usersmaybe WHERE emailaddress = '$email'");
        $numsavedinmarket = mysql_num_rows($marketquery);
        
        if($numsavedinmarket < 1) {
            echo'<div style="margin-left:455px;width:300px;height:300px;margin-top:130px;">
                    <div style="float:center;text-align:center;"><img style="width:60px;" src="graphics/bag.png" /></div>
                    <br />
                    <div style="float:center;text-align:center;font-size:18px;font-weight:300;line-height:24px;">
                    You have no photos saved in your wishlist. <br />
                    <a href="market.php">Visit the Market</a>
                    </div>
                    </div>';
        }
                
                echo'<div id="container" class="grid_15" style="width:660px;margin-top:20px;padding-left:20px;">';
          
                for($iii=0; $iii<$numsavedinmarket; $iii++) {
                        $photo = mysql_result($marketquery, $iii, "source");
                        $photo2 = str_replace("http://photorankr.com/userphotos/","../userphotos/medthumbs/", $photo);
                        $photoid = mysql_result($marketquery, $iii, "id");
                        $imagecartid = mysql_result($marketquery, $iii, "imageid");
                        $caption = mysql_result($marketquery, $iii, "caption");
                        $caption = strlen($caption) > 30 ? substr($caption,0,27). " &#8230;" : $caption;
                        $price = mysql_result($marketquery, $iii, "price");
                        $totalcharge += $price;
                        
                        list($height,$width) = getimagesize($photo);
                        $widthnew = $width / 4;
                        $heightnew = $height / 4;
                
                         echo'<div class="span9">
                        <a name="',$imagecartid,'" style="text-decoration:none;color:#333;" href="fullsizemarket.php?imageid=',$imagecartid,'">
                        <table class="table">
                        <thead>
                        <tr>
                        <th>Photo</th>
                        <th>Caption</th>
                        <th>Price</th>  
                        </tr>
                        </thead>
                        <tbody>
            
                        <tr>
                        <td><div style="height:',$heightls,'px;width:',$width,'"><img style="height:',$heightnew,'px;width:',$widthnew,'" onmousedown="return false" oncontextmenu="return false;" alt="',$caption,'" src="',$photo2,'" /><br /><br />
                        <div style="text-align:left;">
                            <i class="icon-remove"></i>
                            <a style="font-weight:500;font-size:14px;" href="cart.php?view=maybe&imageid=',$imagecartid,'&action=removed">Remove from wishlist</a>
                                <br /> 
                            <i class="icon-arrow-right"></i>               
                            <a style="padding-top:5px;font-weight:500;font-size:14px;" href="cart.php?view=maybe&imageid=',$imagecartid,'&action=cart">Move to Cart</a>
                        </div>
                        </div>
                        </td>
                        <td style="width:140px;">',$caption,'</td>
                        <td style="width:140px;">$',$price,'</td>
                        </tr>

                        </tbody>
                        </table>
                        </a>
                        </div>';
   
                }
        
        echo'</div>
             </div>';
             
             //PAYMENT STUFF ON RIGHT SIDE
    echo'<div class="grid_10 push_2" style="width:365px;padding-left:20px;">';
        
    if($numsavedinmarket > 0) {
    
            echo'<div class="grid_9"><a name="added" style="color:black;text-decoration:none;" href="#"><div style="padding:15px;padding-right:200px;background-color:#ddd;width:180px;margin-left:25px;margin-top:30px;"><span style="font-size:20px;font-weight:300;">Wishlist Summary</span></div></a>
        
        <div style="margin-left:20px;font-size:14px;font-weight:normal;">
        <table class="table">
            <thead>
            <tr>
            <th># Photos</th>
            <th>Total Price</th>
            </thead>
            
            <tbody>
        
            <tr>
            <td style="width:220px;">',$numsavedinmarket,'</td>
            <td>$',$totalcharge,'</td>
            </tr>
        
            </tbody>
            </table>
        </div>
        
        </div><br />';
        
    }
        
}

elseif($view == 'paymentsuccess') {  
    
    //add in code from confirmpaymentpaypal.php
    
    $tokengetexpress = addslashes($_REQUEST['token']);
    $identity = addslashes($_REQUEST['identities']);
    $paymentamount = addslashes($_REQUEST['paymentAmount']);

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
 
$paymentType = urlencode("Authorization");	// or 'Sale' or 'Order'
//$paymentAmount = urlencode("2");
$currencyID = urlencode("USD");	// or other currency code ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')
 
// Add request-specific fields to the request string.

$nvpStr = "&TOKEN=$token&PAYERID=$payerID&PAYMENTACTION=$paymentType&AMT=$paymentamount&CURRENCYCODE=$currencyID";
 
// Execute the API operation; see the PPHttpPost function above.
$httpParsedResponseAr = PPHttpPost('DoExpressCheckoutPayment', $nvpStr);
 
if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
	echo'<div style="font-size:18px;font-weight:300;">Payment Successful</div>';
} else  {
	exit('DoExpressCheckoutPayment failed: ' . print_r($httpParsedResponseAr, true));
}
    
} //end view == 'paymentsuccess'
     

    elseif($view == 'purchases') {  
    
        echo'<script>
            jQuery(document).ready(function(){
                jQuery("#hideshow").live("click", function(event) {        
                    jQuery("#creditcard").toggle();
                });
            });
        </script>';
                
            $downloadquery = mysql_query("SELECT * FROM userdownloads WHERE emailaddress = '$email'");
            $numpurchased = mysql_num_rows($downloadquery);
          
            if($numpurchased < 1) {
            echo'<div style="margin-left:455px;width:300px;height:300px;margin-top:130px;">
                    <div style="float:center;text-align:center;"><img style="width:60px;" src="graphics/bag.png" /></div>
                    <br />
                    <div style="float:center;text-align:center;font-size:18px;font-weight:300;line-height:24px;">
                    You have not purchased any photos yet. <br />
                    <a href="market.php">Visit the Market</a>
                    </div>
                    </div>';
        }
                
                echo'<div id="container" class="grid_15" style="width:660px;margin-top:20px;padding-left:20px;">';
          
                for($iii=0; $iii<$numpurchased; $iii++) {
                        $photo = mysql_result($downloadquery, $iii, "source");
                        $photo2 = str_replace("userphotos/","userphotos/medthumbs/", $photo);
                        $photoid = mysql_result($downloadquery, $iii, "id");
                        $imagecartid = mysql_result($downloadquery, $iii, "imageid");
                        $caption = mysql_result($downloadquery, $iii, "caption");
                        $caption = strlen($caption) > 30 ? substr($caption,0,27). " &#8230;" : $caption;
                        $price = mysql_result($downloadquery, $iii, "price");
                        $totalcharge += $price;
                        
                        list($height,$width) = getimagesize($photo);
                        $widthnew = $width / 4;
                        $heightnew = $height / 4;
                
                         echo'<div class="span9">
                        <table class="table">
                        <thead>
                        <tr>
                        <th>Photo</th>
                        <th>Caption</th>
                        <th>Price</th>  
                        </tr>
                        </thead>
                        <tbody>';
                        ?>
                        
                        <script type="text/javascript">
                            function submitForm() {
                                document.getElementById("download<?php echo $iii; ?>").submit();
                            }
                        </script>
                        
                        <?php
                        echo'
                        <tr>
                        <td><div style="height:',$heightls,'px;width:',$width,'"><img style="height:',$heightnew,'px;width:',$widthnew,'" onmousedown="return false" oncontextmenu="return false;" alt="',$caption,'" src="',$photo2,'" /><br /><br />
                        <div style="text-align:left;">
                             <form action="https://photorankr.com/downloadphoto.php" method="POST" id="download',$iii,'">
                            <input type="hidden" name="image" value="',$photo,'">
                            </form>
                                <br /> 
                            <i class="icon-download"></i>               
                                <a style="padding-top:1px;font-weight:500;font-size:14px;" href="#" onclick="submitForm()">Download</a>
                                <br />
                            <i class="icon-picture"></i>               
                                <a style="padding-top:1px;font-weight:500;font-size:14px;" href="fullsize.php?imageid=',$imagecartid,'">View in network</a>
                        </div>
                        </div>
                        </td>
                        <td style="width:140px;">',$caption,'</td>
                        <td style="width:140px;">$',$price,'</td>
                        </tr>

                        </tbody>
                        </table>
                        </a>
                        </div>';
   
                }
        
        echo'</div>
             </div>';
             
             //PAYMENT STUFF ON RIGHT SIDE
    echo'<div class="grid_10 push_2" style="width:365px;padding-left:20px;">';
        
    if($numpurchased > 0) {
    
            echo'<div class="grid_9"><a name="added" style="color:black;text-decoration:none;" href="#"><div style="padding:15px;padding-right:200px;background-color:#ddd;width:180px;margin-left:25px;margin-top:30px;"><span style="font-size:20px;font-weight:300;">Purchases Summary</span></div></a>
        
        <div style="margin-left:20px;font-size:14px;font-weight:normal;">
        <table class="table">
            <thead>
            <tr>
            <th># Photos</th>
            <th>Total Price</th>
            </thead>
            
            <tbody>
        
            <tr>
            <td style="width:220px;">',$numpurchased,'</td>
            <td>$',$totalcharge,'</td>
            </tr>
        
            </tbody>
            </table>
        </div>
        
        </div><br />';
        
    }
        
} //end view == 'purchases'
        
        
        elseif($view == '') {  
        
        //Delete photo from cart
        if(htmlentities($_GET['action']) == 'removed') {
            $deletequery = mysql_query("DELETE FROM userscart WHERE emailaddress = '$email' AND imageid = '$imageid'");
        }
        
        //Move photo to wishlist
        if(htmlentities($_GET['action']) == 'wishlist') {
            $deletequery = mysql_query("DELETE FROM userscart WHERE emailaddress = '$email' AND imageid = '$imageid'");
            $wishlistquery = mysql_query("INSERT INTO usersmaybe (source, caption, price, emailaddress, imageid) VALUES ('$wishsource','$wishcaption','$wishprice','$email','$imageid')");
        
        }
        
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
            
        
            echo'<div id="container" class="grid_15" style="width:660px;margin-top:20px;padding-left:20px;">';
                        
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
                        
                        //photo information
                        $photoinfoquery = mysql_query("SELECT price,caption,width,height FROM photos WHERE id = '$imagesid[$i]'");
                        $width = mysql_result($photoinfoquery,0,'width');
                        $height = mysql_result($photoinfoquery,0,'height');
                        $price =mysql_result($photoinfoquery,0,'price');
                        $caption =  mysql_result($photoinfoquery,0,'caption');
                        $stickintouserdownloads = mysql_query("INSERT INTO userdownloads (emailaddress,imageid,source,width,height,time,caption,price) VALUES ('$email','$imagesid[$i]','$images[$i]','$width','$height','$currenttime','$caption','$price')");
                        $deletephotofromcart = mysql_query("DELETE FROM userscart WHERE emailaddress = '$email' AND imageid = '$imagesid[$i]'");
                        $addsoldtonewsfeed = mysql_query("INSERT INTO newsfeed (firstname,lastname,emailaddress,type,source,time) VALUES ('$sessionfirst','$sessionlast,','$email','sold','$imagesid[$i]','$currenttime')");
                    
                        //Tell them download was successful
                        echo'<div style="font-size:16px;font-weight:200;margin-top:20px;margin-left:35px;"><img src="',$images[$i],'" height="100" width="100" />&nbsp;&nbsp;&nbsp;Photo Saved in Purchases "',$caption,'"</div>';
                    
                    }
                 
                }
                 
            }
         
         
    //PHOTO CART INFORMATION
    $imagequery = mysql_query("SELECT source,price,caption FROM photos WHERE id = '$imageid'");
    $imagenewsource = mysql_result($imagequery,0,'source');
    $imagenewsource2 = str_replace("userphotos/", "$_SERVER[DOCUMENT_ROOT]/userphotos/",$imagenewsource);
    $imagenewsource3 = str_replace("$_SERVER[DOCUMENT_ROOT]/userphotos/", "http://photorankr.com/userphotos/",$imagenewsource2); 
    $imagenewprice = mysql_result($imagequery,0,'price'); 
    $caption = mysql_result($imagequery,0,'caption');
    
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
            $stickincart = mysql_query("INSERT INTO userscart (source,size,width,height,license,price,emailaddress,imageid,caption) VALUES ('$imagenewsource3','$size','$width','$height','$licenses','$price','$email','$imageid','$caption')");
            }
        }
        
        $incart = mysql_query("SELECT * FROM userscart WHERE emailaddress = '$email' ORDER BY id ASC");
        $incartresults = mysql_num_rows($incart);
        
        if($incartresults < 1  && htmlentities($_GET['action']) != 'download') {
            echo'<div style="margin-left:435px;width:300px;height:300px;margin-top:120px;">
                    <div style="float:center;text-align:center;"><img style="width:60px;" src="graphics/bag.png" /></div>
                    <br />
                    <div style="float:center;text-align:center;font-size:18px;font-weight:300;line-height:24px;">
                    You have no photos saved in your cart. <br />
                    <a href="market.php">Visit the Market</a>
                    </div>
                </div>';
        }
        
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
            <div style="text-align:left;">
                <i class="icon-remove"></i>
                <a style="font-weight:500;font-size:14px;" href="cart.php?imageid=',$imagecartid,'&action=removed">Remove from cart</a>
                    <br /> 
                <i class="icon-arrow-right"></i>               
                <a style="padding-top:5px;font-weight:500;font-size:14px;" href="cart.php?imageid=',$imagecartid,'&action=wishlist">Move to Wishlist</a>
            </div>
          </div>
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
            </div>
            
            <div><a class="btn btn-success" href="',$_SERVER['HTTP_REFERER'],'">Continue Shopping</a>
            </div>';
        } */
        
        echo'</div></div>';
    
    //PAYMENT STUFF ON RIGHT SIDE
    echo'<div class="grid_10 push_2" style="width:365px;padding-left:20px;">';
        
    if($incartresults > 0) {
    
            echo'<div class="grid_9"><a name="added" style="color:black;text-decoration:none;" href="#"><div style="padding:15px;padding-right:200px;background-color:#ddd;width:180px;margin-left:25px;margin-top:30px;"><span style="font-size:20px;font-weight:300;">Payment Summary</span></div></a>
        
        <div style="margin-left:20px;font-size:14px;font-weight:normal;">
        <table class="table">
            <thead>
            <tr>
            <th># Photos</th>
            <th>Total Price</th>
            </thead>
            
            <tbody>
        
            <tr>
            <td style="width:220px;">',$incartresults,'</td>
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
            
    <div class="grid_9"><a name="added" style="color:black;text-decoration:none;"><div style="padding:15px;padding-right:200px;background-color:#ddd;width:180px;"><span style="font-size:20px;font-weight:300;">Billing Details</span></div>
    <br /><br /> 
    
    <div>
        <table class="table">
        <tbody>
            
            <tr>
            <td style="font-size:14px;width:450px;">Payment Options</td>
            <td><img src="graphics/creditcards.jpg" width="200" />
                &nbsp;&nbsp;&nbsp;
                <a href="paypalsetexpresscheckout.php"><img src="graphics/paypal.gif" width="50" /></a></td>
            </tr>
            
            <tr>
            <td style="font-size:14px;">Credit Card</td>
            <td>
            
                <input style="float:left;font-size:15px;padding:5px;position:relative;top:-7px;width:110px;margin-top:5px;" type="text" name="firstname" size="20" autocomplete="off" class="card-number" style;"/>

                <input style="float:left;font-size:15px;margin-left:20px;padding:5px;position:relative;top:-7px;width:110px;margin-top:5px;" type="text" name="lastname" size="20" autocomplete="off" class="card-number" style;"/>
                
                <div style="float:left;margin-top:-10px;font-size:13px;font-weight:200;">First Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Last Name</div>
                
                <br />
                
                <input style="float:left;clear:both;font-size:15px;padding:5px;position:relative;width:255px;margin-top:5px;" type="text" name="cardnumber" size="20" autocomplete="off" class="card-number" style;"/>
                
                <div style="clear:both;float:left;font-size:13px;font-weight:200;">Credit Card Number</div>

                          <input style="clear:both;float:left;font-size:15px;padding:5px;position:relative;width:255px;margin-top:5px;" type="text" name="cardtype" size="20" autocomplete="off" class="card-number" style;"/>
                
                <div style="clear:both;float:left;font-size:13px;font-weight:200;">Card Type</div>
                
                <br />
                                
                <div style="float:left;clear:both;margin-top:15px;">
               <select name = "month" style="width:120px;">
                    <option value="volvo">Select Month</option>
                    <option value="1">January</option>
                    <option value="2">February</option>
                    <option value="3">March</option>
                    <option value="4">April</option>
                    <option value="5">May</option>
                    <option value="6">June</option>
                    <option value="7">July</option>
                    <option value="8">August</option>
                    <option value="9">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                </select>
                </div>
                                
                <div style="float:left;margin-left:10px;margin-top:15px;">
              <select name = "year" style="width:80px;">
                    <option value="2012">2012</option>
                    <option value="2013">2013</option>
                    <option value="2014">2014</option>
                    <option value="2015">2015</option>
                    <option value="2016">2016</option>
                    <option value="2017">2017</option>
                    <option value="2018">2018</option>
                    <option value="2019">2019</option>
                    <option value="2020">2020</option>
                    <option value="2021">2021</option>
                    <option value="2022">2022</option>
                    <option value="2023">2023</option>
                    <option value="2024">2024</option>
                    <option value="2025">2025</option>
                </select>
                </div>
                                
                <div style="float:left;;margin-left:10px;margin-top:18px;">
                <input style="float:left;font-size:15px;padding:5px;position:relative;top:-7px;width:30px;margin-top:5px;" type="text" name="expdatemonth" size="20" autocomplete="off" class="card-number" style;"/>
                </div>
                
                <div style="float:left;margin-top:-10px;font-size:13px;font-weight:200;">Expiration Month&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Year&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CVC #</div>
            
            </div>

            </td>
            </tr>
            
            <tr>
            <td style="font-size:14px;">Billing Address</td>
            <td>
            
                <input style="clear:both;float:left;font-size:15px;padding:5px;position:relative;width:260px;margin-top:5px;" type="text" name="address" size="20" autocomplete="off" class="card-number" style;"/>
                
                <div style="clear:both;float:left;font-size:13px;font-weight:200;">Street Address</div>
                
                <input style="float:left;font-size:15px;padding:5px;position:relative;top:-7px;width:150px;clear:both;margin-top:15px;" type="text" name="city" size="20" autocomplete="off" class="card-number" style;"/>

                <input style="float:left;font-size:15px;margin-left:20px;padding:5px;position:relative;top:-7px;width:80px;margin-top:15px;" type="text" name="zipcode" size="20" autocomplete="off" class="card-number" style;"/>
                   
                <div style="clear:both;float:left;margin-top:-10px;font-size:13px;font-weight:200;">City&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Zip Code</div>
                
                <div style="clear:both;float:left;margin-top:10px;">
                <select name = "country" style="width:160px;">
<option value="" selected="selected">Country</option>
<option value="United States">United States</option>
<option value="United Kingdom">United Kingdom</option>
<option value="Australia">Australia</option>
<option value="Canada">Canada</option>
<option value="France">France</option>
<option value="New Zealand">New Zealand</option>
<option value="India">India</option>
<option value="Brazil">Brazil</option>
<option value="----">----</option>
<option value="Afghanistan">Afghanistan</option>
<option value="Aland Islands">Aland Islands</option>
<option value="Albania">Albania</option>
<option value="Algeria">Algeria</option>
<option value="American Samoa">American Samoa</option>
<option value="Andorra">Andorra</option>
<option value="Angola">Angola</option>
<option value="Anguilla">Anguilla</option>
<option value="Antarctica">Antarctica</option>
<option value="Antigua and Barbuda">Antigua and Barbuda</option>
<option value="Argentina">Argentina</option>
<option value="Armenia">Armenia</option>
<option value="Aruba">Aruba</option>
<option value="Austria">Austria</option>
<option value="Azerbaijan">Azerbaijan</option>
<option value="Bahamas">Bahamas</option>
<option value="Bahrain">Bahrain</option>
<option value="Bangladesh">Bangladesh</option>
<option value="Barbados">Barbados</option>
<option value="Belarus">Belarus</option>
<option value="Belgium">Belgium</option>
<option value="Belize">Belize</option>
<option value="Benin">Benin</option>
<option value="Bermuda">Bermuda</option>
<option value="Bhutan">Bhutan</option>
<option value="Bolivia">Bolivia</option>
<option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
<option value="Botswana">Botswana</option>
<option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
<option value="Brunei Darussalam">Brunei Darussalam</option>
<option value="Bulgaria">Bulgaria</option>
<option value="Burkina Faso">Burkina Faso</option>
<option value="Burundi">Burundi</option>
<option value="Cambodia">Cambodia</option>
<option value="Cameroon">Cameroon</option>
<option value="Cape Verde">Cape Verde</option>
<option value="Cayman Islands">Cayman Islands</option>
<option value="Central African Republic">Central African Republic</option>
<option value="Chad">Chad</option>
<option value="Chile">Chile</option>
<option value="China">China</option>
<option value="Colombia">Colombia</option>
<option value="Comoros">Comoros</option>
<option value="Democratic Republic of the Congo">Democratic Republic of the Congo</option>
<option value="Republic of the Congo">Republic of the Congo</option>
<option value="Cook Islands">Cook Islands</option>
<option value="Costa Rica">Costa Rica</option>
<option value="Cote d\'Ivoire">Cote d\'Ivoire</option>
<option value="Croatia">Croatia</option>
<option value="Cuba">Cuba</option>
<option value="Cyprus">Cyprus</option>
<option value="Czech Republic">Czech Republic</option>
<option value="Denmark">Denmark</option>
<option value="Djibouti">Djibouti</option>
<option value="Dominica">Dominica</option>
<option value="Dominican Republic">Dominican Republic</option>
<option value="East Timor">East Timor</option>
<option value="Ecuador">Ecuador</option>
<option value="Egypt">Egypt</option>
<option value="El Salvador">El Salvador</option>
<option value="Equatorial Guinea">Equatorial Guinea</option>
<option value="Eritrea">Eritrea</option>
<option value="Estonia">Estonia</option>
<option value="Ethiopia">Ethiopia</option>
<option value="Faroe Islands">Faroe Islands</option>
<option value="Fiji">Fiji</option>
<option value="Finland">Finland</option>
<option value="Gabon">Gabon</option>
<option value="Gambia">Gambia</option>
<option value="Georgia">Georgia</option>
<option value="Germany">Germany</option>
<option value="Ghana">Ghana</option>
<option value="Gibraltar">Gibraltar</option>
<option value="Greece">Greece</option>
<option value="Grenada">Grenada</option>
<option value="Guatemala">Guatemala</option>
<option value="Guinea">Guinea</option>
<option value="Guinea-Bissau">Guinea-Bissau</option>
<option value="Guyana">Guyana</option>
<option value="Haiti">Haiti</option>
<option value="Honduras">Honduras</option>
<option value="Hong Kong">Hong Kong</option>
<option value="Hungary">Hungary</option>
<option value="Iceland">Iceland</option>
<option value="Indonesia">Indonesia</option>
<option value="Iran">Iran</option>
<option value="Iraq">Iraq</option>
<option value="Ireland">Ireland</option>
<option value="Israel">Israel</option>
<option value="Italy">Italy</option>
<option value="Jamaica">Jamaica</option>
<option value="Japan">Japan</option>
<option value="Jordan">Jordan</option>
<option value="Kazakhstan">Kazakhstan</option>
<option value="Kenya">Kenya</option>
<option value="Kiribati">Kiribati</option>
<option value="North Korea">North Korea</option>
<option value="South Korea">South Korea</option>
<option value="Kuwait">Kuwait</option>
<option value="Kyrgyzstan">Kyrgyzstan</option>
<option value="Laos">Laos</option>
<option value="Latvia">Latvia</option>
<option value="Lebanon">Lebanon</option>
<option value="Lesotho">Lesotho</option>
<option value="Liberia">Liberia</option>
<option value="Libya">Libya</option>
<option value="Liechtenstein">Liechtenstein</option>
<option value="Lithuania">Lithuania</option>
<option value="Luxembourg">Luxembourg</option>
<option value="Macedonia">Macedonia</option>
<option value="Madagascar">Madagascar</option>
<option value="Malawi">Malawi</option>
<option value="Malaysia">Malaysia</option>
<option value="Maldives">Maldives</option>
<option value="Mali">Mali</option>
<option value="Malta">Malta</option>
<option value="Marshall Islands">Marshall Islands</option>
<option value="Mauritania">Mauritania</option>
<option value="Mauritius">Mauritius</option>
<option value="Mexico">Mexico</option>
<option value="Micronesia">Micronesia</option>
<option value="Moldova">Moldova</option>
<option value="Monaco">Monaco</option>
<option value="Mongolia">Mongolia</option>
<option value="Montenegro">Montenegro</option>
<option value="Morocco">Morocco</option>
<option value="Mozambique">Mozambique</option>
<option value="Myanmar">Myanmar</option>
<option value="Namibia">Namibia</option>
<option value="Nauru">Nauru</option>
<option value="Nepal">Nepal</option>
<option value="Netherlands">Netherlands</option>
<option value="Netherlands Antilles">Netherlands Antilles</option>
<option value="Nicaragua">Nicaragua</option>
<option value="Niger">Niger</option>
<option value="Nigeria">Nigeria</option>
<option value="Norway">Norway</option>
<option value="Oman">Oman</option>
<option value="Pakistan">Pakistan</option>
<option value="Palau">Palau</option>
<option value="Palestine">Palestine</option>
<option value="Panama">Panama</option>
<option value="Papua New Guinea">Papua New Guinea</option>
<option value="Paraguay">Paraguay</option>
<option value="Peru">Peru</option>
<option value="Philippines">Philippines</option>
<option value="Poland">Poland</option>
<option value="Portugal">Portugal</option>
<option value="Puerto Rico">Puerto Rico</option>
<option value="Qatar">Qatar</option>
<option value="Romania">Romania</option>
<option value="Russia">Russia</option>
<option value="Rwanda">Rwanda</option>
<option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
<option value="Saint Lucia">Saint Lucia</option>
<option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>
<option value="Samoa">Samoa</option>
<option value="San Marino">San Marino</option>
<option value="Sao Tome and Principe">Sao Tome and Principe</option>
<option value="Saudi Arabia">Saudi Arabia</option>
<option value="Senegal">Senegal</option>
<option value="Serbia and Montenegro">Serbia and Montenegro</option>
<option value="Seychelles">Seychelles</option>
<option value="Sierra Leone">Sierra Leone</option>
<option value="Singapore">Singapore</option>
<option value="Slovakia">Slovakia</option>
<option value="Slovenia">Slovenia</option>
<option value="Solomon Islands">Solomon Islands</option>
<option value="Somalia">Somalia</option>
<option value="South Africa">South Africa</option>
<option value="Spain">Spain</option>
<option value="Sri Lanka">Sri Lanka</option>
<option value="Sudan">Sudan</option>
<option value="Suriname">Suriname</option>
<option value="Swaziland">Swaziland</option>
<option value="Sweden">Sweden</option>
<option value="Switzerland">Switzerland</option>
<option value="Syria">Syria</option>
<option value="Taiwan">Taiwan</option>
<option value="Tajikistan">Tajikistan</option>
<option value="Tanzania">Tanzania</option>
<option value="Thailand">Thailand</option>
<option value="Togo">Togo</option>
<option value="Tonga">Tonga</option>
<option value="Trinidad and Tobago">Trinidad and Tobago</option>
<option value="Tunisia">Tunisia</option>
<option value="Turkey">Turkey</option>
<option value="Turkmenistan">Turkmenistan</option>
<option value="Tuvalu">Tuvalu</option>
<option value="Uganda">Uganda</option>
<option value="Ukraine">Ukraine</option>
<option value="United Arab Emirates">United Arab Emirates</option>
<option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
<option value="Uruguay">Uruguay</option>
<option value="Uzbekistan">Uzbekistan</option>
<option value="Vanuatu">Vanuatu</option>
<option value="Vatican City">Vatican City</option>
<option value="Venezuela">Venezuela</option>
<option value="Vietnam">Vietnam</option>
<option value="Virgin Islands, British">Virgin Islands, British</option>
<option value="Virgin Islands, U.S.">Virgin Islands, U.S.</option>
<option value="Yemen">Yemen</option>
<option value="Zambia">Zambia</option>
<option value="Zimbabwe">Zimbabwe</option>
</select>
            </div>
            
            <div style="float:left;margin-left:20px;margin-top:10px;">
                <select name = "state" style="width:90px;">
                 <option value="">State</option>
                 <option value="AB">Alberta</option>
                 <option value="BC">British Columbia</option>
                 <option value="MB">Manitoba</option>
                 <option value="NB">New Brunswick</option>
                 <option value="NL">Newfoundland and Labrador</option>
                 <option value="NT">Northwest Territories</option>
                 <option value="NS">Nova Scotia</option>
                 <option value="NU">Nunavut</option>
                 <option value="ON">Ontario</option>
                 <option value="PE">Prince Edward Island</option>
                 <option value="QC">Quebec</option>
                 <option value="SK">Saskatchewan</option>
                 <option value="AL">Alabama</option>
                 <option value="AK">Alaska</option>
                 <option value="AS">American Samoa</option>
                 <option value="AZ">Arizona</option>
                 <option value="AR">Arkansas</option>
                 <option value="CA">California</option>
                 <option value="CO">Colorado</option>
                 <option value="CT">Connecticut</option>
                 <option value="DE">Delaware</option>
                 <option value="DC">District of Columbia</option>
                 <option value="FM">Federated States of Micronesia</option>
                 <option value="FL">Florida</option>
                 <option value="GA">Georgia</option>
                 <option value="GU">Guam</option>
                 <option value="HI">Hawaii</option>
                 <option value="ID">Idaho</option>
                 <option value="IL">Illinois</option>
                 <option value="IN">Indiana</option>
                 <option value="IA">Iowa</option>
                 <option value="KS">Kansas</option>
                 <option value="KY">Kentucky</option>
                <option value="LA">Louisiana</option>
                 <option value="ME">Maine</option>
                 <option value="MH">Marshall Islands</option>
                 <option value="MD">Maryland</option>
                 <option value="MA">Massachusetts</option>
                 <option value="MI">Michigan</option>
                 <option value="MN">Minnesota</option>
                 <option value="MS">Mississippi</option>
                 <option value="MT">Montana</option>
                 <option value="NE">Nebraska</option>
                 <option value="NV">Nevada</option>
                 <option value="NH">New Hampshire</option>
                 <option value="NJ">New Jersey</option>
                 <option value="NM">New Mexico</option>
                <option value="NY">New York</option>
                 <option value="NC">North Carolina</option>
                 <option value="ND">North Dakota</option>
                 <option value="MD">Maryland</option>
                 <option value="MP">Northern Mariana Islands</option>
                 <option value="OH">Ohio</option>
                 <option value="OK">Oklahoma</option>
                 <option value="OR">Oregon</option>
                 <option value="PW">Palau</option>
                 <option value="PA">Pennsylvania</option>
                 <option value="PR">Puerto Rico</option>
                 <option value="RI">Rhode Island</option>
                 <option value="SC">South Carolina</option>
                 <option value="SD">South Dakota</option>
                 <option value="TN">Tennessee</option>
                 <option value="TX">Texas</option>
                 <option value="UT">Utah</option>
                 <option value="VT">Vermont</option>
                 <option value="VI">Virgin Islands</option>
                 <option value="VA">Virginia</option>
                 <option value="WA">Washington</option>
                 <option value="WV">West Virginia</option>
                 <option value="WI">Wisconsin</option>
                 <option value="WY"Wyoming</option>
                 <option value="AA">Armed Forces Americas</option>
                 <option value="AP ">Armed Forces Pacific</option>
                </select>
                </div>
            
            <div style="float:left;font-size:13px;font-weight:200;clear:both;">Country&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;State</div>
                            
            </td>
            </tr>
            
            <tr>
            <td style="font-size:15px;"></td>
            <td> 
            
                <button type="submit" class="button submit btn btn-success" style="font-size:16px;float:left;margin-left:-80px;margin-top:5px;padding-top:10px;padding-bottom:10px;padding-right:40px;padding-left:40px;font-weight:200;width:355px;">Submit Payment</button>
                </form>
            
            </td>
            </tr>
        
        </tbody>
        </table>
    </div>

         
      <div></div>';
   
   
}



elseif($totalcharge == 0 && $incartresults > 0) {
         
         echo'
            <form name="download_form" method="post" action="cart.php?action=download">';
          
            foreach($sourcelist as $value) {
                echo '<input type="hidden" name="downloadedimages[]" value="'. $value. '">';
            }
            
            foreach($idlist as $value) {
                echo '<input type="hidden" name="imagesid[]" value="'. $value. '">';
            }
            
            echo'
            <button type="submit" name="submit" value="download" class="button submit btn btn-success"  style="font-size:16px;float:left;margin-left:40px;margin-top:5px;padding-top:10px;padding-bottom:10px;padding-right:40px;padding-left:40px;font-weight:200;width:355px;">Download Free</button>
            </form>';
         
    }
    
        echo'</div>';


    echo'</div>';

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
 $expDateMonth = $_POST["month"];
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
$stickintouserdownloads = mysql_query("INSERT INTO userdownloads (emailaddress,imageid,source,width,height,time) VALUES ('$repemail','$imagecartid','$imagesource','$imagewidth','$imageheight','$currenttime,')");
 $deletephotofromcart = mysql_query("DELETE FROM userscart WHERE emailaddress = '$email' AND imageid = '$imagecartid'");
    }      


header("Location: cart.php?view=paymentsuccess");

}


else  {
    exit('DoDirectPayment failed: ' . print_r($httpParsedResponseAr, true));

}


//         var form$ = $("#payment-form");

//<meta http-equiv="refresh" content="0;url=http://photorankr.com/account.php?view=download">    
}           

}

//PayPal Return Payment Check
if($view == 'confirmpp') {

    require('../paypalcheckoutagain.php');
    
            echo'<div class="grid_18"><a name="added" style="color:black;text-decoration:none;" href="#"><div style="padding:15px;padding-right:200px;background-color:#ddd;width:180px;margin-left:25px;margin-top:50px;"><span style="font-size:22px;font-weight:200;">Confirm payment</span></div></a>
        
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
        </div>
        
        </div><br />';
                
    }

?>

</div>
    
    </div><!--end of grid 24-->
    
    </div><!--end of container-->

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script src="js/bootstrap-dropdown.js"></script>
    <script type="text/javascript">  
        $(document).ready(function () {  
            $('.dropdown-toggle').dropdown();  
        });  
</script>

</body>
</html>