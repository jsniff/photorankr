<?php 

require("db_connection.php");
require("timefunction.php");

session_start();
$email = $_SESSION['email'];

echo'<script type="text/javascript" src="js/jquery.wookmark.js"></script>';

if($_GET['lastPicture']) {
	$activityquery = mysql_query("SELECT * FROM newsfeed WHERE hide <> 1 AND id < ".$_GET['lastPicture']." AND (emailaddress = '$email' OR owner = '$email') AND type IN ('follow','comment','fave','photo') ORDER BY id DESC LIMIT 13");
    $numresults = mysql_num_rows($activityquery);

echo'
    <div id="main" role="main">
    <ul id="tiles">';
        
        for($iii = 0; $iii < 12; $iii++) {
            
            //(strlen($caption) > 28) ? substr($caption,0,25). " &#8230;" : $caption;

            $type = mysql_result($activityquery,$iii,'type');
            $id = mysql_result($activityquery,$iii,'id');
            $owner = mysql_result($activityquery,$iii,'owner');
            $commenter = mysql_result($activityquery,$iii,'emailaddress');
            $commentimageid = mysql_result($activityquery,$iii,'imageid');
            $time = mysql_result($activityquery,$iii,'time');
            
            $getcommentid = mysql_query("SELECT comment FROM comments WHERE id = '$commentimageid'");
            $comment = mysql_result($getcommentid,0,'comment');
            
            $source = mysql_result($activityquery,$iii,'source');
            
            $getimageid = mysql_query("SELECT id FROM photos WHERE source = '$source'");
            $sourceid = mysql_result($getimageid,0,'id');
            list($width,$height) = getimagesize($source);
            $newwidth = $width/3.2;
            $newheight = $height/3.2;
            
            if($newwidth < 195) {
                $newheight = $newheight * ($newheight/$newwidth);
                $newwidth = 240;
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
                    
                   echo'<li class="fPic" id="',$id,'" style="padding:5px;margin-top:10px;list-style-type: none;width:240px;
">

                    <div style="width:100%;"><div style="float:left;height:60px;"><img style="max-height:40px;" src="',$commenterpic,'" /></div>&nbsp;&nbsp;<div style="float:left;padding-left:8px;width:180px;"><img src="graphics/upload.png" width="25" />&nbsp;&nbsp;',$commentername,' uploaded "',$caption,'"
                    
                    <div style="color:#555;font-weight:500;margin-left:0px;">';if($time > 0) {echo'',converttime($time),'';} echo'</div> 

                    </div>
                    <hr /></div>
                    
                    <a href="fullsize.php?imageid=',$sourceid,'"><img src="',$source,'" width="',$newwidth,'px" height="',$newheight,'px" /></a>
                    </li>';
               
                }
                
                elseif($type == 'follow') {
                
                        
                
                      echo'<li class="fPic" id="',$id,'" style="padding:5px;margin-top:10px;list-style-type: none;width:240px;
">

                     <div style="width:100%;"><div style="float:left;height:60px;"><img style="max-height:40px;" src="',$commenterpic,'" /></div>&nbsp;&nbsp;<div style="float:left;padding-left:8px;width:180px;"><img src="graphics/follower.png" width="35" />&nbsp;&nbsp;<a href="viewprofile.php?u=',$cnid,'">',$commentername,'</a> followed <a href="viewprofile.php?u=',$ownerid,'">',$followername,'</a>
                     
                    <div style="color:#555;font-weight:500;">';if($time > 0) {echo'',converttime($time),'';} echo'</div>

                     </div>
                     <hr /></div>
                     
                     <div><a href="viewprofile.php?u=',$ownerid,'"><img style="float:left;max-height:80px;margin-top:-30px;" src="',$followpic,'" /></a>
                     
                     <div style="width:230px;height:100px;font-size:18px;margin-left:10px;margin-top:40px;"><i><div style="text-align:center;">',$followername,'</div></i></div>
                     </div>
                     
                     <div style="width:240px;">';
                    if($numprofilepics > 3){echo'<img style="padding:3px;" src="',$profileimage,'" height="110" width="110" /><img style="padding:3px;" src="',$profileimage2,'" height="110" width="110" /><img style="padding:3px;" src="',$profileimage3,'" height="110" width="110" /><img style="padding:3px;" src="',$profileimage4,'" height="110" width="110" />';}
                    echo'</div>
                     
                     </li>
                     <br />';
                    
                }
                
                elseif($type == 'comment') {
                    
                     echo'<li class="fPic" id="',$id,'" style="padding:5px;margin-top:10px;list-style-type: none;width:240px;
">

                    <div style="width:100%;"><div style="float:left;height:60px;"><img style="max-height:40px;" src="',$commenterpic,'" /></div>&nbsp;&nbsp;<div style="float:left;padding-left:8px;width:180px;"><img src="graphics/comment.png" width="25" />&nbsp;&nbsp;<a href="viewprofile.php?u=',$commenterid,'">',$commentername,'</a> commented on <a href="viewprofile.php?u=',$cnid,'">',$cn,'\'s</a> photo
                    
                    <div style="color:#555;font-weight:500;">';if($time > 0) {echo'',converttime($time),'';} echo'</div>

                    </div>
                    <hr /></div>
                    
                    <a href="fullsize.php?imageid=',$sourceid,'">
                    
                    <img src="',$source,'" width="',$newwidth,'px" height="',$newheight,'px" />                    
                    </a>';
                    
                    if($comment) {
                    echo'
                    <div style="font-size:15px;width:220px;padding:10px;margin-top:20px;">"',$comment,'"</div>';
                    }
                    
                    echo'
                    </li>
                    <br />';
                
                
                }
                
                elseif($type == "blogcomment") {
                
                    
                
                }
                
                elseif($type == "fave") {
                
                    echo'<li class="fPic" id="',$id,'" style="padding:5px;margin-top:10px;list-style-type: none;width:240px;
">
                        <div style="width:100%;"><div style="float:left;height:60px;"><img style="max-height:40px;" src="',$commenterpic,'" /></div>&nbsp;&nbsp;
                        <div style="float:left;padding-left:8px;width:180px;"><img src="graphics/fave.png" width="25" />&nbsp;&nbsp;<a href="viewprofile.php?u=',$commenterid,'">',$commentername,'</a> favorited <a href="viewprofile.php?u=',$cnid,'">',$cn,'\'s </a> photo
                    
                    <div style="color:#555;font-weight:500;margin-left:0px;">';if($time > 0) {echo'',converttime($time),'';} echo'</div> 

                    </div>
                    <hr /></div>
                    
                    <a href="fullsize.php?imageid=',$sourceid,'"><img src="',$source,'" width="',$newwidth,'px" height="',$newheight,'px" /></a>
                    
                    </li>';
                
                }
        
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
        itemWidth: 260 // Optional, the width of a grid item
      };
      
      // Get a reference to your grid items.
      var handler = $('#tiles li');
      
      // Call the layout function.
      handler.wookmark(options);
      
    });
  </script>
        
<?php
        
}//end if clause

?>
