<?php

//connect to the database
require "db_connection.php";
// require "functionsnav.php";
// require "timefunction.php";

//start the session
session_start();

    // if login form has been submitted
    if (htmlentities($_GET['action']) == "login") { 
        login();
    }
    else if(htmlentities($_GET['action']) == "logout") { 
        logout();
    }

    $email = $_SESSION['email'];
    
    if (!$_SESSION['email']) {
        header("Location: signup.php");
        exit();
    } 
	
$salt1 = "abcde";
$salt2 = "zyxwv";
$temp = md5($salt1."wwiace".$salt2);
$shatemp = sha1($salt1."wwiace".$salt2);
print "md5 hashed is: $temp <br>";
print "sha1 hashed is: $shatemp";
?>