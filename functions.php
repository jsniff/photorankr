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
        
        if(mysql_real_escape_string($_POST['password']) == mysql_real_escape_string($info['password'])){
            //then redirect them to the same page as signed in and set loggedin to 1
            session_start();
            $_SESSION[$sessionemail] = 1;
            $_SESSION['loggedin'] = 1;
            $_SESSION['email'] = mysql_real_escape_string($_POST['emailaddress']);
            
        }
        
        //gives error if the password is wrong
        else if (mysql_real_escape_string($_POST['password']) != mysql_real_escape_string($info['password'])) {
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

<script>

jQuery(document).ready(function(){
    jQuery("#hideshow").live("click", function(event) {        
         jQuery("#notifbox").toggle();
    });
    jQuery("#container").live("click", function(event) {        
         jQuery("#notifbox").hide();
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

});

</script>

<?php
	
    echo'
    
    <link href = "css/main3.css" rel="stylesheet" type="text/css"/>';

    //Notifications Slider
    if($_SESSION['loggedin'] == 1) {
        $email = $_SESSION['email'];
        $profilequery = mysql_query("SELECT user_id,profilepic FROM userinfo WHERE emailaddress = '$email'");
        $sessionprofileid = mysql_result($profilequery,0,'user_id');
        $sessionprofilepic = mysql_result($profilequery,0,'profilepic'); 
                                    
        //QUERY FOR NOTIFICATION COUNT
        $currentnots = "SELECT * FROM userinfo WHERE emailaddress = '$email'";
        $currentnotsquery = mysql_query($currentnots);
        $currentnotsresult = mysql_result($currentnotsquery, 0, "notifications");
        
         //NOTIFICATIONS
                                
        $emailquery = mysql_query("SELECT following FROM userinfo WHERE emailaddress ='$email'");
        $followinglist = mysql_result($emailquery, 0, "following");
        $blogquery = mysql_query("SELECT id FROM blog WHERE emailaddress ='$email'");
        $blogidlist = mysql_result($blogquery, 0, "id");
        $notsquery = mysql_query("SELECT * FROM newsfeed WHERE (owner = '$email' AND emailaddress != '$email') OR following = '$email' ORDER BY id DESC");  
        $numnots = mysql_num_rows($notsquery);

        //DECIDE WHICH NOTIFICATIONS TO WHITEN (ONES ALREADY CLICKED ON)
        $unhighlightquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$email'");
        $whitenlist=mysql_result($unhighlightquery, 0, "unhighlight");

        //Query for Cart Section
        $yourcart = mysql_query("SELECT * FROM userscart WHERE emailaddress = '$email' ORDER BY id ASC");
        $numincart = mysql_num_rows($yourcart);

        echo'
             <div id="notifbox">
                                        
                    <div style="font-size:12px;font-family:helvetica neue,helvetica,arial;font-weight:200;">
                            
                                <!--Top Not Bars-->
                                <div id="topNotBar">
                                
                                    <div id="showNots" class="notDiv">
                                        <div class="intNotifBar">',$currentnotsresult,'</div>
                                        <div class="arrow-down"></div>
                                        <div id="notifText">Notifications</div>
                                    </div>
                                    
                                    <div id="showCart" class="notDiv">
                                        <div class="intNotifBar green">',$numincart,'</div>
                                        <div class="arrow-down-green"></div>
                                        <div id="notifText">Cart</div>
                                    </div>
                                    
                                    <div id="showGroups" class="rightNotDiv">
                                        <div class="intNotifBar">',$currentnotsresult,'</div>
                                        <div class="arrow-down"></div>
                                        <div id="notifText">Groups</div>
                                    </div>
                                    
                                </div>';
                                
                    //Begin Cart Notification View
                    echo'<div id="cartNot">';
                         if($numincart == 0) {
                            echo'<div>You currently have no items in your cart.
                                 <a style="padding:8px;width:300px;" class="btn btn-primary" href="market.php">Visit Market</a>';
                          }
                          else {
                            for($i=0; $i<$numincart; $i++) {
                                $cartsource = mysql_result($yourcart,$i,'source');
                                $cartsource = str_replace('userphotos/','userphotos/medthumbs/',$cartsource);
                                $price = mysql_result($yourcart,$i,'price');
                                $size = mysql_result($yourcart,$i,'size');

                                echo'<img style="width:120px;float:bottom;padding:5px;" src="',$cartsource,'" /><span style="font-size:25px;font-weight:100;">$',$price,' - ',$size,'</span><br />';
                            }
                            echo'<a style="margin-top:10px;padding:8px;width:300px;" class="btn btn-success" href="market.php">Checkout</a>';
                          }
                                 
                                 echo'</div>';                         
                                
         
                //Begin Groups Notification View
                echo'<div id="groupsNot">
                        show some shite here!
                     </div>';

                
                //If notifications > 0
                if($numnots > 0) {
                                
                            echo'<div id="nots">';

                                for($iii=0; $iii <= 20; $iii++) {
                                
                                    $firstname = mysql_result($notsquery,$iii,'firstname');
                                    $lastname = mysql_result($notsquery,$iii,'lastname');
                                    $fullname = $firstname . " " . $lastname;
                                    $fullname = ucwords($fullname);
                                    $fullname = (strlen($fullname) > 16) ? substr($fullname,0,14). "&#8230;" : $fullname;

                                    $type = mysql_result($notsquery,$iii,'type');
                                    $id = mysql_result($notsquery,$iii,'id');
                                    $caption = mysql_result($notsquery,$iii,'caption');
                                    $source = mysql_result($notsquery,$iii,'source');
                                    $time = mysql_result($notsquery,$iii,'time');
                                    $time = converttime($time);
                                    
                                    $commentphotoquery = mysql_query("SELECT source FROM photos WHERE (id = '$source' or source = '$source')");
                                    $commentphoto = mysql_result($commentphotoquery,0,'source');
                                    
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

                                            echo'<a style="text-decoration:none;color:#333;" href="fullsize.php?imageid=',$source,'&id=',$id,'"><div id="',$highlightid,'"><img class="roundednot" style="float:left;padding:5px;" src="http://www.photorankr.com/',$commentphotosource,'" height="60" width="60" />&nbsp;<div id="intbox" style="float:left;margin-top:20px;margin-left:10px;"><img src="../graphics/comment.png" height="15" />&nbsp;&nbsp;&nbsp;<b>',$fullname,'</b> commented on your photo<br />',$time,'</div></div></a>';
                                            
                                        }

                                        elseif($type == "blogcomment") {

                                            $blogcommenterquery = mysql_query("SELECT profilepic,firstname,lastname FROM userinfo WHERE emailaddress = '$blogcommenteremail'");
                                            $blogcommenterpic = mysql_result($blogcommenterquery,0,'profilepic');
                                            $blogcommentername = mysql_result($blogcommenterquery,0,'firstname') ." ". mysql_result($blogcommenterquery,0,'lastname');

                                            echo'<a style="text-decoration:none;color:#333;" href="myprofile.php?view=blog&bi=',$source,'#',$source,'"><div id="',$highlightid,'"><img class="roundednot" style="float:left;padding:5px;" src="',$blogcommenterpic,'" height="60" width="60" />&nbsp;<div id="intbox" style="float:left;margin-top:20px;margin-left:10px;"><b>',$blogcommentername,'</b> commented on your blog post</div></div></a>';
                                            
                                        }

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
                                            

                                            echo'<a style="text-decoration:none;color:#333;" href="myprofile.php?view=viewthread&thread=',$thread,'&id=',$id,'"><div id="',$highlightid,'"><img class="roundednot" style="float:left;padding:5px;" src="http://www.photorankr.com/',$profilepic,'" height="60" width="60" />&nbsp;<div id="intbox" style="float:left;margin-top:20px;margin-left:10px;"><img src="../graphics/contact.png" height="13" />&nbsp;&nbsp;&nbsp;<b>',$fullname,'</b> sent you a message</div></div></a>';

                                        }

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
                                        
                                            echo'<a style="text-decoration:none;color:#333;" href="myprofile.php?view=viewthread&thread=',$thread,'&id=',$id,'"><div id="',$highlightid,'"><img class="roundednot" style="float:left;padding:5px;" src="http://www.photorankr.com/',$profilepic,'" height="60" width="60" />&nbsp;<div id="intbox" style="float:left;margin-top:20px;margin-left:10px;"><img src="../graphics/contact.png" height="13" />&nbsp;&nbsp;&nbsp;<b>',$fullname,'</b> replied to your message</div></div></a>';

                                        }

                                        elseif($type == "fave") {

                                            echo'<a style="text-decoration:none;color:#333;" href="fullsize.php?image=',$source,'&id=',$id,'"><div id="',$highlightid,'"><img  class="roundednot" style="float:left;padding:5px;" src="http://www.photorankr.com/',$newsource,'" height="60" width="60" />&nbsp;<div id="intbox" style="float:left;margin-top:20px;margin-left:10px;"><img src="../graphics/fave.png" height="18" />&nbsp;&nbsp;&nbsp;<b>',$fullname,'</b> favorited your photo<br />',$time,'</div></div></a>';

                                        }
                                        
                                        elseif($type == "exhibitfave") {

                                            echo'<a style="text-decoration:none;color:#333;" href="myprofile.php?view=exhibits&set=',$source,'&id=',$id,'"><div id="',$highlightid,'"><img  class="roundednot" style="float:left;padding:5px;" src="http://www.photorankr.com/',$setcover,'" height="60" width="60" />&nbsp;<div id="intbox" style="float:left;margin-top:20px;margin-left:10px;"><img src="graphics/fave.png" height="18" />&nbsp;&nbsp;&nbsp;<b>',$fullname,'</b> favorited your exhibit</div></div></a>';

                                        }

                                        elseif($type == "trending") {

                                            echo'<a style="text-decoration:none;color:#333;" href="fullsize.php?image=',$source,'&id=',$id,'"><div id="',$highlightid,'"><img class="roundednot" style="float:left;padding:5px;" src="http://www.photorankr.com/',$newsource,'" height="60" width="60" />&nbsp;<div id="intbox" style="float:left;margin-top:20px;margin-left:10px;"><img src="../graphics/trending.png" height="18" />&nbsp;&nbsp;&nbsp;Your photo is now trending<br />',$time,'</div></div></a>';

                                        }

                                        elseif($type == "follow") {

                                            $newaccount = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$followeremail'");
                                            $ownerid = mysql_result($newaccount,0,'user_id');
                                            $profilepic = mysql_result($newaccount,0,'profilepic');
                                            if($profilepic == "") {
                                                $profilepic = "profilepics/default_profile.jpg";
                                            }
                                            
                                            echo'<a style="text-decoration:none;color:#333;" href="viewprofile.php?u=',$ownerid,'&id=',$id,'"><div id="',$highlightid,'"><img class="roundednot" style="float:left;padding:5px;" src="https://photorankr.com/',$profilepic,'" height="60" width="60" />&nbsp;<div id="intbox" style="float:left;margin-top:20px;margin-left:10px;"><img style="margin-left:-10px;" src="../graphics/follower.png" height="19" />&nbsp;&nbsp;&nbsp;<b>',$fullname,'</b> is now following your photography<br />',$time,'</div></div></a>';

                                        }

                                
                                } //end of for loop
                                echo'</div>';

                                } //numnots > 0

                                
                                elseif($numnots < 1) {

                                    echo'<div style="position:relative;width:400px;height:80px;overflow-y:scroll;top:30px;">
                                    <div style="color:black;font-size:15px;font-family:helvetica neue,helvetica,arial;font-weight:400;text-align:center;">You have no new notifications &#8230;</div>
                                    </div>';
                                
                                }
        
        echo'</div>';
        
    }
    
    echo'</div>'; //end of id="not" view
    
    
    //Main Left Bar Start
    echo'<div id="Main">
    
	<div id="leftBar" style="height:100%;width:70px;">
	<ul>
		<li><img src="graphics/aperture_dark.png" /><img src="graphics/logo_text.png" style="margin-top:-18px;width:60px!important;
    height:12px !important;"/> </li>';
        
        //Profile Pic Tab
        if($_SESSION['loggedin'] == 1) {
            echo'<li>
                    <div style="height:80px;width:72px;overflow:hidden;margin-top:-15px;">
                        <img id="hideshow" style="height:80px;width:72px;" src="../',$sessionprofilepic,'" />
                    </div>
                    <div class="arrow-up"></div>
                    <div id="notifBar"><span id="notifNum" style="color:#fff;text-align:center;font-size:13px;font-weight:500;padding-bottom:5px;">',$currentnotsresult,'</span></div>
                 </li>';
        }
        
        echo'
        <li'; if(strpos($uri,'newsfeed.php')) {echo' id="inset"';} echo'><a href="newsfeed.php"><img src="graphics/news_b.png"/><p> News </p></a></li>

        <li'; if(strpos($uri,'galleries.php') || strpos($uri,'newest.php') || strpos($uri,'trending.php') || strpos($uri,'topranked.php') || strpos($uri,'discover.php') || strpos($uri,'fullsize.php') || strpos($uri,'fullsizeview.php')) {echo' id="inset"';} echo'><a href="galleries.php"><img src="graphics/galleries_b.png"/><p> Gallery </p><div class="arrow-right"></div></li></a>
        
		<li'; if(strpos($uri,'groups.php')) {echo' id="inset"';} echo'><a href="groups.php"><img src="graphics/groups_b.png"/><p> Groups </p></a></li>
        
		<li'; if(strpos($uri,'market.php') || strpos($uri,'cart.php')) {echo' id="inset"';} echo'><a href="market.php"><img src="graphics/market_b.png"/><p> Market </p></a></li>
        
		<li'; if(strpos($uri,'blog.php')) {echo' id="inset"';} echo'><a href="blog.php"><img src="graphics/blog_b.png"/><p> Blog </p></a></li>
	</ul>
</div>

<div class="CNav">
	<ul>';
        
        if(strpos($uri,'galleries.php') || strpos($uri,'newest.php') || strpos($uri,'trending.php') || strpos($uri,'discover.php') || strpos($uri,'topranked.php') || strpos($uri,'fullsize.php') || strpos($uri,'fullsizeview.php')) {
        
		echo'
        <li'; if(strpos($uri,'galleries.php')) {echo' id="topselected"';} echo'><a style="text-decoration:none;color:#fff;" href="galleries.php">Cover </a></li>
		<li'; if(strpos($uri,'newest.php') || $view == 'n') {echo' id="topselected"';} echo'><a style="text-decoration:none;color:#fff;" href="newest.php">Newest</a></li>
		<li'; if(strpos($uri,'trending.php') || $view == 't') {echo' id="topselected"';} echo'><a style="text-decoration:none;color:#fff;" href="trending.php">Trending</a></li>
		<li'; if(strpos($uri,'topranked.php') || $view == 'r') {echo' id="topselected"';} echo'><a style="text-decoration:none;color:#fff;" href="topranked.php">Top Ranked</a></li>
		<li'; if(strpos($uri,'discover.php')) {echo' id="topselected"';} echo'><a style="text-decoration:none;color:#fff;" href="discover.php">Discover</a></li>
		<li>';
        }
        
        elseif(strpos($uri,'market.php') || strpos($uri,'cart.php')) {
		echo'
        <li'; if(strpos($uri,'market.php')) {echo' id="topselected"';} echo'><a style="text-decoration:none;color:#fff;" href="market.php">Home</a></li>
		<li'; if(strpos($uri,'search.php')) {echo' id="topselected"';} echo'><a style="text-decoration:none;color:#fff;" href="search.php">Search</a></li>
		<li'; if(strpos($uri,'cart.php')) {echo' id="topselected"';} echo'><a style="text-decoration:none;color:#fff;" href="cart.php">My Cart</a></li>
		<li>';
        }
        
        elseif(strpos($uri,'groups.php')) {
		echo'
        <li'; if($uri == '/groups.php') {echo' id="topselected"';} echo'><a style="text-decoration:none;color:#fff;" href="groups.php">Groups Home</a></li>
		<li'; if(strpos($uri,'mygroups')) {echo' id="topselected"';} echo'><a style="text-decoration:none;color:#fff;" href="groups.php?view=mygroups">My Groups</a></li>';
        }
        
        elseif(strpos($uri,'viewprofile.php')) {
		echo'
        <li'; if($uri == '/groups.php') {echo' id="topselected"';} echo'><a style="text-decoration:none;color:#fff;" href="groups.php">My Portfolio</a></li>
		<li'; if(strpos($uri,'mygroups')) {echo' id="topselected"';} echo'><a style="text-decoration:none;color:#fff;" href="groups.php?view=mygroups">My Network</a></li>
        <li'; if(strpos($uri,'mygroups')) {echo' id="topselected"';} echo'><a style="text-decoration:none;color:#fff;" href="groups.php?view=mygroups">My Blog</a></li>
        <li'; if(strpos($uri,'mygroups')) {echo' id="topselected"';} echo'><a style="text-decoration:none;color:#fff;" href="groups.php?view=mygroups">Store</a></li>
        <li'; if(strpos($uri,'mygroups')) {echo' id="topselected"';} echo'><a style="text-decoration:none;color:#fff;" href="groups.php?view=mygroups">Settings</a></li>';
        }
        
        elseif(strpos($uri,'newsfeed_matthew.php')) {
		echo'
        <li'; if($uri == '/newsfeed.php') {echo' id="topselected"';} echo'><a style="text-decoration:none;color:#fff;" href="newsfeed.php">All News</a></li>
		<li'; if(strpos($uri,'uploads')) {echo' id="topselected"';} echo'><a style="text-decoration:none;color:#fff;" href="newsfeed.php?view=uploads">Uploads</a></li>
		<li'; if(strpos($uri,'favorites')) {echo' id="topselected"';} echo'><a style="text-decoration:none;color:#fff;" href="newsfeed.php?view=favorites">Favorites</a></li>
		<li'; if(strpos($uri,'comments')) {echo' id="topselected"';} echo'><a style="text-decoration:none;color:#fff;" href="newsfeed.php?view=comments">Comments</a></li>
		<li>';
        }

        
            echo'
            <form >
                <input type="text" onkeyup="showResult(this.value)" />
                <img src="graphics/glass.png" width="20px"/>
                <div id="livesearch"></div>
            </form>	
        </li>
				
	</ul>
</div>';
    
} //end of navbar


function navbar2() {

$uri = $_SERVER['REQUEST_URI'];

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

<?php
	
echo'
    
<link href = "css/main4.css" rel="stylesheet" type="text/css"/>

<div class="CNav">
	<ul>
        <li style="margin-top:19px;"><img style="width:42px;margin-left:10px;" src="../graphics/aperture_dark.png"/></li>';
        
        if(strpos($uri,'galleries.php') || strpos($uri,'fullsize.php') || strpos($uri,'newest.php')) {
            echo'<li class="selected"><a style="text-decoration:none;color:white;" href="galleries.php">Galleries</a></li>';
        }
        else {
            echo'<li><a style="text-decoration:none;color:white;" href="galleries.php">Galleries</a></li>';
        }
        echo'
		<li><a style="text-decoration:none;color:white;" href="news.php">News</a></li>
		<li><a style="text-decoration:none;color:white;" href="groups.php">Groups</a></li>
		<li><a style="text-decoration:none;color:white;" href="market.php">Store</a></li>
		<li><a style="text-decoration:none;color:white;" href="blog.php">Blog</a></li>
		<li style="width:290px;margin-top:-32px;margin-left:-20px;">
            <form>
                <input type="text" onkeyup="showResult(this.value)" />
                <img src="graphics/glass.png" style="width:23px;margin-top:58px;margin-left:3px;" />
                <div id="livesearch"></div>
            </form>	
        </li>';
        if($_SESSION['loggedin'] == 1) {
            $email = $_SESSION['email'];
            $profilequery = mysql_query("SELECT user_id,firstname,lastname,profilepic FROM userinfo WHERE emailaddress = '$email'");
            $profileid = mysql_result($profilequery,0,'user_id');
            $profilepic = mysql_result($profilequery,0,'profilepic'); 
            $profilename = mysql_result($profilequery,0,'firstname') ." ". mysql_result($profilequery,0,'lastname'); 
            echo'<li><a href="profile.php"><img style="height:62px;width:60px;margin-top:8px;margin-left:0px;" src="../',$profilepic,'" /></li>
            <div style="float:left;margin-top:14px;font-size:16px;font-family:helvetica,arial;color:white;text-decoration:none;">',$profilename,'</div>';
        }
        echo'
	</ul>
</div>';
    
} //end of navbar


function footer() {

echo'
<link rel="stylesheet" href="css/all.css" type="text/css"/> 
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

function make_url($comment){
    
    $text = $comment;
    
    $pattern = "@\b(https?://)?(([0-9a-zA-Z_!~*'().&=+$%-]+:)?[0-9a-zA-Z_!~*'().&=+$%-]+\@)?(([0-9]{1,3}\.){3}[0-9]{1,3}|([0-9a-zA-Z_!~*'()-]+\.)*([0-9a-zA-Z][0-9a-zA-Z-]{0,61})?[0-9a-zA-Z]\.[a-zA-Z]{2,6})(:[0-9]{1,4})?((/[0-9a-zA-Z_!~*'().;?:\@&=+$,%#-]+)*/?)@";

$text = preg_replace($pattern, '<a target="_blank" rel="nofollow" href="\0">\0</a>', $text);

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



?>