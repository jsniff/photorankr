<?php 

require("db_connection.php");
    
//GET FOLLOWERS 
$email = $_GET['email'];
$emailquery=("SELECT following FROM userinfo WHERE emailaddress ='$email'");
$followresult=mysql_query($emailquery);
$followlist=mysql_result($followresult, 0, "following");
$followrow=mysql_fetch_array($followresult);
$following=$followrow['following'];

if($_GET['lastPicture']) {
	$newsfeedquery = "SELECT * FROM newsfeed WHERE id < ".$_GET['lastPicture']." AND (owner IN ($followlist) OR emailaddress IN ($followlist)) AND emailaddress NOT IN ('$email') ORDER BY id DESC";
    $newsfeedresult = mysql_query($newsfeedquery);

$maxwidth = 400;

$view=htmlentities($_GET['view']);

for($iii=1; $iii <= 30; $iii++) {
    $newsrow = mysql_fetch_array($newsfeedresult);
    $newsemail = $newsrow['emailaddress'];    
    $owner = $newsrow['owner'];
    $emailfollowing = $newsrow['following'];
    $id = $newsrow['id'];
    $type = $newsrow['type'];
    
if($view == '') {

    if ($type == "campaign") {
    $photoid = $newsrow['source'];
	$caption = $newsrow['caption'];
    
    $quotequery = mysql_query("SELECT quote,views FROM campaigns WHERE id = '$photoid'");
    $quote = mysql_result($quotequery,0,'quote');
    $views = mysql_result($quotequery,0,'views');
    $phrase = 'New Campaign: (Reward: $'. $quote .') "'. $caption . '"';
    
    $campphotosquery = mysql_query("SELECT source FROM campaignphotos WHERE campaign = '$photoid' ORDER by (points/votes) DESC");
    $numcampphotos = mysql_num_rows($campphotosquery);
    $flowimage1 = mysql_result($campphotosquery,0,'source');
    $flowimage1=str_replace("userphotos/","campaign/userphotos/medthumbs/", $flowimage1);
    $flowimage2 = mysql_result($campphotosquery,1,'source');
    $flowimage2=str_replace("userphotos/","campaign/userphotos/medthumbs/", $flowimage2);
    $flowimage3 = mysql_result($campphotosquery,2,'source');
    $flowimage3=str_replace("userphotos/","campaign/userphotos/medthumbs/", $flowimage3);
    
    echo '<div class="grid_10 push_1 fPic" id="',$id,'" style="width:600px;border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;"> 
    <img class="dropshadow" style="border: 1px solid white;margin-left:10px;margin-top:10px;" src="graphics/newsfeedcampaignicon.png" height="60" width="60" />&nbsp;&nbsp;<span style="font-size:15px;">',$phrase,'</span>
    <br />
    <div class="phototitle" style="margin-left:85px;height:150px;width:450px;"><a style="text-decoration:none;" href="campaignphotos.php?id=',$photoid,'">';
    
    if($numcampphotos > 2) {
    echo'
    <img style="border:1px solid white;" src="',$flowimage1,'" height="148" width="145" />
    <img style="border:1px solid white;" src="',$flowimage2,'" height="148" width="145" />
    <img style="border:1px solid white;" src="',$flowimage3,'" height="148" width="145" />';
    }
    else {
    echo'<div style="text-align:center;font-size:14px;margin-top:40px;">Less than 3 entries to this campaign so far</div>';
    }
    
    echo'
    </a>
    </div>
    <div style="font-size:13px;margin-left:85px;margin-bottom:10px;clear:both;">Views: ',$views,'&nbsp;|&nbsp;Entries: ',$numcampphotos,'</div>';

    echo '</div>';
    }
                     
    elseif ($type == "photo") {
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
    $phrase = '<a href="viewprofile.php?u=",$ownerid,"">' . $ownerfull . "</a> uploaded " . '"' . $caption . '"';
    $phrase2 = (strlen($phrase) > 90) ? substr($phrase,0,87). " &#8230;" : $phrase;
    
    $imageinfo = mysql_query("SELECT * FROM photos WHERE source = '$image'");
    $views = mysql_result($imageinfo,0,'views');
    $points = mysql_result($imageinfo,0,'points');
    $votes = mysql_result($imageinfo,0,'votes');
    $rank = ($points / $votes);
    $rank = number_format($rank,2);
    
    list($width, $height) = getimagesize($image);
    $width = ($width / 2.5);
    $height = ($height / 2.5);

    echo '<div class="grid_10 push_1 fPic" id="',$id,'" style="width:600px;border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;"> 
    <img class="dropshadow" style="border: 1px solid white;margin-left:10px;margin-top:10px;" src="',$ownerprofilepic,'" height="60" width="60" />&nbsp;&nbsp;<span style="font-size:15px;">',$phrase2,'</span>
    <br /><a href="fullsize.php?image=',$image,'"><img class="phototitle" style="margin-left:85px;margin-bottom:15px;clear:both;" src="',$imagenew,'" width="',$width,'px" height="',$height,'px" /></a>
    <div style="font-size:13px;margin-left:85px;margin-bottom:10px;clear:both;">Views: ',$views,'&nbsp;|&nbsp;Rank: ',$rank,'</div>';
    echo '</div>';  
	}
    
    elseif ($type == "fave") {
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
    $phrase = $fullname . " favorited " . '"' . $caption . '"' . " by " . $ownerfull;
    
    $imageinfo = mysql_query("SELECT * FROM photos WHERE source = '$image'");
    $views = mysql_result($imageinfo,0,'views');
    $points = mysql_result($imageinfo,0,'points');
    $votes = mysql_result($imageinfo,0,'votes');
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
    $width = ($width / 2.5);
    $height = ($height / 2.5);
    
    echo '<div class="grid_10 push_1 fPic" id="',$id,'" style="width:600px;border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;"> 
    <img class="dropshadow" style="border: 1px solid white;margin-left:10px;margin-top:10px;" src="',$commenterpic,'" height="60" width="60" />&nbsp;&nbsp;<span style="font-size:15px;">',$phrase,'</span>
    <br /><a href="fullsize.php?image=',$image,'"><img class="phototitle" style="margin-left:85px;margin-bottom:15px;clear:both;" src="',$imagenew,'" width="',$width,'px" height="',$height,'px" /></a>
    <div style="font-size:13px;margin-left:85px;margin-bottom:10px;clear:both;">Views: ',$views,'&nbsp;|&nbsp;Rank: ',$rank,'&nbsp;|&nbsp;Favorited By: ',$fvlist,'</div>';
    echo '</div>'; 
    
      $fvlist = '';  
    }
    
    elseif ($type == "trending") {
    $owner = $newsrow['owner'];
    $ownersquery = "SELECT * FROM userinfo WHERE emailaddress = '$owner'";
    $ownerresult = mysql_query($ownersquery); 
    $ownerrow = mysql_fetch_array($ownerresult);
    $ownerfirst = $ownerrow['firstname'];
    $ownerlast = $ownerrow['lastname'];
    $ownerprofilepic = $ownerrow['profilepic'];
    $ownerid = $ownerrow['user_id'];
    $ownerfull = "<a href='viewprofile.php?u=" . $ownerid . "'>" . $ownerfirst . " " . $ownerlast . "</a>";
    $ownerfull = ucwords($ownerfull);
    $image = $newsrow['source'];
    $imagenew=str_replace("userphotos/","userphotos/medthumbs/", $image);
	$caption = $newsrow['caption'];
	list($width, $height) = getimagesize($image);
	$imgratio = $height / $width;
	$height = $imgratio * $maxwidth;
    $phrase = '"' . $caption . '"' . " by " . $ownerfull . " is now trending";
    
    $imageinfo = mysql_query("SELECT * FROM photos WHERE source = '$image'");
    $views = mysql_result($imageinfo,0,'views');
    $points = mysql_result($imageinfo,0,'points');
    $votes = mysql_result($imageinfo,0,'votes');
    $rank = ($points / $votes);
    $rank = number_format($rank,2);
    
    list($width, $height) = getimagesize($image);
    $width = ($width / 2.5);
    $height = ($height / 2.5);
    
     echo '<div class="grid_10 push_1 fPic" id="',$id,'" style="width:600px;border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;"> 
    <img class="dropshadow" style="border: 1px solid white;margin-left:10px;margin-top:10px;" src="',$ownerprofilepic,'" height="60" width="60" />&nbsp;&nbsp;<span style="font-size:15px;">',$phrase,'</span>
    <br /><a href="fullsize.php?image=',$image,'"><img class="phototitle" style="margin-left:85px;margin-bottom:15px;clear:both;" src="',$imagenew,'" width="',$width,'px" height="',$height,'px" /></a>
    <div style="font-size:13px;margin-left:85px;margin-bottom:10px;clear:both;">Views: ',$views,'&nbsp;|&nbsp;Rank: ',$rank,'</div>';
    echo '</div>';  
    }
    
    elseif ($type == "blogpost") {
    $owner = $newsrow['emailaddress'];
    $ownersquery = "SELECT * FROM userinfo WHERE emailaddress = '$owner'";
    $ownerresult = mysql_query($ownersquery); 
    $ownerrow = mysql_fetch_array($ownerresult);
    $ownerfirst = $ownerrow['firstname'];
    $ownerlast = $ownerrow['lastname'];
    $ownerprofilepic = $ownerrow['profilepic'];
    $ownerid = $ownerrow['user_id'];
    $ownerfull = "<a href='viewprofile.php?u=" . $ownerid . "'>" . $ownerfirst . " " . $ownerlast . "</a>";
    $ownerfull = ucwords($ownerfull);
    $blogid = $newsrow['source'];
    $phrase = $ownerfull . " wrote a new blog post";
    
    $bloginfo = mysql_query("SELECT * FROM blog WHERE id = '$blogid' AND emailaddress = '$owner'");
    $title = mysql_result($bloginfo,0,'title');
    $subject = mysql_result($bloginfo,0,'subject');
    $content = mysql_result($bloginfo,0,'content');
    $content = (strlen($content) > 700) ? substr($content,0,697). " &#8230;" : $content;

    $photo = mysql_result($bloginfo,0,'photo');
    $time = mysql_result($bloginfo,0,'time');
    if($time) {
    $date = date("m-d-Y", $time); }
    
    $profileshotquery = mysql_query("SELECT profilepic FROM userinfo WHERE emailaddress = '$email'");
    $profileshot = mysql_result($profileshotquery,0,'profilepic');
    
     echo '<div class="grid_10 push_1 fPic" id="',$id,'" style="width:600px;border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;"> 
    <img class="dropshadow" style="border: 1px solid white;margin-left:10px;margin-top:10px;" src="',$ownerprofilepic,'" height="60" width="60" />&nbsp;&nbsp;<span style="font-size:15px;">',$phrase,'</span>
    <br /><div style="margin-left:85px;margin-bottom:15px;clear:both;">

            <a href="viewprofile.php?u=',$ownerid,'&view=blog#',$blogid,'">';
            if($photo) {
            echo'<div style="float:left;padding:20px;width:130px;height:130px;"><img src="',$photo,'" height="100" width="100" /></div>
            <div style="text-decoration:underline;color:black;float:left;font-size:20px;font-weight:200;padding-top:30px;width:300px;">',$title,'</div></a>
            <div style="float:left;font-size:15px;font-weight:200;padding-top:15px;">Subject: ',$subject,'&nbsp;|&nbsp;Date: ',$date,'</div>
                       
            <div style="float:left;margin-top:0px;width:450px;padding-left:20px;font-size:15px;font-weight:200;line-height:1.48;">',$content,'<br /><br />
            </div><br />';
            }
            else {
            echo'
            <div style="text-decoration:underline;color:black;float:left;font-size:20px;font-weight:200;padding-top:30px;width:450px;">',$title,'</div></a>
            <div style="float:left;font-size:15px;font-weight:200;padding-top:15px;">Subject: ',$subject,'&nbsp;|&nbsp;Date: ',$date,'</div>
                       
            <div style="float:left;margin-top:0px;width:450px;padding:20px;font-size:15px;font-weight:200;line-height:1.48;">',$content,'<br /><br />
            </div><br />';
            }
            
            
            echo'
                    <div style="float:left;margin-top:15px;margin-left:20px;width:650px;padding:10px;font-size:15px;font-weight:200;line-height:1.48;">
                    <div class="panelblog',$blogid,'">';
                    
                        //Comment Loop
                        $commentquery= mysql_query("SELECT * FROM blogcomments WHERE blogid = '$blogid'");
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
                    <form action="newsfeed3.php?action=comment&blogid=',$blogid,'" method="POST">
                    <div style="width:450px;"><img style="float:left;padding:10px;" src="',$profileshot,'" height="30" width="30" />
                    <input style="float:left;height:20px;position:relative;top:10px;width:380px;" type="text" name="comment" placeholder="Leave a comment&#8230;" /></div>
                    </form>
                    <br /><br />
                    </div>
                    
                    
                    <a name="',$blogid,'" href="#"><p class="flipblog',$blogid,'" style="font-size:15px;"></a>',$numcomments,' Comments</p>
                    </div>
                     </div>
                     
                    <style type="text/css">
                    p.flipblog',$blogid,' {
                    margin-left:-10px;
                    padding:10px;
                    width:470px;
                    text-align:center;
                    background:white;
                    border:solid 1px #c3c3c3;
                    }

                    p.flipblog',$blogid,':hover {
                    background-color: #ccc;
                    }

                    div.panelblog',$blogid,' {
                    display:none;
                    margin:-10px;
                    width:460px;
                    padding:15px;
                    text-align:left;
                    background:white;
                    border:solid 1px #c3c3c3;
                    }
                    </style>';
                    
                    ?>
                    
                    <!--HIDDEN COMMENT SCRIPT-->
                    <script type="text/javascript">   
                    $(document).ready(function(){
                    $(".flipblog<?php echo $blogid; ?>").click(function(){
                        $(".panelblog<?php echo $blogid; ?>").slideToggle("slow");
                    });
                    });
                    </script>
                    
                    <?php
    
    echo'
    </div></a>';  
    }

    
    elseif ($type == "follow") {
    $email4 = $newsrow['following'];
    $newaccount = "SELECT * FROM userinfo WHERE emailaddress = '$email4'";
    $accountresult = mysql_query($newaccount); 
    $accountrow = mysql_fetch_array($accountresult);
    $profilepic5 = $accountrow['profilepic'];
    $ownerid = $accountrow['user_id'];
    
    $numownerphotosquery = mysql_query("SELECT * FROM photos WHERE emailaddress = '$email4' ORDER by (points/votes) DESC");
    $numownerphotos = mysql_num_rows($numownerphotosquery);
    $flowimage1 = mysql_result($numownerphotosquery,0,'source');
    $flowimage1=str_replace("userphotos/","userphotos/medthumbs/", $flowimage1);
    $flowimage2 = mysql_result($numownerphotosquery,1,'source');
    $flowimage2=str_replace("userphotos/","userphotos/medthumbs/", $flowimage2);
    $flowimage3 = mysql_result($numownerphotosquery,2,'source');
    $flowimage3=str_replace("userphotos/","userphotos/medthumbs/", $flowimage3);
    
    $followersquery="SELECT * FROM userinfo WHERE following LIKE '%$email4%'";
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

    echo '<div class="grid_10 push_1 fPic" id="',$id,'" style="width:600px;border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;"> 
    <img class="dropshadow" style="border: 1px solid white;margin-left:10px;margin-top:10px;" src="',$commenterpic,'" height="60" width="60" />&nbsp;&nbsp;<span style="font-size:15px;">',$phrase,'</span>
    <br /><a href="viewprofile.php?u=',$ownerid,'"><img class="phototitle" style="margin-left:85px;margin-bottom:15px;" src="',$profilepic5,'" width="',$width,'px" height="',$height,'px" /></a>&nbsp;&nbsp;
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
   
    $imageinfo = mysql_query("SELECT * FROM photos WHERE source = '$image'");
    $views = mysql_result($imageinfo,0,'views');
    $points = mysql_result($imageinfo,0,'points');
    $imageID = mysql_result($imageinfo,0,'id');
    $votes = mysql_result($imageinfo,0,'votes');
    $rank = ($points / $votes);
    $rank = number_format($rank,2);
    
    $imagenew=str_replace("userphotos/","userphotos/medthumbs/", $image);
    $fullname = "<a href='viewprofile.php?u=" . $commenterid . "'>" . $firstname . " " . $lastname ."</a>";
    list($width, $height) = getimagesize($image);
	$imgratio = $height / $width;
	$height = $imgratio * $maxwidth;
    $phrase = $fullname . " commented on " . $ownerfull . "'s photo";
    
    list($width, $height) = getimagesize($image);
    $width = ($width / 2.5);
    $height = ($height / 2.5);
    
    echo '<div class="grid_10 push_1 fPic" id="',$id,'" style="width:600px;border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;"> 
    <img class="dropshadow" style="border: 1px solid white;margin-left:10px;margin-top:10px;" src="',$commenterpic,'" height="60" width="60" />&nbsp;&nbsp;<span style="font-size:15px;">',$phrase,'</span>
    <br /><a href="fullsize.php?image=',$image,'"><img class="phototitle" style="margin-left:85px;margin-bottom:15px;clear:both;" src="',$imagenew,'" width="',$width,'px" height="',$height,'px" /></a>';
    
	echo '<br /><br /><div style="margin-left: 85px;padding:15px;width:480px;clear:both;">
    
    <div class="panel',$id,'">';
    
   $grabcomments = mysql_query("SELECT * FROM comments WHERE imageid = '$imageID' ORDER BY id DESC");
        $numcomments = mysql_num_rows($grabcomments);
        
        for($iii = 0; $iii < $numcomments; $iii++) {
        
            $comment = mysql_result($grabcomments,$iii,'comment');
            $commentid = mysql_result($grabcomments,$iii,'id');
            $commenteremail = mysql_result($grabcomments,$iii,'commenter');
            $commenterinfo = mysql_query("SELECT user_id,firstname,lastname,profilepic,reputation FROM userinfo WHERE emailaddress = '$commenteremail'");
            $commentername = mysql_result($commenterinfo,0,'firstname') ." ". mysql_result($commenterinfo,0,'lastname');
            $commenterid = mysql_result($commenterinfo,0,'user_id');
            $commenterpic = mysql_result($commenterinfo,0,'profilepic');
            $commenterrep = number_format(mysql_result($commenterinfo,0,'reputation'),2);
        
        //SHOW PREVIOUS COMMENTS
        echo'
            <div style="width:460px;clear:both;margin-top:10px;">
            <a href="viewprofile.php?u=',$commenterid,'"><div style="float:left;"><img class="roundedall" src="',$commenterpic,'" height="40" width="35"/></a></div>
           
             <div style="float:left;padding-left:6px;width:410px;">
               
                 <div style="float:left;color:#3e608c;font-size:14px;font-family:helvetica;font-weight:500;border-bottom: 1px solid #ccc;width:410px;"><div style="float:left;"><a href="viewprofile.php?u=',$commenterid,'">',$commentername,'</a> &nbsp;<span style="font-size:16px;font-weight:100;color:black;margin-top:2">|</span>&nbsp;<span style="color:#333;font-size:12px;">Rep: ',$commenterrep,'</span></div>&nbsp;&nbsp;&nbsp;
                   
                     <div class="progress progress-success" style="float:left;width:110px;height:7px;opacity:.8;margin:7px;">
                    <div class="bar" style="width:',$commenterrep,'%;">
                    </div></div>
                   
                </div>
                <div style="float:left;width:370px;padding:10px;font-size:13px;font-family:helvetica;font-weight:300;color:#555;">',$comment,'</div>
            </div>
            </div>';
            
        }
        
        
        $imagenew=str_replace("userphotos/","", $image);
        $imagelink=str_replace(" ","", $image);
        $searchchars=array('.jpg','.png','.tiff','.JPG','.jpeg','.JPEG','.gif');
        $imagenew=str_replace($searchchars,"", $imagenew);
        $txt=".txt";
        $file = "comments/" . $imagenew . $txt;
        echo'
        <div style="width:460px;">';
        @include("$file"); 

        echo'</div>
        <br /><br /><br />';

    echo'
    </div>
    
    <p class="flip',$id,'" style="font-size:15px;">View comment thread</p>';
	echo '</div>';
    
    echo'
    <br />
    <div style="font-size:13px;margin-left:85px;margin-bottom:10px;clear:both;">Views: ',$views,'&nbsp;|&nbsp;Rank: ',$rank,'</div>';
    echo '</div>
    
          <style type="text/css">
                    p.flip',$id,' {
                    margin-left:-10px;
                    padding:10px;
                    width:470px;
                    text-align:center;
                    background:white;
                    border:solid 1px #c3c3c3;
                    }

                    p.flip',$id,':hover {
                    background-color: #ccc;
                    }

                    div.panel',$id,' {
                    display:none;
                    margin:-10px;
                    width:460px;
                    padding:15px;
                    text-align:left;
                    background:white;
                    border:solid 1px #c3c3c3;
                    }
                    </style>';
                    
                    ?>
                    
                    <!--HIDDEN COMMENT SCRIPT-->
                    <script type="text/javascript">   
                    $(document).ready(function(){
                    $(".flip<?php echo $id; ?>").click(function(){
                        $(".panel<?php echo $id; ?>").slideToggle("slow");
                    });
                    });
                    </script>
                    
                <?php
    }
    
    } //end view == ''    
    
 
//UPLOAD VIEW
elseif($view == 'up') {
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
    $phrase = '<a href="viewprofile.php?u=",$ownerid,"">' . $ownerfull . "</a> uploaded " . '"' . $caption . '"';
    $phrase2 = (strlen($phrase) > 90) ? substr($phrase,0,87). " &#8230;" : $phrase;
    
    $imageinfo = mysql_query("SELECT * FROM photos WHERE source = '$image'");
    $views = mysql_result($imageinfo,0,'views');
    $points = mysql_result($imageinfo,0,'points');
    $votes = mysql_result($imageinfo,0,'votes');
    $rank = ($points / $votes);
    $rank = number_format($rank,2);
    
    list($width, $height) = getimagesize($image);
    $width = ($width / 2.5);
    $height = ($height / 2.5);

    echo '<div class="grid_10 push_1 fPic" id="',$id,'" style="width:600px;border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;"> 
    <img class="dropshadow" style="border: 1px solid white;margin-left:10px;margin-top:10px;" src="',$ownerprofilepic,'" height="60" width="60" />&nbsp;&nbsp;<span style="font-size:15px;">',$phrase2,'</span>
    <br /><a href="fullsize.php?image=',$image,'"><img class="phototitle" style="margin-left:85px;margin-bottom:15px;clear:both;" src="',$imagenew,'" width="',$width,'px" height="',$height,'px" /></a>
    <div style="font-size:13px;margin-left:85px;margin-bottom:10px;clear:both;">Views: ',$views,'&nbsp;|&nbsp;Rank: ',$rank,'</div>';
    echo '</div>';  
	}
} //end view == 'up'  



//TRENDING VIEW
elseif($view == 'tr') {
if ($type == "trending") {     
    $owner = $newsrow['owner'];
    $ownersquery = "SELECT * FROM userinfo WHERE emailaddress = '$owner'";
    $ownerresult = mysql_query($ownersquery); 
    $ownerrow = mysql_fetch_array($ownerresult);
    $ownerfirst = $ownerrow['firstname'];
    $ownerlast = $ownerrow['lastname'];
    $ownerprofilepic = $ownerrow['profilepic'];
    $ownerid = $ownerrow['user_id'];
    $ownerfull = "<a href='viewprofile.php?u=" . $ownerid . "'>" . $ownerfirst . " " . $ownerlast . "</a>";
    $ownerfull = ucwords($ownerfull);
    $image = $newsrow['source'];
    $imagenew=str_replace("userphotos/","userphotos/medthumbs/", $image);
	$caption = $newsrow['caption'];
	list($width, $height) = getimagesize($image);
	$imgratio = $height / $width;
	$height = $imgratio * $maxwidth;
    $phrase = '"' . $caption . '"' . " by " . $ownerfull . " is now trending";
    
    $imageinfo = mysql_query("SELECT * FROM photos WHERE source = '$image'");
    $views = mysql_result($imageinfo,0,'views');
    $points = mysql_result($imageinfo,0,'points');
    $votes = mysql_result($imageinfo,0,'votes');
    $rank = ($points / $votes);
    $rank = number_format($rank,2);
    
    list($width, $height) = getimagesize($image);
    $width = ($width / 2.5);
    $height = ($height / 2.5);
    
     echo '<div class="grid_10 push_1 fPic" id="',$id,'" style="width:600px;border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;"> 
    <img class="dropshadow" style="border: 1px solid white;margin-left:10px;margin-top:10px;" src="',$ownerprofilepic,'" height="60" width="60" />&nbsp;&nbsp;<span style="font-size:15px;">',$phrase,'</span>
    <br /><a href="fullsize.php?image=',$image,'"><img class="phototitle" style="margin-left:85px;margin-bottom:15px;clear:both;" src="',$imagenew,'" width="',$width,'px" height="',$height,'px" /></a>
    <div style="font-size:13px;margin-left:85px;margin-bottom:10px;clear:both;">Views: ',$views,'&nbsp;|&nbsp;Rank: ',$rank,'</div>';
    echo '</div>';  
	}
} //end view == 'tr'  


//FAVORITE VIEW
elseif($view == 'fv') {
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
    $phrase = $fullname . " favorited " . '"' . $caption . '"' . " by " . $ownerfull;
    
    $imageinfo = mysql_query("SELECT * FROM photos WHERE source = '$image'");
    $views = mysql_result($imageinfo,0,'views');
    $points = mysql_result($imageinfo,0,'points');
    $votes = mysql_result($imageinfo,0,'votes');
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
    $width = ($width / 2.5);
    $height = ($height / 2.5);
    
    echo '<div class="grid_10 push_1 fPic" id="',$id,'" style="width:600px;border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;"> 
    <img class="dropshadow" style="border: 1px solid white;margin-left:10px;margin-top:10px;" src="',$commenterpic,'" height="60" width="60" />&nbsp;&nbsp;<span style="font-size:15px;">',$phrase,'</span>
    <br /><a href="fullsize.php?image=',$image,'"><img class="phototitle" style="margin-left:85px;margin-bottom:15px;clear:both;" src="',$imagenew,'" width="',$width,'px" height="',$height,'px" /></a>
    <div style="font-size:13px;margin-left:85px;margin-bottom:10px;clear:both;">Views: ',$views,'&nbsp;|&nbsp;Rank: ',$rank,'&nbsp;|&nbsp;Favorited By: ',$fvlist,'</div>';
    echo '</div>';   
	
    $fvlist = '';
    }
} //end view == 'fv'  




//FOLLOW VIEW    
elseif($view == 'fw') {
if ($type == "follow") {
    $email4 = $newsrow['following'];
    $newaccount = "SELECT * FROM userinfo WHERE emailaddress = '$email4'";
    $accountresult = mysql_query($newaccount); 
    $accountrow = mysql_fetch_array($accountresult);
    $profilepic5 = $accountrow['profilepic'];
    $ownerid = $accountrow['user_id'];
    
    $numownerphotosquery = mysql_query("SELECT * FROM photos WHERE emailaddress = '$email4' ORDER by (points/votes) DESC");
    $numownerphotos = mysql_num_rows($numownerphotosquery);
    $flowimage1 = mysql_result($numownerphotosquery,0,'source');
    $flowimage1=str_replace("userphotos/","userphotos/medthumbs/", $flowimage1);
    $flowimage2 = mysql_result($numownerphotosquery,1,'source');
    $flowimage2=str_replace("userphotos/","userphotos/medthumbs/", $flowimage2);
    $flowimage3 = mysql_result($numownerphotosquery,2,'source');
    $flowimage3=str_replace("userphotos/","userphotos/medthumbs/", $flowimage3);
    
    $followersquery="SELECT * FROM userinfo WHERE following LIKE '%$email4%'";
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

    echo '<div class="grid_10 push_1 fPic" id="',$id,'" style="width:600px;border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;"> 
    <img class="dropshadow" style="border: 1px solid white;margin-left:10px;margin-top:10px;" src="',$commenterpic,'" height="60" width="60" />&nbsp;&nbsp;<span style="font-size:15px;">',$phrase,'</span>
    <br /><a href="viewprofile.php?u=',$ownerid,'"><img class="phototitle" style="margin-left:85px;margin-bottom:15px;" src="',$profilepic5,'" width="',$width,'px" height="',$height,'px" /></a>&nbsp;&nbsp;
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
} //end view == 'fw'    


//COMMENTS VIEW
elseif($view == 'cmt') {
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
   
    $imageinfo = mysql_query("SELECT * FROM photos WHERE source = '$image'");
    $views = mysql_result($imageinfo,0,'views');
    $points = mysql_result($imageinfo,0,'points');
    $votes = mysql_result($imageinfo,0,'votes');
    $rank = ($points / $votes);
    $rank = number_format($rank,2);
    
    $imagenew=str_replace("userphotos/","userphotos/medthumbs/", $image);
    $fullname = "<a href='viewprofile.php?u=" . $commenterid . "'>" . $firstname . " " . $lastname ."</a>";
    list($width, $height) = getimagesize($image);
	$imgratio = $height / $width;
	$height = $imgratio * $maxwidth;
    $phrase = $fullname . " commented on " . $ownerfull . "'s photo";
    
    list($width, $height) = getimagesize($image);
    $width = ($width / 2.5);
    $height = ($height / 2.5);
    
    echo '<div class="grid_10 push_1 fPic" id="',$id,'" style="width:600px;border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;"> 
    <img class="dropshadow" style="border: 1px solid white;margin-left:10px;margin-top:10px;" src="',$commenterpic,'" height="60" width="60" />&nbsp;&nbsp;<span style="font-size:15px;">',$phrase,'</span>
    <br /><a href="fullsize.php?image=',$image,'"><img class="phototitle" style="margin-left:85px;margin-bottom:15px;clear:both;" src="',$imagenew,'" width="',$width,'px" height="',$height,'px" /></a>';
    
    $txt=".txt";
	$imagenew=str_replace("userphotos/","", $image);
	$searchchars=array('.jpg','.png','.tiff','.JPG','.jpeg','.JPEG','.gif');
	$imagenew=str_replace($searchchars,"", $imagenew);
	$file = "comments/" . $imagenew . $txt; 
	echo '<br /><br /><div style="margin-left: 85px;padding:15px;width:480px;clear:both;">
    <div class="panel">';
    @include("$file");
    echo'
    </div>
    <p class="flip" style="font-size:15px;">View comment thread</p>';
	if (@file_get_contents($file) == '') {
		echo '<div style="text-align: center;">Be the first to leave a comment!<br /><br /></div>';
	}
	echo '</div>';
    
    echo'
    <br />
    <div style="font-size:13px;margin-left:85px;margin-bottom:10px;clear:both;">Views: ',$views,'&nbsp;|&nbsp;Rank: ',$rank,'</div>';
    echo '</div>';    
    }
} //end view == 'cmt'    
      
} //end for loop

 
} //end of if statement
?>

</div> 