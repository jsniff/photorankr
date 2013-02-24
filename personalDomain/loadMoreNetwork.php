<?php 

require("db_connection.php");

if($_GET['lastPicture']) {
	$lastpic = mysql_real_escape_string($_GET['lastPicture']);
	$useremail = mysql_real_escape_string($_GET['email']);
    $userid = mysql_real_escape_string($_GET['userid']);
    $sessionemail = $_SESSION['email'];

    if($option == '') {
            $followingquery = mysql_query("SELECT following,id FROM following WHERE emailaddress = '$useremail' AND id < $lastpic ORDER BY id DESC LIMIT 0,20");
            $numberfollowing = mysql_num_rows($followingquery);
        }
        elseif($option == 'followers') {
            $followingquery = mysql_query("SELECT emailaddress,id FROM following WHERE following = '$userid' AND id < $lastpic ORDER BY id DESC LIMIT 0,20");
            $numberfollowing = mysql_num_rows($followingquery);
        }

    echo'
    <div id="thepics" style="width:1150px;">
    <div id="main">';
            
            for($iii = 0; $iii < $numberfollowing; $iii++) {
                $followingemail = mysql_result($followingquery, $iii, 'emailaddress');
                $followingid = mysql_result($followingquery, $iii, 'following');
                $tableid = mysql_result($followingquery, $iii, 'id');
                $finduser = mysql_query("SELECT profilepic,firstname,lastname,reputation FROM userinfo WHERE (user_id = '$followingid' OR emailaddress = '$followingemail')");
                $followingpic = mysql_result($finduser, 0, "profilepic");
                $followingfirst = mysql_result($finduser, 0, "firstname");
                $followinglast = mysql_result($finduser, 0, "lastname");
                $followingrep = number_format(mysql_result($finduser, 0, "reputation"),2);
                $fullname = $followingfirst . " " . $followinglast;
                $fullname = ucwords($fullname);
                
                echo '   
                <div class="fPic" id="',$tableid,'" style="width:215px;height:245px;overflow:hidden;float:left;margin-left:10px;margin-top:30px;"><a style="text-decoration:none;" href="https://photorankr.com/viewprofile.php?u=',$followingid,'">

                <img id="roundCorners" onmousedown="return false" oncontextmenu="return false;" style="min-height:215px;min-width:215px;" src="https://www.photorankr.com/',$followingpic,'" height="215" width="215" /></a>
                
            <div class="statoverlay" style="z-index:1;background-color:white;position:relative;top:0px;width:280px;height:30px;">
                <div style="line-spacing:1.48;padding:5px;color:#4A4A4A;">
                    <div style="float:left;padding-top:10px;">
                        <span style="font-size:15px;font-weight:500;">',$followingrep,'</span>&nbsp;&nbsp;<span style="font-weight:300;font-size:15px;">',$fullname,'</span>
                    </div>
                </div>
            </div>       	
                
                </div>';
            
            }
    echo'
        </div>
        </div>';
        
        } //end if last pic
    
    ?>
