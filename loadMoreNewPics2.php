<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript" src="http://masonry.desandro.com/jquery.masonry.min.js"></script>
<script type="text/javascript" src="https://raw.github.com/desandro/imagesloaded/master/jquery.imagesloaded.min.js"></script>

<?php 

require("db_connection.php");

if($_GET['lastPicture']) {
	$query = "SELECT * FROM photos WHERE id < ".$_GET['lastPicture']." ORDER BY id DESC LIMIT 0, 8";
	$newestphotos = mysql_query($query) or die(mysql_error());
    

//DISPLAY 20 NEWEST OF ALL PHOTOS

echo'<div class="photo_container" style="z-index:1;position:relative;top:75px;margin-left:50px;">';
for($iii = 0; $iii <= 8; $iii++) {
                $photo[$iii] = mysql_result($newestphotos,$iii,'source');
                $photobig[$iii] = str_replace("userphotos/", "$_SERVER[DOCUMENT_ROOT]/userphotos/", $photo[$iii]);
                $photo[$iii] = str_replace("$_SERVER[DOCUMENT_ROOT]/userphotos/", "http://photorankr.com/userphotos/medthumbs/", $photobig[$iii]);
                $id = mysql_result($newestphotos,$iii,'id');
                $owneremail[$iii] = mysql_result($newestphotos,$iii,'emailaddress');
                $caption[$iii] = mysql_result($newestphotos,$iii,'caption');

                $query1234 = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$owneremail[$iii]'");
                $profilepic[$iii] = mysql_result($query1234,0,'profilepic');

                list($width,$height) = getimagesize($photobig[$iii]);
                $widthnew = $width / 4.5;
                $heightnew = $height / 4.5;
                if($widthnew < 215) {
                $heightnew = $heightnew * ($heightnew/$widthnew);
                $widthnew = 270;
                }



     	echo'
				<div class="masonryImage">
                <div class="phototitle5 fPic" id="',$id,'">
					<a href="fullsize2.php?imageid=',$id,'"><img style="text-align:center;padding-bottom:20px;min-width:265px;" src="',$photo[$iii],'" height="',$heightnew,'px" width="',$widthnew,'px" /></a>
                        <div class="statoverlay" style="background-color:#000;height:35px;margin-top:-20px;width:265px;">
                        <div style="color:white;font-size:16px;font-weight:100;padding:8px;m"><span sty le="font-size:23px;">8.4</span>/10 test caption numero</div>
                        </div>
                    </div>
				</div>';  
	    
      } //end for loop
      
echo'</div>';

}//end if clause

?>
