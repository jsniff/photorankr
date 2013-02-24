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
    
    //Session information
    $viewerquery = mysql_query("SELECT profilepic,user_id,firstname,lastname,reputation FROM userinfo WHERE emailaddress = '$email'");
    $viewerpic = mysql_result($viewerquery,0,'profilepic');
    $sessionfirst = mysql_result($viewerquery,0,'firstname');
    $sessionlast = mysql_result($viewerquery,0,'lastname');
    $sessionid = mysql_result($viewerquery,0,'user_id');
    $sessionrep = mysql_result($viewerquery,0,'reputation');
    
    //Get article
    
    $article = htmlentities($_GET['a']);
    
    $blogquery = mysql_query("SELECT * FROM entries WHERE id = '$article'");
    $title = mysql_result($blogquery,0,'title');
    $contents = mysql_result($blogquery,0,'contents');
    $date = mysql_result($blogquery,0,'date');
    $author = mysql_result($blogquery,0,'author');
    $type = mysql_result($blogquery,0,'type');
    $user_id = mysql_result($blogquery,0,'user_id');
    
    $quote1 = mysql_result($blogquery,0,'quote1');
    $quote1dist = mysql_result($blogquery,0,'quote1dist');
    $quote2 = mysql_result($blogquery,0,'quote2');
    $quote2dist = mysql_result($blogquery,0,'quote2dist');
    $quote3 = mysql_result($blogquery,0,'quote3');
    $quote3dist = mysql_result($blogquery,0,'quote3dist');
    
    $getprofilepic = mysql_query("SELECT user_id,profilepic FROM userinfo WHERE user_id = '$user_id'");
    $profilepic = mysql_result($getprofilepic,0,'profilepic');
    $userid = mysql_result($getprofilepic,0,'user_id');

    //Views counter
    $viewsquery = mysql_query("UPDATE entries SET views = (views + 1) WHERE id = '$article'");
    
?>


<!DOCTYPE HTML>
<head>

	<meta charset="UTF-8">
	<title> <?php echo $title ." by ". $author; ?></title>
    <meta name="Keywords" content="<?php echo $title;?>,photorankr blog, photography blog, blog, photos, sharing photos, photo sharing, photography, stock photography, stock, photography club, sell photos, sell photography, where to sell my photography, good sites for selling photography, making money from photography, making money off photography, social networking, social network, social networks, where to sell my photos, good sites for selling photos, good site to sell photos, making money from photos, social stock, photo licensing, royalty free photos, crowdsource, crowdsourcing photos, crowdsourced photos">
    <meta name="Description" content="<?php echo $title ." by: ". $author; ?>">

	<link rel="stylesheet" type="text/css" href="css/style.css"/>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>
	<link rel="stylesheet" type="text/css" href="css/960grid.css"/>
	<link rel="stylesheet" type="text/css" href="css/reset.css"/>  
    <link rel="stylesheet" type="text/css" href="css/main3.css"/>            
    <link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>
        
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script type="text/javascript">var switchTo5x=true;</script>
    <script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
    <script type="text/javascript" src="http://s.sharethis.com/loader.js"></script>

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
    
    <script type="text/javascript" >

$(function() {
$(".submit").click(function() 
{
var name = '<?php echo $sessionfirst ." ". $sessionlast; ?>';
var email = '<?php echo $email; ?>';
var article = '<?php echo $article; ?>';
var userpic = '<?php echo $viewerpic; ?>';
var viewerid = '<?php echo $sessionid; ?>';
var viewerrep = '<?php echo $sessionrep; ?>';
var comment = $("#comment").val();
var dataString = 'name='+ name + '&email=' + email + '&comment=' + comment + '&userpic=' + userpic + '&article=' + article + '&viewerid=' + viewerid + '&viewerrep=' + viewerrep;
if(email=='' || comment=='')
{
alert('Please Give Valid Details');
}
else
{
$("#flash").show();
$("#flash").fadeIn(400).html();
$.ajax({
type: "POST",
url: "articlecommentajax.php",
data: dataString,
cache: false,
success: function(html){
$("ol#update").append(html);
$("ol#update li:last").fadeIn("slow");
$("#flash").hide();
}
});
}return false;
}); });

</script>


</head>
<body id="body">
<?php include_once("analyticstracking.php") ?>

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

    <div class="container_24">
    
    <div class="grid_4" style="margin-top:40px;">
    
    <?php
        
        if($quote1) {
            echo'
            <blockquote class="pull-right" style="margin-top:',$quote1dist,'px;">
                <p>',$quote1,'</p>
                <small>',$author,'</small>
            </blockquote>';
        }
        
        if($quote2) {
            echo'
            <blockquote class="pull-right" style="margin-top:',$quote2dist,'px;">
                <p>',$quote2,'</p>
                <small>',$author,'</small>
            </blockquote>';
        }
        
        if($quote3) {
            echo'
            <blockquote class="pull-right" style="margin-top:',$quote3dist,'px;">
                <p>',$quote3,'</p>
                <small>',$author,'</small>
            </blockquote>';
        }
        
    ?>

    </div>

	 <!--the bid column right-->
	 <div class="grid_20 pull_1">
	 <div class="grid_20 post" style="height:100%;padding:20px 50px;"><!--post-->
			<article>
            
				<div class="pic_container">
					<a href="viewprofile.php?u=<?php echo $userid; ?>"><img style="width:80px;" src="<?php echo $profilepic; ?>" class="post_pic"></a>
				</div>
                
                <header>
                <br />
                Author:
                <br />
				<h4><a style="text-decoration:none;color:#333;" href="viewprofile.php?u=<?php echo $userid; ?>"><?php echo $author; ?></a></h4>
			<div class="line"></div>	
				<h3> <?php echo $date; ?></h3>
			</header>
            
				<section style="width:500px;margin-left:200px;margin-top:-110px;">
                    <span style="line-height:25px;font-size:24px;font-wieght:200;font-family:"helvetica neue",helvetica,arial;"><?php echo $title; ?></span>
                    <br />
                    <br />
					<?php echo $contents; ?>
				</section>
			</article>
		</div>
        
    </div>	
    
<?php
    
    //AJAX COMMENTING FORM
    if($email) {
    
    echo'
    <div style="width:600px;float:right;"> 
        <form action="#" method="post" style="margin-top:10px;padding-bottom:25px;">        
        <img style="margin-right:10px;padding:10px;" src="',$viewerpic,'" height="45" width="45" />
        <textarea id="comment" style="width:470px;height:80px;position:relative;left:55px;top:-55px;" type="text" placeholder="Leave a comment&#8230;"></textarea>
        <br /><br />
        <input style="margin-top:-91px;margin-left:80px;width:475px;padding:10px;font-size:16px;font-weight:200;font-family:\'helvetica neue\',helvetica,arial;" type="submit" class="submit btn btn-success" value="Post Comment"/>
        </form>
    </div>';
    
    }
    
?>


    <!--AJAX COMMENTS-->
    <div class="float:right;"> 
        <ol id="update" class="timeline">
        </ol>
    </div>
    
    <!--PREVIOUS COMMENTS-->
    <?php
        
        $commentquery = mysql_query("SELECT * FROM articlecomments WHERE article = $article ORDER BY id DESC");
        $numcomments = mysql_num_rows($commentquery);
        
        for($jjj=0; $jjj<$numcomments; $jjj++) {
        
            $prevcomment = mysql_result($commentquery,$jjj,'comment');
            $prevcommenter = mysql_result($commentquery,$jjj,'commenter');
            $prevtime = mysql_result($commentquery,$jjj,'time');
            
            //extra commenter information
            $extrainfo = mysql_query("SELECT user_id,profilepic,firstname,lastname,reputation FROM userinfo WHERE emailaddress = '$prevcommenter'");
            $prevname = mysql_result($extrainfo,0,'firstname') ." ". mysql_result($extrainfo,0,'lastname');
            $prevcommenterrep = mysql_result($extrainfo,0,'reputation');
            $prevcommenterrep = number_format($prevcommenterrep,2);
            $prevcommenterpic = mysql_result($extrainfo,0,'profilepic');
            $prevcommenterid = mysql_result($extrainfo,0,'user_id');
            
            echo'
            <li class="grid_16" style="float:right;width:580px;margin-top:20px;">
            <a href="viewprofile.php?u=',$prevcommenterid,'">
            <div style="float:left;"><img class="roundedall" src="',$prevcommenterpic,'" alt="',$name,'" height="40" width="35"/>
            </a>
        </div>
        
        <div style="float:left;padding-left:6px;width:510px;">
            <div style="float:left;color:#3e608c;font-size:14px;font-family:helvetica;font-weight:500;border-bottom: 1px solid #ccc;width:510px;">
            <div style="float:left;">
            <a name="',$prevcommenterid,'" href="viewprofile.php?u=',$prevcommenterid,'">',$prevname,'</a> &nbsp;<span style="font-size:16px;font-weight:100;color:black;margin-top:2">|</span>&nbsp;<span style="color:#333;font-size:12px;">Rep: ',$prevcommenterrep,'</span>
                </div>
                &nbsp;&nbsp;&nbsp;
                   
                <div class="progress progress-success" style="float:left;width:110px;height:7px;opacity:.8;margin:7px;">
                
                <div class="bar" style="width:',$prevcommenterrep,'%;">
            </div>
            </div>
        </div>
                
                <br />
                
                <div style="float:left;font-size:11px;color:#777;font-weight:400;padding:2px;">',converttime($prevtime),'</div>
                
                <div style="float:left;width:470px;padding:10px;font-size:13px;font-family:helvetica;font-weight:300;color:#555;">',$prevcomment,'</div>
                
            </div>
            </li>';
        
        }
        
    ?>
    
    </div><!--end of container-->
    <br /><br /><br /><br />
    
    <?php footer(); ?>
    
    <!--<script type="text/javascript">stLight.options({publisher: "2c31e7c5-bd4b-4757-8ea0-da2dc02c3404"});</script>
<script>
var options={ "publisher": "2c31e7c5-bd4b-4757-8ea0-da2dc02c3404", "position": "left", "ad": { "visible": false, "openDelay": 5, "closeDelay": 0}, "chicklets": { "items": ["facebook", "twitter", "pinterest", "email", "sharethis"]}};
var st_hover_widget = new sharethis.widgets.hoverbuttons(options);
    </script>-->
    
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script src="../js/bootstrap-dropdown.js"></script>
    <script type="text/javascript">  
        $(document).ready(function () {  
            $('.dropdown-toggle').dropdown();  
        });  
</script>

</body>
</html>
