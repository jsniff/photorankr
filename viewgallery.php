<?php

//connect to the database
require "db_connection.php";
require "functionsnav.php";

//start the session
session_start();

    // if login form has been submitted
    if (htmlentities($_GET['action']) == "login") { 
        login();
    }
    else if(htmlentities($_GET['action']) == "logout") { 
        logout();
    }

    $email = $_SESSION['email'];
    
    //Get article
    
    $gallery = htmlentities($_GET['g']);
    
    $galleryquery = mysql_query("SELECT * FROM featuredgallery WHERE id = $gallery LIMIT 0,1");
    
    $id = mysql_result($galleryquery,0,'id');
    $name = mysql_result($galleryquery,0,'name');
    $about = mysql_result($galleryquery,0,'about');
    $photos = mysql_result($galleryquery,0,'photos');
    $photosarray = explode(" ", $photos);
    $numphotos = count($photosarray);
    
    //Views counter
    $viewsquery = mysql_query("UPDATE featuredgallery SET views = (views + 1) WHERE id = '$gallery'");
?>


<!DOCTYPE HTML>
<head>

	<meta charset="UTF-8">
	<title> Featured Gallery | <?php echo $name; ?></title>
    <meta name="Keywords" content="<?php echo $name; ?>,photorankr blog, photography blog, blog, photos, sharing photos, photo sharing, photography, stock photography, stock, photography club, sell photos, sell photography, where to sell my photography, good sites for selling photography, making money from photography, making money off photography, social networking, social network, social networks, where to sell my photos, good sites for selling photos, good site to sell photos, making money from photos, social stock, photo licensing, royalty free photos, crowdsource, crowdsourcing photos, crowdsourced photos">
    <meta name="Description" content="Featured Gallery | <?php echo $name; ?>">

	<link rel="stylesheet" href="css/blog.css" type="text/css"/>
    <link rel="stylesheet" href="css/style.css" type="text/css"/>
	<link rel="stylesheet" href="css/960_24_col.css" type="text/css"/>
	<link rel="stylesheet" href="css/bootstrapNew.css" type="text/css"/>
	<link rel="stylesheet" href="css/reset.css" />
    
     <script type="text/javascript">var switchTo5x=true;</script>
    <script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
    <script type="text/javascript" src="http://s.sharethis.com/loader.js"></script>

	<style type="text/css" >
        
        .position 	
		{
		margin:42px 0 0 15px;
		color:#fff;
		}
		.margin_none
		{
		margin-left: -5em;
		}
		.navbar-inner-spec
		{
			min-height: 60px;
            padding-right: 20px;
            padding-left: 20px;
            background-color: #ccc;
            -webkit-box-shadow: 0 1px 3px rgba(0, 0, 0, 0.25), inset 0 -1px 3px #666;   
     -moz-box-shadow: 0 1px 3px rgba(0, 0, 0, 0.25), inset 0 -1px 3px #666;
          box-shadow: 0 1px 3px rgba(0, 0, 0, 0.25), inset 0 -1px 3px #666;
          background-image: url('graphics/noise.png');
          background-repeat:repeat-x, repeat-y;
		}

	</style>
</head>
<body id="body">
	
    <?php navbarnew(); ?> 
    
 <div class="navbar-top">
	<div class="navbar-inner-spec" style="margin-top:45px;">
		<div class="container" style="width:1000px;">
			<ul class="navbar" style="margin-left:38px;margin-top:25px;">
            
				<a class="subnav_a" href="blog.php"> 
				<li class="nav"> 
					<div class="nav-tab" >
						<p style="color:#777;"> Blog </p>
					</div>	
				</li></a>
                
                <a class="subnav_a" href="featuredgalleries.php"> 
					<li class="nav"> 
					<div class="nav-tab">
						<p style="color:#333;"> Featured Galleries </p>
					</div>	
				</li></a>
                
                <a class="subnav_a" href="archives.php"> 
					<li class="nav"> 
					<div class="nav-tab">
						<p style="color:#777;"> Archives </p>
					</div>	
				</li></a>
                
			</ul>
		</div>
	</div>
</div>

    <div class="container_24">

	 <!--the bid column right-->
	 <div class="grid_24 push_2" style="margin-top:20px;">
	 
       <span style="font-size:20px;"><strong>Featured Gallery</strong></span> | <span style="font-family:'helvetica neue',helvetica,arial;font-weight:200;font-size:20px;"><?php echo $name; ?></span></a>
        
        <?php
            
            echo'<div style="margin-top:40px;">';
            
            for($iii=0; $iii < $numphotos; $iii++) {
            
                $photoquery = mysql_query("SELECT id,source,caption,points,votes,about,price,emailaddress FROM photos WHERE id = $photosarray[$iii]");
                $source = mysql_result($photoquery,0,'source');
                //$source = str_replace('userphotos/','userphotos/bigphotos/',$source);
                $imageid = mysql_result($photoquery,0,'id');
                $caption = mysql_result($photoquery,0,'caption');
                $points = mysql_result($photoquery,0,'points');
                $votes = mysql_result($photoquery,0,'votes');
                $rank = number_format(($points/$votes),2);
                $price = mysql_result($photoquery,0,'price');
                if($price != 'Not For Sale') {
                    $price = '$'.$price;
                }
                $about = mysql_result($photoquery,0,'about');
                $owner = mysql_result($photoquery,0,'emailaddress');
                
                //ownerpic
                $ownerpicquery = mysql_query("SELECT user_id,firstname,lastname,profilepic FROM userinfo WHERE emailaddress = '$owner'");
                $ownerid = mysql_result($ownerpicquery,0,'user_id');
                $ownerpic = mysql_result($ownerpicquery,0,'profilepic');
                $ownerfirst = mysql_result($ownerpicquery,0,'firstname');
                $ownerlast = mysql_result($ownerpicquery,0,'lastname');
                
                echo'<div style="float:left;margin-left:80px;margin-bottom:50px;">
                        
                        <div style="font-weight:200;font-family:\'helvetica neue\',helvetica,arial;font-size:17px;">',$caption,'</div>
                        
                        <br />
                        
                        <img style="width:600px;" src="',$source,'" alt="',$caption,'" />
                        
                        <br /><br />
                        
                        <div style="width:600px;">
                        
                            <a href="viewprofile.php?u=',$ownerid,'"><img src="',$ownerpic,'" style="width:35px;" /></a>&nbsp;&nbsp;<a style="color:#333;font-size:13px;" href="viewprofile.php?u=',$ownerid,'">',$ownerfirst,' ',$ownerlast,'</a>&nbsp;&nbsp;
                            <span style="font-size:18px;"><strong>',$rank,'</strong></span><span style="font-size:13px;">/10.0 | <span style="font-size:15px;"><strong>',$price,'</strong></span>
                            <a style="float:right;" href="fullsizemarket.php?imageid=',$imageid,'">Purchase</a><a style="float:right;margin-right:5px;" href="fullsize.php?imageid=',$imageid,'">View</a>
                            
                        </div>
                        
                        <br />
                        
                        <div style="font-weight:200;font-family:\'helvetica neue\',helvetica,arial;font-size:14px;color:#555;width:600px;">',$about,'</div>
                        
                    </div>';
            
            }
        
            echo'</div>';
     
        ?>
        
     </div>
     
</div>
    
<?php footer(); ?>

    <script type="text/javascript">stLight.options({publisher: "2c31e7c5-bd4b-4757-8ea0-da2dc02c3404"});</script>
<script>
var options={ "publisher": "2c31e7c5-bd4b-4757-8ea0-da2dc02c3404", "position": "left", "ad": { "visible": false, "openDelay": 5, "closeDelay": 0}, "chicklets": { "items": ["facebook", "twitter", "pinterest", "email", "sharethis"]}};
var st_hover_widget = new sharethis.widgets.hoverbuttons(options);
    </script>


</body>
</html>
