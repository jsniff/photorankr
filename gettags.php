<?php
ini_set('max_input_time', 300);


//CONNECT TO DB
require "db_connection.php";
require 'config.php';
require 'functions.php';



session_start();
$owner = $_SESSION['email'];

//get the q parameter from URL
$q=$_GET["q"];



//grab all tags associated with this owner's selected set
$tagquery="SELECT * FROM sets WHERE owner = '$owner' AND title = '$q'";
$tagqueryrun = mysql_query($tagquery);
$tagarray = mysql_fetch_array($tagqueryrun);
$maintags = $tagarray['maintags'];
$settag1 = $tagarray['settag1'];
$settag2 = $tagarray['settag2'];
$settag3 = $tagarray['settag3'];
$settag4 = $tagarray['settag4'];


    echo'
 
    <input type="checkbox" name="setcover" value="',yes,'" />&nbsp;Make this photo the cover photo
    <br /><br />
    <span style="font-size:16px">Tags you chose to describe the "',$q,'" exhibit:</span>
    <br />
    <span style="font-size:13px">(De-select tags that don\'t fit this photo)</span>
    <br />
    <br />
    ';
        
 
    $maintagsarray = explode("  ", $maintags);
    $maintagcount = count($maintagsarray);

   for($iii=0; $iii < $maintagcount; $iii++) {
    	echo '<input type="checkbox" name="maintags[]" value="',$maintagsarray[$iii],'" checked="checked" />&nbsp;',$maintagsarray[$iii],'
    	<br /><br />';
   }
    
    if($settag1) {
     echo'<input type="checkbox" name="settags[]" value="',$settag1,'" checked="checked" />&nbsp;',ucwords($settag1),'
    <br /><br />';
    }
    
     if($settag2) {
     echo'<input type="checkbox" name="settags[]" value="',$settag2,'" checked="checked" />&nbsp;',ucwords($settag2),'
    <br /><br />';
    }

 if($settag3) {
     echo'<input type="checkbox" name="settags[]" value="',$settag3,'" checked="checked" />&nbsp;',ucwords($settag3),'
    <br /><br />';
    }

 if($settag4) {
     echo'<input type="checkbox" name="settags[]" value="',$settag4,'" checked="checked" />&nbsp;',ucwords($settag4),'
    <br /><br />';
    } 

?>