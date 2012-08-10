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

if($_GET['action'] == "remove" && $_GET['option'] == 'campaigns') {
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


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

 <meta property="og:image" content="http://photorankr.com/<?php echo $profilepic; ?>">
    <title>PhotoRankr - Your Account</title>
   <meta name="Generator" content="EditPlus">
  <meta name="Author" content="PhotoRankr, PhotoRankr.com">
  <meta name="Keywords" content="photos, sharing photos, photo sharing, photography, photography club, sell photos, sell photography, where to sell my photography, good sites for selling photography, making money from photography, making money off photography, social networking, social network, social networks, where to sell my photos, good sites for selling photos, good site to sell photos, making money from photos">
  <meta name="Description" content="PhotoRankr allows photographers of all skill levels to sell and share their work. Create your photostream cutomized to what you want to see. Add photos to your favorites, rank them, and watch them trend. Build your portfolio with Photorankr.">


  <link rel="stylesheet" type="text/css" href="css/bootstrapNew.css" />
    <link rel="stylesheet" href="960_24.css" type="text/css" />
        <link rel="stylesheet" href="css/style.css" type="text/css" />
  <link rel="stylesheet" href="css/text2.css" type="text/css" />

  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
  <script src="js/bootstrap.js" type="text/javascript"></script>
  <script src="js/bootstrap-dropdown.js" type="text/javascript"></script>
  <script src="js/bootstrap-collapse.js" type="text/javascript"></script>
  <link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>

  
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


.statoverlay

{
opacity:.6;
filter:alpha(opacity=40);
z-index:1;
transition: opacity .5s;
-moz-transition: opacity .5s;
-webkit-transition: opacity .5s;
-o-transition: opacity .5s;
}



 .statoverlay2

{
opacity:.6;
filter:alpha(opacity=40);
z-index:1;
transition: opacity .5s;
-moz-transition: opacity .5s;
-webkit-transition: opacity .5s;
-o-transition: opacity .5s;
}
            
.statoverlay:hover
{
opacity:.6;
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
<body style="overflow-x:hidden;min-width:1220px;">

<?php navbarsweet(); ?>  
<div class="container_24"><!--START CONTAINER-->


<!--Following Modal-->
<div class="modal hide fade" id="fwmodal" style="overflow:hidden;">
      
<?php
if($_SESSION['loggedin'] !== 2) {

echo'
<div class="modal-header">
<a style="float:right" class="btn btn-primary" data-dismiss="modal">Close</a>
<img style="margin-top:-4px;" src="graphics/coollogo.png" height="28" width="90" />&nbsp;&nbsp;<span style="font-size:16px;">Please log in to follow ',$fullname,'</span>
  </div>
  <div modal-body" style="width:600px;">

<div id="content" style="font-size:16px;width:500px;">
		
<img class="roundedall" style="margin-left:10px;margin-top:5px;" src="',$profilepic,'" 
height="120px" width="120px" />

<div style="width:500px;margin-left:150px;margin-top:-100px;">
',$fullname,'<br />                 

',$numberofpics,' photos <br />

Portfolio Average: ',$portfolioranking,' <br /><br /><br />

</div>
</div>';

    }
        
        
if($_SESSION['loggedin'] == 2) {
    
		$emailquery=("SELECT * FROM userinfo WHERE emailaddress ='$repemail'");
		$emailresult=mysql_query($emailquery);
		$prevemails=mysql_result($emailresult, 0, "following");
		$viewerfirst = mysql_result($emailresult, 0, "firstname");
		$viewerlast = mysql_result($emailresult, 0, "lastname");
		if($prevemails == "") {$emailaddressformatted="'". $emailaddress . "'";}
		else {$emailaddressformatted=", '". $emailaddress . "'";}
        
        //MAKE SURE NOT FOLLOWING SELF
        if($email == $useremail) {
       echo'
<div class="modal-header">
<a style="float:right" class="btn btn-primary" data-dismiss="modal">Close</a>
<img style="margin-top:-4px;" src="../graphics/logoteal.png" height="28" width="90" />&nbsp;&nbsp;<span style="font-size:16px;">Oops, you accidentally tried to follow yourself.</span>
  </div>
  <div modal-body" style="width:600px;">

<div id="content" style="font-size:16px;width:500px;">
		
<img class="circle" style="margin-left:10px;margin-top:5px;" src="',$profilepic,'" 
height="100px" width="100px" />

<div style="width:500px;margin-left:150px;margin-top:-100px;">
',$fullname,'<br />                 

',$numphotos,' photos <br />

Portfolio Average: ',$portfolioranking,' <br /><br /><br />

</div>
</div>';

        }
        
        
        else {
		//MAKE SURE FOLLOWER ISN'T ADDED TWICE
		$search_string=$prevemails;
		$regex="/$emailaddress/";
		$match=preg_match($regex,$search_string);
		if ($match > 0) {
			echo'
<div class="modal-header">
<a style="float:right" class="btn btn-primary" data-dismiss="modal">Close</a>
<img style="margin-top:-4px;" src="../graphics/logoteal.png" height="28" width="90" />&nbsp;&nbsp;<span style="font-size:16px;">You are already following this photographer</span>
  </div>
  <div modal-body" style="width:600px;">

<div id="content" style="font-size:16px;width:500px;">
		
<img class="circle" style="margin-left:10px;margin-top:5px;" src="',$profilepic,'" 
height="100px" width="100px" />

<div style="width:500px;margin-left:150px;margin-top:-100px;">
',$fullname,'<br />                 

',$numphotos,' photos <br />

Portfolio Average: ',$portfolioranking,' <br /><br /><br />

</div>
</div>';
		} 

else {
            
			echo'
<div class="modal-header">
<a style="float:right" class="btn btn-primary" href="../viewprofile.php?u=', $user_id,'&fw=1">Close</a>
<img style="margin-top:-4px;" src="../graphics/logoteal.png" height="28" width="90" />&nbsp;&nbsp;<span style="font-size:16px;">You are now following ',$firstname,' ',$lastname,'</span>
  </div>
  <div modal-body" style="width:600px;">

<div id="content" style="font-size:16px;width:500px;">
		
<img style="border: 1px solid black;margin-left:10px;margin-top:30px;" src="',$profilepic,'" 
height="100px" width="100px" />

<div style="width:500px;margin-left:130px;margin-top:-90px;">
',$firstname,' ',$lastname,'<br />                 

',$numberofpics,' photos <br />

Portfolio Average: ',$portfolioranking,' <br /><br /><br /><br />

</div>
</div>';
            
  }
    }
} 
        
        
        
?>

</div>
</div>


<!--LEFT SIDEBAR-->
<div class="grid_24" style="width:1120px;">
<div class="grid_4 pull_1 rounded" style="background-color:#eeeff3;position:relative;top:80px;width:250px;">

<div style="width:240px;height:140px;">
<div class="roundedall" style="float:left;overflow:hidden;margin-left:15px;margin-top:15px;">
<img src="<?php echo $logo; ?>" height="120" width="120"/>
</div>
<a data-toggle="modal" href="#fwmodal" data-backdrop="static" class="btn btn-success" style="float:left;width:70px;margin-top:40px;margin-left:10px;font-size:14px;font-weight:150;">Support</a>
</div>

<div style="width:250px;margin-top:0px;">
<div style="font-size:18px;text-align:center;font-weight:200;"><?php echo $fullname; ?></div>
</div>

<div style="text-align:center;font-size:14px;font-weight:200;width:250px;height:100px;margin-top:20px;">
<div style="margin-left:20px;text-align:center;">
   <div style="float:left;"><p>Campaigns:&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;</p></div>
   <div style="float:left;margin-top:-4px;"><p><span style="font-size:20px;">#</span> Saved Photos</p></div>
</div>

<div style="position:relative;top:-15px;margin-left:50px;text-align:center;font-size:20px;">
   <div style="float:left;"><p><?php echo $numcampaigns; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p></div>
   <div style="float:left;"><p><?php echo $nump; ?></p></div>
</div>

</div>

<div style="position:relative;top:-30px;">
<hr style="font-size:50px;">
<a style="text-decoration:none;color:black;font-weight:100;" href="account.php?view=saved"><div style="width:250px;margin-top:-10px;padding-bottom:4px;">
<span class="green" style="text-align:center;font-size:24px;padding-left:15px;<?php if($view == 'saved') {echo'color:#6aae45;';} ?>">Saved Photos&nbsp;&nbsp;<img style="float:right;padding-top:5px;padding-right:20px;" src="graphics/info.png" height="30" width="30"></span>
</div></a>

<hr>
<a style="text-decoration:none;color:black;font-weight:100;" href="account.php?view=downloads"><div style="width:250px;margin-top:-10px;padding-bottom:4px;">
<span class="green" style="text-align:center;font-size:24px;padding:15px;<?php if($view == 'downloads') {echo'color:#6aae45;';} ?>">Downloads&nbsp;&nbsp;<img style="float:right;padding-top:5px;padding-right:20px;" src="graphics/info.png" height="30" width="30"></span>
</div></a>

<hr>
<a style="text-decoration:none;color:black;font-weight:100;" href="account.php?view=photogs"><div style="width:250px;margin-top:-10px;padding-bottom:4px;">
<span class="green" style="text-align:center;font-size:24px;padding:15px;<?php if($view == 'photogs') {echo'color:#6aae45;';} ?>">Photographers&nbsp;&nbsp;<img style="float:right;padding-top:5px;padding-right:20px;" src="graphics/info.png" height="30" width="30"></span>
</div></a>

<hr>
<a style="text-decoration:none;color:black;font-weight:100;" href="account.php?view=account"><div style="width:250px;margin-top:-10px;padding-bottom:4px;">
<span class="green" style="text-align:center;font-size:24px;padding:15px;<?php if($view == 'account') {echo'color:#6aae45;';} ?>">Edit Account&nbsp;&nbsp;<img style="float:right;padding-top:5px;padding-right:20px;" src="graphics/info.png" height="30" width="30"></span>
</div></a>

</div>

</div><!--end 4 grid-->

<div class="grid_18 roundedright" style="background-color:#eeeff3;height:60px;margin-top:80px;width:800px;margin-left:-45px;">

<a style="text-decoration:none;color:black;" href="account.php"><div class="clicked" style="width:180px;height:60px;border-right:1px solid #ccc;float:left;<?php if($view == '') {echo'background-color:#bbb;color:white;';} ?>"><div style="font-size:25px;font-weight:100;margin-top:10px;text-align:center;">My Cart</div></div></a>

<a style="text-decoration:none;color:black;" href="account.php?view=mycampaigns"><div class="clicked" style="width:180px;height:60px;border-right:1px solid #ccc;border-left:1px solid #ccc;float:left;<?php if($view == 'mycampaigns') {echo'background-color:#bbb;color:white;';} ?>"><div style="font-size:25px;font-weight:100;margin-top:10px;text-align:center;">My Campaigns</div></div></a>

<a style="text-decoration:none;color:black;" href="account.php?view=prefs"><div class="clicked" style="width:180px;height:60px;border-right:1px solid #ccc;float:left;<?php if($view == 'prefs') {echo'background-color:#bbb;color:white;';} ?>"><div style="font-size:25px;font-weight:100;margin-top:10px;text-align:center;">My Preferences</div></div></a>

<div style="width:180px;height:60px;float:left;"><div style="font-size:25px;font-weight:100;margin-top:6px;text-align:center;">
<form class="navbar-search" method="GET">
<input class="search" style="position:relative;margin-left:15px;margin-top:2px;font-family:helvetica;font-size:14px;font-weight:100;color:black;" name="searchterm" placeholder="Search Saved Photos&nbsp;.&nbsp;.&nbsp;.&nbsp;" type="text">
</form></div></div>


<?php

    $searchterm = htmlentities($_GET['searchterm']);

    if($view == '' && !$searchterm) {
       
        echo'<div id="container" class="grid_18" style="width:770px;margin-top:20px;padding-left:20px;">';
         
    //PHOTO CART INFORMATION
    $imageid = htmlentities($_GET['imageid']);
   $pricephoto = htmlentities($_GET['price']);
    $imagequery = mysql_query("SELECT * FROM photos WHERE id = '$imageid'");
    $imagenewsource = mysql_result($imagequery,0,'source');
    $imagenewsource2 = str_replace("userphotos/", "$_SERVER[DOCUMENT_ROOT]/userphotos/",$imagenewsource);
    $imagenewsource3 = str_replace("$_SERVER[DOCUMENT_ROOT]/userphotos/", "http://photorankr.com/userphotos/",$imagenewsource2); 
    $imagenewprice = mysql_result($imagequery,0,'price'); 
    
    //ADD TO CART IN DB
    
        if($_SESSION['loggedin'] != 2) {
        echo'
        <div style="margin-top:70px;margin-left:260px;padding-bottom:150px;">
        <div style="text-align:center;font-size:18px;">Login Below or <a href="campaignnewuser.php">Register to Buy:</a></div><br />
        <form name="login_form" method="post" action="fullsize2.php?imageid=',$imageid,'&action=login">
        <div class="well" style="width:380px;padding-top:50px;padding-bottom:50px;padding-left:40px;">
        <span style="font-size:18px;font-family:helvetica, arial;margin-left:0px;">Email: </span><input type="text" style="width:200px;margin-left:40px;" name="emailaddress" /><br />
        <span style="font-size:18px;font-family:helvetica, arial;">Password: </span>&nbsp<input type="password" style="width:200px;" name="password"/><br >
        <input type="submit" class="btn btn-success" style="margin-left:250px;" value="sign in" id="loginButton"/>
        </div>
        </form>
        </div>';
        
        }
    
        elseif($_SESSION['loggedin'] == 2) {
       
        if($imageid) {
        $cartcheck = mysql_query("SELECT * FROM cart WHERE imageid = '$imageid'");
        $numincart = mysql_num_rows($cartcheck);
        if($numincart < 1) {
            $stickincart = mysql_query("INSERT INTO cart (source,emailaddress,imageid,price) VALUES ('$imagenewsource3','$repemail','$imageid', '$pricephoto')");
            }
        }
        
        $incart = mysql_query("SELECT * FROM cart WHERE emailaddress = '$repemail'");
        $incartresults = mysql_num_rows($incart);
        
        for($iii=0; $iii < $incartresults; $iii++) {
            $imagesource[$iii] = mysql_result($incart,$iii,'source');
            $imageprice[$iii] = mysql_result($incart,$iii,'price');
            $imagecartid = mysql_result($incart,$iii,'imageid');
            $totalcartprice = $imagecartid+$totalcartprice;
            $cartidlist = $cartidlist.",".$imagecartid;
            list($width, $height)=getimagesize($imagesource[$iii]);
            $width = $width/5.5;
            $height = $height/5.5;
            
            echo'
            <div class="span9">
            <a style="text-decoration:none;color:#333;" href="fullsize2.php?imageid=',$imagecartid,'">
            <table class="table">
            <thead>
            <tr>
            <th>Photo</th>
            <th>Size</th>
            <th>Image ID</th>
            <th>License</th>
            <th>Price</th>  
            </tr>
            </thead>
            <tbody>
            <tr>
            <td><div style="min-width:400px;height:<?php echo $height; ?>px;width:<?php echo $width; ?>px;"><img onmousedown="return false" oncontextmenu="return false;" src="',$imagesource[$iii],'" height=',$height,' width=',$width,' /></div></td>
            <td>Medium</td>
            <td>',$imagecartid,'</td>
            <td>Royalty Free</td>
            <td>$',$imageprice[$iii],'</td>
            </tr>
            <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            </tr>
            </tbody>
            </table>
            </a>
            </div>';

        }
        
        //check if image already in db
        $found = strpos($cartidlist, $imageid);
        
        if($imageid && $found === false) {
        //New image displayed
        echo'
         <div class="span12">
            <a style="text-decoration:none;color:#333;" href="fullsize2.php?imageid=',$imageid,'">
            <table class="table">
            <thead>
            <tr>
            <th>Photo</th>
            <th>Size</th>
            <th>Image ID</th>
            <th>License</th>
            <th>Price</th>  
            </tr>
            </thead>
            <tbody>
            <tr>
            <td><div style="width:400px;"><img onmousedown="return false" oncontextmenu="return false;" style="height:25%;" src="',$imagenewsource3,'" /></div></td>
            <td>Medium</td>
            <td>',$imageid,'</td>
            <td>Royalty Free</td>
            <td>$',$imagenewprice,'</td>
            </tr>
            <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            </tr>
            </tbody>
            </table>
            </a>
            </div>
            
            <div><a class="btn btn-success" href="',$_SERVER['HTTP_REFERER'],'">Continue Shopping</a>
            </div>';
        }
        
        
    if($incartresults > 0) {
        
        echo'<div class="grid_18"><a name="added" style="color:black;text-decoration:none;" href="#"><div style="padding:15px;padding-right:200px;background-color:#ddd;width:100px;margin-left:-0px;margin-top:20px;"><span style="font-size:22px;font-weight:200;">Payment</span></div></a></div><br />
        
        <!--STRIPE PAYMENT FORM-->
    
        <div class="grid_18" style="margin-top:35px;">
         <label class="creditcards" style="float:left;font-size:16px;">We accept:&nbsp;&nbsp;<img src="card.jpg" style="width:215px;height:25px;margin-top:0px;border-radius:2px;"/> </label> <br /><br /><br />
         <label style="float:left;font-size:16px;" class="creditcards">Card Number:&nbsp;&nbsp;</label>
         <input style="float:left;font-size:15px;padding:6px;position:relative;top:-7px;width:170px;" type="text" size="20" autocomplete="off" class="card-number" style;"/><br /><br /><br />
            
                <label style="float:left;font-size:16px;" class="creditcards">CVC <span style="font-size:15px;">(Verification #):</span>&nbsp;&nbsp;</label>
                <input style="float:left;font-size:16px;padding:6px;position:relative;top:-7px;width:40px;" type="text" size="4" autocomplete="off" class="card-cvc"/><br /><br /><br />
                
                <label style="float:left;font-size:16px;" class="creditcards" >Expiration <span style="font-size:15px;">(MM/YYYY):</span>&nbsp;&nbsp;</label>
                <input type="text" style="float:left;width:50px;padding:6px;position:relative;top:-7px;width:30px;font-size:16px;" class="card-expiry-month"/>
                <span style="float:left;font-size:30px;font-weight:100;">&nbsp;/&nbsp;</span>
                <input style="float:left;padding:6px;position:relative;top:-7px;width:60px;font-size:16px;" type="text" class="card-expiry-year"/><br /><br /><br />
               
   <button type="submit" class="button submit btn btn-success" style="font-size:16px;float:left;margin-top:5px;padding-top:10px;padding-bottom:10px;padding-right:40px;padding-left:40px;font-weight:200;">Submit Payment</button>
   <br /><br /><br /><div></div>
        </div>';
        
        }
        
        
        
        
 } //end if logged in

echo'</div>';
       
}


if($view == '' && $searchterm) {

    echo'<div id="container" class="grid_18" style="width:770px;margin-top:20px;padding-left:20px;">';

    $query = mysql_query("SELECT * FROM maybe JOIN photos ON maybe.imageid = photos.id WHERE concat(photos.caption, photos.tag, photos.camera, photos.tag1, photos.tag2, photos.tag3, photos.tag4, photos.singlecategorytags, photos.singlestyletags, photos.location, photos.country, photos.about, photos.sets, photos.maintags, photos.settags) LIKE '%$searchterm%' AND maybe.emailaddress = '$repemail' ORDER BY photos.views DESC LIMIT 0,60");
$numresults = mysql_num_rows($query);

    for($iii=0; $iii<$numresults; $iii++) {
                        $photo[$iii] = mysql_result($query, $iii, "source");
                        $photo2[$iii] = str_replace("http://photorankr.com/userphotos/","../userphotos/medthumbs/", $photo[$iii]);
                        $photoid[$iii] = mysql_result($query, $iii, "id");
                        $imageid[$iii] = mysql_result($query, $iii, "imageid");
                        $caption = mysql_result($query, $iii, "caption");
                        $price = mysql_result($query, $iii, "price");

                        list($height,$width) = getimagesize($photo2[$iii]);
                        $widthnew = $width / 2.8;
                        $heightnew = $height / 2.8;
                
                echo'
                  <div class="fPic" id="',$id,'" style="width:245px;height:245px;overflow:hidden;float:left;margin-left:10px;margin-top:30px;">
                
                <div class="statoverlay" style="z-index:1;left:0px;top:180px;position:relative;background-color:black;width:245px;height:75px;"><p style="line-spacing:1.48;padding:5px;color:white;"><span style="font-size:16px;font-weight:100;">',$caption,'</span><br><span style="font-size:20px;font-weight:100;">$',$price,'</span></p><a name="removed" href="account.php?view=saved&pd=',$photoid[$iii],'&action=remove#return"><button class="btn btn-primary" style="z-index:12;position:relative;top:-52px;float:right;margin-right:5px;">Remove Photo</button></a></div>
                
                <a href="fullsize2.php?imageid=',$imageid[$iii],'">
                <img onmousedown="return false" oncontextmenu="return false;" style="position:relative;top:-90px;min-height:265px;min-width:245px;" src="',$photo[$iii],'" height="',$heightls,'px" width="',$widthls,'px" /></a></div>';
   
                }


    echo'</div>';
}


elseif($view == 'prefs') {

         echo'<div id="container" class="grid_18" style="width:770px;margin-top:20px;padding-left:20px;">';
         
         
         if($_GET['saved'] == 'yes') {
         
            $singlestyletags = $_POST['singlestyletags'];	
        	$singlecategorytags = $_POST['singlecategorytags'];
            
            //Concatenate single photo box tags
        	$numbersinglestyletags = count($singlestyletags);
    		for($i=0; $i < $numbersinglestyletags; $i++)
    		{
      			$singlestyletags2 = $singlestyletags2 . " " . mysql_real_escape_string($singlestyletags[$i]) . " ";
    		}
            
        	$numbersinglecategorytags = count($singlecategorytags);
    		for($i=0; $i < $numbersinglecategorytags; $i++)
    		{
       			$singlecategorytags2 = $singlecategorytags2 . " " . mysql_real_escape_string($singlecategorytags[$i]) . " ";
        	}
             
            $preferenceslist = $singlestyletags2 . $singlecategorytags2;    
            $prefquery = "UPDATE campaignusers SET prefs='$preferenceslist' WHERE repemail = '$repemail'";
            $runprefquery = mysql_query($prefquery);

            echo'<div style="color:#6aae45;font-size:16px;">Preferences Saved</div>';
                     
         }

echo'
<form action="account.php?view=prefs&saved=yes" method="post" >

    <div style="font-size:16px;font-weight:200;padding:10px;">Choose your photo preferences:</div>
    
        <table class="table">
        <tbody style="font-size:14px;font-weight:200;">
        
        <tr>
        <td>Photography Categories:</td>
        <td>
        <span style="font-size:13px">(Selecting multiple values: Hold down command button if on mac, control button if on PC)</span>
            <br /><br />
            <select style="width:150px;height:150px;" multiple="multiple" name="singlecategorytags[]">
            <option value="Advertising">Advertising</option>
            <option value="Aerial">Aerial</option>
            <option value="Animal">Animal</option>
            <option value="Architecture">Architecture</option>
            <option value="Astro">Astro</option>
            <option value="Aura">Aura</option>
            <option value="Automotive">Automotive</option>
            <option value="Botanical">Botanical</option>
            <option value="Candid">Candid</option>
            <option value="Commercial">Commercial</option>
            <option value="Corporate">Corporate</option>
            <option value="Documentary">Documentary</option>
            <option value="Fashion">Fashion</option>
            <option value="Fine Art">Fine Art</option>
            <option value="Food">Food</option>
            <option value="Historical">Historical</option>
            <option value="Industrial">Industrial</option>
            <option value="Musical">Musical</option>
            <option value="Nature">Nature</option>
            <option value="News">News</option>
            <option value="Night">Night</option>
            <option value="People">People</option>
            <option value="Scenic">Scenic</option>
            <option value="Sports">Sports</option>
            <option value="Still Life">Still Life</option>
            <option value="Transportation">Transportation</option>
            <option value="Urban">Urban</option>
            <option value="War">War</option>
            </select>

            <span style="padding-left:70px;">
            <select style="width:150px;height:150px;" multiple="multiple" name="singlestyletags[]">
            <option value="B&W">Black and White</option>
            <option value="Cityscape">Cityscape</option>
            <option value="Fisheye">Fisheye</option>
            <option value="HDR">HDR</option>
            <option value="Illustration">Illustration</option>
            <option value="InfraredUV">Infrared/UV</option>
            <option value="Landscape">Landscape</option>
            <option value="Long Exposure">Long Exposure</option>
            <option value="Macro">Macro</option>
            <option value="Miniature">Miniature</option>
            <option value="Monochrome">Monochrome</option>
            <option value="Motion Blur">Motion Blur</option>
            <option value="Night">Night</option>
            <option value="Panorama">Panorama</option>
            <option value="Photojournalism">Photojournalism</option>
            <option value="Portrait">Portrait</option>
            <option value="Stereoscopic">Stereoscopic</option>
            <option value="Time Lapse">Time Lapse</option>
            </select>
            
            </span>
        </td>
        </tr>
        
        </tbody>
        </table>

        <button class="btn btn-success" type="submit">Save Preferences</button>
        </form>
        </div>
    
        </div>';


}


elseif($view == 'saved') {
            
            $option = htmlentities($_GET['option']);
    
             echo'<br /><br /><br /><br /><div style="width:760px;text-align:center;font-size:14px;font-weight:200;"><div style="margin-left:20px;"><a class="green" style="text-decoration:none;color:#000;';if($option == '') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'" href="account.php?view=saved">Market Photos</a> | <a class="green" style="text-decoration:none;color:#000;';if($option == 'campaigns') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'" href="account.php?view=saved&option=campaigns">Campaign Photos</a></div></div>';
        
         echo'<div id="container" class="grid_18" style="width:770px;margin-top:-10px;padding-left:20px;">';
         
         
    if($option == '') {
         
                $marketquery = mysql_query("SELECT * FROM maybe WHERE emailaddress = '$repemail'");
                $numsavedinmarket = mysql_num_rows($marketquery);
          
                for($iii=0; $iii<$numsavedinmarket; $iii++) {
                        $photo[$iii] = mysql_result($marketquery, $iii, "source");
                        $photo2[$iii] = str_replace("http://photorankr.com/userphotos/","../userphotos/medthumbs/", $photo[$iii]);
                        $photoid[$iii] = mysql_result($marketquery, $iii, "id");
                        $imageid[$iii] = mysql_result($marketquery, $iii, "imageid");
                        $caption = mysql_result($marketquery, $iii, "caption");
                        $price = mysql_result($marketquery, $iii, "price");

                        list($height,$width) = getimagesize($photo2[$iii]);
                        $widthnew = $width / 2.8;
                        $heightnew = $height / 2.8;
                
                echo'
                  <div class="fPic" id="',$id,'" style="width:245px;height:245px;overflow:hidden;float:left;margin-left:10px;margin-top:30px;">
                
                <div class="statoverlay" style="z-index:1;left:0px;top:180px;position:relative;background-color:black;width:245px;height:75px;"><p style="line-spacing:1.48;padding:5px;color:white;"><span style="font-size:16px;font-weight:100;">',$caption,'</span><br><span style="font-size:20px;font-weight:100;">$',$price,'</span></p><a name="removed" href="account.php?view=saved&pd=',$photoid[$iii],'&action=remove#return"><button class="btn btn-primary" style="z-index:12;position:relative;top:-52px;float:right;margin-right:5px;">Remove Photo</button></a></div>
                
                <a href="fullsize2.php?imageid=',$imageid[$iii],'">
                <img onmousedown="return false" oncontextmenu="return false;" style="position:relative;top:-90px;min-height:265px;min-width:245px;" src="',$photo[$iii],'" height="',$heightls,'px" width="',$widthls,'px" /></a></div>';
   
                }
    
        } //end of option == nothing (market photos)
        
        
    elseif($option == 'campaigns') {
    
            for($iii=0; $iii < mysql_num_rows($photosresult); $iii++) {
            //get the information for the current photo
            $photobig[$iii] = mysql_result($photosresult, $iii, "source");
            $photo[$iii] = str_replace("userphotos/","userphotos/medthumbs/", $photobig[$iii]);
            $points = mysql_result($photosresult, $iii, "points");
            $votes = mysql_result($photosresult, $iii, "votes");
            $average = number_format(($points / $votes),2);
            $photoid = mysql_result($photosresult, $iii, "id");
            $caption = mysql_result($photosresult, $iii, "caption");
            $caption = strlen($caption) > 30 ? substr($caption,0,27). " &#8230;" : $caption;
            
            list($height,$width) = getimagesize($photobig[$iii]);
            $widthnew = $width / 3.8;
            $heightnew = $height / 3.8;
    
            echo'
                  <div class="fPic" id="',$id,'" style="width:245px;height:245px;overflow:hidden;float:left;margin-left:10px;margin-top:30px;"><a href="fullsize2.php?imageid=',$photoid,'">
                
                <div class="statoverlay" style="z-index:1;left:0px;top:180px;position:relative;background-color:black;width:245px;height:75px;"><p style="line-spacing:1.48;padding:5px;color:white;"><span style="font-size:16px;font-weight:100;">',$caption,'</span><br><span style="font-size:20px;font-weight:100;">Score: ',$average,'</span></p><a name="removed" href="account.php?view=saved&option=campaigns&pd=',$photoid,'&action=remove#return"><button class="btn btn-primary" style="z-index:12;position:relative;top:-52px;float:right;margin-right:5px;">Remove Photo</button></a></div>
                
                <img onmousedown="return false" oncontextmenu="return false;" style="position:relative;top:-90px;min-height:265px;min-width:245px;" src="',$photo[$iii],'" height="',$heightls,'px" width="',$widthls,'px" /></a></div>';
                
            } //end foor loop
              
    } //end of option == 'campaign' (campaign photos)
    
    
         echo'</div>';
    
    }
    

elseif($view == 'downloads') {

         echo'<div id="container" class="grid_18" style="width:770px;margin-top:-10px;padding-left:20px;">';    
    
    for($iii=0;$iii<$numdownloads;$iii++) {
    $downloadsource = mysql_result($downquery,$iii,'source');
    
    list($height,$width) = getimagesize($downloadsource);
    $widthnew = $width / 3;
    $heightnew = $height / 3;
    
    
            echo'
                  <div class="fPic" id="',$id,'" style="width:245px;height:245px;overflow:hidden;float:left;margin-left:10px;margin-top:30px;"><a href="fullsize2.php?imageid=',$id,'">
                
            <div class="statoverlay" style="z-index:1;left:0px;top:180px;position:relative;background-color:black;width:245px;height:75px;"><p style="line-spacing:1.48;padding:5px;color:white;"><span style="font-size:16px;font-weight:100;">',$caption,'</span><br>
            <form name="download_form" method="post" action="downloadphoto.php">
                <input type="hidden" name="image" value="',$downloadsource,'">
                <button type="submit" name="submit" value="download" class="btn btn-warning" style="margin-top:-45px;opacity:1;margin-left:12px;width:220px;height:35px;font-size:18px;">Download Photo</button>
            </form>
            </div>
                <img onmousedown="return false" oncontextmenu="return false;" style="position:relative;top:-90px;min-height:265px;min-width:245px;" src="',$downloadsource,'" height="',$heightls,'px" width="',$widthls,'px" /></a></div>';
                
        }//end for loop
        
    echo'</div>';

}


    
    elseif($view == 'mycampaigns') {
    
    $option = htmlentities($_GET['option']);
    
     echo'<br /><br /><br /><br /><div style="width:760px;text-align:center;font-size:14px;font-weight:200;"><div style="margin-left:20px;"><a class="green" style="text-decoration:none;color:#000;';if($option == '') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'" href="account.php?view=mycampaigns">All</a> | <a class="green" style="text-decoration:none;color:#000;';if($option == 'current') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'" href="account.php?view=mycampaigns&option=current">Current</a> | <a class="green" style="text-decoration:none;color:#000;';if($option == 'previous') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'" href="account.php?view=mycampaigns&option=previous">Previous</a> | <a class="green" style="text-decoration:none;color:#000;';if($option == 'create') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'" href="account.php?view=mycampaigns&option=create">Create Campaign</a></div></div>';
        
    
     echo'<div id="container" class="grid_18" style="width:770px;margin-top:20px;padding-left:20px;">';
    
    $currenttime = time();
    
    if($option == '') {
        $allcampaignsquery = "SELECT * FROM campaigns WHERE repemail = '$repemail' ORDER BY endtime DESC";
        $allcampaignsresult = mysql_query($allcampaignsquery);
    }
    elseif($option == 'current') {
        $allcampaignsquery = "SELECT * FROM campaigns WHERE repemail = '$repemail' AND endtime > '$currenttime' ORDER BY endtime DESC";
        $allcampaignsresult = mysql_query($allcampaignsquery);
    }
    elseif($option == 'previous') {
        $allcampaignsquery = "SELECT * FROM campaigns WHERE repemail = '$repemail' AND endtime < '$currenttime' ORDER BY endtime DESC";
        $allcampaignsresult = mysql_query($allcampaignsquery);
    }
    
    $numcurrentcamps = mysql_num_rows($allcampaignsresult);

	//now group photos by their campaign which will be used later on
	$randphotoquery = "SELECT source, campaign FROM campaignphotos GROUP BY campaign";
	$randphotoresult = mysql_query($randphotoquery);

	//loop through the results to create arrays of the needed campaign info and of a photo to display

	for($iii=0; $iii < $numcurrentcamps; $iii++) {
		//find out all the info about this campaign
		$endtime           = mysql_result($allcampaignsresult, $iii, "endtime");
		$quote[$iii]       = mysql_result($allcampaignsresult, $iii, "quote");
		$title[$iii]       = mysql_result($allcampaignsresult, $iii, "title");
		$description[$iii] = mysql_result($allcampaignsresult, $iii, "description");
		$id[$iii]          = mysql_result($allcampaignsresult, $iii, "id");
            $numentriesquery = mysql_query("SELECT id FROM campaignphotos WHERE campaign = '$id[$iii]'");
            $numentries = mysql_num_rows($numentriesquery);
            
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
		}

		echo '
         <a style="text-decoration:none;color:#000;" href="managecampaign.php?id=',$id[$iii],'">
		<div style="width:780px;border:1px solid #ccc;overflow:hidden; margin-right: 20px;">
       <div class="phototitle" style="float:left;padding-bottom:0px;"><img  style="" src="', $coverphoto[$iii], '" height="140px" width="140px" /><div style="text-align:center;float:bottom;font-size:14px;font-weight:300;padding:10px;">',$numentries,' Entries</div></div>
        <div style="width:700px;height:150px;position:relative;left:30px;"><div style="padding-top:25px;font-size:22px;font-family:helvetica;font-weight:100;">',$title[$iii],'</div><br />
        <div style="font-size:15px;margin-top:-5px;">Reward: $',$quote[$iii],'&nbsp;&nbsp;|&nbsp;&nbsp;Time Left: '; if($daysleft > 0) {echo $daysleft, ' days, ', $hoursleft, ' hours, ', $minutesleft, ' minutes';} elseif($daysleft < 0) {echo'This campaign is over.';} echo'<br /><br /><div style="margin-top:-5px;">Description: ',$description[$iii],'</div>
        </div>
        </div></a>';
	}
    
    
    if($option == 'create') {    
    //Campaign Agreement Modal
    
        echo'<div class="modal hide fade" id="campaignagreement" style="overflow-y:scroll;overflow-x:hidden;width:850px;margin-left:-400px;">

<div class="modal-header">
<a style="float:right" class="btn btn-primary" data-dismiss="modal">Close</a>
<img style="margin-top:-4px;" src="graphics/logocampaign.png" width="220" />&nbsp;&nbsp;<span style="font-size:16px;">Campaign Content License Agreement</span>
</div>
<div modal-body" style="width:700px;">
<div id="content" style="font-size:16px;width:830px;height:400px;overflow-x:hidden;margin-top:10px;margin-left:10px;">
<div>
<pre style="font-family:helvetica,arial;font-size:13px;padding-left:10px;margin-right:20px;">
<div style="text-align:center;font-size:15px;font-weight:bold;">INTELLECTUAL PROPERTY LICENSE AGREEMENT</div>';
echo
htmlentities("

    In the event the Licensee selects as the Image, the image, or images, as the case may be, submitted by the Licensor as part of a PhotoRankr Tender, then the Licensee and Licensor will be deemed to enter into a separate and identical binding agreement in relation to the license of the Image and the rights of the Licensor in relation to such Image.

    For the avoidance of doubt:

    This Agreement is in addition to the terms applicable to the Site, which includes without limitation the terms of use, non disclosure agreement, privacy policy, or any other policy or procedure communicated by PhotoRankr from time to time;

    PhotoRankr and its third party providers will not be a party to this separate agreement and will have no liability whatsoever in relation to the performance or failure to perform of a Licensor or Licensee under the terms of this separate agreement.

    The agreement of the Licensor to the terms and conditions set out below is shown by clicking on the 'Agree' button.  By clicking on the 'Agree' button below, you represent and warrant that you have read and understood all of the terms and conditions set forth below and agree to be bound by them.  If you do not agree to such terms, you should not click the 'Agree' button below.  If you click on the 'Agree' button on behalf of your employer, you represent and warrant that you have full legal authority to bind your employer or such other entity.  If you do not have such authority, you should not click the 'Agree' button below.

1.  Definitions

    Unless inconsistent with the context, the following expressions shall have the following meanings:

    'Agreement' means this Intellectual Property License Agreement;

    'Business Day' means any day which is not a Saturday, Sunday, or a national holiday in the United States;

    'Image' means the image or images, as the case may be, which the Licensee selects as the winning image pursuant to the PhotoRankr Tender;

    'PhotoRankr' means PhotoRankr, Inc., a corporation of the State of Delaware, with registered agent located at 160 Greentree Drive, Suite 101, Dover, Delaware 19904;

    'PhotoRankr Tender' means a tender held by the Licensor on the Site, pursuant to which prospective photographers submit images for review and consideration by the Licensor;

    'Intellectual Property' means the Image; 

    'Licensee' means 'Sample Licensee,' identified also by the username 'John Doe'; 

    'Licensor' means 'Campaign Winner' identified also by the username 'Campaign Winner'; 

    'Site' means www.photorankr.com. 

2.  Interpretation 

    In these terms and conditions, unless the context otherwise indicates:

(a) References to any statute, ordinance, or other law shall include all regulations and other instruments thereunder and all consolidations, amendments, re-enactments, or replacements thereof.

(b) Words importing the singular shall include the plural and vice versa, words importing a gender shall include other genders and references to a person shall be construed as references to an individual, firm, body corporate, association (whether incorporated or not), government, and governmental, semi-governmental, and local authority or agency.

(c) Where any word or phrase is given a defined meaning in these terms and conditions, any other part of speech or other grammatical form in respect of such word or phrase shall have a corresponding meaning.

3.  License

(a) Unless otherwise agreed by the Licensor and the Licensee in writing, the Licensor grants the Licensee a license to use the Image as follows:

    (i) Term:  {#Months/Years}, starting from $today;

    (ii)    Territory of Use: $location;

    (iii)   Permitted Uses: $uses;

    (iv)    Exclusive or Non-Exclusive Use: $licenses;

    (v) if the Permitted Uses in (iii) above include Web Advertising, Digital Banners, Social Media, Web Video, E-mail Promotion and Electronic Brochure, Apps, E-Book, Corporate, Retail and Promotional Site, then worldwide territory is hereby granted;

    (vi)    Additional Terms: {AdditionalTerms}

(b) The Licensee must only use the Image as expressly permitted by this Agreement.

(c) Notwithstanding any other provision of this Agreement, the Licensee must not use the Image for any pornographic use, in a manner which is obscene or immoral, for any unlawful purpose, to defame any person, or to violate any person’s right to privacy or publicity.

4.  Third Party Rights

(a) The Licensor agrees, represents and warrants that:

    (i) the Image does not infringe any reputation or intellectual property right of a third party;

    (ii)    all relevant authors have agreed not to assert their moral rights (personal rights associated with authorship of a work under applicable law) in the Image;

    (iii)   if the Image incorporates the intellectual property rights of a third party, then the Licensor has obtained a license from the relevant third party to incorporate the intellectual property rights of that third party in the Image ('Third Party License');

    (iv)    the Third Party License permits the Licensee with a worldwide, royalty free, perpetual right to display, distribute, and reproduce (in any form) the intellectual property rights of the third party contained in the Image.

(b) In the event that the Third Party License is capable of assignment to the Licensee, then the Licensee hereby assigns and transfers to the Licensor, and the Licensee hereby agrees to take an assignment and transfer thereof, the Third Party License and all of the rights and obligations of the Licensor under the Third Party License.

5.  Indemnity

    The Licensor must indemnify and keep indemnified the Licensee from and against all loss, cost, expense (including legal costs and attorneys’ fees) or liability whatsoever incurred by the Licensee arising from any claim, demand, suit, action, or proceeding by any person against the Licensee where such loss or liability arose out of an infringement, or alleged infringement, of the intellectual property rights of any person, which occurred by reason of the license of the Image by the Licensor.

6.  Liability of PhotoRankr and Its Third Party Providers

    Both the Licensor and the Licensee acknowledge and agree that: 

(a) PhotoRankr and its Third Party Providers are not parties to this Agreement; and

(b) each of PhotoRankr and its Third Party Providers shall each not be liable or responsible for any breach of this Agreement by any one or more of the Licensee and the Licensor.

7.  Representations and Warranties

(a) The Image is provided 'as is,' and, to the fullest extent permitted under the applicable law, the Licensor hereby expressly disclaims any and all warranties of any kind or nature, whether express, implied, or statutory.

(b) The Licensee acknowledges and confirms that the Licensor does not make any warranty or representation that the Image will satisfy the requirements of the Licensee.

8.  Termination

    Notwithstanding any other provisions of this Agreement, the Licensor has the right to immediately terminate this Agreement and the license granted hereunder if the Licensee has breached any of its obligations under this Agreement.

9.  Assignment

    This Agreement is personal to each of the Licensee and the Licensor, and may not be assigned without the prior written consent of the other party.

10. Further Assurances

    Each of the parties will upon request by any other party hereto at any time and from time to time, execute, sign, and deliver all documents, and do all things necessary or appropriate to evidence or carry out the intent and purposes of these Terms.

11. Entire Agreement

    These Terms, and any attachments thereto, including, but not limited to, releases from models, property owners, and minors, constitute the entire agreement between the parties and supersedes all prior representations, agreements, statements, and understanding, whether verbal or in writing.

12. Notices

    A notice or other communication given under this Agreement, including, but not limited to, a request, demand, consent, or approval, to or by a party to this Agreement: 

(a)     must be in legible writing and in English;

(b)     must be addressed to the addressee at the mailing address or e-mail address set forth below or to any other mailing address or e-mail address a party notifies to the others in writing:

    (i) if to the Licensor: 
        Mailing Address: {LicensorAddress}
        E-mail Address: {LicensorE-mailAddress}




    (ii)    if to the Licensee: 
        Mailing Address: {LicenseeAddress}
        E-mail Address: {LicenseeE-mailAddress}


(c) without limiting any other means by which a party may be able to prove that a notice has been received by another party, a notice is deemed to be received:

    (i) if sent by hand, when delivered to the addressee;

    (ii)    if by mail, three Business Days from, and including, the date of postmark; or

    (iii)   if by e-mail transmission, on receipt by the sender of an e-mail acknowledgment or read receipt generated by the e-mail client to which the email was sent, but if the delivery or receipt is on a day which is not a Business Day or is after 5.00 p.m. (addressee's time) it is deemed to be received at 9.00 a.m. on the following Business Day.

13. Miscellaneous

(a) Tax Liability.  Licensee agrees to pay and be responsible for any and all sales taxes, use taxes, value added taxes and duties imposed by any jurisdiction as a result of the license granted to Licensee, or Licensee's use of the Image, pursuant to this Agreement. 

(b) Severability.  If any provision of this Agreement is found to be invalid or otherwise unenforceable under any applicable law, such invalidity or unenforceability shall not be construed to render any other provisions contained herein as invalid or unenforceable, and all such other provisions shall be given full force and effect to the same extent as though the invalid or unenforceable provision were not contained herein.

(c) Applicable Law.  This Agreement shall be governed by and construed in accordance with the laws of the State of Delaware without regard to the conflict of law principles of Delaware or any other jurisdiction.  This Agreement will not be governed by the United Nations Convention on Contracts for the International Sale of Goods, the application of which is expressly excluded.  

(d) Arbitration.  Any controversy or claim arising out of or relating to this contract, or the breach thereof, shall be determined by arbitration administered by the American Arbitration Association in accordance with its International Arbitration Rules.  The number of arbitrators shall be one.  The arbitration shall be held, and the award shall be rendered, in English.

(e) Waiver.  No action of Licensor, other than express written waiver, may be construed as a waiver of any provision of this Agreement.  A delay on the part of Licensor in the exercise of its rights or remedies will not operate as a waiver of such rights or remedies, and a single or partial exercise by Licensor of any such rights or remedies will not preclude other or further exercise of that right or remedy.  A waiver of a right or remedy by Licensor on any one occasion will not be construed as a bar to or waiver of rights or remedies on any other occasion.

(f) Section Headings.  The descriptive headings of this Agreement are for convenience only and shall be of no force or effect in construing or interpreting any of the provisions of this Agreement.

14. Electronic Acceptance

    The parties have executed this Agreement as of the date of the Licensor clicking the 'Agree' button.

THE LICENSOR

{LicensorName}
{LicensorUserName}
{LicensorE-mailAddress}


THE LICENSEE

{LicenseeName}
{LicenseeUserName}
{LicenseeE-mailAddress}


");

echo'
</pre>
</div>

</div>
</div>
</div>';
    
    echo'
    <table class="table">
    <tbody>
    
    <tr>
    <td>Campaign Title:</td>
    <td><input style="width:300px;" type="text" name="title" value="',htmlentities($_POST['title']),'" placeholder="Bridge over troubled water" /></td>
    </tr>

    <tr>
    <td>Description of Photo: </td>
    <td><textarea style="width:300px;" rows="4" cols="100" name="description" ">',htmlentities($_POST['description']),'</textarea></td>
    </tr>
    
    <tr>
    <td>Example/Logo Link:</td>
    <td><input style="width:300px;" type="text" name="examplelink" placeholder="Optional link to a similar photo or your company logo" /></td>
    </tr>
    
    <tr>
    <td>What will you be using the photo for? </td>
    <td><input type="checkbox" name="use[]" value="print" /> Print (magazine, newspaper, brochure, etc.) &nbsp;&nbsp;&nbsp; <input type="checkbox" name="use[]" value="webuse" /> Web Use &nbsp;&nbsp;&nbsp;<br /> <input type="checkbox" name="use[]" value="emailpromotion" /> Email Promotion &nbsp;&nbsp;&nbsp; <input type="checkbox" name="use[]" value="personaluse" /> Personal Use </td>
    </tr>
    
    <tr>
    <td>Choose the license you would like for your photo:</td>
    <td><input type="checkbox" name="license[]" value="nonexclusive" /> Non-Exclusive &nbsp;&nbsp;&nbsp; <input type="checkbox" name="license[]" value="exclusive" /> Exclusive &nbsp;&nbsp;&nbsp;</td>
    </tr>
    
    <tr>
    <td>Location of Use:</td>
    <td><input style="margin-top:5px;width:300px;" type="text" name="location" value="',htmlentities($_POST['location']),'" /></td>
    </tr>
    
    <tr>
    <td>Length of Use:</td>
    <td><input style="margin-top:5px;width:300px;" type="text" name="lengthofuse" /></td>
    </tr>
    
    <tr>
    <td>Additional Terms:</td>
    <td><textarea style="margin-top:5px;width:300px;" rows="4" cols="100" name="additionalterms"></textarea></td>
    </tr>
    
    <tr>
    <td>Budget: </td>
    <td><span class="add-on">$</span>
<input id="appendedPrependedInput" class="span1" type="text" name="budget" size="16" value="',htmlentities($_POST['budget']),'">
<span class="add-on">.00</span></textarea></td>
    </tr>
    
    <tr>
    <td>Time frame:</td>
    <td><select class="span2" style="height:30px;" name="timeframe">
        <option value="1">1 Day</option>
        <option value="2">2 Days</option>
        <option value="3">3 Days</option>
        <option value="4">4 Days</option>
        <option value="5">5 Days</option>
        <option value="6">6 Days</option>
        <option value="7">One Week</option>
        <option value="14">Two Weeks</option>
        <option value="30">One Month</option>
        </select>
    </td>
    </tr>
    
    <tr>
    <td>LICENSE AGREEMENT: </td>
    <td colspan="2"><input style="margin-left:0px;" type="checkbox" name="terms" value="terms" />&nbsp;&nbsp;<span style="font-size:13px;">By checking here, you agree to the <b><a data-toggle="modal" data-backdrop="static" href="#campaignagreement">LICENSE AGREEMENT<a/></td>
    </tr>
    
    <tr>
    <td><input class="btn btn-success" style="width:150px;height:35px;" type="submit" value="START CAMPAIGN" /></td>
    </tr>
    
    </tbody>
    </table>';


    }
        
    echo'</div>';
                
}   //end of view
   
   

elseif($view == 'photogs') {

echo'<div id="container" class="grid_18" style="width:770px;margin-top:20px;padding-left:20px;">';

$photogsquery = mysql_query("SELECT following FROM campaignusers WHERE repemail = '$repemail'");
$photogs = mysql_result($photogsquery,0,'following'); 
$individuals = explode(" ", $photogs);
$numinds = count($individuals);

    for($iii=0; $iii < $numinds; $iii++) {
        
        $userquery = mysql_query("SELECT user_id,firstname,lastname,profilepic FROM userinfo WHERE user_id = '$individuals[$iii]'");
        $userpic = mysql_result($userquery,0,'profilepic');
        $userid = mysql_result($userquery,0,'user_id');
        $fullname = mysql_result($userquery,0,'firstname')." ".mysql_result($userquery,0,'lastname');

        if($userpic == '') {
        continue;
        }
        
        echo '   

                <div style="width:245px;height:245px;overflow:hidden;float:left;margin-left:10px;margin-top:30px;"><a href="viewprofile.php?u=',$userid,'">

                <div class="statoverlay" style="z-index:1;left:0px;top:210px;position:relative;background-color:black;width:245px;height:35px;"><p style="line-spacing:1.48;padding:5px;color:white;"><span style="font-size:18px;font-weight:100;">',$fullname,'</span></p></div>

                <img onmousedown="return false" oncontextmenu="return false;" style="position:relative;top:-35px;min-height:245px;min-width:245px;" src="http://www.photorankr.com/',$userpic,'" height="245" width="245" /></a></div>';

    }

echo'</div>';

}


elseif($view == 'promote'){

    echo'<div class="grid_18" style="width:770px;margin-top:0px;margin-left:-10px;padding:35px;background-color:rgba(245,245,245,0.6);">

    <div class="well" style="font-size:16px;font-family:helvetica neue, gill sans, helvetica;">Help promote ',$fullname,'\'s  photography by sharing it:<br /><br />

    <!--FB-->
    <a name="fb_share" share_url="http://photorankr.com/viewprofile.php?u=',$user,'"></a> 
    <script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" 
        type="text/javascript">
    </script>

    <!--TWITTER-->
    <div style="position:relative;margin-top:15px;">
    <a href="https://twitter.com/share" class="twitter-share-button" data-url="http://photorankr.com/viewprofile.php?u=',$user,'" data-text="Visit my photography site on PhotoRankr!" data-via="PhotoRankr" data-related="PhotoRankr">Tweet</a>
    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");
    </script></div>

    <!--GOOGLE PLUS-->
    <div style="position:relative;margin-top:15px;">
    <div class="g-plus" data-action="share" data-href="http://photorankr.com/viewprofile.php?u=',$user,'"></div>';
    ?>

    <script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
    </script>
    <?php
    echo'

    <!--TUMBLR-->
    <div style="position:relative;margin-top:15px;">
    <span id="tumblr_button_abc123"></span>
    </div>

    </div>
    </div>
    ';

}


    
elseif($view == 'about') {
        
        echo'
        <div class="span9" style="margin-top:30px;">
        <table class="table">
        <tbody>';

        if($age) {
        echo'
        <tr>
        <td>Age:</td>
        <td>',$age,'</td>
        </tr>'; }

        if($location) {
        echo'
        <tr>
        <td>From:</td>
        <td>',$location,'</td>
        </tr>'; }

        if($gender) {
        echo'
        <tr>
        <td>Gender:</td>
        <td>',$gender,'</td>
        </tr>'; }

        if($camera) {
        echo'
        <tr>
        <td>Camera:</td>
        <td>',$camera,'</td>
        </tr>'; }

        if($fbook) {
        echo'
        <tr>
        <td>Facebook Page:</td>
        <td><a href="',$fbook,'">',$fbook,'</a></td>
        </tr>'; }

        if($twitter) {
        echo'
        <tr>
        <td>Twitter:</td>
        <td><a href="',$twitter,'">',$twitter,'</a></td>
        </tr>'; }

        if($quote) {
        echo'
        <tr>
        <td>Quote:</td>
        <td>',$quote,'</td>
        </tr>'; }

        if($about) {
        echo'
        <tr>
        <td>About:</td>
        <td>',$about,'</td>
        </tr>'; }

        echo'
        </tbody>
        </table>
        </div>';
    
    }
    
    
    
    elseif($view == 'account') {
    
        
     echo'<div id="container" class="grid_18" style="width:770px;margin-top:-10px;padding-left:20px;">';
    
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


        <table class="table">
        <tbody style="font-size:14px;">
        
        <tr>
        <td>Change Name:</td>
        <td><input style="width:180px;height:25px;" type="text" name="name" value="', $name, '"/></td>
        </tr>
                
        <tr>
        <td>Change Password:</td>
        <td><input style="width:180px;height:25px;" type="password" name="password" value="',$password, '"/>
</td>
        </tr>
                
        <tr>
        <td>Confirm Password:</td>
        <td><input style="width:180px;height:25px;" type="password" name="confirmpassword" value="',$password, '"/></td>
        </tr>

        <tr>
        <td>Change Account Photo:</td>
        <td><input style="margin-top:10px" type="file"  name="file" value="', $profilepic, '"/></td>
        </tr>
        
        </tbody>
        </table>

        <button class="btn btn-success" type="submit">Save Account</button>
        </form>
        </div>
    
        </div>';

    }
        
    
    elseif($view == 'photogs') {
    
            echo'<div id="container" class="grid_18" style="width:770px;margin-top:10px;padding-left:20px;">';
            
               
  
            
            echo'</div>';
    }

    
    
        elseif($view == 'favorites') {
    
        $query = mysql_query("SELECT * FROM photos WHERE source IN ($faves) ORDER BY FIELD (source, $faves) DESC LIMIT 9");
        $numresults = mysql_num_rows($query);
        echo'<div id="thepics">';
        echo'<div id="container" class="grid_18" style="width:770px;margin-top:0px;padding-left:20px;">';

        for($iii=0; $iii < $numresults; $iii++) {
              
                $image[$iii] = mysql_result($query, $iii, "source");
                $imageThumb[$iii] = str_replace("userphotos/","../userphotos/medthumbs/", $image[$iii]);
                $id = mysql_result($query, $iii, "id");
                $caption = mysql_result($query, $iii, "caption");
                $points = mysql_result($query, $iii, "points");
                $votes = mysql_result($query, $iii, "votes");
                $faves = mysql_result($query, $iii, "faves");
                $score = number_format(($points/$votes),2);
                $faveemail = mysql_result($query, $iii, "emailaddress");
                $ownerquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$faveemail'");
                $firstname = mysql_result($query, 0, "firstname");
                $lastname = mysql_result($query, 0, "lastname");
                $reputation = mysql_result($query, 0, "lastname");
                $fullname = $firstname . " " . $lastname;
                list($width, $height) = getimagesize($image);
                $imgratio = $height / $width;
                $heightls = $height / 3.5;
                $widthls = $width / 3.5;

                echo '   

                <div class="fPic" id="',$id,'" style="width:245px;height:245px;overflow:hidden;float:left;margin-left:10px;margin-top:30px;"><a href="http://photorankr.com/fullsize.php?image=', $image[$iii], '">

                <div class="statoverlay" style="z-index:1;left:0px;top:155px;position:relative;background-color:black;width:245px;height:75px;"><p style="line-spacing:1.48;padding:5px;color:white;"><span style="font-size:16px;font-weight:100;">',$caption,'</span><br><span style="font-size:14px;font-weight:100;">Score: ',$score,'<br>Favorites: ',$faves,'</span></p></div>

                <img onmousedown="return false" oncontextmenu="return false;" style="position:relative;top:-90px;min-height:245px;min-width:245px;" src="http://www.photorankr.com/',$imageThumb[$iii],'" height="',$heightls,'px" width="',$widthls,'px" /></a></div>';
	    
                } //end for loop      
        
        echo'</div>';
        echo'</div>';
    
    }
    
    
    elseif($view == 'search') {
        
        $searchterm = htmlentities(mysql_real_escape_string($_POST['searchterm']));
        $query = mysql_query("SELECT * FROM photos WHERE concat(caption,location,tag1,tag2,tag3,tag4) LIKE '%$searchterm%' AND emailaddress = '$useremail' ORDER BY (views) DESC");
        $numresults = mysql_num_rows($query);
        echo'<div id="thepics">';
        echo'<div id="container" class="grid_18" style="width:770px;margin-top:0px;padding-left:20px;">';
        
        echo'<br /><div style="width:760px;text-align:center;font-size:17px;font-weight:200;"><div style="margin-left:20px;">';
        if($numresults > 0) {echo $numresults . ' Photos Found'; } else {echo'Sorry, No Photos Found For "',$searchterm,'"';}
        echo'
        </div></div>';

        for($iii=0; $iii < $numresults; $iii++) {
              
                $image[$iii] = mysql_result($query, $iii, "source");
                $imageThumb[$iii] = str_replace("userphotos/","../userphotos/medthumbs/", $image[$iii]);
                $id = mysql_result($query, $iii, "id");
                $caption = mysql_result($query, $iii, "caption");
                $points = mysql_result($query, $iii, "points");
                $votes = mysql_result($query, $iii, "votes");
                $faves = mysql_result($query, $iii, "faves");
                $score = number_format(($points/$votes),2);
                $faveemail = mysql_result($query, $iii, "emailaddress");
                $ownerquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$faveemail'");
                $firstname = mysql_result($query, 0, "firstname");
                $lastname = mysql_result($query, 0, "lastname");
                $reputation = mysql_result($query, 0, "lastname");
                $fullname = $firstname . " " . $lastname;
                list($width, $height) = getimagesize($image);
                $imgratio = $height / $width;
                $heightls = $height / 3.5;
                $widthls = $width / 3.5;

                echo '   

                <div class="fPic" id="',$id,'" style="width:245px;height:245px;overflow:hidden;float:left;margin-left:10px;margin-top:30px;"><a href="http://photorankr.com/fullsize.php?image=', $image[$iii], '">

                <div class="statoverlay" style="z-index:1;left:0px;top:155px;position:relative;background-color:black;width:245px;height:75px;"><p style="line-spacing:1.48;padding:5px;color:white;"><span style="font-size:16px;font-weight:100;">',$caption,'</span><br><span style="font-size:14px;font-weight:100;">Score: ',$score,'<br>Favorites: ',$faves,'</span></p></div>

                <img onmousedown="return false" oncontextmenu="return false;" style="position:relative;top:-90px;min-height:245px;min-width:245px;" src="http://www.photorankr.com/',$imageThumb[$iii],'" height="',$heightls,'px" width="',$widthls,'px" /></a></div>';
	    
                } //end for loop      
        
        echo'</div>';
        echo'</div>';
    
    }
    
    
    elseif($view == 'contact') {
    
        echo'<div class="grid_16" style="margin-left:20px;font-family: arial; font-size: 18px;font-weight:200;margin-top:20px;">';
	if($_SESSION['loggedin'] == 1) {
	    
		echo' <div style="position:absolute; font-size: 25px; font-family: Helvetica Neue, Helvetica, Arial, sans-serif;
		line-height: 28px; color: #333333;">
    
		<span style="font-size:20px;">Send ',$fullname,' a message:</span>
        <br /><br />
		<form method="post" action="../sendmessage2.php" />
		<textarea cols="95" rows="10" style="width:650px" name="message"></textarea>
    		<br />
    		<br />
		<input type="submit" class="btn btn-success" value="Send Message"/>
		<input type="hidden" name="emailaddressofviewed" value="',$emailaddress,'" />
		</form>';
	}
	else {
    		echo' <div style="font-size: 20px;margin-left:100px;text-align:center;margin-top:150px;font-weight:200;font-family: Helvetica Neue, Helvetica, Arial, sans-serif;
		line-height: 18px;
		color: #333333;">';
		echo 'You must be signed in to contact this person.</div>';
	}

	if($_GET['action'] == "messagesent") {
		echo '<div style="font-size: 20px;">Message Sent!</div>';
	}
    echo '</div>';
    
    
    }
    
?>

</div><!--end grid 18-->

</div><!--end 24 grid-->

</div>


<!--TUMBLR SCRIPTS-->
<script type="text/javascript">
    var tumblr_link_url = "http://photorankr.com/viewprofile.php?u=',$user,'";
    var tumblr_link_name = "My PhotoRankr Portfolio";
    var tumblr_link_description = "Visit and rank my photography on PhotoRankr!";
</script>

<script type="text/javascript">
    var tumblr_button = document.createElement("a");
    tumblr_button.setAttribute("href", "http://www.tumblr.com/share/link?url=" + encodeURIComponent(tumblr_link_url) + "&name=" + encodeURIComponent(tumblr_link_name) + "&description=" + encodeURIComponent(tumblr_link_description));
    tumblr_button.setAttribute("title", "Share on Tumblr");
    tumblr_button.setAttribute("style", "display:inline-block; text-indent:-9999px; overflow:hidden; width:129px; height:20px; background:url('http://platform.tumblr.com/v1/share_3.png') top left no-repeat transparent;");
    tumblr_button.innerHTML = "Share on Tumblr";
    document.getElementById("tumblr_button_abc123").appendChild(tumblr_button);
</script>

<script type="text/javascript" src="http://platform.tumblr.com/v1/share.js"></script>


</body>
</html>
