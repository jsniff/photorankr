<?php

require "db_connection.php";

//Get Variables
$collid = htmlentities($_GET['collid']);
$userid = htmlentities($_GET['u']);
$grabuseremail = mysql_query("SELECT emailaddress FROM userinfo WHERE user_id = '$userid'");
$useremail = mysql_result($grabuseremail,0,'emailaddress');

//Get All Collections
$allcolls = mysql_query("SELECT id,title FROM collections WHERE owner = '$useremail' ORDER BY id DESC");
$numcolls = mysql_num_rows($allcolls);

//If no collection selected
if($collid == '') {
    $collid = mysql_result($allcolls,0,'id');
}

echo'
<div id="response">
<div id="leftWrapper">

				<div id="columnLeft">

					<!--BEGIN COLLECTIONS LIST-->

					<ul id="collections_list"> 

						
						<!--COLLECTIONS ITEM-->';
                        
                    //Grab Collection Information
                    for($iii=0; $iii<$numcolls; $iii++) {

                        $collname = mysql_result($allcolls,$iii,'title');
                        $newcollid = mysql_result($allcolls,$iii,'id');
                        $getcollphotos = mysql_query("SELECT imageid FROM collectionphotos WHERE collection = '$newcollid' AND owner = '$useremail' ORDER BY id DESC");
                        $numcollphotos = mysql_num_rows($getcollphotos);
                        $photo1 = mysql_result($getcollphotos,0,'imageid');
                        $getsource = mysql_query("SELECT source FROM photos WHERE id = '$photo1' LIMIT 1");
                        $photo1source = mysql_result($getsource,0,'source');
                        $photo1source = str_replace("userphotos/","userphotos/medthumbs/",$photo1source);
                        $photo2 = mysql_result($getcollphotos,1,'imageid');
                        $getsource2 = mysql_query("SELECT source FROM photos WHERE id = '$photo2' LIMIT 1");
                        $photo2source = mysql_result($getsource2,0,'source');
                        $photo2source = str_replace("userphotos/","userphotos/medthumbs/",$photo2source);
                        $photo3 = mysql_result($getcollphotos,2,'imageid');
                        $getsource3 = mysql_query("SELECT source FROM photos WHERE id = '$photo3' LIMIT 1");
                        $photo3source = mysql_result($getsource3,0,'source');
                        $photo3source = str_replace("userphotos/","userphotos/medthumbs/",$photo3source);

                        
                        echo'
						<a onclick="ajaxColl(',$newcollid,')" class="collections_list_selected" id="chooseCollection">
							<li>   
								<span> ',$collname,' Collection <span> ',$numcollphotos,' photos </span></span>
								<ul class="aCollection" >';
                                    //Make sure there are 3 photos to show
                                    if($photo1source && $photo2source && $photo3source) {
                                        echo'
                                        <li> <img src="',$photo1source,'"/> </li>
                                        <li> <img src="',$photo2source,'"/> </li>
                                        <li> <img src="',$photo3source,'"/> </li>';
                                    }
                                echo'
								</ul>

							</li>
						</a>';
                        
                    } //end collections preview
                    
                    echo'
					</ul>

				</div>

			</div>


		
				<!--RIGHT COLUMN HERE-->

				<div id="rightColumn">

					<div class="collectionWrapper" >
					<!--BIG COLLECTION-->

					<div class="collectionBlock" id="collectionBlock" >';
                    
                    //Get Selected Collection Information
                    $getcollinfo = mysql_query("SELECT title,about,views FROM collections WHERE owner = '$useremail' AND id = '$collid'");
                    $title = mysql_result($getcollinfo,0,'title');
                    $about = mysql_result($getcollinfo,0,'about');
                    $views = mysql_result($getcollinfo,0,'views');
                    $getnumphotos = mysql_query("SELECT imageid FROM collectionphotos WHERE collection = '$collid' ORDER BY id DESC");
                    $numcollphotos = mysql_num_rows($getnumphotos);
                    
                    echo'
						<header id="collectionTitle"> 
							<h1> ',$title,' Collection </h1>
						</header>

<!-- 						<a href=""><h5> About Collection <img src="graphics/downBtn.png"> </h5></a>
 -->
						<ul class="collectionHeader">

							
							<li>

							<h3> Description </h3>

							<p class="descriptionText"> ',$about,' </p>

							</li>


							<li>

							<h3> Photos </h3>

							<p class="collectionStats"> ',$numcollphotos,' </p>

							</li>


							<li>

							<h3> Views </h3>

							<p class="collectionStats"> ',$collviews,' </p>

							</li>

						</ul>


						<div class="imgContainerBigCollection">

							<ul class="imgCollectionList">';
                            
                            for($i=0; $i<$numcollphotos; $i++) {
                                $imageid = mysql_result($getnumphotos,$i,'imageid');
                                $getphotosource = mysql_query("SELECT source FROM photos WHERE id = '$imageid'");
                                $source = mysql_result($getphotosource,0,'source');
                                $source = str_replace('userphotos/','userphotos/medthumbs/',$source);
                                    
                                echo'
								<li>    
									<div class="imgContainer"> 
												Overlay goes here 
											<div class="overlay"> 
												<h3> Mah Title Yo </h3>
												<span> Battle Record: 68</span>
												<ul style="margin-top:0px;padding:0;">
													<li style="float:left;"> <img src="graphics/fave_w.png" width="15px"/></li>
													<li style="float:left;"> <img src="graphics/fave_w.png" width="15px"/></li>
													<li style="float:left;"> <img src="graphics/fave_w.png" width="15px"/></li>
													<li style="float:left;"> <img src="graphics/fave_w.png" width="15px"/></li>
												</ul>
											</div> 
									 IMAGE GOES HERE
										<img src="',$source,'">
									</div>
								</li>
								
							</ul>';
                            
                            } //end photos for loop

                            echo'

						</div>

					</div>

					</div>
					
				</div>


		</div>
        </div>';
                
?>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.wookmark.js"></script>   
 
<script type="text/javascript">
//Load in collections views on right side
	function ajaxColl(collectionid) {
    var coll = collectionid;

	// load home page on click
    	$("#response").load("loadCollection.php?collid="+coll+"&u=<?php echo $userid; ?>");
    }
</script>

<script type="text/javascript">
(function(){
	var portfolio = $('#subNavList1'),
		exhibit = $('#collectionTitle'),
		PList = $("#PList"),
		EList = $("#EList"),
		count = 1,
		count1 = 0;

	PList.on('click', function () {
		if (count === 1){
			portfolio.animate({'width' : 0});
			count -= 1;
			document.getElementById('A1').src="graphics/arrowRight_w.png" ;
		 } else {
		 	portfolio.animate({'width' : 600});
		 	exhibit.animate({'width' : 0});
		 	count += 1;
		 	document.getElementById('A1').src="graphics/arrowLeft_w.png" ;
		 	if (count1 === 1){
		 		count1 -= 1;
		 		document.getElementById('A2').src="graphics/arrowRight_w.png" ;
		 	}
		 }
			
		
		
	});

	EList.on('click', function () {
		if (count1 === 1){
			exhibit.animate({'width' : 0});
			count1 -= 1;
			document.getElementById('A2').src="graphics/arrowRight_w.png" ;

		 } else {
		 	exhibit.animate({'width' : 600});
		 	portfolio.animate({'width' : 0});
		 	count1 += 1;
		 	document.getElementById('A2').src="graphics/arrowLeft_w.png" ;
		 	if (count === 1){count -= 1;document.getElementById('A1').src="graphics/arrowRight_w.png" ;}
		}
	});


})();

</script>

<script type="text/javascript">
(function(){
	var count = 0;

 $('#menuBtn').on('click', function() {

 	if(count === 0 ){ 
 	$('#left_bar').animate({ 'width' : 0});
 	count += 1;
 	$('#main_container').animate({ 'width' : 1280});
 	$('.center').animate({'padding-left' : 19});
} else {$('#left_bar').animate({ 'width' : 65});
 	count -= 1;
 	$('#main_container').animate({ 'width' : 1162 });
 	$('.center').animate({'padding-left' : 45});
 }

 });

})();
</script>
