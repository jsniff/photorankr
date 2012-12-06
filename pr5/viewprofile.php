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
    
    //GRAB USER INFORMATION
  $userid = htmlentities($_GET['u']);
  $userquery = mysql_query("SELECT * FROM userinfo WHERE user_id = '$userid'");
  $profilepic = mysql_result($userquery,0,'profilepic'); 
  $useremail = mysql_result($userquery,0,'emailaddress');
  $useremail = trim($useremail);
  $firstname = mysql_result($userquery,0,'firstname');
  $lastname = mysql_result($userquery,0,'lastname');
  $fullname = $firstname . " " . $lastname; 
  $age = mysql_result($userquery,0,'age');
  $gender = mysql_result($userquery,0,'gender');
  $location = mysql_result($userquery,0,'location');
  $camera = mysql_result($userquery,0,'camera');
  $about = mysql_result($userquery,0,'about');
  $quote = mysql_result($userquery,0,'quote');
  $fbook = mysql_result($userquery,0,'fbook');
  $twitter = mysql_result($userquery,0,'twitter');
  $faves = mysql_result($userquery,0,'faves');
  $reputation = number_format(mysql_result($userquery,0,'reputation'),2);
  $profileviews = mysql_result($userquery,0,'profileviews');
  $groups = mysql_result($userquery,0,'groups');
  $groupsarray = explode($groups," ");
  $numgroups = count($groupsarray);
  
  //ADD PAGEVIEW TO THEIR PROFILE
  $profileviewquery = mysql_query("UPDATE userinfo SET profileviews = (profileviews + 1) WHERE user_id = '$userid'");

  //GET VIEW
  $view = htmlentities($_GET['view']);
  
    //PORTFOLIO RANKING

    $followersquery = mysql_query("SELECT * FROM userinfo WHERE following LIKE '%$useremail%'");
	$numberfollowers = mysql_num_rows($followersquery);
    
    //Grab Overall Portfolio Ranking
    $userphotos="SELECT * FROM photos WHERE emailaddress = '$useremail'";
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
    
    $scorequery = "UPDATE userinfo SET totalscore = '$portfoliopoints' WHERE emailaddress = '$useremail'";    
    $scoreresult = mysql_query($scorequery);
    
    }
    
    else if ($portfoliovotes < 1) {
    $portfolioranking="N/A";
    }	
    
    //NUMBER FOLLOWING
    $emailquery=("SELECT following FROM userinfo WHERE emailaddress ='$useremail'");
	$followresult=mysql_query($emailquery);
	$followinglist=mysql_result($followresult, 0, "following");
	$followingquery="SELECT * FROM userinfo WHERE emailaddress IN ($followinglist)";
	$followingresult = mysql_query($followingquery);
	$numberfollowing = mysql_num_rows($followingresult);


if(isset($_GET['view'])) {
	$view=htmlentities($_GET['view']); //get which tab of profile they are looking at
}

 //FOLLOWING QUERIES
$follow;
if(isset($_GET['fw'])) {
$follow=$_GET['fw'];
}
else {$follow=0;}

if ($follow==1) {
	if($_SESSION['loggedin'] == 1) {
    
		$emailquery=("SELECT * FROM userinfo WHERE emailaddress ='$email'");
		$emailresult=mysql_query($emailquery);
		$prevemails=mysql_result($emailresult, 0, "following");
		$viewerfirst = mysql_result($emailresult, 0, "firstname");
		$viewerlast = mysql_result($emailresult, 0, "lastname");
		if($prevemails == "") {$emailaddressformatted="'". $useremail . "'";}
		else {$emailaddressformatted=", '". $useremail . "'";}
        
		//MAKE SURE FOLLOWER ISN'T ADDED TWICE
		$search_string=$prevemails;
		$regex="/$useremail/";
		$match=preg_match($regex,$search_string);
		if ($match > 0) {

		} 
        
		else {
        
			$followingstring=$prevemails . $emailaddressformatted;
			$followingstring=addslashes($followingstring);
			$followquery = "UPDATE userinfo SET following = '$followingstring' WHERE emailaddress='$email'";
			$followingresult=mysql_query($followquery);
            
             $type2 = "follow";
             $ownername = $firstname . " " . $lastname;
        $newsfeedfollowquery="INSERT INTO newsfeed (firstname, lastname, emailaddress,following,type,owner,time) VALUES ('$viewerfirst', '$viewerlast', '$email','$useremail','$type2','$ownername','$currenttime')";
        $follownewsquery = mysql_query($newsfeedfollowquery);
        
        //notifications query     
$notsquery = "UPDATE userinfo SET notifications = (notifications + 1) WHERE emailaddress = '$useremail'";
$notsqueryrun = mysql_query($notsquery);  
            
             		//PERSON NOW BEING FOLLOWED
    
//GRAB SETTINGS LIST
$settingquery = "SELECT * FROM userinfo WHERE emailaddress = '$useremail'";
$settingqueryrun = mysql_query($settingquery);
$settinglist = mysql_result($settingqueryrun, 0, "settings");

$setting_string = $settinglist;
$find = "emailfollow";
$foundsetting = strpos($setting_string,$find);
    
        		$to = '"' . $firstname . ' ' . $lastname . '"' . '<'.$useremail.'>';
        		$subject = $viewerfirst . " " . $viewerlast . ' is now following your photography on PhotoRankr!';
        		$message = 'You have a new follower on PhotoRankr! Visit their photography here: https://photorankr.com/viewprofile.php?u='.$sessionuserid;
        		$headers = 'From:PhotoRankr <photorankr@photorankr.com>';
                if($foundsetting > 0) {
        		mail($to, $subject, $message, $headers);   
                }
		}
	}
}

 //Unfollow Query

    if(htmlentities($_GET['uf']) == 1) {

        $followingquery = mysql_query("SELECT following FROM userinfo WHERE emailaddress = '$email'");
        $following = mysql_result($followingquery,0,'following');
        $updatefollowing = "UPDATE userinfo SET following = replace(following,'$useremail','') WHERE emailaddress = '$email'";	
        $updaterun = mysql_query($updatefollowing);

    }

        if($_GET['action'] == 'comment') {
    
            $blogid = htmlentities($_GET['blogid']);
            $comment = mysql_real_escape_string($_POST['comment']);
                    
            $commentinsertion = mysql_query("INSERT INTO blogcomments (comment,blogid,emailaddress) VALUES ('$comment','$blogid','$email')");
            
            $type = 'blogcomment';
            $blogcommentnewsfeed = mysql_query("INSERT INTO newsfeed (emailaddress,type,source,owner,time) VALUES ('$email','$type','$blogid','$useremail','$currenttime')");
            
            //notifications query     
            $notsquery = "UPDATE userinfo SET notifications = (notifications + 1) WHERE emailaddress = '$useremail'";
            $notsqueryrun = mysql_query($notsquery); 

            
            echo '<META HTTP-EQUIV="Refresh" Content="0; URL=viewprofile.php?u=',$userid,'&view=blog#',$blogid,'">';
            exit();
    
        }

        //Exhibit Faves
        
         if($_GET['exfv'] == 1) {
    
        $set = $_GET['set'];
        
        $grabsettitle = mysql_query("SELECT title FROM sets WHERE set = '$set'");
        $settitle = mysql_result($grabsettitle,0,'title');
        
        if($_SESSION['loggedin'] == 1) {
        
            $exhibitfavecheck = mysql_query("SELECT exhibitfaves FROM userinfo WHERE emailaddress = '$email'");
            $faves = mysql_result($exhibitfavecheck,0,'exhibitfaves');
            
            $match=strpos($faves, $set);
        
            if(!$match) {
                $formattedset = '"' . $set . '",';
                $setexfave = mysql_query("UPDATE userinfo SET exhibitfaves = CONCAT(exhibitfaves,'$formattedset') WHERE emailaddress = '$email'");
                $incrementsetfave = mysql_query("UPDATE sets SET faves = (faves + 1) WHERE id = '$set'");
                
                //newsfeed query
                $type = "exhibitfave";
                $newsfeedexhibitfavequery = mysql_query("INSERT INTO newsfeed (firstname,lastname,emailaddress,type,source,owner,time) VALUES ('$sessionfirst', '$sessionlast','$email','$type','$set','$useremail','$currenttime')");
     
                //notifications query     
                $notsquery = "UPDATE userinfo SET notifications = (notifications + 1) WHERE emailaddress = '$useremail'";
                $notsqueryrun = mysql_query($notsquery);       
 
                //GRAB SETTINGS LIST
                $settingquery = "SELECT settings FROM userinfo WHERE emailaddress = '$useremail'";
                $settingqueryrun = mysql_query($settingquery);
                $settinglist = mysql_result($settingqueryrun, 0, "settings");
                                  
                $setting_string = $settinglist;
                $find = "emailfave";
                $foundsetting = strpos($setting_string,$find);
            
                //MAIL PHOTOGRAPHER NOTICE THAT THEIR PHOTO HAS BEEN FAVORITED
                $to = '"' . $sessionfirst . ' ' . $sessionlast . '"' . '<'.$useremail.'>';
                $subject = $sessionfirst . " " . $sessionlast . " favorited one of your exhibits on PhotoRankr";
                $favemessage = $firstname . " " . $lastname . " favorited one of your exhibits on PhotoRankr
        
To view the exhibit, click here: https://photorankr.com/viewprofile.php?u=".$userid."&view=exhibits&set=".$set;
                $headers = 'From:PhotoRankr <photorankr@photorankr.com>';
          
                if($foundsetting > 0) {
                    mail($to, $subject, $favemessage, $headers); 
                }

            } //end of no match
        
        } //end session check

    }

?>

<!DOCTYPE HTML>
<head>

	<meta charset = "UTF-8">
	<meta name="viewport" content="width=1260px">
	<title> Sell, share and discover brilliant photography </title>
    <link rel="stylesheet" type="text/css" href="css/style.css"/>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>
	<link rel="stylesheet" type="text/css" href="css/960grid.css"/>
	<link rel="stylesheet" type="text/css" href="css/reset.css"/> 
	<link rel="stylesheet" media='screen and (max-width:640px)' href="css/640.css"/>

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
	<script src="js/modernizer.js"></script>
    <script type="text/javascript" src="js/jquery.wookmark.js"></script>   
	<style type="text/css">
    
		.show
		{
			display:block !important;
		}
		
		#notify
		{
			width:40px;
			margin: 0 0 0 5px;
			background:#d96f62;
			padding: 5px;
		}
		#notify:hover
		{
			background: rgba(255,255,255,.55);
		}
		#drawer
		{
			width:0px;
			background: url('graphics/noise.png');
			color:#fff;
			white-space: normal;
			font-size: 10px;
			position:fixed;
			height:100%;
			box-shadow: inset 0 0 5px rgba(0,0,0,.25);
			border-radius:0 5px 5px 0;
			margin: 5px 0 0 -5px;
			z-index: 1000;
		}
		.notifications
		{
			font-family:"helvetica neue", helvetica, arial,sans-serif; 
			font-size:20px;
			font-weight: 500;
			color:#fff;
			margin-left: -200px;
			width:200px;

		}
		.test
		{
			height:250px;
			background: rgba(200,200,200,.6);
			box-shadow: 0 0 2px #666;
			margin: 4px 20px 0 0;
		}
		.test2
		{
			height:50px;
			background: rgba(200,200,200,.6);
			box-shadow: 0 0 2px #666;
			margin: 7px 4px !important;
			width:125px;
			float: right;
		}
		.x
		{
			background:none !important;
			color:#222 !important;
			padding: 0 !important;
			box-shadow: 0 0 0 !important;
			margin:10px 5px 0 5px !important;
			border: none !important;
			font-size: 14px;
		}
		/*.arrow-right {
	width: 0; 
	height: 0; 
	border-top: 13px solid transparent;
	border-bottom: 13px solid transparent;
	box-shadow: inset 0 0 1px #999;
	border-right: 13px solid rgba(245,245,245,1);
	position: absolute;
	top:33px;
	left:75px;
}*/
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
<body id="body" >

<?php navbar(); ?>

<!--BEGIN CONTAINER-->

<div class="container_custom clearfix" style="margin:50px auto 0 auto;width:1100px;padding-left:70px;">

<!--LEFT COL-->
	<div id="leftCol">

		<!--top photos-->
		<div id="topPhotos">
			<header> <?php echo $firstname; ?>'s Top Photos </header>

			<!--Top Pics go here-->
            <?php
                $topphotos = mysql_query("SELECT source,id,caption FROM photos WHERE emailaddress = '$useremail' ORDER BY faves DESC LIMIT 0,6");
                $numphotos = mysql_num_rows($topphotos);
                echo'<div id="topPhotoContainer">';

                if($numphotos == 6) {
                    
                    for($ii=0;$ii<=5;$ii++) {
                        $source = mysql_result($topphotos,$ii,'source');
                        $source = str_replace('userphotos','userphotos/medthumbs/',$source);
                        $caption = mysql_result($topphotos,$ii,'caption');
                        $caption = (strlen($caption) > 18) ? substr($caption,0,15). " &#8230;" : $caption;

                        $imageid = mysql_result($topphotos,$ii,'id');

                        echo'<div class="topPhoto">
                             <header> ',$caption,' </header>
                             <a href="fullsizeview.php?imageid=',$imageid,'"><img style="min-height:165px;" src="https://photorankr.com/',$source,'" /></a>
                             <div class="statOverlay"></div>
                             </div>';
                        }
                    }
                ?>				
			</div>
		</div>

		<!--portfolio-->
		<div id="portfolio">
			<header> <?php echo $firstname; ?>'s Portfolio </header>
			
			<nav>
				<ul>
					<a href=""><li> Favorites </li></a>
					<a href=""><li> Collections </li></a>
					<a href=""><li> Exhibits </li></a>
					<a  href=""><li style="border-right:none;"> Portfolio </li></a>
					<form>
						<input style="width:7em;margin-top:3px;" type="text" placeholder="search"/ >
					</form>
				</ul>
			</nav>

			<?php
                //portfolio photo queries
                $query = mysql_query("SELECT * FROM photos WHERE emailaddress = '$useremail' ORDER BY id DESC LIMIT 0,21");
                $numresults = mysql_num_rows($query);
                
            echo'
                <div id="main">
                <ul id="tiles">';

                for($iii=0; $iii < $numresults && $iii < 6; $iii++) {
                    $image = mysql_result($query, $iii, "source");
                    $image= '../' . $image;
                    $imageThumb = str_replace("../userphotos/","userphotos/medthumbs/", $image);
                    $id = mysql_result($query, $iii, "id");
                    $caption = mysql_result($query, $iii, "caption");
                    $points = mysql_result($query, $iii, "points");
                    $votes = mysql_result($query, $iii, "votes");
                    $faves = mysql_result($query, $iii, "faves");
                    $score = number_format(($points/$votes),2);
                    $faveemail = mysql_result($query, $iii, "emailaddress");
                    $ownerquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$faveemail'");
                    $fullname = $firstname . " " . $lastname;
                    
                    list($width, $height) = getimagesize($image);
                    $imgratio = $height / $width;
                    $heightls = $height / 3.7;
                    $widthls = $width / 3.7;
                    if($widthls < 150) {
                        $heightls = $heightls * ($heightls/$widthls);
                        $widthls = 200;
                    }

                   echo'<div class="topPhoto">
                             <header> ',$caption,' </header>
                             <a href="fullsizeview.php?imageid=',$imageid,'"><img style="min-width:200px;" src="https://photorankr.com/',$imageThumb,'" /></a>
                             <div class="statOverlay"></div>
                        </div>';       	
            
      } //end for loop
        
    echo'
        </ul>';
        
    ?>

</div>
    
    </div>
	</div>

<!--END LEFTCOL-->


<!--CENTER COL-->
	<div id="centerCol">

		<!--profile id-->
		<div id="profileId">
			<img src="https://photorankr.com/<?php echo $profilepic; ?>"/>
			<header> <?php echo $fullname; ?> </header>
			<ul>
				<li> <img src="graphics/camera.png"> <?php echo $numphotos; ?> <span> Photos </span> </li>
				<li> <img src="graphics/rep_i.png">  <?php echo $reputation; ?> <span>Reputation</span> </li>
				<li> <img style="width:28px;" src="graphics/network_i.png"> <?php echo $numberfollowers; ?> <span>Followers</span></li>
			</ul>
			<ul style="margin-top: -5px;border-top:1px solid #c6c6c6">
				<li> <img src="graphics/rank_prof.png"> <?php echo $portfolioranking; ?> <span> Avg Rank </span> </li>
				<li style="margin-top:2px;"> <img style="margin: 0 0 5px 0;" src="graphics/views.png"> <?php echo $profileviews; ?> <span>Total Views</span> </li>
				<li style="margin-top:2px;"> <img style="width:40px;margin:0 0 5px 9px" src="graphics/more_i.png">   <span> See More</span></li>
			</ul>
			
			<!--hidden ABOUT div-->
			<div id="aboutUser">
			</div>

			<div id="actionButtons">
				<button id="follow"> Follow </button>
				<button id="message"> Message </button>
				<button id="promote"> Promote </button>
			</div>
            
            <div id="quote">
                <blockquote id="quoteText">
                    <?php echo $quote; ?>
                    </span>
                </blockquote>
            </div>

			<form> 
				<label for="searchTerm" name="Search">  </label>
				<img src="graphics/search_i.png"/>
				<input onfocus="if(this.value == 'search') { this.value = '' }" value="search" type="text" name="searchTerm" placeholder="search"/> 
			</form>
		</div>

		<!--activity feed-->
		<div id="activityFeed">
			<header> Recent Activity </header>
			<div id="comment">
		
			
		</div>
		</div>

	</div>
<!--END CENTER COL-->

<!--RIGHT COL-->
	<div id="rightCol">

		<!--displays groups-->
		<div id="groupsDisplay">
			<!--top photos-->
			<header> <?php echo $firstname; ?>'s Groups <div> <?php echo $numgroups; ?> </div> </header>

			<!--Top Pics go here-->
			<div id="groupContainer">

				<div class="Group">
						
					<div>
						<img src="img/img-8.jpg">
					</div>
					<header><img src="graphics/groups_b.png"/> Group Name </header>
					<img class="holder-TR" src="graphics/holder_bl_i.png">
					<img class="holder-BL" src="graphics/holder_tr_i.png">
				</div>
				
				<div class="Group">
						
					<div>
						<img src="img/img-8.jpg">
					</div>
					<header><img src="graphics/groups_b.png"/> Group Name </header>
					<img class="holder-TR" src="graphics/holder_bl_i.png">
					<img class="holder-BL" src="graphics/holder_tr_i.png">
				</div>

				<div class="Group">
						
					<div>
						<img src="img/img-8.jpg">
					</div>
					<header><img src="graphics/groups_b.png"/> Group Name </header>
					<img class="holder-TR" src="graphics/holder_bl_i.png">
					<img class="holder-BL" src="graphics/holder_tr_i.png">
				</div>

				<div class="Group">
						
					<div>
						<img src="img/img-8.jpg">
					</div>
					<header><img src="graphics/groups_b.png"/> Group Name </header>
					<img class="holder-TR" src="graphics/holder_bl_i.png">
					<img class="holder-BL" src="graphics/holder_tr_i.png">
				</div>

				<div class="Group">
						
					<div>
						<img src="img/img-8.jpg">
					</div>
					<header><img src="graphics/groups_b.png"/> Group Name </header>
					<img class="holder-TR" src="graphics/holder_bl_i.png">
					<img class="holder-BL" src="graphics/holder_tr_i.png">
				</div>

				<div class="Group">
						
					<div>
						<img src="img/img-8.jpg">
					</div>
					<header><img src="graphics/groups_b.png"/> Group Name </header>
					<img class="holder-TR" src="graphics/holder_bl_i.png">
					<img class="holder-BL" src="graphics/holder_tr_i.png">
				</div>

			</div>
		</div>


		<!--Profile store-->
		<div id="profileStore">
			<header> <?php echo $firstname; ?>'s Store </header>

         <?php
                $storephotos = mysql_query("SELECT source,id,caption,price FROM photos WHERE emailaddress = '$useremail' AND price != '.00' ORDER BY points DESC LIMIT 0,6");
                $numphotos = mysql_num_rows($storephotos);
                echo'<div id="topPhotoContainer">';

                if($numphotos == 6) {
                    
                    for($ii=0;$ii<=5;$ii++) {
                        $source = mysql_result($storephotos,$ii,'source');
                        $source = str_replace('userphotos','userphotos/medthumbs/',$source);
                        $price = mysql_result($storephotos,$ii,'price');
                        $price = number_format($price,0);
                        $caption = mysql_result($storephotos,$ii,'caption');
                        $caption = (strlen($caption) > 18) ? substr($caption,0,15). " &#8230;" : $caption;

                        $imageid = mysql_result($storephotos,$ii,'id');

                        echo'<li>
			<div class="storeContainer">
				<div class="storeContainerOverlay">
					<header> $',$price,' </header>
					<header> ',$caption,' </header>
				</div>
				<img src="https://photorankr.com/',$source,'"/>

			</div>	
		</li>';
                        }
                    }
                ?>
                
		</div>

	</div>
<!--END RIGHT COL-->

</div>
<!--END CONTAINER-->

</body>

<!--JAVASCRIPT-->
</html>
	