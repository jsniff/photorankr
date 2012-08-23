<?php

//connect to the database
require "db_connection.php";
require "functionsnav.php"; 
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
    
     //GET INFO FROM CURRENT PHOTO ID
    $imageid = htmlentities($_GET['imageid']);
    
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
    $votes = mysql_result($imagequeryrun,0,'votes');
    $ranking = ($points/$votes);
    $ranking = number_format($ranking,2);
    $location = mysql_result($imagequeryrun,0,'location');
    $camera = mysql_result($imagequeryrun,0,'camera');
    $exhibit = mysql_result($imagequeryrun,0,'set_id');
    $about = mysql_result($imagequeryrun,0,'about');
    $tag1 = mysql_result($imagequeryrun,0,'tag1');
    if($tag1) {$tag1 = $tag1 . ", ";}
    $tag2 = mysql_result($imagequeryrun,0,'tag2');
    if($tag2) {$tag2 = $tag2 . ", ";}
    $tag3 = mysql_result($imagequeryrun,0,'tag3');
    if($tag3) {$tag3 = $tag3 . ", ";}
    $tag4 = mysql_result($imagequeryrun,0,'tag4');
    if($tag4) {$tag4 = $tag4 . ", ";}
    $singlestyletags = mysql_result($imagequeryrun,0,'singlestyletags');
    $singlecategorytags = mysql_result($imagequeryrun,0,'singlecategorytags');
    $singlestyletagsarray = explode("  ", $singlestyletags);
    $singlecategorytagsarray   = explode("  ", $singlecategorytags);
    for($iii=0; $iii < count($singlestyletagsarray); $iii++) {
        if($singlestyletagsarray[$iii] != '') {
        $singlestyletagsfinal = $singlestyletagsfinal . $singlestyletagsarray[$iii] . ", "; }
    }
    for($iii=0; $iii < count($singlecategorytagsarray); $iii++) {
        if($singlecategorytagsarray[$iii] != '') {
        $singlecategorytagsfinal = $singlecategorytagsfinal . $singlecategorytagsarray[$iii] . ", "; }
    }
    
    $keywords = $tag1 . $tag2 . $tag3 . $tag4 . $singlestyletagsfinal . $singlecategorytagsfinal;
    $keywords = substr_replace($keywords ," ",-2);
    
    $ownerquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$owner'");
    $profilepic = mysql_result($ownerquery,0,'profilepic');
    $profilepic = 'http://photorankr.com/' . $profilepic;
    $firstname = mysql_result($ownerquery,0,'firstname');
    $lastname = mysql_result($ownerquery,0,'lastname');
    $userid = mysql_result($ownerquery,0,'user_id');
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
            
            $maybecheckquery=mysql_query("SELECT * FROM usersmaybe WHERE emailaddress = '$email'");
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
        $querycheck = mysql_query("SELECT emailaddress FROM userscart WHERE imageid = '$imageid'");
        $emailcheck = mysql_result($querycheck,0,'emailaddress');
        if($email == $emailcheck) {
            $removequery = mysql_query("DELETE FROM userscart WHERE imageid = '$imageid' AND emailaddress = '$email'");
        }
    }

           
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
"http://w3.org/TR/html4/strict.dtd">
<html>
  <head>     
	<title>Fullsize Photo - "<?php echo $title; ?>"</title>
    
    <meta property="og:image" content="http://photorankr.com/<?php echo $image; ?>">
   <meta name="Generator" content="EditPlus">
  <meta name="Author" content="PhotoRankr, PhotoRankr.com">
  <meta name="Keywords" content="photos, sharing photos, photo sharing, photography, photography club, sell photos, sell photography, where to sell my photography, good sites for selling photography, making money from photography, making money off photography, social networking, social network, social networks, where to sell my photos, good sites for selling photos, good site to sell photos, making money from photos">
  <meta name="Description" content="Purchase this photo from PhotoRankr.">
  
  
 <link rel="stylesheet" href="market/css/bootstrapNew.css" type="text/css" />
    <link rel="stylesheet" href="market/css/reset.css" type="text/css" />
    <link rel="stylesheet" href="market/css/text.css" type="text/css" />
    <link rel="stylesheet" href="market/css/960_24.css" type="text/css" />
    
    <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
    <script src="bootstrap-dropdown.js" type="text/javascript"></script>
    <script src="bootstrap-collapse.js" type="text/javascript"></script>
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
            .show { display: block;  }
            .hide { display: none; }
            .click { cursor:pointer; }
            .clicked {background-color:#ddd;color:#000;cursor:pointer;}
            #row { cursor:pointer; }
            #row2 { cursor:pointer; }
            #row3 { cursor:pointer; }
            #row4 { cursor:pointer; }
            #row5 { cursor:pointer; }
            #row6 { cursor:pointer; }
            #row7 { cursor:pointer; }
            
            .rollover {border:1px solid transparent;}
            .rollover:hover {border:1px solid black;}
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

<body class="background" style="overflow-x: hidden;min-width:1220px;">

<?php navbarnew(); ?>



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




<div class="container_24" style="padding-top:55px;padding-bottom:30px;"><!--Grid container begin-->

<?php
$campaigntitlequery = mysql_query("SELECT title from campaigns WHERE id = '$campaign'");
$camptitle = mysql_result($campaigntitlequery,0,'title');
?>
 
<!--TITLE OF PHOTO-->     
<div class="grid_24">
<div class="grid_21 pull_2"><div style="margin-top:10px;padding-top:5px;padding-left:3px;line-height:30px;font-size:25px;"><?php echo'"',$title,'"'; ?>
</div></div></div>

<!--BIG IMAGE BOX-->
<div class="grid_24">

<div class="grid_10 pull_2" style="margin-top:150px;">
<div style="z-index:1;height:<?php echo $newheight; ?>px;width:<?php echo $newwidth; ?>px;margin-top:-135px;">
<img onmousedown="return false" oncontextmenu="return false;" alt="<?php echo $tags; ?>" src="<?php echo $imagebig2; ?>" alt="<?php echo $title; ?>" height="<?php echo $newheight; ?>px" width="<?php echo $newwidth; ?>px" /></div>

<?php
    if($faves > 5 || $points > 120 || $views > 100) {
        echo'<img style="margin-top:-40px;margin-left:',$newwidth-55,'px;" src="graphics/toplens2.png" height="85" />';
    }
?>
                
</div> 

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
        
        
<div class="grid_4 push_4" style="margin-top:20px;">
<div class="span6">
<table class="table">
<thead>
<tr>
<th>Size</th>
<th>Resolution</th>
<th>Price</th>
</tr>
</thead>
<tbody>

<form action="myprofile.php?view=store&option=cart" method="POST" />

<tr id="row" onclick="showClicked();" style="color:black;">
<td>Small</td>
<td><?php echo $smallwidth; ?> X <?php echo $smallheight; ?></td>
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
<td>License</td>
<td colspan="2"><input style="margin-left:130px;"  type="radio" name="license" value="standard"  onclick="showSelectHide();" /><a style="color:black;text-decoration:none;" href="#"  rel="popover" data-content="

<span style='font-size:13px;'>A perpetual, non-exclusive, non-transferable, worldwide license to use the Content for the following permitted uses:
</br><ul>
<li>Advertising and promotional projects (printed materials, commercials, etc.)</li>
<li>Entertainment applications (books, editorials, broadcast presentations, etc.)</li>
<li>Online or electronic publications (includes use on web pages up to 1200 x 800 pixels)</li>
<li>Prints, posters, and reproductions for personal or promotional purposes up to 500,000 times</li>

</ul></br>
This license does not allow you to:
</br><ul>
<li>Use the Content in products for resale, license, or other distribution unless original is fundamentally modified</li>
<li>Use the Content in more than one location at a time</li>
<li>Incorporate the Content in any product that results in a re-distribution or re-use of the Content</li>
<li>Use the Content in a format that enables it to be downloaded in any peer-to-peer file sharing arrangement</li>
<li>Use 'Editorial Use Only' for any commercial use</li>
<li>Reproduce the Content in excess of 500,000 times</li>
</ul>
</br>
The Standard Content License Agreement governs this option.
</br></br>
</span>

" data-original-title="What is the Standard License?">&nbsp;&nbsp;&nbsp;Standard</a>

<script>  
    $(function ()  
    { $("#licensepopover").popover();  
    });  
</script>

<input style="margin-left:15px;" type="radio" name="license" value="extended"  onclick="showSelect();"/>&nbsp;&nbsp;&nbsp;Extended Options</td>

</tr>
</tbody>
</table>

        <script type="text/javascript">
            function showSelect() {
                var select = document.getElementById('table');
                select.className = 'show';
            }
            function showSelectHide() {
                var select = document.getElementById('table');
                select.className = 'hide';
            }
        </script>



<div id="table" class="hide">
<table class="table">
<tbody>
<tr id="row4" onclick="showClicked4();" style="color:black;">
<td><a style="color:black;text-decoration:none;" href="#" id="multiseat" rel="popover" data-content="<span style='font-size:13px;'>This option allows you to extend usage of the Content to more than one person within your organization, provided that all users are either employees or agree to be bound by the Extended Content License Provisions.
</br></br></span>

" data-original-title="Multi-Seat - Unlimited">Multi-Seat - Unlimited</a>

<script>  
    $(function ()  
    { $("#multiseat").popover();  
});
</script>

</td>
<td colspan="2">+ $20</td>
</tr>
<tr id="row5" onclick="showClicked5();" style="color:black;">
<td><a style="color:black;text-decoration:none;" href="#" rel="popover" data-content="<span style='font-size:13px;'>This Extended License Provision removes the 500,000 limit on reproductions and allows for unlimited reproductions.</br></br>The Extended Content License Provisions govern this option.</br></br></span>

" data-original-title="Unlimited Reproduction / Print Runs">Unlimited Reproduction / Print Runs</a>

<script>  
    $(function ()  
    { $("#unlimitprintpopover").popover();  
    });  
</script>

</td>
<td colspan="2">+ $35</td>
</tr>
<tr id="row6" onclick="showClicked6();" style="color:black;">
<td><a style="color:black;text-decoration:none;" href="#" rel="popover" data-content="<span style='font-size:13px;'>This option allows you to produce the following items for resale, license, or other distribution:</br>
<ul>
<li>Up to 100,000 cards, stationery items, stickers, or paper products</li>
<li>Up to 10,000 posters, calendars, mugs, or mousepads</li>
<li>Up to 2,000 t-shirts, apparel items, games, toys, entertainment goods, or framed artwork</li>
</br>
The Extended Content License Provisions govern this option.
</br></br>
</span>

" data-original-title="Items for Resale - Limited Run">Items for Resale - Limited Run</a>

<script>  
    $(function ()  
    { $("#resalepopover").popover();  
    });  
</script>

</td>
<td colspan="2">+ $35</td>
</tr>
<tr id="row7" onclick="showClicked7();" style="color:black;">
<td><a style="color:black;text-decoration:none;" href="#" rel="popover" data-content="<span style='font-size:13px;'>This option allows you to produce the following items for resale, license, or other distribution:</br>
<ul>
<li>Electronic templates for e-greeting or similar cards</li>
<li>Electronic templates for web or applications development</li>
<li>PowerPoint or Keynote templates</li>
<li>Screensavers and e-mail or brochures templates</li>
</ul>
<br>
Under this option:
<ul>
<li>The right to produce the E-Resale Merchandise does not grant any intellectual property or other rights to the Content</li>
<li>You agree to indemnify PhotoRankr from any expense incurred in connection with any E-Resale Merchandise</li>
</ul>
</br>
The Extended Content License Provisions govern this option.
</br></br>
</span>

" data-original-title="Electronic Items for Resale or Other Distribution - Unlimited Run">Electronic Items for Resale or Other Distribution - Unlimited Run</a>

<script>  
    $(function ()  
    { $("#electronicresalepopover").popover();  
    });  
</script>

</td>
<td colspan="2">+ $35</td>
</tr>
<tr>
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


<input type="hidden" id="multiseat" name="multiseat" value="" />
<input type="hidden" id="unlimited" name="unlimited" value="" />
<input type="hidden" id="resale" name="resale" value="" />
<input type="hidden" id="electronic" name="electronic" value="" />


<?php

        $removequery=mysql_query("SELECT * FROM usersmaybe WHERE emailaddress = '$email'");
        $numsavedremove = mysql_num_rows($removequery);

        for($iii=0; $iii < $numsavedremove; $iii++) {
            $removeimageid = mysql_result($removequery,$iii,'imageid');
            $removelist = $removelist." ".$removeimageid;
        }

		//MAKE SURE CORRECT BUTTON SHOWS
		$search_string4=$removelist;
		$regex4="/$imageid/";
		$removematch=preg_match($regex4,$search_string4);
        
        if($removematch > 0) {
        echo'<a class="btn btn-primary" style="margin-left:200px;width:80px;float:left;" href="#"">Photo Saved</a>';
        }
        else {
        echo'<a class="btn btn-success" style="margin-left:200px;width:80px;float:left;" data-toggle="modal" data-backdrop="static" href="#maybemodal">Maybe Later</a>';
        }

		$cartquery=mysql_query("SELECT * FROM userscart WHERE emailaddress = '$email'");
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
        echo'<a class="btn btn-danger" style="margin-left:10px;width:120px;float:left;" href="fullsize2.php?imageid=',$imageid,'&action=removed">Remove from Cart</a>';
        }
        else {
            echo'<button type="submit" class="btn btn-success" style="margin-left:10px;width:100px;float:left;" href="download2.php?imageid=',$imageid,'&action=added">Add to Cart</button>
            </form>';		
}

?>
</div>


<div class="span6" style="margin-left:0px;margin-top:10px;">

<b>Photo Market Details</b><br /><hr />
<div><span style="float:left;">Photographer:</span><a style="font-weight:bold;color:#3e608c;" href="viewprofile.php?u=<?php echo $userid; ?>"><img style="float:left;margin-top:-10px;margin-left:5px;border: 1px solid rgb(115,115,115);" src="<?php echo $profilepic; ?>" alt="<?php echo $fullname; ?>" height="30" width="30" /><span style="padding-left:5px;"><?php echo $fullname; ?></span></a></div>
<br />

<?php 

    if($location) {echo'
        Location: ',$location,'<br />'; }

    if($about) {echo'
        About Photo: ',$about,'<br />'; }

    if($exhibit) {
        $exname = mysql_query("SELECT * FROM sets WHERE id = '$exhibit'");
        $exhibitname = mysql_result($exname,0,'title');
        echo'
        Exhibit: <a href="viewprofile.php?u=',$userid,'&view=exhibits&set=',$exhibit,'">',$exhibitname,'</a><br />'; }

if($keywords) {echo'
        Keywords: ',$keywords,'<br />'; }
 
?>

</div>


<div class="span6" style="margin-left:0px;margin-top:25px;">

    <b>Photo Network Details</b><br /><hr />

    <div style="margin-top:-10px;">Photo Rank: <?php echo $ranking; ?>
    <br />

    Views: <?php echo $views; ?>
    <br />

    Favorites: <?php echo $faves; ?>
    <br />
    
    <a href="fullsize.php?image=<?php echo $image; ?>">View Photo in Network</a>

</div>


<br />
</div>
</div>

<!--SIMILAR PHOTOS CODE-->
<?php
$similarquery = mysql_query("SELECT * FROM photos WHERE (caption LIKE '$caption' OR location LIKE '$location' OR caption LIKE '$tag1' OR caption LIKE '$tag2' OR caption LIKE '$tag3') ORDER BY RAND() LIMIT 0,5");
$numsimilar = mysql_num_rows($similarquery);
if($numsimilar > 4) {
echo'
    <div class="span6">
    <b>Similar Photos:</b><br /><hr />';

    for($iii=0; $iii < $numsimilar && $iii < 5; $iii++) {
        $simphoto = mysql_result($similarquery,$iii,'source');
        $simphoto2 = str_replace("userphotos/", "http://photorankr.com/userphotos/medthumbs/", $simphoto); 
        $simimageid = mysql_result($similarquery,$iii,'id');

        echo'<a href="fullsizemarket.php?imageid=',$simimageid,'"><img class="rollover" style="float:left;margin-right:3px;" src="',$simphoto2,'" height="85" width="85" /></a>';
        
    }
}

?>
</div>




</div><!--end 24 grid-->

</div><!--end container-->
</div>
<br /><br />
<?php footer(); ?>

<!--Javascripts-->
<script src="market/js/bootstrap.js" type="text/javascript"></script>

</body>
</html>
      
       
        
    