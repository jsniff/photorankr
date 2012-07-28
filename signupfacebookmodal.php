<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
"http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="reset.css" />
        <link rel="stylesheet" type="text/css" href="bootstrap.css" />
        <link rel="stylesheet" type="text/css" href="960_16.css" />
        <link rel="stylesheet" type="text/css" href="smoothDivScroll.css" />
        <link rel="stylesheet" type="text/css" href="Sign Up.css" />
        <script type="text/javascript" src="bootstrap-dropdown.js"></script>
        
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
        <script type="text/javascript" src="jquery-ui-1.8.18.custom.min.js"></script>
        <script type="text/javascript" src="jquery.mousewheel.min.js"></script>
        <script type="text/javascript" src="jquery.smoothdivscroll-1.2.js"></script>
        
        <script type="text/javascript">
            // Initialize the plugin with no custom options
            $(document).ready(function () {
                              // None of the options are set
                              $("div#makeMeScrollable").smoothDivScroll({});
                              }); 
            </script>
        
        <style type="text/css">
            
            #makeMeScrollable
            {
                border:20px solid white;
                border-radius:5px;
                width:1150px;
                height:300px;
                position:relative;
            }
            #makeMeScrollable div.scrollableArea img
            {
                z-index:-1;
                position: relative;
                float: left;
                margin: 0;
                padding: 0;
                /* If you don't want the images in the scroller to be selectable, try the following
                 block of code. It's just a nice feature that prevent the images from
                 accidentally becoming selected/inverted when the user interacts with the scroller. */
                -webkit-user-select: none;
                -khtml-user-select: none;
                -moz-user-select: none;
                -o-user-select: none;
                user-select: none;
            } 
            
            .scrollpics
            {
                width:393px;
                height:300px;
            }
            
            .inner
            {
                position:relative;
                top:-150px;
                left:0;
            }
            
            .statoverlay
            {
                opacity:.0;
                filter:alpha(opacity=40);
                z-index:1;
            }
            
            .statoverlay:hover
            {
                opacity:.5;
            }
            
            .navbartext
            {
                font-family:Helvetica-light, Helvetica, Arial, sans-serif;
            }
            
            .titletext
            {
                font-size:50px;
                font-family:Helvetica-light, Helvetica, Arial, sans-serif;
                color:rgb(182,195,205);
            }
            
            .titletextbig
            {
                font-size:40px;
                font-family:Helvetica-light, Helvetica, Arial, sans-serif;
                color:rgb(182,195,205);
            }
            
            .signupbutton
            {
                width:280px;
                height:50px;
                border:2px solid rgb(58,85,105);
                border-bottom-left-radius:10px;
                border-bottom-right-radius:10px;
                background-color:rgb(58,85,103);
                filter:alpha(opacity=40);
                z-index:1;
            }
            .signupbutton:hover
            {
                background-color:rgb(250,250,250);
            }
            p.buttontext
            {
                padding:16px;
                text-align:center;
                color:rgb(255,255,255);
                font:helvetica-light,helvetica,arial,sans-serif;
                font-size:30px;z-index:1;
                border:none;
            }
            p.buttontext:hover
            {
                color:rgb(58,85,105)
            }
            .footer
            {
                display:inline;
                color:rgb(56,85,103);
                margin-right:20px;
                font-size:14px;
            }
            
            </style>
        
    </head>
    
    <body class= "background">
        
        <!--Navbar begin-->
        <div class="navbar" style="padding-top:0px;z-index:10;font-size:16px;">
            <div class="navbar-inner">
                <div class="container">
                    <ul class="nav" style="margin:0px;">
                        <li><a style="margin-top:10px;margin-right:80px;" class="brand" href="index.php"><div style="margin-top:-6px"><img src="logo.png" width="180" /></div></a></li>
                        <li class="navbartext"><a style="color:rgb(56,85,103);margin-top:8px;margin-right:80px;" href="trending.php">Trending</a></li>
                        <li class="navbartext"><a style="color:rgb(56,85,103);margin-top:8px;margin-right:80px;" href="newest.php">Newest</a></li>
                        <li class="navbartext"><a style="color:rgb(56,85,103);margin-top:8px;margin-right:80px;" href="topranked.php">Top Ranked</a></li>
                        <li class="navbartext"><a style="color:rgb(56,85,103);margin-top:8px;margin-right:100px;" href="login.php">Log In</a></li>
                        <li class="navbartext"><form class="navbar-search" action="search.php" method="post"><input type="text" style="border-color:rgb(58,85,103);background-color:rgb(182,195,205);margin-top:4px;width:18em" class="search-query" name="searchterm" placeholder="Search"></form></li>
                    </ul>
                </div>
            </div>
        </div>
        <!--Navbar end-->
        
        <div style="padding-top:40px;" class="container_16"><!--Grid container begin-->
            
            <!--Title begin-->
            <div class="grid_16 pull_2">
                <p1 class="titletext">Bring Your Photos To Life.</p1>
                <br />
                <br />
                <p2 class="titletextbig">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;To Market.&nbsp;&nbsp;To People.&nbsp;&nbsp;<span style="font-family:Helvetica-light, Helvetica, Arial, sans-serif;font-size:40px;color:rgb(58,85,103);">With&nbsp;<span></p2>
                <img style="margin-bottom:20px;" src="logo.png" width="300" />
                <p3 style="margin-top:90px;font-family:Helvetica-light, Helvetica, Arial, sans-serif;font-size:60px;color:rgb(58,85,103)">.</p3>
                <br />
                <br />
            </div>
            
            <div class="grid_16 pull_2">
                
                <div style="border:2px solid rgb(56,85,103);width:1190px;height:340px;border-radius:5px;">
                    <div id="makeMeScrollable">
                        <div class="scrollingHotSpotLeft" style="display: block; opacity: 0;"></div>
                        <div class="scrollingHotSpotRight" style="opacity: 0;display:block;"></div>
                        <div class="scrollWrapper">
                            <div class="scrollableArea" style="width:250px">
                                <div>
                                    <img class="scrollpics" style="inline;" id="gnome" alt="Demo image" src="images/Wizard.jpg">
                                        <div class="statoverlay" style="position:absolute;top:240px;background-color:black;width:393px;height:60px;"><p style="margin:5px;color:white;">Info.</p></div>
                                        </div>
                                
                                <div>
                                    <img class="scrollpics" style="inline;" id="gnome" alt="Demo image" src="images/Girl.jpg">
                                        <div class="statoverlay" style="position:absolute;top:240px;left:393px;background-color:black;width:393px;height:60px;"><p style="margin:5px;color:white;">"Once Was"<br>By "Bryan Hall"</br>Score: 8.3</p></div>
                                        </div>
                                
                                <div>
                                    <img class="scrollpics" style="inline;" id="gnome" alt="Demo image" src="images/Falls.jpg">
                                        <div class="statoverlay" style="position:absolute;top:240px;left:786px;background-color:black;width:393px;height:60px;"><p style="margin:5px;color:white;">Info.</p></div>
                                        </div>
                                
                                <div>
                                    <img class="scrollpics" style="inline;" id="gnome" alt="Demo image" src="images/Owl.jpg">
                                        <div class="statoverlay" style="position:absolute;top:240px;left:1179px;background-color:black;width:393px;height:60px;"><p style="margin:5px;color:white;">Info.</p></div>
                                        </div>
                                
                                <div>
                                    <img class="scrollpics" style="inline;" id="gnome" alt="Demo image" src="images/Dress.jpg">
                                        <div class="statoverlay" style="position:absolute;top:240px;left:1572px;background-color:black;width:393px;height:60px;"><p style="margin:5px;color:white;">Info.</p></div>
                                        </div>
                                
                                <img class="scrollpics" id="train" alt="Demo image" src="images/demo/train.jpg">
                                    <img class="scrollpics" id="leaf" alt="Demo image" src="images/demo/leaf.jpg">
                                        <img class="scrollpics" id="dog" alt="Demo image" src="images/demo/dog.jpg">
                                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="grid_16 push_12" style="z-index:1;">
                <div style="margin-top:-55px;margin-left:15px;">
                    <div style="background-color:rgb(57,84,103);opacity:.8;width:280px;height:55px;border-top-left-radius:10px;border-top-right-radius:10px">
                        <div p style="padding-top:10px;text-align:center;color:white;font-size:35px;opacity:1;width:280px;height:55px;border-top-left-radius:10px;border-top-right-radius:10px">Sign Up Free<p>
                    </div>
                    </div>
                    <div style="padding-top:15px;margin-top:0px;width:278px;height:180px;border-left: 2px solid #3a5569;border-right: 2px solid #3a5569;background-color:rgb(255,255,255);padding-bottom:20px;">
                        <form style="width:180px;margin-left:30px;">
                            <p style="font-size:14px;margin:0px;">&nbsp;Name</p>
                            <input style="font-size:13px;margin-bottom:20px;border-radius:5px;background-color:rgb(238,239,243);" type="text" name="fullname" placeholder="Name" /><br />
                            <p style="font-size:14px;margin:0px;">&nbsp;E-mail address</p>
                            <input style="font-size:13px;margin-bottom:20px;border-radius:5px;background-color:rgb(238,239,243);" type="text" name="email" placeholder="E-mail address" /><br />
                            <p style="font-size:14px;margin:0px;">&nbsp;Password</p>
                            <input style="font-size:13px;border-radius:5px;background-color:rgb(238,239,243);" type="text" name="password" placeholder="Password" /><br />
                        </form>
                    </div>
                    <div class="signupbutton">
                        <p class="buttontext"> Join PhotoRankr</p>
                    </div>
                </div>
            </div>
            <div class="grid_14 pull_1">
                <div style="position:relative;left:-60px;top:-1px;width:1190px;height:40px;margin-top:-270px;opacity:.3;"> <img src="BAR.png" width="1190px" height="40px">
                    <div class="grid_1" style="z-index:-1;position:relative;left:850px;top:-15px;width:282px;height:220px;opacity:.3;border-radius:10px;background-color:black;">
                    </div>
                    </div>
                <div class="grid_14 pull_1">
                    <div style="position:relative;left:50px;top:-225px;width:1070px;height:100px;padding-bottom:100px;padding-top:10px;padding-left:10px;background-color:#FFFFFF;border-bottom-left-radius:10px;border-bottom-right-radius:10px;border-width:1px;border-style:solid; border-color:rgb(169,169,169);z-index:-2;"> <img src="equation.png" width="770px">
                        </div>
                </div> 	
                <div class="grid_10 pull_1">
                    <div style="position:relative;left:-60px;top:-1px;width:1190px;height:40px;margin-bottom:20px;margin-top:-228px;opacity:.3;margin-left:60px"> <img src="BAR2.png" width="1190px" height="40px">
                     <div style="position:relative;height:20px;width:1200px;background-color:white;opacity:0;">
                      </div>
                    </div>
                </div>
            <div class="grid_1 pull_1">
             <div class="box8">
                <div class="box7">
                </div>
                </div>
            </div>
                <div class="grid_1" style="float:left;">
                    <div class="square3">
                        <div class="box1">
                            <h1 class="content"> Content Recycling </h1>
                            <div class="box2">
                                <p class="textbox">You images are always relevant on PhotoRankr. Every profile has a personal gallery where you can select the kinds of images you want to see. </p>
                            </div>	
                        </div>
                    </div>
                </div>
                <div class="grid_8" style="float:right;">
                    <div class="square4">
                        <div class="box3">
                            <h1 class= "content1"> Show, Share, Sell </h1>
                            <div class="box4">
                                <p class="textbox"> Upload your photos, show them to your followers: photographers who want to see your work. </p>
                        </div>
                    </div>
                </div>
            </div>
                <div class="grid_2 push_5" style="float:right;">
                    <div class="square5">
                        <div class="box5">
                            <h1 class= "content"> Grow </h1>
                            <div class="box6">
                                <p class="textbox">Help other photographers get better. Rank, comment, and favorite photos across the PhotoRankr community; give feedback to other photographetrs and they can return the favor with a few keystrokes and clicks. </p>
                            </div>
                        </div>
                    </div> 
                </div>
                <!--Footer begin-->
                <div class="grid_16" style="text-align:center;">
                    <div class="grid_16" style="height:5px;background-color:rgb:100,100,100);">
                        <div class="grid_16" style="height:30px;margin-top:30px;background-color:rgb:(238,239,243);text-align:center;padding-top:10px;background-color:white;">
                            <ul>
                                <li class="footer">PhotoRankr&nbsp;&copy;&nbsp;2012</li1>
                                    <li class="footer"><a href="http://photorankr.com/about.php">About</a></li1>
                                        <li class="footer">Privacy</li>
                                        <li class="footer"><a href="http://photorankr.com/terms.php">Terms</a></li1>
                                            <li class="footer"><a href="http://photorankr.com/contact.php">Help/Contact&nbsp;Us<a><li1>
                                                </ul>
                        </div>
                    </div>
                </div>






<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>

<script src="bootstrap.js" type="text/javascript"></script>


<div class="modal hide fade" id="myModal">
  <div class="modal-header">
    <a style="float:right" class="btn btn-primary" data-dismiss="modal">close</a>
    <h3>Modal header</h3>
  </div>
  <div class="modal-body">
    <p>Shit go here mothafucka</p>
  </div>
  
</div>

<a class="btn btn-primary" data-toggle="modal" href="#myModal" >Launch Modal</a>




                
                
                <!--Footer end-->
                
            </div>


  <head> 
    <title> 
      New JavaScript SDK
    </title> 
  </head> 
<body> 
 	
<div id="fb-root"></div>
<div id="user-info"></div>
      <div class="fb-login-button" style="position:absolute;top:800px;">Login with Facebook</div>




<script>
window.fbAsyncInit = function() {
  FB.init({ appId: '433110216717524', 
	    status: true, 
	    cookie: true,
	    xfbml: true,
	    oauth: true});

  function updateButton(response) {
    var button = document.getElementById('fb-auth');
		
    if (response.authResponse) {
      //user is already logged in and connected
      var userInfo = document.getElementById('user-info');
      FB.api('/me', function(response) {
        userInfo.innerHTML = '<img src="https://graph.facebook.com/' 
	  + response.id + '/picture">' + response.email;
        button.innerHTML = 'Logout';
            
             });
      button.onclick = function() {
        FB.logout(function(response) {
          var userInfo = document.getElementById('user-info');
          userInfo.innerHTML="";
	});
      };
        
       
        
} 
    
    
    
    
    
    
    else {
      //user is not connected to your app or logged out
      button.innerHTML = 'Login';
      button.onclick = function() {
        FB.login(function(response) {
	  if (response.authResponse) {
            FB.api('/me', function(response) {
	      var userInfo = document.getElementById('user-info');
	      userInfo.innerHTML = 
                '<img src="https://graph.facebook.com/' 
	        + response.id + '/picture" style="margin-right:5px"/>' 
	        + response.name;
           
                
	    });	   
          } else {
            //user cancelled login or did not grant authorization
          }
        }, {scope:'email'});  	
      }
    }
  }
    
    
  // run once with current status and whenever the status changes
  FB.getLoginStatus(updateButton);
  FB.Event.subscribe('auth.statusChange', updateButton);	
};
	
(function() {
  var e = document.createElement('script'); e.async = true;
  e.src = document.location.protocol 
    + '//connect.facebook.net/en_US/all.js';
  document.getElementById('fb-root').appendChild(e);
}());




</script>



//<script>

                  
                //  </script>
                  



<?php
 define('YOUR_APP_ID', '433110216717524');

    //uses the PHP SDK.  Download from https://github.com/facebook/php-sdk
    require 'facebook.php';

    $facebook = new Facebook(array(
      'appId'  => '433110216717524',
      'secret' => '1e48c1da8711b920a081ba37e56e0f1f',
    ));

    $userId = $facebook->getUser();
  






    $userInfo = $facebook->api('/me');

    echo "<pre>";
   //print_r($userInfo);
    echo "</pre>";


//$id = $userInfo['id'];
$first_name = $userInfo['first_name'];
$email = $userInfo['email'];
//$password = $userInfo['password'];



// Connecting to database
require "db_info.php";
//mysql_connect('DATABASE_HOST', 'DATABASE_USERNAME', 'DATABASE_PASSWORD');
//mysql_select_db('DATABASE_NAME');


//grab user_id query
$userid = "SELECT * FROM userinfo WHERE email = '$email' LIMIT 0,1";
$useridquery = mysql_query($userid);
$user = mysql_result($useridquery, 0, "user_id");
  //if (response.authResponse) {
//echo'<a href="http://photorankr.com/myprofile.php?u=',$user,'">My Profile</a>';
//}
$con=mysql_connect(DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD) 
 
 or die("<p>Error connecting to the database: " . mysql_error() . "</p>");

 mysql_select_db(DATABASE_NAME) or die(mysql_error()); 
 
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

 mysql_select_db(DATABASE_NAME,con);


// Inserting into users table
$test = "INSERT INTO FacebookStuff (FacebookName,FacebookEmail) VALUES ('$first_name','$email')";
$resultquery = mysql_query($test);


//if($test){
// User successfully stored
//echo 'finally is working';

//}
//else
//{
//echo 'wft  is not';
//}



?>

<script>

    FB.getLoginStatus(function(response) {
    if (response.status === 'connected') {
    
     //   document.write('I hate scott fink');
    
                      window.location = "http://photorankr.com/newest.php"
                      
    }
    
    
    }
                      </script>

    
    
            
    </body>
</html>