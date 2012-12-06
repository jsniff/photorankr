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
	<title> PhotoRankr blog archives. Browse all previous photography articles.</title>
    <meta name="Keywords" content="photorankr blog, photography blog, blog, photos, sharing photos, photo sharing, photography, stock photography, stock, photography club, sell photos, sell photography, where to sell my photography, good sites for selling photography, making money from photography, making money off photography, social networking, social network, social networks, where to sell my photos, good sites for selling photos, good site to sell photos, making money from photos, social stock, photo licensing, royalty free photos, crowdsource, crowdsourcing photos, crowdsourced photos">
    <meta name="Description" content="PhotoRankr blog archives. Browse all previous photography articles.">
    
	<link rel="stylesheet" type="text/css" href="css/style.css"/>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>
	<link rel="stylesheet" type="text/css" href="css/960grid.css"/>
	<link rel="stylesheet" type="text/css" href="css/reset.css"/>            
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

	</style>
</head>
<body id="body">
	
    <?php navbar(); ?> 
    
 <div class="navbar-top">
	<div class="navbar-inner-spec" style="margin-top:0px;">
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
						<p style="color:#777;"> Featured Galleries </p>
					</div>	
				</li></a>
                
                <a class="subnav_a" href="archives.php"> 
					<li class="nav"> 
					<div class="nav-tab">
						<p style="color:#333;"> Archives </p>
					</div>	
				</li></a>
                
			</ul>
		</div>
	</div>
</div>

<div class="container_24">

	 <!--the bid column right-->
	 <div class="grid_20 push_2">
     
     
        <div style="font-family:'helvetica neue',helvetica,arial;font-weight:200;font-size:26px;margin-top:30px;">
            Archives
        </div>
        
        <div style="list-style-type:none;margin-top:20px;">
            <ul>
        
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
            
            echo'<li style="padding:10px;padding-top:20px;"><img style="width:50px;padding-right:8px;" src="',$profilepic,'" /><a style="text-decoration:none;color:#333;" href="post.php?a=',$id,'">',$title,'</a><br /><div style="padding-left:60px;font-size:13px;color:#555;">',$date,'</div></li>';
        
        }
        
        ?>
        
            </ul>
        </div>
        
     </div>	
    
    
</div>

<br />
<br />
<br />
    
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
