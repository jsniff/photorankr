<?php 

require("db_connection.php");

if($_GET['lastPicture']) {
	$lastpic = mysql_real_escape_string($_GET['lastPicture']);
	$user = mysql_real_escape_string($_GET['user']);
	$userquery = "SELECT faves FROM userinfo WHERE user_id='$user' LIMIT 0, 1";
	$userquery = mysql_query($userquery);
	$faves = mysql_result($userquery, 0, "faves");

	$favesquery = "SELECT * FROM photos WHERE source IN($faves) ORDER BY FIELD(source, $faves) DESC";
	$favesresult = mysql_query($favesquery);

	$favesSeen = "";
	$iii=0;
	for(; $iii<mysql_num_rows($favesresult); $iii++) {
		if($lastpic!=mysql_result($favesresult, $iii, "id")) {
			$favesSeen .= "'";
			$favesSeen .= mysql_result($favesresult, $iii, "source");
			$favesSeen .= "',";
		}
		else if($lastpic == mysql_result($favesresult, $iii, "id")) {
			break;
		}
	}
	$favesSeen .= "'";
	$favesSeen .= mysql_result($favesresult, $iii, "source");
	$favesSeen .= "'";

	$query = "SELECT * FROM photos WHERE source IN($faves) AND source NOT IN($favesSeen) ORDER BY FIELD(source, $faves) DESC";
	$mysqlquery = mysql_query($query);
	$numphotos = mysql_num_rows($mysqlquery);

	//DISPLAY 20 NEWEST OF ALL PHOTOS
	echo '<div class="grid_16 push_4" id="container" style="width:780px;">';

	for($iii=0; $iii < 9 && $iii < $numphotos; $iii++) {
		$image[$iii] = mysql_result($mysqlquery, $iii, "source");
    $imageThumb[$iii] = str_replace("userphotos/","userphotos/medthumbs/", $image[$iii]);
    $imageThumb[$iii] = str_replace(".JPG",".jpg", $imageThumb[$iii]);
	$id = mysql_result($mysqlquery, $iii, "id");
    $caption = mysql_result($mysqlquery, $iii, "caption");
    $points = mysql_result($mysqlquery, $iii, "points");
    $votes = mysql_result($mysqlquery, $iii, "votes");
    $faves = mysql_result($mysqlquery, $iii, "faves");
    $faveemail = mysql_result($mysqlquery, $iii, "emailaddress");
    $score = number_format(($points/$votes),2);
    $ownerquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$faveemail'");
    $firstname = mysql_result($ownerquery, 0, "firstname");
    $lastname = mysql_result($ownerquery, 0, "lastname");
    $fullname = $firstname . " " . $lastname;
	list($width, $height) = getimagesize($image);
	$imgratio = $height / $width;
    $heightls = $height / 3.5;
    $widthls = $width / 3.5;

echo '

<div class="photoshadow fPic" id="',$id,'" style="width:245px;height:245px;overflow:hidden;float:left;margin-left:10px;margin-top:30px;"><a href="http://www.photorankr.com/fullsize.php?image=', $image[$iii], '">

<div class="statoverlay" style="z-index:1;left:0px;top:160px;position:relative;background-color:black;width:245px;height:90px;"><p style="line-spacing:1.48;padding:5px;color:white;">"',$caption,'"<br>By: ',$fullname,'<br/>Score: ',$score,'<br>Favorites: ',$faves,'</p></div>

<img style="position:relative;top:-90px;min-height:245px;min-width:245px;" src="http://www.photorankr.com/',$imageThumb[$iii],'" height="',$heightls,'px" width="',$widthls,'px" /></a></div>';
	    
      } //end for loop
      
echo'</div>';

}//end if clause

?>
