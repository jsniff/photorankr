<?php

//connect to the database
require "db_connection.php";
require "timefunction.php";
require "functions.php";

$commenttime = time();

if($_POST)
{
$firstname = mysql_real_escape_string($_POST['firstname']);
$lastname = mysql_real_escape_string($_POST['lastname']);
$name = $firstname ." ". $lastname;
$email = mysql_real_escape_string($_POST['email']);
$unformattedcomment = $_POST['comment'];
$comment= mysql_real_escape_string($unformattedcomment);
$imageid = mysql_real_escape_string($_POST['photo']);
$commenterid = mysql_real_escape_string($_POST['viewerid']);
$commenterrep = mysql_real_escape_string($_POST['viewerrep']);
$commenterrep = number_format($commenterrep,2);
$userpic = mysql_real_escape_string($_POST['userpic']);

}

//PHOTO OWNER INFORMATION
$getowner = mysql_query("SELECT emailaddress FROM photos WHERE id = $imageid");
$emailaddress = mysql_result($getowner,0,'emailaddress');
$getowner = mysql_query("SELECT firstname,lastname FROM userinfo WHERE emailaddress = '$emailaddress'");
$ownerfirst = mysql_result($getowner,0,'firstname');
$ownerlast = mysql_result($getowner,0,'lastname');

//Comment ID
$commentidquery = mysql_query("SELECT id FROM comments WHERE imageid = '$imageID' ORDER BY id DESC LIMIT 1");
$commentid = mysql_result($commentidquery,$iii,'id');

echo'
    <div id="comment" style="width:820px;clear:both;margin-left:0px;">
    
        <div id="commentProfPic">
            <img src="https://photorankr.com/',$userpic,'" height="55" width="50" />
        </div>
        
        <div style="position:relative;left:15px;">
        
            <div class="commentTriangle" style="margin-top:-18px;"></div>

			<div class="commentName">
				<header><span style="font-size:14px;">',$commenterrep,'</span> <a href="viewprofile.php?u=',$commenterid,'">',$firstname,' ',$lastname,'</a> </header>
				<p> ',converttime($commenttime),' </p>&nbsp;
                <img style="width:12px;padding-right:3px;" src="graphics/clock.png"/>&nbsp;
			</div>
			<div class="commentBody"><p>',$comment,'</p>
            
            <div id="edit"><a href="fullsize.php?image=',$imageid,'&action=editcomment&cid=',$commentid,'#',$commentid,'"> Edit Comment</a></div>
                
            <div id="edit"><a href="fullsize.php?image=',$imageid,'&action=deletecomment&cid=',$commentid,'">Delete Comment</a></div>';
            
            if($_GET['action'] == 'editcomment' && $commentid == $_GET['cid']) {
                
                    echo'
                    <form action="fullsize.php?image=',$imageid,'#',$commentid,'" method="POST" />
                    <textarea style="height:55px;width:95%;margin-left:10px;margin-top:10px;" name="commentedit">',$comment,'</textarea>
                    <input type="hidden" name="commentid" value="',$commentid,'" />
                    <br />
                    <input type="submit" class="btn btn-primary" style="float:right;font-size:12px;margin-right:10px;margin-bottom:5px;" value="Save Edit" />
                    </form>';
                    
            }
            
            echo'
            </div>
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
