<?php
//log them out if they try to logout
session_start();

if($_GET['action'] == logout) {
	$_SESSION['loggedin'] = 0;
	session_destroy();
}

//connect to the database
require "db_connection.php";




//start session
@session_start();
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


//QUERY FOR 'LIVE' PHOTOSTREAM
$email=$_SESSION['email'];

//QUERY FOR USERINFO
$select_query="SELECT * FROM userinfo WHERE emailaddress ='$email'";
$result=mysql_query($select_query);
$row=mysql_fetch_array($result);
$firstname=$row['firstname'];
$firstname = ucwords($firstname);
$lastname=$row['lastname'];
$lastname = ucwords($lastname);
$profilepic=$row['profilepic'];
$profilescore=$row['totalscore'];
$numberofpics = mysql_query("SELECT * FROM photos WHERE emailaddress='$email'");
$numberofpics = mysql_num_rows($numberofpics);

//PORTFOLIO RANKING

$followersquery="SELECT * FROM userinfo WHERE following LIKE '%$email%'";
	$followersresult=mysql_query($followersquery);
	$numberfollowers = mysql_num_rows($followersresult);
    
    
    $userphotos="SELECT * FROM photos WHERE emailaddress = '$email'";
    $userphotosquery=mysql_query($userphotos);
    $numphotos=mysql_num_rows($userphotosquery);
    
    for($iii = 0; $iii < $numphotos; $iii++) {
		$points = mysql_result($userphotosquery, $iii, "points");
        $votes = mysql_result($userphotosquery, $iii, "votes");
        $portfoliopoints+=$points;
        $portfoliovotes+=$votes;
        }
    
    if ($portfoliovotes > 0) {
    $portfolioranking=($portfoliopoints/$portfoliovotes);
    $portfolioranking=number_format($portfolioranking, 2, '.', '');
    
    $scorequery = "UPDATE userinfo SET totalscore = '$portfoliopoints' WHERE emailaddress = '$emailaddress'";    
    $scoreresult = mysql_query($scorequery);
    
    }
    
    else if ($portfoliovotes < 1) {
    $portfolioranking="N/A";
    }	
    
    
    //NUMBER FOLLOWING
    $emailquery=("SELECT following FROM userinfo WHERE emailaddress ='$email'");
	$followresult=mysql_query($emailquery);
	$followinglist=mysql_result($followresult, 0, "following");
	$followingquery="SELECT * FROM userinfo WHERE emailaddress IN ($followinglist)";
	$followingresult = mysql_query($followingquery);
	$numberfollowing = mysql_num_rows($followingresult);
    
    //QUERY FOR NOTIFICATIONS
$currentnots = "SELECT * FROM userinfo WHERE emailaddress = '$email'";
$currentnotsquery = mysql_query($currentnots);
$currentnotsresult = mysql_result($currentnotsquery, 0, "notifications");

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
 <head>
  <title>My Photostream</title>
  <meta name="Generator" content="EditPlus">
     <meta name="viewport" content="width=1200" /> 
  <meta name="Author" content="PhotoRankr, PhotoRankr.com">
  <meta name="Keywords" content="photos, sharing photos, photo sharing, photography, photography club, sell photos, sell photography, where to sell my photography, good sites for selling photography, making money from photography, making money off photography, social networking, social network, social networks, where to sell my photos, good sites for selling photos, good site to sell photos, making money from photos">
  <meta name="Description" content="PhotoRankr allows photographers of all skill levels to sell and share their work. Create your photostream cutomized to what you want to see. Add photos to your favorites, rank them, and watch them trend. Build your portfolio with Photorankr.">

 <link rel="stylesheet" href="reset.css" type="text/css" />
  <link rel="stylesheet" href="text.css" type="text/css" />
  <link rel="stylesheet" href="960_24.css" type="text/css" />
    <link rel="shortcut icon" type="image/x-png" href="http://photorankr.com/graphics/favicon.png"/>
	  <link rel="stylesheet" type="text/css" href="bootstrapPS.css" />
  <script type="text/javascript" src="jquery.js"></script>   
  <script src="bootstrap-dropdown.js" type="text/javascript"></script>
  <script src="bootstrap-collapse.js" type="text/javascript"></script>
  
  
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

<script type="text/javascript">
  $(function() {
  // Setup drop down menu
  $('.dropdown-toggle').dropdown();
 
  // Fix input element click problem
  $('.dropdown input, .dropdown label').click(function(e) {
    e.stopPropagation();
  });
});
</script>

 </head>
 

<body style="overflow-x:hidden; background-color: #eeeff3;">

<!--NAVIGATION BAR-->
<div class="navbar" style="z-index:10; font-size: 16px;">
	<div class="navbar-inner">
		<div class="container">
			    <ul class="nav">
					<li><a class="brand" href="index.php"><div style="margin-top:-6px"><img src="logo.png" width="160" /></div></a></li>
					<li class="active"><a href="photostream.php">Photostream</a></li>
					<li><a href="trending.php">Trending</a></li>
					<li><a href="newest.php">Newest</a></li>
					<li><a href="topranked.php">Top Ranked</a></li>
					<?php if($_SESSION['loggedin'] == 1) { echo'
					'; } ?>
					<li class="dropdown">
<?php
@session_start();
if($_SESSION['loggedin'] == 1) {
	echo '
						<a href="myprofile.php" class="dropdown-toggle" data-toggle="dropdown">My Profile<b class="caret"></b></a>
						<ul class="dropdown-menu" data-dropdown="dropdown">
							<li><a href="myprofile.php?view=info">Information</a></li>
							<li><a href="myprofile.php">Photography</a></li>
							<li><a href="myprofile.php?view=upload">Upload</a></li>
							<li><a href="myprofile.php?view=followers">Followers</a></li>
							<li><a href="myprofile.php?view=following">Following</a></li>
							<li><a href="myprofile.php?view=faves">Favorites</a></li>
							<li><a href="photostream.php?action=logout">Log Out</a></li>
						</ul>
                        
                        <li><a href="myprofile.php?view=notifications"><span style="font-size:18px;padding-right:3px;"><span style="position:relative;top:4px"><i class="icon-exclamation-sign icon-white"></i></span> ',$currentnotsresult,'</a></li>
                        
                        '; 
} else {
				echo '	<a href="signin.php" class="dropdown-toggle" data-toggle="dropdown">Log In<b class="caret"></b></a>
						<ul class="dropdown-menu" data-dropdown="dropdown">
							<li><a href="signin.php">Register Now</a></li>
							<li><br/></li>
							<form name="login_form" method="post" action="photostream.php?action=login">
							<li style="margin-left: 5px; margin-right: 5px; width: 200px;"><span style="color: white; margin-bottom: 5px;">Email: </span><input type="text" style="width:100px; margin-left: 40px;" name="emailaddress" /></li>
							<li style="margin-left: 5px;"><span style="color: white">Password: </span>&nbsp<input type="password" style="width:100px; margin-left: 5px;" name="password"/></li>
							<li style="margin-left: 70px;"><input type="submit" value="sign in" id="loginButton"/></li>
							</form>
						</ul>';
} ?>
					</li>
					<form class="navbar-search" action="search.php" method="post">
						<input type="text" class="search-query" name="searchterm" placeholder="Search">
					 </form>
					 
				</ul>
			
		</div> <!--/end boostrap divs navbar-->
    </div>
</div>



 <!--big container-->
<?php
if($_SESSION['loggedin'] == 1) {
    
    echo'
    <div id="container" class="container_24">
    //NAV PILLS
    <div class="grid_2 alpha" style="margin-left:-200px;margin-top:70px;position:fixed;">
    <div id="example" style="background-color: rgb(255, 255, 255); border-radius: 3px 3px 3px 3px; width: 285px; height: 100px;" >
    <a href="viewprofile.php?first=',$firstname,'&last=',$lastname,'" style="font-size: 16px; text-decoration:none;">
					<div style="position: relative; left: 10px; top: 10px;"><img style="border: 1px solid black" src="',$profilepic,'" 
					height="80px" width="80px" />
					</div> 
					<div style="position: relative; left: 100px; top: -67px;">',$firstname, ' ', $lastname,'
					</div>
					</a>
					<a href="myprofile.php">
					<span style="position: relative; left: 100px; top: -60px;"><button class="btn btn-primary" style="height: 28px; width: 90px; font-family: arial; font-size:14px;" type="button">PROFILE</button></span></a>
					<span style="position: relative; left: 10px; top: -30px;"><span style="opacity: .5">Portfolio Average: </span>',$portfolioranking,'</span>';
                    if ($numberofpics < 100) {
                    echo'
					<div style="position:relative; left: 200px; top: -82px; font-size: 18px; font-family: arial;">
					',$numberofpics,' photos</div>
                    </div>'; 
                    }
                    elseif ($numberofpics >99) {
                    echo'
					<div style="position:relative; left: 195px; top: -82px; font-size: 17px; font-family: arial;">
					',$numberofpics,' photos</div>
                    </div>';
                    }
                    
      echo'              
    <div style="margin-top:0px;position:fixed;margin-left:40px;">
    
         <!--Information Box-->
            <div style="text-align: center">
            <div id="example4" style="margin-top:30px; width:200px;">
            <br />
            <div style="opacity:.5">Number of Photos:</div>',$numberofpics,'
            <br /><br />
            <div style="opacity:.5">Total Points:</div>',$portfoliopoints,'
            <br /><br />
            <div style="opacity:.5">Portfolio Score:</div>', $portfolioranking,'
            <br /><br />
            <div style="opacity:.5">Followers:</div>',$numberfollowers,'
            <br /><br />
            <div style="opacity:.5">Following:</div>',$numberfollowing,'
            <br /><br />
            </div>
            </div>
            
            <div style="margin-top:15px;margin-left:-40px;font-size:15px;font-family:arial,helvetica neue;width:290px;">
            Welcome to your new photostream! In it you can keep up with all of the photographers you are following. This includes who they are following, uploads, comments, favorites, and recently trending photography.</div>
            
            </div>
            </div>
        <!--End Information Box-->
    
    
    <!--NAV LIST FOR LATER-->
    <!--<ul class="nav nav-pills nav-stacked">
    <li><a href="photostream.php">Newsfeed</a></li>
    <li><a href="photostream.php?view=newest">Newest Photography</a></li>
  <li><a href="photostream.php?view=comments">Latest Comments</a></li>
  <li><a href="photostream.php?view=favorites">Recent Favorites</a></li>
  <li><a href="photostream.php?view=following">Now Following</a></li>
  <li><a href="photostream.php?view=trending">Recently Trending</a></li>
</ul>-->';

  }  
    
   
    
    //GET VIEW
    if(isset($_GET['view'])) {
	$view=htmlentities($_GET['view']); //get which tab of profile they are looking at
}
 ?>
 
<div class="grid_24" id="thepics" style="margin-top:20px;">

 
<?php

if($_SESSION['loggedin'] != 1) { //if they aren't logged in, display signin central
echo'
<div style="text-align:center;line-height: 25px;margin-top:100px;padding-bottom:230px;padding-left:100px;padding-right:100px;">
Please sign in above to view your personal Photostream...
<br /><br />
Your photostream is a personalized stream of photography based on who you are following on PhotoRankr. Here you can see what your favorite photographers are uploading, which of their photos are now trending, and who they are following. 
<br />
<br />
You can follow photographers by viewing their profile and clicking the follow button.
</div>';
}



else { //if they are logged in

//PHOTOSTREAM PHOTOS QUERY
$emailquery=("SELECT following FROM userinfo WHERE emailaddress ='$email'");
$followresult=mysql_query($emailquery);
$followlist=mysql_result($followresult, 0, "following");
$followrow=mysql_fetch_array($followresult);
$following=$followrow['following'];

$newsfeedquery = "SELECT * FROM newsfeed ORDER BY ID DESC";
$newsfeedresult = mysql_query($newsfeedquery);

$maxwidth = 400;
       
echo'
<div id="container" class="container_24 push_5" style="border-left:1px solid #333;">';      
            
for($iii=1; $iii <= 10; $iii++) {
    $newsrow = mysql_fetch_array($newsfeedresult);
    $id = $newsrow['id'];
    $type = $newsrow['type'];
    $email2 = $newsrow['emailaddress'];
    $isfollowing = strpos($followlist,$email2);
    
        
    if ($type == "photo") {
	$image = $newsrow['source'];
	$caption = $newsrow['caption'];
    $owner = $newsrow['emailaddress'];
    $ownersquery = "SELECT * FROM userinfo WHERE emailaddress = '$owner'";
    $ownerresult = mysql_query($ownersquery); 
    $ownerrow = mysql_fetch_array($ownerresult);
    $ownerfirst = $ownerrow['firstname'];
    $ownerlast = $ownerrow['lastname'];
    $ownerfull = $ownerfirst . " " . $ownerlast;
    $ownerfull = ucwords($ownerfull);
	list($width, $height) = getimagesize($image);
	$imgratio = $height / $width;
	$height = $imgratio * $maxwidth;
    echo '<div class="grid_9 push_2 fPic photoshadowFEED" id="',$id,'" style="width:600px; height:',$height,'+30px; margin-top:40px; overflow: hidden;">
    <a href="http://www.photorankr.com/fullsize.php?image=',$image,'"><img src="http://www.photorankr.com/',$image,'" height=', $height, 'px width="600px" /></a>
    <br /><div style="margin-top:5px;color:#333;font-size:17px;font-family:arial,helvetica neue;padding-bottom:3px;"><img src="graphics/newsfeedarrow.png" height="50" width="50" />
    ',$ownerfull,' uploaded "',$caption,'"</div>';
    echo '</div>';  

	}
    
    elseif ($type == "fave") {
    $owner = $newsrow['owner'];
    $ownersquery = "SELECT * FROM userinfo WHERE emailaddress = '$owner'";
    $ownerresult = mysql_query($ownersquery); 
    $ownerrow = mysql_fetch_array($ownerresult);
    $ownerfirst = $ownerrow['firstname'];
    $ownerlast = $ownerrow['lastname'];
    $ownerfull = $ownerfirst . " " . $ownerlast;
    $ownerfull = ucwords($ownerfull);
    $firstname = $newsrow['firstname'];
    $firstname = ucwords($firstname);
    $lastname = $newsrow['lastname'];
    $lastname = ucwords($lastname);
    $image = $newsrow['source'];
	$caption = $newsrow['caption'];
	list($width, $height) = getimagesize($image);
	$imgratio = $height / $width;
	$height = $imgratio * $maxwidth;
    $fullname = $firstname . " " . $lastname;
    echo '<div class="grid_9 push_2 fPic photoshadowFEED" id="',$id,'" style="width:600px; height:',$height,'+30px; margin-top:40px; overflow: hidden;">
    <a href="http://www.photorankr.com/fullsize.php?image=',$image,'"><img src="http://www.photorankr.com/',$image,'" height=', $height, 'px width="600px" /></a>
    <br /><div style="margin-top:5px;color:#333;font-size:17px;font-family:arial,helvetica neue;padding-bottom:3px;"><img src="graphics/newsfeedfavorite.png" height="50" width="50" />
    ',$fullname,' favorited "',$caption,'" by ',$ownerfull,'</div>';
    echo '</div>';   
    }
    
    elseif ($type == "trending") {
    $owner = $newsrow['owner'];
    $ownersquery = "SELECT * FROM userinfo WHERE emailaddress = '$owner'";
    $ownerresult = mysql_query($ownersquery); 
    $ownerrow = mysql_fetch_array($ownerresult);
    $ownerfirst = $ownerrow['firstname'];
    $ownerlast = $ownerrow['lastname'];
    $ownerfull = $ownerfirst . " " . $ownerlast;
    $ownerfull = ucwords($ownerfull);
    $image = $newsrow['source'];
	$caption = $newsrow['caption'];
	list($width, $height) = getimagesize($image);
	$imgratio = $height / $width;
	$height = $imgratio * $maxwidth;
    echo '<div class="grid_9 push_2 fPic photoshadowFEED" id="',$id,'" style="width:600px; height:',$height,'+30px; margin-top:40px; overflow: hidden;">
    <a href="http://www.photorankr.com/fullsize.php?image=',$image,'"><img src="http://www.photorankr.com/',$image,'" height=', $height, 'px width="600px" /></a>
    <br /><div style="margin-top:5px;color:#333;font-size:17px;font-family:arial,helvetica neue;padding-bottom:3px;"><img src="graphics/newsfeedtrending.png" height="50" width="50" />
    "',$caption,'" by ',$ownerfull,' is now trending</div>';
    echo '</div>';   
    }
    
    elseif ($type == "follow") {
    $email4 = $newsrow['following'];
    $newaccount = "SELECT * FROM userinfo WHERE emailaddress = '$email4'";
    $accountresult = mysql_query($newaccount); 
    $accountrow = mysql_fetch_array($accountresult);
    $profilepic = $accountrow['profilepic'];
    $ownerfirst = $accountrow['firstname'];
    $ownerlast = $accountrow['lastname'];
    $firstname = $newsrow['firstname'];
    $firstname = ucwords($firstname);
    $lastname = $newsrow['lastname'];
    $lastname = ucwords($lastname);
    $owner = $newsrow['owner'];
    $owner = ucwords($owner);
    echo '<a style="text-decoration:none" href="http://www.photorankr.com/viewprofile.php?first=',$ownerfirst,'&last=',$ownerlast,'"><div class="grid_9 push_2 fPic photoshadowFEED" id="',$id,'" style="width:600px; height:48px; margin-top:40px; overflow: hidden;color:#333;font-size:17px;font-family:arial,helvetica neue;padding-bottom:3px;"><img src="graphics/newsfeednewfollower.png" height="50" width="50" />
<img src="',$profilepic,'" height="50" width="50" />&nbsp;&nbsp;&nbsp;&nbsp;', $firstname, ' ',$lastname,' is now following ',$owner,'</div></a><br />';
    }
    
    elseif ($type == "comment") {
    $owner = $newsrow['owner'];
    $ownersquery = "SELECT * FROM userinfo WHERE emailaddress = '$owner'";
    $ownerresult = mysql_query($ownersquery); 
    $ownerrow = mysql_fetch_array($ownerresult);
    $ownerfirst = $ownerrow['firstname'];
    $ownerlast = $ownerrow['lastname'];
    $ownerfull = $ownerfirst . " " . $ownerlast;
    $ownerfull = ucwords($ownerfull);
    $firstname = $newsrow['firstname'];
    $firstname = ucwords($firstname);
    $lastname = $newsrow['lastname'];
    $lastname = ucwords($lastname);
    $image = $newsrow['source'];
    $fullname = $firstname . " " . $lastname;
    list($width, $height) = getimagesize($image);
	$imgratio = $height / $width;
	$height = $imgratio * $maxwidth;
    echo '<div class="grid_9 push_2 fPic photoshadowFEED" id="',$id,'" style="width:600px; height:',$height,'+30px; margin-top:40px; overflow: hidden;"><a style="text-decoration:none" href="http://www.photorankr.com/fullsize.php?image=',$image,'">
    <img src="http://www.photorankr.com/',$image,'" height=', $height, 'px width="600px" /></a>
    <br /><br /><div style="margin-top:5px;color:#333;font-size:17px;font-family:arial,helvetica neue;padding-bottom:3px;"><img src="graphics/newsfeedcomment.png" height="50" width="50" />
    ',$fullname,' commented on ',$ownerfull,'&#39;s photo:</div>';
    $txt=".txt";
	$imagenew=str_replace("userphotos/","", $image);
	$searchchars=array('.jpg','.png','.tiff','.JPG','.jpeg','.JPEG','.gif');
	$imagenew=str_replace($searchchars,"", $imagenew);
	$file = "comments/" . $imagenew . $txt; 
	echo '<br /><hr style="color: black" /><div style="margin-left: 5px; height: 100%; overflow-y: scroll;">';
	@include("$file");
	if (@file_get_contents($file) == '') {
		echo '<div style="text-align: center;">Be the first to leave a comment!<br /><br /></div>';
	}
	echo '</div>';
    echo '</div>';  
    }    
    
    



    if ($type == "signup") {
    $email5 = $newsrow['emailaddress'];
    $newaccount = "SELECT * FROM userinfo WHERE emailaddress = '$email5'";
    $accountresult = mysql_query($newaccount); 
    $accountrow = mysql_fetch_array($accountresult);
    $profilepic = $accountrow['profilepic'];
    $firstname = $newsrow['firstname'];
    $firstname = ucwords($firstname);
    $lastname = $newsrow['lastname'];
    $lastname = ucwords($lastname);
    echo '<a style="text-decoration:none" href="http://www.photorankr.com/viewprofile.php?first=',$firstname,'&last=',$lastname,'"><div class="grid_9 push_2 fPic photoshadowFEED" id="',$id,'" style="width:600px; height:48px; margin-top:40px; overflow: hidden;color:#333;font-size:17px;font-family:arial,helvetica neue;padding-bottom:3px;"><img src="graphics/newsfeedsignup.png" height="50" width="50" />
<img src="',$profilepic,'" height="50" width="50" />&nbsp&nbsp&nbsp&nbsp', $firstname, ' ',$lastname,' joined PhotoRankr</div></a><br />';
    }
}
    }
                                                                                                        
echo '</div>';
//end grid_24 div
?>


<div id="loadMoreNewsfeed" style="display: none; text-align: center; margin-top: 25px;">Loading...</div>
<?php

echo '<script>

var last = 0;

	$(window).scroll(function(){
		if($(window).scrollTop() > $(document).height() - $(window).height()-100) {
			if(last != $(".fPic:last").attr("id")) {
				$("div#loadMorePics").show();
				$.ajax({
					url: "loadMoreNewsfeed.php?lastPicture=" + $(".fPic:last").attr("id") + "&first=', $firstname, ' + &last=', $lastname, ' + &email=', $email, '",
					success: function(html) {
						if(html) {
							$("#thepics").append(html);
							$("div#loadMoreNewsfeed").hide();
						}
					}
				});
				last = $(".fPic:last").attr("id");
			}
		}
	});
</script>';
?>

</div>
<!--/end container-->

<!--footer
			<div class="grid_24" style="margin-top: 30px; text-align: center;">
				<a href="http://photorankr.com/about.php" style="text-decoration: none">About</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<a href="http://photorankr.com/terms.php" style="text-decoration: none">Terms</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<a href="http://photorankr.com/contact.php" style="text-decoration: none">Contact Us</a>
			</div>
			<div class="grid_24" style="text-align: center; margin-bottom: 25px; margin-top: 5px;">
				&copy; Photorankr 2012
			</div>
        end footer-->




</body>
</html>