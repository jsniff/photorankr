<?php 

require("db_connection.php");

if($_GET['lastPicture']) {

$category = htmlentities($_GET['c']);

if($category == '') {
$query = mysql_query("SELECT * FROM photos WHERE id < ".$_GET['lastPicture']." ORDER BY id DESC LIMIT 0, 20") or die(mysql_error());
}

elseif($category == 'top') {
$query = mysql_query("SELECT * FROM photos ORDER BY points DESC LIMIT 0,60");
$numresults = mysql_num_rows($query);
}

elseif($category == 'trending' || $category == '') {
$query = mysql_query("SELECT * FROM photos ORDER BY score DESC LIMIT 0,60");
$numresults = mysql_num_rows($query);
}

elseif($category == 'pop') {
$query = mysql_query("SELECT * FROM photos WHERE views > 120 ORDER BY faves DESC LIMIT 0,60");
$numresults = mysql_num_rows($query);
}

elseif($category == 'deal') {
$deal = '10.00';
$twoweeksago = time() - 1209600;
$query = mysql_query("SELECT * FROM photos WHERE price < '$deal' AND time > '$twoweeksago' ORDER BY points DESC LIMIT 0,60");
$numresults = mysql_num_rows($query);
}

elseif($c == 'f') {
$searchterm = htmlentities($_GET['searchterm']);
$query = mysql_query("SELECT * FROM photos WHERE caption LIKE '%$searchterm%' ORDER BY (points/votes) DESC LIMIT 0,60");
$numresults = mysql_num_rows($query);
}


//DISPLAY 20 NEWEST OF ALL PHOTOS
for($iii=0; $iii < 20; $iii++) {
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
    $widthnew = $width / 6.5;
    $heightnew = $height / 6.5;

    echo'<div class="fPic"  id="',$imageid,'" style="width:230px;height:250px;overflow:hidden;float:left;"><a href="fullsize2.php?imageid=',$imageid,'"><img onmousedown="return false" oncontextmenu="return false;" class="phototitle" style="margin-right:30px;margin-top:20px;clear:right;" src="',$imagebig2[$iii],'" height="',$widthnew,'px" width="',$heightnew,'px" /></a>
    <div style="text-align:center;font-size:14px;clear:both;">',$price,'&nbsp;|&nbsp;
     
     <a style="font-size:18px;" href="#" id="popover',$iii,'" rel="popover" data-content="<h5>Rating: ',$rating,'</h5><h5>License: ',$license,'<h5 /><h5>Photographer: ',$fullname,'</h5><h5>Downloads: ',$sold,'</h5>" data-original-title="',$title,'"><span style="font-size:12px;">Photo Info',$category,'</span></a>
          
     </div>';
     ?>
     
    <script>  
    $(function ()  
    { $("#popover<?php echo $iii; ?>").popover();  
    });  
    </script>
    
    <?php
    echo'
    </div>';
} //end for loop


}//end if clause

?>
