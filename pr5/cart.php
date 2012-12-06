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
    
<div class="grid_24" style="width:1120px;">
    
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
                <form action="../downloadphoto.php" method="POST" name="download">
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
               
               echo $images;
               echo $imagesid;
               echo'here';

               $numberimages = count($images);
    		
                for($i=0; $i < $numberimages; $i++) {

                    $images[$i] = mysql_real_escape_string($images[$i]);
                    $imagesid[$i] = mysql_real_escape_string($imagesid[$i]);
                    
                    $downloadcheck = mysql_query("SELECT * FROM userdownloads WHERE imageid = '$imagesid[$i]'");
                    $downloadcheckrows = mysql_num_rows($downloadcheck);
                    
                    if($downloadcheckrows < 1) {
                    
                        $stickintouserdownloads = mysql_query("INSERT INTO userdownloads (emailaddress,imageid,source) VALUES ('$email','$imagesid[$i]','$images[$i]')");
                        $deletephotofromcart = mysql_query("DELETE FROM userscart WHERE emailaddress = '$email' AND imageid = '$imagesid[$i]'");
                        $addsoldtonewsfeed = mysql_query("INSERT INTO newsfeed (emailaddress,type,source) VALUES ('$email','sold','$imagesid[$i]')");
                    
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
            </div>
            
            <div><a class="btn btn-success" href="',$_SERVER['HTTP_REFERER'],'">Continue Shopping</a>
            </div>';
        } */
        
        
    if($incartresults > 0) {
        
        echo'<div class="grid_18"><a name="checkout" style="color:black;text-decoration:none;" href="#"><div style="padding:15px;padding-right:200px;background-color:#ddd;width:190px;margin-top:50px;"><span style="font-size:22px;font-weight:200;">Payment Summary</span></div></a>
        
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
        
        
        </div><br />';
        
        //STRIPE PAYMENT FORM AND DOWNLOAD SYSTEM
        
        if($totalcharge > 0) {
        
        echo'
    
    <!-- to display errors returned by createToken -->
    <span class="payment-errors" style="font-weight:bold;font-size:15px;"></span>

    <form action="',htmlentities($_SERVER['PHP_SELF']),'?charge=1" method="POST" id="payment-form">
    <div class="form-row">
            
<input type="hidden" name="price" value="',$price,'">
<input type="hidden" name="firstname" value="',$firstname,'">
<input type="hidden" name="lastname" value="',$lastname,'">
<input type="hidden" name="image" value="',$image,'">
<input type="hidden" name="label" value="',$label,'">
<input type="hidden" name="imageID" value="',$imageID,'">
<input type="hidden" name="customeremail" value="',$customeremail,'">
 
    <div class="grid_18"><a name="added" style="color:black;text-decoration:none;"><div style="padding:15px;padding-right:200px;background-color:#ddd;width:190px;margin-top:50px;"><span style="font-size:22px;font-weight:200;">Billing Details</span></div>
    <br /><br /> 
    
    <div style="width:740px;">
        <table class="table">
        <tbody>
        
            <tr>
            <td style="font-size:15px;width:450px;">Payment Options</td>
            <td><input type="radio" name="creditcard" value="creditcard" />&nbsp;&nbsp;<img src="graphics/creditcards.jpg" width="300" />
                &nbsp;&nbsp;&nbsp;
                <input type="radio" name="paypal" value="paypal" />&nbsp;&nbsp;<img src="graphics/paypal.gif" width="60" /></td>
            </tr>
            
            <div id="creditcard">
            
            <tr>
            <td style="font-size:15px;">Credit Card</td>
            <td>
            
                <input style="float:left;font-size:15px;padding:5px;position:relative;top:-7px;width:180px;margin-top:5px;" type="text" name="firstname" size="20" autocomplete="off" class="card-number" style;"/>

                <input style="float:left;font-size:15px;margin-left:40px;padding:5px;position:relative;top:-7px;width:180px;margin-top:5px;" type="text" name="lastname" size="20" autocomplete="off" class="card-number" style;"/>
                
                <div style="float:left;margin-top:-10px;font-size:13px;font-weight:200;">First Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Last Name</div>
                
                <br />
                
                <input style="float:left;font-size:15px;padding:5px;position:relative;width:415px;margin-top:5px;" type="text" name="card" size="20" autocomplete="off" class="card-number" style;"/>
                
                <div style="float:left;font-size:13px;font-weight:200;">Credit Card Number</div>
                
                <br />
                                
                <div style="float:left;clear:both;margin-top:15px;">
                <select style="width:120px;">
                    <option value="volvo">Select Month</option>
                    <option value="January">January</option>
                    <option value="February">February</option>
                    <option value="March">March</option>
                    <option value="April">April</option>
                    <option value="May">May</option>
                    <option value="June">June</option>
                    <option value="July">July</option>
                    <option value="August">August</option>
                    <option value="September">September</option>
                    <option value="October">October</option>
                    <option value="November">November</option>
                    <option value="December">December</option>
                </select>
                </div>
                                
                <div style="float:left;margin-left:10px;margin-top:15px;">
                <select style="width:80px;">
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
                                
                <div style="float:left;;margin-left:45px;margin-top:15px;">
                <input style="float:left;font-size:15px;padding:5px;position:relative;top:-7px;width:160px;margin-top:5px;" type="text" name="code" size="20" autocomplete="off" class="card-number" style;"/>
                </div>
                
                <div style="float:left;margin-top:-10px;font-size:13px;font-weight:200;">Expiration Month&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Year&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CVC (Verification #)</div>
            
            </div>

            </td>
            </tr>
            
            <tr>
            <td style="font-size:15px;">Billing Address</td>
            <td>
            
                <input style="float:left;font-size:15px;padding:5px;position:relative;width:415px;margin-top:5px;" type="text" name="address" size="20" autocomplete="off" class="card-number" style;"/>
                
                <div style="float:left;font-size:13px;font-weight:200;">Street Address</div>
                
                <input style="float:left;font-size:15px;padding:5px;position:relative;top:-7px;width:150px;clear:both;margin-top:15px;" type="text" name="city" size="20" autocomplete="off" class="card-number" style;"/>

                <input style="float:left;font-size:15px;margin-left:20px;padding:5px;position:relative;top:-7px;width:110px;margin-top:15px;" type="text" name="zip" size="20" autocomplete="off" class="card-number" style;"/>
                
                <div style="float:left;margin-left:20px;margin-top:10px;">
                <select style="width:100px;">
                    <option value="volvo">State</option>
                    <option value="volvo">Volvo</option>
                    <option value="saab">Saab</option>
                    <option value="mercedes">Mercedes</option>
                    <option value="audi">Audi</option>
                </select>
                </div>
                
                <div style="float:left;margin-top:-10px;font-size:13px;font-weight:200;">City&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Zip Code&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;State</div>
                
                <div style="float:left;margin-top:10px;">
                <select>
<option value="" selected="selected"></option>
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
            
            <div style="float:left;font-size:13px;font-weight:200;clear:both;">Country</div>
                
            </td>
            </tr>
            
            <tr>
            <td style="font-size:15px;">Submit Payment</td>
            <td> 
            
                <button type="submit" class="button submit btn btn-success" style="font-size:16px;float:left;margin-top:5px;padding-top:10px;padding-bottom:10px;padding-right:40px;padding-left:40px;font-weight:200;width:440px;">Submit Payment</button>
                </form>
            
            </td>
            </tr>
            
            </div>
        
        </tbody>
        </table>
    </div>

         
    </div></div>'; 
       
         }
         
         else {
         
         echo'
            <form name="download_form" method="post" action="cart.php?action=download">';
          
            foreach($sourcelist as $value) {
                echo '<input type="hidden" name="downloadedimages[]" value="'. $value. '">';
            }
            
            foreach($idlist as $value) {
                echo '<input type="hidden" name="imagesid[]" value="'. $value. '">';
            }
            
            echo'
            <button type="submit" name="submit" class="button submit btn btn-success"  style="font-size:16px;font-weight:200;width:295px;height:40px;">Download Free</button>
            </form>';
         
         }
        
        }
        
        
        
        
 } //end if logged in

echo'</div>';

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
        
        <div class="grid_7 cartBox rounded shadow" style="position:fixed;margin-left:200px;">
             <div class="cartText"><a class="green" style="text-decoration:none;color:#333;'; if($view == '') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'" href="cart.php">My Cart (',$incartresults,')</a></div>
             <div class="cartText"><a class="green" style="text-decoration:none;color:#333;'; if($view == 'purchases') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'" href="cart.php?view=purchases">Purchases (',$numpurchased,')</a></div>
             <div class="cartText"><a class="green" style="text-decoration:none;color:#333;'; if($view == 'maybe') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'" href="cart.php?view=maybe">Wish List (',$numsavedinmarket,')</a></div>
        </div>   
        <br />
        <a href="#checkout" class="grid_6 btn btn-success" style="width:245px;font-size:20px;padding:12px;position:fixed;margin-left:200px;margin-top:140px;color:white;text-decoration:none;">Checkout</a>     
    </div>';
    
?>

    
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