<?php

//connect to the database
require "db_connection.php";
require "functionsnav.php";

//start the session
session_start();

    // if login form has been submitted
    if (htmlentities($_GET['action']) == "login") { 
        login();
    }
    else if(htmlentities($_GET['action']) == "logout") { 
        logout();
    }

    $email = $_SESSION['email'];
    
    if ($_SESSION['loggedin'] != 1) {
        header("Location: signin.php");
        exit();
    } 

//QUERY FOR NOTIFICATIONS
$currentnots = "SELECT * FROM userinfo WHERE emailaddress = '$email'";
$currentnotsquery = mysql_query($currentnots);
$currentnotsresult = mysql_result($currentnotsquery, 0, "notifications");

//notifications query reset 
if($currentnotsresult > 0) {
$notsquery = "UPDATE userinfo SET notifications = 0 WHERE emailaddress = '$email6'";
$notsqueryrun = mysql_query($notsquery); }

//DISCOVER SCRIPT
    
  //get the users information from the database
  $likesquery = "SELECT * FROM userinfo WHERE emailaddress='$email'";
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
  
  //PORTFOLIO RANKING

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
    
    //NUMBER FOLLOWING
    $emailquery=("SELECT following FROM userinfo WHERE emailaddress ='$email'");
	$followresult=mysql_query($emailquery);
	$followinglist=mysql_result($followresult, 0, "following");
	$followingquery="SELECT * FROM userinfo WHERE emailaddress IN ($followinglist)";
	$followingresult = mysql_query($followingquery);
	$numberfollowing = mysql_num_rows($followingresult);

if(isset($_GET['view'])) {
	$view=htmlentities($_GET['view']); //get which tab of profile they are looking at
}
  
  
  //GRAB USER INFORMATION
  $userquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$email'");
  $profilepic = mysql_result($userquery,0,'profilepic'); 
  $email = mysql_result($userquery,0,'emailaddress'); 
  $firstname = mysql_result($userquery,0,'firstname');
  $lastname = mysql_result($userquery,0,'lastname');
  $fullname = $firstname." ".$lastname; 
  $age = mysql_result($userquery,0,'age');
  $gender = mysql_result($userquery,0,'gender');
  $location = mysql_result($userquery,0,'location');
  $camera = mysql_result($userquery,0,'camera');
  $about = mysql_result($userquery,0,'bio');
  $quote = mysql_result($userquery,0,'quote');
  $fbook = mysql_result($userquery,0,'facebookpage');
  $twitter = mysql_result($userquery,0,'twitteraccount');
  $faves = mysql_result($userquery,0,'faves');
  $reputation = number_format(mysql_result($userquery,0,'reputation'),1);
  $password = mysql_result($userquery,0,'password');
  $background = mysql_result($userquery,0,'background');
  $background = str_replace('userphotos/','userphotos/medthumbs/',$background);
  
  $view = htmlentities($_GET['view']);
  
  //UPDATE BACKGROUND IMAGE
  if($_GET['mode'] == 'updatebackground') {
  
        $newbg = $_POST['checked'];
        $newbgquery = mysql_query("UPDATE userinfo SET background = '$newbg' WHERE emailaddress = '$email'");
        
    }
  
          
        if($_GET['action'] == 'comment') {
    
            $blogid = htmlentities($_GET['blogid']);
            $comment = mysql_real_escape_string($_POST['comment']);
                    
            $commentinsertion = mysql_query("INSERT INTO blogcomments (comment,blogid,emailaddress) VALUES ('$comment','$blogid','$email')");
            
            echo '<META HTTP-EQUIV="Refresh" Content="0; URL=myprofile3.php?view=blog#',$blogid,'">';
            exit();

    
        }
        
        if($_GET['action'] == 'submitpost') {
    
            $blogtitle = mysql_real_escape_string($_POST['title']);
            $blogsubject = mysql_real_escape_string($_POST['subject']);
            $blogcontent = mysql_real_escape_string($_POST['content']);
            $source = mysql_real_escape_string($_POST['checked']);
            $time = mysql_real_escape_string($_POST['time']);
            
            $bloginsertion = mysql_query("INSERT INTO blog (title,subject,content,photo,emailaddress,time) VALUES ('$blogtitle','$blogsubject','$blogcontent','$source','$email','$time')");
            
            $getblogid = mysql_query("SELECT id FROM blog WHERE emailaddress = '$email' ORDER BY id DESC LIMIT 0,1");
            $lastblogid = mysql_result($getblogid,0,'id');
            
            $blognewsfeed = mysql_query("INSERT INTO newsfeed (firstname,lastname,emailaddress,type,source) VALUES ('$firstname','$lastname','$email','blogpost','$lastblogid')");
            
            echo '<META HTTP-EQUIV="Refresh" Content="0; URL=myprofile3.php?view=blog">';
            exit();

    
        }
        
        
         if($_GET['action'] == 'submittomarket') {
    
            $source = mysql_real_escape_string($_POST['checked']);
            $newprice = mysql_real_escape_string($_POST['newprice']);
            
            $marketphotoquery = mysql_query("UPDATE photos SET (price = '$newprice') WHERE source = '$source' AND emailaddress = '$email'");
    
        }


?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
   <meta property="og:image" content="http://photorankr.com/<?php echo $profilepic; ?>">
   <title><?php echo $firstname . " " . $lastname; ?> - PhotoRankr</title>
   <meta name="Generator" content="EditPlus">
  <meta name="Author" content="PhotoRankr, PhotoRankr.com">
  <meta name="Keywords" content="photos, sharing photos, photo sharing, photography, photography club, sell photos, sell photography, where to sell my photography, good sites for selling photography, making money from photography, making money off photography, social networking, social network, social networks, where to sell my photos, good sites for selling photos, good site to sell photos, making money from photos">
  <meta name="Description" content="PhotoRankr allows photographers of all skill levels to sell and share their work. Create your photostream cutomized to what you want to see. Add photos to your favorites, rank them, and watch them trend. Build your portfolio with Photorankr.">

  <link rel="stylesheet" type="text/css" href="css/bootstrapNew.css" />
  <link rel="stylesheet" href="text2.css" type="text/css" />
  <link rel="stylesheet" href="960_24.css" type="text/css" />
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
  <script src="bootstrap.js" type="text/javascript"></script>
  <script src="bootstrap-dropdown.js" type="text/javascript"></script>
  <script src="bootstrap-collapse.js" type="text/javascript"></script>
  <link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>

<title>PhotoRankr - Newest Photography</title>

  
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

.show { 
display: block;
}

.hide { 
display: none; 
}

 .statoverlay

{
opacity:.6;
filter:alpha(opacity=40);
z-index:1;
transition: opacity .5s;
-moz-transition: opacity .5s;
-webkit-transition: opacity .5s;
-o-transition: opacity .5s;
}



 .statoverlay2

{
opacity:.6;
filter:alpha(opacity=40);
z-index:1;
transition: opacity .5s;
-moz-transition: opacity .5s;
-webkit-transition: opacity .5s;
-o-transition: opacity .5s;
}
            
.statoverlay:hover
{
opacity:.6;
}                

.item {
  margin: 10px;
  float: left;
  border: 2px solid transparent;
}

.item:hover {
  margin: 10px;
  float: left;
  border: 2px solid black;
}

</style>

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




<!--AJAX to pull off tags associated with a particular owners set they choose-->

<script type="text/javascript">
function showTags(str)
{
var xmlhttp;    
if (str=="")
  {
  document.getElementById("boxesappear").innerHTML="";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("boxesappear").innerHTML=xmlhttp.responseText;
    }
  }
xmlhttp.open("GET","gettags.php?q="+str,true);
xmlhttp.send();
}
</script>


</head>
<body style="background-color:#fff;overflow-x:hidden;background-image:url('');background-position: center top;background-size:100%;background-repeat:no-repeat;background-attachment:fixed;">

<?php navbarnew(); ?>  

<div class="container_24"><!--START CONTAINER-->

<!--LEFT SIDEBAR-->
<div class="grid_24" style="width:1120px;">



<div class="grid_4 pull_1 rounded" style="background-color:#eeeff3;position:relative;top:80px;width:250px;">

<div style="width:240px;height:140px;">
<div class="circle" style="float:left;overflow:hidden;margin-left:15px;margin-top:15px;">
<img src="<?php echo $profilepic; ?>" height="120" width="120"/>
</div>
<a class="btn btn-success" style="float:left;width:70px;margin-top:40px;margin-left:10px;font-size:14px;font-weight:150;" href="myprofile3.php?view=upload">Upload</a>
<a class="btn btn-primary" style="float:left;width:70px;margin-top:7px;margin-left:10px;font-size:14px;font-weight:150;" href="myprofile3.php?view=promote">Promote</a>
</div>

<div style="width:250px;margin-top:0px;">
<div style="font-size:18px;text-align:center;font-weight:200;"><?php echo $fullname; ?></div>
</div>

<div style="text-align:center;font-size:14px;font-weight:200;width:250px;height:190px;margin-top:20px;">
<p>Reputation: <span style="font-size:20px;"><?php echo $reputation; ?>/</span><span style="font-size:15px;">100</span></p>
<div class="progress" style="margin-top:-15px;margin-left:28px;width:195px;height:15px;">
   <div class="bar" style="width: <?php echo $reputation; ?>%;"></div>
   </div>
<div style="margin-left:30px;text-align:center;">
   <div style="float:left;"><p>Avg. Portfolio:&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;</p></div>
   <div style="float:left;margin-top:-4px;"><p><span style="font-size:20px;">#</span> Photos</p></div>
</div>

<div style="position:relative;top:-15px;margin-left:50px;text-align:center;font-size:20px;">
   <div style="float:left;"><p><?php echo $portfolioranking; ?>/<span style="font-size:15px;">10</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p></div>
   <div style="float:left;"><p><?php echo $numphotos; ?></p></div>
</div>

<div style="position:relative;left:15px;top:-25px;margin-left:30px;text-align:center;">
   <div style="float:left;"><p>Favorited:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;</p></div>
   <div style="float:left;"><p>Followers:</p></div>
</div>

<div style="position:relative;top:-35px;margin-left:52px;text-align:center;font-size:20px;">
   <div style="float:left;"><p>&nbsp;&nbsp;<?php echo $portfoliofaves; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p></div>
   <div style="float:left;"><p><?php echo $numberfollowers; ?></p></div>
</div>

</div>

<div style="position:relative;top:-30px;">
<hr>
<a style="text-decoration:none;color:black;font-weight:100;" href="myprofile3.php?view=info"><div style="width:250px;margin-top:-10px;padding-bottom:4px;">
<span class="green" style="text-align:center;font-size:24px;padding-left:15px;<?php if($view == 'info' || $view == 'editinfo') {echo'color:#6aae45;';} ?>">Info&nbsp;&nbsp;<img style="float:right;padding-top:5px;padding-right:20px;" src="graphics/info.png" height="30" width="30"></span>
</div></a>

<hr>
<a style="text-decoration:none;color:black;font-weight:100;" href="myprofile3.php?view=network"><div style="width:250px;margin-top:-10px;padding-bottom:4px;">
<span class="green" style="text-align:center;font-size:24px;padding:15px;<?php if($view == 'network') {echo'color:#6aae45;';} ?>">Network&nbsp;&nbsp;<img style="float:right;padding-top:5px;padding-right:20px;" src="graphics/info.png" height="30" width="30"></span>
</div></a>

<hr>
<a style="text-decoration:none;color:black;font-weight:100;" href="myprofile3.php?view=favorites"><div style="width:250px;margin-top:-10px;padding-bottom:4px;">
<span class="green" style="text-align:center;font-size:24px;padding:15px;<?php if($view == 'favorites') {echo'color:#6aae45;';} ?>">Favorites&nbsp;&nbsp;<img style="float:right;padding-top:5px;padding-right:20px;" src="graphics/info.png" height="30" width="30"></span>
</div></a>

<hr>
<a style="text-decoration:none;color:black;font-weight:100;" href="myprofile3.php?view=messages"><div style="width:250px;margin-top:-10px;padding-bottom:4px;">
<span class="green" style="text-align:center;font-size:24px;padding:15px;<?php if($view == 'messages' || $view == 'viewthread') {echo'color:#6aae45;';} ?>">Messages&nbsp;&nbsp;<img style="float:right;padding-top:5px;padding-right:20px;"src="graphics/messages.png" height="30" width="30"></span>
</div></a>

<hr>
<a style="text-decoration:none;color:black;font-weight:100;" href="myprofile3.php?view=settings"><div style="width:250px;margin-top:-10px;padding-bottom:4px;">
<span class="green" style="text-align:center;font-size:24px;padding:15px;<?php if($view == 'settings') {echo'color:#6aae45;';} ?>">Settings&nbsp;&nbsp;<img style="float:right;padding-top:5px;padding-right:20px;"src="graphics/messages.png" height="30" width="30"></span>
</div></a>
</div>

</div><!--end 4 grid-->

<div class="grid_18 roundedright" style="background-color:#eeeff3;height:60px;margin-top:80px;width:830px;margin-left:-45px;">

<a style="text-decoration:none;color:black;" href="myprofile3.php"><div class="clicked" style="width:180px;height:60px;border-right:1px solid #ccc;border-left:1px solid #ccc;float:left;<?php if($view == '') {echo'background-color:#bbb;color:white;';} ?>"><div style="font-size:25px;font-weight:100;margin-top:10px;text-align:center;">Portfolio</div></div></a>

<a style="text-decoration:none;color:black;" href="myprofile3.php?view=store"><div class="clicked" style="width:180px;height:60px;border-right:1px solid #ccc;float:left;<?php if($view == 'store') {echo'background-color:#bbb;color:white;';} ?>"><div style="font-size:25px;font-weight:100;margin-top:10px;text-align:center;">My Store</div></div></a>

<a style="text-decoration:none;color:black;" href="myprofile3.php?view=blog"><div class="clicked" style="width:180px;height:60px;border-right:1px solid #ccc;float:left;<?php if($view == 'blog') {echo'background-color:#bbb;color:white;';} ?>"><div style="font-size:25px;font-weight:100;margin-top:10px;text-align:center;">Blog</div></div></a>

<div style="width:180px;height:60px;float:left;"><div style="font-size:25px;font-weight:100;margin-top:6px;text-align:center;">
<form class="navbar-search" action="myprofile3.php?view=search" method="post">
<input class="search" style="position:relative;margin-left:15px;margin-top:2px;" name="searchterm" type="text">
</form></div></div>


<?php

    if($view == '') {
    
        $option = htmlentities($_GET['option']);    
    
        echo'<br /><br /><br /><br /><div style="width:760px;text-align:center;font-size:14px;font-weight:200;"><div style="margin-left:20px;"><a class="green" style="text-decoration:none;'; if($option == '') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'" href="myprofile3.php">Newest</a> | <a class="green" style="text-decoration:none;color:#333;'; if($option == 'top') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'" href="myprofile3.php?option=top">Top Ranked</a> | <a class="green" style="text-decoration:none;color:#333;'; if($option == 'fave') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'" href="myprofile3.php?option=fave">Most Favorited</a> | <a class="green" style="text-decoration:none;color:#333;" href="myprofile3.php?view=exhibits">Exhibits</a></div></div>';
        
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
        
        echo'<div id="thepics">';
        echo'<div id="container" class="grid_18" style="width:770px;margin-top:-38px;margin-left:-10px;padding:35px;background-color:rgba(245,245,245,0.6);">';

        for($iii=0; $iii < $numresults; $iii++) {
              
                $image[$iii] = mysql_result($query, $iii, "source");
                $imageThumb[$iii] = str_replace("userphotos/","../userphotos/medthumbs/", $image[$iii]);
                $id = mysql_result($query, $iii, "id");
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
                $heightls = $height / 3.5;
                $widthls = $width / 3.5;

                echo '   

                <div class="fPic" id="',$id,'" style="width:245px;height:245px;overflow:hidden;float:left;margin-left:10px;margin-top:30px;"><a href="http://photorankr.com/fullsize.php?image=', $image[$iii], '">

                <div class="statoverlay" style="z-index:1;left:0px;top:155px;position:relative;background-color:black;width:245px;height:75px;"><p style="line-spacing:1.48;padding:5px;color:white;"><span style="font-size:16px;font-weight:100;">',$caption,'</span><br><span style="font-size:14px;font-weight:100;">Score: ',$score,'<br>Favorites: ',$faves,'</span></p></div>

                <img onmousedown="return false" oncontextmenu="return false;" style="position:relative;top:-90px;min-height:245px;min-width:245px;" src="http://www.photorankr.com/',$imageThumb[$iii],'" height="',$heightls,'px" width="',$widthls,'px" /></a></div>';
	    
                } //end for loop      
        
        echo'</div>';
        echo'</div>';
        
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
    

        }
        
    
    elseif($view == 'exhibits') {
    
    echo'<br /><br /><br /><br /><div style="width:760px;text-align:center;font-size:14px;font-weight:200;"><div style="margin-left:20px;"><a class="green" style="text-decoration:none;color:#333;" href="myprofile3.php">Newest</a> | <a class="green" style="text-decoration:none;color:#333;" href="myprofile3.php?option=top">Top Ranked</a> | <a class="green" style="text-decoration:none;color:#333;" href="myprofile3.php?option=fave">Most Favorited</a> | <a class="green" style="text-decoration:none;color:#333;'; if($view == 'exhibits') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'" href="myprofile3.php?view=exhibits">Exhibits</a></div></div>';


        if(isset($_GET['set'])){
		$set = mysql_real_escape_string($_GET['set']);
	}
    
    //get exhibit mode
if(isset($_GET['mode'])){
		$mode = ($_GET['mode']);
	}

if($mode == 'added') {
//add checked photos to existing exhibit

if(!empty($_POST['addthese'])) {
    foreach($_POST['addthese'] as $checked) {
        //insert each checked photo into corresponding set
        $checkedset = "UPDATE photos SET set_id = '$set' WHERE source = '$checked'";
        $checkedsetrun = mysql_query($checkedset);
        }
        }
	
echo'<span style="position:relative;margin-top:-130px;font-size: 16px;"><span class="label label-success" style="font-size:16px;" >Your exhibits have been updated successfully!</span><br /><br /><a href="myprofile.php?ex=y">Click here to view them</a><br /><br /></span>';
}

if($mode == 'coverchanged') {
//edit existing exhibit

    $newcaption = mysql_real_escape_string($_POST['caption']);
    $newaboutset = mysql_real_escape_string($_POST['aboutset']);
    $newcover = mysql_real_escape_string($_POST['addthis']);
    
    $exhibitchange = "UPDATE [sets] SET (title = '$newcaption', about = '$newaboutset', cover = '$newcover') WHERE id = '$set' AND owner = '$email'";
    $exhibitrun = mysql_query($exhibitchange);
        	
echo'<span style="position:relative;margin-top:-130px;font-size: 16px;"><span class="label label-success" style="font-size:16px;" >Your exhibit has been updated successfully!</span><br /><br /><a href="myprofile.php?ex=y">Click here to view exhibits</a><br /><br /></span>';
}

//select all exhibits of user
$allsetsquery = "SELECT * FROM sets WHERE owner = '$email'";
$allsetsrun = mysql_query($allsetsquery);
$numbersets = mysql_num_rows($allsetsrun);
echo'<div style="margin-top:-60px">';

if($numbersets == 0) {
echo'<div style="font-size:18px;font-weight:200;padding:40px;text-align:center;margin-left:-35px;margin-top:120px;"><a style="color:#333;" href="myprofile3.php?view=upload&option=newexhibit">Click here to create your first exhibit.</a></div>';
}

if($set == '' & $numbersets > 0) {

echo'<div class="grid_18" style="width:770px;margin-top:22px;margin-left:-10px;padding:35px;background-color:rgba(245,245,245,0.6);"><a href="myprofile3.php?view=upload&option=newexhibit"><button class="btn btn-success">Create New Exhibit</button></a><br /><br />
'; 

for($iii=0; $iii < $numbersets; $iii++) {
$setname[$iii] = mysql_result($allsetsrun, $iii, "title");
$setcover = mysql_result($allsetsrun, $iii, "cover");
$set_id[$iii] = mysql_result($allsetsrun, $iii, "id");
$setname2[$iii] = (strlen($setname[$iii]) > 30) ? substr($setname[$iii],0,27). " &#8230;" : $setname[$iii];
if($setcover == '') {
$setcover = "profilepics/nocoverphoto.png";
}
        list($width, $height) = getimagesize($setcover);
        $imgratio = $height / $width;
        $heightls = $height / 3.5;
        $widthls = $width / 3.5;
        
//grab all photos in the exhibit
$grabphotos = "SELECT * FROM photos WHERE emailaddress = '$email' AND set_id = '$set_id[$iii]'";
$grabphotosrun = mysql_query($grabphotos);
$numphotosgrabbed = mysql_num_rows($grabphotosrun);


    echo'<div style="width:245px;height:245px;overflow:hidden;float:left;margin-left:10px;margin-top:30px;"><a href="myprofile3.php?view=exhibits&set=',$set_id[$iii],'">

    <div class="statoverlay" style="z-index:1;left:0px;top:190px;position:relative;background-color:black;width:245px;height:70px;"><p style="line-spacing:1.48;padding:5px;color:white;"><span style="font-size:16px;font-weight:100;">',$setname2[$iii],'</span><br><span style="font-size:14px;font-weight:100;">Number Photos: ',$numphotosgrabbed,'<br></span></p></div>

    <img onmousedown="return false" oncontextmenu="return false;" style="position:relative;top:-90px;min-height:265px;min-width:245px;" src="http://www.photorankr.com/',$setcover,'" height="',$heightls,'px" width="',$widthls,'px" /></a></div>';
    
} //end of set == '' view
echo'</div>';

} //end of set == '' view


elseif($set != '') {
//get exhibit mode
if(isset($_GET['mode'])){
		$mode = ($_GET['mode']);
	}
if($mode == '') {
//grab all photos in the exhibit
$grabphotos = "SELECT * FROM photos WHERE emailaddress = '$email' AND set_id = '$set'";
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

echo'<div class="grid_18" style="width:770px;margin-top:22px;margin-left:-10px;padding:35px;background-color:rgba(245,245,245,0.6);">

<div class="well grid_14" style="width:735px;font-size:16px;line-height:25px;margin-top:15px;"><u>Exhibit:</u> "',$settitle,'"<br />
<br /><u>About this exhibit:</u> ',$aboutset,'<br /><br />
<a data-toggle="modal" data-backdrop="static" href="#add"><button class="btn btn-success">Add Photos to Exhibit</button></a>&nbsp;&nbsp;
<a data-toggle="modal" data-backdrop="static" href="#editexhibit"><button class="btn btn-success">Edit Exhibit</button></a></div>';


for($iii=0; $iii < $numphotosgrabbed; $iii++) {
    $insetname[$iii] = mysql_result($grabphotosrun, $iii, "caption");
    $insetsource[$iii] = mysql_result($grabphotosrun, $iii, "source");
    $newsource = str_replace("userphotos/","userphotos/medthumbs/", $insetsource[$iii]);
    $caption = mysql_result($grabphotosrun, $iii, "caption");
    $faves = mysql_result($grabphotosrun, $iii, "faves");
    $points = mysql_result($grabphotosrun, $iii, "points");
    $votes = mysql_result($grabphotosrun, $iii, "votes");
    $score = number_format(($points/$votes),2);
    
            list($width, $height) = getimagesize($insetsource[$iii]);
            $imgratio = $height / $width;
            $heightls = $height / 3.5;
            $widthls = $width / 3.5;
                
    echo'<div style="width:245px;height:245px;overflow:hidden;float:left;margin-left:10px;margin-top:30px;"><a href="fullsizeme.php?image=',$insetsource[$iii],'">

    <div class="statoverlay" style="z-index:1;left:0px;top:180px;position:relative;background-color:black;width:245px;height:75px;"><p style="line-spacing:1.48;padding:5px;color:white;"><span style="font-size:16px;font-weight:100;">',$caption,'</span><br><span style="font-size:14px;font-weight:100;">Score: ',$score,'<br>Favorites: ',$faves,'</span></p></div>

    <img onmousedown="return false" oncontextmenu="return false;" style="position:relative;top:-90px;min-height:265px;min-width:245px;" src="',$newsource,'" height="',$heightls,'px" width="',$widthls,'px" /></a></div>';
 
    } //end for loop

    echo'</div>';
    echo'</div>';

   } //end of no exhibit mode
   
   }
   
   
   
        //Add Photos to Exhibit Modal

echo'<div class="modal hide fade" id="add" style="overflow-y:scroll;overflow-x:hidden;">

<div class="modal-header">
<a style="float:right" class="btn btn-primary" data-dismiss="modal">Close</a>
<img style="margin-top:-4px;" src="graphics/logoteal.png" height="28" width="90" />&nbsp;&nbsp;<span style="font-size:16px;">Add photos to your exhibit below:</span>
  </div>
  <div modal-body" style="width:600px;">

<div id="content" style="font-size:16px;width:550px;height:500px;overflow-x:hidden;">
		
<img style="border: 1px solid black;margin-left:10px;margin-top:10px;" src="',$setcover,'" 
height="100px" width="100px" />

<div style="width:540px;margin-left:130px;margin-top:-100px;overflow-y:scroll;overflow-x:hidden;">

<form action="myprofile.php?vie=-exhibits&set=',$set,'&mode=added" method="post" enctype="multipart/form-data">
    <span style="font-size:14px;">
    Exhibit Name:&nbsp;&nbsp;',$settitle,'
    <br />
    <br />
    About this Exhibit:&nbsp;&nbsp;
    ',stripslashes($aboutset),'
    <br />
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
        echo'<img src="',$newsource,'" height="70" width="70" />&nbsp;&nbsp;<input type="checkbox" name="addthese[]" value="',      $userphotosource[$iii],'" />&nbsp;"',$userphotoscaption[$iii],'"
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
    
    
    
    elseif($view == 'info') {
        
        echo'
        <div class="span9" style="margin-top:0px;margin-left:-5px;padding:67px;padding-top:40px;background-color:rgba(245,245,245,0.6);">
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
        <a class="btn btn-success" href="myprofile3.php?view=editinfo">Edit Profile</a>
        </div>';
    
    }
    
    
    elseif ($view == 'editinfo') { //if they are on the edit info tab
	//see if they have submitted the form
	$action = htmlentities($_GET['action']);
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
		require 'functions.php';	
	
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
				//$profilepic = $path_to_profpic_directory . $firstname . $lastname . $extension;
  
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
        echo '<META HTTP-EQUIV="Refresh" Content="0; URL=myprofile3.php?view=editinfo&action=saved">';
        exit();
	}
    else if($action == "saved") {
        echo '<h3>Profile Saved</h3><br />';
    }

echo'

        <div class="span9" style="margin-top:0px;;margin-left:-5px;padding:20px;padding-left:67px;background-color:rgba(245,245,245,0.6);">
        <span style="font-size:18px;font-weight:200;">Edit Your Information:</span>
        <br /><br />
        <form action="myprofile3.php?view=editinfo&action=submit" method="post" enctype="multipart/form-data">
        <table class="table">
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
        <td><a href="',$facebookpage,'"><input style="width:180px;height:20px;" type="text" name="facebookpage" value="',$fbook,'"/></a></td>
        </tr>

        <tr>
        <td>Twitter:</td>
        <td><a href="',$twitteraccount,'"><input style="width:180px;height:20px;" type="text" name="twitteraccount" value="',$twitter,'"/></a></td>
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
        <td>Choose Your Discover Preferences:</td>
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
        
}


    elseif($view == 'store') {
    
        $option = htmlentities($_GET['option']);    
    
        echo'<br /><br /><br /><br /><div style="width:760px;text-align:center;font-size:14px;font-weight:200;"><div style="margin-left:20px;"><a class="green" style="text-decoration:none;'; if($option == '') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'" href="myprofile3.php?view=store">Manage Store</a> | <a class="green" style="text-decoration:none;color:#333;'; if($option == 'addtostore') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'" href="myprofile3.php?view=store&option=addtostore">Add to Store</a> | <a class="green" style="text-decoration:none;color:#333;'; if($option == 'cart') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'" href="myprofile3.php?view=store&option=cart">My Cart</a></div></div>';
        
        
        if($option == '') {        
        $query = mysql_query("SELECT * FROM photos WHERE emailaddress = '$email' AND price != ('Not For Sale') ORDER BY id DESC LIMIT 0,21");
        $numresults = mysql_num_rows($query);
        
        echo'<div id="thepics">';
        echo'<div id="container" class="grid_18" style="width:770px;margin-top:-38px;margin-left:-10px;padding:35px;background-color:rgba(245,245,245,0.6);">';

        for($iii=0; $iii < $numresults; $iii++) {
              
                $image[$iii] = mysql_result($query, $iii, "source");
                $imageThumb[$iii] = str_replace("userphotos/","../userphotos/medthumbs/", $image[$iii]);
                $id = mysql_result($query, $iii, "id");
                $caption = mysql_result($query, $iii, "caption");
                $points = mysql_result($query, $iii, "points");
                $votes = mysql_result($query, $iii, "votes");
                $faves = mysql_result($query, $iii, "faves");
                $price = mysql_result($query, $iii, "price");
                $sold = mysql_result($query, $iii, "sold");
                $score = number_format(($points/$votes),2);
                $faveemail = mysql_result($query, $iii, "emailaddress");
                $ownerquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$faveemail'");
                $firstname = mysql_result($query, 0, "firstname");
                $lastname = mysql_result($query, 0, "lastname");
                $reputation = mysql_result($query, 0, "lastname");
                $fullname = $firstname . " " . $lastname;
                list($width, $height) = getimagesize($image);
                $imgratio = $height / $width;
                $heightls = $height / 3.5;
                $widthls = $width / 3.5;

                echo '   

                <div class="fPic" id="',$id,'" style="width:245px;overflow:hidden;float:left;margin-left:10px;margin-top:30px;"><a href="http://photorankr.com/fullsize.php?image=', $image[$iii], '">
                
                <div style="width:245px;height:245px;overflow:hidden;">
                <div class="statoverlay" style="z-index:1;left:0px;top:155px;position:relative;background-color:black;width:245px;height:75px;"><p style="line-spacing:1.48;padding:5px;color:white;"><span style="font-size:16px;font-weight:100;">',$caption,'</span><br><span style="font-size:14px;font-weight:100;">Sold: ',$sold,'<br>Base Price: $',$price,'</span></p></div>

                <img onmousedown="return false" oncontextmenu="return false;" style="position:relative;top:-90px;min-height:245px;min-width:245px;" src="http://www.photorankr.com/',$imageThumb[$iii],'" height="',$heightls,'px" width="',$widthls,'px" /></a>
                <br />      
                </div>    
                    
                    <!--DROPDOWN MANAGE-->
                    <div class="panel',$id,'">
                    
                    
            <script type="text/javascript">
            function showOtherPrice() {
                if (document.getElementById(\'price\').value == \'Other Price\')
                    {
                        document.getElementById(\'otherprice\').className = \'show\';
                    }
                else if (document.getElementById(\'price\').value == \'Not For Sale\')
                    {
                        document.getElementById(\'remove\').className = \'show\';
                    }
                else {
                    document.getElementById(\'otherprice',$id,'\').className = \'hide\';
                    }
            }
            </script>
            
        <!--FOR SALE-->
        <table class="table">
        <tbody>
        
        <tr>
        <td>Base Price:</td>
        <td>
            <div>
            <select id="price" name="pricebox" style="width:120px;float:left;margin-left:-70px;" onchange="showOtherPrice()">
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
            </div>
            <div id="otherprice" class="hide" style="margin-left:-150px;width:290px;"><br /><div class="input-prepend input-append" style="float:left;"> 
                <span class="add-on">$</span><input class="span2" id="appendedPrependedInput" size="16" type="text"><span class="add-on">.00</span>
              </div></div>
        </td>
        </tr>
        
        <tr>
        <td colspan="2"><br /><b>Edit Options for Sale:</b></td>
        </tr>
        
        <tr>
        <td><div style="width:150px;"><input type="checkbox" name="multiseat" value="multiseat" />&nbsp;&nbsp;Multi-Seat</div></td>
        <td>+ $30</td>
        </tr>
        
        <tr>
        <td><div style="width:150px;"><input type="checkbox" name="printruns" value="printruns" />&nbsp;&nbsp;Unlimited Reproduction</div></td>
        <td colspan="2">+ $40</td>
        </tr>
        
        <tr>
        <td><div style="width:150px;"><input type="checkbox" name="resale" value="resale" />&nbsp;&nbsp;Allow Resale</div></td>
        <td colspan="2">+ $50</td>
        </tr>
        
        <tr>
        <td><div style="width:150px;"><input type="checkbox" name="electronic" value="electronic" />&nbsp;&nbsp;Allow Electronic Use</div></td>
        <td colspan="2">+ $60</td>
        </tr>

        </tbody>
        </table>
        
        <div id="remove" class="hide" style="text-align:center;">
        <a class="btn btn-success" style="width:210px;padding:7px;" href="#">Remove from Store</a>
        </div>
        
                    </div>
                    
                    <a name="',$id,'" href="#"><p class="flip',$id,'" style="font-size:15px;"></a>Manage</p>
                    
                    
                    <style type="text/css">
                    p.flip',$id,' {
                    padding:10px;
                    width:223px;
                    clear:both;
                    text-align:center;
                    background:white;
                    border:solid 1px #c3c3c3;
                    }

                    p.flip',$id,':hover {
                    background-color: #ccc;
                    }

                    div.panel',$id,' {
                    display:none;
                    clear:both;
                    padding:300px;
                    padding:5px;
                    text-align:left;
                    background:white;
                    border:solid 1px #c3c3c3;
                    }
                    </style>
                    
                    <!--HIDDEN COMMENT SCRIPT-->
                    <script type="text/javascript">   
                    $(document).ready(function(){
                    $(".flip',$id,'").click(function(){
                        $(".panel',$id,'").slideToggle("slow");
                    });
                    });
                    </script>
                    
                </div>';


	    
                } //end for loop      
        
        echo'</div>';
        echo'</div>';
        
        
        }
        
        
        elseif($option == 'addtostore') {  
        
        echo'
            <div class="grid_18" style="margin:auto;border:1px solid #ccc;margin-top:30px;margin-left:20px;">';
            
            if($_GET['action'] == 'submittomarket') {
    
                $source = mysql_real_escape_string($_POST['checked']);
                $newprice = mysql_real_escape_string($_POST['newprice']);
            
                echo'<div style="margin:auto;border:1px solid #ccc;height:150px;">
                <img style="float:left;padding:15px;" src="',$source,'" height="120" width="120" />
                <div style="float:left;padding:15px;margin-top:20px;font-size:14px;font-weight:200;">
                <span style="font-size:16px;font-weight:400;color:green;">Now in Market</span><br />
                New Price: $',$newprice,'<br/>
                New License: Extended License</div>
                </div><br />';
            }
            
            echo'
            
            <script type="text/javascript">
            function showSelect() {
                var select = document.getElementById(\'extended\');
                select.className = \'show\';
                document.getElementById(\'cc\').className = \'hide\';
            }
            function showSelectHide() {
                var select = document.getElementById(\'extended\');
                select.className = \'hide\';
            }
            function showOtherPrice() {
                if (document.getElementById(\'price\').value == \'Other Price\')
                    {
                        document.getElementById(\'otherprice\').className = \'show\';
                    }
                else {
                    document.getElementById(\'otherprice\').className = \'hide\';
                    }
            }
            </script>
            
            
        <div>
        <div style="padding:10px;"><a style="width:150px;padding:8px;float:left;" class="btn btn-success" data-toggle="modal" data-backdrop="static" href="#marketphoto">Add Photo To Market</a></div>
                <form action="myprofile3.php?view=store&option=addtostore&action=submittomarket" method="POST">
            <div style="float:left;padding:10px;">
            <select id="price" name="newprice" style="width:150px;margin-top:-15px;margin-left:10px;" onchange="showOtherPrice()">
            <option value="">Choose Price&#8230;</option>
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
            <option value="Other Price">Custom Price</option>
            </select>
            </div>
            <div id="otherprice" class="hide" style="float:left;margin-top:-5px;margin-left:10px;"><div class="input-prepend input-append">
                <span class="add-on">$</span><input class="span2" id="appendedPrependedInput" size="16" type="text"><span class="add-on">.00</span>
              </div></div>
            
         </div>
         <hr>   
 
        <div style="text-align:center;">
        <input type="radio" name="market" value="standard" onclick="showSelectHide();" />&nbsp;&nbsp;Standard License&nbsp;&nbsp;<input style="margin-left:40px;" type="radio" name="market" value="extended" onclick="showSelect();" />&nbsp;&nbsp;Extended License&nbsp;&nbsp;
        </div>
        
        <div id="extended" class="hide">
        
        <br />
        <b style="padding:10px;">Additonal Options for Sale:</b>
        
        <table class="table">
        <tbody>
        
        <tr>
        <td><input type="checkbox" name="multiseat" value="multiseat" />&nbsp;&nbsp;Multi-Seat (Unlimited)</td>
        <td colspan="2">+ $30</td>
        </tr>
        
        <tr>
        <td><input type="checkbox" name="printruns" value="printruns" />&nbsp;&nbsp;Unlimited Reproduction / Print Runs</td>
        <td colspan="2">+ $40</td>
        </tr>
        
        <tr>
        <td><input type="checkbox" name="resale" value="resale" />&nbsp;&nbsp;Items for Resale - Limited Run</td>
        <td colspan="2">+ $50</td>
        </tr>
        
        <tr>
        <td><input type="checkbox" name="electronic" value="electronic" />&nbsp;&nbsp;Unlimited Electronic Use</td>
        <td colspan="2">+ $60</td>
        </tr>
        </div>
        
        </tbody>
        </table>
        </div>
        
            <!--ADD PHOTO TO BLOG POST MODAL-->

            <div class="modal hide fade" id="marketphoto" style="overflow-y:scroll;overflow-x:hidden;">

            <div class="modal-header">
            <a style="float:right" class="btn btn-primary" data-dismiss="modal">Close</a>
            <img style="margin-top:-4px;" src="graphics/logoteal.png" height="28" width="90" />&nbsp;&nbsp;<span style="font-size:16px;">Choose a photo to add to your blog post:</span>
            </div>
            <div modal-body" style="width:600px;">

            <div id="content" style="font-size:16px;width:550px;height:500px;overflow-x:hidden;">';

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
        
        <div style="padding:10px;float:right;"><button style="width:150px;padding:8px;" class="btn btn-primary" type="submit">Upload Now</button></div>
        </form>    
        </div>';
            
        }
    
    }
    
    

    elseif($view == 'network') {
    
        $option = htmlentities($_GET['option']);    
    
        echo'<br /><br /><br /><br /><div style="width:760px;text-align:center;font-size:14px;font-weight:200;"><div style="margin-left:20px;"><a class="green" style="text-decoration:none;color:#000;';if($option == '') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'" href="myprofile3.php?view=network">Following</a> | <a class="green" style="text-decoration:none;color:#000;';if($option == 'followers') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'" href="myprofile3.php?view=network&option=followers">Followers</a></div></div>';
        
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
        
        echo'<div style="margin-left:20px;">';
        for($iii = 0; $iii < $numberfollowing; $iii++) {
		$followingpic = mysql_result($followingquery, $iii, "profilepic");
		$followingfirst = mysql_result($followingquery, $iii, "firstname");
		$followinglast = mysql_result($followingquery, $iii, "lastname");
        $fullname = $followingfirst . " " . $followinglast;
        $fullname = ucwords($fullname);
        $followingid = mysql_result($followingquery, $iii, "user_id");
		
                echo '   

                <div style="width:245px;height:245px;overflow:hidden;float:left;margin-left:10px;margin-top:30px;"><a href="http://photorankr.com/viewprofile3.php?u=',$followingid,'">

                <div class="statoverlay" style="z-index:1;left:0px;top:210px;position:relative;background-color:black;width:245px;height:35px;"><p style="line-spacing:1.48;padding:5px;color:white;"><span style="font-size:18px;font-weight:100;">',$fullname,'</span></p></div>

                <img onmousedown="return false" oncontextmenu="return false;" style="position:relative;top:-35px;min-height:245px;min-width:245px;" src="http://www.photorankr.com/',$followingpic,'" height="245" width="245" /></a></div>';
        
        }
        echo'</div>';
    }
    
    
    elseif($view == 'favorites') {
    
        $favesquery = "SELECT * FROM userinfo WHERE emailaddress='$email' LIMIT 0, 1";
        $favesresult = mysql_query($favesquery) or die(mysql_error());
        $faves = mysql_result($favesresult, 0, "faves");
        
        $query = mysql_query("SELECT * FROM photos WHERE source IN ($faves) ORDER BY FIELD (source, $faves) DESC LIMIT 9");
        $numresults = mysql_num_rows($query);
        echo'<div id="thepics">';
        echo'<div id="container" class="grid_18" style="width:770px;margin-top:0px;padding-left:20px;padding-right:45px;margin-left:-5px;background-color:rgba(245,245,245,0.6);">';

        for($iii=0; $iii < $numresults; $iii++) {
              
                $image[$iii] = mysql_result($query, $iii, "source");
                $imageThumb[$iii] = str_replace("userphotos/","../userphotos/medthumbs/", $image[$iii]);
                $id = mysql_result($query, $iii, "id");
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
                $heightls = $height / 3.5;
                $widthls = $width / 3.5;

                echo '   

                <div class="fPic" id="',$id,'" style="width:245px;height:245px;overflow:hidden;float:left;margin-left:10px;margin-top:30px;"><a href="http://photorankr.com/fullsize.php?image=', $image[$iii], '">

                <div class="statoverlay" style="z-index:1;left:0px;top:155px;position:relative;background-color:black;width:245px;height:75px;"><p style="line-spacing:1.48;padding:5px;color:white;"><span style="font-size:16px;font-weight:100;">',$caption,'</span><br><span style="font-size:14px;font-weight:100;">Score: ',$score,'<br>Favorites: ',$faves,'</span></p></div>

                <img onmousedown="return false" oncontextmenu="return false;" style="position:relative;top:-90px;min-height:245px;min-width:245px;" src="http://www.photorankr.com/',$imageThumb[$iii],'" height="',$heightls,'px" width="',$widthls,'px" /></a></div>';
	    
                } //end for loop      
        
        echo'</div>';
        echo'</div>';
        
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
				$("div#loadMoreFavePics").show();
				$.ajax({
					url: "loadMoreFavePics3.php?lastPicture=" + $(".fPic:last").attr("id"),
					success: function(html) {
						if(html) {
							$("#thepics").append(html);
							$("div#loadMoreFavePics").hide();
						}
					}
				});
				last = $(".fPic:last").attr("id");
			}
		}
	});
</script>';

}
    
    
        elseif($view == 'favorites') {
    
        $query = mysql_query("SELECT * FROM photos WHERE source IN ($faves) ORDER BY FIELD (source, $faves) DESC LIMIT 9");
        $numresults = mysql_num_rows($query);
        echo'<div id="thepics">';
        echo'<div id="container" class="grid_18" style="width:770px;margin-top:0px;padding-left:20px;">';

        for($iii=0; $iii < $numresults; $iii++) {
              
                $image[$iii] = mysql_result($query, $iii, "source");
                $imageThumb[$iii] = str_replace("userphotos/","../userphotos/medthumbs/", $image[$iii]);
                $id = mysql_result($query, $iii, "id");
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
                $heightls = $height / 3.5;
                $widthls = $width / 3.5;

                echo '   

                <div class="fPic" id="',$id,'" style="width:245px;height:245px;overflow:hidden;float:left;margin-left:10px;margin-top:30px;"><a href="http://photorankr.com/fullsize.php?image=', $image[$iii], '">

                <div class="statoverlay" style="z-index:1;left:0px;top:155px;position:relative;background-color:black;width:245px;height:75px;"><p style="line-spacing:1.48;padding:5px;color:white;"><span style="font-size:16px;font-weight:100;">',$caption,'</span><br><span style="font-size:14px;font-weight:100;">Score: ',$score,'<br>Favorites: ',$faves,'</span></p></div>

                <img onmousedown="return false" oncontextmenu="return false;" style="position:relative;top:-90px;min-height:245px;min-width:245px;" src="http://www.photorankr.com/',$imageThumb[$iii],'" height="',$heightls,'px" width="',$widthls,'px" /></a></div>';
	    
                } //end for loop      
        
        echo'</div>';
        echo'</div>';
    
    }
    
    
    elseif($view == 'search') {
        
        $searchterm = htmlentities(mysql_real_escape_string($_POST['searchterm']));
        $query = mysql_query("SELECT * FROM photos WHERE concat(caption,location,tag1,tag2,tag3,tag4) LIKE '%$searchterm%' AND emailaddress = '$email' ORDER BY (views) DESC");
        $numresults = mysql_num_rows($query);
        echo'<div id="thepics">';
        echo'<div id="container" class="grid_18" style="width:770px;margin-top:0px;padding-left:20px;">';
        
        echo'<br /><div style="width:760px;text-align:center;font-size:17px;font-weight:200;"><div style="margin-left:20px;">';
        if($numresults > 0) {echo $numresults . ' Photos Found'; } else {echo'Sorry, No Photos Found For "',$searchterm,'"';}
        echo'
        </div></div>';

        for($iii=0; $iii < $numresults; $iii++) {
              
                $image[$iii] = mysql_result($query, $iii, "source");
                $imageThumb[$iii] = str_replace("userphotos/","../userphotos/medthumbs/", $image[$iii]);
                $id = mysql_result($query, $iii, "id");
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
                $heightls = $height / 3.5;
                $widthls = $width / 3.5;

                echo '   

                <div class="fPic" id="',$id,'" style="width:245px;height:245px;overflow:hidden;float:left;margin-left:10px;margin-top:30px;"><a href="http://photorankr.com/fullsize.php?image=', $image[$iii], '">

                <div class="statoverlay" style="z-index:1;left:0px;top:155px;position:relative;background-color:black;width:245px;height:75px;"><p style="line-spacing:1.48;padding:5px;color:white;"><span style="font-size:16px;font-weight:100;">',$caption,'</span><br><span style="font-size:14px;font-weight:100;">Score: ',$score,'<br>Favorites: ',$faves,'</span></p></div>

                <img onmousedown="return false" oncontextmenu="return false;" style="position:relative;top:-90px;min-height:245px;min-width:245px;" src="http://www.photorankr.com/',$imageThumb[$iii],'" height="',$heightls,'px" width="',$widthls,'px" /></a></div>';
	    
                } //end for loop      
        
        echo'</div>';
        echo'</div>';
    
    }
    
    
    
    elseif($view == 'upload') {
    
                $option = htmlentities($_GET['option']);    

                echo'<br /><br /><br /><br /><div style="width:760px;text-align:center;font-size:14px;font-weight:200;"><div style="margin-left:20px;"><a class="green" style="text-decoration:none;';if($option == '') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'" href="myprofile3.php?view=upload">Single Upload</a> | <a class="green" style="text-decoration:none;';if($option == 'batch') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'" href="myprofile3.php?view=upload&option=batch">Batch Upload</a> | <a class="green" style="text-decoration:none;';if($option == 'newexhibit') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'" href="myprofile3.php?view=upload&option=newexhibit">Create an Exhibit</a></div></div>
                <div id="container" class="grid_18" style="width:770px;margin-top:0px;padding-left:20px;margin-left:10px;">';
                        
                if($option == '') {
                        
                        //select all sets associated with user email
                        $setsemail = $_SESSION['email'];
                        $setsquery = "SELECT * FROM sets WHERE owner = '$setsemail'";
                        $setsqueryrun = mysql_query($setsquery);
                        $setscount = mysql_num_rows($setsqueryrun);

                        //upload a photo
                        if (htmlentities($_GET['action']) == "uploadsuccess") { 
                                echo '<span class="label label-success" style="font-size: 16px;">Upload Successful!</span><br /><br />';

                        }
    
                        else if (htmlentities($_GET['action']) == "uploadfailure") {
                                echo '<span class="label label-important" style="font-size: 16px;">Please fill out all required items.</span><br /><br />';
        
                        }
                           
        echo'
                            
        <div class="span9" style="margin-top:-38px;;margin-left:-35px;padding:20px;padding-left:67px;background-color:rgba(245,245,245,0.6);">
        <br /><br />
        <form action="myprofile3.php?view=editinfo&action=submit" method="post" enctype="multipart/form-data">
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
        
        <input type="radio" name="market" value="forsale" onclick="showSelect();" />&nbsp;&nbsp;For Sale&nbsp;&nbsp;<input style="margin-left:40px;" type="radio" name="market" value="notforsale" onclick="showSelectHide();" />&nbsp;&nbsp;Not For License&nbsp;&nbsp;<input style="margin-left:40px;" type="radio" name="market" value="cc" onclick="showSelect2();" />&nbsp;&nbsp;Creative Commons&nbsp;
        
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
            <select id="price" name="pricebox" style="width:100px;float:left;" onchange="showOtherPrice()">
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
            <option value="Not For Sale">Other Price</option>
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
        <td><input type="checkbox" name="multiseat" value="multiseat" />&nbsp;&nbsp;Multi-Seat (Unlimited)</td>
        <td colspan="2">+ $30</td>
        </tr>
        
        <tr>
        <td><input type="checkbox" name="printruns" value="printruns" />&nbsp;&nbsp;Unlimited Reproduction / Print Runs</td>
        <td colspan="2">+ $40</td>
        </tr>
        
        <tr>
        <td><input type="checkbox" name="resale" value="resale" />&nbsp;&nbsp;Items for Resale - Limited Run</td>
        <td colspan="2">+ $50</td>
        </tr>
        
        <tr>
        <td><input type="checkbox" name="electronic" value="electronic" />&nbsp;&nbsp;Unlimited Electronic Use</td>
        <td colspan="2">+ $60</td>
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
        <td colspan="2"><input type="radio" name="mods" value="yes" />&nbsp&nbsp;Yes&nbsp;&nbsp;<input style="margin-left:20px;"  type="radio" name="mods" value="no" />&nbsp&nbsp;No&nbsp;&nbsp;<input style="margin-left:20px;" type="radio" name="mods" value="sharealike" />&nbsp&nbsp;Share Alike&nbsp;&nbsp;</td>
        </tr>
        
        <tr>
        <td>Allow Commercial Uses of Your Work?</td>
        <td colspan="2"><input type="radio" name="commercial" value="yes" />&nbsp&nbsp;Yes&nbsp;&nbsp;<input style="margin-left:20px;" type="radio" name="commercial" value="no" />&nbsp&nbsp;No&nbsp;&nbsp;</td>
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

<!-- Force latest IE rendering engine or ChromeFrame if installed -->
<!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><![endif]-->
<!-- Generic page styles -->
<link rel="stylesheet" href="css/stylebatch.css">
<!-- Bootstrap CSS fixes for IE6 -->
<!--[if lt IE 7]><link rel="stylesheet" href="http://blueimp.github.com/cdn/css/bootstrap-ie6.min.css"><![endif]-->
<!-- Bootstrap Image Gallery styles -->
<link rel="stylesheet" href="http://blueimp.github.com/Bootstrap-Image-Gallery/css/bootstrap-image-gallery.min.css">
<!-- CSS to style the file input field as button and adjust the Bootstrap progress bars -->
<link rel="stylesheet" href="css/jquery.fileupload-ui.css">
<!-- Shim to make HTML5 elements usable in older Internet Explorer versions -->
<!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->


<div class="container">
    <br>
    <!-- The file upload form used as target for the file upload widget -->
    <form id="fileupload" action="server/php/" method="POST" enctype="multipart/form-data">
        <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
        <div class="row fileupload-buttonbar">
            <div class="span7">
                <!-- The fileinput-button span is used to style the file input field as button -->
                <span class="btn btn-success fileinput-button">
                    <i class="icon-plus icon-white"></i>
                    <span>Add Photos...</span>
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
                <div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
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
        <table role="presentation" class="table table-striped"><tbody class="files" data-toggle="modal-gallery" data-target="#modal-gallery"></tbody></table>
    </form>
    <br>
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
<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
var d = new Date(milliseconds);
    <tr class="template-upload fade">
        <td class="preview"><span class="fade"></span></td>
        <td class="name"><span>{%= file.name %}</span></td>
            <td colspan="1" class="desc">Name: <input type="text" name="desc"></td>
<td colspan="1" class="desc">Price: <input type="text" name='{%= "price_" + String(file.name).replace(/([.]+)/gi, '_') + d%}'/></td>
<td colspan="1" class="desc">Keyword: <input type="text" name='{%= "keyword_" + String(file.name).replace(/([.]+)/gi, '_')%}'/></td>
<td>
    <select style="width:150px;height:150px;" multiple="multiple" name='{%= "singlestyletags" + String(file.name).replace(/([.]+)/gi, '_')%} + []'>
    <option value="standardcontentlicense">Standard Content License</option>
    <option value="extendedlicense1">Unlimited Reproduction and Print Run</option>
     <option value="extendedlicense2">Multi-Seat License</option>
    <option value="limitedlicense">Limited Resale</option>
     <option value="extendedlicense4">Electronic Resale or Other Distribution</option>
     </select>
</td>
        <td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
        {% if (file.error) { %}
            <td class="error" colspan="2"><span class="label label-important">{%=locale.fileupload.error%}</span> {%=locale.fileupload.errors[file.error] || file.error%}</td>
        {% } else if (o.files.valid && !i) { %}
            <td>
                <div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="bar" style="width:0%;"></div></div>
            </td>
            <td class="start">{% if (!o.options.autoUpload) { %}
                <button class="btn btn-primary">
                    <i class="icon-upload icon-white"></i>
                    <span>{%=locale.fileupload.start%}</span>
                </button>
            {% } %}</td>
        {% } else { %}
            <td colspan="2"></td>
        {% } %}
        <td class="cancel">{% if (!i) { %}
            <button class="btn btn-warning">
                <i class="icon-ban-circle icon-white"></i>
                <span>{%=locale.fileupload.cancel%}</span>
            </button>
        {% } %}</td>
    </tr>
{% } %}

</script>

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
<script src="js/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="js/jquery.fileupload.js"></script>
<!-- The File Upload file processing plugin -->
<script src="js/jquery.fileupload-fp.js"></script>
<!-- The File Upload user interface plugin -->
<script src="js/jquery.fileupload-ui.js"></script>
<!-- The localization script -->
<script src="js/locale.js"></script>
<!-- The main application script -->
<script src="js/main.js"></script>
<!-- The XDomainRequest Transport is included for cross-domain file deletion for IE8+ -->
<!--[if gte IE 8]><script src="js/cors/jquery.xdr-transport.js"></script><![endif]-->
                            
                            <?php

                        }
                        
                        elseif($option == 'newexhibit') {
                        
	if (htmlentities($_GET['ns']) == "success") { 
    echo'<br /><br /><span style="font-size: 20px;"><a href="myprofile.php?view=upload">Add photos to your new exhibit!</a></span><br />';
    }
    
    elseif (htmlentities($_GET['ns']) == "failure") { 
    echo'<br /><br /><span style="font-size: 20px;color:red;">Please fill out all fields!</span><br />';
    }
    
    elseif (htmlentities($_GET['ns']) == "name") { 
    echo'<br /><br /><span style="font-size: 20px;color:red;">You already have an exhibit titled this!</span><br />';
    }
    
    echo'
	<form action="create_set.php" method="post" enctype="multipart/form-data">
    <br />
    <br />
    <div class="well">
	<span style="font-size:16px">Title of exhibit:&nbsp;</span><input type="text" name="title" />
    <br />
    <br />
	<span style="font-size:16px">Pick 2 or more tags (search terms) that describe this exhibit:</span>
    <br />
    <span style="font-size:13px">(Hold down command button if on mac, control button if on PC)</span>
    <br />
    <br />
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
    <br />
    <br />
    Choose some of your own tags: <br /><br />
    <input style="width:80px;height:20px;" type="text" name="settag1" />
    <input style="width:80px;height:20px;" type="text" name="settag2" />
    <input style="width:80px;height:20px;" type="text" name="settag3" />
    <input style="width:80px;height:20px;" type="text" name="settag4" />
<br />
<br />
<div style="line-height:22px;">
About this exhibit:<br /><textarea style="width:500px" rows="4" cols="60" name="about"></textarea></div> 
<br />

<button type="submit" name="Submit" class="btn btn-success">Create Exhibit</button>
</form>
</div> <!--end of well-->
</div>';

            }
                        
                
                    
                echo'</div>';
    }
    
    
    
    
    elseif($view == 'blog') {
    
  
         $option = htmlentities($_GET['option']);
  
         echo'<br /><br /><br /><br /><div style="width:760px;text-align:center;font-size:14px;font-weight:200;"><div style="margin-left:20px;"><a class="green" style="text-decoration:none;'; if($option == 'newpost') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'" href="myprofile3.php?view=blog&option=newpost">Make New Post</a> | <a class="green" style="text-decoration:none;color:#333;'; if($option == '') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'" href="myprofile3.php?view=blog">View Blog</a></div></div>';
         
        echo'<div id="container" class="grid_18" style="width:770px;margin-top:-38px;margin-left:-10px;padding:35px;background-color:rgba(245,245,245,0.6);">';

        if($option == 'newpost') {
        
        $time = time();
       
         echo'
            <script>
             function showBlogPhoto() {
                   var blogphoto = document.getElementById(\'blogphoto\').value;
                }
            </script>
            
            <div class="grid_18" style="margin:auto;border:1px solid #ccc;margin-top:30px;margin-left:20px;">
            <div style="float:left;padding:15px;width:130px;height:130px;"><img style="width:130px;height:130px;" src="" height="120" width="120" /><br /><div style="padding:10px;"><a style="width:80px;" class="btn btn-success" data-toggle="modal" data-backdrop="static" href="#blogphoto">Add Photo</a></div></div>
            <div style="float:left;font-size:15px;font-weight:200;padding-top:25px;">Title:<br /><br />Subject:<br /><br />Content (400 words):</div>
           
            <form action="myprofile3.php?view=blog&action=submitpost" method="POST">
            
            <div style="float:left;padding:25px;width:350px;"><input style="width:220px;height:20px;" type="text" name="title" placeholder="Title of Blog Post" /><br />
            <input style="width:220px;height:20px;" type="text" name="subject" placeholder="Subject of Blog Post" /></div>
            <input type="hidden" name="time" value="',$time,'" />
            <div style="float:left;margin-top:15px;"><textarea style="width:480px;max-width:480px;" rows="12" cols="60" name="content"></textarea><br /><br />

             <!--ADD PHOTO TO BLOG POST MODAL-->

            <div class="modal hide fade" id="blogphoto" style="overflow-y:scroll;overflow-x:hidden;">

            <div class="modal-header">
            <a style="float:right" class="btn btn-primary" data-dismiss="modal">Close</a>
            <img style="margin-top:-4px;" src="graphics/logoteal.png" height="28" width="90" />&nbsp;&nbsp;<span style="font-size:16px;">Choose a photo to add to your blog post:</span>
            </div>
            <div modal-body" style="width:600px;">

            <div id="content" style="font-size:16px;width:550px;height:500px;overflow-x:hidden;">';

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
        <button class="btn btn-success" data-dismiss="modal">Submit Photos</button>
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
                echo'<div style="font-size:18px;font-weight:200;padding:40px;text-align:center;"><a style="color:#333;" href="myprofile3.php?view=blog&option=newpost">You have no blog posts yet. Click here to write your first post.</a></div>';
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
                    <form action="myprofile3.php?view=blog&action=comment&blogid=',$id,'" method="POST">
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
	
		echo '<div class="grid_18" style="background-color:rgba(245,245,245,0.6);padding-left:30px;padding-right:90px;padding-bottom:20px;padding-top:20px;margin-left:-45px;">';

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
			<a href="myprofile3.php?view=viewthread&thread=', $currentthread, '" style="text-decoration: none;">
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
			$othersquery = "SELECT firstname, lastname, profilepic, emailaddress FROM userinfo WHERE emailaddress='" . $othersemail . "' LIMIT 0, 1";
			$othersresult = mysql_query($othersquery);
			$otherspic = mysql_result($othersresult, 0, "profilepic");
			$othersfirst = mysql_result($othersresult, 0, "firstname");
			$otherslast = mysql_result($othersresult, 0, "lastname");
			
			//for loop to go through all the messages in reverse order so that the newest one is last
			for($iii=$numberofmessages-1; $iii >= 0; $iii--) {
				//find out who sent the current message in the loop
				$currentsender = mysql_result($threadresult, $iii, "sender");

				//if the current message's sender is the owner of the profile, set the variables as necessary
				if($currentsender == $email) {
					$currentfirst = $firstname;
					$currentlast = $lastname;
					$currentpic = $profilepic;
				}
				//otherwise the other person is the message's sender, so set the variables accordingly
				else {
					$currentfirst = $othersfirst;
					$currentlast = $otherslast;
					$currentpic = $otherspic;
				}
				
				//find out what the current message is
				$currentmessage = mysql_result($threadresult, $iii, "contents");

				//now that we have everything in line, display the message
				echo '
				<div class="grid_18 message" style="margin-bottom: 20px; font-family: arial;">
					<a href="viewprofile.php?first=', $currentfirst, '&last=', $currentlast,'">
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


elseif($view == 'promote') {

echo'
<div class="grid_18" style="width:770px;margin-top:10px;padding-left:20px;">

<div class="well" style="font-size:16px;font-family:helvetica neue, gill sans, helvetica;">

<!--Referral Success-->';

$refer=htmlentities($_GET['refer']); 

if ($refer == 'referralsuccess') {
$sendname = $_POST['sendname'];
$sendemail = $_POST['email'];
$to = $sendemail;
$subject = "Your Personal Invitation";
$message = "Hi! You've been invited by $sendname to join PhotoRankr, a site for photographers of all skill levels. What makes PhotoRankr different from the other photo sharing sites?

– The ability to choose the price of your photography 
– Unlimited uploads and 100% free
– Follow other photographers with one click, and view your live 'photostream' of photography from those you follow
– Rank other photography and get feedback from other photographers through comments 
– Make your own profile where you can view your entire portfolio, your followers, who's following you, and edit your information

To accept your invitation and begin following photography today, just click the link below:

http://photorankr.com/signin.php

We hope you'll enjoy PhotoRankr as much as we have building it,

Sincerely,
The PhotoRankr Team
";

$headers = 'From:PhotoRankr <photorankr@photorankr.com>';
mail($to, $subject, $message, $headers);

echo '<span style="position:relative;top:0px;font-family:lucida grande, georgia, helvetica; font-size: 16px;" class="label label-success">Referral successfully sent</span><br /><br />';

}


echo'
Help promote your portfolio and your PhotoRankr page by sharing it with your friends:<br /><span style="font-size:13px;">(This will help increase traffic to your specific page, increase sales, and raise the chances of your photos becoming trending.)</span><br /><br />

<!--FB-->
<a name="fb_share" share_url="http://photorankr.com/viewprofile.php?u=',$user,'"></a> 
<script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" 
        type="text/javascript">
</script>

<!--TWITTER-->
<div style="position:relative;margin-top:15px;">
<a href="https://twitter.com/share" class="twitter-share-button" data-url="http://photorankr.com/viewprofile.php?u=',$user,'" data-text="Visit my photography site on PhotoRankr!" data-via="PhotoRankr" data-related="PhotoRankr">Tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");
</script></div>

<!--GOOGLE PLUS-->
<div style="position:relative;margin-top:15px;">
<div class="g-plus" data-action="share" data-href="http://photorankr.com/viewprofile.php?u=',$user,'"></div>';
?>

<script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script>
<?php
echo'

<!--TUMBLR-->
<div style="position:relative;margin-top:15px;">
<span id="tumblr_button_abc123"></span>
</div>

<br />

<!--Referral System-->

Invite your friends to join and follow your photography on PhotoRankr:<br /><br />

<div style="position:relative; top:20px; font-family:lucida grande, georgia, helvetica; font-size: 14px;">Your Name:</div>

<div style="position:relative; top:45px; font-family:lucida grande, georgia, helvetica; font-size: 14px;">Send invitation to:</div>

<div style="position:relative; top:-25px; left:160px;">
<form action="myprofile.php?view=promote&refer=referralsuccess" method="POST">
<input style="width:180px;height:22px;" type="text" name="sendname" value="',$firstname,' ',$lastname,'" />
</div>
<div style="position:relative; top:-20px; left:160px;">
<input style="width:180px;height:22px;" type="text" name="email" placeholder="Email Address"/>
</div>
<div style="position:relative; top:-20px; left:263px;">
<button type="submit" name="Submit" class="btn btn-success">Send Invite</button>
</div>
</form>
</div>

</div>
</div>';

}

    
elseif($view == 'settings') {
    
        $action = htmlentities($_GET['action']);

if ($action == 'savesettings') {
    
$emailcomment = mysql_real_escape_string($_POST['emailcomment']);
$emailreturncomment = mysql_real_escape_string($_POST['emailreturncomment']);
$emailfave = mysql_real_escape_string($_POST['emailfave']);		
$emailfollow = mysql_real_escape_string($_POST['emailfollow']);	

$settinglist = $emailcomment . $emailreturncomment . $emailfave . $emailfollow;

$settingquery = "UPDATE userinfo SET settings = '$settinglist' WHERE emailaddress='$email'";
$settingrun = mysql_query($settingquery);

//Grab what they have checked
$settingemail = $_SESSION['email'];
$settingquery = "SELECT * FROM userinfo WHERE emailaddress = '$settingemail'";
$settingqueryrun = mysql_query($settingquery);
$settinglist = mysql_result($settingqueryrun, 0, "settings");

echo'
<div class="grid_18" style="background-color:rgba(245,245,245,0.6);padding-left:30px;padding-right:95px;padding-bottom:20px;padding-top:20px;margin-left:-5px;">
<span style="font-size:16px;">Notification Settings:</span>
<br />
<span class="label label-success" style="font-size:16px;position:relative;top:15px;">Settings Saved</span><br /><br />
<form action="', htmlentities($_SERVER['PHP_SELF']), '?view=settings&action=savesettings" method="post" enctype="multipart/form-data">
<br />';
        
$setting_string = $settinglist;
$find = "emailcomment";
$foundsetting = strpos($setting_string,$find);
if($foundsetting > 0) {
echo'
<input type="checkbox" name="emailcomment" value=" emailcomment " checked />&nbsp;Receive an email when your photo is commented on<br /><br />'; }
else {
echo'
<input type="checkbox" name="emailcomment" value=" emailcomment " />&nbsp;Receive an email when your photo is commented on<br /><br />'; }

$find2 = "emailreturncomment";
$foundsetting2 = strpos($setting_string,$find2);
if($foundsetting2 > 0) {
echo'
<input type="checkbox" name="emailreturncomment" value=" emailreturncomment " checked />&nbsp;Receive an email when another photographer comments on a photo you also commented on<br /><br />'; }
else {
echo'
<input type="checkbox" name="emailreturncomment" value=" emailreturncomment " />&nbsp;Receive an email when another photographer comments on a photo you also commented on<br /><br />'; }

$find3 = "emailfave";
$foundsetting3 = strpos($setting_string,$find3);
if($foundsetting3 > 0) {
echo'
<input type="checkbox" name="emailfave" value=" emailfave " checked />&nbsp;Receive an email when another photographer favorites your photo<br /><br />'; }
else {
echo'
<input type="checkbox" name="emailfave" value=" emailfave " />&nbsp;Receive an email when another photographer favorites your photo<br /><br />'; }

$find4 = "emailfollow";
$foundsetting4 = strpos($setting_string,$find4);
if($foundsetting4 > 0) {
echo'
<input type="checkbox" name="emailfollow" value=" emailfollow " checked />&nbsp;Receive an email when someone follows your photography<br /><br />'; }
else {
echo'
<input type="checkbox" name="emailfollow" value=" emailfollow " />&nbsp;Receive an email when someone follows your photography<br /><br />'; }

echo'
<button type="submit" name="Submit" class="btn btn-success">Save Notification Settings</button>
</form>
</div>
';

}
    
else {
 
 
$settingemail = $_SESSION['email'];
$settingquery = "SELECT * FROM userinfo WHERE emailaddress = '$settingemail'";
$settingqueryrun = mysql_query($settingquery);
$settinglist = mysql_result($settingqueryrun, 0, "settings");

echo'
<div class="grid_18" style="background-color:rgba(245,245,245,0.6);padding-left:30px;padding-right:95px;padding-bottom:20px;padding-top:20px;margin-left:-5px;">
<span style="font-size:16px;">Notification Settings:</span>
<form action="', htmlentities($_SERVER['PHP_SELF']), '?view=settings&action=savesettings" method="post" enctype="multipart/form-data">
<br />';
        
$setting_string = $settinglist;
$find = "emailcomment";
$foundsetting = strpos($setting_string,$find);
if($foundsetting > 0) {
echo'
<input type="checkbox" name="emailcomment" value=" emailcomment " checked/>&nbsp;Receive an email when your photo is commented on<br /><br />'; }
else {
echo'
<input type="checkbox" name="emailcomment" value=" emailcomment " />&nbsp;Receive an email when your photo is commented on<br /><br />'; }

$find2 = "emailreturncomment";
$foundsetting2 = strpos($setting_string,$find2);
if($foundsetting2 > 0) {
echo'
<input type="checkbox" name="emailreturncomment" value=" emailreturncomment " checked />&nbsp;Receive an email when another photographer comments on a photo you also commented on<br /><br />'; }
else {
echo'
<input type="checkbox" name="emailreturncomment" value=" emailreturncomment " />&nbsp;Receive an email when another photographer comments on a photo you also commented on<br /><br />'; }

$find3 = "emailfave";
$foundsetting3 = strpos($setting_string,$find3);
if($foundsetting3 > 0) {
echo'
<input type="checkbox" name="emailfave" value=" emailfave " checked />&nbsp;Receive an email when another photographer favorites your photo<br /><br />'; }
else {
echo'
<input type="checkbox" name="emailfave" value=" emailfave " />&nbsp;Receive an email when another photographer favorites your photo<br /><br />'; }

$find4 = "emailfave";
$foundsetting4 = strpos($setting_string,$find4);
if($foundsetting4 > 0) {
echo'
<input type="checkbox" name="emailfollow" value=" emailfollow " checked />&nbsp;Receive an email when someone follows your photography<br /><br />'; }
else {
echo'
<input type="checkbox" name="emailfollow" value=" emailfollow " />&nbsp;Receive an email when someone follows your photography<br /><br />'; }

echo'
<button type="submit" name="Submit" class="btn btn-success">Save Notification Settings</button>
</form>


<!--Choose Background Photo-->';

if($_GET['mode'] == 'updatebackground') {
echo'<br /><span style="position:relative;margin-top:-130px;font-size: 16px;"><span class="label label-success" style="font-size:16px;" >Background Saved</span><br /><br /<br /><br /></span>';
}

echo'
<a data-toggle="modal" data-backdrop="static" href="#submitfromportfolio"><button style="margin-top:20px;" class="btn btn-success"><b>Choose Background Image</b></button></a>

</div>';

}

//Update Background Modal
echo'<div class="modal hide fade" id="submitfromportfolio" style="overflow-y:scroll;overflow-x:hidden;">

<div class="modal-header">
<a style="float:right" class="btn btn-primary" data-dismiss="modal">Close</a>
<img style="margin-top:-4px;" src="graphics/logoteal.png" height="30" width="100" />&nbsp;&nbsp;<span style="font-size:16px;">Choose your profile background image:</span>
  </div>
  <div modal-body" style="width:600px;">

<div id="content" style="font-size:16px;width:550px;height:500px;overflow-x:hidden;">';

if($email != '') {
echo'
<img style="border: 1px solid black;margin-left:10px;margin-top:10px;" src="',$profilepic,'" 
height="100px" width="100px" />

<div style="width:540px;margin-left:130px;margin-top:-125px;overflow-y:scroll;overflow-x:hidden;">

<form action="myprofile3.php?view=settings&mode=updatebackground" method="post">
    <span style="font-size:14px;">
    <br /><br />';
    $allusersphotos = "SELECT * FROM photos WHERE emailaddress = '$email'";
    $allusersphotosquery = mysql_query($allusersphotos);
    $usernumphotos = mysql_num_rows($allusersphotosquery);
    
    for($iii = 0; $iii < $usernumphotos; $iii++) {
        $userphotosource = mysql_result($allusersphotosquery, $iii, "source");
        $userphotosource = str_replace("userphotos/","http://photorankr.com/userphotos/", $userphotosource);
        $userphotosset[$iii] = mysql_result($allusersphotosquery, $iii, "sets");
        $userphotoscaption[$iii] = mysql_result($allusersphotosquery, $iii, "caption");
        $newsource = str_replace("userphotos/","userphotos/thumbs/", $userphotosource);
        
        echo'<img src="',$newsource,'" height="70" width="70" />&nbsp;&nbsp;<input type="checkbox" name="checked" value="',$userphotosource,'" />&nbsp;"',$userphotoscaption[$iii],'"
        <br /><br />'; 
    
    } //end of for loop
    
    
    echo'
    </span>
    <button class="btn btn-success" type="submit">Save Background</button>
    <br />
    <br />
    </form>';
    }
    
    else {
    echo'<div style="text-align:center;margin-top:100px;"><b>Please login or register to upload</b></div>';
    }
    
    echo'
    </div>
    </div>';
    
    }
    
?>

</div><!--end grid 18-->


<?php

    //Edit Exhibit Modal

echo'<div class="modal hide fade" id="editexhibit" style="overflow-y:scroll;overflow-x:hidden;">

<div class="modal-header">
<a style="float:right" class="btn btn-primary" data-dismiss="modal">Close</a>
<img style="margin-top:-4px;" src="graphics/logoteal.png" height="28" width="90" />&nbsp;&nbsp;<span style="font-size:16px;">Edit your exhibit\'s information below:</span>
  </div>
  <div modal-body" style="width:600px;">

<div id="content" style="font-size:16px;width:550px;height:500px;overflow-x:hidden;">
		
<img style="border: 1px solid black;margin-left:10px;margin-top:10px;" src="',$setcover,'" 
height="100px" width="100px" />

<div style="width:540px;margin-left:130px;margin-top:-100px;overflow-y:scroll;overflow-x:hidden;">

<form action="', htmlentities($_SERVER['PHP_SELF']), '?ex=y&set=',$set,'&mode=coverchanged" method="post" enctype="multipart/form-data">
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
            echo'<img src="',$newsource,'" height="70" width="70" />&nbsp;&nbsp;<input type="checkbox" name="addthis" value="',$userphotosource[$iii],'" />&nbsp;"',$userphotoscaption[$iii],'"
        <br /><br />'; }
        
    } //end of for loop
    
    echo'
    </span>
    <button class="btn btn-success" type="submit">Save Info</button>
    </form>
    
    </div>
    </div>
    </div>';
    
?>


</div><!--end 24 grid-->

</div>


<!--TUMBLR SCRIPTS-->
<script type="text/javascript">
    var tumblr_link_url = "http://photorankr.com/viewprofile.php?u=',$user,'";
    var tumblr_link_name = "My PhotoRankr Portfolio";
    var tumblr_link_description = "Visit and rank my photography on PhotoRankr!";
</script>

<script type="text/javascript">
    var tumblr_button = document.createElement("a");
    tumblr_button.setAttribute("href", "http://www.tumblr.com/share/link?url=" + encodeURIComponent(tumblr_link_url) + "&name=" + encodeURIComponent(tumblr_link_name) + "&description=" + encodeURIComponent(tumblr_link_description));
    tumblr_button.setAttribute("title", "Share on Tumblr");
    tumblr_button.setAttribute("style", "display:inline-block; text-indent:-9999px; overflow:hidden; width:129px; height:20px; background:url('http://platform.tumblr.com/v1/share_3.png') top left no-repeat transparent;");
    tumblr_button.innerHTML = "Share on Tumblr";
    document.getElementById("tumblr_button_abc123").appendChild(tumblr_button);
</script>

<script type="text/javascript" src="http://platform.tumblr.com/v1/share.js"></script>


</body>
</html>
