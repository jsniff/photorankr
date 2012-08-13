<?php

//log them out if they try to logout
session_start();

if($_GET['action'] == logout) {
	$_SESSION['loggedin'] = 0;
	session_destroy();
}

//connect to the database
require "db_connection.php";
require_once("stripe/lib/Stripe.php");

//echo "working";


//start session
session_start();
//if the login form is submitted
if (htmlentities($_GET['action']) == "login") { // if login form has been submitted

	// makes sure they filled it in
	if(!htmlentities($_POST['emailaddress']) | !htmlentities($_POST['password'])) {
		die('You did not fill in a required field.');
	}

	// checks it against the database
	/*if (!get_magic_quotes_gpc()) {
   	$_POST['emailaddress'] = addslashes(htmlentities($_POST['emailaddress']));
	$_POST['emailaddress'] = mysql_real_escape_string($_POST['emailaddress']);
    	}*/
    	$check = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '".mysql_real_escape_string($_POST['emailaddress'])."'")or die(mysql_error());
	//Gives error if user dosen't exist




	$check2 = mysql_num_rows($check);
    
	if ($check2 == 0) {
        	die('That user does not exist in our database. <a href=signin.php>Click Here to Register</a> or <a href="lostpassword.php">here to recover a forgotten password</a>.');
        }

	$info = mysql_fetch_array($check);    
	if(mysql_real_escape_string($_POST['password']) == mysql_real_escape_string($info['password'])){

	//then redirect them to the same page as signed in and set loggedin to 1
	$_SESSION['loggedin']=1;
	$_SESSION['email']=$_POST['emailaddress'];
	}
   
	//gives error if the password is wrong
    	else if (mysql_real_escape_string($_POST['password']) != mysql_real_escape_string($info['password'])) {
die('Incorrect password, please try again. <a href="lostpassword.php"> Lost your password?</a>');	}
}

//QUERY FOR NOTIFICATIONS
$currentnots = "SELECT * FROM userinfo WHERE emailaddress = '$email'";
$currentnotsquery = mysql_query($currentnots);
$currentnotsresult = mysql_result($currentnotsquery, 0, "notifications");


$emailtrial = "tyler.sniff@gmail.com";

 $getstripeinfo = "SELECT * FROM userinfo WHERE emailaddress = '$emailtrial'";
$striperesult = mysql_query($getstripeinfo); 
$stripepubkey = mysql_result($striperesult, 0, 'pubkey');
echo $stripepubkey;
echo "whatup";


$getstripeinfo = "SELECT * FROM userinfo WHERE emailaddress = '$emailtrial'";
$striperesult = mysql_query($getstripeinfo); 
$stripekey = mysql_result($striperesult, 0, 'token');
echo $stripekey;


?>



<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
"http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta name="Contact Us"></meta>
<link rel="stylesheet" type="text/css" href="download.css"/>
<link rel="stylesheet" type="text/css" href="reset.css"/>
<link rel="stylesheet" type="text/css" href="text.css"/>
<link rel="stylesheet" type="text/css" href="bootstrapnew.css"/>
<link rel="stylesheet" type="text/css" href="960_24.css"/>
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
            var key = "<? print $stripepubkey; ?>";
           
//Tyler's
           // Stripe.setPublishableKey('pk_07nH7wAErP9SujawnAdTmrkb047qv');
           Stripe.setPublishableKey('pk_wyF8CPirmy3KmAv7lmf5gKwV5bElr');
            //document.write("workplease");
            //document.write(key);
//echo $stripepubkey;
         // Stripe.setPublishableKey(key); 
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

<body style="background-color:rgb(238,239,243);background-image:url('texture3new.png');min-width:1100px;">

<!--NAVIGATION BAR-->
<div class="navbar" style="z-index:10;min-width:1100px;padding-top:0px;font-size:16px;width:100%;">
	<div class="navbar-inner">
		<div class="container">
			    <ul class="nav">
					<li><a style="color:#fff;" class="brand" style="margin-top:10px;margin-right:20px;" href="../index.php"><div style="margin-top:-2px"><img src="../logo.png" width="160" /></div></a></li>
                    
                    <?php               
                     if($_SESSION['loggedin'] == 1) {
                     echo'
                     <li style="color:#fff;margin-top:1px;" class="dropdown active, navbartext"><a style="color:rgb(56,85,103);margin-right:20px;" href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <span style="font-size:18px;padding-right:3px;color:#fff;margin-top:2px;"><span style="position:relative;top:4px;"><i class="icon-exclamation-sign icon-white"></i></span> ',$currentnotsresult,'</a>
                    <ul class="dropdown-menu" data-dropdown="dropdown">';


$email7 = $_SESSION['email'];


//NOTIFICATION QUERIES  
$emailquery=("SELECT following FROM userinfo WHERE emailaddress ='$email7'");
$followresult=mysql_query($emailquery);
$followinglist=mysql_result($followresult, 0, "following");

$notsquery = "SELECT * FROM newsfeed WHERE (owner = '$email7' AND emailaddress != '$email7') OR following = '$email7' ORDER BY id DESC";
$notsresult = mysql_query($notsquery);
$numnots = mysql_num_rows($notsresult);

//DECIDE WHICH NOTIFICATIONS TO WHITEN (ONES ALREADY CLICKED ON)
$unhighlightquery = "SELECT * FROM userinfo WHERE emailaddress = '$email7'";
$unhighlightqueryrun = mysql_query($unhighlightquery);
$whitenlist=mysql_result($unhighlightqueryrun, 0, "unhighlight");


if($numnots > 1) {
echo'<div style="width:500px;height:400px;overflow-y:scroll;font-size:14px;">';

for ($iii=1; $iii <= 20; $iii++) {
$notsarray = mysql_fetch_array($notsresult);
$firstname4 = $notsarray['firstname'];
$lastname4 = $notsarray['lastname'];
$fullname4 = $firstname4 . " " . $lastname4;
$fullname4 = ucwords($fullname4);
$type = $notsarray['type'];
$id = $notsarray['id'];

//SEARCH IF ID IS IN UNHIGHLIGHT LIST
$search_string = $whitenlist;
$regex = $id;
$match=strpos($whitenlist,$regex);

if($match < 1) {
if($type == "comment") {
$caption4 = $notsarray['caption'];
$source= $notsarray['source'];
$newsource = str_replace("userphotos/","userphotos/thumbs/", $source);
echo'<a style="text-decoration:none" href="../fullsize.php?image=',$source,'&id=',$id,'"><div id="photoshadowhighlight2"><img src="../graphics/newsfeedcomment.png" height="50" width="50" />&nbsp;<img src="http://www.photorankr.com/',$newsource,'" height="50" width="50" />&nbsp;<span style="color:white">',$fullname4,' commented on your photo</span></div></a><br />';
}

elseif($type == "message") {
$ownermessage = $notsarray['owner'];
$thread = $notsarray['thread'];
$newaccount = "SELECT * FROM userinfo WHERE emailaddress = '$ownermessage'";
$accountresult = mysql_query($newaccount); 
$accountrow = mysql_fetch_array($accountresult);
$profilepic4 = $accountrow['profilepic'];
if($profilepic4 == "") {
$profilepic4 = "../profilepics/default_profile.jpg";
}
echo'<a style="text-decoration:none" href="../myprofile.php?view=viewthread&thread=',$thread,'&id=',$id,'"><div id="photoshadowhighlight2"><img src="../graphics/messagesicon.png" height="50" width="50" />&nbsp;<img src="',$profilepic4,'" height="50" width="50" />&nbsp;<span style="color:white">',$fullname4,' sent you a message</span></div></a><br />';
}

elseif($type == "reply") {
$ownermessage = $notsarray['owner'];
$thread = $notsarray['thread'];
$newaccount = "SELECT * FROM userinfo WHERE emailaddress = '$ownermessage'";$accountresult = mysql_query($newaccount); 
$accountrow = mysql_fetch_array($accountresult);
$profilepic4 = $accountrow['profilepic'];
if($profilepic4 == "") {
$profilepic4 = "../profilepics/default_profile.jpg";
}
echo'<a style="text-decoration:none" href="../myprofile.php?view=viewthread&thread=',$thread,'&id=',$id,'"><div id="photoshadowhighlight2"><img src="../graphics/messagesicon.png" height="50" width="50" />&nbsp;<img src="',$profilepic4,'" height="50" width="50" />&nbsp;<span style="color:white">',$fullname4,' replied to your message</span></div></a><br />';
}

elseif($type == "fave") {
$caption4 = $notsarray['caption'];
$source= $notsarray['source'];
$newsource = str_replace("userphotos/","userphotos/thumbs/", $source);
echo'<a style="text-decoration:none" href="../fullsize.php?image=',$source,'&id=',$id,'"><div id="photoshadowhighlight2"><img src="../graphics/newsfeedfavorite.png" height="50" width="50" />&nbsp;<img src="http://www.photorankr.com/',$newsource,'" height="50" width="50" />&nbsp;<span style="color:white">',$fullname4,' favorited your photo</span></div></a><br />';
}

elseif($type == "trending") {
$caption4 = $notsarray['caption'];
$source= $notsarray['source'];
$newsource = str_replace("userphotos/","userphotos/thumbs/", $source);
echo'<a style="text-decoration:none" href="../fullsize.php?image=',$source,'&id=',$id,'"><div id="photoshadowhighlight2"><img src="../graphics/newsfeedtrending.png" height="50" width="50" />&nbsp;<img src="http://www.photorankr.com/',$newsource,'" height="50" width="50" />&nbsp;<span style="color:white">Your photo is now trending</span></div></a><br />';
}

elseif($type == "follow") {
$caption4 = $notsarray['caption'];
$followeremail= $notsarray['emailaddress'];
$newaccount = "SELECT * FROM userinfo WHERE emailaddress = '$followeremail'";
$accountresult = mysql_query($newaccount); 
$accountrow = mysql_fetch_array($accountresult);
$ownerid = $accountrow['user_id'];
$profilepic4 = $accountrow['profilepic'];
if($profilepic4 == "") {
$profilepic4 = "../profilepics/default_profile.jpg";
}
echo'<a style="text-decoration:none" href="../viewprofile.php?u=',$ownerid,'&id=',$id,'"><div id="photoshadowhighlight2"><img src="../graphics/newsfeednewfollower.png" height="50" width="50" />&nbsp;<img src="',$profilepic4,'" height="50" width="50" />&nbsp;<span style="color:white">',$fullname4,' is now following your photography</span></div></a><br />';
}
} //end if statement

elseif($match > 0) {
if($type == "comment") {
$caption4 = $notsarray['caption'];
$source= $notsarray['source'];
$newsource = str_replace("userphotos/","userphotos/thumbs/", $source);
echo'<a style="text-decoration:none" href="../fullsize.php?image=',$source,'&id=',$id,'"><div id="photoshadow2"><img src="../graphics/newsfeedcomment.png" height="50" width="50" />&nbsp;<img src="http://www.photorankr.com/',$newsource,'" height="50" width="50" />&nbsp;<span style="color:black">',$fullname4,' commented on your photo</span></div></a><br />';
}

elseif($type == "message") {
$ownermessage = $notsarray['owner'];
$thread = $notsarray['thread'];
$newaccount = "SELECT * FROM userinfo WHERE emailaddress = '$ownermessage'";
$accountresult = mysql_query($newaccount); 
$accountrow = mysql_fetch_array($accountresult);
$profilepic4 = $accountrow['profilepic'];
if($profilepic4 == "") {
$profilepic4 = "../profilepics/default_profile.jpg";
}
echo'<a style="text-decoration:none" href="../myprofile.php?view=viewthread&thread=',$thread,'&id=',$id,'"><div id="photoshadow2"><img src="../graphics/messagesicon.png" height="50" width="50" />&nbsp;<img src="',$profilepic4,'" height="50" width="50" />&nbsp;<span style="color:black;">',$fullname4,' sent you a message</span></div></a><br />';
}

elseif($type == "reply") {
$ownermessage = $notsarray['owner'];
$thread = $notsarray['thread'];
$newaccount = "SELECT * FROM userinfo WHERE emailaddress = '$ownermessage'";$accountresult = mysql_query($newaccount); 
$accountrow = mysql_fetch_array($accountresult);
$profilepic4 = $accountrow['profilepic'];
if($profilepic4 == "") {
$profilepic4 = "../profilepics/default_profile.jpg";
}
echo'<a style="text-decoration:none" href="../myprofile.php?view=viewthread&thread=',$thread,'&id=',$id,'"><div id="photoshadow2"><img src="../graphics/messagesicon.png" height="50" width="50" />&nbsp;<img src="',$profilepic4,'" height="50" width="50" />&nbsp;<span style="color:black;">',$fullname4,' replied to your message</span></div></a><br />';
}

elseif($type == "fave") {
$caption4 = $notsarray['caption'];
$source= $notsarray['source'];
$newsource = str_replace("userphotos/","userphotos/thumbs/", $source);
echo'<a style="text-decoration:none" href="../fullsize.php?image=',$source,'&id=',$id,'"><div id="photoshadow2"><img src="../graphics/newsfeedfavorite.png" height="50" width="50" />&nbsp;<img src="http://www.photorankr.com/',$newsource,'" height="50" width="50" />&nbsp;<span style="color:black">',$fullname4,' favorited your photo</span></div></a><br />';
}

elseif($type == "trending") {
$caption4 = $notsarray['caption'];
$source= $notsarray['source'];
$newsource = str_replace("userphotos/","userphotos/thumbs/", $source);
echo'<a style="text-decoration:none" href="../fullsize.php?image=',$source,'&id=',$id,'"><div id="photoshadow2"><img src="../graphics/newsfeedtrending.png" height="50" width="50" />&nbsp;<img src="http://www.photorankr.com/',$newsource,'" height="50" width="50" />&nbsp;<span style="color:black">Your photo is now trending</span></div></a><br />';
}

elseif($type == "follow") {
$caption4 = $notsarray['caption'];
$followeremail= $notsarray['emailaddress'];
$newaccount = "SELECT * FROM userinfo WHERE emailaddress = '$followeremail'";
$accountresult = mysql_query($newaccount); 
$accountrow = mysql_fetch_array($accountresult);
$ownerid = $accountrow['user_id'];
$profilepic4 = $accountrow['profilepic'];
if($profilepic4 == "") {
$profilepic4 = "../profilepics/default_profile.jpg";
}
echo'<a style="text-decoration:none" href="../viewprofile.php?u=',$ownerid,'&id=',$id,'"><div id="photoshadow2"><img src="../graphics/newsfeednewfollower.png" height="50" width="50" />&nbsp;<img src="',$profilepic4,'" height="50" width="50" />&nbsp;<span style="color:black">',$fullname4,' is now following your photography</div></a><br /></span>';
}
} //end ifelse statement

} //end of for loop
echo'</div>';

}

elseif($numnots < 1) {
echo'<div style="position:relative;width:400px;height:80px;overflow-y:scroll;font-size:14px;top: 30px;">';
echo'<div style="font-size:16px;color:white;text-align:center;">You have no new notifications &#8230;</div>';
echo'</div>';
}



echo'
    </ul>
    </li>'; 
            }
     ?>
                    
					<li class="dropdown">
                    <a style="color:#fff;margin-top:2px;" href="newest.php" class="dropdown-toggle" data-toggle="dropdown">Galleries<b class="caret" style="background-color:#1a618a;
"></b></a>
						<ul class="dropdown-menu" data-dropdown="dropdown">
							<li ><a style="color:#fff;margin-top:2px;" href="trending.php">Trending</a></li>
                            <li><a style="color:#fff;margin-top:2px;" href="newest.php">Newest</a></li>
                            <li><a style="color:#fff;margin-top:2px;" href="topranked.php">Top Ranked</a></li>
                        </ul>
                </li>
                
                <li ><a style="color:#fff;margin-top:2px;" href="viewcampaigns.php">Campaigns</a></li>

					
  <?php
  
  echo'
  <li><a style="color:#fff;margin-top:2px;" href="';
                    if($nolikes) {echo 'myprofile.php?view=editinfo&action=discover#discover';}else { echo 'discover.php?image=',$discoverimage;} echo '">Discover</a></li>';
                    
@session_start();
if($_SESSION['loggedin'] == 1) {

	echo '			
                   
                    <li class="dropdown">

						<a style="color:#fff;margin-top:2px;" href="../myprofile.php" class="dropdown-toggle" data-toggle="dropdown">My Profile<b class="caret" style="background-color:#1a618a;
"></b></a>
						<ul class="dropdown-menu" data-dropdown="dropdown">
							<li><a style="color:#fff;" href="../myprofile.php?view=news">News</a></li>
							<li><a style="color:#fff;" href="../myprofile.php">Photography</a></li>
                            <li><a style="color:#fff;" href="../myprofile.php?view=info">Information</a></li>
							<li><a style="color:#fff;" href="../myprofile.php?view=upload">Upload</a></li>
							<li><a style="color:#fff;" href="../myprofile.php?view=followers">Followers</a></li>
							<li><a style="color:#fff;" href="../myprofile.php?view=following">Following</a></li>
							<li><a style="color:#fff;" href="../myprofile.php?view=faves">Favorites</a></li>
							<li><a style="color:#fff;" href="../myprofile.php?view=',$view,'action=logout">Log Out</a></li>
						</ul>'; 
} else {
				echo '	
                <li class="dropdown">
                <a style="color:#fff;margin-top:2px;" href="../signin.php" class="dropdown-toggle" data-toggle="dropdown">Log In<b class="caret"></b></a>
						<ul class="dropdown-menu" data-dropdown="dropdown">
							<li><a style="color:#fff;" href="../signin.php">Register Now</a></li>
							<li><br/></li>
							<form name="login_form" method="post" action="../newest.php?action=login">
							<li style="margin-left: 5px; margin-right: 5px; width: 200px;"><span style="color: white; margin-bottom: 5px;">Email: </span><input type="text" style="width:100px; margin-left: 40px;" name="emailaddress" /></li>
							<li style="margin-left: 10px;"><span style="color: white">Password: </span>&nbsp<input type="password" style="width:100px; margin-left: 1px;" name="password"/></li>
							<li style="margin-left: 70px;"><input type="submit" value="sign in" id="loginButton"/></li>
							</form>
						</ul>';
} ?>
					</li>
					<form class="navbar-search" action="../search.php" method="get">
						<input type="text" style="width:180px;border-color:#fff;background-color:#fff;margin-left:20px;" class="search-query" name="searchterm" placeholder="Search">
					 </form>
					 
				</ul>
			
		</div> <!--/end boostrap divs navbar-->

    </div>
</div>

<?php

//STRIPE DOWNLOAD SYSTEM ALL RIGHT HERE 

$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];  
$price = $_POST['price'];
$customeremail = $_POST['customeremail'];
$label = $_POST['label'];
$width = $_POST['width'];
$height = $_POST['height'];
$imageID = $_POST['imageID'];
$image = $_POST['image'];
$imagenew = str_replace("userphotos/","userphotos/bigphotos/", $image);
$imagename = str_replace("userphotos/","", $image);

if($price == 'Not For Sale') {
echo'
<div class="container_24" style="padding-top:80px;">
<div style="font-size:30px;text-align:center;margin-top:200px;padding-bottom:220px;">Sorry, ',$firstname,' ',$lastname,' is not selling this photo.</div>
</div>';
}

if($price == '.00') {
echo '<div class="grid_12 push_12 download1" style="margin-top:100px;margin-left:480px;>

<div class="grid_8 push_3 image" style="width:300px;"><img src="',$image,'" style="width:300px;height:300px;"/> 


<form name="download_form" method="post" action="downloadphoto.php">
<input type="hidden" name="image" value="', $image, '">
<input type="hidden" name="label" value="', $label, '">
<input type="hidden" name="imageID" value="', $imageID, '">
<input type="hidden" name="customeremail" value="', $customeremail, '">
<div class="grid_24" style="margin-top:30px;">
<button type="submit" name="submit" value="download" class="btn btn-warning" style="width:295px;height:40px;">DOWNLOAD PHOTO</button>
</div>
</form>

<br />
<br />
<br />

<div class="info" style="margin-top:25px;"> 
<h1 class="field"> Price: Free</h1> 
<h2 class="field"> Photographer: ',$firstname,' ',$lastname,'</h2>
<h3 class="field"> Photo: "',$label,'" </h3>
<h3 class="field"> StripeInfo: "',$stripekey,'" </h3>
<h3 class="field"> StripeInfo: "',$stripekey,'" </h3>
<h3 class="field"> Image ID: "',$imageID,'"</h3>
</div>
</div>
<br /><br /><br /><br />';

require "db_connection.php";
$query="UPDATE photos SET purchases=(purchases+1) WHERE source='$image'";
mysql_query($query) or die(mysql_error());

}


if($_REQUEST['charge'] != 1 && $price != 'Not For Sale' && $price !='.00') {

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


<div class="grid_8 push_3 image" style="width:300px;"><img src="',$image,'" style="width:300px;height:300px;"/> 
</div>
<div class="grid_6 push_4 info" style="margin-top:25px;"> 
<h1 class="field"> Price: USD $',$price,'</h1> 
<h2 class="field"> Photographer: ',$firstname,' ',$lastname,'</h2>
<h3 class="field"> Photo: "',$label,'" </h3>
<h3 class="field"> Stripekey: "',$stripekey,'" </h3>
<h3 class="field"> Stripepubkey: "',$stripepubkey,'" </h3>
<h3 class="field"> Stripepubkey: "',$customeremail,'" </h3>
<h3 class="field"> Image ID: "',$imageID,'"</h3>
</div>
</div>
</div>';

} 

//STRIPE ACCOUNT CHARGING AND DOWNLOAD PHOTO SYSTEM HERE

if($_GET['charge'] == 1 OR $price == '.00') {

	//PRICE CHANGE INTO CENTS FOR STRIPE
if ($price == '.05') {
$newprice = 5;
}

else if ($price == '.10') {
$newprice = 10;
}

else if ($price == '.15') {
$newprice = 15;
}

else if ($price == '.25') {
$newprice = 25;
}

else if ($price == '.50') {
$newprice = 50;
}

else if ($price == '.75') {
$newprice = 75;
}

else if ($price == '1.00') {
$newprice = 100;
}

else if ($price == '2.00') {
$newprice = 200;
}

else if ($price == '5.00') {
$newprice = 500;
}

else if ($price == '10.00') {
$newprice = 1000;
}

else if ($price == '15.00') {
$newprice = 1500;
}

else if ($price == '25.00') {
$newprice = 2500;
}  
  
else if ($price == '50.00') {
$newprice = 5000;
}  

else if ($price == '100.00') {
$newprice = 10000;
} 

else if ($price == '200.00') {
$newprice = 20000;
} 

// set your secret key: remember to change this to your live secret key in production
// see your keys here https://manage.stripe.com/account
//Stripe::setApiKey("I4xWtNfGWVVGzVuOr6mrSYZ5nOrfMA9X");

//Stripe::setApiKey("jpdzMPMCFihJ43mXpa5I89wrtHDDxtlE");

// get the credit card details submitted by the form
$token = $_POST['stripeToken'];



/*
# charge the Customer instead of the card
Stripe::Charge.create(
    :amount => $newprice, # in cents
    :currency => "usd",
    :customer => $customeremail
)

# save the customer ID in your database so you can use it later
save_stripe_customer_id(user, $customeremail)

# later
customer_id = get_stripe_customer_id(user)

Stripe::Charge.create(
    :amount => $newprice, 
    :currency => "usd",
    :customer => customer_id
)
*/


$newprice = 20000;
//Stripe::setApiKey("sk_08gH6o7QPOeoHI8lUAoGp0LjAPoL7");
//Stripe::setApiKey($stripekey);
//Stripe::setPubKey($stripepubkey); 

///$photorankrfee = $newprice*.3;


// create the charge on Stripe's servers - this will charge the user's card
  // $charge = Stripe_Charge::create(array(
  //   "amount" => $newprice,
  //     "card" => $token,
  //       "application_fee"=>$photorankrfee,
  //  "currency" => "usd"
  //  )
//   // );
// $charge = Stripe_Charge::create(array(
//   "plan" => 1, // amount in cents, again
//   "card" => $token,
//   "customer" => $customeremail
// )
// );






// $charge = Stripe_Charge::create(array(
//   "plan" => "Exclusive Lifetime Plan", // amount in cents, again
//   "card" => $token
// )
//  );


Stripe::setApiKey("jpdzMPMCFihJ43mXpa5I89wrtHDDxtlE");

//Working Subscription Code
$customer = Stripe_Customer::create(array(
  "card" => $token,
  "plan" => 1,
  "email" => "paying@example.com")
);




$image = $_POST['image'];
$image = str_replace("userphotos/","userphotos/medthumbs/", $image);   
$label = $_POST['label'];
$imageID = $_POST['imageID'];


echo '<div class="grid_12 push_12 download1" style="margin-top:100px;margin-left:480px;>

<div class="grid_8 push_3 image" style="width:300px;"><img src="',$image,'" style="width:300px;height:300px;"/> 


<form name="download_form" method="post" action="downloadphoto.php">
<input type="hidden" name="image" value="', $image, '">
<input type="hidden" name="label" value="', $label, '">
<input type="hidden" name="imageID" value="', $imageID, '">
<input type="hidden" name="customeremail" value="', $customeremail, '">
<div class="grid_24" style="margin-top:30px;">
<button type="submit" name="submit" value="download" class="btn btn-warning" style="width:295px;height:40px;">DOWNLOAD PHOTO</button>
</div>
</form>

</div>';

require "db_connection.php";
$query="UPDATE photos SET purchases=(purchases+1) WHERE source='$image'";
mysql_query($query) or die(mysql_error());

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
 