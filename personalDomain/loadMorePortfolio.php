<?php 

require("db_connection.php");
$email = htmlentities($_GET['email']);
$view = htmlentities($_GET['view']);
$id = htmlentities($_GET['id']);
$price = htmlentities($_GET['price']);

if($_GET['lastPicture']) {

    if($view == '' && $price == '') {
        $query = "SELECT * FROM photos WHERE id < ".$_GET['lastPicture']." AND emailaddress = '$email' ORDER BY id DESC LIMIT 0, 16";
    }
    elseif($view == 'pop') {
        $query = "SELECT * FROM photos WHERE views < ".$_GET['lastPicture']." AND emailaddress = '$email' ORDER BY views DESC LIMIT 0, 16";
    }
    elseif($view == 'top') {
        $query = "SELECT * FROM photos WHERE (points/votes) < ".$_GET['lastPicture']." AND emailaddress = '$email' ORDER BY (points/votes) DESC LIMIT 0, 16";
    }
    if($price == 'hl') {
        $query = "SELECT * FROM photos WHERE price < ".$_GET['lastPicture']." AND emailaddress = '$email' ORDER BY (points/votes) DESC LIMIT 0, 16";
    }
    elseif($price == 'lh') {
        $query = "SELECT * FROM photos WHERE price > ".$_GET['lastPicture']." AND emailaddress = '$email' ORDER BY (points/votes) DESC LIMIT 0, 16";
    }
	$mysqlquery = mysql_query($query) or die(mysql_error());
    $numpics = mysql_num_rows($mysqlquery);
    
echo'<div id="main">';
    
for($iii=1; $iii <= $numpics; $iii++) {
	$image = mysql_result($mysqlquery, $iii-1, "source");
	$imageThumb = str_replace("userphotos/","userphotos/medthumbs/", $image);
	$id = mysql_result($mysqlquery, $iii-1, "id");
    $caption = mysql_result($mysqlquery, $iii-1, "caption");
    $caption = (strlen($caption) > 28) ? substr($caption,0,25). " &#8230;" : $caption;
    $firstprice = mysql_result($mysqlquery, $iii-1, "price");
    if($firstprice < 0) {$realprice='NFS';}
    else {
        $realprice = "$".$firstprice;
    }   
    $views = mysql_result($mysqlquery, $iii-1, "views");
    $points = mysql_result($mysqlquery, $iii-1, "points");
    $votes = mysql_result($mysqlquery, $iii-1, "votes");
    $score = number_format(($points/$votes),2);
    $owner = mysql_result($mysqlquery, $iii-1, "emailaddress");
    $ownerquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$owner'");
    $firstname = mysql_result($ownerquery, 0, "firstname");
    $lastname = mysql_result($ownerquery, 0, "lastname");
    $fullname = $firstname . " " . $lastname;

	list($width, $height) = getimagesize('http://photorankr.com/'.$image);
	$imgratio = $height / $width;
    $heightls = $height / 4.3;
    $widthls = $width / 4.3;
    if($widthls < 225) {
    $heightls = $heightls * ($heightls/$widthls);
    $widthls = 270;
    }

    if($view == '') {
                 echo'<div class="fPic" id="',$id,'" style="float:left;overflow:hidden;height:300px;">
                    <div class="portfolioImage">
                        <a href="?view=big&imageid=',$id,'">
                            <img style="min-height:',$heightls,'px;width:',$widthls,'px;" onmousedown="return false" oncontextmenu="return false;" src="https://photorankr.com/',$imageThumb,'" />
                        </a>
                    </div>
                <div style="clear:both;margin:15px 0px;margin-left:20px;height:30px;width:252px;background-color:rgb(234,234,234);">
                    <div style="font-size:16px;color:#666;text-align:left;float:left;padding:5px;">',$realprice,'&nbsp;&nbsp;&nbsp;<span style="font-weight:300;font-size:15px;">',$caption,'</span></div>
                </div>
                </div>';
            }
            elseif($view == 'pop') {
                echo'<div class="fPic" id="',$views,'" style="float:left;overflow:hidden;height:300px;">
                    <div class="portfolioImage">
                        <a href="?view=big&imageid=',$id,'">
                            <img style="min-height:',$heightls,'px;width:',$widthls,'px;" onmousedown="return false" oncontextmenu="return false;" src="https://photorankr.com/',$imageThumb,'" />
                        </a>
                    </div>
                <div style="clear:both;margin:15px 0px;margin-left:20px;height:30px;width:252px;background-color:rgb(234,234,234);">
                    <div style="font-size:16px;color:#666;text-align:left;float:left;padding:5px;">',$realprice,'&nbsp;&nbsp;&nbsp;<span style="font-weight:300;font-size:15px;">',$caption,'</span></div>
                </div>
                </div>';
            }
            elseif($view == 'top') {
                echo'<div class="fPic" id="',($points/$votes),'" style="float:left;overflow:hidden;height:300px;">
                    <div class="portfolioImage">
                        <a href="?view=big&imageid=',$id,'">
                            <img style="min-height:',$heightls,'px;width:',$widthls,'px;" onmousedown="return false" oncontextmenu="return false;" src="https://photorankr.com/',$imageThumb,'" />
                        </a>
                    </div>
                <div style="clear:both;margin:15px 0px;margin-left:20px;height:30px;width:252px;background-color:rgb(234,234,234);">
                    <div style="font-size:16px;color:#666;text-align:left;float:left;padding:5px;">',$realprice,'&nbsp;&nbsp;&nbsp;<span style="font-weight:300;font-size:15px;">',$caption,'</span></div>
                </div>
                </div>';
            }
            if($price == 'hl') {
               echo'<div class="fPic" id="',$realprice,'" style="float:left;overflow:hidden;height:300px;">
                    <div class="portfolioImage">
                        <a href="?view=big&imageid=',$id,'">
                            <img style="min-height:',$heightls,'px;width:',$widthls,'px;" onmousedown="return false" oncontextmenu="return false;" src="https://photorankr.com/',$imageThumb,'" />
                        </a>
                    </div>
                <div style="clear:both;margin:15px 0px;margin-left:20px;height:30px;width:252px;background-color:rgb(234,234,234);">
                    <div style="font-size:16px;color:#666;text-align:left;float:left;padding:5px;">',$realprice,'&nbsp;&nbsp;&nbsp;<span style="font-weight:300;font-size:15px;">',$caption,'</span></div>
                </div>
                </div>';
            }
            elseif($price == 'lh') {
               echo'<div class="fPic" id="',$realprice,'" style="float:left;overflow:hidden;height:300px;">
                    <div class="portfolioImage">
                        <a href="?view=big&imageid=',$id,'">
                            <img style="min-height:',$heightls,'px;width:',$widthls,'px;" onmousedown="return false" oncontextmenu="return false;" src="https://photorankr.com/',$imageThumb,'" />
                        </a>
                    </div>
                <div style="clear:both;margin:15px 0px;margin-left:20px;height:30px;width:252px;background-color:rgb(234,234,234);">
                    <div style="font-size:16px;color:#666;text-align:left;float:left;padding:5px;">',$realprice,'&nbsp;&nbsp;&nbsp;<span style="font-weight:300;font-size:15px;">',$caption,'</span></div>
                </div>
                </div>';
            }
            	    
      } //end for loop

}//end if clause

?>
  
</div>
