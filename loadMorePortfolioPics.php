<?php 

require("db_connection.php");

session_start();
$useremail = $_SESSION['email'];

if($_GET['lastPicture']) {
	$query = "SELECT * FROM photos WHERE id < ".$_GET['lastPicture']." AND emailaddress='$useremail' ORDER BY id DESC LIMIT 0, 9";
	$mysqlquery = mysql_query($query) or die(mysql_error());
	$numphotos = mysql_num_rows($mysqlquery);

	//DISPLAY 20 NEWEST OF ALL PHOTOS

	echo'<div class="grid_16 push_4" id="container" style="width:780px;">';

	for($iii=0; $iii < 9 && $iii < $numphotos; $iii++) {
    $image[$iii] = mysql_result($mysqlquery, $iii, "source");
    $imageThumb[$iii] = str_replace("userphotos/","userphotos/medthumbs/", $image[$iii]);
    $imageThumb[$iii] = str_replace(".JPG",".jpg", $imageThumb[$iii]);
	$id = mysql_result($mysqlquery, $iii, "id");
    $caption = mysql_result($mysqlquery, $iii, "caption");
    $points = mysql_result($mysqlquery, $iii, "points");
    $votes = mysql_result($mysqlquery, $iii, "votes");
    $faves = mysql_result($mysqlquery, $iii, "faves");
    $score = number_format(($points/$votes),2);
	list($width, $height) = getimagesize($image);
	$imgratio = $height / $width;
    $heightls = $height / 3.5;
    $widthls = $width / 3.5;

echo '

<div class="photoshadow fPic" id="',$id,'" style="width:245px;height:245px;overflow:hidden;float:left;margin-left:10px;margin-top:30px;"><a href="http://www.photorankr.com/fullsizeme.php?image=', $image[$iii], '">

<div class="statoverlay" style="z-index:1;left:0px;top:170px;position:relative;background-color:black;width:245px;height:75px;"><p style="line-spacing:1.48;padding:5px;color:white;">"',$caption,'"<br>Score: ',$score,'<br>Favorites: ',$faves,'</p></div>

<img style="position:relative;top:-75px;min-height:245px;min-width:245px;" src="http://www.photorankr.com/',$imageThumb[$iii],'" height="',$heightls,'px" width="',$widthls,'px" /></a></div>';
	    
      } //end for loop
      	
	echo'</div>';

}//end if clause

?>
