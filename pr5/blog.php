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
	<title> PhotoRankr Blog </title>
    <meta name="Keywords" content="photorankr blog, photography blog, blog, photos, sharing photos, photo sharing, photography, stock photography, stock, photography club, sell photos, sell photography, where to sell my photography, good sites for selling photography, making money from photography, making money off photography, social networking, social network, social networks, where to sell my photos, good sites for selling photos, good site to sell photos, making money from photos, social stock, photo licensing, royalty free photos, crowdsource, crowdsourcing photos, crowdsourced photos">
    <meta name="Description" content="The official PhotoRankr blog. Read photography articles and browse through featured galleries of work.">

	<link rel="stylesheet" type="text/css" href="css/style.css"/>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>
	<link rel="stylesheet" type="text/css" href="css/960grid.css"/>
	<link rel="stylesheet" type="text/css" href="css/reset.css"/>           
    <link rel="stylesheet" type="text/css" href="css/main3.css"/>
 
    <link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    
    <!--GOOGLE ANALYTICS CODE-->
<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-28031297-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'https://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>


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
<body style="background-image:url('graphics/paper.png');background-repeat:repeat;">
	
   <?php navbar(); ?> 
    
 <div class="navbar-top">
	<div class="navbar-inner-spec" style="margin-top:35px;">
		<div class="container" style="width:1000px;">
			<ul class="navbar" style="margin-left:38px;margin-top:25px;">
            
				<a class="subnav_a" href="blog.php"> 
				<li class="nav"> 
					<div class="nav-tab" >
						<p style="color:#333;"> Blog </p>
					</div>	
				</li></a>
                
                <a class="subnav_a" href="featuredgalleries.php"> 
					<li class="nav"> 
					<div class="nav-tab">
						<p style="color:#777;"> Featured Galleries </p>
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

	 <!--the bid column right-->
	 <div class="grid_12">
     
     <?php
     
        $galleryquery = mysql_query("SELECT * FROM featuredgallery ORDER BY id DESC");
        $numgalleries = mysql_num_rows($galleryquery);
        
        for($iii=0; $iii<$numgalleries; $iii++) {
            
            $id = mysql_result($galleryquery,$iii,'id');
            $name = mysql_result($galleryquery,$iii,'name');
            $about = mysql_result($galleryquery,$iii,'about');
            $photos = mysql_result($galleryquery,$iii,'photos');
            
            $photosarray = explode(" ", $photos);
            
            echo'<div class="grid_12 gallery" style="padding-bottom:20px;">
                    <header>
                        <br />
                        <a style="text-decoration:none;color:#333;" href="viewgallery.php?g=',$id,'"><span style="font-size:20px;"><strong>Featured Gallery</strong></span> | <span style=\'font-family:"helvetica neue",helvetica,arial;font-weight:200;font-size:20px;\'>',$name,'</span></a>				
                        <br />
                        <div class="line" style="background:#62a2de;margin-bottom:10px;"></div>
                    </header>';

                
                $photo1query = mysql_query("SELECT source FROM photos WHERE id = $photosarray[0]");
                $photo1 = mysql_result($photo1query,0,'source');
                $photo1 = str_replace('userphotos/','userphotos/medthumbs/',$photo1);

                $photo2query = mysql_query("SELECT source FROM photos WHERE id = $photosarray[1]");
                $photo2 = mysql_result($photo2query,0,'source');
                $photo2 = str_replace('userphotos/','userphotos/medthumbs/',$photo2);
                                
                $photo3query = mysql_query("SELECT source FROM photos WHERE id = $photosarray[2]");
                $photo3 = mysql_result($photo3query,0,'source');
                $photo3 = str_replace('userphotos/','userphotos/medthumbs/',$photo3);

                echo'<div class="omega grid_6" style="margin:0;height:400px;overflow:hidden;" >	
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
                $photo4 = str_replace('userphotos/','userphotos/medthumbs/',$photo4);

                $photo5query = mysql_query("SELECT source FROM photos WHERE id = $photosarray[4]");
                $photo5 = mysql_result($photo5query,0,'source');
                $photo5 = str_replace('userphotos/','userphotos/medthumbs/',$photo5);

                $photo6query = mysql_query("SELECT source FROM photos WHERE id = $photosarray[5]");
                $photo6 = mysql_result($photo6query,0,'source');
                $photo6 = str_replace('userphotos/','userphotos/medthumbs/',$photo6);

                echo'<div class="omega grid_5" style="margin:0;height:400px;overflow:hidden;">
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
            
            echo'</div><br /><br /><br /><br />';
        
        }
     
     ?>
     
    </div> <!-- end of 12 grid -->
        
    
		<div class="grid_12" style="margin-top:20px;"><!--Where all the text goes-->
            
        <?php
        
        $blogquery = mysql_query("SELECT * FROM entries ORDER BY id DESC");
        $numposts = mysql_num_rows($blogquery);
        
        for($iii=0; $iii < $numposts; $iii++) {
            
            $id = mysql_result($blogquery,$iii,'id');
            $title = mysql_result($blogquery,$iii,'title');
            $contents = mysql_result($blogquery,$iii,'contents');
            $date = mysql_result($blogquery,$iii,'date');
            $author = mysql_result($blogquery,$iii,'author');
            $contentsshort = (strlen($contents) > 1500) ? substr($contents,0,1490). " <br /><a href='post.php?a=".$id."'>Read More&#8230;</a>" : $contents;
            $type = mysql_result($blogquery,$iii,'type');
            $user_id = mysql_result($blogquery,$iii,'user_id');
            
            $getprofilepic = mysql_query("SELECT profilepic FROM userinfo WHERE user_id = '$user_id'");
            $profilepic = mysql_result($getprofilepic,0,'profilepic');
    
            echo'
                    <div class="grid_12 post" style="padding-bottom:35px;"><!--post-->
                    
                <header>
                
                    <div style="float:left;" class="pic_container">
                        <img style="padding:8px 8px 8px 0px;" src="',$profilepic,'" class="post_pic" width="50">
                    </div>
                    
                        <div style="float:left;width:400px;">
                            <a style="color:#333;text-decoration:none;" href="post.php?a=',$id,'">
                            <div id="blogheader" style="padding:5px;"> ',$title,' </div>
                            <div id="blogsubtext" style="padding-left:5px;"> ',$date,' </div>
                            <div id="blogsubtext" style="padding-left:5px;"> by ',$author,' </div>
                            </a>
                        </div>
                </header>
                
                <div style="clear:both;padding-top:10px;">
                    <article>
                        <section id="blogcontents">
                        ',$contentsshort,'
                        </section>
                    </article>
                </div>
            </div>';
        
        }
        
        ?>
    
	</div>	
	</div>
    
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
