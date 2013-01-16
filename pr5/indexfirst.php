<html>
<link href = "css/main2 2.css" rel="stylesheet" type="text/css"/>
<style type="text/css">
.scroll
    {
        position:relative !important;
        margin:-45px 0 0 0 !important;
    }
</style>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>

<hgroup>
			<header> 
				<img src="graphics/logo_big.png">		
			</header>
			<h1> Photo sharing meets the market </h1>
		</hgroup>
        
     <?php
        
            if($_GET['action'] == 'notactivated') {
                    
                        echo'<div class="grid_10 pull_4" id="bar_container" style="color:green;font-size:16px;font-weight:bold;text-align:center;margin-bottom:8px;">Please check your email to finish registration, thank you.</div>';
                        
            }
            
            elseif($_GET['action'] == 'activated') {
                    
                        echo'<div class="grid_10 pull_4" id="bar_container" style="color:green;font-size:18px;font-weight:bold;text-align:center;margin-bottom:8px;">You may now login to your account</div>';
                        
            }
        ?>
    
    
		<ul id="valProp">
			<li id="homeSocialA"> Join a network of people who share your passion. <span> </span>
				<ul id="homeSocial">
					<li> <img style="width:15px;margin-top:-5px;" src="graphics/user.png" /><b> Personalized Profile </b> <br />  Profile lets you display your portfolio, manage market sales, and view reputation and statistics. </li>
					<li> <img style="width:15px;margin-top:-5px;" src="graphics/list 1.png" /> <b> News Feed </b> <br /> Follow photographers to view their uploads, activity, and photo sales.  </li>
					<li> <img style="width:15px;margin-top:-5px;" src="graphics/groups_b.png" /> <b> Groups </b><br /> Network with others about photos, techniques, and equipment.</li>
				</ul>


			</li>

			<li id="homePersonalA"> Easily sell your best work. <span> </span>
				<ul id="homePersonal">
					<li> <img style="width:15px;margin-top:-5px;" src="graphics/cloud.png" /> <b>Open Platform</b><br /> If your images adhere to our <a href=""/> guidelines </a> they're automatically on the market. </li>
					<li> <img style="width:15px;margin-top:-5px;" src="graphics/tag.png" /> <b> Pricing </b> <br />Name the base price for your photos. </li>
					<li> <img style="width:15px;margin-top:-5px;" src="graphics/file down.png" /> <b> Licensing </b> <br /> Control which of your photos are available for commercial or editorial licensing or not for sale. </li>
				</ul>

			</li>
			<li id="homeMarketA"> Purchase high-quality photos on our marketplace. <span> </span>
				<ul id="homeMarket">
					<li> <img style="width:15px;margin-top:-5px;" src="graphics/tick 2.png" /> <b>Simple licensing and pricing options </b><br /> Purchase commercial and editorial photos priced according to resolution level.</li>
					<li> <img style="width:15px;margin-top:-5px;" src="graphics/search.png" /> <b>Powerful Search</b> <br /> Quickly find the image you need with our simple and powerful marketplace search features. </li>
					<li> <img style="width:15px;margin-top:-5px;" src="graphics/star.png" /> <b>Social Marketplace </b><br /> Find fresh and creative content by searching social metrics such as top-ranked photos.</li>
					
				</ul>


			</li>
		</ul>

<a id="signUpBtnA" href="register.php"><button id="signUpBtn"> Sign Up </button></a>
		<div id="miniFooter">
			<ul>
				<li> About </li>
				<li> Contact </li>
				<li> Privacy Policy </li>
				<li> Terms </li>
				<li style="width:30px;z-index: 1000;padding-left:5px;margin: -4px 0 0 0;"> <img style="height:25px;border-radius: 3px 0 0 3px;" src="graphics/facebook_s.png"/></li>
				<li style="width:35px;padding-left:0;padding-right:5px;margin: -4px 0 0 0px;"> <img  style="height:25px;border-radius:0 3px 3px 0;" src="graphics/twitter_s.png"/></li>
			</ul>
		</div>
	</div>

<script type="text/javascript">  
    //load in onboarding form
        $(document).ready(function(){
            // load index page when the page loads
            $("#signUpBtnA").click(function(){
            //load form page on click
                $("#formContainer").load("indexform.php");
            });
        });

(function(){
        	var count = 1
        	$("#homeSocialA").on('click',function(){
        		if(count === 1){
        		$("#homeSocial").animate({
        			'height' : 205
        		});
        		$('#formContainer').addClass('scroll');
        		count -= 1;}
        		else {
        			$("#homeSocial").animate({
        			'height' : 0

        		});
        			count += 1;
        		}
        	});

        })();
        (function(){
        	var count = 1
        	$("#homePersonalA").on('click',function(){
        		if(count === 1){
        		$("#homePersonal").animate({
        			'height' : 200
        		});
        		$('#formContainer').addClass('scroll');
        		count -= 1;}
        		else {
        			$("#homePersonal").animate({
        			'height' : 0

        		});
        			count += 1;
        		}
        	});

        })(); 
        (function(){
        	var count = 1
        	$("#homeMarketA").on('click',function(){
        		if(count === 1){
        		$("#homeMarket").animate({
        			'height' : 225
        		});
        		$('#formContainer').addClass('scroll');
        		count -= 1;}
        		else {
        			$("#homeMarket").animate({
        			'height' : 0

        		});
        			count += 1;
        		}
        	});

        })() 
   </script> 

</script>

</html>