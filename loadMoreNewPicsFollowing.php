<?php 

require("db_connection.php");

$useremail = $_GET['useremail'];

 //FOLLOWING LIST
    $followingquery = "SELECT following FROM userinfo WHERE emailaddress='$useremail'";
    $followingresult = mysql_query($followingquery);
    $followinglistowner = mysql_result($followingresult, 0, "following");   


if($_GET['lastPicture']) {
	$query = "SELECT * FROM photos WHERE emailaddress IN ($followinglistowner) AND id < ".$_GET['lastPicture']." ORDER BY id DESC LIMIT 0, 8";
	$mysqlquery = mysql_query($query) or die(mysql_error());


//DISPLAY 20 NEWEST OF ALL PHOTOS

echo'<div id="container" style="width:1210px;margin-left:-112px; top:15px;">';
for($iii=1; $iii <= 8; $iii++) {
	$image = mysql_result($mysqlquery, $iii-1, "source");
	$imageThumb = str_replace("userphotos/","userphotos/medthumbs/", $image);
	$id = mysql_result($mysqlquery, $iii-1, "id");
    $caption = mysql_result($mysqlquery, $iii-1, "caption");
    $caption = (strlen($caption) > 28) ? substr($caption,0,25). " &#8230;" : $caption;
    $points = mysql_result($mysqlquery, $iii-1, "points");
    $votes = mysql_result($mysqlquery, $iii-1, "votes");
    $score = number_format(($points/$votes),2);
    $owner = mysql_result($mysqlquery, $iii-1, "emailaddress");
    $ownerquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$owner'");
    $firstname = mysql_result($ownerquery, 0, "firstname");
    $lastname = mysql_result($ownerquery, 0, "lastname");
    $fullname = $firstname . " " . $lastname;
    $profilepic = mysql_result($ownerquery, 0, "profilepic");

	list($width, $height) = getimagesize($image);
	$imgratio = $height / $width;
    $heightls = $height / 2.5;
    $widthls = $width / 2.5;


     	echo '<div class="fPic" id="',$id,'" style="float:left;margin-right:20px;margin-top:20px;width:280px;height:280px;overflow:hidden;"><a style="text-decoration:none;" href="http://photorankr.com/fullsize.php?image=',$image,'&v=n">
        
          <div class="statoverlay" style="z-index:1;left:0px;top:235px;position:relative;background-color:black;width:280px;height:50px;"><p style="line-spacing:1.48;padding:5px;color:white;"><img src="',$profilepic,'" width="30" />&nbsp;&nbsp;<span style="font-family:helvetica neue,arial;font-weight:100;font-size:20px;">',$caption,'</span><br/></div>
        
        <img onmousedown="return false" oncontextmenu="return false;" style="position:relative;top:-75px;min-height:300px;min-width:280px;" src="http://photorankr.com/',$imageThumb,'" alt="',$caption,'" height="',$heightls,'px" width="',$widthls,'px" /></a></div>';    
  
	    
      } //end for loop
      
echo'</div>';

}//end if clause

?>
