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
	for($iii=0; $iii < mysql_num_rows($mysqlquery); $iii++) {
	//get the information for the current photo
	$photo[$iii] = mysql_result($mysqlquery, $iii, "source");
    $photo[$iii] = str_replace("userphotos/","userphotos/medthumbs/", $photo[$iii]);
	$points = mysql_result($mysqlquery, $iii, "points");
	$votes = mysql_result($mysqlquery, $iii, "votes");
	$average[$iii] = number_format(($points / $votes),2);
	$photoid[$iii] = mysql_result($mysqlquery, $iii, "id");
	$caption[$iii] = mysql_result($mysqlquery, $iii, "caption");

	list($width, $height) = getimagesize($photo[$iii]);
	$imgratio = $height / $width;
   	$heightls = $height / 2.5;
   	$widthls = $width / 2.5;

	echo '
    
    <div class="fPic" id="',$photoid[$iii],'" style="width:280px;overflow:hidden;float:left;margin-left:30px;margin-top:30px;"><a href="fullsizeme.php?id=',$photoid[$iii],'">
                
                <div style="width:280px;height:280px;overflow:hidden;">
                <div class="statoverlay" style="z-index:1;left:0px;top:215px;position:relative;background-color:black;width:280px;height:90px;"><p style="line-spacing:1.48;padding:5px;color:white;"><span style="font-size:18px;font-weight:100;">',$caption[$iii],'</span><br><span style="font-size:15px;font-weight:100;">Rank: ',$average[$iii],'<br /></div>
                <img onmousedown="return false" oncontextmenu="return false;" style="position:relative;top:-90px;min-height:300px;min-width:280px;" src="',$photo[$iii],'" height="',$heightls,'px" width="',$widthls,'px" /></a>
                <br />      
                </div>
                
                
                </div>';
    
}

}//end if clause

mysql_close();

?>