<?php
ini_set('max_input_time', 300);   

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
        
        if(!file_exists($path_to_coverpic_directory)) {  
          if(!mkdir($path_to_coverpic_directory)) {  
               die("There was a problem. Please try again!");  
          }  
        } 
      
        imagejpeg($nm, $filename);  
		chmod ($filename, 0644);
        //$tn = '<img src="' . $path_to_profpic_directory . $filename . '" alt="image" />';  
        //$tn .= '<br />Upload Successful!';  
        //echo $tn;  
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
        
        if(!file_exists($path_to_coverpic_directory)) {  
          if(!mkdir($path_to_coverpic_directory)) {  
               die("There was a problem. Please try again!");  
          }  
        } 
      
      	$filename = str_replace("profilepics", "profilepics/thumbs", $filename);
        imagejpeg($nm, $filename);  
		chmod ($filename, 0644);
        //$tn = '<img src="' . $path_to_profpic_directory . $filename . '" alt="image" />';  
        //$tn .= '<br />Upload Successful!';  
        //echo $tn;  
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
	
	 imagejpeg($finalimage, $path_to_medimage_directory . $filename); 	
	
	chmod ($path_to_medimage_directory . $filename, 0644);
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


function login() {
        
        session_start();
        $seshemail = mysql_real_escape_string($_POST['emailaddress']);
        
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
            $_SESSION[$seshemail] = 1;
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
    $_SESSION['email'] = "";
    $_SESSION[$emailaddress] = "";

    session_destroy();
}

function navbarnew() {
echo'
<link rel="stylesheet" href="css/style.css" type="text/css"/> 
<div class="navbar navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container" style="height:50px;width:1040px;">
				<ul class="nav" style="height:50px;">
					<li class="margint"> <a href="index.php"><img class="logo" src="graphics/coollogo.png" style="position:relative;left:-10px;height:45px;width:186px;margin-top:-8px;padding-right:20px;"/></a></li>
					<li class="margint" style="margin-left:-20px;"> <form class="navbar-search" action="search.php" method="get">
<input class="search3 margint marginl" style="height:1.4em;padding-right:25px;margin-top:2px;margin-left:5em;margin-right:5.5em;font-family:helvetica;font-size:13px;font-weight:100;color:black;" name="searchterm" type="text" placeholder="Search for photos & people">
</form></li>
					<li style="margin-left:-20px;"> <a href="newsfeed.php"> News </a> </li>
					<li class="dropdown topcenter"'; if($_SERVER['REQUEST_URI'] == '/newest3.php') {echo'style="color:white;"';} echo'id="accountmenu">
						<a class="dropdown-toggle" data-toggle="dropdown" href="#"> Photos </b></a>
							<ul class="dropdown-menu" style="margin-top:0px;background-color:#fff;">
                                <li> <a href="trending.php"> Trending </a></li>
                                <li class="divider"></li>
								<li> <a href="newest.php"> Newest </a></li>
								<li class="divider"></li>
                                <li> <a href="topranked.php"> Top Ranked </a></li>
                                <li class="divider"></li>';
                                
                                
  //get the users information from the database
  $email = $_SESSION['email'];
  
  $likesquery = "SELECT * FROM userinfo WHERE emailaddress='$email'";
  $likesresult = mysql_query($likesquery) or die(mysql_error());
  $discoverseen = mysql_result($likesresult, 0, "discoverseen");

  //find out what they like
  $likes = mysql_result($likesresult, 0, "viewLikes");
    if($likes=="") {
		$nolikes = 1;
        		
	}

  $likes .= "  ";
  $likes .= mysql_result($likesresult, 0, "buyLikes");

  //create an array from what they like
  $likesArray = explode("  ", $likes);

  //loop through the array to format the likes in the proper format for the query
  $formattedLikes = "%";
  for($iii=0; $iii < count($likesArray); $iii++) {
    $formattedLikes .= $likesArray[$iii];
    $formattedLikes .= "%";
  }

    //make an array of the photos they have already seen
  if($discoverseen != "") {
    $discoverArray = explode(" ", $discoverseen);
    $discoverFormatted = "";
    for($iii=0; $iii < count($discoverArray)-1; $iii++) {
      $discoverFormatted .= "'";
      $discoverFormatted .= $discoverArray[$iii];
      $discoverFormatted .= "', ";
    }
    $discoverFormatted .= "'";
    $discoverFormatted .= $discoverArray[count($discoverArray)-1];
    $discoverFormatted .= "'";
  }
  
  //select the image that they will be seeing next
  //delineate between whether they have used discover feature before
  if($discoverseen != "") {     //get the photos that match this person's view interests
    $viewquery = "SELECT *, MATCH(maintags, singlecategorytags, singlestyletags) AGAINST ('$formattedLikes') AS matching FROM photos WHERE MATCH(maintags, singlecategorytags, singlestyletags) AGAINST ('$formattedLikes') AND id NOT IN(" . $discoverFormatted . ") ORDER BY matching DESC, points DESC LIMIT 0, 1";
    $viewresult = mysql_query($viewquery) or die(mysql_error());
  }
  else {
    //get the photos that match this person's view interests
    $viewquery = "SELECT *, MATCH(maintags, singlecategorytags, singlestyletags) AGAINST ('$formattedLikes') AS matching FROM photos WHERE MATCH(maintags, singlecategorytags, singlestyletags) AGAINST ('$formattedLikes') ORDER BY matching DESC, points DESC LIMIT 0, 1";
    $viewresult = mysql_query($viewquery) or die(mysql_error());
  }

  $discoverimage = mysql_result($viewresult, 0, "id");
                                
                        echo'
								<li> <a href="discover.php?image=',$discoverimage,'"> Discover </a> </li>
							</ul>
						</li>
						<li class="dropdown topcenter" id="accountmenu">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#"> Market</b> </a>
							<ul class="dropdown-menu" style="margin-top:0px;background-color:#fff;">
								<li> <a href="marketplace.php"> Marketplace </a></li>
                                <li class="divider"></li>
								<li> <a href="viewcampaigns.php"> Campaigns </a></li>
							</ul>
						</li>
                        <li> <a href="/blog/post"> Blog </a> </li>';
                        
                        if($_SESSION['loggedin'] != 1) {
                        
                        echo'<li class="dropdown topcenter " id="accountmenu">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#" style="font-family:helvetica;"> Log In </b></a>
                                <ul class="dropdown-menu" style="margin-top:0px;background-color:#fff;width:192px;">
								<li><a style="color:#000;font-size:15px;" href="signup3.php">Register for free today</a></li>
                                <li class="divider"></li>';
                            
                                    if(strpos($_SERVER['REQUEST_URI'],'?') !== false) {
                                        echo'<form name="login_form" method="post" action="',htmlentities($_SERVER['REQUEST_URI']),'&action=login">';
                                    }   
                                    else {
                                         echo'<form name="login_form" method="post" action="',htmlentities($_SERVER['REQUEST_URI']),'?action=login">';
                                    }                                
                                echo'
                                <li style="margin-left:15px;color:#000;float:left;">Email: </li>
                                <li><input type="text" style="width:150px;margin-top:3px;margin-left:15px;float:left;" name="emailaddress" autocomplete="on" /></li>
                                <li><span style="float:left;margin-left:15px;color:#000;float:left;">Password: </li>
                                <input type="password" style="width:150px;margin-top:3px;margin-left:15px;float:left;" name="password" autocomplete="on" /></li>
                                <li style="margin-left: 110px;float:left;"><input type="submit" class="btn btn-success" value="Sign In" id="loginButton"/></li>
                                </form>
								</ul>
						</li>';
                        
                        }
                        
                        elseif($_SESSION['loggedin'] == 1) {
                            $email = $_SESSION['email'];
                            
                            $profilequery = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$email'");
                            $sessionprofilepic = mysql_result($profilequery,0,'profilepic');
                            $sessionname = mysql_result($profilequery,0,'firstname')." ".mysql_result($profilequery,0,'lastname');
                            $sessionname = (strlen($sessionname) > 13) ? substr($sessionname,0,10). "&#8230;" : $sessionname;
                            
                        //Campaign Notifications
                        
                                $campnots = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$email'");
                                $campnotsresult = mysql_result($campnots, 0, "campaign_notifications");
                                
                        echo'
                        
						<li class="dropdown"  id="accountmenu">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><img src="graphics/campaignnots.png" height="15" />';
                        
                        if($campnotsresult > 0) {
                                
                                echo'
                                <div style="position:relative;top:-27px;color:red;left:12px;font-size:13px;font-weight:bolder;">',$campnotsresult,'</div>';
                            
                            }
                        
                        echo'</a><ul class="dropdown-menu" style="margin-top:3px;margin-left:-215px;background-color:#fff;">';
                        
                        $campaignnews = mysql_query("SELECT * FROM newsfeed WHERE type = 'campaign' OR type = 'campaignended' OR type = 'feedback' ORDER BY id DESC");
                        $numcamps = mysql_num_rows($campaignnews);
                       
                        echo'<div style="width:450px;height:350px;overflow-y:scroll;font-size:12px;font-family:helvetica neue,helvetica,arial;font-weight:200;">';
                         
                        for($iii=0; $iii <= 20; $iii++) {
                            
                            $typecampaign = mysql_result($campaignnews,$iii,'type');
                            $caption = mysql_result($campaignnews,$iii,'caption');
                            $source= mysql_result($campaignnews,$iii,'source');
                            $id= mysql_result($campaignnews,$iii,'id');
                            $newsource = str_replace("userphotos/","userphotos/thumbs/", $source);
                            $campaignentrees = mysql_result($campaignnews,$iii,'campaignentree');
                            $entreematch = strpos($campaignentrees,$email);
                            
                            if($typecampaign == 'campaign') {
                                
                                $quotecampquery = mysql_query("SELECT quote FROM campaigns WHERE id = '$source'");
                                $quotecamp = mysql_result($quotecampquery, 0, "quote");
                                $phrase = 'New Campaign: <b>(Reward $' . $quotecamp . ')  "</b>' . $caption . '"';
                                $phrase = (strlen($phrase) > 56) ? substr($phrase,0,53). " &#8230;" : $phrase;
                                $coverphotoquery = mysql_query("SELECT source FROM campaignphotos WHERE campaign = '$source' ORDER BY (points/votes) DESC LIMIT 0,1");
                                $coverphoto = mysql_result($coverphotoquery,0,'source');
                                $coverphoto = str_replace('userphotos/','market/userphotos/medthumbs/',$coverphoto);

                                echo'<a style="text-decoration:none" href="campaignphotos.php?id=',$source,'&newsid=',$id,'"><div id="greenshadow"><img style="float:left;padding:5px;" src="',$coverphoto,'" height="50" width="50" />&nbsp;<div style="float:left;margin-top:20px;margin-left:10px;">',$phrase,'</div></div></a>';
                            }

                            elseif($typecampaign == 'campaignended' && $entreematch > 0) {
                            
                                $phrase = '<b>Campaign Winner Picked:</b> "'.$caption.'"';
                                $phrase = (strlen($phrase) > 56) ? substr($phrase,0,53). " &#8230;" : $phrase;
                                $selectwinner = mysql_query("SELECT winneremail FROM campaigns WHERE id = '$source'");
                                $winneremail = mysql_result($selectwinner,0,'winneremail');
                                $getwinnerpic = mysql_query("SELECT profilepic FROM userinfo WHERE emailaddress = '$winneremail'");
                                $winnerpic = mysql_result($getwinnerpic,0,'profilepic');
                                
                                echo'<a style="text-decoration:none" href="campaignphotos.php?id=',$source,'&newsid=',$id,'"><div id="greenshadow"><img style="float:left;padding:5px;" src="',$winnerpic,'" height="50" width="50" />&nbsp;<div style="float:left;margin-top:20px;margin-left:10px;">',$phrase,'</div></div></a>';
                            }

                            elseif($typecampaign == 'feedback' && $entreematch > 0) {

                                $phrase = '<b>Campaign Feedback:</b> "'.$caption.'"';
                                $phrase = (strlen($phrase) > 56) ? substr($phrase,0,53). " &#8230;" : $phrase;
                                $coverphotoquery = mysql_query("SELECT source FROM campaignphotos WHERE campaign = '$source' ORDER BY (points/votes) DESC LIMIT 0,1");
                                $coverphoto = mysql_result($coverphotoquery,0,'source');
                                $coverphoto = str_replace('userphotos/','market/userphotos/medthumbs/',$coverphoto);
                                
                                echo'<a style="text-decoration:none" href="campaignphotos.php?id=',$source,'&newsid=',$id,'"><div id="greenshadow"><img style="float:left;padding:5px;" src="',$coverphoto,'" height="50" width="50" />&nbsp;<div style="float:left;margin-top:20px;margin-left:10px;">',$phrase,'</div></div></a>';
                            }

                        }
                        
                        echo'
                        </div>
                        </ul>
                        </li>';

                        
                        //All Other Notifications
                        echo'
						<li class="dropdown"  id="accountmenu">';
                                                            
                                //QUERY FOR NOTIFICATION COUNT
                                $currentnots = "SELECT * FROM userinfo WHERE emailaddress = '$email'";
                                $currentnotsquery = mysql_query($currentnots);
                                $currentnotsresult = mysql_result($currentnotsquery, 0, "notifications");

                        echo'
                        
							<a class="dropdown-toggle" data-toggle="dropdown" href="#"><img style="margin-left:-3px;" src="graphics/notification.png" height="25" />';
                            
                            if($currentnotsresult > 0) {
                                
                                echo'
                                <div style="position:relative;top:-27px;color:red;left:9px;font-size:13px;font-weight:bolder;">',$currentnotsresult,'</div>';
                            
                            }
                            
                            echo'</a><ul class="dropdown-menu" style="margin-top:-1px;margin-left:-255px;background-color:#fff;">';
								
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

                                if($numnots > 1) {
                                
                                    echo'<div style="width:450px;max-height:350px;overflow-y:scroll;font-size:12px;font-family:helvetica neue,helvetica,arial;font-weight:200;">';

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
                                    $newsource = str_replace("userphotos/","userphotos/thumbs/", $source);
                                    
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

                                            echo'<a style="text-decoration:none" href="fullsize.php?image=',$source,'&id=',$id,'"><div id="',$highlightid,'"><img class="roundednot" style="float:left;padding:5px;" src="http://www.photorankr.com/',$newsource,'" height="50" width="50" />&nbsp;<div style="float:left;margin-top:20px;margin-left:10px;"><img src="graphics/comment.png" height="15" />&nbsp;&nbsp;&nbsp;<b>',$fullname,'</b> commented on your photo</div></div></a>';
                                            
                                        }

                                        elseif($type == "blogcomment") {

                                            $blogcommenterquery = mysql_query("SELECT profilepic,firstname,lastname FROM userinfo WHERE emailaddress = '$blogcommenteremail'");
                                            $blogcommenterpic = mysql_result($blogcommenterquery,0,'profilepic');
                                            $blogcommentername = mysql_result($blogcommenterquery,0,'firstname') ." ". mysql_result($blogcommenterquery,0,'lastname');

                                            echo'<a style="text-decoration:none" href="myprofile.php?view=blog&bi=',$source,'#',$source,'"><div id="',$highlightid,'"><img class="roundednot" style="float:left;padding:5px;" src="',$blogcommenterpic,'" height="50" width="50" />&nbsp;<div style="float:left;margin-top:20px;margin-left:10px;"><b>',$blogcommentername,'</b> commented on your blog post</div></div></a>';
                                            
                                        }

                                        elseif($type == "message") {

                                            $newaccount = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$ownermessage'");
                                            $profilepic = mysql_result($newaccount,0,'profilepic');
                                            if($profilepic == "") {
                                                $profilepic = "profilepics/default_profile.jpg";
                                            }

                                            echo'<a style="text-decoration:none" href="myprofile.php?view=viewthread&thread=',$thread,'&id=',$id,'"><div id="',$highlightid,'"><img class="roundednot" style="float:left;padding:5px;" src="',$profilepic,'" height="50" width="50" />&nbsp;<div style="float:left;margin-top:20px;margin-left:10px;"><img src="graphics/contact.png" height="13" />&nbsp;&nbsp;&nbsp;<b>',$fullname,'</b> sent you a message</div></div></a>';

                                        }

                                        elseif($type == "reply") {

                                            $newaccount = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$ownermessage'");
                                            $profilepic = mysql_result($newaccount,0,'profilepic');
                                            if($profilepic == "") {
                                                $profilepic = "profilepics/default_profile.jpg";
                                            }
                                        
                                            echo'<a style="text-decoration:none" href="myprofile.php?view=viewthread&thread=',$thread,'&id=',$id,'"><div id="',$highlightid,'"><img class="roundednot" style="float:left;padding:5px;" src="',$profilepic,'" height="50" width="50" />&nbsp;<div style="float:left;margin-top:20px;margin-left:10px;"><img src="graphics/contact.png" height="13" />&nbsp;&nbsp;&nbsp;<b>',$fullname,'</b> replied to your message</div></div></a>';

                                        }

                                        elseif($type == "fave") {

                                            echo'<a style="text-decoration:none" href="fullsize.php?image=',$source,'&id=',$id,'"><div id="',$highlightid,'"><img  class="roundednot" style="float:left;padding:5px;" src="http://www.photorankr.com/',$newsource,'" height="50" width="50" />&nbsp;<div style="float:left;margin-top:20px;margin-left:10px;"><img src="graphics/fave.png" height="18" />&nbsp;&nbsp;&nbsp;<b>',$fullname,'</b> favorited your photo</div></div></a>';

                                        }
                                        
                                        elseif($type == "exhibitfave") {

                                            echo'<a style="text-decoration:none" href="myprofile.php?view=exhibits&set=',$source,'&id=',$id,'"><div id="',$highlightid,'"><img  class="roundednot" style="float:left;padding:5px;" src="http://www.photorankr.com/',$setcover,'" height="50" width="50" />&nbsp;<div style="float:left;margin-top:20px;margin-left:10px;"><img src="graphics/fave.png" height="18" />&nbsp;&nbsp;&nbsp;<b>',$fullname,'</b> favorited your exhibit</div></div></a>';

                                        }

                                        elseif($type == "trending") {

                                            echo'<a style="text-decoration:none" href="fullsize.php?image=',$source,'&id=',$id,'"><div id="',$highlightid,'"><img class="roundednot" style="float:left;padding:5px;" src="http://www.photorankr.com/',$newsource,'" height="50" width="50" />&nbsp;<div style="float:left;margin-top:20px;margin-left:10px;"><img src="graphics/trending.png" height="18" />&nbsp;&nbsp;&nbsp;Your photo is now trending</div></div></a>';

                                        }

                                        elseif($type == "follow") {

                                            $newaccount = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$followeremail'");
                                            $ownerid = mysql_result($newaccount,0,'user_id');
                                            $profilepic = mysql_result($newaccount,0,'profilepic');
                                            if($profilepic == "") {
                                                $profilepic = "profilepics/default_profile.jpg";
                                            }
                                            
                                            echo'<a style="text-decoration:none" href="viewprofile.php?u=',$ownerid,'&id=',$id,'"><div id="',$highlightid,'"><img class="roundednot" style="float:left;padding:5px;" src="',$profilepic,'" height="50" width="50" />&nbsp;<div style="float:left;margin-top:20px;margin-left:10px;"><img style="margin-left:-10px;" src="graphics/follower.png" height="19" />&nbsp;&nbsp;&nbsp;<b>',$fullname,'</b> is now following your photography</div></div></a>';

                                        }

                                
                                } //end of for loop
                                echo'</div>';

                                } //numnots > 0

                                
                                elseif($numnots < 1) {

                                    echo'<div style="position:relative;width:400px;height:80px;overflow-y:scroll;top:30px;">
                                    <div style="color:black;font-size:15px;font-family:helvetica neue,helvetica,arial;font-weight:400;text-align:center;">You have no new notifications &#8230;</div>
                                    </div>';
                                
                                }
                        
                                echo'
								</ul>	
                                </li>
                            
                            
						<li class="dropdown topcenter marginT" id="accountmenu" style="position:relative;">
							<a style="text-decoration:none;" class="dropdown-toggle" data-toggle="dropdown" href="myprofile.php"><div class="profile" style="text-decoration:none;margin-top:-15px;padding:4px;padding-right:8px;"><a style="text-decoration:none;" href="myprofile.php"><img src="',$sessionprofilepic,'" style="width:30px;height:30px;"/><span style="font-size:13px;color:white;font-weight:200;">&nbsp;&nbsp;&nbsp;',$sessionname,'</span></a></div></a>
								<ul class="dropdown-menu" style="margin-top:0px;background-color:#fff;width:150px;">
                                    <li> <a href="myprofile.php"> Profile </a> </li>
                                    <li class="divider"></li>
                                    <li> <a href="myprofile.php?view=portfolio"> Portfolio </a> </li>
                                    <li class="divider"></li>
                                    <li> <a href="myprofile.php?view=favorites"> Favorites </a> </li>
                                    <li class="divider"></li>
                                    <li> <a href="myprofile.php?view=cart"> My Cart </a> </li>
                                    <li class="divider"></li>
                                    <li> <a href="myprofile.php?view=upload"> Upload </a> </li>
                                    <li class="divider"></li>';
                                    if(strpos($_SERVER['REQUEST_URI'],'myprofile.php') !== false) {
                                        echo'<li> <a href="newest.php?action=logout"> Log Out </a> </li>';
                                    }
                                    elseif(strpos($_SERVER['REQUEST_URI'],'?') !== false) {
                                        echo'<li> <a href="',$_SERVER['REQUEST_URI'],'&action=logout"> Log Out </a> </li>';
                                    }   
                                    else {
                                         echo'<li> <a href="',$_SERVER['REQUEST_URI'],'?action=logout"> Log Out </a> </li>';
                                    }
                                    echo'
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


function footermarket() {
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



?>