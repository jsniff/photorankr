<?php 

require("db_connection.php");

$followlistquery = mysql_query("SELECT following FROM userinfo WHERE firstname='".$_GET['first']."' AND lastname='".$_GET['last']."' LIMIT 0,1") or die(mysql_error());
$followlist = mysql_result($followlistquery, 0, "following");

if($_GET['lastPicture']) {
	$query = "SELECT * FROM photos WHERE id < ".$_GET['lastPicture']." AND emailaddress IN (".$followlist.") ORDER BY id DESC LIMIT 0, 10";
	$mysqlquery = mysql_query($query) or die(mysql_error());

//DISPLAY 20 NEWEST OF ALL PHOTOS
for($iii=1; $iii <= 10; $iii++) {
	$image = mysql_result($mysqlquery, $iii-1, "source");
	$imageThumb = str_replace("userphotos/","userphotos/thumbs/", $image);
	$caption = mysql_result($mysqlquery, $iii-1, "caption");
	$id = mysql_result($mysqlquery, $iii-1, "id");

	$maxwidth = 400;
	list($width, $height) = getimagesize($image);
	$imgratio = $height / $width;
	$height = $imgratio * $maxwidth;

	if(($iii % 2) == 1) {
		echo '<div class="grid_10 alpha fPic photoshadowPS" id="',$id,'" style="width:400px; height: 725px; margin-top:40px; overflow: hidden;">
				<a href="http://www.photorankr.com/fullsize.php?image=',$image,'"><img src="http://www.photorankr.com/',$image,'" height=', $height, 'px width="400px" /></a>
		';
	}
	else {
		echo '<div class="grid_10 push_2 omega fPic photoshadowPS" id="',$id,'" style="width:400px; height: 725px; margin-top:40px; overflow: hidden">
				<a href="http://www.photorankr.com/fullsize.php?image=',$image,'"><img src="http://www.photorankr.com/',$image,'" height=', $height, 'px width="400px" /></a>
		';		
	}
	
	echo '<br /><br />';

	$emailnew=mysql_result($mysqlquery, $iii-1, "emailaddress");
	$queryimageone=mysql_query("SELECT firstname, lastname FROM userinfo WHERE emailaddress = '$emailnew' LIMIT 0,1");
	$namequeryone=mysql_fetch_array($queryimageone);
	$firstnameone=$namequeryone['firstname'];
	$lastnameone=$namequeryone['lastname'];
	echo '<div style="text-align: center;"><a href="http://www.photorankr.com/viewprofile.php?first=',$firstnameone,'&last=',$lastnameone,'">',$firstnameone,' ',$lastnameone,'</a></div>';
	echo '<div style="text-align: center;">"',$caption,'"</div>';

	$txt=".txt";
	$imagenew=str_replace("userphotos/","", $image);
	$searchchars=array('.jpg','.png','.tiff','.JPG','.jpeg','.JPEG','.gif');
	$imagenew=str_replace($searchchars,"", $imagenew);
	$file = "comments/" . $imagenew . $txt; 
	echo '<br /><hr style="color: black" /><div style="margin-left: 5px; height: ', (675-$height), 'px; overflow-y: scroll;">';
	@include("$file");
	if (@file_get_contents($file) == '') {
		echo '<div style="text-align: center;">Be the first to leave a comment!<br /><br /></div>';
	}
	echo '</div>';
	
	echo '</div>';
} //end for loop

}//end if clause

?>
