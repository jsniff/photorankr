<?php

//connect to the database
require "db_connection.php";
require "functions.php";

@session_start();
$email = $_SESSION['email'];
 
$comment = mysql_real_escape_string(htmlentities($_POST['comment']));
$group_id = mysql_real_escape_string(htmlentities($_POST['group_id']));
$currenttime = time();

//add checked photos to existing exhibit
if(!empty($_POST['addthese'])) {
    foreach($_POST['addthese'] as $checked) {
        $photoidlist = $photoidlist . $checked ." ";
       
        }
    }

//Insert group post into database as comment and newsfeed post
$insertintocomments = mysql_query("INSERT INTO groupnews (commenter,comment,photo,group_id,firstname,lastname,time,type) VALUES ('$email','$comment','$photoidlist','$group_id','matt','sniff','$currenttime','post')");

//return user to group after comment
echo '<META HTTP-EQUIV="Refresh" Content="0; URL=groups.php?id=',$group_id,'">';
exit();	
   


?>