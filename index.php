<?php

if($_SERVER['HTTPS']!="on")
  {
     $redirect= "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
     header("Location:$redirect");
  }
  
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


$query="SELECT * FROM photos ORDER BY id DESC LIMIT 0, 16";
$result=mysql_query($query);
$numberofpics=mysql_num_rows($result);

//QUERY FOR NOTIFICATIONS
$currentnots = "SELECT * FROM userinfo WHERE emailaddress = '$email'";
$currentnotsquery = mysql_query($currentnots);
$currentnotsresult = mysql_result($currentnotsquery, 0, "notifications");

//notifications query reset 
if($currentnotsresult > 0) {
$notsquery = "UPDATE userinfo SET notifications = 0 WHERE emailaddress = '$email6'";
$notsqueryrun = mysql_query($notsquery); }

//DISCOVER SCRIPT
    
  //get the users information from the database
  $likesquery = "SELECT * FROM userinfo WHERE emailaddress='$email'";
  $likesresult = mysql_query($likesquery) or die(mysql_error());
  $discoverseen = mysql_result($likesresult, 0, "discoverseen");

  //find out what they like
  $likes = mysql_result($likesresult, 0, "viewLikes");
    if($likes=="") {
		$nolikes = 1;
        		
	}

  $likes .= "  ";
  $likes .= mysql_result($likesresult, 0, "buyLikes");

  //create an array from what they like
  $likesArray = explode("  ", $likes);

  //loop through the array to format the likes in the proper format for the query
  $formattedLikes = "%";
  for($iii=0; $iii < count($likesArray); $iii++) {
    $formattedLikes .= $likesArray[$iii];
    $formattedLikes .= "%";
  }

    //make an array of the photos they have already seen
  if($discoverseen != "") {
    $discoverArray = explode(" ", $discoverseen);
    $discoverFormatted = "";
    for($iii=0; $iii < count($discoverArray)-1; $iii++) {
      $discoverFormatted .= "'";
      $discoverFormatted .= $discoverArray[$iii];
      $discoverFormatted .= "', ";
    }
    $discoverFormatted .= "'";
    $discoverFormatted .= $discoverArray[count($discoverArray)-1];
    $discoverFormatted .= "'";
  }
  
  //select the image that they will be seeing next
  //delineate between whether they have used discover feature before
  if($discoverseen != "") {     //get the photos that match this person's view interests
    $viewquery = "SELECT *, MATCH(maintags, singlecategorytags, singlestyletags) AGAINST ('$formattedLikes') AS matching FROM photos WHERE MATCH(maintags, singlecategorytags, singlestyletags) AGAINST ('$formattedLikes') AND id NOT IN(" . $discoverFormatted . ") ORDER BY matching DESC, points DESC LIMIT 0, 1";
    $viewresult = mysql_query($viewquery) or die(mysql_error());
  }
  else {
    //get the photos that match this person's view interests
    $viewquery = "SELECT *, MATCH(maintags, singlecategorytags, singlestyletags) AGAINST ('$formattedLikes') AS matching FROM photos WHERE MATCH(maintags, singlecategorytags, singlestyletags) AGAINST ('$formattedLikes') ORDER BY matching DESC, points DESC LIMIT 0, 1";
    $viewresult = mysql_query($viewquery) or die(mysql_error());
  }

  $discoverimage = mysql_result($viewresult, 0, "id");
  
?>

<!DOCTYPE HTML>
	<head>
	<title>Photo Sharing Meets the Market</title>
	<meta name="Keywords" content="photos, sharing photos, photo sharing, photography, stock photography, stock, photography club, sell photos, sell photography, where to sell my photography, good sites for selling photography, making money from photography, making money off photography, social networking, social network, social networks, where to sell my photos, good sites for selling photos, good site to sell photos, making money from photos, social stock, photo licensing, royalty free photos, crowdsource, crowdsourcing photos, crowdsourced photos">

<meta name="Description" content="PhotoRankr is a social marketplace for photography that makes buying and selling photos as easy as it is to share them. Photographers share, view, rank, comment, favorite photos, and watch them trend. Image buyers can find custom and creative content by searching the marketplace using social filters such as trending and photographer reputation or by creating a Campaign that crowd-sources photography needs.">

	<link href="css/bootstrap.css" rel="stylesheet" type="text/css"/>
	<link href="css/new.css" rel="stylesheet" type="text/css"/>
	<link href="css/reset.css" rel="stylesheet" type="text/css"/>
	<link href="css/960_24_col.css" rel="stylesheet" type="text/css"/>

     <meta name="viewport" content="width=1200" /> 
     	<script src="js/bootstrap-dropdown.js"></script>

	<script type="text/javascript">
    document.write("\<script src='//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js' type='text/javascript'>\<\/script>");
  </script> 
	  <script type="text/javascript" src="js/jquery.wookmark.js"></script>        
	<script src="js/javascript.js" type="text/javascript"></script>
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
        
        .statoverlay: hover
        {
        opacity: .8;
        }
    
		ul.nav li.dropdown:active ul.dropdown-menu
		{
    	display: block;    
        z-index:5000;
		}
		ul.nav li.dropdown:focus ul.dropdown-menu
		{
    	display: block;    
		}
   .nav > li > a:hover
   {
    background:none;
   }
   .nav > li > a:focus
   {
    background:none;
   }
     .nav > li > a:focus
   {
    background:none;
   }
		.btn-custom3 { padding: 10px 140px; background-color: hsl(209, 56%, 37%) !important; background-repeat: repeat-x; filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#5393ce", endColorstr="#296093"); background-image: -khtml-gradient(linear, left top, left bottom, from(#5393ce), to(#296093)); background-image: -moz-linear-gradient(top, #5393ce, #296093); background-image: -ms-linear-gradient(top, #5393ce, #296093); background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #5393ce), color-stop(100%, #296093)); background-image: -webkit-linear-gradient(top, #5393ce, #296093); background-image: -o-linear-gradient(top, #5393ce, #296093); background-image: linear-gradient(#5393ce, #296093); border-color: #296093 #296093 hsl(209, 56%, 32%); color: #fff !important; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.33); -webkit-font-smoothing: antialiased; }
		.btn-custom { padding:..32em 24em;b3ackground-color: hsl(100, 56%, 34%) !important; background-repeat: repeat-x; filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#73cb48", endColorstr="#468726"); background-image: -khtml-gradient(linear, left top, left bottom, from(#73cb48), to(#468726)); background-image: -moz-linear-gradient(top, #73cb48, #468726); background-image: -ms-linear-gradient(top, #73cb48, #468726); background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #73cb48), color-stop(100%, #468726)); background-image: -webkit-linear-gradient(top, #73cb48, #468726); background-image: -o-linear-gradient(top, #73cb48, #468726); background-image: linear-gradient(#73cb48, #468726); border-color: #468726 #468726 hsl(100, 56%, 29%); color: #fff !important; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.33); -webkit-font-smoothing: antialiased; }
		.btn-custom2 { padding:..375em 4em; background-color: hsl(209, 56%, 37%) !important; background-repeat: repeat-x; filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#5393ce", endColorstr="#296093"); background-image: -khtml-gradient(linear, left top, left bottom, from(#5393ce), to(#296093)); background-image: -moz-linear-gradient(top, #5393ce, #296093); background-image: -ms-linear-gradient(top, #5393ce, #296093); background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #5393ce), color-stop(100%, #296093)); background-image: -webkit-linear-gradient(top, #5393ce, #296093); background-image: -o-linear-gradient(top, #5393ce, #296093); background-image: linear-gradient(#5393ce, #296093); border-color: #296093 #296093 hsl(209, 56%, 32%); color: #fff !important; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.33); -webkit-font-smoothing: antialiased; }
		.btn-custom4 { padding:.5em .375em 2em;background-color: hsl(100, 56%, 34%) !important; background-repeat: repeat-x; filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#73cb48", endColorstr="#468726"); background-image: -khtml-gradient(linear, left top, left bottom, from(#73cb48), to(#468726)); background-image: -moz-linear-gradient(top, #73cb48, #468726); background-image: -ms-linear-gradient(top, #73cb48, #468726); background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #73cb48), color-stop(100%, #468726)); background-image: -webkit-linear-gradient(top, #73cb48, #468726); background-image: -o-linear-gradient(top, #73cb48, #468726); background-image: linear-gradient(#73cb48, #468726); border-color: #468726 #468726 hsl(100, 56%, 29%); color: #fff !important; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.33); -webkit-font-smoothing: antialiased; }

	</style>
	</head>
<body style="background:rgb(245,245,245);">
	<!--Navbar is here-->
<div class="navbar-fixed-top">
		<div class="navbar-inner" style="min-height:50px;">
			<div class="container"  style="height:50px;">
				<ul class="nav">
					<li style="float:left;margin-top:.40em;"> <img src="graphics/coollogo.png" style="width:180px;"/></li>
					<li class="dropdown"  id="accountmenu" style="font-size:16px;font-family:helvetica neue,helvetica,arial, sans-serif;float:right;margin-top:.75em;">
						<a style="color:#fff;"class="dropdown-toggle" data-toggle="dropdown" href="#"> Sign In <b class="caret"></b></a>
							<ul class="dropdown-menu" style="margin-left:-420px;margin-top:19px;">
								<li>
									<div style="width:520px;">
									<div style="float:left;width:240px;padding: 0 0 1em 1em;background:#eee;">
										<form action="myprofile.php?action=log_in" method="POST">
											<fieldset>
											<legend style="font-weight:700;font-size:16px;">Photographer Sign-In</legend>
											<label style="color:#000;font-weight:300;"for="email">Email</label>
											<input class="split_form" type="text" name="emailaddress"/>
											<label style="color:#000;font-weight:300;"for="Password">Password</label>
											<input class="split_form" type="password" name="password">
											<input style="font-family:helvetica, arial, sans-serif;font-size:14px;" class="btn btn-success" type="submit" value="Photographer Sign In"/>
										</form>
									</div>	
									<div style="float:right;width:240px;padding: 0 0 1em 1em;">
										<form action="/market?action=log_in" method="POST">
											<fieldset>
											<legend style="font-weight:700;font-size:16px;">Market Sign-In</legend>
											<label style="color:#000;font-weight:300;"for="email">Email </label>
											<input class="split_form" type="text" name="emailaddress"/>
											<label style="color:#000;" for="Password:">Password</label>
											<input style="color:#000;font-weight:300;"class="split_form" type="password" name="password">
											<input style="font-family:helvetica, arial, sans-serif;font-size:14px;opacity:.8;" class="btn btn-primary" type="submit" value="Market Sign In"/>
										</form>
									</div>	
								</div>
								</li>
							</ul>
                        <a style="text-decoration:none;color:#fff;" href="/market"><li style="color:#fff;font-size:16px;font-family:helvetica neue,helvetica,arial, sans-serif;float:right;margin-top:.75em;margin-right:40px;">Market</li></a>
                        <a style="text-decoration:none;color:#fff;" href="newest.php"><li style="color:#fff;font-size:16px;font-family:helvetica neue,helvetica,arial, sans-serif;float:right;margin-top:.75em;margin-right:40px;">Photos</li></a>
				</ul>
			</div>
		</div>
	</div>
    
<div class="container_24" style="margin-top:60px;">
	
    <div class="grid_24">
        
		<div class="grid_19 pull_3" style="float:left;"><!--images go here-->
			
            <?php 
                
                $imagesquery = mysql_query("SELECT * FROM photos ORDER BY score DESC LIMIT 17");
                
                echo'<div id="thepics">
                     <div id="main" role="main">
                     <ul id="tiles">';
                
                for($iii = 1; $iii <= 16; $iii++) {
                    $image = mysql_result($imagesquery, $iii-1, "source");
                    $imageThumb=str_replace("userphotos/","userphotos/medthumbs/", $image);
                    $id = mysql_result($imagesquery, $iii-1, "id");
                    $caption = mysql_result($imagesquery, $iii-1, "caption");
                    $caption = (strlen($caption) > 18) ? substr($caption,0,16). " &#8230;" : $caption;
                    $price = mysql_result($result, $iii-1, "price");
                    if($price != 'Not For Sale') {
                        $price = '$' . $price;
                    }
                    elseif($price == 'Not For Sale') {
                        $price = 'NFS';
                    }
                    elseif($price == '.00') {
                        $price = 'Free';
                    }
                    $points = mysql_result($imagesquery, $iii-1, "points");
                    $votes = mysql_result($imagesquery, $iii-1, "votes");
                    $score = number_format(($points/$votes),2);
                    $owner = mysql_result($result, $iii-1, "emailaddress");
                    $ownerquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$owner'");
                    $firstname = mysql_result($ownerquery, 0, "firstname");
                    $lastname = mysql_result($ownerquery, 0, "lastname");
                    $fullname = $firstname . " " . $lastname;
    
                    list($width, $height) = getimagesize($image);
                    $imgratio = $height / $width;
                    $heightls = $height / 5;
                    $widthls = $width / 5;

                    if($widthls < 205) {
                        $heightls = $heightls * ($heightls/$widthls);
                        $widthls = 240;
                    }
                    
                    echo '
        <a style="text-decoration:none;" href="fullsize.php?imageid=',$id,'&v=n"><li class="fPic" id="',$id,'" style="padding:5px;margin-right:0px;margin-top:10px;list-style-type: none;
"><img src="https://photorankr.com/',$imageThumb,'" height="',$heightls,'px" width="',$widthls,'px" />

 <div class="statoverlay" style="z-index:1;background-color:black;position:relative;top:0px;width:240px;height:30px;"><div style="line-spacing:1.48;padding:5px;color:white;"><div style="float:left;"<span style="font-size:18px;font-weight:100;">',$score,'</span>&nbsp;&nbsp;<span style="font-weight:100;font-size:16px;">',$caption,'</span></div><div style="float:right;"><span style="font-size:13px;">',$price,'</span></div></div><br/></div>

</li></a>';
                                      
                }
                            
            ?>
            
            </ul>
    
    <!-- Once the page is loaded, initalize the plug-in. -->
  <script type="text/javascript">
    $(document).ready(new function() {
      // Prepare layout options.
      var options = {
        autoResize: true, // This will auto-update the layout when the browser window is resized.
        container: $('#main'), // Optional, used for some extra CSS styling
        offset: 5, // Optional, the distance between grid items
        itemWidth: 245 // Optional, the width of a grid item
      };
      
      // Get a reference to your grid items.
      var handler = $('#tiles li');
      
      // Call the layout function.
      handler.wookmark(options);
      
    });
  </script>
            
</div>
</div>
            
    <!--AJAX CODE HERE-->
   <div class="grid_6 push_9" style="padding-top:50px;">
   <div id="loadMorePics" style="display: none; text-align: center;font-family:arial,helvetica neue; font-size:15px;">Loading More Photos&hellip;</div>
   </div>

<script>

var last = 0;

	$(window).scroll(function(){
		if($(window).scrollTop() > $(document).height() - $(window).height()-100) {
			if(last != $(".fPic:last").attr("id")) {
				$("div#loadMoreTrendingPics").show();
				$.ajax({
					url: "loadMoreTrendingFront.php?lastPicture=" + $(".fPic:last").attr("id"),
					success: function(html) {
						if(html) {
							$("#thepics").append(html);
							$("div#loadMoreTrendingPics").hide();
						}
					}
				});
				last = $(".fPic:last").attr("id");
			}
		}
	});
</script>    
                            
    </div>
        
        
		<div class="grid_10" style="float:right;margin:15px 0 0 80px;position:fixed;"><!--sign up form container-->
			<div class="grid_10 pull_4" id="header_container">
				<h1 id="sign_up_header"> PhotoRankr makes <span style="font-weight:600;font-size:24px;"> buying and selling photos  </span>as <span style="font-weight:600;font-size:24px;"> easy </span>as it is to share them </h1>
			<h1 id="subheader"> </h1>
		</div>
		
			<div class="grid_10 pull_4" id="bar_container"><!--contains searchbar-->
				<h1 class="search_sign"> Buying Photos? Search the marketplace</h1>
				<div class="grid_10">
				<form action="/market" method="GET"><!--put the search function here-->
					<input id="search_bar" name="searchterm" type="text"/>
                </form>
					<div class="grid_1 pull_2" id="search_glass">
						<img src="graphics/glass2.png"/> 
					</div>


				</div>
			</div>		
			<div class="grid_10 pull_4" id="form_container"><!--contains form-->
				<h1 class="search_sign"> Sign up to buy, share, and sell your photos </h1>
				<div class="grid_10"  style="margin:20px 0;">
				<form action="post">
					<input id="first_name" name="firstname" type="text" placeholder="First name"/>
					<input id="last_name" name="lastname" type="text" placeholder="Last name"/>
					<input id="email" name="email" type="text" placeholder="Email"/>
					<input id="password" name="password" type="password" placeholder="Password"/>
                    <input type="submit" onclick='this.form.action="signup3.php";' class="btn btn-success" style="padding:10px 12px;font-size:16px;" value="Photographer Sign Up"</button>
				  <input type="submit" onclick='this.form.action="market/signup2.php";' class="btn btn-primary" style="padding:10px 24px;font-size:16px;opacity:.8;" value="Buyer Sign Up"</button>
				</form>
			</div>	
		</div>
			<!--<div id="explore_container" class="grid_10">
				<h1 class="search_sign"> Explore PhotoRankr's Image Galleries </h1>
				
				<a href="newest.php"> <a href="newest.php"> <button class="btn btn-custom3" style="font-size:16px;text-align:center;margin-top:10px;">Explore</button></a>
		</div>-->
					


		</div>

	</div>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
	
	<script type="text/javascript" src="https://masonry.desandro.com/jquery.masonry.min.js"></script>
	<script src="js/bootstrap-dropdown.js"></script>


  <script type="text/javascript">

    $(document).ready(function() {

        var $container = $('#container1');
          $container.imagesLoaded(function(){
            $container.masonry({
              itemSelector : '.masonryImage',
              columnWidth : 320     //Added gutter to simulate margin
          });
        });

    });
  </script>
  <script type="text/javascript">  
 			 $(document).ready(function () {  
            $('.dropdown-toggle').dropdown();  
        });  
   </script> 
</body>
</html>


