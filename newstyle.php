<?php
//log them out if they try to logout
session_start();

if($_GET['action'] == logout) {
	$_SESSION['loggedin'] = 0;
	session_destroy();
}

?>
<?php

//connect to the database
require "db_connection.php";

$query="SELECT * FROM photos ORDER BY id ASC";
$firstresult=mysql_query($query);
$numberofpics=mysql_num_rows($firstresult);

$Time;         //time is in ten hours
$Points;
$source;
$Gravity=1.8;
$secondsPerHour=3600;
$timeIncrementsInHrs=10;

for($iii = 0; $iii <= $numberofpics-1; $iii++) {
	$currenttime=time();
	$phototime=mysql_result($firstresult, $iii, "time");
	$Time=$currenttime-$phototime;
	$Time/=$secondsPerHour;
	$Time/=$timeIncrementsInHrs;
	$Points=mysql_result($firstresult, $iii, "points");
	$Score=($Points-10)/(pow($Time, $Gravity));
	
	$source=mysql_result($firstresult, $iii, "source");
	$Scorequery="UPDATE photos SET score='$Score' WHERE source='$source'";
	mysql_query($Scorequery);
}

$realquery="SELECT * FROM photos ORDER BY score ASC";
$result=mysql_query($realquery);

//get x which holds the picture number
$x;
if(isset($_GET['x'])) {
$x=htmlentities($_GET['x']);
}
else {$x=0;}
if ($x < 0) {
$x=$numberofpics+$x;
}
if ($x>=$numberofpics) {
$x=$x-$numberofpics;
}

$d=$x+1;
if ($d >= $numberofpics) {$d = $d - $numberofpics;}
$e=$d+1;
if ($e >= $numberofpics) {$e = $e - $numberofpics;}
$f=$e+1;
if ($f >= $numberofpics) {$f = $f - $numberofpics;}
$ag=$f+1;
if ($ag >= $numberofpics) {$ag = $ag - $numberofpics;}
$ah=$ag+1;
if ($ah >= $numberofpics) {$ah = $ah - $numberofpics;}
$ai=$ah+1;
if ($ai >= $numberofpics) {$ai = $ai - $numberofpics;}
$aj=$ai+1;
if ($aj >= $numberofpics) {$aj = $aj - $numberofpics;}
$ak=$aj+1;
if ($ak >= $numberofpics) {$ak = $ak - $numberofpics;}
$al=$ak+1;
if ($al >= $numberofpics) {$al = $al - $numberofpics;}
$am=$al+1;
if ($am >= $numberofpics) {$am = $am - $numberofpics;}
$an=$am+1;
if ($an >= $numberofpics) {$an = $an - $numberofpics;}
$ao=$an+1;
if ($ao >= $numberofpics) {$ao = $ao - $numberofpics;}
$ap=$ao+1;
if ($ap >= $numberofpics) {$ap = $ap - $numberofpics;}
$aq=$ap+1;
if ($aq >= $numberofpics) {$aq = $aq - $numberofpics;}
$ar=$aq+1;
if ($ar >= $numberofpics) {$ar = $ar - $numberofpics;}
$as=$ar+1;
if ($as >= $numberofpics) {$as = $as - $numberofpics;}
$at=$as+1;
if ($at >= $numberofpics) {$at = $at - $numberofpics;}
$au=$at+1;
if ($au >= $numberofpics) {$au = $au - $numberofpics;}
$av=$au+1;
if ($av >= $numberofpics) {$av = $av - $numberofpics;}


//create the image to be displayed and the caption
$imageOne=mysql_result($result, $numberofpics-1-$x, "source");
$imageOneThumb=str_replace("userphotos/","userphotos/thumbs/", $imageOne);

$imageTwo=mysql_result($result, $numberofpics-1-$d, "source");
$imageTwoThumb=str_replace("userphotos/","userphotos/thumbs/", $imageTwo);


$imageThree=mysql_result($result, $numberofpics-1-$e, "source");
$imageThreeThumb=str_replace("userphotos/","userphotos/thumbs/", $imageThree);


$imageFour=mysql_result($result, $numberofpics-1-$f, "source");
$imageFourThumb=str_replace("userphotos/","userphotos/thumbs/", $imageFour);

$imageFive=mysql_result($result, $numberofpics-1-$ag, "source");
$imageFiveThumb=str_replace("userphotos/","userphotos/thumbs/", $imageFive);


$imageSix=mysql_result($result, $numberofpics-1-$ah, "source");
$imageSixThumb=str_replace("userphotos/","userphotos/thumbs/", $imageSix);


$imageSeven=mysql_result($result, $numberofpics-1-$ai, "source");
$imageSevenThumb=str_replace("userphotos/","userphotos/thumbs/", $imageSeven);


$imageEight=mysql_result($result, $numberofpics-1-$aj, "source");
$imageEightThumb=str_replace("userphotos/","userphotos/thumbs/", $imageEight);


$imageNine=mysql_result($result, $numberofpics-1-$ak, "source");
$imageNineThumb=str_replace("userphotos/","userphotos/thumbs/", $imageNine);


$imageTen=mysql_result($result, $numberofpics-1-$al, "source");
$imageTenThumb=str_replace("userphotos/","userphotos/thumbs/", $imageTen);


$imageEleven=mysql_result($result, $numberofpics-1-$am, "source");
$imageElevenThumb=str_replace("userphotos/","userphotos/thumbs/", $imageEleven);


$imageTwelve=mysql_result($result, $numberofpics-1-$an, "source");
$imageTwelveThumb=str_replace("userphotos/","userphotos/thumbs/", $imageTwelve);


$imageThirteen=mysql_result($result, $numberofpics-1-$ao, "source");
$imageThirteenThumb=str_replace("userphotos/","userphotos/thumbs/", $imageThirteen);


$imageFourteen=mysql_result($result, $numberofpics-1-$ap, "source");
$imageFourteenThumb=str_replace("userphotos/","userphotos/thumbs/", $imageFourteen);


$imageFifteen=mysql_result($result, $numberofpics-1-$aq, "source");
$imageFifteenThumb=str_replace("userphotos/","userphotos/thumbs/", $imageFifteen);


$imageSixteen=mysql_result($result, $numberofpics-1-$ar, "source");
$imageSixteenThumb=str_replace("userphotos/","userphotos/thumbs/", $imageSixteen);

$imageSeventeen=mysql_result($result, $numberofpics-1-$as, "source");
$imageSeventeenThumb=str_replace("userphotos/","userphotos/thumbs/", $imageSeventeen);

$imageEighteen=mysql_result($result, $numberofpics-1-$at, "source");
$imageEighteenThumb=str_replace("userphotos/","userphotos/thumbs/", $imageEighteen);

$imageNineteen=mysql_result($result, $numberofpics-1-$au, "source");
$imageNineteenThumb=str_replace("userphotos/","userphotos/thumbs/", $imageNineteen);

$imageTwenty=mysql_result($result, $numberofpics-1-$av, "source");
$imageTwentyThumb=str_replace("userphotos/","userphotos/thumbs/", $imageTwenty);


$label=mysql_result($result, $numberofpics-1-$x, "caption");
$imageID=mysql_result($result, $numberofpics-1-$x, "id");
$price=mysql_result($result, $numberofpics-1-$x, "price");
if ($price == "") {$price='.25';}

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
    
    ?>


<div style="position:absolute; top:0px;left:0px;font-family:lucida grande, georgia, helvetica; font-size: 25px; background:url('graphics/background.png');height:100%;width:100%;">


<?php

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

?>

</div>

<script language="JavaScript">

var x=<?php echo $x; ?>;

function slideshowForward() {
x=x+20;
location.href="trending.php?x="+x;
}

function slideshowBackward() {
x=x-20;
location.href="trending.php?x="+x;
}

</script>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<script type="text/javascript" src="js/prototype.js"></script>
<script type="text/javascript" src="js/scriptaculous.js?load=effects,builder"></script>
<script type="text/javascript" src="js/lightbox.js"></script>
<link rel="stylesheet" href="lightbox.css" type="text/css" media="screen" />

<link rel="stylesheet" href="style.css" type="text/css" />
<link rel="stylesheet" href="discoverandrank.css" type="text/css" />

<link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>

<title>Trending Photos</title>

<!--SOCIAL MEDIA SCRIPTS
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>

<script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script>

<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>

<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>-->

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


</head>
<body style="overflow-x:hidden;">

<?php
if($_SESSION['loggedin'] != 1) { //if they aren't logged in, display signin central
echo '<form name="login_form" method="post" action="', htmlentities($_SERVER['PHP_SELF']), '?action=login">
<div id="login">Email: &nbsp<input type="text" style="width:120px" name="emailaddress"/></div>
<div id="login2">Password: &nbsp<input type="password" style="width:120px" name="password"/></div>
<div id="login3"><a href="http://www.photorankr.com/signin.php">Not Registered?</a></div>

<input type="image" src="graphics/signin.png" style="height:35px; width:140px;" id="loginButton"/></form>';
}

else { //if they are logged in, show my profile button
echo '<div class="profileButton">
<a href="http://www.photorankr.com/myprofile.php"><img src="graphics/profileButton.png" height="45" width="180"></img></a>
</div>';
}
?>

<?php

if($_SESSION['loggedin'] == 1) {
echo '
<!--LOGOUT BUTTON-->
<div id="logout">
<a href="',htmlentities($_SERVER['PHP_SELF']),'?action=logout"><img src="graphics/logout.png" style="height:45px; width:170px;"/></a>
</div>';
}

?>

<!--LOGO AND SLOGAN-->
<div id="header">
<div style="text-align:left">
<div class="logo">
<a href="index.php"><img src="graphics/photorankr2.png"></img></a>
</div>
<div id="slogan">
<a href="index.php"><img src="graphics/slogan.png"></img></a>
</div>
</div>

<!--SOCIAL MEDIA BUTTONS

<div style="z-index:50000000;position:absolute;top:35px;left:560px;">
<a href="https://twitter.com/PhotoRankr" class="twitter-follow-button" data-show-count="false" data-size="large" data-show-screen-name="false">Follow @PhotoRankr</a>
</div>

<div style="z-index:50000000;position:absolute;top:37px;left:820px;">
<g:plusone></g:plusone>

<div style="position:absolute;top:3px;left:-160px;">
<div class="fb-like" data-href="https://www.facebook.com/pages/PhotoRankr/140599622721692" data-send="true" data-layout="button_count" data-width="200" data-show-faces="false"></div>-->
</div>

</div>

</div>



</div>

<!--FORWARD AND BACK FUNCTION-->

<a class="back" href="JavaScript:slideshowBackward()"><img src="graphics/arrowofthegodsleft.png" height="125" width="100"/></a>
<a class="next" href="JavaScript:slideshowForward()"><img src="graphics/arrowofthegodsright.png"height="125" width="100"/></a>

<!--LEFT BOX GRAPHIC, SET WIDTH AND HEIGHT, ETC.

<div style="z-index:0;float:left;position:absolute;top:115px;left:348px;margin:auto;">
<img src="graphics/controlbar2.png" alt="middle" width="130" height="50"/>
</div>-->  


<!--SOCIAL MEDIA BUTTONS
<div style="position:absolute;top:158px;left:910px;">
<a href="https://twitter.com/share" class="twitter-share-button" data-url="http://www.photorankr.com" data-text="I love this site!" data-via="PhotoRankr" data-size="large" data-related="PhotoRankr">Tweet</a>
</div>-->


<div style="z-index:2;margin-top:-1px;margin-left:500px;">




<!--PLACE THREE NEXT PICS IN BOXES TO RIGHT-->

<div id="nextpicone">
<a href="fullsize.php?image=<?php echo $imageOne; ?>" ><img src="http://www.photorankr.com/<?php echo $imageOneThumb; ?>" name="mynextpicone"/></a>

</div>

<div id="nextpictwo">
<a href="fullsize.php?image=<?php echo $imageTwo; ?>" ><img src="http://www.photorankr.com/<?php echo $imageTwoThumb; ?>" name="mynextpictwo"/></a>

</div>

<div id="nextpicthree">
<a href="fullsize.php?image=<?php echo $imageThree; ?>" ><img src="http://www.photorankr.com/<?php echo $imageThreeThumb; ?>" name="mynextpicthree"></img></a>
</div>

<div id="nextpicFour">
<a href="fullsize.php?image=<?php echo $imageFour; ?>" ><img src="http://www.photorankr.com/<?php echo $imageFourThumb; ?>" name="mynextpicone"></img></a>

</div>

<div id="nextpicFive">
<a href="fullsize.php?image=<?php echo $imageFive; ?>" ><img src="http://www.photorankr.com/<?php echo $imageFiveThumb; ?>" name="mynextpictwo"></img></a>

</div>

<div id="nextpicSix">
<a href="fullsize.php?image=<?php echo $imageSix; ?>" ><img src="http://www.photorankr.com/<?php echo $imageSixThumb; ?>" name="mynextpicthree"></img></a>
</div>

<div id="nextpicSeven">
<a href="fullsize.php?image=<?php echo $imageSeven; ?>" ><img src="http://www.photorankr.com/<?php echo $imageSevenThumb; ?>" name="mynextpicone"></img></a>

</div>

<div id="nextpicEight">
<a href="fullsize.php?image=<?php echo $imageEight; ?>" ><img src="http://www.photorankr.com/<?php echo $imageEightThumb; ?>" name="mynextpictwo"></img></a>

</div>

<div id="nextpicNine">
<a href="fullsize.php?image=<?php echo $imageNine; ?>" ><img src="http://www.photorankr.com/<?php echo $imageNineThumb; ?>" name="mynextpicthree"></img></a>
</div>

<div id="nextpicTen">
<a href="fullsize.php?image=<?php echo $imageTen; ?>" ><img src="http://www.photorankr.com/<?php echo $imageTenThumb; ?>" name="mynextpicone"></img></a>

</div>

<div id="nextpicEleven">
<a href="fullsize.php?image=<?php echo $imageEleven; ?>" ><img src="http://www.photorankr.com/<?php echo $imageElevenThumb; ?>" name="mynextpictwo"></img></a>

</div>

<div id="nextpicTwelve">
<a href="fullsize.php?image=<?php echo $imageTwelve; ?>" ><img src="http://www.photorankr.com/<?php echo $imageTwelveThumb; ?>" name="mynextpicthree"></img></a>
</div>

<div id="nextpicThirteen">
<a href="fullsize.php?image=<?php echo $imageThirteen; ?>" ><img src="http://www.photorankr.com/<?php echo $imageThirteenThumb; ?>" name="mynextpicone"></img></a>

</div>

<div id="nextpicFourteen">
<a href="fullsize.php?image=<?php echo $imageFourteen; ?>" ><img src="http://www.photorankr.com/<?php echo $imageFourteenThumb; ?>" name="mynextpictwo"></img></a>

</div>

<div id="nextpicFifteen">
<a href="fullsize.php?image=<?php echo $imageFifteen; ?>" ><img src="http://www.photorankr.com/<?php echo $imageFifteenThumb; ?>" name="mynextpicthree"></img></a>
</div>

<div id="nextpicSixteen">
<a href="fullsize.php?image=<?php echo $imageSixteen; ?>" ><img src="http://www.photorankr.com/<?php echo $imageSixteenThumb; ?>" name="mynextpicthree"></img></a>
</div>

<div id="nextpicSeventeen">
<a href="fullsize.php?image=<?php echo $imageSeventeen; ?>" ><img src="http://www.photorankr.com/<?php echo $imageSeventeenThumb; ?>" name="mynextpicthree"></img></a>
</div>


<div id="nextpicEighteen">
<a href="fullsize.php?image=<?php echo $imageEighteen; ?>" ><img src="http://www.photorankr.com/<?php echo $imageEighteenThumb; ?>" name="mynextpicthree"></img></a>
</div>

<div id="nextpicNineteen">
<a href="fullsize.php?image=<?php echo $imageNineteen; ?>" ><img src="http://www.photorankr.com/<?php echo $imageNineteenThumb; ?>" name="mynextpicthree"></img></a>
</div>


<div id="nextpicTwenty">
<a href="fullsize.php?image=<?php echo $imageTwenty; ?>" ><img src="http://www.photorankr.com/<?php echo $imageTwentyThumb; ?>" name="mynextpicthree"></img></a>
</div>  

<!--MIDDLE GRAPHIC
<div style="position:absolute; top:140px; left:45px; z-index:-10000;">
<img src="graphics/middle.png" alt="middle" width="1150" height="800"/>
</div>-->

<!--BLACK LINE AT TOP AND BOTTOM-->

<img style="position:absolute;top:100px;left:20px;z-index:12342134123412039840129834091823409123041234;" src="graphics/navbartest.png" height="60" width="1200"></img>

<img style="position:absolute;width:100%;height:1px;top:954px;left:0px;" src="graphics/line.png"></img>


<!--PICTURE SHADOW FOR GALLERY-->
<div class="picshadow1">
<img src="graphics/pictureshadow.png" height="200 width="200"></img>  
</div>

<div class="picshadow2">
<img src="graphics/pictureshadow.png" height="200 width="200"></img>  
</div>

<div class="picshadow3">
<img src="graphics/pictureshadow.png" height="200 width="200"></img>  
</div>

<div class="picshadow4">
<img src="graphics/pictureshadow.png" height="200 width="200"></img>  
</div>

<div class="picshadow5">
<img src="graphics/pictureshadow.png" height="200 width="200"></img>  
</div>

<div class="picshadow6">
<img src="graphics/pictureshadow.png" height="200 width="200"></img>  
</div>

<div class="picshadow7">
<img src="graphics/pictureshadow.png" height="200 width="200"></img>  
</div>

<div class="picshadow8">
<img src="graphics/pictureshadow.png" height="200 width="200"></img>  
</div>

<div class="picshadow9">
<img src="graphics/pictureshadow.png" height="200 width="200"></img>  
</div>

<div class="picshadow10">
<img src="graphics/pictureshadow.png" height="200 width="200"></img>  
</div>

<div class="picshadow11">
<img src="graphics/pictureshadow.png" height="200 width="200"></img>  
</div>

<div class="picshadow12">
<img src="graphics/pictureshadow.png" height="200 width="200"></img>  
</div>

<div class="picshadow13">
<img src="graphics/pictureshadow.png" height="200 width="200"></img>  
</div>

<div class="picshadow14">
<img src="graphics/pictureshadow.png" height="200 width="200"></img>  
</div>

<div class="picshadow15">
<img src="graphics/pictureshadow.png" height="200 width="200"></img>  
</div>

<div class="picshadow16">
<img src="graphics/pictureshadow.png" height="200 width="200"></img>  
</div>

<div class="picshadow17">
<img src="graphics/pictureshadow.png" height="200 width="200"></img>  
</div>

<div class="picshadow18">
<img src="graphics/pictureshadow.png" height="200 width="200"></img>  
</div>

<div class="picshadow19">
<img src="graphics/pictureshadow.png" height="200 width="200"></img>  
</div>

<div class="picshadow20">
<img src="graphics/pictureshadow.png" height="200 width="200"></img>  
</div>



<!--FOOTER-->

<div id="footerDiscoverandRank">
<br/>
<div id="copyright">&copy 2012 photorankr<br/></div>
<hr/>
<div id="footerlinks">
<a href="contact.html">Contact Us&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<a href="about.html">About</a>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</a>
<a href="terms.html">Terms</a>
</div>
</div>


</div>

</div>

</body>
</html>