<?php 

echo " ";

require "db_connection.php";

if($_GET['lastPicture']) {
	$lastpicID = mysql_real_escape_string($_GET['lastPicture']);
	$view = mysql_real_escape_string($_GET['view']);	
	$campaignquery = "SELECT campaign, score FROM campaignphotos WHERE id='$lastpicID' LIMIT 1";
	$campaignresult = mysql_query($campaignquery);
	$campaignID = mysql_result($campaignresult, 0, "campaign");
	$currentscore = mysql_result($campaignresult, 0, "score");

	if($view == "newest") {
		$query = "SELECT * FROM campaignphotos WHERE id <'$lastpicID' AND campaign='$campaignID' ORDER BY id DESC LIMIT 9";
	}
	else {
		$query = "SELECT * FROM campaignphotos WHERE score <= '$currentscore' AND campaign='$campaignID' ORDER BY score DESC, id DESC LIMIT 9";
	}
	$mysqlquery = mysql_query($query);
	$numphotos = mysql_num_rows($mysqlquery);

	//DISPLAY 20 NEWEST OF ALL PHOTOS
	for($iii=0; $iii < $numphotos; $iii++) {
		$photo[$iii] = mysql_result($mysqlquery, $iii, "source");
    	$photo[$iii] = str_replace("userphotos/","userphotos/medthumbs/", $photo[$iii]);
		$photoid[$iii] = mysql_result($mysqlquery, $iii, "id");
		$title[$iii] = mysql_result($mysqlquery, $iii, "caption");
        $points = mysql_result($mysqlquery, $iii, "points");
        $votes = mysql_result($mysqlquery, $iii, "votes");
        $average = $points / $votes;
        
		list($width, $height) = getimagesize($photo[$iii]);
		$imgratio = $height / $width;
    	$heightls = $height / 1.5;
    	$widthls = $width / 1.5;
	
		echo '
	<div class="phototitle fPic" id="',$photoid[$iii],'" style="width:280px;height:280px;overflow:hidden;">
		<a href="fullsize.php?id=',$photoid[$iii],'">
       		<div class="statoverlay" style="z-index:1;left:0px;top:210px;position:relative;background-color:black;width:280px;height:75px;"><p style="line-spacing:1.48;padding:5px;color:white;">"',$title[$iii],'"<br />Score: ',$average,'</p></div>
       		<img style="position:relative;top:-95px;min-height:300px;min-width:280px;" src="', $photo[$iii], '" height="',$heightls,'px" width="',$widthls,'px" />
       	</a>
    </div>';
    
    } //end for loop
}//end if clause

mysql_close();

?>