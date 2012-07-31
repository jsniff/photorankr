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
    $imageid = htmlentities($_GET['imageid']);
    
    //add to the views column
    $updatequery = mysql_query("UPDATE photos SET views=views+1 WHERE   id='$imageid'") or die(mysql_error());

    $imagequery = "SELECT * FROM photos WHERE id = '$imageid'";
    $imagequeryrun= mysql_query($imagequery);
    $image = mysql_result($imagequeryrun,0,'source');
    $owner = mysql_result($imagequeryrun,0,'emailaddress');
    $price = mysql_result($imagequeryrun,0,'price');
    $points = mysql_result($imagequeryrun,0,'points');
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
       
            $maybecheckquery=mysql_query("SELECT * FROM maybe WHERE emailaddress = '$repemail'");
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
            $savequery = mysql_query("INSERT INTO maybe (source,caption,price,emailaddress,imageid) VALUES  ('$imagebig2','$caption','$price','$repemail','$imageid')");
            
        }
    }
    
    //REMOVE PHOTO QUERY
    if(htmlentities($_GET['action']) == "removed") { 
        $querycheck = mysql_query("SELECT emailaddress FROM cart WHERE imageid = '$imageid'");
        $emailcheck = mysql_result($querycheck,0,'emailaddress');
        if($repemail == $emailcheck) {
            $removequery = mysql_query("DELETE FROM cart WHERE imageid = '$imageid' AND emailaddress = '$repemail'");
        }
    }

           
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
"http://w3.org/TR/html4/strict.dtd">
<html>
  <head>      
   <meta name="description" content="View the fullsize image of a photo from a campaign">
   <meta name="keywords" content="campaign, view, image, full-size, photo, photography">
   <meta name="author" content="The PhotoRankr Team">
 
	<title>Fullsize Photo - "<?php echo $title; ?>"</title>
  <link rel="stylesheet" href="css/bootstrapnew2.css" type="text/css" />
    <link rel="stylesheet" href="css/reset.css" type="text/css" />
    <link rel="stylesheet" href="css/text.css" type="text/css" />
    <link rel="stylesheet" href="css/960_24.css" type="text/css" />
    <link rel="stylesheet" href="css/index.css" type="text/css"/> 
    <link rel="stylesheet" href="css/itunes.css" type="text/css"/> 
    <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
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
            .show { display: block;  }
            .hide { display: none; }
            .click { }
            .clicked {background-color:black;}
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

<!--STRIPE SCRIPTS-->
 <script type="text/javascript" src="https://js.stripe.com/v1/"></script>
        <!-- jQuery is used only for this example; it isn't required to use Stripe -->
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
        <script type="text/javascript">
            // this identifies your website in the createToken call below
            Stripe.setPublishableKey('pk_NuzRruZd0ks8VMufKgWZtecdiIqFK');

            function stripeResponseHandler(status, response) {
                if (response.error) {
                    // re-enable the submit button
                    $('.submit-button').removeAttr("disabled");
                    // show the errors on the form
                    $(".payment-errors").html(response.error.message);
                } else {
                    var form$ = $("#payment-form");
                    // token contains id, last4, and card type
                    var token = response['id'];
                    // insert the token into the form so it gets submitted to the server
                    form$.append("<input type='hidden' name='stripeToken' value='" + token + "' />");
                    // and submit
                    form$.get(0).submit();
                }
            }

            $(document).ready(function() {
                $("#payment-form").submit(function(event) {
                    // disable the submit button to prevent repeated clicks
                    $('.submit-button').attr("disabled", "disabled");
                    // createToken returns immediately - the supplied callback submits the form if there are no errors
                    Stripe.createToken({
                        number: $('.card-number').val(),
                        cvc: $('.card-cvc').val(),
                        exp_month: $('.card-expiry-month').val(),
                        exp_year: $('.card-expiry-year').val()
                    }, stripeResponseHandler);
                    return false; // submit from callback
                });
            });

            if (window.location.protocol === 'file:') {
                alert("stripe.js does not work when included in pages served over file:// URLs. Try serving this page over a webserver. Contact support@stripe.com if you need assistance.");
            }
</script>
</head>

<body class="background" style="overflow-x: hidden;">

<?php navbarnew(); ?>



<!--MAYBE LATER MODAL-->
<div class="modal hide fade" id="maybemodal" style="overflow:hidden;">
      
<?php
if($_SESSION['loggedin'] != 2) {

echo'
<div class="modal-header">
<a style="float:right" class="btn btn-primary" data-dismiss="modal" >Close</a>
<img style="margin-top:-4px;float:left;" src="graphics/logomarket.png" width="180" />
  </div>
  <div modal-body" style="width:600px;">

<div id="content" style="font-size:16px;width:500px;">
		
<img style="border: 1px solid black;margin-left:10px;margin-top:30px;" src="',$imagebig2,'" 
height="100px" width="100px" /> 

<div style="width:500px;margin-left:130px;margin-top:-80px;">
"',$title,'"<br />                 

Please Login to Save this Photo.<br /><br /><br />

</div>
</div>';

    }
        
        
     if($_SESSION['loggedin'] == 2) {
    
		$maybequery=mysql_query("SELECT * FROM maybe WHERE emailaddress ='$repemail'");
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
                <div class="modal-header">
                <a style="float:right" class="btn btn-primary" data-dismiss="modal">Close</a>
                <img style="margin-top:-4px;" src="graphics/logomarket.png" width="150" />&nbsp;&nbsp;<span style="font-size:16px;">You already saved this photo</span>
                </div>
                <div modal-body" style="width:600px;">

                <div id="content" style="font-size:16px;width:500px;">
		
                <img style="border: 1px solid black;margin-left:10px;margin-top:30px;" src="',$profilepic,'" 
height="100px" width="100px" />

                <div style="width:500px;margin-left:130px;margin-top:-90px;">
',$firstname,' ',$lastname,'<br />                 

                ',$numberofpics,' photos <br />

                Portfolio Average: ',$portfolioranking,' <br /><br /><br />

                </div>
                </div>';
        }

        else {
            
			echo'
                <div class="modal-header">
                <a style="float:right" class="btn btn-primary" href="fullsize2.php?imageid=',$imageid,'&ml=saved">Close</a>
                <img style="margin-top:-4px;" src="graphics/logomarket.png" width="150" />&nbsp;&nbsp;<span style="font-size:16px;">Photo Saved.</span>
                </div>
                <div modal-body" style="width:600px;">

                <div id="content" style="font-size:16px;width:500px;">
		
                <img style="border: 1px solid black;margin-left:10px;margin-top:30px;" src="',$profilepic,'" 
height="100px" width="100px" />

                <div style="width:500px;margin-left:130px;margin-top:-90px;">
                ',$firstname,' ',$lastname,'<br />                 

                ',$numberofpics,' photos <br />

                Portfolio Average: ',$portfolioranking,' <br /><br /><br />

                </div>
                </div>';
            
        }
    
    }
        
?>

</div>
</div>




<div class="container_24" style="padding-bottom:30px;"><!--Grid container begin-->

<?php
$campaigntitlequery = mysql_query("SELECT title from campaigns WHERE id = '$campaign'");
$camptitle = mysql_result($campaigntitlequery,0,'title');
?>
 
<!--TITLE OF PHOTO-->     
<div class="grid_24">
<div class="grid_21 pull_2"><div style="margin-top:10px;padding-top:5px;padding-left:3px;line-height:30px;font-size:25px;
"><?php echo'"',$title,'"'; ?>
</div></div></div>

<!--BIG IMAGE BOX-->
<div class="grid_24">

<div class="grid_10 pull_2" style="margin-top:150px;">
<div class="phototitle" style="height:<?php echo $newheight; ?>px;width:<?php echo $newwidth; ?>px;margin-top:-135px;">
<img onmousedown="return false" oncontextmenu="return false;" alt="<?php echo $tags; ?>" src="<?php echo $imagebig2; ?>" height="<?php echo $newheight; ?>px" width="<?php echo $newwidth; ?>px" /></div>
</div> 

<?php
$smallwidth = number_format(($originalwidth/2.5),0,',','');
$smallheight = number_format(($originalheight/2.5),0,',','');
$smallprice = number_format(($price / 2.5),0,',','');
$medwidth = number_format(($originalwidth/1.3),0,',','');
$medheight = number_format(($originalheight/1.3),0,',','');
$medprice = number_format(($price / 1.5),0,',','');
?>

        <script type="text/javascript">
            function showClicked() {
                var select = document.getElementById('row');
                select.className = 'clicked';
            }
                function showClicked2() {
                var select = document.getElementById('row2');
                select.className = 'clicked';
            }
                function showClicked3() {
                var select = document.getElementById('row3');
                select.className = 'clicked';
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
<tr id="row" onclick="showClicked();">
<td>Small</td>
<td><?php echo $smallwidth; ?> X <?php echo $smallheight; ?></td>
<td>$<?php echo $smallprice; ?></td>
</tr>
<tr id="row2" onclick="showClicked();">  
<td>Medium</td>
<td><?php echo $medwidth; ?> X <?php echo $medheight; ?></td>
<td>$<?php echo $medprice; ?></td>
</tr>
<tr id="row3" onclick="showClicked();">
<td>Large</td>
<td><?php echo $originalwidth; ?> X <?php echo $originalheight; ?></td>
<td>$<?php echo $price; ?></td>
</tr>
<tr>
<td>License</td>
<td colspan="2"><input style="margin-left:130px;"  type="radio" name="license" value="standard"  onclick="showSelectHide();" />&nbsp;&nbsp;&nbsp;Standard <input style="margin-left:15px;" type="radio" name="license" value="extended"  onclick="showSelect();"/>&nbsp;&nbsp;&nbsp;Extended</td>
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
<tr>
<td>License 1</td>
<td colspan="2">+ $10</td>
</tr>
<tr>
<td>License 2</td>
<td colspan="2">+ $30</td>
</tr>
<tr>
<td>License 3</td>
<td colspan="2">+ $50</td>
</tr>
</tbody>
</table>
</div>

<div>


<?php

        $removequery=mysql_query("SELECT * FROM maybe WHERE emailaddress = '$repemail'");
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

		$cartquery=mysql_query("SELECT * FROM cart WHERE emailaddress = '$repemail'");
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
        echo'<a class="btn btn-success" style="margin-left:10px;width:80px;float:left;" href="download2.php?imageid=',$imageid,'#added">Add to Cart</a>';
        }

?>


</div>

<div class="span6" style="margin-left:0px;margin-top:10px;">
<b>Photo Details</b><br /><hr />
<div><span style="float:left;">Photographer:</span><a href="viewprofile.php?u=<?php echo $userid; ?>"><img style="float:left;margin-top:-10px;padding-left:5px;" src="<?php echo $profilepic; ?>" height="30" width="30" /><span style="padding-left:5px;"><?php echo $fullname; ?></span></a></div>
<div style="margin-top:20px;">Photo Rank: <?php echo $ranking; ?>
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

</div>


</div>
</div>
</div>







 
 

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
      
       
        
    