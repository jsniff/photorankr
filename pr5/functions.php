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
    jQuery("#signUp").live("click", function(event) {        
         jQuery("#hiddenSignUp").toggle();
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
                
    }
    
    
    
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

        <li'; if(strpos($uri,'galleries.php') || strpos($uri,'newest.php') || strpos($uri,'trending.php') || strpos($uri,'topranked.php') || strpos($uri,'discover.php') || strpos($uri,'fullsize.php') || strpos($uri,'fullsizeview.php')) {echo' id="inset"';} echo'><a href="galleries.php"><img src="graphics/galleries_b.png"/><p> Galleries </p><div class="arrow-right"></div></li></a>
        
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
        
        elseif(strpos($uri,'profile.php')) {
		echo'
        <li'; if($uri == '/groups.php') {echo' id="topselected"';} echo'><a style="text-decoration:none;color:#fff;" href="groups.php">My Portfolio</a></li>
		<li'; if(strpos($uri,'mygroups')) {echo' id="topselected"';} echo'><a style="text-decoration:none;color:#fff;" href="groups.php?view=mygroups">My Network</a></li>
        <li'; if(strpos($uri,'mygroups')) {echo' id="topselected"';} echo'><a style="text-decoration:none;color:#fff;" href="groups.php?view=mygroups">My Blog</a></li>
        <li'; if(strpos($uri,'mygroups')) {echo' id="topselected"';} echo'><a style="text-decoration:none;color:#fff;" href="groups.php?view=mygroups">Store</a></li>
        <li'; if(strpos($uri,'mygroups')) {echo' id="topselected"';} echo'><a style="text-decoration:none;color:#fff;" href="groups.php?view=mygroups">Settings</a></li>
        <li>Status</li>';
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
            
            <li style="float:right;height:19px;">
            <!-- creation menu -->
				<div id="add">
					<a id="addbutton" href="/organizer#upload"><div id="plus"></div>UPLOAD</a>
                </div>
			</li>	
            
            <li style="width:120px;height:19px;color:#333;">
            <form action="search.php" method="get">
                <input type="text" onkeyup="showResult(this.value)" name="searchterm" />
                <img id="search" src="graphics/glass.png" width="20px"/>
                <div id="livesearch"></div>
            </form>	
            </li>

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

      <dl class="footer_nav">
        <dt>PhotoRankr</dt>
        <dd><a href="https:/photorankr.com/about.php">About Us</a></dd>
        <dd><a href="https:/photorankr.com/blog.php">Blog</a></dd>
        <dd><a href="https:/photorankr.com/help.php">Help & FAQ\'s</a></dd>
        <dd><a href="https:/photorankr.com/contact.php">Contact Us</a></dd>
      </dl>

      <dl class="footer_nav">
        <dt>Our Blog</dt>
        <dd><a href="http://mac.github.com/">The Blog</a></dd>';
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
        <dd><a href="http://pages.github.com/">Legal</a></dd>
      </dl>

      <dl class="footer_nav">
        <dt>More</dt>
        <dd><a href="http://training.github.com/">Training</a></dd>
        <dd><a href="https://github.com/edu">Students &amp; teachers</a></dd>
        <dd><a href="http://shop.github.com">The Shop</a></dd>
      </dl>

      <br /><br /><br /><br /><br /><br /><br />
        <p class="footer-divider"></p>
      <br />


    <p class="right">&copy; 2012 <span>PhotoRankr</span> Inc. All rights reserved.</p>
    <a class="left" href="https://photorankr.com/">
      <span id="footer_image"><img src="graphics/aperture_dark.png" style="width:30px;" /></span>
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