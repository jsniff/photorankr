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

    //notifications query reset 
    if($currentnotsresult > 0) {
    $notsquery = "UPDATE userinfo SET notifications = 0 WHERE emailaddress = '$email6'";
    $notsqueryrun = mysql_query($notsquery); }
    
    //GRAB USER INFORMATION
   $userid = htmlentities($_GET['u']);
   if(!$userid) {
        header('Location: http://www.photorankr.com/trending.php');
   }

   //Query Stats Table 
   $timestampentertimeslicequery="INSERT INTO Statistics (ViewTimeStamp, Person, Type, user_id) VALUES ('$currenttime', '$email', 'profileview', '$userid')";
   $timestampquery= mysql_query($timestampentertimeslicequery);


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
$reputation = number_format($reputation,1);
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

ollow;
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

<?php
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
                <div id="nameLabel" style="margin-top:0px;">
                    <header><span style="font-weight:normal;font-size:17px;"><?php echo $reputation; ?></span> <?php echo $fullname ?></header>
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
                <a href="viewprofile.php?u=',$userid,'&uf=1" class="buttonNew" style="text-decoration:none;color:#000;width:100px;"><img style="width:15px;margin:-5px 4px 0px 2px;" src="graphics/tick 2.png" /> Following </a>';
                
                }
                
                else {
        echo'
				<a data-toggle="modal" data-backdrop="static" href="#fwmodal"  class="buttonNew" style="text-decoration:none;color:#000;width:100px;"><img style="width:15px;margin:-5px 4px 0px 2px;" src="graphics/tick 2.png" /> Follow </a>';
                
                }
                
            ?>
                    <a class="buttonNew" style="color:#000;width:100px;text-decoration:none;" data-toggle="modal" data-backdrop="static" href="#messagemodal"><img style="width:15px;margin:-5px 4px 0px 2px;" src="graphics/comment_1.png" />Message</a>
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
            
            <!--------Status Box------>
            <?php
                 $getStatuses = mysql_query("SELECT * FROM statuses WHERE emailaddress = '$useremail' ORDER BY id DESC LIMIT 6");
                 $numstatuses = mysql_num_rows($getStatuses);
            ?>
            <div class="smallAboutBox uiScrollableAreaTrack invisible_elem" style="float:left;margin-top:20px;margin-left:20px;width:310px;overflow-y:scroll;">   
                <?php 
                    if($numstatuses < 1) {
                        
                        echo'<div style="width:180px;height:40px;margin-left:100px;margin-top:60px;font-size:16px;font-weight:300;">
                                No updates yet&hellip;
                             </div>';
                    
                    }
                ?>
                        <!--AJAX COMMENTS-->
                        <div class="float:left;"> 
                            <ol id="update" class="timeline">
                            </ol>
                        </div>
                        
                        <!---Previous Statuses--->
                        <?php
                        for($ii=0; $ii < $numstatuses; $ii++) {
                            $status = mysql_result($getStatuses,$ii,'status');
                            $statustime =  mysql_result($getStatuses,$ii,'time');
                            $statustime = converttime($statustime);
                            echo'<div class="status ">
                                    <img style="width:15px;margin:-5px 4px 0px 2px;" src="graphics/comment_1.png" />',$status,'
                                    <span style="float:right;font-weight:500;color:#666;font-size:11px;padding-right:2px;">',$statustime,'</span>
                                 </div>';
                        }
                        ?>
            </div>
        
        
        </div><!---end of right side profile-->
        
        <!---------------------NAV ELEMENTS----------------->
         <div class="profileBottomNav">
            <ul>
              <a href="viewprofile.php?u=<?php echo $userid; ?>"><li id="hideViews"><li id="showViews"><?php if($view == '') {echo'<div class="oval"><img style="width:15px;padding-bottom:5px;" src="graphics/grid.png" /> Portfolio</div>';} else {echo'<img style="width:15px;padding-bottom:5px;" src="graphics/grid.png" /> Portfolio';} ?></li></a>
                <a href="viewprofile.php?u=<?php echo $userid; ?>&view=store"><li id="hideViews"><?php if($view == 'store') {echo'<div class="oval"><img style="width:15px;padding-bottom:5px;" src="graphics/tag.png" /> Store</div>';} else {echo'<img style="width:15px;padding-bottom:5px;" src="graphics/tag.png" /> Store';} ?></li></a>
                <a href="viewprofile.php?u=<?php echo $userid; ?>&view=faves"><li id="hideViews"><?php if($view == 'faves') {echo'<div class="oval"><img style="width:16px;padding-bottom:5px;" src="graphics/heart.png" /> Favorites</div>';} else {echo'<img style="width:16px;padding-bottom:5px;" src="graphics/heart.png" /> Favorites ';} ?></li></a>
               <a href="viewprofile.php?u=<?php echo $userid; ?>&view=network"> <li id="hideViews"><?php if($view == 'network') {echo'<div class="oval"><img style="width:15px;padding-bottom:5px;" src="graphics/user.png" /> Network</div>';} else {echo'<img style="width:15px;padding-bottom:5px;" src="graphics/user.png" /> Network';} ?></li></a>
                <a href="viewprofile.php?u=<?php echo $userid; ?>&view=about"><li id="hideViews"><?php if($view == 'about') {echo'<div class="oval"><img style="width:7px;padding-bottom:5px;" src="graphics/info.png" /> About</div>';} else {echo'<img style="width:7px;padding-bottom:5px;" src="graphics/info.png" /> About';} ?></li></a>
            </ul>
         </div>

       </div> 
    </div>
    
    <!-----------------------PORTFOLIO BOTTOM HALF------------------------>
    <div class="container_24" style="width:1120px;position:relative;left:30px;">
        <!--determine where arrow should be placed based on the view--->
        <div class="upArrow" <?php if($view == '') {echo'style="left:400px;"';} 
                                   elseif($view == 'store') {echo'style="left:485px;"';} 
                                   elseif($view == 'faves') {echo'style="left:570px;"';} 
                                   elseif($view == 'network') {echo'style="left:670px;"';} 
                                   elseif($view == 'about') {echo'style="left:760px;"';} 
                                   elseif($view == 'messages') {echo'style="left:845px;"';} 
                                   elseif($view == 'settings') {echo'style="left:945px;"';} 
        ?>></div>
        
        <!-------Hidden box for portfolio views
        <div id="portfolioViews">test</div>--------->
        
        <!--------------------------Portfolio View---------------------------->
        <?php
        if($view == '') {   
        
        echo'<div class="portfolioDrop">
                <ul>
                    <li><img style="width:15px;margin-top:-4px;" src="graphics/clock.png"> <a href="viewprofile.php?u=',$userid,'"> Newest </a></li>
                    <li><img style="width:15px;margin-top:-4px;" src="graphics/star.png"> <a href="viewprofile.php?u=',$userid,'&option=top"> Top Ranked </a></li>
                    <li><img style="width:15px;margin-top:-4px;" src="graphics/heart.png"> <a href="viewprofile.php?u=',$userid,'&option=fave"> Most Favorited</a></li>
                    <li><img style="width:15px;margin-top:-4px;" src="graphics/grid.png"> <a href="viewprofile.php?u=',$userid,'&view=exhibits"> Exhibits</a></li>
                    <li><img style="width:15px;margin-top:-4px;" src="graphics/picture.png"> <a href="viewprofile.php?u=',$userid,'&view=collections"> Collections </a></li>
                    <li style="width:240px;">
                    <form method="get" style="display:inline;">
                    <input type="text" id="searchProf" placeholder="Search Photos &hellip;"/>
                    </form>
                    </li>
                </ul>
             </div>';
              
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
        
    echo'
    <div id="thepics" style="position:relative;left:-60px;top:10px;width:1250px;">
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
					url: "loadMorePortfolioPicsVP3.php?lastPicture=" + $(".fPic:last").attr("id"),
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
        
        
         <!--------------------------Store View---------------------------->
        <?php
        if($view == 'store') {  
        
        echo'<div style="width:1180px;overflow:hidden;position:relative;left:-40px;top:8px;">';
              
        if($option == '') {
            $query = mysql_query("SELECT * FROM photos WHERE emailaddress = '$useremail' ORDER BY id DESC");
        }
        elseif($option == 'faved') {
            $query = mysql_query("SELECT * FROM photos WHERE emailaddress = '$useremail' ORDER BY faves DESC");
        }
        elseif($option == 'top') {
            $query = mysql_query("SELECT * FROM photos WHERE emailaddress = '$useremail' ORDER BY (points/votes) DESC");
        }
        elseif($option == 'sold') {
            $query = mysql_query("SELECT * FROM photos WHERE emailaddress = '$useremail' AND sold = 1 ORDER BY (points/votes) DESC");
        }
            $numresults = mysql_num_rows($query);
        
    echo'
    <div id="thepics" style="float:left;top:10px;width:910px;">
    <div id="main">
    <ul id="tiles">';

        for($iii=0; $iii < $numresults && $iii < 15; $iii++) {
              
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
        <a style="text-decoration:none;color:#333;" href="fullsizemarket.php?imageid=',$id,'&v=n"><li class="fPic" id="',$id,'" style="list-style-type: none;width:280px;"><img style="min-width:280px;" src="https://photorankr.com/',$imageThumb,'" height="',$heightls,'px" width="',$widthls,'px" />
        
            <div class="statoverlay" style="z-index:1;background-color:rgba(0,0,0,.8);position:relative;top:0px;width:280px;height:30px;"><div style="line-spacing:1.48;padding:5px;color:white;"><div style="float:left;"<span style="font-size:18px;font-weight:100;">$',$price,'</span>&nbsp;&nbsp;<span style="font-weight:100;font-size:16px;">',$caption,'</span></div><div style="float:right;"><span style="font-size:13px;">',$price,'</span></div></div><br/></div>';       	
            
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
					url: "loadMorePortfolioPicsVP3.php?lastPicture=" + $(".fPic:last").attr("id"),
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
        
        echo'<div class="cartText" style="padding:10px;">',$usersfirst,'\'s Store</div>';

         //Search the Store
        echo'<div class="grid_6" style="">
            <form method="GET">
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
            echo'<a style="text-decoration:none;" href="viewprofile.php?u=',$userid,'&view=store&option=sold"><div class="rectangle"><div style="padding-top:7px;text-align:center;">Sold</div><div class="arrow-leftStore" style="float:right;margin-right:288px;margin-top:-21px;"></div></div></a>';
        }
        
        else {
             echo'<a style="text-decoration:none;" href="viewprofile.php?u=',$userid,'&view=store&option=sold"><div class="rectangle"><div style="padding-top:7px;text-align:center;">Sold</div></div></a>';
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
    <div id="thepics" style="position:relative;left:-60px;top:10px;width:1250px;">
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
        $allsetsquery = "SELECT * FROM sets WHERE owner = '$useremail'";
        $allsetsrun = mysql_query($allsetsquery);
        $numbersets = mysql_num_rows($allsetsrun);
        
        //if no sets, propmt them to create one
        $numbersets = mysql_num_rows($allsetsrun);
        if($numbersets == 0) {
            echo'<div style="font-size:18px;font-weight:200;padding:40px;text-align:center;margin-left:-35px;margin-top:120px;"><a style="color:#333;" href="myprofile.php?view=upload&option=newexhibit">',$usersfirst,' has no exhibits</a></div>';
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
$notsquery = "UPDATE userinfo SET notifications = 0 WHERE emailaddress = '$useremail'";
$notsqueryrun = mysql_query($notsquery); }
}

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
