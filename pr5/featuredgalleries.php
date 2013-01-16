<?php

//connect to the database
require "db_connection.php";
require "functions.php";

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
    
?>

<!DOCTYPE HTML>
<head>
	
    <meta charset="UTF-8">
	<title> Featured Galleries.</title>
    <meta name="Keywords" content="photorankr blog, photography blog, blog, photos, sharing photos, photo sharing, photography, stock photography, stock, photography club, sell photos, sell photography, where to sell my photography, good sites for selling photography, making money from photography, making money off photography, social networking, social network, social networks, where to sell my photos, good sites for selling photos, good site to sell photos, making money from photos, social stock, photo licensing, royalty free photos, crowdsource, crowdsourcing photos, crowdsourced photos">
    <meta name="Description" content="Browse through the curated PhotoRankr galleries.">

	<link rel="stylesheet" type="text/css" href="css/style.css"/>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>
	<link rel="stylesheet" type="text/css" href="css/960grid.css"/>
	<link rel="stylesheet" type="text/css" href="css/reset.css"/>
    <link rel="stylesheet" type="text/css" href="css/main3.css"/>            
    <link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>
    
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

        .pic_1 {
        padding:2px;
        }
        
        .pic_2 {
        padding:2px;
        }
        
	</style>
</head>
<body id="body">
	
   <?php navbar(); ?> 
    
 <div class="navbar-top">
	<div class="navbar-inner-spec" style="margin-top:35px;">
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
	
<!--end bootstrap navbar-->
<div class="container_24">

        <div style="font-family:'helvetica neue',helvetica,arial;font-weight:200;font-size:26px;margin-top:30px;padding-bottom:40px;">
            Featured Galleries
        </div> 

	 <!--the bid column right-->
     
     <?php
     
        $galleryquery = mysql_query("SELECT * FROM featuredgallery ORDER BY id DESC");
        $numgalleries = mysql_num_rows($galleryquery);
                
        for($iii=0; $iii<$numgalleries; $iii++) {
            
            $id = mysql_result($galleryquery,$iii,'id');
            $name = mysql_result($galleryquery,$iii,'name');
            $about = mysql_result($galleryquery,$iii,'about');
            $photos = mysql_result($galleryquery,$iii,'photos');
            
            $photosarray = explode(" ", $photos);
            
            echo'<div class="grid_12 gallery" style="float:left;">
                    <header>
                        <br />
                        <a style="text-decoration:none;color:#333;" href="viewgallery.php?g=',$id,'"><span style=\'font-family:"helvetica neue",helvetica,arial;font-weight:200;font-size:20px;\'>',$name,'</span></a>				
                        <br />
                        <div class="line" style="background:#62a2de;margin-bottom:10px;"></div>
                    </header>';

                
                $photo1query = mysql_query("SELECT source FROM photos WHERE id = $photosarray[0]");
                $photo1 = mysql_result($photo1query,0,'source');
                
                $photo2query = mysql_query("SELECT source FROM photos WHERE id = $photosarray[1]");
                $photo2 = mysql_result($photo2query,0,'source');
                
                $photo3query = mysql_query("SELECT source FROM photos WHERE id = $photosarray[2]");
                $photo3 = mysql_result($photo3query,0,'source');
                
                echo'<div class="omega grid_6" style="margin:0;height:350px;overflow:hidden;" >	
                    <div class="pic_1">
                        <img src="',$photo1,'" class="gallery_pic"/>
                    </div>
                    <div class="pic_1">
                        <img src="',$photo2,'" class="gallery_pic"/>
                    </div>
                    <div class="pic_1">
                        <img src="',$photo3,'" class="gallery_pic"/>
                    </div>
                    </div>';
    
                $photo4query = mysql_query("SELECT source FROM photos WHERE id = $photosarray[3]");
                $photo4 = mysql_result($photo4query,0,'source');
                
                $photo5query = mysql_query("SELECT source FROM photos WHERE id = $photosarray[4]");
                $photo5 = mysql_result($photo5query,0,'source');
                
                $photo6query = mysql_query("SELECT source FROM photos WHERE id = $photosarray[5]");
                $photo6 = mysql_result($photo6query,0,'source');

                echo'<div class="omega grid_5" style="margin:0;height:350px;overflow:hidden;">
                     <div class="pic_2">
                        <img src="',$photo4,'" class="gallery_pic"/>
                    </div>
                    <div class="pic_2">
                        <img src="',$photo5,'" class="gallery_pic"/>
                    </div>
                    <div class="pic_2">
                        <img src="',$photo6,'" class="gallery_pic"/>
                    </div>
                    </div>';
            
            echo'</div>';
        
        }
    
     ?>
     
        
    
</div>

<br /><br /><br /><br />

<?php footer(); ?>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script src="../js/bootstrap-dropdown.js"></script>
    <script type="text/javascript">  
        $(document).ready(function () {  
            $('.dropdown-toggle').dropdown();  
        });  
</script> 

</body>
</html>
