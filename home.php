<?php

    //connect to the database
    require "db_connection.php";
    require "functionsnav.php";
    require "timefunction.php";

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

    //Notifications and user information
    $userinfo = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$email'");
    $currentnotsresult = mysql_result($userinfo, 0, "notifications");
    $sessionuserid = mysql_result($userinfo,0,'user_id');
    $sessionfirst =  mysql_result($userinfo,0,'firstname');
    $sessionlast =  mysql_result($userinfo,0,'lastname');
    $sessionphoto =  mysql_result($userinfo,0,'profilepic');
    $sessionfollowing =  mysql_result($userinfo,0,'following');
    $sessionrep =  number_format(mysql_result($userinfo,0,'reputation'),2);
    $followersquery = mysql_query("SELECT * FROM userinfo WHERE following LIKE '%$email%'");
    $numberfollowers = mysql_num_rows($followersquery);
    $followingquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress IN ($sessionfollowing)");
    $numberfollowing = mysql_num_rows($followingquery);
    $userphotos = mysql_query("SELECT id FROM photos WHERE emailaddress = '$email'");
    $numphotos = mysql_num_rows($userphotos);
    
    //Notifications query reset 
    if($currentnotsresult > 0) {
        $notsquery = "UPDATE userinfo SET notifications = 0 WHERE emailaddress = '$email'";
        $notsqueryrun = mysql_query($notsquery);
    }

    //Grab the views
    $view = htmlentities($_GET['view']);
    $category = htmlentities($_GET['cat']);
    $set = htmlentities($_GET['set']);
    
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

  <title>PhotoRankr</title>

<meta name="Generator" content="EditPlus">
<meta name="Author" content="PhotoRankr, PhotoRankr.com">
<meta name="Keywords" content="photos, sharing photos, photo sharing, photography, photography club, sell photos, sell photography, where to sell my photography, good sites for selling photography, making money from photography, making money off photography, social networking, social network, social networks, where to sell my photos, good sites for selling photos, good site to sell photos, making money from photos">
<meta name="Description" content="A gallery of the newest photography, photographers, and exhibits on PhotoRankr."> 
<meta name="viewport" content="width=1200" /> 

  <link rel="stylesheet" type="text/css" href="market/css/bootstrapNew.css" />
  <link rel="stylesheet" href="market/css/reset.css" type="text/css" />
  <link rel="stylesheet" href="market/css/text.css" type="text/css" />
  <link rel="stylesheet" href="css/style.css" type="text/css" />
  <link rel="stylesheet" href="960_24.css" type="text/css" />
  <link rel="stylesheet" type="text/css" href="market/css/all.css"/>              
  <script type="text/javascript" href="js/bootstrap-dropdown.js"></script>
  <script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
  <script type="text/javascript" src="js/jquery.wookmark.js"></script>        
  <link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>
  
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
<body style="overflow-x:hidden; background-color:#ddd; min-width:1250px;">

<?php navbarnew(); ?>

   <!--big container-->

	<div id="container" class="container_24">

        <div class="grid_5 sidebar">
        
        <a style="text-decoration:none;" href="home.php"><div class="sidebaritem" <?php if($view == '') {echo'style="background:#1c1c1c;"';} ?>>Newsfeed</div></a>
        <a style="text-decoration:none;" href="home.php?view=trending"><div class="sidebaritem" <?php if($view == 'trending') {echo'style="background:#1c1c1c;"';} ?>>Trending</div></a>
        <a style="text-decoration:none;" href="home.php?view=newest"><div class="sidebaritem" <?php if($view == 'newest') {echo'style="background:#1c1c1c;"';} ?>>Newest</div></a>
        <a style="text-decoration:none;" href="home.php?view=topranked"><div class="sidebaritem" <?php if($view == 'top') {echo'style="background:#1c1c1c;"';} ?>>Top Ranked</div></a>
        <a style="text-decoration:none;" href="home.php?view=discover"><div class="sidebaritem" <?php if($view == 'discover') {echo'style="background:#1c1c1c;"';} ?>>Discover</div></a>
        <a style="text-decoration:none;" href="home.php?view=exhibits"><div class="sidebaritem" <?php if($view == 'exhibits') {echo'style="background:#1c1c1c;"';} ?>>Exhibits</div></a>
        <a style="text-decoration:none;" href="home.php?view=market"><div class="sidebaritem" <?php if($view == 'market') {echo'style="background:#1c1c1c;"';} ?>>Market</div></a>
        <a style="text-decoration:none;" href="home.php?view=campaigns"><div class="sidebaritem" <?php if($view == 'campaigns') {echo'style="background:#1c1c1c;"';} ?>>Campaigns</div></a>
        <a style="text-decoration:none;" href="home.php?view=posts"><div class="sidebaritem" <?php if($view == 'posts') {echo'style="background:#1c1c1c;"';} ?>>Posts</div></a>
        
        </div>
        
        
        <div class="grid_18 push_1" style="width:900px;">
    
            <!--NEWSFEED-->
            
            <?php

            if($view == '') {

            echo'
            <div class="grid_14 push_1" id="thepics" style="margin-top:70px;">
            <div id="container">';
            
            echo'<div style="width:600px;border-left:1px solid #ccc;border-bottom:1px solid #ccc;margin-left:45px;">
                        <a style="width:70px;padding:5px;margin-left:15px;" class="btn btn-success" href="#"><p class="button_text">Upload </p><div class="grid_1" id="upload" style="margin: 0px 0 0 0;"><img src="graphics/upload_1.png" height="19"/></div></a>
                        <img style="margin-left:10px;" src="',$sessionphoto,'" width="30" />&nbsp;<input style="padding:6px;width:390px;margin-top:8px;" type="text" name="status" placeholder="Post a question or statement to your followers&#8230;"/>
                        <br /><br />
                 </div>';
                 
                 
                 //who is online
                 
    $query2 = mysql_query("SELECT * FROM userinfo");
    $numusers = mysql_num_rows($query2);
    
    for($iii=0; $iii<$numusers; $iii++) {
        
        $useremail = mysql_result($query2,$iii,'emailaddress');
        $username = mysql_result($query2,$iii,'firstname') ." ". mysql_result($query2,$iii,'lastname');
        $userpic = mysql_result($query2,$iii,'profilepic');
        $userid = mysql_result($query2,$iii,'id');
    
        if($_SESSION[$useremail] == 1) {
            echo '<a href="viewprofile.php?u=',$userid,'"><img style="padding:10px;" src="',$userpic,'" width="45" /></a> <a href="viewprofile.php?u=',$userid,'">',$username,'</a><br />';
        }
        
    }                 

            //PHOTOS QUERY
            $emailquery = mysql_query("SELECT following FROM userinfo WHERE emailaddress = '$email'");
            $followlist = mysql_result($emailquery, 0, "following");
            $newsfeedquery = mysql_query("SELECT * FROM newsfeed WHERE (owner IN ($followlist) OR emailaddress IN ($followlist) OR type = 'signup') AND emailaddress NOT IN ('$email') AND type NOT IN ('message','reply') ORDER BY id DESC LIMIT 13");
               
            for($iii=0; $iii <= 12; $iii++) {
    
                $newsemail = mysql_result($newsfeedquery,$iii,'emailaddress');    
                $owner = mysql_result($newsfeedquery,$iii,'owner');
                $uploader = mysql_result($newsfeedquery,$iii,'emailaddress');
                $time = mysql_result($newsfeedquery,$iii,'time');
                $emailfollowing = mysql_result($newsfeedquery,$iii,'following');
                $id = mysql_result($newsfeedquery,$iii,'id');
                $caption = mysql_result($newsfeedquery,$iii,'caption');
                $type = mysql_result($newsfeedquery,$iii,'type');
                $image = mysql_result($newsfeedquery,$iii,'source');
                $firstname = mysql_result($newsfeedquery,$iii,'firstname');
                $lastname = mysql_result($newsfeedquery,$iii,'lastname');
    
                if($type == "photo") {
                    $emailcheck[$uploader] = $emailcheck[$uploader] + 1;
                    $imagenew=str_replace("userphotos/","userphotos/medthumbs/", $image);
                    $ownersquery = mysql_query("SELECT user_id,firstname,lastname,profilepic FROM userinfo WHERE emailaddress = '$uploader'");
                    $ownerfirst = mysql_result($ownersquery,0,'firstname');
                    $ownerlast = mysql_result($ownersquery,0,'lastname');
                    $ownerid = mysql_result($ownersquery,0,'user_id');
                    $ownerprofilepic = mysql_result($ownersquery,0,'profilepic');
                    $ownerfull = ucwords($ownerfirst . " " . $ownerlast);
                    list($width, $height) = getimagesize($image);
                    $imgratio = $height / $width;
                    $height = $imgratio * $maxwidth;
                    $phrase = "<a href='viewprofile.php?u=" . $ownerid . "'>" . $ownerfull . "</a> uploaded " . '"' . $caption . '"';    
                    $imageinfo = mysql_query("SELECT * FROM photos WHERE source = '$image'");
                    $views = mysql_result($imageinfo,0,'views');
                    $points = mysql_result($imageinfo,0,'points');
                    $votes = mysql_result($imageinfo,0,'votes');
                    $rank = ($points / $votes);
                    $rank = number_format($rank,2);
                    $about = mysql_result($imageinfo,0,'about');
                    list($width, $height) = getimagesize($image);
                    $width = ($width / 3);
                    $height = ($height / 3);
                    
                    if($emailcheck[$uploader] > 1) {
                        $emailcheck[$uploader] = 0;
                        continue;
                    }

                    echo '<div class="grid_10 push_1 fPic" id="',$id,'" style="width:600px;border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;"> 
                        <img class="dropshadow" style="float:left;border: 1px solid white;margin-left:10px;margin-top:10px;" src="',$ownerprofilepic,'" height="50" width="50" />&nbsp;&nbsp;<div style="float:left;font-size:14px;padding:10px;width:490px;font-weight:600;">',$phrase,'';
    
                    if($time > 0) {
                        echo'
                        <br /><div style="float:left;font-size:12px;color:#777;font-weight:400;padding:2px;">',converttime($time),'</div>';
                    }
    
                    echo'
                        </div>
                        <br /><a href="fullsize.php?image=',$image,'"><img style="border:1px solid #fff;margin-left:85px;margin-bottom:15px;clear:both;" src="',$imagenew,'" width="',$width,'px" height="',$height,'px" /></a>';
                        
                    if($about) {
                        echo'
                        <br /><div style="float:left;margin-left:85px;font-size:12px;color:#777;font-weight:400;padding:2px;width:460px;">',$about,'</div>';
                    }
                        
                        echo'
                        <div style="font-size:13px;margin-left:85px;margin-bottom:10px;clear:both;">Views: ',$views,'&nbsp;|&nbsp;Rank: ',$rank,'</div>';
                        echo '</div>';  
                    }
                    
                elseif ($type == "fave") {
                    $emailcheck[$uploader] = $emailcheck[$uploader] + 1;
                    $ownersquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$uploader'");
                    $ownerfirst = mysql_result($ownersquery,0,'firstname');
                    $ownerlast = mysql_result($ownersquery,0,'lastname');
                    $ownerid = mysql_result($ownersquery,0,'user_id');
                    $ownerprofilepic = mysql_result($ownersquery,0,'profilepic');
                    $ownerfull = ucwords($ownerfirst . " " . $ownerlast);
                    $commentersquery = mysql_query("SELECT user_id,profilepic FROM userinfo WHERE emailaddress = '$uploader'");
                    $commenterpic = mysql_result($commentersquery,0,'profilepic');
                    $commenterid = mysql_result($commentersquery,0,'user_id');
                    $imagenew=str_replace("userphotos/","userphotos/medthumbs/", $image);
                    $fullname = "<a href='viewprofile.php?u=" . $commenterid . "'>" . $firstname . " " . $lastname . "</a>";
                    $phrase = $fullname . " favorited " . '"' . $caption . '"' . " by " . $ownerfull;
                    $imageinfo = mysql_query("SELECT * FROM photos WHERE source = '$image'");
                    $views = mysql_result($imageinfo,0,'views');
                    $points = mysql_result($imageinfo,0,'points');
                    $votes = mysql_result($imageinfo,0,'votes');
                    $about = mysql_result($imageinfo,0,'about');
                    $rank = ($points / $votes);
                    $rank = number_format($rank,2);
    
                    //Faves List Modal
                    $favelistquery = mysql_query("SELECT firstname,lastname,user_id FROM userinfo WHERE faves LIKE '%$image%'");
                    $numfavesinlist = mysql_num_rows($favelistquery);
                    
                    for($jjj=0; $jjj<$numfavesinlist; $jjj++) {
                        $fvname = mysql_result($favelistquery,$jjj,'firstname') . " " . mysql_result($favelistquery,$jjj,'lastname');
                        $fvid = mysql_result($favelistquery,$jjj,'user_id');
                        $fvname = '<a href="viewprofile.php?u='.$fvid.'"'.'>' . $fvname . '</a>';
                       
                        if($jjj == 0) {
                            $fvlist = $fvlist . $fvname;
                        } 
                        
                        elseif($jjj > 0) {
                            $fvlist = $fvlist . ", " .$fvname; }
                        }
    
                    list($width, $height) = getimagesize($image);
                    $width = ($width / 3);
                    $height = ($height / 3);
                    
                    if($emailcheck[$uploader] > 1) {
                        $emailcheck[$uploader] = 0;
                        continue;
                    }
    
                    echo '<div class="grid_10 push_1 fPic" id="',$id,'" style="width:600px;border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;"> 
                        <img class="dropshadow" style="float:left;border: 1px solid white;margin-left:10px;margin-top:10px;" src="',$commenterpic,'" height="50" width="50" />&nbsp;&nbsp;<div style="float:left;font-size:14px;padding:10px;width:490px;font-weight:600;">',$phrase,'';
    
                        if($time > 0) {
                            echo'
                            <br /><div style="float:left;font-size:12px;color:#777;font-weight:400;padding:2px;">',converttime($time),'</div>';
                        }
    
                        echo'
                            </div>
                            <br />
                            <a href="fullsize.php?image=',$image,'"><img style="border:1px solid #fff;margin-left:85px;margin-bottom:15px;clear:both;" src="',$imagenew,'" width="',$width,'px" height="',$height,'px" /></a>';
                            
                        if($about) {
                            echo'
                            <br /><div style="float:left;margin-left:85px;font-size:12px;color:#777;font-weight:400;padding:2px;width:460px;padding-bottom:10px;">',$about,'</div>';
                        }
                        
                        echo'
                            <div style="font-size:13px;margin-left:85px;margin-bottom:10px;clear:both;">Views: ',$views,'&nbsp;|&nbsp;Rank: ',$rank,'&nbsp;|&nbsp;Favorited By: ',$fvlist,'</div>';
                        echo '</div>';   
                        $fvlist = '';
                    }
                    
                elseif($type == "trending") {
                    $emailcheck[$owner] = $emailcheck[$owner] + 1;
                    $ownersquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$owner'");
                    $ownerfirst = mysql_result($ownersquery,0,'firstname');
                    $ownerlast = mysql_result($ownersquery,0,'lastname');
                    $ownerid = mysql_result($ownersquery,0,'user_id');
                    $ownerprofilepic = mysql_result($ownersquery,0,'profilepic');
                    $ownerfull = "<a href='viewprofile.php?u=" . $ownerid . "'>" . $ownerfirst . " " . $ownerlast . "</a>";
                    $ownerfull = ucwords($ownerfull);
                    $imagenew=str_replace("userphotos/","userphotos/medthumbs/", $image);
                    $phrase = '"' . $caption . '"' . " by " . $ownerfull . " is now trending";
                    $imageinfo = mysql_query("SELECT * FROM photos WHERE source = '$image'");
                    $views = mysql_result($imageinfo,0,'views');
                    $points = mysql_result($imageinfo,0,'points');
                    $votes = mysql_result($imageinfo,0,'votes');
                    $rank = ($points / $votes);
                    $rank = number_format($rank,2);
                    $about = mysql_result($imageinfo,0,'about');
                    list($width, $height) = getimagesize($image);
                    $width = ($width / 3);
                    $height = ($height / 3);
                    
                    if($emailcheck[$owner] > 1) {
                        $emailcheck[$owner] = 0;
                        continue;
                    }
    
                    echo '<div class="grid_10 push_1 fPic" id="',$id,'" style="width:600px;border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;"> 
                         <img class="dropshadow" style="float:left;border: 1px solid white;margin-left:10px;margin-top:10px;" src="',$ownerprofilepic,'" height="50" width="50" />&nbsp;&nbsp;<div style="float:left;font-size:14px;padding:10px;width:490px;font-weight:600;">',$phrase,'';
    
                        if($time > 0) {
                            echo'
                            <br /><span style="font-size:12px;color:#777;font-weight:400;padding:2px;">',converttime($time),'</span>';
                        }
    
                        echo'
                            </div>
                            <br /><a href="fullsize.php?image=',$image,'"><img style="border:1px solid #fff;margin-left:85px;margin-bottom:15px;clear:both;" src="',$imagenew,'" width="',$width,'px" height="',$height,'px" /></a>';
                            
                        if($about) {
                            echo'
                            <br /><div style="float:left;margin-left:85px;font-size:12px;color:#777;font-weight:400;padding:2px;width:460px;padding-bottom:10px;">',$about,'</div>';
                        }
                        
                        echo'
                            <div style="font-size:13px;margin-left:85px;margin-bottom:10px;clear:both;">Views: ',$views,'&nbsp;|&nbsp;Rank: ',$rank,'</div>';
                            echo '</div>';  
                    }
                
                elseif($type == "follow") {
                    $emailcheck[$uploader] = $emailcheck[$uploader] + 1;
                    $newaccount = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$emailfollowing'");
                    $profilepic5 = mysql_result($newaccount,0,'profilepic');
                    $ownerid = mysql_result($newaccount,0,'user_id');
                    $numownerphotosquery = mysql_query("SELECT source FROM photos WHERE emailaddress = '$emailfollowing' ORDER by (points/votes) DESC LIMIT 4");
                    $numownerphotos = mysql_num_rows($numownerphotosquery);
                    $flowimage1 = mysql_result($numownerphotosquery,0,'source');
                    $flowimage1 = str_replace("userphotos/","userphotos/medthumbs/", $flowimage1);
                    $flowimage2 = mysql_result($numownerphotosquery,1,'source');
                    $flowimage2 = str_replace("userphotos/","userphotos/medthumbs/", $flowimage2);
                    $flowimage3 = mysql_result($numownerphotosquery,2,'source');
                    $flowimage3 = str_replace("userphotos/","userphotos/medthumbs/", $flowimage3);
                    $followersquery = mysql_query("SELECT * FROM userinfo WHERE following LIKE '%$emailfollowing%'");
                    $numberfollowers = mysql_num_rows($followersquery);
    
                    for($i = 0; $i < $numownerphotos; $i++) {
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
    
                    elseif($portfoliovotes < 1) {
                        $portfolioranking="N/A";
                    }	
                    
                    if($emailcheck[$uploader] > 1) {
                        $emailcheck[$uploader] = 0;
                        continue;
                    }
    
                    $ownerfirst = mysql_result($newaccount,0,'firstname');
                    $commentersquery = mysql_query("SELECT user_id,profilepic FROM userinfo WHERE emailaddress = '$newsemail'");
                    $commenterpic = mysql_result($commentersquery,0,'profilepic');
                    $commenterid = mysql_result($commentersquery,0,'user_id');
                    $ownerlast = mysql_result($newaccount,0,'lastname');
                    $fullname = "<a href='viewprofile.php?u=" . $commenterid . "'>" . $firstname . " " . $lastname. "</a>";
                    $ownerfull = "<a href='viewprofile.php?u=" . $ownerid . "'>" . $ownerfirst . " " . $ownerlast . "'s</a>";
                    $owner = ucwords($ownerfull);
                    if($profilepic5 == "") {
                        $profilepic5 = "profilepics/default_profile.jpg";
                    }
                    $phrase = $fullname . ' is now following ' . $owner ." photography";
    
                    list($width, $height) = getimagesize($profilepic5);
                    $width = ($width / 3.5);
                    $height = ($height / 3.5);

                    echo '<div class="grid_10 push_1 fPic" id="',$id,'" style="width:600px;border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;"> 
                        <img class="dropshadow" style="float:left;border: 1px solid white;margin-left:10px;margin-top:10px;" src="',$commenterpic,'" height="50" width="50" />&nbsp;&nbsp;<div style="float:left;font-size:14px;padding:10px;width:490px;font-weight:600;">',$phrase,'';
    
                        if($time > 0) {
                            echo'
                                <br /><div style="float:left;font-size:11px;color:#777;font-weight:400;padding:2px;">',converttime($time),'</div>';
                        }           
    
                    echo'
                        </div>
                        <br />
                        <a href="viewprofile.php?u=',$ownerid,'"><img class="phototitle" style="margin-left:20px;margin-top:15px;margin-bottom:15px;" src="',$profilepic5,'" width="',$width,'px" height="',$height,'px" /></a>&nbsp;&nbsp;
                        <div class="phototitle" style="height:110px;width:320px;">';
    
                    if($numownerphotos > 2) {
                        echo'
                            <img style="border:1px solid white;" src="',$flowimage1,'" height="108" width="102" />
                            <img style="border:1px solid white;" src="',$flowimage2,'" height="108" width="102" />
                            <img style="border:1px solid white;" src="',$flowimage3,'" height="108" width="102" />';
                    }
                    
                    else {
                        echo'<div style="text-align:center;font-size:14px;margin-top:40px;">',$ownerfirst,' just joined!</div>';
                    }
    
                    echo'
                        </div>
                        <div style="font-size:13px;margin-left:85px;margin-bottom:10px;clear:both;">Photos: ',$numownerphotos,'&nbsp;|&nbsp;Portfolio Average: ',$portfolioranking,'&nbsp;|&nbsp;Followers: ',$numberfollowers,'</div>';

                    echo '</div>'; 
        
                    } 
                
             elseif ($type == "exhibitfave") {
                $emailcheck[$uploader] = $emailcheck[$uploader] + 1;
                $setinfo = mysql_query("SELECT title,cover,faves,about FROM sets WHERE id = '$image'");
                $settitle = mysql_result($setinfo,0,'title');
                $setfaves = mysql_result($setinfo,0,'faves');
                $aboutset = mysql_result($setinfo,0,'about');
                $setcover = mysql_result($setinfo,0,'cover');
                $pulltopphoto = mysql_query("SELECT source FROM photos WHERE set_id = '$image' ORDER BY votes DESC");
    
                if($setcover == '') {
                    $setcover = mysql_result($pulltopphoto, 0, "source");
                    $setcover = str_replace("userphotos/","userphotos/medthumbs/",$setcover);
                }
    
                $numsetphotos = mysql_num_rows($pulltopphoto);
                $thumb1 = mysql_result($pulltopphoto, 1, "source");
                $thumb1 = str_replace("userphotos/","userphotos/medthumbs/",$thumb1);
                $thumb2 = mysql_result($pulltopphoto, 2, "source");
                $thumb2 = str_replace("userphotos/","userphotos/medthumbs/",$thumb2);
                $thumb3 = mysql_result($pulltopphoto, 3, "source");
                $thumb3 = str_replace("userphotos/","userphotos/medthumbs/",$thumb3);
                $thumb4 =mysql_result($pulltopphoto, 4, "source");
                $thumb4 = str_replace("userphotos/","userphotos/medthumbs/",$thumb4);
                
                $ownersquery = mysql_query("SELECT user_id,profilepic FROM userinfo WHERE emailaddress = '$uploader'");
                $ownerid = mysql_result($ownersquery,0,'user_id');
                $ownerprofilepic = mysql_result($ownersquery,0,'profilepic');
                $commentersquery = mysql_query("SELECT user_id,profilepic FROM userinfo WHERE emailaddress = '$owner'");
                $commenterpic = mysql_result($commentersquery,0,'profilepic');
                $commenterid = mysql_result($commentersquery,0,'user_id');
                $fullname = "<a href='viewprofile.php?u=" . $commenterid . "'>" . $firstname . " " . $lastname. "</a>";
                $findowner = mysql_query("SELECT user_id,firstname,lastname FROM userinfo WHERE emailaddress = '$owner'");
                $ownername = mysql_result($findowner,0,'firstname') ." ". mysql_result($findowner,0,'lastname'); 
                $ownerid = mysql_result($findowner,0,'user_id');
                $ownerfull = "<a href='viewprofile.php?u=" . $ownerid . "'>" . $ownername . "'s</a>";
                $owner = ucwords($ownerfull);
                $phrase = $fullname . ' favorited ' . $owner ." exhibit: <a href='home.php?view=intex&set=" . $image . "'>".$settitle."</a>";
    
                list($width, $height) = getimagesize($setcover);
                $width = ($width / 4.5);
                $height = ($height / 4.5);
                
                if($emailcheck[$uploader] > 1) {
                        $emailcheck[$uploader] = 0;
                        continue;
                    }

                echo '<div class="grid_10 push_1 fPic" id="',$id,'" style="width:600px;border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;"> 
                    <img class="dropshadow" style="float:left;border: 1px solid white;margin-left:10px;margin-top:10px;" src="',$ownerprofilepic,'" height="50" width="50" />&nbsp;&nbsp;<div style="float:left;font-size:14px;padding:10px;width:490px;font-weight:600;">',$phrase,'';
    
                    if($time > 0) {
                        echo'
                        <br /><div style="float:left;font-size:11px;color:#777;font-weight:400;padding:2px;">',converttime($time),'</div>';
                    }
    
                echo'
                    </div>
                    <br />
                    <a href="home.php?view=intex&set=',$image,'"><img class="phototitle" style="margin-left:20px;margin-top:15px;margin-bottom:15px;" src="',$setcover,'" width="140px" /></a>&nbsp;&nbsp;
                    <div class="phototitle" style="height:110px;width:320px;">';
    
                    if($numsetphotos > 2) {
                        echo'
                            <a style="clear:both;" href="viewprofile.php?u=',$ownerid,'&view=exhibits&set=',$set,'">
                            <img style="border:1px solid white;" src="',$thumb1,'" height="108" width="102" />
                            <img style="border:1px solid white;" src="',$thumb2,'" height="108" width="102" />
                            <img style="border:1px solid white;" src="',$thumb3,'" height="108" width="102" />
                            </a>';
                    }
    
                    echo'
                    </div>';
    
                    if($aboutset) {
                        echo'
                            <div style="width:520px;clear:both;padding:20px;margin-left:65px;">
                            <span style="font-weight:400;font-size:15px;">About Exhibit:</span> <span style="font-weight:200;font-size:14px;">',$aboutset,'</span>
                        </div>';
                    }
    
                echo'
                    <div style="font-size:13px;margin-left:85px;margin-bottom:10px;margin-bottom:30px;clear:both;"># Photos: ',$numsetphotos,'&nbsp;|&nbsp;Exhibit Favorites: ',$setfaves,'&nbsp;</div>';

                echo '</div>'; 
            
                }
        
             elseif($type == "comment") {
                $emailcheck[$uploader] = $emailcheck[$uploader] + 1;
                $ownersquery =  mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$owner'");
                $ownerfirst = mysql_result($ownersquery,0,'firstname');
                $ownerlast = mysql_result($ownersquery,0,'lastname');
                $ownerid = mysql_result($ownersquery,0,'user_id');
                $ownerfull = ucwords($ownerfirst . " " . $ownerlast);
                $ownerfull = "<a href='viewprofile.php?u=" . $ownerid . "'>" . $ownerfirst . " " . $ownerlast . "</a>";
                $commentersquery = mysql_query("SELECT user_id,profilepic FROM userinfo WHERE emailaddress = '$uploader'");
                $commenterpic = mysql_result($commentersquery,0,'profilepic');
                $commenterid = mysql_result($commentersquery,0,'user_id');
                $imageinfo = mysql_query("SELECT * FROM photos WHERE source = '$image'");
                $views = mysql_result($imageinfo,0,'views');
                $points = mysql_result($imageinfo,0,'points');
                $imageID = mysql_result($imageinfo,0,'id');
                $votes = mysql_result($imageinfo,0,'votes');
                $rank = ($points / $votes);
                $rank = number_format($rank,2);
                $imagenew=str_replace("userphotos/","userphotos/medthumbs/", $image);
                $fullname = "<a href='viewprofile.php?u=" . $commenterid . "'>" . $firstname . " " . $lastname ."</a>";
                $phrase = $fullname . " commented on " . $ownerfull . "'s photo";
                list($width, $height) = getimagesize($image);
                $width = ($width / 3);
                $height = ($height / 3);
                
                if($emailcheck[$uploader] > 1) {
                    $emailcheck[$uploader] = 0;
                    continue;
                }
    
                echo'
                    <div class="grid_10 push_1 fPic" id="',$id,'" style="width:600px;border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;"> 
                    <img class="dropshadow" style="float:left;border: 1px solid white;margin-left:10px;margin-top:10px;" src="',$commenterpic,'" height="50" width="50" />&nbsp;&nbsp;<div style="float:left;font-size:14px;padding:10px;width:490px;font-weight:600;">',$phrase,'';
    
                    if($time > 0) {
                        echo'
                        <br />
                        <div style="float:left;font-size:11px;color:#777;font-weight:400;padding:2px;">',converttime($time),'</div>';
                    }
    
                    echo'
                        </div>
                        <br />
                        <a href="fullsize.php?image=',$image,'"><img style="border:1px solid #fff;margin-left:85px;margin-bottom:15px;clear:both;" src="',$imagenew,'" width="',$width,'px" height="',$height,'px" /></a>';
    
                    echo'
                        <br />
                        <br />
                        <div style="margin-left: 85px;padding:5px;width:480px;clear:both;">';
    
                        $grabphotocomments = mysql_query("SELECT * FROM comments WHERE imageid = '$imageID' ORDER BY id DESC LIMIT 0,4");
                        $numcomments = mysql_num_rows($grabphotocomments);

                        for($ii = 0; $ii < $numcomments; $ii++) {
                            $comment = mysql_result($grabphotocomments,$ii,'comment');
                            $commentid = mysql_result($grabphotocomments,$ii,'id');
                            $commenteremail = mysql_result($grabphotocomments,$ii,'commenter');
                            echo $commenter;
                            $commenterinfo = mysql_query("SELECT user_id,firstname,lastname,profilepic,reputation FROM userinfo WHERE emailaddress = '$commenteremail' LIMIT 1");
                            $commentername = mysql_result($commenterinfo,0,'firstname') ." ". mysql_result($commenterinfo,0,'lastname');
                            $commenterid2 = mysql_result($commenterinfo,0,'user_id');
                            $commenterpic2 = mysql_result($commenterinfo,0,'profilepic');
                            $commenterrep = number_format(mysql_result($commenterinfo,0,'reputation'),2);
                
                    //SHOW PREVIOUS COMMENTS
                    
                    echo'
                        <div style="width:460px;clear:both;margin-top:10px;">
                        <a href="viewprofile.php?u=',$commenterid,'">
                        <div style="float:left;"><img class="roundedall" src="',$commenterpic2,'" height="40" width="35"/></a></div>
           
                        <div style="float:left;padding-left:6px;width:410px;">
               
                        <div style="float:left;color:#3e608c;font-size:14px;font-family:helvetica;font-weight:500;border-bottom: 1px solid #ccc;width:410px;"><div style="float:left;"><a href="viewprofile.php?u=',$commenterid2,'">',$commentername,'</a> &nbsp;<span style="font-size:16px;font-weight:100;color:black;margin-top:2">|</span>&nbsp;<span style="color:#333;font-size:12px;">Rep: ',$commenterrep,'</span></div>&nbsp;&nbsp;&nbsp;
                   
                        <div class="progress progress-success" style="float:left;width:110px;height:7px;opacity:.8;margin:7px;">
                        <div class="bar" style="width:',$commenterrep,'%;">
                        </div></div>
                   
                        </div>
                        <div style="float:left;width:370px;padding:10px;font-size:13px;font-family:helvetica;font-weight:300;color:#555;">',$comment,'</div>
                        
                        </div>
                        </div>';
            
                    }
                    
                    echo'</div>
                    </div>';
                    
            } //end of type comments 
            
            
            elseif($type == "exhibitcomment") {

            
            
            }
            
            
            elseif($type == "signup") {
                $ownersquery =  mysql_query("SELECT user_id,profilepic FROM userinfo WHERE emailaddress = '$uploader'");
                $ownerid = mysql_result($ownersquery,0,'user_id');
                $ownerpic = mysql_result($ownersquery,0,'profilepic');
                $fullname = "<a href='viewprofile.php?u=" . $ownerid . "'>" . $firstname . " " . $lastname ."</a>";
                $phrase = $fullname . " joined PhotoRankr";       
                
                        echo '<div class="grid_10 push_1 fPic" id="',$id,'" style="width:600px;border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;"> 
                        <img class="dropshadow" style="float:left;border: 1px solid white;margin-left:10px;margin-top:10px;" src="',$ownerpic,'" height="50" width="50" />&nbsp;&nbsp;<div style="float:left;font-size:14px;padding:10px;width:490px;font-weight:600;">',$phrase,'';
    
                        if($time > 0) {
                            echo'
                                <br /><div style="float:left;font-size:11px;color:#777;font-weight:400;padding:2px;">',converttime($time),'</div>';
                        } 
                        
                        echo'<br /><br />
                            </div>
                            </div>';
            }
            
            
            elseif($type == "blogpost") {

            
            
            }
            
            
            elseif($type == "blogreply") {

            
            
            }
            
            
            elseif($type == "sold") {

            
            
            }
            
            elseif($type == "newcampaign") {

            
            
            }
            
            elseif($type == "collection") {

            
            
            }
            
            elseif($type == "status") {

            
            
            }
            
            elseif($type == "discover") {

            
            
            }
            
            elseif($type == "group") {

            
            
            }
            
    
    } //end of for loop            
            echo'</div>
                 </div>';
           
            
            //Right sidebar
            echo'<div class="grid_7 push_5 rightbar" style="margin-top:90px;">
                
                    <div class="innerbox">
                        <img class="rightbarphoto" src="http://photorankr.com/',$sessionphoto,'" height="50" width="50" />
                        <span class="infoword">',$sessionfirst,' ',$sessionlast,'</span>
                        <hr class="thinline" />
                    </div>
                    
                    <div class="rightbarsmallbox verticalborder">',$numphotos,'<br /><span class="smalltext">Photos</span></div>
                    <div class="rightbarsmallbox verticalborder">',$numberfollowers,'<br /><span class="smalltext">Followers</span></div>
                    <div class="rightbarsmallbox">',$numberfollowing,'<br /><span class="smalltext">Following</span></div>                    
                    </div>';
           
            //Bottom right sidebar suggested photographers
            
            echo'<div class="grid_7 push_5 rightbar" style="margin-top:20px;">
            
                    <div class="innerbox" style="margin-top:5px;margin-left:5px;">
                        <span class="medtext">Suggested Photographers</span>
                        <hr class="thinline" style="margin-left:-5px;margin-top:5px;" />
                    </div>';
                    
                    //Suggested photographers
                    
                    //PHOTOGRAPHERS THEY MIGHT LIKE
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
                        $displayquery = "SELECT firstname, lastname, profilepic,user_id FROM userinfo WHERE emailaddress IN($followinglist) AND emailaddress NOT IN('$email',                           $followinglistowner, 'support@photorankr.com') ORDER BY RAND() LIMIT 4";		
                        $displayresult = mysql_query($displayquery) or die(mysql_error());
                        $numdisplayresult = mysql_num_rows($displayresult);
                    }
	
                    //loop through the people, printing out their name and profile picture
                    //echo'<span style="font-size:16px;font-weight:100;padding:15px;">Photographers to Follow:</span>';
        
                    for($iii=0; $iii < 4; $iii++) {
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
             
                            $numownerphotosquery = mysql_query("SELECT * FROM photos WHERE emailaddress = '$profileemail'");
                            $numownerphotos = mysql_num_rows($numownerphotosquery);
            
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
            
                            echo '<div style="width:270px;">
                                <a style="text-decoration:none;" href="viewprofile.php?u=',$profileid,'">
                                <img class="dropshadow" style="border: 1px solid white;margin-left:8px;margin-top:-8px;" src="',$profilepic,'" height="40" width="40"/>
                                <a id="suggestedtext" style="padding-top:15px;padding-left:10px;" href="viewprofile.php?u=',$profileid,'">',$name,'</a>
                                <br />
                                <div style="font-size:12px;font-weight:300;margin-bottom:5px;clear:both;padding:6px;">Photos: ',$numownerphotos,'&nbsp;|&nbsp;Average: ',$portfolioranking,'&nbsp;|&nbsp;Followers: ',$numberfollowers,'</div>
                            <hr></a></div>';
                        }
            
                echo'</div>';
                
                
                //Bottom right sidebar trending photographers
            
            echo'<div class="grid_7 push_5 rightbar" style="margin-top:20px;">
            
                    <div class="innerbox" style="margin-top:5px;margin-left:5px;">
                        <span class="medtext">Trending Photos Now</span>
                        <hr class="thinline" style="margin-left:-5px;margin-top:5px;" />
                    </div>';
                    
                    $trendingnow = mysql_query("SELECT * FROM photos ORDER BY score DESC LIMIT 0,4");
                    
                    for($iii=0; $iii<4; $iii++) {
                        $trendingimage = mysql_result($trendingnow, $iii, 'source');
                        $trendingimage2 = str_replace("userphotos/","userphotos/medthumbs/", $trendingimage);
                        $caption = mysql_result($trendingnow,$iii,'caption');
                        $views = mysql_result($trendingnow,$iii,'views');
                        $points = mysql_result($trendingnow,$iii,'points');
                        $votes = mysql_result($trendingnow,$iii,'votes');
                        $about = mysql_result($trendingnow,$iii,'about');
                        $imageid = mysql_result($trendingnow,$iii,'id');
                        $rank = ($points / $votes);
                        $rank = number_format($rank,2);
                                
                        list($width, $height) = getimagesize($trendingimage);
                        $width = ($width / 5.5);
                        $height = ($height / 5.5);
			
                        echo'
                            <div style="width:245px;padding:10px;"><a style="text-decoration:none;" href="fullsize.php?imageid=',$imageid,'">
                            <img style="max-width:275px;border: 2px solid white;" src="',$trendingimage2,'" width="',$width,'px" height="',$height,'px" />
                            <br /><br />
                            <span style="font-size:15px;"><a style="padding-top:20px;" href="fullsize.php?imageid=',$imageid,'">"',$caption,'"</a></span>
                            <br />
                            <div style="font-size:13px;margin-bottom:10px;clear:both;">Views: ',$views,'&nbsp;|&nbsp;Rank: ',$rank,'
                            <br /><br />',$about,'</div>
                            <hr></a></div>';            
                        
                        }
                    
                    echo'</div>';
                
                
                //Bottom right sidebar suggested photos
            
            echo'<div class="grid_7 push_5 rightbar" style="margin-top:20px;">
            
                    <div class="innerbox" style="margin-top:5px;margin-left:5px;">
                        <span class="medtext">Suggested Photography</span>
                        <hr class="thinline" style="margin-left:-5px;margin-top:5px;" />
                    </div>';
                    
                    //Suggested photographers
                    
//find out all of the photos they have ever favorited
	$favesquery = "SELECT faves from userinfo WHERE emailaddress='$email' LIMIT 1";
	$favesresult = mysql_query($favesquery);
	$faveslistowner = mysql_result($favesresult, 0, "faves");
	
	//select all the photos they have ever favorited
	$favesquery = "SELECT maintags, singlecategorytags, singlestyletags FROM photos WHERE source IN($faveslistowner)";
	$favesresult = mysql_query($favesquery);
	$favesnumber = mysql_num_rows($favesresult);

	//if they actually had favorites
	if($favesnumber != 0) {	
		//loop through the results to create a variable which holds all tags for all the photos they have ever favorited
		$favetags = "";
		for($iii=0; $iii < $favesnumber; $iii++) {
			$favetags .= mysql_result($favesresult, $iii, "maintags");
			$favetags .= mysql_result($favesresult, $iii, "singlecategorytags");
			$favetags .= mysql_result($favesresult, $iii, "singlestyletags");		
		}	

		//now select all of the photos with similar tags that weren't favorited by them
		$favesquery = "SELECT source, MATCH(maintags, singlecategorytags, singlestyletags) AGAINST ('$favetags') AS matching FROM photos WHERE MATCH(maintags, singlecategorytags, singlestyletags) AGAINST ('$favetags') AND source NOT IN($faveslistowner) ORDER BY RAND() LIMIT 4";
		$favesresult = mysql_query($favesquery);
	}
	//otherwise they had no faves so pick four random photos
	else {	
		$favesquery = "SELECT source FROM photos ORDER BY RAND() LIMIT 4";
		$favesresult = mysql_query($favesquery);
	}

	//see if they had enough information to display photos
	if(mysql_num_rows($favesresult) >= 4) {
		for($iii=0; $iii < 4; $iii++) {
        $image = mysql_result($favesresult, $iii, "source");
        $image2 = str_replace("userphotos/","userphotos/medthumbs/", $image);
        list($width, $height) = getimagesize($image);
        $imgratio = $height / $width;
        $heightls = $height / 4;
        $widthls = $width / 4;
        $query7 =  mysql_query("SELECT caption FROM photos WHERE source = '$image'");
        $captionarray = mysql_fetch_array($query7);
        $caption = $captionarray['caption'];
        
        $imageinfo = mysql_query("SELECT * FROM photos WHERE source = '$image'");
        $views = mysql_result($imageinfo,0,'views');
        $points = mysql_result($imageinfo,0,'points');
        $votes = mysql_result($imageinfo,0,'votes');
        $about = mysql_result($imageinfo,0,'about');
        $imageid = mysql_result($imageinfo,0,'id');
        $rank = ($points / $votes);
        $rank = number_format($rank,2);
        
        list($width, $height) = getimagesize($image);
        $width = ($width / 5.5);
        $height = ($height / 5.5);
			
            echo'
                <div style="width:245px;padding:10px;"><a style="text-decoration:none;" href="fullsize.php?imageid=',$imageid,'">
                <img style="max-width:275px;border: 2px solid white;" src="',$image2,'"  width="',$width,'px" height="',$height,'px" />
                <br /><br />
                <span style="font-size:15px;"><a style="padding-top:20px;" href="fullsize.php?imageid=',$image,'">"',$caption,'"</a></span>
                <br />
                <div style="font-size:13px;margin-bottom:10px;clear:both;">Views: ',$views,'&nbsp;|&nbsp;Rank: ',$rank,'
                <br /><br />',$about,'</div>
                <hr></a></div>';            
            }
            
                echo'</div>';
        }   
                
    }
        
    if($view == 'newest') {
    
            if($cat == '') {
                
                    $cat = htmlentities($_GET['c']);

                echo'            
                    <!-- Select Basic -->
                    <form action="newest.php" method="get">';
                    
                        echo'
                        <select name="c"  onchange="submitTime(this)" class="input-large" style="width:140px;margin-top:-33px;margin-left:525px;">
                        
                        <option value=""'; if($cat == '') {echo'selected value=""';} echo'>All Photos</option>
                        
                        <option value="aerial"'; if($cat == 'aerial') {echo'selected value=""';} echo'>Aerial</option>
                        
                        <option value="animal"'; if($cat == 'animal') {echo'selected value=""';} echo'>Animal</option>
                        
                        <option value="architecture"'; if($cat == 'architecture') {echo'selected value=""';} echo'>Architecture</option>
                        
                        <option value="astro"'; if($cat == 'astro') {echo'selected value=""';} echo'>Astro</option>
                        
                        <option value="automotive"'; if($cat == 'automotive') {echo'selected value=""';} echo'>Automotive</option>
                        
                        <option value="bw"'; if($cat == 'bw') {echo'selected value=""';} echo'>Black & White</option>
                        
                        <option value="cityscape"'; if($cat == 'cityscape') {echo'selected value=""';} echo'>Cityscape</option>
                        
                        <option value="fashion"'; if($cat == 'fashion') {echo'selected value=""';} echo'>Fashion</option>
                        
                        <option value="fineart"'; if($cat == 'fineart') {echo'selected value=""';} echo'>Fine Art</option>
                        
                        <option value="fisheye"'; if($cat == 'fisheye') {echo'selected value=""';} echo'>Fish Eye</option>
                        
                        <option value="food"'; if($cat == 'food') {echo'selected value=""';} echo'>Food</option>
                        
                        <option value="HDR"'; if($cat == 'HDR') {echo'selected value=""';} echo'>HDR</option>
                        
                        <option value="historical"'; if($cat == 'historical') {echo'selected value=""';} echo'>Historical</option>
                        
                        <option value="industrial"'; if($cat == 'industrial') {echo'selected value=""';} echo'>Industrial</option>
                        
                        <option value="landscape"'; if($cat == 'landscape') {echo'selected value=""';} echo'>Landscape</option>
                        
                        <option value="longexposure"'; if($cat == 'longexposure') {echo'selected value=""';} echo'>Long Exposure</option>
                        
                        <option value="macro"'; if($cat == 'macro') {echo'selected value=""';} echo'>Macro</option>
                        
                        <option value="monochrome"'; if($cat == 'monochrome') {echo'selected value=""';} echo'>Monochrome</option>
                        
                        <option value="nature"'; if($cat == 'nature') {echo'selected value=""';} echo'>Nature</option>
                        
                        <option value="news"'; if($cat == 'news') {echo'selected value=""';} echo'>News</option>
                        
                        <option value="night"'; if($cat == 'night') {echo'selected value=""';} echo'>Night</option>
                        
                        <option value="panorama"'; if($cat == 'panorama') {echo'selected value=""';} echo'>Panorama</option>
                        
                        <option value="people"'; if($cat == 'people') {echo'selected value=""';} echo'>People</option>
                        
                        <option value="scenic"'; if($cat == 'scenic') {echo'selected value=""';} echo'>Scenic</option>
                        
                        <option value="sports"'; if($cat == 'sports') {echo'selected value=""';} echo'>Sports</option>
                        
                        <option value="stilllife"'; if($cat == 'stilllife') {echo'selected value=""';} echo'>Still Life</option>
                        
                        <option value="transportation"'; if($cat == 'transportation') {echo'selected value=""';} echo'>Transportation</option>
                        
                        <option value="war"'; if($cat == 'war') {echo'selected value=""';} echo'>War</option>
                        
                        </select>';

                    echo'    
                    </form>';
                } 
        
                
    //DISPLAY 20 NEWEST OF ALL PHOTOS
        
    if($cat == '') {
        $result = mysql_query("SELECT * FROM photos ORDER BY id DESC LIMIT 0, 16");
    }
    
    elseif($cat == 'aerial') {
        $result = mysql_query("SELECT * FROM photos WHERE singlecategorytags LIKE '%Aerial%' ORDER BY id DESC LIMIT 0, 16");
    }
    
    elseif($cat == 'animal') {
        $result = mysql_query("SELECT * FROM photos WHERE singlecategorytags LIKE '%Animal%' ORDER BY id DESC LIMIT 0, 16");
    }
    
    elseif($cat == 'architecture') {
        $result = mysql_query("SELECT * FROM photos WHERE singlecategorytags LIKE '%Architecture%' ORDER BY id DESC LIMIT 0, 16");
    }
    
    elseif($cat == 'astro') {
        $result = mysql_query("SELECT * FROM photos WHERE singlecategorytags LIKE '%Astro%' ORDER BY id DESC LIMIT 0, 16");
    }
    
    elseif($cat == 'automotive') {
        $result = mysql_query("SELECT * FROM photos WHERE singlecategorytags LIKE '%Automotive%' ORDER BY id DESC LIMIT 0, 16");
    }
    
    elseif($cat == 'bw') {
        $result = mysql_query("SELECT * FROM photos WHERE singlestyletags LIKE '%B&W%' ORDER BY id DESC LIMIT 0, 16");
    }
    
    elseif($cat == 'cityscape') {
        $result = mysql_query("SELECT * FROM photos WHERE singlestyletags LIKE '%cityscape%' ORDER BY id DESC LIMIT 0, 16");
    }
    
     elseif($cat == 'fashion') {
        $result = mysql_query("SELECT * FROM photos WHERE singlecategorytags LIKE '%Fashion%' ORDER BY id DESC LIMIT 0, 16");
    }
    
    
     elseif($cat == 'fineart') {
        $result = mysql_query("SELECT * FROM photos WHERE singlecategorytags LIKE '%Fine Art%' ORDER BY id DESC LIMIT 0, 16");
    }
    
    elseif($cat == 'fisheye') {
        $result = mysql_query("SELECT * FROM photos WHERE singlestyletags LIKE '%Fisheye%' ORDER BY id DESC LIMIT 0, 16");
    }
    
     elseif($cat == 'food') {
        $result = mysql_query("SELECT * FROM photos WHERE singlecategorytags LIKE '%Food%' ORDER BY id DESC LIMIT 0, 16");
    }
    
    elseif($cat == 'HDR') {
        $result = mysql_query("SELECT * FROM photos WHERE singlestyletags LIKE '%HDR%' ORDER BY id DESC LIMIT 0, 16");
    }
    
     elseif($cat == 'historical') {
        $result = mysql_query("SELECT * FROM photos WHERE singlecategorytags LIKE '%Historical%' ORDER BY id DESC LIMIT 0, 16");
    }
    
     elseif($cat == 'industrial') {
        $result = mysql_query("SELECT * FROM photos WHERE singlecategorytags LIKE '%Industrial%' ORDER BY id DESC LIMIT 0, 16");
    }
    
    elseif($cat == 'landscape') {
        $result = mysql_query("SELECT * FROM photos WHERE singlestyletags LIKE '%Landscape%' ORDER BY id DESC LIMIT 0, 16");
    }
    
    elseif($cat == 'longexposure') {
        $result = mysql_query("SELECT * FROM photos WHERE singlestyletags LIKE '%Long Exposure%' ORDER BY id DESC LIMIT 0, 16");
    }
    
    elseif($cat == 'macro') {
        $result = mysql_query("SELECT * FROM photos WHERE singlestyletags LIKE '%Macro%' ORDER BY id DESC LIMIT 0, 16");
    }
    
    elseif($cat == 'monochrome') {
        $result = mysql_query("SELECT * FROM photos WHERE singlestyletags LIKE '%Monochrome%' ORDER BY id DESC LIMIT 0, 16");
    }
    
     elseif($cat == 'nature') {
        $result = mysql_query("SELECT * FROM photos WHERE singlecategorytags LIKE '%Nature%' ORDER BY id DESC LIMIT 0, 16");
    }
    
    elseif($cat == 'news') {
        $result = mysql_query("SELECT * FROM photos WHERE singlecategorytags LIKE '%News%' ORDER BY id DESC LIMIT 0, 16");
    }
    
    elseif($cat == 'night') {
        $result = mysql_query("SELECT * FROM photos WHERE singlestyletags LIKE '%Night%' ORDER BY id DESC LIMIT 0, 16");
    }
    
    elseif($cat == 'panorama') {
        $result = mysql_query("SELECT * FROM photos WHERE singlestyletags LIKE '%Panorama%' ORDER BY id DESC LIMIT 0, 16");
    }
    
    elseif($cat == 'people') {
        $result = mysql_query("SELECT * FROM photos WHERE singlecategorytags LIKE '%People%' ORDER BY id DESC LIMIT 0, 16");
    }
    
    elseif($cat == 'scenic') {
        $result = mysql_query("SELECT * FROM photos WHERE singlecategorytags LIKE '%Scenic%' ORDER BY id DESC LIMIT 0, 16");
    }
    
    elseif($cat == 'sports') {
        $result = mysql_query("SELECT * FROM photos WHERE singlecategorytags LIKE '%Sports%' ORDER BY id DESC LIMIT 0, 16");
    }
    
    elseif($cat == 'stilllife') {
        $result = mysql_query("SELECT * FROM photos WHERE singlecategorytags LIKE '%Still Life%' ORDER BY id DESC LIMIT 0, 16");
    }
    
    elseif($cat == 'transportation') {
        $result = mysql_query("SELECT * FROM photos WHERE singlecategorytags LIKE '%Transportation%' ORDER BY id DESC LIMIT 0, 16");
    }
    
    elseif($cat == 'war') {
        $result = mysql_query("SELECT * FROM photos WHERE singlecategorytags LIKE '%War%' ORDER BY id DESC LIMIT 0, 16");
    }
    
    $numberofpics=mysql_num_rows($result);
    
    echo'
    <div id="thepics" style="position:relative;top:15px;width:1100px;margin-left:-15px;">
    <div id="main" role="main">
    <ul id="tiles">';
    
for($iii=1; $iii <= 16; $iii++) {
	$image = mysql_result($result, $iii-1, "source");
    $imageThumb=str_replace("userphotos/","userphotos/medthumbs/", $image);
	$id = mysql_result($result, $iii-1, "id");
    $caption = mysql_result($result, $iii-1, "caption");
     $caption = (strlen($caption) > 28) ? substr($caption,0,25). " &#8230;" : $caption;
    $points = mysql_result($result, $iii-1, "points");
    $price = mysql_result($result, $iii-1, "price");
    if($price != 'Not For Sale') {
        $price = '$' . $price;
    }
    elseif(!$price || $price == 'Not For Sale') {
        $price = 'NFS';
    }
    elseif($price == '.00') {
        $price = 'Free';
    }
    $votes = mysql_result($result, $iii-1, "votes");
    $score = number_format(($points/$votes),2);
    $owner = mysql_result($result, $iii-1, "emailaddress");
    $ownerquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$owner'");
    $firstname = mysql_result($ownerquery, 0, "firstname");
    $lastname = mysql_result($ownerquery, 0, "lastname");
    $fullname = $firstname . " " . $lastname;

	list($width, $height) = getimagesize($image);
	$imgratio = $height / $width;
    $heightls = $height / 3.5;
    $widthls = $width / 3.5;
    if($widthls < 275) {
    $heightls = $heightls * ($heightls/$widthls);
    $widthls = 300;
    }

		echo '
        <a style="text-decoration:none;color:#333;" href="fullsize.php?imageid=',$id,'&v=n"><li class="fPic" id="',$id,'" style="padding:5px;margin-right:10px;margin-top:10px;list-style-type: none;width:300px;
"><img src="http://photorankr.com/',$image,'" height="',$heightls,'px" width="',$widthls,'px" /><div style="padding:3px;"><div style="float:left;">',$caption,'</div><div style=float:right;font-size:13px;font-weight:500;">',$price,'</div></div></li></a>';
       
	    
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
        itemWidth: 330 // Optional, the width of a grid item
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
</div>

<!--AJAX CODE HERE-->
   <div class="grid_6 push_9" style="padding-top:50px;">
   <div id="loadMorePics" style="display: none; text-align: center;font-family:arial,helvetica neue; font-size:15px;">Loading More Photos&hellip;</div>
   </div>';


echo '<script>

var last = 0;

	$(window).scroll(function(){
		if($(window).scrollTop() > $(document).height() - $(window).height()-100) {
			if(last != $(".fPic:last").attr("id")) {
				$("div#loadMorePics").show();
				$.ajax({
					url: "loadMoreNewPicsTest.php?lastPicture=" + $(".fPic:last").attr("id")+"&c=',$cat,'",
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

} //end of view == 'newest'


    if($view == 'top') {

        if(isset($_GET['t'])){
            $timesetting = $_GET['t'];
        }

        echo'<br /><br /><br /><br />
        <div style="margin-left:0px;font-size:15px;font-weight:200;font-family:"Helvetica Neue",Helvetica,Arial;">
        
        <a class="pxbutton" style="text-decoration:none;margin-right:15px;';if($view == '') {echo'padding:10px;-moz-border-radius: 5px;-webkit-border-radius: 5px;border-radius: 5px;background-color:#000;color:#fff;opacity:.9;';} else {echo'';} echo'" href="topranked.php">Top Photos</a> 
        
        <a class="pxbutton" style="text-decoration:none;margin-right:15px;';if($view == 'prs') {echo'padding:10px;-moz-border-radius: 5px;-webkit-border-radius: 5px;border-radius: 5px;background-color:#000;color:#fff;opacity:.9;';} else {echo'';} echo'" href="topranked.php?v=prs">Top Photographers</a>
        
         <a class="pxbutton" style="text-decoration:none;margin-right:15px;';if($view == 'ex') {echo'padding:10px;-moz-border-radius: 5px;-webkit-border-radius: 5px;border-radius: 5px;background-color:#000;color:#fff;opacity:.9;';} else {echo'';} echo'" href="topranked.php?v=ex">Top Exhibits</a>
        
        </div>
        
                <script>
                    function submitTime(sel) {
                        sel.form.submit();
                    }
                </script>';
                
                if($category == '') {
                echo'            
                    <!-- Select Basic -->
                    <label class="control-label"></label>
                    <div class="controls">
                    <form action="topranked.php" method="get">';
                    
                    if($timesetting == '') {
                        echo'
                        <select name="t"  onchange="submitTime(this)" class="input-large" style="width:110px;margin-left:340px;margin-top:-25px;">
                        <option value="" selected value="">All Time</option>
                        <option value="m">This Month</option>
                        <option value="w">This Week</option>
                        </select>';
                     }   
                     elseif($timesetting == 'm') {
                        echo'
                        <select name="t"  onchange="submitTime(this)" class="input-large" style="width:110px;margin-left:420px;margin-top:-35px;">
                        <option value="">All Time</option>
                        <option value="m" selected value="" >This Month</option>
                        <option value="w">This Week</option>
                        </select>';
                     }  
                     elseif($timesetting == 'w') {
                        echo'
                        <select name="t"  onchange="submitTime(this)" class="input-large" style="width:110px;margin-left:420px;margin-top:-35px;">
                        <option value="">All Time</option>
                        <option value="m" >This Month</option>
                        <option value="w" selected value="" >This Week</option>
                        </select>';
                     }  
                    
                    echo'    
                    </form>
                    </div>';
                }    
        

if ($category=='') {

//Time setting is set to all time

if ($timesetting == '') {
    $query="SELECT * FROM photos ORDER BY points DESC LIMIT 0, 21";
    $result=mysql_query($query);
}

elseif ($timesetting == 'm') {
    $lowertimebound = time() - 2419900;
    $query="SELECT * FROM photos WHERE time > '$lowertimebound' ORDER BY points DESC LIMIT 0, 21";
    $result=mysql_query($query);
}

elseif ($timesetting == 'w') {
    $lowertimebound = time() - 604800;
    $query="SELECT * FROM photos WHERE time > '$lowertimebound' ORDER BY points DESC LIMIT 0, 21";
    $result=mysql_query($query);
}

$numberofpics=mysql_num_rows($result);

    echo'
    <div id="thepics" style="position:relative;top:15px;width:1100px;margin-left:-15px;">
    <div id="main" role="main">
    <ul id="tiles">';

for($iii=1; $iii <= 20; $iii++) {
$image = mysql_result($result, $iii-1, "source");
$imageThumb = str_replace("userphotos/","userphotos/medthumbs/", $image);
$caption = mysql_result($result, $iii-1, "caption");
$id = mysql_result($result, $iii-1, "id");
$price = mysql_result($result, $iii-1, "price");
$points = mysql_result($result, $iii-1, "points");
$emailaddress = mysql_result($result, $iii-1, "emailaddress");
$namequery="SELECT * FROM userinfo WHERE emailaddress='$emailaddress'";
$nameresult=mysql_query($namequery);
$row=mysql_fetch_array($nameresult);
$firstname=$row['firstname'];
$lastname=$row['lastname'];
$fullname = $firstname . " " . $lastname;
$fullname = ucwords($fullname);
	
    list($width, $height) = getimagesize($image);
	$imgratio = $height / $width;
    $heightls = $height / 3.5;
    $widthls = $width / 3.5;
    
    if($widthls < 275) {
        $heightls = $heightls * ($heightls/$widthls);
        $widthls = 300;
    }
		echo '
        <a style="text-decoration:none;color:#333;" href="fullsize.php?imageid=',$id,'&v=r"><li class="fPic" id="',$id,'" style="padding:5px;margin-right:10px;margin-top:10px;list-style-type: none;width:300px;
"><img src="http://photorankr.com/',$imageThumb,'" height="',$heightls,'px" width="',$widthls,'px" /><div style="padding:3px;"><div style="float:left;">',$caption,'</div><div style=float:right;font-size:13px;font-weight:500;">$',$price,'</div></div></li></a>';
       

        
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
        itemWidth: 330 // Optional, the width of a grid item
      };
      
      // Get a reference to your grid items.
      var handler = $('#tiles li');
      
      // Call the layout function.
      handler.wookmark(options);
      
    });
  </script>
  
<?php  
    
if ($timesetting == '') {

echo'
<!--AJAX CODE HERE-->
   <div class="grid_6 push_9" style="padding-top:50px;">
   <div id="loadMorePics" style="display: none; text-align: center;font-family:arial,helvetica neue; font-size:15px;">Loading More Photos&hellip;</div>
   </div>';


echo '<script>

var last = 0;

	$(window).scroll(function(){
		if($(window).scrollTop() > $(document).height() - $(window).height()-100) {
			if(last != $(".fPic:last").attr("id")) {
				$("div#loadMorePics").show();
				$.ajax({
					url: "loadMoreTopRanked.php?lastPicture=" + $(".fPic:last").attr("id"),
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

} //end of if
    
} //end of all time if clause  


elseif ($category == 'prs') {
//TOP 20 PHOTOGRAPHERS



//get number of photographers with score greater than 700
$query = "SELECT * FROM userinfo WHERE totalscore > 700 AND emailaddress NOT IN ('msniff16@gmail.com','sniff06@aol.com')";
$queryresult = mysql_query($query);
$numresult = mysql_num_rows($queryresult);


//nested for loop get photos from an individual user
for($iii=0; $iii < $numresult; $iii++) {
$owner = mysql_result($queryresult, $iii, "emailaddress");
$tpoints = mysql_result($queryresult, $iii, "totalpoints");
$photocheck = "SELECT * FROM photos WHERE emailaddress = '$owner' ORDER BY (points/votes) DESC";
$photocheckrun = mysql_query($photocheck);
$numphotos = mysql_num_rows($photocheckrun);

//select and calculate score for users with number of photos greater than 16
for($ii=0; $ii < 15; $ii++) {
$singlescore = mysql_result($photocheckrun, $ii, "points");
$votes = mysql_result($photocheckrun, $ii, "votes");
$totalpoints += $singlescore;
$totalvotes += $votes;
    }
    
    $finalaverage = ($totalpoints/$totalvotes);
    
    $averagearray[$iii] =  $finalaverage;
    $emailaddressarray[$iii] = $owner;

} 

//end of for where totalscore > 700

for($i = 0; $i < sizeof($averagearray); $i++){
array_multisort($averagearray,$emailaddressarray);
}


echo'<div id="container" style="width:1140px;position:relative;left:-70px;top:20px;">';
    for($iii=1; $iii <= 20; $iii++) {
    $newquery = "SELECT * FROM userinfo WHERE emailaddress = '$emailaddressarray[$iii]'";
$firstname = mysql_result($queryresult, $iii-1, "firstname");
$user_id = mysql_result($queryresult, $iii-1, "user_id");
$lastname = mysql_result($queryresult, $iii-1, "lastname");
$fullname = $firstname . " " . $lastname;
$fullname = ucwords($fullname);
$profilepic = mysql_result($queryresult, $iii-1, "profilepic");
if($profilepic == 'http://www.photorankr.com/profilepics/default_profile.jpg'){
$profilepic = 'profilepics/default_profile.jpg';
}

echo '<div class="fPic" id="',$id,'" style="float:left;margin-right:20px;margin-top:20px;width:260px;height:260px;overflow:hidden;"><a style="text-decoration:none;" href="viewprofile.php?u=',$user_id,'">
        
        <div class="statoverlay" style="z-index:1;left:0px;top:215px;position:relative;background-color:black;width:260px;height:70px;"><p style="line-spacing:1.48;padding:5px;color:white;"><span style="font-family:helvetica neue,arial;font-weight:100;font-size:22px;">#',$iii,'&nbsp;&nbsp;',$fullname,'</span></div>
        
        <img onmousedown="return false" oncontextmenu="return false;" style="position:relative;top:-75px;min-height:280px;min-width:260px;" src="',$profilepic,'" alt="',$fullname,'" height="',$heightls,'px" width="',$widthls,'px" /></a></div>';
        
    } //end of for loop
echo'</div>'; 
        
} //end of elseif clause


elseif($category == 'ex') {

echo'<div id="container" style="width:1140px;position:relative;left:-70px;top:20px;">';
    for($iii=1; $count < 20; $iii++) {
    $exquery = "SELECT * FROM sets ORDER BY avgscore DESC";
    $exqueryrun = mysql_query($exquery);
    $owner = mysql_result($exqueryrun, $iii-1, "owner");

$exinfo = "SELECT * FROM userinfo WHERE emailaddress = '$owner'"; 
$exinforun = mysql_query($exinfo);
$firstname = mysql_result($exinforun, 0, "firstname");
$lastname = mysql_result($exinforun, 0, "lastname");
$fullname = $firstname . " " . $lastname;
$fullname = ucwords($fullname);
$user_id = mysql_result($exinforun, 0, "user_id");
$exhibit_id = mysql_result($exqueryrun, $iii-1, "id");
$caption = mysql_result($exqueryrun, $iii-1, "title");
$caption = (strlen($caption) > 24) ? substr($caption,0,21). " &#8230;" : $caption;
$coverpic = mysql_result($exqueryrun, $iii-1, "cover");
if($coverpic == '') {
    continue;
    }
   $count += 1; 

    echo'
    <div class="fPic" id="',$id,'" style="float:left;margin-right:20px;margin-top:20px;width:260px;height:260px;overflow:hidden;"><a style="text-decoration:none;" href="viewprofile.php?u=',$user_id,'&view=exhibits&set=',$exhibit_id,'">
        
        <div class="statoverlay" style="z-index:1;left:0px;top:205px;position:relative;background-color:black;width:260px;height:70px;"><p style="line-spacing:1.48;padding:5px;color:white;"><span style="font-family:helvetica neue,arial;font-weight:100;font-size:18px;">#',$count,'&nbsp;&nbsp;',$caption,'</span><br/><span style="font-family:helvetica,arial;font-weight:100;font-size:12px;">By: ',$fullname,'</p></div>
        
        <img onmousedown="return false" oncontextmenu="return false;" style="position:relative;top:-75px;min-height:280px;min-width:260px;" src="http://photorankr.com/',$coverpic,'" alt="',$caption,'" height="',$heightls,'px" width="',$widthls,'px" /></a></div>';
    
        
    } //end of for loop
    
echo'</div>'; 

        
} //end of elseif clause

    } //end of view == 'top'
    
    
    elseif($view == 'exhibits') {
        $result = mysql_query("SELECT * FROM sets ORDER BY id DESC LIMIT 0,16");
        $numberexhibits=mysql_num_rows($result);

        echo'
            <div id="thepics" style="position:relative;margin-left:25px;top:45px;width:1000px;">
            <div id="main" role="main">
            <ul id="tiles">';
    
        for($iii=1; $iii <= $numberexhibits; $iii++) {
            $coverpic = mysql_result($result, $iii-1, "cover");
            $coverpic2 = str_replace("userphotos/","userphotos/medthumbs/",$coverpic);
            $caption = mysql_result($result, $iii-1, "title");
            $set_id = mysql_result($result, $iii-1, "id");
            $pulltopphoto = mysql_query("SELECT source FROM photos WHERE set_id = '$set_id' ORDER BY votes DESC LIMIT 5");

            if($coverpic == '') {
                $coverpic = mysql_result($pulltopphoto, 0, "source");
                $coverpic2 = str_replace("userphotos/","userphotos/medthumbs/",$coverpic);
            }

        $thumb1 = mysql_result($pulltopphoto, 1, "source");
        $thumb1 = str_replace("userphotos/","userphotos/medthumbs/",$thumb1);
        $thumb2 = mysql_result($pulltopphoto, 2, "source");
        $thumb2 = str_replace("userphotos/","userphotos/medthumbs/",$thumb2);
        $thumb3 = mysql_result($pulltopphoto, 3, "source");
        $thumb3 = str_replace("userphotos/","userphotos/medthumbs/",$thumb3);
        $thumb4 =mysql_result($pulltopphoto, 4, "source");
        $thumb4 = str_replace("userphotos/","userphotos/medthumbs/",$thumb4);

        list($width, $height) = getimagesize($coverpic);
        $imgratio = $height / $width;
        $heightls = $height / 3.2;
        $widthls = $width / 3.2;
        
        if($widthls < 220) {
            $heightls = $heightls * ($heightls/$widthls);
            $widthls = 230;
        }

        $owner = mysql_result($result, $iii-1, "owner");
        $exhibitquery = mysql_query("SELECT * FROM photos WHERE set_id = '$set_id'");
        $numberphotos = mysql_num_rows($exhibitquery);
    
        if($numberphotos < 1) {
            continue;
        }
   
        for($i = 0; $i < $numberphotos; $i++) {
            $points += mysql_result($exhibitquery, $i, "points");
            $votes += mysql_result($exhibitquery, $i, "votes");
        }
    
        $score = number_format(($points/$votes),2);
        $price = mysql_result($exhibitquery, $iii, "price");
        if($price != 'Not For Sale') {
            $price = '$' . $price;
        }
        elseif($price == 'Not For Sale') {
            $price = 'NFS';
        }
    
        $avgscorequery = mysql_query("UPDATE sets SET avgscore = '$score' WHERE id = '$set_id'");
    
        $ownerquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$owner'");
        $firstname = mysql_result($ownerquery, 0, "firstname");
        $lastname = mysql_result($ownerquery, 0, "lastname");
        $fullname = $firstname . " " . $lastname;
        $userid = mysql_result($ownerquery, 0, "user_id");
    
        echo'
            <li style="width:230px;list-style-type:none;position:relative;" class="fPic" id="',$set_id,'"><a style="text-decoration:none;" href="?view=intex&set=',$set_id,'">
    
            <div style="padding-top:5px;padding-left:3px;font-size:13px;text-decoration:none;color:#000;font-weight:200;"><span style="font-size:15px;font-weight:400;">',$caption,'</span><br />',$numberphotos,' Photos</div>
            <hr />
        
            <img style="margin-top:-6px;" onmousedown="return false" oncontextmenu="return false;" src="http://www.photorankr.com/',$coverpic2,'" alt="',$setname[$iii],'" height="',$heightls,'px" width="',$widthls,'px" />';
    
                
            echo'
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
        itemWidth: 240 // Optional, the width of a grid item
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

<!--AJAX CODE HERE-->
   <div class="grid_13 push_9" style="padding-top:50px;">
   <div id="loadMorePics" style="display: none; text-align: center;font-family:arial,helvetica neue; font-size:15px;">Loading More Exhibits&hellip;</div>
   </div>';


echo '<script>

var last = 0;

	$(window).scroll(function(){
		if($(window).scrollTop() > $(document).height() - $(window).height()-100) {
			if(last != $(".fPic:last").attr("id")) {
				$("div#loadMorePics").show();
				$.ajax({
					url: "loadMoreNewExhibits.php?lastPicture=" + $(".fPic:last").attr("id"),
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



} //end of view == 'exts'
    
    
    elseif($view == 'intex') {
        $exview = htmlentities($_GET['exview']);
        $setinfo = mysql_query("SELECT * FROM sets WHERE id = '$set'");
        $settitle = mysql_result($setinfo,0,'title');
        $settitle = (strlen($settitle) > 50) ? substr($settitle,0,47). " &#8230;" : $settitle;
        $setabout = mysql_result($setinfo,0,'about');
        $setowner = mysql_result($setinfo,0,'owner');
        $personinfo = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$setowner'");
        $owner = mysql_result($personinfo,0,'firstname') ." ". mysql_result($personinfo,0,'lastname');
        $ownerpic = mysql_result($personinfo,0,profilepic);
        $setphotos = mysql_query("SELECT * FROM photos WHERE set_id = '$set' ORDER BY points/votes DESC");
        $numsetphotos = mysql_num_rows($setphotos);
        
        echo'<div class="intexbox">';
            
            for($iii=0; $iii < 5; $iii++) {
                $setimage = mysql_result($setphotos,$iii,'source');
                $setimagethumb = str_replace("userphotos/","userphotos/medthumbs/", $setimage);
                list($width, $height) = getimagesize($setimage);
                $imgratio = $height / $width;
                $heightls = $height / 3;
                $widthls = $width / 3;
                
                echo'<div class="exhibitbox"><img src="',$setimagethumb,'" width="',$widthls,'px" height="',$widthls,'px" /></div>';
            
            }
        
        echo'    
             </div>
             <img class="setpic" src="',$ownerpic,'" width="100" />
             
             <div class="topbar">
                <div class="exhibittext">',$settitle,'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
                <ul id="navlist">
                    <li '; if($exview == '') {echo'style="background-color:#ccc;"';} echo'><a style="color:#333;" href="?view=intex&set=',$set,'">Photos</a></li>
                    <li '; if($exview == 'about') {echo'style="background-color:#ccc;"';} echo'><a style="color:#333;" href="?view=intex&set=',$set,'&exview=about">About</a></li>
                    <li '; if($exview == 'favorites') {echo'style="float:left;background-color:#ccc;"';} echo'><a style="color:#333;" href="?view=intex&set=',$set,'&exview=favorites">Favorites</a></li>
                    <li style="border-right:1px solid #ccc;'; if($exview == 'comments') {echo'background-color:#ccc;';} echo'"><a style="color:#333;" href="?view=intex&set=',$set,'&exview=comments">Comments</a></li>
                </ul>
             </div>';
             
             
             if($exview == '') {
             
                echo'
                    <div id="thepics" style="position:relative;width:960px;margin-left:22px;clear:both;">
                    <div id="main" role="main">
                    <ul id="tiles">';
             
                for($iii=0; $iii<$numsetphotos; $iii++) {
                    $setphotossort = mysql_query("SELECT * FROM photos WHERE set_id = '$set' ORDER BY id DESC");
                    $photo = mysql_result($setphotossort,$iii,'source');
                    $photothumb =  str_replace("userphotos/","userphotos/medthumbs/", $photo);
                    $photoid = mysql_result($setphotos,$iii,'id');
                    list($width, $height) = getimagesize($photo);
                    $heightls = $height / 3.2;
                    $widthls = $width / 3.2;
                    if($widthls < 225) {
                        $heightls = $heightls * ($heightls/$widthls);
                        $widthls = 235;
                    }
                
                    echo'<li style="list-style-type:none;width:225px;">
                        <a style="text-decoration:none;" href="fullsizeview.php?image=',$photoid,'"><img onmousedown="return false" oncontextmenu="return false;"  src="',$photothumb,'" alt="',$caption,'" height="',$heightls,'px" width="',$widthls,'px" /></a>
                        <div style="padding:3px;"><div style="float:left;">',$caption,'</div><div style=float:right;font-size:13px;font-weight:500;">',$price,'</div><br /><i class="icon-heart"></i>&nbsp;',$faves,' favorites</div>';
                            
                }
                
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
                    itemWidth: 235 // Optional, the width of a grid item
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
             
             }
             
             elseif($exview == 'about') {
                
                echo'<div style="width:960px;margin-left:25px;margin-top:20px;font-size:14px;color#333;font-weight:400;">
                <span style="font-weight:600;font-size:15px;">About:</span>
                ',$setabout,'</div>';
             
             }
             
             elseif($exview == 'favorites') {
                
                $favoriters = mysql_query("SELECT user_id,firstname,lastname,profilepic FROM userinfo WHERE exhibitfaves LIKE '%$set%'");
                $numfavoriters = mysql_num_rows($favoriters);
                
                echo'<div style="width:960px;margin-left:25px;margin-top:20px;font-size:14px;color#333;font-weight:400;">
                <div style="font-weight:600;font-size:15px;">Photographers who favorited this exhibit:</div>';
                
                for($iii=0; $iii < $numfavoriters; $iii++) {
                
                    $id = mysql_result($favoriters,0,user_id);
                    $firstname = mysql_result($favoriters,0,'firstname');
                    $lastname = mysql_result($favoriters,0,'lastname');
                    $profilepic = mysql_result($favoriters,0,'profilepic');

                    echo'<div><a href="viewprofile.php?u=',$id,'"><img class="favoriter" src="',$profilepic,'" height="100" /></a></div>';
                    
                }
                
                echo'
                </div>';
             
             }
    
    }

?>
        
        </div><!--end of 24 grid-->

	</div><!--end of container-->

</body>
</html>