<?php

//connect to the database
require "db_connection.php";
require "functions.php"; 

    // if login form has been submitted
    if (htmlentities($_GET['action']) == "login") { 
        login();
    }
    else if(htmlentities($_GET['action']) == "logout") { 
        logout();
    }

    //start the session
    session_start();

    $email = $_SESSION['email'];

    //IP Address
    $ip = $_SERVER['REMOTE_ADDR'];  
    
     //GET INFO FROM CURRENT PHOTO ID
    $imageid = htmlentities($_GET['imageid']);
    $currenttime = time();
    
    //add to the views column
    $updatequery = mysql_query("UPDATE photos SET views=views+1 WHERE id='$imageid'") or die(mysql_error());
    
    //add to the usermarketviews column
    $userupdatequery = mysql_query("UPDATE photos SET usermarketviews=usermarketviews+1 WHERE id='$imageid'") or die(mysql_error());

    $imagequery = "SELECT * FROM photos WHERE id = '$imageid'";
    $imagequeryrun= mysql_query($imagequery);
    $image = mysql_result($imagequeryrun,0,'source');
    $owner = mysql_result($imagequeryrun,0,'emailaddress');
    $price = mysql_result($imagequeryrun,0,'price');
    $points = mysql_result($imagequeryrun,0,'points');
    $faves = mysql_result($imagequeryrun,0,'faves');
    $views = mysql_result($imagequeryrun,0,'views');
    $caption = mysql_result($imagequeryrun,0,'caption');
    $uploaded = mysql_result($imagequeryrun,0,'time');
    $votes = mysql_result($imagequeryrun,0,'votes');
    $ranking = ($points/$votes);
    $ranking = number_format($ranking,2);
    $location = mysql_result($imagequeryrun,0,'location');
    $camera = mysql_result($imagequeryrun,0,'camera');
    $exhibit = mysql_result($imagequeryrun,0,'set_id');
    $about = mysql_result($imagequeryrun,0,'about');
    $tag1 = mysql_result($imagequeryrun,0,'tag1');
    $tag2 = mysql_result($imagequeryrun,0,'tag2');
    $tag3 = mysql_result($imagequeryrun,0,'tag3');
    $tag4 = mysql_result($imagequeryrun,0,'tag4');
    $classification = mysql_result($imagequeryrun,0,'classification');
    
    $ownerquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$owner'");
    $profilepic = mysql_result($ownerquery,0,'profilepic');
    $firstname = mysql_result($ownerquery,0,'firstname');
    $lastname = mysql_result($ownerquery,0,'lastname');
    $userid = mysql_result($ownerquery,0,'user_id');
    $reputation = mysql_result($ownerquery,0,'reputation');
    $fullname = $firstname . " " . $lastname;
    $imagebig = str_replace("userphotos/", "$_SERVER[DOCUMENT_ROOT]/userphotos/", $image);
    $imageoriginal = str_replace("userphotos/", "$_SERVER[DOCUMENT_ROOT]/userphotos/bigphotos/", $image);
    $imagebig2 = str_replace("$_SERVER[DOCUMENT_ROOT]/userphotos/", "http://photorankr.com/userphotos/", $imagebig); 
    $title = mysql_result($imagequeryrun,0,'caption');

//calculate the size of the picture

$maxwidth=550;
$maxheight=550;

list($originalwidth, $originalheight)=getimagesize($imageoriginal);

list($width, $height)=getimagesize($imagebig);
$imgratio=$width/$height;

if($imgratio > 1) {
    $newwidth=$maxwidth;
    $newheight=$maxwidth/$imgratio;
}
else {
    $newheight=$maxheight;
    $newwidth=$maxheight*$imgratio;
}
    
    if(!$_GET['imageid'] || $_GET['imageid'] == "") {
	    mysql_close();
        echo '<META HTTP-EQUIV="Refresh" Content="0; URL=trending.php">';
		exit();			
    }
    
    
    //SAVE PHOTO QUERY
    if($_GET['ml'] == "saved") {
            
            $maybecheckquery=mysql_query("SELECT * FROM usersmaybe WHERE (emailaddress = '$email' AND emailaddress != '') OR ip_address = '$ip'");
            $nummaybesaved = mysql_num_rows($maybecheckquery);

            for($iii=0; $iii < $nummaybesaved; $iii++) {
                $maybeimageid = mysql_result($maybecheckquery,$iii,'imageid');
                $maybesavedlist = $maybesavedlist." ".$maybeimageid;
            }
                
            //MAKE SURE CORRECT BUTTON SHOWS
            $search_string3=$maybesavedlist;
            $regex3="/$imageid/";
            $maybematch=preg_match($regex3,$search_string3);
            
            if($maybematch < 1) {
            $savequery = mysql_query("INSERT INTO usersmaybe (source,caption,price,emailaddress,imageid) VALUES  ('$imagebig2','$caption','$price','$email','$imageid')");
            
        }
    }
    
    //REMOVE PHOTO QUERY
    if(htmlentities($_GET['action']) == "removed") { 
        $querycheck = mysql_query("SELECT emailaddress,ip_address FROM userscart WHERE imageid = '$imageid'");
        $emailcheck = mysql_result($querycheck,0,'emailaddress');
        $cartip = mysql_result($querycheck,0,'ip_address');
        if(($email == $emailcheck) || ($ip == $cartip)) {
            $removequery = mysql_query("DELETE FROM userscart WHERE imageid = '$imageid' AND (emailaddress = '$email' AND emailaddress != '') OR ip_address = '$cartip'");
        }
    }

    //Query Stats Table
    $imageid = htmlentities($_GET['imageid']);
    $photoview = "marketview";
    $timestampentertimeslicequery="INSERT INTO Statistics (ViewTimeStamp, Source, Person, Type) VALUES ('$currenttime', '$imageid', '$email', '$photoview')";
$timestampquery= mysql_query($timestampentertimeslicequery);

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
"http://w3.org/TR/html4/strict.dtd">
<html>
  <head>     
	<meta name="Generator" content="EditPlus">
    <meta property="og:image" content="http://photorankr.com/<?php echo $image; ?>">
    <meta name="Author" content="PhotoRankr, PhotoRankr.com">
    <meta name="Keywords" content="photos, sharing photos, photo sharing, photography, photography club, sell photos, sell photography, where to sell my photography, good sites for selling photography, making money from photography, making money off photography, social networking, social network, social networks, where to sell my photos, good sites for selling photos, good site to sell photos, making money from photos">
    <meta name="Description" content="<?php echo $caption; ?> by <?php echo $firstname ." ". $lastname; ?>">
    <meta name="viewport" content="width=1200" />
	<meta charset = "UTF-8">

	<title> "<?php echo $caption; ?>" | PhotoRankr </title>
    
    <link rel="stylesheet" type="text/css" href="css/style.css"/>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>
	<link rel="stylesheet" type="text/css" href="css/960grid.css"/>
	<link rel="stylesheet" type="text/css" href="css/reset.css"/> 
    <link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>
    <link rel="stylesheet" type="text/css" href="css/main3.css"/>

    <link rel="stylesheet" media='screen and (max-width:640px)' href="css/640.css"/>
    <link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>
    
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="js/bootstrap.js" type="text/javascript"></script>
	<script src="js/modernizer.js"></script>
     
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
            .show { display: block;  }
            .hide { display: none; }
            .click { cursor:pointer; }
            .clicked {background-color:#ddd;color:#000;cursor:pointer;}
            #row { cursor:pointer; }
            .row:hover {background-color:#ddd;color:#000;}
            #row2 { cursor:pointer; }
            #row3 { cursor:pointer; }
            #row3:hover {background-color:#ddd;color:#000;}
            #row4 { cursor:pointer; }
            #row5 { cursor:pointer; }
            #row6 { cursor:pointer; }
            #row7 { cursor:pointer; }
            
            .rollover {border:1px solid transparent;}
            .rollover:hover {border:1px solid black;}
        </style>


</head>

<!--MAYBE LATER MODAL-->
<div class="modal hide fade" id="maybemodal" style="overflow:hidden;border:5px solid rgba(102,102,102,.8);">
      
<?php
if($_SESSION['loggedin'] != 1) {

echo'
<div class="modal-header" style="background-color:#111;color:#fff;">
<a style="float:right" class="btn btn-success" data-dismiss="modal">Close</a>
<img style="margin-top:-2px;" src="graphics/aperture_white.png" height="34" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:16px;font-family:helvetica,arial;font-weight:100;">Please login to save this photo</span>
  </div>
  <div modal-body" style="width:600px;">

<div id="content" style="font-size:16px;width:560px;font-family:helvetica,arial;font-weight:100;background-color:rgb(245,245,245);">
		
<img style="border: 1px solid black;margin-left:10px;margin-top:5px;" src="',$imagebig2,'" 
height="100px" width="100px" /> 

<div style="width:500px;margin-left:130px;margin-top:-70px;">
"',$title,'"<br />                 

<br /><br /><br />

</div>
</div>';

    }
        
        
     if($_SESSION['loggedin'] == 1) {
    
		$maybequery=mysql_query("SELECT * FROM usersmaybe WHERE emailaddress ='$email'");
        $numsaved = mysql_num_rows($maybequery);
        for($iii=0; $iii < $numsaved; $iii++) {
            $savedimageid = mysql_result($maybequery,$iii,'imageid');
            $prevsavedlist = $prevsavedlist.",".$savedimageid;
        }
        
		//MAKE SURE FOLLOWER ISN'T ADDED TWICE
		$search_string=$prevsavedlist;
		$regex="/$imageid/";
		$match=preg_match($regex,$search_string);
		
        if ($match > 0) {
			
            echo'
                <div class="modal-header" style="background-color:#111;color:#fff;">
<a style="float:right" class="btn btn-success" data-dismiss="modal">Close</a>
<img style="margin-top:-2px;" src="graphics/aperture_white.png" height="34" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:16px;font-family:helvetica,arial;font-weight:100;">&nbsp;&nbsp;<span style="font-size:16px;">You already saved this photo</span>
                </div>
                <div modal-body" style="width:600px;">

                <div id="content" style="font-size:16px;width:560px;font-family:helvetica,arial;font-weight:100;background-color:rgb(245,245,245);">
		
                <img style="border: 1px solid black;margin-left:10px;margin-top:5px;" src="',$imagebig2,'" 
height="100px" width="100px" />

                <div style="width:500px;margin-left:130px;margin-top:-78px;">
"',$caption,'"<br />                 

                <br /><br /><br />

                </div>
                </div>';
        }

        else {
            
			echo'
                <div class="modal-header" style="background-color:#111;color:#fff;">
<a style="float:right" class="btn btn-success" href="fullsizemarket.php?imageid=',$imageid,'&ml=saved">Close</a>
<img style="margin-top:-2px;" src="graphics/aperture_white.png" height="34" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:16px;font-family:helvetica,arial;font-weight:100;">Photo saved in your profile</span></div>
                
                <div modal-body" style="width:700px;">

                <div id="content" style="font-size:16px;width:560px;font-family:helvetica,arial;font-weight:100;background-color:rgb(245,245,245);">
		
                <img style="border: 1px solid black;margin-left:10px;margin-top:20px;" src="',$imagebig2,'" 
height="100px" width="100px" />

                <div style="width:500px;margin-left:130px;margin-top:-70px;">
                "',$caption,'"<br />                

               <br /><br /><br />

                </div>
                </div>';
            
        }
    
    }
        
?>

</div>
</div>


<body id="body" >
<?php include_once("analyticstracking.php") ?>

<?php navbar(); ?>

<!-------------------------Grid container begin----------------------->
<div class="container_24" style="width:1100px;position:relative;left:30px;padding-top:55px;padding-bottom:30px;">

<?php
$campaigntitlequery = mysql_query("SELECT title from campaigns WHERE id = '$campaign'");
$camptitle = mysql_result($campaigntitlequery,0,'title');
?>

<!-------------------------Title----------------------->
<div class="mainTitle">
<p><?php echo $title; ?> <span id="downText"><img src="graphics/fileDownload.png" height="25" /> Licensed Download</span></p>
</div>

<div class="grid_24">
<div class="grid_12">

<!-------------------------Image----------------------->
<div class="mainImage">
<img onmousedown="return false" oncontextmenu="return false;" alt="<?php echo $tags; ?>" src="<?php echo $imagebig2; ?>" alt="<?php echo $title; ?>" />
</div>


<!-------------------------About Image----------------------->
		<div id="marketAbout" style="width:580px;">
			<header> <img src="https://photorankr.com/<?php echo $profilepic; ?>" /> <h5><a href="viewprofile.php?u=<?php echo $userid; ?>"><?php echo $fullname; ?></a></h5><p>Reputation: <?php echo $reputation; ?></p> </header>
			<ul>
                <?php 
                if($views) {
				echo'<li><img src="graphics/views.png"/>  Views: <span style="margin-left:38px;">',$views,'</span></li>';
                }
                if($camera) {
				echo'<li><img src="graphics/camera.png"/> Camera: <span style="margin-left:28px;">',$camera,'</span></li>';
                }
                if($aperture) {
				echo'<li><img src="graphics/aperature.png"/> Aperture: <span style="margin-left:24px;">',$aperture,'</span></li>';
                }
                if($focallength) {
				echo'<li> <img src="graphics/focalLength.png"/> Focal Length:  <span style="margin-left:3px;">',$focallength,'</span> </li>';
                }
                if($lens) {
				echo'<li> <img src="graphics/lens.png"/> Lens: <span style="margin-left:42px;">',$lens,'</span> </li>';
                }
                if($shutterspeed) {
				echo'<li> <img src="graphics/shutterSpeed.png"/> Shutter: <span style="margin-left:30px;">',$shutterspeed,'</span> </li>';
                }
                if($uploaded) {
                    echo'<li> <img src="graphics/captureDate.png" style="width:16px;margin-left:-3px;"/> Capture Date <span> ',$uploaded,' </span></li>';
                }
				if($location) { 
                    echo'<li> <img src="graphics/location.png" style="width:10px;margin: 0 8px 0 0;"/> Location: <span> ', $location ,' </span></li>';
                }

                ?>
            <?php
                if($tag1 || $tag2 || $tag3 || $tag4) {
                        echo'<li> <img src="graphics/tag.png" style="width:18px;margin: 0 8px 0 0;"/> Tags:<span style="width:450px;margin-top:-20px;">
                                <ul class="tags" style="position:relative;float:left;">';
                            if($tag1) {
                                echo'<li><a href="newsfeed.php?view=search&tag=',$tag1,'">',$tag1,'</a></li>';
                            }
                            if($tag2) {
                                echo'<li><a href="newsfeed.php?view=search&tag=',$tag2,'"">',$tag2,'</a></li>';
                            }
                            if($tag3) {
                                echo'<li><a href="newsfeed.php?view=search&tag=',$tag3,'"">',$tag3,'</a></li>';
                            }
                            if($tag4) {
                                echo'<li><a href="newsfeed.php?view=search&tag=',$tag4,'"">',$tag4,'</a></li>';
                            }
                            echo'
                        </ul>
                      </span>
                      </li>';
                }
            ?>

            </ul>
		</div>

</div>


<div class="grid_7 push_4">        
<!-------------------------License Options----------------------->
<div id="marketLicense">
    <header>
        Pick Your Resolution & License
        <p>Every download comes watermark-free and at your chosen resolution. Images you download are subject to the terms of your chosen license type. </p>
    </header>

<?php
$smallwidth = number_format(($originalwidth/2.5),0,',','');
$smallheight = number_format(($originalheight/2.5),0,',','');
$smallprice = number_format(($price / 2.5),2);
$medwidth = number_format(($originalwidth/1.3),0,',','');
$medheight = number_format(($originalheight/1.3),0,',','');
$medprice = number_format(($price / 1.5),2);
?>


      <script type="text/javascript">

        var rowcount = 1;
        var rowcount2= 1;
        var rowcount3 = 1;
        var rowcount4 = 1;
        var rowcount5 = 1;
        var rowcount6 = 1;
        var rowcount7 = 1;


            function showClicked() {
                var select = document.getElementById('row');
                select.className = 'clicked';
                rowcount++;
                document.getElementById('size').value = 'Small';
                document.getElementById('width').value = '<?php echo $smallwidth; ?>';
                document.getElementById('height').value = '<?php echo $smallheight; ?>';
                document.getElementById('price').value = '<?php echo $smallprice; ?>';

                if(rowcount>2){
                    select.className = 'unclicked';
                    rowcount=rowcount-2;
                }
                if(rowcount2>1){
            var select = document.getElementById('row2');
               select.className = 'unclicked';
               rowcount2=rowcount2-1;
                }
                if(rowcount3>1){
            var select = document.getElementById('row3');
               select.className = 'unclicked';
               rowcount3=rowcount3-1;
                }

            }
                function showClicked2() {
                var select = document.getElementById('row2');
                select.className = 'clicked';
                document.getElementById('size').value = 'Medium';
                document.getElementById('width').value = '<?php echo $medwidth; ?>';
                document.getElementById('height').value = '<?php echo $medheight; ?>';
                document.getElementById('price').value = '<?php echo $medprice; ?>';
                
                   rowcount2++;
                if(rowcount2>2){
                    select.className = 'unclicked';
                    rowcount2=rowcount2-2;
                }
                 if(rowcount>1){
            var select = document.getElementById('row');
               select.className = 'unclicked';
               rowcount = rowcount-1;
                }
                if(rowcount3>1){
            var select = document.getElementById('row3');
               select.className = 'unclicked';
               rowcount3-1;
                }
            }
               
  function showClicked3() { 
               
                    var select = document.getElementById('row3');
                    select.className = 'clicked';
                    var selectedphoto = 'large';
                    document.getElementById('size').value = 'Large';
                document.getElementById('width').value = '<?php echo $originalwidth; ?>';
                document.getElementById('height').value = '<?php echo $originalheight; ?>';
                document.getElementById('price').value = '<?php echo $price; ?>';
                    
                    rowcount3++;
                
                    if(rowcount3>2){
                        select.className = 'unclicked';
                        rowcount3=rowcount3-2;
                    }
                    
                    if(rowcount>1){
                        var select = document.getElementById('row');
                        select.className = 'unclicked';
                        rowcount=rowcount-1;
                    }
                    
                    if(rowcount2>1){
                        var select = document.getElementById('row2');
                        select.className = 'unclicked';
                        rowcount2=rowcount2-1;
                    }
                }
                
            
             function showClicked4() { 
                var select = document.getElementById('row4');
                select.className = 'clicked';
                rowcount4++;
                document.getElementById('multiseat').value = 'checked';
                
                if(rowcount4>2){
                    select.className = 'unclicked';
                    rowcount4=rowcount4-2;
                }
            }


                  function showClicked5() { 
                    var select = document.getElementById('row5');
                    select.className = 'clicked';
                    rowcount5++;
                    document.getElementById('unlimited').value = 'checked';


                if(rowcount5>2){
                    select.className = 'unclicked';
                    rowcount5=rowcount5-2;
                }
            }

                  function showClicked6() { 
                var select = document.getElementById('row6');
                select.className = 'clicked';
                rowcount6++;
                document.getElementById('resale').value = 'checked';

                if(rowcount6>2){
                    select.className = 'unclicked';
                    rowcount6=rowcount6-2;
                }
            }


                  function showClicked7() { 
                var select = document.getElementById('row7');
                select.className = 'clicked';
                rowcount7++;
                document.getElementById('electronic').value = 'checked';

                if(rowcount7>2){
                    select.className = 'unclicked';
                    rowcount7=rowcount7-2;
                }
            }
            

        </script>
        
        
<div class="grid_4" style="margin-top:20px;">
<div class="span6 licenseTable">
<table class="table">
<thead>
<tr>
<th style="color:black;">Size</th>
<th style="color:black;">Resolution</th>
<th style="color:black;">Price</th>
</tr>
</thead>
<tbody>

<form action="cart.php#<?php echo $imageid; ?>" method="POST" />

<tr id="row" onclick="showClicked();" style="color:black;">
<td class="row">Small</td>
<td class="row"><?php echo $smallwidth; ?> X <?php echo $smallheight; ?></td>
<td>

<?php
if($smallprice > 0) {
echo'$ ',$smallprice,'';
}
else {
echo'Free';
}
?>

</td>
</tr>

<tr id="row2" onclick="showClicked2();" style="color:black;">  
<td>Medium</td>
<td><?php echo $medwidth; ?> X <?php echo $medheight; ?></td>
<td>

<?php
if($medprice > 0) {
echo'$ ',$medprice,'';
}
else {
echo'Free';
}
?>

</td>
</tr>

<tr id="row3" onclick="showClicked3();" style="color:black;">
<td>Large</td>
<td><?php echo $originalwidth; ?> X <?php echo $originalheight; ?></td>
<td>

<?php
if($price > 0) {
echo'$ ',$price,'';
}
else {
echo'Free';
}
?>

</td>
</tr>

<tr>

<?php

if($classification) {
    echo'
    <td style="color:black;font-weight:700;">License</td>
    <td colspan="2" style="color:black;">';

    //If editorial license
    if(strpos($classification,'editorial') !== false) {
        echo'<div style="float:right;padding:5px;"><input style="margin-left:130px;"  type="radio" name="license" value="editorial" />&nbsp;&nbsp;Editorial</a></div>';
    }

    //If commercial license
    if(strpos($classification,'commercial') !== false) {
        echo'<div style="float:right;padding:5px;"><input style="margin-left:15px;" type="radio" name="license" value="commerical" />&nbsp;&nbsp;Commercial</div>';
    }
}

?>

</td>

</tr>
</tbody>
</table>

</div>

<div>

<!--HIDDEN INPUT FIELDS-->
<input type="hidden" id="size" name="size" value="" />
<input type="hidden" id="width" name="width" value="" />
<input type="hidden" id="height" name="height" value="" />
<input type="hidden" id="price" name="price" value="" />
<input type="hidden" id="imageid" name="imageid" value="<?php echo $imageid; ?>" />
<input type="hidden" id="originalprice" name="originalprice" value="<?php echo $price; ?>" />
<input type="hidden" id="originalwidth" name="originalwidth" value="<?php echo $originalwidth; ?>" />
<input type="hidden" id="originalheight" name="originalheight" value="<?php echo $originalheight; ?>" />


<?php

        $removequery=mysql_query("SELECT * FROM usersmaybe WHERE emailaddress = '$email' AND emailaddress != ''");
        $numsavedremove = mysql_num_rows($removequery);

        for($iii=0; $iii < $numsavedremove; $iii++) {
            $removeimageid = mysql_result($removequery,$iii,'imageid');
            $removelist = $removelist." ".$removeimageid;
        }

echo'<div style="width:440px;height:40px;clear:both;overflow:hidden;font-weight:500;">';

		//MAKE SURE CORRECT BUTTON SHOWS
		$search_string4=$removelist;
		$regex4="/$imageid/";
		$removematch=preg_match($regex4,$search_string4);
        
        if($removematch > 0) {
        echo'<a class="btn btn-primary" style="margin-left:200px;width:80px;float:left;" href="#"">Photo Saved</a>';
        }
        else {
        echo'<a class="btn btn-success buyButton" style="margin-left:200px;width:90px;float:left;" data-toggle="modal" data-backdrop="static" href="#maybemodal">Maybe Later</a>';
        }

		$cartquery=mysql_query("SELECT * FROM userscart WHERE (emailaddress = '$email' AND emailaddress != '') OR ip_address = '$ip'");
        $numsaved = mysql_num_rows($cartquery);

        for($iii=0; $iii < $numsaved; $iii++) {
            $savedcartimageid = mysql_result($cartquery,$iii,'imageid');
            $prevsavedcartlist = $prevsavedcartlist." ".$savedcartimageid;
        }

		//MAKE SURE CORRECT BUTTON SHOWS
		$search_string2=$prevsavedcartlist;
		$regex2="/$imageid/";
		$cartmatch=preg_match($regex2,$search_string2);

        if($cartmatch > 0) {
        echo'<a class="btn btn-danger" style="margin-left:10px;width:100px;float:left;" href="fullsizemarket.php?imageid=',$imageid,'&action=removed">Remove Photo</a>';
        }
        else {
            echo'<button type="submit" class="btn btn-success buyButton" style="margin-left:10px;width:100px;float:left;" href="download2.php?imageid=',$imageid,'&action=added">Add to Cart</button>
            </form>';		
}

echo'</div>';

?>

</div>
</div>
</div><!--end grid_8-->

<!-------------------------Similar Photos Code----------------------->
<div class="grid_7">
<?php
$similarquery = mysql_query("SELECT * FROM photos WHERE (caption LIKE '$caption' OR location LIKE '$location' OR caption LIKE '$tag1' OR caption LIKE '$tag2' OR caption LIKE '$tag3') ORDER BY RAND() LIMIT 0,5");
$numsimilar = mysql_num_rows($similarquery);
if($numsimilar > 4) {
echo'
    <div class="span6 similarMarket">
    <p class="similarText">Recommended For You</p>';

    for($iii=0; $iii < $numsimilar && $iii < 5; $iii++) {
        $simphoto = mysql_result($similarquery,$iii,'source');
        $simphoto2 = str_replace("userphotos/", "http://photorankr.com/userphotos/medthumbs/", $simphoto); 
        $simimageid = mysql_result($similarquery,$iii,'id');

        echo'<a href="fullsizemarket.php?imageid=',$simimageid,'"><img class="rollover" style="float:left;margin-right:3px;" src="',$simphoto2,'" height="84" width="84" /></a>';
        
    }
}

?>
</div>

<!-------------------------End Grid 24----------------------->
</div>
<!-------------------------Container Ends----------------------->
</div>


</body>
</html>