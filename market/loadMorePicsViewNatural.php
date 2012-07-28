<?php 

require "db_connection.php";

echo " ";

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
		$query = "SELECT * FROM campaignphotos WHERE score <= '$currentscore' AND campaign='$campaignID' AND id!='$lastpicID' ORDER BY score DESC, id DESC LIMIT 9";
	}
	$mysqlquery = mysql_query($query);
	$numphotos = mysql_num_rows($mysqlquery);

	//DISPLAY 20 NEWEST OF ALL PHOTOS
	for($iii=0; $iii < $numphotos; $iii++) {
		$photobig[$iii] = mysql_result($mysqlquery, $iii, "source");
		$photoid[$iii] = mysql_result($mysqlquery, $iii, "id");
		$title[$iii] = mysql_result($mysqlquery, $iii, "caption");
        $points = mysql_result($mysqlquery, $iii, "points");
        $votes = mysql_result($mysqlquery, $iii, "votes");
        $average = $points / $votes;

		list($width, $height) = getimagesize($photobig[$iii]);
		$imgratio = $height / $width;
    	$heightls = $height / 2;
    	$widthls = $width / 2;
	
		echo '<div fPic" id="', $photoid[$iii],'">
		<a href="fullsizecampaign.php?id=',$photoid[$iii],'">
       		<img class="phototitle  style="float:bottom;margin-top:20px;" src="',$photobig[$iii], '" height="',$heightls,'px" width="',$widthls,'px" /></a></div>';
    } //end for loop
}//end if clause

mysql_close();

?>