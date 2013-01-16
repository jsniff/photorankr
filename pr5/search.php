<?php

//connect to the database
require "db_connection.php";
require "functions.php";

//start the session
session_start();

    // if login form has been submitted
    if(htmlentities($_GET['action']) == "login") { 
        login();
    }
    elseif(htmlentities($_GET['action']) == "logout") { 
        logout();
    }

    $email = $_SESSION['email'];
    
    //GET SEARCH TERM
    $searchterm = trim(htmlentities($_GET['searchterm']));
    $filter = htmlentities($_GET['filter']);
    $order = htmlentities($_GET['order']);

    //Search Queries
    if($filter == '') {
        $query = mysql_query("SELECT * FROM photos WHERE concat(caption,location,tag1,tag2,tag3,tag4) LIKE '%$searchterm%' ORDER BY (points/votes) DESC");
        if($order == 'faves') {
            $query = mysql_query("SELECT * FROM photos WHERE concat(caption,location,tag1,tag2,tag3,tag4) LIKE '%$searchterm%' ORDER BY faves DESC");
        }
        elseif($order == 'views') {
            $query = mysql_query("SELECT * FROM photos WHERE concat(caption,location,tag1,tag2,tag3,tag4) LIKE '%$searchterm%' ORDER BY views DESC");
        }
    }
    elseif($filter == 'people') {
        $searchterm = explode(" ",$searchterm);
        $query =  mysql_query("SELECT * FROM userinfo WHERE firstname LIKE '%".$searchterm[0]."%' AND lastname  LIKE '%".$searchterm[1]."%' OR lastname LIKE '%".$searchterm[0]."%' ORDER BY reputation DESC");
        if($order == 'pts') {
            $query =  mysql_query("SELECT * FROM userinfo WHERE firstname LIKE '%".$searchterm[0]."%' AND lastname  LIKE '%".$searchterm[1]."%' OR lastname LIKE '%".$searchterm[0]."%' ORDER BY totalscore DESC");
        }
        elseif($order == 'views') {
            $query =  mysql_query("SELECT * FROM userinfo WHERE firstname LIKE '%".$searchterm[0]."%' AND lastname  LIKE '%".$searchterm[1]."%' OR lastname LIKE '%".$searchterm[0]."%' ORDER BY profileviews DESC");
        }
    }
    elseif($filter == 'groups') {
    
    }
    elseif($filter == 'collections') {
    
    }
    elseif($filter == 'exhibits') {
        $query = mysql_query("SELECT * FROM sets WHERE concat(title,maintags,about,settag1,settag2,settag3,settag4) LIKE '%$searchterm%' ORDER BY views DESC");
        if($order == 'score') {
            $query = mysql_query("SELECT * FROM sets WHERE concat(title,maintags,about,settag1,settag2,settag3,settag4) LIKE '%$searchterm%' ORDER BY avgscore DESC");
        }
        elseif($order == 'faves') {
            $query = mysql_query("SELECT * FROM sets WHERE concat(title,maintags,about,settag1,settag2,settag3,settag4) LIKE '%$searchterm%' ORDER BY faves DESC");
        }
    }
    elseif($filter == 'blogposts') {
    
    }
    elseif($filter == 'featured') {
    
    }

    $numresults = mysql_num_rows($query);
    $uri = $_SERVER['REQUEST_URI'];
          
?>

<!DOCTYPE HTML>
<head>

    <meta charset = "UTF-8">
    <title> Sell, share and discover brilliant photography </title>
    <link rel="stylesheet" type="text/css" href="css/style.css"/>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>
    <link rel="stylesheet" type="text/css" href="css/960grid.css"/>
    <link rel="stylesheet" type="text/css" href="css/reset.css"/> 
    <link rel="stylesheet" type="text/css" href="css/main3.css"/>

    <link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>
    <link rel="stylesheet" media='screen and (max-width:640px)' href="css/640.css"/>
    <link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>
    
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="js/bootstrap.js" type="text/javascript"></script>
    <script src="js/modernizer.js"></script>


<!--GOOGLE ANALYTICS CODE-->
<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-28031297-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'https://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>



</head>
<body style="overflow-x:hidden; background-color: rgb(244,244,244);">

<?php navbar(); ?>

   <!--big container-->
    <div id="container" class="container_24" style="width:1200px;">

    <div id="searchTitle" style="margin-top:80px;margin-left:120px;">Search Results 
    
    <?php 
    if($searchterm) {
    echo'
        <span id="searchResults">
        ',$numresults,' results for "',htmlentities($_GET['searchterm']),'" 
        </span>';
    }
    ?>
    
    </div>
    
    <!--Search Bar-->
    <div class="searchBar">
    
        <a href="search.php?searchterm=<?php echo $searchterm; ?>"><div class="searchButton" <?php if($filter == '') {echo'style="background-color:#ddd;"';} ?>> <span id="subSearchWord">Photos</span> </div>
        
        <a href="search.php?searchterm=<?php echo $searchterm; ?>&filter=people"><div class="searchButton" <?php if($filter == 'people') {echo'style="background-color:#ddd;"';} ?>><span id="subSearchWord">People</span> </div></a>
        
        <a href="search.php?searchterm=<?php echo $searchterm; ?>&filter=exhibits"><div class="searchButton" <?php if($filter == 'exhibits') {echo'style="background-color:#ddd;"';} ?>> <span id="subSearchWord">Exhibits</span> </div></a>
        
        <a href="search.php?searchterm=<?php echo $searchterm; ?>&filter=groups"><div class="searchButton" <?php if($filter == 'groups') {echo'style="background-color:#ddd;"';} ?>> <span id="subSearchWord">Groups</span> </div></a>
        
        <a href="search.php?searchterm=<?php echo $searchterm; ?>&filter=collections"><div class="searchButton" <?php if($filter == 'collections') {echo'style="background-color:#ddd;"';} ?>> <span id="subSearchWord">Collections</span> </div></a>
        
        <a href="search.php?searchterm=<?php echo $searchterm; ?>&filter=blogposts"><div class="searchButton" <?php if($filter == 'blogposts') {echo'style="background-color:#ddd;"';} ?>> <span id="subSearchWord">Blog Posts</span> </div></a>
        
        <a href="search.php?searchterm=<?php echo $searchterm; ?>&filter=featured"><div class="searchButton" <?php if($filter == 'featured') {echo'style="background-color:#ddd;"';} ?>> <span id="subSearchWord">Featured Galleries</span> </div></a>
        
    </div>
    
    <?php
         if(!$searchterm) {
            echo'<div id="subSearchWord" style="margin: 0 auto;margin-top:120px;margin-left:450px;">Please type in a search term</div>';
        }
        
    if($searchterm) {
    
    echo'
    <!--Narrow Results-->
    <div class="searchSide"> 
        <div id="subSearchWord" style="margin-top:20px;margin-bottom:10px;">Narrow Results</div>';
        
        if($filter == '') {
            echo'
            <a style="text-decoration:none;color:#333;" href="search.php?searchterm=',$searchterm,'"><div id="searchNarrow"'; if($order == '') {echo'style="background-color:#ddd;"';} echo'>Ranking</div></a>
            <a style="text-decoration:none;color:#333;" href="search.php?searchterm=',$searchterm,'&order=faves"><div id="searchNarrow"'; if($order == 'faves') {echo'style="background-color:#ddd;"';} echo'>Favorites</div></a>
            <a style="text-decoration:none;color:#333;" href="search.php?searchterm=',$searchterm,'&order=views"><div id="searchNarrow"'; if($order == 'views') {echo'style="background-color:#ddd;"';} echo'>Views</div></a>
            </div>';
        }
        
        elseif($filter == 'people') {
            echo'
            <a style="text-decoration:none;color:#333;" href="search.php?searchterm=',$searchterm,'&filter=people"><div id="searchNarrow"'; if($order == 'rep') {echo'style="background-color:#ddd;"';} echo'>Reputation</div></a> 
            <a style="text-decoration:none;color:#333;" href="search.php?searchterm=',$searchterm,'&filter=people&order=pts"><div id="searchNarrow"'; if($order == 'pts') {echo'style="background-color:#ddd;"';} echo'>Points</div></a>
            <a style="text-decoration:none;color:#333;" href="search.php?searchterm=',$searchterm,'&filter=people&order=views"><div id="searchNarrow"'; if($order == 'views') {echo'style="background-color:#ddd;"';} echo'>Profile Views</div></a>
            </div>';
        }
        
        elseif($filter == 'exhibits') {
            echo'
            <a style="text-decoration:none;color:#333;" href="search.php?searchterm=',$searchterm,'&filter=exhibits"><div id="searchNarrow"'; if($order == '') {echo'style="background-color:#ddd;"';} echo'>Average Score</div></a>
            <a style="text-decoration:none;color:#333;" href="search.php?searchterm=',$searchterm,'&filter=exhibits&order=faves"><div id="searchNarrow"'; if($order == 'views') {echo'style="background-color:#ddd;"';} echo'>Views</div></a>
            <a style="text-decoration:none;color:#333;" href="search.php?searchterm=',$searchterm,'&filter=exhibits&order=score"><div id="searchNarrow"'; if($order == 'faves') {echo'style="background-color:#ddd;"';} echo'>Faves</div></a>
            </div>';
        }
    
    echo'
    <!--Search Results-->
    <div class="searchLeft">';
    
        if($filter == '') {
    
            echo'<div id="thepics">';
            echo'<div id="container">';
            
            for($iii=0; $iii<$numresults & $iii<15; $iii++) {
                $image = mysql_result($query,$iii,'source');
                $imageid = mysql_result($query,$iii,'id');
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
                $width = $width/3;
                $height = $height/3;
    
                echo'<div class="searchItem fPic" id="',$imageid,'">
                        <div style="float:left;width:400px;">
                            <img style="width:',$width,'px;height:',$height,';max-width:300px;max-height:500px;" src="https://photorankr.com/',$imagemed,'" />
                        </div>
                        <div style="float:left;">
                           <span id="subSearchWord" style="margin-left:-10px;">',$caption,'</span><br /><br />
                           <span id="searchMicro"><img style="width:30px;height:30px;" src="https://photorankr.com/',$profilepic,'" /> ',$photographer,'</span>
                           <br /><br />
                           <span id="searchMicro">Rank: ',$ranking,'/10.0</span>
                           <br />
                           <span id="searchMicro">Views: ',$views,'</span>
                           <br />
                           <span id="searchMicro">Faves: ',$faves,'</span>
                           <br />
                        </div>
                     </div>';
        
            }
            
            echo'</div></div>';
            
            echo'
        <!--AJAX CODE HERE-->
        <div class="grid_16" style="padding:20px;">
            <div id="loadMorePics" style="display: none; text-align: center;font-family:arial,helvetica neue; font-size:15px;"><img style="width:50px;" src="graphics/LoadingGIF.gif" /></div>
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
        
    if($filter == 'people') {
    
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
            
            echo'
                 <div class="searchItem">
                 <div style="padding:15px;float:left;"><img src="https://photorankr.com/',$profilepic,'" height="100" width="100" alt="',$photographer,'" />';
                            
                if($reputation > 60) {
                    echo'<img style="margin-top:-10px;margin-left:10px;" src="https://photorankr.com/graphics/toplens.png" height="75" />';
                }
            
        echo'
            </div><div style="float:left;margin-top:15px;"><a style="color:#3e608c;font-weight:bold;font-size:14px;" href="viewprofile.php?u=',$userid,'">',$photographer,'</a><br />Reputation: ',$reputation,'<br />Followers: ',$numberfollowers,'<br />Portfolio Ranking: ',$portfolioranking,'<br />Favorites: ',$portfoliofaves,'</div><div style="float:left;margin-top:15px;margin-left:30px;">';if($numphotos > 3){echo'<img style="padding:3px;" src="https://photorankr.com/',$profileimage,'" height="100" width="100" /><img style="padding:3px;" src="https://photorankr.com/',$profileimage2,'" height="100" width="100" /><img style="padding:3px;" src="https://photorankr.com/',$profileimage3,'" height="100" width="100" /><img style="padding:3px;" src="https://photorankr.com/',$profileimage4,'" height="100" width="100" />';}echo'</div>
            </div>';
        }
    
    }
    
    if($filter == 'exhibits') {
        
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

            echo'<div class="searchItem">
            
                <div style="padding:15px;float:left;">';
                
            if($coverphoto) {
                echo'<img src="https://photorankr.com/',$coverphoto,'" alt="',$title,'" height="100" width="100" />';
            }
            elseif(!$coverphoto) {
                echo'<img src="https://photorankr.com/graphics/no_cover.png" alt="',$title,'" height="100" width="100" />';
            }
            echo'</div><div style="float:left;margin-top:15px;width:190px;"><a style="color:#3e608c;font-weight:bold;font-size:14px;" href="viewprofile.php?u=',$photographerid,'&view=exhibits&set=',$setid,'">',$title,'</a><br />
            <div style="padding:4px;"><img src="https://photorankr.com/',$profilepic,'" width="25" /> <a style="color:black;" href="viewprofile.php?u=',$photographerid,'">',$photographer,'</a></div>
            # Photos: ',$numphotos,'<br />Views: ',$views,'</div><div style="float:left;margin-top:15px;margin-left:30px;">';
            if($coverphoto2) {
                echo'<img style="padding:3px;" src="https://photorankr.com/',$coverphoto2,'" height="100" width="100" />';
                }
            if($coverphoto3) {
                echo'<img style="padding:3px;" src="https://photorankr.com/',$coverphoto3,'" height="100" width="100" />';
                }
            if($coverphoto4) {
                echo'<img style="padding:3px;" src="https://photorankr.com/',$coverphoto4,'" height="100" width="100" />';
                }
            if($coverphoto5) {
                echo'<img style="padding:3px;" src="https://photorankr.com/',$coverphoto5,'" height="100" width="100" />';
                }
               
                echo'</div>
                     </div>';

    }
}
        
    }//end of search view
        
    ?>
    </div>
    
</div><!--end of container-->
</body>
</html>
    