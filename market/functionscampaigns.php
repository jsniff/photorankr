<?php
ini_set('max_input_time', 300);   

function createprofthumbnail($filename) {  
		ini_set('max_input_time', 1200);
      
        require 'configcampaigns.php';  
        
        $filename=str_replace("JPG","jpg",$filename);

        if(preg_match('/[.](jpg)$/', $filename)) {  
            $im = imagecreatefromjpeg($path_to_profpic_directory . $filename);  
        } else if (preg_match('/[.](gif)$/', $filename)) {  
            $im = imagecreatefromgif($path_to_profpic_directory . $filename);  
        } 
        else if (preg_match('/[.](jpeg)$/', $filename)) {  
            $im = imagecreatefromjpeg($path_to_profpic_directory . $filename);  
        }
        else if (preg_match('/[.](png)$/', $filename)) {  
            $im = imagecreatefrompng($path_to_profpic_directory . $filename);  
        } 
      
      
        $ox = imagesx($im);  
        $oy = imagesy($im);  
      
        $nx = $final_width_of_image;  
        $ny = $final_height_of_image;  
      
        $nm = imagecreatetruecolor($nx, $ny);  
      
        imagecopyresampled($nm, $im, 0,0,0,0,$nx,$ny,$ox,$oy);  
      
        if(!file_exists($path_to_profpic_directory)) {  
          if(!mkdir($path_to_profpic_directory)) {  
               die("There was a problem. Please try again!");  
          }  
           }  
      
        imagejpeg($nm, $path_to_profpic_directory . $filename);  
        //$tn = '<img src="' . $path_to_profpic_directory . $filename . '" alt="image" />';  
        //$tn .= '<br />Upload Successful!';  
        //echo $tn;  
chmod ($path_to_profpic_directory . $filename, 0644);
}  


//Navbar function
function navbar() {  
echo'

<!--NAVIGATION BAR-->
<div class="navbar" style="z-index:10;padding-top:0px;font-size:16px;width:100%;min-width:1140px">
	<div class="navbar-inner">
		<div class="container">
			    <ul class="nav">
					<li><a class="brand" style="margin-top:0px;margin-right:20px;padding-bottom:9px;" href="index.php"><div style="margin-top:-2px"><img src="graphics/logocampaign.png" width="260" /></div></a></li>
					<li><a style="color:#21608E;margin-top:4px;padding-bottom:9px;" style="color:rgb(56,85,103);margin-right:20px;" href="viewcampaigns.php">All Campaigns</a></li>
                    <li><a style="color:#21608E;margin-top:4px;padding-bottom:9px;margin-right:20px;" href="mycampaigns.php">My Campaigns</a></li>
                    <li><a style="color:#21608E;margin-top:4px;padding-bottom:9px;margin-right:20px;" href="createcampaign.php">Create Campaign</a></li>';
                    @session_start();
                    if($_SESSION['loggedin'] == 2) {
                        echo'
                        <li><a style="color:#21608E;margin-top:4px;margin-right:20px;" href="',htmlentities($_SERVER['PHP_SELF']),'?action=logout">Log Out</a></li>';
                    }
                   
                     else {
                        echo'
                        <li class="dropdown">
                        <a style="color:#21608E;margin-top:3px;padding-bottom:10px;" href="signin.php" class="dropdown-toggle" data-toggle="dropdown">Log In<b class="caret"></b></a>
						<ul class="dropdown-menu" data-dropdown="dropdown" style="width:200px;">
                        <li><a style="color:#21608E;margin-left:-29px;font-size:15px;" href="signin.php">Register for free today</a></li>
                        <li><br/></li>
                        <form name="login_form" method="post" action="',htmlentities($_SERVER['PHP_SELF']),'?action=login">
                        <li style="margin-left: 5px; margin-right: 5px; width: 185px;color:#21608E;"><span style="margin-bottom: 5px;margin-left:10px;">Email: <br /></span><input type="text" style="width:150px;margin-top:3px;margin-left:10px;" name="emailaddress" /></li>
							<li><span style="margin-left:-16px;color:#21608E;">Password: <br /></span><input type="password" style="width:150px;margin-top:3px;margin-left:-16px;" name="password"/></li>
                        <li style="margin-left: 110px;"><input type="submit" class="btn btn-success" value="Sign In" id="loginButton"/></li>
                        </form>
						</ul>';
                    }
               
                     echo'
				</div> 
   			</div>
		</div>
	<!--/END NAVBAR-->

	<br /><br /><br />';
}



//NAVBAR NEW
function navbarnew() {  
echo'
<div style="width:100%;height:65px;border-bottom:1px solid #999;" class="gradient">
<img style="float:left;padding-left:30px;padding-top:18px;" src="graphics/logocampaign.png" width="260" />
<label style="float:left;font-size:13px;color:#333;padding-top:23px;padding-left:100px;font-weight:bold;">Search</label>
<input class="search" style="position:relative;margin-left:10px;margin-top:15px;" type="text">
<span style="float:right;padding-right:50px;padding-top:23px;">
<a href="#">Marketplace</a>&nbsp;&nbsp;|&nbsp;&nbsp;
<a href="#">Campaigns</a>&nbsp;&nbsp;|&nbsp;&nbsp;
<a href="#">My Account</a>&nbsp;&nbsp;&nbsp;&nbsp;
</span>
</div>
';
}


function navbar2() {  
echo'

<!--NAVIGATION BAR-->
<div class="navbar" style="position:relative;z-index:10;padding-top:0px;font-size:16px;width:950px;">
	<div class="navbar-inner">
		<div class="container">
			    <ul class="nav">
                    <li><a style="color:#21608E;margin-top:2px;padding-bottom:9px;" style="color:rgb(56,85,103);margin-right:20px;" href="viewcampaigns.php">Style</a></li>
                    <li><a style="color:#21608E;margin-top:2px;padding-bottom:9px;margin-right:20px;" href="mycampaigns.php">Use</a></li>
                    <li><a style="color:#21608E;margin-top:2px;padding-bottom:9px;margin-right:20px;" href="createcampaign.php">File Size</a></li>
                    <li><a style="color:#21608E;margin-top:2px;padding-bottom:9px;margin-right:20px;" href="createcampaign.php">Resolution</a></li>
                    <li><a style="color:#21608E;margin-top:2px;padding-bottom:9px;margin-right:20px;" href="createcampaign.php">Release</a></li>
                    <li><a style="color:#21608E;margin-top:2px;padding-bottom:9px;margin-right:20px;" href="createcampaign.php">Other</a></li>';
                                   
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