<?php

//connect to the database
require "db_connection.php";
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
    $currenttime = time();


    //QUERY FOR NOTIFICATIONS
    $currentnots = "SELECT * FROM userinfo WHERE emailaddress = '$email'";
    $currentnotsquery = mysql_query($currentnots);
    $currentnotsresult = mysql_result($currentnotsquery, 0, "notifications");
    $sessionfirst =  mysql_result($currentnotsquery,0,'firstname');
    $sessionlast =  mysql_result($currentnotsquery,0,'lastname');

   
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
    
    //GRAB USER INFORMATION
   $userid = htmlentities($_GET['u']);
   if(!$userid) {
        header('Location: http://www.photorankr.com/trending.php');
   }

   //Query Stats Table 
  $timestampentertimeslicequery = mysql_query("INSERT INTO Statistics (ViewTimeStamp, Person, Type, user_id) VALUES ('$currenttime', '$email', 'profileview', '$userid')");

//User information
$userinfo = mysql_query("SELECT * FROM userinfo WHERE user_id = '$userid'");
$profilepic = mysql_result($userinfo,0,'profilepic');
$usersfirst= mysql_result($userinfo,0,'firstname');
$firstname= mysql_result($userinfo,0,'firstname');
$lastname = mysql_result($userinfo,0,'lastname');
$useremail = mysql_result($userinfo,0,'emailaddress');
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
$userreputation = number_format($reputation,1);
$profileviews = mysql_result($userinfo,0,'profileviews');

//ADD PAGEVIEW TO THEIR PROFILE
$profileviewquery = mysql_query("UPDATE userinfo SET profileviews = (profileviews + 1) WHERE user_id = '$userid'");

    //Photos
    $userphotosquery = mysql_query("SELECT points,votes,faves,price,sold,width,height,views FROM photos WHERE user_id = '$userid''");
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
    $followersquery="SELECT * FROM userinfo WHERE following LIKE '%$useremail%'";
	$followersresult=mysql_query($followersquery);
	$numberfollowers = mysql_num_rows($followersresult);
    
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
    
    //Number Following
    $emailquery=("SELECT following FROM userinfo WHERE emailaddress ='$useremail'");
	$followresult=mysql_query($emailquery);
	$followinglist=mysql_result($followresult, 0, "following");
	$followingquery="SELECT * FROM userinfo WHERE emailaddress IN ($followinglist)";
	$followingresult = mysql_query($followingquery);
	$numberfollowing = mysql_num_rows($followingresult);

    //Activity Queries
    $activityquery = mysql_query("SELECT * FROM newsfeed WHERE hide <> 1 AND (emailaddress = '$useremail' OR owner = '$useremail') AND type IN ('follow','comment','fave','photo') ORDER BY id DESC LIMIT 13");

    //Get Views & URI
    $view = htmlentities($_GET['view']);
    $searchword = htmlentities($_GET['searchword']);
    $action = htmlentities($_GET['action']);
    $option = htmlentities($_GET['option']);  
    $uri = $_SERVER['REQUEST_URI'];
    
/*$userphotos="SELECT * FROM Statistics WHERE email = '$email'";
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

*/

follow;
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

//Exhibit Fave
 
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


//Grab OWNERS reputation score
    
    $toprankedphotos2 = "SELECT * FROM photos WHERE emailaddress = '$useremail' ORDER BY points DESC";
    $toprankedphotosquery2 = mysql_query($toprankedphotos2);
    $numtoprankedphotos2 = mysql_num_rows($toprankedphotos2);

    for($i=0;$i<15;$i++){
    $toprankedphotopoints2 = (mysql_result($toprankedphotosquery2, $i, "points")/mysql_result($toprankedphotosquery2, $i, "votes")) + $toprankedphotopoints2;
    }
        
    $userphotos2="SELECT * FROM photos WHERE emailaddress = '$useremail'";
    $userphotosquery2=mysql_query($userphotos2);
    $numphotos2=mysql_num_rows($userphotosquery2);
    
    //Gather Total Number of Votes for All Photos (This is Visibility)
    for($ii=0; $ii<$numphotos2;$ii++){
    $totalvotes2 = mysql_result($userphotosquery2, $ii, "votes") + $totalvotes2; 
    }
    

    $followersquery2="SELECT * FROM userinfo WHERE following LIKE '%$useremail%'";
	$followersresult2 = mysql_query($followersquery2);
    $numberfollowers2 = mysql_num_rows($followersresult2);
    $totalpgviews2 = $totalvotes2;
    $ranking2 = $toprankedphotopoints2;
    $followerlimit2 = 50;
    $totalpgviewslimit2 = 800;
    $rankinglimit2 = 150; 
    $followerweight2 = .3;
    $totalpgviewsweight2 = .3;
    $rankingweight2 = .4; 

    
    if($numberfollowers2 > $followerlimit2) {
    $followerweighted2 = $followerweight2;
    }
    
    else{
    $followerdivisionfactor2 = ($numberfollowers2)/($followerlimit2);    
    $followerweighted2 = $followerweight2*$followerdivisionfactor2;
    }

    if($totalpgviews2 > $totalpgviewslimit2) {
        $totalpgviewsweighted2 = $totalpgviewsweight2;
    }
    
    else {
        $totalpgviewsdivisionfactor2 = ($totalpgviews2)/($totalpgviewslimit2); 
        $totalpgviewsweighted2 = $totalpgviewsweight2*$totalpgviewsdivisionfactor2;

    }
    

    
   if($ranking2 > 140) {
        $rankingweighted2 = $rankingweight2;
    }
    
    elseif($ranking2 > 135) {
        $rankingweighted2 = $rankingweight2 * .90;
    }
    
    elseif($ranking2 <= 135 && $ranking2 > 120) {       
     $rankingweighted2 = $rankingweight2 *.85;
    }
    
    elseif($ranking2 <= 120 && $ranking2 > 105) {
        $rankingweighted2 = $rankingweight2 *.75;
    }
    
    elseif($ranking2 <= 105 && $ranking2 > 90) {
        $rankingweighted2 = $rankingweight2 *.50;
    }
    
    elseif($ranking2 <= 90 && $ranking2 > 75) {
        $rankingweighted2 = $rankingweight2 *.30;
    }
    
    else {
       $rankingweighted2 = $rankingweight2 *.10;
    }
        
    if($numphotos2 < 14) { 
    $rankingweighted2 = .1;
    }
    

    $ultimatereputation = ($followerweighted2+$rankingweighted2+$totalpgviewsweighted2) * 100;

    $insertquery=mysql_query("UPDATE userinfo SET reputation = $ultimatereputation WHERE emailaddress='$useremail'");
    mysql_query($insertquery);

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="https://www.w3.org/1999/xhtml">

<head>

 <meta name="Generator" content="EditPlus">
  <meta name="Author" content="PhotoRankr, PhotoRankr.com">
  <meta name="Keywords" content="photos, sharing photos, photo sharing, photography, photography club, sell photos, sell photography, where to sell my photography, good sites for selling photography, making money from photography, making money off photography, social networking, social network, social networks, where to sell my photos, good sites for selling photos, good site to sell photos, making money from photos">
  <meta name="Description" content="<?php echo $fullname; ?>'s photography page on PhotoRankr">
     <meta name="viewport" content="width=1200" /> 

    <link rel="stylesheet" type="text/css" href="css/style.css"/>
    <link rel="stylesheet" type="text/css" href="css/vpstyle.css"/>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>
	<link rel="stylesheet" type="text/css" href="css/960grid.css"/>
	<link rel="stylesheet" type="text/css" href="css/reset.css"/>  
    <link rel="stylesheet" type="text/css" href="css/main3.css"/>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
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
  
  <title><?php echo $fullname; ?></title>
  
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

/*Page views line graph
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
*/
      
    //Display textarea
    $(function() 
    {
        $("#status").focus(function()
        {
        $("#button_block2").slideDown("fast");
        return false;
        });
        
    });
        </script>
        
        <style type="text/css">
            #button_block2 {
                display:none;
            }
            #button {
                background-color:#33C33C;
                color:#ffffff;
                font-size:13px;
                font-weight:bold;
                padding:3px;
                margin-left:40px;
            }
        </style>
</head>

<body style="overflow-x:hidden; background-image:url('graphics/paper.png');">

<!--Following Modal-->
<div class="modal hide fade" id="fwmodal" style="overflow:hidden;border:5px solid rgba(102,102,102,.8);z-index:100000;">
      
<?php
if($_SESSION['loggedin'] !== 1) {

echo'
<div class="modal-header" style="background-color:rgba(234,234,234,.9);color:#333;">
<a style="float:right" class="btn btn-success" data-dismiss="modal">Close</a>
<img style="margin-top:-2px;" src="graphics/aperture_dark.png" height="34" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:16px;font-family:helvetica,arial;font-weight:100;">Please log in to follow ',$fullname,'</span>
  </div>
  
<div modal-body" style="width:450px;height:145px;">

<div id="content" style="font-size:16px;width:560px;font-family:helvetica,arial;font-weight:100;background-color:rgb(245,245,245);">
		
<img class="roundedall" style="margin-left:20px;margin-top:20px;" src="',$profilepic,'" 
height="100px" width="100px" />

<div style="width:350px;margin-left:140px;margin-top:-85px;line-height:1.48;">
',$firstname,' ',$lastname,'<br />                 

',$numphotos,' photos <br />

Avg. Portfolio: ',$portfolioranking,' <br /><br /><br />

</div>
</div>';


    }
        
        
if($_SESSION['loggedin'] == 1) {
    
		$emailquery=("SELECT * FROM userinfo WHERE emailaddress ='$email'");
		$emailresult=mysql_query($emailquery);
		$prevemails=mysql_result($emailresult, 0, "following");
		$viewerfirst = mysql_result($emailresult, 0, "firstname");
		$viewerlast = mysql_result($emailresult, 0, "lastname");
		if($prevemails == "") {$emailaddressformatted="'". $emailaddress . "'";}
		else {$emailaddressformatted=", '". $emailaddress . "'";}
        
        //MAKE SURE NOT FOLLOWING SELF
        if($email == $useremail) {
       echo'
<div class="modal-header" style="background-color:rgba(234,234,234,.9);color:#333;">
<a style="float:right" class="btn btn-success" data-dismiss="modal">Close</a>
<img style="margin-top:-2px;" src="graphics/aperture_dark.png" height="34" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:16px;font-family:helvetica,arial;font-weight:100;">Oops, you accidentally tried to follow yourself.</span>
  </div>

<div modal-body" style="width:450px;height:145px;">

<div id="content" style="font-size:16px;width:560px;font-family:helvetica,arial;font-weight:100;background-color:rgb(245,245,245);">
		
<img class="roundedall" style="margin-left:20px;margin-top:20px;" src="',$profilepic,'" 
height="100px" width="100px" />

<div style="width:350px;margin-left:140px;margin-top:-85px;line-height:1.48;">
',$firstname,' ',$lastname,'<br />                 

',$numphotos,' photos <br />

Avg. Portfolio: ',$portfolioranking,' <br /><br /><br />

</div>
</div>';


        }
        
        
        else {
		//MAKE SURE FOLLOWER ISN'T ADDED TWICE
		$search_string=$prevemails;
		$regex="/$useremail/";
		$match=preg_match($regex,$search_string);
		if ($match > 0) {
			echo'
<div class="modal-header" style="background-color:rgba(234,234,234,.9);color:#333;">
<a style="float:right" class="btn btn-success" data-dismiss="modal">Close</a>
<img style="margin-top:-2px;" src="graphics/aperture_dark.png" height="34" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:16px;font-family:helvetica,arial;font-weight:100;">You are already following ',$firstname,'</span>
  </div>

<div modal-body" style="width:450px;height:145px;">

<div id="content" style="font-size:16px;width:560px;font-family:helvetica,arial;font-weight:100;background-color:rgb(245,245,245);">
		
<img class="roundedall" style="margin-left:20px;margin-top:20px;" src="',$profilepic,'" 
height="100px" width="100px" />

<div style="width:350px;margin-left:140px;margin-top:-85px;line-height:1.48;">
',$firstname,' ',$lastname,'<br />                 

',$numphotos,' photos <br />

Avg. Portfolio: ',$portfolioranking,' <br /><br /><br />

</div>
</div>';
		} 

else {
            
			echo'
<div class="modal-header" style="background-color:rgba(234,234,234,.9);color:#333;">
<a style="float:right" class="btn btn-success" href="viewprofile.php?u=', $userid,'&fw=1">Close</a>
<img style="margin-top:-2px;" src="graphics/aperture_dark.png" height="34" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:16px;font-family:helvetica,arial;font-weight:100;">You are now following ',$firstname,' ',$lastname,'</span>
  </div>

<div modal-body" style="width:450px;height:145px;">

<div id="content" style="font-size:16px;width:560px;font-family:helvetica,arial;font-weight:100;background-color:rgb(252,252,252);">
		
<img class="roundedall" style="margin-left:20px;margin-top:20px;" src="',$profilepic,'" 
height="100px" width="100px" />

<div style="width:350px;margin-left:140px;margin-top:-85px;line-height:1.48;">
',$firstname,' ',$lastname,'<br />                 

',$numphotos,' photos <br />

Avg. Portfolio: ',$portfolioranking,' <br /><br /><br />

</div>
</div>';
            
  }
    }
} 
        
        
        
?>

</div>
</div>



<!--Favorite Modal-->
<div class="modal hide fade" id="exfvmodal" style="overflow:hidden;border:5px solid rgba(102,102,102,.8);z-index:100000;">
  
<?php

$set = $_GET['set'];
$setinfo = mysql_query("SELECT title, cover FROM sets WHERE id = '$set'");
$settitle = mysql_result($setinfo,0,'title');
$setcover = mysql_result($setinfo,0,'cover');
if($setcover == '') {
$pulltopphoto = mysql_query("SELECT source FROM photos WHERE set_id = '$set' ORDER BY votes DESC LIMIT 1");
if($setcover == '') {
$setcover = mysql_result($pulltopphoto, 0, "source");
}

} 
 
if($_SESSION['loggedin'] !== 1) {

echo'
<div class="modal-header" style="background-color:rgba(234,234,234,.9);color:#333;">
<a style="float:right" class="btn btn-success" data-dismiss="modal">Close</a>
<img style="margin-top:-2px;" src="graphics/aperture_dark.png" height="34" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:16px;font-family:helvetica,arial;font-weight:100;">Please login to favorite this exhibit</span>
  </div>
 
<div modal-body" style="width:450px;height:145px;">

<div id="content" style="font-size:16px;width:560px;font-family:helvetica,arial;font-weight:100;background-color:rgb(252,252,252);height:150px;">
		
<img class="roundedall" style="margin-left:20px;margin-top:20px;" src="',$setcover,'" 
height="100px" width="100px" />

<div style="width:350px;margin-left:140px;margin-top:-75px;line-height:1.48;">              

',$settitle,'<br />

By: 
<a style="color:black;" href="viewprofile.php?u=',$user,'">',$firstname,' ',$lastname,'</a><br />   

</div>
</div>';
    
}

if($_SESSION['loggedin'] == 1) {
		$vieweremail = $_SESSION['email'];
		//run a query to be used to check if the image is already there
		$check = mysql_query("SELECT * FROM userinfo WHERE emailaddress='$vieweremail'") or die(mysql_error());
        $viewerfirst = mysql_result($check, 0, "firstname");
        $viewerlast = mysql_result($check, 0, "lastname");
        $imagelink2=str_replace(" ","", $image);
	
		//create the image variable to be used in the query, appropriately escaped
		$queryimage = "'" . $image . "'";
		$queryimage = ", " . $queryimage;
		$queryimage = addslashes($queryimage);
	
		//search for the image in the database as a check for repeats
		$mycheck = mysql_result($check, 0, "exhibitfaves");
		$search_string = $mycheck;
		$regex=$set;
		$match=strpos($search_string, $regex);
        
        //if tries to favorite own photo
        if($vieweremail == $useremail) {
        echo'
<div class="modal-header" style="background-color:rgba(234,234,234,.9);color:#333;">
<a style="float:right" class="btn btn-success" data-dismiss="modal">Close</a>
<img style="margin-top:-2px;" src="graphics/aperture_dark.png" height="34" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:16px;font-family:helvetica,arial;font-weight:100;">Oops, you tried to favorite your own exhibit.</span>
  </div>

<div modal-body" style="width:450px;height:145px;">

<div id="content" style="font-size:16px;width:560px;font-family:helvetica,arial;font-weight:100;background-color:rgb(252,252,252);height:150px;">
		
<img class="roundedall" style="margin-left:20px;margin-top:20px;" src="',$setcover,'" 
height="100px" width="100px" />

<div style="width:350px;margin-left:140px;margin-top:-75px;line-height:1.48;">              

',$settitle,'<br />

By: 
',$firstname,' ',$lastname,'</a>   

</div>
</div>';
    
    }
        
        else {
        
		//if the image has already been favorited
		if($match) {
			//tell them so
			        echo'
<div class="modal-header" style="background-color:rgba(234,234,234,.9);color:#333;">
<a style="float:right" class="btn btn-success" data-dismiss="modal">Close</a>
<img style="margin-top:-2px;" src="graphics/aperture_dark.png" height="34" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:16px;font-family:helvetica,arial;font-weight:100;">This exhibit is already in your favorites.</span>
  </div>

<div modal-body" style="width:450px;height:145px;">

<div id="content" style="font-size:16px;width:560px;font-family:helvetica,arial;font-weight:100;background-color:rgb(252,252,252);height:150px;">
		
<img class="roundedall" style="margin-left:20px;margin-top:20px;" src="',$setcover,'" 
height="100px" width="100px" />

<div style="width:350px;margin-left:140px;margin-top:-75px;line-height:1.48;">              

',$settitle,'<br />

By: 
<a style="color:black;" href="viewprofile.php?u=',$user,'">',$firstname,' ',$lastname,'</a><br />   

</div>
</div>';

    }
        
		else {
        
        echo'
<div class="modal-header" style="background-color:rgba(234,234,234,.9);color:#333;">
<a style="float:right" class="btn btn-success" href="viewprofile.php?u=',$userid,'&view=exhibits&set=',$set,'&exfv=1">Close</a>
<img style="margin-top:-2px;" src="graphics/aperture_dark.png" height="34" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:16px;font-family:helvetica,arial;font-weight:100;">This exhibit has been added to your favorites.</span>
  </div>

<div modal-body" style="width:450px;height:145px;">

<div id="content" style="font-size:16px;width:560px;font-family:helvetica,arial;font-weight:100;background-color:rgb(252,252,252);height:150px;">
		
<img class="roundedall" style="margin-left:20px;margin-top:20px;" src="',$setcover,'" 
height="100px" width="100px" />

<div style="width:350px;margin-left:140px;margin-top:-75px;line-height:1.48;">              

',$settitle,'<br />

By: 
<a style="color:black;" href="viewprofile.php?u=',$user,'">',$firstname,' ',$lastname,'</a><br />   

</div>
</div>';
    
    } 
      }  
	} 
  
?>

</div>
</div>


<!--Message Modal-->
<div class="modal hide fade" id="messagemodal" style="overflow-y:scroll;overflow-x:hidden;border:5px solid rgba(102,102,102,.8);z-index:100000;">
  
<?php
 
if($_SESSION['loggedin'] !== 1) {

echo'
<div class="modal-header" style="background-color:rgba(234,234,234,.9);color:#333;">
<a style="float:right" class="btn btn-success" data-dismiss="modal">Close</a>
<img style="margin-top:-2px;"  src="graphics/aperture_dark.png" height="34" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:16px;font-family:helvetica,arial;font-weight:100;">Please login to message ',$firstname,'</span>
  </div>
 
<div modal-body" style="width:450px;height:145px;">

<div id="content" style="font-size:16px;width:560px;font-family:helvetica,arial;font-weight:100;background-color:rgb(252,252,252);height:150px;">
		
<img class="roundedall" style="margin-left:20px;margin-top:20px;" src="',$profilepic,'" 
height="100px" width="100px" />

<div style="width:350px;margin-left:140px;margin-top:-75px;line-height:1.48;">              

',$firstname,' ',$lastname,'<br />

</div>
</div>';
    
}

elseif($_SESSION['loggedin'] == 1) {
    echo'

    <div class="modal-header" style="background-color:rgba(234,234,234,.9);color:#333;">
    <a style="float:right" class="btn btn-success" data-dismiss="modal">Close</a>
    <img style="margin-top:-2px;" src="graphics/aperture_dark.png" height="34" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:16px;font-family:helvetica,arial;font-weight: 100;">Message ',$firstname,' below</span>
    </div>

    <div modal-body" style="width:450px;height:190px;">

    <div id="content" style="font-size:16px;width:560px;font-family:helvetica,arial;font-weight:300;background-color:rgb(252,252,252);height:190px;">
		
    <img class="roundedall" style="margin-left:20px;margin-top:20px;" src="',$profilepic,'" 
height="100px" width="100px" />

    <div style="width:350px;margin-left:140px;margin-top:-100px;line-height:1.48;font-size:14px;">              

    Message:<br />
    
    <form method="post" action="sendmessage.php" />
        <textarea style="width:360px;height:70px;" name="message"></textarea>
        <br />   
        <button style="float:right;margin-right:-15px;" type="submit" class="btn btn-success">Send</button>
        <input type="hidden" name="emailaddressofviewed" value="',$useremail,'" />
    </form>

    </div>
    </div>';
    
        } 
 ?>

</div>
</div>


<?php navbar(); ?>
    
    <!------------------------WHITE TOP HALF------------------------>
    <div class="tophalf">
        <div class="container_24" style="width:1120px;position:relative;left:30px;">
            
            <!------------------------PROFILE PICTURE------------------------>    
            <div class="profileBox">
                <div id="profilePicture">
                    <!--<div class="storeContainerOverlay">
                        <div style="padding:20px;font-weight:300;font-size:13px;line-height:18px;"> <?php echo $bio;?> </div>
                    </div>-->
                    <img src="https://photorankr.com/<?php echo $profilepic ?>" />
                </div>
                <div id="nameLabel" style="margin-top:-5px;">
                    <header><span style="font-weight:normal;font-size:17px;"><?php echo $userreputation; ?></span> <?php echo $fullname ?></header>
                </div>
                <div id="followBlock">
                
                 <?php
            
                $emailquery=("SELECT * FROM userinfo WHERE emailaddress ='$email'");
                $emailresult=mysql_query($emailquery);
                $prevemails=mysql_result($emailresult, 0, "following");
                $viewerfirst = mysql_result($emailresult, 0, "firstname");
                $viewerlast = mysql_result($emailresult, 0, "lastname");

                $search_string=$prevemails;
                $regex="/$useremail/";
                $match=preg_match($regex,$search_string);
            
                if ($match > 0) {
                echo'
                <a href="viewprofile.php?u=',$userid,'&uf=1" class="buttonNew" style="text-decoration:none;color:#000;width:100px;position:relative;top:-4px;"><img style="width:15px;margin:-5px 4px 0px 2px;" src="graphics/tick 2.png" /> Following </a>';
                
                }
                
                else {
        echo'
				<a data-toggle="modal" data-backdrop="static" href="#fwmodal"  class="buttonNew" style="text-decoration:none;color:#000;width:100px;position:relative;top:-4px;"><img style="width:15px;margin:-5px 4px 0px 2px;" src="graphics/tick 2.png" /> Follow </a>';
                
                }
                
            ?>
                    <a class="buttonNew" style="color:#000;width:100px;text-decoration:none;position:relative;top:-4px;" data-toggle="modal" data-backdrop="static" href="#messagemodal"><img style="width:15px;margin:-5px 4px 0px 2px;" src="graphics/comment_1.png" />Message</a>
                </div>
            </div>
            
        <div class="profileRightSide">
            <!------------------------STATS BOXES------------------------>   
            <div class="smallAboutBox" style="float:left;margin-top:20px;">   
                <div class="smallCornerTab" style="background-color:rgb(240,240,240);">
                    <img src="graphics/camera2.png" /> Snapshot
                </div>
                <ul id="snapshot" style="margin-left:8px;">
                    <li><img style="width:12px;" src="graphics/camera.png"> Photos &mdash; <span style="color:#80A953;font-weight:500;"><?php echo $numphotos; ?></span></li>
                    <li><img style="width:12px;" src="graphics/rank_prof.png"> Avg. Score &mdash;<span style="color:#80A953;font-weight:500;"><?php echo $portfolioranking; ?></span></li>
                    <li><img style="width:14px;margin-left:-2px;" src="graphics/eye.png"> Views &mdash; <span style="color:#80A953;font-weight:500;"><?php echo $profileviews; ?></span></li>
                    <li><img style="width:17px;margin-left:-5px;" src="graphics/groups_b.png"> Followers &mdash;<span style="color:#80A953;font-weight:500;"> <?php echo $numberfollowers; ?></span></li>
                     <li><img style="width:17px;margin-left:-5px;" src="graphics/heart.png"> Favorites &mdash; <span style="color:#80A953;font-weight:500;"><?php echo $portfoliofaves; ?></span></li>
                </ul>
            </div>
            
            <!--------Activity Box------>
            <div class="smallAboutBox" style="float:left;margin-top:20px;margin-left:20px;width:310px;">   
                <div class="smallCornerTab" style="background-color:rgb(240,240,240);">
                    <img src="graphics/graph.png" /> Activity
                </div>

                <div class="uiScrollableAreaTrack invisible_elem" id="activityText">
                    <ul>
                    <?php
                    for($iii=0; $iii <= 20; $iii++) {
                        $firstname = mysql_result($activityquery,$iii,'firstname');
                        $lastname = mysql_result($activityquery,$iii,'lastname');
                        $owneremail = mysql_result($activityquery,$iii,'owner');
                        $fullname = $firstname . " " . $lastname;
                        $fullname = ucwords($fullname);
                        $fullname = (strlen($fullname) > 16) ? substr($fullname,0,14). "&#8230;" : $fullname;
                        $type = mysql_result($activityquery,$iii,'type');
                        $id = mysql_result($activityquery,$iii,'id');
                        $caption = mysql_result($activityquery,$iii,'caption');
                        $source = mysql_result($activityquery,$iii,'source');
                        $time = mysql_result($activityquery,$iii,'time');
                        $time = converttime($time);
                        
                        //Owner Info
                        $getownerinfo = mysql_query("SELECT firstname,lastname,user_id FROM userinfo WHERE emailaddress = '$owneremail'");
                        $ownerfirst = mysql_result($getownerinfo,0,'firstname');
                        $ownerlast = mysql_result($getownerinfo,0,'lastname');
                        $ownerfull = $ownerfirst . " " . $ownerlast;
                        $ownerid = mysql_result($getownerinfo,0,'user_id');
                        
                        $commentphotoquery = mysql_query("SELECT source,id FROM photos WHERE (id = '$source' or source = '$source')");
                        $commentphoto = mysql_result($commentphotoquery,0,'source');
                        $photoid = mysql_result($commentphotoquery,0,'id');

                                    
                        $newsource = str_replace("userphotos/","userphotos/thumbs/", $source);
                        $commentphotosource = str_replace("userphotos/","userphotos/thumbs/", $commentphoto);
                                    
                        $exhibitsource = mysql_query("SELECT cover FROM sets WHERE id = '$source'");
                        $setcover = mysql_result($exhibitsource,$iii,'cover');
                            if(!$setcover) {
                                $pulltopphoto = mysql_query("SELECT source FROM photos WHERE set_id = '$source' ORDER BY votes DESC LIMIT 1");
                                $setcover = mysql_result($pulltopphoto, 0, "source");
                            }
                        $setcover = str_replace("userphotos/","userphotos/thumbs/", $setcover);
                                    
                        $blogcommenteremail = mysql_result($notsquery,$iii,'emailaddress');
                        $followeremail = mysql_result($notsquery,$iii,'emailaddress');
                        $ownermessage = mysql_result($notsquery,$iii,'owner');
                        $thread = mysql_result($notsquery,$iii,'thread');

                        //SEARCH IF ID IS IN UNHIGHLIGHT LIST
                        $match=strpos($whitenlist,$id);
            
                        if($match < 1) {
                            $highlightid = 'greenshadowhighlight';
                        }
                                    
                        elseif($match > 0) {
                            $highlightid = 'greenshadow';
                        }
                        
                        if($type == "comment") {
                           echo'<a style="text-decoration:none;" href="fullsize.php?imageid=',$source,'">
                                <div style="padding:3px;clear:both;overflow:hidden;border-bottom:1px solid #aaa;color:#aaa;padding-left:0px;">
                                 <img style="padding-left:2px;float:left;width:80px;height:80px;" src="http://www.photorankr.com/',$commentphotosource,'" />
                                    <div class="commentTriangle" style="margin-top:-20px;"></div>
                                    <div style="width:200px;float:left;padding-left:10px;height:55px;margin-top:10px;text-align:left;font-size:13px;font-weight:300;color:#333;">
                                        <span style="width:15px;"><img src="graphics/comment_1.png" height="15" />&nbsp;&nbsp;&nbsp;<b>',$fullname,'</b> commented on ',$ownerfull,'\'s photo<br /><span style="font-size:12px;color:#666;font-weight:700;">',$time,'</span></span>
                                    </div>
                                </div>
                            </a>';
                        } //end type comments
                        
                        elseif($type == "fave") {
                            echo'<a style="text-decoration:none;" href="fullsize.php?imageid=',$photoid,'">
                                <div style="padding:3px;clear:both;overflow:hidden;border-bottom:1px solid #aaa;color:#aaa;padding-left:0px;">
                                 <img style="padding-left:2px;float:left;width:80px;height:80px;" src="http://www.photorankr.com/',$newsource,'" />
                                    <div class="commentTriangle" style="margin-top:-20px;"></div>
                                    <div style="width:200px;float:left;padding-left:10px;height:55px;margin-top:10px;text-align:left;font-size:13px;font-weight:300;color:#333;">
                                        <span style="width:15px;"><img src="graphics/heart.png" height="15" />&nbsp;&nbsp;&nbsp;<b>',$fullname,'</b> favorited ',$ownerfull,'\'s photo<br /><span style="font-size:12px;color:#666;font-weight:700;">',$time,'</span></span>
                                    </div>
                                </div>
                            </a>';

                        } //end type faves
                        
                         elseif($type == "exhibitfave") {
                            echo'<a style="text-decoration:none;" href="viewprofile.php?u=',$userid,'&view=exhibits&set=',$source,'&id=',$id,'">
                                <div style="padding:3px;clear:both;overflow:hidden;border-bottom:1px solid #aaa;color:#aaa;padding-left:0px;">
                                 <img style="padding-left:2px;float:left;width:80px;height:80px;" src="http://www.photorankr.com/',$setcover,'" />
                                    <div class="commentTriangle" style="margin-top:-20px;"></div>
                                    <div style="width:200px;float:left;padding-left:10px;height:55px;margin-top:10px;text-align:left;font-size:13px;font-weight:300;color:#333;">
                                        <span style="width:15px;"><img src="graphics/grid.png" height="15" />&nbsp;&nbsp;&nbsp;<b>',$fullname,'</b> favorited your exhibit<br /><span style="font-size:12px;color:#666;font-weight:700;">',$time,'</span></span>
                                    </div>
                                </div>
                            </a>';

                        } //end type exhibit faves
                        
                        elseif($type == "trending") {
                            echo'<a style="text-decoration:none;" href="fullsize.php?image=',$source,'&id=',$id,'">
                                <div style="padding:3px;clear:both;overflow:hidden;border-bottom:1px solid #aaa;color:#aaa;padding-left:0px;">
                                 <img style="padding-left:2px;float:left;width:80px;height:80px;" src="http://www.photorankr.com/',$newsource,'" />
                                    <div class="commentTriangle" style="margin-top:-20px;"></div>
                                    <div style="width:200px;float:left;padding-left:10px;height:55px;margin-top:10px;text-align:left;font-size:13px;font-weight:300;color:#333;">
                                        <span style="width:15px;"><img src="graphics/graph.png" height="15" />&nbsp;&nbsp;&nbsp;Your photo is now trending<br /><span style="font-size:12px;color:#666;font-weight:700;">',$time,'</span></span>
                                    </div>
                                </div>
                            </a>';

                        } //end type trending

                        elseif($type == "follow") {
                            $newaccount = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$followeremail'");
                            $ownerid = mysql_result($newaccount,0,'user_id');
                            $profilepic = mysql_result($newaccount,0,'profilepic');
                            if($profilepic == "") {
                                $profilepic = "profilepics/default_profile.jpg";
                            }
                            
                            echo'<a style="text-decoration:none;color:#333;" href="viewprofile.php?u=',$ownerid,'&id=',$id,'">
                                <div style="padding:3px;clear:both;overflow:hidden;border-bottom:1px solid #aaa;color:#aaa;padding-left:0px;">
                                 <img style="padding-left:2px;float:left;width:80px;height:80px;" src="http://www.photorankr.com/',$profilepic,'" />
                                    <div class="commentTriangle" style="margin-top:-20px;"></div>
                                    <div style="width:200px;float:left;padding-left:10px;height:55px;margin-top:10px;text-align:left;font-size:13px;font-weight:300;color:#333;">
                                        <span style="width:15px;"><img src="graphics/user.png" height="15" />&nbsp;&nbsp;&nbsp;<b>',$fullname,'</b> is now following ',$fullname,'\'s photography<br /><span style="font-size:12px;color:#666;font-weight:700;">',$time,'</span></span>
                                    </div>
                                </div>
                            </a>';

                        } //end type follow
                                                
                    } //end notifications for loop
                    ?>
                    </ul>
                </div>
            </div>
            
            <!--------Network Box------>
            <div class="smallAboutBox" style="float:left;margin-top:20px;margin-left:20px;width:310px;">   
                <div class="smallCornerTab" style="background-color:rgb(240,240,240);">
                    <img style="height:20px;width:20px!important;" src="graphics/user.png" /> Network
                </div>
                
                <div style="width:160px;margin-top:10px;margin-left:145px;text-align:center;">
                        <div style="font-size:14px;color:#666;font-weight:500;">
                            Following <?php echo $numberfollowing; ?>
                        </div>
                </div>
                
                <div id="hoverNet" style="width:310px;margin-top:10px;overflow:hidden;">
                <?php
                    $getnetwork = mysql_query("SELECT following FROM userinfo WHERE emailaddress = '$useremail'");
                    $netlist = mysql_result($getnetwork, 0, "following");
                    $netfollowing = mysql_query("SELECT * FROM userinfo WHERE emailaddress IN ($netlist) ORDER BY reputation DESC LIMIT 15");
                    $numinnet = mysql_num_rows($netfollowing);
                    
                    for($jjj=0;$jjj<15 && $jjj<$numinnet; $jjj++) {
                        $profileshot = mysql_result($netfollowing,$jjj,'profilepic');
                        //$profileshot = str_replace('profilepics/','profilepics/thumbs/',$profileshot);
                        $netid = mysql_result($netfollowing,$jjj,'user_id');
                        
                        echo'<a href="viewprofile.php?u=',$netid,'">
                             <img style="float:left;width:60px;height:57px;padding:1px;" src="',$profileshot,'" />
                             </a>';
                        
                    }
                ?>
                </div>
                
            </div>        
        
        </div><!---end of right side profile-->
        
        <!---------------------NAV ELEMENTS----------------->
         <div class="profileBottomNav">
            <ul>
              <a href="viewprofile.php?u=<?php echo $userid; ?>"><li id="hideViews"><li id="showViews"><?php if($view == '' || $view == 'exhibits' || $view == 'collections') {echo'<div class="oval"><img style="width:15px;padding-bottom:5px;" src="graphics/grid.png" /> Portfolio</div>';} else {echo'<img style="width:15px;padding-bottom:5px;" src="graphics/grid.png" /> Portfolio';} ?></li></a>
                <a href="viewprofile.php?u=<?php echo $userid; ?>&view=store"><li id="hideViews"><?php if($view == 'store') {echo'<div class="oval"><img style="width:15px;padding-bottom:5px;" src="graphics/tag.png" /> Store</div>';} else {echo'<img style="width:15px;padding-bottom:5px;" src="graphics/tag.png" /> Store';} ?></li></a>
                <a href="viewprofile.php?u=<?php echo $userid; ?>&view=faves"><li id="hideViews"><?php if($view == 'faves') {echo'<div class="oval"><img style="width:16px;padding-bottom:5px;" src="graphics/heart.png" /> Favorites</div>';} else {echo'<img style="width:16px;padding-bottom:5px;" src="graphics/heart.png" /> Favorites ';} ?></li></a>
               <a href="viewprofile.php?u=<?php echo $userid; ?>&view=network"> <li id="hideViews"><?php if($view == 'network') {echo'<div class="oval"><img style="width:15px;padding-bottom:5px;" src="graphics/user.png" /> Network</div>';} else {echo'<img style="width:15px;padding-bottom:5px;" src="graphics/user.png" /> Network';} ?></li></a>
                <a href="viewprofile.php?u=<?php echo $userid; ?>&view=about"><li id="hideViews"><?php if($view == 'about') {echo'<div class="oval"><img style="width:7px;padding-bottom:5px;" src="graphics/info.png" /> About</div>';} else {echo'<img style="width:7px;padding-bottom:5px;" src="graphics/info.png" /> About';} ?></li></a>
            </ul>
         </div>

       </div> 
    </div>
    
    <?php flush(); ?>
    
    <!-----------------------PORTFOLIO BOTTOM HALF------------------------>
    <div class="container_24" style="width:1120px;position:relative;left:30px;">
        <!--determine where arrow should be placed based on the view--->
        <div class="upArrow" <?php if($view == '' || $view == 'exhibits' || $view == 'collections') {echo'style="left:400px;"';} 
                                   elseif($view == 'store') {echo'style="left:485px;"';} 
                                   elseif($view == 'faves') {echo'style="left:570px;"';} 
                                   elseif($view == 'network') {echo'style="left:670px;"';} 
                                   elseif($view == 'about') {echo'style="left:760px;"';} 
        ?>></div>
        
        <!-------Hidden box for portfolio views
        <div id="portfolioViews">test</div>--------->
        
        <!--------------------------Portfolio View---------------------------->
        <?php
        if($view == '' || $view == 'exhibits' || $view == 'collections') {   
        
        echo'<div class="portfolioDrop">
                <ul>
                    <li><img style="width:15px;margin-top:-4px;" src="graphics/clock.png"> <a href="viewprofile.php?u=',$userid,'"> Newest </a></li>
                    <li><img style="width:15px;margin-top:-4px;" src="graphics/star.png"> <a href="viewprofile.php?u=',$userid,'&option=top"> Top Ranked </a></li>
                    <li><img style="width:15px;margin-top:-4px;" src="graphics/heart.png"> <a href="viewprofile.php?u=',$userid,'&option=fave"> Most Favorited</a></li>
                    <li><img style="width:15px;margin-top:-4px;" src="graphics/grid.png"> <a href="viewprofile.php?u=',$userid,'&view=exhibits"> Exhibits</a></li>
                    <li><img style="width:15px;margin-top:-4px;" src="graphics/picture.png"> <a href="viewprofile.php?u=',$userid,'&view=collections"> Collections </a></li>
                    <li style="width:240px;">
                    <form method="get" style="display:inline;">
                    <input type="hidden" name="u" value="',$userid,'" />
                    <input type="text" id="searchProf" name="searchword" placeholder="Search Photos &hellip;"/>
                    </form>
                    </li>
                </ul>
             </div>';
            
        }
        
        if($view == '') {
              
        if($option == '') {        
        $query = mysql_query("SELECT * FROM photos WHERE emailaddress = '$useremail' ORDER BY id DESC LIMIT 0,16");
        $numresults = mysql_num_rows($query);
        }
        
        elseif($option == 'top') {
        $query = mysql_query("SELECT * FROM photos WHERE emailaddress = '$useremail' AND views > 20 ORDER BY (points/votes) DESC LIMIT 0,16");
        $numresults = mysql_num_rows($query);
        }
                
        elseif($option == 'fave') {
        $query = mysql_query("SELECT * FROM photos WHERE emailaddress = '$useremail' ORDER BY faves DESC LIMIT 0,16");
        $numresults = mysql_num_rows($query);
        }
        
        if($searchword) {
         $query = mysql_query("SELECT * FROM photos WHERE emailaddress = '$useremail' AND concat(tag1,tag,2,tag3,tag4,singlestyletags,singlecategorytags,caption) LIKE '%$searchword%' ORDER BY id DESC LIMIT 0,16");
        $numresults = mysql_num_rows($query);
        }

    echo'
    <div id="thepics" style="position:relative;left:-25px;top:10px;width:1185px;">
    <div id="main">
    <ul id="tiles">';

        for($iii=0; $iii < $numresults; $iii++) {
              
                $image = mysql_result($query, $iii, "source");
                $imageThumb = str_replace("userphotos/","userphotos/medthumbs/", $image);
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
                $caption = (strlen($caption) > 25) ? substr($caption,0,23). "&#8230;" : $caption;
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
    if($option == 'fave') {
            echo'<a style="text-decoration:none;color:#333;" href="fullsizeview.php?imageid=',$id,'&v=n"><li class="fPic" id="',$faves,'" style="list-style-type: none;width:280px;"><img style="min-width:280px;" onmousedown="return false" oncontextmenu="return false;"  src="https://photorankr.com/',$imageThumb,'" height="',$heightls,'px" width="',$widthls,'px" />
            
            <div class="statoverlay" style="z-index:1;background-color:white;position:relative;top:0px;width:280px;height:30px;"><div style="line-spacing:1.48;padding:5px;color:#4A4A4A;"><div style="float:left;"><img style="box-shadow:none;width:15px;" src="graphics/heart.png" /><span style="font-size:16px;font-weight:bold;"> ',$faves,'</span>&nbsp;&nbsp;<span style="font-weight:bold;font-size:12px;">',$caption,'</span></div></div><br/></div>'; 
        }
        
        elseif($option == 'top') {
           echo'<a style="text-decoration:none;color:#333;" href="fullsizeview.php?imageid=',$id,'&v=n"><li class="fPic" id="',$score,'" style="list-style-type: none;width:280px;"><img style="min-width:280px;" onmousedown="return false" oncontextmenu="return false;"  src="https://photorankr.com/',$imageThumb,'" height="',$heightls,'px" width="',$widthls,'px" />
             
             <div class="statoverlay" style="z-index:1;background-color:white;position:relative;top:0px;width:280px;height:30px;"><div style="line-spacing:1.48;padding:5px;color:#4A4A4A;"><div style="float:left;"<span style="font-size:16px;font-weight:bold;">',$score,'</span>&nbsp;&nbsp;<span style="font-weight:bold;font-size:12px;">',$caption,'</span></div></div><br/></div>';  
        }
        
        else{
             echo'<a style="text-decoration:none;color:#333;" href="fullsizeview.php?imageid=',$id,'&v=n"><li class="fPic" id="',$id,'" style="list-style-type: none;width:280px;"><img style="min-width:280px;" onmousedown="return false" oncontextmenu="return false;" src="https://photorankr.com/',$imageThumb,'" height="',$heightls,'px" width="',$widthls,'px" />
             
             <div class="statoverlay" style="z-index:1;background-color:white;position:relative;top:0px;width:280px;height:30px;"><div style="line-spacing:1.48;padding:5px;color:#4A4A4A;"><div style="float:left;"<span style="font-size:16px;font-weight:bold;">',$score,'</span>&nbsp;&nbsp;<span style="font-weight:bold;font-size:12px;">',$caption,'</span></div></div><br/></div>';
        }
            
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
   <div class="grid_6 push_11" style="padding-top:25px;padding-bottom:25px;">
   <div id="loadMorePics" style="display: none; text-align: center;font-family:arial,helvetica neue; font-size:15px;"><img style="width:50px;" src="graphics/LoadingGIF.gif" /></div>
   </div>';


echo '<script>

var last = 0;

	$(window).scroll(function(){
		if($(window).scrollTop() > $(document).height() - $(window).height()-100) {
			if(last != $(".fPic:last").attr("id")) {
				$("div#loadMorePics").show();
				$.ajax({
					url: "loadMorePortfolioPicsVP3.php?lastPicture=" + $(".fPic:last").attr("id")+"&option=',$option,'"+"&emailaddress=',$useremail,'"+"&searchword=',$searchword,'",
					success: function(html) {
						if(html) {
							$("#thepics").append(html);
							$("div#loadMorePics").hide();
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
        
    <!--------------------------Collections View---------------------------->
    <?php
    if($view == 'collections') {  
            
                echo'<div style="width:1150px;">';
        
        
    if(isset($_GET['set'])){
		$set = mysql_real_escape_string($_GET['set']);
	}
        
         if($set == '') {
        
        $getcollections = mysql_query("SELECT * FROM collections WHERE owner = '$useremail'");
        $numcollections = mysql_num_rows($getcollections);
        
            if($numcollections < 1) {
               echo'<div style="font-size:18px;font-weight:200;padding:40px;text-align:center;margin-left:-35px;margin-top:120px;">',$usersfirst,' has no collections </div>';
            
            }
        
        if($set == '' && $numcollections > 0) {
        
        for($iii=0; $iii < $numcollections; $iii++) {
            $setname = mysql_result($getcollections, $iii, "title");
            $setcover = mysql_result($getcollections, $iii, "cover");
            $setabout = mysql_result($getcollections, $iii, "about");
            $setviews = mysql_result($getcollections, $iii, "views");
            $setfaves = mysql_result($getcollections, $iii, "faves");
            $set_id[$iii] = mysql_result($getcollections, $iii, "id");
            $setname2[$iii] = (strlen($setname[$iii]) > 30) ? substr($setname[$iii],0,27). " &#8230;" : $setname[$iii];
            $photos = mysql_result($getcollections, $iii, "photos");
            $photos = explode(" ",$photos);
            $numphotos = count($photos) - 1;
            $pulltopphoto = mysql_query("SELECT source FROM photos WHERE id = '$photos[0]' ORDER BY votes DESC LIMIT 10");
            if($setcover == '') {
                $setcover = mysql_result($pulltopphoto, 0, "source");
                if($setcover == '') {
                     $setcover = 'graphics/no_photos.png';
                }
            }
            
            echo'<div class="exhibitBox">
                <div class="exhibitInfo">
                    <header>',$setname,'</header>
                    <div id="exhibitStats">Views: ',$setviews,' Photos: ',$numphotos,'</div>
                    <p>',$setabout,'</p>
                </div>
                <a style="text-decoration:none;" href="viewprofile.php?u=',$userid,'&view=collections&set=',$set_id[$iii],'">
                <img style="width:280px;height:280px;" src="https://photorankr.com/',$setcover,'" />
                <div class="exhibitSmallBox" style="float:left;width:580px;height:280px;">';
                
                for($jjj=1; $jjj < 9 && $jjj < $numphotos; $jjj++) {
                $grabphotosrun = mysql_query("SELECT source FROM photos WHERE id = '$photos[$jjj]'");
                $insetname = mysql_result($grabphotosrun, 0, "caption");
                $insetsource = mysql_result($grabphotosrun, 0, "source");
                $newsource = str_replace("userphotos/","userphotos/medthumbs/", $insetsource);
    
                echo'
                    <div>
                        <img style="float:left;padding:2px;" onmousedown="return false" oncontextmenu="return false;"  src="http://www.photorankr.com/',$newsource,'" width="136" height="136" />
                    </div>';
            }
                
                echo'
                </div>
                </a>
                </div>';
        }
        
        }
        
        } //end of set == ''

    elseif($set != '') {

//increment exhibit view count
$updateexviews = mysql_query("UPDATE collections SET views = (views+1) WHERE id = '$set'"); 

//grab all photos in the exhibit
$grabphotos = mysql_query("SELECT * FROM collections WHERE owner = '$useremail' AND id = '$set'");

//grab about this set
$aboutset = "SELECT * FROM collections WHERE owner = '$useremail' AND id = '$set' LIMIT 0,1";
$aboutsetrun = mysql_query($aboutset);
$aboutarray = mysql_fetch_array($aboutsetrun);
$aboutset = $aboutarray['about'];
$settitle = $aboutarray['title'];
$setcover = $aboutarray['cover'];
if($setcover == '') {
$setcover = 'profilepics/nocoverphoto.png';
}

echo'<div class="grid_18" style="width:770px;margin-left:-20px;padding:15px;position:relative;clear:both;">

<div class="grid_18 well" style="position:relative;clear:both;width:1060px;line-height:25px;margin-top:15px;"><span style="font-size:25px;font-family:helvetica,arial;font-weight:200;">',$settitle,'</span><br />';
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
        
        echo'<a href="viewprofile.php?u=',$faceid,'"><img style="padding:3px;" src="',$facepic,'" height="50" /></a>';
    
    }
    
    echo'</div>';

echo'
    </div>

    <div id="thepics" style="position:relative;left:-22px;top:10px;width:1185px;clear:both;">
    <div id="main" role="main">
    <ul id="tiles">';

    $photos = mysql_result($grabphotos, 0, "photos");
    $photos = explode(" ",$photos);
    $numphotos = count($photos);
    
    for($jjj=0; $jjj<$numphotos-1; $jjj++) {
    $grabphotosrun = mysql_query("SELECT * FROM photos WHERE id = '$photos[$jjj]'");
    $insetname = mysql_result($grabphotosrun, 0, "caption");
    $insetsource = mysql_result($grabphotosrun, 0, "source");
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
    $heightls = $height / 3.3;
    $widthls = $width / 3.3;
    if($widthls < 235) {
    $heightls = $heightls * ($heightls/$widthls);
    $widthls = 280;
    }

		echo '
        <a style="text-decoration:none;color:#333;" href="fullsize.php?imageid=',$insetid,'&v=n"><li class="fPic" id="',$id,'" style="list-style-type: none;width:280px;"><img style="min-width:280px;" onmousedown="return false" oncontextmenu="return false;"  src="',$newsource,'" height="',$heightls,'px" width="',$widthls,'px" />
        
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
      
    } //end of collections view

?>
        
        
         <!--------------------------Store View---------------------------->
        <?php
        if($view == 'store') {  
        
        echo'<div style="width:1180px;overflow:hidden;position:relative;left:-40px;top:8px;">';
              
        if($option == '') {
            $query = mysql_query("SELECT * FROM photos WHERE emailaddress = '$useremail' ORDER BY id DESC LIMIT 16");
        }
        elseif($option == 'faved') {
            $query = mysql_query("SELECT * FROM photos WHERE emailaddress = '$useremail' ORDER BY faves DESC LIMIT 16");
        }
        elseif($option == 'top') {
            $query = mysql_query("SELECT * FROM photos WHERE emailaddress = '$useremail' ORDER BY (points/votes) DESC LIMIT 16");
        }
        elseif($option == 'sold') {
            $query = mysql_query("SELECT * FROM photos WHERE emailaddress = '$useremail' AND sold = 1 ORDER BY id DESC LIMIT 16");
        }
        $numresults = mysql_num_rows($query);
        
    echo'
    <div id="thepics" style="float:left;top:10px;width:910px;min-height:600px;">
    <div id="main">
    <ul id="tiles">';
    
        //Haven't sold anything
        if($option == 'sold' && $numresults == 0) {
            echo'<div style="margin-left:300px;width:300px;height:300px;margin-top:180px;">
                    <div style="float:center;text-align:center;"><img style="width:60px;" src="graphics/bag.png" /></div>
                    <br />
                    <div style="float:center;text-align:center;font-size:18px;font-weight:300;line-height:24px;">
                    ',$usersfirst,' has no sales yet
                    </div>
                </div>';
        }

        for($iii=0; $iii < $numresults; $iii++) {
              
                $image = mysql_result($query, $iii, "source");
                $imageThumb = str_replace("userphotos/","userphotos/medthumbs/", $image);
                $id = mysql_result($query, $iii, "id");
                $price = mysql_result($query, $iii, "price");
                $caption = mysql_result($query, $iii, "caption");
                $caption = (strlen($caption) > 25) ? substr($caption,0,23). "&#8230;" : $caption;
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

		if($option == '' || $option == 'sold') {
            echo'<a style="text-decoration:none;color:#333;" href="fullsize.php?imageid=',$id,'&v=n"><li class="fPic" id="',$id,'" style="list-style-type: none;width:280px;"><img style="min-width:280px;" onmousedown="return false" oncontextmenu="return false;" src="https://photorankr.com/',$imageThumb,'" height="',$heightls,'px" width="',$widthls,'px" />
            
            <div class="statoverlay" style="z-index:1;background-color:white;position:relative;top:0px;width:280px;height:30px;"><div style="line-spacing:1.48;padding:5px;color:#4A4A4A;"><div style="float:left;"><img style="box-shadow:none;width:15px;" src="graphics/tag.png" /><span style="font-size:16px;font-weight:bold;"> $',$price,'</span>&nbsp;&nbsp;<span style="font-weight:bold;font-size:12px;">',$caption,'</span></div></div><br/></div>'; 
            }
            
            elseif($option == 'faved') {
            echo'<a style="text-decoration:none;color:#333;" href="fullsize.php?imageid=',$id,'&v=n"><li class="fPic" id="',$faves,'" style="list-style-type: none;width:280px;"><img style="min-width:280px;" onmousedown="return false" oncontextmenu="return false;" src="https://photorankr.com/',$imageThumb,'" height="',$heightls,'px" width="',$widthls,'px" />
            
            <div class="statoverlay" style="z-index:1;background-color:white;position:relative;top:0px;width:280px;height:30px;"><div style="line-spacing:1.48;padding:5px;color:#4A4A4A;"><div style="float:left;"><img style="box-shadow:none;width:15px;" src="graphics/tag.png" /><span style="font-size:16px;font-weight:bold;"> $',$price,'</span>&nbsp;&nbsp;<span style="font-weight:bold;font-size:12px;">',$caption,'</span><img style="margin-left:10px;box-shadow:none;width:13px;" src="graphics/heart.png" /> ',$faves,'</div></div><br/></div>'; 
            }
            
            elseif($option == 'top') {
            echo'<a style="text-decoration:none;color:#333;" href="fullsize.php?imageid=',$id,'&v=n"><li class="fPic" id="',$score,'" style="list-style-type: none;width:280px;"><img style="min-width:280px;" onmousedown="return false" oncontextmenu="return false;" src="https://photorankr.com/',$imageThumb,'" height="',$heightls,'px" width="',$widthls,'px" />
            
            <div class="statoverlay" style="z-index:1;background-color:white;position:relative;top:0px;width:280px;height:30px;"><div style="line-spacing:1.48;padding:5px;color:#4A4A4A;"><div style="float:left;"><img style="box-shadow:none;width:15px;" src="graphics/tag.png" /><span style="font-size:16px;font-weight:bold;"> $',$price,'</span>&nbsp;&nbsp;<span style="font-weight:bold;font-size:12px;">',$caption,'</span>&nbsp;&nbsp;&nbsp;&nbsp; ',$score,'</div></div><br/></div>'; 
            }      	
            
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
   <div class="grid_6 push_11" style="padding-top:25px;padding-bottom:25px;">
   <div id="loadMorePics" style="display: none; text-align: center;font-family:arial,helvetica neue; font-size:15px;"><img style="width:50px;" src="graphics/LoadingGIF.gif" /></div>
   </div>';


echo '<script>

var last = 0;

	$(window).scroll(function(){
		if($(window).scrollTop() > $(document).height() - $(window).height()-100) {
			if(last != $(".fPic:last").attr("id")) {
				$("div#loadMorePortfolioPics").show();
				$.ajax({
					url: "loadMorePortfolioPicsVP3.php?lastPicture=" + $(".fPic:last").attr("id")+"&view=',$view,'"+"&option=',$option,'"+"&emailaddress=',$useremail,'",
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

echo'<div class="grid_6 filled rounded shadow" style="float:left;width:240px;margin-top:-50px;">';
        
        echo'<div class="cartText" style="padding:10px;">',$usersfirst,'\'s Store</div>';

         //Search the Store
        echo'<div class="grid_6" style="">
            <form action="#" method="GET">
                <input id="searchStore" name="searchword" placeholder="Search ',$usersfirst,'\'s store&hellip;" type="text" />
            </form>
        </div>';
        
        echo'
            <ul>
                <li id="stattitle"># Photos: <span id="stat">',$numphotos,'</span></li>
                <li id="stattitle">Avg. Photo Price: <span id="stat">$',$avgprice,'</span></li>
                <li id="stattitle">Avg. Portfolio Score: <span id="stat">',$portfolioranking,'</span></li>
                <li id="stattitle">Avg. Resolution: <span id="stat">',$avgwidth,' X ',$avgheight,'</span></li>
        </div>';
        
    //Store Filters
     echo'<div class="grid_6" style="float:left;width:240px;">';
        if($option== '') {
            echo'<a style="text-decoration:none;" href="viewprofile.php?u=',$userid,'&view=store"><div class="rectangle"><div style="padding-top:7px;text-align:center;">Newest</div><div class="arrow-leftStore" style="float:right;margin-right:288px;margin-top:-21px;"></div></div></a>';
        }
        
        else {
             echo'<a style="text-decoration:none;" href="viewprofile.php?u=',$userid,'&view=store"><div class="rectangle"><div style="padding-top:7px;text-align:center;">Newest</div></div></a>';
        }
        
        if($option == 'faved') {
            echo'<a style="text-decoration:none;" href="viewprofile.php?u=',$userid,'&view=store&option=faved"><div class="rectangle"><div style="padding-top:7px;text-align:center;">Most Favorited</div><div class="arrow-leftStore" style="float:right;margin-right:288px;margin-top:-21px;"></div></div></a>';
        }
        
        else {
             echo'<a style="text-decoration:none;" href="viewprofile.php?u=',$userid,'&view=store&option=faved"><div class="rectangle"><div style="padding-top:7px;text-align:center;">Most Favorited</div></div></a>';
        }
        
        if($option == 'top') {
            echo'<a style="text-decoration:none;" href="viewprofile.php?u=',$userid,'&view=store&option=top"><div class="rectangle"><div style="padding-top:7px;text-align:center;">Top Ranked</div><div class="arrow-leftStore" style="float:right;margin-right:288px;margin-top:-21px;"></div></div></a>';
        }
        
        else {
             echo'<a style="text-decoration:none;" href="viewprofile.php?u=',$userid,'&view=store&option=top"><div class="rectangle"><div style="padding-top:7px;text-align:center;">Top Ranked</div></div></a>';
        }
        
        if($option == 'sold') {
            echo'<a style="text-decoration:none;" href="viewprofile.php?u=',$userid,'&view=store&option=sold"><div class="rectangle"><div style="padding-top:7px;text-align:center;">Recently Sold</div><div class="arrow-leftStore" style="float:right;margin-right:288px;margin-top:-21px;"></div></div></a>';
        }
        
        else {
             echo'<a style="text-decoration:none;" href="viewprofile.php?u=',$userid,'&view=store&option=sold"><div class="rectangle"><div style="padding-top:7px;text-align:center;">Recently Sold</div></div></a>';
        }
        
    echo'</div>';


            echo'</div>
                 </div>';

        } //end of view == 'store'
                
    ?>
    
    
    <!--------------------------Favorites View---------------------------->
        <?php
        if($view == 'faves') {   
              
        $favesquery = "SELECT faves FROM userinfo WHERE emailaddress='$useremail' LIMIT 0, 1";
        $favesresult = mysql_query($favesquery) or die(mysql_error());
        $faves = mysql_result($favesresult, 0, "faves");
        
        $query = mysql_query("SELECT * FROM photos WHERE source IN ($faves) ORDER BY FIELD (source, $faves) DESC LIMIT 16");
        $numresults = mysql_num_rows($query);
                
    echo'
    <div id="thepics" style="position:relative;left:-25px;top:10px;width:1185px;">
    <div id="main">
    <ul id="tiles">';

        for($iii=0; $iii < $numresults; $iii++) {
                $image = mysql_result($query, $iii, "source");
                $imageThumb = str_replace("userphotos/","userphotos/medthumbs/", $image);
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
        <a style="text-decoration:none;color:#333;" href="fullsize.php?imageid=',$id,'&v=n"><li class="fPic" id="',$id,'" style="list-style-type: none;width:280px;"><img style="min-width:280px;" onmousedown="return false" oncontextmenu="return false;"  src="https://photorankr.com/',$imageThumb,'" height="',$heightls,'px" width="',$widthls,'px" />
        
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
   <div class="grid_6 push_11" style="padding-top:25px;padding-bottom:25px;">
   <div id="loadMorePics" style="display: none; text-align: center;font-family:arial,helvetica neue; font-size:15px;"><img style="width:50px;" src="graphics/LoadingGIF.gif" /></div>
   </div>';


echo '<script>

var last = 0;

	$(window).scroll(function(){
		if($(window).scrollTop() > $(document).height() - $(window).height()-100) {
			if(last != $(".fPic:last").attr("id")) {
				$("div#loadMorePics").show();
				$.ajax({
					url: "loadMoreFavePicsVP3.php?lastPicture=" + $(".fPic:last").attr("id")+"&user=',$userid,'",
					success: function(html) {
						if(html) {
							$("#thepics").append(html);
							$("div#loadMorePics").hide();
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

    
    <?php
      /*--------------------------Network View----------------------------*/
      if($view == 'network') {
      
          echo'<div class="portfolioDrop">
                <ul>
                    <li><a href="viewprofile.php?u=',$userid,'&view=network">Following</a></li>
                    <li><a href="viewprofile.php?u=',$userid,'&view=network&option=followers">Followers</a></li>
                </ul>
             </div>';
      
         if($option == '') {
            $query = mysql_query("SELECT following FROM userinfo WHERE emailaddress = '$useremail'");
            $followinglist = mysql_result($query, 0, "following");
            $followingquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress IN ($followinglist)");
            $numberfollowing = mysql_num_rows($followingquery);
        }
        elseif($option == 'followers') {
            $followersquery="SELECT * FROM userinfo WHERE following LIKE '%$useremail%'";
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
                    <div style="float:right;margin-top:7px;margin-right:15px;">';
                        if($facebookpage) {echo'<a href="',$facebookpage,'"><img src="https://photorankr.com/graphics/facebook.png" style="width:30px;height:30px;margin: 7px 9px 0px 10px;"></a>';}
                        if($twitterpage) {echo'<a href="',$twitterpage,'"><img src="https://photorankr.com/graphics/twitter.png" style="width:30px;height:30px;margin: 7px 9px 0px 10px;"></a>';}
                        if($pinterest) {echo'<a href="',$pinterest,'"><img src="https://photorankr.com/graphics/pinterest.png" style="width:30px;height:30px;margin: 7px 9px 0px 10px;"></a>';}
                        if($googleplus) {echo'<a href="',$googleplus,'"><img src="https://photorankr.com/graphics/g+r.png" style="width:30px;height:30px;margin: 7px 9px 0px 10px;"></a>';}
                        echo'
                    </div>
                </div>
                
    <!--------EDIT INFO--------------->';
            
    echo'<!---About List--->
    <div id="aboutList">
        <table class="table" style="clear:both;font-size:13px;font-weight:300;line-height:20px;margin-top:5px;">
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
        
        
      } //end of about view
    ?>
    
      <?php
      /*--------------------------Exhibit View----------------------------*/
      if($view == 'exhibits') {
      
        echo'<div style="width:1150px;">';
        
        
    if(isset($_GET['set'])){
		$set = mysql_real_escape_string($_GET['set']);
	}
    
        
        //select all exhibits of user
        $allsetsquery = "SELECT * FROM sets WHERE owner = '$useremail'";
        $allsetsrun = mysql_query($allsetsquery);
        $numbersets = mysql_num_rows($allsetsrun);
        
        //if no sets, propmt them to create one
        $numbersets = mysql_num_rows($allsetsrun);
        if($numbersets == 0) {
            echo'<div style="font-size:18px;font-weight:200;padding:40px;text-align:center;margin-left:-35px;margin-top:120px;">',$usersfirst,' has no exhibits </div>';
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
                <a style="text-decoration:none;" href="viewprofile.php?u=', $userid,'&view=exhibits&set=',$set_id[$iii],'">
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

//increment exhibit view count
$updateexviews = mysql_query("UPDATE sets SET views = (views+1) WHERE id = '$set'"); 

//grab all photos in the exhibit
$grabphotos = "SELECT * FROM photos WHERE emailaddress = '$useremail' AND set_id LIKE '%$set%'";
$grabphotosrun = mysql_query($grabphotos);
$numphotosgrabbed = mysql_num_rows($grabphotosrun);

//grab about this set
$aboutset = "SELECT * FROM sets WHERE owner = '$useremail' AND id = '$set' LIMIT 0,1";
$aboutsetrun = mysql_query($aboutset);
$aboutarray = mysql_fetch_array($aboutsetrun);
$aboutset = $aboutarray['about'];
$settitle = $aboutarray['title'];
$setcover = $aboutarray['cover'];
$setfaves = $aboutarray['faves'];
if($setcover == '') {
$setcover = 'profilepics/nocoverphoto.png';
}

echo'<div class="grid_18" style="width:770px;margin-left:-20px;padding:35px;position:relative;clear:both;">

<div class="grid_18 well" style="position:relative;clear:both;width:1060px;line-height:25px;margin-top:-15px;"><span style="font-size:25px;font-family:helvetica,arial;font-weight:200;">',$settitle,'</span><br />
 <div style="clear:both;padding-left:5px;padding-top:15px;">
    <a class="btn btn-danger" data-toggle="modal" data-backdrop="static" href="#exfvmodal" style="padding: .45em 2em .45em 2em;margin-left:-5px;margin-right:5px;"><img src="graphics/heart_white.png" style="width:18px;float:right;"/></a>
        <span style="font-size:14px;font-family:helvetica,arial;font-weight:400;">&nbsp;Favorites: ',$setfaves,'</span>
        </div>';
        
    if($aboutset) {echo'
        <br />
       <span style="font-size:16px;font-family:helvetica,arial;font-weight:200;">',$aboutset,'</span>';
    }
echo'</div>

    <div id="thepics" style="position:relative;left:-25px;top:10px;width:1185px;clear:both;">
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
    $insetid = mysql_result($grabphotosrun, $iii, "id");
    
      list($width, $height) = getimagesize($insetsource);
	$imgratio = $height / $width;
    $heightls = $height / 3.3;
    $widthls = $width / 3.3;
    if($widthls < 235) {
    $heightls = $heightls * ($heightls/$widthls);
    $widthls = 280;
    }

		echo '
        <a style="text-decoration:none;color:#333;" href="fullsize.php?imageid=',$insetid,'&v=n"><li class="fPic" id="',$id,'" style="list-style-type: none;width:280px;"><img style="min-width:280px;" src="',$newsource,'" height="',$heightls,'px" width="',$widthls,'px" />
        
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
