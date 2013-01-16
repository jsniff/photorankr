<?php 

require("db_connection.php");
require("timefunction.php");

//GET VIEW 
$view = htmlentities($_GET['view']);

//GET FOLLOWERS 
$email = $_GET['email'];
$emailquery=("SELECT following,groups,profilepic FROM userinfo WHERE emailaddress ='$email'");
$followresult=mysql_query($emailquery);
$sessionpic = mysql_result($followresult,0,'profilepic');
$followlist=mysql_result($followresult, 0, "following");
$groupslist = mysql_result($followresult, 0, "groups");
$groupslist = substr($groupslist,0,-1);
$followrow=mysql_fetch_array($followresult);
$following=$followrow['following'];

if($_GET['lastPicture']) {
	$newsfeedquery = "SELECT * FROM newsfeed WHERE id < ".$_GET['lastPicture']." AND (owner IN ($followlist) OR emailaddress IN ($followlist) OR group_id IN ($groupslist)) AND emailaddress NOT IN ('$email') AND type NOT IN ('message','reply') ORDER BY id DESC LIMIT 0,20";
    $newsfeedresult = mysql_query($newsfeedquery);
    $numresults = mysql_num_rows($newsfeedresult);
    
echo'<div style="margin-top:0px;">';

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
        var imageid = '<? echo $imageID; ?>';
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
        var imageid = '<? echo $imageID; ?>';
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
                         <a href="fullsize.php?imageID=',id,'"><img style="max-height:500px;" src="https://photorankr.com/',$source,'" /></a>
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
                                
                                echo'<a href="fullsize.php?imageid=',$photoid,'"><div style="float:left;height:215px;max-width:215px;padding:3px;"><img style="height:195px;" src="https://photorankr.com/',$medphoto,'" width="',$widthls,'" /></div></a>';
                            }
                            echo'</div>';
                       }
                        echo'
                        <div style="width:400px;padding:25px;font-size:14px;font-weight:300;">
                            <img style="width:18px;padding:4px;margin-top:-4px;" src="graphics/groups_b.png"> ',$comment,'
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
                         <a href="fullsize.php?imageID=',id,'"><img style="max-height:500px;" src="https://photorankr.com/',$source,'" /></a>
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
      
} //end for loop
 
} //end of if statement
?>

</div> 