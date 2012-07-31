<?php

//connect to the database
require "db_connection.php";

//start the session
session_start();

require "functionscampaigns3.php"; 
    // if login form has been submitted
    if (htmlentities($_GET['action']) == "login") { 
        login();
    }
    else if(htmlentities($_GET['action']) == "logout") { 
        logout();
    }

    if($_SESSION['loggedin'] != 2) {
    
        mysql_close();
        header('Location: index.php');
        exit();	
    
    }

//find the current time
$currenttime = time();

//find out which view they are looking at
$view = htmlentities($_GET['view']);

?>


<!DOCTYPE html>
<html>
<head>
<title>PhotoRankr Market - Stock Photos Done Differently</title>
	<link rel="stylesheet" href="css/bootstrapnew2.css" type="text/css" />
    <link rel="stylesheet" href="css/reset.css" type="text/css" />
    <link rel="stylesheet" href="css/text.css" type="text/css" />
    <link rel="stylesheet" href="css/960_24.css" type="text/css" />
    <link rel="stylesheet" href="css/index.css" type="text/css"/> 
    <link rel="stylesheet" href="css/itunes.css" type="text/css"/> 
	<link rel="stylesheet" type="text/css" href="css/all.css"/>
	<script type="text/javascript" href="js/bootstrap-dropdown.js"></script>
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript" src="http://masonry.desandro.com/jquery.masonry.min.js"></script>
<script type="text/javascript" src="https://raw.github.com/desandro/imagesloaded/master/jquery.imagesloaded.min.js"></script>
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

	<!--GOOGLE ANALYTICS CODE-->
	<script type="text/javascript">
  	var _gaq = _gaq || [];
  	_gaq.push(['_setAccount', 'UA-28031297-1']);
  	_gaq.push(['_trackPageview']);
  	(function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
 	 })();
	</script>

</head>
<body style="overflow-x:hidden;">

<?php navbarnew(); ?>
  
	
<!--CONTAINER-->
<div class="container">
<div class="grid_24" style="width: 1140px;top:30px;margin-left:-100px;">
<?php

if($_SESSION['loggedin'] != 2) {
	mysql_close();
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=campaignnewuser.php">';
	exit();	
}

//start the session
session_start();

$repemail = $_SESSION['repemail'];

if($_GET['action'] == 'remove') {
$savedphotoid = $_GET['pd'];
$querycheck = mysql_query("SELECT emailaddress FROM maybe WHERE id = '$savedphotoid'");
$emailcheck = mysql_result($querycheck,0,'emailaddress');

    if($repemail == $emailcheck) {
        $removequery = mysql_query("DELETE FROM maybe WHERE id = '$savedphotoid' AND emailaddress = '$repemail'");
    }
}

if($_GET['action'] == "remove" && $_GET['select'] == 'campaigns') {
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
$numcampsquery = mysql_query("SELECT * FROM campaigns WHERE repemail = '$repemail'");
$numcampaigns = mysql_num_rows($numcampsquery);

?>

<!--NEW STYLE-->

<div class="container_24" style="position:relative;margin-top:10px">
	<div class="grid_7 pull_2">
		<div class="grid_7 container" id="profilebox">
			<img class="phototitle2" src="<?php echo $logo; ?>" style="width:120px;height:120px;float:left;"/>
			
            <?php 
            //saved photos queries
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
            
            
            //download view queries
            $findidsqueryd = "SELECT id FROM campaigns WHERE repemail = '$repemail'";
            $findidsd = mysql_query($findidsqueryd);
            $numidsd = mysql_num_rows($findidsd);
            for($iii=0; $iii < $numidsd; $iii++) {
            $camidd = mysql_result($findidsd,$iii,'id');
            $idlistd = $idlistd . $camidd . " ";
            }
        
            $downquery = mysql_query("SELECT source FROM campaignphotos WHERE campaign IN ('$idlistd') AND downloaded = '1' ORDER BY id DESC");
            $numdownloads = mysql_num_rows($downquery);
			?>
            
        <div style="margin-top:15px;">
		<div style="position:relative;left:10px;top:5px;padding-bottom:3px;font-size:13px;">Saved Photos: <?php echo $nump; ?>
        </div>
        <div style="position:relative;left:10px;top:5px;padding-bottom:3px;font-size:13px;">Downloads: <?php echo $numdownloads; ?>
        </div>
        <div style="position:relative;left:10px;top:5px;padding-bottom:3px;font-size:13px;">Campaigns: <?php echo $numcampaigns; ?>
        </div>
        <div style="position:relative;left:10px;top:5px;padding-bottom:3px;font-size:13px;">Following: 38
        </div>
        <div style="float:left;width:260px;font-size:18px;margin-top:5px;padding:6px;"><?php echo $name; ?></div>
        </div>
		</div>
		
		<a style="text-decoration:none;color:black;" href="account.php"><div class="grid_6 btn3" <?php if($view == '') {echo'style="background:#cccccc;font-size:22px"';} else {echo'style="font-size:22px;"';} ?>>
			<span class="btntext2">Saved Photos
		</div></a>
        
        <a style="text-decoration:none;color:black;" href="account.php?view=campaigns"><div class="grid_6 btn3" <?php if($view == 'campaigns') {echo'style="background:#cccccc;font-size:22px"';} else {echo'style="font-size:22px;"';} ?>>
			<span class="btntext2" style="font-size:22px;">Campaigns
		</div></a>
        
		<a style="text-decoration:none;color:black;" href="account.php?view=downloads"><div class="grid_6 btn3" <?php if($view == 'downloads') {echo'style="background:#cccccc;font-size:22px"';} else {echo'style="font-size:22px;"';} ?>>
			<span class="btntext2" style="font-size:22px;">Downloads
		</div></a>
        
        <a style="text-decoration:none;color:black;" href="account.php?view=photogs"><div class="grid_6 btn3" <?php if($view == 'photogs') {echo'style="background:#cccccc;font-size:22px"';} else {echo'style="font-size:22px;"';} ?>>
			<span class="btntext2" style="font-size:22px;"> Photographers
		</div></a>
        
        <a style="text-decoration:none;color:black;" href="account.php?view=account"><div class="grid_6 btn3" <?php if($view == 'account') {echo'style="background:#cccccc;font-size:22px"';} else {echo'style="font-size:22px;"';} ?>>
			<span class="btntext2" style="font-size:22px;"> Edit Account
		</div></a>
        
        </div>
	

            <?php navbar4(); ?>

            <?php 
            
            if($view == '') {
            $select = htmlentities($_GET['select']); 
                       
            echo'<div class="grid_14 push_6" style="margin-top:-325px;width:780px;">';

            if($select == '') {
                                   
            //Saved photos from market
            $marketquery = mysql_query("SELECT * FROM maybe WHERE emailaddress = '$repemail'");
            $numsavedinmarket = mysql_num_rows($marketquery);
            
                for($iii=0; $iii<$numsavedinmarket; $iii++) {
                        $photo[$iii] = mysql_result($marketquery, $iii, "source");
                        $photo2[$iii] = str_replace("http://photorankr.com/userphotos/","../userphotos/medthumbs/", $photo[$iii]);
                        $photoid[$iii] = mysql_result($marketquery, $iii, "id");
                        $caption[$iii] = mysql_result($marketquery, $iii, "caption");
        
                        list($height,$width) = getimagesize($photo2[$iii]);
                        $widthnew = $width / 2.8;
                        $heightnew = $height / 2.8;
                
                            echo'
                            <div style="width:250px;height:280px;overflow:hidden;float:left;">
                                <a href="fullsize2.php?imageid=',$imageid[$iii],'"><img style="text-align:center;clear:both;" class="phototitle2" src="',$photo[$iii],'" height="',$widthnew,'px" width="',$heightnew,'px" /></a>
				
                        <div style="text-align:left;font-size:14px;clear:both;padding-top:15px;"><a name="removed" href="account.php?pd=',$photoid[$iii],'&action=remove#return"<button class="btn btn-primary" style="z-index:2;position:relative;opacity:.9;">Remove Saved Photo</button></a></div></div>';
                }
             
            }
            
            elseif($select == 'campaigns') {
            for($iii=0; $iii < mysql_num_rows($photosresult); $iii++) {
            //get the information for the current photo
            $photobig[$iii] = mysql_result($photosresult, $iii, "source");
            $photo[$iii] = str_replace("userphotos/","userphotos/medthumbs/", $photobig[$iii]);
            $points = mysql_result($photosresult, $iii, "points");
            $votes = mysql_result($photosresult, $iii, "votes");
            $average[$iii] = number_format(($points / $votes),2);
            $photoid[$iii] = mysql_result($photosresult, $iii, "id");
            $photoid2[$iii] = mysql_result($photosresult, $iii, "id");
            $caption[$iii] = mysql_result($photosresult, $iii, "caption");

            list($height,$width) = getimagesize($photobig[$iii]);
            $widthnew = $width / 3.8;
            $heightnew = $height / 3.8;
                
                echo'
				<div style="width:250px;height:280px;overflow:hidden;float:left;">
					<a href="fullsize2.php?imageid=',$imageid[$iii],'"><img style="text-align:center;clear:both;" class="phototitle2" src="',$photo[$iii],'" height="',$widthnew,'px" width="',$heightnew,'px" /></a>
				
               <div style="text-align:left;font-size:14px;clear:both;padding-top:15px;"><a name="removed" href="account.php?select=campaigns&pd=',$photoid[$iii],'&action=remove#return"<button class="btn btn-primary" style="z-index:2;position:relative;opacity:.9;">Remove Saved Photo</button></a></div></div>';
            }
        }
     echo'</div>'; 
} //end saved view
    
  
    if($view == 'downloads') {
    
    echo'<div class="grid_15 push_6" style="margin-top:-325px;width:820px;margin-left:-10px">';
    for($iii=0;$iii<$numdownloads;$iii++) {
    $downloadsource = mysql_result($downquery,$iii,'source');
    
    list($height,$width) = getimagesize($downloadsource);
    $widthnew = $width / 3;
    $heightnew = $height / 3;
    
            echo '
            <div class="phototitle" style="width:220px;height:220px;overflow:hidden; margin-right: 20px;">
            <div style="z-index:1;left:0px;top:215px;position:relative;width:280px;height:75px;"><p style="line-spacing:1.48;padding:5px;color:white;"><br />
            
            <form name="download_form" method="post" action="downloadphoto.php">
            <input type="hidden" name="image" value="',$downloadsource,'">
            <button type="submit" name="submit" value="download" class="btn btn-warning" style="margin-top:-230px;margin-left:10px;width:200px;height:35px;font-size:18px;">Download Photo</button>
            </form>
            
            </p></div>
            <img style="position:relative;top:-95px;min-height:230px;min-width:230px;" src="', $downloadsource, '" height="',$heightnew,'px" width="',$widthnew,'px" />
            </div>';
        
    }//end for loop
    echo'</div>';

    }
    
    elseif($view == 'campaigns') {
    
    $allcampaignsquery = "SELECT * FROM campaigns WHERE repemail='$repemail' ORDER BY endtime DESC";
	$allcampaignsresult = mysql_query($allcampaignsquery);
    $numcurrentcamps = mysql_num_rows($allcampaignsresult);

	//now group photos by their campaign which will be used later on
	$randphotoquery = "SELECT source, campaign FROM campaignphotos GROUP BY campaign";
	$randphotoresult = mysql_query($randphotoquery);

	//loop through the results to create arrays of the needed campaign info and of a photo to display
    echo'<div class="grid_15 push_6" style="margin-top:-325px;width:820px;margin-left:-10px">';
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
		<div class="phototitle fPic" id="',$id[$iii],'" style="width:220px;height:220px;overflow:hidden; margin-right: 20px;">
			<a href="managecampaign.php?id=',$id[$iii],'">
        		<div class="statoverlay" style="z-index:1;left:0px;top:210px;position:relative;background-color:black;width:280px;height:75px;"><p style="line-spacing:1.48;padding:5px;color:white;">"',$title[$iii],'"<br />Reward: $', $quote[$iii], '<br />Time Left:'; if($daysleft > 0) {echo $daysleft, ' days, ', $hoursleft, ' hours, ', $minutesleft, ' minutes';} elseif($daysleft < 0) {echo'This campaign is over.';} echo'</p></div>
        		<img style="position:relative;top:-95px;min-height:220px;min-width:220px;" src="', $coverphoto[$iii], '" height="',$heightls,'px" width="',$widthls,'px" />
        	</a>
        </div>';
	}
    echo'</div>';
    
    
    }
    
    elseif($view == 'account') {

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
            echo '<h4 style="margin-top:20px;padding-bottom:-40px;">Account Saved</h4><br />';
        }

        echo'
<div style="margin-top:30px;font-family:helvetica neue,arial; font-size:16px;">
<form action="',htmlentities($_SERVER['PHP_SELF']), '?view=account&action=submit" method="post" enctype="multipart/form-data">
Change Name: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input style="width:180px;height:25px;" type="text" name="name" value="', $name, '"/>
<br />
Change Password: &nbsp;<input style="width:180px;height:25px;" type="password" name="password" value="',$password, '"/>
<br />
Confirm Password: &nbsp;<input style="width:180px;height:25px;" type="password" name="confirmpassword" value="',$password, '"/>
<br />
Change Logo/Account Photo: <br /><input style="margin-top:10px" type="file"  name="file" value="', $profilepic, '"/>
<br />
<button class="btn btn-success" type="submit">Save Account</button>
</form>
</div>';
        
    }
    
    ?>
    
           
           
</div><!--end container-->
</body>
</html>