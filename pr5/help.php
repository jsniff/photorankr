<?php
//log them out if they try to logout
session_start();

if($_GET['action'] == logout) {
	$_SESSION['loggedin'] = 0;
	session_destroy();
}

//connect to the database
require "db_connection.php";
require "functions.php";


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




 //DISCOVER SCRIPT

    $useremail = $_SESSION['email'];
    
  //get the users information from the database
  $likesquery = "SELECT * FROM userinfo WHERE emailaddress='$useremail'";
  $likesresult = mysql_query($likesquery) or die(mysql_error());
  $discoverseen = mysql_result($likesresult, 0, "discoverseen");

  //find out what they like
  $likes = mysql_result($likesresult, 0, "viewLikes");
    if($likes=="") {
		$nolikes = 1;
        		
	}
  $likes .= "  ";
  $likes .= mysql_result($likesresult, 0, "buyLikes");

  //create an array from what they like
  $likesArray = explode("  ", $likes);

  //loop through the array to format the likes in the proper format for the query
  $formattedLikes = "%";
  for($iii=0; $iii < count($likesArray); $iii++) {
    $formattedLikes .= $likesArray[$iii];
    $formattedLikes .= "%";
  }

    //make an array of the photos they have already seen
  if($discoverseen != "") {
    $discoverArray = explode(" ", $discoverseen);
    $discoverFormatted = "";
    for($iii=0; $iii < count($discoverArray)-1; $iii++) {
      $discoverFormatted .= "'";
      $discoverFormatted .= $discoverArray[$iii];
      $discoverFormatted .= "', ";
    }
    $discoverFormatted .= "'";
    $discoverFormatted .= $discoverArray[count($discoverArray)-1];
    $discoverFormatted .= "'";
  }
  
  //select the image that they will be seeing next
  //delineate between whether they have used discover feature before
  if($discoverseen != "") {     //get the photos that match this person's view interests
    $viewquery = "SELECT *, MATCH(maintags, singlecategorytags, singlestyletags) AGAINST ('$formattedLikes') AS matching FROM photos WHERE MATCH(maintags, singlecategorytags, singlestyletags) AGAINST ('$formattedLikes') AND id NOT IN(" . $discoverFormatted . ") ORDER BY matching DESC, points DESC LIMIT 0, 1";
    $viewresult = mysql_query($viewquery) or die(mysql_error());
  }
  else {
    //get the photos that match this person's view interests
    $viewquery = "SELECT *, MATCH(maintags, singlecategorytags, singlestyletags) AGAINST ('$formattedLikes') AS matching FROM photos WHERE MATCH(maintags, singlecategorytags, singlestyletags) AGAINST ('$formattedLikes') ORDER BY matching DESC, points DESC LIMIT 0, 1";
    $viewresult = mysql_query($viewquery) or die(mysql_error());
  }

  $discoverimage = mysql_result($viewresult, 0, "id");
  
?> 

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
"http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title></title>

<link rel="stylesheet" type="text/css" href="market/css/bootstrapNew.css" />
 <link rel="stylesheet" href="market/css/reset.css" type="text/css" />
 <link rel="stylesheet" type="text/css" href="help.css"/>
  <link rel="stylesheet" href="market/css/text.css" type="text/css" />
   <link rel="stylesheet" href="css/style.css" type="text/css" />
  <link rel="stylesheet" href="960_24.css" type="text/css" />
      <link href="css/main3.css" rel="stylesheet" type="text/css"/>

	<script type="text/javascript" href="js/bootstrap-dropdown.js"></script>
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript" src="http://masonry.desandro.com/jquery.masonry.min.js"></script>
<script type="text/javascript" src="https://raw.github.com/desandro/imagesloaded/master/jquery.imagesloaded.min.js"></script>
    <link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>
    
<meta name="Help Frequently Asked Questions"> </meta>


</head>
<body style="overflow-x:hidden; background-color:rgb(238,239,243);min-width:1220px;">

<?php navbar(); ?>
  
<div class="container_24" style="padding-top:90px;"><!--container begin-->
 <div class="grid_24">
  <div class="faq">
   <h1 class="header">Support and Frequently Asked Questions</h1>
    </div>
     </div>
 <div class="grid_18">     
  <div class="grid_10 push_4" id="questionbox1" style="float:left;">
   <h1 class= "qheader"> What is PhotoRankr? </h1>
  </div>
 <div class="grid_14 push_2 answerbox" style="float:left;"> 
  <p class="answer"> PhotoRankr is a web based start up where you can upload, sell, share, and discover photography. We provide a social network, marketplace, and
  creative outlet for passionate amateur,semi professional, and professional photographers. 
 Our goal is to create a true community of photographers.    </p>
  </div>
   <div class="grid_11 push_4" id="questionbox2" style="float:left;">
   <h1 class= "qheader">What are the social features on PhotoRankr?
 </h1>
  </div>
 <div class="grid_14 push_2 answerbox" style="float:left;"> 
  <p class="answer"> The features are the ability to rank, favorite, and comment on photos, follow photographers they like, message those photographers within the site, and a newsfeed that shows you the uploads and social activity around the website of your followers. Individually, from the profile page you can promote your profile across Facebook, Twitter, Google +, and Tumblr.
</p>
  </div>
   <div class="grid_11 push_4" id="questionbox3" style="float:left;">
   <h1 class= "qheader"> How do the social features work? </h1>
  </div>
 <div class="grid_14 push_2 answerbox" style="float:left;z-index:-1;height:545px;"> 
    <div class="grid_2 featurebox" style="float:left;">
      <h1 class="featureboxh">  Ranking </h1>
     </div>
     <div class="grid_11 featureboxa" style="float:left;">
        <p class="featureboxp"> Click the drop-down next to the green Rank button then select on a scale of 1-10 what you think the photo deserves. Then click the Rank button. You cannot rank your own photos and you must have an account to rank photos.</p>
      </div>
    <div class="grid_2 featurebox" style="float:left;">
      <h1 class="featureboxh">  Favoriting </h1>
     </div>
     <div class="grid_11 featureboxa" style="float:left;">
        <p class="featureboxp">  Click the red Favorite button to the right of the photo and a box will appear notifying you the photo has been added your favorites.Your favorites are located under the Favorites tab in your profile. </p>
      </div><div class="grid_2 featurebox" style="float:left;">
      <h1 class="featureboxh">  Following </h1>
     </div>
     <div class="grid_11 featureboxa" style="float:left;">
        <p class="featureboxp"> On PhotoRankr you can follow photographers who's work you enjoy. When you follow a photographer that photographer's activity appears in your newsfeed. Other members can follow you. 
</p>
      </div><div class="grid_2 featurebox" style="float:left;">
      <h1 class="featureboxh">  Promotion </h1>
     </div>
     <div class="grid_11 featureboxa" style="float:left;">
        <p class="featureboxp"> The yellow promotion button on the profile page allows you to easily post a link to your PhotoRankr profile on Facebook, Tumblr, Google + and Twitter. Other photographers can Promote you.</p>
      </div><div class="grid_2 featurebox" style="float:left;">
      <h1 class="featureboxh">  Messaging </h1>
     </div>
     <div class="grid_11 featureboxa" style="float:left;">
        <p class="featureboxp"> Using the Contact section of other members' profiles you can message a photographer with PhotoRankr's internal messaging system. Your conversations appear under the Messaging section in your profile.</p>
      </div><div class="grid_2 featurebox" style="float:left;">
      <h1 class="featureboxh">  Newsfeed </h1>
     </div>
     <div class="grid_11 featureboxa" style="float:left;">
        <p class="featureboxp">The newsfeed section in your profile shows the activity of photographers you follow on PhotoRankr. You have two views to choose from, grid and photostream.</p>
      </div>
      <div class="grid_2 featurebox" style="float:left;padding-left:2px;padding-right:2px;">
       <h1 class="featureboxh"> Notifications </h1>
     </div>
     <div class="grid_11 featureboxa" style="float:left;height:105px;">
        <p class="featureboxp">The notification system sends you an email when: 
            <ul class="featureboxp1">
              <li>your photo is commented on </li>
              <li>another photographer comments on a photo you also commented on </li>
              <li>another photographer favorites your photo </li>
              <li>someone follows you </li>
             </ul> </p>
         <p class="featureboxp" style="margin-top:-9px;">  You can change these settings at any time in the "Settings" tab in your profile.</p>
      </div>
  </div>
  
  <div class="grid_10 push_4" id="questionbox4" style="float:left;">
   <h1 class= "qheader"> What is "Reputation" and how does it work?</h1>
  </div>
 <div class="grid_14 push_2 answerbox" style="float:left;height:140px;"> 
  <p class="answer"> Your reputation is based on 3 factors. The first is the total number of followers you have. The second is your average portfolio score. The third is the total number of votes you have received on your photography. The higher your reputation, the more your vote counts. If you reputation score is between 0 and 30, your votes counts once. If your reputation score is between a 30 and 50, your votes counts twice as much. If your reputation score is between 50 and 70, your votes counts three times more than a novice, and if your score is higher than 70 your votes counts four times. 
 </p>
  </div>
   <div class="grid_10 push_4" id="questionbox5" style="float:left;">
   <h1 class= "qheader"> What is the "Discover" page and how does it work?
 </h1>
  </div>
 <div class="grid_14 push_2 answerbox" style="float:left;height:110px;"> 
  <p class="answer">  The discover page is a revolutionary way to discover new photography and ensure people can always see your photos, no matter how long your photos have been on PhotoRankr. Click the blue "Discover" button in the top left-hand corner to discover a new photo. It displays photos personalized to preferences you check in the "about" section of your profile. You can change these preferences at any time. </p>
  </div>
   <div class="grid_10 push_4" id="questionbox6" style="float:left;">
   <h1 class= "qheader"> How do I get paid?</h1>
  </div>
 <div class="grid_14 push_2 answerbox" style="float:left;"> 
  <p class="answer"> At the end of the month, we total balances for all photographers. If you have a balance of $25.00, you are due a payout. You will receive an email from us detailing the photos and quantities purchased and asking for the email address associated with your PayPal account. You will then receive an email from PayPal indicating you are due a payment from us, and you will be able to access your funds. If you do not have a PayPal account, you will be able to register then.</p>
  </div>
   <div class="grid_10 push_4" id="questionbox7" style="float:left;">
   <h1 class= "qheader"> What e-commerce system do you use?</h1>
  </div>
 <div class="grid_14 push_2 answerbox" style="float:left;"> 
  <p class="answer"> All purchases take place through Stripe's secure API. We accept Visa, MasterCard, American Express, Discover, JCB, and Diners Club cards. We use PayPal to distribute payouts to photographers.</p>
  </div>
   <div class="grid_10 push_4" id="questionbox8" style="float:left;">
   <h1 class= "qheader">How do I purchase a photo? </h1>
  </div>
 <div class="grid_14 push_2 answerbox" style="float:left;height:113px;"> 
  <p class="answer">  You can buy photos in two forms on PhotoRankr, a digital copy and print of the photo. The digital copy has a one-time personal use copy right release. The buyer cannot copy and redistribute this photo.
To purchase a photo, click on the "Buy This Photo" button and a window that will walk you through the payment and process appears. All of our purchase transactions go through Stripe's secure API.
      </p>
  </div>
   <div class="grid_10 push_4" id="questionbox9" style="float:left;">
   <h1 class= "qheader"> Are all of my photos for sale? </h1>
  </div>
 <div class="grid_14 push_2 answerbox" style="float:left;"> 
  <p class="answer">  You have the ability to price your own photo on PhotoRankr or release it as "free to download" under a Creative Commons License. We will add a "Not for Sale" option in the near future. </p>
  </div>
<div class="grid_10 push_4" id="questionbox10" style="float:left;">
   <h1 class= "qheader"> What types of images can I upload? </h1>
  </div>
 <div class="grid_14 push_2 answerbox" style="float:left;"> 
  <p class="answer"> You can upload .jpg, .png and .gif files to PhotoRankr.</p>
  </div>
<div class="grid_10 push_4" id="questionbox11" style="float:left;">
   <h1 class= "qheader"> What happens to my photos once I upload them? </h1>
  </div>
 <div class="grid_14 push_2 answerbox" style="float:left;"> 
  <p class="answer"> Once you upload photos we store your original image on our servers and we save two lower resolution copies to display on the website. This way, no one can access your original files except by purchasing them. </p>
  </div>
<div class="grid_10 push_4" id="questionbox12" style="float:left;">
   <h1 class= "qheader"> Can I edit my photo information once I have uploaded them? </h1>
  </div>
 <div class="grid_14 push_2 answerbox" style="float:left;"> 
  <p class="answer">Yes. Go to your profile page and click on the photo with the information you want to edit.  Click on the "Edit Photo" button and begin editing your information. 
</p>
  </div><div class="grid_10 push_4" id="questionbox13" style="float:left;">
   <h1 class= "qheader"> What do you do with the information I provide? </h1>
  </div>
 <div class="grid_14 push_2 answerbox" style="float:left;"> 
  <p class="answer"> We use your information to give you a better experience on PhotoRankr. We do not sell or disclose any personal information to third-parties except at legitimate government requests for this information. For more information please see our Terms.

</p>
  </div><div class="grid_10 push_4" id="questionbox14" style="float:left;">
   <h1 class= "qheader"> What is the trending page? </h1>
  </div>
 <div class="grid_14 push_2 answerbox" style="float:left;"> 
  <p class="answer"> The trending page shows the photos users are ranking the most at the moment.
</p>
  </div><div class="grid_10 push_4" id="questionbox15" style="float:left;">
   <h1 class= "qheader"> How do my photos become trending?
 </h1>
  </div>
 <div class="grid_14 push_2 answerbox" style="float:left;"> 
  <p class="answer"> When many other photographers rank your photos in a short period of time it will become trending.
</p>
  </div><div class="grid_10 push_4" id="questionbox16" style="float:left;">
   <h1 class= "qheader">How do I get on the Top Ranked page? </h1>
  </div>
 <div class="grid_14 push_2 answerbox" style="float:left;"> 
  <p class="answer"> The top ranked photographers list is compiled based on the average score of your top 15 photographs if you have received more than 1000 points. 
</p>
  </div>
  <div class="grid_10 push_4" id= "questionbox17" style="float:left;">
   <h1 class= "qheader"> How does my photo become a Top-Ranked Photo?</h1>
  </div>
 <div class="grid_14 push_2 answerbox" style="float:left;"> 
  <p class="answer"> The top ranked photography list is compiled based on the total percentage of votes your individual photo has compared to every photo times its average ranking.
</p>
  </div>
  <div class="grid_10 push_4" id="questionbox17" style="float:left;">
   <h1 class= "qheader"> What is an Exhibit? </h1>
  </div>
 <div class="grid_14 push_2 answerbox" style="float:left;"> 
  <p class="answer"> An exhibit is like an album but more specific. It's a collection of photos based around a subject, style, location, person, or concept. You can add photos to an exhibit from your profile or when you upload new photos.</p>
  </div><div class="grid_10 push_4" id="questionbox18" style="float:left;">
   <h1 class= "qheader"> Do you have mobile apps? </h1>
  </div>
 <div class="grid_14 push_2 answerbox" style="float:left;"> 
  <p class="answer"> Not yet, but over the next month we are developing an iOS and Android application with a Windows application soon to follow. </p>
  </div>
  
 </div> 
  <div class="grid_6 pull_1" style="z-index:-2">  </div>
  <div class="grid_6 pull_1 list"> 
   <div class="grid_6 questionlist" style="width:200px;height:45px;">
    <h1 class="contact" style="margin-bottom:10px;"> Support </h1>
     <div class="btn btn-warning" style="padding-left:12px;padding-top:3px;padding-bottom:3px;width:115px;height:20px;margin-left:35px;margin-top:13zpx;border-radius:5px;"> <a style="text-decoration:none;color:white;" href="mailto:photorankr@photorankr.com?Subject="Help/Suggestion">Email the Team</a>
      </div>
   </div> 
  <div class="grid_6 questionlist" style="width:200px;">
   <h1 class="contact">  </h1>
   
   <!--Query Images-->
   
   <?php
   
   $imagequery = mysql_query("SELECT source FROM photos WHERE points > 100 ORDER BY (points/votes) DESC LIMIT 0,20");
   
   for($iii=0; $iii < 14; $iii++){
   $imagesource = mysql_result($imagequery,$iii,"source");

    echo'<div class="grid_6" style="margin-left:5px;margin-top:10px;margin-bottom:      10px;"><img style="border:solid white 5px;" src=',$imagesource,' height="195" width="200" /></div>
    <br />';
    }
        
?>

    </div>  


   </div>
  </div>
 </div> 
</div><!--container end-->
<br />
<br />

<?php //footer(); ?>
         
</body>
</html>