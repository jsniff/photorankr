<?php

function login() {
        
        session_start();
        $sessionemail = mysql_real_escape_string($_POST['emailaddress']);
        
        // makes sure they filled it in
        if(!htmlentities($_POST['emailaddress'])) {
            header('Location: signup.php?action=fie');
            die();
        }
        
        if(!htmlentities($_POST['password'])) {
            header('Location: signup.php?action=fip');
            die();
        }


        $check = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '".mysql_real_escape_string($_POST['emailaddress'])."'")or die(mysql_error());
        //Gives error if user dosen't exist

        $check2 = mysql_num_rows($check);
    
        if ($check2 == 0) {
            header('Location: signup.php?action=nu');
            die(); 
        }

        $info = mysql_fetch_array($check);
		
		$salt1 = "@bRa";
		$salt2 = "Cad@bra!";
		$plainpass = $_POST['password'];
		$shapass = sha1($salt1.$plainpass.$salt2);
		for($i=0;$i<20000;$i++){
			$shapass = sha1($shapass);
		}
        
        if(mysql_real_escape_string($shapass) == mysql_real_escape_string($info['password'])){
            //then redirect them to the same page as signed in and set loggedin to 1
            session_start();
            $_SESSION[$sessionemail] = 1;
            $_SESSION['loggedin'] = 1;
            $_SESSION['email'] = mysql_real_escape_string($_POST['emailaddress']);
            
        }
        
        //gives error if the password is wrong
        else if (mysql_real_escape_string($shapass) != mysql_real_escape_string($info['password'])) {
            header('Location: signup.php?action=lp');
            die();   
        }
}

function logout() {
    
    $emailaddress = $_SESSION['email'];
    session_start();
    $_SESSION['loggedin'] = 0;
    $_SESSION['email'] = 0;
    $_SESSION[$emailaddress] = "";
    session_destroy();
    
}


function navbar() {

$uri = $_SERVER['REQUEST_URI'];
$view = htmlentities($_GET['v']);
include('timefunction.php')

?>


<script type="text/javascript">
function createRequestObject() {

    var ajaxRequest;  //ajax variable
	
	try{
		// Opera 8.0+, Firefox, Safari
		ajaxRequest = new XMLHttpRequest();
	} catch (e){
		// Internet Explorer Browsers
		try{
			ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try{
				ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e){
				// Something went wrong
				alert("Your browser broke!");
				return false;
			}
		}
	}
    
    return ajaxRequest;
    
}


function showResult(str)
{

    ajaxRequest = createRequestObject();

 // Create a function that will receive data sent from the server
	ajaxRequest.onreadystatechange = function(){
		if(ajaxRequest.readyState == 4  && ajaxRequest.status == 200){
			var ajaxDisplay = document.getElementById('livesearch');
            ajaxDisplay.innerHTML = ajaxRequest.responseText;
		}
	}
	
	ajaxRequest.open("GET", "searchresults.php?q=" + str, true);
	ajaxRequest.send(null); 

}
</script>

<style type="text/css">
.sidebar-nav {
    padding: 9px 0;
}

.dropdown-menu .sub-menu {
    left: 100%;
    position: absolute;
    top: 0;
    visibility: hidden;
    margin-top: -1px;
}

.dropdown-menu li:hover .sub-menu {
    visibility: visible;
}

.dropdown:hover .dropdown-menu {
    display: block;
}

.nav-tabs .dropdown-menu, .nav-pills .dropdown-menu, .navbar .dropdown-menu {
    margin-top: 0;
}

.navbar .sub-menu:before {
    border-bottom: 7px solid transparent;
    border-left: none;
    border-right: 7px solid rgba(0, 0, 0, 0.2);
    border-top: 7px solid transparent;
    left: -7px;
    top: 10px;
}
.navbar .sub-menu:after {
    border-top: 6px solid transparent;
    border-left: none;
    border-right: 6px solid #fff;
    border-bottom: 6px solid transparent;
    left: 10px;
    top: 11px;
    left: -6px;
}

</style>

<script>

jQuery(document).ready(function(){
    jQuery("#hideshow").live("click", function(event) {        
         jQuery("#notifbox").toggle();
    });
    jQuery("#container").live("click", function(event) {        
         jQuery("#notifbox").hide();
	 jQuery("#searchDiv").hide();
    });
    jQuery("#showNots").live("click", function(event) {        
         jQuery("#nots").show();
         jQuery("#cartNot").hide();
         jQuery("#groupsNot").hide();
    });
    jQuery("#showCart").live("click", function(event) {        
         jQuery("#cartNot").show();
         jQuery("#nots").hide();
         jQuery("#groupsNot").hide();
    });
    jQuery("#showGroups").live("click", function(event) {        
         jQuery("#groupsNot").show();
         jQuery("#cartNot").hide();
         jQuery("#nots").hide();
    });
    jQuery("#signUp").live("click", function(event) {        
         jQuery("#hiddenSignUp").toggle();
    });

});

</script>

<?php

    //Session Information
    if($_SESSION['loggedin'] == 1) {
        $email = $_SESSION['email'];
        $profilequery = mysql_query("SELECT user_id,firstname,lastname,profilepic FROM userinfo WHERE emailaddress = '$email'");
        $sessionfirst = mysql_result($profilequery,0,'firstname');
        $sessionlast = mysql_result($profilequery,0,'lastname');
        $sessionfull = $sessionfirst." ".$sessionlast;
        $sessionprofileid = mysql_result($profilequery,0,'user_id');
        $sessionprofilepic = mysql_result($profilequery,0,'profilepic'); 
    }
    
    
    //Main Left Bar Start
    echo'<div id="Main">
    
	<div id="leftBar" style="height:100%;width:70px;">
	<ul>
		<li><img style="width:60px;" src="graphics/aperature_dark.png" /><img src="graphics/logo_text.png" style="margin-top:-18px;width:60px!important;
    height:12px !important;"/> </li>';
    
    //show newsfeed if logged in
    if($_SESSION['loggedin'] == 1) { 
        echo'<li id="shadowleft"'; if(strpos($uri,'newsfeed.php')) {echo' style="box-shadow: inset 0 0 6px #444; "';} echo'><a href="newsfeed.php"><img src="graphics/news_b.png"/><p> News </p></a></li>';
    }
    
    echo'
        <li id="shadowleft" class="dropdown"'; if(strpos($uri,'galleries.php') || strpos($uri,'newest') || strpos($uri,'trending') || strpos($uri,'topranked') || strpos($uri,'discover') || strpos($uri,'fullsize.php') || strpos($uri,'fullsizeview.php')) {echo' style="box-shadow: inset 0 0 6px #444;"';} echo'><a data-toggle="dropdown" class="dropdown-toggle" href="#"><a style="text-decoration:none;" href="https://photorankr.com/galleries.php"><img src="graphics/galleries_b.png"/><p> Galleries </p></a></a>
        
            <ul class="dropdown-menu" style="height:67px;width:388px;margin-top:-75px;margin-left:73px;background-color:#555;    background-color:rgba(224, 224, 224, 01);box-shadow: 2px 0px 3px #222;border:none;-webkit-border-top-right-radius: 2px;-webkit-border-bottom-right-radius: 2px;-moz-border-radius-topright: 2px;-moz-border-radius-bottomright: 2px;border-top-right-radius: 2px;border-bottom-right-radius: 2px;-webkit-border-top-left-radius: 1px;-webkit-border-bottom-left-radius: 1px;-moz-border-radius-topleft: 1px;-moz-border-radius-bottomleft: 1px;border-top-left-radius: 1px;border-bottom-left-radius: 1px;">

                <a style="text-decoration:none;color:#fff;display:inline;" href="newest"><li class="horizontalDrop" style="display:inline;width:145px;margin-top:-5px;"> <img style="padding:3px;width:20px;height:20px;" src="graphics/clock.png" /> <br /> Newest </li></a>
                <a style="text-decoration:none;color:#fff;display:inline;" href="trending"><li class="horizontalDrop" style="display:inline;width:145px;margin-top:-5px;"><img style="padding:3px;width:20px;height:20px;" src="graphics/graph.png" /> <br /> Trending </li></a>
                 <a style="text-decoration:none;color:#fff;display:inline;" href="topranked"> <li class="horizontalDrop" style="display:inline;width:145px;margin-top:-5px;"><img style="padding:3px;width:20px;height:20px;" src="graphics/award.png" /> <br /> Top Ranked </li></a>
                 <a style="text-decoration:none;color:#fff;display:inline;" href="discover"> <li class="horizontalDrop" style="display:inline;width:145px;margin-top:-5px;"><img style="padding:3px;width:20px;height:20px;" src="graphics/picture.png" /> <br /> Discover </li></a>
                 <a style="text-decoration:none;color:#fff;display:inline;" href="galleries.php"> <li class="horizontalDrop" style="display:inline;width:145px;margin-top:-5px;"><img style="padding:3px;width:20px;height:20px;" src="graphics/star.png" /> <br /> Featured </li></a>
            </ul>
        </li>
        
		<li id="shadow" '; if(strpos($uri,'groups.php')) {echo' style="box-shadow: inset 0 0 6px #444;"';} echo'><a href="groups.php"><img src="graphics/groups_b.png"/><p> Groups </p></a></li>
        
		<li id="shadowleft"'; if(strpos($uri,'market.php') || strpos($uri,'cart.php')) {echo' style="box-shadow: inset 0 0 6px #444;"';} echo'><a href="market.php"><img src="graphics/market_b.png"/><p> Market </p></a></li>
        
		<li id="shadowleft"'; if(strpos($uri,'blog.php')) {echo' style="box-shadow: inset 0 0 6px #444;"';} echo'><a href="blog.php"><img src="graphics/blog_b.png"/><p> Blog </p></a></li>
	</ul>
</div>

<div class="CNav">
	<ul>';
        
        if(strpos($uri,'galleries.php') || strpos($uri,'newest') || strpos($uri,'trending') || strpos($uri,'discover') || strpos($uri,'topranked') || strpos($uri,'fullsize.php') || strpos($uri,'fullsizeview.php')) {
        
    echo'        
        <li class="dropdown">
            <a style="color:#fff;text-decoration:none;" class="dropdown-toggle" data-toggle="dropdown" href="#"> Photos</b> </a>
            <ul class="dropdown-menu" style="height:120px;margin-top:0px;background-color:#555;">
                <a style="text-decoration:none;color:#fff;" href="newest"><li id="shadow" class="CNavDrop" style="width:145px;margin-top:-5px;"><i style="float:left;margin-top:2px;padding-right:4px;" class="icon-time icon-white"></i> Newest </li></a>
                <a style="text-decoration:none;color:#fff;" href="trending"><li id="shadow" class="CNavDrop" style="width:145px;"><i style="float:left;margin-top:2px;padding-right:4px;" class="icon-fire icon-white"></i> Trending </li></a>
                 <a style="text-decoration:none;color:#fff;" href="topranked"> <li id="shadow" class="CNavDrop" style="width:145px;"><i style="float:left;margin-top:2px;padding-right:4px;" class="icon-star icon-white"></i> Top Ranked </li></a>
            </ul>
        </li>
        
        <li id="shadow" '; if(strpos($uri,'discover') || $view == 'n') {echo' id="topselected"';} echo'><a style="text-decoration:none;color:#fff;" href="discover"> Discover </a></li>
        
        <li id="shadow" '; if(strpos($uri,'galleries.php') || $view == 'n') {echo' id="topselected"';} echo'><a style="text-decoration:none;color:#fff;" href="galleries.php">Featured</a></li>';
        }
        
        elseif(strpos($uri,'market.php') || strpos($uri,'cart.php')) {
		echo'
        <li id="shadow" '; if(strpos($uri,'market.php')) {echo' id="topselected"';} echo'><a style="text-decoration:none;color:#fff;" href="market.php">Home</a></li>
		<li id="shadow" '; if(strpos($uri,'cart.php')) {echo' id="topselected"';} echo'><a style="text-decoration:none;color:#fff;" href="cart.php">My Cart</a></li>
        <li id="shadow" '; if(strpos($uri,'cart.php?view=wishlist')) {echo' id="topselected"';} echo'><a style="text-decoration:none;color:#fff;" href="cart.php?view=maybe">Wish List</a></li>';
        }
        
        elseif(strpos($uri,'groups.php')) {
		echo'
        <li id="shadow" '; if($uri == '/groups.php') {echo' id="topselected"';} echo'><a style="text-decoration:none;color:#fff;" href="groups.php">Groups Home</a></li>';
        }
        
        elseif(strpos($uri,'viewprofile.php')) {
        $userid = htmlentities($_GET['u']);
		echo'
        <li id="shadow" '; if($uri == 'profile.php') {echo' id="topselected"';} echo'><a style="text-decoration:none;color:#fff;" href="viewprofile.php?u=',$userid,'"> Portfolio </a></li>
		<li id="shadow" '; if(strpos($uri,'view=store')) {echo' id="topselected"';} echo'><a style="text-decoration:none;color:#fff;" href="viewprofile.php?u=',$userid,'&view=store"> Store </a></li>
        <li id="shadow" '; if(strpos($uri,'view=faves')) {echo' id="topselected"';} echo'><a style="text-decoration:none;color:#fff;" href="viewprofile.php?u=',$userid,'&view=favorites">  Favorites </a></li>';
        }
        
        elseif(strpos($uri,'profile.php')) {
		echo'
        <li id="shadow" '; if($uri == 'profile.php') {echo' id="topselected"';} echo'><a style="text-decoration:none;color:#fff;" href="profile.php">My Portfolio</a></li>
		<li id="shadow" '; if(strpos($uri,'view=store')) {echo' id="topselected"';} echo'><a style="text-decoration:none;color:#fff;" href="profile.php?view=store">My Store</a></li>
        <li id="shadow" '; if(strpos($uri,'view=faves')) {echo' id="topselected"';} echo'><a style="text-decoration:none;color:#fff;" href="profile.php?view=faves">My Favorites</a></li>';
        }
      
        elseif(strpos($uri,'newsfeed.php')) {
		echo'
        <li id="shadow" '; if($uri == '/newsfeed.php') {echo' id="topselected"';} echo'><a style="text-decoration:none;color:#fff;" href="newsfeed.php">All News</a></li>
		<li id="shadow" '; if(strpos($uri,'uploads')) {echo' id="topselected"';} echo'><a style="text-decoration:none;color:#fff;" href="newsfeed.php?view=uploads">Uploads</a></li>
		<li id="shadow" '; if(strpos($uri,'favorites')) {echo' id="topselected"';} echo'><a style="text-decoration:none;color:#fff;" href="newsfeed.php?view=favorites">Favorites</a></li>';
        }
        
        //Log Out Button
        if($_SESSION['loggedin'] == 1) { 
            if(strpos($_SERVER['REQUEST_URI'],'profile.php') !== false) {
                echo'<a href="newest?action=logout"><li>  Log Out </li></a>';
            }
            elseif(strpos($_SERVER['REQUEST_URI'],'?') !== false) {
                echo'<a href="',$_SERVER['REQUEST_URI'],'&action=logout"> <li> Log Out </li></a>';
            }   
            else {
                echo'<a href="',$_SERVER['REQUEST_URI'],'?action=logout"><li> Log Out </li> </a>';
            }
        }
        
        //Log In Button
        elseif($_SESSION['loggedin'] != 1 && !strpos($uri,'signup.php') && !strpos($uri,'register.php') && !strpos($uri,'reg_success.php')) { 
            echo'<li class="dropdown" id="accountmenu">
                        <a data-toggle="dropdown" class="dropdown-toggle"  href="#" style="font-family:helvetica;text-decoration:none;color:#fff;"> Log In </b></a>
                        <ul class="dropdown-menu" style="margin-top:0px;background-color:#333;width:192px;margin-left:10px;">
                            <div><a style="color:#fff;font-size:15px;" href="signup3.php">Register for free today</a></div>';
                            
                            if(strpos($_SERVER['REQUEST_URI'],'?') !== false) {
                                echo'<form name="login_form" method="post" action="',htmlentities($_SERVER['REQUEST_URI']),'&action=login">';
                            }   
                            else {
                                echo'<form name="login_form" method="post" action="',htmlentities($_SERVER['REQUEST_URI']),'?action=login">';
                            }                                
                        echo'
                            <div style="margin-left:15px;margin-top:15px;color:#fff;float:left;">Email: </div>
                            <div><input type="text" style="width:155px!important;height:22px!important;font-size:15px!important;margin-top:3px;margin-left:15px;float:left;background-color:#fff;color:#333;padding-right:0px;" name="emailaddress" autocomplete="on" /></div>
                            <div><span style="float:left;margin-left:15px;color:#fff;float:left;">Password: </div>
                            <input type="password" style="width:155px!important;height:22px!important;font-size:15px!important;margin-top:3px;margin-left:15px;float:left;background-color:#fff;color:#333;padding-right:0px;" name="password" />
                            <div style="float:left;text-align:center;padding-botom:5px;"><input type="submit" class="btn btn-success" value="Log In" style="font-size:18px!important;width:160px!important;margin-top:10px;margin-left:15px;padding-left:35px!important;background-image:none;" id="loginButton" /></div>
                        </form>
                    </ul>
                </li>
                
                <!--Search-->
                <li style="width:120px;margin-top:2px;height:19px;color:#333;float:left;margin-left:-10px;">
            <form action="search.php" method="get">
                <input type="text" style="color:#fff;font-size:16px;font-weight:300;width:210px!important;" onkeyup="showResult(this.value)" name="searchterm" placeholder="Search&hellip;" />
                <div id="livesearch"></div>
            </form>	
            </li>';
        }

        
        if($_SESSION['loggedin'] == 1) { 
            
            echo'
            <li class="dropdown" style="float:right;height:19px;">
            <!-- creation menu -->
				<div id="add">
					<a id="addbutton" href="profile.php?view=upload" class="dropdown-toggle" data-toggle="dropdown"><div id="plus"></div>UPLOAD</a>
                <ul class="dropdown-menu" style="height:163px;margin-top:0px;margin-left:-44px;background-color:#555;">
                <a style="text-decoration:none;color:#fff;" href="profile.php?view=upload"><li id="shadow" class="CNavDrop" style="width:145px;margin-top:-5px;"><i style="float:left;margin-top:2px;padding-right:4px;" class="icon-upload icon-white"></i> Upload </li></a>
                <a style="text-decoration:none;color:#fff;" href="profile.php?view=upload&option=batch"><li id="shadow" class="CNavDrop" style="width:145px;"><i style="float:left;margin-top:2px;padding-right:4px;" class="icon-arrow-up icon-white"></i> Batch </li></a>
                 <a style="text-decoration:none;color:#fff;" href="profile.php?view=upload&option=newexhibit"> <li id="shadow" class="CNavDrop" style="width:145px;"><i style="float:left;margin-top:2px;padding-right:4px;" class="icon-th-large icon-white"></i> New Exhibit </li></a>
                <a style="text-decoration:none;color:#fff;" href="profile.php?view=collections&option=newcollection"> <li id="shadow" class="CNavDrop" style="width:145px;"><i style="float:left;margin-top:2px;padding-right:4px;" class=" icon-folder-open icon-white"></i> New Collection </li></a>

            </ul>
        
        </div>
        </li>';
            
            //Notifications Start Here!
            
                //QUERY FOR NOTIFICATION COUNT
                $currentnots = "SELECT notifications FROM userinfo WHERE emailaddress = '$email'";
                $currentnotsquery = mysql_query($currentnots);
                $currentnotsresult = mysql_result($currentnotsquery, 0, "notifications");
                
                //NOTIFICATIONS
                $emailquery = mysql_query("SELECT following,groups FROM userinfo WHERE emailaddress ='$email'");
                $followinglist = mysql_result($emailquery, 0, "following");
                $groupslist = mysql_result($emailquery, 0, "groups");
                $groupslist = substr($emailquery,0,-1);     
                $notsquery = mysql_query("SELECT * FROM newsfeed WHERE (owner = '$email' AND emailaddress != '$email') OR following = '$email' ORDER BY id DESC");  
                $numnots = mysql_num_rows($notsquery);

                //DECIDE WHICH NOTIFICATIONS TO WHITEN (ONES ALREADY CLICKED ON)
                $unhighlightquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$email'");
                $whitenlist=mysql_result($unhighlightquery, 0, "unhighlight");


               echo'<li class="dropdown" id="accountmenu" style="float:right;margin-right:30px;width:30px;height:19px;margin-top:-6px;"> 
                <div class="dropdown-toggle notificationBox" data-toggle="dropdown" href="#">
                    <i style="margin-top:3px;padding-right:2px;" class="icon-globe icon-white"></i> ',$currentnotsresult,' 
                </div>
				<ul class="dropdown-menu notsDropDown uiScrollableAreaTrack invisible_elem" style="width:310px;overflow:hidden;overflow-y:scroll;">
                <div class="notsTriangle"></div>';
                
                if($numnots > 0) { 
                    
                    for($iii=0; $iii <= 20; $iii++) {
                        $firstname = mysql_result($notsquery,$iii,'firstname');
                        $lastname = mysql_result($notsquery,$iii,'lastname');
                        $owneremail = mysql_result($notsquery,$iii,'owner');
                        $fullname = $firstname . " " . $lastname;
                        $fullname = ucwords($fullname);
                        $fullname = (strlen($fullname) > 16) ? substr($fullname,0,14). "&#8230;" : $fullname;
                        $type = mysql_result($notsquery,$iii,'type');
                        $id = mysql_result($notsquery,$iii,'id');
                        $newsgroupemail = mysql_result($notsquery,$iii,'emailaddress');
                        $caption = mysql_result($notsquery,$iii,'caption');
                        $source = mysql_result($notsquery,$iii,'source');
                        $group_id = mysql_result($notsquery,$iii,'group_id');
                        $time = mysql_result($notsquery,$iii,'time');
                        $time = converttime($time);
                        
                        //group info
                        $groupinfo = mysql_query("SELECT * FROM groups WHERE id = '$group_id'");
                        $groupname = mysql_result($groupinfo,0,'name');
                        $commentphotoquery = mysql_query("SELECT source,id FROM photos WHERE (id = '$source' or source = '$source')");
                        $commentphoto = mysql_result($commentphotoquery,0,'source');
                        $imageid = mysql_result($commentphotoquery,0,'id');
                                    
                        $newsource = str_replace("userphotos/","userphotos/thumbs/", $source);
                        $commentphotosource = str_replace("userphotos/","userphotos/thumbs/", $commentphoto);
                        $exhibitsource = mysql_query("SELECT cover FROM sets WHERE id = '$source'");
                        $setcover = mysql_result($exhibitsource,$iii,'cover');
                            if(!$setcover) {
                                $pulltopphoto = mysql_query("SELECT source FROM photos WHERE set_id = '$source' ORDER BY votes DESC LIMIT 1");
                                $setcover = mysql_result($pulltopphoto, 0, "source");
                            }
                        $setcover = str_replace("userphotos/","userphotos/thumbs/", $setcover);
                                    
                        $blogcommenteremail = mysql_result($notsquery,$iii,'emailaddress');
                        $followeremail = mysql_result($notsquery,$iii,'emailaddress');
                        $ownermessage = mysql_result($notsquery,$iii,'owner');
                        $thread = mysql_result($notsquery,$iii,'thread');

                        //SEARCH IF ID IS IN UNHIGHLIGHT LIST
                        $match=strpos($whitenlist,$id);
            
                        if($match < 1) {
                            $highlightid = 'greenshadowhighlight';
                        }
                                    
                        elseif($match > 0) {
                            $highlightid = 'greenshadow';
                        }
                        
                        if($type == "comment") {
                           echo'<a style="text-decoration:none;" href="fullsize.php?imageid=',$source,'&id=',$id,'">
                                <div style="padding:3px;clear:both;overflow:hidden;border-bottom:1px solid #aaa;color:#aaa;padding-left:0px;">
                                 <img style="padding-left:2px;float:left;width:80px;height:80px;" src="http://www.photorankr.com/',$commentphotosource,'" />
                                    <div class="commentTriangle" style="margin-top:-20px;"></div>
                                    <div style="width:200px;float:left;padding-left:10px;height:55px;margin-top:10px;text-align:left;font-size:13px;font-weight:300;color:#333;">
                                        <span style="width:15px;"><img src="graphics/comment_1.png" height="15" />&nbsp;&nbsp;&nbsp;<b>',$fullname,'</b> commented on your photo<br /><span style="font-size:12px;color:#666;font-weight:700;">',$time,'</span></span>
                                    </div>
                                </div>
                            </a>';
                        } //end type comments
                        
                        if($type == "sold" && $owneremail == $email) {
                           echo'<a style="text-decoration:none;" href="fullsize.php?imageid=',$source,'&id=',$id,'">
                                <div style="padding:3px;clear:both;overflow:hidden;border-bottom:1px solid #aaa;color:#aaa;padding-left:0px;">
                                 <img style="padding-left:2px;float:left;width:80px;height:80px;" src="http://www.photorankr.com/',$commentphotosource,'" />
                                    <div class="commentTriangle" style="margin-top:-20px;"></div>
                                    <div style="width:200px;float:left;padding-left:10px;height:55px;margin-top:10px;text-align:left;font-size:13px;font-weight:300;color:#333;">
                                        <span style="width:15px;"><img src="graphics/tag.png" height="15" />&nbsp;&nbsp;&nbsp;<b>',$fullname,'</b> purchased your photo<br /><span style="font-size:12px;color:#666;font-weight:700;">',$time,'</span></span>
                                    </div>
                                </div>
                            </a>';
                        } //end type comments
                        
                        if($type == "groupcomment") {
                            $ownerpicquery = mysql_query("SELECT profilepic FROM userinfo WHERE emailaddress = '$newsgroupemail'");
                            $profilepic = mysql_result($ownerpicquery,0,'profilepic');
                            if($profilepic == "") {
                                $profilepic = "profilepics/default_profile.jpg";
                            }
                           echo'<a style="text-decoration:none;" href="groups.php?id=',$group_id,'#',$source,'">
                                <div style="padding:3px;clear:both;overflow:hidden;border-bottom:1px solid #aaa;color:#aaa;padding-left:0px;">
                                 <img style="padding-left:2px;float:left;width:80px;height:80px;" src="http://www.photorankr.com/',$profilepic,'" />
                                    <div class="commentTriangle" style="margin-top:-20px;"></div>
                                    <div style="width:200px;float:left;padding-left:10px;height:55px;margin-top:10px;text-align:left;font-size:13px;font-weight:300;color:#333;">
                                        <span style="width:15px;"><img src="graphics/comment_1.png" height="15" />&nbsp;&nbsp;&nbsp;<b>',$fullname,'</b> commented on your post in ',$groupname,' <br /><span style="font-size:12px;color:#666;font-weight:700;">',$time,'</span></span>
                                    </div>
                                </div>
                            </a>';
                        } //end type comments

                        elseif($type == "fave") {
                            echo'<a style="text-decoration:none;" href="fullsize.php?imageid=',$imageid,'&id=',$id,'">
                                <div style="padding:3px;clear:both;overflow:hidden;border-bottom:1px solid #aaa;color:#aaa;padding-left:0px;">
                                 <img style="padding-left:2px;float:left;width:80px;height:80px;" src="http://www.photorankr.com/',$newsource,'" />
                                    <div class="commentTriangle" style="margin-top:-20px;"></div>
                                    <div style="width:200px;float:left;padding-left:10px;height:55px;margin-top:10px;text-align:left;font-size:13px;font-weight:300;color:#333;">
                                        <span style="width:15px;"><img src="graphics/heart.png" height="15" />&nbsp;&nbsp;&nbsp;<b>',$fullname,'</b> favorited your photo<br /><span style="font-size:12px;color:#666;font-weight:700;">',$time,'</span></span>
                                    </div>
                                </div>
                            </a>';

                        } //end type faves
                        
                         elseif($type == "exhibitfave") {
                            echo'<a style="text-decoration:none;" href="profile.php?view=exhibits&set=',$source,'&id=',$id,'">
                                <div style="padding:3px;clear:both;overflow:hidden;border-bottom:1px solid #aaa;color:#aaa;padding-left:0px;">
                                 <img style="padding-left:2px;float:left;width:80px;height:80px;" src="http://www.photorankr.com/',$setcover,'" />
                                    <div class="commentTriangle" style="margin-top:-20px;"></div>
                                    <div style="width:200px;float:left;padding-left:10px;height:55px;margin-top:10px;text-align:left;font-size:13px;font-weight:300;color:#333;">
                                        <span style="width:15px;"><img src="graphics/grid.png" height="15" />&nbsp;&nbsp;&nbsp;<b>',$fullname,'</b> favorited your exhibit<br /><span style="font-size:12px;color:#666;font-weight:700;">',$time,'</span></span>
                                    </div>
                                </div>
                            </a>';

                        } //end type exhibit faves
                        
                        elseif($type == "trending") {
                            echo'<a style="text-decoration:none;" href="fullsize.php?image=',$source,'&id=',$id,'">
                                <div style="padding:3px;clear:both;overflow:hidden;border-bottom:1px solid #aaa;color:#aaa;padding-left:0px;">
                                 <img style="padding-left:2px;float:left;width:80px;height:80px;" src="http://www.photorankr.com/',$newsource,'" />
                                    <div class="commentTriangle" style="margin-top:-20px;"></div>
                                    <div style="width:200px;float:left;padding-left:10px;height:55px;margin-top:10px;text-align:left;font-size:13px;font-weight:300;color:#333;">
                                        <span style="width:15px;"><img src="graphics/graph.png" height="15" />&nbsp;&nbsp;&nbsp;Your photo is now trending<br /><span style="font-size:12px;color:#666;font-weight:700;">',$time,'</span></span>
                                    </div>
                                </div>
                            </a>';

                        } //end type trending

                        elseif($type == "follow") {
                            $newaccount = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$followeremail'");
                            $ownerid = mysql_result($newaccount,0,'user_id');
                            $profilepic = mysql_result($newaccount,0,'profilepic');
                            if($profilepic == "") {
                                $profilepic = "profilepics/default_profile.jpg";
                            }
                            
                            echo'<a style="text-decoration:none;color:#333;" href="viewprofile.php?u=',$ownerid,'&id=',$id,'">
                                <div style="padding:3px;clear:both;overflow:hidden;border-bottom:1px solid #aaa;color:#aaa;padding-left:0px;">
                                 <img style="padding-left:2px;float:left;width:80px;height:80px;" src="http://www.photorankr.com/',$profilepic,'" />
                                    <div class="commentTriangle" style="margin-top:-20px;"></div>
                                    <div style="width:200px;float:left;padding-left:10px;height:55px;margin-top:10px;text-align:left;font-size:13px;font-weight:300;color:#333;">
                                        <span style="width:15px;"><img src="graphics/user.png" height="15" />&nbsp;&nbsp;&nbsp;<b>',$fullname,'</b> is now following your photography<br /><span style="font-size:12px;color:#666;font-weight:700;">',$time,'</span></span>
                                    </div>
                                </div>
                            </a>';

                        } //end type follow
                                                
                        elseif($type == "message") {
                                            
                            if(!$followeremail) {
                                $newaccount = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$ownermessage'");
                                $profilepic = mysql_result($newaccount,0,'profilepic');
                            }
                                
                            elseif($followeremail) {
                                $newaccount = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$followeremail'");
                                $profilepic = mysql_result($newaccount,0,'profilepic');
                            }
                                            
                            elseif($profilepic == "") {
                                $profilepic = "profilepics/default_profile.jpg";
                            }
                                            
                            echo'<a style="text-decoration:none;" href="profile.php?view=messages&thread=',$thread,'&id=',$id,'">
                                <div style="padding:3px;clear:both;overflow:hidden;border-bottom:1px solid #aaa;color:#aaa;padding-left:0px;">
                                 <img style="padding-left:2px;float:left;width:80px;height:80px;" src="https://photorankr.com/',$profilepic,'" />
                                    <div class="commentTriangle" style="margin-top:-20px;"></div>
                                    <div style="width:200px;float:left;padding-left:10px;height:55px;margin-top:10px;text-align:left;font-size:13px;font-weight:300;color:#333;">
                                        <span style="width:15px;"><img src="graphics/comment_1.png" height="15" />&nbsp;&nbsp;&nbsp;<b>',$fullname,'</b> sent you a message<br /><span style="font-size:12px;color:#666;font-weight:700;"></span></span>
                                    </div>
                                </div>
                            </a>';

                        } //end type message
                        
                        elseif($type == "reply") {
                                            
                            if(!$followeremail) {
                                $newaccount = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$ownermessage'");
                                $profilepic = mysql_result($newaccount,0,'profilepic');
                            }
                                
                            elseif($followeremail) {
                                $newaccount = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$followeremail'");
                                $profilepic = mysql_result($newaccount,0,'profilepic');
                            }
                                            
                            elseif($profilepic == "") {
                                $profilepic = "profilepics/default_profile.jpg";
                            }
                                            
                            echo'<a style="text-decoration:none;" href="profile.php?view=messages&thread=',$thread,'&id=',$id,'">
                                <div style="padding:3px;clear:both;overflow:hidden;border-bottom:1px solid #aaa;color:#aaa;padding-left:0px;">
                                 <img style="padding-left:2px;float:left;width:80px;height:80px;" src="https://photorankr.com/',$profilepic,'" />
                                    <div class="commentTriangle" style="margin-top:-20px;"></div>
                                    <div style="width:200px;float:left;padding-left:10px;height:55px;margin-top:10px;text-align:left;font-size:13px;font-weight:300;color:#333;">
                                        <span style="width:15px;"><img src="graphics/comment_1.png" height="15" />&nbsp;&nbsp;&nbsp;<b>',$fullname,'</b> replied to your message<br /><span style="font-size:12px;color:#666;font-weight:700;"></span></span>
                                    </div>
                                </div>
                            </a>';

                        } //end type reply
                        
                    } //end notifications for loop
                
                } //end if not's > 0
                    
                    echo'
				</ul>
			</li>';
            }
            
            
        //Profile Pic Tab
        if($_SESSION['loggedin'] == 1) {
            $sessionfull = (strlen($sessionfull) > 14) ? substr($sessionfull,0,12). "&#8230;" : $sessionfull;
            echo'<li class="dropdown" style="width:140px;height:19px;float:right;margin-top:-5px;">        
                <a style="text-decoration:none;" href="profile.php"><img id="hideshow" style="height:30px;width:30px;" src="https://photorankr.com/',$sessionprofilepic,'" /></a>

                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" style="text-decoration:none;color:#fff;" href="profile.php"><a style="text-decoration:none;" href="profile.php"><span id="notifNum" style="color:#fff;text-align:center;font-size:13px;font-weight:500;padding-bottom:5px;">',$sessionfull,'</span></a></a>
                    
                    <ul class="dropdown-menu" style="height:205px;margin-top:0px;margin-right:10px;background-color:#555;">
                <a style="text-decoration:none;color:#fff;" href="profile.php"><li id="shadow" class="CNavDrop" style="width:145px;margin-top:-5px;"><i style="float:left;margin-top:2px;padding-right:4px;" class="icon-picture icon-white"></i>  Portfolio </li></a>
                <a style="text-decoration:none;color:#fff;" href="profile.php?view=faves"><li id="shadow" class="CNavDrop" style="width:145px;"><i style="float:left;margin-top:2px;padding-right:4px;" class="icon-heart icon-white"></i>  Favorites </li></a>
                 <a style="text-decoration:none;color:#fff;" href="profile.php?view=store"> <li id="shadow" class="CNavDrop" style="width:145px;"><i style="float:left;margin-top:2px;padding-right:4px;" class="icon-tag icon-white"></i>  Store </li></a>
                 <a style="text-decoration:none;color:#fff;" href="profile.php?view=messages"> <li id="shadow" class="CNavDrop" style="width:145px;"><i style="float:left;margin-top:2px;padding-right:4px;" class="icon-comment icon-white"></i>  Messages </li></a>
                 <a style="text-decoration:none;color:#fff;" href="profile.php?view=settings"> <li id="shadow" class="CNavDrop" style="width:145px;"><i style="float:left;margin-top:2px;padding-right:4px;" class="icon-cog icon-white"></i>  Settings </li></a>
            </ul>
        </li>';
        
        }
        
        if(!strpos($uri,'signup.php') && !strpos($uri,'register.php') && !strpos($uri,'reg_success.php') && $_SESSION['loggedin'] == 1) {
            echo'
            <li style="width:120px;margin-top:2px;height:19px;color:#333;float:right;margin-right:155px;">
            <form action="search.php" method="get">
                <input type="text" style="color:#fff;font-size:16px;font-weight:300;width:210px!important;" onkeyup="showResult(this.value)" name="searchterm" placeholder="Search&hellip;" />
                <div id="livesearch"></div>
            </form>	
            </li>';
        }
        
        echo'
	</ul>
    
    <!--<div style="padding-bottom:30px;">
        <div id="hiddenSignUp"><div style="font-size:60px;text-align:center;position:relative;top:100px;">JOIN PHOTORANKR!!!</div></div>
        <div id="signUp" class="signUp"><div class="btn btn-success" style="text-align:center;font-weight:500;margin-left:43%;font-size:14px;position:relative;top:5px;">Sign Up Free Today</div></div>
    </div>-->
    
</div>';
    
} //end of navbar

function footer() {

echo'
 <!-- footer -->
      <div id="footer">
  <div class="footercontainer clearfix">

      <dl class="footer_nav" style="margin-left:150px;">
        <dt>PhotoRankr</dt>
        <dd><a href="https:/photorankr.com/about.php">About Us</a></dd>
        <dd><a href="https:/photorankr.com/blog.php">Blog</a></dd>
        <dd><a href="https:/photorankr.com/help.php">Help & FAQ\'s</a></dd>
        <dd><a href="https:/photorankr.com/contact.php">Contact Us</a></dd>
      </dl>

      <dl class="footer_nav">
        <dt>Our Blog</dt>
        <dd><a href="http://photorankr.com/blog.php">The Blog</a></dd>';
        //last blog posts
        $blogposts = mysql_query("SELECT * FROM entries ORDER BY id DESC LIMIT 5");
        for($iii=0; $iii<4; $iii++) {   
            $id = mysql_result($blogposts,$iii,'id');
            $title = mysql_result($blogposts,$iii,'title');
            $title = (strlen($title) > 22) ? substr($title,0,20). "&#8230;" : $title;
            echo'
            <dd><a href="http://photorankr.com/post.php?a=',$id,'">',$title,'</a></dd>';
        }
        echo'
      </dl>

      <dl class="footer_nav">
        <dt>Featured Galleries</dt>';
        //last featured galleries
        $galleries = mysql_query("SELECT * FROM featuredgallery ORDER BY id DESC LIMIT 4");
        for($ii=0; $ii<4; $ii++) {   
            $id = mysql_result($galleries,$ii,'id');
            $name = mysql_result($galleries,$ii,'name');
            $name = (strlen($name) > 22) ? substr($name,0,20). "&#8230;" : $name;
            echo'
            <dd><a href="http://photorankr.com/viewgallery.php?g=',$id,'">',$name,'</a></dd>';
        }
        echo'
      </dl>

      <dl class="footer_nav">
        <dt>Contact</dt>
        <dd><a href="http://photorankr.com/contact.php">Contact & Support</a></dd>
        <dd><a href="mailto:advertise@photorankr.com?subject=Advertising Inquiry">Advertise</a></dd>
        <dd><a href="http://photorankr.com/legal.php">Legal</a></dd>
      </dl>

      <!--<dl class="footer_nav">
        <dt>More</dt>
        <dd> Careers </dd>
        <dd> Invest </dd>
        <dd> Press </dd>
      </dl>-->

      <br /><br /><br /><br /><br /><br /><br />
        <p class="footer-divider"></p>
      <br />


    <p class="right">&copy; 2012 <span>PhotoRankr</span> Inc. All rights reserved.</p>
    <a class="left" href="https://photorankr.com/">
      <span id="footer_image"><img src="graphics/aperature_dark.png" style="width:30px;" /></span>
    </a>
    <ul id="legal">
        <li><a style="color:#777;" href="https://photorankr.com/terms.php">Terms of Service</a></li>
        <li><a style="color:#777;" href="https://photorankr.com/privacy.php">Privacy</a></li>
        <li><a style="color:#777;" href="https://photorankr.com/security.php">Security</a></li>
    </ul>

  </div><!-- /.container -->

</div><!-- /.#footer -->';

}

function make_url($comment){
    
    $text = $comment;
    
    $pattern = "@\b(https?://)?(([0-9a-zA-Z_!~*'().&=+$%-]+:)?[0-9a-zA-Z_!~*'().&=+$%-]+\@)?(([0-9]{1,3}\.){3}[0-9]{1,3}|([0-9a-zA-Z_!~*'()-]+\.)*([0-9a-zA-Z][0-9a-zA-Z-]{0,61})?[0-9a-zA-Z]\.[a-zA-Z]{2,6})(:[0-9]{1,4})?((/[0-9a-zA-Z_!~*'().;?:\@&=+$,%#-]+)*/?)@";

$text = preg_replace($pattern, '<a rel="nofollow" href="\0">\0</a>', $text);

return $text;
    
}

//this function works for trending to find the spot of the pic
function findPicTrend($image) {
	//find out what it was ranked (what number it corresponds to)
	$trendquery = "SELECT * FROM photos ORDER BY score DESC";
	$trendresult = mysql_query($trendquery);
	$totalnumber = mysql_num_rows($trendresult);
	for($iii = 0; $iii < $totalnumber; $iii++)
	{
		$comparison = mysql_result($trendresult, $iii, "source");
		//if the current image matches the function image
		//return the index of where the image is	
		if($comparison == $image)
		{
			return $iii;
		}
	}
}

//this function works for newest to find the spot of the pic
function findPicNew($image) {
	//find out what it was ranked (what number it corresponds to)
	$newquery = "SELECT * FROM photos ORDER BY id DESC";
	$newresult = mysql_query($newquery);
	$totalnumber = mysql_num_rows($newresult);
	for($iii = 0; $iii < $totalnumber; $iii++)
	{
		$comparison = mysql_result($newresult, $iii, "source");
		//if the current image matches the function image
		//return the index of where the image is	
		if($comparison == $image)
		{
			return $iii;
		}
	}
}

//this function works for newest to find the spot of the pic
function findPicTop($image) {
	//find out what it was ranked (what number it corresponds to)
	$newquery = "SELECT * FROM photos ORDER BY points DESC";
	$newresult = mysql_query($newquery);
	$totalnumber = mysql_num_rows($newresult);
	for($iii = 0; $iii < $totalnumber; $iii++)
	{
		$comparison = mysql_result($newresult, $iii, "source");
		//if the current image matches the function image
		//return the index of where the image is	
		if($comparison == $image)
		{
			return $iii;
		}
	}
}

//this function works for myprofile to find the spot of the pic
function findPicMe($email, $image) {
	//find out what it was ranked (what number it corresponds to)
	$newquery = "SELECT * FROM photos WHERE emailaddress = '$email' ORDER BY id DESC";
	$newresult = mysql_query($newquery);
	$totalnumber = mysql_num_rows($newresult);
	for($iii = 0; $iii < $totalnumber; $iii++)
	{
		$comparison = mysql_result($newresult, $iii, "source");
		//if the current image matches the function image
		//return the index of where the image is	
		if($comparison == $image)
		{
			return $iii;
		}
	}
}

//this function works for viewprofile to find the spot of the pic
function findPicView($email, $image) {
	//find out what it was ranked (what number it corresponds to)
	$newquery = "SELECT * FROM photos WHERE emailaddress = '$email' ORDER BY id DESC";
	$newresult = mysql_query($newquery);
	$totalnumber = mysql_num_rows($newresult);
	for($iii = 0; $iii < $totalnumber; $iii++)
	{
		$comparison = mysql_result($newresult, $iii, "source");
		//if the current image matches the function image
		//return the index of where the image is	
		if($comparison == $image)
		{
			return $iii;
		}
	}
}

//this function works for photostream to find the spot of the pic
function findPicStream($followlist, $image) {
	//find out what it was ranked (what number it corresponds to)
	$newquery = "SELECT * FROM photos WHERE emailaddress IN ($followlist) ORDER BY id DESC";
	$newresult = mysql_query($newquery);
	$totalnumber = mysql_num_rows($newresult);
	for($iii = 0; $iii < $totalnumber; $iii++)
	{
		$comparison = mysql_result($newresult, $iii, "source");
		//if the current image matches the function image
		//return the index of where the image is	
		if($comparison == $image)
		{
			return $iii;
		}
	}
}

//PHOTO UPLOAD FUNCTIONS
function createThumbnail($filename) {  
		ini_set('max_input_time', 300);
      
        require 'config.php';  
        
        $filename=str_replace("JPG","jpg",$filename);

        if(preg_match('/[.](jpg)$/', $filename)) {  
            $im = imagecreatefromjpeg($path_to_image_directory . $filename);  
        } else if (preg_match('/[.](gif)$/', $filename)) {  
            $im = imagecreatefromgif($path_to_image_directory . $filename);  
        } 
        else if (preg_match('/[.](jpeg)$/', $filename)) {  
            $im = imagecreatefromjpeg( $filename);  
        }
        else if (preg_match('/[.](png)$/', $filename)) {  
            $im = imagecreatefrompng($path_to_image_directory . $filename);  
        } 
      
      
        $ox = imagesx($im);  
        $oy = imagesy($im);  
      
        $nx = $final_width_of_image;  
        $ny = $final_height_of_image;  
      
        $nm = imagecreatetruecolor($nx, $ny);  
      
        imagecopyresampled($nm, $im, 0,0,0,0,$nx,$ny,$ox,$oy);  
      
        if(!file_exists($path_to_thumbs_directory)) {  
          if(!mkdir($path_to_thumbs_directory)) {  
               die("There was a problem. Please try again!");  
          }  
           }  
      
        imagejpeg($nm, $path_to_thumbs_directory . $filename);  
        //$tn = '<img src="' . $path_to_thumbs_directory . $filename . '" alt="image" />';  
        //$tn .= '<br />Upload Successful!';  
        //echo $tn;  
chmod ($path_to_thumbs_directory . $filename, 0644);
}  

function createprofthumbnail($filename) {  
ini_set('max_input_time', 300);
      
        require 'config.php';  
      
        if(preg_match('/[.](jpg)$/', $filename)) {  
            $im = imagecreatefromjpeg( $filename);  
        } else if (preg_match('/[.](gif)$/', $filename)) {  
            $im = imagecreatefromgif( $filename);  
        } else if (preg_match('/[.](jpeg)$/', $filename)) {  
            $im = imagecreatefromjpeg( $filename);  
        }
        else if (preg_match('/[.](png)$/', $filename)) {  
            $im = imagecreatefrompng($filename);  
        }  
      
        $ox = imagesx($im);  
        $oy = imagesy($im);  
        
        $ny = 400;  
        $nx = 400;
      
        $nm = imagecreatetruecolor($nx, $ny);  

        imagecopyresized($nm, $im, 0,0,0,0,$nx,$ny,$ox,$oy);  
      
        if(!file_exists($path_to_profpic_directory)) {  
          if(!mkdir($path_to_profpic_directory)) {  
               die("There was a problem. Please try again!");  
          }  
        }  
      
        imagejpeg($nm, $filename);  
		chmod ($filename, 0644);
 
}  

function createprofthumbdim($filename) {  
ini_set('max_input_time', 300);
      
        require 'config.php';  
      
        if(preg_match('/[.](jpg)$/', $filename)) {  
            $im = imagecreatefromjpeg( $filename);  
        } else if (preg_match('/[.](gif)$/', $filename)) {  
            $im = imagecreatefromgif( $filename);  
        } else if (preg_match('/[.](jpeg)$/', $filename)) {  
            $im = imagecreatefromjpeg( $filename);  
        }
        else if (preg_match('/[.](png)$/', $filename)) {  
            $im = imagecreatefrompng($filename);  
        }  
      
        $ox = imagesx($im);  
        $oy = imagesy($im);  
        
        $ny = 400;  
		$nx = $ny * $ox / $oy;
      
        $nm = imagecreatetruecolor($nx, $ny);  

        imagecopyresized($nm, $im, 0,0,0,0,$nx,$ny,$ox,$oy);  
      
        if(!file_exists($path_to_profpicthumbs_directory)) {  
          if(!mkdir($path_to_profpicthumbs_directory)) {  
               die("There was a problem. Please try again!");  
          }  
        }  
        
      
      	$filename = str_replace("profilepics", "profilepics/thumbs", $filename);
        imagejpeg($nm, $filename);  
		chmod ($filename, 0644);

}  

//Create Medium Thumbnail
function createMedThumbnail($filename) {  
ini_set('max_input_time', 300);
      
        require 'config.php';  
        
        $filename=str_replace("JPG","jpg",$filename);

        if(preg_match('/[.](jpg)$/', $filename)) {  
            $im = imagecreatefromjpeg($path_to_image_directory . $filename);  
        } else if (preg_match('/[.](gif)$/', $filename)) {  
            $im = imagecreatefromgif($path_to_image_directory . $filename);  
        }
        else if (preg_match('/[.](jpeg)$/', $filename)) {  
            $im = imagecreatefromjpeg($path_to_image_directory . $filename);  
        }
         else if (preg_match('/[.](png)$/', $filename)) {  
            $im = imagecreatefrompng($path_to_image_directory . $filename);  
        } 
      
      
        $ox = imagesx($im);  
        $oy = imagesy($im);  
      
        $nx = $final_width_of_medimage;  
        $ny = $final_height_of_medimage;  
      
        $nm = imagecreatetruecolor($nx, $ny);  
      
        imagecopyresampled($nm, $im, 0,0,0,0,$nx,$ny,$ox,$oy);  
      
        if(!file_exists($path_to_medthumbs_directory)) {  
          if(!mkdir($path_to_medthumbs_directory)) {  
               die("There was a problem. Please try again!");  
          }  
           }  
      
        imagejpeg($nm, $path_to_medthumbs_directory . $filename);  
        //$tn = '<img src="' . $path_to_medthumbs_directory . $filename . '" alt="image" />';  
        //$tn .= '<br />Upload Successful!';  
        //echo $tn;  
chmod ($path_to_medthumbs_directory . $filename, 0644);
}    

function watermarkpic($filename) {
	
	ini_set('max_input_time', 300);

	require 'config.php';  

	$watermark = imagecreatefrompng('watermarknew.png');
	$watermarkwidth = imagesx($watermark);
	$watermarkheight = imagesy($watermark);

        $filename=str_replace("JPG","jpg",$filename);

	if(preg_match('/[.](jpg)$/', $filename)) {  
            $originalimage = imagecreatefromjpeg($path_to_image_directory . $filename);  
        } else if (preg_match('/[.](gif)$/', $filename)) {  
            $originalimage = imagecreatefromgif($path_to_image_directory . $filename);  
        }
        else if (preg_match('/[.](jpeg)$/', $filename)) {  
            $originalimage = imagecreatefromjpeg($path_to_image_directory . $filename);  
        }
         else if (preg_match('/[.](png)$/', $filename)) {  
            $originalimage = imagecreatefrompng($path_to_image_directory . $filename);  
        }  

	$originalwidth = imagesx($originalimage);
	$originalheight = imagesy($originalimage);
	
	$maxsize = 1200;
	$imgratio = $originalwidth / $originalheight;

	if($imgratio > 1) {
		$finalwidth = $maxsize;
		$finalheight = $maxsize / $imgratio;
	}
	else {
		$finalheight = $maxsize;
		$finalwidth = $maxsize * $imgratio;
	}	

	$finalimage = imagecreatetruecolor($finalwidth,$finalheight);
	
	imagecopyresampled($finalimage, $originalimage, 0,0,0,0,$finalwidth,$finalheight,$originalwidth,$originalheight);

	imagecopy($finalimage, $watermark, 0, 0, 0, 0, $watermarkwidth, $watermarkheight);

	//now move the file where it needs to go
	if(!file_exists($path_to_medimage_directory)) {  
        	if(!mkdir($path_to_medimage_directory)) {  
               		die("There was a problem. Please try again!");  
          	}  
         } 
	
	 imagejpeg($finalimage, $path_to_medimage_directory . $filename, 100); 	
	
	chmod ($path_to_medimage_directory . $filename, 0644);
}

function watermarkpicnew($filename) {
	
	ini_set('max_input_time', 300);

	require 'config.php';  

	$watermark = imagecreatefrompng('graphics/watermark.png');
	$watermarkwidth = imagesx($watermark);
	$watermarkheight = imagesy($watermark);

        $filename=str_replace("JPG","jpg",$filename);

	if(preg_match('/[.](jpg)$/', $filename)) {  
            $originalimage = imagecreatefromjpeg($path_to_image_directory . $filename);  
        } else if (preg_match('/[.](gif)$/', $filename)) {  
            $originalimage = imagecreatefromgif($path_to_image_directory . $filename);  
        }
        else if (preg_match('/[.](jpeg)$/', $filename)) {  
            $originalimage = imagecreatefromjpeg($path_to_image_directory . $filename);  
        }
         else if (preg_match('/[.](png)$/', $filename)) {  
            $originalimage = imagecreatefrompng($path_to_image_directory . $filename);  
        }  

	$originalwidth = imagesx($originalimage);
	$originalheight = imagesy($originalimage);
	
	$maxsize = 1200;
	$imgratio = $originalwidth / $originalheight;

	if($imgratio > 1) {
		$finalwidth = $maxsize;
		$finalheight = $maxsize / $imgratio;
	}
	else {
		$finalheight = $maxsize;
		$finalwidth = $maxsize * $imgratio;
	}	

	$finalimage = imagecreatetruecolor($finalwidth,$finalheight);
	
	imagecopyresampled($finalimage, $originalimage, 0,0,0,0,$finalwidth,$finalheight,$originalwidth,$originalheight);

	imagecopy($finalimage, $watermark, 0, 0, 0, 0, $watermarkwidth, $watermarkheight);

	//now move the file where it needs to go
	if(!file_exists($path_to_medimage_directory)) {  
        	if(!mkdir($path_to_medimage_directory)) {  
               		die("There was a problem. Please try again!");  
          	}  
         } 
	
	 imagejpeg($finalimage, $path_to_medimage_directory . $filename, 100); 	
	
	chmod ($path_to_medimage_directory . $filename, 0644);
}

function watermark_text($filename,$sessionname) {
    
    $lastid = mysql_query("SELECT id FROM photos ORDER BY id DESC LIMIT 0,1"); 
    $id = mysql_result($lastid,0,'id') + 1;
                
	$font_path = "graphics/HelveticaNeue.ttf"; // Font file
	$font_size = 20; // in pixels
	$water_mark_text_2 = "Image ID: " . $id ."


By 
".$sessionname; // Watermark Text
		
	ini_set('max_input_time', 300);
	require 'config.php';  

	$watermark = imagecreatefrompng('graphics/watermarksmallest.png');
	$watermarkwidth = imagesx($watermark);
	$watermarkheight = imagesy($watermark);

        $filename=str_replace("JPG","jpg",$filename);

	if(preg_match('/[.](jpg)$/', $filename)) {  
            $originalimage = imagecreatefromjpeg($path_to_image_directory . $filename);  
        } else if (preg_match('/[.](gif)$/', $filename)) {  
            $originalimage = imagecreatefromgif($path_to_image_directory . $filename);  
        }
        else if (preg_match('/[.](jpeg)$/', $filename)) {  
            $originalimage = imagecreatefromjpeg($path_to_image_directory . $filename);  
        }
         else if (preg_match('/[.](png)$/', $filename)) {  
            $originalimage = imagecreatefrompng($path_to_image_directory . $filename);  
        }  

	$originalwidth = imagesx($originalimage);
	$originalheight = imagesy($originalimage);
    	
	$maxsize = 1200;
	$imgratio = $originalwidth / $originalheight;

	if($imgratio > 1) {
		$finalwidth = $maxsize;
		$finalheight = $maxsize / $imgratio;
	}
	else {
		$finalheight = $maxsize;
		$finalwidth = $maxsize * $imgratio;
	}	

	$finalimage = imagecreatetruecolor($finalwidth,$finalheight);
	
	imagecopyresampled($finalimage, $originalimage, 0,0,0,0,$finalwidth,$finalheight,$originalwidth,$originalheight);
	

	$gray = imagecolorallocate($finalimage, 255, 255, 255);
	imagettftext($finalimage, $font_size, 0, $finalwidth - 275, $finalheight - 210, $gray, $font_path, $water_mark_text_2);

	imagecopy($finalimage, $watermark, $finalwidth - 300, $finalheight - 265, 0, 0,  $watermarkwidth, $watermarkheight);

	//now move the file where it needs to go
	if(!file_exists($path_to_medimage_directory)) {  
        	if(!mkdir($path_to_medimage_directory)) {  
               		die("There was a problem. Please try again!");  
          	}  
         } 
	
	 imagejpeg($finalimage, $path_to_medimage_directory . $filename, 100); 	
	
	chmod ($path_to_medimage_directory . $filename, 0644);
}

?>