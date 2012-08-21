<?php


//connect to the database
require "db_connection.php";
require "functionsnav.php";

//log them out if they try to logout
session_start();

if($_GET['action'] == logout) {
	$_SESSION['loggedin'] = 0;
	session_destroy();
}


//start session
session_start();
//if the login form is submitted
if ($_GET['action'] == "login") { // if login form has been submitted

	// makes sure they filled it in
	if(!$_POST['emailaddress']) {
    echo '<META HTTP-EQUIV="Refresh" Content="0; URL=signup.php?action=fie">';
	}
    
    if(!$_POST['password']) {
       echo '<META HTTP-EQUIV="Refresh" Content="0; URL=signup.php?action=fip">';

    }

	// checks it against the database
	if (!get_magic_quotes_gpc()) {
   	$_POST['emailaddress'] = addslashes($_POST['emailaddress']);
    	}
    	$check = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '".$_POST['emailaddress']."'")or die(mysql_error());
	//Gives error if user dosen't exist

	$check2 = mysql_num_rows($check);

	if ($check2 == 0) {
                 echo '<META HTTP-EQUIV="Refresh" Content="0; URL=signup.php?action=nu">';
        }
        
	$info = mysql_fetch_array($check);    
	if($_POST['password'] == $info['password']){

	//then redirect them to the same page as signed in and set loggedin to 1
	$_SESSION['loggedin']=1; 
	$_SESSION['email']=$_POST['emailaddress'];
    $email = $_SESSION['email'];
	}
    
	//gives error if the password is wrong
    	if ($_POST['password'] != $info['password']) {
           echo '<META HTTP-EQUIV="Refresh" Content="0; URL=signup.php?action=lp">';

	}
}


//GET SEARCH TERM
$searchterm = trim(htmlentities($_GET['searchterm']));
$filter = htmlentities($_GET['filter']);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

 <meta name="Generator" content="EditPlus">
  <meta name="Author" content="PhotoRankr, PhotoRankr.com">
  <meta name="Keywords" content="photos, sharing photos, photo sharing, photography, photography club, sell photos, sell photography, where to sell my photography, good sites for selling photography, making money from photography, making money off photography, social networking, social network, social networks, where to sell my photos, good sites for selling photos, good site to sell photos, making money from photos">
  <meta name="Description" content="Search for a specific photo, photographer, or campaign.">
  
   <link rel="stylesheet" type="text/css" href="css/bootstrapNew.css" />
    <link rel="stylesheet" href="960_24.css" type="text/css" />
        <link rel="stylesheet" href="css/style.css" type="text/css" />
  <link rel="stylesheet" href="text2.css" type="text/css" />
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
  <script src="bootstrap-dropdown.js" type="text/javascript"></script>
  <script src="bootstrap-collapse.js" type="text/javascript"></script>
  <link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>

<title><?php echo $searchterm; ?></title>

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
<body style="overflow-x:hidden; background-color: #eeeff3;min-width:1220px;">

<?php navbarnew(); ?>

<div class="container_24">

<div class="grid_4" style="margin-top:70px;">
<span style="font-weight:bold;font-size:14px;">Search Filters</span>
<hr style="width:160px;">

<div <?php if($filter == ''){echo'style="background-color:#ddd;padding:5px;"';} ?>><a style="color:#3e608c;" href="search.php?searchterm=<?php echo $searchterm; ?>">Photos</a></div><br />

<div <?php if($filter == 'photogs'){echo'style="background-color:#ddd;padding:5px;"';} ?>><a style="color:#3e608c;" href="search.php?searchterm=<?php echo $searchterm; ?>&filter=photogs">Photographers</a></div><br />

<div <?php if($filter == 'exhibits'){echo'style="background-color:#ddd;padding:5px;"';} ?>><a style="color:#3e608c;" href="search.php?searchterm=<?php echo $searchterm; ?>&filter=exhibits">Exhibits</a></div><br />

<div <?php if($filter == 'campaigns'){echo'style="background-color:#ddd;padding:5px;"';} ?>><a style="color:#3e608c;" href="search.php?searchterm=<?php echo $searchterm; ?>&filter=campaigns">Campaigns</a></div><br />
</div>

<div class="grid_20 push_4" style="border-left: 1px solid #ccc;margin-top:-220px;width:825px;">

<?php 
    
     if($filter == 'photogs') {
        $searchterm = explode(" ",$searchterm);
        $query =  mysql_query("SELECT * FROM userinfo WHERE firstname LIKE '%".$searchterm[0]."%' AND lastname  LIKE '%".$searchterm[1]."%' OR lastname LIKE '%".$searchterm[0]."%'");
        $numresults = mysql_num_rows($query);
        echo'<div style="font-size:18px;margin-top:10px;padding:15px;">Photographers | ',$numresults,' Results<hr></div>'; 


        for($iii=0; $iii<$numresults; $iii++) {
            $photographer = mysql_result($query,$iii,'firstname')." ".mysql_result($query,$iii,'lastname');
            $profilepic = mysql_result($query,$iii,'profilepic'); 
            $userid = mysql_result($query,$iii,'user_id'); 
            $reputation = mysql_result($query,$iii,'reputation'); 
            $useremail = mysql_result($query,$iii,'emailaddress'); 
            $followersquery="SELECT * FROM userinfo WHERE following LIKE '%$useremail%'";
            $followersresult=mysql_query($followersquery);
            $numberfollowers = mysql_num_rows($followersresult);
            $userphotos="SELECT * FROM photos WHERE emailaddress = '$useremail' ORDER BY (points/votes) DESC";
            $userphotosquery=mysql_query($userphotos);
            $profileimage = mysql_result($userphotosquery,0,'source'); 
            $profileimage = str_replace('userphotos/','userphotos/thumbs/',$profileimage);
            $profileimage2 = mysql_result($userphotosquery,1,'source');
            $profileimage2 = str_replace('userphotos/','userphotos/thumbs/',$profileimage2);
            $profileimage3 = mysql_result($userphotosquery,2,'source');
            $profileimage3 = str_replace('userphotos/','userphotos/thumbs/',$profileimage3);
            $profileimage4 = mysql_result($userphotosquery,3,'source');
            $profileimage4 = str_replace('userphotos/','userphotos/thumbs/',$profileimage4);
            $numphotos=mysql_num_rows($userphotosquery);
                for($ii = 0; $ii < $numphotos; $ii++) {
                    $points = mysql_result($userphotosquery, $ii, "points");
                    $votes = mysql_result($userphotosquery, $ii, "votes");
                    $totalfaves = mysql_result($userphotosquery, $ii, "faves");
                    $portfoliopoints+=$points;
                    $portfoliovotes+=$votes;
                    $portfoliofaves+=$totalfaves;
                }
                if($portfoliovotes > 0) {
                    $portfolioranking=($portfoliopoints/$portfoliovotes);
                    $portfolioranking=number_format($portfolioranking, 2, '.', '');
                }
                elseif($portfoliovotes = 0){$portfolioranking="N/A";}
            
            echo'<div style="padding:15px;float:left;"><img src="',$profilepic,'" height="100" width="100" alt="',$photographer,'" />';
                            
                if($reputation > 60) {
                    echo'<img style="margin-top:-10px;margin-left:10px;" src="graphics/toplens.png" height="75" />';
                }
            
        echo'
            </div><div style="float:left;margin-top:15px;"><a style="color:#3e608c;font-weight:bold;font-size:14px;" href="viewprofile.php?u=',$userid,'">',$photographer,'</a><br />Reputation: ',$reputation,'<br />Followers: ',$numberfollowers,'<br />Portfolio Ranking: ',$portfolioranking,'<br />Favorites: ',$portfoliofaves,'</div><div style="float:left;margin-top:15px;margin-left:30px;">';if($numphotos > 3){echo'<img style="padding:3px;" src="',$profileimage,'" height="100" width="100" /><img style="padding:3px;" src="',$profileimage2,'" height="100" width="100" /><img style="padding:3px;" src="',$profileimage3,'" height="100" width="100" /><img style="padding:3px;" src="',$profileimage4,'" height="100" width="100" />';}echo'</div><hr>';
        }
    }    
    
    elseif($filter == '') {
        $querycount = mysql_query("SELECT * FROM photos WHERE concat(caption,location,tag1,tag2,tag3,tag4) LIKE '%$searchterm%'");
        $numresultscount = mysql_num_rows($querycount);
        $query = mysql_query("SELECT * FROM photos WHERE concat(caption,location,tag1,tag2,tag3,tag4,singlestyletags,singlecategorytags) LIKE '%$searchterm%' ORDER BY views DESC LIMIT 0,20");
        $numresults = mysql_num_rows($query);
        echo'<div style="font-size:18px;margin-top:10px;padding:15px;">Photos | ',$numresultscount,' Results<hr></div>'; 
        
        echo'<div id="thepics">';
        echo'<div id="container">';
        for($iii=0; $iii<$numresults; $iii++) {
            $image = mysql_result($query,$iii,'source');
            $imagemed = str_replace('userphotos/','userphotos/medthumbs/',$image);
            $caption = mysql_result($query,$iii,'caption');
            $caption = (strlen($caption) > 30) ? substr($caption,0,27). " &#8230;" : $caption;
            $ranking = (mysql_result($query,$iii,'points')/mysql_result($query,$iii,'votes'));
            $ranking = number_format($ranking,1);
            $faves = mysql_result($query,$iii,'faves');
            $views = mysql_result($query,$iii,'views');
            $useremail = mysql_result($query,$iii,'emailaddress');

            $query2 =  mysql_query("SELECT user_id,firstname,lastname,profilepic FROM userinfo WHERE emailaddress = '$useremail'");
        $numresults = mysql_num_rows($query);
            $photographer = mysql_result($query2,0,'firstname')." ".mysql_result($query2,0,'lastname');
            $profilepic = mysql_result($query2,0,'profilepic');
            $userid = mysql_result($query2,0,'user_id');
            
            list($width,$height) = getimagesize($image);
            $width = $width/4.5;
            $height = $height/4.5;
            
            echo'<div class="fPic" id="',$views,'" style="padding:15px;float:left;width:300px;">';
            
            if($faves > 5 || $points > 120 || $views > 100) {
            echo'
            <div style="margin-top:-50px;"><img style="max-width:300px;max-height:300px;" src="',$imagemed,'" alt="',$caption,'" height="',$height,'" width="',$width,'" />
            <img style="margin-top:',$height,'px;margin-left:',$newwidth-55,'px;" src="graphics/toplens2.png" height="85" /></div>';
            }
            else {
            echo'
           <img style="max-width:300px;max-height:300px;" src="',$imagemed,'" alt="',$caption,'" height="',$height,'" width="',$width,'" />';            
            }

        echo'
            </div><div style="margin-left:30px;float:left;margin-top:',$height/2,';"><span style="font-size:24px;color:black;">"<a style="color:black;" href="fullsize.php?image=',$image,'">',$caption,'</a>"</span><br /><br /><img src="',$profilepic,'" alt="',$photographer,'" width="40" height="40" />&nbsp;&nbsp;<a href="viewprofile.php?u=',$userid,'" style="color:#3e608c;font-weight:bold;font-size:14px;">',$photographer,'</a><br />Photo Rank:&nbsp;<span style="font-size:22px;">',$ranking,'</span><span style="opacity:.7;">/10</span><br />Favorites: <span style="font-size:22px;">',$faves,'</span><br />Views: <span style="font-size:22px;">',$views,'</span><br /><br /></div><hr>';
            
        }
        
    echo'
        </div>
        </div>';
        
        echo'
        <!--AJAX CODE HERE-->
        <div class="grid_16" style="padding:20px;">
            <div id="loadMorePics" style="display: none; text-align: center;font-family:arial,helvetica neue; font-size:15px;">Loading More Photos&hellip;</div>
            </div>';

   echo'<script>

    var last = 0;

        $(window).scroll(function(){
            if($(window).scrollTop() > $(document).height() - $(window).height()-100) {
                if(last != $(".fPic:last").attr("id")) {
                    $("div#loadMorePics").show();
                        $.ajax({
                            url: "loadMoreSearch.php?lastPicture=" + $(".fPic:last").attr("id")+"&searchterm=',$searchterm,'",
                                success: function(html) {
						if(html) {
							$("#thepics").append(html);
							$("div#loadMorePics").hide();
						}
					}
				});
				last = $(".fPic:last").attr("id");
			}
		}
	});
</script>';

    }   
    
    if($filter == 'campaigns') {
        $query = mysql_query("SELECT * FROM campaigns WHERE title LIKE '%$searchterm%'");
        $numresults = mysql_num_rows($query);
        echo'<div style="font-size:18px;margin-top:10px;padding:15px;">Campaigns | ',$numresults,' Results<hr></div>'; 
        
            for($iii=0; $iii<$numresults; $iii++) {
            $title = mysql_result($query,$iii,'title');
            $title = (strlen($title) > 30) ? substr($title,0,27). " &#8230;" : $title;
            $quote = mysql_result($query,$iii,'quote');
            $quote = $quote * .7;
            $license = mysql_result($query,$iii,'license');
            $campid = mysql_result($query,$iii,'id');
            $winneremail = mysql_result($query,$iii,'winneremail');
            $userquery =  mysql_query("SELECT user_id,firstname,lastname,profilepic FROM userinfo WHERE emailaddress = '$winneremail'");
            $photographer = mysql_result($userquery,0,'firstname')." ".mysql_result($userquery,0,'lastname');
            $profilepic = mysql_result($userquery,0,'profilepic');
            $photographerid = mysql_result($userquery,0,'user_id');

        $query2 = mysql_query("SELECT source,points,votes FROM campaignphotos WHERE campaign = '$campid' ORDER BY (points/votes) DESC");
        $numphotos = mysql_num_rows($query2);
        $coverphoto = mysql_result($query2,0,'source');
        $coverphoto = str_replace('userphotos/','market/userphotos/',$coverphoto);
        $coverphoto2 = mysql_result($query2,1,'source');
        $coverphoto2 = str_replace('userphotos/','market/userphotos/',$coverphoto2);
        $coverphoto3 = mysql_result($query2,2,'source');
        $coverphoto3 = str_replace('userphotos/','market/userphotos/',$coverphoto3);
        $coverphoto4 = mysql_result($query2,3,'source');
        $coverphoto4 = str_replace('userphotos/','market/userphotos/',$coverphoto4);
        $coverphoto5 = mysql_result($query2,4,'source');
        $coverphoto5 = str_replace('userphotos/','market/userphotos/',$coverphoto5);



            echo'<div style="padding:15px;float:left;"><img src="',$coverphoto,'" alt="',$title,'" height="100" width="100" /></div><div style="float:left;margin-top:15px;"><a style="color:#3e608c;font-weight:bold;font-size:14px;" href="campaignphotos.php?id=',$campid,'">',$title,'</a><br />Reward: $',$quote,'<br />License Needed: ',$license,'<br />Photos Submitted: ',$numphotos,'<br />Winner:'; if($winneremail != '') {echo'<img src="',$profilepic,'" alt="',$photographer,'" width="30" height="30" /> <a href="viewprofile.php?u=',$photographerid,'">',$photographer,'</a>';}else{echo'&nbsp;<i>Winner not chosen.</i>';} echo'</div><div style="float:left;margin-top:15px;margin-left:30px;">';if($numphotos > 3){echo'<img style="padding:3px;" src="',$coverphoto2,'" height="100" width="100" /><img style="padding:3px;" src="',$coverphoto3,'" height="100" width="100" /><img style="padding:3px;" src="',$coverphoto4,'" height="100" width="100" /><img style="padding:3px;" src="',$coverphoto5,'" height="100" width="100" />';}echo'</div><hr>';

    }
}

 if($filter == 'exhibits') {
        $query = mysql_query("SELECT * FROM sets WHERE concat(title,maintags,about,settag1,settag2,settag3,settag4) LIKE '%$searchterm%'");
        $numresults = mysql_num_rows($query);
        echo'<div style="font-size:18px;margin-top:10px;padding:15px;">Exhibits | ',$numresults,' Results<hr></div>'; 
        
            for($iii=0; $iii<$numresults; $iii++) {
            $title = mysql_result($query,$iii,'title');
            $title = (strlen($title) > 30) ? substr($title,0,27). " &#8230;" : $title;
            $setid = mysql_result($query,$iii,'id');
            $views = mysql_result($query,$iii,'views');
            $owner = mysql_result($query,$iii,'owner');

            $userquery =  mysql_query("SELECT user_id,firstname,lastname,profilepic FROM userinfo WHERE emailaddress = '$owner'");
            $photographer = mysql_result($userquery,0,'firstname')." ".mysql_result($userquery,0,'lastname');
            $profilepic = mysql_result($userquery,0,'profilepic');
            $photographerid = mysql_result($userquery,0,'user_id');

        $query2 = mysql_query("SELECT source,points,votes FROM photos WHERE set_id = '$setid' ORDER BY (points/votes) DESC");
        $numphotos = mysql_num_rows($query2);
        $coverphoto = mysql_result($query2,0,'source');
        if($coverphoto) {
            $coverphoto = str_replace('userphotos/','userphotos/medthumbs/',$coverphoto);
            }
        $coverphoto2 = mysql_result($query2,1,'source');
        if($coverphoto2) {
            $coverphoto2 = str_replace('userphotos/','userphotos/medthumbs/',$coverphoto2);
            }
        $coverphoto3 = mysql_result($query2,2,'source');
        if($coverphoto3) {
            $coverphoto3 = str_replace('userphotos/','userphotos/medthumbs/',$coverphoto3);
            }
        $coverphoto4 = mysql_result($query2,3,'source');
        if($coverphoto4) {
            $coverphoto4 = str_replace('userphotos/','userphotos/medthumbs/',$coverphoto4);
            }
        $coverphoto5 = mysql_result($query2,4,'source');
        if($coverphoto5) {
            $coverphoto5 = str_replace('userphotos/','userphotos/medthumbs/',$coverphoto5);
            }

            echo'<div style="padding:15px;float:left;">';
            if($coverphoto) {
                echo'<img src="',$coverphoto,'" alt="',$title,'" height="100" width="100" />';
            }
            elseif(!$coverphoto) {
                echo'<img src="graphics/no_cover.png" alt="',$title,'" height="100" width="100" />';
            }
            echo'</div><div style="float:left;margin-top:15px;width:190px;"><a style="color:#3e608c;font-weight:bold;font-size:14px;" href="viewprofile.php?u=',$photographerid,'&view=exhibits&set=',$setid,'">',$title,'</a><br />
            <div style="padding:4px;"><img src="',$profilepic,'" width="25" /> <a style="color:black;" href="viewprofile.php?u=',$photographerid,'">',$photographer,'</a></div>
            # Photos: ',$numphotos,'<br />Views: ',$views,'</div><div style="float:left;margin-top:15px;margin-left:30px;">';
            if($coverphoto2) {
                echo'<img style="padding:3px;" src="',$coverphoto2,'" height="100" width="100" />';
                }
            if($coverphoto3) {
                echo'<img style="padding:3px;" src="',$coverphoto3,'" height="100" width="100" />';
                }
            if($coverphoto4) {
                echo'<img style="padding:3px;" src="',$coverphoto4,'" height="100" width="100" />';
                }
            if($coverphoto5) {
                echo'<img style="padding:3px;" src="',$coverphoto5,'" height="100" width="100" />';
                }
               
                echo'</div><hr>';

    }
}


?>

</div>

</div><!--end container-->
</body>
</html>
