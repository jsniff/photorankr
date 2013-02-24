<?php

    //connect to the database
    require "db_connection.php";
    require "functions.php";

    $email = mysql_real_escape_string($_GET['age']);
    $imageid = mysql_real_escape_string($_GET['image']);
    
    //IP Address
    $ip = $_SERVER['REMOTE_ADDR'];
    
    //Time
    $currenttime = time();
    
    //Grab image information & source
    $getimagesource = mysql_query("SELECT source,caption,width,height,price FROM photos WHERE id = '$imageid' LIMIT 0,1");
    $image = mysql_result($getimagesource,0,'source');
    $caption = mysql_result($getimagesource,0,'caption');
    $width = mysql_result($getimagesource,0,'width');
    $height = mysql_result($getimagesource,0,'height');
    $price = mysql_result($getimagesource,0,'price');

    if($imageid) {
        $cartcheck = mysql_query("SELECT * FROM personaldomaincart WHERE imageid = '$imageid' && ip_address = '$ip'");
        $numincart = mysql_num_rows($cartcheck);
        $prevboughtquery = mysql_query("SELECT * FROM personaldomaindownloads WHERE imageid = '$imageid' && ip_address = '$ip'");
        $prevboughtcheck = mysql_num_rows($prevboughtquery);
        if($numincart < 1 && $prevboughtcheck < 1) {
            $stickincart = mysql_query("INSERT INTO personaldomaincart (source,width,height,price,imageid,caption,ip_address,domain) VALUES ('$image','$width','$height','$price','$imageid','$caption','$ip','$email')");
            //image placed in cart
            echo '<i style="margin-top:3px;" class="icon-ok"></i> Image added to cart';
            }
            //image already in cart 
            if($numincart > 0) {
                echo '<i style="margin-top:3px;" class="icon-ok icon-white"></i> Image already in cart';
            }
            //image already purchased
            if($prevboughtcheck > 0) {
                echo '<i style="margin-top:3px;" class="icon-ok icon-white"></i> Image already purchased';
            }
        }
    
?>