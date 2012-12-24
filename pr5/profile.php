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
$profilepic = mysql_result($userinfo,0,'profilepic');
$firstname= mysql_result($userinfo,0,'firstname');
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
$reputation = number_format($reputation,1);
$profileviews = mysql_result($userinfo,0,'profileviews');

//Blog Information
$blogquery = mysql_query("SELECT * FROM blog WHERE emailaddress = '$email' ORDER BY id DESC");
$numblogposts = mysql_num_rows($blogquery);
$newestpost =  mysql_result($blogquery,0,'content');
$posttime =  mysql_result($blogquery,0,'time');
$postdate = '10/24/12';

    //Photos
    $userphotosquery = mysql_query("SELECT points,votes,faves,price,sold,width,height,views FROM photos WHERE emailaddress = '$email'");
    $numphotos = mysql_num_rows($userphotosquery);
    for($iii = 0; $iii < $numphotos; $iii++) {
		$points = mysql_result($userphotosquery, $iii, "points");
        $votes = mysql_result($userphotosquery, $iii, "votes");
        $totalfaves = mysql_result($userphotosquery, $iii, "faves");
        $price = mysql_result($userphotosquery, $iii, "price");
        $views = mysql_result($userphotosquery, $iii, "views");
        $width = mysql_result($userphotosquery, $iii, "width");
        $height = mysql_result($userphotosquery, $iii, "height");
        $sold = mysql_result($userphotosquery, $iii, "sold");
        $portfoliopoints += $points;
        $portfoliovotes += $votes;
        $portfoliofaves += $totalfaves;
        $portfolioprice += $price;
        $portfoliowidth += $width;
        $portfolioheight += $height;
        $portfoliosold += $sold;
        $portfolioviews += $views;
        if($width && $height) {
            $numresphotos += 1;
        }
    }
    if($portfoliovotes > 0) {
        $portfolioranking=($portfoliopoints/$portfoliovotes);
        $portfolioranking=number_format($portfolioranking, 2, '.', '');
    }
    elseif($portfoliovotes < 1) {
        $portfolioranking="N/A";
    }
        $avgprice = number_format(($portfolioprice/$numphotos), 2);
        $avgwidth = number_format(($portfoliowidth/$numresphotos), 0);
        $avgheight = number_format(($portfolioheight/$numresphotos), 0);
        $portfolioviews = number_format($portfolioviews, 0);

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

    //Activity Queries
    $activityquery = mysql_query("SELECT * FROM newsfeed WHERE hide <> 1 AND (emailaddress = '$email' OR owner = '$email') AND type IN ('follow','comment','fave','photo') ORDER BY id DESC LIMIT 13");

    //Get Views & URI
    $view = htmlentities($_GET['view']);
    $action = htmlentities($_GET['action']);
    $option = htmlentities($_GET['option']);  
    $uri = $_SERVER['REQUEST_URI'];
    
$userphotos="SELECT * FROM Statistics WHERE Email = '$email'";
    $userphotosquery=mysql_query($userphotos);
    $numphotos=mysql_num_rows($userphotosquery);

//Initialize week page views to 0
$viewsweekone = 0;
$viewsweektwo = 0;
$viewsweekthree = 0;
$viewsweekfour = 0;
$viewsweekfive = 0;
$viewsweeksix = 0;
$viewsweekseven = 0;
$viewsweekeight = 0;
$viewsweeknine=0;
$viewsweekten=0;
$viewsweekeleven=0;
$viewsweektwelve=0;
$monthone = 0;
$monthtwo = 0;
$monththree=0;
$currenttime = time();
for($iii = 0; $iii < $numphotos; $iii++) {
$timestamp = mysql_result($userphotosquery,$iii,'ViewTimeStamp');

    $timedifference = $currenttime-$timestamp;
if($timedifference < 604800){
$viewsweekone += 1;
}
elseif($timedifference<604800*2 && $timedifference>604800){
$viewsweektwo += 1;
}
elseif($timedifference<604800*3  && $timedifference>604800*2){
$viewsweekthree += 1;
}
elseif($timedifference<604800*4  && $timedifference>604800*3){
$viewsweekfour += 1;
}
elseif($timedifference<604800*5  && $timedifference>604800*4){
$viewsweekfive += 1;
}
elseif($timedifference<604800*6  && $timedifference>604800*5){
$viewsweeksix += 1;
}
elseif($timedifference<604800*7  && $timedifference>604800*6){
$viewsweekseven += 1;
}
elseif($timedifference<604800*8  && $timedifference>604800*7){
$viewsweekeight += 1;
}
elseif($timedifference<604800*9  && $timedifference>604800*8){
$viewsweeknine += 1;
}
elseif($timedifference<604800*10  && $timedifference>604800*9){
$viewsweekten += 1;
}
elseif($timedifference<604800*11  && $timedifference>604800*10){
$viewsweekeleven += 1;
}
elseif($timedifference<604800*12  && $timedifference>604800*11){
$viewsweektwelve += 1;
}

}

$monthone = $viewsweekone+$viewsweektwo+$viewsweekthree+$viewsweekfour;
$monthtwo = $viewsweekfive + $viewsweeksix + $viewsweekseven + $viewsweekeight;
$monththree = $viewsweeknine + $viewsweekten + $viewsweekeleven + $viewsweektwelve;



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
    <link rel="stylesheet" type="text/css" href="css/vpstyle.css"/>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>
	<link rel="stylesheet" type="text/css" href="css/960grid.css"/>
	<link rel="stylesheet" type="text/css" href="css/reset.css"/>    
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery.wookmark.js"></script>  
    <script src="js/bootstrap.js" type="text/javascript"></script>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>          
    <link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>
    
    <style type="text/css">
     .statoverlay

{
-moz-box-shadow: 0 1px 2px rgba(0, 0, 0, 0.15);
-webkit-box-shadow: 0 1px 2px rgba(0, 0, 0, 0.15);
box-shadow: 0 1px 2px rgba(0, 0, 0, 0.15);
filter:alpha(opacity=40);
z-index:1;
transition: opacity .5s;
-moz-transition: opacity .5s;
-webkit-transition: opacity .5s;
-o-transition: opacity .5s;
-webkit-border-radius: 2px;
-moz-border-radius: 2px;
border-radius: 2px;
-webkit-border-bottom-right-radius: 3px;
-webkit-border-bottom-left-radius: 3px;
-moz-border-radius-bottomright: 3px;
-moz-border-radius-bottomleft: 3px;
border-bottom-right-radius: 3px;
border-bottom-left-radius: 3px;
}

    </style>
  
  <title>PhotoRankr - View Profile</title>
  
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
  
//Drop down for portfolio pages
jQuery(document).ready(function(){
    jQuery("#showViews").live("click", function(event) {        
         jQuery("#portfolioViews").toggle();
    });
    jQuery("#hideViews").live("click", function(event) {        
         jQuery("#portfolioViews").hide();
    });
})
    
//Status Update Form

$(function() {
$("#submitStatus").click(function() 
{
var firstname = '<?php echo $firstname; ?>';
var lastname = '<?php echo $lastname; ?>';
var status = $("#status").val();
var dataString = 'firstname='+ firstname + '&lastname=' + lastname + '&status=' + status;
if(status=='')
{
alert('Please Post an Update');
}
else
{
$.ajax({
type: "POST",
url: "ajaxStatus.php",
data: dataString,
cache: false,
success: function(html){
$("ol#update").append(html);
$("ol#update li:last").fadeIn("slow");
}
});
}return false;
}); });

//Page views line graph
google.load("visualization", "1", {packages:["corechart"]});
google.setOnLoadCallback(drawChart);
  function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Month', 'PageViews'],
          ['30',  <?php echo $monthone; ?>],
           ['60', <?php echo $monthtwo; ?>],
           ['90', <?php echo $monththree; ?>],
       ]);

        var options = {
          title: 'Photography Page Views  <?php echo $totalphotoviews?>'
        };

        var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>

</head>

<?php
 //Add Photos to Exhibit Modal

echo'<div class="modal hide fade" id="add" style="overflow-y:scroll;overflow-x:hidden;border:5px solid rgba(102,102,102,.8);position:relative;top:50px;">

<div class="modal-header" style="background-color:#111;color:#fff;">
<a style="float:right" class="btn btn-success" data-dismiss="modal">Close</a>
<img style="margin-top:-2px;" src="graphics/aperture_white.png" height="34" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:16px;font-family:helvetica,arial;font-weight:100;">Add photos to your exhibit below:</span>
  </div>
  <div modal-body" style="width:600px;">

<div id="content" style="font-size:16px;width:550px;height:500px;overflow-x:hidden;background-color:rgb(245,245,245);">
		
<img style="border: 1px solid black;margin-left:10px;margin-top:10px;" src="',$setcover,'" 
height="100px" width="100px" />

<div style="width:540px;margin-left:130px;margin-top:-100px;overflow-y:scroll;overflow-x:hidden;">

<form action="myprofile.php?view=exhibits&set=',$set,'&mode=added" method="post" enctype="multipart/form-data">
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
        echo'<img src="https://photorankr.com/',$newsource,'" height="70" width="70" />&nbsp;&nbsp;<input type="checkbox" name="addthese[]" value="',$userphotosource[$iii],'" checked />&nbsp;"',$userphotoscaption[$iii],'"
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
    </div>
    </div>';
    
    
    //Edit Exhibit Modal

echo'<div class="modal hide fade" id="editexhibit" style="overflow-y:scroll;overflow-x:hidden;border:5px solid rgba(102,102,102,.8);">

<div class="modal-header" style="background-color:rgb(224,224,224);color:#333;font-weight:300;font-size:16px;">
<a style="float:right" class="btn btn-success" data-dismiss="modal">Close</a>
<img style="margin-top:-2px;" src="graphics/aperture_dark.png" height="34" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:16px;font-family:helvetica,arial;font-weight:100;">Edit your exhibit\'s information below:</span>
</div>
  <div modal-body" style="width:600px;">

<div id="content" style="font-size:16px;width:550px;height:450px;overflow-x:hidden;background-color:rgb(250,250,250);">
		
<img style="border: 1px solid black;margin-left:10px;margin-top:10px;" src="',$setcover,'" 
height="100px" width="100px" />

<div style="width:540px;margin-left:130px;margin-top:-100px;overflow-y:scroll;overflow-x:hidden;">

<form action="', htmlentities($_SERVER['PHP_SELF']), '?view=exhibits&set=',$set,'&mode=coverchanged" method="post" enctype="multipart/form-data">
    <span style="font-size:14px;">
    Exhibit Name:&nbsp;&nbsp; <input name="caption" value="',$settitle,'">
    <br />
    About this Exhibit:&nbsp;
    <br />
    <textarea style="width:380px;" rows="4" cols="60" name="aboutset">',stripslashes($aboutset),'</textarea>
    <br />
    Change Exhibit Cover Photo (choose one):
    <br /><br />';
    $allusersphotos2 = "SELECT * FROM photos WHERE emailaddress = '$email' AND set_id = '$set'";
    $allusersphotosquery2 = mysql_query($allusersphotos2);
    $usernumphotos2 = mysql_num_rows($allusersphotosquery2);

    for($iii = 0; $iii < $usernumphotos2; $iii++) {
        $userphotosource[$iii] = mysql_result($allusersphotosquery2, $iii, "source");
        $userphotosset[$iii] = mysql_result($allusersphotosquery2, $iii, "sets");
        $userphotoscaption[$iii] = mysql_result($allusersphotosquery2, $iii, "caption");
        $newsource = str_replace("userphotos/","userphotos/thumbs/", $userphotosource[$iii]);
        if($userphotosset[$iii] == $set) {
            echo'<img src="',$newsource,'" height="70" width="70" />&nbsp;&nbsp;<input type="checkbox" name="addthis" value="',$userphotosource[$iii],'" checked />&nbsp;"',$userphotoscaption[$iii],'"
    <br /><br />'; }
        else {
            echo'<img src="https://photorankr.com/',$newsource,'" height="70" width="70" />&nbsp;&nbsp;<input type="checkbox" name="addthis" value="',$userphotosource[$iii],'" />&nbsp;"',$userphotoscaption[$iii],'"
        <br /><br />'; }
        
    } //end of for loop
    
    echo'
    </span>
    <div>
    <button style="float:left;" class="btn btn-success" type="submit">Save Info</button>
    </form>
    <div style="float:left;margin-left:180px;"><a class="btn btn-danger" href="myprofile.php?view=exhibits&set=',$set,'&mode=deleteexhibit">Delete Exhibit</a></div>
    </div>
    
    </div>
    </div>
    </div>
    </div>';
    
?>

<body style="overflow-x:hidden; background-color:#fff;">

<?php navbar(); ?>
    
    <!------------------------WHITE TOP HALF------------------------>
    <div class="tophalf">
        <div class="container_24" style="width:1120px;position:relative;left:30px;">
            
            <!------------------------PROFILE PICTURE------------------------>    
            <div class="profileBox">
                <div id="profilePicture">
                    <img src="https://photorankr.com/<?php echo $profilepic ?>" />
                </div>
                <div id="nameLabel">
                    <header><span style="font-weight:normal;font-size:17px;"><?php echo $reputation; ?></span> <?php echo $fullname ?></header>
                </div>
                <div id="followBlock">
                    <a class="buttonNew" style="text-decoration:none;color:#000;width:100px;"><img style="width:15px;margin:-5px 4px 0px 2px;" src="graphics/tick 2.png" />Follow</a>
                    <a class="buttonNew" style="color:#000;width:100px;text-decoration:none;"><img style="width:15px;margin:-5px 4px 0px 2px;" src="graphics/comment_1.png" />Message</a>
                </div>
            </div>
            
        <div class="profileRightSide">
            <!------------------------STATS BOXES------------------------>   
            <div class="topHalfBlock">
                <div id="bioText">
                    <header>About Me</header>
                    <img style="width:12px;margin:-10px 4px 0px 2px;" src="graphics/quote 2.png" />
                    <?php echo $bio; ?>
                    <img style="width:12px;margin:-10px 4px 0px 4px;" src="graphics/quote 1.png" />
                </div>
            </div>
            
            <div class="topHalfBlock">
                <div id="bioText" style="padding-left:12px;">
                    <header> Reputation </header>
                    <ul>
                        <li><span id="statTxt"><img style="width:12px;" src="graphics/rep_i.png" /> Rep: <?php echo $reputation; ?></span></li>
                        <li><span id="statTxt"><img style="width:12px;" src="graphics/camera.png"> Photos: <?php echo $numphotos; ?></span></li>
                        <li><span id="statTxt"> <img style="width:12px;" src="graphics/rank_prof.png"> Avg Score: <?php echo $portfolioranking; ?></span></li>
                        <li><span id="statTxt"> <img style="width:14px;" src="graphics/eye.png"> Views: <?php echo $profileviews; ?></span></li>
                        <li><span id="statTxt"> <img style="width:20px;margin-left:-8px;" src="graphics/network_i.png"> Followers: <?php echo $numberfollowers; ?></span></li>
                    </ul>
                </div>
            </div>
            
           <!---<div class="topHalfBlock">
                <div id="bioText" style="padding:5px 0px 5px 0px;">
                    <header>My Network</header>
                    <?php
                    
                        $followersquery="SELECT * FROM userinfo WHERE following LIKE '%$email%' ORDER BY reputation DESC";
                        $followingquery=mysql_query($followersquery);
                        $numberfollowing = mysql_num_rows($followingquery);
                        
                        for($iii = 0; $iii < $numberfollowing; $iii++) {
                            $followingpic = mysql_result($followingquery, $iii, "profilepic");
                            $followingid = mysql_result($followingquery, $iii, "user_id");
		
                            echo '   
                            <div style="width:52px;height:52px;overflow:hidden;float:left;"><a style="text-decoration:none;" href="http://photorankr.com/viewprofile.php?u=',$followingid,'">
                            <img onmousedown="return false" oncontextmenu="return false;" style="min-height:52px;min-width:52px;padding:1px;" src="http://www.photorankr.com/',$followingpic,'" width="50" /></a></div>';
        
                        }
                      
                    ?>
                </div>
            </div>--->
            
            <!-------<div class="topHalfBlock" style="width:197px;">
                <div id="bioText" style="padding:5px 0px 5px 0px;">
                    <header>My Store</header>
                    <?php
                        $storephotos = mysql_query("SELECT source,id,caption,price FROM photos WHERE emailaddress = '$email' AND price != '.00' ORDER BY points DESC LIMIT 0,6");
                        $numphotos = mysql_num_rows($storephotos);
        
                        if($numphotos == 6) {
                            for($ii=0;$ii<=5;$ii++) {
                                $source = mysql_result($storephotos,$ii,'source');
                                $source = str_replace('userphotos','userphotos/medthumbs/',$source);
                                $price = mysql_result($storephotos,$ii,'price');
                                $price = number_format($price,0);
                                $caption = mysql_result($storephotos,$ii,'caption');
                                $caption = (strlen($caption) > 18) ? substr($caption,0,15). " &#8230;" : $caption;      

                                $imageid = mysql_result($storephotos,$ii,'id');

                                echo'<li style="list-style-type:none;">
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
            </div>----------->
            
            <div class="topHalfBlock" style="width:265px;">
                <div id="activityText">
                    <header>Activity</header>
                    <ul>
                    <?php
                     for($iii = 0; $iii < 12; $iii++) {
            
            $type = mysql_result($activityquery,$iii,'type');
            $id = mysql_result($activityquery,$iii,'id');
            $owner = mysql_result($activityquery,$iii,'owner');
            $commenter = mysql_result($activityquery,$iii,'emailaddress');
            $commentimageid = mysql_result($activityquery,$iii,'imageid');
            $time = mysql_result($activityquery,$iii,'time');
            
            $getcommentid = mysql_query("SELECT comment FROM comments WHERE id = '$commentimageid'");
            $comment = mysql_result($getcommentid,0,'comment');
            
            $source = mysql_result($activityquery,$iii,'source');
            
            $commentphotoquery = mysql_query("SELECT source FROM photos WHERE (id = '$source' or source = '$source')");
            $commentphoto = mysql_result($commentphotoquery,0,'source');
                                
            $commentphotosource = str_replace("userphotos/","userphotos/medthumbs/", $commentphoto);
            
            $getimageid = mysql_query("SELECT id FROM photos WHERE source = '$source'");
            $sourceid = mysql_result($getimageid,0,'id');
            list($width,$height) = getimagesize($commentphoto);
            $newwidth = $width/3.2;
            $newheight = $height/3.2;
            
            if($newwidth < 195) {
                $newheight = $newheight * ($newheight/$newwidth);
                $newwidth = 250;
            }

            $newsemail = mysql_result($activityquery,$iii,'emailaddress');
            $caption = mysql_result($activityquery,$iii,'caption');
            $followemail = mysql_result($activityquery,$iii,'following');
            
            $following = mysql_query("SELECT user_id,firstname,lastname,emailaddress,profilepic FROM userinfo WHERE emailaddress = '$followemail'");
            $ownerid = mysql_result($following,0,'user_id');
            $followername = mysql_result($following,0,'firstname') ." ". mysql_result($following,0,'lastname');
            $followpic = mysql_result($following,0,'profilepic');
            if($followpic == "") {
                $followpic = "profilepics/default_profile.jpg";
            }
            
            $commenter = mysql_query("SELECT user_id,firstname,lastname,emailaddress,profilepic FROM userinfo WHERE emailaddress = '$commenter'");
            $commenterid = mysql_result($commenter,0,'user_id');
            $commentername = mysql_result($commenter,0,'firstname') ." ". mysql_result($commenter,0,'lastname');
            $commenterpic = mysql_result($commenter,0,'profilepic');
            if($commenterpic == "") {
                $commenterpic = "profilepics/default_profile.jpg";
            }
            
            $cnquery = mysql_query("SELECT user_id,firstname,lastname FROM userinfo WHERE emailaddress = '$owner'");
            $cn = mysql_result($cnquery,0,'firstname') ." ". mysql_result($cnquery,0,'lastname');
            $cnid = mysql_result($cnquery,0,'user_id');
            
            $followerpics = mysql_query("SELECT id,source FROM photos WHERE emailaddress = '$followemail' ORDER BY (points) DESC LIMIT 0,4");
            $numprofilepics = mysql_num_rows($followerpics);
            $profileimage = mysql_result($followerpics,0,'source'); 
            $profileimage = str_replace('userphotos/','userphotos/thumbs/',$profileimage);
            $profileimage2 = mysql_result($followerpics,1,'source');
            $profileimage2 = str_replace('userphotos/','userphotos/thumbs/',$profileimage2);
            $profileimage3 = mysql_result($followerpics,2,'source');
            $profileimage3 = str_replace('userphotos/','userphotos/thumbs/',$profileimage3);
            $profileimage4 = mysql_result($followerpics,3,'source');
            $profileimage4 = str_replace('userphotos/','userphotos/thumbs/',$profileimage4);
    
                        
                if($type == 'photo') {
                    
                   echo'<li class="activityBox">

                    <div style="width:100%;"><div style="float:left;height:60px;"><img style="max-height:40px;" src="https://photorankr.com/',$commenterpic,'" /></div>&nbsp;&nbsp;<div style="float:left;padding-left:8px;width:180px;"><img src="graphics/upload.png" width="25" />&nbsp;&nbsp;',$commentername,' uploaded "',$caption,'"
                    
                    <div style="color:#555;font-weight:500;margin-left:0px;">';if($time > 0) {echo'',converttime($time),'';} echo'</div> 

                    </div>
                    </div>
                    
                    <a href="fullsize.php?imageid=',$sourceid,'"><img src="https://photorankr.com/',$source,'" width="190" /></a>
                    </li>';
               
                }
                
                elseif($type == 'follow') {
                
                        
                
                      echo'<li class="activityBox">
                      
                        <div style="width:100%;"><div style="float:left;height:60px;"><img style="max-height:40px;" src="https://photorankr.com/',$profilepic,'" /></div>&nbsp;&nbsp;<div style="float:left;padding-left:8px;width:180px;"><img src="https://photorankr.com/graphics/follower.png" width="35" />&nbsp;&nbsp;<a href="viewprofile.php?u=',$cnid,'">',$firstname,' ',$lastname,'</a> followed <a href="viewprofile.php?u=',$ownerid,'"><img style="padding:3px;max-height:25px;" src="https://photorankr.com/',$followpic,'" /></a> <a href="viewprofile.php?u=',$ownerid,'">',$followername,'</a>
                     
                    <div style="clear:both;color:#555;font-weight:500;">';if($time > 0) {echo'',converttime($time),'';} echo'</div>

                     </div>
                     </div>
                            
                    
                     <div style="width:240px;">';
                    if($numprofilepics > 3){echo'<img style="padding:3px;" src="https://photorankr.com/',$profileimage,'" height="110" width="110" /><img style="padding:3px;" src="https://photorankr.com/',$profileimage2,'" height="110" width="110" /><img style="padding:3px;" src="https://photorankr.com/',$profileimage3,'" height="110" width="110" /><img style="padding:3px;" src="https://photorankr.com/',$profileimage4,'" height="110" width="110" />';}
                    echo'</div>
                     
                     </li>
                     <br />';
                    
                }
                
                elseif($type == 'comment') {
                    
                     echo'<li class="activityBox">
                     
                         <div style="width:100%;"><div style="float:left;height:60px;"><img style="max-height:40px;" src="https://photorankr.com/',$commenterpic,'" /></div>&nbsp;&nbsp;<div style="float:left;padding-left:8px;width:180px;"><img src="https://photorankr.com/graphics/comment.png" width="25" />&nbsp;&nbsp;<a href="viewprofile.php?u=',$commenterid,'">',$commentername,'</a> commented on <a href="viewprofile.php?u=',$cnid,'">',$cn,'\'s</a> photo
                    
                         <div style="color:#555;font-weight:500;">';if($time > 0) {echo'',converttime($time),'';} echo'</div>

                    </div>
                    </div>
                    
                    <a href="fullsize.php?imageid=',$sourceid,'">
                    
                    <img src="https://photorankr.com/',$commentphotosource,'" width="190" />                    
                    </a>';
                    
                    if($comment) {
                    echo'
                    <div style="font-size:15px;width:220px;padding:10px;margin-top:20px;">"',$comment,'"</div>';
                    }
                    
                    echo'
                    </li>
                    <br />';
                
                
                }
                
                elseif($type == "fave") {
                
                    echo'<li class="activityBox">
                    
                        <div style="width:100%;"><div style="float:left;height:60px;"><img style="max-height:40px;" src="https://photorankr.com/',$commenterpic,'" /></div>&nbsp;&nbsp;
                        <div style="font-size:14px;float:left;padding-left:8px;width:180px;"><img src="https://photorankr.com/graphics/fave.png" width="20" />&nbsp;&nbsp;<a href="viewprofile.php?u=',$commenterid,'">',$commentername,'</a> favorited <a href="viewprofile.php?u=',$cnid,'">',$cn,'\'s </a> photo
                    
                    <div style="color:#555;font-weight:500;margin-left:0px;">';if($time > 0) {echo'',converttime($time),'';} echo'</div> 

                    </div>
                    </div>
                    
                    <a href="fullsize.php?imageid=',$sourceid,'"><img style="padding:10px;"src="https://photorankr.com/',$source,'" width="150" /></a>
                    
                    </li>';
                
                }
        
        } //end of for loop
                    
                    ?>
                    </ul>
                </div>
            </div>
            
            
             <div class="topHalfBlock" style="width:280px;">
                <div id="bioText" style="overflow-y:scroll;">
                    <header>Post an Update</header>
                        <form action="#" name="commentForm" method="post"> 
                        <textarea id="status"  style="margin-left:5px;margin-top:-5px;width:248px;height:50px;" placeholder="What's new with your photography?"></textarea>
                        <a class="buttonNew" href="#" id="submitStatus" style="clear:both;text-decoration:none;color:#000;width:60px;float:right;margin:6px;margin-right:4px;"><img style="width:15px;margin:-5px 4px 0px 2px;" src="graphics/arrow 6.png" />Post</a>
                        </form>
                        
                        <!--AJAX COMMENTS-->
                        <div class="float:left;"> 
                            <ol id="update" class="timeline">
                            </ol>
                        </div>
                        
                        <!---Previous Statuses--->
                        <?php
                        $getStatuses = mysql_query("SELECT * FROM statuses WHERE emailaddress = '$email' ORDER BY id DESC LIMIT 6");
                        $numstatuses = mysql_num_rows($getStatuses);
                        for($ii=0; $ii < $numstatuses; $ii++) {
                            $status = mysql_result($getStatuses,$ii,'status');
                            $statustime =  mysql_result($getStatuses,$ii,'time');
                            $statustime = converttime($statustime);
                            echo'<div class="status">
                                    <img style="width:15px;margin:-5px 4px 0px 2px;" src="graphics/comment_1.png" />',$status,'
                                    <span style="float:right;font-weight:500;color:#666;font-size:11px;padding-right:2px;">',$statustime,'</span>
                                 </div>';
                        }
                        ?>
                </div>
            </div>

        
        </div><!---end of right side profile-->
        
        <!---------------------NAV ELEMENTS----------------->
         <div class="profileBottomNav">
            <ul>
              <a href="profile.php"><li id="hideViews"><li id="showViews"><?php if($view == '') {echo'<div class="oval"><img style="width:15px;padding-bottom:5px;" src="graphics/grid.png" /> Portfolio</div>';} else {echo'<img style="width:15px;padding-bottom:5px;" src="graphics/grid.png" /> Portfolio';} ?></li></a>
                <a href="profile.php?view=store"><li id="hideViews"><?php if($view == 'store') {echo'<div class="oval"><img style="width:15px;padding-bottom:5px;" src="graphics/tag.png" /> Store</div>';} else {echo'<img style="width:15px;padding-bottom:5px;" src="graphics/tag.png" /> Store';} ?></li></a>
                <a href="profile.php?view=faves"><li id="hideViews"><?php if($view == 'faves') {echo'<div class="oval"><img style="width:16px;padding-bottom:5px;" src="graphics/heart.png" /> Favorites</div>';} else {echo'<img style="width:16px;padding-bottom:5px;" src="graphics/heart.png" /> Favorites ';} ?></li></a>
               <a href="profile.php?view=network"> <li id="hideViews"><?php if($view == 'network') {echo'<div class="oval"><img style="width:15px;padding-bottom:5px;" src="graphics/user.png" /> Network</div>';} else {echo'<img style="width:15px;padding-bottom:5px;" src="graphics/user.png" /> Network';} ?></li></a>
                <a href="profile.php?view=about"><li id="hideViews"><?php if($view == 'about') {echo'<div class="oval"><img style="width:7px;padding-bottom:5px;" src="graphics/info.png" /> About</div>';} else {echo'<img style="width:7px;padding-bottom:5px;" src="graphics/info.png" /> About';} ?></li></a>
               <a href="profile.php?view=messages"><li id="hideViews"><?php if($view == 'messages') {echo'<div class="oval"><img style="width:15px;padding-bottom:5px;" src="graphics/list 1.png" /> Messages</div>';} else {echo'<img style="width:15px;padding-bottom:5px;" src="graphics/list 1.png" /> Messages';} ?></li></a>
            </ul>
         </div>

       </div> 
    </div>
    
    <!-----------------------PORTFOLIO BOTTOM HALF------------------------>
    <div class="container_24" style="width:1120px;position:relative;left:30px;">
        <!--determine where arrow should be placed based on the view--->
        <div class="upArrow" <?php if($view == '') {echo'style="left:400px;"';} 
                                   elseif($view == 'store') {echo'style="left:415px;"';} 
                                   elseif($view == 'network') {echo'style="left:485px;"';} 
                                   elseif($view == 'about') {echo'style="left:560px;"';} 
                                   elseif($view == 'messages') {echo'style="left:620px;"';} 
        ?>></div>
        
        <!-------Hidden box for portfolio views
        <div id="portfolioViews">test</div>--------->
        
        <!--------------------------Portfolio View---------------------------->
        <?php
        if($view == '') {   
        
        echo'<div class="portfolioDrop">
                <ul>
                    <li><a href="profile.php">Newest Photos</a></li>
                    <li><a href="profile.php?option=top">Top Ranked Photos</a></li>
                    <li><a href="profile.php?option=fave">Most Favorited</a></li>
                    <li><a href="profile.php?view=exhibits">Exhibits</a></li>
                </ul>
             </div>';
              
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
    <div id="thepics" style="position:relative;left:-60px;top:10px;width:1250px;">
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
    $widthls = 280;
    }

		echo '
        <a style="text-decoration:none;color:#333;" href="fullsize.php?imageid=',$id,'&v=n"><li class="fPic" id="',$id,'" style="list-style-type: none;width:280px;"><img style="min-width:280px;" src="https://photorankr.com/',$imageThumb,'" height="',$heightls,'px" width="',$widthls,'px" />
        
          <div class="statoverlay" style="z-index:1;background-color:white;position:relative;top:0px;width:280px;height:30px;"><div style="line-spacing:1.48;padding:5px;color:#4A4A4A;"><div style="float:left;"<span style="font-size:16px;font-weight:bold;">',$score,'</span>&nbsp;&nbsp;<span style="font-weight:bold;font-size:12px;">',$caption,'</span></div></div><br/></div>
';       	
            
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
        offset: 10, // Optional, the distance between grid items
        itemWidth: 280 // Optional, the width of a grid item
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
   <div class="grid_6 push_9" style="top:150px;">
   <div id="loadMorePics" style="display: none; text-align: center;font-family:arial,helvetica neue; font-size:15px;"><img src="graphics/load.gif" /></div>
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
    } //end portfolio view
    
        ?>
        
        
         <!--------------------------Store View---------------------------->
        <?php
        if($view == 'store') {  
        
        echo'<div style="width:1180px;overflow:hidden;position:relative;left:-40px;top:8px;">';
              
        if($option == '') {
            $query = mysql_query("SELECT * FROM photos WHERE emailaddress = '$email' ORDER BY id DESC");
        }
        elseif($option == 'faved') {
            $query = mysql_query("SELECT * FROM photos WHERE emailaddress = '$email' ORDER BY faves DESC");
        }
        elseif($option == 'top') {
            $query = mysql_query("SELECT * FROM photos WHERE emailaddress = '$email' ORDER BY (points/votes) DESC");
        }
        elseif($option == 'sold') {
            $query = mysql_query("SELECT * FROM photos WHERE emailaddress = '$email' AND sold = 1 ORDER BY (points/votes) DESC");
        }
            $numresults = mysql_num_rows($query);
        
    echo'
    <div id="thepics" style="float:left;top:10px;width:910px;">
    <div id="main">
    <ul id="tiles">';

        for($iii=0; $iii < $numresults && $iii < 15; $iii++) {
              
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
    $widthls = 280;
    }

		echo '
        <a style="text-decoration:none;color:#333;" href="fullsizemarket.php?imageid=',$id,'&v=n"><li class="fPic" id="',$id,'" style="list-style-type: none;width:280px;"><img style="min-width:280px;" src="https://photorankr.com/',$imageThumb,'" height="',$heightls,'px" width="',$widthls,'px" />
        
            <div class="statoverlay" style="z-index:1;background-color:rgba(0,0,0,.8);position:relative;top:0px;width:280px;height:30px;"><div style="line-spacing:1.48;padding:5px;color:white;"><div style="float:left;"<span style="font-size:18px;font-weight:100;">',$score,'</span>&nbsp;&nbsp;<span style="font-weight:100;font-size:16px;">',$caption,'</span></div><div style="float:right;"><span style="font-size:13px;">',$price,'</span></div></div><br/></div>';       	
            
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
        offset: 10, // Optional, the distance between grid items
        itemWidth: 280 // Optional, the width of a grid item
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
   <div class="grid_6 push_9" style="top:150px;">
   <div id="loadMorePics" style="display: none; text-align: center;font-family:arial,helvetica neue; font-size:15px;"><img src="graphics/load.gif" /></div>
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
    
        
    //Right Sidebar of Stats

echo'<div class="grid_6 filled rounded shadow" style="float:left;width:240px;">';
        
        echo'<div class="cartText" style="padding:10px;">Your Store</div>';

         //Search the Store
        echo'<div class="grid_6" style="">
            <form method="GET">
                <input id="searchStore" name="searchword" placeholder="Search your store&hellip;" type="text" />
            </form>
        </div>';
        
        echo'
            <ul>
                <li id="stattitle"># Photos: <span id="stat">',$numphotos,'</span></li>
                <li id="stattitle">Avg. Photo Price: <span id="stat">$',$avgprice,'</span></li>
                <li id="stattitle">Avg. Portfolio Score: <span id="stat">',$portfolioranking,'</span></li>
                <li id="stattitle">Photos Sold: <span id="stat">',$portfoliosold,'</span></li>
                <li id="stattitle">Photo Views: <span id="stat">',$portfolioviews,'</span></li>

                <li id="stattitle">Avg. Resolution: <span id="stat">',$avgwidth,' X ',$avgheight,'</span></li>
        </div>';
        
    //Store Filters
     echo'<div class="grid_6" style="float:left;width:240px;">';
        if($option== '') {
            echo'<a style="text-decoration:none;" href="profile.php?view=store"><div class="rectangle"><div style="padding-top:7px;text-align:center;">Newest</div><div class="arrow-leftStore" style="float:right;margin-right:288px;margin-top:-21px;"></div></div></a>';
        }
        
        else {
             echo'<a style="text-decoration:none;" href="profile.php?view=store"><div class="rectangle"><div style="padding-top:7px;text-align:center;">Newest</div></div></a>';
        }
        
        if($option == 'faved') {
            echo'<a style="text-decoration:none;" href="profile.php?view=store&option=faved"><div class="rectangle"><div style="padding-top:7px;text-align:center;">Most Favorited</div><div class="arrow-leftStore" style="float:right;margin-right:288px;margin-top:-21px;"></div></div></a>';
        }
        
        else {
             echo'<a style="text-decoration:none;" href="profile.php?view=store&option=faved"><div class="rectangle"><div style="padding-top:7px;text-align:center;">Most Favorited</div></div></a>';
        }
        
        if($option == 'top') {
            echo'<a style="text-decoration:none;" href="profile.php?view=store&option=top"><div class="rectangle"><div style="padding-top:7px;text-align:center;">Top Ranked</div><div class="arrow-leftStore" style="float:right;margin-right:288px;margin-top:-21px;"></div></div></a>';
        }
        
        else {
             echo'<a style="text-decoration:none;" href="profile.php?view=store&option=top"><div class="rectangle"><div style="padding-top:7px;text-align:center;">Top Ranked</div></div></a>';
        }
        
        if($option == 'sold') {
            echo'<a style="text-decoration:none;" href="profile.php?view=store&option=sold"><div class="rectangle"><div style="padding-top:7px;text-align:center;">Sold</div><div class="arrow-leftStore" style="float:right;margin-right:288px;margin-top:-21px;"></div></div></a>';
        }
        
        else {
             echo'<a style="text-decoration:none;" href="profile.php?view=store&option=sold"><div class="rectangle"><div style="padding-top:7px;text-align:center;">Sold</div></div></a>';
        }
        
    echo'</div>';


            echo'</div>
                 </div>';

        } //end of view == 'store'
                
    ?>
    
    
    <!--------------------------Favorites View---------------------------->
        <?php
        if($view == 'faves') {   
              
        $favesquery = "SELECT faves FROM userinfo WHERE emailaddress='$email' LIMIT 0, 1";
        $favesresult = mysql_query($favesquery) or die(mysql_error());
        $faves = mysql_result($favesresult, 0, "faves");
        
        $query = mysql_query("SELECT * FROM photos WHERE source IN ($faves) ORDER BY FIELD (source, $faves) DESC LIMIT 9");
        $numresults = mysql_num_rows($query);
                
    echo'
    <div id="thepics" style="position:relative;left:-60px;top:10px;width:1250px;">
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
    $widthls = 280;
    }

		echo '
        <a style="text-decoration:none;color:#333;" href="fullsize.php?imageid=',$id,'&v=n"><li class="fPic" id="',$id,'" style="list-style-type: none;width:280px;"><img style="min-width:280px;" src="https://photorankr.com/',$imageThumb,'" height="',$heightls,'px" width="',$widthls,'px" />
        
            <div class="statoverlay" style="z-index:1;background-color:rgba(0,0,0,.8);position:relative;top:0px;width:280px;height:30px;"><div style="line-spacing:1.48;padding:5px;color:white;"><div style="float:left;"<span style="font-size:18px;font-weight:100;">',$score,'</span>&nbsp;&nbsp;<span style="font-weight:100;font-size:16px;">',$caption,'</span></div><div style="float:right;"><span style="font-size:13px;">',$price,'</span></div></div><br/></div>';       	
            
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
        offset: 10, // Optional, the distance between grid items
        itemWidth: 280 // Optional, the width of a grid item
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
   <div class="grid_6 push_9" style="top:150px;">
   <div id="loadMorePics" style="display: none; text-align: center;font-family:arial,helvetica neue; font-size:15px;"><img src="graphics/load.gif" /></div>
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
    
    } //end favorites view
    
    ?>

     <!--------------------------Messages View---------------------------->
    <div style="top:10px;width:1160px;">
    <?php
    if($view == 'messages') {
    
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
	
		echo '<div class="uploadBox" style="width:330px;float:left;margin-top:20px;">';

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
                     <div style="font-size:24px;font-weight:300;text-align:center;padding:10px 15px 10px 15px;">Your Conversations</div>
                        <p>(Contact photographers through the "contact" tab in their profile)</p><br /><br />';

		for($iii=0; $iii<$numberofmessages; $iii++) {
			$otherspic = mysql_result($moreinforesult, $iii, "profilepic");
			$othersfirst = mysql_result($moreinforesult, $iii, "firstname");
			$otherslast = mysql_result($moreinforesult, $iii, "lastname");
			$currentthread = mysql_result($messageresult, $iii, "thread");

			//now lets display the message with the other's profile picture and name
			echo '
			<a href="profile.php?view=messages&v=viewthread&thread=', $currentthread, '" style="text-decoration:none;color:#333;">
            <div class="messageBox" style="margin-bottom:20px; font-family: helvetica neue; font-size:13px;color:#333;line-height:18px;">
                <div style="float:left;padding:2px 10px 4px 4px;">
                    <img src="https://photorankr.com/',$otherspic, '" width="60px" height="60px" alt="profile picture" style="margin-bottom: 5px;"/>
                </div>
                <div style="width:300px;">
                    <span style="font-size:16px;">',$othersfirst, ' ', $otherslast,'</span><br />
                    ',$currentmessage[$iii],'
                </div>
			</div>
			</a>';
		}

		echo '</div>';
	}
    
        echo'<div style="float:left;width:600px;">';
        
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

//get last thread
$thread = mysql_real_escape_string(htmlentities($_GET['thread']));
if(!$thread) {
    $getlastthread = mysql_query("SELECT thread FROM messages WHERE sender = '$email' OR receiver = '$email' ORDER BY id DESC LIMIT 1");
    $thread = mysql_result($getlastthread,0,'thread');    
}

	//if no thread was sent, tell them no thread found
	if(!thread) {
            echo '<div style="margin-left: 480px; margin-top: -300px;">No thread found!</div></div>';
	}
    
	//otherwise there is a thread
	else {
		//select all the messages that match the thread number
		$threadquery = "SELECT * FROM messages WHERE thread = '$thread' ORDER BY id DESC LIMIT 0, 20";
		$threadresult = mysql_query($threadquery) or die(mysql_error());
		$numberofmessages = mysql_num_rows($threadresult);
		//if this returns zero messages, then tell them no thread found
		if($numberofmessages == 0) {
			echo '<div style="margin-left: 480px; margin-top: -300px;">No thread found!</div></div>';
		}
		//otherwise there were messages found
		else {
			echo '</div>';
	
			echo '<div class="grid_12" style="width:620px;background-color:rgba(0,0,0,.05);padding-left:10px;padding-right:90px;margin-top:20px;padding-bottom:20px;padding-top:20px;-webkit-box-shadow: 0 1px 3px rgba(0, 0, 0, 0.45), inset 0 -1px 1px #666;
-moz-box-shadow: 0 1px 3px rgba(0, 0, 0, 0.45), inset 0 -1px 1px #666;
box-shadow: 0 1px 3px rgba(0, 0, 0, 0.45), inset 0 -1px 1px #666;
-webkit-border-radius: 4px;
-moz-border-radius: 4px;
border-radius: 4px;">';

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
				<div class="grid_17 message" style="margin-bottom: 20px; font-family: arial;font-size:14px;font-weight:300;color:#333;line-height:18px;border-bottom:1px solid #aaa;">
					<a href="viewprofile.php?u=',$currentuserid,'">
					<div class="grid_3">
						<img src="https://photorankr.com/', $currentpic, '" width="60px" height="60px" alt="profile_picture" style="margin-bottom: 5px;"/><br />',$currentfirst,' ', $currentlast,' 
					</div>
					</a>
					<div class="grid_12" style="margin-top: -55px; margin-left: 120px;">',$currentmessage,'
					</div>
				</div>';			
			}

			//now let's display the box from which they can send a message
			echo' <div class="grid_12" style="font-size: 20px; font-family: Helvetica Neue, Helvetica, Arial, sans-serif;
			line-height: 28px; color: #333333;">
    
			<span style="font-size:16px;">Reply:</span>
			<form method="post" action="replymessage.php" />
			<textarea cols="50" rows="4" style="width:675px" name="message"></textarea>
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
        
        echo'</div>';
                  
}  //end of view messages

    ?>
    
    
    <?php
      /*--------------------------Network View----------------------------*/
      if($view == 'network') {
      
          echo'<div class="portfolioDrop">
                <ul>
                    <li><a href="profile.php?view=network">Following</a></li>
                    <li><a href="profile.php?view=network&option=followers">Followers</a></li>
                </ul>
             </div>';
      
         if($option == '') {
            $query = mysql_query("SELECT following FROM userinfo WHERE emailaddress = '$email'");
            $followinglist = mysql_result($query, 0, "following");
            $followingquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress IN ($followinglist)");
            $numberfollowing = mysql_num_rows($followingquery);
        }
        elseif($option == 'followers') {
            $followersquery="SELECT * FROM userinfo WHERE following LIKE '%$email%'";
            $followingquery=mysql_query($followersquery);
            $numberfollowing = mysql_num_rows($followingquery);
        }

        echo'<div style="width:1150px;">';
        
            for($iii = 0; $iii < $numberfollowing; $iii++) {
                $followingpic = mysql_result($followingquery, $iii, "profilepic");
                $followingfirst = mysql_result($followingquery, $iii, "firstname");
                $followinglast = mysql_result($followingquery, $iii, "lastname");
                $fullname = $followingfirst . " " . $followinglast;
                $fullname = ucwords($fullname);
                $followingid = mysql_result($followingquery, $iii, "user_id");
                
                echo '   

                <div style="width:215px;height:245px;overflow:hidden;float:left;margin-left:10px;margin-top:30px;"><a style="text-decoration:none;" href="http://photorankr.com/viewprofile.php?u=',$followingid,'">

                <img id="roundCorners" onmousedown="return false" oncontextmenu="return false;" style="   min-height:215px;min-width:215px;" src="http://www.photorankr.com/',$followingpic,'" height="215" width="215" /></a>
                
                 <div class="statoverlay"><div style="line-spacing:1.48;padding:5px;color:#4A4A4A;"><div style="float:left;"<span style="font-size:16px;font-weight:bold;">',$score,'</span>&nbsp;&nbsp;<span style="font-weight:bold;font-size:12px;">',$fullname,'</span></div></div><br/></div>       	

                
                </div>';
            
            }
        
        echo'</div>';
      }
    ?>
    
    
    <?php
      /*--------------------------Upload View----------------------------*/
      if($view == 'upload') {
        echo'<div style="width:1150px;">
            
            <!--Drop Down-->
             <div class="portfolioDrop">
                <ul>
                    <li><a href="profile.php?view=upload">Single Upload</a></li>
                    <li><a href="profile.php?view=upload&option=batch">Batch Upload</a></li>
                    <li><a href="profile.php?view=upload&option=newexhibit">Create Exhbit</a></li>
                </ul>
             </div>';
            
                $option = htmlentities($_GET['option']);    
                $set = htmlentities($_GET['cs']); 
                $collection = htmlentities($_GET['cc']); 
                        
                if($option == '') {
                
                        //Display upload Boxes
                        echo'<div class="grid_6">
                                <div class="uploadBox" style="margin-top:40px;">
                                    <span id="bigUploadText">1.</span>
                                    <header>Choose a photo to upload</header> 
                                    <p><u>You retain all copyrights to your images.</u> A watermark is provided to protect your photos. Only photos that are of a base resolution 1200 X 1600 or higher and do not have a watermark will be placed on the PhotoRankr Market. </p>
                                </div>
                                <div class="uploadBox" style="margin-top:40px;">
                                    <span id="bigUploadText">2.</span>
                                    <header>Pick a few tags</header> 
                                    <p>By tagging your photos with relevant tags the chances of your work will appear more frequently in market searches. By choosing the appropriate category and style of photo, it will make sure your photos is cateogrized properly for buyers and other photographers. </p>
                                </div>
                                <div class="uploadBox" style="margin-top:40px;">
                                    <span id="bigUploadText">3.</span>
                                    <header>Name your price</header> 
                                    <p>Choose a price and license for your photo. If you do not wish to sell your work, you may also choose such.</p>
                                </div>
                             </div>';
                        
                        //select all sets associated with user email
                        $setsemail = $_SESSION['email'];
                        $setsquery = "SELECT * FROM sets WHERE owner = '$setsemail'";
                        $setsqueryrun = mysql_query($setsquery);
                        $setscount = mysql_num_rows($setsqueryrun);

                        //upload a photo
                        if (htmlentities($_GET['action']) == "uploadsuccess") { 
                        
                                $lastupload = mysql_query("SELECT id,source FROM photos WHERE emailaddress = '$email' ORDER BY id DESC LIMIT 1");
                                $lastphotoid = mysql_result($lastupload,0,'id');
                                $lastphotosource = mysql_result($lastupload,0,'source');
                                $lastphotocaption = mysql_result($lastupload,0,'caption');
                                $lastphotosource = str_replace("userphotos/","userphotos/medthumbs/",$lastphotosource);
                                
                                echo '<img style="float:left;padding:10px;margin-left:50px;" src="',$lastphotosource,'" width="100" /><div style="margin-top:20px;margin-left:10px;color:#6aae45;float:left;font-size:16px;font-weight:200;"><strong>Upload Successful</strong>
                                <br />
                                <div style="color:#333;margin-top:5px;">Share this photo?&nbsp;&nbsp;
                                  
                                    <div>
                                              
                                    <div style="float:left;margin-top:5px;">                                                                                                                                                                                                                   
                                    <a name="fb_share" type="button" share_url="https://photorankr.com/fullsizeview.php?imageid=',$lastphotoid,'" href="http://www.facebook.com/sharer.php">Share</a>
<script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script></div>
                                
                                    &nbsp;&nbsp;

                                    <div style="float:left;padding:2px;margin-left:6px;margin-top:5px;"><a href="https://twitter.com/share" class="twitter-share-button" data-url="https://photorankr.com/fullsizeview.php?imageid=',$lastphotoid,'" data-text="Check out my latest PhotoRankr upload!" data-via="PhotoRankr" data-count="none"></a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
                                    </div>

                                    </div>
                                
                                </div>
                                
                                </div><br />';
                                
                                                            
                        }
                        
                        if($set == 'n') {
                        
                            if (htmlentities($_GET['ns']) == "success") { 
                                echo'<br /><br /><span style="margin-top:20px;margin-left:60px;font-size:18px;color:#6aae45"><a href="myprofile.php?view=upload">Add photos to your new exhibit below</a></span><br />';
                            }
    
                            elseif (htmlentities($_GET['ns']) == "failure") { 
                                echo'<br /><br /><span style="margin-top:20px;margin-left:60px;font-size: 18px;color:red;">Please fill out all fields</span><br />';
                            }
    
                            elseif (htmlentities($_GET['ns']) == "name") { 
                                echo'<br /><br /><span style="margin-top:20px;margin-left:60px;font-size: 18px;color:red;">You already have an exhibit titled this</span><br />';
                            }
                        
                        }
                        
                        if($collection == 'n') {
                        
                            if (htmlentities($_GET['ns']) == "success") { 
                                echo'<br /><br /><span style="margin-top:20px;margin-left:60px;font-size:18px;color:#6aae45"><a href="myprofile.php?view=upload">Add photos to your new collection below</a></span><br />';
                            }
    
                            elseif (htmlentities($_GET['ns']) == "failure") { 
                                echo'<br /><br /><span style="margin-top:20px;margin-left:60px;font-size: 18px;color:red;">Please fill out all fields</span><br />';
                            }
    
                            elseif (htmlentities($_GET['ns']) == "name") { 
                                echo'<br /><br /><span style="margin-top:20px;margin-left:60px;font-size: 18px;color:red;">You already have a collection titled this</span><br />';
                            }
                        
                        }

    
                        else if (htmlentities($_GET['action']) == "uploadfailure") {
                                echo '<div style="margin-top:20px;margin-left:60px;color:red;float:left;font-size:20px;font-weight:200;">Please Fill Out All Required Information.</div><br />';
        
                        }
        
        echo'
        <div id="container" class="grid_14 push_3" style="width:700px;margin-top:50px;padding-left:20px;margin-left:10px;font-size:14px;font-weight:300;">
                           
        <div class="span9" style="margin-top:-58px;margin-left:-35px;padding:20px;padding-left:67px;">
        <br />
        <form action="upload_photo3.php" method="post" enctype="multipart/form-data">
        <table class="table">
        <tbody>
    
        <tr>
        <td>Upload Photo:</td>
        <td><input type="file" name="file" /><input type="hidden" name="MAX_FILE_SIZE" value="2500000000" /></td>
        </tr>
        
        <tr>
        <td>Title:</td>
        <td><input style="width:180px;height:20px;" type="text" name="caption" /></td>
        </tr>
        
        <tr>
        <td>Camera:</td>
        <td><input style="width:180px;height:20px;" type="text" value="',$camera,'" name="camera" /></td>
        </tr>

        <tr>
        <td>Location:</td>
        <td><input style="width:180px;height:20px;" type="text" name="age" placeholder="City, State/Province, Country" /></td>
        </tr>

        <tr>
        <td>Keywords:</td>
        <td><input style="width:80px;height:20px;" type="text" name="tag1" />
            <input style="width:80px;height:20px;" type="text" name="tag2" />
            <input style="width:80px;height:20px;" type="text" name="tag3" />
            <input style="width:80px;height:20px;" type="text" name="tag4" /></td>
        </tr>
        
        <tr>
        <td>Style & Category:</td>
        <td>
            <span style="font-size:13px">(Selecting multiple values: Hold down command button if on mac, control button if on PC)</span>
            <br /><br />
            
            <select style="width:150px;height:150px;" multiple="multiple" name="singlecategorytags[]">
            <option value="Advertising">Advertising</option>
            <option value="Aerial">Aerial</option>
            <option value="Animal">Animal</option>
            <option value="Architecture">Architecture</option>
            <option value="Astro">Astro</option>
            <option value="Aura">Aura</option>
            <option value="Automotive">Automotive</option>
            <option value="Botanical">Botanical</option>
            <option value="Candid">Candid</option>
            <option value="Commercial">Commercial</option>
            <option value="Corporate">Corporate</option>
            <option value="Documentary">Documentary</option>
            <option value="Fashion">Fashion</option>
            <option value="Fine Art">Fine Art</option>
            <option value="Food">Food</option>
            <option value="Historical">Historical</option>
            <option value="Industrial">Industrial</option>
            <option value="Musical">Musical</option>
            <option value="Nature">Nature</option>
            <option value="News">News</option>
            <option value="Night">Night</option>
            <option value="People">People</option>
            <option value="Scenic">Scenic</option>
            <option value="Sports">Sports</option>
            <option value="Still Life">Still Life</option>
            <option value="Transportation">Transportation</option>
            <option value="Urban">Urban</option>
            <option value="War">War</option>
            </select>

            <span style="padding-left:70px;">
            <select style="width:150px;height:150px;" multiple="multiple" name="singlestyletags[]">
            <option value="B&W">Black and White</option>
            <option value="Cityscape">Cityscape</option>
            <option value="Fisheye">Fisheye</option>
            <option value="HDR">HDR</option>
            <option value="Illustration">Illustration</option>
            <option value="InfraredUV">Infrared/UV</option>
            <option value="Landscape">Landscape</option>
            <option value="Long Exposure">Long Exposure</option>
            <option value="Macro">Macro</option>
            <option value="Miniature">Miniature</option>
            <option value="Monochrome">Monochrome</option>
            <option value="Motion Blur">Motion Blur</option>
            <option value="Night">Night</option>
            <option value="Panorama">Panorama</option>
            <option value="Photojournalism">Photojournalism</option>
            <option value="Portrait">Portrait</option>
            <option value="Stereoscopic">Stereoscopic</option>
            <option value="Time Lapse">Time Lapse</option>
            </select>
            
            </span>
        </td>
        </tr>
        
        <tr>
        <td>About Photo:</td>
        <td><textarea style="width:500px" rows="4" cols="60" name="about"></textarea></td>
        </tr>

        <tr>
        <td>Add to Exhibit:</td>
        <td>
            <select name="addtoset" onchange="showTags(this.value)" style="width:160px;">
                <option value="" style="display:none;">Choose an exhibit:</option>';
                for($iii=0; $iii < $setscount; $iii++) {
                $settitle = mysql_result($setsqueryrun, $iii, "title");
                echo'<option value="',$settitle,'">',$settitle,'</option>';
            }
            echo'
            </select>
    
            <br />
            <br />
    
            <div id="boxesappear"> </div>
        </td>
        </tr>
    
        
        <tr>
        <td>Marketplace:</td>
        <td>
        
        <input type="radio" name="market" value="forsale" onclick="showSelect();" />&nbsp;&nbsp;For Sale&nbsp;&nbsp;<input style="margin-left:40px;" type="radio" name="market" value="notforsale" onclick="showSelectHide();" />&nbsp;&nbsp;Not For License&nbsp;&nbsp;<input style="margin-left:40px;" type="radio" name="cc" value="cc" onclick="showSelect2();" />&nbsp;&nbsp;Creative Commons&nbsp;
        
        <a href="#" id="popovercc" rel="popover" data-content="Tags help us help you. By selecting various tags for your photos, we can make sure your photos are seen more often in search and on the discovery page. It helps ensure that your photos will always be seen." data-original-title="Creative Commmons?">
        (?)</a>&nbsp;</td>
        </tr>
                
        </tbody>
        </table>
        
        <script>  
            $(function ()  
            { $("#popovercc").popover();  
            });  
        </script>
        
        
        <!--SELECTABLE LICENSES DROPDOWN & OTHER PRICE-->
        
            <script type="text/javascript">
            function showSelect() {
                var select = document.getElementById(\'forsale\');
                select.className = \'show\';
                document.getElementById(\'cc\').className = \'hide\';
            }
            function showSelect2() {
                var select = document.getElementById(\'cc\');
                select.className = \'show\';
                document.getElementById(\'forsale\').className = \'hide\';
            }
            function showSelectHide() {
                var select = document.getElementById(\'forsale\');
                select.className = \'hide\';
                document.getElementById(\'cc\').className = \'hide\';
            }
            function showOtherPrice() {
                if (document.getElementById(\'price\').value == \'Not For Sale\')
                    {
                        document.getElementById(\'otherprice\').className = \'show\';
                    }
                else {
                    document.getElementById(\'otherprice\').className = \'hide\';
                    }
            }
            </script>
            
        <!--FOR SALE-->
        <div id="forsale" class="hide">
        <table class="table">
        <tbody>
        
        <tr>
        <td>Base Price:</td>
        <td>
            <select id="price" name="price" style="width:100px;float:left;" onchange="showOtherPrice()">
            <option value=".00">Free</option>
            <option value=".50">$.50</option>
            <option value=".75">$.75</option>
            <option value="1.00">$1.00</option>
            <option value="2.00">$2.00</option>
            <option value="5.00">$5.00</option>
            <option value="10.00">$10.00</option>
            <option value="15.00">$15.00</option>
            <option value="25.00">$25.00</option>
            <option value="50.00">$50.00</option>
            <option value="100.00">$100.00</option>
            <option value="200.00">$200.00</option>
            <option>Other Price</option>
            </select>
            <div id="otherprice" class="hide" style="float:left;padding-left:20px;"><div class="input-prepend input-append">
                <span class="add-on">$</span><input class="span2" id="appendedPrependedInput" size="16" type="text"><span class="add-on">.00</span>
              </div></div>
        </td>
        </tr>
        
        <tr>
        <td colspan="2"><br /><b>Additonal Options for Sale:</b></td>
        </tr>
        
        <tr>
        <td><input type="checkbox" name="extendedlicenses[]" value="multiseat" />&nbsp;&nbsp;Multi-Seat (Unlimited)</td>
        <td colspan="2">+ $20</td>
        </tr>
        
        <tr>
        <td><input type="checkbox" name="extendedlicenses[]" value="printruns" />&nbsp;&nbsp;Unlimited Reproduction / Print Runs</td>
        <td colspan="2">+ $35</td>
        </tr>
        
        <tr>
        <td><input type="checkbox" name="extendedlicenses[]" value="resale" />&nbsp;&nbsp;Items for Resale - Limited Run</td>
        <td colspan="2">+ $35</td>
        </tr>
        
        <tr>
        <td><input type="checkbox" name="extendedlicenses[]" value="electronic" />&nbsp;&nbsp;Unlimited Electronic Use</td>
        <td colspan="2">+ $35</td>
        </tr>

        </tbody>
        </table>
        </div>
        
        <!--CREATIVE COMMONS-->
        <div id="cc" class="hide">
        <table class="table">
        <tbody>
        
        <tr>
        <td>Allow Modifications of Your Work?</td>
        <td colspan="2"><input type="radio" name="ccmods" value="yes" />&nbsp&nbsp;Yes&nbsp;&nbsp;<input style="margin-left:20px;"  type="radio" name="ccmods" value="no" />&nbsp&nbsp;No&nbsp;&nbsp;<input style="margin-left:20px;" type="radio" name="ccmods" value="sharealike" />&nbsp&nbsp;Share Alike&nbsp;&nbsp;</td>
        </tr>
        
        <tr>
        <td>Allow Commercial Uses of Your Work?</td>
        <td colspan="2"><input type="radio" name="cccom" value="yes" />&nbsp&nbsp;Yes&nbsp;&nbsp;<input style="margin-left:20px;" type="radio" name="cccom" value="no" />&nbsp&nbsp;No&nbsp;&nbsp;</td>
        </tr>
        
        </tbody>
        </table>
        </div>
    
    </tbody>
    </table>

	<br />
	<button type="submit" name="Submit" class="btn btn-success">Upload Now</button>
	</form>';

}
                        
            elseif($option == 'batch') {
                        
            ?>

<!-- Generic page styles -->
<link rel="stylesheet" href="css/style.css">
<!-- Bootstrap styles for responsive website layout, supporting different screen sizes -->
<link rel="stylesheet" href="http://blueimp.github.com/cdn/css/bootstrap-responsive.min.css">
<!-- Bootstrap CSS fixes for IE6 -->
<!--[if lt IE 7]><link rel="stylesheet" href="http://blueimp.github.com/cdn/css/bootstrap-ie6.min.css"><![endif]-->
<!-- Bootstrap Image Gallery styles -->
<link rel="stylesheet" href="http://blueimp.github.com/Bootstrap-Image-Gallery/css/bootstrap-image-gallery.min.css">
<!-- CSS to style the file input field as button and adjust the Bootstrap progress bars -->
<link rel="stylesheet" href="batch/css/jquery.fileupload-ui.css">
<!-- Shim to make HTML5 elements usable in older Internet Explorer versions -->
<!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->

<div style="text-align:center;font-size:14px;font-family:helvetica;font-weight:100;margin-left:-35px;margin-top:15px;">Drap and Drop Supported</div>

    <form id="fileupload" action="batch/server/php/" method="POST" enctype="multipart/form-data">
        <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
        <div class="row fileupload-buttonbar" style="margin-left:150px;margin-top:15px;">
            <div class="span7">
                <!-- The fileinput-button span is used to style the file input field as button -->
                <span class="btn btn-success fileinput-button">
                    <i class="icon-plus icon-white"></i>
                    <span>Add files...</span>
                    <input type="file" name="files[]" multiple>
                </span>
                <button type="submit" class="btn btn-primary start">
                    <i class="icon-upload icon-white"></i>
                    <span>Start upload</span>
                </button>
                <button type="reset" class="btn btn-warning cancel">
                    <i class="icon-ban-circle icon-white"></i>
                    <span>Cancel upload</span>
                </button>
                <button type="button" class="btn btn-danger delete">
                    <i class="icon-trash icon-white"></i>
                    <span>Delete</span>
                </button>
                <input type="checkbox" class="toggle">
            </div>
            <!-- The global progress information -->
            <div class="span5 fileupload-progress fade">
                <!-- The global progress bar -->
                <div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width:425px;">
                    <div class="bar" style="width:0%;"></div>
                </div>
                <!-- The extended global progress information -->
                <div class="progress-extended">&nbsp;</div>
            </div>
        </div>
        <!-- The loading indicator is shown during file processing -->
        <div class="fileupload-loading"></div>
        <br>
        <!-- The table listing the files available for upload/download -->
        <table role="presentation" class="table table-striped"><tbody class="files" style="width:800px;" data-toggle="modal-gallery" data-target="#modal-gallery"></tbody></table>
    </form>
    </div>
    
<!-- modal-gallery is the modal dialog used for the image gallery -->
<div id="modal-gallery" class="modal modal-gallery hide fade" data-filter=":odd">
    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h3 class="modal-title"></h3>
    </div>
    <div class="modal-body"><div class="modal-image"></div></div>
    <div class="modal-footer">
        <a class="btn modal-download" target="_blank">
            <i class="icon-download"></i>
            <span>Download</span>
        </a>
        <a class="btn btn-success modal-play modal-slideshow" data-slideshow="5000">
            <i class="icon-play icon-white"></i>
            <span>Slideshow</span>
        </a>
        <a class="btn btn-info modal-prev">
            <i class="icon-arrow-left icon-white"></i>
            <span>Previous</span>
        </a>
        <a class="btn btn-primary modal-next">
            <span>Next</span>
            <i class="icon-arrow-right icon-white"></i>
        </a>
    </div>
</div>

<div style="width:800px;margin-top:-15px;"">
<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        <td class="preview"><span class="fade"></span></td>
        <td class="name"><span>{%= file.name %}</span></td>
        <div>
        <div>    
	<td colspan="1" class="desc">Name: <input type="text" style="width:120px;" name='{%= "names_" + String(file.name).replace(/([.]+)/gi, '_')%}' required="required"/></td>
        </div>
        <div>
    <td colspan="1" class="desc">Price: 
        <select id="price" style="padding-left:5px;width:120px;" name='{%= "price_" + String(file.name).replace(/([.]+)/gi, '_')%}' style="width:120px;float:left;margin-left:-70px;" onchange="showOtherPrice()">
            <option value=".00">Free</option>
            <option value=".50">$.50</option>
            <option value=".75">$.75</option>
            <option value="1.00">$1.00</option>
            <option value="2.00">$2.00</option>
            <option value="5.00">$5.00</option>
            <option value="10.00">$10.00</option>
            <option value="15.00">$15.00</option>
            <option value="25.00">$25.00</option>
            <option value="50.00">$50.00</option>
            <option value="100.00">$100.00</option>
            <option value="200.00">$200.00</option>
            <option value="Other Price">Choose Price</option>
            <option value="Not For Sale">Not For Sale</option>
            </select>
        </td>

        </div>
	</div>
<td colspan="1" class="desc">Keywords: <input type="text" style="width:120px;" name='{%= "keyword_" + String(file.name).replace(/([.]+)/gi, '_')%}' required="required"/></td>

        <td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
        {% if (file.error) { %}
            <td class="error" colspan="2"><span class="label label-important">{%=locale.fileupload.error%}</span> {%=locale.fileupload.errors[file.error] || file.error%}</td>
        {% } else if (o.files.valid && !i) { %}

            <td class="start">{% if (!o.options.autoUpload) { %}
                <button class="btn btn-primary" style="width:80px;">
                    <i class="icon-upload icon-white"></i>
                    <span>{%=locale.fileupload.start%}</span>
                </button>
            {% } %}</td>
        {% } else { %}
            <td colspan="2"></td>
        {% } %}
        <td class="cancel">{% if (!i) { %}
            <button class="btn btn-warning" style="width:80px;">
                <i class="icon-ban-circle icon-white"></i>
                <span>{%=locale.fileupload.cancel%}</span>
            </button>
        {% } %}</td>
    </tr>
{% } %}

</script>
</div>

<div style="width:800px;">
<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade">
        {% if (file.error) { %}
            <td></td>
            <td class="name"><span>{%=file.name%}</span></td>
            <td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
            <td class="error" colspan="2"><span class="label label-important">{%=locale.fileupload.error%}</span> {%=locale.fileupload.errors[file.error] || file.error%}</td>        {% } else { %}
            <td class="preview">{% if (file.thumbnail_url) { %}
                <a href="{%=file.url%}" title="{%=file.name%}" rel="gallery" download="{%=file.name%}"><img src="{%=file.thumbnail_url%}"></a>
            {% } %}</td>
            <td class="name">
                <a href="{%=file.url%}" title="{%=file.name%}" rel="{%=file.thumbnail_url&&'gallery'%}" download="{%=file.name%}">{%=file.name%}</a>
            </td>
            <td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
            <td colspan="2"></td>
        {% } %}
        <td class="delete">
            <button class="btn btn-danger" data-type="{%=file.delete_type%}" data-url="{%=file.delete_url%}">
                <i class="icon-trash icon-white"></i>
                <span>{%=locale.fileupload.destroy%}</span>
            </button>
            <input type="checkbox" name="delete" value="1">
        </td>
    </tr>
{% } %}
</script>
</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<script src="js/vendor/jquery.ui.widget.js"></script>
<!-- The Templates plugin is included to render the upload/download listings -->
<script src="http://blueimp.github.com/JavaScript-Templates/tmpl.min.js"></script>
<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
<script src="http://blueimp.github.com/JavaScript-Load-Image/load-image.min.js"></script>
<!-- The Canvas to Blob plugin is included for image resizing functionality -->
<script src="http://blueimp.github.com/JavaScript-Canvas-to-Blob/canvas-to-blob.min.js"></script>
<!-- Bootstrap JS and Bootstrap Image Gallery are not required, but included for the demo -->
<script src="http://blueimp.github.com/cdn/js/bootstrap.min.js"></script>
<script src="http://blueimp.github.com/Bootstrap-Image-Gallery/js/bootstrap-image-gallery.min.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="batch/js/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="batch/js/jquery.fileupload.js"></script>
<!-- The File Upload file processing plugin -->
<script src="batch/js/jquery.fileupload-fp.js"></script>
<!-- The File Upload user interface plugin -->
<script src="batch/js/jquery.fileupload-ui.js"></script>
<!-- The localization script -->
<script src="batch/js/locale.js"></script>
<!-- The main application script -->
<script src="batch/js/main.js"></script>
<!-- The XDomainRequest Transport is included for cross-domain file deletion for IE8+ -->
<!--[if gte IE 8]><script src="batch/js/cors/jquery.xdr-transport.js"></script><![endif]-->   
                
                            
           <?php

                        }
                        
        elseif($option == 'newexhibit') {
                        
	     //Display upload Boxes
         echo'<div class="grid_6">
                    <div class="uploadBox" style="margin-top:40px;">
                        <div style="font-size:24px;font-weight:300;text-align:center;padding:10px 15px 10px 15px;">Create your exhibit</div>
                        <p>Please select more than 2 tags (selecting multiple values: hold down command button if on mac, control button if on PC). <br /><i><span style="font-size:16px;">* </span>Required fields.</i></p>
                     </div>
                     <div class="uploadBox" style="margin-top:40px;">
                        <div style="font-size:24px;font-weight:300;text-align:center;padding:10px 15px 10px 15px;">What is an exhibit?</div>
                                    <p>An exhibit is a container for photos that pertain to a certain subject. Exhibits are more specific than an album. Choose tags for your exhibit below and write a bit about it so that photographers can find and rate it easier.</p>
                    </div>
              </div>
        
	<form action="create_set.php" method="post" enctype="multipart/form-data">
    
    <div class="span9 grid_14 push_3"  style="width:700px;margin-top:50px;padding-left:20px;margin-left:10px;font-size:14px;font-weight:300;">
    <table class="table">
    <tbody>
    
    <tr>
    <td>*Title of exhibit:</td>
    <td><input type="text" name="title" /></td>
    </tr>
    
    <tr>
    <td>*Pick Keywords:</td>
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
    <td>About this exhibit:</td>
    <td><textarea style="width:500px" rows="4" cols="60" name="about"></textarea></td>
    </tr>
    
    </tbody>
    </table>

<button type="submit" name="Submit" class="btn btn-success">Create Exhibit</button>
</form>
</div>
    
</div> <!--end of well-->
</div>';

            }
        
        echo'</div>';
        
      } //end upload view
    ?>

    
     <?php
      /*--------------------------About View / Edit Info View----------------------------*/
    if($view == 'about') {

        $option = htmlentities($_GET['option']);
        $action = htmlentities($_GET['action']);

        echo'<div style="width:1150px;">';
        
        echo'<div class="aboutBox" style="float:left;margin-top:20px;">   
         
                <div class="cornerTab">
                    <img src="graphics/list 2.png" /> About 
                </div>
                
                <div class="socialBar">
                    <div id="memberLevel"><a href="profile.php?view=about&option=editinfo">Edit Profile</a></div>
                    <div style="float:right;margin-top:7px;margin-right:15px;">';
                        if($facebookpage) {echo'<a href="',$facebookpage,'"><img src="https://photorankr.com/graphics/facebook.png" style="width:30px;height:30px;margin: 7px 9px 0px 10px;"></a>';}
                        if($twitterpage) {echo'<a href="',$twitterpage,'"><img src="https://photorankr.com/graphics/twitter.png" style="width:30px;height:30px;margin: 7px 9px 0px 10px;"></a>';}
                        if($pinterest) {echo'<a href="',$pinterest,'"><img src="https://photorankr.com/graphics/pinterest.png" style="width:30px;height:30px;margin: 7px 9px 0px 10px;"></a>';}
                        if($googleplus) {echo'<a href="',$googleplus,'"><img src="https://photorankr.com/graphics/g+r.png" style="width:30px;height:30px;margin: 7px 9px 0px 10px;"></a>';}
                        echo'
                    </div>
                </div>
                
    <!--------EDIT INFO--------------->';
    if($option == 'editinfo') {
            
	if($action == 'submit') {

		//GET UPDATED PROFILE INFORMATION
        if(isset($_POST['firstname'])) {$firstname=mysql_real_escape_string($_POST['firstname']); }
        if(isset($_POST['lastname'])) {$lastname=mysql_real_escape_string($_POST['lastname']); }
		if(isset($_POST['age'])) {$age=mysql_real_escape_string($_POST['age']); }
		if(isset($_POST['gender'])) {$gender=mysql_real_escape_string($_POST['gender']); }
		if(isset($_POST['location'])) {$location = mysql_real_escape_string($_POST['location']);}
		if(isset($_POST['camera'])) {$camera=mysql_real_escape_string($_POST['camera']);}
		if(isset($_POST['facebookpage'])) {$facebookpage=mysql_real_escape_string($_POST['facebookpage']);}
		if(isset($_POST['twitteraccount'])) {$twitteraccount=mysql_real_escape_string($_POST['twitteraccount']);}
        if(isset($_POST['quote'])) {$quote=mysql_real_escape_string($_POST['quote']);}
		if(isset($_POST['bio'])) {$bio=mysql_real_escape_string($_POST['bio']);}
		if(isset($_POST['password'])) {$password=mysql_real_escape_string($_POST['password']);}
		if(isset($_POST['confirmpassword'])) {$confirmpassword=mysql_real_escape_string($_POST['confirmpassword']);}

      $singlestyletags = $_POST['singlestyletags']; 
          $singlecategorytags = $_POST['singlecategorytags'];

          //Concatenate single photo box tags
          $numbersinglestyletags = count($singlestyletags);
        for($i=0; $i < $numbersinglestyletags; $i++)
        {
            $singlestyletags2 = $singlestyletags2 . " " . mysql_real_escape_string($singlestyletags[$i]) . " ";
        }
          $numbersinglecategorytags = count($singlecategorytags);
        for($i=0; $i < $numbersinglecategorytags; $i++)
        {
            $singlecategorytags2 = $singlecategorytags2 . " " . mysql_real_escape_string($singlecategorytags[$i]) . " ";
          }

          $viewLikes = $singlecategorytags2 . "  " . $singlestyletags2;
	
		//check if confirm password and password are same
		if ($confirmpassword != $password) {
			die('Your passwords did not match.');
		}	
		
		//require files that will help with picture uploading and thumbnail creation/display
		require 'config.php';
	
		//move the file
		if(isset($_FILES['file'])) {  
  
    			if(preg_match('/[.](jpg)|(jpeg)|(gif)|(png)|(JPG)$/', $_FILES['file']['name'])) {  
        			$filename = $_FILES['file']['name'];  
				    $newfilename=str_replace(" ","",$filename);
 				    $newfilename=str_replace("#","",$newfilename);		
    				$newfilename=str_replace("&","",$newfilename);
				    $newfilename=strtolower($newfilename);
    				$newfilename=str_replace("?","",$newfilename);	
    				$newfilename=str_replace("'","",$newfilename);
    				$newfilename=str_replace("#","",$newfilename);
    				$newfilename=str_replace(":","",$newfilename);
    				$newfilename=str_replace("*","",$newfilename);
    				$newfilename=str_replace("<","",$newfilename);
    				$newfilename=str_replace(">","",$newfilename);
    				$newfilename=str_replace("(","",$newfilename);
    				$newfilename=str_replace(")","",$newfilename);
    				$newfilename=str_replace("^","",$newfilename);
   			     	$newfilename=str_replace("$","",$newfilename);
    				$newfilename=str_replace("@","",$newfilename);
    				$newfilename=str_replace("!","",$newfilename);
    				$newfilename=str_replace("+","",$newfilename);
    				$newfilename=str_replace("=","",$newfilename);
    				$newfilename=str_replace("|","",$newfilename);
   			     	$newfilename=str_replace(";","",$newfilename);
    				$newfilename=str_replace("[","",$newfilename);
    				$newfilename=str_replace("{","",$newfilename);
    				$newfilename=str_replace("}","",$newfilename);
    				$newfilename=str_replace("]","",$newfilename);
    				$newfilename=str_replace("~","",$newfilename);
    				$newfilename=str_replace("`","",$newfilename);
			     	$newfilename=str_replace("?","",$newfilename);
				/*if(preg_match('/[.](jpg)$/', $newfilename)) {  
            				$extension = ".jpg";
        			} else if (preg_match('/[.](gif)$/', $newfilename)) {  
            				$extension = ".gif"; 
        			} else if (preg_match('/[.](png)$/', $newfilename)) {  
            				$extension = ".png";  
        			}*/
        			
                    $time = time();
                    $newfilename = $time . $newfilename;
                    $source = $_FILES['file']['tmp_name'];  
        			$profilepic = $path_to_profpic_directory . $newfilename; 
  
        			move_uploaded_file($source, $profilepic);  
                    chmod($profilepic, 0777);
                    
                    createprofthumbdim($profilepic);
        			createprofthumbnail($profilepic);
					
    			}  
		}  
	
		//update the database with this new information
		if(isset($_POST['bio'])) {
			$infoupdatequery=("UPDATE userinfo SET firstname = '$firstname', lastname = '$lastname', age = '$age', gender = '$gender', location = '$location', camera = '$camera', facebookpage='$facebookpage', twitteraccount='$twitteraccount', quote='$quote', bio='$bio', profilepic='$profilepic', password='$password', viewLikes='$viewLikes' WHERE emailaddress='$email'");
		}
		else {
        
			$infoupdatequery=("UPDATE userinfo SET firstname = '$firstname', lastname = '$lastname', age = '$age', gender = '$gender', location = '$location', camera = '$camera', facebookpage='$facebookpage', twitteraccount='$twitteraccount', profilepic='$profilepic', password='$password', viewLikes='$viewLikes' WHERE emailaddress='$email'");
		}
		$infoupdateresult=mysql_query($infoupdatequery);

        mysql_close();
        echo '<META HTTP-EQUIV="Refresh" Content="0; URL=profile.php?view=about&action=saved">';
        exit();
	}

echo'

    <div id="aboutList">

        <form action="profile.php?view=about&option=editinfo&action=submit" method="post" enctype="multipart/form-data">
        <table class="table" style="font-size:13px;font-weight:300;line-height:20px;margin-top:5px;">
        <tbody>
        
        <tr>
        <td>Firstname:</td>
        <td><input style="width:180px;height:20px;" type="text" name="firstname" value="', $firstname, '"/></td>
        </tr>
        
        <tr>
        <td>Lastname:</td>
        <td><input style="width:180px;height:20px;" type="text" name="lastname" value="', $lastname, '"/></td>
        </tr>

        <tr>
        <td>Age:</td>
        <td><input style="width:50px;height:20px;" type="text" name="age" value="', $age, '"/></td>
        </tr>

        <tr>
        <td>From:</td>
        <td><input style="width:180px;height:20px;" type="text" name="location" value="', $location, '"/></td>
        </tr> 

        <tr>
        <td>Gender:</td>
        <td>';
            if ($gender == 'Male') {
                echo '<input type="radio" name="gender" value="Male" checked="checked" /> Male&nbsp;&nbsp; 
                <input type="radio" name="gender" value="Female" /> Female&nbsp;&nbsp;';
            }
            else {
                echo '<input type="radio" name="gender" value="Male" /> Male&nbsp;&nbsp; 
                <input type="radio" name="gender" value="Female" checked="checked" /> Female&nbsp;&nbsp;';
            }
            echo '</td>
        </tr>

        <tr>
        <td>Camera:</td>
        <td><input style="width:180px;height:20px;" type="text" name="camera" value="', $camera, '"/></td>
        </tr>

        <tr>
        <td>Facebook Page:</td>
        <td><input style="width:180px;height:20px;" type="text" name="facebookpage" value="',$fbook,'"/></td>
        </tr>

        <tr>
        <td>Twitter:</td>
        <td><input style="width:180px;height:20px;" type="text" name="twitteraccount" value="',$twitter,'"/></td>
        </tr>

        <tr>
        <td>Quote:</td>
        <td><textarea style="width:500px" rows="2" cols="100" name="quote">',stripslashes($quote),'</textarea></td>
        </tr>
        
        <tr>
        <td>Change Password:</td>
        <td><input type="password" style="width:180px;height:25px;"  name="password" value="', $password, '"/></td>
        </tr>
        
        <tr>
        <td>Confirm Password:</td>
        <td><input type="password" style="width:180px;height:25px;"  name="confirmpassword" value="', $password, '"/></td>
        </tr>
        
        <tr>
        <td>Change Profile Photo:</td>
        <td><img src="',$profilepic,'" height="30" width="30" />&nbsp;&nbsp;&nbsp;<input type="file"  name="file" value="', $profilepic, '"/></td>
        </tr>
        
        <tr>
        <td>About:</td>
        <td><textarea style="width:500px" rows="6" cols="100" name="bio">',stripslashes($about),'</textarea></td>
        </tr>
        
        <tr>
        <td id="disc">Choose Your Discover Preferences:';
        if($_GET['error'] == 'disc') {echo'<div style="color:red;font-weight:700;"><br /><br /><br /><br />Please choose more photos to discover</div>';}
        echo'</td>
        <td><span style="font-size:13px">(Selecting multiple values: Hold down command button if on mac, control button if on PC)</span>
            <br /><br />
            <select style="width:150px;height:150px;" multiple="multiple" name="singlestyletags[]">
            <option value="B&W">Black and White</option>
            <option value="Cityscape">Cityscape</option>
            <option value="Fisheye">Fisheye</option>
            <option value="HDR">HDR</option>
            <option value="Illustration">Illustration</option>
            <option value="InfraredUV">Infrared/UV</option>
            <option value="Landscape">Landscape</option>
            <option value="Long Exposure">Long Exposure</option>
            <option value="Macro">Macro</option>
            <option value="Miniature">Miniature</option>
            <option value="Monochrome">Monochrome</option>
            <option value="Motion Blur">Motion Blur</option>
            <option value="Night">Night</option>
            <option value="Panorama">Panorama</option>
            <option value="Photojournalism">Photojournalism</option>
            <option value="Portrait">Portrait</option>
            <option value="Stereoscopic">Stereoscopic</option>
            <option value="Time Lapse">Time Lapse</option>
            </select>
            <span style="padding-left:70px">
            <select style="width:150px;height:150px;" multiple="multiple" name="singlecategorytags[]">
            <option value="Advertising">Advertising</option>
            <option value="Aerial">Aerial</option>
            <option value="Animal">Animal</option>
            <option value="Architecture">Architecture</option>
            <option value="Astro">Astro</option>
            <option value="Aura">Aura</option>
            <option value="Automotive">Automotive</option>
            <option value="Botanical">Botanical</option>
            <option value="Candid">Candid</option>
            <option value="Commercial">Commercial</option>
            <option value="Corporate">Corporate</option>
            <option value="Documentary">Documentary</option>
            <option value="Fashion">Fashion</option>
            <option value="Fine Art">Fine Art</option>
            <option value="Food">Food</option>
            <option value="Historical">Historical</option>
            <option value="Industrial">Industrial</option>
            <option value="Musical">Musical</option>
            <option value="Nature">Nature</option>
            <option value="News">News</option>
            <option value="Night">Night</option>
            <option value="People">People</option>
            <option value="Scenic">Scenic</option>
            <option value="Sports">Sports</option>
            <option value="Still Life">Still Life</option>
            <option value="Transportation">Transportation</option>
            <option value="Urban">Urban</option>
            <option value="War">War</option>
            </select><br /><br />
        </td>
        </tr>
                
        </tbody>
        </table>
        <button class="btn btn-success" type="submit">Save Profile</button>
        </form>
        </div>';
            
    } //end of option == 'editinfo'
    
    elseif($option == '') {
    
    if($action == "saved") {
        echo '<div style="margin-top:20px;margin-left:60px;color:#6aae45;float:left;font-size:20px;font-weight:200;">Profile Saved</div><br />';
    }
    
    echo'<!---About List--->
    <div id="aboutList">
        <table class="table" style="font-size:13px;font-weight:300;line-height:20px;margin-top:5px;">
        <tbody>';

        if($age) {
        echo'
        <tr>
        <td style="font-weight:500;">Age:</td>
        <td>',$age,'</td>
        </tr>'; }

        if($location) {
        echo'
        <tr>
        <td style="font-weight:500;">From:</td>
        <td>',$location,'</td>
        </tr>'; }

        if($gender) {
        echo'
        <tr>
        <td style="font-weight:500;">Gender:</td>
        <td>',$gender,'</td>
        </tr>'; }

        if($camera) {
        echo'
        <tr>
        <td style="font-weight:500;">Camera:</td>
        <td>',$camera,'</td>
        </tr>'; }

        if($quote) {
        echo'
        <tr>
        <td style="font-weight:500;">Quote:</td>
        <td>',$quote,'</td>
        </tr>'; }

        if($bio) {
        echo'
        <tr>
        <td style="font-weight:500;">About:</td>
        <td>',$bio,'</td>
        </tr>'; }

        echo'
        </tbody>
        </table>
        <a class="btn btn-success" href="myprofile.php?view=editinfo">Edit Profile</a>
    </div>                
    </div>
    
             <!---Photo Views Line Graph--->
             <div class="graphBox" style="float:left;margin-top:20px;margin-left:30px;">   
         
                <div class="cornerTab" style="width:180px;">
                    <img style="margin-top:-3px;" src="graphics/stats 3.png" /> Photo Views 
                </div>

                <div id="chart_div" style="width:495px;height:400px;margin-top:50px;"></div>
                
             </div>';
    
        echo'</div>';
        
        } //end of option == ''
        
      } //end of about view
    ?>
    
      <?php
      /*--------------------------Exhibit View----------------------------*/
      if($view == 'exhibits') {
      
            echo'<div class="portfolioDrop">
                <ul>
                    <li><a href="profile.php">Newest Photos</a></li>
                    <li><a href="profile.php?option=top">Top Ranked Photos</a></li>
                    <li><a href="profile.php?option=fave">Most Favorited</a></li>
                    <li><a href="profile.php?view=exhibits">Exhibits</a></li>
                </ul>
             </div>';
             
        echo'<div style="width:1150px;">';
        
        
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

echo '<META HTTP-EQUIV="Refresh" Content="0; URL=myprofile.php?view=exhibits&set=',$set,'">';
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
    echo '<META HTTP-EQUIV="Refresh" Content="0; URL=myprofile.php?view=exhibits">';

}
        
        //select all exhibits of user
        $allsetsquery = "SELECT * FROM sets WHERE owner = '$email'";
        $allsetsrun = mysql_query($allsetsquery);
        $numbersets = mysql_num_rows($allsetsrun);
        
        //if no sets, propmt them to create one
        $numbersets = mysql_num_rows($allsetsrun);
        if($numbersets == 0) {
            echo'<div style="font-size:18px;font-weight:200;padding:40px;text-align:center;margin-left:-35px;margin-top:120px;"><a style="color:#333;" href="myprofile.php?view=upload&option=newexhibit">Click here to create your first exhibit.</a></div>';
        }
        
        if($set == '' && $numbersets > 0) {
        
        for($iii=0; $iii < $numbersets; $iii++) {
            $setname = mysql_result($allsetsrun, $iii, "title");
            $setcover = mysql_result($allsetsrun, $iii, "cover");
            $setabout = mysql_result($allsetsrun, $iii, "about");
            $setviews = mysql_result($allsetsrun, $iii, "views");
            $setfaves = mysql_result($allsetsrun, $iii, "faves");
            $set_id[$iii] = mysql_result($allsetsrun, $iii, "id");
            $setname2[$iii] = (strlen($setname[$iii]) > 30) ? substr($setname[$iii],0,27). " &#8230;" : $setname[$iii];
            $pulltopphoto = mysql_query("SELECT source FROM photos WHERE set_id = '$set_id[$iii]' ORDER BY votes DESC LIMIT 8");
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
            $thumb5 = mysql_result($pulltopphoto, 5, "source");
            $thumb5 = str_replace("userphotos/","userphotos/medthumbs/",$thumb5);
            $thumb6 = mysql_result($pulltopphoto, 6, "source");
            $thumb6 = str_replace("userphotos/","userphotos/medthumbs/",$thumb6);
            $thumb7 = mysql_result($pulltopphoto, 7, "source");
            $thumb7 = str_replace("userphotos/","userphotos/medthumbs/",$thumb7);
            $thumb8 =mysql_result($pulltopphoto, 8, "source");
            $thumb8 = str_replace("userphotos/","userphotos/medthumbs/",$thumb8);
            
            echo'<div class="exhibitBox">
                <div class="exhibitInfo">
                    <header>',$setname,'</header>
                    <div id="exhibitStats">Views: ',$setviews,' Faves: ',$setfaves,'</div>
                    <p>',$setabout,'</p>
                </div>
                <a style="text-decoration:none;" href="profile.php?view=exhibits&set=',$set_id[$iii],'">
                <img style="width:280px;height:280px;" src="https://photorankr.com/',$setcover,'" />
                <div class="exhibitSmallBox" style="float:left;width:580px;height:280px;">';
                if($thumb1) {
                    echo'<img style="width:136px;height:136px;" src="https://photorankr.com/',$thumb1,'" />';
                }
                if($thumb2) {
                    echo'<img style="width:136px;height:136px;" src="https://photorankr.com/',$thumb2,'" />';
                }
                if($thumb3) {
                    echo'<img style="width:136px;height:136px;" src="https://photorankr.com/',$thumb3,'" />';
                }
                if($thumb4) {
                    echo'<img style="width:136px;height:136px;" src="https://photorankr.com/',$thumb4,'" />';
                }
                if($thumb5) {
                    echo'<img style="width:136px;height:136px;" src="https://photorankr.com/',$thumb5,'" />';
                }
                if($thumb6) {
                    echo'<img style="width:136px;height:136px;" src="https://photorankr.com/',$thumb6,'" />';
                }
                if($thumb7) {
                    echo'<img style="width:136px;height:136px;" src="https://photorankr.com/',$thumb7,'" />';
                }
                if($thumb8) {
                    echo'<img style="width:136px;height:136px;" src="https://photorankr.com/',$thumb8,'" />';
                }
                echo'
                </div>
                </a>
                </div>';
        }
        
        } //end of set == ''
        
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

echo'<div class="grid_18" style="width:770px;margin-left:-20px;padding:35px;position:relative;clear:both;">

<div class="grid_18 well" style="position:relative;clear:both;width:1060px;line-height:25px;margin-top:-15px;"><span style="font-size:25px;font-family:helvetica,arial;font-weight:200;">',$settitle,'</span><br />';
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

    <div id="thepics" style="position:relative;left:-72px;width:1250px;clear:both;">
    <div id="main" role="main">
    <ul id="tiles">';

for($iii=0; $iii < $numphotosgrabbed; $iii++) {
    $insetname[$iii] = mysql_result($grabphotosrun, $iii, "caption");
    $insetsource = mysql_result($grabphotosrun, $iii, "source");
    $insetsource = "https://photorankr.com/" . $insetsource;
    $newsource = str_replace("userphotos/","userphotos/medthumbs/", $insetsource);
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
    
      list($width, $height) = getimagesize($insetsource);
	$imgratio = $height / $width;
    $heightls = $height / 3.3;
    $widthls = $width / 3.3;
    if($widthls < 235) {
    $heightls = $heightls * ($heightls/$widthls);
    $widthls = 280;
    }

		echo '
        <a style="text-decoration:none;color:#333;" href="fullsize.php?imageid=',$id,'&v=n"><li class="fPic" id="',$id,'" style="list-style-type: none;width:280px;"><img style="min-width:280px;" src="',$newsource,'" height="',$heightls,'px" width="',$widthls,'px" />
        
          <div class="statoverlay" style="z-index:1;background-color:white;position:relative;top:0px;width:280px;height:30px;"><div style="line-spacing:1.48;padding:5px;color:#4A4A4A;"><div style="float:left;"<span style="font-size:16px;font-weight:bold;">',$score,'</span>&nbsp;&nbsp;<span style="font-weight:bold;font-size:12px;">',$caption,'</span></div></div><br/></div>';
 
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
        offset: 10, // Optional, the distance between grid items
        itemWidth: 280 // Optional, the width of a grid item
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

   
   } //end set view != ''
   
      } //end of exhibit view
    ?>

    </div>
    </div><!---end of bottom half container---->
</body>
</html>