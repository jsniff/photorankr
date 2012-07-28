<?php

//connect to the database
require "db_connection.php";
require "functions.php";


?>

<!DOCTYPE html>

<html>
<head>

	<link rel="stylesheet" href="market/css/bootstrapnew2.css" type="text/css" />
    <link rel="stylesheet" href="market/css/reset.css" type="text/css" />
    <link rel="stylesheet" href="text.css" type="text/css" />
    <link rel="stylesheet" href="market/css/960_24.css" type="text/css" />
    <link rel="stylesheet" href="market/css/index.css" type="text/css"/> 
    <link rel="stylesheet" href="market/css/itunes.css" type="text/css"/> 
	<link rel="stylesheet" type="text/css" href="market/css/all.css"/>
    <link rel="stylesheet" href="market/css/style.css" type="text/css"/> 

	<script type="text/javascript" href="js/bootstrap-dropdown.js"></script>
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript" src="http://masonry.desandro.com/jquery.masonry.min.js"></script>
<script type="text/javascript" src="https://raw.github.com/desandro/imagesloaded/master/jquery.imagesloaded.min.js"></script>
    <link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>

    
    <style type="text/css">
.navbar-inner
{
	text-align:center;
}

.center.navbar .nav,
.center.navbar .nav > li {
    float:none;
    display:inline-block;
    *display:inline; /* ie7 fix */
    *zoom:1; /* hasLayout ie7 trigger */
    vertical-align: top;
}

.center .navbar-inner {
    text-align:center;
}
.navbar .nav,
.navbar .nav > li {
    float:none;
    display:inline-block;
    *display:inline; /* ie7 fix */
    *zoom:1; /* hasLayout ie7 trigger */
    vertical-align: top;
}
.center .dropdown-menu {
    text-align: left;
}
</style>

<body style="background-color: #fff; min-width:1220px;">

<?php navbarnew(); ?>


				<div id="container2" style="z-index:1;position:relative;top:75px;margin-left:50px;">
                
         
				<?php 

                
                $newestphotos = mysql_query("SELECT * FROM photos ORDER BY id DESC LIMIT 0,51");
                $numphotos = mysql_num_rows($newestphotos);
                
                for($iii = 0; $iii < $numphotos; $iii++) {
                $photo[$iii] = mysql_result($newestphotos,$iii,'source');
                $photobig[$iii] = str_replace("userphotos/", "$_SERVER[DOCUMENT_ROOT]/userphotos/", $photo[$iii]);
                $photo[$iii] = str_replace("$_SERVER[DOCUMENT_ROOT]/userphotos/", "http://photorankr.com/userphotos/medthumbs/", $photobig[$iii]);
                $imageid[$iii] = mysql_result($newestphotos,$iii,'id');
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
                <div class="phototitle5">
					<a href="fullsize2.php?imageid=',$imageid[$iii],'"><img style="text-align:center;padding-bottom:20px;min-width:265px;" src="',$photo[$iii],'" height="',$heightnew,'px" width="',$widthnew,'px" /></a>
                    <div style="background-color:#eee;height:35px;margin-top:-14px;">
                    <img style="position:relative;left:3px;top:2px;float:left;" class="dropshadow" src="',$profilepic[$iii],'" height="30" width="30" />&nbsp;<div style="font-size:14px;font-weight:150;margin-left:7px;margin-top:6px;font-family:helvetica neue;float:left;">"',$caption[$iii],'"</div></div>
                    </div>
				</div>';
                }
                
                
                
                ?>
		
		</div>
  <script type="text/javascript">

    $(document).ready(function() {

        var $container = $('#container2');
          $container.imagesLoaded(function(){
            $container.masonry({
              itemSelector : '.masonryImage',
              columnWidth : 280     //Added gutter to simulate margin
          });
        });

    });
  </script>
    
    
</body>
</html>	

