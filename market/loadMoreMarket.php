<?php 

require("db_connection.php");
    
if($_GET['lastPicture']) {

$category = htmlentities($_GET['c']);
$score = $_GET['score'];
$search = $_GET['search'];
$views = $_GET['views'];
$points = $_GET['points'];

if($category == '' && $search == '') {
$query = mysql_query("SELECT * FROM photos WHERE id < ".$_GET['lastPicture']." AND price != ('Not For Sale') ORDER BY id DESC LIMIT 0, 20") or die(mysql_error());
$numresults = mysql_num_rows($query);
}

elseif($category == 'top') {
$query = mysql_query("SELECT * FROM photos WHERE points < $points AND price != ('Not For Sale') ORDER BY points DESC LIMIT 0,20");
$numresults = mysql_num_rows($query);
}

elseif($category == 'trending' || $category == '') {
$query = mysql_query("SELECT * FROM photos WHERE score < $score AND price != ('Not For Sale') ORDER BY score DESC LIMIT 0,20");
$numresults = mysql_num_rows($query);
}

elseif($category == 'pop') {
$query = mysql_query("SELECT * FROM photos WHERE views < ".$_GET['views']." AND views > 120 AND price != ('Not For Sale') ORDER BY faves DESC LIMIT 0,20");
$numresults = mysql_num_rows($query);
}

elseif($category == 'deal') {
$deal = '10.00';
$twoweeksago = time() - 1209600;
$query = mysql_query("SELECT * FROM photos WHERE id < ".$_GET['lastPicture']." AND price < '$deal' AND time > $twoweeksago AND price != ('Not For Sale') ORDER BY points DESC LIMIT 0,20");
$numresults = mysql_num_rows($query);
}

if($search!= '') {
$query = mysql_query("SELECT * FROM photos WHERE concat(caption, tag, camera, tag1, tag2, tag3, tag4, singlecategorytags, singlestyletags, location, country, about, sets, maintags, settags) LIKE '%$search%' AND price != ('Not For Sale') AND views < ".$_GET['views']." ORDER BY views DESC LIMIT 0,20");
$numresults = mysql_num_rows($query);
}


//DISPLAY 20 NEWEST OF ALL PHOTOS
for($iii=0; $iii < 20 && $iii < $numresults; $iii++) {
    $imagebig[$iii] = mysql_result($query,$iii,'source');
    $imagebig[$iii] = str_replace("userphotos/", "$_SERVER[DOCUMENT_ROOT]/userphotos/", $imagebig[$iii]);
    $imagebig2[$iii] = str_replace("$_SERVER[DOCUMENT_ROOT]/userphotos/", "http://photorankr.com/userphotos/medthumbs/", $imagebig[$iii]);
    $price = mysql_result($query,$iii,'price');
    if($price == 0) {
    $price = 'FREE';  
    }
    elseif($price > 0) {
    $price = '$' . $price;
    }
    $title = mysql_result($query,$iii,'caption');
    $imageid = mysql_result($query,$iii,'id');
    $title = "'" . $title . "'";
    $owner = mysql_result($query,$iii,'emailaddress');
    $sold = mysql_result($query,$iii,'sold');
    $points = mysql_result($query,$iii,'points');
    $votes = mysql_result($query,$iii,'votes');
    $rating = ($points/$votes);
    $score = mysql_result($query,$iii,'score');
    $views = mysql_result($query,$iii,'views');
    $license = mysql_result($query,$iii,'license');
    if($license == '') {
    $license = 'Royalty Free';
    }
    $rating = number_format($rating,2);
    
    $ownerquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$owner'");
    $ownerpic = mysql_result($ownerquery,0,'profilepic');
    $firstname = mysql_result($ownerquery,0,'firstname');
    $lastname = mysql_result($ownerquery,0,'lastname');
    $fullname = $firstname . " " . $lastname;
    
    list($height,$width) = getimagesize($imagebig[$iii]);
    $widthnew = $width / 8.25;
    $heightnew = $height / 8.25;
    $widthmed = $width / 5.5;
    $heightmed = $height / 5.5;

       echo'<div class="fPic"  id="',$imageid,'" style="width:180px;height:200px;overflow:hidden;float:left;border-top:1px solid #ccc;"><br /><a href="fullsize2.php?imageid=',$imageid,'"><div style="width:',$heightnew,'px;"><img id="popover3" rel="popover" data-content="<span style=font-family:helvetica;font-weight:200;font-size:13px;>Rating: ',$rating,'<br />Full Resolution: ',$fullres,'<br />Photographer: ',$fullname,'</span><br /><br /><img src=',$imagebig2[$iii],' height=',$widthmed,'px width=',$heightmed,'px  />" data-original-title="',$title,'" onmousedown="return false" oncontextmenu="return false;" class="phototitletest" style="margin-top:20px;clear:right;float:bottom;margin:auto;" src="',$imagebig2[$iii],'" height="',$widthnew,'px" width="',$heightnew,'px" /></a>
    <div style="text-align:center;font-size:14px;clear:both;padding-top:10px;">',$price,'&nbsp;|&nbsp;',$rating,'
     </div></div>
     
    <script>  
    $(function ()  
    { $("#popover3").popover();  
    });  
    </script>
    
    </div>';
    
} //end for loop


}//end if clause

?>
