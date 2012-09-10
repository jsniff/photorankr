<?php 

require("db_connection.php");

echo'<script type="text/javascript" src="js/jquery.wookmark.js"></script>';


if($_GET['lastPicture']) {
    
    $query="SELECT * FROM sets WHERE id < ".$_GET['lastPicture']." ORDER BY id DESC LIMIT 0,8";
    $result=mysql_query($query);   
    $numexhibits = mysql_num_rows($result);


echo'<div id="main" role="main">
    <ul id="tiles">';
    
for($iii=1; $iii <= $numexhibits; $iii++) {
	$coverpic = mysql_result($result, $iii-1, "cover");
    $coverpic2 = str_replace("userphotos/","userphotos/medthumbs/",$coverpic);
    $caption = mysql_result($result, $iii-1, "title");
    $set_id = mysql_result($result, $iii-1, "id");
    $pulltopphoto = mysql_query("SELECT source FROM photos WHERE set_id = '$set_id' ORDER BY votes DESC LIMIT 5");
    if($coverpic == '') {
        $coverpic = mysql_result($pulltopphoto, 0, "source");
        $coverpic2 = str_replace("userphotos/","userphotos/medthumbs/",$coverpic);

    }

    $thumb1 = mysql_result($pulltopphoto, 1, "source");
    $thumb1 = str_replace("userphotos/","userphotos/medthumbs/",$thumb1);
    $thumb2 = mysql_result($pulltopphoto, 2, "source");
    $thumb2 = str_replace("userphotos/","userphotos/medthumbs/",$thumb2);
    $thumb3 = mysql_result($pulltopphoto, 3, "source");
    $thumb3 = str_replace("userphotos/","userphotos/medthumbs/",$thumb3);
    $thumb4 =mysql_result($pulltopphoto, 4, "source");
    $thumb4 = str_replace("userphotos/","userphotos/medthumbs/",$thumb4);

    list($width, $height) = getimagesize($coverpic);
    $imgratio = $height / $width;
    $heightls = $height / 3.2;
    $widthls = $width / 3.2;
        
    if($widthls < 240) {
        $heightls = $heightls * ($heightls/$widthls);
        $widthls = 250;
    }

    $owner = mysql_result($result, $iii-1, "owner");
    $exhibitquery = mysql_query("SELECT * FROM photos WHERE set_id = '$set_id'");
    $numberphotos = mysql_num_rows($exhibitquery);
    
    if($numberphotos < 1) {
        continue;
    }
   
    for($i = 0; $i < $numberphotos; $i++) {
    $points += mysql_result($exhibitquery, $i, "points");
    $votes += mysql_result($exhibitquery, $i, "votes");
    }
    
    $score = number_format(($points/$votes),2);
    $price = mysql_result($exhibitquery, $iii, "price");
    if($price != 'Not For Sale') {
                    $price = '$' . $price;
                }
                elseif($price == 'Not For Sale') {
                    $price = 'NFS';
                }
    
    $avgscorequery = mysql_query("UPDATE sets SET avgscore = '$score' WHERE id = '$set_id'");
    
    $ownerquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$owner'");
    $firstname = mysql_result($ownerquery, 0, "firstname");
    $lastname = mysql_result($ownerquery, 0, "lastname");
    $fullname = $firstname . " " . $lastname;
    
	$userid = mysql_result($ownerquery, 0, "user_id");
    
    echo'<li style="width:240px;list-style-type:none;position:relative;" class="fPic" id="',$set_id,'"><a style="text-decoration:none;" href="viewprofile.php?u=',$userid,'&view=exhibits&set=',$set_id,'">
    
        <div style="padding-top:5px;padding-left:3px;font-size:13px;text-decoration:none;color:#000;font-weight:200;"><span style="font-size:15px;font-weight:400;">',$caption,'</span><br />',$numberphotos,' Photos</div>
<hr />

    <img style="margin-top:-6px;" onmousedown="return false" oncontextmenu="return false;" src="http://www.photorankr.com/',$coverpic2,'" alt="',$setname[$iii],'" height="',$heightls,'px" width="',$widthls,'px" />';
    
    
    
    if($thumb4) {
        echo'
            <div style="margin-top:5px;">
            <img style="float:left;padding:4px;" src="http://www.photorankr.com/',$thumb1,'" width="112" height="110" />
            <img style="float:left;padding:4px;" src="http://www.photorankr.com/',$thumb2,'" width="112" height="110" />
            <img style="float:left;padding:4px;" src="http://www.photorankr.com/',$thumb3,'" width="112" height="110" />
            <img style="float:left;padding:4px;" src="http://www.photorankr.com/',$thumb4,'" width="112" height="110" />
            </div>';
    }
    
    echo'
    </a>
    
    </li>';
        
    } //end for loop

echo'</ul>';
        
    ?>
    
    <!-- Once the page is loaded, initalize the plug-in. -->
  <script type="text/javascript">
    $(document).ready(new function() {
      // Prepare layout options.
      var options = {
        autoResize: true, // This will auto-update the layout when the browser window is resized.
        container: $('#main'), // Optional, used for some extra CSS styling
        offset: 4, // Optional, the distance between grid items
        itemWidth: 290 // Optional, the width of a grid item
      };
      
      // Get a reference to your grid items.
      var handler = $('#tiles li');
      
      // Call the layout function.
      handler.wookmark(options);
      
    });
  </script>

    
 <?php

echo'
</div>';

}//end if clause

?>
