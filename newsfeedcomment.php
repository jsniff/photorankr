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
$imageid = mysql_real_escape_string($_POST['imageid']);
$commenterid = mysql_real_escape_string($_POST['viewerid']);
$userpic = mysql_real_escape_string($_POST['userpic']);
}

//PHOTO OWNER INFORMATION
$getowner = mysql_query("SELECT emailaddress FROM photos WHERE id = $imageid");
$emailaddress = mysql_result($getowner,0,'emailaddress');
$getowner = mysql_query("SELECT firstname,lastname FROM userinfo WHERE emailaddress = '$emailaddress'");
$ownerfirst = mysql_result($getowner,0,'firstname');
$ownerlast = mysql_result($getowner,0,'lastname');


    echo'<div class="previousComments"> 
            <ul class="indPrevComment">
            <li style="overflow:hidden;"> 
            <div style="width:35px;float:left;"><img src="https://photorankr.com/',$userpic,'" height="35" width="35" /></div>
                <div style="width:460px;float:left;" id="commenterName"><a href="viewprofile.php?u=',$commenterid,'">',$name,'</a>
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

    $insertcomment = mysql_query("INSERT INTO comments (comment,commenter,photoowner,imageid,time) VALUES ('$comment','$email','$emailaddress','$imageid','$currenttime')");

    
    //INSERT INTO NEWSFEED
    $type = 'comment';
    
    $commentidquery = mysql_query("SELECT id FROM comments WHERE commenter = '$email' ORDER BY id DESC LIMIT 0,1");
    $commentid = mysql_result($commentidquery,0,'id');
        
    $newsfeedcomment = mysql_query("INSERT INTO newsfeed (firstname, lastname, emailaddress,owner,type,source,imageid,time) VALUES ('$firstname', '$lastname', '$email','$emailaddress','$type','$imageid','$commentid','$currenttime')") or die();
    
    //notifications query     
    $notsquery = "UPDATE userinfo SET notifications = (notifications + 1) WHERE emailaddress = '$emailaddress'";
    $notsqueryrun = mysql_query($notsquery); 

 //MAIL TO OWNER OF PHOTO
    $settingquery = mysql_query("SELECT settings FROM userinfo WHERE emailaddress = '$emailaddress'");
    $settinglist = mysql_result($settingquery,0,"settings");
    $check = 'emailcomment';
    $foundsetting = strpos($settinglist,$check);
    
    if($emailaddress != $email) {
    $to = '"' . $ownerfirst . ' ' . $ownerlast . '"' . '<'.$emailaddress.'>';
    $subject = $name ." commented on your photo on PhotoRankr";
    $message = $unformattedcomment . "
To view the photo, click here: https://photorankr.com/fullsize.php?imageid=".$imageid;
    $headers = 'From:PhotoRankr <photorankr@photorankr.com>';
    
        if($foundsetting > 0) {
            mail($to, $subject, $message, $headers);  
        }
        
    }

    //MAIL TO PREVIOUS COMMENTERS ON PHOTO
    $previouscommenters = mysql_query("SELECT commenter FROM comments WHERE imageid = '$imageid'");
    $numcommenters = mysql_num_rows($previouscommenters);
    $prevemails .= $email;
      
    for($iii = 0; $iii < $numcommenters; $iii++) {
        
        $prevemail = mysql_result($previouscommenters,$iii,'commenter');
        $alreadysent = strpos($prevemails, $prevemail);
        
        if($alreadysent < 1 && $prevemail != $emailaddress) {
        
            $settingquery = mysql_query("SELECT firstname,lastname,emailaddress,settings FROM userinfo WHERE emailaddress = '$prevemail'");
            $settinglist = mysql_result($settingquery,0,"settings");
            $foundsetting = strpos($settinglist,"emailreturncomment");
            $sendtofirst = mysql_result($settingquery,0,"firstname");
            $sendtolast = mysql_result($settingquery,0,"lastname");
            $sendtoemail = mysql_result($settingquery,0,"emailaddress");
            
            $to = '"' . $sendtofirst . ' ' . $sendtolast . '"' . '<'.$sendtoemail.'>';
            $subject = $name . " also commented on " . $ownerfirst . " " . $ownerlast ."'s photo on PhotoRankr";
            $returnmessage = $unformattedcomment . "
        
To view the photo, click here: https://photorankr.com/fullsize.php?imageid=".$imageid;
            
            $headers = 'From:PhotoRankr <photorankr@photorankr.com>';
                        
            if($foundsetting > 0 && ($sendtoemail != $email) && $email) {     
                mail($to, $subject, $returnmessage, $headers);
            } 
    
        }
        
        elseif($alreadysent > 0) {
            continue;
        }
        
        $prevemails .= " " . $prevemail;
    
    }  
    

?>