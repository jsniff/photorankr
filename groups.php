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
    
    //Search Term
    $searchterm = mysql_real_escape_string(htmlentities($_GET['searchterm']));
    
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
    $view = htmlentities($_GET['view']);
    $searchterm = htmlentities($_GET['searchterm']);
    
    //Groups Queries
    $groupcheck = mysql_query("SELECT members,name FROM groups WHERE id = $groupid");
    $currentmembers = mysql_result($groupcheck,0,'members');
    $group_name = mysql_result($groupcheck,0,'name');
    $matchid = $sessionid . " ";
    $regex="/$matchid/";
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
        //create group
        $newgroupquery = mysql_query("INSERT INTO groups (group_owner,name,about,privacy,time) VALUES ('$email','$groupname','$about','$privacy','$currenttime')");
        
        //get new group id
        $getlastgroup = mysql_query("SELECT id FROM groups WHERE group_owner = '$email' ORDER BY id DESC LIMIT 0,1");
        $lastid = mysql_result($getlastgroup,0,'id');
        
        //insert into news & update profile saying in this group now
        $updatemembers = $sessionid . ",";
        $addtogroups = mysql_query("UPDATE groups SET members = concat(members,'$updatemembers') WHERE id = $lastid");
        $updategroup = $group . ",";
        $updateprofilegroups = mysql_query("UPDATE userinfo SET groups = concat(groups,'$updategroup') WHERE user_id = $sessionid");
        $addtonewsfeed = mysql_query("INSERT INTO groupnews (group_id,firstname, lastname, commenter,time,type) VALUES ('$lastid','$sessionfirst', '$sessionlast', '$email','$currenttime','create')") or die();
        $addtoothernewsfeed = mysql_query("INSERT INTO newsfeed (firstname, lastname, emailaddress,type,time,group_id) VALUES ('$sessionfirst', '$sessionlast', '$email','create','$currenttime','$lastid')") or die();
        }
        //Redirect to new group
        header("Location: groups.php?id=$lastid");
    
    }
    
    
    //Join Group
    if($action == 'join') {
        //check if already in group
        if(!$match) {
            $updatemembers = $sessionid . ",";
            $addtogroups = mysql_query("UPDATE groups SET members = concat(members,'$updatemembers') WHERE id = $groupid");
            $groupid = htmlentities($_GET['id']);
            $updategroup = $groupid . ",";
            $updateprofilegroups = mysql_query("UPDATE userinfo SET groups = concat(groups,'$updategroup') WHERE user_id = $sessionid");
            $addtonewsfeed = mysql_query("INSERT INTO groupnews (group_id,firstname, lastname, commenter,time,type) VALUES ('$groupid','$sessionfirst', '$sessionlast', '$email','$currenttime','join')") or die();
            $addtoothernewsfeed = mysql_query("INSERT INTO newsfeed (firstname, lastname, emailaddress,type,time,group_id) VALUES ('$sessionfirst', '$sessionlast', '$email','join','$currenttime','$groupid')") or die();

        }
    }
    
    //Leave Group
    if($action == 'leave') {
        $updategroup = $group . " ";
        $newgroupid = $groupid . ",";
        $formatteduserid = $sessionid . ",";
        $newmemberslist = str_replace($formatteduserid,"",$currentmembers);
        $updatedgroups = str_replace($newgroupid,"",$usergroups);
        $updatedgroups = substr($updatedgroups, 0, -1);  
        $leavegroup = mysql_query("UPDATE groups SET members = '$newmemberslist' WHERE id = $groupid");
        $updateprofilegroups = mysql_query("UPDATE userinfo SET groups = '$updatedgroups' WHERE user_id = $sessionid");
    }
    
    //Groups Queries
    $groupcheck = mysql_query("SELECT members FROM groups WHERE id = $groupid");
    $currentmembers = mysql_result($groupcheck,0,'members');
    $matchid = $sessionid.",";
    $regex="/$matchid/";
    $match=preg_match($regex,$currentmembers);
    
   //Delete Post
    if($action == 'delete') {
    
        $postid = mysql_real_escape_string($_GET['post']);
        $deletepost = mysql_query("DELETE FROM groupnews WHERE id = '$postid'");
    }
    
    //Admin Panel Backend Stuff
     $action = htmlentities($_GET['action']);
        //add checked photogrphers to admin list
        if($action == 'addadmins') {
            if(!empty($_POST['addAdmins'])) {
                foreach($_POST['addAdmins'] as $checked) {
                    $emailaddress = " ".$checked;
                    //grab user's name
                    $userinfo = mysql_query("SELECT firstname,lastname FROM userinfo WHERE emailaddress = '$emailaddress'");
                    $userfirst = mysql_result($userinfo,0,'firstname');
                    $userlast = mysql_result($userinfo,0,'lastname');
                    $ownermatch = preg_match("/$emailaddress/",$owner);
                    //check if already an administrator
                    if(!$ownermatch) {
                        $addtoadminlist = mysql_query("UPDATE groups SET group_owner = concat(group_owner,'$emailaddress') WHERE id = $groupid");
                        //send new admin an email
                        $to = '"' . $userfirst . ' ' . $userlast . '"' . '<'.$emailaddress.'>';
                        $subject = $sessionname ." added you as an administrator to the group ". $group_name;
                        $message = $sessionname ." added you as an administrator to the group ". $group_name .
"To visit the group, click here: https://photorankr.com/groups.php?id=".$groupid;
                        $headers = 'From:PhotoRankr <photorankr@photorankr.com>';
                        mail($to, $subject, $message, $headers);          

                    }
                }
            }
            header('Location: https://photorankr.com/groups.php?id='.$groupid.'&gv=settings&action=adminssaved');
        } //end add admins action  
        
         if($action == 'invite') {
            if(!empty($_POST['invite'])) {
                foreach($_POST['invite'] as $checked) {
                    $emailaddress = " ".$checked;
                    $userinfo = mysql_query("SELECT firstname,lastname FROM userinfo WHERE emailaddress = '$emailaddress'");
                    $userfirst = mysql_result($userinfo,0,'firstname');
                    $userlast = mysql_result($userinfo,0,'lastname');
                    //Email invitation
                    $to = '"' . $userfirst . ' ' . $userlast . '"' . '<'.$emailaddress.'>';
                    $subject = $sessionname ." invited you to join the group ". $group_name;
                    $message = $sessionname ." invited you to join the group ". $group_name .
"To join the group, click here: https://photorankr.com/groups.php?id=".$groupid;
                    $headers = 'From:PhotoRankr <photorankr@photorankr.com>';
                    mail($to, $subject, $message, $headers);                         
                }
            }
            header('Location: https://photorankr.com/groups.php?id='.$groupid.'&gv=settings&action=invitessent');
        } //end invites action   

        
?>


<!DOCTYPE HTML>
<head>
    
    <meta name="Generator" content="EditPlus">
    <meta name="Author" content="PhotoRankr, PhotoRankr.com">
    <meta name="Keywords" content="photos, sharing photos, photo sharing, photography, photography club, sell photos, sell photography, where to sell my photography, good sites for selling photography, making money from photography, making money off photography, social networking, social network, social networks, where to sell my photos, good sites for selling photos, good site to sell photos, making money from photos">
    <meta name="Description" content="Photography groups on PhotoRankr. Join today to share and learn about specific topics in photograpy.">
    <meta name="viewport" content="width=1200" /> 
    
    <title>PhotoRankr Groups</title>
    
    <link rel="stylesheet" type="text/css" href="css/style.css"/>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>
	<link rel="stylesheet" type="text/css" href="css/960grid.css"/>
	<link rel="stylesheet" type="text/css" href="css/reset.css"/> 
    <link rel="stylesheet" type="text/css" href="css/main3.css"/>

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
    <script src="js/modernizer.js"></script>
    <script type="text/javascript" src="js/jquery.wookmark.js"></script>    
    <script src="js/bootstrap.js" type="text/javascript"></script>        
    <link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>  
    
	<style type="text/css">
		.show
		{
			display:block !important;
		}
		
		#notify
		{
			width:40px;
			margin: 0 0 0 5px;
			background:#d96f62;
			padding: 5px;
		}
		#notify:hover
		{
			background: rgba(255,255,255,.55);
		}
		#drawer
		{
			width:0px;
			background: url('graphics/noise.png');
			color:#fff;
			white-space: normal;
			font-size: 10px;
			position:fixed;
			height:100%;
			box-shadow: inset 0 0 5px rgba(0,0,0,.25);
			border-radius:0 5px 5px 0;
			margin: 5px 0 0 -5px;
			z-index: 1000;
		}
		.notifications
		{
			font-family:"helvetica neue", helvetica, arial,sans-serif; 
			font-size:20px;
			font-weight: 500;
			color:#fff;
			margin-left: -200px;
			width:200px;

		}
		.test
		{
			height:250px;
			background: rgba(200,200,200,.6);
			box-shadow: 0 0 2px #666;
			margin: 4px 20px 0 0;
		}
		.test2
		{
			height:50px;
			background: rgba(200,200,200,.6);
			box-shadow: 0 0 2px #666;
			margin: 7px 4px !important;
			width:125px;
			float: right;
		}
		.x
		{
			background:none !important;
			color:#222 !important;
			padding: 0 !important;
			box-shadow: 0 0 0 !important;
			margin:10px 5px 0 5px !important;
			border: none !important;
			font-size: 14px;
		}
		.fixedTop
		{
			position: fixed;
			top: -20px;
			width:1000px !important;
			background-image: url("graphics/paper.png"),  -webkit-gradient(linear, left top, left bottom, from(#444), to(#444)) url("graphics/paper.png"); /* Saf4+, Chrome */, url("graphics/paper.png") !important	;
			background-image: url("graphics/paper.png"),  -webkit-linear-gradient(top, #666, #444) !important;
			background-image: url("graphics/paper.png"),  -moz-linear-gradient(top, #666, #444) !important;
			background-image: url("graphics/paper.png"),  -o-linear-gradient(top, #666, #444) !important;
			background-image: url("graphics/paper.png"),  -ms-linear-gradient(top, #666, #444) !important;
		}	
		/*.arrow-right {
	width: 0; 
	height: 0; 
	border-top: 13px solid transparent;
	border-bottom: 13px solid transparent;
	box-shadow: inset 0 0 1px #999;
	border-right: 13px solid rgba(245,245,245,1);
	position: absolute;
	top:33px;
	left:75px;
}*/
	</style>
    
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

<?php

//CREATE GROUP MODAL 
echo'<div class="modal hide fade" id="createmodal" style="overflow:hidden;border:5px solid rgba(102,102,102,.8);z-index:100000;">

<div class="modal-header" style="background-color:rgba(234,234,234,.9);color:#fff;">
<a style="float:right" class="btn btn-success" data-dismiss="modal">Close</a>
<img style="margin-top:-4px;" src="graphics/aperture_dark.png" width="30" />&nbsp;&nbsp;<span style="font-size:18pxfont-weight:300;;color:#333">Create your group below</span>
  </div>
  <div modal-body" style="width:600px;">

<div id="content" style="font-size:16px;width:550px;height:260px;overflow-x:hidden;">';

if($email != '') {
echo'
<div style="float:left;width:520px;margin-left:15px;margin-top:10px;overflow-y:scroll;overflow-x:hidden;">

<div style="font-size:20px;font-weight:300;border-bottom:1px solid #666;padding-bottom:5px;">Create a New Group</div><br />
                <form action="groups.php?action=creategroup" method="post">
                <span style="font-weight:500;">Group Name:</span>&nbsp;&nbsp;<input type="text" name="name" /><br />
                <span style="font-weight:500;">About Group:</span>&nbsp;&nbsp;<textarea name="about" style="height:60px;width:98%;"></textarea><br />
                <!--<strong>Privacy:</strong>&nbsp;&nbsp;
                <select name="privacy">
                    <option value="0">Public (All can join)</option>
                    <option value="1">Private (Invite Only)</option>
                </select><br />-->
                <button type="submit" class="btn btn-success" style="width:98%;padding:8px;">Create Group</button>
                </form>
    <br />
    <br />';
    }
    
    else {
    echo'<div style="text-align:center;margin-top:100px;"><b>Please login or register to create a group</b></div>';
    }
    
    echo'
    </div>
    
    </div>
    </div>
    </div>';
    
?>

<body style="overflow-x:hidden; background-image:url('graphics/paper.png');">
<?php include_once("analyticstracking.php") ?>

<?php navbar(); ?>

<?php 
    if(!$email) {
        echo'
        <div id="registerBar">
            <img style="width:16px;padding:4px;margin-top:-3px;" src="graphics/groups_b.png" />
            <a href="register.php">Register for free today to start joining groups.
            </a> 
        </div>
        <div style="clear:both;padding-bottom:20px;"></div>';
    }
?>

<!--BEGIN CONTAINER-->


<?php 
if(!$email) {
    echo'<div style="width:100%;height:25;padding:5px;margin-top:-10px;background-color:rgba(220,220,220,.7);z-index:101;font-weight:300;font-size:17px;text-align:center;position:fixed;"><img style="width:16px;padding:4px;margin-top:-3px;" src="graphics/groups_b.png" /> Please login above to join this group.</div>';
}
?>

<div class="container_custom clearfix" style="margin:50px auto 0 auto;width:1000px;padding-left:70px;">

<?php   
    
    //Groups Portal Interface
    if(!$groupid) {
        
    echo'<div style="width:1090px;">';
        
        echo'<div style="width:100%;font-size:26px;font-weight:300;padding:15px;margin-left:-70px;">PhotoRankr Groups</div>';
        
        echo'<div class="grid_12" style="margin-left:-65px;">
        
                <!---Left Bar--->
                <div class="groupsBar">
                   <img style="margin-top:-5px;padding:3px;width:18px;" src="graphics/graph.png" /> Groups Activity
                </div>
                
                <div class="grid_12 groupsBody" style="min-height:600px;">';
                    $groupnewsquery = mysql_query("SELECT * FROM newsfeed WHERE type IN ('create','post','join') ORDER BY id DESC LIMIT 20");
                    $numgroupnews = mysql_num_rows($groupnewsquery); 

                    for($iii=0; $iii < 20 && $iii < $numgroupnews; $iii++) {
                        $newsrow = mysql_fetch_array($groupnewsquery);
                        $type = $newsrow['type'];
                        $group_id = $newsrow['group_id'];
                        $newsemail = $newsrow['emailaddress']; 
                        $firstname = $newsrow['firstname'];
                        $lastname = $newsrow['lastname']; 
                        $postsource = $newsrow['source'];   
                    
    if($type == 'create') {
        $ownersquery = "SELECT * FROM userinfo WHERE emailaddress = '$newsemail'";
        $ownerresult = mysql_query($ownersquery); 
        $ownerrow = mysql_fetch_array($ownerresult);
        $ownerfirst = $ownerrow['firstname'];
        $ownerlast = $ownerrow['lastname'];
        $ownerid = $ownerrow['user_id'];
        $ownerprofilepic = $ownerrow['profilepic'];
        //group info
        $groupinfoquery = mysql_query("SELECT * FROM groups WHERE id = $group_id");
        $about = mysql_result($groupinfoquery,0,'about');
        $groupname = mysql_result($groupinfoquery,0,'name');
        
        echo'<div class="grid_16">
         <!--Profile Picture-->
            <div class="newsBlock">
                <ul>
                    <li><img id="newsProfilePic" src="',$ownerprofilepic,'" />
                </ul>
            </div>
                     
         <!--Content Box-->
         <div class="newsContainer">
            <div class="newsTriangle" style="z-index:1;"></div>
                <div class="newsItem" style="width:430px;">
                    <!--Top Controls-->
                    <div class="newsControls">
                        <div class="newsName"><a href="viewprofile.php?u=',$ownerid,'">',$ownerfirst,' ',$ownerlast,'</a> created a new group</div>
                        <div class="newsTools">
                            <span id="time">',$time,'</span>
                        </div>
                    </div>
                    <!--Content-->
                    <div id="followContent">
                        <a style="color:#333;" href="groups.php?id=',$group_id,'">
                            <header style="padding: 0px 20px;font-size:18px;font-weight:300;line-height:20px;"><img style="width:18px;padding:4px;margin-top:-4px;" src="graphics/groups_b.png">',$groupname,'</header>
                        </a>
                        <div style="width:380px;padding:25px;font-size:14px;font-weight:300;">
                            <span style="font-size:15px;font-weight:normal;line-height:22px;">About:</span><br />
                            ',$about,'
                        </div>
                    </div>
                </div>
         <!--End Content Box-->
         </div>
                  
         </div>';
    
    } //end type == 'create'
    
    elseif($type == 'join') {
        $ownersquery = "SELECT * FROM userinfo WHERE emailaddress = '$newsemail'";
        $ownerresult = mysql_query($ownersquery); 
        $ownerrow = mysql_fetch_array($ownerresult);
        $ownerfirst = $ownerrow['firstname'];
        $ownerlast = $ownerrow['lastname'];
        $ownerid = $ownerrow['user_id'];
        $ownerprofilepic = $ownerrow['profilepic'];
        //group info
        $groupinfoquery = mysql_query("SELECT * FROM groups WHERE id = $group_id");
        $about = mysql_result($groupinfoquery,0,'about');
        $groupname = mysql_result($groupinfoquery,0,'name');
        
        echo'<div class="grid_16">
         <!--Profile Picture-->
            <div class="newsBlock">
                <ul>
                    <li><img id="newsProfilePic" src="https://photorankr.com/',$ownerprofilepic,'" />
                </ul>
            </div>
                     
         <!--Content Box-->
         <div class="newsContainer">
            <div class="newsTriangle" style="z-index:1;"></div>
                <div class="newsItem" style="width:430px;">
                    <!--Top Controls-->
                    <div class="newsControls">
                        <div class="newsName"><a href="viewprofile.php?u=',$ownerid,'">',$firstname,' ',$lastname,'</a> joined the group <a href="groups.php?id=',$group_id,'">',$groupname,'</a></div>
                        <div class="newsTools">
                            <span id="time">',$time,'</span>
                        </div>
                    </div>
                    <!--Content-->
                    <div id="followContent">
                        <a style="color:#333;" href="groups.php?id=',$groupid,'">
                            <header style="padding: 0px 20px;font-size:18px;font-weight:300;line-height:20px;"><img style="width:18px;padding:4px;margin-top:-4px;" src="graphics/groups_b.png">',$groupname,'</header>
                        </a>
                        <div style="width:380px;padding:25px;font-size:14px;font-weight:300;">
                            <span style="font-size:15px;font-weight:normal;line-height:22px;">About:</span><br />
                            ',$about,'
                        </div>
                    </div>
                </div>
         <!--End Content Box-->
         </div>
                  
         </div>';
    
    } //end type == 'join'
    
    
    elseif($type == 'post') {
        $ownersquery = "SELECT * FROM userinfo WHERE emailaddress = '$newsemail'";
        $ownerresult = mysql_query($ownersquery); 
        $ownerrow = mysql_fetch_array($ownerresult);
        $ownerfirst = $ownerrow['firstname'];
        $ownerlast = $ownerrow['lastname'];
        $ownerid = $ownerrow['user_id'];
        $ownerprofilepic = $ownerrow['profilepic'];
        //Group info
        $groupinfoquery = mysql_query("SELECT * FROM groups WHERE id = $group_id");
        $groupname = mysql_result($groupinfoquery,0,'name');
        //Post info
        $groupphotosquery = mysql_query("SELECT * FROM groupnews WHERE id = '$postsource' LIMIT 1");
        $comment = mysql_result($groupphotosquery,0,'comment');
        $photos = mysql_result($groupphotosquery,0,'photo');
        $photos = trim($photos);
        $photosarray = explode(" ",$photos);
        $numberphotos = count($photosarray);
        
        echo'<div class="grid_16">
         <!--Profile Picture-->
            <div class="newsBlock">
                <ul>
                    <li><img id="newsProfilePic" src="https://photorankr.com/',$ownerprofilepic,'" />
                </ul>
            </div>
                     
         <!--Content Box-->
         <div class="newsContainer">
            <div class="newsTriangle" style="z-index:1;"></div>
                <div class="newsItem" style="width:430px;">
                    <!--Top Controls-->
                    <div class="newsControls">
                        <div class="newsName"><a href="viewprofile.php?u=',$ownerid,'">',$firstname,' ',$lastname,'</a> posted in <a href="groups.php?id=',$group_id,'">',$groupname,'</a></div>
                        <div class="newsTools">
                            <span id="time">',$time,'</span>
                        </div>
                    </div>
                    <!--Content-->
                    <div id="postContent">';
                        //Show photos if posted with a photo
                        
                        if($numberphotos == 1) {
                            for($ii=0; $ii < 1; $ii++) {
                                $getphotosource = mysql_query("SELECT id,source FROM photos WHERE id = $photosarray[$ii]");
                                $photosource = mysql_result($getphotosource,0,'source');
                                $medphoto = str_replace("userphotos/","userphotos/medthumbs/",$photosource);
                                $photoid = mysql_result($getphotosource,0,'id');
                                list($width, $height) = getimagesize($photosource);
                                $heightls = $height / 2.8;
                                $widthls = $width / 2.8;
                                                        
                                echo'<a href="fullsize.php?imageid=',$photoid,'"><div style="width:390px;padding:10px;margin-left:4%;overflow:hidden;"><img style="min-width:440px;" src="https://photorankr.com/',$medphoto,'" width="',$widthls,'" height="',$heightls,'" /></div></a>';
                            }
                        }
                       
                       elseif($numberphotos == 2) {
                            echo'<div style="width:505px;padding:10px;margin-left:10px;overflow:hidden;">';
                            for($ii=0; $ii < 2; $ii++) {
                                $getphotosource = mysql_query("SELECT id,source FROM photos WHERE id = $photosarray[$ii]");
                                $photosource = mysql_result($getphotosource,0,'source');
                                $photoid = mysql_result($getphotosource,0,'id');
                                $medphoto = str_replace("userphotos/","userphotos/medthumbs/",$photosource);
                                $photoid = mysql_result($getphotosource,0,'id');
                                list($width, $height) = getimagesize($photosource);
                                $heightls = $height / 3.5;
                                $widthls = $width / 3.5;
                                
                                echo'<a href="fullsize.php?imageid=',$photoid,'"><div style="float:left;height:195px;max-width:198px;padding:3px;"><img style="height:245px;" src="https://photorankr.com/',$medphoto,'" width="',$widthls,'" /></div></a>';
                            }
                            echo'</div>';
                       }
                       
                       elseif($numberphotos == 3) {
                              echo'<div style="width:505px;padding:10px;margin-left:10px;overflow:hidden;">';
                            for($ii=0; $ii < 3; $ii++) {
                                $getphotosource = mysql_query("SELECT id,source FROM photos WHERE id = $photosarray[$ii]");
                                $photosource = mysql_result($getphotosource,0,'source');
                                $photoid = mysql_result($getphotosource,0,'id');
                                $medphoto = str_replace("userphotos/","userphotos/medthumbs/",$photosource);
                                $photoid = mysql_result($getphotosource,0,'id');
                                list($width, $height) = getimagesize($photosource);
                                $heightls = $height / 3.5;
                                $widthls = $width / 3.5;
                                
                                echo'<a href="fullsize.php?imageid=',$photoid,'"><div style="float:left;height:140px;max-width:127px;padding:3px;"><img style="height:160px;" src="https://photorankr.com/',$medphoto,'" width="',$widthls,'" /></div></a>';
                            }
                            echo'</div>';
                       }
                       
                       elseif($numberphotos == 4) {
                              echo'<div style="width:505px;padding:10px;margin-left:10px;overflow:hidden;">';
                            for($ii=0; $ii < 4; $ii++) {
                                $getphotosource = mysql_query("SELECT id,source FROM photos WHERE id = $photosarray[$ii]");
                                $photosource = mysql_result($getphotosource,0,'source');
                                $photoid = mysql_result($getphotosource,0,'id');
                                $medphoto = str_replace("userphotos/","userphotos/medthumbs/",$photosource);
                                $photoid = mysql_result($getphotosource,0,'id');
                                list($width, $height) = getimagesize($photosource);
                                $heightls = $height / 3.5;
                                $widthls = $width / 3.5;
                                
                                echo'<a href="fullsize.php?imageid=',$photoid,'"><div style="float:left;height:195px;max-width:198px;padding:3px;"><img style="height:195px;" src="https://photorankr.com/',$medphoto,'" width="',$widthls,'" /></div></a>';
                            }
                            echo'</div>';
                       }
                        echo'
                        <div style="width:400px;padding:25px;font-size:14px;font-weight:300;">
                            <img style="width:18px;padding:4px;margin-top:-4px;" src="graphics/groups_b.png"> ',$comment,'
                            <div style="font-weight:500;padding-top:10px;">
                                <a href="groups.php?id=',$group_id,'#',$postsource,'">View post >>></a>
                            </div>
                        </div>
                    </div>
                </div>
         <!--End Content Box-->
         </div>
                  
         </div>';
    
    } //end type == 'post'
                                            
                    } //end group news for loop
                
                echo'
                </div>
                
             </div>
             
            <!---Middle Bar--->
            <div class="grid_6 groupsBar" style="width:235px;margin-left:20px;">
                <ul style="padding:5px;border-bottom:1px solid #aaa;">
                    <li id="featuredGroups"><img style="margin-top:-5px;padding:3px;width:14px;" src="graphics/star.png" /> Featured</li>
                    <li id="myGroups"><img style="margin-top:-5px;padding:3px;width:14px;" src="graphics/user.png" /> My Groups</li>
                </ul>';
                                    
                        
                //Featured Groups Div
                echo'<div id="featuredGroupsDiv" style="margin-top:5px;">';
                    $groupinfo = mysql_query("SELECT * FROM groups ORDER BY views DESC LIMIT 10");
                    $numgroups = mysql_num_rows($groupinfo);
                    for($ii=0; $ii<10 && $ii < $numgroups; $ii++) {
                        $groupname = mysql_result($groupinfo,$ii,'name');
                        $members = mysql_result($groupinfo,$ii,'members');
                        $membersarray = explode(",",$members);
                        $numbermembers = count($membersarray) - 1;
                        $about = mysql_result($groupinfo,$ii,'about');
                        $shortabout = (strlen($about ) > 105) ? substr($about,0,100). " &#8230;" : $about;
                        $group_id = mysql_result($groupinfo,$ii,'id');
                        
                        //Grab group photos 
                        $groupphotosquery2 = mysql_query("SELECT * FROM groupnews WHERE group_id = '$group_id' ORDER BY id DESC");
                        $numphotonews = mysql_num_rows($groupphotosquery2);
                        $totalnumposts = 0;
                        $totalnumphotos = 0;
                        $imagesources = "";
                        for($iii=0; $iii<=$numphotonews; $iii++) {
                            $gpphotos = mysql_result($groupphotosquery2,$iii,'photo');
                            $imagesources .= $gpphotos;
                            $photoarray = explode(" ",$gpphotos);
                            $numphotosinarray = count($photoarray);
                            $totalnumphotos += $numphotosinarray;
                            $type = mysql_result($groupphotosquery2,$iii,'type');
                            if($type == 'post') {
                                $totalnumposts += 1;
                            }
                        }
                        $imagesourcesArray = explode(" ",$imagesources);
                        $imagecount = count($imagesourcesArray);

                        echo'<div class="myGroupsBox">
                             <a style="color:#333;text-decoration:none;" href="groups.php?id=',$group_id,'">
                                <div style="width:220px;padding:4px;font-size:14px;font-weight:500;border-bottom:1px solid #aaa;">
                                    <img style="margin-top:-5px;padding:3px;width:12px;" src="graphics/groups_b.png" /> ',$groupname,'
                                    <br />
                                    <span style="font-size:13px;font-weight:300;"> 
                                        <img style="margin-top:-5px;padding:3px;width:12px;" src="graphics/user.png" /> ',$numbermembers,' members
                                        <img style="margin-top:-5px;padding:3px;width:12px;" src="graphics/comment_1.png" /> ',$totalnumposts,' posts 
                                    </span>
                                </div>';
                                
                                if($totalnumphotos > 2) {
                                    $count = 0;
                                    for($iii=0;$iii<$imagecount;$iii++) {
                                        if($count >= 6) {
                                            break;
                                        }
                                        $sourcequery = mysql_query("SELECT source FROM photos WHERE id = '$imagesourcesArray[$iii]'");
                                        $source = mysql_result($sourcequery,0,'source');
                                        $source = str_replace("userphotos/","userphotos/medthumbs/",$source);
                                        if(!$source) {
                                            continue;
                                        }
                                        elseif($source) {
                                            $count += 1; 
                                        }

                                        echo'<img style="width:73px;height:73px;padding:1px;padding-top:2px;" src="https://photorankr.com/',$source,'" />';
                                    }
                                }
                                
                                else {
                                    echo'<div style="width:230px;height:20px;padding-top:15px;font-size:16px;text-align:center;font-weight:300;">No Photos Yet</div>';
                                }
                                
                                echo'
                                </a>
                            </div>';
                     
                    } //end of my groups for loop
                    
                echo'</div>'; //end featuredGroupsDiv
                
                   $mygroups = explode(",",$usergroups);
                   $nummygroups = count($mygroups);
                
                //My Groups Div
                echo'<div id="myGroupsDiv" style="margin-top:5px;">';
                    for($ii=0; $ii<$nummygroups - 1; $ii++) {
                        $groupinfo = mysql_query("SELECT * FROM groups WHERE id = $mygroups[$ii]");
                        $groupname = mysql_result($groupinfo,0,'name');
                        $members = mysql_result($groupinfo,0,'members');
                        $membersarray = explode(",",$members);
                        $numbermembers = count($membersarray) - 1;
                        $about = mysql_result($groupinfo,0,'about');
                        $shortabout = (strlen($about ) > 105) ? substr($about,0,100). " &#8230;" : $about;
                        $group_id = $mygroups[$ii];
                        if($group_id == "1") {
                            continue;
                        }
                        
                        //Grab group photos 
                        $groupphotosquery3 = mysql_query("SELECT * FROM groupnews WHERE group_id = '$group_id' ORDER BY id DESC");
                        $numphotonews = mysql_num_rows($groupphotosquery3);
                        $totalnumposts = 0;
                        $totalnumphotos = 0;
                        $imagesources = "";
                        for($iii=0; $iii<=$numphotonews; $iii++) {
                            $numphotos = mysql_result($groupphotosquery3,$iii,'photo');
                            $imagesources .= $numphotos;
                            $photoarray = explode(" ",$numphotos);
                            $numphotosinarray = count($photoarray);
                            $totalnumphotos += $numphotosinarray;
                            $type = mysql_result($groupphotosquery3,$iii,'type');
                            if($type == 'post') {
                                $totalnumposts += 1;
                            }
                        }
                        $imagesourcesArray = explode(" ",$imagesources);
                        $imagecount = count($imagesourcesArray);

                        echo'<div class="myGroupsBox">
                             <a style="color:#333;text-decoration:none;" href="groups.php?id=',$group_id,'">
                                <div style="width:220px;padding:4px;font-size:14px;font-weight:500;border-bottom:1px solid #aaa;">
                                    <img style="margin-top:-5px;padding:3px;width:12px;" src="graphics/groups_b.png" /> ',$groupname,'
                                    <br />
                                    <span style="font-size:13px;font-weight:300;"> 
                                        <img style="margin-top:-5px;padding:3px;width:12px;" src="graphics/user.png" /> ',$numbermembers,' members
                                        <img style="margin-top:-5px;padding:3px;width:12px;" src="graphics/comment_1.png" /> ',$totalnumposts,' posts 
                                    </span>
                                </div>';
                                
                                if($totalnumphotos > 2) {
                                    $count = 0;
                                    for($iii=0;$iii<$imagecount;$iii++) {
                                        if($count >= 6) {
                                            break;
                                        }
                                        $sourcequery = mysql_query("SELECT source FROM photos WHERE id = '$imagesourcesArray[$iii]'");
                                        $source = mysql_result($sourcequery,0,'source');
                                        $source = str_replace("userphotos/","userphotos/medthumbs/",$source);
                                        if(!$source) {
                                            continue;
                                        }
                                        elseif($source) {
                                            $count += 1; 
                                        }

                                        echo'<img style="width:73px;height:73px;padding:1px;padding-top:2px;" src="https://photorankr.com/',$source,'" />';
                                    }
                                }

                                
                                else {
                                    echo'<div style="width:230px;height:20px;padding-top:15px;font-size:16px;text-align:center;font-weight:300;">No Photos Yet</div>';
                                }
                                
                                echo'
                                </a>
                            </div>';
                     
                    } //end of my groups for loop
                    
                echo'</div>'; //end myGroupsDiv
                    
                echo'
            </div>
                            
            <!---Right Bar--->
            <div class="grid_6">
            <div class="groupsBar" style="width:255px;margin-left:20px;">
                <ul>
                    <li style="padding:0px;">
                        <form method="get" style="display:inline;">
                            <input type="hidden" value="search" name="view" />
                            <input type="text" style="padding:4px;width:150px;" name="searchterm" placeholder="Search all groups"/>
                        </form>
                    </li>
                    <li><a style="color:#333;" data-toggle="modal" data-backdrop="static" href="#createmodal">
                           <img style="margin-top:-5px;padding:3px;width:14px;" src="graphics/plus.png" /> Create </li>
                        </a>
                </ul>
            </div>';
            
            //No Search Term
            if($view == '') {
                echo'
                <div class="smallGroupsBody">
                    <div style="font-size:18px;font-weight:300;text-align:center;">
                        <img style="margin-top:20px;margin-left:15px;width:28px;padding-bottom:12px;" src="graphics/groups_b.png" /><br />Search All Groups
                    </div>
                </div>';
            }
            elseif($view == 'search') {
                $mygroups = explode(",",$usergroups);
                $nummygroups = count($mygroups);
                $groupinfo = mysql_query("SELECT * FROM groups WHERE concat(name,about) LIKE '%$searchterm%' ORDER BY views DESC LIMIT 10");
                $numfinds = mysql_num_rows($groupinfo);
                
                for($ii=0; $ii<$numfinds; $ii++) {
                        $groupname = mysql_result($groupinfo,0,'name');
                        $members = mysql_result($groupinfo,0,'members');
                        $membersarray = explode(",",$members);
                        $numbermembers = count($membersarray) - 1;
                        $about = mysql_result($groupinfo,0,'about');
                        $shortabout = (strlen($about ) > 105) ? substr($about,0,100). " &#8230;" : $about;
                        $group_id = mysql_result($groupinfo,0,'id');
                        
                        //Grab group photos 
                        $groupphotosquery = mysql_query("SELECT * FROM groupnews WHERE group_id = '$group_id' ORDER BY id DESC");
                        $numphotonews = mysql_num_rows($groupphotosquery);
                        $totalnumposts = 0;
                        $totalnumphotos = 0;
                        $imagesources = "";
                        for($iii=0; $iii<=$numphotonews; $iii++) {
                            $numphotos = mysql_result($groupphotosquery,$iii,'photo');
                            $imagesources .= $numphotos;
                            $photoarray = explode(" ",$numphotos);
                            $numphotosinarray = count($photoarray);
                            $totalnumphotos += $numphotosinarray;
                            $type = mysql_result($groupphotosquery,$iii,'type');
                            if($type == 'post') {
                                $totalnumposts += 1;
                            }
                        }
                        $imagesourcesArray = explode(" ",$imagesources);
                        $imagecount = count($imagesourcesArray);

                        echo'<div class="myGroupsSearchBox" style="margin-left:20px;">
                             <a style="color:#333;text-decoration:none;" href="groups.php?id=',$group_id,'">
                                <div style="width:220px;padding:4px;font-size:14px;font-weight:500;border-bottom:1px solid #aaa;">
                                    <img style="margin-top:-5px;padding:3px;width:12px;" src="graphics/groups_b.png" /> ',$groupname,'
                                    <br />
                                    <span style="font-size:13px;font-weight:300;"> 
                                        <img style="margin-top:-5px;padding:3px;width:12px;" src="graphics/user.png" /> ',$numbermembers,' members
                                        <img style="margin-top:-5px;padding:3px;width:12px;" src="graphics/comment_1.png" /> ',$totalnumposts,' posts 
                                    </span>
                                </div>';
                                
                                if($totalnumphotos > 2) {
                                    $count = 0;
                                    for($iii=0;$iii<$imagecount;$iii++) {
                                        if($count >= 6) {
                                            break;
                                        }
                                        $sourcequery = mysql_query("SELECT source FROM photos WHERE id = '$imagesourcesArray[$iii]'");
                                        $source = mysql_result($sourcequery,0,'source');
                                        $source = str_replace("userphotos/","userphotos/medthumbs/",$source);
                                        if(!$source) {
                                            continue;
                                        }
                                        elseif($source) {
                                            $count += 1; 
                                        }

                                        echo'<img style="width:86px;height:86px;padding:1px;padding-top:2px;" src="https://photorankr.com/',$source,'" />';
                                    }
                                }

                                
                                else {
                                    echo'<div style="width:230px;height:20px;padding-top:15px;font-size:16px;text-align:center;font-weight:300;">No Photos Yet</div>';
                                }
                                
                                echo'
                                </a>
                            </div>';
                     
                    } //end of all groups search for loop
            } //end groups all search view
                        
                echo'
            </div>
            
        </div>';               
                        
    } //end of groups portal interface
    
    
    //Internal Groups Interface
    if($groupid) {
    
        //Query a page view for this group 
        $pageview = mysql_query("UPDATE groups SET views = (views + 1) WHERE id = $groupid");
        
         //Group Information
        $groupinfoquery = mysql_query("SELECT * FROM groups WHERE id = $groupid");
        $owner = mysql_result($groupinfoquery,0,'group_owner');
        $groupname = mysql_result($groupinfoquery,0,'name');
        $members = mysql_result($groupinfoquery,0,'members');
        $membersarray = explode(",",$members);
        $numbermembers = count($membersarray) - 1;
        $about = mysql_result($groupinfoquery,0,'about');
        $shortabout = (strlen($about ) > 105) ? substr($about,0,100). " &#8230;" : $about;
        $coverphoto = mysql_result($groupinfoquery,0,'coverphoto');
        
        //Find owner matches
        $ownermatch=preg_match("/$email/",$owner);
        
        //Get # photos in group
        $groupphotosquery = mysql_query("SELECT * FROM groupnews WHERE group_id = '$groupid' ORDER BY id DESC");
        $numphotonews = mysql_num_rows($groupphotosquery);
        $groupnewsquery = mysql_query("SELECT * FROM groupnews WHERE group_id = $groupid AND type IN ('post') ORDER BY id DESC");
        $numgroupnews = mysql_num_rows($groupnewsquery);
        $totalnumphotos = 0;
        $totalnumposts = 0;
        $totalnumcomments = 0;
        $imagesources = '';
        for($ii=0; $ii<=$numphotonews; $ii++) {
            $numphotos = mysql_result($groupphotosquery,$ii,'photo');
            $imagesources .= $numphotos;
            $photoarray = explode(" ",$numphotos);
            $numphotosinarray = count($photoarray) - 1;
            $totalnumphotos += $numphotosinarray;
            $type = mysql_result($groupphotosquery,$ii,'type');
            if($type == 'comment') {
                $totalnumcomments += 1;
            }
            if($type == 'post') {
                $totalnumposts += 1;
            }
        }
        
    echo'
    <div id="coverImage">';
		             
       $imagesourcesArray = explode(" ",$imagesources);
       $imagecount = count($imagesourcesArray);
    
    if($imagecount > 2) {   
    
       for($iii=0;$iii<$imagecount;$iii++) {
            if($count >= 3) {
                break;
            }
            $sourcequery = mysql_query("SELECT source FROM photos WHERE id = '$imagesourcesArray[$iii]'");
            $source = mysql_result($sourcequery,0,'source');
            $source = str_replace("userphotos/","userphotos/medthumbs/",$source);

            if($source) {
                $count += 1; 
            }
            elseif(!$source) {
                continue;
            }
            echo'<div class="imgContainerG">
                    <img src="https://photorankr.com/',$source,'"/>
                </div>';

       }
       
    }
       
       elseif($imagecount < 3) {
       
            echo'<div class="imgContainerG">
                    <img src="graphics/noPhotosYet.png"/>
                </div>';       
       }
        
        echo'
		<div class="groupName"'; if($imagecount < 3) {echo'style="height:400px;"';} echo'>
            <div style="width:370px;overflow:hidden;height:50px;clear:both;">
                <img src="graphics/groups_b.png"/>
                <header> ',$groupname,' </header>
            </div>
			<!--<ul>
				<li> <img src="graphics/captureDate.png"/> Created: <br /> 12/25/12 </li>
			</ul>-->
			<ul id="groupStats1">
				<li> <img src="graphics/network_i.png"/> ',$numbermembers,' Members </li>
				<li> <img src="graphics/camera.png"/> ',$imagecount - 1,' Photos </li>
			</ul>
			<ul  id="groupStats2">
				<li> <img src="graphics/collection_i.png"/> ',$totalnumposts,' Posts </li>
				<li> <img src="graphics/blog_b.png"/> ',$totalnumcomments,' Comments </li>
			</ul>
			<header style=" float: none;
    font-size: 16px;
    display: block;border:none;margin-left:45px;"> About </header>
			<p> ',$shortabout,'</p>
		</div>
	</div>
    
	<div id="headerContainer">
	<div id="GroupNav">
			<ul>';
				if($ownermatch) {
                        echo'<a href="?id=',$groupid,'&gv=settings" '; if($groupview == 'settings') {echo'style="background-color:rgba(0,0,0, .5);border-left: 1px solid #666;"';} echo'><li>Admin Panel</li></a>';
                }
                echo'
				<a href="?id=',$groupid,'&gv=photos" '; if($groupview == 'photos') {echo'style="background-color:rgba(0,0,0, .5);border-left: 1px solid #666;"';} echo'><li> Photos </li></a>
				<a href="?id=',$groupid,'&gv=members" '; if($groupview == 'members') {echo'style="background-color:rgba(0,0,0, .5);border-left: 1px solid #666;"';} echo'><li> Members </li></a>
                <a href="?id=',$groupid,'&gv=about" '; if($groupview == 'about') {echo'style="background-color:rgba(0,0,0, .5);border-left: 1px solid #666;"';} echo'><li> About </li></a>
				<a href="?id=',$groupid,'" '; if($groupview == '') {echo'style="background-color:rgba(0,0,0, .5);border-left: 1px solid #666;"';} else{echo'style="border-left: 1px solid #666;"';} echo'><li> Home </li></a>
                
                <form method="get">
                    <input type="hidden" value="',$groupid,'" name="id" />
					<input type="text" name="searchterm" placeholder="search this group"/>
					<img src="graphics/search_i.png"/>
				</form>';
                
                  if(!$match) {
                    echo'
                    <div style="margin-top:-10px;">
                        <a href="groups.php?id=',$groupid,'&action=join"><button> <span> Join </span> + </button></a>
                    </div>';
                    }
                    elseif($match && $email) {
                    echo'
                    <div style="margin-top:-10px;">
                        <a href="groups.php?id=',$groupid,'&action=leave"><button> <span> Leave </span> – </button></a>
                    </div>';
                    }
                
                echo'
            </ul>
		</div>
	</div>';
    
    echo'
	<div class="container groupsColBig" style="width:1000px;overflow:hidden;">
	<div id="colLeftG">
        <div id="recentActivity">';
        if($numgroupnews>0) {
			echo'<header> Recent Activity </header>';
        }
                for($iii=0;$iii<$numgroupnews && $iii < 6; $iii++) {
                    $type = mysql_result($groupphotosquery,$iii,'type');
                    $firstname = mysql_result($groupphotosquery,$iii,'firstname');
                    $lastname = mysql_result($groupphotosquery,$iii,'lastname');
                    $fullname = $firstname ." ". $lastname;
                    $time = mysql_result($groupphotosquery,$iii,'time');
                    $emailaddress = mysql_result($groupphotosquery,$iii,'emailaddress');
                    $comment = mysql_result($groupphotosquery,$iii,'comment');
                    
                    if($type == 'comment') {
                        echo'<div class="activityBlock">
                                 <header><img style="width:13px;padding:3px;margin-top:-2px;" src="graphics/comment_1.png" />',$fullname,' left a comment</header> 
                                    ',$comment,'
                             </div>';    
                    }
                    
                    elseif($type == 'join') {
                        echo'<div class="activityBlock">
                                 <header><img style="width:13px;padding:3px;margin-top:-2px;" src="graphics/plus.png" />',$fullname,' joined this group</header> 
                             </div>'; 
                    }
                    
                    elseif($type == 'post') {
                        echo'<div class="activityBlock">
                                 <header><img style="width:13px;padding:3px;margin-top:-2px;" src="graphics/list 1.png" />',$fullname,' posted in the group</header> 
                                    ',$comment,'
                             </div>'; 
                    }
                }
            
            echo'
		</div>
		<div id="groupMembers">
			<header> Group Members | ',$numbermembers,' </header>';
                
                for($ii=0; $ii < $numbermembers  && $ii < 12; $ii++) {
                    $getprofilepic = mysql_query("SELECT profilepic FROM userinfo WHERE user_id = $membersarray[$ii]");
                    $profilepic = mysql_result($getprofilepic,0,'profilepic');
                    echo'<a href="viewprofile.php?u=',$membersarray[$ii],'"><img src="../',$profilepic,'" style="width:80px;height:80px;" /></a>';
                }
               
                echo'
			<!--<div class="seeMore">
				<img src="graphics/seeMore.png"/>
			</div>-->


		</div>
		<div id="groupAdmins">';
            $ownersarray = explode(" ", $owner);
            $numowners = count($ownersarray);
                echo'<header> Admins | ',$numowners,' </header>';
            for($iii=0; $iii<$numowners; $iii++) {
                $owneremail = trim($ownersarray[$iii]);
                $ownerinfoquery = mysql_query("SELECT profilepic, user_id FROM userinfo WHERE emailaddress = '$owneremail'");
                $profilepic = mysql_result($ownerinfoquery,0,'profilepic');
                $ownerid=  mysql_result($ownerinfoquery,0,'user_id');
                
                echo'<a href="viewprofile.php?u=',$ownerid,'"><img src="https://photorankr.com/',$profilepic,'"/></a>';
            }
            echo'
        </div>		
	</div>';
    
    
    /*------------------------ GROUP VIEW == '' ----------------------*/
	if($groupview == '' && !$searchterm) {
    
    echo'
	<div id="colRightG" style="min-height:800px;">';
    
    ?>    
    
    <script type="text/javascript">
            //Display textarea
        $(function() 
        {
       
         $("#comment").focus(function()
        {
        $(this).animate({"height": "85px",}, "fast" );
        $("#button_block").slideDown("fast");
        return false;
        });
        
        });
        </script>
        
        <style type="text/css">
            #hiddenComments {
                display:none;
            }
            #commentOption {
                width:500px;
                float:left;
                font-size:13px;
                padding:0px 0px 0px 20px;
                font-weight:700;
                cursor:pointer;
            }
            #button_block {
                display:none;
            }
            #button {
                background-color:#33C33C;
                color:#ffffff;
                font-size:13px;
                font-weight:bold;
                padding:3px;
                margin-left:40px;
            }
            #addphotos {
                background-color:#33C33C;
                color:#ffffff;
                font-size:13px;
                font-weight:bold;
                padding:3px;
                margin-left:40px;
            }
        </style>
	
    <?php
    
    echo'
    
         <!----------------------------Comment Box------------------------>';
         if(!$match) {
             echo'
                <div style="width:555px;position:relative;top:10px;margin-left:10px;overflow:hidden;height:30px;background-color:rgb(250,250,250);font-size:18px;font-weight:300;text-align:center;padding:10px 0px 6px 0px;-webkit-border-radius: 2px;-moz-border-radius: 2px;border-radius: 2px;border:1px solid #aaa;-webkit-box-shadow: 0 1px 3px rgba(0, 0, 0, 0.25), inset 0 -1px 1px #aaa;-moz-box-shadow: 0 1px 3px rgba(0, 0, 0, 0.25), inset 0 -1px 1px #aaa;box-shadow: 0 1px 3px rgba(0, 0, 0, 0.25), inset 0 -1px 1px #aaa;">
                    Please join this group to add a post
                </div>';
         }
         
         elseif($match && $email) {
            echo'
                <a style="color:#333;text-decoration:none;" data-toggle="modal" data-backdrop="static" href="#photomodal">
                     <div style="width:555px;position:relative;top:10px;margin-left:10px;overflow:hidden;height:30px;background-color:rgb(250,250,250);font-size:18px;font-weight:300;text-align:center;padding:10px 0px 6px 0px;-webkit-border-radius: 2px;-moz-border-radius: 2px;border-radius: 2px;border:1px solid #aaa;-webkit-box-shadow: 0 1px 3px rgba(0, 0, 0, 0.25), inset 0 -1px 1px #aaa;-moz-box-shadow: 0 1px 3px rgba(0, 0, 0, 0.25), inset 0 -1px 1px #aaa;box-shadow: 0 1px 3px rgba(0, 0, 0, 0.25), inset 0 -1px 1px #aaa;">
                        Add a Discussion
                     </div>
                </a>';
        }
        
        echo'
         <!---------------------------- News Feed ------------------------>
                    <div id="thepics" style="position:relative;width:600px;margin-top:30px;">
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
                    
                    //All Previous Comments
                    $grabcomments = mysql_query("SELECT * FROM groupcomments WHERE post_id = '$postid' ORDER BY id DESC");
                    $numcomments = mysql_num_rows($grabcomments);
            ?>
            
    <script type="text/javascript">
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
var postid = '<?php echo $postid; ?>';
var comment = $("#commentBox<?php echo $postid; ?>").val();
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

        //Display textarea
        $(function() 
        {
        $("#comment<?php echo $postid; ?>").click(function()
        {
        $("#commentBox<?php echo $postid; ?>").animate({"height": "85px",}, "fast" );
        $("#button_block<?php echo $postid; ?>").slideDown("fast");
        jQuery("#commentform<?php echo $postid; ?>").toggle();
        return false;
        });        
        });
 
    jQuery(document).ready(function(){
        jQuery("#showStats<?php echo $postid; ?>").live("click", function(event) {        
            jQuery(".hiddenStats<?php echo $postid; ?>").toggle();
        });
    });
    </script>
        
        <style type="text/css">
        #commentform<?php echo $postid; ?> {
            display:none;
            padding:20px 0;
        }
        #commentOption<?php echo $postid; ?> {
            font-size:13px;
            padding-top:10px;
            font-weight:700;
            cursor:pointer;
        }
        #button_block<?php echo $postid; ?> {
            display:none;
        }
        #button<?php echo $postid; ?> {
            background-color:#33C33C;
            color:#ffffff;
            font-size:13px;
            font-weight:bold;
            padding:3px;
            margin-left:40px;
        }
    </style>
    
        <?php
                
        echo'<div class="grid_16">
         <!--Profile Picture-->
            <div class="newsBlock" style="margin-left:0px;margin-top:0px;">
                <ul>
                    <li>
                        <a name="',$postid,'" href="viewprofile.php?u=',$commenterid,'">
                        <img id="newsProfilePic" src="https://photorankr.com/',$commenterpic,'" />
                        </a>
                    <li id="comment',$postid,'"><img src="graphics/comment_1.png"></li>
                </ul>
            </div>
         
         <!--Content Box-->
         <div class="newsContainer" style="margin-left:3px;">
            <div class="newsTriangle"></div>
                <div class="newsItem">
                    <!--Top Controls-->
                    <div class="newsControls">
                        <div class="newsName">
                            <a href="viewprofile.php?u=',$commenterid,'">',$commentername,'</a>
                        </div>
                        <div class="newsTools">
                            <span id="time">',$time,'</span>';
                            if($email == $owner) {
                                echo'<span style="float:right;color:#888;font-weight:700;font-size:14px;padding:3px;"><a href="groups.php?id=',$groupid,'&post=',$postid,'&action=delete">X</a></span>';
                            }
                            echo'
                        </div>
                    </div>
                    <!--Content-->
                    <div class="newsContent" style="padding-bottom:10px;">';
                        
                        if($comment) {echo'
                        <div style="width:400px;padding:25px;font-size:15px;font-weight:300;line-height:20px;">
                            ',$comment,'
                        </div>';
                        }
                    
                        if($numberphotos == 1) {
                            for($ii=0; $ii < 1; $ii++) {
                                $getphotosource = mysql_query("SELECT id,source FROM photos WHERE id = $photosarray[$ii]");
                                $photosource = mysql_result($getphotosource,0,'source');
                                $medphoto = str_replace("userphotos/","userphotos/medthumbs/",$photosource);
                                $photoid = mysql_result($getphotosource,0,'id');
                                list($width, $height) = getimagesize($photosource);
                                $heightls = $height / 2.5;
                                $widthls = $width / 2.5;
                                                        
                                echo'<a href="fullsize.php?imageid=',$photoid,'"><div style="width:440px;padding:10px;margin-left:4%;overflow:hidden;"><img src="',$medphoto,'" width="',$widthls,'" height="',$heightls,'" /></div></a>';
                            }
                        }
                       
                       elseif($numberphotos == 2) {
                            echo'<div style="width:505px;padding:10px;margin-left:10px;overflow:hidden;">';
                            for($ii=0; $ii < 2; $ii++) {
                                $getphotosource = mysql_query("SELECT id,source FROM photos WHERE id = $photosarray[$ii]");
                                $photosource = mysql_result($getphotosource,0,'source');
                                $photoid = mysql_result($getphotosource,0,'id');
                                $medphoto = str_replace("userphotos/","userphotos/medthumbs/",$photosource);
                                $photoid = mysql_result($getphotosource,0,'id');
                                list($width, $height) = getimagesize($photosource);
                                $heightls = $height / 3.5;
                                $widthls = $width / 3.5;
                                
                                echo'<a href="fullsize.php?imageid=',$photoid,'"><div style="float:left;height:215px;max-width:215px;padding:3px;"><img style="height:245px;" src="',$medphoto,'" width="',$widthls,'" /></div></a>';
                            }
                            echo'</div>';
                       }
                       
                       elseif($numberphotos == 3) {
                              echo'<div style="width:505px;padding:10px;margin-left:10px;overflow:hidden;">';
                            for($ii=0; $ii < 3; $ii++) {
                                $getphotosource = mysql_query("SELECT id,source FROM photos WHERE id = $photosarray[$ii]");
                                $photosource = mysql_result($getphotosource,0,'source');
                                $photoid = mysql_result($getphotosource,0,'id');
                                $medphoto = str_replace("userphotos/","userphotos/medthumbs/",$photosource);
                                $photoid = mysql_result($getphotosource,0,'id');
                                list($width, $height) = getimagesize($photosource);
                                $heightls = $height / 3.5;
                                $widthls = $width / 3.5;
                                
                                echo'<a href="fullsize.php?imageid=',$photoid,'"><div style="float:left;height:160px;max-width:140px;padding:3px;"><img style="height:160px;" src="',$medphoto,'" width="',$widthls,'" /></div></a>';
                            }
                            echo'</div>';
                       }
                       
                       elseif($numberphotos == 4) {
                              echo'<div style="width:505px;padding:10px;margin-left:10px;overflow:hidden;">';
                            for($ii=0; $ii < 4; $ii++) {
                                $getphotosource = mysql_query("SELECT id,source FROM photos WHERE id = $photosarray[$ii]");
                                $photosource = mysql_result($getphotosource,0,'source');
                                $photoid = mysql_result($getphotosource,0,'id');
                                $medphoto = str_replace("userphotos/","userphotos/medthumbs/",$photosource);
                                $photoid = mysql_result($getphotosource,0,'id');
                                list($width, $height) = getimagesize($photosource);
                                $heightls = $height / 3.5;
                                $widthls = $width / 3.5;
                                
                                echo'<a href="fullsize.php?imageid=',$photoid,'"><div style="float:left;height:215px;max-width:215px;padding:3px;"><img style="height:215px;" src="',$medphoto,'" width="',$widthls,'" /></div></a>';
                            }
                            echo'</div>';
                       }
                       
                        echo'
                    </div>
                    
                    <!--Comment Box-->
                    <div class="postCommentBox">
                         <form action="#" id="commentform',$postid,'" method="post" style="margin-top:5px;padding-bottom:5px;"> 
                        <img style="float:left;" src="https://photorankr.com/',$sessionpic,'" height="30" width="30"  />
                        <textarea id="commentBox',$postid,'" style="resize:none;margin-left:5px;width:395px;height:20px;" placeholder="Leave feedback for ',$commentername,'&#8230;"></textarea>
                        <div id="button_block',$postid,'">
                         <input type="submit" id="button',$postid,'" class="btn btn-success" value=" Comment "/>
                        </div>
                        </form>
                    </div>
                    
                    <!--AJAX COMMENTS-->
                        <ol id="update',$postid,'" class="timeline">
                        </ol>
                    
                    <!--Previous Comments-->
                    <div class="previousComments" style="width:480px;">
                        <ul class="indPrevComment" style="padding:0px;">';
                                
                             for($iii = 0; $iii < $numcomments; $iii++) {
                                $prevcomment = mysql_result($grabcomments,$iii,'comment');
                                $commentid = mysql_result($grabcomments,$iii,'id');
                                $commenttime = mysql_result($grabcomments,$iii,'time');
                                $commenttime = converttime($commenttime);
                                $commenteremail = mysql_result($grabcomments,$iii,'commenter');
                                $commenterinfo = mysql_query("SELECT user_id,firstname,lastname,profilepic,reputation FROM userinfo WHERE emailaddress = '$commenteremail'");
                                $commentername = mysql_result($commenterinfo,0,'firstname') ." ". mysql_result($commenterinfo,0,'lastname');
                                $commenterid = mysql_result($commenterinfo,0,'user_id');
                                $commenterpic = mysql_result($commenterinfo,0,'profilepic');            
                                $commenterrep = number_format(mysql_result($commenterinfo,0,'reputation'),2);

                                echo'<li style="overflow:hidden;"> 
                                        <div style="width:35px;float:left;"><img src="https://photorankr.com/',$commenterpic,'" height="35" width="35" /></div>
                                        <div style="width:420px;float:left;" id="commenterName" style="float:left;"><a href="viewprofile.php?u=',$commenterid,'">',$commentername,'</a>
                                        <div style="float:right">',$commenttime,'</div>
                                        <div id="commentText">
                                            ',$prevcomment,'
                                        </div>
                                        </div>
                                     
                                     </li>';
                        
                            }
                            
                            echo'
                        </ul>
                    </div>
         <!--End Content Box-->
         </div>
    </div>
</div>';
                            
                }
                                
                echo'</div>
                     </div>
                    
	</div> <!--end of right column-->';
        
    } //end groups view == ''       
    
    elseif($groupview == 'about' && !$searchterm) {
    
        echo'<div id="colRightG">
		<div id="memberContainer">
        
			<header> About the group </header>
            
                    ',$about,'
                                   
		</div>
	</div>';
    
    } //end of members view
    
    elseif($groupview == 'members' && !$searchterm) {
    
        echo'<div id="colRightG" style="min-height:600px;">
		<div id="memberContainer">
        
			<header> Members | ',$numbermembers,' </header>';
            
            for($iii=0; $iii<$numbermembers; $iii++) {
                $memberinfo = mysql_query("SELECT * FROM userinfo WHERE user_id = '$membersarray[$iii]'");
                $firstname = mysql_result($memberinfo,0,'firstname'); 
                $lastname = mysql_result($memberinfo,0,'lastname'); 
                $fullname = $firstname ." ". $lastname; 
                $reputation = number_format(mysql_result($memberinfo,0,'reputation'),1); 
                $memberemail = mysql_result($memberinfo,0,'emailaddress'); 
                $profilepic = mysql_result($memberinfo,0,'profilepic');
                $memberid = mysql_result($memberinfo,0,'user_id'); 
                
                $nummemberphotosquery = mysql_query("SELECT id FROM photos WHERE emailaddress = '$memberemail'");
                $nummemberphotos = mysql_num_rows($nummemberphotosquery);
                
                $joinquery = mysql_query("SELECT time FROM groupnews WHERE commenter = '$memberemail' AND type = 'join'");
                $jointime = mysql_result($joinquery,0,'time');
                $jointime = converttime($jointime);
                
                echo'
                <div class="memberBlock"> 
                    <img src="https://photorankr.com/',$profilepic,'"/>
                    <div class="topHalf">
                        <a href="viewprofile.php?u=',$memberid,'"><header> ',$fullname,' </header></a>
                        <p> Joined ',$jointime,' </p>
                        <button id="follow" style="width:50px;height:18px;padding-top:1px;"> Follow </button>
                    </div>
                    <div class="bottomHalf">
                        <!--<header><img src="graphics/camera.png"/> ',$nummemberphotos,' </header>-->

                        <header> <img src="graphics/rep_i.png"/> ',$reputation,' </header>
                    </div>
                </div>';
            }
            
            echo' 
		</div>
	</div>';
    
    } //end of members view
    
    elseif($groupview == '' && $searchterm) {
    
    
    }
    
    elseif($groupview == 'settings' && $ownermatch && $email) {
                   
          echo'<div id="colRightG">
                    <div id="memberContainer" style="width:590px;margin-left:0px;">
                    <header> Group Settings </header>';
                    
                    if($action == 'adminssaved') {
                        echo'<div style="width:550px;padding:10px;font-weight:300;font-size:20px;text-align:center;">Group Admins Added</div>';         
                    }
                    
                    if($action == 'invitessent') {
                        echo'<div style="width:550px;padding:10px;font-weight:300;font-size:20px;text-align:center;">Group Invites Sent</div>';         
                    }
                    
                    echo'
                    <div style="margin-top:10px;">
                        <div class="settingHead" style="width:560px;padding:5px;background-color:rgb(240,240,240);height:20px;">
                            <header>Add Group Administrators</header>
                        </div>
                        <div class="settingDiv uiScrollableAreaTrack invisible_elem" style="width:560px;padding:5px;height:320px;overflow-y:scroll;">';

                                $followresult = mysql_query("SELECT following FROM userinfo WHERE emailaddress = '$email'");   
                                $followlist = mysql_result($followresult, 0, "following");
                                $followingarray = explode(", ",$followlist);
                                $numfollowing = count($followingarray);
                                
                                echo'<form action="groups.php?id=',$groupid,'&gv=settings&action=addadmins" method="POST">';
                                for($iii=0;$iii<$numfollowing;$iii++) {
                                    $getpicture = mysql_query("SELECT profilepic,emailaddress,user_id,firstname,lastname,reputation FROM userinfo WHERE emailaddress = $followingarray[$iii]");
                                    $profilepic = mysql_result($getpicture,0,'profilepic');
                                    $personid = mysql_result($getpicture,0,'user_id');
                                    $personemail = mysql_result($getpicture,0,'emailaddress');
                                    $name = mysql_result($getpicture,0,'firstname') ." ". mysql_result($getpicture,0,'lastname');
                                    $reputation = mysql_result($getpicture,0,'reputation');
                                    
                                    if($personid == '' || $reputation < 15) {
                                        continue;
                                    }
                                    
                                    echo'<div id="settingBox">
                                            <img style="float:left;width:60px;height:60px;" src="https://photorankr.com/',$profilepic,'" />
                                                <div style="width:100px;float:left;overflow:hidden;font-size:13px;font-weight:300;padding:3px;">
                                                    <input type="checkbox" name="addAdmins[]" value="',$personemail,'" /><a href="viewprofile.php?u=',$personid,'">',$name,'</a>
                                                </div>
                                         </div>';
                                }
                            echo'
                            <div style="width:550px;height:40px;padding:5px;">
                                <input style="float:right;padding:6px;width:100%;margin:5px;" type="submit" class="btn btn-success" value="Add Administrators">
                            </div>
                            </form>
                        </div>
                    </div>
                    
                    <div style="margin-top:10px;">
                        <div class="settingHead" style="width:560px;padding:5px;background-color:rgb(240,240,240);height:20px;">
                            <header>Invite Your Followers</header>
                        </div>
                        <div class="settingDiv uiScrollableAreaTrack invisible_elem" style="width:560px;padding:5px;height:320px;overflow-y:scroll;">';

                                $followresult = mysql_query("SELECT following FROM userinfo WHERE emailaddress = '$email'");   
                                $followlist = mysql_result($followresult, 0, "following");
                                $followingarray = explode(", ",$followlist);
                                $numfollowing = count($followingarray);
                                
                                echo'<form action="groups.php?id=',$groupid,'&gv=settings&action=invite" method="POST">';
                                for($iii=0;$iii<$numfollowing;$iii++) {
                                    $getpicture = mysql_query("SELECT profilepic,emailaddress,user_id,firstname,lastname,reputation FROM userinfo WHERE emailaddress = $followingarray[$iii]");
                                    $profilepic = mysql_result($getpicture,0,'profilepic');
                                    $personid = mysql_result($getpicture,0,'user_id');
                                    $name = mysql_result($getpicture,0,'firstname') ." ". mysql_result($getpicture,0,'lastname');
                                    $reputation = mysql_result($getpicture,0,'reputation');
                                    $personemail = mysql_result($getpicture,0,'emailaddress');
                                    
                                    if($personid == '' || $reputation < 15) {
                                        continue;
                                    }
                                    
                                    echo'<div id="settingBox">
                                            <img style="float:left;width:60px;height:60px;" src="https://photorankr.com/',$profilepic,'" />
                                                <div style="width:100px;float:left;overflow:hidden;font-size:13px;font-weight:300;padding:3px;">
                                                    <input type="checkbox" name="invite[]" value="',$personemail,'" /><a href="viewprofile.php?u=',$personid,'">',$name,'</a>
                                                </div>
                                         </div>';
                                }
                            echo'
                            <div style="width:550px;height:40px;padding:5px;">
                                <input style="float:right;padding:6px;width:100%;margin:5px;" type="submit" class="btn btn-success" value="Invite These Photographers">
                            </div>
                            </form>
                        </div>
                    </div>

                    
                    <!----<div style="margin-top:10px;">
                        <div class="settingHead" style="width:560px;padding:5px;background-color:rgb(240,240,240);height:20px;">
                            <header>Ban Photographers</header>
                        </div>
                        <div class="settingDiv uiScrollableAreaTrack invisible_elem" style="width:560px;padding:5px;height:320px;overflow-y:scroll;">';

                                $followresult = mysql_query("SELECT following FROM userinfo WHERE emailaddress = '$email'");   
                                $followlist = mysql_result($followresult, 0, "following");
                                $followingarray = explode(", ",$followlist);
                                $numfollowing = count($followingarray);
                                
                                echo'<form action="" method="POST">';
                                for($ii=0; $ii < $numbermembers  && $ii < 12; $ii++) {
                                    $getprofilepic = mysql_query("SELECT profilepic FROM userinfo WHERE user_id = $membersarray[$ii]");
                                    $profilepic = mysql_result($getprofilepic,0,'profilepic');
                                    
                                    echo'<div id="settingBox">
                                            <img style="float:left;width:60px;height:60px;" src="https://photorankr.com/',$profilepic,'" />
                                                <div style="width:100px;float:left;overflow:hidden;font-size:13px;font-weight:300;padding:3px;">
                                                    <input type="checkbox" name="addAdmins[]" value="',$personid,'" /><a href="viewprofile.php?u=',$membersarray[$ii],'">',$name,'</a>
                                                </div>
                                         </div>';
                                }
                            echo'
                            <div style="width:550px;height:40px;padding:5px;">
                                <input style="float:right;padding:6px;width:100%;margin:5px;" type="submit" class="btn btn-success" value="Ban these Photographers">
                            </div>
                            </form>
                        </div>
                    </div>

                    
                    </div>
                </div>---->';
    
    }
    
    elseif($groupview == 'photos' && !$searchterm) {
    
       $imagesourcesArray = explode(" ",$imagesources);
       $imagecount = count($imagesourcesArray);
       
       echo'<div id="colRightG">
            <div id="memberContainer" style="width:590px;margin-left:0px;">
            
            <header> Photos | ',$imagecount,' </header>';
       
       for($iii=0;$iii<$imagecount;$iii++) {
            $sourcequery = mysql_query("SELECT source,id,caption,points,votes FROM photos WHERE id = '$imagesourcesArray[$iii]'");
            $source = mysql_result($sourcequery,0,'source');
            $source = str_replace("userphotos/","userphotos/medthumbs/",$source);
            $caption = mysql_result($sourcequery, 0, "caption");
            $caption = (strlen($caption) > 28) ? substr($caption,0,25). " &#8230;" : $caption;
            $points = mysql_result($sourcequery, 0, "points");
            $votes = mysql_result($sourcequery, 0, "votes");
            $score = number_format(($points/$votes),2);
            $imageid = mysql_result($sourcequery,0,'id');
            if(!$source) {
                continue;
            }
            echo'<div style="overflow:hidden;width:185px;height:185px;padding:3px;float:left;">
                        <a href="fullsize.php?imageid=',$imageid,'">
                        <img style="min-width:190px;min-height:190px;" src="https://photorankr.com/',$source,'"/></a>
                </div>';

       }
        
       echo'</div>
            </div>';
    
    } //end of photos view
    
} //end of internal groups interface
    
//ADD PHOTOS MODAL
echo'<div class="modal hide fade" id="photomodal" style="overflow:hidden;border:5px solid rgba(102,102,102,.8);z-index:100000;">

<div class="modal-header" style="background-color:rgba(234,234,234,.9);color:#fff;">
<a style="float:right" class="btn btn-success" data-dismiss="modal">Close</a>
<img style="margin-top:-4px;" src="graphics/aperture_dark.png" width="30" />&nbsp;&nbsp;<span style="font-size:18pxfont-weight:300;;color:#333">Add up to 4 photos to your post:</span>
  </div>
  <div modal-body" style="width:600px;">

<div id="content" style="font-size:16px;width:550px;height:450px;overflow-x:hidden;">';

if($email != '') {
echo'
<div style="float:left;width:440px;margin-left:15px;margin-top:10px;overflow-y:scroll;overflow-x:hidden;">

<form action="groupupload.php" method="post">
    <div style="font-size:14px;margin-top:20px;">
    <input type="hidden" name="group_id" value="',$groupid,'" />
    <textarea id="addphoto" type="text" style="padding:5px;width:380px;height:60px;" name="comment" placeholder="Leave something to talk about&#8230;"></textarea>
    </div>
    <br /><br />';
    $allusersphotos = "SELECT * FROM photos WHERE emailaddress = '$email' ORDER BY id DESC";
    $allusersphotosquery = mysql_query($allusersphotos);
    $usernumphotos = mysql_num_rows($allusersphotosquery);
    
    for($iii = 0; $iii < $usernumphotos; $iii++) {
        $userphotosource = mysql_result($allusersphotosquery, $iii, "source");
        $userphotosource = str_replace("userphotos/","http://photorankr.com/userphotos/", $userphotosource);
        $userphotosid = mysql_result($allusersphotosquery, $iii, "id");
        $userphotosset[$iii] = mysql_result($allusersphotosquery, $iii, "sets");
        $userphotoscaption[$iii] = mysql_result($allusersphotosquery, $iii, "caption");
        $newsource = str_replace("userphotos/","userphotos/thumbs/", $userphotosource);
        
        echo'<div style="padding:3px;clear:both;">
                <img style="float:left;width:100px;height:100px;" src="',$newsource,'" />
                    <div class="commentTriangle" style="margin-top:-10px;"></div>
                    <div style="width:275px;float:left;padding-left:10px;height:75px;margin-top:25px;border-bottom:1px solid #aaa;font-size:15px;font-weight:300;">
                        <input type="checkbox" name="addthese[]" value="',$userphotosid,'" />  ',$userphotoscaption[$iii],'                
                </div>
            </div>'; 
    
    } //end of for loop
    
    
    echo'
    </span>
    <br />
    <br />';
    }
    
    else {
    echo'<div style="text-align:center;margin-top:100px;"><b>Please login or register to upload</b></div>';
    }
    
    echo'
    </div>
    
    <div style="width:100px;float:left;position:fixed;margin-left:425px;">
        <img style="border: 1px solid black;margin-left:10px;margin-top:10px;" src="https://photorankr.com/',$sessionpic,'" 
height="100px" width="100px" />
        <button style="margin-top:10px;background-color:#33C33C;width:80px;margin-left:20px;" class="btn btn-success" type="submit">Submit</button>
        </form>
    </div>
    
    </div>
    </div>';

?>


</body>
 <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript">

//Swap out myGroupsDiv and featuredGroupsDiv
jQuery(document).ready(function(){
     jQuery("#featuredGroups").live("click", function(event) {
         jQuery("#featuredGroupsDiv").show();
         jQuery("#myGroupsDiv").hide();
    });
     jQuery("#myGroups").live("click", function(event) {
         jQuery("#myGroupsDiv").show();
         jQuery("#featuredGroupsDiv").hide();
    });
});

(function(){

	var $header = $("#GroupNav");
	var HeaderOffset = $header.position().top;
	$("#headerContainer").css({ height: $header.height() });

$("#Main").scroll(function() {
    if($(this).scrollTop() > HeaderOffset) {
        $header.addClass("fixedTop");
    } else {
        $header.removeClass("fixedTop");
    }
});
	
})();

</script>
</html>