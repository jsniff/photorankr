<?php

//connect to the database
require "db_connection.php";

//start the session
session_start();

require "functionscampaigns3.php"; 
    // if login form has been submitted
    if (htmlentities($_GET['action']) == "login") { 
        login();
    }
    else if(htmlentities($_GET['action']) == "logout") { 
        logout();
    }

//PHOTOGRAPHER INFORMATION
$userid = htmlentities($_GET['u']);
$userinfoquery = mysql_query("SELECT * FROM userinfo WHERE user_id = '$userid'");
$useremail = mysql_result($userinfoquery,0,'emailaddress');
$profilepic = mysql_result($userinfoquery,0,'profilepic');
$profilepic = 'http://photorankr.com/' . $profilepic;
$firstname = mysql_result($userinfoquery,0,'firstname');
$lastname = mysql_result($userinfoquery,0,'lastname');
$fullname = $firstname . " " . $lastname;
$age = mysql_result($userinfoquery,0,'age');
$gender = mysql_result($userinfoquery,0,'gender');
$location = mysql_result($userinfoquery,0,'location');
$camera = mysql_result($userinfoquery,0,'camera');
$about = mysql_result($userinfoquery,0,'about');
$quote = mysql_result($userinfoquery,0,'quote');
$fbook = mysql_result($userinfoquery,0,'fbook');
$twitter = mysql_result($userinfoquery,0,'twitter');

$userphotosquery = mysql_query("SELECT * FROM photos WHERE emailaddress = '$useremail'");
$numuserphotos = mysql_num_rows($userphotosquery);

//PORTFOLIO RANKING
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
    
    //NUMBER FOLLOWING
    $emailquery=("SELECT following FROM userinfo WHERE emailaddress ='$useremail'");
	$followresult=mysql_query($emailquery);
	$followinglist=mysql_result($followresult, 0, "following");
	$followingquery="SELECT * FROM userinfo WHERE emailaddress IN ($followinglist)";
	$followingresult = mysql_query($followingquery);
	$numberfollowing = mysql_num_rows($followingresult);


//find out which view they are looking at
$view = htmlentities($_GET['view']);

?>

<!DOCTYPE html>

<html>
<head>

	<link rel="stylesheet" href="css/bootstrapnew2.css" type="text/css" />
    <link rel="stylesheet" href="css/reset.css" type="text/css" />
    <link rel="stylesheet" href="css/text.css" type="text/css" />
    <link rel="stylesheet" href="css/960_24.css" type="text/css" />
    <link rel="stylesheet" href="css/index.css" type="text/css"/> 
    <link rel="stylesheet" href="css/itunes.css" type="text/css"/> 
	<link rel="stylesheet" type="text/css" href="css/all.css"/>
	
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
    <script src="http://twitter.github.com/bootstrap/1.4.0/bootstrap-twipsy.js"></script>
    <script src="http://twitter.github.com/bootstrap/1.4.0/bootstrap-popover.js"></script>
    <script src="bootstrap-dropdown.js" type="text/javascript"></script>
    <script src="bootstrap-collapse.js" type="text/javascript"></script>
    <link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>
    
    <!--Navbar Dropdowns-->
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
    
<style type="text/css">
.navbar-inner
{
	text-align:center;
}

.center.navbar .nav,
.center.navbar .nav > li {
    float:none;
    display:inline-block;
    *display:inline; /* ie7 fix */
    *zoom:1; /* hasLayout ie7 trigger */
    vertical-align: top;
}

.center .navbar-inner {
    text-align:center;
}
.navbar .nav,
.navbar .nav > li {
    float:none;
    display:inline-block;
    *display:inline; /* ie7 fix */
    *zoom:1; /* hasLayout ie7 trigger */
    vertical-align: top;
}
.center .dropdown-menu {
    text-align: left;
}

.statoverlay

{
opacity:.0;
filter:alpha(opacity=40);
z-index:1;
transition: opacity .5s;
-moz-transition: opacity .5s;
-webkit-transition: opacity .5s;
-o-transition: opacity .5s;
}
     
.statoverlay:hover
{
opacity:.7;

</style>

<!--LIVE SEARCH IN PORTFOLIO-->    
<script type="text/javascript">
	$(document).ready(function(){
		$(".livesearch").click(function(){
			$.post("searchphotog.php", { keywords: $(".keywords").val() }, function(data){
				$("div#content").empty()
				$.each(data, function(){
					$("div#content").append("<div style='width:230px;height:250px;overflow:hidden;float:left;'><img class='phototitle' style='margin-right:30px;margin-top:20px;clear:right;' src='http://photorankr.com/" + this.source + "' height='60%' width:'60%' /><div style='text-align:center;font-size:14px;'>" + this.price + "&nbsp;|&nbsp;Photo Info</div></div>");
				});
			}, "json");
		});
	});
      
</script>

</head>

<body>

<?php navbarnew(); ?>

<div class="container_24" style="position:relative;margin-top:50px">
	<div class="grid_7 pull_2">
		<div class="grid_7 container" id="profilebox">
			<img class="phototitle2" src="<?php echo $profilepic; ?>" style="width:130px;height:130px;float:left;"/>
			<?php 
			//whatever you use to display profile pics
			?>
		<div class="grid_3">
			<h1 id="name"><?php echo $fullname; ?></h1>	
		</div>
		<div style="width:80px;margin-top:10px;margin-left:15px;" class="btn btn-success">Follow</div>
			
        <div class="grid_1" id="repcricle"> 
		 <?php //fancy jQuery stuff
		?>
		</div>
		<div class="grid_1" id="avgscore"> 
		<?php //fancy jQuery stuff
		?>
		</div>
		<div class="grid_3" id="stats">
			<span style="font-size:13px;"> Followers: <?php echo $numberfollowers; ?><br />
			Following: <?php echo $numberfollowing; ?><br />
			Photos: <?php echo $numuserphotos; ?>
		</div>
	</div>
    
		<a style="text-decoration:none;color:black;" href="viewprofile.php?u=<?php echo $userid; ?>"><div class="grid_6 btn3" <?php if($view == '') {echo'style="background:#cccccc;font-size:22px"';} else {echo'style="font-size:22px;"';} ?>>
			<span class="btntext2">Portfolio</span>
		</div></a>
        
		 <a style="text-decoration:none;color:black;" href="viewprofile.php?u=<?php echo $userid; ?>&view=exhibits"><div class="grid_6 btn3" <?php if($view == 'exhibits') {echo'style="background:#cccccc;font-size:22px"';} else {echo'style="font-size:22px;"';} ?>>
			<span class="btntext2">Exhibits</span>
		</div></a>
        
		<a style="text-decoration:none;color:black;" href="viewprofile.php?u=<?php echo $userid; ?>&view=info"><div class="grid_6 btn3" <?php if($view == 'info') {echo'style="background:#cccccc;font-size:22px"';} else {echo'style="font-size:22px;"';} ?>>
			<span class="btntext2"> Information </span>
		</div></a>
        
        </div>
	
    <div class="grid_19"  id="canvas" style="margin-top:-380px;">
	
            <?php echo navbar3(); ?>		
                
    </div>


				<div class="grid_21" style="margin-top:-270px;margin-left:230px;">

				<?php 
                $order = htmlentities($_GET['od']);

                if($view == '') {
                
                if($order == '') {
                $newestphotos = mysql_query("SELECT * FROM photos WHERE emailaddress = '$useremail' ORDER BY id DESC LIMIT 0,20");
                $numphotos = mysql_num_rows($newestphotos);
                }
                if($order == 'topranked') {
                $newestphotos = mysql_query("SELECT * FROM photos WHERE emailaddress = '$useremail' ORDER BY (points/votes) DESC");
                $numphotos = mysql_num_rows($newestphotos);
                }
                if($order == 'pop') {
                $newestphotos = mysql_query("SELECT * FROM photos WHERE emailaddress = '$useremail' ORDER BY faves DESC");
                $numphotos = mysql_num_rows($newestphotos);
                }
                
                for($iii = 0; $iii < $numphotos; $iii++) {
                $photo[$iii] = mysql_result($newestphotos,$iii,'source');
                $photobig[$iii] = str_replace("userphotos/", "$_SERVER[DOCUMENT_ROOT]/userphotos/", $photo[$iii]);
                $photo[$iii] = str_replace("$_SERVER[DOCUMENT_ROOT]/userphotos/", "http://photorankr.com/userphotos/medthumbs/", $photobig[$iii]);
                $imageid[$iii] = mysql_result($newestphotos,$iii,'id');
                $caption = mysql_result($newestphotos,$iii,'caption');
                $ranking = (mysql_result($newestphotos,$iii,'points')/mysql_result($newestphotos,$iii,'votes'));
                $ranking = number_format($ranking,2);

                list($width,$height) = getimagesize($photobig[$iii]);
                $widthnew = $width / 5;
                $heightnew = $height / 5;
                if($widthnew < 165) {
                $heightnew = $heightnew * ($heightnew/$widthnew);
                $widthnew = 240;
                }
                
                echo'
				<div class="phototitle fPic" id="',$id,'" style="width:230px;height:230px;overflow:hidden;">
                
                 <a href="fullsize2.php?imageid=',$imageid[$iii],'"><div class="statoverlay" style="z-index:1;left:0px;top:140px;position:relative;background-color:black;width:238px;height:75px;"><p style="line-spacing:1.48;padding:5px;color:white;">"',$caption,'"<br>By: ',$fullname,'</br>Rank: ',$ranking,'</p></div>
                 
					<img onmousedown="return false" oncontextmenu="return false;" style="position:relative;top:-100px;min-height:240px;min-width:240px;" class="phototitle2" src="',$photo[$iii],'" height="',$heightnew,'px" width="',$widthnew,'px" /></a>
				</div>';
                }
                
                }
                

    if($view == 'exhibits') {
    $allsetsrun = mysql_query("SELECT * FROM sets WHERE owner = '$useremail' ORDER BY id DESC");
    $numbersets = mysql_num_rows($allsetsrun);
    
    if($set == '') {

    for($iii=0; $iii < $numbersets; $iii++) {
    $setname[$iii] = mysql_result($allsetsrun, $iii, "title");
    $set_id[$iii] = mysql_result($allsetsrun, $iii, "id");
    $setcover = mysql_result($allsetsrun, $iii, "cover");
    $setname2[$iii] = (strlen($setname[$iii]) > 30) ? substr($setname[$iii],0,27). " &#8230;" : $setname[$iii];
    if($setcover == '') {
    $setcover = "../profilepics/nocoverphoto.png";
    }

    //grab all photos in the exhibit
    $grabphotos = "SELECT * FROM photos WHERE emailaddress = '$useremail' AND set_id = '$set_id[$iii]'";
    $grabphotosrun = mysql_query($grabphotos);
    $numphotosgrabbed = mysql_num_rows($grabphotosrun);
    
    echo'<div class="grid_12 phototitle2" style="margin-top:20px;width:235px;height:295px;">'; 

    echo'
    <a style="text-decoration:none;" href="viewprofile.php?u=',$userid,'&view=exhibits&set=',$set_id[$iii],'">
    <img onmousedown="return false" oncontextmenu="return false;" src="http://www.photorankr.com/',$setcover,'" width="235" height="220" />
    <br />
    <div style="color:#333;font-size:16px;font-family:arial,helvetica neue;padding-left:5px;padding-top:-15px;text-align:left;">
    "',$setname2[$iii],'"</div>

    <span style="text-decoration:none;">&nbsp;',$numphotosgrabbed,' Photos</span></a>
    </div>';
    }
    } //end of set == '' view


elseif($set != '') {
//grab all photos in the exhibit
$grabphotos = "SELECT * FROM photos WHERE emailaddress = '$useremail' AND set_id = '$set'";
$grabphotosrun = mysql_query($grabphotos);
$numphotosgrabbed = mysql_num_rows($grabphotosrun);

//grab about this set
$aboutset = "SELECT * FROM sets WHERE owner = '$useremail' AND id = '$set' LIMIT 0,1";
$aboutsetrun = mysql_query($aboutset);
$aboutarray = mysql_fetch_array($aboutsetrun);
$aboutset = $aboutarray['about'];
$settitle = $aboutarray['title'];


echo'
<div style="margin-top:10px;">
<div style="font-size:16px;width:740px;line-height:25px;padding:10px;"><u>Exhibit:</u> "',$settitle,'"<br /><hr style="margin-top:15px;"/><u>About this exhibit:</u> ',$aboutset,'</div>';

for($iii=0; $iii < $numphotosgrabbed; $iii++) {
$insetname[$iii] = mysql_result($grabphotosrun, $iii, "caption");
$insetsource[$iii] = mysql_result($grabphotosrun, $iii, "source");
$insetimageid[$iii] = mysql_result($grabphotosrun, $iii, "id");
$newsource = str_replace("userphotos/","userphotos/thumbs/", $insetsource[$iii]);

        $photobig[$iii] = str_replace("userphotos/", "$_SERVER[DOCUMENT_ROOT]/userphotos/", $insetsource[$iii]);
        $photo[$iii] = str_replace("$_SERVER[DOCUMENT_ROOT]/userphotos/", "http://photorankr.com/userphotos/medthumbs/", $photobig[$iii]);
        $imageid[$iii] = mysql_result($newestphotos,$iii,'id');
        $caption = mysql_result($grabphotosrun,$iii,'caption');
        $ranking = (mysql_result($grabphotosrun,$iii,'points')/mysql_result($grabphotosrun,$iii,'votes'));
        $ranking = number_format($ranking,2);
                
        list($height,$width) = getimagesize($photobig[$iii]);
        $widthnew = $width / 5;
        $heightnew = $height / 5;
        
                echo'
				<div class="phototitle fPic" id="',$id,'" style="width:230px;height:230px;overflow:hidden;">
                
                 <a href="fullsize2.php?imageid=',$insetimageid[$iii],'"><div class="statoverlay" style="z-index:1;left:0px;top:140px;position:relative;background-color:black;width:238px;height:75px;"><p style="line-spacing:1.48;padding:5px;color:white;">"',$caption,'"<br>By: ',$fullname,'</br>Rank: ',$ranking,'</p></div>
                 
					<img onmousedown="return false" oncontextmenu="return false;" style="position:relative;top:-100px;min-height:240px;min-width:240px;" class="phototitle2" src="',$photo[$iii],'" height="',$heightnew,'px" width="',$widthnew,'px" /></a>
				</div>';

                
}
echo'</div>';
} //end of set != '' view

    } //end of exhibit view
    

if($view == 'info') {
echo'
<div class="span9" style="margin-top:30px;">
<table class="table">
<tbody>';

if($age) {
echo'
<tr>
<td>Age:</td>
<td>',$age,'</td>
</tr>'; }

if($location) {
echo'
<tr>
<td>From:</td>
<td>',$location,'</td>
</tr>'; }

if($gender) {
echo'
<tr>
<td>Gender:</td>
<td>',$gender,'</td>
</tr>'; }

if($camera) {
echo'
<tr>
<td>Camera:</td>
<td>',$camera,'</td>
</tr>'; }

if($fbook) {
echo'
<tr>
<td>Facebook Page:</td>
<td><a href="',$fbook,'">',$fbook,'</a></td>
</tr>'; }

if($twitter) {
echo'
<tr>
<td>Twitter:</td>
<td><a href="',$twitter,'">',$twitter,'</a></td>
</tr>'; }

if($quote) {
echo'
<tr>
<td>Quote:</td>
<td>',$quote,'</td>
</tr>'; }

if($about) {
echo'
<tr>
<td>About:</td>
<td>',$about,'</td>
</tr>'; }

echo'
</tbody>
</table>
</div>';
}


?>


	</div>			

    </div>



<script type="text/javascript" src="js/mocha.js"></script>    
<script src="js/bootstrap.js" type="text/javascript"></script>
<script src="js/bootstrap-dropdown.js" type="text/javascript"></script>
<script src="js/bootstrap-collapse.js" type="text/javascript"></script>
<!--HIDDEN UPLOAD INFORMATION SCRIPT-->
<script type="text/
t">   
$(document).ready(function(){
  $(".flip2").click(function(){
    $(".panel2").slideToggle("slow");
  });
});
</script>
    
    
    
</body>
</html>	

