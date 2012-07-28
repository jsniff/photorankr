<?php

//connect to the database
require "db_connection.php";
require "functionscampaigns3.php"; 
    // if login form has been submitted
    if (htmlentities($_GET['action']) == "login") { 
        login();
    }
    else if(htmlentities($_GET['action']) == "logout") { 
        logout();
    }

//start the session
session_start();

//find the current time
$currenttime = time();

//find out which view they are looking at
$view = htmlentities($_GET['view']);

?>
<!DOCTYPE html>
<head>
 <meta name="description" content="View and edit your campaigns">
 <meta name="keywords" content="campaigns, my campaigns, PhotoRankr,"<
 <meta name="author" content="The PhotoRankr Team">
	<title>View all of the campaigns on PhotoRankr</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrapnew.css" />
    <link rel="stylesheet" href="css/reset.css" type="text/css" />
    <link rel="stylesheet" href="css/text.css" type="text/css" />
    <link rel="stylesheet" href="css/960_24.css" type="text/css" />
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
  	<script src="js/bootstrap.js" type="text/javascript"></script>
  	<script src="js/bootstrap-dropdown.js" type="text/javascript"></script>
  	<script src="js/bootstrap-collapse.js" type="text/javascript"></script>
  	<link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>
  
  <!--Navbar Dropdowns-->
  	<script type="text/javascript">
 	 $(function() {
  	// Setup drop down menu
  	$('.dropdown-toggle').dropdown();
  	// Fix input element click problem
  	$('.dropdown input, .dropdown label').click(function(e) {
    e.stopPropagation();
 		 });
	});
    </script>


  <style type="text/css">


 .statoverlay {
opacity:1;
filter:alpha(opacity=40);
z-index:1;
transition: opacity .5s;
-moz-transition: opacity .5s;
-webkit-transition: opacity .5s;
-o-transition: opacity .5s;
}
            
.statoverlay:hover {
opacity:.7;
}                

.item {
  margin: 10px;
  float: left;
  border: 2px solid transparent;
}

.item:hover {
  margin: 10px;
  float: left;
  border: 2px solid black;
}

</style>
</head>

<body style="overflow-x:hidden; background-color: #eeeff3;">

<?php navbarnew(); ?>

	<div id="container" class="container_24">
		<div class="grid_24 pull_2" style="width: 1140px;top:10px;">
<?php

if($_SESSION['loggedin'] != 2) {
	mysql_close();
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=campaignnewuser.php">';
	exit();	
}

//start the session
session_start();

$repemail = $_SESSION['repemail'];

if($_GET['action'] == "remove") {
    $savedphotoid = $_GET['pd'];
    $zero = 'zero';
    $savedquery = "UPDATE campaignphotos SET saved = '$zero' WHERE id = '$savedphotoid'";
    $savedqueryrun = mysql_query($savedquery);
}

//User information
$userquery = mysql_query("SELECT * FROM campaignusers WHERE repemail = '$repemail'");
$logo = mysql_result($userquery,0,'logo');
if($logo == '') {
$logo = 'graphics/nologo.png';
}
$name = mysql_result($userquery,0,'name');
$password = mysql_result($userquery,0,'password');

//if the view wasn't set
if($view == "" || $view == "current") {

if($view == "") {

	$allcampaignsquery = "SELECT * FROM campaigns WHERE repemail='$repemail' ORDER BY endtime DESC";
	$allcampaignsresult = mysql_query($allcampaignsquery);
    $numcurrentcamps = mysql_num_rows($allcampaignsresult);

}

//if view set to current
elseif($view == "current") {
	//they are viewing the ongoing campaigns

	//select all the campaigns that are still live and show the one closest to ending first
	$allcampaignsquery = "SELECT * FROM campaigns WHERE endtime > '$currenttime' AND repemail='$repemail' ORDER BY endtime ASC";
	$allcampaignsresult = mysql_query($allcampaignsquery);
    $numcurrentcamps = mysql_num_rows($allcampaignsresult);
}

    //Profile Photos
    echo'<div style="width:130px;height:160px;"><img class="dropshadow" style="border: 2px solid white; padding:-10px;" src="',$logo,'" height="120" width="120" />
     <a style="width:101px;margin-top:5px;" class="btn btn-success" href="mycampaigns.php?view=logo">Edit Account</a></div>
      <div class="dropshadow well" style="font-size:16px;font-family:helvetica neue,arial;margin-left:140px;margin-top:-130px;">';
      if($name) {
      echo'<div style="font-size:22px;width:800px;">',$name,'</div>';
      }
      if($numcurrentcamps < 1) {
      echo'You currently do not have any campaigns going on. <a href="createcampaign.php">Click here</a> to get the shot you need.';
      }
      else{
      echo'You currently have ',$numbercamps,' active campaigns.';
      }
      echo'</div>';
    

	//now group photos by their campaign which will be used later on
	$randphotoquery = "SELECT source, campaign FROM campaignphotos GROUP BY campaign";
	$randphotoresult = mysql_query($randphotoquery);

	//loop through the results to create arrays of the needed campaign info and of a photo to display
    echo'<div style="margin-top:80px;">';
	for($iii=0; $iii < $numcurrentcamps; $iii++) {
		//find out all the info about this campaign
		$endtime           = mysql_result($allcampaignsresult, $iii, "endtime");
		$quote[$iii]       = mysql_result($allcampaignsresult, $iii, "quote");
		$title[$iii]       = mysql_result($allcampaignsresult, $iii, "title");
		$description[$iii] = mysql_result($allcampaignsresult, $iii, "description");
		$id[$iii]          = mysql_result($allcampaignsresult, $iii, "id");
		$timeleft          = $endtime - time();
		//find out how many days hours minutes are left
			$daysleft          = floor($timeleft / (24*60*60));
    			$timeleft          -= 24*60*60*$daysleft;
    			$hoursleft         = floor($timeleft / (60*60));
			$timeleft          -= 60*60*$hoursleft;
			$minutesleft       = floor($timeleft / 60);

		//find the photo in $randphotoresult where the campaign id matches
		for($jjj=0; $jjj < mysql_num_rows($randphotoresult); $jjj++) {
			//if the current photo matches
			if(mysql_result($randphotoresult, $jjj, "campaign") == $id[$iii]) {
				//then it is the photo we want
				$coverphoto[$iii] = mysql_result($randphotoresult, $jjj, "source");
			}
		}
		$coverphoto[$iii] = str_replace("userphotos/","userphotos/medthumbs/", $coverphoto[$iii]);

		list($width, $height) = getimagesize($coverphoto[$iii]);
		$imgratio = $height / $width;
    	$heightls = $height / 2.5;
    	$widthls = $width / 2.5;

    	//if there aren't any photos in the campaign at all, set it to the default
		if($coverphoto[$iii] == "") {
			$coverphoto[$iii] = "graphics/nophotosubmit.png";
			$heightls = 280;
			$widthls = 280;
		}

		echo '
		<div class="phototitle fPic" id="',$id[$iii],'" style="width:280px;height:280px;overflow:hidden; margin-right: 20px;">
			<a href="managecampaign.php?id=',$id[$iii],'">
        		<div class="statoverlay" style="z-index:1;left:0px;top:210px;position:relative;background-color:black;width:280px;height:75px;"><p style="line-spacing:1.48;padding:5px;color:white;">"',$title[$iii],'"<br />Reward: $', $quote[$iii], '<br />Time Left:'; if($daysleft > 0) {echo $daysleft, ' days, ', $hoursleft, ' hours, ', $minutesleft, ' minutes';} elseif($daysleft < 0) {echo'This campaign is over.';} echo'</p></div>
        		<img style="position:relative;top:-95px;min-height:300px;min-width:280px;" src="', $coverphoto[$iii], '" height="',$heightls,'px" width="',$widthls,'px" />
        	</a>
        </div>';
	}
    echo'</div>';
}


//View downloads
elseif($view == 'downloads') {

//select all the campaigns that are still live and show the one closest to ending first
    $findidsquery = "SELECT id FROM campaigns WHERE repemail = '$repemail'";
    $findids = mysql_query($findidsquery);
    $numids = mysql_num_rows($findids);
    for($iii=0; $iii < $numids; $iii++) {
    $camid = mysql_result($findids,$iii,'id');
    $idlist = $idlist . $camid . " ";
    }
        
$downquery = mysql_query("SELECT source FROM campaignphotos WHERE campaign IN ('$idlist') AND downloaded = '1' ORDER BY id DESC");
$numdownloads = mysql_num_rows($downquery);

    //Profile Photos
    echo'<div style="width:130px;height:160px;"><img class="dropshadow" style="border: 2px solid white; padding:-10px;" src="',$logo,'" height="120" width="120" />
     <a style="width:101px;margin-top:5px;" class="btn btn-success" href="mycampaigns.php?view=logo">Edit Account</a></div>
      <div class="dropshadow well" style="font-size:16px;font-family:helvetica neue,arial;margin-left:140px;margin-top:-130px;">';
      if($name) {
      echo'<div style="font-size:22px;width:800px;">',$name,'</div>';
      }
      if($numcurrentcamps < 1) {
      echo'You currently do not have any campaigns going on. <a href="createcampaign.php">Click here</a> to get the shot you need.';
      }
      else{
      echo'You currently have ',$numbercamps,' active campaigns.';
      }
      echo'</div>';


echo'<div style="margin-top:80px;">';
for($iii=0;$iii<$numdownloads;$iii++) {
$downloadsource = mysql_result($downquery,$iii,'source');

            echo '
            <div class="phototitle" style="width:280px;height:280px;overflow:hidden; margin-right: 20px;">
            <div style="z-index:1;left:0px;top:215px;position:relative;width:280px;height:75px;"><p style="line-spacing:1.48;padding:5px;color:white;"><br />
            
            <form name="download_form" method="post" action="downloadphoto.php">
            <input type="hidden" name="image" value="',$downloadsource,'">
            <button type="submit" name="submit" value="download" class="btn btn-warning" style="margin-top:-40px;margin-left:10px;width:260px;height:35px;font-size:18px;">Download Photo</button>
            </form>
            
            </p></div>
            <img style="position:relative;top:-95px;min-height:300px;min-width:280px;" src="', $downloadsource, '" height="',$heightls,'px" width="',$widthls,'px" />
            </div>';
        
    }//end for loop
    echo'</div>';

}

elseif($view == 'logo') {

    if($action == 'submit') {
    
        if(isset($_POST['name'])) {$name=mysql_real_escape_string($_POST['name']); }
		if(isset($_POST['password'])) {$password=mysql_real_escape_string($_POST['password']); }
		if(isset($_POST['confirmpassword'])) {$confirmpassword = mysql_real_escape_string($_POST['confirmpassword']);}
        
        //check if confirm password and password are same
		if ($confirmpassword != $password) {
			die('Your passwords did not match.');
		}
        
        //require files that will help with picture uploading and thumbnail creation/display
		require 'configcampaigns.php';
		//require 'functionscampaigns.php';	
	
		//move the file
		if(isset($_FILES['file'])) {  
  
    			if(preg_match('/[.](jpg)|(jpeg)|(gif)|(png)|(JPG)$/', $_FILES['file']['name'])) {  
        			$filename = $_FILES['file']['name'];  
                    $newfilename=str_replace(" ","",$filename);
                    $newfilename=str_replace("#","",$newfilename);		
    				$newfilename=str_replace("&","",$newfilename);
                    $newfilename=strtolower($newfilename);
    				$newfilename=str_replace("?","",$newfilename);	
    				$newfilename=str_replace("'","",$newfilename);
    				$newfilename=str_replace("#","",$newfilename);
    				$newfilename=str_replace(":","",$newfilename);
    				$newfilename=str_replace("*","",$newfilename);
    				$newfilename=str_replace("<","",$newfilename);
    				$newfilename=str_replace(">","",$newfilename);
    				$newfilename=str_replace("(","",$newfilename);
    				$newfilename=str_replace(")","",$newfilename);
    				$newfilename=str_replace("^","",$newfilename);
                    $newfilename=str_replace("$","",$newfilename);
    				$newfilename=str_replace("@","",$newfilename);
    				$newfilename=str_replace("!","",$newfilename);
    				$newfilename=str_replace("+","",$newfilename);
    				$newfilename=str_replace("=","",$newfilename);
    				$newfilename=str_replace("|","",$newfilename);
                    $newfilename=str_replace(";","",$newfilename);
    				$newfilename=str_replace("[","",$newfilename);
    				$newfilename=str_replace("{","",$newfilename);
    				$newfilename=str_replace("}","",$newfilename);
    				$newfilename=str_replace("]","",$newfilename);
    				$newfilename=str_replace("~","",$newfilename);
    				$newfilename=str_replace("`","",$newfilename);
                    $newfilename=str_replace("?","",$newfilename);
                                
                    $time = time();
                    $newfilename = $time . $newfilename;
                    $source = $_FILES['file']['tmp_name'];  
        			$profilepic = $path_to_profpic_directory . $newfilename; 
        			move_uploaded_file($source, $profilepic);  
        			createprofthumbnail($newfilename);
					chmod($profilepic, 0644);
                    }  
            }  
	
            //Insert profile pic into database
            $updatequery = "UPDATE campaignusers SET password = '$password', logo = '$profilepic', name = '$name' WHERE repemail = '$repemail'";
            $updatequeryrun = mysql_query($updatequery);
            echo '<h3>Account Saved</h3><br />';
        }


   //Profile Photos
    echo'<div style="width:130px;height:160px;"><img class="dropshadow" style="border: 2px solid white; padding:-10px;" src="',$logo,'" height="120" width="120" />
     <a style="width:101px;margin-top:5px;" class="btn btn-success" href="mycampaigns.php?view=logo">Edit Account</a></div>
      <div class="dropshadow well" style="font-size:16px;font-family:helvetica neue,arial;margin-left:140px;margin-top:-130px;">';
      if($name) {
      echo'<div style="font-size:22px;width:800px;">',$name,'</div>';
      }
      if($numcurrentcamps < 1) {
      echo'You currently do not have any campaigns going on. <a href="createcampaign.php">Click here</a> to get the shot you need.';
      }
      else{
      echo'You currently have ',$numbercamps,' active campaigns.';
      }
      echo'</div>';


  
echo'
<div class="dropshadow well" style="margin-top:100px;font-family:helvetica neue,arial; font-size:16px;">
<form action="',htmlentities($_SERVER['PHP_SELF']), '?view=logo&action=submit" method="post" enctype="multipart/form-data">
Change Name: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input style="width:180px;height:25px;" type="text" name="name" value="', $name, '"/>
<br />
Change Password: &nbsp;<input style="width:180px;height:25px;" type="password" name="password" value="',$password, '"/>
<br />
Confirm Password: &nbsp;<input style="width:180px;height:25px;" type="password" name="confirmpassword" value="',$password, '"/>
<br />
Change Logo/Account Photo: <br /><input style="margin-top:10px" type="file"  name="file" value="', $profilepic, '"/>
<br />
<button class="btn btn-primary" type="submit">Save Account</button>
</form>
</div>';


}

elseif($view == 'saved') {

//select all the campaigns that are still live and show the one closest to ending first
    
    $findidsquery = "SELECT id FROM campaigns WHERE repemail = '$repemail'";
    $findids = mysql_query($findidsquery);
    $numids = mysql_num_rows($findids);
    for($iii=0; $iii < $numids; $iii++) {
    $camid = mysql_result($findids,$iii,'id');
    if($idlist != '') {
    $idlist = $idlist . ",'" . $camid . "'";
    }
    elseif($idlist == '') {
    $idlist = $idlist . "'" . $camid . "'";
    }
    
    }
    
    
	$allcampaignsquery = "SELECT * FROM campaignphotos WHERE campaign IN ($idlist) AND saved = '1' ORDER BY id DESC";
	$photosresult = mysql_query($allcampaignsquery);
    $nump = mysql_num_rows($photosresult);

    
     //Profile Photos
    echo'<div style="width:130px;height:160px;"><img class="dropshadow" style="border: 2px solid white; padding:-10px;" src="',$logo,'" height="120" width="120" />
     <a style="width:101px;margin-top:5px;" class="btn btn-success" href="mycampaigns.php?view=logo">Edit Account</a></div>
      <div class="dropshadow well" style="font-size:16px;font-family:helvetica neue,arial;margin-left:140px;margin-top:-130px;">';
      if($name) {
      echo'<div style="font-size:22px;width:800px;">',$name,'</div>';
      }
      if($numcurrentcamps < 1) {
      echo'You currently do not have any campaigns going on. <a href="createcampaign.php">Click here</a> to get the shot you need.';
      }
      else{
      echo'You currently have ',$numbercamps,' active campaigns.';
      }
      echo'</div>';

	//loop through the results to create arrays of the needed campaign info and of a photo to display
    echo'<div style="margin-top:80px;">';
	for($iii=0; $iii < mysql_num_rows($photosresult); $iii++) {
	//get the information for the current photo
	$photo[$iii] = mysql_result($photosresult, $iii, "source");
	$photo[$iii] = str_replace("userphotos/","userphotos/medthumbs/", $photo[$iii]);
	$points = mysql_result($photosresult, $iii, "points");
	$votes = mysql_result($photosresult, $iii, "votes");
	$average[$iii] = number_format(($points / $votes),2);
	$photoid[$iii] = mysql_result($photosresult, $iii, "id");
    $photoid2[$iii] = mysql_result($photosresult, $iii, "id");
    $caption[$iii] = mysql_result($photosresult, $iii, "caption");

	list($width, $height) = getimagesize($photo[$iii]);
	$imgratio = $height / $width;
   	$heightls = $height / 2.5;
   	$widthls = $width / 2.5;

	echo '
	<div class="phototitle fPic" id="',$photoid[$iii],'" style="width:280px;height:280px;overflow:hidden;">
		<a name="return" href="fullsizeme.php?id=',$photoid[$iii],'">
       		<div class="statoverlay" style="z-index:1;left:0px;top:210px;position:relative;background-color:black;width:280px;height:75px;"><p style="line-spacing:1.48;padding:5px;color:white;">"',$caption[$iii],'"<br />Score: ',$average[$iii],'</p></div>
       		<img style="position:relative;top:-95px;min-height:300px;min-width:280px;" src="', $photo[$iii], '" height="',$heightls,'px" width="',$widthls,'px" />
       	</a>
        
        <a name="removed" href="mycampaigns.php?view=saved&pd=',$photoid[$iii],'&action=remove#return"<button class="btn btn-primary" style="z-index:2;position:relative;top:-130px;left:205px;opacity:.9;">Remove</button></a>';

    echo'
    </div>';
    }

}


//otherwise they are looking at the past campaigns
else {
	//select all the campaigns that are still live and show the one closest to ending first
	$allcampaignsquery = "SELECT * FROM campaigns WHERE endtime <= '$currenttime' AND repemail='$repemail' ORDER BY endtime DESC LIMIT 16";
	$allcampaignsresult = mysql_query($allcampaignsquery);
    
     //Profile Photos
    echo'<div style="width:130px;height:160px;"><img class="dropshadow" style="border: 2px solid white; padding:-10px;" src="',$logo,'" height="120" width="120" />
     <a style="width:101px;margin-top:5px;" class="btn btn-success" href="mycampaigns.php?view=logo">Edit Account</a></div>
      <div class="dropshadow well" style="font-size:16px;font-family:helvetica neue,arial;margin-left:140px;margin-top:-130px;">';
      if($name) {
      echo'<div style="font-size:22px;width:800px;">',$name,'</div>';
      }
      if($numcurrentcamps < 1) {
      echo'You currently do not have any campaigns going on. <a href="createcampaign.php">Click here</a> to get the shot you need.';
      }
      else{
      echo'You currently have ',$numbercamps,' active campaigns.';
      }
      echo'</div>';


	//now group photos by their campaign which will be used later on
	$randphotoquery = "SELECT source, campaign FROM campaignphotos GROUP BY campaign";
	$randphotoresult = mysql_query($randphotoquery);

	//loop through the results to create arrays of the needed campaign info and of a photo to display
    echo'<div style="margin-top:80px;">';
	for($iii=0; $iii < mysql_num_rows($allcampaignsresult); $iii++) {
		//find out all the info about this campaign
		$quote[$iii]       = mysql_result($allcampaignsresult, $iii, "quote");
		$title[$iii]       = mysql_result($allcampaignsresult, $iii, "title");
		$description[$iii] = mysql_result($allcampaignsresult, $iii, "description");
		$id[$iii]          = mysql_result($allcampaignsresult, $iii, "id");
		$coverphotoid[$iii]  = mysql_result($allcampaignsresult, $iii, "winnerphoto");
        $coverquery = mysql_query("SELECT source FROM campaignphotos WHERE id = '$coverphotoid[$iii]'");
        $coverphoto[$iii] = mysql_result($coverquery, 0, "source");
		$winner            = mysql_result($allcampaignsresult, $iii, "winneremail");

		//if a winner hasn't been selected yet for this campaign
		if($coverphoto[$iii] == "") {
			//find the photo in $randphotoresult where the campaign id matches
			for($jjj=0; $jjj < mysql_num_rows($randphotoresult); $jjj++) {
				//if the current photo matches
				if(mysql_result($randphotoresult, $jjj, "campaign") == $id[$iii]) {
					//then it is the photo we want
					$coverphoto[$iii] = mysql_result($randphotoresult, $jjj, "source");
				}
			}
		}	
		//otherwise, a winner has been selected so find out their first and last name
		else {
			//add this person to the winners emailaddress list
			$winneremaillist .= $winner;
			$winneremaillist .= "',";
		}
		$coverphoto[$iii] = str_replace("userphotos/","userphotos/medthumbs/", $coverphoto[$iii]);

		list($width, $height) = getimagesize($coverphoto[$iii]);
		$imgratio = $height / $width;
    	$heightls = $height / 2.5;
    	$widthls = $width / 2.5;

    	//if there aren't any photos in the campaign at all, set it to the default
		if($coverphoto[$iii] == "") {
			$coverphoto[$iii] = "userphotos/default_cover.png";
			$heightls = 280;
			$widthls = 280;
		}

		echo '
		<div class="phototitle fPic" id="',$id[$iii],'" style="width:280px;height:280px;overflow:hidden; margin-right:20px;">
			<a href="managecampaign.php?id=',$id[$iii],'">
        		<div class="statoverlay" style="z-index:1;left:0px;top:210px;position:relative;background-color:black;width:280px;height:75px;"><p style="line-spacing:1.48;padding:5px;color:white;">"',$title[$iii],'"<br />Reward: $', $quote[$iii], '<br />This campaign is closed.</p></div>
        		<img style="position:relative;top:-95px;min-height:300px;min-width:280px;" src="', $coverphoto[$iii], '" height="',$heightls,'px" width="',$widthls,'px" />
        	</a>
        </div>';
	}
    
    echo'</div>';
}

?>
<div class="grid_3 " style="position:fixed; right: 80px;">
<div id="accordion2" class="accordion" style="margin-top:40px;width:150px;">

<div class="accordion-group">
<div style="background-color:#eeeff3;" class="accordion-heading dropshadow">
<a class="accordion-toggle" style="color:#21608E;font-weight:bold;" href="mycampaigns.php">All Campaigns</a>
</div>
<div id="collapseOne" class="accordion-body collapse">
</div>
</div>

<div class="accordion-group">
<div style="background-color:#eeeff3;" class="accordion-heading dropshadow">
<a class="accordion-toggle" style="color:#21608E;font-weight:bold;" href="mycampaigns.php?view=current">Current</a>
</div>
<div id="collapseOne" class="accordion-body collapse">
</div>
</div>

<div class="accordion-group">
<div style="background-color:#eeeff3;" class="accordion-heading dropshadow">
<a class="accordion-toggle" style="color:#21608E;font-weight:bold;" href="mycampaigns.php?view=previous">Previous</a>
</div>
<div id="collapseTwo" class="accordion-body collapse">
</div>
</div>

<div class="accordion-group">
<div style="background-color:#eeeff3;" class="accordion-heading dropshadow">
<a class="accordion-toggle" style="color:#21608E;font-weight:bold;" href="mycampaigns.php?view=downloads">Downloads</a>
</div>
<div id="collapseTwo" class="accordion-body collapse">
</div>
</div>

<div class="accordion-group">
<div style="background-color:#eeeff3;" class="accordion-heading dropshadow">
<a class="accordion-toggle" style="color:#21608E;font-weight:bold;" href="mycampaigns.php?view=saved">Saved Photos</a>
</div>
<div id="collapseTwo" class="accordion-body collapse">
</div>
</div>


<?php
if($_GET['action'] == "remove") {
    echo'<br /><span class="label label-important" style="font-size:14px;font-family:helvetica neue,arial;margin-left:15px;padding:6px;">Photo Removed</span><br />';
}
?>

</div>
</div>


</body>
</html>
<?php 

mysql_close();

?>