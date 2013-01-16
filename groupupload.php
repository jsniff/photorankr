<?php

//connect to the database
require "db_connection.php";
require "functions.php";

@session_start();
$email = $_SESSION['email'];

$userinfoquery = mysql_query("SELECT firstname,lastname FROM userinfo WHERE emailaddress = '$email'");
$firstname = mysql_result($userinfoquery,0,'firstname');
$lastname =  mysql_result($userinfoquery,0,'lastname');
 
$comment = mysql_real_escape_string(htmlentities($_POST['comment']));
$group_id = mysql_real_escape_string(htmlentities($_POST['group_id']));
$currenttime = time();

//add checked photos to existing exhibit
$count = 0;
if(!empty($_POST['addthese'])) {
    foreach($_POST['addthese'] as $checked) {
        if($count >= 4) {
            break;
        }
        $photoidlist = $photoidlist . $checked ." ";
        $count += 1;
        }
    }

//Insert group post into database as comment and newsfeed post
$insertintocomments = mysql_query("INSERT INTO groupnews (commenter,comment,photo,group_id,firstname,lastname,time,type) VALUES ('$email','$comment','$photoidlist','$group_id','$firstname','$lastname','$currenttime','post')");

//grab query id
$getid = mysql_query("SELECT id FROM groupnews WHERE commenter = '$email' ORDER BY id DESC LIMIT 1");
$postid = mysql_result($getid,0,'id');

//insert into other newsfeed
$addtoothernewsfeed = mysql_query("INSERT INTO newsfeed (firstname, lastname, emailaddress,type,source,time,group_id) VALUES ('$firstname', '$lastname', '$email','post','$postid','$currenttime','$group_id')") or die();

//return user to group after comment
echo '<META HTTP-EQUIV="Refresh" Content="0; URL=groups.php?id=',$group_id,'">';
exit();	
   


?>