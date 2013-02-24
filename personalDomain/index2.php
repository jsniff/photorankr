<?php

    //connect to the database
    require "db_connection.php";
    require "functions.php";

    //start the session
    session_start();

    // if login form has been submitted
    if (htmlentities($_GET['action']) == "login") { 
        login();
    }
    else if(htmlentities($_GET['action']) == "logout") { 
        logout();
    }
    
    //Session Variables
    $email = $_SESSION['email'];
    
    //Get the view
    $view = htmlentities($_GET['view']);
    
    //Get the price
    $price = htmlentities($_GET['price']);
    
    //Get imageid
    $imageid = htmlentities($_GET['imageid']);
    
    //Get the url
    $uri = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    
    //Grab the subdomain
    $nameChunks = explode('.', $_SERVER['HTTP_HOST']);
    $subDomainName = $nameChunks[count($nameChunks) - 3];
 
    //Check the subdomain
    $checkDomain = mysql_query("SELECT * FROM personaldomain WHERE domain = '$subDomainName' LIMIT 0,1");
    $match = mysql_result($checkDomain,0,'domain');
    $emailaddress = mysql_result($checkDomain,0,'emailaddress');
    
    //Grab user information
    $userinfo = mysql_query("SELECT firstname,lastname,profilepic,bio FROM userinfo WHERE emailaddress = '$emailaddress'");
    $firstname = mysql_result($userinfo,0,'firstname');
    $lastname = mysql_result($userinfo,0,'lastname');
    $fullname = $firstname ." ". $lastname;
    $profilepic = mysql_result($userinfo,0,'profilepic');
    $about = mysql_result($userinfo,0,'bio');
    
    echo'<script>
            function submitForm(sel) {
                sel.form.submit();
            }
        </script>';
    
    //User photos
    if($view == '' && $price == '') {
        $userphotos = mysql_query("SELECT id,source,caption,price FROM photos WHERE emailaddress = '$emailaddress' ORDER BY id DESC LIMIT 16");
    }
    elseif($view == 'pop') {
         $userphotos = mysql_query("SELECT id,source,caption,price,views,points,votes FROM photos WHERE emailaddress = '$emailaddress' ORDER BY views DESC LIMIT 16");
    }
    elseif($view == 'top') {
         $userphotos = mysql_query("SELECT id,source,caption,price,views,points,votes FROM photos WHERE emailaddress = '$emailaddress' ORDER BY (points/votes) DESC LIMIT 16");
    }
    if($price == 'hl') {
        $userphotos = mysql_query("SELECT id,source,caption,price,views,points,votes FROM photos WHERE emailaddress = '$emailaddress' ORDER BY price DESC LIMIT 16");
        }
    elseif($price == 'lh') {
        $userphotos = mysql_query("SELECT id,source,caption,price,views,points,votes FROM photos WHERE emailaddress = '$emailaddress' ORDER BY price ASC LIMIT 16");
    }
    $numuserphotos = mysql_num_rows($userphotos);
    
    /*if($match) {
        echo'You have a domain name as a Pro Member';
    }
    elseif(!$match) {
        echo'You do not have a domain name as a free Member';
    }*/
 
 ?>
 
 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="https://www.w3.org/1999/xhtml">

<head>

    <meta name="Generator" content="EditPlus">
    <meta property="og:image" content="http://photorankr.com/<?php echo $profilepic; ?>">
    <meta name="Author" content="PhotoRankr, PhotoRankr.com">
    <meta name="Keywords" content="photos, sharing photos, photo sharing, photography, photography club, sell photos, sell photography, where to sell my photography, good sites for selling photography, making money from photography, making money off photography, stock photos, photography school, digital cameras, learn photography, social networking, social network, social networks, where to sell my photos, good sites for selling photos, good site to sell photos, making money from photos">
    <meta name="Description" content="<?php echo $fullname; ?> Photography">
    <meta name="viewport" content="width=1200" /> 
     
    <title><?php echo $fullname; ?> Photography</title>

    <link rel="stylesheet" type="text/css" href="css/main.css"/>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>
	<link rel="stylesheet" type="text/css" href="css/960grid.css"/>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>
    
</head>

<body class="darkBody">

<!-----Top Bar---->

<div class="topBar">
    <header><?php echo $fullname; ?> Photography</header>
    <ul>
        <a href="/"><li <?php if($view == '') {echo'style="color: #77bb55;"';} ?>> Portfolio </li></a>
        <li id="portfolioDrop"> Exhibits <img style="width:13px;" src="graphics/arrowDown.png"/></li>
        <li id="aboutDrop"> About <img style="width:13px;" src="graphics/arrowDown.png"/></li>
        <li id="contactDrop"> Contact <img style="width:13px;" src="graphics/arrowDown.png"/></li>
        <a href="cart.php"><li> Cart </li></a>
    </ul>
</div>

<!--Portfolio Drop-->
<div id="portfolioDropBox">

    <div style="float:left;font-size:16px;padding:10px 20px;">
    
        <?php
        
        $grabexhibits = mysql_query("SELECT * FROM sets WHERE owner = '$emailaddress'");
        $numexhibits = mysql_num_rows($grabexhibits);        
        
        for($iii=0; $iii<$numexhibits; $iii++) {
            $name = mysql_result($grabexhibits,$iii,'title');
            $set_id = mysql_result($grabexhibits,$iii,'id');
            $grabcoverphoto = mysql_query("SELECT source,id FROM photos WHERE set_id = '$set_id' ORDER BY points DESC");
            $numphotos = mysql_num_rows($grabcoverphoto);
            $setcover = mysql_result($grabcoverphoto,0,'source');
            $setcover = str_replace('userphotos','userphotos/medthumbs',$setcover);
            $setcoverid = mysql_result($grabcoverphoto,0,'id');
            
            echo'<div style="float:left;margin:10px;">
            <a style="text-decoration:none;color:#333;" href="?view=big&exhibit=',$set_id,'&imageid=',$setcoverid,'">
                <img style="width:50px;height:50px;" src="https://photorankr.com/',$setcover,'" /> <span style="font-size:16px;font-weight:500;">',$name,' <span style="font-size:14px;font-weight:300;">&nbsp;&nbsp; ',$numphotos,' photos &nbsp;&nbsp;&nbsp;&nbsp;</span>
            </a>
            </div>';
        
        }
        
        ?>
        
    </div>
</div>

<!--About Drop-->
<div id="aboutDropBox">
    <div id="profilepic">
        <img src="https://photorankr.com/<?php echo $profilepic; ?>" />
        <br /><br />
    </div>
    <div style="padding-top:25px;padding-bottom:10px;margin-left:20px;width:900px;float:left;border-bottom:1px solid #aaa;font-size:24px;color:#555;">About my Photography</div>
    <div id="aboutBoxText"><?php echo $about; ?></div>
</div>

<!--Contact Drop-->
<div id="contactDropBox">
contact me here!!
</div>

<!-----Begin Container---->

<div class="container_24" style="text-align:center;">

<!--<div id="choiceBar">
    <span><i class="icon-th-large icon-white"></i>
 Grid View </span>
    <span> &nbsp;&nbsp;&nbsp;&nbsp;<i class="icon-picture icon-white"></i> <a style="color:white;" href="?view=big">Big View</a> </span>
</div>-->

<?php 

    if($view == '' || $view == 'pop' || $view == 'top') {

        echo'<div id="thepics" class="grid_24 pull_2 leftPortfolioBox" style="z-index:10;">
            <ul id="portfolioList">
            <a href="/"><li> Newest </li></a>
            <a href="/?view=pop"><li> Most Popular </li></a>
            <a href="/?view=top"><li> Top Ranked </li></a>
            <li>
                <form method="get">
                <select onchange="submitForm(this)" name="price" style="width:140px;float:right;margin-top:-5px;">
                    <option value=""> Price </option>
                    <option value="hl"'; if($price == 'hl') {echo'selected value=""';} echo'> High to Low </option>
                    <option value="lh"'; if($price == 'lh') {echo'selected value=""';} echo'> Low to High </option>
                </select>
                </form>
            </li>
            </ul>
             <div id="main">';
        
        for($iii=0; $iii<$numuserphotos; $iii++) {
            $source = mysql_result($userphotos,$iii,'source');
            $sourceThumb = str_replace('userphotos','userphotos/medthumbs',$source);
            $caption = mysql_result($userphotos,$iii,'caption');
            $caption = (strlen($caption) > 28) ? substr($caption,0,25). " &#8230;" : $caption;
            $id = mysql_result($userphotos,$iii,'id');
            $views = mysql_result($userphotos,$iii,'views');
            $points = mysql_result($userphotos,$iii,'points');
            $votes = mysql_result($userphotos,$iii,'votes');
            $firstprice = mysql_result($userphotos,$iii,'price');
            if ($firstprice < 0) {$realprice='NFS';}
            else {
                $realprice = "$".$firstprice;
            }   
            list($width, $height) = getimagesize('http://photorankr.com/'.$source);
            $imgratio = $height / $width;
            $heightls = $height / 4;
            $widthls = $width / 4;
                
            if($view == '' && $price == '') {
                echo'<div class="fPic" id="',$id,'" style="float:left;overflow:hidden;height:300px;">
                    <div class="portfolioImage">
                        <a href="?view=big&imageid=',$id,'">
                            <img style="min-height:',$heightls,'px;width:',$widthls,'px;" onmousedown="return false" oncontextmenu="return false;" src="https://photorankr.com/',$sourceThumb,'" />
                        </a>
                    </div>
                <div style="clear:both;margin:15px 0px;margin-left:20px;height:30px;width:252px;background-color:rgb(234,234,234);">
                    <div style="font-size:16px;color:#666;text-align:left;float:left;padding:5px;">',$realprice,'&nbsp;&nbsp;&nbsp;<span style="font-weight:300;font-size:15px;">',$caption,'</span></div>
                </div>
                </div>';
            }
            elseif($view == 'pop') {
                echo'<div class="fPic" id="',$views,'" style="float:left;overflow:hidden;height:300px;">
                    <div class="portfolioImage">
                        <a href="?view=big&imageid=',$id,'">
                            <img style="min-height:',$heightls,'px;width:',$widthls,'px;" onmousedown="return false" oncontextmenu="return false;" src="https://photorankr.com/',$sourceThumb,'" />
                        </a>
                    </div>
                <div style="clear:both;margin:15px 0px;margin-left:20px;height:30px;width:252px;background-color:rgb(234,234,234);">
                    <div style="font-size:16px;color:#666;text-align:left;float:left;padding:5px;">',$realprice,'&nbsp;&nbsp;&nbsp;<span style="font-weight:300;font-size:15px;">',$caption,'</span></div>
                </div>
                </div>';
            }
            if($view == 'top') {
                echo'<div class="fPic" id="',($points/$votes),'" style="float:left;overflow:hidden;height:300px;">
                    <div class="portfolioImage">
                        <a href="?view=big&imageid=',$id,'">
                            <img style="min-height:',$heightls,'px;width:',$widthls,'px;" onmousedown="return false" oncontextmenu="return false;" src="https://photorankr.com/',$sourceThumb,'" />
                        </a>
                    </div>
                <div style="clear:both;margin:15px 0px;margin-left:20px;height:30px;width:252px;background-color:rgb(234,234,234);">
                    <div style="font-size:16px;color:#666;text-align:left;float:left;padding:5px;">',$realprice,'&nbsp;&nbsp;&nbsp;<span style="font-weight:300;font-size:15px;">',$caption,'</span></div>
                </div>
                </div>';

            }
            if($price == 'hl') {
                echo'<div class="fPic" id="',$realprice,'" style="float:left;overflow:hidden;height:300px;">
                    <div class="portfolioImage">
                        <a href="?view=big&imageid=',$id,'">
                            <img style="min-height:',$heightls,'px;width:',$widthls,'px;" onmousedown="return false" oncontextmenu="return false;" src="https://photorankr.com/',$sourceThumb,'" />
                        </a>
                    </div>
                <div style="clear:both;margin:15px 0px;margin-left:20px;height:30px;width:252px;background-color:rgb(234,234,234);">
                    <div style="font-size:16px;color:#666;text-align:left;float:left;padding:5px;">',$realprice,'&nbsp;&nbsp;&nbsp;<span style="font-weight:300;font-size:15px;">',$caption,'</span></div>
                </div>
                </div>';

            }
            elseif($price == 'lh') {
                echo'<div class="fPic" id="',$realprice,'" style="float:left;overflow:hidden;height:300px;">
                    <div class="portfolioImage">
                        <a href="?view=big&imageid=',$id,'">
                            <img style="min-height:',$heightls,'px;width:',$widthls,'px;" onmousedown="return false" oncontextmenu="return false;" src="https://photorankr.com/',$sourceThumb,'" />
                        </a>
                    </div>
                <div style="clear:both;margin:15px 0px;margin-left:20px;height:30px;width:252px;background-color:rgb(234,234,234);">
                    <div style="font-size:16px;color:#666;text-align:left;float:left;padding:5px;">',$realprice,'&nbsp;&nbsp;&nbsp;<span style="font-weight:300;font-size:15px;">',$caption,'</span></div>
                </div>
                </div>';
            }
        
        }
    
    echo'</div>
         </div>
    
    <!--AJAX CODE HERE-->
   <div class="grid_6 push_13" style="padding-top:25px;padding-bottom:25px;">
   <div id="loadMorePics" style="display: none; text-align: center;font-family:arial,helvetica neue; font-size:15px;"><img style="width:50px;" src="graphics/LoadingGIF.gif" /></div>
   </div>';


echo '<script>

var last = 0;

	$(window).scroll(function(){
		if($(window).scrollTop() > $(document).height() - $(window).height()-100) {
			if(last != $(".fPic:last").attr("id")) {
				$("div#loadMorePics").show();
				$.ajax({
					url: "loadMorePortfolio.php?lastPicture=" + $(".fPic:last").attr("id")+"&id=',$id,'"+"&email=',$emailaddress,'"+"&view=',$view,'"+"&price=',$price,'",
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

    } //end view == ''
    
    elseif($view == 'big') {
        
        if(!htmlentities($_GET['exhibit'])) {
        
            $getimage = mysql_query("SELECT id,source,caption,about,price FROM photos WHERE id = $imageid LIMIT 1");
            $source = mysql_result($getimage,0,'source');
            $caption = mysql_result($getimage,0,'caption');
            $about = mysql_result($getimage,0,'about');
            $price = mysql_result($getimage,0,'price');
            if($price < 0) {
                $price = "Not For Sale";
            }
            else {
                $price = "$" . $price;
            }
            
        echo'<div style="width:1200px;">
        
            <div class="bigBox">
        
                <div class="arrowleft">
                    <a id="ajaxBack" href="javascript:ajaxBack(',$imageid,')"><img style="width:10px;height:15px;" src="graphics/newarrowleft.png" /></a>
                </div>
                            
                <div id="nextimgid" style="width:800px;padding:0px;float:left;overflow:hidden;height:530px;vertical-align:middle;">
                    <a href="?view=big&imageid=1234">
                        <img id="nextimg" nonmousedown="return false" oncontextmenu="return false;" src="https://photorankr.com/',$source,'" />
                    </a>
                </div>
                
                <div class="arrowright">
                    <a id="ajaxNext" href="javascript:ajaxNext(',$imageid,')"><img style="width:10px;height:15px;" src="graphics/newarrowright.png" /></a>
                </div>
                
           </div>
           
           <div class="rightInfo">
                <header id="caption"> ',$caption,' </header>
                <p id="price"><img style="width:15px;" src="https://photorankr.com/graphics/tag.png" /> ',$price,' <a href="#"> Add to Cart </a> </p>
                <p id="about">',$about,' </p>
           </div>
           
        </div>';
        
        }
        
        //If exhibit chosen
        elseif(htmlentities($_GET['exhibit'])) {
        
            //Set Title Info
            $set_id = htmlentities($_GET['exhibit']);
            $settitlequery = mysql_query("SELECT title FROM sets WHERE id = '$set_id'");
            $settitle = mysql_result($settitlequery,0,'title');
            
            $exhibitreel = mysql_query("SELECT id,source FROM photos WHERE set_id = '$set_id' ORDER BY id ASC");
            $numexphotos = mysql_num_rows($exhibitreel);
            
            //Exhibit Info at Top                        
            echo'<div style="margin-top:80px;float:left;text-align:left;color:white;font-size:20px;font-weight:500;overflow:hidden;"><div style="float:left;padding:10px;">',$settitle,' <span style="font-weight:300;font-size:18px;">',$numexphotos,' photos</span></div>';
            //Exhibit Photo Reel
            for($ii=0;$ii<$numexphotos;$ii++) {
                $exthumb = mysql_result($exhibitreel,$ii,'source');
                $photoid =  mysql_result($exhibitreel,$ii,'id');
                $exthumb = str_replace('userphotos/','userphotos/thumbs/',$exthumb);
                
                //Highlight Current Photo in Reel
                if($photoid == $imageid) {
                    echo'<img id="',$photoid,'" class="exreel" style="border:1px solid white;width:45px;margin:3px;float:left;" src="https://photorankr.com/',$exthumb,'" />';
                }
                else {
                    echo'<img id="',$photoid,'" class="exreel" style="width:45px;margin:3px;float:left;" src="https://photorankr.com/',$exthumb,'" />';
                }
            }
            
            echo'</div>';
            
            
            //Exhibit Photos
            $getimage = mysql_query("SELECT id,source,caption,about,price FROM photos WHERE id = $imageid LIMIT 1");
            $source = mysql_result($getimage,0,'source');
            $caption = mysql_result($getimage,0,'caption');
            $about = mysql_result($getimage,0,'about');
            $price = mysql_result($getimage,0,'price');
            if($price < 0) {
                $price = "Not For Sale";
            }
            else {
                $price = "$" . $price;
            }
        
            //Current Photo
            echo'<div class="bigBox" style="position:relative;top:-25px;">
                
                <div class="grid_1 arrowleft">
                    <a id="ajaxBack" href="javascript:ajaxBackExhibit(',$imageid,',',$set_id,')"><img src="graphics/newarrowleft.png" /></a>
                </div>
                            
                <div id="nextimgid" class="grid_20" style="margin-top:35px;">
                    <a href="?view=big&exhibit=',$set_id,'&imageid=1234">
                        <img id="nextimg" nonmousedown="return false" oncontextmenu="return false;" src="https://photorankr.com/',$source,'" />
                    </a>
                </div>
                
                <div class="grid_1 arrowright">
                    <a id="ajaxNext" href="javascript:ajaxNextExhibit(',$imageid,',',$set_id,')"><img src="graphics/newarrowright.png" /></a>
                </div>
                
           </div>
           
           <div class="grid_20" style="text-align:left;padding:10px;">
                <div id="price">',$price,' </div>
                <div id="caption"> ',$caption,' </div>
                <div id="about">',$about,' </div>
           </div>';
        
        }
        
    }

?>


<!----End Container--->
</div>

<script type="text/javascript">
//Create Request Object
function createRequestObject() {

    var ajaxRequest;  //ajax variable
	
	try{
		// Opera 8.0+, Firefox, Safari
		ajaxRequest = new XMLHttpRequest();
	} catch (e){
		// Internet Explorer Browsers
		try{
			ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try{
				ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e){
				// Something went wrong
				alert("Your browser broke!");
				return false;
			}
		}
	}
    
    return ajaxRequest;
    
}

var email = '<?php echo $emailaddress; ?>';

//LightBox Next Image
function ajaxNext(imageid){
    
    ajaxRequest = createRequestObject();
    
    var image = imageid;
	
    // Create a function that will receive data sent from the server
	ajaxRequest.onreadystatechange = function(){
		if(ajaxRequest.readyState == 4){
            
           //image source
            var json = eval('(' + ajaxRequest.responseText +')');
            var nextimg = document.getElementById('nextimg');
            nextimg.src = 'http://photorankr.com/' + json.nextimg;

            //image id
            var nextimgid = document.getElementById('nextimgid');
            nextimgid.href = '?view=big&imageid=' + json.nextimgid;
            
            //left arrow href change
            var arrowLeft = document.getElementById('ajaxBack');
            arrowLeft.href = 'javascript:ajaxBack('+json.previmgid+')';
            
            //right arrow href change
            var arrowRight = document.getElementById('ajaxNext');
            arrowRight.href = 'javascript:ajaxNext('+json.nextimgid+')';
            
            //image caption
            var caption = document.getElementById('caption');
            caption.innerHTML = json.caption;
            
            //image price
            var price = document.getElementById('price');
            price.innerHTML = json.price;
            
            //image about
            var about = document.getElementById('about');
            about.innerHTML = json.about;
            
            //send to new url without reloading
            window.history.pushState('tets', '', '?view=big&imageid='+json.nextimgid);
            
            $(document).ready(function(){
                $("#nextimgid").fadeIn("slow");
            });


		}
                
	}


    var queryString = "?image=" + image + "&email=" + email;
	ajaxRequest.open("GET", "ajaxNext.php" + queryString, true);
	ajaxRequest.send(null);
    
}

//LightBox Next Image
function ajaxBack(imageid){
    
    ajaxRequest = createRequestObject();
    
    var image = imageid;
	
    // Create a function that will receive data sent from the server
	ajaxRequest.onreadystatechange = function(){
		if(ajaxRequest.readyState == 4){
            
           //image source
            var json = eval('(' + ajaxRequest.responseText +')');
            var nextimg = document.getElementById('nextimg');
            nextimg.src = 'http://photorankr.com/' + json.nextimg;

            //image id
            var nextimgid = document.getElementById('nextimgid');
            nextimgid.href = '?view=big&imageid=' + json.nextimgid;
            
            //left arrow href change
            var arrowLeft = document.getElementById('ajaxBack');
            arrowLeft.href = 'javascript:ajaxBack('+json.nextimgid+')';
            
            //right arrow href change
            var arrowRight = document.getElementById('ajaxNext');
            arrowRight.href = 'javascript:ajaxNext('+json.nextimgid+')';
            
            //image caption
            var caption = document.getElementById('caption');
            caption.innerHTML = json.caption;
            
            //image price
            var price = document.getElementById('price');
            price.innerHTML = json.price;
            
            //image about
            var about = document.getElementById('about');
            about.innerHTML = json.about;
            
            //send to new url without reloading
            window.history.pushState('tets', '', '?view=big&imageid='+json.nextimgid);
            
            $(document).ready(function(){
                $("#nextimgid").fadeIn("slow");
            });


		}
                
	}


    var queryString = "?image=" + image + "&email=" + email;
	ajaxRequest.open("GET", "ajaxBack.php" + queryString, true);
	ajaxRequest.send(null);
    
}

//LightBox Next Image
function ajaxNextExhibit(imageid,set_id){
    
    ajaxRequest = createRequestObject();
    
    var image = imageid;
	var set_id = set_id;
    	
    // Create a function that will receive data sent from the server
	ajaxRequest.onreadystatechange = function(){
		if(ajaxRequest.readyState == 4){
            
           //image source
            var json = eval('(' + ajaxRequest.responseText +')');
            var nextimg = document.getElementById('nextimg');
            nextimg.src = 'http://photorankr.com/' + json.nextimg;

            //image id
            var nextimgid = document.getElementById('nextimgid');
            nextimgid.href = '?view=big&exhibit=' + json.exhibitid + '&imageid=' + json.nextimgid;
            
            //left arrow href change
            var arrowLeft = document.getElementById('ajaxBack');
            arrowLeft.href = 'javascript:ajaxBackExhibit('+json.nextimgid+','+json.exhibitid+')';
            
            //right arrow href change
            var arrowRight = document.getElementById('ajaxNext');
            arrowRight.href = 'javascript:ajaxNextExhibit('+json.nextimgid+','+json.exhibitid+')';
                        
            //image caption
            var caption = document.getElementById('caption');
            caption.innerHTML = json.caption;
            
             //Move the highlighted photo down
            $(".exreel").css({'border' : '0px', 'margin' : '3px', 'float' : 'left'});
            document.getElementById(json.nextimgid).style.border = "1px solid white";
            
            //image price
            var price = document.getElementById('price');
            price.innerHTML = json.price;
            
            //image about
            var about = document.getElementById('about');
            about.innerHTML = json.about;
            
            //send to new url without reloading
            window.history.pushState('tets', '', '?view=big&exhibit='+json.exhibitid+'&imageid='+json.nextimgid);
            
            $(document).ready(function(){
                $("#nextimgid").fadeIn("slow");
            });


		}
                
	}


    var queryString = "?image=" + image + "&set_id=" + set_id + "&email=" + email;
	ajaxRequest.open("GET", "ajaxNextExhibit.php" + queryString, true);
	ajaxRequest.send(null);    
}

//LightBox Previous Exhibit Image
function ajaxBackExhibit(imageid,set_id){
    
    ajaxRequest = createRequestObject();
    
    var image = imageid;
	var set_id = set_id;
    
    // Create a function that will receive data sent from the server
	ajaxRequest.onreadystatechange = function(){
		if(ajaxRequest.readyState == 4){
            
           //image source
            var json = eval('(' + ajaxRequest.responseText +')');
            var nextimg = document.getElementById('nextimg');
            nextimg.src = 'http://photorankr.com/' + json.nextimg;

            //image id
            var nextimgid = document.getElementById('nextimgid');
            nextimgid.href = '?view=big&exhibit=' + json.exhibitid + '&imageid=' + json.nextimgid;
            
            //left arrow href change
            var arrowLeft = document.getElementById('ajaxBack');
            arrowLeft.href = 'javascript:ajaxBackExhibit('+json.nextimgid+','+json.exhibitid+')';
            
            //right arrow href change
            var arrowRight = document.getElementById('ajaxNext');
            arrowRight.href = 'javascript:ajaxNextExhibit('+json.nextimgid+','+json.exhibitid+')';
            
            //image caption
            var caption = document.getElementById('caption');
            caption.innerHTML = json.caption;
            
            //image price
            var price = document.getElementById('price');
            price.innerHTML = json.price;
            
            //Move the highlighted photo down
            $(".exreel").css({'border' : '0px', 'margin' : '3px', 'float' : 'left'});
            document.getElementById(json.nextimgid).style.border = "1px solid white";

            //image about
            var about = document.getElementById('about');
            about.innerHTML = json.about;
            
            //send to new url without reloading
            window.history.pushState('tets', '', '?view=big&exhibit='+json.exhibitid+'&imageid='+json.nextimgid);
            
            $(document).ready(function(){
                $("#nextimgid").fadeIn("slow");
            });


		}
                
	}


    var queryString = "?image=" + image + "&set_id=" + set_id + "&email=" + email;
	ajaxRequest.open("GET", "ajaxBackExhibit.php" + queryString, true);
	ajaxRequest.send(null);
    
}

//Drop Downs
jQuery(document).ready(function(){
     jQuery("#portfolioDrop").live("click", function(event) {
         jQuery("#portfolioDropBox").slideToggle();
         jQuery("#aboutDropBox").hide();
         jQuery("#contactDropBox").hide();
    });
    jQuery("#aboutDrop").live("click", function(event) {
         jQuery("#aboutDropBox").slideToggle();
         jQuery("#portfolioDropBox").hide();
         jQuery("#contactDropBox").hide();
    });
    jQuery("#contactDrop").live("click", function(event) {
         jQuery("#contactDropBox").slideToggle();
         jQuery("#portfolioDropBox").hide();
         jQuery("#aboutDropBox").hide();
    });
});

</script>
</body>
</html>