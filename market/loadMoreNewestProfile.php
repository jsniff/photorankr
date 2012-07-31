<?php 

require("db_connection.php");

if($_GET['lastPicture']) {
	$lastpicID = mysql_real_escape_string($_GET['lastPicture']);
	$query = mysql_query("SELECT emailaddress FROM photos WHERE id='$lastpicID' LIMIT 0, 1");
	$useremail = mysql_result($query, 0, "emailaddress");
    $lastphoto = $_GET['lastPicture'];
    
	$newestphotos = mysql_query("SELECT * FROM photos WHERE id < '$lastphoto' AND emailaddress = '$useremail' ORDER BY id DESC LIMIT 0, 21") or die(mysql_error());
    $num = mysql_num_rows($newestphotos);
//DISPLAY 20 NEWEST OF ALL PHOTOS

echo'<div id="container" class="grid_19">';
for($iii = 0; $iii < $num; $iii++) {
                $photo[$iii] = mysql_result($newestphotos,$iii,'source');
                $photobig[$iii] = str_replace("userphotos/", "$_SERVER[DOCUMENT_ROOT]/userphotos/", $photo[$iii]);
                $photo[$iii] = str_replace("$_SERVER[DOCUMENT_ROOT]/userphotos/", "http://photorankr.com/userphotos/medthumbs/", $photobig[$iii]);
                $imageid = mysql_result($newestphotos,$iii,'id');
                $caption = mysql_result($newestphotos,$iii,'caption');
                $ranking = (mysql_result($newestphotos,$iii,'points')/mysql_result($newestphotos,$iii,'votes'));
                $ranking = number_format($ranking,2);

                list($width,$height) = getimagesize($photobig[$iii]);
                $widthnew = $width / 5;
                $heightnew = $height / 5;
                    if($widthnew < 165) {
                        $heightnew = $heightnew * ($heightnew/$widthnew);
                        $widthnew = 240;
                    }
                    
                echo'
				<div class="phototitle fPic" id="',$imageid,'" style="width:230px;height:230px;overflow:hidden;">
                
                 <a href="fullsize2.php?imageid=',$imageid,'"><div class="statoverlay" style="z-index:1;left:0px;top:140px;position:relative;background-color:black;width:238px;height:75px;"><p style="line-spacing:1.48;padding:5px;color:white;">"',$caption,'"<br>By: ',$fullname,'</br>Rank: ',$ranking,'</p></div>
                 
					<img onmousedown="return false" oncontextmenu="return false;" style="position:relative;top:-100px;min-height:240px;min-width:240px;" class="phototitle2" src="',$photo[$iii],'" height="',$heightnew,'px" width="',$widthnew,'px" /></a>
				</div>';
            
        } //end for loop
      
echo'</div>';

}//end if clause

?>
