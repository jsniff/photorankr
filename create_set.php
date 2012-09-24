<?php
ini_set('max_input_time', 300);


//CONNECT TO DB
require "db_connection.php";
require 'config.php';
require 'functions.php';



session_start();
$owner = $_SESSION['email'];


//POST VARIABLES FROM SET FORM
$title=mysql_real_escape_string($_POST['title']);
$maintags=$_POST['maintags'];
$settag1=mysql_real_escape_string($_POST['settag1']);
$settag2=mysql_real_escape_string($_POST['settag2']);
$settag3=mysql_real_escape_string($_POST['settag3']);
$settag4=mysql_real_escape_string($_POST['settag4']);
$about=mysql_real_escape_string($_POST['about']);

$numbertags = count($maintags);
    for($i=0; $i < $numbertags; $i++)
    {
      $tags = $tags . " " . $maintags[$i] . " ";
    }
    

//Check to make sure they do not create set with same name
$samename = "SELECT * FROM sets WHERE owner = '$owner'";
$samenamequery = mysql_query($samename);
$sncount = mysql_num_rows($samenamequery);

for($iii=0; $iii < $sncount; $iii++)
    {
    $samenametitle = mysql_result($samenamequery, $iii, "title");
    $snmatch = $snmatch . " " . $samenametitle . " ";
    }

$search_string = $snmatch;
$find=$title;
$match=strpos($search_string, $find);

if($match > 0) 
    {
            header("location:myprofile.php?view=upload&cs=n&ns=name");
            exit();
    }

//Make sure they filled out all required information
if (($numbertags < 1) | !$title ) 
		{
         		header("location:myprofile.php?view=upload&cs=n&ns=failure");
        		exit();
		}		
else {
//Build queries to create the new set
$setsquery = "INSERT INTO sets (owner, title, maintags, about, settag1, settag2, settag3, settag4) VALUES ('$owner','$title','$tags','$about','$settag1','$settag2','$settag3','$settag4')";
$setqueryrun = mysql_query($setsquery);

//userinfo query
$infoquery = "SELECT * FROM userinfo WHERE emailaddress = '$owner'";
$infoqueryrun = mysql_query($infoquery);
$infoarray = mysql_fetch_array($infoqueryrun);
$first = $infoarray['firstname'];
$last = $infoarray['lastname'];
$type = 'exhibit';

$newsfeedquery = "INSERT INTO newsfeed (firstname, lastname, emailaddress, type, caption) VALUES ('$first','$last','$owner','$type','$title')";
$newsfeedqueryrun = mysql_query($newsfeedquery);

header("location: myprofile.php?view=upload&cs=n&ns=success");
exit();

}

?>