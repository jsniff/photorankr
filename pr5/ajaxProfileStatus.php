<?php

//connect to the database
require "db_connection.php";
require "timefunction.php";
require "functions.php";

//Grab email
session_start();
$email = $_SESSION['email'];

$currenttime = time();
$formattedtime = converttime($currenttime);

if($_POST) {
    $firstname = mysql_real_escape_string($_POST['firstname']);
    $lastname = mysql_real_escape_string($_POST['lastname']);
    $status = mysql_real_escape_string($_POST['status']);
}

//insert into status table
$statusupdate = mysql_query("INSERT INTO statuses (emailaddress, status, time) VALUES ('$email','$status','$currenttime')");

//grab id of status
$statusidquery = mysql_query("SELECT id FROM statuses WHERE emailaddress = '$email' ORDER BY id DESC LIMIT 1");
$status_id = mysql_result($statusidquery,0,'id');

//insert into newsfeed
$statusNewsfeed = mysql_query("INSERT INTO newsfeed (firstname,lastname,emailaddress,type,time,status,status_id) VALUES ('$firstname','$lastname','$email','status','$currenttime','$status','$status_id')");

//Print Status
echo'<div class="status">
        <img style="width:15px;margin:-5px 4px 0px 2px;" src="graphics/comment_1.png" />',$status,'
      <span style="float:right;font-weight:500;color:#666;font-size:11px;padding-right:2px;">',$formattedtime,'</span>
     </div>';