<?php

//connect to the database
require "db_connection.php";
require "functionsnav.php";


?>

<!DOCTYPE html>

<html>
<head>

	<link rel="stylesheet" href="css/bootstrapNew.css" type="text/css" />
    <link rel="stylesheet" href="market/css/reset.css" type="text/css" />
    <link rel="stylesheet" href="css/text2.css" type="text/css" />
    <link rel="stylesheet" href="market/css/960_24.css" type="text/css" />
    <link rel="stylesheet" href="market/css/index.css" type="text/css"/> 
	<link rel="stylesheet" type="text/css" href="market/css/all.css"/>
    <link rel="stylesheet" href="market/css/style.css" type="text/css"/> 

	<script type="text/javascript" href="js/bootstrap-dropdown.js"></script>
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript" src="http://masonry.desandro.com/jquery.masonry.min.js"></script>
<script type="text/javascript" src="https://raw.github.com/desandro/imagesloaded/master/jquery.imagesloaded.min.js"></script>
    <link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>

    
   <style type="text/css">


 .statoverlay

{
opacity:.8;
filter:alpha(opacity=40);
z-index:1;
transition: opacity .5s;
-moz-transition: opacity .5s;
-webkit-transition: opacity .5s;
-o-transition: opacity .5s;
}



 .statoverlay2

{
opacity:.6;
filter:alpha(opacity=40);
z-index:1;
transition: opacity .5s;
-moz-transition: opacity .5s;
-webkit-transition: opacity .5s;
-o-transition: opacity .5s;
}
                         

.item {
  margin: 10px;
  float: left;
  border: 2px solid transparent;
}

.item:hover {
  margin: 10px;
  float: left;
  border: 2px solid black;
}

</style>

<body style="background-color: #fff; min-width:1220px;">

<?php navbarnew(); ?>

                <div id="thepics">

                
         
				<?php 

                
                $newestphotos = mysql_query("SELECT * FROM photos ORDER BY id DESC LIMIT 0,51");
                $numphotos = mysql_num_rows($newestphotos);
                
                for($iii = 0; $iii < $numphotos; $iii++) {
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
                        
                    </div>
				</div>';
                }
                
                
                echo'
                     </div>

            
<!--AJAX CODE HERE-->
   <div class="grid_6 push_9" style="padding-top:50px;">
   <div id="loadMorePics" style="display: none; text-align: center;font-family:arial,helvetica neue; font-size:15px;">Loading More Photos&hellip;</div>
   </div>';


echo '<script>

var last = 0;

	$(window).scroll(function(){
		if($(window).scrollTop() > $(document).height() - $(window).height()-100) {
			if(last != $(".fPic:last").attr("id")) {
				$("div#loadMorePics").show();
				$.ajax({
					url: "loadMoreNewPics2.php?lastPicture=" + $(".fPic:last").attr("id"),
					success: function(html) {
						if(html) {
							$("#thepics").append(html);
							$("div#loadMorePics").hide();
                            $container.masonry( "appended", $(".masonryImage"), true);

						}
					}
				});
				last = $(".fPic:last").attr("id");
			}
		}
	});
</script>';

                            
?>

		
  <script type="text/javascript">

        var $container = $('#thepics');
          $container.imagesLoaded(function(){
            $container.masonry({
              itemSelector : '.masonryImage',
              columnWidth : 280     //Added gutter to simulate margin
          });
        });

  </script>
    
    
</body>
</html>	

