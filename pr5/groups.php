<?php

//connect to the database
require "db_connection.php";
require "functions.php";

//start the session
session_start();

    // if login form has been submitted
    if(htmlentities($_GET['action']) == "login") { 
        login();
    }
    elseif(htmlentities($_GET['action']) == "logout") { 
        logout();
    }

    $email = $_SESSION['email'];
    
    $findreputationme = mysql_query("SELECT user_id,reputation,profilepic,firstname,lastname FROM userinfo WHERE emailaddress = '$email'");
    $reputationme = mysql_result($findreputationme,0,'reputation');
    $sessionpic = mysql_result($findreputationme,0,'profilepic');
    $sessionuserid =  mysql_result($findreputationme,0,'user_id');
    $sessionfirst =  mysql_result($findreputationme,0,'firstname');
    $sessionlast =  mysql_result($findreputationme,0,'lastname');
    $sessionid =  mysql_result($findreputationme,0,'user_id');
    $sessionname = mysql_result($findreputationme,0,'firstname') ." ". mysql_result($findreputationme,0,'lastname');
    $currenttime = time();
        
    //QUERY FOR NOTIFICATIONS
    $sessioninfo = mysql_query("SELECT user_id,profilepic,firstname,lastname FROM userinfo WHERE emailaddress = '$email'");
    $sessionprofilepic = mysql_result($sessioninfo, 0, "profilepic");
    
    //Get Views
    $groupid = htmlentities($_GET['id']);
    $groupview = htmlentities($_GET['gv']);
    $action = htmlentities($_GET['action']);
    
    //Groups Queries
    $groupcheck = mysql_query("SELECT members FROM groups WHERE id = $groupid");
    $currentmembers = mysql_result($groupcheck,0,'members');
    $regex="/$sessionid/";
    $match=preg_match($regex,$currentmembers);
    
    //Get User's groups
    $usergroupsquery = mysql_query("SELECT groups FROM userinfo WHERE user_id = $sessionid");
    $usergroups = mysql_result($usergroupsquery,0,'groups');
    
    //Create New Group
    if($action == 'creategroup') {
    
        $groupname = mysql_real_escape_string(htmlentities($_POST['name']));
        $about = mysql_real_escape_string(htmlentities($_POST['about']));
        $privacy = mysql_real_escape_string(htmlentities($_POST['privacy']));
        
        if($groupname == '') {
            header("Location: groups.php?error=name");
        }
        
        elseif($about == '') {
            header("Location: groups.php?error=about");
        }
        
        else {
        $newgroupquery = mysql_query("INSERT INTO groups (group_owner,name,about,privacy) VALUES ('$email','$groupname','$about','$privacy')");
        }
        
        //Redirect to new group
        $getlastgroup = mysql_query("SELECT id FROM groups WHERE group_owner = '$email' ORDER BY id DESC LIMIT 0,1");
        $lastid = mysql_result($getlastgroup,0,'id');
        header("Location: groups.php?id=$lastid");
    
    }
    
    
    //Join Group
    if($action == 'join') {
        //check if already in group
        if(!$match) {
            $updatemembers = $sessionid . " ";
            $addtogroups = mysql_query("UPDATE groups SET members = concat(members,'$updatemembers') WHERE id = $groupid");
            $updategroup = $group . " ";
            $updateprofilegroups = mysql_query("UPDATE userinfo SET groups = concat(groups,'$updategroup') WHERE user_id = $sessionid");
            $addtonewsfeed = mysql_query("INSERT INTO groupnews (group_id,firstname, lastname, commenter,time,type) VALUES ('$groupid','$sessionfirst', '$sessionlast', '$email','$currenttime','join')") or die();
        }
    }
    
    //Leave Group
    if($action == 'leave') {
        $updategroup = $group . " ";
        $formatteduserid = $sessionid . " ";
        $newmemberslist = str_replace($formatteduserid,"",$currentmembers);
        $updatedgroups = str_replace($updategroup,"",$usergroups);
        $leavegroup = mysql_query("UPDATE groups SET members = '$newmemberslist' WHERE id = $groupid");
        $updateprofilegroups = mysql_query("UPDATE userinfo SET groups = '$updatedgroups' WHERE user_id = $sessionid");
    }
    
    //Groups Queries
    $groupcheck = mysql_query("SELECT members FROM groups WHERE id = $groupid");
    $currentmembers = mysql_result($groupcheck,0,'members');
    $regex="/$sessionid/";
    $match=preg_match($regex,$currentmembers);
    
   //Delete Post
    if($action == 'delete') {
    
        $postid = mysql_real_escape_string($_GET['post']);
        $deletepost = mysql_query("DELETE FROM groupnews WHERE id = '$postid'");
    }
        
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="https://www.w3.org/1999/xhtml">

<head>

 <meta name="Generator" content="EditPlus">
  <meta name="Author" content="PhotoRankr, PhotoRankr.com">
  <meta name="Keywords" content="photos, sharing photos, photo sharing, photography, photography club, sell photos, sell photography, where to sell my photography, good sites for selling photography, making money from photography, making money off photography, social networking, social network, social networks, where to sell my photos, good sites for selling photos, good site to sell photos, making money from photos">
  <meta name="Description" content="A gallery of the newest photography, photographers, and exhibits on PhotoRankr.">
     <meta name="viewport" content="width=1200" /> 

    <link rel="stylesheet" type="text/css" href="css/style.css"/>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>
	<link rel="stylesheet" type="text/css" href="css/960grid.css"/>
	<link rel="stylesheet" type="text/css" href="css/reset.css"/> 
    <link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>
    
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script src="js/bootstrap.js" type="text/javascript"></script>
    <link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>
  
  <title>PhotoRankr Groups</title>

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


function showGroupResult(str)
{

    ajaxRequest = createRequestObject();

  //Create a function that will receive data sent from the server
	ajaxRequest.onreadystatechange = function(){
		if(ajaxRequest.readyState == 4  && ajaxRequest.status == 200){
			var ajaxDisplay = document.getElementById('groupsearch');
            ajaxDisplay.innerHTML = ajaxRequest.responseText;
		}
	}
	
    var photog = <?php echo $email; ?>;
	ajaxRequest.open("GET", "groupsearchresults.php?q=" + str + "&photog=" + photog, true);
	ajaxRequest.send(null); 

}

function groupComment(str)
{
    alert('herer');
    ajaxRequest = createRequestObject();

  //Create a function that will receive data sent from the server
	ajaxRequest.onreadystatechange = function(){
		if(ajaxRequest.readyState == 4  && ajaxRequest.status == 200){
			var ajaxDisplay = document.getElementById('groupsearch');
            ajaxDisplay.innerHTML = ajaxRequest.responseText;
		}
	}
	
    var photog = <?php echo $email; ?>;
	ajaxRequest.open("GET", "groupcomment.php?q=" + str + "&photog=" + photog, true);
	ajaxRequest.send(null); 

}

</script>


</head>

<body style="overflow-x:hidden; background-color: #eeeff3;">

<?php navbar(); ?>

   <!--big container-->
    <div id="container" class="container_24" style="margin-top:20px;">

<?php   
    
    //Groups Portal Interface
    if(!$groupid) {
    
        echo'<div class="grid_8">'; //begin 8 grid
        
        if($_GET['error'] == 'name') {
            echo'<div style="font-size:15px;color:red;font-weight:300;text-align:center;">Please name your group</div>';
        }
        
        elseif($_GET['error'] == 'about') {
            echo'<div style="font-size:15px;color:red;font-weight:300;text-align:center;">Please describe your group</div>';
        }
    
        //Create new group
        echo'<div class="grid_8 newgroup" style="margin-top:50px;">
               <div style="font-size:20px;font-weight:lighter;border-bottom:1px solid #666;padding-bottom:5px;">Create a New Group</div><br />
                <form action="groups.php?action=creategroup" method="post">
                <strong>Group Name:</strong>&nbsp;&nbsp;<input type="text" name="name" /><br />
                <strong>About Group:</strong>&nbsp;&nbsp;<textarea name="about" style="height:60px;width:95%;"></textarea><br />
                <strong>Privacy:</strong>&nbsp;&nbsp;
                <select name="privacy">
                    <option value="0">Public (All can join)</option>
                    <option value="1">Private (Invite Only)</option>
                </select><br />
                <button type="submit" class="btn btn-success" style="width:95%;padding:8px;">Create Group</button>
                </form>
             </div>';
             
        //My Groups
        if($_SESSION['loggedin'] == 1) {
        echo'<div class="grid_8 newgroup" style="margin-top:50px;">
               <div style="font-size:20px;font-weight:lighter;border-bottom:1px solid #666;padding-bottom:5px;">My Groups</div><br />
                
             </div>';
        }
    
        //All Groups
        echo'<div class="grid_8 newgroup" style="margin-top:50px;">
               <div style="font-size:20px;font-weight:lighter;border-bottom:1px solid #666;padding-bottom:5px;">Search Groups</div><br />
               <input type="text" name="search" style="width:95%;" placeholder="Search for groups&#8230;" /><br />';
               
                $groupsinfo = mysql_query("SELECT * FROM groups ORDER BY id DESC");
                $numgroups = mysql_num_rows($groupsinfo);
                        
                for($jjj=0; $jjj<$numgroups; $jjj++) {
                
                    $groupname = mysql_result($groupsinfo,$jjj,'name');
                    $portalgroupid = mysql_result($groupsinfo,$jjj,'id');
                    $privacy =  mysql_result($groups,$jjj,'privacy');
                    $members = mysql_result($groups,$jjj,'members');
                    $membersarray = explode(" ",$members);
                    $numbermembers = count($membersarray);
                    $portalcoverphoto = mysql_result($groupsinfo,$jjj,'coverphoto');
                    if($portalcoverphoto) {
                        $getportalcoversource = mysql_query("SELECT source FROM photos WHERE id = '$portalcoverphoto'");
                        $portalcoverphoto = mysql_result($getportalcoversource,0,'source');
                    }
    
                    echo'<div><img src="../',$portalcoverphoto,'" style="width:50px;" />&nbsp;&nbsp;<a href="groups.php?id=',$portalgroupid,'">',$groupname,'</a></div>';
                
                }
            
             echo'
             </div>';
             
        echo'</div>'; //end of 8 grid
             
        echo'<div class="grid_16">'; //begin 16 grid     
        
        //Group News
         echo'<div class="grid_14 newgroup" style="margin-top:50px;margin-left:100px;">
               <div style="font-size:20px;font-weight:lighter;border-bottom:1px solid #666;padding-bottom:5px;">Newsfeed</div><br />
                
             </div>';
    
        echo'</div>'; //end 16 grid
        
    } //end of groups portal interface
    
    
    //Internal Groups Interface
    if($groupid) {
        
        //Group Information
        $groupinfoquery = mysql_query("SELECT * FROM groups WHERE id = $groupid");
        $owner = mysql_result($groupinfoquery,0,'group_owner');
        $groupname = mysql_result($groupinfoquery,0,'name');
        $members = mysql_result($groupinfoquery,0,'members');
        $membersarray = explode(" ",$members);
        $numbermembers = count($membersarray);
        $about = mysql_result($groupinfoquery,0,'about');
        $coverphoto = mysql_result($groupinfoquery,0,'coverphoto');
        
        echo'
        <div class="grid_8 cartBox rounded shadow" style="width:260px;margin-left:-30px;margin-top:20px;float:left;">
             <div class="cartText">Members <span style="font-size:13px;">',$numbermembers,' members</span></div>';
             for($ii=0; $ii <= $numbermembers; $ii++) {
                $getprofilepic = mysql_query("SELECT profilepic FROM userinfo WHERE user_id = $membersarray[$ii]");
                $profilepic = mysql_result($getprofilepic,0,'profilepic');
                echo'<a href="viewprofile.php?u=',$membersarray[$ii],'"><div class="memberbox" style="float:left;width:80px;height:80px;overflow:hidden;"><img src="../',$profilepic,'" style="min-width:80px;min-height:80px;" /></a></div>';
                }
        echo'
        </div>';
        
        echo'<div class="grid_16" style="margin-left:40px;border-left:1px solid #aaa;margin-top:20px;">';
        
        if($coverphoto) {
            $getcoversource = mysql_query("SELECT source FROM photos WHERE id = '$coverphoto'");
            $coverphoto = mysql_result($getcoversource,0,'source');
        
            echo'<div class="intexbox">';
        
                //Cover Photo
                list($width, $height) = getimagesize($coverphoto);
                $imgratio = $height / $width;
                $heightls = $height / 3;
                $widthls = $width / 3;
            
                              
                echo'<div style=""><img src="../',$coverphoto,'" /></div>
                
                </div>';
            }
            
        if(!$coverphoto) {
        
            echo'<div style="margin-top:30px;width:80px;">';
             
            for($ii=0; $ii<12 && $ii <= $numbermembers; $ii++) {
                $getprofilepic = mysql_query("SELECT profilepic FROM userinfo WHERE user_id = $membersarray[$ii]");
                $profilepic = mysql_result($getprofilepic,0,'profilepic');
                echo'<a href="viewprofile.php?u=',$membersarray[$ii],'"><img src="../',$profilepic,'" style="width:80px;height:80px;" /></a>';
            }
            
            echo'</div>';
        }
             
        echo'
            
             <div class="topbar">
                <div class="exhibittext">',$groupname,'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
                <ul id="navlist">
                    <li '; if($groupview == '') {echo'style="background-color:#ccc;"';} echo'><a style="color:#333;" href="?id=',$groupid,'">Home</a></li>
                    <li '; if($groupview == 'about') {echo'style="background-color:#ccc;"';} echo'><a style="color:#333;" href="?id=',$groupid,'&gv=about">About</a></li>
                    <li '; if($groupview == 'members') {echo'style="float:left;background-color:#ccc;"';} echo'><a style="color:#333;" href="?id=',$groupid,'&gv=members">Members</a></li>
                    <li style="border-right:1px solid #ccc;'; if($groupview == 'photos') {echo'background-color:#ccc;';} echo'"><a style="color:#333;" href="?id=',$groupid,'&gv=photos">Photos</a></li>';
                    if($email == $owner) {
                        echo'<li style="border-right:1px solid #ccc;'; if($groupview == 'settings') {echo'background-color:#ccc;';} echo'"><a style="color:#333;" href="?id=',$groupid,'&gv=settings">Admin Panel</a></li>';
                    }
                    echo'
                </ul>
             </div>';
             
             
             if($groupview == '') {
             
                //Group News
                $groupnewsquery = mysql_query("SELECT * FROM groupnews WHERE group_id = '$groupid' ORDER BY id DESC");
                $numgroupnews = mysql_num_rows($groupnewsquery);
                
                //Commenting and Posting Area
                echo'
                    <div style="font-size:15px;"><a class="btn btn-primary" data-toggle="modal" data-backdrop="static" href="#photomodal">Choose Photo From Portfolio</a></div>';
                    if(!$match) {
                    echo'
                    <div style="font-size:15px;margin-top:10px;"><a href="groups.php?id=',$groupid,'&action=join" class="btn btn-success"><img style="padding-right:6px;" src="../graphics/plus.png" height="17" />Join Group</a></div>';
                    }
                    elseif($match) {
                    echo'
                    <div style="font-size:15px;margin-top:10px;"><a href="groups.php?id=',$groupid,'&action=leave" class="btn btn-primary"><img style="padding-right:6px;" src="../graphics/plus.png" height="17" />Leave Group</a></div>';
                    }
                    
                    
                   echo'
                    <div id="thepics" style="position:relative;width:800px;margin-top:20px;">
                    <div id="main" role="main">';
                                        
                for($i=0; $i<10 && $i<$numgroupnews; $i++) {
                    
                    $commenter = mysql_result($groupnewsquery,$i,'commenter');
                    $comment = mysql_result($groupnewsquery,$i,'comment');
                    $photos = mysql_result($groupnewsquery,$i,'photo');
                    $photos = trim($photos);
                    $photosarray = explode(" ",$photos);
                    $numberphotos = count($photosarray);
                    $postid = mysql_result($groupnewsquery,$i,'id');
                    $time = mysql_result($groupnewsquery,$i,'time');
                    $time = converttime($time);

                    //commenter info
                    $commenterinfoquery = mysql_query("SELECT user_id,firstname,lastname,profilepic FROM userinfo WHERE emailaddress = '$commenter'");
                    $commentername = mysql_result($commenterinfoquery,0,'firstname') ." ". mysql_result($commenterinfoquery,0,'lastname');
                    $commenterpic = mysql_result($commenterinfoquery,0,'profilepic');
                    $commenterid = mysql_result($commenterinfoquery,0,'user_id');
                            
                    $photoid = mysql_result($setphotos,$iii,'id');
                
                    echo'<li class="fPic" id="',$id,'" style="list-style-type: none;width:550px;">
    
            <div class="comment" style="margin:8px;">
				<hgroup>
				<img src="https://photorankr.com/',$commenterpic,'" height="55" width="55" />
					<header style="width:450px;">
					<a style="margin-top:0px;color:#3B5998;font-size:14px;font-weight:bold;" href="viewprofile.php?u=',$commenterid,'">',$commentername,'</a>';
                    if($email == $owner) {
                            echo'<span style="float:right;"><a href="groups.php?id=',$groupid,'&post=',$postid,'&action=delete">X</a></span>';
                        }
                    echo'
                    <br /><span style="font-size:13px;color:#666;"><i class="icon-time"></i> ',$time,'</span>
					</header>
				</hgroup>
				<div style="clear:both;">
                    <div style="margin-left:60px;padding:10px;width:415px;">',$comment,'</div>';
                                           
                       if($numberphotos == 1) {
                            for($ii=0; $ii < 1; $ii++) {
                                $getphotosource = mysql_query("SELECT id,source FROM photos WHERE id = $photosarray[$ii]");
                                $photosource = mysql_result($getphotosource,0,'source');
                                $photosource = "../" . $photosource;
                                $medphoto = str_replace("userphotos/","userphotos/medthumbs/",$photosource);
                                $photoid = mysql_result($getphotosource,0,'id');
                                list($width, $height) = getimagesize($photosource);
                                $heightls = $height / 2.5;
                                $widthls = $width / 2.5;
                                                        
                                echo'<a href="fullsize.php?imageid=',$photoid,'"><div style="width:500px;padding:10px;margin-left:4%;overflow:hidden;"><img src="https://photorankr.com/',$medphoto,'" width="',$widthls,'" height="',$heightls,'" /></div></a>';
                            }
                        }
                       
                       elseif($numberphotos == 2) {
                            echo'<div style="width:505px;padding:10px;margin-left:10px;overflow:hidden;">';
                            for($ii=0; $ii < 2; $ii++) {
                                $getphotosource = mysql_query("SELECT id,source FROM photos WHERE id = $photosarray[$ii]");
                                $photosource = mysql_result($getphotosource,0,'source');
                                $photosource = "../" . $photosource;
                                $photoid = mysql_result($getphotosource,0,'id');
                                $medphoto = str_replace("userphotos/","userphotos/medthumbs/",$photosource);
                                $photoid = mysql_result($getphotosource,0,'id');
                                list($width, $height) = getimagesize($photosource);
                                $heightls = $height / 3.5;
                                $widthls = $width / 3.5;
                                
                                echo'<a href="fullsize.php?imageid=',$photoid,'"><div style="float:left;height:245px;max-width:245px;padding:3px;"><img style="height:245px;" src="https://photorankr.com/',$medphoto,'" width="',$widthls,'" /></div></a>';
                            }
                            echo'</div>';
                       }
                       
                       elseif($numberphotos == 3) {
                              echo'<div style="width:505px;padding:10px;margin-left:10px;overflow:hidden;">';
                            for($ii=0; $ii < 3; $ii++) {
                                $getphotosource = mysql_query("SELECT id,source FROM photos WHERE id = $photosarray[$ii]");
                                $photosource = mysql_result($getphotosource,0,'source');
                                $photosource = "../" . $photosource;
                                $photoid = mysql_result($getphotosource,0,'id');
                                $medphoto = str_replace("userphotos/","userphotos/medthumbs/",$photosource);
                                $photoid = mysql_result($getphotosource,0,'id');
                                list($width, $height) = getimagesize($photosource);
                                $heightls = $height / 3.5;
                                $widthls = $width / 3.5;
                                
                                echo'<a href="fullsize.php?imageid=',$photoid,'"><div style="float:left;height:160px;max-width:160px;padding:3px;"><img style="height:160px;" src="https://photorankr.com/',$medphoto,'" width="',$widthls,'" /></div></a>';
                            }
                            echo'</div>';
                       }
                       
                       elseif($numberphotos == 4) {
                              echo'<div style="width:505px;padding:10px;margin-left:10px;overflow:hidden;">';
                            for($ii=0; $ii < 4; $ii++) {
                                $getphotosource = mysql_query("SELECT id,source FROM photos WHERE id = $photosarray[$ii]");
                                $photosource = mysql_result($getphotosource,0,'source');
                                $photosource = "../" . $photosource;
                                $photoid = mysql_result($getphotosource,0,'id');
                                $medphoto = str_replace("userphotos/","userphotos/medthumbs/",$photosource);
                                $photoid = mysql_result($getphotosource,0,'id');
                                list($width, $height) = getimagesize($photosource);
                                $heightls = $height / 3.5;
                                $widthls = $width / 3.5;
                                
                                echo'<a href="fullsize.php?imageid=',$photoid,'"><div style="float:left;height:245px;max-width:245px;padding:3px;"><img style="height:245px;" src="https://photorankr.com/',$medphoto,'" width="',$widthls,'" /></div></a>';
                            }
                            echo'</div>';
                       }
                       
                
                echo'
				</div>';
                if($_SESSION['loggedin'] == 1) {
                
                ?>
                
                <script type="text/javascript" >
                    //Comment jQuery Script
$(function() {
$("#commentform<?php echo $postid; ?>").submit(function() 
{
var firstname = '<?php echo $sessionfirst; ?>';
var lastname = '<?php echo $sessionlast; ?>';
var email = '<?php echo $email; ?>';
var groupid = '<?php echo $groupid; ?>';
var userpic = '<?php echo $sessionpic; ?>';
var viewerid = '<?php echo $sessionid; ?>';
var postid = $("#postid").val();
var comment = $("#comment<?php echo $postid; ?>").val();
var dataString = 'firstname='+ firstname + '&lastname=' + lastname + '&email=' + email + '&comment=' + comment + '&userpic=' + userpic + '&groupid=' + groupid + '&post=' + postid + '&viewerid=' + viewerid;
$("#flash").show();
$("#flash").fadeIn(400).html();
$.ajax({
type: "POST",
url: "groupcomment.php",
data: dataString,
cache: false,
success: function(html){
$("ol#update<?php echo $postid; ?>").append(html);
$("ol#update li:last").fadeIn("slow");
$("#flash").hide();
}
});
return false;
}); });
                
            </script>
                
                <?php
                       echo'<form action="#" id="commentform',$postid,'" method="post" style="margin-top:5px;padding-bottom:25px;">        
            <img style="float:left;padding:10px;" src="https://photorankr.com/',$sessionpic,'" height="30" width="30" />
            <input type="hidden" id="postid" value="',$postid,'" />
            <textarea id="comment',$postid,'" style="float:left;width:395px;position:relative;top:10px;height:20px;" type="text" placeholder="Leave feedback for ',$firstname,'&#8230;"></textarea>
            <br /><br />
            <input style="float:left;margin-left:8px;margin-top:-24px;" type="submit" class="submit btn btn-success" value="Post"/>
            </form>';
                }
                
                echo'
                <!--AJAX COMMENTS-->
                <div class="float:left;"> 
                    <ol id="update',$postid,'" class="timeline">
                    </ol>
                </div>
            
                </li>';

                            
                }
                                
                echo'</div>
                     </div>';
             
             }
             
             elseif($groupview == 'about') {
                
                echo'<div style="width:960px;margin-left:25px;margin-top:20px;font-size:14px;color#333;font-weight:400;">
                <span style="font-weight:600;font-size:15px;">About:</span>
                ',$about,'</div>';
             
             }
             
             elseif($groupview == 'members') {
                
                echo'<div style="width:960px;margin-left:25px;margin-top:20px;font-size:14px;color#333;font-weight:400;">
                <div style="font-weight:600;font-size:15px;">',$numbermembers,' Members</div><br />';
                                
                for($ii=0; $ii <= $numbermembers; $ii++) {
                
                $getprofilepic = mysql_query("SELECT profilepic FROM userinfo WHERE user_id = $membersarray[$ii]");
                $profilepic = mysql_result($getprofilepic,0,'profilepic');
                echo'<a href="viewprofile.php?u=',$membersarray[$ii],'"><div class="memberbox" style="float:left;width:235px;height:230px;overflow:hidden;"><img src="../',$profilepic,'" style="min-width:235px;min-height:230px;" /></a></div>';
                
                }
                
                echo'
                </div>';
             
             }
             
             elseif($groupview == 'photos') {
                
                echo'<div style="width:960px;margin-left:25px;margin-top:20px;font-size:14px;color#333;font-weight:400;">
                <span style="font-weight:600;font-size:15px;">About:</span>
                ',$about,'</div>';
             
             }
        
         //Admin Panel Settings
        elseif($groupview == 'settings' && ($email == $owner)) {
        
        $action = htmlentities($_GET['action']);
        
        if($action == 'submit') {
		//GET UPDATED PROFILE INFORMATION
        if(isset($_POST['firstname'])) {$firstname=mysql_real_escape_string($_POST['firstname']); }
        if(isset($_POST['lastname'])) {$lastname=mysql_real_escape_string($_POST['lastname']); }
		if(isset($_POST['age'])) {$age=mysql_real_escape_string($_POST['age']); }
		if(isset($_POST['gender'])) {$gender=mysql_real_escape_string($_POST['gender']); }
		if(isset($_POST['location'])) {$location = mysql_real_escape_string($_POST['location']);}
		if(isset($_POST['camera'])) {$camera=mysql_real_escape_string($_POST['camera']);}
		if(isset($_POST['facebookpage'])) {$facebookpage=mysql_real_escape_string($_POST['facebookpage']);}
		if(isset($_POST['twitteraccount'])) {$twitteraccount=mysql_real_escape_string($_POST['twitteraccount']);}
        if(isset($_POST['quote'])) {$quote=mysql_real_escape_string($_POST['quote']);}
		if(isset($_POST['bio'])) {$bio=mysql_real_escape_string($_POST['bio']);}
		if(isset($_POST['password'])) {$password=mysql_real_escape_string($_POST['password']);}
		if(isset($_POST['confirmpassword'])) {$confirmpassword=mysql_real_escape_string($_POST['confirmpassword']);}

        //require files that will help with picture uploading and thumbnail creation/display
		require '../config.php';
	
		//move the file
		if(isset($_FILES['file'])) {  
  
    			if(preg_match('/[.](jpg)|(jpeg)|(gif)|(png)|(JPG)$/', $_FILES['file']['name'])) {  
        			$filename = $_FILES['file']['name'];  
                    $newfilename = md5(uniqid(rand(), true)) . $filename;
                    
                    $source = $_FILES['file']['tmp_name'];  
        			$profilepic = $path_to_profpic_directory . $newfilename; 
  
        			move_uploaded_file($source, $profilepic);  
                    chmod($profilepic, 0777);
                    
                    createprofthumbdim($profilepic);
        			createprofthumbnail($profilepic);
					
    			}  
		}  
	
		//update the database with this new information
		if(isset($_POST['bio'])) {
			$infoupdatequery=("UPDATE userinfo SET firstname = '$firstname', lastname = '$lastname', age = '$age', gender = '$gender', location = '$location', camera = '$camera', facebookpage='$facebookpage', twitteraccount='$twitteraccount', quote='$quote', bio='$bio', profilepic='$profilepic', password='$password', viewLikes='$viewLikes' WHERE emailaddress='$email'");
		}
		else {
        
			$infoupdatequery=("UPDATE userinfo SET firstname = '$firstname', lastname = '$lastname', age = '$age', gender = '$gender', location = '$location', camera = '$camera', facebookpage='$facebookpage', twitteraccount='$twitteraccount', profilepic='$profilepic', password='$password', viewLikes='$viewLikes' WHERE emailaddress='$email'");
		}
		$infoupdateresult=mysql_query($infoupdatequery);

        mysql_close();
        echo '<META HTTP-EQUIV="Refresh" Content="0; URL=myprofile.php?view=editinfo&action=saved">';
        exit();
	}
    else if($action == "saved") {
        echo '<div style="margin-top:20px;margin-left:60px;color:#6aae45;float:left;font-size:20px;font-weight:200;">Profile Saved</div><br />';
    }

        echo'
        <div class="span9" style="font-size:13px;font-family:helvetica,arial;">
        <table class="table">
        <tbody>
        <form action="#" method="post">';

        if($about) {
        echo'
        <tr>
        <td>About Group:</td>
        <td><textarea style="width:100%;height:50px;" name="about">',$about,'</textarea></td>
        </tr>'; }

        if($privacy == 0) {
        echo'
        <tr>
        <td>Group Privacy:</td>
        <td>
            <input checked type="radio" name="privacy" value="0"> Public<br>
            <input type="radio" name="privacy" value="1"> Pivate
            </td>
        </tr>'; }
        
        elseif($privacy == 1) {
        echo'
        <tr>
        <td>Group Privacy:</td>
        <td>
            <input type="radio" name="privacy" value="0"> Public<br>
            <input checked type="radio" name="privacy" value="1"> Pivate
            </td>
        </tr>'; }

        echo'
        <tr>
        <td>Cover Photo:</td>
        <td><img src="',$coverphoto,'" height="30" width="30" />&nbsp;&nbsp;&nbsp;<input type="file"  name="file" value="', $coverphoto, '"/></td>
        </tr>';
        
        echo'
        </form>
        </tbody>
        </table>
        </div>';
        
             
             }
             
        echo'</div>'; //end grid_16
            
    } //end of internal groups interface
    
    
    //ADD PHOTOS MODAL
echo'<div class="modal hide fade" id="photomodal" style="overflow:hidden;border:5px solid rgba(102,102,102,.8);">

<div class="modal-header" style="background-color:#111;color:#fff;">
<a style="float:right" class="btn btn-primary" data-dismiss="modal">Close</a>
<img style="margin-top:-4px;" src="graphics/coollogo.png" height="28" width="90" />&nbsp;&nbsp;<span style="font-size:16px;">Add up to 4 photos to your post:</span>
  </div>
  <div modal-body" style="width:600px;">

<div id="content" style="font-size:16px;width:550px;height:450px;overflow-x:hidden;">';

if($email != '') {
echo'
<img style="border: 1px solid black;margin-left:10px;margin-top:10px;" src="https://photorankr.com/',$sessionpic,'" 
height="100px" width="100px" />

<div style="width:440px;margin-left:130px;margin-top:-125px;overflow-y:scroll;overflow-x:hidden;">

<form action="groupupload.php" method="post">
    <div style="font-size:14px;margin-top:20px;">
    <input type="hidden" name="group_id" value="',$groupid,'" />
    <textarea id="addphoto" type="text" style="padding:5px;width:360px;height:60px;" name="comment" placeholder="Leave something to talk about&#8230;"></textarea>
    </div>
    <br /><br />';
    $allusersphotos = "SELECT * FROM photos WHERE emailaddress = '$email'";
    $allusersphotosquery = mysql_query($allusersphotos);
    $usernumphotos = mysql_num_rows($allusersphotosquery);
    
    for($iii = 0; $iii < $usernumphotos; $iii++) {
        $userphotosource = mysql_result($allusersphotosquery, $iii, "source");
        $userphotosource = str_replace("userphotos/","http://photorankr.com/userphotos/", $userphotosource);
        $userphotosid = mysql_result($allusersphotosquery, $iii, "id");
        $userphotosset[$iii] = mysql_result($allusersphotosquery, $iii, "sets");
        $userphotoscaption[$iii] = mysql_result($allusersphotosquery, $iii, "caption");
        $newsource = str_replace("userphotos/","userphotos/thumbs/", $userphotosource);
        
        echo'<img src="',$newsource,'" height="70" width="70" />&nbsp;&nbsp;<input type="checkbox" name="addthese[]" value="',$userphotosid,'" />&nbsp;"',$userphotoscaption[$iii],'"
        <br /><br />'; 
    
    } //end of for loop
    
    
    echo'
    </span>
    <button class="btn btn-success" type="submit">Submit Photos</button>
    <br />
    <br />
    </form>';
    }
    
    else {
    echo'<div style="text-align:center;margin-top:100px;"><b>Please login or register to upload</b></div>';
    }
    
    echo'
    </div>
    </div>
    </div>';
   
?>
   
    </div><!--end of container-->

    <script src="js/bootstrap-dropdown.js"></script>
    <script type="text/javascript">  
        $(document).ready(function () {  
            $('.dropdown-toggle').dropdown();  
        });  
    </script> 
        
</body>
</html>
