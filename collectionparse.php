<?php

//connect to the database
require "db_connection.php";
require "functions.php";

//Current time
$currenttime = time();

//All Users
$allcolls = mysql_query("SELECT * FROM collections ORDER BY id DESC");
$numcolls = mysql_num_rows($allcolls);

for($iii=0; $iii < $numcolls; $iii++) {

$useremail = mysql_result($allcolls,$iii,'owner');
$collid = mysql_result($allcolls,$iii,'id');
$photos = mysql_result($allcolls,$iii,'photos');

//Photos Split Up
$photosarray = explode(" ",$photos);
$numphotos = count($photosarray);

for($ii=0; $ii<$numphotos; $ii++) {
    
    if($photosarray[$ii] == '') {
        continue;
    }
    
    //Photo check
    $checkforphoto = mysql_query("SELECT id FROM collectionphotos WHERE owner = '$useremail' AND imageid = '$photosarray[$ii]'");
    $check = mysql_num_rows($checkforphoto);
    

    //Add to new table
    $newcollquery="INSERT INTO collectionphotos (owner,collection,imageid,time) VALUES ('$useremail','$collid','$photosarray[$ii]','$currenttime')";
    mysql_query($newcollquery);
    echo $ii . '<br />';

}

}  //end loop

?>