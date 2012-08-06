<?php
ini_set('max_input_time', 300);   


function login() {
    @session_start();

        // makes sure they filled it in
        if(!htmlentities($_POST['emailaddress']) | !htmlentities($_POST['password'])) {
            die('You did not fill in a required field.');
        }

        $check = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '".mysql_real_escape_string($_POST['emailaddress'])."'")or die(mysql_error());
        //Gives error if user dosen't exist

        $check2 = mysql_num_rows($check);
    
        if ($check2 == 0) {
            die('That user does not exist in our database. <a href="signin.php">Click Here to Register</a> or <a href="lostpassword.php">here to recover a forgotten password</a>.');
        }

        $info = mysql_fetch_array($check);
        
        if(mysql_real_escape_string($_POST['password']) == mysql_real_escape_string($info['password'])){
            //then redirect them to the same page as signed in and set loggedin to 1
            $_SESSION['loggedin'] = 1;
            $_SESSION['email'] = mysql_real_escape_string($_POST['emailaddress']);
        }
        //gives error if the password is wrong
        else if (mysql_real_escape_string($_POST['password']) != mysql_real_escape_string($info['password'])) {
            die('Incorrect password, please try again. <a href="lostpassword.php"> Lost your password?</a>');   
        }
}

function logout() {
    session_start();
    $_SESSION['loggedin'] = 0;
    $_SESSION['email'] = "";

    session_destroy();
}

function navbarnew() {
echo'
<link rel="stylesheet" href="css/style.css" type="text/css"/> 
<div class="navbar navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container" style="height:50px;width:1040px;">
				<ul class="nav" style="height:50px;">
					<li class="topcenter"> <a href="index.php"> <img src="graphics/PRlogowithtext.png" style="positon:fixed;top:.0em;left:1em;height:40px;margin-top:-8px;"/></a></li>
					<li class="margint"> <form class="navbar-search" action="search.php" method="get">
<input class="search3 margint marginl" style="height:1.4em;padding-right:25px;margin-left:5em;margin-right:5.5em" name="searchterm" type="text">
</form></li>
					<li class="marginL"> <a href="home.php"> Home </a> </li>
					<li> <a href="blog.php"> Blog </a> </li>
					<li class="dropdown topcenter " id="accountmenu">
						<a class="dropdown-toggle" data-toggle="dropdown" href="#"> Photos </b></a>
							<ul class="dropdown-menu" style="background-color:#fff;">
								<li> <a href="newest.php"> New </a></li>
								<li> <a href="trending.php"> Trending </a></li>
								<li class="divider"></li> 								<li> <a href="topranked.php"> Top Ranked </a></li>
								<li> <a href="discover.php"> Discover </a> </li>
							</ul>
						</li>
						<li class="dropdown topcenter" id="accountmenu">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#"> Market</b> </a>
							<ul class="dropdown-menu" style="background-color:#fff;">
								<li> <a href="newest.php"> Marketplace </a></li>
								<li> <a href="trending.php"> Campaigns </a></li>
							</ul>
						</li>';
                        
                        if($_SESSION['loggedin'] == 1) {
                            $email = $_SESSION['email'];
                            
                            $profilequery = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$email'");
                            $profilepic = mysql_result($profilequery,0,'profilepic');
                            $fullname = mysql_result($profilequery,0,'firstname')." ".mysql_result($profilequery,0,'lastname');
                        
                        echo'
						<li class="dropdown"  id="accountmenu">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#"> <div class="notifications" style="margin-top:-5px;"><div style="position:relative;top:4px;color:#6aae45;font-size:13px;font-weight:bolder;">1</div></div> </a>
								<ul class="dropdown-menu" style="margin-top:-.16em;background-color:#fff;">
									<li> <a href="magiclogoutfunction.php"> notify me </a> </li>
									<li class="divider"></li>
									<li> <a href="magiclogoutfunction.php"> notify me </a> </li>
									<li class="divider"></li>
									<li> <a href="magiclogoutfunction.php"> notify me </a> </li>
									<li class="divider"></li>
									<li> <a href="magiclogoutfunction.php"> notify me </a> </li>
									<li class="divider"></li>
									<li> <a href="settingsthings.php"> so many notifications!  </a> </li>
								</ul>	
							</li>
						<li class="dropdown topcenter marginT" id="accountmenu">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#"> <img src="',$profilepic,'" style="width:30px;height:30px;"/><span style="font-size:14px;color:white;font-weight:200;">&nbsp;&nbsp;&nbsp;',$fullname,'</span></a>
								<ul class="dropdown-menu" style="margin-top:-.36em;background-color:#fff;">
									<li> <a href="magiclogoutfunction.php"> Settings </a> </li>
									<li class="divider"></li>
									<li> <a href="trending.php?action=logout"> Log Out </a> </li>
								</ul>	
							</li>	
					</ul>';	
                    
                            }
                
                    echo'
				</div>
			</div>	
		</div>
	</div>';
}

function navbar() {
echo'
<link rel="stylesheet" href="css/style.css" type="text/css"/> 
<div style="width:100%;height:50px;border-bottom:1px solid #999;min-width:1085px;position:fixed;z-index:15;" class="gunmetal">
<a href="/market"><img class="hover" style="float:left;padding-left:30px;padding-top:2px;" src="market/graphics/logotext.png" width="210" /></a>
<label style="float:left;font-size:13px;color:white;padding-top:15px;padding-left:80px;font-weight:normal;">Search</label>

<form class="navbar-search" action="/market/#search" method="get">
<input class="search" style="position:relative;margin-left:10px;margin-top:2px;" name="searchterm" type="text">
<a href="#search"><input style="margin-top:3px;margin-left:-3px;" type="submit" class="go" value="Go"></a>
</form>

';
@session_start();
if($_SESSION['loggedin'] != 1) {
echo'
<div style="float:right;margin-top:15px;">
<a class="navhover" style="font-weight:lighter;" href="/market">Galleries</a>
<a class="navhover" href="viewcampaigns.php">Market</a>
<a class="navhover" href="blog.php">Blog</a>
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
 </span>
 </div>';
}
elseif($_SESSION['loggedin'] == 1) {
   $email = $_SESSION['email'];

$profilequery = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$email'");
$profilepic = mysql_result($profilequery,0,'profilepic');
$fullname = mysql_result($profilequery,0,'firstname')." ".mysql_result($profilequery,0,'lastname');

echo'
<div style="float:right;">

<a href="account.php" class="dropdown-toggle navhover" data-toggle="dropdown" style="position:relative;top:8px;">
<div style="height:50px;width:auto;float:right;">
<img style="border:1px solid #ccc;margin-top:8px;margin-left:3px;" src="',$profilepic,'" height="30" width="30" />
<span style="font-size:13px;font-weight:bold;">',$fullname,'</span></div></a>

<span class="dropdown">
<ul class="dropdown-menu gunmetal" data-dropdown="dropdown" style="width:200px;margin-top:30px;margin-left:-40px;">
<a style="padding:15px;color:white;" href="account.php">Saved Images</a><hr />
<a style="padding:15px;padding-top:0px;color:white;" href="download2.php">Cart</a><hr />
<a style="padding:15px;padding-top:0px;color:white;" href="account.php?view=campaigns">Manage Campaigns</a>
</ul>
</span>

<a class="navhover" style="float:right;margin-top:4px;padding-right:30px;" href="blog.php">Blog</a>
<a class="navhover" style="float:right;margin-top:4px;" href="viewcampaigns.php">Market</a>
<a class="navhover" style="font-weight:lighter;float:right;margin-top:4px;" href="/market">Galleries</a>
<a class="navhover" style="font-weight:lighter;float:right;margin-top:4px;" href="/market">Home</a>

</div>';

}
echo'
</div>
';
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
    <li><a class="foot"  style="text-decoration:none;" href="http://photorankr.com/contact.php">Contact US</a></li>
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
    </br>
    <p1 class="navgreen">Follow</p1>
    
    <div style="padding-left:25px;width:250px">
             <div style="margin-right:10px;float:left">
             <a href="https://www.facebook.com/pages/PhotoRankr/140599622721692"><img style="width:25px;height:25px;" src="graphics/facebook.jpg"/></a>
             </div>

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
<p1>Copyright � 2012 PhotoRankr, Inc.<p1>
</div>
  </div> 
</div>
 
    <br />
    <br />                   
    </div>';
    
}



?>