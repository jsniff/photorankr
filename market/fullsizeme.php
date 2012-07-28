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


     //GET INFO FROM CURRENT PHOTO ID
    $id = htmlentities($_GET['id']);
    $imagequery = "SELECT source,caption,campaign,emailaddress,points,votes FROM campaignphotos WHERE id = '$id'";
    $imagequeryrun = mysql_query($imagequery);
    $image = mysql_result($imagequeryrun, 0, 'source');
    if($image == '') {
    $image = 'graphics/nophotosubmit.png';
    }
	$title = mysql_result($imagequeryrun, 0, 'caption');
    $photogemail = mysql_result($imagequeryrun, 0, 'emailaddress');
    $campaign = mysql_result($imagequeryrun, 0, 'campaign');
    $numincampquery = mysql_query("SELECT id FROM campaignphotos WHERE campaign = '$campaign'");
    $numincamp = mysql_num_rows($numincampquery);
    $imageLast = "SELECT id FROM campaignphotos WHERE campaign = '$campaign' ORDER BY id DESC LIMIT 1";
    $imageLastquery = mysql_query($imageLast);
    $lastID = mysql_result($imageLastquery, 0, 'id');
    $imageFirst = "SELECT id FROM campaignphotos WHERE campaign = '$campaign' ORDER BY id ASC LIMIT 1";
    $imageFirstquery = mysql_query($imageFirst);
    $firstID = mysql_result($imageFirstquery, 0, 'id');

    
    //GET ID's OF PREVIEWS AND NEXT/BACK FUNCTIONS        
    $imageBeforequery = "SELECT id FROM campaignphotos WHERE campaign = '$campaign' AND id < '$id' ORDER BY id DESC LIMIT 1";
    $imageBeforequeryrun = mysql_query($imageBeforequery);
    $imageBeforeID = mysql_result($imageBeforequeryrun, 0, 'id');
    if($imageBeforeID == '') {
        $imageBeforeID = $lastID;
    }
    
    $imageNextquery = "SELECT id FROM campaignphotos WHERE campaign = '$campaign' AND id > '$id' LIMIT 1";
    $imageNextqueryrun = mysql_query($imageNextquery);
    $imageNextID = mysql_result($imageNextqueryrun, 0, 'id');
    if($imageNextID == '') {
        $imageNextID = $firstID; 
    }
    
    $imageTwoquery = "SELECT id FROM campaignphotos WHERE campaign = '$campaign' AND id > '$imageNextID' LIMIT 1";
    $imageTwoqueryrun = mysql_query($imageTwoquery);
    $imageTwoID = mysql_result($imageTwoqueryrun, 0, 'id');
    if($imageTwoID > $lastID) {
        $imageTwoID = $firstID; 
    }
    
    $imageThreequery = "SELECT id FROM campaignphotos WHERE campaign = '$campaign' AND id > '$imageTwoID' LIMIT 1";
    $imageThreequeryrun = mysql_query($imageThreequery);
    $imageThreeID = mysql_result($imageThreequeryrun, 0, 'id');
    if($imageThreeID > $lastID) {
        $imageThreeID = $firstID; 
    }
    
    //GET THE PREVIEW"S SOURCES
    if($numincamp == 1){
    $imageNextID = '';
    }
    $imagenextquery = "SELECT source FROM campaignphotos WHERE campaign = '$campaign' AND id = '$imageNextID'";
    $imagenextqueryrun = mysql_query($imagenextquery);
    $imageNext = mysql_result($imagenextqueryrun, 0, 'source');
    $imageNext = str_replace("userphotos/","userphotos/medthumbs/", $imageNext);
    if($imageNext == '') {
    $imageNext = 'graphics/nophotosubmit.png';
    }
    
    if($numincamp == 1){
    $imageTwoID = '';
    }
    $imagetwoquery = "SELECT source FROM campaignphotos WHERE campaign = '$campaign' AND id = '$imageTwoID'";
    $imagetwoqueryrun = mysql_query($imagetwoquery);
    $imageTwo = mysql_result($imagetwoqueryrun, 0, 'source');
    $imageTwo = str_replace("userphotos/","userphotos/medthumbs/", $imageTwo);
    if($imageTwo == '') {
    $imageTwo = 'graphics/nophotosubmit.png';
    }
    
    if($numincamp == 1){
    $imageThreeID = '';
    }
    $imagethreequery = "SELECT source FROM campaignphotos WHERE campaign = '$campaign' AND id = '$imageThreeID'";
    $imagethreequeryrun = mysql_query($imagethreequery);
    $imageThree = mysql_result($imagethreequeryrun, 0, 'source');
    $imageThree = str_replace("userphotos/","userphotos/medthumbs/", $imageThree);
    if($imageThree == '') {
    $imageThree = 'graphics/nophotosubmit.png';
    }    
    
    $prevvotes = mysql_result($imagequeryrun, 0, 'votes');
    $prevpoints = mysql_result($imagequeryrun, 0, 'points');

    //Get Campaign Quote
    $campaignquery = mysql_query("SELECT quote FROM campaigns WHERE id = '$campaign'");
    $quote =  mysql_result($campaignquery, 0, 'quote');
 
  //calculate the size of the picture

$maxwidth=850;
$maxheight=850;

list($width, $height)=getimagesize($image);
$imgratio=$width/$height;

if($imgratio > 1) {
    $newwidth=$maxwidth;
    $newheight=$maxwidth/$imgratio;
}
else {
    $newheight=$maxheight;
    $newwidth=$maxheight*$imgratio;
}
  
    
    if(!$_GET['id'] || $_GET['id'] == "") {
	    mysql_close();
        echo '<META HTTP-EQUIV="Refresh" Content="0; URL=trending.php">';
		exit();			
    }

$row=mysql_fetch_array($result);
$emailaddress=$row['emailaddress'];
$caption=$row['caption'];
$location=$row['location'];
$country=$row['country'];
$imageID=$row['id'];
$price=mysql_result($result, 0, "price");
$camera = $row['camera'];
$faves= $row['faves'];
$views = $row['views'];
$exhibit = $row['set_id'];
$exhibitname = $row['sets'];
$focallength = $row['focallength'];
$shutterspeed = $row['shutterspeed'];
$aperture = $row['aperture'];
$lens = $row['lens'];
$filter = $row['filter'];
$copyright = $row['copyright'];
$about = $row['about'];
$tag1 = $row['tag1'];
$tag2 = $row['tag2'];
$tag3 = $row['tag3'];
$tag4 = $row['tag4'];
$maintags = $row['maintags'];
$settags = $row['settags'];
$singlecategorytags = $row['singlecategorytags'];
$singlestyletags = $row['singlestyletags'];
$tags = $settags + $maintags + $singlecategorytags + $singlestyletags;

//find how many photos the photographer has
$numberofpics = mysql_query("SELECT * FROM photos WHERE emailaddress='$emailaddress'");
$numberofpics = mysql_num_rows($numberofpics);

$locationandcountry = $location . $country;

if ($price == "") {$price='.25';}  

//FIND THE PHOTOGRAPHER NAME IN DATABASE
$namequery="SELECT * FROM userinfo WHERE emailaddress='$emailaddress'";
$nameresult=mysql_query($namequery);
$row=mysql_fetch_array($nameresult);
$user=$row['user_id'];
$firstname=$row['firstname'];
$lastname=$row['lastname'];
$profilepic=$row['profilepic'];
$profilescore=$row['totalscore'];

//calculate the size of the picture
$maxwidth=850;
$maxheight=850;

list($width, $height)=getimagesize($image);
$imgratio=$width/$height;

if($imgratio > 1) {
	$newwidth=$maxwidth;
	$newheight=$maxwidth/$imgratio;
}
else {
	$newheight=$maxheight;
	$newwidth=$maxheight*$imgratio;
}


?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
"http://w3.org/TR/html4/strict.dtd">
<html>
  <head>       
	<title>Fullsize Photo - "<?php echo $title; ?>"</title>
  <link rel="stylesheet" type="text/css" href="css/bootstrapnew.css" />
  <link rel="stylesheet" href="css/reset.css" type="text/css" />
  <link rel="stylesheet" href="css/text.css" type="text/css" />
  <link rel="stylesheet" href="css/newfullsize.css" type="text/css" />
  <link rel="stylesheet" href="css/960_24.css" type="text/css" />
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
  <script src="js/bootstrap.js" type="text/javascript"></script>
  <script src="js/bootstrap-dropdown.js" type="text/javascript"></script>
  <script src="js/bootstrap-collapse.js" type="text/javascript"></script>
  <link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>

     <script src="bootstrap-dropdown.js" type="text/javascript"></script>
     <script src="bootstrap-collapse.js" type="text/javascript"></script>
     
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
      
        .imageContainer{width:auto;} 
        .imageContainer img {display:block;width:100%;height:auto;}
        
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

<body oncontextmenu="return false;" class="background" style="overflow-x: hidden;">

<?php navbarnew(); ?>

<div class="container_24" style="padding-bottom:30px;"><!--Grid container begin-->
 
<?php
$campaigntitlequery = mysql_query("SELECT title from campaigns WHERE id = '$campaign'");
$camptitle = mysql_result($campaigntitlequery,0,'title');
?>
 
<!--TITLE OF PHOTO-->     
<div class="grid_24">
<div class="grid_21 pull_2"><div style="margin-top:10px;padding-top:5px;padding-left:3px;line-height:30px;font-size:30px;
"><?php echo '(<a href="campaignphotos.php?id=',$campaign,'">',$camptitle,'</a>) "',$title,'"'; ?>
</div></div></div>

<!--BIG IMAGE BOX-->
<div class="grid_24">

<div class="grid_15 pull_2" style="margin-top:150px;">

<div class="imageContainer" style="margin-top:-135px;">
<img class="phototitle" onmousedown="return false" oncontextmenu="return false;" alt="<?php echo $tags; ?>" src="<?php echo $image; ?>" /></div>
  
</div> 


<!--ARROWS-->
<div class="grid_4 push_6 arrows" style="width:218px;padding:2px;">
<span style="margin-left:30px;"><a style="text-decoration:none;" href="fullsizeme.php?id=<?php echo $imageBeforeID; ?>"><img src="graphics/arrow left.png" alt="Scroll through photos in their full size glory"
height="50" width="50"/></a></span>

<span style="margin-left:55px;"><a style="text-decoration:none;" href="fullsizeme.php?id=<?php echo $imageNextID; ?>"><img  src="graphics/arrow right.png" alt="Scroll through photos in their full size glory"
height="50" width="50"/></a></span>

</div>

<!--Download Button-->
<div class="grid_4 push_6" style="width:218px;padding-bottom:5px;padding-top:3px;">

<form name="download_form" method="post" action="download.php">
<input type="hidden" name="price" value="<?php echo $quote; ?>">
<input type="hidden" name="caption" value="<?php echo $title; ?>">
<input type="hidden" name="owner" value="<?php echo $photogemail; ?>">
<input type="hidden" name="campaignnumber" value="<?php echo $campaign; ?>">
<input type="hidden" name="imageID" value="<?php echo $id; ?>">
<input type="hidden" name="image" value="<?php echo $image; ?>">
<input type="hidden" name="width" value="<?php echo $width; ?>">
<input type="hidden" name="height" value="<?php echo $height; ?>">
<button type="submit" name="submit" value="DOWNLOAD NOW" class="btn btn-warning" style="margin-left:4px;width:225px; height: 40px; font-family: arial; position: relative; font-size:14px; top: 7px; left: 0px; ">DOWNLOAD NOW</button>

</form>
</div>
    
<!--PHOTO INFORMATION BOX-->    
<div class="grid_4 push_6 photoinfo" style="padding:2px;width:218px;">
 <br />
 
 
 <?php

//get the ranking variable and update the database
$ranking=mysql_real_escape_string($_POST['ranking']);
if($_POST['ranking']) { //if ranking was posted
    $voteremail=$_SESSION['email'];
        
    if($voteremail) {
 $rankcheck = mysql_query("SELECT voters FROM photos WHERE source='$image'") or die(mysql_error());
    $votecheck = mysql_result($rankcheck, 0, "voters");
		$search_string2 = $votecheck;
		$regex=$voteremail;
		$votematch=strpos($search_string2, $regex);
         
        //check if own photo
        if($voteremail == $emailaddress) {
        $voteself == 1;
        }
        
		//if the image hasn't already been voted on
		if(!$votematch && ($voteremail != $emailaddress)) {
        
	$ranking=mysql_real_escape_string($_POST['ranking']); //make ranking equal to the posted ranking as an integer data type
	if ($ranking >= 1 & $ranking <= 10) {  //if ranking makes sense
		
        
        if($ultimatereputationme > 70 && $ultimatereputationme < 100)
        {
        $prevpoints+=($ranking*2.5);
		$prevvotes+=2.5;
		$rankquery="UPDATE photos SET points='$prevpoints', votes='$prevvotes' WHERE source='$image'";
		mysql_query($rankquery); 
        }
        
        elseif($ultimatereputationme > 50 && $ultimatereputationme < 70)
        {
        $prevpoints+=($ranking*2.0);
		$prevvotes+=2;
		$rankquery="UPDATE photos SET points='$prevpoints', votes='$prevvotes' WHERE source='$image'";
		mysql_query($rankquery); 
        }
        
        elseif($ultimatereputationme > 30 && $ultimatereputationme < 50)
        {
        $prevpoints+=($ranking*1.5);
		$prevvotes+=1.5;
		$rankquery="UPDATE photos SET points='$prevpoints', votes='$prevvotes' WHERE source='$image'";
		mysql_query($rankquery); 
        }
        
        elseif($ultimatereputationme > 0 && $ultimatereputationme < 30)
        {
        $prevpoints+=$ranking;
		$prevvotes+=1;
		$rankquery="UPDATE photos SET points='$prevpoints', votes='$prevvotes' WHERE source='$image'";
		mysql_query($rankquery); 
        }
        
        }  //end querying points and votes count
    
        //Add voter's name to database    
    $voter = "'" . $voteremail . "'";
    $voter = ", " . $voter;
    $voter = addslashes($voter);
    $votersquery = mysql_query("UPDATE photos SET voters=CONCAT(voters,'$voter') WHERE source='$image'");
    
    echo '<div style="position: relative; top: 0px; text-align: center; font-size: 15px; font-family: arial;">Thanks for voting!</div>';

	} 
    
    elseif(votematch && ($voteremail != $emailaddress)){
    	echo '<div style="position: relative; top: 0px; text-align: center; font-size: 15px; font-family: arial;">You already voted!</div>';

    }
    
    elseif($voteremail == $emailaddress) {
    echo '<div style="position: relative;  top: 0px; text-align: center; font-size: 15px; font-family: arial;">Oops, your photo!</div>';

    }
    }
    
    else{
        	echo '<div style="position: relative; top: 0px; text-align: center; font-size: 15px; font-family: arial;">Please login to vote</div>';

    }
       }

//RANKING
if($prevvotes >=1.0) {
	$display=($prevpoints/$prevvotes);	
	echo '<div style="position:relative; left: 30px; top: 0px;">
	<span style="font-size:16px;">Rank:&nbsp;&nbsp;&nbsp;</span><span style="font-size:30px;">',round($display, 1),'</span><span style="opacity: .6; font-size: 18px;">/10.0</span><br />';
    }
else  {
	echo '<div style="position:relative; left: 30px; top: 0px;"><span style="font-size:16px;">Rank:&nbsp;&nbsp;&nbsp;</span><span style="font-size:30px;">0.0</span><span style="opacity: .6; font-size: 18px;">/10.0</span><br />';
    }	
echo'
<br />
</div>';
?> 


<!--PREVIEWS-->
<div class="grid_4">
    <div style="float:left;">
    
   <?php if($imageNextID != '') {echo'<a href="fullsize.php?id=',$imageNextID,'">';} ?>
   <img onmousedown="return false" oncontextmenu="return false;" class="preview" src="<?php echo $imageNext; ?>" height="200" width="210" /></a>
    
    <?php if($imageTwoID != '') {echo'<a href="fullsize.php?id=',$imageTwoID,'">';} ?>
    <img onmousedown="return false" oncontextmenu="return false;" class="preview" style="margin-top:5px;" src="<?php echo $imageTwo; ?>" height="200" width="210" /></a>
    
    <?php if($imageThreeID != '') {echo'<a href="fullsize.php?id=',$imageThreeID,'">';} ?>
    <img onmousedown="return false" oncontextmenu="return false;" class="preview" style="margin-top:5px;" src="<?php echo $imageThree; ?>" height="200" width="210" /></a>
    
    </div>
</div>

</div><!--end of 4 grid-->

<!--Footer begin-->   
<div class="grid_24" style="height:30px;margin-top:30px;background-color:rgb:(238,239,243);text-align:center;padding-top:10px;padding-bottom:20px; background-color:none;text-decoration:none;">
<p style="text-decoration:none;">
</br></br>
<div style="text-align:center;">
Copyright&nbsp;&copy;&nbsp;2012&nbsp;PhotoRankr, Inc.&nbsp;&nbsp;
</div>
<br />
<br />
</p>                   
</div>
<!--Footer end-->

</body>
</html>
      
       
        
    