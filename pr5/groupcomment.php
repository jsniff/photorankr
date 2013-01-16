<?php

//connect to the database
require "db_connection.php";
require "timefunction.php";
require "functions.php";

$commenttime = time();
$commenttime = converttime($commenttime);

if($_POST)
{
$firstname = mysql_real_escape_string($_POST['firstname']);
$lastname = mysql_real_escape_string($_POST['lastname']);
$name = $firstname ." ". $lastname;
$email = mysql_real_escape_string($_POST['email']);
$unformattedcomment = $_POST['comment'];
$comment= mysql_real_escape_string($unformattedcomment);
$postid = mysql_real_escape_string($_POST['post']);
$groupid = mysql_real_escape_string($_POST['groupid']);
$commenterid = mysql_real_escape_string($_POST['viewerid']);
$userpic = mysql_real_escape_string($_POST['userpic']);

}

//Group Information
$groupinfo = mysql_query("SELECT * FROM groups WHERE id = '$groupid'");
$groupname = mysql_result($groupinfo,0,'name');

//POST OWNER INFORMATION
$getowner = mysql_query("SELECT commenter FROM groupnews WHERE id = $postid");
$emailaddress = mysql_result($getowner,0,'commenter');
$getowner = mysql_query("SELECT firstname,lastname FROM userinfo WHERE emailaddress = '$emailaddress'");
$ownerfirst = mysql_result($getowner,0,'firstname');
$ownerlast = mysql_result($getowner,0,'lastname');

echo'<div class="previousComments" style="width:480px;margin-left:0px;padding:0px;"> 
            <ul class="indPrevComment" style="padding:0px;">
            <li style="overflow:hidden;padding-bottom:-10px;"> 
            <div style="width:35px;float:left;"><img src="https://photorankr.com/',$userpic,'" height="35" width="35" /></div>
                <div style="width:420px;float:left;" id="commenterName"><a href="viewprofile.php?u=',$commenterid,'">',$name,'</a>
                <div style="float:right">',$commenttime,'</div>
                <div id="commentText">
                    ',$comment,'
                </div>
            </div>
        </li>
        </ul>
        </div>';

    //INSERT COMMENT INTO DATABASE
    $currenttime = time();
        
    //Convert all instances of 'http' to a link
    $comment = trim($comment);
    $comment = make_url($comment);

    $insertcomment = mysql_query("INSERT INTO groupcomments (commenter,comment,group_id,post_id,time) VALUES ('$email','$comment','$groupid','$postid','$currenttime')");
    
    //INSERT INTO NEWSFEED    
    $commentidquery = mysql_query("SELECT id FROM groupcomments WHERE commenter = '$email' ORDER BY id DESC LIMIT 0,1");
    $commentid = mysql_result($commentidquery,0,'id');
        
    $newsfeedcomment = mysql_query("INSERT INTO groupnews (group_id,firstname, lastname, commenter,comment,time,type) VALUES ('$groupid','$firstname', '$lastname', '$email','$comment','$currenttime','comment')") or die();
    
    //grab query id
$getid = mysql_query("SELECT id FROM groupnews where commenter = '$email' ORDER BY id DESC LIMIT 1");
$postid = mysql_result($getid,0,'id');

    //insert into other newsfeed
    $addtoothernewsfeed = mysql_query("INSERT INTO newsfeed (firstname, lastname, emailaddress,type,source,owner,time,group_id) VALUES ('$firstname', '$lastname', '$email','groupcomment','$postid','$emailaddress','$currenttime','$groupid')") or die();
    
    //notifications query     
    $notsquery = "UPDATE userinfo SET notifications = (notifications + 1) WHERE emailaddress = '$emailaddress'";
    $notsqueryrun = mysql_query($notsquery); 

 //MAIL TO OWNER OF PHOTO
    /*$settingquery = mysql_query("SELECT settings FROM userinfo WHERE emailaddress = '$emailaddress'");
    $settinglist = mysql_result($settingquery,0,"settings");
    $check = 'emailcomment';
    $foundsetting = strpos($settinglist,$check);*/
    
    if($emailaddress != $email) {
    $to = '"' . $ownerfirst . ' ' . $ownerlast . '"' . '<'.$emailaddress.'>';
    $subject = "[".$groupname."]". $name ." commented on your post";
    $message = $unformattedcomment . "
        
To view the comment, click here: https://photorankr.com/groups.php?id=".$groupid."#".$postid;

    $headers = 'From:PhotoRankr <photorankr@photorankr.com>';    
    mail($to, $subject, $message, $headers);      
    }

    //MAIL TO PREVIOUS COMMENTERS ON PHOTO
    $previouscommenters = mysql_query("SELECT commenter FROM groupcomments WHERE post_id = '$postid'");
    $numcommenters = mysql_num_rows($previouscommenters);
    $prevemails .= $email;
      
    for($iii = 0; $iii < $numcommenters; $iii++) {
        
        $prevemail = mysql_result($previouscommenters,$iii,'commenter');
        $alreadysent = strpos($prevemails, $prevemail);
        
        if($alreadysent < 1 && $prevemail != $emailaddress) {
        
            /*$settingquery = mysql_query("SELECT firstname,lastname,emailaddress,settings FROM userinfo WHERE emailaddress = '$prevemail'");
            $settinglist = mysql_result($settingquery,0,"settings");
            $foundsetting = strpos($settinglist,"emailreturncomment");*/
            $sendtofirst = mysql_result($settingquery,0,"firstname");
            $sendtolast = mysql_result($settingquery,0,"lastname");
            $sendtoemail = mysql_result($settingquery,0,"emailaddress");
            
            $to = '"' . $sendtofirst . ' ' . $sendtolast . '"' . '<'.$sendtoemail.'>';
            $subject = "[".$groupname."]". $name . " also commented on " . $ownerfirst . " " . $ownerlast ."'s post";
            $returnmessage = $unformattedcomment . "
        
To view the comment, click here: https://photorankr.com/groups.php?id=".$groupid."#".$postid;
            
            $headers = 'From:PhotoRankr <photorankr@photorankr.com>';
            mail($to, $subject, $returnmessage, $headers);

            /*if($foundsetting > 0 && ($sendtoemail != $email) && $email) {     
                mail($to, $subject, $returnmessage, $headers);
            } */
    
        }
        
        elseif($alreadysent > 0) {
            continue;
        }
        
        $prevemails .= " " . $prevemail;
    
    } 


?>
