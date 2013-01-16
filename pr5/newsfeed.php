<?php

//connect to the database
require "db_connection.php";
require "functions.php";

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
    
    $findreputationme = mysql_query("SELECT user_id,reputation,profilepic,firstname,lastname,following FROM userinfo WHERE emailaddress = '$email'");
    $reputationme = mysql_result($findreputationme,0,'reputation');
    $sessionpic = mysql_result($findreputationme,0,'profilepic');
    $sessionuserid =  mysql_result($findreputationme,0,'user_id');
    $sessionfirst =  mysql_result($findreputationme,0,'firstname');
    $sessionlast =  mysql_result($findreputationme,0,'lastname');
    $sessionid =  mysql_result($findreputationme,0,'user_id');
    $sessionfollowing =  mysql_result($findreputationme,0,'following');
    $sessionname = mysql_result($findreputationme,0,'firstname') ." ". mysql_result($findreputationme,0,'lastname');
    $followersquery = mysql_query("SELECT * FROM userinfo WHERE following LIKE '%$email%'");
    $numberfollowers = mysql_num_rows($followersquery);
    $followingquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress IN ($sessionfollowing)");
    $numberfollowing = mysql_num_rows($followingquery);
    $userphotos = mysql_query("SELECT id FROM photos WHERE emailaddress = '$email'");
    $numphotos = mysql_num_rows($userphotos);
    $currenttime = time();
    
       
    if (!$_SESSION['email']) {
        header("Location: galleries.php");
        exit();
    } 

$query="SELECT * FROM photos ORDER BY id DESC LIMIT 0, 16";
$result=mysql_query($query);
$numberofpics=mysql_num_rows($result);

//QUERY FOR NOTIFICATIONS
$currentnots = "SELECT * FROM userinfo WHERE emailaddress = '$email'";
$currentnotsquery = mysql_query($currentnots);
$currentnotsresult = mysql_result($currentnotsquery, 0, "notifications");

//notifications query reset 
if($currentnotsresult > 0) {
$notsquery = "UPDATE userinfo SET notifications = 0 WHERE emailaddress = '$email6'";
$notsqueryrun = mysql_query($notsquery); }

//Grab the view
if(isset($_GET['view'])) {
$view=htmlentities($_GET['view']);
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="https://www.w3.org/1999/xhtml">

<head>

  <meta name="Keywords" content="photos, sharing photos, photo sharing, photography, photography club, sell photos, sell photography, where to sell my photography, good sites for selling photography, making money from photography, making money off photography, social networking, social network, social networks, where to sell my photos, good sites for selling photos, good site to sell photos, making money from photos">
  <meta name="Description" content="A gallery of the newest photography, photographers, and exhibits on PhotoRankr.">
     <meta name="viewport" content="width=1200" /> 

	 <link rel="stylesheet" type="text/css" href="css/style.css"/>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>
	<link rel="stylesheet" type="text/css" href="css/960grid.css"/>
	<link rel="stylesheet" type="text/css" href="css/reset.css"/> 
    <link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>
    <link rel="stylesheet" type="text/css" href="css/main3.css"/>

    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery.wookmark.js"></script>  
    <script src="js/bootstrap.js"></script>          
    <link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>

    <title>PhotoRankr - Your Personal News Feed</title>

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
  
    //Fix Sidebar Left
    $(window).scroll(function(){
    if  ($(window).scrollTop() >= 229){
          $('#suggestedphotos').css({position:'fixed',top:400});
    } 
    });
    
    //Post status update
        $(function() {
        $("#statusButton").click(function() 
        {
        var firstname = '<?php echo $sessionfirst; ?>';
        var lastname = '<?php echo $sessionlast; ?>';
        var email = '<?php echo $email; ?>';
        var userpic = '<?php echo $sessionpic; ?>';
        var viewerid = '<?php echo $sessionid; ?>';
        var imageid = '<? echo $imageID; ?>';
        var status = $("#status").val();
        var dataString = 'firstname='+ firstname + '&lastname=' + lastname + '&email=' + email + '&status=' + status + '&userpic=' + userpic + '&viewerid=' + viewerid + '&imageid=' + imageid;
        $("#flash").show();
        $("#flash").fadeIn(400).html();
        $.ajax({
        type: "POST",
        url: "ajaxStatus.php",
        data: dataString,
        cache: false,
        success: function(html){
        $("ol#updateStatus").append(html);
        $("ol#update li:last").fadeIn("slow");
        $("#flash").hide();
        }
        });
        return false;
        }); });    

			//Display textarea
        $(function() 
        {
        $("#status").focus(function()
        {
        $(this).animate({"height": "42px",}, "fast" );
        $("#button_blockFeed").slideDown("fast");
        return false;
        });
        
       /* $("#status").focusout(function()
        {
        $(this).animate({"height": "22px",}, "fast" );
        $("#button_blockFeed").slideUp("fast");
        return false;
        }); */

        
        });
        </script>
        
        <style type="text/css">
            #button_blockFeed {
                display:none;
            }
            #statusButton {
                background-color:#33C33C;
                color:#ffffff;
                font-size:13px;
                font-weight:bold;
                padding:3px;
                margin-left:40px;
                float:right;
                margin-right:15px;
            }
        </style> 
</head>
<body style="overflow-x:hidden;background-image:url('graphics/linen.png')">

<?php navbar(); ?>

<!-----------------------Begin Container---------------------->
<div id="newsbg" class="container_24" style="width:1050px;position:relative;left:35px;">

<!-----------------------Begin Newsfeed----------------------->

  <!--NEWSFEED-->
    <div class="grid_14" id="thepics" style="width:530px;margin-top:70px;position:relative;margin-bottom:-30px;left:-60px;">
    <div id="container">
    
<?php

//Grab the view!
$view = htmlentities($_GET['view']);

if($view == 'search') {

    $hashTag = htmlentities($_GET['tag']);
    $searchQuery = mysql_query("SELECT * FROM photos WHERE concat(tag1,tag2,tag3,tag4,singlestyletags,singlecategorytags) LIKE '%$hashTag%' ORDER BY id DESC LIMIT 0,16");
    $numhashresults = mysql_num_rows($searchQuery);
    
    echo'<div class="galleryToolbar" style="width:550px;margin-bottom:10px;margin-left:5px;">
            <ul>
                <div style="font-size:16px;font-weight:300;padding:10px;"><img style="width:18px;" src="graphics/search.png" />&nbsp;&nbsp;<span style="font-weight:500;">',$numhashresults,' Results</span> for ',$hashTag,'</div>
            </ul>
         </div>';
         
    for($ii=0; $ii< $numhashresults && $ii < 16; $ii++) {
    
	$image = mysql_result($searchQuery,$ii,'source');
    $imagenew=str_replace("userphotos/","userphotos/medthumbs/", $image);
    $caption = mysql_result($searchQuery,$ii,'caption');
    $owner = mysql_result($searchQuery,$ii,'emailaddress');
    $ownersquery = "SELECT * FROM userinfo WHERE emailaddress = '$owner'";
    $ownerresult = mysql_query($ownersquery); 
    $ownerrow = mysql_fetch_array($ownerresult);
    $ownerfirst = $ownerrow['firstname'];
    $ownerlast = $ownerrow['lastname'];
    $ownerid = $ownerrow['user_id'];
    $ownerprofilepic = $ownerrow['profilepic'];
    $ownerfull = $ownerfirst . " " . $ownerlast;
    $ownerfull = ucwords($ownerfull);
  	list($width, $height) = getimagesize($image);
	$imgratio = $height / $width;
	$height = $imgratio * $maxwidth;
    $phrase = "<a href='viewprofile.php?u=" . $ownerid . "'>" . $ownerfull . "</a> uploaded a photo";
    
    $imageinfo = mysql_query("SELECT * FROM photos WHERE source = '$image'");
    $views = mysql_result($imageinfo,0,'views');
    $points = mysql_result($imageinfo,0,'points');
    $votes = mysql_result($imageinfo,0,'votes');
    $faves = mysql_result($imageinfo,0,'faves');
    $about = mysql_result($imageinfo,0,'about');
    $camera = mysql_result($imageinfo,0,'camera');
    $time = mysql_result($imageinfo,0,'time');
    $time = converttime($time);
    $id = mysql_result($imageinfo,0,'id');
    $lens = mysql_result($imageinfo,0,'lens');
    $filter = mysql_result($imageinfo,0,'filter');
    $aperture = mysql_result($imageinfo,0,'aperture');
    $shutterspeed = mysql_result($imageinfo,0,'shutterspeed');
    $rank = ($points / $votes);
    $rank = number_format($rank,2);
    $tag1 = mysql_result($imageinfo,0,'tag1');
    $tag2 = mysql_result($imageinfo,0,'tag2');
    $tag3 = mysql_result($imageinfo,0,'tag3');
    $tag4 = mysql_result($imageinfo,0,'tag4');
    
    list($width, $height) = getimagesize($image);
    $width = ($width / 3.2);
    $height = ($height / 3.2);
    
    ?>
    
    <script type="text/javascript">
        
        //Comment jQuery Script
        $(function() {
        $("#commentform<?php echo $id; ?>").submit(function() 
        {
        var firstname = '<?php echo $sessionfirst; ?>';
        var lastname = '<?php echo $sessionlast; ?>';
        var email = '<?php echo $email; ?>';
        var userpic = '<?php echo $sessionpic; ?>';
        var viewerid = '<?php echo $sessionid; ?>';
        var imageid = '<? echo $id; ?>';
        var comment = $("#commentBox<?php echo $id; ?>").val();
        var dataString = 'firstname='+ firstname + '&lastname=' + lastname + '&email=' + email + '&comment=' + comment + '&userpic=' + userpic + '&viewerid=' + viewerid + '&imageid=' + imageid;
        $("#flash").show();
        $("#flash").fadeIn(400).html();
        $.ajax({
        type: "POST",
        url: "newsfeedcomment.php",
        data: dataString,
        cache: false,
        success: function(html){
        $("ol#update<?php echo $id; ?>").append(html);
        $("ol#update li:last").fadeIn("slow");
        $("#flash").hide();
        }
        });
        return false;
        }); });
        
        //Display textarea
        $(function() 
        {
        $("#comment<?php echo $id; ?>").click(function()
        {
        $("#commentBox<?php echo $id; ?>").animate({"height": "85px",}, "fast" );
        $("#button_block<?php echo $id; ?>").slideDown("fast");
        jQuery("#commentform<?php echo $id; ?>").toggle();
        jQuery("#hiddenComments<?php echo $id; ?>").toggle();
        return false;
        });        
        });
 
    jQuery(document).ready(function(){
        jQuery("#showStats<?php echo $id; ?>").live("click", function(event) {        
            jQuery(".hiddenStats<?php echo $id; ?>").toggle();
        });
    });
    </script>
    
     <style type="text/css">
        .hiddenStats<?php echo $id; ?> {
            display:none;
        }
        .hiddenStats<?php echo $id; ?> ul {
            padding:15px;
            padding-top:60px;
            font-size:13px;
        }
        .hiddenStats<?php echo $id; ?> li {
            display:inline;
            padding:4px;
        }
        #hiddenComments<?php echo $id; ?> {
                display:none;
        }
        #commentform<?php echo $id; ?> {
            display:none;
            padding:20px 0;
        }
        #commentOption<?php echo $id; ?> {
            font-size:13px;
            padding-top:10px;
            font-weight:700;
            cursor:pointer;
        }
        #button_block<?php echo $id; ?> {
            display:none;
        }
        #button<?php echo $id; ?> {
            background-color:#33C33C;
            color:#ffffff;
            font-size:13px;
            font-weight:bold;
            padding:3px;
            margin-left:40px;
        }
    </style>
    
    <?php

    echo'<div class="grid_16">
         <!--Profile Picture-->
            <div class="newsBlock">
                <ul>
                    <li><img id="newsProfilePic" src="https://photorankr.com/',$ownerprofilepic,'" />
                    <li id="comment',$id,'"><img src="graphics/comment_1.png"></li>
                    <li><img src="graphics/heart.png"></li>
                     <a href="fullsizemarket.php?imageid=',id,'"><li><img src="graphics/tag.png"></li></a>
                </ul>
            </div>
         
         <!--Content Box-->
         <div class="newsContainer">
            <div class="newsTriangle"></div>
                <div class="newsItem">
                    <!--Top Controls-->
                    <div class="newsControls">
                        <div class="newsName">',$phrase,'</div>
                        <div class="newsTools">
                            <span id="time">',$time,'</span>
                        </div>
                    </div>
                    <!--Content-->
                    <div class="newsContent">
                        <a href="fullsize.php?imageid=',$id,'"><img style="min-width:485px;" src="https://photorankr.com/',$imagenew,'" width="',$width,'px" height="',$height,'px" /></a>
                    </div>
                    <!---Stats--->
                    <div class="newsStats">
                        <span style="font-size:18px;font-weight:500;">',$rank,'</span>
                        <span style="font-size:18px;font-weight:300;">',$caption,'
                        <div style="display:inline;width:240px;float:right;margin-right:50px;text-align:right;font-size:15px;">';
                        if($tag1) {
                            echo'#',$tag1,' ';
                        }
                        if($tag2) {
                            echo'#',$tag2,' ';
                        }
                        if($tag3) {
                            echo'#',$tag3,' ';
                        }                    
                        echo'
                            <a href="#"><img id="showStats',$id,'" style="width:20px;margin-top:-3px;padding-left:8px;" src="graphics/stats 4.png" /></a>
                        </div>
                    </div>
                    
                    <div class="hiddenStats',$id,'">
                        <ul>
                            <li><img src="graphics/view.png" width="15" /> Views: ',$views,'</li>';
                        
                        if($camera) {
                            echo'<li><img src="graphics/camera.png" width="15" /> Camera: ',$camera,'</li>';
                        }
                        if($lens) {
                            echo'<li><img src="graphics/lens.png" width="15" /> Lens: ',$lens,'</li>';
                        }
                        if($focallength) {
                            echo'<li><img src="graphics/focal-length.png" width="15" /> Focal Length: ',$focallength,'</li>';
                        }
                        if($aperture) {
                            echo'<li><img src="graphics/aperature.png" width="15" /> Aperture: ',$aperture,'</li>';
                        }
                        if($shutterspeed) {
                            echo'<li><img src="graphics/shutter-speed.png" width="15" /> Shutter Speed: ',$shutterspeed,'</li>';
                        }   
                        echo'
                        </ul>
                    <!--About Photo-->';
                    if($about) {
                    echo'<div style="font-size:14px;font-weight:700;padding:10px 0 0 20px;">Behind the lens</div>
                        <div class="newsAbout">
                           ',$about,'
                        </div>';
                    }
                    if($tag1 || $tag2 || $tag3 || $tag4) {
                    echo'<div class="newsTagBox">
                        <ul class="tags">';
                            if($tag1) {
                                echo'<li><a href="#">',$tag1,'</a></li>';
                            }
                            if($tag2) {
                                echo'<li><a href="#">',$tag2,'</a></li>';
                            }
                            if($tag3) {
                                echo'<li><a href="#">',$tag3,'</a></li>';
                            }
                            if($tag4) {
                                echo'<li><a href="#">',$tag4,'</a></li>';
                            }
                            echo'
                        </ul>
                    </div>';
                    }
                    
                    echo'
                </div>
                <!--Comment Box-->
                    <div class="postCommentBox">
                         <form action="#" id="commentform',$id,'" method="post" style="margin-top:5px;padding-bottom:5px;"> 
                        <img style="float:left;" src="https://photorankr.com/',$sessionpic,'" height="30" width="30"  />
                        <textarea id="commentBox',$id,'" style="resize:none;margin-left:5px;width:395px;height:20px;" placeholder="Leave feedback for ',$firstname,'&#8230;"></textarea>
                        <div id="button_block',$id,'">
                         <input type="submit" id="button',$id,'" class="btn btn-success" value=" Comment "/>
                        </div>
                        </form>
                    </div>
                    
                    <!--AJAX COMMENTS-->
                        <ol id="update',$id,'" class="timeline">
                        </ol>
                    
                    <!--Previous Comments-->
                    <div id="hiddenComments',$id,'" class="previousComments">
                        <ul class="indPrevComment">';

                             for($iii = 0; $iii < $numcomments; $iii++) {
                                $prevcomment = mysql_result($grabcomments,$iii,'comment');
                                $commentid = mysql_result($grabcomments,$iii,'id');
                                $commenttime = mysql_result($grabcomments,$iii,'time');
                                $commenttime = converttime($commenttime);
                                $commenteremail = mysql_result($grabcomments,$iii,'commenter');
                                $commenterinfo = mysql_query("SELECT user_id,firstname,lastname,profilepic,reputation FROM userinfo WHERE emailaddress = '$commenteremail'");
                                $commentername = mysql_result($commenterinfo,0,'firstname') ." ". mysql_result($commenterinfo,0,'lastname');
                                $commenterid = mysql_result($commenterinfo,0,'user_id');
                                $commenterpic = mysql_result($commenterinfo,0,'profilepic');            
                                $commenterrep = number_format(mysql_result($commenterinfo,0,'reputation'),2);

                                echo'<li style="overflow:hidden;"> 
                                        <div style="width:35px;float:left;"><img src="https://photorankr.com/',$commenterpic,'" height="35" width="35" /></div>
                                        <div style="width:420px;float:left;" id="commenterName" style="float:left;"><a href="viewprofile.php?u=',$commenterid,'">',$commentername,'</a>
                                        <div style="float:right">',$commenttime,'</div>
                                        <div id="commentText">
                                            ',$prevcomment,'
                                        </div>
                                        </div>
                                     
                                     </li>';
                        
                            }
                            
                            echo'
                        </ul>
                    </div>
         <!--End Content Box-->
         </div>
    </div>
</div>';
        
       } //end for loop

}

//AJAX STATUS HERE
echo'<!--AJAX COMMENTS-->
<div class="float:left;"> 
    <ol id="updateStatus" class="timeline">
    </ol>
</div>';

$followresult = mysql_query("SELECT following,groups FROM userinfo WHERE emailaddress = '$email'");   
$followlist = mysql_result($followresult, 0, "following");
$groupslist = mysql_result($followresult, 0, "groups");
$groupslist = substr($groupslist,0,-1);
$newsfeedquery = "SELECT * FROM newsfeed WHERE (owner IN ($followlist) OR emailaddress IN ($followlist) OR group_id IN ($groupslist)) AND emailaddress NOT IN ('$email') AND type NOT IN ('message','reply') ORDER BY id DESC LIMIT 0,20";
$newsfeedresult = mysql_query($newsfeedquery);
$numresults = mysql_num_rows($newsfeedresult);
               
for($iii=0; $iii <= 19 && $iii < $numresults; $iii++) {
    
    $newsrow = mysql_fetch_array($newsfeedresult);
    $newsid = $newsrow['id'];
    $pos = strpos($idlist,$id);
    $newsemail = $newsrow['emailaddress'];    
    $photoowner = $newsrow['owner'];
    $time = $newsrow['time'];
    $time = converttime($time);
    $emailfollowing = $newsrow['following'];
    $type = $newsrow['type'];
    $source = $newsrow['source'];
    $firstname = $newsrow['firstname'];
    $lastname = $newsrow['lastname'];
    $idcomment = $newsrow['imageid'];
    $status = $newsrow['status'];
    $statusid = $newsrow['status_id'];
    $statusemail = $newsrow['status_poster'];
    $groupid = $newsrow['group_id'];
    
if($view == '') {

    if ($type == "photo") {
	$image = $newsrow['source'];
    $imagenew=str_replace("userphotos/","userphotos/medthumbs/", $image);
	$caption = $newsrow['caption'];
    $owner = $newsrow['emailaddress'];
    $ownersquery = "SELECT * FROM userinfo WHERE emailaddress = '$owner'";
    $ownerresult = mysql_query($ownersquery); 
    $ownerrow = mysql_fetch_array($ownerresult);
    $ownerfirst = $ownerrow['firstname'];
    $ownerlast = $ownerrow['lastname'];
    $ownerid = $ownerrow['user_id'];
    $ownerprofilepic = $ownerrow['profilepic'];
    $ownerfull = $ownerfirst . " " . $ownerlast;
    $ownerfull = ucwords($ownerfull);
  	list($width, $height) = getimagesize($image);
	$imgratio = $height / $width;
	$height = $imgratio * $maxwidth;
    $phrase = "<a href='viewprofile.php?u=" . $ownerid . "'>" . $ownerfull . "</a> uploaded a photo";
    
    $imageinfo = mysql_query("SELECT * FROM photos WHERE source = '$image'");
    $views = mysql_result($imageinfo,0,'views');
    $points = mysql_result($imageinfo,0,'points');
    $votes = mysql_result($imageinfo,0,'votes');
    $faves = mysql_result($imageinfo,0,'faves');
    $about = mysql_result($imageinfo,0,'about');
    $camera = mysql_result($imageinfo,0,'camera');
    $id = mysql_result($imageinfo,0,'id');
    $lens = mysql_result($imageinfo,0,'lens');
    $filter = mysql_result($imageinfo,0,'filter');
    $aperture = mysql_result($imageinfo,0,'aperture');
    $shutterspeed = mysql_result($imageinfo,0,'shutterspeed');
    $rank = ($points / $votes);
    $rank = number_format($rank,2);
    $tag1 = mysql_result($imageinfo,0,'tag1');
    $tag2 = mysql_result($imageinfo,0,'tag2');
    $tag3 = mysql_result($imageinfo,0,'tag3');
    $tag4 = mysql_result($imageinfo,0,'tag4');
    
    list($width, $height) = getimagesize($image);
    $width = ($width / 3.2);
    $height = ($height / 3.2);
    
    ?>
    
    <script type="text/javascript">
        
        //Comment jQuery Script
        $(function() {
        $("#commentform<?php echo $newsid; ?>").submit(function() 
        {
        var firstname = '<?php echo $sessionfirst; ?>';
        var lastname = '<?php echo $sessionlast; ?>';
        var email = '<?php echo $email; ?>';
        var userpic = '<?php echo $sessionpic; ?>';
        var viewerid = '<?php echo $sessionid; ?>';
        var imageid = '<? echo $id; ?>';
        var comment = $("#commentBox<?php echo $newsid; ?>").val();
        var dataString = 'firstname='+ firstname + '&lastname=' + lastname + '&email=' + email + '&comment=' + comment + '&userpic=' + userpic + '&viewerid=' + viewerid + '&imageid=' + imageid;
        $("#flash").show();
        $("#flash").fadeIn(400).html();
        $.ajax({
        type: "POST",
        url: "newsfeedcomment.php",
        data: dataString,
        cache: false,
        success: function(html){
        $("ol#update<?php echo $newsid; ?>").append(html);
        $("ol#update li:last").fadeIn("slow");
        $("#flash").hide();
        }
        });
        return false;
        }); });
        
        //Display textarea
        $(function() 
        {
        $("#comment<?php echo $newsid; ?>").click(function()
        {
        $("#commentBox<?php echo $newsid; ?>").animate({"height": "85px",}, "fast" );
        $("#button_block<?php echo $newsid; ?>").slideDown("fast");
        jQuery("#commentform<?php echo $newsid; ?>").toggle();
        jQuery("#hiddenComments<?php echo $newsid; ?>").toggle();
        return false;
        });        
        });
 
    jQuery(document).ready(function(){
        jQuery("#showStats<?php echo $newsid; ?>").live("click", function(event) {        
            jQuery(".hiddenStats<?php echo $newsid; ?>").toggle();
        });
    });
    </script>
    
     <style type="text/css">
        .hiddenStats<?php echo $newsid; ?> {
            display:none;
        }
        .hiddenStats<?php echo $newsid; ?> ul {
            padding:15px;
            padding-top:60px;
            font-size:13px;
        }
        .hiddenStats<?php echo $newsid; ?> li {
            display:inline;
            padding:4px;
        }
        #hiddenComments<?php echo $newsid; ?> {
                display:none;
        }
        #commentform<?php echo $newsid; ?> {
            display:none;
            padding:20px 0;
        }
        #commentOption<?php echo $newsid; ?> {
            font-size:13px;
            padding-top:10px;
            font-weight:700;
            cursor:pointer;
        }
        #button_block<?php echo $newsid; ?> {
            display:none;
        }
        #button<?php echo $newsid; ?> {
            background-color:#33C33C;
            color:#ffffff;
            font-size:13px;
            font-weight:bold;
            padding:3px;
            margin-left:40px;
        }
    </style>
    
    <?php

    echo'<div class="grid_16 fPic" id="',$newsid,'">
         <!--Profile Picture-->
            <div class="newsBlock">
                <ul>
                    <li><img id="newsProfilePic" src="https://photorankr.com/',$ownerprofilepic,'" />
                    <li id="comment',$newsid,'"><img src="graphics/comment_1.png"></li>
                    <li><img src="graphics/heart.png"></li>
                     <a href="fullsizemarket.php?imageid=',$id,'"><li><img src="graphics/tag.png"></li></a>
                </ul>
            </div>
         
         <!--Content Box-->
         <div class="newsContainer">
            <div class="newsTriangle"></div>
                <div class="newsItem">
                    <!--Top Controls-->
                    <div class="newsControls">
                        <div class="newsName">',$phrase,'</div>
                        <div class="newsTools">
                            <span id="time">',$time,'</span>
                        </div>
                    </div>
                    <!--Content-->
                    <div class="newsContent">
                        <a href="fullsize.php?imageid=',$id,'"><img style="min-width:480px;" src="https://photorankr.com/',$imagenew,'" width="',$width,'px" height="',$height,'px" /></a>
                    </div>
                    <!---Stats--->
                    <div class="newsStats">
                        <span style="font-size:18px;font-weight:500;">',$rank,'</span>
                        <span style="font-size:18px;font-weight:300;">',$caption,'
                        <div style="display:inline;width:240px;float:right;margin-right:50px;text-align:right;font-size:15px;">';
                        if($tag1) {
                            echo'#',$tag1,' ';
                        }
                        if($tag2) {
                            echo'#',$tag2,' ';
                        }
                        if($tag3) {
                            echo'#',$tag3,' ';
                        }                    
                        echo'
                            <a href="#"><img id="showStats',$newsid,'" style="width:20px;margin-top:-3px;padding-left:8px;" src="graphics/stats 4.png" /></a>
                        </div>
                    </div>
                    
                    <div class="hiddenStats',$newsid,'">
                        <ul>
                            <li><img src="graphics/view.png" width="15" /> Views: ',$views,'</li>';
                        
                        if($camera) {
                            echo'<li><img src="graphics/camera.png" width="15" /> Camera: ',$camera,'</li>';
                        }
                        if($lens) {
                            echo'<li><img src="graphics/lens.png" width="15" /> Lens: ',$lens,'</li>';
                        }
                        if($focallength) {
                            echo'<li><img src="graphics/focal-length.png" width="15" /> Focal Length: ',$focallength,'</li>';
                        }
                        if($aperture) {
                            echo'<li><img src="graphics/aperature.png" width="15" /> Aperture: ',$aperture,'</li>';
                        }
                        if($shutterspeed) {
                            echo'<li><img src="graphics/shutter-speed.png" width="15" /> Shutter Speed: ',$shutterspeed,'</li>';
                        }   
                        echo'
                        </ul>
                    <!--About Photo-->';
                    if($about) {
                    echo'<div style="font-size:14px;font-weight:700;padding:10px 0 0 20px;">Behind the lens</div>
                        <div class="newsAbout">
                           ',$about,'
                        </div>';
                    }
                    if($tag1 || $tag2 || $tag3 || $tag4) {
                    echo'<div class="newsTagBox">
                        <ul class="tags">';
                            if($tag1) {
                                echo'<li><a href="#">',$tag1,'</a></li>';
                            }
                            if($tag2) {
                                echo'<li><a href="#">',$tag2,'</a></li>';
                            }
                            if($tag3) {
                                echo'<li><a href="#">',$tag3,'</a></li>';
                            }
                            if($tag4) {
                                echo'<li><a href="#">',$tag4,'</a></li>';
                            }
                            echo'
                        </ul>
                    </div>';
                    }
                    
                    echo'
                </div>
                <!--Comment Box-->
                    <div class="postCommentBox">
                         <form action="#" id="commentform',$newsid,'" method="post" style="margin-top:5px;padding-bottom:5px;"> 
                        <img style="float:left;" src="https://photorankr.com/',$sessionpic,'" height="30" width="30"  />
                        <textarea id="commentBox',$newsid,'" style="resize:none;margin-left:5px;width:395px;height:20px;" placeholder="Leave feedback for ',$firstname,'&#8230;"></textarea>
                        <div id="button_block',$newsid,'">
                         <input type="submit" id="button',$newsid,'" class="btn btn-success" value=" Comment "/>
                        </div>
                        </form>
                    </div>
                    
                    <!--AJAX COMMENTS-->
                        <ol id="update',$newsid,'" class="timeline">
                        </ol>
                    
                    <!--Previous Comments-->
                    <div id="hiddenComments',$newsid,'" class="previousComments">
                        <ul class="indPrevComment">';

                             for($iii = 0; $iii < $numcomments; $iii++) {
                                $prevcomment = mysql_result($grabcomments,$iii,'comment');
                                $commentid = mysql_result($grabcomments,$iii,'id');
                                $commenttime = mysql_result($grabcomments,$iii,'time');
                                $commenttime = converttime($commenttime);
                                $commenteremail = mysql_result($grabcomments,$iii,'commenter');
                                $commenterinfo = mysql_query("SELECT user_id,firstname,lastname,profilepic,reputation FROM userinfo WHERE emailaddress = '$commenteremail'");
                                $commentername = mysql_result($commenterinfo,0,'firstname') ." ". mysql_result($commenterinfo,0,'lastname');
                                $commenterid = mysql_result($commenterinfo,0,'user_id');
                                $commenterpic = mysql_result($commenterinfo,0,'profilepic');            
                                $commenterrep = number_format(mysql_result($commenterinfo,0,'reputation'),2);

                                echo'<li style="overflow:hidden;"> 
                                        <div style="width:35px;float:left;"><img src="https://photorankr.com/',$commenterpic,'" height="35" width="35" /></div>
                                        <div style="width:420px;float:left;" id="commenterName" style="float:left;"><a href="viewprofile.php?u=',$commenterid,'">',$commentername,'</a>
                                        <div style="float:right">',$commenttime,'</div>
                                        <div id="commentText">
                                            ',$prevcomment,'
                                        </div>
                                        </div>
                                     
                                     </li>';
                        
                            }
                            
                            echo'
                        </ul>
                    </div>
         <!--End Content Box-->
         </div>
    </div>
</div>';
    } //end type upload
    
    if ($type == "fave") {
    $owner = $newsrow['owner'];
    $ownersquery = "SELECT * FROM userinfo WHERE emailaddress = '$owner'";
    $ownerresult = mysql_query($ownersquery); 
    $ownerrow = mysql_fetch_array($ownerresult);
    $ownerfirst = $ownerrow['firstname'];
    $ownerlast = $ownerrow['lastname'];
    $ownerid = $ownerrow['user_id'];
    $ownerfull = "<a href='viewprofile.php?u=" . $ownerid . "'>" . $ownerfirst . " " . $ownerlast . "</a>";
    $ownerfull = ucwords($ownerfull);
    $firstname = $newsrow['firstname'];
    $commenteremail = $newsrow['emailaddress'];
    $commentersquery = mysql_query("SELECT user_id,profilepic FROM userinfo WHERE emailaddress = '$commenteremail'");
    $commenterpic = mysql_result($commentersquery,0,'profilepic');
    $commenterid = mysql_result($commentersquery,0,'user_id');
    $firstname = ucwords($firstname);
    $lastname = $newsrow['lastname'];
    $lastname = ucwords($lastname);
    $image = $newsrow['source'];
    $imagenew=str_replace("userphotos/","userphotos/medthumbs/", $image);
	$caption = $newsrow['caption'];
	list($width, $height) = getimagesize($image);
	$imgratio = $height / $width;
	$height = $imgratio * $maxwidth;
    $fullname = "<a href='viewprofile.php?u=" . $commenterid . "'>" . $firstname . " " . $lastname . "</a>";
    if($type == "fave") {
        $phrase = $fullname . " favorited " . 'a photo' . " by " . $ownerfull;
    }
    elseif($type == "discoverfave") {
            $phrase = $fullname . " discovered " . 'a photo' . " by " . $ownerfull;
    }
    $imageinfo = mysql_query("SELECT * FROM photos WHERE source = '$image'");
    $views = mysql_result($imageinfo,0,'views');
    $points = mysql_result($imageinfo,0,'points');
    $votes = mysql_result($imageinfo,0,'votes');
    $faves = mysql_result($imageinfo,0,'faves');
    $about = mysql_result($imageinfo,0,'about');
    $camera = mysql_result($imageinfo,0,'camera');
    $id = mysql_result($imageinfo,0,'id');
    $lens = mysql_result($imageinfo,0,'lens');
    $filter = mysql_result($imageinfo,0,'filter');
    $aperture = mysql_result($imageinfo,0,'aperture');
    $shutterspeed = mysql_result($imageinfo,0,'shutterspeed');
    $rank = ($points / $votes);
    $rank = number_format($rank,2);
    $tag1 = mysql_result($imageinfo,0,'tag1');
    $tag2 = mysql_result($imageinfo,0,'tag2');
    $tag3 = mysql_result($imageinfo,0,'tag3');
    $tag4 = mysql_result($imageinfo,0,'tag4');
    
    list($width, $height) = getimagesize($image);
    $width = ($width / 3.2);
    $height = ($height / 3.2);
    
    ?>
    
    <script type="text/javascript">
        
        //Comment jQuery Script
        $(function() {
        $("#commentform<?php echo $newsid; ?>").submit(function() 
        {
        var firstname = '<?php echo $sessionfirst; ?>';
        var lastname = '<?php echo $sessionlast; ?>';
        var email = '<?php echo $email; ?>';
        var userpic = '<?php echo $sessionpic; ?>';
        var viewerid = '<?php echo $sessionid; ?>';
        var imageid = '<? echo $id; ?>';
        var comment = $("#commentBox<?php echo $newsid; ?>").val();
        var dataString = 'firstname='+ firstname + '&lastname=' + lastname + '&email=' + email + '&comment=' + comment + '&userpic=' + userpic + '&viewerid=' + viewerid + '&imageid=' + imageid;
        $("#flash").show();
        $("#flash").fadeIn(400).html();
        $.ajax({
        type: "POST",
        url: "newsfeedcomment.php",
        data: dataString,
        cache: false,
        success: function(html){
        $("ol#update<?php echo $newsid; ?>").append(html);
        $("ol#update li:last").fadeIn("slow");
        $("#flash").hide();
        }
        });
        return false;
        }); });
        
        //Display textarea
        $(function() 
        {
        $("#comment<?php echo $newsid; ?>").click(function()
        {
        $("#commentBox<?php echo $newsid; ?>").animate({"height": "85px",}, "fast" );
        $("#button_block<?php echo $newsid; ?>").slideDown("fast");
        jQuery("#commentform<?php echo $newsid; ?>").toggle();
        jQuery("#hiddenComments<?php echo $newsid; ?>").toggle();
        return false;
        });        
        });
 
    jQuery(document).ready(function(){
        jQuery("#showStats<?php echo $newsid; ?>").live("click", function(event) {        
            jQuery(".hiddenStats<?php echo $newsid; ?>").toggle();
        });
    });
    </script>
    
     <style type="text/css">
        .hiddenStats<?php echo $newsid; ?> {
            display:none;
        }
        .hiddenStats<?php echo $newsid; ?> ul {
            padding:15px;
            padding-top:60px;
            font-size:13px;
        }
        .hiddenStats<?php echo $newsid; ?> li {
            display:inline;
            padding:4px;
        }
        #hiddenComments<?php echo $newsid; ?> {
                display:none;
        }
        #commentform<?php echo $newsid; ?> {
            display:none;
            padding:20px 0;
        }
        #commentOption<?php echo $newsid; ?> {
            font-size:13px;
            padding-top:10px;
            font-weight:700;
            cursor:pointer;
        }
        #button_block<?php echo $newsid; ?> {
            display:none;
        }
        #button<?php echo $newsid; ?> {
            background-color:#33C33C;
            color:#ffffff;
            font-size:13px;
            font-weight:bold;
            padding:3px;
            margin-left:40px;
        }
    </style>
    
    <?php
    
    //All Previous Comments
    $grabcomments = mysql_query("SELECT * FROM comments WHERE imageid = '$id' ORDER BY id DESC");
    $numcomments = mysql_num_rows($grabcomments);


    echo'<div class="grid_16 fPic" id="',$newsid,'">
         <!--Profile Picture-->
            <div class="newsBlock">
                <ul>
                    <li><img id="newsProfilePic" src="https://photorankr.com/',$commenterpic,'" />
                    <li id="comment',$newsid,'"><img src="graphics/comment_1.png"></li>
                    <li><img src="graphics/heart.png"></li>
                     <a href="fullsizemarket.php?imageid=',$id,'"><li><img src="graphics/tag.png"></li></a>
                </ul>
            </div>
         
         <!--Content Box-->
         <div class="newsContainer">
            <div class="newsTriangle"></div>
                <div class="newsItem">
                    <!--Top Controls-->
                    <div class="newsControls">
                        <div class="newsName">',$phrase,'</div>
                        <div class="newsTools">
                            <span id="time">',$time,'</span>
                        </div>
                    </div>
                    <!--Content-->
                    <div class="newsContent">
                        <a href="fullsize.php?imageid=',$id,'"><img style="min-width:480px;" src="https://photorankr.com/',$imagenew,'" width="',$width,'px" height="',$height,'px" /></a>
                    </div>
                    <!---Stats--->
                    <div class="newsStats">
                        <span style="font-size:18px;font-weight:500;">',$rank,'</span>
                        <span style="font-size:18px;font-weight:300;">',$caption,'
                        <div style="display:inline;width:240px;float:right;margin-right:50px;text-align:right;font-size:15px;">';
                        if($tag1) {
                            echo'#',$tag1,' ';
                        }
                        if($tag2) {
                            echo'#',$tag2,' ';
                        }
                        if($tag3) {
                            echo'#',$tag3,' ';
                        }                    
                        echo'
                            <a href="#"><img id="showStats',$newsid,'" style="width:20px;margin-top:-3px;padding-left:8px;" src="graphics/stats 4.png" /></a>
                        </div>
                    </div>
                    
                    <div class="hiddenStats',$newsid,'">
                        <ul>
                            <li><img src="graphics/view.png" width="15" /> Views: ',$views,'</li>';
                        
                        if($camera) {
                            echo'<li><img src="graphics/camera.png" width="15" /> Camera: ',$camera,'</li>';
                        }
                        if($lens) {
                            echo'<li><img src="graphics/lens.png" width="15" /> Lens: ',$lens,'</li>';
                        }
                        if($focallength) {
                            echo'<li><img src="graphics/focal-length.png" width="15" /> Focal Length: ',$focallength,'</li>';
                        }
                        if($aperture) {
                            echo'<li><img src="graphics/aperature.png" width="15" /> Aperture: ',$aperture,'</li>';
                        }
                        if($shutterspeed) {
                            echo'<li><img src="graphics/shutter-speed.png" width="15" /> Shutter Speed: ',$shutterspeed,'</li>';
                        }   
                        echo'
                        </ul>
                    <!--About Photo-->';
                    if($about) {
                    echo'<div style="font-size:14px;font-weight:700;padding:10px 0 0 20px;">Behind the lens</div>
                        <div class="newsAbout">
                           ',$about,'
                        </div>';
                    }
                    if($tag1 || $tag2 || $tag3 || $tag4) {
                    echo'<div class="newsTagBox">
                        <ul class="tags">';
                            if($tag1) {
                                echo'<li><a href="#">',$tag1,'</a></li>';
                            }
                            if($tag2) {
                                echo'<li><a href="#">',$tag2,'</a></li>';
                            }
                            if($tag3) {
                                echo'<li><a href="#">',$tag3,'</a></li>';
                            }
                            if($tag4) {
                                echo'<li><a href="#">',$tag4,'</a></li>';
                            }
                            echo'
                        </ul>
                    </div>';
                    }
                    
                    echo'
                </div>
                <!--Comment Box-->
                    <div class="postCommentBox">
                         <form action="#" id="commentform',$newsid,'" method="post" style="margin-top:5px;padding-bottom:5px;"> 
                        <img style="float:left;" src="https://photorankr.com/',$sessionpic,'" height="30" width="30"  />
                        <textarea id="commentBox',$newsid,'" style="resize:none;margin-left:5px;width:395px;height:20px;" placeholder="Leave feedback for ',$ownerfirst,'&#8230;"></textarea>
                        <div id="button_block',$newsid,'">
                         <input type="submit" id="button',$newsid,'" class="btn btn-success" value=" Comment "/>
                        </div>
                        </form>
                    </div>
                    
                    <!--AJAX COMMENTS-->
                        <ol id="update',$newsid,'" class="timeline">
                        </ol>
                    
                    <!--Previous Comments-->
                    <div id="hiddenComments',$newsid,'" class="previousComments">
                        <ul class="indPrevComment">';

                             for($iii = 0; $iii < $numcomments; $iii++) {
                                $prevcomment = mysql_result($grabcomments,$iii,'comment');
                                $commentid = mysql_result($grabcomments,$iii,'id');
                                $commenttime = mysql_result($grabcomments,$iii,'time');
                                $commenttime = converttime($commenttime);
                                $commenteremail = mysql_result($grabcomments,$iii,'commenter');
                                $commenterinfo = mysql_query("SELECT user_id,firstname,lastname,profilepic,reputation FROM userinfo WHERE emailaddress = '$commenteremail'");
                                $commentername = mysql_result($commenterinfo,0,'firstname') ." ". mysql_result($commenterinfo,0,'lastname');
                                $commenterid = mysql_result($commenterinfo,0,'user_id');
                                $commenterpic = mysql_result($commenterinfo,0,'profilepic');            
                                $commenterrep = number_format(mysql_result($commenterinfo,0,'reputation'),2);

                                echo'<li style="overflow:hidden;"> 
                                        <div style="width:35px;float:left;"><img src="https://photorankr.com/',$commenterpic,'" height="35" width="35" /></div>
                                        <div style="width:420px;float:left;" id="commenterName" style="float:left;"><a href="viewprofile.php?u=',$commenterid,'">',$commentername,'</a>
                                        <div style="float:right">',$commenttime,'</div>
                                        <div id="commentText">
                                            ',$prevcomment,'
                                        </div>
                                        </div>
                                     
                                     </li>';
                        
                            }
                            
                            echo'
                        </ul>
                    </div>
         <!--End Content Box-->
         </div>
    </div>
</div>';
    } //end type favorite
    
    elseif ($type == "comment") {
		$owner = $newsrow['owner'];
		$ownersquery = "SELECT * FROM userinfo WHERE emailaddress = '$owner'";
		$ownerresult = mysql_query($ownersquery); 
		$ownerrow = mysql_fetch_array($ownerresult);
		$ownerfirst = $ownerrow['firstname'];
		$ownerlast = $ownerrow['lastname'];
		$ownerid = $ownerrow['user_id'];
		$ownerfull = "<a href='viewprofile.php?u=" . $ownerid . "'>" . $ownerfirst . " " . $ownerlast . "</a>";
		$ownerfull = ucwords($ownerfull);
		$firstname = $newsrow['firstname'];
		$commenteremail = $newsrow['emailaddress'];
		$commentersquery = mysql_query("SELECT user_id,profilepic FROM userinfo WHERE emailaddress = '$commenteremail'");
		$commenterpic = mysql_result($commentersquery,0,'profilepic');
		$commenterid = mysql_result($commentersquery,0,'user_id');
		$firstname = ucwords($firstname);
		$lastname = $newsrow['lastname'];
		$lastname = ucwords($lastname);
		$image = $newsrow['source'];
		$id = $newsrow['id'];
		$getimageid = mysql_query("SELECT id FROM photos WHERE source = '$image'");
		$oldimageid = mysql_result($getimageid,0,'id');
	   
		$imageinfo = mysql_query("SELECT * FROM photos WHERE (id = '$image' OR id = '$oldimageid')");
		$views = mysql_result($imageinfo,0,'views');
		$points = mysql_result($imageinfo,0,'points');
		$about = mysql_result($imageinfo,0,'about');
		$imageID = mysql_result($imageinfo,0,'id');
		$source = mysql_result($imageinfo,0,'source');
		$votes = mysql_result($imageinfo,0,'votes');
		$rank = ($points / $votes);
		$rank = number_format($rank,2);
        
        ?>
        
        <script type="text/javascript">
        //Comment jQuery Script
        $(function() {
        $("#commentform<?php echo $id; ?>").submit(function() 
        {
        var firstname = '<?php echo $sessionfirst; ?>';
        var lastname = '<?php echo $sessionlast; ?>';
        var email = '<?php echo $email; ?>';
        var userpic = '<?php echo $sessionpic; ?>';
        var viewerid = '<?php echo $sessionid; ?>';
        var imageid = '<? echo $id; ?>';
        var comment = $("#commentBox<?php echo $id; ?>").val();
        var dataString = 'firstname='+ firstname + '&lastname=' + lastname + '&email=' + email + '&comment=' + comment + '&userpic=' + userpic + '&viewerid=' + viewerid + '&imageid=' + imageid;
        $("#flash").show();
        $("#flash").fadeIn(400).html();
        $.ajax({
        type: "POST",
        url: "newsfeedcomment.php",
        data: dataString,
        cache: false,
        success: function(html){
        $("ol#update<?php echo $id; ?>").append(html);
        $("ol#update li:last").fadeIn("slow");
        $("#flash").hide();
        }
        });
        return false;
        }); });
 
        
        //Display textarea
        $(function() 
        {
        $("#comment<?php echo $id; ?>").click(function()
        {
        $("#commentBox<?php echo $id; ?>").animate({"height": "85px",}, "fast" );
        $("#button_block<?php echo $id; ?>").slideDown("fast");
        jQuery("#commentform<?php echo $id; ?>").toggle();
        jQuery("#hiddenComments<?php echo $id; ?>").toggle();
        return false;
        });        
        });
        </script>
        
        <style type="text/css">
            #hiddenComments<?php echo $id; ?> {
                display:none;
            }
            #commentform<?php echo $id; ?> {
            	display:none;
                padding:20px 0;
            }
            #commentOption<?php echo $id; ?> {
                font-size:13px;
                padding-top:10px;
                font-weight:700;
                cursor:pointer;
            }
            #button_block<?php echo $id; ?> {
                display:none;
            }
            #button<?php echo $id; ?> {
                background-color:#33C33C;
                color:#ffffff;
                font-size:13px;
                font-weight:bold;
                padding:3px;
                margin-left:40px;
            }
        </style>
        
        <?php
        
        //Get user's comment
        $lastcommentquery = mysql_query("SELECT comment FROM comments WHERE id = '$idcomment' LIMIT 0,1");
        $lastcomment = mysql_result($lastcommentquery,0,'comment');
        
         //All Previous Comments
        $grabcomments = mysql_query("SELECT * FROM comments WHERE imageid = '$imageID' ORDER BY id DESC");
        $numcomments = mysql_num_rows($grabcomments);
		
		$imagenew=str_replace("userphotos/","userphotos/medthumbs/", $source);
		$fullname = "<a href='viewprofile.php?u=" . $commenterid . "'>" . $firstname . " " . $lastname ."</a>";
		list($width, $height) = getimagesize($image);
		$imgratio = $height / $width;
		$height = $imgratio * $maxwidth;
		$phrase = $fullname . " commented on " . $ownerfull . "'s photo";
		
		list($width, $height) = getimagesize($source);
		$width = ($width / 2.5);
		$height = ($height / 2.5);
        
        echo'<div class="grid_16 fPic" id="',$newsid,'">
         <!--Profile Picture-->
            <div class="newsBlock">
                <ul>
                    <li><img id="newsProfilePic" src="https://photorankr.com/',$commenterpic,'" />
                    <li id="comment',$id,'"><img src="graphics/comment_1.png"></li>
                    <li><img src="graphics/heart.png"></li>
                     <a href="fullsizemarket.php?imageid=',$imageID,'"><li><img src="graphics/tag.png"></li></a>
                </ul>
            </div>
                     
         <!--Content Box-->
         <div class="newsContainer">
            <div class="newsTriangle"></div>
                <div class="newsItem">
                    <!--Top Controls-->
                    <div class="newsControls">
                        <div class="newsName">',$fullname,' > ',$ownerfull,'</div>
                        <div class="newsTools">
                            <span id="time">',$time,'</span>
                        </div>
                    </div>
                    <!--Content-->
                    <div class="commentPhoto">
                         <a href="fullsize.php?imageid=',$imageID,'"><img style="max-height:500px;" src="https://photorankr.com/',$source,'" /></a>
                    </div>
                    <div class="commentBox">
                        <blockquote>
                            <p>',$lastcomment,'</p>
                        </blockquote>
                    </div>
                    <!--Comment Box-->
                    <div class="postCommentBox">
                         <form action="#" id="commentform',$id,'" method="post" style="margin-top:5px;padding-bottom:5px;"> 
                        <img style="float:left;" src="https://photorankr.com/',$sessionpic,'" height="30" width="30"  />
                        <textarea id="commentBox',$id,'" style="resize:none;margin-left:5px;width:395px;height:20px;" placeholder="Leave feedback for ',$firstname,'&#8230;"></textarea>
                        <div id="button_block',$id,'">
                         <input type="submit" id="button',$id,'" class="btn btn-success" value=" Comment "/>
                        </div>
                        </form>
                    </div>
                    
                    <!--AJAX COMMENTS-->
                        <ol id="update',$id,'" class="timeline">
                        </ol>
                    
                    <!--Previous Comments-->
                    <div id="hiddenComments',$id,'" class="previousComments">
                        <ul class="indPrevComment">';

                             for($iii = 0; $iii < $numcomments; $iii++) {
                                $prevcomment = mysql_result($grabcomments,$iii,'comment');
                                $commentid = mysql_result($grabcomments,$iii,'id');
                                $commenttime = mysql_result($grabcomments,$iii,'time');
                                $commenttime = converttime($commenttime);
                                $commenteremail = mysql_result($grabcomments,$iii,'commenter');
                                $commenterinfo = mysql_query("SELECT user_id,firstname,lastname,profilepic,reputation FROM userinfo WHERE emailaddress = '$commenteremail'");
                                $commentername = mysql_result($commenterinfo,0,'firstname') ." ". mysql_result($commenterinfo,0,'lastname');
                                $commenterid = mysql_result($commenterinfo,0,'user_id');
                                $commenterpic = mysql_result($commenterinfo,0,'profilepic');            
                                $commenterrep = number_format(mysql_result($commenterinfo,0,'reputation'),2);

                                echo'<li style="overflow:hidden;"> 
                                        <div style="width:35px;float:left;"><img src="https://photorankr.com/',$commenterpic,'" height="35" width="35" /></div>
                                        <div style="width:420px;float:left;" id="commenterName" style="float:left;"><a href="viewprofile.php?u=',$commenterid,'">',$commentername,'</a>
                                        <div style="float:right">',$commenttime,'</div>
                                        <div id="commentText">
                                            ',$prevcomment,'
                                        </div>
                                        </div>
                                     
                                     </li>';
                        
                            }
                            
                            echo'
                        </ul>
                    </div>
                    
                </div>
         <!--End Content Box-->
         </div>
         
         </div>';
        
    } //end type == 'comments'
    
        
    elseif($type == 'follow') {
    
    $newaccount = "SELECT * FROM userinfo WHERE emailaddress = '$emailfollowing'";
		$accountresult = mysql_query($newaccount); 
		$accountrow = mysql_fetch_array($accountresult);
		$profilepic5 = $accountrow['profilepic'];
		$ownerid = $accountrow['user_id'];
		
		$numownerphotosquery = mysql_query("SELECT source FROM photos WHERE emailaddress = '$emailfollowing' ORDER by (points/votes) DESC LIMIT 4");
		$numownerphotos = mysql_num_rows($numownerphotosquery);
		$flowimage1 = mysql_result($numownerphotosquery,0,'source');
		$flowimage1=str_replace("userphotos/","userphotos/medthumbs/", $flowimage1);
		$flowimage2 = mysql_result($numownerphotosquery,1,'source');
		$flowimage2=str_replace("userphotos/","userphotos/medthumbs/", $flowimage2);
		$flowimage3 = mysql_result($numownerphotosquery,2,'source');
		$flowimage3=str_replace("userphotos/","userphotos/medthumbs/", $flowimage3);
		
		$followersquery="SELECT * FROM userinfo WHERE following LIKE '%$emailfollowing%'";
		$followersresult=mysql_query($followersquery);
		$numberfollowers = mysql_num_rows($followersresult);
		
		for($iii = 0; $iii < $numownerphotos; $iii++) {
			$points = mysql_result($numownerphotosquery, $iii, "points");
			$votes = mysql_result($numownerphotosquery, $iii, "votes");
			$totalfaves = mysql_result($numownerphotosquery, $iii, "faves");
			$portfoliopoints+=$points;
			$portfoliovotes+=$votes;
			$portfoliofaves+=$totalfaves;
		}
		
		if ($portfoliovotes > 0) {
			$portfolioranking=($portfoliopoints/$portfoliovotes);
			$portfolioranking=number_format($portfolioranking, 2, '.', '');    
		}
		else if ($portfoliovotes < 1) {
			$portfolioranking="N/A";
		}	

		$ownerfirst = $accountrow['firstname'];
		$commenteremail = $accountrow['emailaddress'];
		$commenteremail = $newsrow['emailaddress'];
		$commentersquery = mysql_query("SELECT user_id,profilepic FROM userinfo WHERE emailaddress = '$commenteremail'");
		$commenterpic = mysql_result($commentersquery,0,'profilepic');
		$commenterid = mysql_result($commentersquery,0,'user_id');
		$ownerlast = $accountrow['lastname'];
		$firstname = $newsrow['firstname'];
		$firstname = ucwords($firstname);
		$lastname = $newsrow['lastname'];
		$lastname = ucwords($lastname);
		$fullname = "<a href='viewprofile.php?u=" . $commenterid . "'>" . $firstname . " " . $lastname. "</a>";
		$owner = $newsrow['owner'];
		$ownerfull = "<a href='viewprofile.php?u=" . $ownerid . "'>" . $ownerfirst . " " . $ownerlast . "'s</a>";
		$owner = ucwords($ownerfull);
		if($profilepic5 == "") {
		$profilepic5 = "profilepics/default_profile.jpg";
		}
		$phrase = $fullname . ' is now following ' . $owner ." photography";
		
		list($width, $height) = getimagesize($profilepic5);
		$width = ($width / 3.5);
		$height = ($height / 3.5);

         echo'<div class="grid_16 fPic" id="',$newsid,'">
         <!--Profile Picture-->
            <div class="newsBlock">
                <ul>
                    <li><img id="newsProfilePic" src="https://photorankr.com/',$commenterpic,'" />
                </ul>
            </div>
                     
         <!--Content Box-->
         <div class="newsContainer">
            <div class="newsTriangle"></div>
                <div class="newsItem"s>
                    <!--Top Controls-->
                    <div class="newsControls">
                        <div class="newsName">',$phrase,'</div>
                        <br />
                        <div class="newsTools">
                            <span id="time">',$time,'</span>
                        </div>
                    </div>
                    <!--Content-->
                    <div id="followContent">';
                       if($numownerphotos > 2) {
                        echo'
                            <img style="border:1px solid white;" src="https://photorankr.com/',$flowimage1,'" />
                            <img style="border:1px solid white;" src="https://photorankr.com/',$flowimage2,'"  />
                            <img style="border:1px solid white;" src="https://photorankr.com/',$flowimage3,'" />';
                        }
                        else {
                            echo'<div style="text-align:center;font-size:14px;margin-top:40px;">',$ownerfirst,' just joined!</div>';
                        }
                       echo'
                    </div>
                </div>
         <!--End Content Box-->
         </div>
         
         </div>';
    
    } //end type == 'follow'
    
    elseif($type == 'status') {
        $ownersquery = "SELECT * FROM userinfo WHERE emailaddress = '$newsemail'";
        $ownerresult = mysql_query($ownersquery); 
        $ownerrow = mysql_fetch_array($ownerresult);
        $ownerfirst = $ownerrow['firstname'];
        $ownerlast = $ownerrow['lastname'];
        $ownerid = $ownerrow['user_id'];
        $ownerprofilepic = $ownerrow['profilepic'];
        
        echo'<div class="grid_16 fPic" id="',$newsid,'">
         <!--Profile Picture-->
            <div class="newsBlock">
                <ul>
                    <li><img id="newsProfilePic" src="https://photorankr.com/',$ownerprofilepic,'" />
                </ul>
            </div>
                     
         <!--Content Box-->
         <div class="newsContainer">
            <div class="newsTriangle"></div>
                <div class="newsItem">
                    <!--Top Controls-->
                    <div class="newsControls">
                        <div class="newsName"><a href="viewprofile.php?u=',$ownerid,'">',$firstname,' ',$lastname,'</a> posted an update</div>
                        <div class="newsTools">
                            <span id="time">',$time,'</span>
                        </div>
                    </div>
                    <!--Content-->
                    <div id="followContent">
                        ',$status,'
                    </div>
                </div>
         <!--End Content Box-->
         </div>
                  
         </div>';
    
    } //end type == 'status'
    
    elseif($type == 'create') {
        $ownersquery = "SELECT * FROM userinfo WHERE emailaddress = '$newsemail'";
        $ownerresult = mysql_query($ownersquery); 
        $ownerrow = mysql_fetch_array($ownerresult);
        $ownerfirst = $ownerrow['firstname'];
        $ownerlast = $ownerrow['lastname'];
        $ownerid = $ownerrow['user_id'];
        $ownerprofilepic = $ownerrow['profilepic'];
        //group info
        $groupinfoquery = mysql_query("SELECT * FROM groups WHERE id = $groupid");
        $about = mysql_result($groupinfoquery,0,'about');
        $groupname = mysql_result($groupinfoquery,0,'name');
        
        echo'<div class="grid_16 fPic" id="',$newsid,'">
         <!--Profile Picture-->
            <div class="newsBlock">
                <ul>
                    <li><img id="newsProfilePic" src="https://photorankr.com/',$ownerprofilepic,'" />
                </ul>
            </div>
                     
         <!--Content Box-->
         <div class="newsContainer">
            <div class="newsTriangle"></div>
                <div class="newsItem">
                    <!--Top Controls-->
                    <div class="newsControls">
                        <div class="newsName"><a href="viewprofile.php?u=',$ownerid,'">',$firstname,' ',$lastname,'</a> created a new group</div>
                        <div class="newsTools">
                            <span id="time">',$time,'</span>
                        </div>
                    </div>
                    <!--Content-->
                    <div id="followContent">
                        <a style="color:#333;" href="groups.php?id=',$groupid,'">
                            <header style="padding: 0px 20px;font-size:18px;font-weight:300;line-height:20px;"><img style="width:18px;padding:4px;margin-top:-4px;" src="graphics/groups_b.png">',$groupname,'</header>
                        </a>
                        <div style="width:400px;padding:25px;font-size:14px;font-weight:300;">
                            <span style="font-size:15px;font-weight:normal;line-height:22px;">About:</span><br />
                            ',$about,'
                        </div>
                    </div>
                </div>
         <!--End Content Box-->
         </div>
                  
         </div>';
    
    } //end type == 'create'
    
    elseif($type == 'create') {
        $ownersquery = "SELECT * FROM userinfo WHERE emailaddress = '$newsemail'";
        $ownerresult = mysql_query($ownersquery); 
        $ownerrow = mysql_fetch_array($ownerresult);
        $ownerfirst = $ownerrow['firstname'];
        $ownerlast = $ownerrow['lastname'];
        $ownerid = $ownerrow['user_id'];
        $ownerprofilepic = $ownerrow['profilepic'];
        //group info
        $groupinfoquery = mysql_query("SELECT * FROM groups WHERE id = $groupid");
        $about = mysql_result($groupinfoquery,0,'about');
        $groupname = mysql_result($groupinfoquery,0,'name');
        
        echo'<div class="grid_16 fPic" id="',$newsid,'">
         <!--Profile Picture-->
            <div class="newsBlock">
                <ul>
                    <li><img id="newsProfilePic" src="https://photorankr.com/',$ownerprofilepic,'" />
                </ul>
            </div>
                     
         <!--Content Box-->
         <div class="newsContainer">
            <div class="newsTriangle"></div>
                <div class="newsItem">
                    <!--Top Controls-->
                    <div class="newsControls">
                        <div class="newsName"><a href="viewprofile.php?u=',$ownerid,'">',$firstname,' ',$lastname,'</a> created a new group</div>
                        <div class="newsTools">
                            <span id="time">',$time,'</span>
                        </div>
                    </div>
                    <!--Content-->
                    <div id="followContent">
                        <a style="color:#333;" href="groups.php?id=',$groupid,'">
                            <header style="padding: 0px 20px;font-size:18px;font-weight:300;line-height:20px;"><img style="width:18px;padding:4px;margin-top:-4px;" src="graphics/groups_b.png">',$groupname,'</header>
                        </a>
                        <div style="width:400px;padding:25px;font-size:14px;font-weight:300;">
                            <span style="font-size:15px;font-weight:normal;line-height:22px;">About:</span><br />
                            ',$about,'
                        </div>
                    </div>
                </div>
         <!--End Content Box-->
         </div>
                  
         </div>';
    
    } //end type == 'create'
    
    elseif($type == 'join') {
        $ownersquery = "SELECT * FROM userinfo WHERE emailaddress = '$newsemail'";
        $ownerresult = mysql_query($ownersquery); 
        $ownerrow = mysql_fetch_array($ownerresult);
        $ownerfirst = $ownerrow['firstname'];
        $ownerlast = $ownerrow['lastname'];
        $ownerid = $ownerrow['user_id'];
        $ownerprofilepic = $ownerrow['profilepic'];
        //group info
        $groupinfoquery = mysql_query("SELECT * FROM groups WHERE id = $groupid");
        $about = mysql_result($groupinfoquery,0,'about');
        $groupname = mysql_result($groupinfoquery,0,'name');
        
        echo'<div class="grid_16 fPic" id="',$newsid,'">
         <!--Profile Picture-->
            <div class="newsBlock">
                <ul>
                    <li><img id="newsProfilePic" src="https://photorankr.com/',$ownerprofilepic,'" />
                </ul>
            </div>
                     
         <!--Content Box-->
         <div class="newsContainer">
            <div class="newsTriangle"></div>
                <div class="newsItem">
                    <!--Top Controls-->
                    <div class="newsControls">
                        <div class="newsName"><a href="viewprofile.php?u=',$ownerid,'">',$firstname,' ',$lastname,'</a> joined the group <a href="groups.php?id=',$groupid,'">',$groupname,'</a></div>
                        <div class="newsTools">
                            <span id="time">',$time,'</span>
                        </div>
                    </div>
                    <!--Content-->
                    <div id="followContent">
                        <a style="color:#333;" href="groups.php?id=',$groupid,'">
                            <header style="padding: 0px 20px;font-size:18px;font-weight:300;line-height:20px;"><img style="width:18px;padding:4px;margin-top:-4px;" src="graphics/groups_b.png">',$groupname,'</header>
                        </a>
                        <div style="width:400px;padding:25px;font-size:14px;font-weight:300;">
                            <span style="font-size:15px;font-weight:normal;line-height:22px;">About:</span><br />
                            ',$about,'
                        </div>
                    </div>
                </div>
         <!--End Content Box-->
         </div>
                  
         </div>';
    
    } //end type == 'join'
    
    
    elseif($type == 'post') {
        $ownersquery = "SELECT * FROM userinfo WHERE emailaddress = '$newsemail'";
        $ownerresult = mysql_query($ownersquery); 
        $ownerrow = mysql_fetch_array($ownerresult);
        $ownerfirst = $ownerrow['firstname'];
        $ownerlast = $ownerrow['lastname'];
        $ownerid = $ownerrow['user_id'];
        $ownerprofilepic = $ownerrow['profilepic'];
        //Group info
        $groupinfoquery = mysql_query("SELECT * FROM groups WHERE id = $groupid");
        $groupname = mysql_result($groupinfoquery,0,'name');
        //Post info
        $groupphotosquery = mysql_query("SELECT * FROM groupnews WHERE id = '$source' LIMIT 1");
        $comment = mysql_result($groupphotosquery,0,'comment');
        $photos = mysql_result($groupphotosquery,0,'photo');
        $photos = trim($photos);
        $photosarray = explode(" ",$photos);
        $numberphotos = count($photosarray);
        
        echo'<div class="grid_16 fPic" id="',$newsid,'">
         <!--Profile Picture-->
            <div class="newsBlock">
                <ul>
                    <li><img id="newsProfilePic" src="https://photorankr.com/',$ownerprofilepic,'" />
                </ul>
            </div>
                     
         <!--Content Box-->
         <div class="newsContainer">
            <div class="newsTriangle"></div>
                <div class="newsItem">
                    <!--Top Controls-->
                    <div class="newsControls">
                        <div class="newsName"><a href="viewprofile.php?u=',$ownerid,'">',$firstname,' ',$lastname,'</a> posted in <a href="groups.php?id=',$groupid,'">',$groupname,'</a></div>
                        <div class="newsTools">
                            <span id="time">',$time,'</span>
                        </div>
                    </div>
                    <!--Content-->
                    <div id="postContent">';
                        //Show photos if posted with a photo
                        
                        if($numberphotos == 1) {
                            for($ii=0; $ii < 1; $ii++) {
                                $getphotosource = mysql_query("SELECT id,source FROM photos WHERE id = $photosarray[$ii]");
                                $photosource = mysql_result($getphotosource,0,'source');
                                $medphoto = str_replace("userphotos/","userphotos/medthumbs/",$photosource);
                                $photoid = mysql_result($getphotosource,0,'id');
                                list($width, $height) = getimagesize($photosource);
                                $heightls = $height / 2.5;
                                $widthls = $width / 2.5;
                                                        
                                echo'<a href="fullsize.php?imageid=',$photoid,'"><div style="width:440px;padding:10px;margin-left:4%;overflow:hidden;"><img style="min-width:440px;" src="https://photorankr.com/',$medphoto,'" width="',$widthls,'" height="',$heightls,'" /></div></a>';
                            }
                        }
                       
                       elseif($numberphotos == 2) {
                            echo'<div style="width:505px;padding:10px;margin-left:10px;overflow:hidden;">';
                            for($ii=0; $ii < 2; $ii++) {
                                $getphotosource = mysql_query("SELECT id,source FROM photos WHERE id = $photosarray[$ii]");
                                $photosource = mysql_result($getphotosource,0,'source');
                                $photoid = mysql_result($getphotosource,0,'id');
                                $medphoto = str_replace("userphotos/","userphotos/medthumbs/",$photosource);
                                $photoid = mysql_result($getphotosource,0,'id');
                                list($width, $height) = getimagesize($photosource);
                                $heightls = $height / 3.5;
                                $widthls = $width / 3.5;
                                
                                echo'<a href="fullsize.php?imageid=',$photoid,'"><div style="float:left;height:215px;max-width:215px;padding:3px;"><img style="height:245px;" src="https://photorankr.com/',$medphoto,'" width="',$widthls,'" /></div></a>';
                            }
                            echo'</div>';
                       }
                       
                       elseif($numberphotos == 3) {
                              echo'<div style="width:505px;padding:10px;margin-left:10px;overflow:hidden;">';
                            for($ii=0; $ii < 3; $ii++) {
                                $getphotosource = mysql_query("SELECT id,source FROM photos WHERE id = $photosarray[$ii]");
                                $photosource = mysql_result($getphotosource,0,'source');
                                $photoid = mysql_result($getphotosource,0,'id');
                                $medphoto = str_replace("userphotos/","userphotos/medthumbs/",$photosource);
                                $photoid = mysql_result($getphotosource,0,'id');
                                list($width, $height) = getimagesize($photosource);
                                $heightls = $height / 3.5;
                                $widthls = $width / 3.5;
                                
                                echo'<a href="fullsize.php?imageid=',$photoid,'"><div style="float:left;height:160px;max-width:140px;padding:3px;"><img style="height:160px;" src="https://photorankr.com/',$medphoto,'" width="',$widthls,'" /></div></a>';
                            }
                            echo'</div>';
                       }
                       
                       elseif($numberphotos == 4) {
                              echo'<div style="width:505px;padding:10px;margin-left:10px;overflow:hidden;">';
                            for($ii=0; $ii < 4; $ii++) {
                                $getphotosource = mysql_query("SELECT id,source FROM photos WHERE id = $photosarray[$ii]");
                                $photosource = mysql_result($getphotosource,0,'source');
                                $photoid = mysql_result($getphotosource,0,'id');
                                $medphoto = str_replace("userphotos/","userphotos/medthumbs/",$photosource);
                                $photoid = mysql_result($getphotosource,0,'id');
                                list($width, $height) = getimagesize($photosource);
                                $heightls = $height / 3.5;
                                $widthls = $width / 3.5;
                                
                                echo'<a href="fullsize.php?imageid=',$photoid,'"><div style="float:left;height:195px;max-width:225px;padding:3px;"><img style="height:195px;" src="https://photorankr.com/',$medphoto,'" width="',$widthls,'" /></div></a>';
                            }
                            echo'</div>';
                       }
                        echo'
                        <div style="width:400px;padding-left:25px;padding-right:25px;font-size:14px;font-weight:300;">';
                            if($comment) {
                                echo'<img style="width:18px;padding:4px;margin-top:-4px;" src="graphics/groups_b.png"> ',$comment,'';
                            }
                            echo'
                            <div style="font-weight:500;padding-top:10px;">
                                <a href="groups.php?id=',$groupid,'#',$source,'">View post >>></a>
                            </div>
                        </div>
                    </div>
                </div>
         <!--End Content Box-->
         </div>
                  
         </div>';
    
    } //end type == 'post'
    
    elseif($type == 'sold') {
        $owner = $newsrow['owner'];
		$ownersquery = "SELECT * FROM userinfo WHERE emailaddress = '$owner'";
		$ownerresult = mysql_query($ownersquery); 
		$ownerrow = mysql_fetch_array($ownerresult);
		$ownerfirst = $ownerrow['firstname'];
		$ownerlast = $ownerrow['lastname'];
		$ownerid = $ownerrow['user_id'];
		$ownerfull = "<a href='viewprofile.php?u=" . $ownerid . "'>" . $ownerfirst . " " . $ownerlast . "</a>";
		$ownerfull = ucwords($ownerfull);
		$firstname = $newsrow['firstname'];
		$commenteremail = $newsrow['emailaddress'];
		$commentersquery = mysql_query("SELECT user_id,profilepic FROM userinfo WHERE emailaddress = '$commenteremail'");
		$commenterpic = mysql_result($commentersquery,0,'profilepic');
		$commenterid = mysql_result($commentersquery,0,'user_id');
		$firstname = ucwords($firstname);
		$lastname = $newsrow['lastname'];
		$lastname = ucwords($lastname);
        
        $imageinfo = mysql_query("SELECT * FROM photos WHERE source = '$source'");
        $image = mysql_result($imageinfo,0,'source');
        $imagenew=str_replace("userphotos/","userphotos/medthumbs/", $image);

        $views = mysql_result($imageinfo,0,'views');
        $points = mysql_result($imageinfo,0,'points');
        $votes = mysql_result($imageinfo,0,'votes');
        $faves = mysql_result($imageinfo,0,'faves');
        $about = mysql_result($imageinfo,0,'about');
        $camera = mysql_result($imageinfo,0,'camera');
        $id = mysql_result($imageinfo,0,'id');
        $lens = mysql_result($imageinfo,0,'lens');
        $filter = mysql_result($imageinfo,0,'filter');
        $aperture = mysql_result($imageinfo,0,'aperture');
        $shutterspeed = mysql_result($imageinfo,0,'shutterspeed');
        $rank = ($points / $votes);
        $rank = number_format($rank,2);
        $tag1 = mysql_result($imageinfo,0,'tag1');
        $tag2 = mysql_result($imageinfo,0,'tag2');
        $tag3 = mysql_result($imageinfo,0,'tag3');
        $tag4 = mysql_result($imageinfo,0,'tag4');
        
        $image = $newsrow['source'];
		$imageinfo = mysql_query("SELECT * FROM photos WHERE (id = '$image' OR id = '$oldimageid')");
		$views = mysql_result($imageinfo,0,'views');
		$points = mysql_result($imageinfo,0,'points');
		$about = mysql_result($imageinfo,0,'about');
		$imageID = mysql_result($imageinfo,0,'id');
		$source = mysql_result($imageinfo,0,'source');
		$votes = mysql_result($imageinfo,0,'votes');
		$rank = ($points / $votes);
		$rank = number_format($rank,2);
        
		list($width, $height) = getimagesize($source);
		$width = ($width / 2.5);
		$height = ($height / 2.5);

        
        echo'<div class="grid_16 fPic" id="',$newsid,'">
         <!--Profile Picture-->
            <div class="newsBlock">
                <ul>
                    <li><img id="newsProfilePic" src="https://photorankr.com/',$commenterpic,'" />
                </ul>
            </div>
                     
         <!--Content Box-->
         <div class="newsContainer">
            <div class="newsTriangle"></div>
                <div class="newsItem">
                    <!--Top Controls-->
                    <div class="newsControls">
                        <div class="newsName"><a href="viewprofile.php?u=',$ownerid,'">',$firstname,' ',$lastname,'</a> purchased <a href="viewprofile.php?u=',$ownerid,'">',$ownerfirst,' ',$ownerlast,'\'s photo</a></div>
                        <div class="newsTools">
                            <span id="time">',$time,'</span>
                        </div>
                    </div>
                    <!--Content-->
                    <div class="newsContent">
                        <a href="fullsize.php?imageid=',$imageID,'"><img style="min-width:480px;" src="https://photorankr.com/',$source,'" width="',$width,'px" height="',$height,'px" /></a>
                    </div>
                </div>
         <!--End Content Box-->
         </div>
                  
         </div>';
    
    } //end type == 'sold'
    
  } //end view == ''

  elseif($view == 'up') {
    
    if($type == "photo") {
	
    $image = $newsrow['source'];
    $imagenew=str_replace("userphotos/","userphotos/medthumbs/", $image);
	$caption = $newsrow['caption'];
    $owner = $newsrow['emailaddress'];
    $ownersquery = "SELECT * FROM userinfo WHERE emailaddress = '$owner'";
    $ownerresult = mysql_query($ownersquery); 
    $ownerrow = mysql_fetch_array($ownerresult);
    $ownerfirst = $ownerrow['firstname'];
    $ownerlast = $ownerrow['lastname'];
    $ownerid = $ownerrow['user_id'];
    $ownerprofilepic = $ownerrow['profilepic'];
    $ownerfull = $ownerfirst . " " . $ownerlast;
    $ownerfull = ucwords($ownerfull);
  	list($width, $height) = getimagesize($image);
	$imgratio = $height / $width;
	$height = $imgratio * $maxwidth;
    $phrase = "<a href='viewprofile.php?u=" . $ownerid . "'>" . $ownerfull . "</a> uploaded a photo";
    
    $imageinfo = mysql_query("SELECT * FROM photos WHERE source = '$image'");
    $views = mysql_result($imageinfo,0,'views');
    $points = mysql_result($imageinfo,0,'points');
    $votes = mysql_result($imageinfo,0,'votes');
    $faves = mysql_result($imageinfo,0,'faves');
    $about = mysql_result($imageinfo,0,'about');
    $camera = mysql_result($imageinfo,0,'camera');
    $id = mysql_result($imageinfo,0,'id');
    $lens = mysql_result($imageinfo,0,'lens');
    $filter = mysql_result($imageinfo,0,'filter');
    $aperture = mysql_result($imageinfo,0,'aperture');
    $shutterspeed = mysql_result($imageinfo,0,'shutterspeed');
    $rank = ($points / $votes);
    $rank = number_format($rank,2);
    $tag1 = mysql_result($imageinfo,0,'tag1');
    $tag2 = mysql_result($imageinfo,0,'tag2');
    $tag3 = mysql_result($imageinfo,0,'tag3');
    $tag4 = mysql_result($imageinfo,0,'tag4');
    
    list($width, $height) = getimagesize($image);
    $width = ($width / 3.2);
    $height = ($height / 3.2);
    
    ?>
    
    <script type="text/javascript">
        
        //Comment jQuery Script
        $(function() {
        $("#commentform<?php echo $id; ?>").submit(function() 
        {
        var firstname = '<?php echo $sessionfirst; ?>';
        var lastname = '<?php echo $sessionlast; ?>';
        var email = '<?php echo $email; ?>';
        var userpic = '<?php echo $sessionpic; ?>';
        var viewerid = '<?php echo $sessionid; ?>';
        var imageid = '<? echo $id; ?>';
        var comment = $("#commentBox<?php echo $id; ?>").val();
        var dataString = 'firstname='+ firstname + '&lastname=' + lastname + '&email=' + email + '&comment=' + comment + '&userpic=' + userpic + '&viewerid=' + viewerid + '&imageid=' + imageid;
        $("#flash").show();
        $("#flash").fadeIn(400).html();
        $.ajax({
        type: "POST",
        url: "newsfeedcomment.php",
        data: dataString,
        cache: false,
        success: function(html){
        $("ol#update<?php echo $id; ?>").append(html);
        $("ol#update li:last").fadeIn("slow");
        $("#flash").hide();
        }
        });
        return false;
        }); });
        
        //Display textarea
        $(function() 
        {
        $("#comment<?php echo $id; ?>").click(function()
        {
        $("#commentBox<?php echo $id; ?>").animate({"height": "85px",}, "fast" );
        $("#button_block<?php echo $id; ?>").slideDown("fast");
        jQuery("#commentform<?php echo $id; ?>").toggle();
        jQuery("#hiddenComments<?php echo $id; ?>").toggle();
        return false;
        });        
        });
 
    jQuery(document).ready(function(){
        jQuery("#showStats<?php echo $id; ?>").live("click", function(event) {        
            jQuery(".hiddenStats<?php echo $id; ?>").toggle();
        });
    });
    </script>
    
     <style type="text/css">
        .hiddenStats<?php echo $id; ?> {
            display:none;
        }
        .hiddenStats<?php echo $id; ?> ul {
            padding:15px;
            padding-top:60px;
            font-size:13px;
        }
        .hiddenStats<?php echo $id; ?> li {
            display:inline;
            padding:4px;
        }
        #hiddenComments<?php echo $id; ?> {
                display:none;
        }
        #commentform<?php echo $id; ?> {
            display:none;
            padding:20px 0;
        }
        #commentOption<?php echo $id; ?> {
            font-size:13px;
            padding-top:10px;
            font-weight:700;
            cursor:pointer;
        }
        #button_block<?php echo $id; ?> {
            display:none;
        }
        #button<?php echo $id; ?> {
            background-color:#33C33C;
            color:#ffffff;
            font-size:13px;
            font-weight:bold;
            padding:3px;
            margin-left:40px;
        }
    </style>
    
    <?php

    echo'<div class="grid_16 fPic" id="',$newsid,'">
         <!--Profile Picture-->
            <div class="newsBlock">
                <ul>
                    <li><img id="newsProfilePic" src="https://photorankr.com/',$ownerprofilepic,'" />
                    <li id="comment',$id,'"><img src="graphics/comment_1.png"></li>
                    <li><img src="graphics/heart.png"></li>
                     <a href="fullsizemarket.php?imageid=',id,'"><li><img src="graphics/tag.png"></li></a>
                </ul>
            </div>
         
         <!--Content Box-->
         <div class="newsContainer">
            <div class="newsTriangle"></div>
                <div class="newsItem">
                    <!--Top Controls-->
                    <div class="newsControls">
                        <div class="newsName">',$phrase,'</div>
                        <div class="newsTools">
                            <span id="time">',$time,'</span>
                        </div>
                    </div>
                    <!--Content-->
                    <div class="newsContent">
                        <a href="fullsize.php?imageid=',id,'"><img style="min-width:480px;" src="https://photorankr.com/',$imagenew,'" width="',$width,'px" height="',$height,'px" /></a>
                    </div>
                    <!---Stats--->
                    <div class="newsStats">
                        <span style="font-size:18px;font-weight:500;">',$rank,'</span>
                        <span style="font-size:18px;font-weight:300;">',$caption,'
                        <div style="display:inline;width:240px;float:right;margin-right:50px;text-align:right;font-size:15px;">';
                        if($tag1) {
                            echo'#',$tag1,' ';
                        }
                        if($tag2) {
                            echo'#',$tag2,' ';
                        }
                        if($tag3) {
                            echo'#',$tag3,' ';
                        }                    
                        echo'
                            <a href="#"><img id="showStats',$id,'" style="width:20px;margin-top:-3px;padding-left:8px;" src="graphics/stats 4.png" /></a>
                        </div>
                    </div>
                    
                    <div class="hiddenStats',$id,'">
                        <ul>
                            <li><img src="graphics/view.png" width="15" /> Views: ',$views,'</li>';
                        
                        if($camera) {
                            echo'<li><img src="graphics/camera.png" width="15" /> Camera: ',$camera,'</li>';
                        }
                        if($lens) {
                            echo'<li><img src="graphics/lens.png" width="15" /> Lens: ',$lens,'</li>';
                        }
                        if($focallength) {
                            echo'<li><img src="graphics/focal-length.png" width="15" /> Focal Length: ',$focallength,'</li>';
                        }
                        if($aperture) {
                            echo'<li><img src="graphics/aperature.png" width="15" /> Aperture: ',$aperture,'</li>';
                        }
                        if($shutterspeed) {
                            echo'<li><img src="graphics/shutter-speed.png" width="15" /> Shutter Speed: ',$shutterspeed,'</li>';
                        }   
                        echo'
                        </ul>
                    <!--About Photo-->';
                    if($about) {
                    echo'<div style="font-size:14px;font-weight:700;padding:10px 0 0 20px;">Behind the lens</div>
                        <div class="newsAbout">
                           ',$about,'
                        </div>';
                    }
                    if($tag1 || $tag2 || $tag3 || $tag4) {
                    echo'<div class="newsTagBox">
                        <ul class="tags">';
                            if($tag1) {
                                echo'<li><a href="#">',$tag1,'</a></li>';
                            }
                            if($tag2) {
                                echo'<li><a href="#">',$tag2,'</a></li>';
                            }
                            if($tag3) {
                                echo'<li><a href="#">',$tag3,'</a></li>';
                            }
                            if($tag4) {
                                echo'<li><a href="#">',$tag4,'</a></li>';
                            }
                            echo'
                        </ul>
                    </div>';
                    }
                    
                    echo'
                </div>
                <!--Comment Box-->
                    <div class="postCommentBox">
                         <form action="#" id="commentform',$id,'" method="post" style="margin-top:5px;padding-bottom:5px;"> 
                        <img style="float:left;" src="https://photorankr.com/',$sessionpic,'" height="30" width="30"  />
                        <textarea id="commentBox',$id,'" style="resize:none;margin-left:5px;width:395px;height:20px;" placeholder="Leave feedback for ',$firstname,'&#8230;"></textarea>
                        <div id="button_block',$id,'">
                         <input type="submit" id="button',$id,'" class="btn btn-success" value=" Comment "/>
                        </div>
                        </form>
                    </div>
                    
                    <!--AJAX COMMENTS-->
                        <ol id="update',$id,'" class="timeline">
                        </ol>
                    
                    <!--Previous Comments-->
                    <div id="hiddenComments',$id,'" class="previousComments">
                        <ul class="indPrevComment">';

                             for($iii = 0; $iii < $numcomments; $iii++) {
                                $prevcomment = mysql_result($grabcomments,$iii,'comment');
                                $commentid = mysql_result($grabcomments,$iii,'id');
                                $commenttime = mysql_result($grabcomments,$iii,'time');
                                $commenttime = converttime($commenttime);
                                $commenteremail = mysql_result($grabcomments,$iii,'commenter');
                                $commenterinfo = mysql_query("SELECT user_id,firstname,lastname,profilepic,reputation FROM userinfo WHERE emailaddress = '$commenteremail'");
                                $commentername = mysql_result($commenterinfo,0,'firstname') ." ". mysql_result($commenterinfo,0,'lastname');
                                $commenterid = mysql_result($commenterinfo,0,'user_id');
                                $commenterpic = mysql_result($commenterinfo,0,'profilepic');            
                                $commenterrep = number_format(mysql_result($commenterinfo,0,'reputation'),2);

                                echo'<li style="overflow:hidden;"> 
                                        <div style="width:35px;float:left;"><img src="https://photorankr.com/',$commenterpic,'" height="35" width="35" /></div>
                                        <div style="width:420px;float:left;" id="commenterName" style="float:left;"><a href="viewprofile.php?u=',$commenterid,'">',$commentername,'</a>
                                        <div style="float:right">',$commenttime,'</div>
                                        <div id="commentText">
                                            ',$prevcomment,'
                                        </div>
                                        </div>
                                     
                                     </li>';
                        
                            }
                            
                            echo'
                        </ul>
                    </div>
         <!--End Content Box-->
         </div>
    </div>
</div>';
             
    } //end type upload

} //end view == 'up' 

elseif($view == 'comments') {

    if ($type == "comment") {
    
		$owner = $newsrow['owner'];
		$ownersquery = "SELECT * FROM userinfo WHERE emailaddress = '$owner'";
		$ownerresult = mysql_query($ownersquery); 
		$ownerrow = mysql_fetch_array($ownerresult);
		$ownerfirst = $ownerrow['firstname'];
		$ownerlast = $ownerrow['lastname'];
		$ownerid = $ownerrow['user_id'];
		$ownerfull = "<a href='viewprofile.php?u=" . $ownerid . "'>" . $ownerfirst . " " . $ownerlast . "</a>";
		$ownerfull = ucwords($ownerfull);
		$firstname = $newsrow['firstname'];
		$commenteremail = $newsrow['emailaddress'];
		$commentersquery = mysql_query("SELECT user_id,profilepic FROM userinfo WHERE emailaddress = '$commenteremail'");
		$commenterpic = mysql_result($commentersquery,0,'profilepic');
		$commenterid = mysql_result($commentersquery,0,'user_id');
		$firstname = ucwords($firstname);
		$lastname = $newsrow['lastname'];
		$lastname = ucwords($lastname);
		$image = $newsrow['source'];
		$id = $newsrow['id'];
		$getimageid = mysql_query("SELECT id FROM photos WHERE source = '$image'");
		$oldimageid = mysql_result($getimageid,0,'id');
	   
		$imageinfo = mysql_query("SELECT * FROM photos WHERE (id = '$image' OR id = '$oldimageid')");
		$views = mysql_result($imageinfo,0,'views');
		$points = mysql_result($imageinfo,0,'points');
		$about = mysql_result($imageinfo,0,'about');
		$imageID = mysql_result($imageinfo,0,'id');
		$source = mysql_result($imageinfo,0,'source');
		$votes = mysql_result($imageinfo,0,'votes');
		$rank = ($points / $votes);
		$rank = number_format($rank,2);
        
        ?>
        
        <script type="text/javascript">
        //Comment jQuery Script
        $(function() {
        $("#commentform<?php echo $id; ?>").submit(function() 
        {
        var firstname = '<?php echo $sessionfirst; ?>';
        var lastname = '<?php echo $sessionlast; ?>';
        var email = '<?php echo $email; ?>';
        var userpic = '<?php echo $sessionpic; ?>';
        var viewerid = '<?php echo $sessionid; ?>';
        var imageid = '<? echo $imageID; ?>';
        var comment = $("#commentBox<?php echo $id; ?>").val();
        var dataString = 'firstname='+ firstname + '&lastname=' + lastname + '&email=' + email + '&comment=' + comment + '&userpic=' + userpic + '&viewerid=' + viewerid + '&imageid=' + imageid;
        $("#flash").show();
        $("#flash").fadeIn(400).html();
        $.ajax({
        type: "POST",
        url: "newsfeedcomment.php",
        data: dataString,
        cache: false,
        success: function(html){
        $("ol#update<?php echo $id; ?>").append(html);
        $("ol#update li:last").fadeIn("slow");
        $("#flash").hide();
        }
        });
        return false;
        }); });
 
        
        //Display textarea
        $(function() 
        {
        $("#comment<?php echo $id; ?>").click(function()
        {
        $("#commentBox<?php echo $id; ?>").animate({"height": "85px",}, "fast" );
        $("#button_block<?php echo $id; ?>").slideDown("fast");
        jQuery("#commentform<?php echo $id; ?>").toggle();
        jQuery("#hiddenComments<?php echo $id; ?>").toggle();
        return false;
        });        
        });
        </script>
        
        <style type="text/css">
            #hiddenComments<?php echo $id; ?> {
                display:none;
            }
            #commentform<?php echo $id; ?> {
            	display:none;
                padding:20px 0;
            }
            #commentOption<?php echo $id; ?> {
                font-size:13px;
                padding-top:10px;
                font-weight:700;
                cursor:pointer;
            }
            #button_block<?php echo $id; ?> {
                display:none;
            }
            #button<?php echo $id; ?> {
                background-color:#33C33C;
                color:#ffffff;
                font-size:13px;
                font-weight:bold;
                padding:3px;
                margin-left:40px;
            }
        </style>
        
        <?php
        
        //Get user's comment
        $lastcommentquery = mysql_query("SELECT comment FROM comments WHERE id = '$idcomment' LIMIT 0,1");
        $lastcomment = mysql_result($lastcommentquery,0,'comment');
        
         //All Previous Comments
        $grabcomments = mysql_query("SELECT * FROM comments WHERE imageid = '$imageID' ORDER BY id DESC");
        $numcomments = mysql_num_rows($grabcomments);
		
		$imagenew=str_replace("userphotos/","userphotos/medthumbs/", $source);
		$fullname = "<a href='viewprofile.php?u=" . $commenterid . "'>" . $firstname . " " . $lastname ."</a>";
		list($width, $height) = getimagesize($image);
		$imgratio = $height / $width;
		$height = $imgratio * $maxwidth;
		$phrase = $fullname . " commented on " . $ownerfull . "'s photo";
		
		list($width, $height) = getimagesize($source);
		$width = ($width / 2.5);
		$height = ($height / 2.5);
        
        echo'<div class="grid_16 fPic" id="',$newsid,'">
         <!--Profile Picture-->
            <div class="newsBlock">
                <ul>
                    <li><img id="newsProfilePic" src="https://photorankr.com/',$commenterpic,'" />
                    <li id="comment',$id,'"><img src="graphics/comment_1.png"></li>
                    <li><img src="graphics/heart.png"></li>
                     <a href="fullsizemarket.php?imageID=',id,'"><li><img src="graphics/tag.png"></li></a>
                </ul>
            </div>
                     
         <!--Content Box-->
         <div class="newsContainer">
            <div class="newsTriangle"></div>
                <div class="newsItem">
                    <!--Top Controls-->
                    <div class="newsControls">
                        <div class="newsName">',$fullname,' > ',$ownerfull,'</div>
                        <div class="newsTools">
                            <span id="time">',$time,'</span>
                        </div>
                    </div>
                    <!--Content-->
                    <div class="commentPhoto">
                         <a href="fullsize.php?imageid=',$imageID,'"><img style="max-height:500px;" src="https://photorankr.com/',$source,'" /></a>
                    </div>
                    <div class="commentBox">
                        <blockquote>
                            <p>',$lastcomment,'</p>
                        </blockquote>
                    </div>
                    <!--Comment Box-->
                    <div class="postCommentBox">
                         <form action="#" id="commentform',$id,'" method="post" style="margin-top:5px;padding-bottom:5px;"> 
                        <img style="float:left;" src="https://photorankr.com/',$sessionpic,'" height="30" width="30"  />
                        <textarea id="commentBox',$id,'" style="resize:none;margin-left:5px;width:395px;height:20px;" placeholder="Leave feedback for ',$firstname,'&#8230;"></textarea>
                        <div id="button_block',$id,'">
                         <input type="submit" id="button',$id,'" class="btn btn-success" value=" Comment "/>
                        </div>
                        </form>
                    </div>
                    
                    <!--AJAX COMMENTS-->
                        <ol id="update',$id,'" class="timeline">
                        </ol>
                    
                    <!--Previous Comments-->
                    <div id="hiddenComments',$id,'" class="previousComments">
                        <ul class="indPrevComment">';

                             for($iii = 0; $iii < $numcomments; $iii++) {
                                $prevcomment = mysql_result($grabcomments,$iii,'comment');
                                $commentid = mysql_result($grabcomments,$iii,'id');
                                $commenttime = mysql_result($grabcomments,$iii,'time');
                                $commenttime = converttime($commenttime);
                                $commenteremail = mysql_result($grabcomments,$iii,'commenter');
                                $commenterinfo = mysql_query("SELECT user_id,firstname,lastname,profilepic,reputation FROM userinfo WHERE emailaddress = '$commenteremail'");
                                $commentername = mysql_result($commenterinfo,0,'firstname') ." ". mysql_result($commenterinfo,0,'lastname');
                                $commenterid = mysql_result($commenterinfo,0,'user_id');
                                $commenterpic = mysql_result($commenterinfo,0,'profilepic');            
                                $commenterrep = number_format(mysql_result($commenterinfo,0,'reputation'),2);

                                echo'<li style="overflow:hidden;"> 
                                        <div style="width:35px;float:left;"><img src="https://photorankr.com/',$commenterpic,'" height="35" width="35" /></div>
                                        <div style="width:420px;float:left;" id="commenterName" style="float:left;"><a href="viewprofile.php?u=',$commenterid,'">',$commentername,'</a>
                                        <div style="float:right">',$commenttime,'</div>
                                        <div id="commentText">
                                            ',$prevcomment,'
                                        </div>
                                        </div>
                                     
                                     </li>';
                        
                            }
                            
                            echo'
                        </ul>
                    </div>
                    
                </div>
         <!--End Content Box-->
         </div>
         
         </div>';
        
    } //end type == 'comments'

} //end view == 'comments'

elseif($view == 'faves') {

if ($type == "fave") {
    $owner = $newsrow['owner'];
    $ownersquery = "SELECT * FROM userinfo WHERE emailaddress = '$owner'";
    $ownerresult = mysql_query($ownersquery); 
    $ownerrow = mysql_fetch_array($ownerresult);
    $ownerfirst = $ownerrow['firstname'];
    $ownerlast = $ownerrow['lastname'];
    $ownerid = $ownerrow['user_id'];
    $ownerfull = "<a href='viewprofile.php?u=" . $ownerid . "'>" . $ownerfirst . " " . $ownerlast . "</a>";
    $ownerfull = ucwords($ownerfull);
    $firstname = $newsrow['firstname'];
    $commenteremail = $newsrow['emailaddress'];
    $commentersquery = mysql_query("SELECT user_id,profilepic FROM userinfo WHERE emailaddress = '$commenteremail'");
    $commenterpic = mysql_result($commentersquery,0,'profilepic');
    $commenterid = mysql_result($commentersquery,0,'user_id');
    $firstname = ucwords($firstname);
    $lastname = $newsrow['lastname'];
    $lastname = ucwords($lastname);
    $image = $newsrow['source'];
    $imagenew=str_replace("userphotos/","userphotos/medthumbs/", $image);
	$caption = $newsrow['caption'];
	list($width, $height) = getimagesize($image);
	$imgratio = $height / $width;
	$height = $imgratio * $maxwidth;
    $fullname = "<a href='viewprofile.php?u=" . $commenterid . "'>" . $firstname . " " . $lastname . "</a>";
    if($type == "fave") {
        $phrase = $fullname . " favorited " . 'a photo' . " by " . $ownerfull;
    }
    elseif($type == "discoverfave") {
            $phrase = $fullname . " discovered " . 'a photo' . " by " . $ownerfull;
    }
    $imageinfo = mysql_query("SELECT * FROM photos WHERE source = '$image'");
    $views = mysql_result($imageinfo,0,'views');
    $points = mysql_result($imageinfo,0,'points');
    $votes = mysql_result($imageinfo,0,'votes');
    $faves = mysql_result($imageinfo,0,'faves');
    $about = mysql_result($imageinfo,0,'about');
    $camera = mysql_result($imageinfo,0,'camera');
    $id = mysql_result($imageinfo,0,'id');
    $lens = mysql_result($imageinfo,0,'lens');
    $filter = mysql_result($imageinfo,0,'filter');
    $aperture = mysql_result($imageinfo,0,'aperture');
    $shutterspeed = mysql_result($imageinfo,0,'shutterspeed');
    $rank = ($points / $votes);
    $rank = number_format($rank,2);
    $tag1 = mysql_result($imageinfo,0,'tag1');
    $tag2 = mysql_result($imageinfo,0,'tag2');
    $tag3 = mysql_result($imageinfo,0,'tag3');
    $tag4 = mysql_result($imageinfo,0,'tag4');
    
    list($width, $height) = getimagesize($image);
    $width = ($width / 3.2);
    $height = ($height / 3.2);
    
    ?>
    
    <script type="text/javascript">
        
        //Comment jQuery Script
        $(function() {
        $("#commentform<?php echo $id; ?>").submit(function() 
        {
        var firstname = '<?php echo $sessionfirst; ?>';
        var lastname = '<?php echo $sessionlast; ?>';
        var email = '<?php echo $email; ?>';
        var userpic = '<?php echo $sessionpic; ?>';
        var viewerid = '<?php echo $sessionid; ?>';
        var imageid = '<? echo $id; ?>';
        var comment = $("#commentBox<?php echo $id; ?>").val();
        var dataString = 'firstname='+ firstname + '&lastname=' + lastname + '&email=' + email + '&comment=' + comment + '&userpic=' + userpic + '&viewerid=' + viewerid + '&imageid=' + imageid;
        $("#flash").show();
        $("#flash").fadeIn(400).html();
        $.ajax({
        type: "POST",
        url: "newsfeedcomment.php",
        data: dataString,
        cache: false,
        success: function(html){
        $("ol#update<?php echo $id; ?>").append(html);
        $("ol#update li:last").fadeIn("slow");
        $("#flash").hide();
        }
        });
        return false;
        }); });
        
        //Display textarea
        $(function() 
        {
        $("#comment<?php echo $id; ?>").click(function()
        {
        $("#commentBox<?php echo $id; ?>").animate({"height": "85px",}, "fast" );
        $("#button_block<?php echo $id; ?>").slideDown("fast");
        jQuery("#commentform<?php echo $id; ?>").toggle();
        jQuery("#hiddenComments<?php echo $id; ?>").toggle();
        return false;
        });        
        });
 
    jQuery(document).ready(function(){
        jQuery("#showStats<?php echo $id; ?>").live("click", function(event) {        
            jQuery(".hiddenStats<?php echo $id; ?>").toggle();
        });
    });
    </script>
    
     <style type="text/css">
        .hiddenStats<?php echo $id; ?> {
            display:none;
        }
        .hiddenStats<?php echo $id; ?> ul {
            padding:15px;
            padding-top:60px;
            font-size:13px;
        }
        .hiddenStats<?php echo $id; ?> li {
            display:inline;
            padding:4px;
        }
        #hiddenComments<?php echo $id; ?> {
                display:none;
        }
        #commentform<?php echo $id; ?> {
            display:none;
            padding:20px 0;
        }
        #commentOption<?php echo $id; ?> {
            font-size:13px;
            padding-top:10px;
            font-weight:700;
            cursor:pointer;
        }
        #button_block<?php echo $id; ?> {
            display:none;
        }
        #button<?php echo $id; ?> {
            background-color:#33C33C;
            color:#ffffff;
            font-size:13px;
            font-weight:bold;
            padding:3px;
            margin-left:40px;
        }
    </style>
    
    <?php

    echo'<div class="grid_16 fPic" id="',$newsid,'">
         <!--Profile Picture-->
            <div class="newsBlock">
                <ul>
                    <li><img id="newsProfilePic" src="https://photorankr.com/',$commenterpic,'" />
                    <li id="comment',$id,'"><img src="graphics/comment_1.png"></li>
                    <li><img src="graphics/heart.png"></li>
                     <a href="fullsizemarket.php?imageid=',$id,'"><li><img src="graphics/tag.png"></li></a>
                </ul>
            </div>
         
         <!--Content Box-->
         <div class="newsContainer">
            <div class="newsTriangle"></div>
                <div class="newsItem">
                    <!--Top Controls-->
                    <div class="newsControls">
                        <div class="newsName">',$phrase,'</div>
                        <div class="newsTools">
                            <span id="time">',$time,'</span>
                        </div>
                    </div>
                    <!--Content-->
                    <div class="newsContent">
                        <a href="fullsize.php?imageid=',$id,'"><img style="min-width:480px;" src="https://photorankr.com/',$imagenew,'" width="',$width,'px" height="',$height,'px" /></a>
                    </div>
                    <!---Stats--->
                    <div class="newsStats">
                        <span style="font-size:18px;font-weight:500;">',$rank,'</span>
                        <span style="font-size:18px;font-weight:300;">',$caption,'
                        <div style="display:inline;width:240px;float:right;margin-right:50px;text-align:right;font-size:15px;">';
                        if($tag1) {
                            echo'#',$tag1,' ';
                        }
                        if($tag2) {
                            echo'#',$tag2,' ';
                        }
                        if($tag3) {
                            echo'#',$tag3,' ';
                        }                    
                        echo'
                            <a href="#"><img id="showStats',$id,'" style="width:20px;margin-top:-3px;padding-left:8px;" src="graphics/stats 4.png" /></a>
                        </div>
                    </div>
                    
                    <div class="hiddenStats',$id,'">
                        <ul>
                            <li><img src="graphics/view.png" width="15" /> Views: ',$views,'</li>';
                        
                        if($camera) {
                            echo'<li><img src="graphics/camera.png" width="15" /> Camera: ',$camera,'</li>';
                        }
                        if($lens) {
                            echo'<li><img src="graphics/lens.png" width="15" /> Lens: ',$lens,'</li>';
                        }
                        if($focallength) {
                            echo'<li><img src="graphics/focal-length.png" width="15" /> Focal Length: ',$focallength,'</li>';
                        }
                        if($aperture) {
                            echo'<li><img src="graphics/aperature.png" width="15" /> Aperture: ',$aperture,'</li>';
                        }
                        if($shutterspeed) {
                            echo'<li><img src="graphics/shutter-speed.png" width="15" /> Shutter Speed: ',$shutterspeed,'</li>';
                        }   
                        echo'
                        </ul>
                    <!--About Photo-->';
                    if($about) {
                    echo'<div style="font-size:14px;font-weight:700;padding:10px 0 0 20px;">Behind the lens</div>
                        <div class="newsAbout">
                           ',$about,'
                        </div>';
                    }
                    if($tag1 || $tag2 || $tag3 || $tag4) {
                    echo'<div class="newsTagBox">
                        <ul class="tags">';
                            if($tag1) {
                                echo'<li><a href="#">',$tag1,'</a></li>';
                            }
                            if($tag2) {
                                echo'<li><a href="#">',$tag2,'</a></li>';
                            }
                            if($tag3) {
                                echo'<li><a href="#">',$tag3,'</a></li>';
                            }
                            if($tag4) {
                                echo'<li><a href="#">',$tag4,'</a></li>';
                            }
                            echo'
                        </ul>
                    </div>';
                    }
                    
                    echo'
                </div>
                <!--Comment Box-->
                    <div class="postCommentBox">
                         <form action="#" id="commentform',$id,'" method="post" style="margin-top:5px;padding-bottom:5px;"> 
                        <img style="float:left;" src="https://photorankr.com/',$sessionpic,'" height="30" width="30"  />
                        <textarea id="commentBox',$id,'" style="resize:none;margin-left:5px;width:395px;height:20px;" placeholder="Leave feedback for ',$firstname,'&#8230;"></textarea>
                        <div id="button_block',$id,'">
                         <input type="submit" id="button',$id,'" class="btn btn-success" value=" Comment "/>
                        </div>
                        </form>
                    </div>
                    
                    <!--AJAX COMMENTS-->
                        <ol id="update',$id,'" class="timeline">
                        </ol>
                    
                    <!--Previous Comments-->
                    <div id="hiddenComments',$id,'" class="previousComments">
                        <ul class="indPrevComment">';

                             for($iii = 0; $iii < $numcomments; $iii++) {
                                $prevcomment = mysql_result($grabcomments,$iii,'comment');
                                $commentid = mysql_result($grabcomments,$iii,'id');
                                $commenttime = mysql_result($grabcomments,$iii,'time');
                                $commenttime = converttime($commenttime);
                                $commenteremail = mysql_result($grabcomments,$iii,'commenter');
                                $commenterinfo = mysql_query("SELECT user_id,firstname,lastname,profilepic,reputation FROM userinfo WHERE emailaddress = '$commenteremail'");
                                $commentername = mysql_result($commenterinfo,0,'firstname') ." ". mysql_result($commenterinfo,0,'lastname');
                                $commenterid = mysql_result($commenterinfo,0,'user_id');
                                $commenterpic = mysql_result($commenterinfo,0,'profilepic');            
                                $commenterrep = number_format(mysql_result($commenterinfo,0,'reputation'),2);

                                echo'<li style="overflow:hidden;"> 
                                        <div style="width:35px;float:left;"><img src="https://photorankr.com/',$commenterpic,'" height="35" width="35" /></div>
                                        <div style="width:420px;float:left;" id="commenterName" style="float:left;"><a href="viewprofile.php?u=',$commenterid,'">',$commentername,'</a>
                                        <div style="float:right">',$commenttime,'</div>
                                        <div id="commentText">
                                            ',$prevcomment,'
                                        </div>
                                        </div>
                                     
                                     </li>';
                        
                            }
                            
                            echo'
                        </ul>
                    </div>
         <!--End Content Box-->
         </div>
    </div>
</div>';
    
    } //end type == 'favorite'

} //end of view favorites

} //end of main for loop

//AJAX HERE!
echo'
<div class="grid_6 push_4" style="padding-top:25px;padding-bottom:25px;">
<div id="loadMorePics" style="display: none; text-align: center;font-family:arial,helvetica neue; font-size:15px;"><img style="width:50px;" src="LoadingGIF.gif" /></div>
</div>';

echo'<script>

var last = 0;

	$(window).scroll(function(){
		if($(window).scrollTop() > $(document).height() - $(window).height()-400) {
			if(last != $(".fPic:last").attr("id")) {
				$("div#loadMorePics").show();
				$.ajax({
					url: "loadMoreNewsfeedNew.php?view=',$view,'&lastPicture=" + $(".fPic:last").attr("id") + "&email=',$email,'",
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

echo'
</div>
</div>';

?>

<!-----------------------End Newsfeed----------------------->



<!-----------------------Begin Middle Column---------------------->

<div class="grid_6" style="position:relative;left:-15px;margin-top:55px;">

<!-------------------Suggested Photographers----------------->
    <?php
        //select all of the people they are following
	$followingquery = "SELECT following FROM userinfo WHERE emailaddress='$email' LIMIT 1";
	$followingresult = mysql_query($followingquery);
	$followinglistowner = mysql_result($followingresult, 0, "following");
    
	//select all the people they are following who aren't themselves
	$str = mysql_real_escape_string("%'%',%'%',%'%',%'%'%',%'%'%");
	$followingquery = "SELECT following FROM userinfo WHERE emailaddress IN($followinglistowner) AND emailaddress NOT IN('$email') AND following LIKE('" . $str . "') ORDER BY RAND() LIMIT 1";
	$followingresult = mysql_query($followingquery) or die(mysql_error());
	$followinglist = mysql_result($followingresult, 0, "following");
	$followingnumber = mysql_num_rows($followingresult);
	
	//if they aren't yet following anyone, just get four random photographers
	if($followingnumber == 0) {
		$displayquery = "SELECT firstname, lastname, profilepic FROM userinfo ORDER BY RAND() LIMIT 4";
		$displayresult = mysql_query($displayquery);
	}
	//else they are following people so go ahead with the original procedure
	else {
		$displayquery = "SELECT firstname, lastname, profilepic,user_id FROM userinfo WHERE emailaddress IN($followinglist) AND emailaddress NOT IN('$email', $followinglistowner, 'support@photorankr.com') ORDER BY RAND() LIMIT 4";		
		$displayresult = mysql_query($displayquery) or die(mysql_error());
        $numdisplayresult = mysql_num_rows($displayresult);
	}
	
	//see if we have enough information to even display this 
		//loop through the people, printing out their name and profile picture
        //echo'<span style="font-size:16px;font-weight:100;padding:15px;">Photographers to Follow:</span>';
        
        echo'<div class="updateBoxBottom" style="width:275px;margin-top:20px;">
             <header>Who to Follow</header>
             </div>';
        
        echo'<ul class="followBoxes" style="margin-top:-35px;">';
        
		for($iii=0; $iii < 4; $iii++) {
            $firstname = mysql_result($displayresult, $iii, "firstname");
			$name = mysql_result($displayresult, $iii, "firstname") . " " . mysql_result($displayresult, $iii, "lastname");
			$profilepic = mysql_result($displayresult, $iii, "profilepic");
            $profileid = mysql_result($displayresult, $iii, "user_id");
            
            if($name == '' || $pofilepic == ''){
            $somequery = mysql_query("SELECT firstname,lastname,profilepic,user_id,emailaddress FROM userinfo WHERE profilepic != 'profilepics/default_profile.jpg' && firstname != 'PhotoRankr' ORDER BY RAND()");
            $firstname = mysql_result($somequery, $iii, "firstname");
            $name = $firstname . " " . mysql_result($somequery, $iii, "lastname");
			$profilepic = mysql_result($somequery, $iii, "profilepic");
            $profileid = mysql_result($somequery, $iii, "user_id");
            $profileemail = mysql_result($somequery, $iii, "emailaddress");
             
            $numownerphotosquery = mysql_query("SELECT source,id FROM photos WHERE emailaddress = '$profileemail' ORDER BY (points/votes) DESC");
            $numownerphotos = mysql_num_rows($numownerphotosquery);
            $photo1 = mysql_result($numownerphotosquery,0,'source');
            $photo1 = str_replace('userphotos/','userphotos/medthumbs/',$photo1);
            $photo1id = mysql_result($numownerphotosquery,0,'id');
            $photo2 = mysql_result($numownerphotosquery,1,'source');
            $photo2 = str_replace('userphotos/','userphotos/medthumbs/',$photo2);
            $photo2id = mysql_result($numownerphotosquery,1,'id');
            $photo3 = mysql_result($numownerphotosquery,2,'source');
            $photo3 = str_replace('userphotos/','userphotos/medthumbs/',$photo3);
            $photo3id = mysql_result($numownerphotosquery,2,'id');
    
            $followersquery="SELECT * FROM userinfo WHERE following LIKE '%$profileemail%'";
            $followersresult=mysql_query($followersquery);
            $numberfollowers = mysql_num_rows($followersresult);
                
            for($ii = 0; $ii < $numownerphotos; $ii++) {
                $points = mysql_result($numownerphotosquery, $ii, "points");
                $votes = mysql_result($numownerphotosquery, $ii, "votes");
                $totalfaves = mysql_result($numownerphotosquery, $ii, "faves");
                $portfoliopoints+=$points;
                $portfoliovotes+=$votes;
                $portfoliofaves+=$totalfaves;
            }
            if ($portfoliovotes > 0) {
                $portfolioranking=($portfoliopoints/$portfoliovotes);
                $portfolioranking=number_format($portfolioranking, 2, '.', '');    
                }
            else if ($portfoliovotes < 1) {
                $portfolioranking="N/A";
                }	
                
        } 
        ?>
        
        <script type="text/javascript">
        //Show Follower Images
        jQuery(document).ready(function(){
            jQuery("#showImages<?php echo $profileid; ?>").live("click", function(event) {        
                jQuery("#hiddenImages<?php echo $profileid; ?>").toggle();
            });
        });
        </script>
        
        <?php

                    echo'<li>
                            <img style="float:left;" src="https://photorankr.com/',$profilepic,'" />
                            <div class="innerFollowBox">
                                <div id="name" style="width:245px;">
                                    <a style="color:#666;" href="viewprofile.php?u=',$profileid,'">
                                    ',$name,' <br />
                                    </a>
                                    <span id="smaller">Followed by ',$numberfollowers,' photographers</span>
                                </div>
                                
                                <!--Top Images from this Photographer---->';
                                if($numownerphotos > 2) {
                                echo'<div style="float:left;width:250px;font-size:13px;">
                                        <div style="color:#3b5998;cursor:pointer;" id="showImages',$profileid,'">Top Images from ',$firstname,'</div>
                                     </div>
                                     </div>
                                     <div id="hiddenImages',$profileid,'" class="hiddenFollowerImages">
                                        <a href="fullsize.php?imageid=',$photo1id,'"><img src="https://photorankr.com/',$photo1,'" /></a>
                                        <a href="fullsize.php?imageid=',$photo2id,'"><img src="https://photorankr.com/',$photo2,'" /></a>
                                        <a href="fullsize.php?imageid=',$photo3id,'"><img src="https://photorankr.com/',$photo3,'" /></a>
                                     </div>';
                                }
                             echo'
                        </li>';
		}
        
                
        echo'</ul>';
?>


<!-------Search by Hashtags------>

<div class="updateBoxBottom" style="width:245px;padding-bottom:0px;padding:15px;">
    <form action="" method="get">
        <input type="hidden" value="search" name="view" />
        <input type="text" name="tag" id="newsSearchBox" placeholder="Search By Tags&hellip;" />
    </form>
</div>

<ul class="followBoxes" style="margin-top:-30px;">
<?php
    $getTags = mysql_query("SELECT * FROM photos ORDER BY id DESC LIMIT 250");
    $tagsArray = array();
    for($iii=0; $iii<250; $iii++) {
        $tag1 = mysql_result($getTags,$iii,'tag1');
        $tag1 = strtolower($tag1);
        $tagsArray[$tag1] += 1;
        $tag2 = mysql_result($getTags,$iii,'tag2');
        $tag3 = strtolower($tag2);
        $tagsArray[$tag2] += 1;
        $tag3 = mysql_result($getTags,$iii,'tag3');
        $tag3 = strtolower($tag3);
        $tagsArray[$tag3] += 1;
        $tag4 = mysql_result($getTags,$iii,'tag4');
        $tag4 = strtolower($tag4);
        $tagsArray[$tag4] += 1;
        $singlestylearray = mysql_result($getTags,$iii,'singlestyletags');
            foreach ($singlestylearray as $value)
            {
                $value = trim(strtolower($value));
                $tagsArray[$value] += 1;
            }
        $singlecategoryarray = mysql_result($getTags,$iii,'singlecategorytags');
            foreach ($singlecategoryarray as $value)
            {
                $value = trim(strtolower($value));
                $tagsArray[$value] += 1;
            }
        }
        arsort($tagsArray);
        foreach ($tagsArray as $key => $val) {
            if($count == 0) {
                $count += 1;
                continue;
            }
            if($count > 4) {
                break;
            }
            $key = strtolower($key);
            $tagPhotos = mysql_query("SELECT source,id FROM photos WHERE concat(tag1,tag2,tag3,tag4,singlestyletags,singlecategorytags) LIKE '%$key%' ORDER BY views DESC LIMIT 4");
            $image1 = mysql_result($tagPhotos,0,'source');
            $image1 = str_replace('userphotos/','userphotos/medthumbs/',$image1);
            $image1id = mysql_result($tagPhotos,0,'id');
            $image2 = mysql_result($tagPhotos,1,'source');
            $image2 = str_replace('userphotos/','userphotos/medthumbs/',$image2);
            $image2id = mysql_result($tagPhotos,1,'id');
            $image3 = mysql_result($tagPhotos,2,'source');
            $image3 = str_replace('userphotos/','userphotos/medthumbs/',$image3);
            $image3id = mysql_result($tagPhotos,2,'id');
            $image4 = mysql_result($tagPhotos,3,'source');
            $image4 = str_replace('userphotos/','userphotos/medthumbs/',$image4);
            $image4id = mysql_result($tagPhotos,3,'id');
        ?>
        <script type="text/javascript">
        //Show More Trending Images
        jQuery(document).ready(function(){
            jQuery("#showTrendingImages<?php echo $key; ?>").live("click", function(event) {        
                jQuery("#hiddenTrendingImages<?php echo $key; ?>").toggle();
            });
        });
        </script>
        <?php
             echo'<li>
                    <a href="fullsize.php?imageid=',$image1id,'">
                    <img style="float:left;" src="https://photorankr.com/',$image1,'" />
                    </a>
                        <div class="innerFollowBox">
                            <div id="name" style="width:245px;">
                                <a style="color:#666;" href="newsfeed.php?view=search&tag=',$key,'">
                                    #',$key,' 
                                </a>
                                <br />
                                <span id="smaller">',$val,' trending photographs</span>
                            </div>
                                
                            <div style="float:left;width:250px;font-size:13px;">
                                <div style="color:#3b5998;cursor:pointer;" id="showTrendingImages',$key,'">More trending images #',$key,'</div>
                                </div>
                            </div>
                            <div id="hiddenTrendingImages',$key,'" class="hiddenFollowerImages">
                                <a href="fullsize.php?imageid=',$image2id,'"><img src="https://photorankr.com/',$image2,'" /></a>
                                <a href="fullsize.php?imageid=',$image3id,'"><img src="https://photorankr.com/',$image3,'" /></a>
                               <a href="fullsize.php?imageid=',$image4id,'"><img src="https://photorankr.com/',$image4,'" /></a>
                            </div>';
                        echo'
                    </li>';
                        
            $count += 1;
        }

?>
</ul>


</div>


<!-----------------------End Middle Column---------------------->



<!-----------------------Begin Far Right Column---------------------->

<div class="grid_6" style="margin-top:70px;position:relative;left:45px;">

    <!-----------------------Title------------------------------->
    
    <div class="newsUpdateBox">
    
    	<div class="updateBoxTop">
    		<img src="https://photorankr.com/<?php echo $sessionpic; ?>" />
    		<header><?php echo $sessionname; ?></header>
    	</div>
    	
    	<div class="updateBoxMiddle">
    		<ul>
    			<li><?php echo $numphotos; ?><p>Photos</p></li>
    			<li><?php echo $numberfollowers; ?><p>Followers</p></li>
    			<li style="border:none;"><?php echo $numberfollowing; ?><p>Following</p></li>
    		</ul>
    	</div>
    
		<div class="updateBoxBottom">
			<form action="#" id="statusForm">
				<textarea id="status" style="resize:none;margin:10px;padding:4px;width:240px;height:22px;" placeholder="What's new with your photography?"></textarea>
				 <div id="button_blockFeed">
                 <input type="submit" id="statusButton" class="btn btn-success" value=" Post "/>
             </div>
        </form>
        
        </div>    
    
    </div>

    <!--------------------Menu Options--------------------------->
    
    <ul class="followBoxes menu">
        <a class="link" href="newsfeed.php"><li><img src="graphics/list 2.png" />All News</li></a>
        <a class="link" href="newsfeed.php?view=up"><li><img src="graphics/camera2.png" />Uploads</li></a>
        <a class="link" href="newsfeed.php?view=comments"><li><img src="graphics/comment_1.png" />Comments</li></a>
        <a class="link" href="newsfeed.php?view=faves"><li><img src="graphics/heart.png" />Favorites</li></a>
    </ul>

</div>

<!-----------------------End Far Right Column---------------------->


<!-----------------------End of Container--------------------->
</div>
</body>
</html>