<?php

//connect to the database
require "db_connection.php";
require "functions.php";

//Current time
$currenttime = time();

//All Users
$allusers = mysql_query("SELECT id FROM userinfo ORDER BY id ASC");
$numusers = mysql_num_rows($allusers);

//Grab Faves
$selectuser = mysql_query("SELECT faves,emailaddress FROM userinfo WHERE user_id = '4'");
$faves = mysql_result($selectuser,0,'faves');
$useremail = mysql_result($selectuser,0,'emailaddress');

//Split Faves Up
$favesarray = explode(", ",$faves);
$numfaves = count($favesarray);

for($ii=0; $ii<$numfaves; $ii++) {
	$findimageid = mysql_query("SELECT id,emailaddress FROM photos WHERE source = $favesarray[$ii]");
	$imageid = mysql_result($findimageid,0,'id');
	$owner = mysql_result($findimageid,0,'emailaddress');
    
    if($imageid == '') {
        continue;
    }
    
	//New faving query
    $newfavesquery="INSERT INTO favorites (imageid,time,emailaddress,owner) VALUES ('$imageid','$currenttime','$useremail','$owner')";
    mysql_query($newfavesquery);
    echo $ii . '<br />';

}

?>