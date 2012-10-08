<?php

//connect to the database
require "db_connection.php";
require "timefunction.php";

$commenttime = time();

if($_POST)
{
$name = mysql_real_escape_string($_POST['name']);
$email = mysql_real_escape_string($_POST['email']);
$comment= mysql_real_escape_string($_POST['comment']);
$article = mysql_real_escape_string($_POST['article']);
$commenterid = mysql_real_escape_string($_POST['viewerid']);
$commenterrep = mysql_real_escape_string($_POST['viewerrep']);
$commenterrep = number_format($commenterrep,2);
$userpic = mysql_real_escape_string($_POST['userpic']);

}

//AUTHOR INFORMATION
$authorinfo = mysql_query("SELECT author,emailaddress FROM entries WHERE id = $article");
$author = mysql_result($authorinfo,0,'author');
$authoremail = mysql_result($authorinfo,0,'emailaddress');


echo'

        <li class="grid_16" style="float:right;width:580px;margin-top:20px;">
            <a href="viewprofile.php?u=',$commenterid,'">
            <div style="float:left;"><img class="roundedall" src="',$userpic,'" alt="',$name,'" height="40" width="35"/>
            </a>
        </div>
        
        <div style="float:left;padding-left:6px;width:510px;">
            <div style="float:left;color:#3e608c;font-size:14px;font-family:helvetica;font-weight:500;border-bottom: 1px solid #ccc;width:510px;">
            <div style="float:left;">
            <a name="',$commentid,'" href="viewprofile.php?u=',$commenterid,'">',$name,'</a> &nbsp;<span style="font-size:16px;font-weight:100;color:black;margin-top:2">|</span>&nbsp;<span style="color:#333;font-size:12px;">Rep: ',$commenterrep,'</span>
                </div>
                &nbsp;&nbsp;&nbsp;
                   
                <div class="progress progress-success" style="float:left;width:110px;height:7px;opacity:.8;margin:7px;">
                
                <div class="bar" style="width:',$commenterrep,'%;">
            </div>
            </div>
        </div>
                
                <br />
                
                <div style="float:left;font-size:11px;color:#777;font-weight:400;padding:2px;">',converttime($commenttime),'</div>
                
                <div style="float:left;width:470px;padding:10px;font-size:13px;font-family:helvetica;font-weight:300;color:#555;">',$comment,'</div>
                
            </div>
            </li>';

//INSERT COMMENT INTO DATABASE
$entrycommentquery = mysql_query("INSERT INTO articlecomments (comment,commenter,article,time) VALUES ('$comment','$email','$article','$commenttime')");

//INSERT INTO NEWSFEED
$type = 'articlecomment';
$newsfeedquery = mysql_query("INSERT INTO newsfeed (firstname, emailaddress, type, source,time) VALUES ('$name','$email','$type','$article','$commenttime')");

//MAIL TO AUTHOR OF BLOG POST
        
    if($authoremail != $email) {
    $to = '"' . $author . '"' . '<'.$authoremail.'>';
    $subject = $name ." commented on your PhotoRankr blog post";
    $message = $comment . "
To view the blog post, click here: https://photorankr.com/post.php?a=".$article;
    $headers = 'From:PhotoRankr <photorankr@photorankr.com>';
    
    mail($to, $subject, $message, $headers);  
        
    }

    //MAIL TO PREVIOUS COMMENTERS ON POST
    $previouscommenters = mysql_query("SELECT commenter FROM articlecomments WHERE article = '$article'");
    $numcommenters = mysql_num_rows($previouscommenters);
    $prevemails .= $email;
      
    for($iii = 0; $iii < $numcommenters; $iii++) {
        
        $prevemail = mysql_result($previouscommenters,$iii,'commenter');
        $alreadysent = strpos($prevemails, $prevemail);
        
        if($alreadysent < 1 && $prevemail != $authoremail) {
        
            $settingquery = mysql_query("SELECT firstname,lastname,emailaddress,settings FROM userinfo WHERE emailaddress = '$prevemail'");
            $sendtofirst = mysql_result($settingquery,0,"firstname");
            $sendtolast = mysql_result($settingquery,0,"lastname");
            $sendtoemail = mysql_result($settingquery,0,"emailaddress");
            
            $to = '"' . $sendtofirst . ' ' . $sendtolast . '"' . '<'.$sendtoemail.'>';
            $subject = $name . " also commented on " . $author ."'s blog post on PhotoRankr ";
            $returnmessage = stripslashes($comment) . "
        
To view the blog post, click here: https://photorankr.com/post.php?a=".$article;
            
            $headers = 'From:PhotoRankr <photorankr@photorankr.com>';
            
            mail($to, $subject, $returnmessage, $headers);
    
        }
        
        elseif($alreadysent > 0) {
            continue;
        }
        
        $prevemails .= " " . $prevemail;
    
    }


?>
