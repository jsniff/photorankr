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
        } else if (preg_match('/[.](png)$/', $filename)) {  
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
        } else if (preg_match('/[.](png)$/', $filename)) {  
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
        } else if (preg_match('/[.](png)$/', $filename)) {  
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

//Create Medium Thumbnail
function createMedThumbnail($filename) {  
ini_set('max_input_time', 300);
      
        require 'config.php';  
        
        $filename=str_replace("JPG","jpg",$filename);

        if(preg_match('/[.](jpg)$/', $filename)) {  
            $im = imagecreatefromjpeg($path_to_image_directory . $filename);  
        } else if (preg_match('/[.](gif)$/', $filename)) {  
            $im = imagecreatefromgif($path_to_image_directory . $filename);  
        } else if (preg_match('/[.](png)$/', $filename)) {  
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

//Navbar function
function navbar() {  
echo'

<!--NAVIGATION BAR-->
<div class="navbar" style="z-index:10;padding-top:0px;font-size:14px;width:100%;min-width:1140px">
	<div class="navbar-inner">
		<div class="container">
			    <ul class="nav">
					
					<li><a style="color:#21608E;margin-top:4px;padding-bottom:9px;" style="color:rgb(56,85,103);margin-right:0px;" href="viewcampaigns.php">All Campaigns</a></li>
                    <li><a style="color:#21608E;margin-top:4px;padding-bottom:9px;margin-right:0px;" href="mycampaigns.php">My Campaigns</a></li>
                    <li><a style="color:#21608E;margin-top:4px;padding-bottom:9px;margin-right:0px;" href="createcampaign.php">Create Campaign</a></li>
                    
                    <li><a class="brand" style="margin-top:0px;margin-right:20px;padding-bottom:9px;" href="index.php"><div style="margin-top:-2px"><img src="graphics/logocampaign.png" width="260" /></div></a></li>';
                    
                    @session_start();
                    if($_SESSION['loggedin'] == 2) {
                        echo'
                        <li><a style="color:#21608E;margin-top:4px;margin-right:20px;" href="',htmlentities($_SERVER['PHP_SELF']),'?action=logout">Log Out</a></li>';
                    }
                   
                     else {
                        echo'
                        <form name="login_form" method="post" action="',htmlentities($_SERVER['PHP_SELF']),'?action=login">
                       <li><input type="text" style="width:100px;margin-top:3px;" name="emailaddress" /></li>
							<li><input type="password" style="width:100px;margin-top:3px;margin-left:-16px;" name="password"/></li>
                        //<li style="margin-left: 110px;"><input type="submit" class="btn btn-success" value="Sign In" id="loginButton"/></li>
                        </form>
						';
                    }
               
                     echo'
				</div> 
   			</div>
		</div>
	<!--/END NAVBAR-->

	<br /><br /><br />';
}

function login() {
    @session_start();

        // makes sure they filled it in
        if(!htmlentities($_POST['emailaddress']) | !htmlentities($_POST['password'])) {
            die('You did not fill in a required field.');
        }

        $check = mysql_query("SELECT * FROM campaignusers WHERE repemail = '".mysql_real_escape_string($_POST['emailaddress'])."'")or die(mysql_error());
        //Gives error if user dosen't exist

        $check2 = mysql_num_rows($check);
    
        if ($check2 == 0) {
            die('That user does not exist in our database. <a href="campaignnewuser.php">Click Here to Register</a> or <a href="lostpassword.php">here to recover a forgotten password</a>.');
        }

        $info = mysql_fetch_array($check);

        if(mysql_real_escape_string($_POST['password']) == mysql_real_escape_string($info['password'])){
            //then redirect them to the same page as signed in and set loggedin to 1
            $_SESSION['loggedin']=2;
            $_SESSION['repemail'] = mysql_real_escape_string($_POST['emailaddress']);
        }
        //gives error if the password is wrong
        else if (mysql_real_escape_string($_POST['password']) != mysql_real_escape_string($info['password'])) {
            die('Incorrect password, please try again. <a href="lostpassword.php"> Lost your password?</a>');   
        }
}

function logout() {
    session_start();
    $_SESSION['loggedin'] = 0;
    $_SESSION['repemail'] = "";

    session_destroy();
}

function get_footer() {
    echo '<center>Copyright &copy; 2012 PhotoRankr, Inc.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="terms.php">Terms</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="http://photorankr.com/contact.php">Contact Us</a></center>';
}

?>