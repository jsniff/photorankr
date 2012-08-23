<?php
ini_set('max_input_time', 300);   

function createprofthumbnail($filename) {  
		ini_set('max_input_time', 1200);
      
        require 'configcampaigns.php';  
        
        $filename=str_replace("JPG","jpg",$filename);

        if(preg_match('/[.](jpg)$/', $filename)) {  
            $im = imagecreatefromjpeg($path_to_profpic_directory . $filename);  
        } else if (preg_match('/[.](gif)$/', $filename)) {  
            $im = imagecreatefromgif($path_to_profpic_directory . $filename);  
        } 
        else if (preg_match('/[.](jpeg)$/', $filename)) {  
            $im = imagecreatefromjpeg($path_to_profpic_directory . $filename);  
        }
        else if (preg_match('/[.](png)$/', $filename)) {  
            $im = imagecreatefrompng($path_to_profpic_directory . $filename);  
        } 
      
      
        $ox = imagesx($im);  
        $oy = imagesy($im);  
      
        $nx = $final_width_of_image;  
        $ny = $final_height_of_image;  
      
        $nm = imagecreatetruecolor($nx, $ny);  
      
        imagecopyresampled($nm, $im, 0,0,0,0,$nx,$ny,$ox,$oy);  
      
        if(!file_exists($path_to_profpic_directory)) {  
          if(!mkdir($path_to_profpic_directory)) {  
               die("There was a problem. Please try again!");  
          }  
           }  
      
        imagejpeg($nm, $path_to_profpic_directory . $filename);  
        //$tn = '<img src="' . $path_to_profpic_directory . $filename . '" alt="image" />';  
        //$tn .= '<br />Upload Successful!';  
        //echo $tn;  
chmod ($path_to_profpic_directory . $filename, 0644);
}  


//Navbar function
function navbar() {  
echo'

<!--NAVIGATION BAR-->
<div class="navbar" style="z-index:10;padding-top:0px;font-size:16px;width:100%;min-width:1140px">
	<div class="navbar-inner">
		<div class="container">
			    <ul class="nav">
					<li><a class="brand" style="margin-top:0px;margin-right:20px;padding-bottom:9px;" href="index.php"><div style="margin-top:-2px"><img src="graphics/logocampaign.png" width="260" /></div></a></li>
					<li><a style="color:#21608E;margin-top:4px;padding-bottom:9px;" style="color:rgb(56,85,103);margin-right:20px;" href="viewcampaigns.php">All Campaigns</a></li>
                    <li><a style="color:#21608E;margin-top:4px;padding-bottom:9px;margin-right:20px;" href="mycampaigns.php">My Campaigns</a></li>
                    <li><a style="color:#21608E;margin-top:4px;padding-bottom:9px;margin-right:20px;" href="createcampaign.php">Create Campaign</a></li>';
                    @session_start();
                    if($_SESSION['loggedin'] == 2) {
                        echo'
                        <li><a style="color:#21608E;margin-top:4px;margin-right:20px;" href="',htmlentities($_SERVER['PHP_SELF']),'?action=logout">Log Out</a></li>';
                    }
                   
                     else {
                        echo'
                        <li class="dropdown">
                        <a style="color:#21608E;margin-top:3px;padding-bottom:10px;" href="signin.php" class="dropdown-toggle" data-toggle="dropdown">Log In<b class="caret"></b></a>
						<ul class="dropdown-menu" data-dropdown="dropdown" style="width:200px;">
                        <li><a style="color:#21608E;margin-left:-29px;font-size:15px;" href="signin.php">Register for free today</a></li>
                        <li><br/></li>
                        <form name="login_form" method="post" action="',htmlentities($_SERVER['PHP_SELF']),'?action=login">
                        <li style="margin-left: 5px; margin-right: 5px; width: 185px;color:#21608E;"><span style="margin-bottom: 5px;margin-left:10px;">Email: <br /></span><input type="text" style="width:150px;margin-top:3px;margin-left:10px;" name="emailaddress" /></li>
							<li><span style="margin-left:-16px;color:#21608E;">Password: <br /></span><input type="password" style="width:150px;margin-top:3px;margin-left:-16px;" name="password"/></li>
                        <li style="margin-left: 110px;"><input type="submit" class="btn btn-success" value="Sign In" id="loginButton"/></li>
                        </form>
						</ul>';
                    }
               
                     echo'
				</div> 
   			</div>
		</div>
	<!--/END NAVBAR-->

	<br /><br /><br />';
}



//NAVBAR NEW
function navbarnew() {  
echo'
<link rel="stylesheet" href="css/style.css" type="text/css"/> 
<div style="width:100%;height:50px;border-bottom:1px solid #999;min-width:1085px;position:fixed;z-index:15;" class="gunmetal">
<a href="/market"><img class="hover" style="float:left;padding-left:30px;padding-top:2px;" src="graphics/logotext.png" width="210" /></a>
<label style="float:left;font-size:13px;color:white;padding-top:15px;padding-left:80px;font-weight:normal;">Search</label>

<form class="navbar-search" action="/market/#search" method="get">
<input class="search" style="position:relative;margin-left:10px;margin-top:2px;" name="searchterm" type="text">
<a href="#search"><input style="margin-top:3px;margin-left:-3px;" type="submit" class="go" value="Go"></a>
</form>

<span style="float:right;padding-right:0px;padding-top:13px;">
<a class="navhover" style="font-weight:lighter;" href="/market">Marketplace</a>';
@session_start();
if($_SESSION['loggedin'] != 2) {
echo'<a class="navhover" href="viewcampaigns.php">Campaigns</a>
 <span class="dropdown">
 <a class="dropdown-toggle navhover" data-toggle="dropdown">Log In</a>
 <ul class="dropdown-menu gunmetal" data-dropdown="dropdown" style="width:200px;margin-left:-120px;margin-top:15px;">
 <li><a style="color:white;margin-left:-29px;font-size:15px;" href="campaignnewuser.php">Register for free today</a></li>
 <li><br/></li>
 <form name="login_form" method="post" action="',htmlentities($_SERVER['PHP_SELF']),'?action=login">
 <li style="margin-left: 5px; margin-right: 0px; width: 185px;color:white;"><span style="margin-bottom: 5px;margin-left:10px;font-size:13px;">Email: <br /></span><input type="text" style="width:150px;margin-top:3px;margin-left:10px;padding:7px;" name="emailaddress" /></li>
 <li><span style="font-size:13px;margin-left:-16px;color:white;">Password: <br /></span><input type="password" style="width:150px;margin-top:3px;margin-left:-16px;padding:7px;" name="password"/></li>
 <li style="margin-left: 110px;"><input type="submit" class="btn btn-success" value="Sign In" id="loginButton"/></li>
 </form>
 </ul>
 </span>';
}
elseif($_SESSION['loggedin'] == 2) {
echo'
<span class="dropdown">
<a href="viewcampaigns.php" class="dropdown-toggle navhover" data-toggle="dropdown">Campaigns</a>
<ul class="dropdown-menu gunmetal" data-dropdown="dropdown" style="width:200px;;margin-top:30px;">
<a style="padding:15px;color:white;" href="viewcampaigns.php">View Campaigns</a><hr />
<a style="padding:15px;padding-top:0px;color:white;" href="mycampaigns.php">My Campaigns</a><hr />
<a style="padding:15px;padding-top:0px;color:white;" href="createcampaign.php">Create a Campaign</a>
</ul>
</span>

<span class="dropdown" style="font-size:20px;margin-top:auto;margin-bottom:auto;">
<a href="account.php" class="dropdown-toggle navhover" data-toggle="dropdown">Account</a>
<ul class="dropdown-menu gunmetal" data-dropdown="dropdown" style="width:200px;margin-top:30px;margin-left:-40px;">
<a style="padding:15px;color:white;" href="account.php">Saved Images</a><hr />
<a style="padding:15px;padding-top:0px;color:white;" href="download2.php">Cart</a><hr />
<a style="padding:15px;padding-top:0px;color:white;" href="account.php?view=campaigns">Manage Campaigns</a>
</ul>
</span>

<a class="navhover" style="margin-right:0px;font-weight:200;" href="',htmlentities($_SERVER['PHP_SELF']),'?action=logout">&nbsp;Log Out&nbsp;</a>';
}
echo'
</span>
</div>
';
}


function navbar2() {  
echo'

<!--NAVIGATION BAR-->
<div class="navbar" style="position:relative;z-index:10;padding-top:0px;font-size:16px;width:1200px;background-color: #6BBE44;">
	<div class="navbar-inner gunmetal">
		<div class="container">
			    <ul class="nav">
                    <li><a name="search" style="color:#fff;margin-top:2px;padding-bottom:9px;margin-right:20px;';if(htmlentities($_GET['c']) == 'trending' OR htmlentities($_GET['c']) == '') {echo'color:#6aae45;"';}else{echo'"';} echo'href="/market/?c=trending">Trending</a></li>
                    <li><a style="color:#fff;margin-top:2px;padding-bottom:9px;margin-right:20px;';if(htmlentities($_GET['c']) == 'pop') {echo'color:#6aae45;"';}else{echo'"';} echo'href="/market/?c=pop">Popular</a></li>
                    <li><a style="color:#fff;font-weight:100;margin-top:2px;padding-bottom:9px;margin-right:20px;';if(htmlentities($_GET['c']) == 'newest') {echo'color:#6aae45;;"';}else{echo'"';} echo'href="/market/?c=newest">Newest</a></li>
                    <li><a style="color:#fff;margin-top:2px;padding-bottom:9px;margin-right:20px;';if(htmlentities($_GET['c']) == 'deal') {echo'color:#6aae45;"';}else{echo'"';} echo'href="/market/?c=deal">Best Deal</a></li>
                    <li><a style="color:#fff;margin-top:2px;padding-bottom:9px;margin-right:20px;';if(htmlentities($_GET['c']) == 'top') {echo'color:#6aae45;"';}else{echo'"';} echo'href="/market/?c=top">Top Ranked</a></li>
                    <li><a style="color:#fff;margin-top:2px;padding-bottom:9px;margin-right:20px;';if(htmlentities($_GET['c']) == 'exhibits') {echo'color:#6aae45;"';}else{echo'"';} echo'href="/market/?c=exhibits">Top Exhibits</a></li>';
                    @session_start();
                    if($_SESSION['loggedin'] == 2) {
                    echo'
                    <li><a style="color:#fff;margin-top:2px;padding-bottom:9px;margin-right:20px;" href="/market/?c=following">My Photographers</a></li>';
                    }               
                     echo'
				</div> 
   			</div>
		</div>
	<!--/END NAVBAR-->

	<br /><br /><br />';
}


function navbar3() {  
$view = htmlentities($_GET['view']);

echo'
<!--NAVIGATION BAR-->
<div class="navbar" style="position:relative;font-size:16px;height:20px;">
	<div class="navbar-inner" style="background: #6aae45;">
		<div class="container" style="width:700px;">
			    <ul class="nav">';
                if($view == '') {
                $userid = htmlentities($_GET['u']);
                echo'
                    <li style="margin-left:-34px;"><img style="position:relative;left:-10px;margin-top:8px;" src="graphics/logo.png" height="22px" /></li>
                    <li><a style="color:#fff;margin-top:2px;margin-right:20px;';if(htmlentities($_GET['od']) == '') {echo'background-color:#71d05b;"';}else{echo'"';} echo'href="viewprofile.php?u=',$userid,'">Newest</a></li>
                    <li><a style="color:#fff;margin-top:2px;margin-right:20px;';if(htmlentities($_GET['od']) == 'topranked') {echo'background-color:#71d05b;"';}else{echo'"';} echo'href="viewprofile.php?u=',$userid,'&od=topranked">Top Ranked</a></li>
                    <li><a style="color:#fff;margin-top:2px;margin-right:20px;';if(htmlentities($_GET['od']) == 'pop') {echo'background-color:#71d05b;"';}else{echo'"';} echo'href="viewprofile.php?u=',$userid,'&od=pop">Most Popular</a></li>';
                    }
                else {
                echo'<li style="margin-left:-530px;"><img style="margin-top:8px;" src="graphics/logo.png" height="22px" /></li>';
                }
                    
                     echo'
				</div> 
   			</div>
		</div>
	<!--/END NAVBAR-->

	<br /><br /><br />';
}


function navbar4() {  
$view = htmlentities($_GET['view']);
echo'
<!--NAVIGATION BAR-->
<div class="grid_19"  id="canvas" style="margin-top:-448px;">
<div class="navbar" style="position:relative;font-size:16px;">
	<div class="navbar-inner" style="background: #6aae45;">
		<div class="container" style="width:700px;">
			    <ul class="nav">
                <li> <img style="position:relative;left:-10px;margin-top:8px;" src="graphics/logo.png" height="22px" /></li>';
                
                    if($view == '') {
                    echo'
                    <li><a style="color:#fff;margin-top:2px;padding-bottom:9px;" style="color:rgb(56,85,103);margin-right:20px;" href="account.php">Market Photos</a></li>
                    <li><a style="color:#fff;margin-top:2px;padding-bottom:9px;margin-right:20px;" href="account.php?select=campaigns">Campaign Photos</a></li>';
                    }
                    
                    if($view == 'campaigns') {
                    echo'
                    <li><a style="color:#fff;margin-top:2px;padding-bottom:9px;" style="color:rgb(56,85,103);margin-right:20px;" href="account.php?view=campaigns">Manage Campaigns</a></li>
                    <li><a style="color:#fff;margin-top:2px;padding-bottom:9px;margin-right:20px;" href="createcampaign.php">Create a Campaign</a></li>';
                    }
                    
                     echo'
				</div> 
   			</div>
		</div>
    </div>
	<!--/END NAVBAR-->

	<br /><br /><br />';
}



function login() {
    @session_start();

        // makes sure they filled it in
        if(!htmlentities($_POST['emailaddress']) | !htmlentities($_POST['password'])) {
            die('You did not fill in a required field.');
        }

        $check = mysql_query("SELECT * FROM campaignusers WHERE repemail = '".mysql_real_escape_string($_POST['emailaddress'])."'")or die(mysql_error());
        //Gives error if user dosen't exist

        $check2 = mysql_num_rows($check);
    
        if ($check2 == 0) {
            die('That user does not exist in our database. <a href="campaignnewuser.php">Click Here to Register</a> or <a href="lostpassword.php">here to recover a forgotten password</a>.');
        }

        $info = mysql_fetch_array($check);

        if(mysql_real_escape_string($_POST['password']) == mysql_real_escape_string($info['password'])){
            //then redirect them to the same page as signed in and set loggedin to 1
            $_SESSION['loggedin'] = 2;
            $_SESSION['repemail'] = mysql_real_escape_string($_POST['emailaddress']);
        }
        //gives error if the password is wrong
        else if (mysql_real_escape_string($_POST['password']) != mysql_real_escape_string($info['password'])) {
            die('Incorrect password, please try again. <a href="lostpassword.php"> Lost your password?</a>');   
        }
}

function logout() {
    session_start();
    $_SESSION['loggedin'] = 0;
    $_SESSION['repemail'] = "";

    session_destroy();
}

function footer() {
    echo'
   <link rel="stylesheet" href="css/style.css" type="text/css"/> 
    
    <div class="grid_24" style="height:30px;margin-top:70px;background-color:rgb:(238,239,243);text-align:center;padding-top:10px;background-color:none;text-decoration:none;">
    <hr style="color:#ccc"/>
    </div>

    <span style="color:rgb(117,117,117);">
    <div class="grid_4" style="height:145px;border-right:2px solid #ddd;">
    <p1 class="navgreen">Network</p1>
    <ul style="list-style:none;">
    <li><a class="foot"  style="text-decoration:none;" href="http://photorankr.com/trending.php">Trending</a></li>
    <li><a class="foot"  style="text-decoration:none;" href="http://photorankr.com/newest.php">Newest</a></li>
    <li><a class="foot"  style="text-decoration:none;" href="http://photorankr.com/topranked.php">Top Ranked</a></li>
    <li><a class="foot"  style="text-decoration:none;" href="http://photorankr.com/blog/post.php">Blog</a></li>
    <li><a class="foot"  style="text-decoration:none;" href="http://photorankr.com/discover.php">Discover</a></li>
    </ul> 
    </div>
  
    <div class="grid_4" style="height:145px;border-right:2px solid #ddd;">
    <p1 class="navgreen">Marketplace</p1>
    <ul style="list-style:none;">
    <li><a class="foot"  style="text-decoration:none;" href="http://photorankr.com/market">Marketplace</a></li>
    <li><a class="foot"  style="text-decoration:none;" href="http://photorankr.com/market/viewcampaigns.php">Campaigns</a></li>
    </ul> 
    </div>

    <div class="grid_4" style="height:145px;border-right:2px solid #ddd;">
    <p1 class="navgreen">More</p1>
    <ul style="list-style:none;">
    <li><a class="foot"  style="text-decoration:none;" href="http://photorankr.com/about.php">About</a></li>
    <li><a class="foot"  style="text-decoration:none;" href="http://photorankr.com/contact.php">Contact Us</a></li>
    <li>Careers</li>
    <li>Partners</li>
    </ul> 
    </div>

    <div class="grid_4" style="height:145px;border-right:2px solid #ddd;">
    <p1 class="navgreen">Legal</p1>
    <ul style="list-style:none;">
    <li><a class="foot" style="text-decoration:none;" href="http://photorankr.com/market/terms.php">Terms of Use</a></li>
    <li><a class="foot"  style="text-decoration:none;" href="http://photorankr.com/market/privacy.php">Privacy Policy</a></li>
    <li><a class="foot"  style="text-decoration:none;" href="http://photorankr.com/market/legal.php">Standard Content License Agreement</a></li>
    <li><a class="foot"  style="text-decoration:none;" href="http://photorankr.com/market/extended.php">Extended Content License Provisions</a></li>
    </ul> 
    </div>

    <div class="grid_4">
    <p1 class="navgreen">Support</p1>
    <ul style="list-style:none;">
    <li>(330) 573-3776</li>
    <li><a href="mailto:support@photorankr.com">support@photorankr.com</a></li>
    </ul>
    <p1 class="navgreen">Follow</p1>
    
    <div style="padding-left:25px;width:250px">

	     <div style="margin-right:10px;float:left" class="fb-like" data-href="http://photorankr.com" data-send="false" data-layout="button_count" data-width="300" data-show-faces="false" data-font="arial"></div>

             <div style="margin-right:10px;float:left">
             <a href="https://twitter.com/PhotoRankr" class="twitter-follow-button" data-show-count="false">Follow @PhotoRankr</a>
             <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
             </div>

             <div style="float:left">
             <script src="//platform.linkedin.com/in.js" type="text/javascript"></script>
             <script type="IN/Share"></script> 
             </div>
    </div>
    </div>




</span>

  <div class="grid_24">
<div style="font-size:10px;color:#bbb;text-align:center;">
  </br>
  <p1>PhotoRankr is a trademark of PhotoRankr, Inc. The PhotoRankr Logo is a trademark of PhotoRankr, Inc.</p1>
</br>
<p1>Copyright &#169; 2012 PhotoRankr, Inc.<p1>
</div>
  </div> 
</div>
 
    <br />
    <br />                   
    </div>';
    
}

function navbarsweet() {
echo'
<link rel="stylesheet" href="css/style.css" type="text/css"/> 
<div class="navbar navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container" style="height:50px;width:1060px;">
				<ul class="nav" style="height:50px;">
					<li class="topcenter"> <a href="index.php"> <img class="logo" src="graphics/coollogo.png" style="margin-left:-20px;height:45px;width:186px;margin-top:-8px;padding-right:10px;" /></a></li>
					<li class="margint"> <form class="navbar-search" action="/market" method="get">
<input class="search3 margint marginl" style="height:1.5em;width:270px;padding-right:25px;margin-left:5em;margin-right:5.5em;font-family:helvetica;font-size:14px;font-weight:100;color:black;" name="searchterm" type="text" placeholder="Search all photography">
</form></li>
					<li> <a href="/market" style="font-family:helvetica;font-weight:100;"> Marketplace </a> </li>

                    <li><a href="viewcampaigns.php" style="font-family:helvetica;font-weight:100;"> Campaigns </b></a></li>';
                        
                        if($_SESSION['loggedin'] == 2) {
                            $repemail = $_SESSION['repemail'];
                            $profilequery = mysql_query("SELECT logo,name FROM campaignusers WHERE repemail = '$repemail'");
                            $profilepic = mysql_result($profilequery,0,'logo');
                            if($profilepic == '') {
                                $profilepic = 'graphics/nologo.png';
                            }
                            $name = mysql_result($profilequery,0,'name');
                            $name = (strlen($name) > 14) ? substr($name,0,11). "&#8230;" : $name;

                  echo'
                            <li><a href="download2.php" class="cart" style="font-family:helvetica;font-weight:100;"></a></li>
                        
                        <li class="dropdown topcenter marginT" id="accountmenu">
							<a class="dropdown-toggle" data-toggle="dropdown" href="myprofile.php"> <img src="',$profilepic,'" style="width:30px;height:30px;"/><span style="font-size:13px;color:white;font-family:helvetica;font-weight:100;">&nbsp;&nbsp;&nbsp;',$name,'</span></a>
								
                                <ul class="dropdown-menu" style="margin-top:0px;background-color:#fff;width:150px;">
                                    <li> <a href="account.php"> My Account </a> </li>
                                    <li class="divider"></li>
									<li> <a href="account.php?view=saved"> Saved Photos </a> </li>
                                    <li> <a href="account.php?view=downloads"> Downloads </a> </li>
                                    <li> <a href="account.php?view=photogs"> Photographers </a> 
                                    <li class="divider"></li>
                                    <li> <a href="account.php?view=account"> Edit Account </a> </li>	
                                    <li class="divider"></li>
									<li> <a href="index.php?action=logout"> Log Out </a> </li>
                                </ul>				
                        </li>';
                    
                        }
                            
                        elseif($_SESSION['loggedin'] != 2) {
                        
                        echo'<li class="dropdown topcenter " id="accountmenu">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#" style="font-family:helvetica;font-weight:100;"> Log In </b></a>
                                <ul class="dropdown-menu" style="margin-top:0px;background-color:#fff;width:192px;">
								<li><a style="color:#000;font-size:15px;" href="signup2.php">Register for free today</a></li>
                                <li class="divider"></li>';
                                
                                 if(strpos($_SERVER['REQUEST_URI'],'?') !== false) {
                                        echo'<form name="login_form" method="post" action="',htmlentities($_SERVER['REQUEST_URI']),'&action=login">';
                                    }   
                                    else {
                                         echo'<form name="login_form" method="post" action="',htmlentities($_SERVER['REQUEST_URI']),'?action=login">';
                                    }  

                                echo'
                                <li style="margin-left:15px;color:#000;">Email: </li>
                                <li><input type="text" style="width:150px;margin-top:3px;margin-left:15px;" name="emailaddress" /></li>
                                <li><span style="float:left;margin-left:15px;color:#000;">Password: </li>
                                <input type="password" style="width:150px;margin-top:3px;margin-left:15px;" name="password"/></li>
                                <li style="margin-left: 110px;"><input type="submit" class="btn btn-success" value="Sign In" id="loginButton"/></li>
                                </form>
								</ul>
						</li>';
                        
                        }
                
                    echo'
				</div>
			</div>	
		</div>
	</div>';
}


function footersweet() {

echo'
<link rel="stylesheet" href="../css/all.css" type="text/css"/> 
<div class="fixed-bottom" style="width:100%;background:#ccc;box-shadow: inset 1px 1px 1px #999;">
	<div class="container_24">
	<div class="grid_24" style="margin-top:.1em;">	
	<div class="grid_18 push_2">
		<ul class="footer">
			<li> <a href="about.php"><div class="footer_grid"> About </div> </a> </li>
			<li> <a href="contact.php"><div class="footer_grid">Contact Us </div></a> </li>
			<li> <a href="help.php"><div class="footer_grid">Help/FAQ </div></a> </li>
			<li> <a href="terms.php"><div class="footer_grid">Terms </div></a> </li>
			<li> <a href="privacy.php"><div class="footer_grid">Privacy Policy </div></a> </li>
			<li> <a href="blog/post"><div class="footer_grid">Blog  </div></a></li>
			<li> <a href="press.php"><div class="footer_grid">Press </div></a></li>
		</ul>
	</div>	
		<div class="grid_4 pull_1" style="margin: 1em 3em 0 0 ">
			<div class="grid_1" style="float:right;"><a class="twitter" href="https://twitter.com/photorankr"><img src="graphics/twitter.png"/> </a>
			</div>
			<div class="grid_1" style="float:right;"><a class="twitter" href="http://www.facebook.com/pages/PhotoRankr/140599622721692"><img src="graphics/facebook.png"/> </a>
			</div>
			<div class="grid_1" style="float:right;"><a class="twitter" href="https://plus.google.com/102253183291914861528/posts"><img src="graphics/g+.png"/> </a>
			</div>
	</div>
<div class="grid_24">
	<p class="copyright" style="margin-top:1em;">PhotoRankr is a trademark of PhotoRankr, Inc. The PhotoRankr Logo is a trademark of PhotoRankr, Inc. </p>
<p class="copyright" style="margin-bottom:1em;">Copyright &copy 2012 PhotoRankr, Inc.</p>
</div>
</div>';

}



?>