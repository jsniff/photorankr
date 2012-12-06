<?php

//connect to the database
require "../db_connection.php";
require "functions.php";

//start the session
session_start();

    // if login form has been submitted
    if(htmlentities($_GET['action']) == "login") { 
        login();
    }
    elseif(htmlentities($_GET['action']) == "logout") { 
        logout();
    }

    $email = $_SESSION['email'];

    //QUERY FOR NOTIFICATIONS
    $currentnots = "SELECT * FROM userinfo WHERE emailaddress = '$email'";
    $currentnotsquery = mysql_query($currentnots);
    $currentnotsresult = mysql_result($currentnotsquery, 0, "notifications");

    //notifications query reset 
    if($currentnotsresult > 0) {
    $notsquery = "UPDATE userinfo SET notifications = 0 WHERE emailaddress = '$email6'";
    $notsqueryrun = mysql_query($notsquery); }

//User information
$userinfo = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$email'");
$profilpic = mysql_result($userinfo,0,'profilepic');
$firstname = mysql_result($userinfo,0,'firstname');
$lastname = mysql_result($userinfo,0,'lastname');
$fullname = $firstname ." ". $lastname;
$age = mysql_result($userinfo,0,'age');
$gender = mysql_result($userinfo,0,'gender');
$location = mysql_result($userinfo,0,'location');
$camera = mysql_result($userinfo,0,'camera');
$facebookpage = mysql_result($userinfo,0,'facebookpage');
$twitterpage = mysql_result($userinfo,0,'twitterpage');
$bio = mysql_result($userinfo,0,'bio');
$quote = mysql_result($userinfo,0,'quote');
$reputation = mysql_result($userinfo,0,'reputation');
$profileviews = mysql_result($userinfo,0,'profileviews');

//Blog Information
$blogquery = mysql_query("SELECT * FROM blog WHERE emailaddress = '$email' ORDER BY id DESC");
$numblogposts = mysql_num_rows($blogquery);
$newestpost =  mysql_result($blogquery,0,'content');
$posttime =  mysql_result($blogquery,0,'time');
$postdate = '10/24/12';

//Portfolio Information
    $followersquery="SELECT * FROM userinfo WHERE following LIKE '%$email%'";
	$followersresult=mysql_query($followersquery);
	$numberfollowers = mysql_num_rows($followersresult);
    
    //Grab Overall Portfolio Ranking
    $userphotos="SELECT * FROM photos WHERE emailaddress = '$email'";
    $userphotosquery=mysql_query($userphotos);
    $numphotos=mysql_num_rows($userphotosquery);
    
    for($iii = 0; $iii < $numphotos; $iii++) {
		$points = mysql_result($userphotosquery, $iii, "points");
        $votes = mysql_result($userphotosquery, $iii, "votes");
        $totalfaves = mysql_result($userphotosquery, $iii, "faves");
        $portfoliopoints+=$points;
        $portfoliovotes+=$votes;
        $portfoliofaves+=$totalfaves;
        }
    
    if ($portfoliovotes > 0) {
    $portfolioranking=($portfoliopoints/$portfoliovotes);
    $portfolioranking=number_format($portfolioranking, 2, '.', '');
    
    $scorequery = "UPDATE userinfo SET totalscore = '$portfoliopoints' WHERE emailaddress = '$email'";    
    $scoreresult = mysql_query($scorequery);
    
    }
    
    else if ($portfoliovotes < 1) {
    $portfolioranking="N/A";
    }	
    
    //Number Following
    $emailquery=("SELECT following FROM userinfo WHERE emailaddress ='$email'");
	$followresult=mysql_query($emailquery);
	$followinglist=mysql_result($followresult, 0, "following");
	$followingquery="SELECT * FROM userinfo WHERE emailaddress IN ($followinglist)";
	$followingresult = mysql_query($followingquery);
	$numberfollowing = mysql_num_rows($followingresult);


    //Get Views & URI
    $view = htmlentities($_GET['view']);
    $action = htmlentities($_GET['action']);
    $option = htmlentities($_GET['option']);  
    $uri = $_SERVER['REQUEST_URI'];

if($_GET['action'] == "signup") { //if they tried to sign up from signin.php
	$firstname = addslashes($_REQUEST['firstname']);
    $firstname = trim($firstname);
    $firstname = ucwords($firstname);
	$lastname = addslashes($_REQUEST['lastname']);
    $optin = addslashes($_REQUEST['optin']);
    $lastname = trim($lastname);
    $lastname = ucwords($lastname);
	$newemail = mysql_real_escape_string($_REQUEST['emailaddress']);
	$password = mysql_real_escape_string($_REQUEST['password']);
	$confirmpassword = mysql_real_escape_string($_REQUEST['confirmpassword']);
	$terms = mysql_real_escape_string($_REQUEST['terms']);
	$mattfollow = "'support@photorankr.com'";
	$originalfave = "'userphotos/paintedbuilding1.jpg'";
	$originalfave = addslashes($originalfave);
	$mattfollow = addslashes($mattfollow);
	$check = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$newemail'");
	$others = mysql_num_rows($check);
    $currenttime = time();

	//if they forgot to enter any information
	if(!$_REQUEST['firstname'] or !$_REQUEST['lastname'] or !$_REQUEST['emailaddress'] or !$_REQUEST['password'] or !$_REQUEST['confirmpassword'] or !$_REQUEST['terms']) {
		mysql_close();
        echo '<META HTTP-EQUIV="Refresh" Content="0; URL=signup3.php?error=1">';
        exit();
	}
	else if($password != $confirmpassword) { //if passwords dont match
		mysql_close();
        echo '<META HTTP-EQUIV="Refresh" Content="0; URL=signup3.php?error=2">';
        exit();
	}
	//else if that email address is already in the database
	else if($others != 0) {
		header("Location: lostpassword.php");
	}
	else {
		//put their info in database
        $settinglist = " emailcomment emailreturncomment emailfave emailfollow ";
		$newuserquery = "INSERT INTO userinfo (firstname, lastname, emailaddress, password, following, faves, settings, promos, time) VALUES ('$firstname', '$lastname', '$newemail', '$password', '$mattfollow', '$originalfave','$settinglist','$optin','$currenttime')";
		mysql_query($newuserquery);
        
         //newsfeed query
        $type = "signup";
        $newsfeedsignupquery=mysql_query("INSERT INTO newsfeed (firstname, lastname, emailaddress,type) VALUES ('$firstname', '$lastname', '$newemail','$type')");
        
        //SEND REGISTRATION GREETING
        
        $to = $newemail;
        $subject = 'Welcome to PhotoRankr!';
        $message = 'Thank you for signing up with PhotoRankr! You can now upload your own photos and sell them at your own price, follow the best photographers, and become part of a growing community. If you have any questions about PhotoRankr or would like to suggest an improvement, you can email us at photorankr@photorankr.com. We greatly value your feedback and hope you will spread the word about PhotoRankr to your friends by referring them to the site with the link below:
        
		http://photorankr.com/referral.php        

		Again, welcome to the site!

		Sincerely,
		PhotoRankr';
        $headers = 'From:PhotoRankr <photorankr@photorankr.com>';
        mail($to, $subject, $message, $headers);  
              
		session_start();
		$_SESSION['email'] = $newemail;
		$_SESSION['loggedin'] = 1;
            
    }
}

  
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
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery.wookmark.js"></script>            
    <link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>
  
  
  <title>PhotoRankr - Profile</title>
  
  <style type="text/css">


 .statoverlay

{
background-attachment: scroll;
background-clip: border-box;
background-color: 
rgba(0, 0, 0, 0.848438);
background-image: none;
background-origin: padding-box;
bottom: 0px;
color: 
rgb(255, 255, 255);
display: block;
font-family: 'Helvetica Neue', 'Helvetica Neue', Helvetica, Arial, sans-serif;
font-size: 14px;
font-style: normal;
font-variant: normal;
font-weight: normal;
line-height: 0px;
margin-bottom: 0px;
margin-left: 0px;
margin-right: 0px;
margin-top: 0px;
overflow-x: hidden;
overflow-y: hidden;
padding-bottom: 0px;
padding-left: 0px;
padding-right: 0px;
padding-top: 0px;
white-space: nowrap;
width: 270px;
}   

</style>
  
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

<body style="overflow-x:hidden; background-color: #eeeff3;">

<?php navbar(); ?>

   <!--big container-->
    <div id="container" class="container_24" style="margin-top:70px;width:95%;margin-left:30px;">
    
	<div id="profile_summary"><!--profile information-->
		<div id="profile_picture">
			<img src="../<?php echo $profilpic ?>" style="width:150px;" />
		</div>
		<div id="name_label">
			<header><?php echo $fullname ?></header>
		</div>
		<div class="btn btn-primary" id="follow">
			<header><a style="text-decoration:none;color:#fff;" href="profile.php"> Follow </a></header>
		</div>
		<div class="btn btn-primary" id="connect">
			<header><a style="text-decoration:none;color:#fff;" href="profile.php"> Store </a></header>
		</div>
		<div class="btn btn-primary" id="network">
			<header><a style="text-decoration:none;color:#fff;" href="profile.php?view=network"> Network <a/></header>
		</div>
	</div>
	<div id="info_summary">
		<div id="mini_nav">
			<div class="mini_nav_tab">
				<header> <a style="text-decoration:none;color:#fff;" href="profile.php">Portfolio</a> </header>
			</div>
			<div class="mini_nav_tab">
				<header><a style="text-decoration:none;color:#fff;" href="profile.php?view=collections"> Collections </a></header>
			</div>
			<div class="mini_nav_tab">
				<header><a style="text-decoration:none;color:#fff;" href="profile.php?view=store"> Store </a></header>
			</div>
			<div class="mini_nav_tab">
				<header><a style="text-decoration:none;color:#fff;" href="profile.php?view=exhibits"> Exhibits </a></header>
			</div>
		</div>
		<div id="about">
			<header> About </header>
			<div><ul>
				<li>Age: <?php echo $age; ?></li>
				<li>Equipment: <?php echo $camera; ?></li>
				<li>Location: <?php echo location; ?></li>
				<li>Social Networks: 
                <a href="<?php echo $facebookpage; ?>">Facebook</a>
                <a href="<?php echo $twitterpage; ?>">Twitter</a></li>
				<li>Profile Views: <?php echo $profileviews; ?></li>
			</ul>
			</div>	
		</div>
		<div id = "groups">
			<header> <?php echo $firstname; ?>'s </header>
			<header id="bigText"> Groups </header><br />
		<div class="group_square">
		</div>
		<div class="group_square">
		</div>
		<div class="group_square">
		</div>
		<div class="group_square">
		</div>
	</div>
	</div>
    
     <!--Top 6 Photos-->
    <?php 
    
        $topphotos = mysql_query("SELECT source,id FROM photos WHERE emailaddress = '$email' ORDER BY faves DESC LIMIT 0,6");
        $numphotos = mysql_num_rows($topphotos);
        
        echo'<div id="blogOut" style="background-color:#fff;">';
        
        if($numphotos == 6) {
            for($ii=0;$ii<=5;$ii++) {
                $source = mysql_result($topphotos,$ii,'source');
                $source = str_replace('userphotos','userphotos/medthumbs/',$source);
                $imageid = mysql_result($topphotos,$ii,'imageid');

                echo'<div style="float:left;width:124px;height:100px;overflow:hidden;margin-top:1px;margin-right:1px;">
                     <img src="../',$source,'" style="width:124px;height:120px;" />
                     </div>';
            
            }
        }
    
        echo'</div>';
    
    ?>

    <!--<div id="blogOut">
		<header> <?php echo $firstname; ?>'s Blog </header>
		<div id="blogIn">
			<header>
				<header style="float:left;">Latest Post</header>
				<header style="font-size:18px;float:right;"> <?php echo $postdate; ?> </header>
			</header>
			<p> <?php echo $newestpost; ?> </p>            
		</div>
	</div>-->
    
    
  <!--Bottom Section-->
  
    <div class="grid_24">
  
   <?php if($view == '') {    
   
    echo'<br /><div style="width:1050px;text-align:center;font-size:14px;font-weight:200;"><div style="margin-left:35px;"><a class="green" style="text-decoration:none;color:#333;" href="editphotos.php">Edit Portfolio</a> | <a class="green" style="text-decoration:none;'; if($option == '') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'" href="profile.php?view=portfolio">Newest</a> | <a class="green" style="text-decoration:none;color:#333;'; if($option == 'top') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'" href="profile.php?view=portfolio&option=top">Top Ranked</a> | <a class="green" style="text-decoration:none;color:#333;'; if($option == 'fave') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'" href="profile.php?view=portfolio&option=fave">Most Favorited</a> | <a class="green" style="text-decoration:none;color:#333;" href="profile.php?view=exhibits">Exhibits</a></div></div><br /><br />';
    
    if($option == '') {        
        $query = mysql_query("SELECT * FROM photos WHERE emailaddress = '$email' ORDER BY id DESC LIMIT 0,21");
        $numresults = mysql_num_rows($query);
        }
        
        elseif($option == 'top') {
        $query = mysql_query("SELECT * FROM photos WHERE emailaddress = '$email' AND views > 20 ORDER BY (points/votes) DESC LIMIT 0,21");
        $numresults = mysql_num_rows($query);
        }
                
        elseif($option == 'fave') {
        $query = mysql_query("SELECT * FROM photos WHERE emailaddress = '$email' ORDER BY faves DESC LIMIT 0,21");
        $numresults = mysql_num_rows($query);
        }
        
    echo'
    <div id="thepics" style="position:relative;left:18px;top:35px;width:1200px;">
    <div id="main">
    <ul id="tiles">';

        for($iii=0; $iii < $numresults; $iii++) {
              
                $image = mysql_result($query, $iii, "source");
                $image= '../' . $image;
                $imageThumb = str_replace("../userphotos/","userphotos/medthumbs/", $image);
                $id = mysql_result($query, $iii, "id");
                $price = mysql_result($query, $iii, "price");
                if($price != 'Not For Sale') {
                    $price = '$' . $price;
                }
                elseif($price == 'Not For Sale') {
                    $price = 'NFS';
                }
                elseif($price == '.00' || $price == '') {
                    $price = 'Free';
                }
                $caption = mysql_result($query, $iii, "caption");
                $points = mysql_result($query, $iii, "points");
                $votes = mysql_result($query, $iii, "votes");
                $faves = mysql_result($query, $iii, "faves");
                $score = number_format(($points/$votes),2);
                $faveemail = mysql_result($query, $iii, "emailaddress");
                $ownerquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$faveemail'");
                $firstname = mysql_result($query, 0, "firstname");
                $lastname = mysql_result($query, 0, "lastname");
                $reputation = mysql_result($query, 0, "lastname");
                $fullname = $firstname . " " . $lastname;
                list($width, $height) = getimagesize($image);
                $imgratio = $height / $width;
                $heightls = $height / 3.2;
                $widthls = $width / 3.2;
                
                list($width, $height) = getimagesize($image);
	$imgratio = $height / $width;
    $heightls = $height / 3.3;
    $widthls = $width / 3.3;
    if($widthls < 235) {
    $heightls = $heightls * ($heightls/$widthls);
    $widthls = 285;
    }

		echo '
        <a style="text-decoration:none;color:#333;" href="fullsize.php?imageid=',$id,'&v=n"><li class="fPic" id="',$id,'" style="list-style-type: none;width:270px;"><img style="min-width:270px;" src="https://photorankr.com/',$imageThumb,'" height="',$heightls,'px" width="',$widthls,'px" />
        
            <div class="statoverlay" style="top:0px;height:50px;position:relative;">
            <p style="font-weight:100;font-size:20px;padding-top:15px;padding-left:5px;">',$caption,'</p>
            <p style="font-weight:100;font-size:16px;margin-top:20px;padding-left:5px;">',$score,'<span style="font-size:14px;">/10.0</span></p>
            </div>';       	
            
      } //end for loop
        
    echo'
        </ul>';
        
?>

<!-- Once the page is loaded, initalize the plug-in. -->
  <script type="text/javascript">
    $(document).ready(new function() {
      // Prepare layout options.
      var options = {
        autoResize: true, // This will auto-update the layout when the browser window is resized.
        container: $('#main'), // Optional, used for some extra CSS styling
        offset: 4, // Optional, the distance between grid items
        itemWidth: 270 // Optional, the width of a grid item
      };
      
      // Get a reference to your grid items.
      var handler = $('#tiles li');
      
      // Call the layout function.
      handler.wookmark(options);
      
    });
  </script>

</div>
</div>
    
<?php      
        
        //AJAX CODE HERE
echo'
   <div class="grid_6 push_9" style="top:20px;">
   <div id="loadMorePics" style="display: none; text-align: center;font-family:arial,helvetica neue; font-size:15px;">Loading More Photos&hellip;</div>
   </div>';


echo '<script>

var last = 0;

	$(window).scroll(function(){
		if($(window).scrollTop() > $(document).height() - $(window).height()-100) {
			if(last != $(".fPic:last").attr("id")) {
				$("div#loadMorePortfolioPics").show();
				$.ajax({
					url: "loadMorePortfolioPics3.php?lastPicture=" + $(".fPic:last").attr("id"),
					success: function(html) {
						if(html) {
							$("#thepics").append(html);
							$("div#loadMorePortfolioPics").hide();
						}
					}
				});
				last = $(".fPic:last").attr("id");
			}
		}
	});
</script>';
    
        echo'</div>';
        echo'</div>';

        } //end of portfolio view
    
    elseif($view == 'collections') {

    $option = $_GET['option'];
    $set = $_GET['set'];
    $mode = ($_GET['mode']);

    echo'<br /><div style="width:1050px;text-align:center;font-size:14px;font-weight:200;"><div style="margin-left:20px;"><a class="green" style="'; if($option == '') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'" href="profile.php?view=collections">Collections</a> | <a class="green" style="'; if($option == 'newcollection') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'" href="profile.php?view=collections&option=newcollection">Create Collection</a></div></div>';
    
    if (htmlentities($_GET['ncol']) == "success") { 
        echo'<br /><br /><div style="margin-top:0px;margin-left:0px;text-align:center;padding-bottom:10px;font-size:15px;color:#6aae45"><strong>Your collection has been created. Add to your collection by browsing through photos.</strong></div><br /><br />';
    }


    if($mode == 'delete') {

    $image = htmlentities($_GET['image']);
    $imagefind = $image . " ";
    $set = htmlentities($_GET['set']);

    $getsetid = mysql_query("SELECT photos FROM collections WHERE id = '$set'");
    $photos = mysql_result($getsetid,0,'photos');
    $newset_id = str_replace($imagefind,"",$photos);

    $deletephotofromset = mysql_query("UPDATE collections SET photos = '$newset_id' WHERE id = '$set'");

    echo '<META HTTP-EQUIV="Refresh" Content="0; URL=profile.php?view=collections&set=',$set,'">';
    exit();

    }

    if($option == '') {
        
    if($set == '') {
        
        $getcollections = mysql_query("SELECT * FROM collections WHERE owner = '$email' ORDER BY id DESC");
        $numcollections = mysql_num_rows($getcollections);
        
            if($numcollections < 1) {
            
                echo'<div style="text-align:center;margin-top:120px;margin-left:-35px;font-size:16px;">You have no collections. <a href="profile.php?view=collections&option=newcollection">Create one?</a><div>';
            
            }
            
                
        echo'
        <div id="thepics" style="position:relative;width:1050px;margin-left:15px;margin-top:20px;">
        <div id="main" role="main">
        <ul id="tiles">';

        for($iii=0; $iii < $numcollections; $iii++) {
            $setname[$iii] = mysql_result($getcollections, $iii, "title");
            $setcover = mysql_result($getcollections, $iii, "cover");
            $set_id[$iii] = mysql_result($getcollections, $iii, "id");
            $setname2[$iii] = (strlen($setname[$iii]) > 30) ? substr($setname[$iii],0,27). " &#8230;" : $setname[$iii];
            $photos = mysql_result($getcollections, $iii, "photos");
            $photos = explode(" ",$photos);
            $numphotos = count($photos) - 1;
            
            $pulltopphoto = mysql_query("SELECT source FROM photos WHERE id = '$photos[0]' ORDER BY votes DESC LIMIT 5");
            if($setcover == '') {
                $setcover = mysql_result($pulltopphoto, 0, "source");
                if($setcover == '') {
                     $setcover = 'graphics/no_photos.png';
                }
            }

            $thumb4 =mysql_result($pulltopphoto, 4, "source");
            $thumb4 = str_replace("userphotos/","userphotos/medthumbs/",$thumb4);
                
            list($width, $height) = getimagesize($setcover);
            $imgratio = $height / $width;
            $heightls = $height / 3.2;
            $widthls = $width / 3.2;
            if($widthls < 240) {
                $heightls = $heightls * ($heightls/$widthls);
                $widthls = 250;
            }
            if($setcover == 'graphics/no_photos.png') {
                $heightls = 220;
                $widthls = 240;
            }

            echo'<li class="photobox" style="width:240px;list-style-type:none;"><a style="text-decoration:none;" href="profile.php?view=collections&set=',$set_id[$iii],'">
    
            <div style="width:100%;">
    
            <div style="padding-top:5px;padding-left:3px;font-size:13px;text-decoration:none;color:#000;font-weight:200;"><span style="font-size:15px;font-weight:400;">',$setname2[$iii],'</span><br />',$numphotos,' Photos</div>
<hr />

    <img style="margin-top:-6px;" onmousedown="return false" oncontextmenu="return false;" src="http://www.photorankr.com/',$setcover,'" alt="',$setname[$iii],'" height="',$heightls,'px" width="',$widthls,'px" />';
    
        if($numphotos > 4) {
        
            for($jjj=1; $jjj < 5; $jjj++) {
                $grabphotosrun = mysql_query("SELECT source FROM photos WHERE id = '$photos[$jjj]'");
                $insetname = mysql_result($grabphotosrun, 0, "caption");
                $insetsource = mysql_result($grabphotosrun, 0, "source");
                $newsource = str_replace("userphotos/","userphotos/medthumbs/", $insetsource);
    
                echo'
                    <div>
                        <img style="float:left;padding:5px;" src="http://www.photorankr.com/',$newsource,'" width="110" height="110" />
                    </div>';
            }
            
        }
    
    echo'
    </a>
    
    </li><br />';
    
} //end of for loop

echo'</ul>';
        
    ?>
    
    <!-- Once the page is loaded, initalize the plug-in. -->
  <script type="text/javascript">
    $(document).ready(new function() {
      // Prepare layout options.
      var options = {
        autoResize: true, // This will auto-update the layout when the browser window is resized.
        container: $('#main'), // Optional, used for some extra CSS styling
        offset: 4, // Optional, the distance between grid items
        itemWidth: 250 // Optional, the width of a grid item
      };
      
      // Get a reference to your grid items.
      var handler = $('#tiles li');
      
      // Call the layout function.
      handler.wookmark(options);
      
    });
  </script>

    
 <?php      

echo'</div>
</div>';

    } //end set == ''
    
    
    elseif($set != '') {
    
//grab all photos in the exhibit
$grabphotos = mysql_query("SELECT * FROM collections WHERE owner = '$email' AND id = '$set'");

//grab about this set
$aboutset = "SELECT * FROM collections WHERE owner = '$email' AND id = '$set' LIMIT 0,1";
$aboutsetrun = mysql_query($aboutset);
$aboutarray = mysql_fetch_array($aboutsetrun);
$aboutset = $aboutarray['about'];
$settitle = $aboutarray['title'];
$setcover = $aboutarray['cover'];
if($setcover == '') {
$setcover = 'profilepics/nocoverphoto.png';
}

echo'<div class="grid_18" style="width:1050px;margin-left:-15px;padding:35px;">

<div class="grid_14 well" style="position:relative;clear:both;width:1050px;line-height:25px;margin-top:15px;margin-left:-15px;"><span style="font-size:25px;font-family:helvetica,arial;font-weight:200;">',$settitle,'</span><br />';
if($aboutset) {echo'
    <br />
    <span style="font-size:16px;font-family:helvetica,arial;font-weight:200;">',$aboutset,'</span>';
}


    $photos = mysql_result($grabphotos, 0, "photos");
    $photos = explode(" ",$photos);
    $numphotos = count($photos);
    
    for($ii=0; $ii<$numphotos-1; $ii++) {
        $facepile = mysql_query("SELECT * FROM photos WHERE id = '$photos[$ii]'");
        $faceemail = mysql_result($facepile, 0, "emailaddress");
        $pos = strpos($emailarray, $faceemail);
        if($pos === false) {
            $emailarray .= $faceemail . " ";
        }
           
    }
    
    $faces = explode(" ",$emailarray);
    $numfaces = count($faces);
    
    echo'<br /><div style="">';
    
    for($i=0; $i<$numfaces-1; $i++) {
        $facepile2 = mysql_query("SELECT user_id,profilepic FROM userinfo WHERE emailaddress = '$faces[$i]'");
        $facepic = mysql_result($facepile2, 0, "profilepic");
        $faceid = mysql_result($facepile2, 0, "user_id");
        
        echo'<a href="viewprofile.php?u=',$faceid,'"><img style="padding:3px;" src="../',$facepic,'" height="50" /></a>';
    
    }
    
    echo'</div>';

    echo'
    </div>

    <div id="thepics" style="position:relative;width:1100px;margin-left:-15px;clear:both;">
    <div id="main" role="main">
    <ul id="tiles">';

    for($jjj=0; $jjj<$numphotos-1; $jjj++) {

    $grabphotosrun = mysql_query("SELECT * FROM photos WHERE id = '$photos[$jjj]'");
    $insetname = mysql_result($grabphotosrun, 0, "caption");
    $insetsource = mysql_result($grabphotosrun, 0, "source");
    $insetsource = '../'.$insetsource;
    $insetid = mysql_result($grabphotosrun, 0, "id");        
    $newsource = str_replace("userphotos/","userphotos/medthumbs/", $insetsource);
    $caption = mysql_result($grabphotosrun, 0, "caption");
    $faves = mysql_result($grabphotosrun, 0, "faves");
    $price = mysql_result($grabphotosrun, 0, "price");
    if($price != 'Not For Sale') {
                    $price = '$' . $price;
                }
                elseif($price == 'Not For Sale') {
                    $price = 'NFS';
                }
    $points = mysql_result($grabphotosrun, 0, "points");
    $votes = mysql_result($grabphotosrun, 0, "votes");
    $score = number_format(($points/$votes),2);
    
        list($width, $height) = getimagesize($insetsource);
        $imgratio = $height / $width;
        $heightls = $height / 3.2;
        $widthls = $width / 3.2;
        
        if($widthls < 215) {
            $heightls = $heightls * ($heightls/$widthls);
            $widthls = 260;
        }
                
    echo'<li class="photobox" style="list-style-type:none;width:260px;">

    <a style="text-decoration:none;" href="fullsize.php?imageid=',$insetid,'">
    
    <img style="-moz-border-radius: 3px;-webkit-border-radius: 3px;border-radius: 3px;" onmousedown="return false" oncontextmenu="return false;"  src="../',$newsource,'" alt="',$caption,'" height="',$heightls,'px" width="',$widthls,'px" /></a>
                
    <div class="statoverlay" style="z-index:1;background-color:white;position:relative;top:0px;width:260px;height:30px;"><div style="line-spacing:1.48;padding:5px;color:#4A4A4A;"><div style="float:left;"<span style="font-size:16px;font-weight:bold;">',$score,'</span>&nbsp;&nbsp;<span style="font-weight:bold;font-size:12px;">',$caption,'</span></div><div style="float:right;"><span style="font-size:12px;">',$price,'</span><a style="color:#333;text-decoration:none;" href="profile.php?view=collections&set=',$set,'&image=',$photos[$jjj],'&mode=delete"><span style="float:right;font-size:13px;">&nbsp;&nbsp;X</span></a></div></div><br/></div>
        </a>
        
    </li>';
 
    } //end for loop
    

    echo'</ul>';
        
    ?>
    
    <!-- Once the page is loaded, initalize the plug-in. -->
  <script type="text/javascript">
    $(document).ready(new function() {
      // Prepare layout options.
      var options = {
        autoResize: true, // This will auto-update the layout when the browser window is resized.
        container: $('#main'), // Optional, used for some extra CSS styling
        offset: 4, // Optional, the distance between grid items
        itemWidth: 270 // Optional, the width of a grid item
      };
      
      // Get a reference to your grid items.
      var handler = $('#tiles li');
      
      // Call the layout function.
      handler.wookmark(options);
      
    });
  </script>

    
 <?php
 
 echo'
    </div>
    </div>';

   
   } //set != ''

    
} //end of view == ''


elseif($option == 'newcollection') {
    
    echo'
    <div style="font-size:12px;padding-left:50px;font-family:Helvetica Neue,helvetica,arial;font-weight:200;"><br /><br />
    
    <strong style="font-size:20px;">New Collection</strong><br /><br />
    
    &nbsp;<a style="text-decoration:none;" href="#" id="pricepopover" rel="popover" data-content="A collection is a container for photos that catch your eye. You can make a container for photos that you see on PhotoRankr but aren\'t necessarilly yours. Think of collections as an exhibit of photos that you would like to keep. Choose tags and write a bit about your collection below so other photographers can find your collection easier." data-original-title="Collections">(What is a collection?)</a>
    <script>  
    $(function ()  
    { $("#pricepopover").popover();  
    });  
    </script>
    
    <br /><span style="font-size:16px;">* </span>Required fields. Please select more than 2 tags. (Selecting multiple values: Hold down command button if on mac, control button if on PC)</div>

	<form action="create_collection.php" method="post">
    
    <div class="span9" style="margin-top:30px;padding-left:30px;">
    <table class="table">
    <tbody>
    
    <tr>
    <td>*Title of collection:</td>
    <td><input type="text" name="title" /></td>
    </tr>
    
    <tr>
    <td>*Pick style tags:</td>
    <td>
    <select multiple="multiple" name="maintags[]">
    <option value="Advertising">Advertising</option>
    <option value="Aerial">Aerial</option>
    <option value="Animal">Animal</option>
    <option value="Astro">Astro</option>
    <option value="Aura">Aura</option>
    <option value="Automotive">Automotive</option>
    <option value="B&W">B&W</option>
    <option value="Botanical">Botanical</option>
    <option value="Candid">Candid</option>
    <option value="Cityscape">Cityscape</option>
    <option value="Commercial">Commercial</option>
    <option value="Corporate">Corporate</option>
    <option value="Documentary">Documentary</option>
    <option value="Fashion">Fashion</option>
    <option value="Fine Art">Fine Art</option>
    <option value="Food">Food</option>
    <option value="HDR">HDR</option>
    <option value="Historical">Historical</option>
    <option value="Industrial">Industrial</option>
    <option value="Landscape">Landscape</option>
    <option value="Long Exposure">Long Exposure</option>
    <option value="Macro">Macro</option>
    <option value="Musical">Musical</option>
    <option value="Nature">Nature</option>
    <option value="News">News</option>
    <option value="Night">Night</option>
    <option value="Panorama">Panorama</option>
    <option value="People">People</option>
    <option value="Portrait">Portrait</option>
    <option value="Scenic">Scenic</option>
    <option value="Sports">Sports</option>
    <option value="Still Life">Still Life</option>
    <option value="Time Lapse">Time Lapse</option>
    <option value="Transportation">Transportation</option>
    <option value="Urban">Urban</option>
    <option value="War">War</option>
    </select>
    </td>
    </tr>
    
    <tr>
    <td>*Choose some of your own tags:</td>
    <td>
    <input style="width:80px;height:20px;" type="text" name="settag1" />
    <input style="width:80px;height:20px;" type="text" name="settag2" />
    <input style="width:80px;height:20px;" type="text" name="settag3" />
    <input style="width:80px;height:20px;" type="text" name="settag4" />
    </td>
    </tr>
        
    <tr>
    <td>About this collection:</td>
    <td><textarea style="width:500px" rows="4" cols="60" name="about"></textarea></td>
    </tr>
    
    </tbody>
    </table>

<button type="submit" name="Submit" class="btn btn-success">Create Collection</button>
</form>
</div>
    
</div> <!--end of well-->
</div>';

} //end of new collection


} //end of collections view


elseif($view == 'exhibits') {
    
    echo'<br /><div style="width:1050px;text-align:center;font-size:14px;font-weight:200;"><div style="margin-left:20px;"><a class="green" style="text-decoration:none;color:#333;" href="profile.php?view=portfolio">Newest</a> | <a class="green" style="text-decoration:none;color:#333;" href="profile.php?view=portfolio&option=top">Top Ranked</a> | <a class="green" style="text-decoration:none;color:#333;" href="profile.php?view=portfolio&option=fave">Most Favorited</a> | <a class="green" style="text-decoration:none;color:#333;'; if($view == 'exhibits') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'" href="profile.php?view=exhibits">Exhibits</a></div></div>';


        if(isset($_GET['set'])){
		$set = mysql_real_escape_string($_GET['set']);
	}
    
    //get exhibit mode
if(isset($_GET['mode'])){
		$mode = ($_GET['mode']);
	}
    
if($mode == 'delete') {

$image = htmlentities($_GET['image']);
$set = htmlentities($_GET['set']);

$getsetid = mysql_query("SELECT set_id FROM photos WHERE source = '$image'");
$set_id = mysql_result($getsetid,0,'set_id');
$newset_id = str_replace($set,"",$set_id);

$deletephotofromset = mysql_query("UPDATE photos SET set_id = '$newset_id' WHERE source = '$image'");

echo '<META HTTP-EQUIV="Refresh" Content="0; URL=profile.php?view=exhibits&set=',$set,'">';
exit();

}

elseif($mode == 'added') {
//add checked photos to existing exhibit

if(!empty($_POST['addthese'])) {
    foreach($_POST['addthese'] as $checked) {
        $setnew = $set ." ";
        //insert each checked photo into corresponding set
        $checkedset = "UPDATE photos SET set_id = CONCAT(set_id,'$setnew') WHERE source = '$checked'";
        $checkedsetrun = mysql_query($checkedset);
        }
        }
	
}

elseif($mode == 'coverchanged') {
//edit existing exhibit

    $newcaption = mysql_real_escape_string($_POST['caption']);
    $newaboutset = mysql_real_escape_string($_POST['aboutset']);
    $newcover = mysql_real_escape_string($_POST['addthis']);
        
    $exhibitchange = "UPDATE sets SET about = '$newaboutset', title = '$newcaption', cover = '$newcover' WHERE id = '$set'  AND owner = '$email'";
    $exhibitrun = mysql_query($exhibitchange);
        	
}

elseif($mode == 'deleteexhibit') {

    $deleteexhibit = mysql_query("DELETE FROM sets WHERE id = '$set' AND owner = '$email'");
    echo '<META HTTP-EQUIV="Refresh" Content="0; URL=profile.php?view=exhibits">';

}

//select all exhibits of user
$allsetsquery = "SELECT * FROM sets WHERE owner = '$email'";
$allsetsrun = mysql_query($allsetsquery);
$numbersets = mysql_num_rows($allsetsrun);
echo'<div style="margin-top:-60px">';

if($numbersets == 0) {
echo'<div style="font-size:18px;font-weight:200;padding:40px;text-align:center;margin-left:-35px;margin-top:120px;"><a style="color:#333;" href="profile.php?view=upload&option=newexhibit">Click here to create your first exhibit.</a></div>';
}

if($set == '' & $numbersets > 0) {

echo'<div class="grid_18" style="width:1050px;margin-top:22px;margin-left:-15px;padding:35px;"><a href="profile.php?view=upload&option=newexhibit"><button class="btn btn-success">Create New Exhibit</button></a><br /><br /></div>

    <div id="thepics" style="position:relative;width:1050px;margin-left:15px;top:110px;">
    <div id="main" role="main">
    <ul id="tiles">';

for($iii=0; $iii < $numbersets; $iii++) {
$setname[$iii] = mysql_result($allsetsrun, $iii, "title");
$setcover = mysql_result($allsetsrun, $iii, "cover");
$set_id[$iii] = mysql_result($allsetsrun, $iii, "id");
$setname2[$iii] = (strlen($setname[$iii]) > 30) ? substr($setname[$iii],0,27). " &#8230;" : $setname[$iii];
$pulltopphoto = mysql_query("SELECT source FROM photos WHERE set_id = '$set_id[$iii]' ORDER BY votes DESC LIMIT 5");
if($setcover == '') {
$setcover = mysql_result($pulltopphoto, 0, "source");
}

$thumb1 = mysql_result($pulltopphoto, 1, "source");
$thumb1 = str_replace("userphotos/","userphotos/medthumbs/",$thumb1);
$thumb2 = mysql_result($pulltopphoto, 2, "source");
$thumb2 = str_replace("userphotos/","userphotos/medthumbs/",$thumb2);
$thumb3 = mysql_result($pulltopphoto, 3, "source");
$thumb3 = str_replace("userphotos/","userphotos/medthumbs/",$thumb3);
$thumb4 =mysql_result($pulltopphoto, 4, "source");
$thumb4 = str_replace("userphotos/","userphotos/medthumbs/",$thumb4);

        list($width, $height) = getimagesize($setcover);
        $imgratio = $height / $width;
        $heightls = $height / 3.2;
        $widthls = $width / 3.2;
if($widthls < 240) {
    $heightls = $heightls * ($heightls/$widthls);
    $widthls = 250;
}
        
//grab all photos in the exhibit
$grabphotos = "SELECT * FROM photos WHERE emailaddress = '$email' AND set_id LIKE '%$set_id[$iii]%'";
$grabphotosrun = mysql_query($grabphotos);
$numphotosgrabbed = mysql_num_rows($grabphotosrun);


    echo'<li class="photobox" style="width:240px;list-style-type:none;"><a style="text-decoration:none;" href="profile.php?view=exhibits&set=',$set_id[$iii],'">
    
    <div style="width:100%;">
    
    <div style="padding-top:5px;padding-left:3px;font-size:13px;text-decoration:none;color:#000;font-weight:200;"><span style="font-size:15px;font-weight:400;">',$setname2[$iii],'</span><br />',$numphotosgrabbed,' Photos</div>
<hr />

    <img style="margin-top:-6px;" onmousedown="return false" oncontextmenu="return false;" src="http://www.photorankr.com/',$setcover,'" alt="',$setname[$iii],'" height="',$heightls,'px" width="',$widthls,'px" />';
    
    if($thumb4) {
        echo'
            <div>
            <img style="float:left;padding:5px;" src="http://www.photorankr.com/',$thumb1,'" width="110" height="110" />
            <img style="float:left;padding:5px;" src="http://www.photorankr.com/',$thumb2,'" width="110" height="110" />
            <img style="float:left;padding:5px;" src="http://www.photorankr.com/',$thumb3,'" width="110" height="110" />
            <img style="float:left;padding:5px;" src="http://www.photorankr.com/',$thumb4,'" width="110" height="110" />
            </div>';
    }
    
    echo'
    </a>
    
    </li><br />';
    
} //end of for loop

echo'</ul>';
        
    ?>
    
    <!-- Once the page is loaded, initalize the plug-in. -->
  <script type="text/javascript">
    $(document).ready(new function() {
      // Prepare layout options.
      var options = {
        autoResize: true, // This will auto-update the layout when the browser window is resized.
        container: $('#main'), // Optional, used for some extra CSS styling
        offset: 4, // Optional, the distance between grid items
        itemWidth: 250 // Optional, the width of a grid item
      };
      
      // Get a reference to your grid items.
      var handler = $('#tiles li');
      
      // Call the layout function.
      handler.wookmark(options);
      
    });
  </script>

    
 <?php      

echo'</div>
</div>';

} //end of set == '' view


elseif($set != '') {

//DE-HIGHLIGHT NOTIFICATIONS IF CLICKED ON
if(isset($_GET['id'])){
$id = htmlentities($_GET['id']);
$idformatted = $id . " ";
$unhighlightquery = "UPDATE userinfo SET unhighlight = CONCAT(unhighlight,'$idformatted') WHERE emailaddress = '$email'";
$unhighlightqueryrun = mysql_query($unhighlightquery);

//notifications query reset 
if($currentnotsresult > 0) {
$notsquery = "UPDATE userinfo SET notifications = 0 WHERE emailaddress = '$email'";
$notsqueryrun = mysql_query($notsquery); }
}

//grab all photos in the exhibit
$grabphotos = "SELECT * FROM photos WHERE emailaddress = '$email' AND set_id LIKE '%$set%'";
$grabphotosrun = mysql_query($grabphotos);
$numphotosgrabbed = mysql_num_rows($grabphotosrun);

//grab about this set
$aboutset = "SELECT * FROM sets WHERE owner = '$email' AND id = '$set' LIMIT 0,1";
$aboutsetrun = mysql_query($aboutset);
$aboutarray = mysql_fetch_array($aboutsetrun);
$aboutset = $aboutarray['about'];
$settitle = $aboutarray['title'];
$setcover = $aboutarray['cover'];
if($setcover == '') {
$setcover = 'profilepics/nocoverphoto.png';
}

echo'<div class="grid_18" style="width:770px;margin-top:22px;margin-left:-10px;padding:35px;">

<div class="grid_14 well" style="position:relative;clear:both;width:735px;line-height:25px;margin-top:15px;"><span style="font-size:25px;font-family:helvetica,arial;font-weight:200;">',$settitle,'</span><br />';
if($aboutset) {echo'
    <br />
    <span style="font-size:16px;font-family:helvetica,arial;font-weight:200;">',        $aboutset,'</span>';
}
echo'
<div style="float:bottom;margin-top:10px;clear:both;">
<a data-toggle="modal" data-backdrop="static" href="#add"><button class="btn btn-success">Add Photos to Exhibit</button></a>&nbsp;&nbsp;
<a data-toggle="modal" data-backdrop="static" href="#editexhibit"><button class="btn btn-success">Edit Exhibit</button></a></div>
</div>';

echo'

    <div id="thepics" style="position:relative;width:1100px;clear:both;">
    <div id="main" role="main">
    <ul id="tiles">';

for($iii=0; $iii < $numphotosgrabbed; $iii++) {
    $insetname[$iii] = mysql_result($grabphotosrun, $iii, "caption");
    $insetsource[$iii] = mysql_result($grabphotosrun, $iii, "source");
    $insetsource[$iii] = '../' . $insetsource[$iii];
    $newsource = str_replace("userphotos/","userphotos/medthumbs/", $insetsource[$iii]);
    $caption = mysql_result($grabphotosrun, $iii, "caption");
    $faves = mysql_result($grabphotosrun, $iii, "faves");
    $price = mysql_result($grabphotosrun, $iii, "price");
    if($price != 'Not For Sale') {
                    $price = '$' . $price;
                }
                elseif($price == 'Not For Sale') {
                    $price = 'NFS';
                }
    $points = mysql_result($grabphotosrun, $iii, "points");
    $votes = mysql_result($grabphotosrun, $iii, "votes");
    $score = number_format(($points/$votes),2);
    
        list($width, $height) = getimagesize($insetsource[$iii]);
        $imgratio = $height / $width;
        $heightls = $height / 3.2;
        $widthls = $width / 3.2;
        
        if($widthls < 215) {
            $heightls = $heightls * ($heightls/$widthls);
            $widthls = 260;
        }
                
    echo'<li class="photobox" style="list-style-type:none;width:260px;">

    <a style="text-decoration:none;" href="fullsizeme.php?image=',$insetsource[$iii],'">
    
    <img style="-moz-border-radius: 3px;-webkit-border-radius: 3px;border-radius: 3px;" onmousedown="return false" oncontextmenu="return false;"  src="',$newsource,'" alt="',$caption,'" height="',$heightls,'px" width="',$widthls,'px" /></a>

    <div class="statoverlay" style="z-index:1;background-color:white;position:relative;top:0px;width:260px;height:30px;"><div style="line-spacing:1.48;padding:5px;color:#4A4A4A;"><div style="float:left;"<span style="font-size:16px;font-weight:bold;">',$score,'</span>&nbsp;&nbsp;<span style="font-weight:bold;font-size:12px;">',$caption,'</span></div><div style="float:right;"><a style="color:#333;text-decoration:none;" href="profile.php?view=exhibits&set=',$set,'&image=',$insetsource[$iii],'&mode=delete"><span style="float:right;">X</span></a></a></div></div><br/></div>
        </a>
        
    </li>';
 
    } //end for loop

    echo'</ul>';
        
    ?>
    
    <!-- Once the page is loaded, initalize the plug-in. -->
  <script type="text/javascript">
    $(document).ready(new function() {
      // Prepare layout options.
      var options = {
        autoResize: true, // This will auto-update the layout when the browser window is resized.
        container: $('#main'), // Optional, used for some extra CSS styling
        offset: 4, // Optional, the distance between grid items
        itemWidth: 270 // Optional, the width of a grid item
      };
      
      // Get a reference to your grid items.
      var handler = $('#tiles li');
      
      // Call the layout function.
      handler.wookmark(options);
      
    });
  </script>

    
 <?php
 
 echo'
    </div>
    </div>';

   
   }
   
   
   
        //Add Photos to Exhibit Modal

echo'<div class="modal hide fade" id="add" style="overflow-y:scroll;overflow-x:hidden;border:5px solid rgba(102,102,102,.8);">

<div class="modal-header" style="background-color:#111;color:#fff;">
<a style="float:right" class="btn btn-success" data-dismiss="modal">Close</a>
<img style="margin-top:-2px;" src="graphics/aperture_white.png" height="34" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:16px;font-family:helvetica,arial;font-weight:100;">Add photos to your exhibit below:</span>
  </div>
  <div modal-body" style="width:600px;">

<div id="content" style="font-size:16px;width:550px;height:500px;overflow-x:hidden;background-color:rgb(245,245,245);">
		
<img style="border: 1px solid black;margin-left:10px;margin-top:10px;" src="',$setcover,'" 
height="100px" width="100px" />

<div style="width:540px;margin-left:130px;margin-top:-100px;overflow-y:scroll;overflow-x:hidden;">

<form action="profile.php?view=exhibits&set=',$set,'&mode=added" method="post" enctype="multipart/form-data">
    <span style="font-size:14px;">
    Exhibit Name:&nbsp;&nbsp;',$settitle,'
    <br />
    <br />';
    if($aboutset) {
        echo'
        About this Exhibit:&nbsp;&nbsp;
        ',stripslashes($aboutset),'
        <br /><br />';
    }
    echo'
    Check photos to add to this exhibit:
    <br /><br />';
    $allusersphotos = "SELECT * FROM photos WHERE emailaddress = '$email'";
    $allusersphotosquery = mysql_query($allusersphotos);
    $usernumphotos = mysql_num_rows($allusersphotosquery);


    for($iii = 0; $iii < $usernumphotos; $iii++) {
        $userphotosource[$iii] = mysql_result($allusersphotosquery, $iii, "source");
        $userphotosset[$iii] = mysql_result($allusersphotosquery, $iii, "sets");
        $userphotoscaption[$iii] = mysql_result($allusersphotosquery, $iii, "caption");
        $newsource = str_replace("userphotos/","userphotos/thumbs/", $userphotosource[$iii]);
        if($userphotosset[$iii] == $set) {
        echo'<img src="',$newsource,'" height="70" width="70" />&nbsp;&nbsp;<input type="checkbox" name="addthese[]" value="',      $userphotosource[$iii],'" checked />&nbsp;"',$userphotoscaption[$iii],'"
    <br /><br />'; }
        else {
        echo'<img src="',$newsource,'" alt="',$userphotoscaption[$iii],'" height="70" width="70" />&nbsp;&nbsp;<input type="checkbox" name="addthese[]" value="',      $userphotosource[$iii],'" />&nbsp;"',$userphotoscaption[$iii],'"
        <br /><br />'; 
        }    
    
    } //end of for loop

    
    echo'
    </span>
    <button class="btn btn-success" type="submit">Save Exhibit</button>
    </form>
    
    </div>
    </div>
    </div>';
        
    
    }

     elseif($view == 'blog') {
    
        //unhighlight query for blog comments
    
            if(isset($_GET['bi'])){
                $id = htmlentities($_GET['bi']);
                $idformatted = $id . " ";
                $unhighlightquery = "UPDATE userinfo SET unhighlight = CONCAT(unhighlight,'$idformatted') WHERE emailaddress = '$email'";
                $unhighlightqueryrun = mysql_query($unhighlightquery);

        //notifications query reset 
        
            if($currentnotsresult > 0) {
                $notsquery = "UPDATE userinfo SET notifications = 0 WHERE emailaddress = '$email'";
                $notsqueryrun = mysql_query($notsquery); }
            }
    
  
         $option = htmlentities($_GET['option']);
  
         echo'<br /><br /><br /><br /><div style="width:760px;text-align:center;font-size:14px;font-weight:200;"><div style="margin-left:20px;"><a class="green" style="text-decoration:none;'; if($option == 'newpost') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'" href="profile.php?view=blog&option=newpost">Make New Post</a> | <a class="green" style="text-decoration:none;color:#333;'; if($option == '') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'" href="profile.php?view=blog">View Blog</a></div></div>';
         
        echo'<div id="container" class="grid_18" style="width:770px;margin-top:-38px;margin-left:-10px;padding:35px;">';

        if($option == 'newpost') {
        
        $time = time();
       
         echo'
            <script>
             function showBlogPhoto() {
                   var blogphoto = document.getElementById(\'blogphoto\').value;
                }
            </script>
            
            <div class="grid_18" style="margin:auto;border:1px solid #ccc;margin-top:30px;margin-left:20px;">
            <div style="float:left;padding:15px;width:130px;height:130px;"><div style="width:130px;"></div><br /><div style="padding-left:10px;"><a style="width:90px;padding:7px;" class="btn btn-success" data-toggle="modal" data-backdrop="static" href="#blogphoto">Add Photo</a></div></div>
            <div style="float:left;font-size:15px;font-weight:200;padding-top:25px;">Title:<br /><br />Subject:<br /><br />Content (400 words):</div>
           
            <form action="profile.php?view=blog&action=submitpost" method="POST">
            
            <div style="float:left;padding:25px;width:350px;"><input style="width:220px;height:20px;" type="text" name="title" placeholder="Title of Blog Post" /><br />
            <input style="width:220px;height:20px;" type="text" name="subject" placeholder="Subject of Blog Post" /></div>
            <input type="hidden" name="time" value="',$time,'" />
            <div style="float:left;margin-top:15px;"><textarea style="width:480px;max-width:480px;" rows="12" cols="60" name="content"></textarea><br /><br />

             <!--ADD PHOTO TO BLOG POST MODAL-->

            <div class="modal hide fade" id="blogphoto" style="overflow-y:scroll;overflow-x:hidden;border:5px solid rgba(102,102,102,.8);"">

            <div class="modal-header" style="background-color:#111;color:#fff;">
            <a style="float:right" class="btn btn-success" data-dismiss="modal">Close</a>
            <img style="margin-top:-2px;" src="graphics/aperture_white.png" height="34" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:16px;font-family:helvetica,arial;font-weight:100;">Choose a photo to add to your blog post:</span>
            </div>
            <div modal-body" style="width:600px;">

            <div id="content" style="font-size:16px;width:550px;height:500px;overflow-x:hidden;background-color:rgb(245,245,245);">';

            if($email != '') {
            echo'
            <img style="border: 1px solid black;margin-left:10px;margin-top:10px;" src="',$profilepic,'" 
            height="100px" width="100px" />

            <div style="width:540px;margin-left:130px;margin-top:-125px;overflow-y:scroll;overflow-x:hidden;">

            <span style="font-size:14px;">
            <br />';
            $allusersphotos = "SELECT * FROM photos WHERE emailaddress = '$email' ORDER BY id DESC";
            $allusersphotosquery = mysql_query($allusersphotos);
            $usernumphotos = mysql_num_rows($allusersphotosquery);
    
            for($iii = 0; $iii < $usernumphotos; $iii++) {
            $userphotosource = mysql_result($allusersphotosquery, $iii, "source");
            $userphotosource = str_replace("userphotos/","http://photorankr.com/userphotos/", $userphotosource);
            $userphotosset[$iii] = mysql_result($allusersphotosquery, $iii, "sets");
            $userphotoscaption[$iii] = mysql_result($allusersphotosquery, $iii, "caption");
            $newsource = str_replace("userphotos/","userphotos/thumbs/", $userphotosource);
        
            echo'<img src="',$newsource,'" height="70" width="70" />&nbsp;&nbsp;<input id="blogphoto" type="radio" name="checked" value="',$userphotosource,'" onclick="showBlogPhoto();" />&nbsp;"',$userphotoscaption[$iii],'"
            <br /><br />'; 
    
        } //end of for loop
    
    
        echo'
        </span>
        <button class="btn btn-success" data-dismiss="modal">Submit Photo</button>
        <br />
        <br />';
        }
        
        else {
        echo'<div style="text-align:center;margin-top:100px;"><b>Please login or register to upload</b></div>';
        }
    
        echo'
        </div>
        </div>
        </div></div>
    
        
            <div style="text-align:center;"><button style="width:460px;padding:10px;font-size:15px;font-weight:200;" class="btn btn-success" type="submit">Submit Blog Post</button><br /><br /></div>
            </div>
            </form>
            
            </div>';
            
        }
        
        elseif($option == '') {
        
            $blogquery = mysql_query("SELECT * FROM blog WHERE emailaddress = '$email' ORDER BY id DESC");
            $numblogposts = mysql_num_rows($blogquery);
            
            echo'<div class="grid_18" style="margin:auto;border:1px solid #ccc;margin-top:30px;margin-left:20px;">';
            
            if($numblogposts == 0) {
                echo'<div style="font-size:18px;font-weight:200;padding:40px;text-align:center;"><a style="color:#333;" href="profile.php?view=blog&option=newpost">You have no blog posts yet. Click here to write your first post.</a></div>';
            }
            
                for($iii=0; $iii < $numblogposts; $iii++) {
                    $id = mysql_result($blogquery,$iii,'id');
                    $title = mysql_result($blogquery,$iii,'title');
                    $subject = mysql_result($blogquery,$iii,'subject');
                    $content = mysql_result($blogquery,$iii,'content');
                    $photo = mysql_result($blogquery,$iii,'photo');
                    $time = mysql_result($blogquery,$iii,'time');
                        
                    if($time) {
                    $date = date("m-d-Y", $time); }
                    
                    
                    if($photo) {
                        echo'
                        <div style="float:left;padding:20px;width:130px;height:130px;"><img src="',$photo,'" height="120" width="120" /></div>
                        <div style="float:left;font-size:20px;font-weight:200;padding-top:30px;width:520px;">',$title,'</div>
                        <div style="float:left;font-size:15px;font-weight:200;padding-top:15px;">Subject: ',$subject,'&nbsp;|&nbsp;Date: ',$date,'</div>
                       
                        <div style="float:left;margin-top:15px;width:650px;padding:20px;font-size:15px;font-weight:200;line-height:1.48;">',$content,'<br /><br />
                        </div><br />';
                    }
                    
                    else {
                        echo'
                        <div style="float:left;font-size:20px;font-weight:200;padding-left:20px;padding-top:30px;width:520px;">',$title,'</div><br />
                        <div style="float:left;font-size:15px;font-weight:200;padding-left:20px;padding-top:15px;">Subject: ',$subject,'&nbsp;|&nbsp;Date: ',$date,'</div>
                       
                        <div style="float:left;margin-top:15px;width:650px;padding:20px;font-size:15px;font-weight:200;line-height:1.48;">',$content,'<br /><br />
                        </div><br />';
                    }
                    
                    echo'
                    <div style="float:left;margin-top:15px;margin-left:20px;width:650px;padding:10px;font-size:15px;font-weight:200;line-height:1.48;">
                    <div class="panelblog',$id,'">';
                    
                        //Comment Loop
                        $commentquery= mysql_query("SELECT * FROM blogcomments WHERE blogid = '$id'");
                        $numcomments = mysql_num_rows($commentquery);
                        
                            for($ii=0; $ii < $numcomments; $ii++) {
                                $comment = mysql_result($commentquery,$ii,'comment');
                                $commenteremail = mysql_result($commentquery,$ii,'emailaddress');
                                $userquery = mysql_query("SELECT user_id,profilepic,firstname,lastname FROM userinfo WHERE emailaddress = '$commenteremail'");
                                $commenterpic = mysql_result($userquery,0,'profilepic');
                                $commenterid = mysql_result($userquery,0,'user_id');
                                $commentername = mysql_result($userquery,0,'firstname')." ".mysql_result($userquery,0,'lastname');
                                
                                echo'<div><a href="viewprofile.php?u=',$commenterid,'"><img src="',$commenterpic,'" height="30" width="30" /><span style="font-weight:bold;color:#3e608c;font-size:12px;padding-left:10px;">',$commentername,'</a></span>&nbsp;&nbsp;',$comment,'</div><hr>';
                            }
                    echo'
                    <form action="profile.php?view=blog&action=comment&blogid=',$id,'" method="POST">
                    <div style="width:620px;"><img style="float:left;padding:10px;" src="',$profilepic,'" height="30" width="30" />
                    <input style="float:left;width:440px;height:20px;position:relative;top:10px;" type="text" name="comment" placeholder="Leave a comment&#8230;" /></div>
                    </form>
                    <br /><br />
                    </div>
                    
                    
                    <a name="',$id,'" href="#"><p class="flipblog',$id,'" style="font-size:15px;"></a>',$numcomments,' Comments</p>
                    </div>
                    
                    <style type="text/css">
                    p.flipblog',$id,' {
                    margin:0px;
                    padding:10px;
                    text-align:center;
                    background:white;
                    border:solid 1px #c3c3c3;
                    }

                    p.flipblog',$id,':hover {
                    background-color: #ccc;
                    }

                    div.panelblog',$id,' {
                    display:none;
                    margin:0px;
                    padding:5px;
                    text-align:left;
                    background:white;
                    border:solid 1px #c3c3c3;
                    }
                    </style>'; ?>
                    
                    <!--HIDDEN COMMENT SCRIPT-->
                    <script type="text/javascript">   
                    $(document).ready(function(){
                    $(".flipblog<?php echo $id; ?>").click(function(){
                        $(".panelblog<?php echo $id; ?>").slideToggle("slow");
                    });
                    });
                    </script>
                    
                    <?php
                    
                    echo'
                    <hr>'; 
                
                }
                
            echo'</div>';
        
        }
        
        echo'</div>';
        
    }
    
    
    
    elseif($view == 'messages') {
            
            	//get all the messages that correspond to them by grouping them by thread number
	$messagequery = "SELECT * FROM (SELECT * FROM messages ORDER BY id DESC) AS theorder WHERE (sender='$email' OR receiver='$email') GROUP BY thread ORDER BY id DESC LIMIT 0,20";
	$messageresult = mysql_query($messagequery) or die(mysql_error());
	$numberofmessages = mysql_num_rows($messageresult);

	//if they don't have any messages, display that
	if($numberofmessages == 0) {
		echo '<div style="margin-left: 460px; margin-top: -130px;font-size:16px;">You have no messages!</div>
        <br />
        <div style="margin-left: 280px; margin-top: 20px;font-size:16px;">(Contact photographers through the "contact" tab in their profile)</div></div>';
	}
	//if they do have messages
	else {
		echo '</div>';
	
		echo '<div class="grid_18" style="padding-left:30px;padding-right:90px;padding-bottom:20px;padding-top:20px;margin-left:-45px;">';

		$comma = 0;

		//for loop to go through each row in the result
		for($iii=0; $iii<$numberofmessages; $iii++) {
			//find what the message is and who it was from and who it was to
			$currentmessage[$iii] = mysql_result($messageresult, $iii, "contents");
			$currentsender = mysql_result($messageresult, $iii, "sender");
			$currentreceiver = mysql_result($messageresult, $iii, "receiver");

			//find out more about the person involved who is not them
			//if the last message was not from the person whose profile it is
			if($currentsender != $email) {
				//the other person is whomever the message was from
				if($comma == 0) {
					$otherpeople .= "'" . $currentsender . "'";
					$comma = 1;
				}
				else {
					$otherpeople .= ", '" . $currentsender . "'";
				}
			}
			//otherwise the last message was from the person the person whose profile it is
			else {
				//the other person is whomever the last message was sent to
				if($comma == 0) {
					$otherpeople .= "'" . $currentreceiver . "'";
					$comma = 1;
				}
				else {
					$otherpeople .= ", '" . $currentreceiver . "'";
				}
			}
		}

		//now that we know everyone whose information we will need, lets get it
		$moreinfoquery = "SELECT firstname, lastname, profilepic FROM userinfo WHERE emailaddress IN (" . $otherpeople . ") ORDER BY FIELD(emailaddress, " . $otherpeople . ") LIMIT 0, 20";
		$moreinforesult = mysql_query($moreinfoquery) or die(mysql_error());
		
		//now go through the results to get the information and then display it
        echo'
                    <span style="font-size:18px;font-weight:200;">Your Conversations:</span><br />
                    <span style="font-size:13px;font-weight:200;">(Contact photographers through the "contact" tab in their profile)</span>
                    <br /><br />';


		for($iii=0; $iii<$numberofmessages; $iii++) {
			$otherspic = mysql_result($moreinforesult, $iii, "profilepic");
			$othersfirst = mysql_result($moreinforesult, $iii, "firstname");
			$otherslast = mysql_result($moreinforesult, $iii, "lastname");
			$currentthread = mysql_result($messageresult, $iii, "thread");

			//now lets display the message with the other's profile picture and name
			echo '
			<a href="profile.php?view=viewthread&thread=', $currentthread, '" style="text-decoration: none;">
			<div class="grid_18 message" style="margin-bottom:20px; font-family: helvetica neue; font-size:14px;">
				<div  class="grid_3">
					<img src="', $otherspic, '" width="60px" height="60px" alt="profile picture" style="margin-bottom: 5px;"/>
					<br />', 
					$othersfirst, ' ', $otherslast, 
				'</div>
				<div class="grid_15" style="margin-top: -75px; margin-left: 120px;">', $currentmessage[$iii], 
				'</div>
			</div>
            <hr>
			</a>';
		}

		echo '</div>';
	}
}
else if($view == "viewthread") {

//DE-HIGHLIGHT NOTIFICATIONS IF CLICKED ON
if(isset($_GET['id'])){
$id = htmlentities($_GET['id']);
$idformatted = $id . " ";
$unhighlightquery = "UPDATE userinfo SET unhighlight = CONCAT(unhighlight,'$idformatted') WHERE emailaddress = '$email'";
$unhighlightqueryrun = mysql_query($unhighlightquery);

//notifications query reset 
if($currentnotsresult > 0) {
$notsquery = "UPDATE userinfo SET notifications = 0 WHERE emailaddress = '$email'";
$notsqueryrun = mysql_query($notsquery); }
}
	
	//if no thread was sent, tell them no thread found
	if(!isset($_GET['thread'])) {
		echo '<div style="margin-left: 480px; margin-top: -300px;">No thread found!</div></div>';
	}
	//otherwise there is a thread
	else {
		//select all the messages that match the thread number
		$threadquery = "SELECT * FROM messages WHERE thread=".mysql_real_escape_string(htmlentities($_GET['thread']))." ORDER BY id DESC LIMIT 0, 20";
		$threadresult = mysql_query($threadquery) or die(mysql_error());
		$numberofmessages = mysql_num_rows($threadresult);
		
		//if this returns zero messages, then tell them no thread found
		if($numberofmessages == 0) {
			echo '<div style="margin-left: 480px; margin-top: -300px;">No thread found!</div></div>';
		}
		//otherwise there were messages found
		else {
			echo '</div>';
	
			echo '<div class="grid_18" style="background-color:rgba(245,245,245,0.6);padding-left:30px;padding-right:90px;padding-bottom:20px;padding-top:20px;margin-left:-45px;">';

			//find out the other persons email address
			if(mysql_result($threadresult, 0, "sender") == $email) {
				$othersemail = mysql_result($threadresult, 0, "receiver");
			}
			else {
				$othersemail = mysql_result($threadresult, 0, "sender");
			}

			//update the database to show that these messages have been read
			$updatequery = "UPDATE messages SET unread='0' WHERE receiver='$email' AND thread='".mysql_real_escape_string(htmlentities($_GET['thread']))."'";
			mysql_query($updatequery); 

			//find out all the info we need about the other person
			$othersquery = "SELECT user_id, firstname, lastname, profilepic, emailaddress FROM userinfo WHERE emailaddress='" . $othersemail . "' LIMIT 0, 1";
			$othersresult = mysql_query($othersquery);
			$otherspic = mysql_result($othersresult, 0, "profilepic");
			$othersfirst = mysql_result($othersresult, 0, "firstname");
			$otherslast = mysql_result($othersresult, 0, "lastname");
            $othersid = mysql_result($othersresult, 0, "user_id");
			
			//for loop to go through all the messages in reverse order so that the newest one is last
			for($iii=$numberofmessages-1; $iii >= 0; $iii--) {
				//find out who sent the current message in the loop
				$currentsender = mysql_result($threadresult, $iii, "sender");

				//if the current message's sender is the owner of the profile, set the variables as necessary
				if($currentsender == $email) {
					$currentfirst = $firstname;
					$currentlast = $lastname;
					$currentpic = $profilepic;
                    $currentuserid = $userid;
				}
				//otherwise the other person is the message's sender, so set the variables accordingly
				else {
					$currentfirst = $othersfirst;
					$currentlast = $otherslast;
					$currentpic = $otherspic;
                    $currentuserid = $othersid;
				}
				
				//find out what the current message is
				$currentmessage = mysql_result($threadresult, $iii, "contents");

				//now that we have everything in line, display the message
				echo '
				<div class="grid_18 message" style="margin-bottom: 20px; font-family: arial;">
					<a href="viewprofile.php?u=',$currentuserid,'">
					<div class="grid_3">
						<img src="', $currentpic, '" width="60px" height="60px" alt="profile_picture" style="margin-bottom: 5px;"/><br />',$currentfirst,' ', $currentlast,' 
					</div>
					</a>
					<div class="grid_15" style="margin-top: -55px; margin-left: 120px;">',$currentmessage,'
					</div>
				</div>
                <hr />';			
			}

			//now let's display the box from which they can send a message
			echo' <div class="grid_18" style="font-size: 20px; font-family: Helvetica Neue, Helvetica, Arial, sans-serif;
			line-height: 28px; color: #333333;">
    
			<span style="font-size:16px;">Reply:</span>
			<form method="post" action="replymessage.php" />
			<textarea cols="80" rows="4" style="width:715px" name="message"></textarea>
    			<br />
    			<br />
			<button class="btn btn-success" type="submit" value="Send Message">Send Message</button>
			<input type="hidden" name="emailaddressofviewed" value="',$othersemail,'" />
			</form>';

			if(htmlentities($_GET['action'])=="messagesent") {
				echo 'Message Sent!';
			}
			
			echo '</div>';
		}
	}
    
}
        
  
   ?>
   
   </div> <!--end 24 portfolio grid-->
    
</div><!--end of container-->

    <script src="js/bootstrap-dropdown.js"></script>
    <script type="text/javascript">  
        $(document).ready(function () {  
            $('.dropdown-toggle').dropdown();  
        });  
    </script> 
    
</body>
</html>
