<?php

if ($_GET['method'] == "login") { // if login form has been submitted

@session_start();

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
            $_SESSION['loggedin'] = 1;
            $_SESSION['email'] = mysql_real_escape_string($_POST['emailaddress']);
        }
        //gives error if the password is wrong
        else if (mysql_real_escape_string($shapass) != mysql_real_escape_string($info['password'])) {
            header('Location: signup.php?action=lp');
            die();   
        }

}
    
    //VARIABLES
    $action = htmlentities($_GET['action']);
    $saveinfo = htmlentities($_GET['saveinfo']);
    $saveaddinfo = htmlentities($_GET['saveaddinfo']);
    
    //User information
    $userinfo = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$email'");
    $profilepic = mysql_result($userinfo,0,'profilepic');
    $sessionpic = mysql_result($userinfo,0,'profilepic');
    $userid = mysql_result($userinfo,0,'user_id');
    $balance = mysql_result($userinfo,0,'balance');
    $paypal_email = mysql_result($userinfo,0,'paypal_email');
    $firstname= mysql_result($userinfo,0,'firstname');
    $lastname = mysql_result($userinfo,0,'lastname');
    $sessionfirst = $firstname;
    $sessionlast = $lastname;
    $fullname = $firstname ." ". $lastname;
    $age = mysql_result($userinfo,0,'age');
    $gender = mysql_result($userinfo,0,'gender');
    $location = mysql_result($userinfo,0,'location');
    $password = mysql_result($userinfo,0,'password');
    $camera = mysql_result($userinfo,0,'camera');
    $facebookpage = mysql_result($userinfo,0,'facebookpage');
    $twitterpage = mysql_result($userinfo,0,'twitteraccount');
    $website = mysql_result($userinfo,0,'website');
    $bio = mysql_result($userinfo,0,'bio');
    $quote = mysql_result($userinfo,0,'quote');
    
     if($saveaddinfo == 'true') {
        if(isset($_POST['facebookpage'])) {$facebookpage=mysql_real_escape_string($_POST['facebookpage']); }
        if(isset($_POST['twitterpage'])) {$twitterpage=mysql_real_escape_string($_POST['twitterpage']); }
		if(isset($_POST['website'])) {$website=mysql_real_escape_string($_POST['website']); }
        
        $addinfoupdatequery=("UPDATE userinfo SET facebookpage='$facebookpage', twitteraccount='$twitterpage', website='$website' WHERE emailaddress='$email'");
        $runupdate = mysql_query($addinfoupdatequery);
        
    }

    if($saveinfo == 'true') {
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
        
        //require files that will help with picture uploading and thumbnail creation/display
		require 'config.php';
	
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
				/*if(preg_match('/[.](jpg)$/', $newfilename)) {  
            				$extension = ".jpg";
        			} else if (preg_match('/[.](gif)$/', $newfilename)) {  
            				$extension = ".gif"; 
        			} else if (preg_match('/[.](png)$/', $newfilename)) {  
            				$extension = ".png";  
        			}*/
        			
                    $currenttime = time();
                    $newfilename = $currenttime . $newfilename;
                    $source = $_FILES['file']['tmp_name'];  
        			$profilepic = $path_to_profpic_directory . $newfilename; 
  
        			move_uploaded_file($source, $profilepic);  
                    chmod($profilepic, 0777);
                    
                    createprofthumbdim($profilepic);
        			createprofthumbnail($profilepic);
					
    			}  
		}  

        $infoupdatequery=("UPDATE userinfo SET firstname = '$firstname', lastname = '$lastname', age = '$age', gender = '$gender', location = '$location', camera = '$camera',  quote='$quote', bio='$bio', profilepic='$profilepic' WHERE emailaddress='$email'");
        $runupdate = mysql_query($infoupdatequery);
    
    }
    
        <link rel="stylesheet" type="text/css" href="css/bootstrap1.css" />
     	<link href = "css/main2 2.css" rel="stylesheet" type="text/css"/>
 		<link rel="stylesheet" type="text/css" href="css/all.css"/>
        <link href = "css/reset.css" rel="stylesheet" type="text/css"/>
        <link href = "css/grid.css" rel="stylesheet" type="text/css"/>



.fixedTop
		{
			position: fixed;
			top: 0px;
			}
				.triangle
	{
		width: 0; 
		height: 0; 
		border-left: 11px solid transparent;
		border-right: 11px solid transparent;
		border-bottom: 12px solid #ddd;
		position: relative;
		top:-16px;
		left:107px;
	}
	.triangleLeft
	{
		width: 0; 
		height: 0; 
		border-top: 15px solid transparent;
		border-bottom: 15px solid transparent;
		border-right: 16px solid #eee;
		position: relative;
		top:50px;
		left:310px;
		z-index: 1000;
	}
	#spec{
		background: none;float:left;height:55px !important;width:55px !important;margin:-11px 0 0 100px !important;
	}
	#spec:hover
	{
		background: none;
	}
	#spec img
	{
		width: 182px !important	;
		height:42px !important;
	}
	.scroll
	{
		position:relative !important;
		margin:15px 0 0 0 !important;
	}
    .statoverlay

{
background-attachment: scroll;
background-clip: border-box;
background-color: 
rgba(0, 0, 0, 0.848438);
background-image: none;
background-origin: padding-box;
color: rgb(255, 255, 255);
bottom: 0px;
display: block;
font-family: 'Helvetica Neue', 'Helvetica Neue', Helvetica, Arial, sans-serif;
font-size: 14px;
font-style: normal;
font-variant: normal;
font-weight: normal;
line-height: 0px;
margin-bottom: 0px;
margin-left: 0px;
margin-right: 0px;
margin-top: 0px;
overflow-x: hidden;
overflow-y: hidden;
padding-bottom: 0px;
padding-left: 0px;
padding-right: 0px;
padding-top: 0px;
white-space: nowrap;
width: 270px;
-moz-box-shadow: 1px 1px 5px #888;
-webkit-box-shadow: 1px 1px 5px #888;
box-shadow: 1px 1px 5px #888;
}
<div class="CNav" style="position:fixed;top:0;left:0;z-index:10000;">
<div class="homeNav" style="width:100%;z-index:10000;">
	<ul>
		<li id="spec"> <img src="graphics/logo_big_w.png" style="height:55px;margin-top:8px;width:55px"/> </li>
	</ul>
</div></div>

    <?php
    
         if($_GET['action'] == "step1") {echo'<div style="text-align:center;">About You &ndash; Step 1 of 3</div>';}
         elseif($_GET['action'] == "step2") {echo'<div style="text-align:center;">Optional Info &ndash; Step 2 of 3</div>';}
         elseif($_GET['action'] == "step3") {echo'<div style="text-align:center;"> Begin Your Discovery &ndash; Step 3 of 3</div>';}
        else {echo'<div style="text-align:center;"> Complete your sign up for free. </div>';}
    ?>
   </h1>

<div class="container_24" style="height:70%;">

   <div class="grid_24 tutorialBox">
        <div style="overflow:hidden;padding-top:10px;padding-bottom:10px;border-bottom:2px solid white;">
        
        <?php
            if($action == 'step1') {
            echo'
            <div class="tutRecGreen">
                <div style="color:#fff;padding:8px 20px;padding-bottom:0px;font-size:15px;font-weight:500;">Step 1</div>
                <div style="color:#fff;padding:0px 20px;font-size:14px;font-weight:300;">About you</div>
            </div>
            <div class="tutTriangleGreen"></div>';
            }
            
            else {
            echo'
            <div class="tutRec">
                <div style="color:#333;padding:8px 20px;padding-bottom:0px;font-size:15px;font-weight:500;">Step 1</div>
                <div style="color:#333;padding:0px 20px;font-size:14px;font-weight:300;">About you</div>
            </div>
            <div class="tutTriangle"></div>';
            }
            
            if($action == 'step2') {
            echo'
            <div class="tutRecGreen">
                <div style="color:#fff;padding:8px 20px;padding-bottom:0px;font-size:15px;font-weight:500;">Step 2</div>
                <div style="color:#fff;padding:0px 20px;font-size:14px;font-weight:300;">Optional Info</div>
            </div>
            <div class="tutTriangleGreen"></div>';
            }
            
            else {
            echo'
            <div class="tutRec">
                <div style="color:#333;padding:8px 20px;padding-bottom:0px;font-size:15px;font-weight:500;">Step 2</div>
                <div style="color:#333;padding:0px 20px;font-size:14px;font-weight:300;">Optional Info</div>
            </div>
            <div class="tutTriangle"></div>';
            }
            
            if($action == 'step3') {
            echo'
            <div class="tutRecGreen">
                <div style="color:#fff;padding:8px 20px;padding-bottom:0px;font-size:15px;font-weight:500;">Step 3</div>
                <div style="color:#fff;padding:0px 20px;font-size:14px;font-weight:300;">Begin Discovering</div>
            </div>
            <div class="tutTriangleGreen"></div>';
            }
            
            else {
            echo'
            <div class="tutRec">
                <div style="color:#333;padding:8px 20px;padding-bottom:0px;font-size:15px;font-weight:500;">Step 3</div>
                <div style="color:#333;padding:0px 20px;font-size:14px;font-weight:300;">Begin Discovering</div>
            </div>
            <div class="tutTriangle"></div>';
            }
            
        ?>
            
            <?php
                if($action == 'step1') { echo'<a style="font-size:15px;font-weight:300;float:right;margin-top:20px;margin-right:50px;" href="tutorial.php?action=step2">Skip</a>';
                }
                if($action == 'step2') { echo'<a style="font-size:15px;font-weight:300;float:right;margin-top:20px;margin-right:50px;" href="tutorial.php?action=step3">Skip</a>';
                }
                if($action == 'step3') { echo'<a style="font-size:15px;font-weight:500;float:right;margin-top:15px;margin-right:50px;" class="btn btn-success" href="profile.php">Continue to Profile</a>';
                }
            ?>
            
        </div>
        
    <?php 
        
    //Step #1
        if($action == 'step1') {
           echo'<div class="step1BoxLeft">
                    <ul style="margin-top:-5px;">
                        <li>First Name:</li>
                        <li>Last Name:</li>
                        <li>Location:</li>
                        <li>Age:</li>
                        <li>Gender:</li>
                        <li>Camera:</li>
                        <li>Profile Photo:</li>
                    </ul>
                </div>
                
                <form action="tutorial.php?action=step2&saveinfo=true" method="post">
                
                <div class="step1BoxRight">
                    <ul>
                        <li><input type="text" name="firstname" placeholder="First Name" value="',$sessionfirst,'" /></li>
                        <li><input type="text" name="lastname" placeholder="Last Name" value="',$sessionlast,'" /></li>
                        <li><input type="text" name="location" placeholder="Location" value="',$location,'" /></li>
                        <li><input type="text" name="age" placeholder="Age" value="',$age,'" /></li>';
                    echo'<div style="width:230px;height:30px;margin-top:5px;margin-left:3px;">';
                    if($gender == 'Male') {
                        echo '<input type="radio" name="gender" value="Male" checked="checked" /> Male&nbsp;&nbsp; 
                        <input type="radio" name="gender" value="Female" /> Female&nbsp;&nbsp;';
                    }
                    else {
                        echo '<input type="radio" name="gender" value="Male" /> Male&nbsp;&nbsp; 
                        <input type="radio" name="gender" value="Female" checked="checked" /> Female&nbsp;&nbsp;';
                    }       
                    
                    echo'
                    </div>
                        <li><input type="text" name="camera" placeholder="Camera" value="',$camera,'" /></li>
                        <li style="font-size:14px;width:300px;"><input type="file" name="file" placeholder="Location" value="" /></li>

                    </ul>
                </div>
                
                <div style="float:left;width:100px;margin-top:25px;text-align:center;">
                    <div style="font-size:16px;font-weight:300;width:180px;">About your photography:</div>
                    <textarea style="padding:10px;resize:none;width:410px;height:120px;" name="bio">',$bio,'</textarea>
                    <div style="overflow:hidden;width:300px;margin-left:110px;">
                        <button type="submit" class="btn btn-success" style="float:left;margin-top:30px;padding:4px;width:180px;font-size:15px;font-weight:500;">Save and Continue</button>
                    </div>
                    
                    </form>
                    
                </div>';
        
        }
        
        
        //Step #2
        if($action == 'step2') {
           echo'<div class="step1BoxLeft">
                    <ul style="margin-top:-8px;">
                        <li style="width:130px!important;">Facebook Page:</li>
                        <li style="width:130px!important;">Twitter Page:</li>
                        <li style="width:130px!important;">Personal Website:</li>
                        <li style="width:130px!important;">PayPal Email:<br /><span style="font-size:12px;">(For monthly payments)</span></li>
                    </ul>
                </div>
                
                <form action="tutorial.php?action=step3&saveaddinfo=true" method="post">
                <div class="step1BoxRight" style="width:300px;">
                    <ul>
                        <li><input type="text" name="facebookpage" placeholder="Facebook URL" value="',$facebookpage,'" /></li>
                        <li><input type="text" name="twitterpage" placeholder="Twitter URL" value="',$twitterpage,'" /></li>
                        <li><input type="text" name="website" placeholder="Website URL" value="',$website,'" /></li>
                        <li><input type="text" name="website" placeholder="PayPal Email Address" value="',$paypal,'" /></li>
                    </ul>
                </div>
                
                <div style="margin-left:120px;float:left;width:100px;margin-top:70px;">
                    <button type="submit" class="btn btn-success" style="padding:4px;width:180px;font-size:15px;font-weight:500;">Save and Continue</button>
                </div>
                
                </form>';
        
        }
        
        
        //Step #3
        if($action == 'step3') {
          
           echo'<div style="padding: 10px 0 0 40px;font-size:23px;font-weight:300;">
                   Photos to Favorite & Like:
               </div>';
               
                //top photographers to follow
            $topphotos = mysql_query("SELECT * FROM photos WHERE faves > 7 ORDER BY id DESC LIMIT 4");
            
            echo'<div style="width:100%;overflow:hidden;padding:0 50px;">';
            
            for($iii=0; $iii<4; $iii++) {
                $caption = mysql_result($topphotos,$iii,'caption');
                $id = mysql_result($topphotos,$iii,'id');
                $source = mysql_result($topphotos,$iii,'source');
                $source = str_replace('userphotos/','userphotos/medthumbs/',$source);
                
                echo'<div style="float:left;width:195px;margin-left:10px;">
                        <div style="float:left;width:190px;height:220px;overflow:hidden;float:left;margin-left:10px;margin-top:30px;"><a style="text-decoration:none;" href="http://photorankr.com/fullsize.php?imageid=',$id,'">
                
                    <img id="roundCorners" onmousedown="return false" oncontextmenu="return false;" style="min-height:180px;min-width:190px;" src="',$source,'" height="160" width="180" /></a>
                    <div style="background-color:white;font-size:15px;font-weight:300;padding-left:10px;padding-top:0px;">',$caption,'</div>
                    </div>
                    
                </div>';
     	
                }
            echo'</div>';
                    
          echo'<div style="padding: 10px 0 0 40px;font-size:23px;font-weight:300;">
                    Popular Photographers to Follow:
               </div>';
               
            //top photographers to follow
            $topphotogs = mysql_query("SELECT * FROM userinfo WHERE reputation > 50 ORDER BY user_id DESC LIMIT 4");
            
            echo'<div style="width:100%;overflow:hidden;padding:0 50px;padding-bottom:25px;">';
            
            for($iii=0; $iii<4; $iii++) {
                $fullname = mysql_result($topphotogs,$iii,'firstname') ." ". mysql_result($topphotogs,$iii,'lastname');
                $user_id = mysql_result($topphotogs,$iii,'user_id');
                $profilepic = mysql_result($topphotogs,$iii,'profilepic');
                $owner = mysql_result($topphotogs,$iii,'emailaddress');
                
                $topphotos = mysql_query("SELECT id,source FROM photos WHERE emailaddress = '$owner' ORDER BY faves DESC LIMIT 4");
                $photo1 = mysql_result($topphotos,0,'source');
                $photo1 = str_replace('userphotos/','userphotos/medthumbs/',$photo1);
                $photo2 = mysql_result($topphotos,1,'source');
                $photo2 = str_replace('userphotos/','userphotos/medthumbs/',$photo2);
                $photo3 = mysql_result($topphotos,2,'source');
                $photo3 = str_replace('userphotos/','userphotos/medthumbs/',$photo3);
                $photo4 = mysql_result($topphotos,3,'source');
                $photo4 = str_replace('userphotos/','userphotos/medthumbs/',$photo4);
                
                echo'<div style="float:left;width:410px;margin-left:10px;">
                        <div style="float:left;width:190px;height:200px;overflow:hidden;float:left;margin-left:10px;margin-top:30px;"><a style="text-decoration:none;" href="http://photorankr.com/viewprofile.php?u=',$user_id,'">
                
                    <img id="roundCorners" onmousedown="return false" oncontextmenu="return false;" style="min-height:180px;min-width:190px;" src="',$profilepic,'" height="160" width="180" /></a>
                    <div style="background-color:white;font-size:15px;font-weight:300;padding-left:10px;padding-top:0px;">',$fullname,'</div>
                    </div>
                    
                    <!---owners top 4 photos--->
                    <div style="float:left;width:200px;overflow:hidden;margin-top:30px;">
                        <img style="float:left;padding:1px;width:94px;height:98px;" src="',$photo1,'" />
                        <img style="float:left;padding:1px;width:94px;height:98px;" src="',$photo2,'" />
                        <img style="float:left;padding:1px;width:94px;height:98px;" src="',$photo3,'" />
                        <img style="float:left;padding:1px;width:94px;height:98px;" src="',$photo4,'" />
                    </div>
                    
                </div>';
     	
                }
            echo'</div>';
        
        }
        
    ?>
    
   </div>
   
   <div style="width:100%;clear:both;height:100px;"></div>
   
</div>