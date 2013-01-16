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

//user information
$grabinfo = mysql_query("SELECT user_id,profilepic FROM userinfo WHERE emailaddress = '$email'");
$ownerprofilepic = mysql_result($grabinfo,0,'profilepic');
$ownerid = mysql_result($grabinfo,0,'user_id');

//insert into status table
$statusupdate = mysql_query("INSERT INTO statuses (emailaddress, status, time) VALUES ('$email','$status','$currenttime')");

//grab id of status
$statusidquery = mysql_query("SELECT id FROM statuses WHERE emailaddress = '$email' ORDER BY id DESC LIMIT 1");
$status_id = mysql_result($statusidquery,0,'id');

//insert into newsfeed
$statusNewsfeed = mysql_query("INSERT INTO newsfeed (firstname,lastname,emailaddress,type,time,status,status_id) VALUES ('$firstname','$lastname','$email','status','$currenttime','$status','$status_id')");

//Print Status     
      echo'<div class="grid_16">
         <!--Profile Picture-->
            <div class="newsBlock">
                <ul>
                    <li><img id="newsProfilePic" src="https://photorankr.com/',$ownerprofilepic,'" />
                </ul>
            </div>
                     
         <!--Content Box-->
         <div class="newsContainer">
            <div class="newsTriangle"></div>
                <div class="newsItem">
                    <!--Top Controls-->
                    <div class="newsControls">
                        <div class="newsName"><a href="viewprofile.php?u=',$ownerid,'">',$firstname,' ',$lastname,'</a> posted an update</div>
                        <div class="newsTools">
                            <span id="time">',$formattedtime,'</span>
                        </div>
                    </div>
                    <!--Content-->
                    <div id="followContent">
                        ',$status,'
                    </div>
                </div>
         <!--End Content Box-->
         </div>
                  
         </div>';
