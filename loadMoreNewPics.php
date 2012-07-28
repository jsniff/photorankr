<?php 

require("db_connection.php");

if($_GET['lastPicture']) {
	$query = "SELECT * FROM photos WHERE id < ".$_GET['lastPicture']." ORDER BY id DESC LIMIT 0, 8";
	$mysqlquery = mysql_query($query) or die(mysql_error());


//DISPLAY 20 NEWEST OF ALL PHOTOS

echo'<div id="container" style="width:1140px;position:relative;left:-120px; top:55px;">';
for($iii=1; $iii <= 8; $iii++) {
	$image = mysql_result($mysqlquery, $iii-1, "source");
	$imageThumb = str_replace("userphotos/","userphotos/medthumbs/", $image);
	$id = mysql_result($mysqlquery, $iii-1, "id");
    $caption = mysql_result($mysqlquery, $iii-1, "caption");
    $points = mysql_result($mysqlquery, $iii-1, "points");
    $votes = mysql_result($mysqlquery, $iii-1, "votes");
    $score = number_format(($points/$votes),2);
    $owner = mysql_result($mysqlquery, $iii-1, "emailaddress");
    $ownerquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$owner'");
    $firstname = mysql_result($ownerquery, 0, "firstname");
    $lastname = mysql_result($ownerquery, 0, "lastname");
    $fullname = $firstname . " " . $lastname;

	list($width, $height) = getimagesize($image);
	$imgratio = $height / $width;
    $heightls = $height / 3.5;
    $widthls = $width / 3.5;


     	echo '<div class="phototitle fPic" id="',$id,'" style="width:240px;height:240px;overflow:hidden;"><a href="http://www.photorankr.com/fullsize.php?image=',$image,'&v=n">
        
        <div class="statoverlay" style="z-index:1;left:0px;top:170px;position:relative;background-color:black;width:240px;height:75px;"><p style="line-spacing:1.48;padding:5px;color:white;">"',$caption,'"<br>By: ',$fullname,'</br>Score: ',$score,'</p></div>
        
        <img onmousedown="return false" oncontextmenu="return false;" style="position:relative;top:-75px;min-height:240px;min-width:240px;" src="http://www.photorankr.com/',$imageThumb,'" height="',$heightls,'px" width="',$widthls,'px" /></a></div>';
       
  
	    
      } //end for loop
      
echo'</div>';

}//end if clause

?>
