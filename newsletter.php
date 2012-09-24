<?php

     //Connect to database
     
    require "db_connection.php";
    require "functionsnav.php";

     //Get top 3 photos of the week
     
    $lowertimebound = time() - 604800;
    $query = mysql_query("SELECT * FROM photos WHERE time > '$lowertimebound' ORDER BY points DESC LIMIT 3");
    
     //Top photo number 1 information
    
    $photo1 = mysql_result($query,0,'source');
    $photo1id = mysql_result($query,0,'id');
    $photo1caption = mysql_result($query,0,'caption');
    $photo1about = mysql_result($query,0,'about');
    $photo1price = mysql_result($query,0,'price');
    $photo1score = number_format((mysql_result($query,0,'points')/mysql_result($query,0,'votes')),2);
    $owner1 = mysql_result($query,0,'emailaddress');
    $ownerquery1 = mysql_query("SELECT user_id,firstname,lastname,profilepic FROM userinfo WHERE emailaddress = '$owner1' LIMIT 1");
    $owner1id = mysql_result($ownerquery1,0,'user_id');
    $owner1full = mysql_result($ownerquery1,0,'firstname') ." ". mysql_result($ownerquery1,0,'lastname');
    $owner1pic = mysql_result($ownerquery1,0,'profilepic');

     //Top photo number 2 information
     
    $photo2 = mysql_result($query,1,'source');
    $photo2id = mysql_result($query,1,'id');
    $photo2caption = mysql_result($query,1,'caption');
    $photo2price = mysql_result($query,1,'price');
    $photo2about = mysql_result($query,1,'about');
    $photo2score = number_format((mysql_result($query,1,'points')/mysql_result($query,1,'votes')),2);
    $owner2 = mysql_result($query,1,'emailaddress');
    $ownerquery2 = mysql_query("SELECT user_id,firstname,lastname,profilepic FROM userinfo WHERE emailaddress = '$owner2' LIMIT 1");
    $owner2id = mysql_result($ownerquery2,0,'user_id');
    $owner2full = mysql_result($ownerquery2,0,'firstname') ." ". mysql_result($ownerquery2,0,'lastname');
    $owner2pic = mysql_result($ownerquery2,0,'profilepic');

     //Top photo number 3 information
     
    $photo3 = mysql_result($query,2,'source');
    $photo3id = mysql_result($query,2,'id');
    $photo3caption = mysql_result($query,2,'caption');
    $photo3price = mysql_result($query,2,'price');
    $photo3about = mysql_result($query,2,'about');
    $photo3score = number_format((mysql_result($query,2,'points')/mysql_result($query,2,'votes')),2);
    $owner3 = mysql_result($query,2,'emailaddress');
    $ownerquery3 = mysql_query("SELECT user_id,firstname,lastname,profilepic FROM userinfo WHERE emailaddress = '$owner3' LIMIT 1");
    $owner3id = mysql_result($ownerquery3,0,'user_id');
    $owner3full = mysql_result($ownerquery3,0,'firstname') ." ". mysql_result($ownerquery3,0,'lastname');
    $owner3pic = mysql_result($ownerquery3,0,'profilepic');
    
    
    //Get top 3 most popular of the week
     
    $lowertimebound = time() - 604800;
    $popquery = mysql_query("SELECT * FROM photos WHERE time > '$lowertimebound' ORDER BY views DESC LIMIT 3");
    
     //Top photo number 1 information
    
    $popphoto1 = mysql_result($popquery,0,'source');
    $popphoto1id = mysql_result($popquery,0,'id');
    $popphoto1caption = mysql_result($popquery,0,'caption');
    $popphoto1about = mysql_result($popquery,0,'about');
    $popphoto1price = mysql_result($popquery,0,'price');
    $popphoto1score = number_format((mysql_result($popquery,0,'points')/mysql_result($popquery,0,'votes')),2);
    $popowner1 = mysql_result($popquery,0,'emailaddress');
    $popownerquery1 = mysql_query("SELECT user_id,firstname,lastname,profilepic FROM userinfo WHERE emailaddress = '$popowner1' LIMIT 1");
    $popowner1id = mysql_result($popownerquery1,0,'user_id');
    $popowner1full = mysql_result($popownerquery1,0,'firstname') ." ". mysql_result($popownerquery1,0,'lastname');
    $popowner1pic = mysql_result($popownerquery1,0,'profilepic');

     //Top photo number 2 information
     
    $popphoto2 = mysql_result($popquery,1,'source');
    $popphoto2id = mysql_result($popquery,1,'id');
    $popphoto2caption = mysql_result($popquery,1,'caption');
    $popphoto2price = mysql_result($popquery,1,'price');
    $popphoto2about = mysql_result($popquery,1,'about');
    $popphoto2score = number_format((mysql_result($popquery,1,'points')/mysql_result($popquery,1,'votes')),2);
    $popowner2 = mysql_result($popquery,1,'emailaddress');
    $popownerquery2 = mysql_query("SELECT user_id,firstname,lastname,profilepic FROM userinfo WHERE emailaddress = '$popowner2' LIMIT 1");
    $popowner2id = mysql_result($popownerquery2,0,'user_id');
    $popowner2full = mysql_result($popownerquery2,0,'firstname') ." ". mysql_result($popownerquery2,0,'lastname');
    $popowner2pic = mysql_result($popownerquery2,0,'profilepic');

     //Top photo number 3 information
     
    $popphoto3 = mysql_result($popquery,2,'source');
    $popphoto3id = mysql_result($popquery,2,'id');
    $popphoto3caption = mysql_result($popquery,2,'caption');
    $popphoto3price = mysql_result($popquery,2,'price');
    $popphoto3about = mysql_result($popquery,2,'about');
    $popphoto3score = number_format((mysql_result($popquery,2,'points')/mysql_result($popquery,2,'votes')),2);
    $popowner3 = mysql_result($popquery,2,'emailaddress');
    $popownerquery3 = mysql_query("SELECT user_id,firstname,lastname,profilepic FROM userinfo WHERE emailaddress = '$popowner3' LIMIT 1");
    $popowner3id = mysql_result($popownerquery3,0,'user_id');
    $popowner3full = mysql_result($popownerquery3,0,'firstname') ." ". mysql_result($popownerquery3,0,'lastname');
    $popowner3pic = mysql_result($popownerquery3,0,'profilepic');
    
     //Top new photographers of the week
     
    $numphotogsquery = mysql_query("SELECT emailaddress FROM userinfo WHERE unsubscriber NOT IN (1) ORDER BY user_id DESC");
    $numphotogs = mysql_num_rows($numphotogsquery);
     
    $topphotogs = mysql_query("SELECT user_id,firstname,lastname,profilepic FROM userinfo WHERE user_id > ($numphotogs - 50) ORDER BY reputation DESC LIMIT 3");
    $topphotog1id = mysql_result($topphotogs,0,'user_id');
    $topphotog1full = mysql_result($topphotogs,0,'firstname') ." ". mysql_result($topphotogs,0,'lastname');
    $topphotog1pic = mysql_result($topphotogs,0,'profilepic');
    
    $topphotog2id = mysql_result($topphotogs,1,'user_id');
    $topphotog2full = mysql_result($topphotogs,1,'firstname') ." ". mysql_result($topphotogs,1,'lastname');
    $topphotog2pic = mysql_result($topphotogs,1,'profilepic');
    
    $topphotog3id = mysql_result($topphotogs,2,'user_id');
    $topphotog3full = mysql_result($topphotogs,2,'firstname') ." ". mysql_result($topphotogs,2,'lastname');
    $topphotog3pic = mysql_result($topphotogs,2,'profilepic');
    
    
    //Send button
    
     if(htmlentities($_GET['send']) != 'done') {
        echo'
        <link href="css/bootstrap.css" rel="stylesheet" type="text/css"/>
        <a class="btn btn-danger" style="margin-top:320px;margin-left:500px;" href="newsletter.php?send=true" />Send Newsletter to ',$numphotogs,' recipients?</a>';
    }
    
    elseif(htmlentities($_GET['send']) == 'done') {
        echo'
        <link href="css/bootstrap.css" rel="stylesheet" type="text/css"/>
        <button class="btn btn-success" style="margin-top:20px;margin-left:20px;">Newsletter Sent :)</button>';
    }
?>


<?php

 //Send mail through Mandrill API
 
 if(htmlentities($_GET['send']) == 'true') {
 
 
 //Email Loop
 for($iii = 0; $iii < $numphotogs; $iii++) {
 
    $toaddress = mysql_result($numphotogsquery,$iii,'emailaddress');
    
 

$args = array(
    'key' => '454585d2-8e9c-460c-af4e-1c548d50b84f',  
    'message' => array(
        "html" => "
        
        <!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html>
    <head>
        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />
        
        <!-- Facebook sharing information tags -->
        <meta property=\"og:title\" content=\"PhotoRankr Newsletter\" />
        
        <title>PhotoRankr Newsletter</title>
		<style type=\"text/css\">
			/* Client-specific Styles */
			#outlook a{padding:0;} /* Force Outlook to provide a view in browser button. */
			body{width:100% !important;} .ReadMsgBody{width:100%;} .ExternalClass{width:100%;} /* Force Hotmail to display emails at full width */
			body{-webkit-text-size-adjust:none;} /* Prevent Webkit platforms from changing default text sizes. */

			/* Reset Styles */
			body{margin:0; padding:0;}
			img{border:0; height:auto; line-height:100%; outline:none; text-decoration:none;}
			table td{border-collapse:collapse;}
			#backgroundTable{height:100% !important; margin:0; padding:0; width:100% !important;}

			/* Template Styles */

			/* /\/\/\/\/\/\/\/\/\/\ STANDARD STYLING: COMMON PAGE ELEMENTS /\/\/\/\/\/\/\/\/\/\ */

			/**
			* @tab Page
			* @section background color
			* @tip Set the background color for your email. You may want to choose one that matches your company's branding.
			* @theme page
			*/
			body, #backgroundTable{
				/*@editable*/ background-color:#FAFAFA;
			}

			/**
			* @tab Page
			* @section email border
			* @tip Set the border for your email.
			*/
			#templateContainer{
				/*@editable*/ border: 1px solid #DDDDDD;
			}

			/**
			* @tab Page
			* @section heading 1
			* @tip Set the styling for all first-level headings in your emails. These should be the largest of your headings.
			* @style heading 1
			*/
			h1, .h1{
				/*@editable*/ color:#202020;
				display:block;
				/*@editable*/ font-family:Arial;
				/*@editable*/ font-size:34px;
				/*@editable*/ font-weight:bold;
				/*@editable*/ line-height:100%;
				margin-top:0;
				margin-right:0;
				margin-bottom:10px;
				margin-left:0;
				/*@editable*/ text-align:left;
			}

			/**
			* @tab Page
			* @section heading 2
			* @tip Set the styling for all second-level headings in your emails.
			* @style heading 2
			*/
			h2, .h2{
				/*@editable*/ color:#202020;
				display:block;
				/*@editable*/ font-family:Arial;
				/*@editable*/ font-size:30px;
				/*@editable*/ font-weight:bold;
				/*@editable*/ line-height:100%;
				margin-top:0;
				margin-right:0;
				margin-bottom:10px;
				margin-left:0;
				/*@editable*/ text-align:left;
			}

			/**
			* @tab Page
			* @section heading 3
			* @tip Set the styling for all third-level headings in your emails.
			* @style heading 3
			*/
			h3, .h3{
				/*@editable*/ color:#202020;
				display:block;
				/*@editable*/ font-family:Arial;
				/*@editable*/ font-size:26px;
				/*@editable*/ font-weight:bold;
				/*@editable*/ line-height:100%;
				margin-top:0;
				margin-right:0;
				margin-bottom:10px;
				margin-left:0;
				/*@editable*/ text-align:left;
			}

			/**
			* @tab Page
			* @section heading 4
			* @tip Set the styling for all fourth-level headings in your emails. These should be the smallest of your headings.
			* @style heading 4
			*/
			h4, .h4{
				/*@editable*/ color:#202020;
				display:block;
				/*@editable*/ font-family:Arial;
				/*@editable*/ font-size:22px;
				/*@editable*/ font-weight:bold;
				/*@editable*/ line-height:100%;
				margin-top:1;
				margin-right:0;
				margin-bottom:10px;
				margin-left:0;
				/*@editable*/ text-align:left;
			}

			/* /\/\/\/\/\/\/\/\/\/\ STANDARD STYLING: PREHEADER /\/\/\/\/\/\/\/\/\/\ */

			/**
			* @tab Header
			* @section preheader style
			* @tip Set the background color for your email's preheader area.
			* @theme page
			*/
			#templatePreheader{
				/*@editable*/ background-color:#FAFAFA;
			}

			/**
			* @tab Header
			* @section preheader text
			* @tip Set the styling for your email's preheader text. Choose a size and color that is easy to read.
			*/
			.preheaderContent div{
				/*@editable*/ color:#505050;
				/*@editable*/ font-family:Arial;
				/*@editable*/ font-size:10px;
				/*@editable*/ line-height:100%;
				/*@editable*/ text-align:left;
			}

			/**
			* @tab Header
			* @section preheader link
			* @tip Set the styling for your email's preheader links. Choose a color that helps them stand out from your text.
			*/
			.preheaderContent div a:link, .preheaderContent div a:visited, /* Yahoo! Mail Override */ .preheaderContent div a .yshortcuts /* Yahoo! Mail Override */{
				/*@editable*/ color:#336699;
				/*@editable*/ font-weight:normal;
				/*@editable*/ text-decoration:underline;
			}



			/* /\/\/\/\/\/\/\/\/\/\ STANDARD STYLING: HEADER /\/\/\/\/\/\/\/\/\/\ */

			/**
			* @tab Header
			* @section header style
			* @tip Set the background color and border for your email's header area.
			* @theme header
			*/
			#templateHeader{
				/*@editable*/ background-color:#FFFFFF;
				/*@editable*/ border-bottom:0;
			}

			/**
			* @tab Header
			* @section header text
			* @tip Set the styling for your email's header text. Choose a size and color that is easy to read.
			*/
			.headerContent{
				/*@editable*/ color:#202020;
				/*@editable*/ font-family:Arial;
				/*@editable*/ font-size:34px;
				/*@editable*/ font-weight:bold;
				/*@editable*/ line-height:100%;
				/*@editable*/ padding:0;
				/*@editable*/ text-align:center;
				/*@editable*/ vertical-align:middle;
			}

			/**
			* @tab Header
			* @section header link
			* @tip Set the styling for your email's header links. Choose a color that helps them stand out from your text.
			*/
			.headerContent a:link, .headerContent a:visited, /* Yahoo! Mail Override */ .headerContent a .yshortcuts /* Yahoo! Mail Override */{
				/*@editable*/ color:#336699;
				/*@editable*/ font-weight:normal;
				/*@editable*/ text-decoration:underline;
			}

			#headerImage{
				height:auto;
				max-width:600px;
			}

			/* /\/\/\/\/\/\/\/\/\/\ STANDARD STYLING: COLUMNS; LEFT, CENTER, RIGHT /\/\/\/\/\/\/\/\/\/\ */

			/**
			* @tab Columns
			* @section left column text
			* @tip Set the styling for your email's left column text. Choose a size and color that is easy to read.
			*/
			.leftColumnContent{
				/*@editable*/ background-color:#FFFFFF;
			}

			/**
			* @tab Columns
			* @section left column text
			* @tip Set the styling for your email's left column text. Choose a size and color that is easy to read.
			*/
			.leftColumnContent div{
				/*@editable*/ color:#505050;
				/*@editable*/ font-family:Arial;
				/*@editable*/ font-size:14px;
				/*@editable*/ line-height:150%;
				/*@editable*/ text-align:left;
			}

			/**
			* @tab Columns
			* @section left column link
			* @tip Set the styling for your email's left column links. Choose a color that helps them stand out from your text.
			*/
			.leftColumnContent div a:link, .leftColumnContent div a:visited, /* Yahoo! Mail Override */ .leftColumnContent div a .yshortcuts /* Yahoo! Mail Override */{
				/*@editable*/ color:#336699;
				/*@editable*/ font-weight:normal;
				/*@editable*/ text-decoration:underline;
			}

			.leftColumnContent img{
				display:inline;
				height:auto;
			}

			/**
			* @tab Columns
			* @section center column text
			* @tip Set the styling for your email's center column text. Choose a size and color that is easy to read.
			*/
			.centerColumnContent{
				/*@editable*/ background-color:#FFFFFF;
			}

			/**
			* @tab Columns
			* @section center column text
			* @tip Set the styling for your email's center column text. Choose a size and color that is easy to read.
			*/
			.centerColumnContent div{
				/*@editable*/ color:#505050;
				/*@editable*/ font-family:Arial;
				/*@editable*/ font-size:14px;
				/*@editable*/ line-height:150%;
				/*@editable*/ text-align:left;
			}

			/**
			* @tab Columns
			* @section center column link
			* @tip Set the styling for your email's center column links. Choose a color that helps them stand out from your text.
			*/
			.centerColumnContent div a:link, .centerColumnContent div a:visited, /* Yahoo! Mail Override */ .centerColumnContent div a .yshortcuts /* Yahoo! Mail Override */{
				/*@editable*/ color:#336699;
				/*@editable*/ font-weight:normal;
				/*@editable*/ text-decoration:underline;
			}

			.centerColumnContent img{
				display:inline;
				height:auto;
			}

			/**
			* @tab Columns
			* @section right column text
			* @tip Set the styling for your email's right column text. Choose a size and color that is easy to read.
			*/
			.rightColumnContent{
				/*@editable*/ background-color:#FFFFFF;
			}

			/**
			* @tab Columns
			* @section right column text
			* @tip Set the styling for your email's right column text. Choose a size and color that is easy to read.
			*/
			.rightColumnContent div{
				/*@editable*/ color:#505050;
				/*@editable*/ font-family:Arial;
				/*@editable*/ font-size:14px;
				/*@editable*/ line-height:150%;
				/*@editable*/ text-align:left;
			}

			/**
			* @tab Columns
			* @section right column link
			* @tip Set the styling for your email's right column links. Choose a color that helps them stand out from your text.
			*/
			.rightColumnContent div a:link, .rightColumnContent div a:visited, /* Yahoo! Mail Override */ .rightColumnContent div a .yshortcuts /* Yahoo! Mail Override */{
				/*@editable*/ color:#336699;
				/*@editable*/ font-weight:normal;
				/*@editable*/ text-decoration:underline;
			}

			.rightColumnContent img{
				display:inline;
				height:auto;
			}			
			/* /\/\/\/\/\/\/\/\/\/\ STANDARD STYLING: MAIN BODY /\/\/\/\/\/\/\/\/\/\ */

			/**
			* @tab Body
			* @section body style
			* @tip Set the background color for your email's body area.
			*/
			#templateContainer, .bodyContent{
				/*@editable*/ background-color:#FFFFFF;
			}

			/**
			* @tab Body
			* @section body text
			* @tip Set the styling for your email's main content text. Choose a size and color that is easy to read.
			* @theme main
			*/
			.bodyContent div{
				/*@editable*/ color:#505050;
				/*@editable*/ font-family:Arial;
				/*@editable*/ font-size:14px;
				/*@editable*/ line-height:150%;
				/*@editable*/ text-align:left;
			}

			/**
			* @tab Body
			* @section body link
			* @tip Set the styling for your email's main content links. Choose a color that helps them stand out from your text.
			*/
			.bodyContent div a:link, .bodyContent div a:visited, /* Yahoo! Mail Override */ .bodyContent div a .yshortcuts /* Yahoo! Mail Override */{
				/*@editable*/ color:#336699;
				/*@editable*/ font-weight:normal;
				/*@editable*/ text-decoration:underline;
			}

			.bodyContent img{
				display:inline;
				height:auto;
			}


			/* /\/\/\/\/\/\/\/\/\/\ STANDARD STYLING: FOOTER /\/\/\/\/\/\/\/\/\/\ */

			/**
			* @tab Footer
			* @section footer style
			* @tip Set the background color and top border for your email's footer area.
			* @theme footer
			*/
			#templateFooter{
				/*@editable*/ background-color:#FFFFFF;
				/*@editable*/ border-top:0;
			}

			/**
			* @tab Footer
			* @section footer text
			* @tip Set the styling for your email's footer text. Choose a size and color that is easy to read.
			* @theme footer
			*/
			.footerContent div{
				/*@editable*/ color:#707070;
				/*@editable*/ font-family:Arial;
				/*@editable*/ font-size:12px;
				/*@editable*/ line-height:125%;
				/*@editable*/ text-align:left;
			}

			/**
			* @tab Footer
			* @section footer link
			* @tip Set the styling for your email's footer links. Choose a color that helps them stand out from your text.
			*/
			.footerContent div a:link, .footerContent div a:visited, /* Yahoo! Mail Override */ .footerContent div a .yshortcuts /* Yahoo! Mail Override */{
				/*@editable*/ color:#336699;
				/*@editable*/ font-weight:normal;
				/*@editable*/ text-decoration:underline;
			}

			.footerContent img{
				display:inline;
			}

			/**
			* @tab Footer
			* @section social bar style
			* @tip Set the background color and border for your email's footer social bar.
			* @theme footer
			*/
			#social{
				/*@editable*/ background-color:#FAFAFA;
				/*@editable*/ border:0;
			}

			/**
			* @tab Footer
			* @section social bar style
			* @tip Set the background color and border for your email's footer social bar.
			*/
			#social div{
				/*@editable*/ text-align:center;
			}

			/**
			* @tab Footer
			* @section utility bar style
			* @tip Set the background color and border for your email's footer utility bar.
			* @theme footer
			*/
			#utility{
				/*@editable*/ background-color:#FFFFFF;
				/*@editable*/ border:0;
			}

			/**
			* @tab Footer
			* @section utility bar style
			* @tip Set the background color and border for your email's footer utility bar.
			*/
			#utility div{
				/*@editable*/ text-align:center;
			}

			#monkeyRewards img{
				max-width:190px;
			}
		</style>
	</head>
    <body leftmargin=\"0\" marginwidth=\"0\" topmargin=\"0\" marginheight=\"0\" offset=\"0\">
    	<center>
        	<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" height=\"100%\" width=\"100%\" id=\"backgroundTable\">
            	<tr>
                	<td align=\"center\" valign=\"top\">
                        <!-- // Begin Template Preheader \\ -->
                        <table border=\"0\" cellpadding=\"10\" cellspacing=\"0\" width=\"600\" id=\"templatePreheader\">
                            <tr>
                                <td valign=\"top\" class=\"preheaderContent\">
                                
                                	<!-- // Begin Module: Standard Preheader \ -->
                                    <table border=\"0\" cellpadding=\"10\" cellspacing=\"0\" width=\"100%\">
                                    	<tr>
                                        	<td valign=\"top\">
                                            	<div mc:edit=\"std_preheader_content\">
                                                	 The PhotoRankr Weekly Newsletter. Top photography of the week, site news & updates, and recommended content.
                                                </div>
                                            </td>
                                            <!-- *|IFNOT:ARCHIVE_PAGE|* 
											<td valign=\"top\" width=\"190\">
                                            	<div mc:edit=\"std_preheader_links\">
                                                	Is this email not displaying correctly?<br /><a href=\"*|ARCHIVE|*\" target=\"_blank\">View it in your browser</a>. -->
                                                </div>
                                            </td>
											<!-- *|END:IF|* -->
                                        </tr>
                                    </table>
                                	<!-- // End Module: Standard Preheader \ -->
                                
                                </td>
                            </tr>
                        </table>
                        <!-- // End Template Preheader \\ -->
                    	<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\" id=\"templateContainer\">
                        	<tr>
                            	<td align=\"center\" valign=\"top\">
                                    <!-- // Begin Template Header \\ -->
                                	<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\" id=\"templateHeader\">
                                        <tr>
                                            <td class=\"headerContent\">
                                            
                                            	<!-- // Begin Module: Standard Header Image \\ -->
                                            	<img src=\"http://photorankr.com/graphics/newsletterlogo.png\" style=\"max-width:500px;padding-top:10px;\" id=\"headerImage campaign-icon\" mc:label=\"header_image\" mc:edit=\"header_image\" mc:allowdesigner mc:allowtext />
                                            	<!-- // End Module: Standard Header Image \\ -->
                                            
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- // End Template Header \\ -->
                                </td>
                            </tr>
                            
                            <!-- // News \\ -->
                            
                            <tr>
                                        	<td colspan=\"3\" valign=\"top\" class=\"bodyContent\">
                                            
                                                <!-- // Begin Module: Standard Content \\ -->
                                                <table border=\"0\" cellpadding=\"20\" cellspacing=\"0\" width=\"100%\">
                                                    <tr>
                                                        <td valign=\"top\">
                                                            <div mc:edit=\"std_content00\">
                                                               
                                                                <span style=\"font-size:16px;\"><h4 class=\"h4\">PR Newsletter | Week 3 September</span></h4>
                                                                <br />
                                                                It's Friday!
                                                                <br /><br />
                                                                Happy Friday PhotoRankr's. This week's newsletter is again, short but sweet. As for site news, you can now directly share each and every upload from PhotoRankr to Facebook and Twitter. Very simple and painless, try it out! In addition, this coming week, look out for your own personal store page (akin to FineArtAmerica) where you will be able to provide to potential buyers and viewers a link with all of your licensed images in one spot. We hope this will satisfy those of you who are more serious about having one place to license your highest-quality photography. We are very exicted!
                                                                
<br /><br />                                                                
This week's top photos and photographers are featured below. If you haven't checked out the amazing work of <a href=\"https://photorankr.com/viewprofile.php?u=1188\">John Jewett</a>, <a href=\"https://photorankr.com/viewprofile.php?u=1297\">Alejandro Ferrer Ruiz</a>, or <a href=\"https://photorankr.com/viewprofile.php?u=1311\">Mark Johnson</a> yet, be sure to do so! They have some magnificent long exposures and macros you don't want to miss. Also, be sure to like us on Facebook and follow us on Twitter by clicking on the links at the bottom of the newsletter.
                                                                <br /><br />
                                                                Best Regards,
                                                                <br /><br />
                                                                –The PhotoRankr Team
                                                                                                                        
                                                           </div>
														</td>
                                                    </tr>
                            
                            <!-- // End of News Section \\ -->

                            
                            <!-- // Begin Top Photos Section \\ -->
                            <tr>
                                <td>
                                <h3 style=\"font-size:20px;font-family:helvetica neue,helvetica,arial,font-weight:200;\">Top Photos of the Week:</h3>
                                </td>
                            </tr>
                            
                        	<tr>
                            	<td align=\"center\" valign=\"top\">
                                    <!-- // Begin Template Body \\ -->
                                	<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\" id=\"templateBody\">
                                    	<tr>
                                        	<td valign=\"top\" width=\"180\" class=\"leftColumnContent\">
                                                                                        
                                                <!-- // Begin Module: Top Image with Content \\ -->
                                                <table border=\"0\" cellpadding=\"20\" cellspacing=\"0\" width=\"100%\">
                                                    <tr mc:repeatable>
                                                        <td valign=\"top\">
                                                            <a href=\"http://photorankr.com/fullsize.php?imageid=$photo1id&v=r\"><img src=\"http://photorankr.com/$photo1\" style=\"max-width:160px;\" mc:label=\"image\" mc:edit=\"tiwc200_image00\" /></a>
                                                            <div mc:edit=\"tiwc200_content00\">
                                                               <br />
 	                                                           <h4 class=\"h4\">$photo1caption</h4>
                                                               
                                                               <a style=\"text-decoration:none;\" href=\"http://photorankr.com/viewprofile.php?u=$owner1id\">
                                                               <img src=\"http://photorankr.com/$owner1pic\" style=\"padding-top:15px;max-width:30px;\" />
                                                               <div style=\"margin-top:-25px;margin-left:35px;\">$owner1full</div>
                                                               </a>
                                                               
                                                               <br />
                                                               
                                                               <strong>Rank: </strong><span style=\"color:#000;font-size:16px;font-weight:500;\">$photo1score</span><span style=\"font-size:12px;\">/10.0</span>
                                                               <br />
                                                               <strong>Base Price: </strong><span style=\"color:#000;font-size:15px;font-weight:500;\">$$photo1price</span>
                                                               <br /><br />
                                                               <a style=\"color:#333;\" href=\"http://photorankr.com/fullsizemarket.php?imageid=$photo1id\">Purchase Photograph</a>

                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <!-- // End Module: Top Image with Content \\ -->
                                            
                                            </td>
                                        	<td valign=\"top\" width=\"180\" class=\"centerColumnContent\">
                                            
                                                <!-- // Begin Module: Top Image with Content \\ -->
                                                <table border=\"0\" cellpadding=\"20\" cellspacing=\"0\" width=\"100%\">
                                                    <tr mc:repeatable>
                                                        <td valign=\"top\">
                                                            <a href=\"http://photorankr.com/fullsize.php?imageid=$photo2id&v=r\"><img src=\"http://photorankr.com/$photo2\" style=\"max-width:160px;\" mc:label=\"image\" mc:edit=\"tiwc200_image01\" /></a>
                                                            <div mc:edit=\"tiwc200_content01\">
                                                               <br />
 	                                                           <h4 class=\"h4\">$photo2caption</h4>
                                                               
                                                               <a style=\"text-decoration:none;\" href=\"http://photorankr.com/viewprofile.php?u=$owner2id\">
                                                               <img src=\"http://photorankr.com/$owner2pic\" style=\"padding-top:15px;max-width:30px;\" />
                                                               <div style=\"margin-top:-25px;margin-left:35px;\">$owner2full</div>
                                                               </a>
                                                               
                                                               <br />
                                                               
                                                               <strong>Rank: </strong><span style=\"color:#000;font-size:16px;font-weight:500;\">$photo2score</span><span style=\"font-size:12px;\">/10.0</span>
                                                               <br />
                                                               <strong>Base Price: </strong><span style=\"color:#000;font-size:15px;font-weight:500;\">$$photo2price</span>
                                                               <br /><br />
                                                               <a style=\"color:#333;\" href=\"http://photorankr.com/fullsizemarket.php?imageid=$photo2id\">Purchase Photograph</a>


                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <!-- // End Module: Top Image with Content \\ -->
                                            
                                            </td>
                                        	<td valign=\"top\" width=\"180\" class=\"rightColumnContent\">
                                            
                                                <!-- // Begin Module: Top Image with Content \\ -->
                                                <table border=\"0\" cellpadding=\"20\" cellspacing=\"0\" width=\"100%\">
                                                    <tr mc:repeatable>
                                                        <td valign=\"top\">
                                                            <a href=\"http://photorankr.com/fullsize.php?imageid=$photo3id&v=r\"><img src=\"http://photorankr.com/$photo3\"  style=\"max-width:160px;\" mc:label=\"image\" mc:edit=\"tiwc200_image02\" /></a>
                                                            <div mc:edit=\"tiwc200_content02\">
                                                               <br />
 	                                                           <h4 class=\"h4\">$photo3caption</h4>
                                                               
                                                               <a style=\"text-decoration:none;\" href=\"http://photorankr.com/viewprofile.php?u=$owner3id\">
                                                               <img src=\"http://photorankr.com/$owner3pic\" style=\"padding-top:15px;max-width:30px;\" />
                                                               <div style=\"margin-top:-25px;margin-left:35px;\">$owner3full</div>
                                                               </a>
                                                               
                                                               <br />
                                                               
                                                               <strong>Rank: </strong><span style=\"color:#000;font-size:16px;font-weight:500;\">$photo3score</span><span style=\"font-size:12px;\">/10.0</span>
                                                               <br />
                                                               <strong>Base Price: </strong><span style=\"color:#000;font-size:15px;font-weight:500;\">$$photo3price</span>
                                                               <br /><br />
                                                               <a style=\"color:#333;\" href=\"http://photorankr.com/fullsizemarket.php?imageid=$photo3id\">Purchase Photograph</a>


                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <!-- // End Module: Top Image with Content \\ -->
                                            
                                            </td>
                                        </tr>
                                    </table>
                                    
                                    
                                     <!-- // Begin Most Popular Photos Section \\ -->
                        
                            <tr>
                                <td>
                                <h3 style=\"font-size:20px;font-family:helvetica neue,helvetica,arial,font-weight:200;\">Most Popular This Week:</h3>
                                </td>
                            </tr>
                            
                        	<tr>
                            	<td align=\"center\" valign=\"top\">
                                    <!-- // Begin Template Body \\ -->
                                	<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\" id=\"templateBody\">
                                    	<tr>
                                        	<td valign=\"top\" width=\"180\" class=\"leftColumnContent\">
                                                                                        
                                                <!-- // Begin Module: Top Image with Content \\ -->
                                                <table border=\"0\" cellpadding=\"20\" cellspacing=\"0\" width=\"100%\">
                                                    <tr mc:repeatable>
                                                        <td valign=\"top\">
                                                            <a href=\"http://photorankr.com/fullsize.php?imageid=$popphoto1id&v=r\"><img src=\"http://photorankr.com/$popphoto1\" style=\"max-width:160px;\" mc:label=\"image\" mc:edit=\"tiwc200_image00\" /></a>
                                                            <div mc:edit=\"tiwc200_content00\">
                                                               <br />
 	                                                           <h4 class=\"h4\">$popphoto1caption</h4>
                                                               
                                                               <a style=\"text-decoration:none;\" href=\"http://photorankr.com/viewprofile.php?u=$popowner1id\">
                                                               <img src=\"http://photorankr.com/$popowner1pic\" style=\"padding-top:15px;max-width:30px;\" />
                                                               <div style=\"margin-top:-25px;margin-left:35px;\">$popowner1full</div>
                                                               </a>
                                                               
                                                               <br />
                                                               
                                                               <strong>Rank: </strong><span style=\"color:#000;font-size:16px;font-weight:500;\">$popphoto1score</span><span style=\"font-size:12px;\">/10.0</span>
                                                               <br />
                                                               <strong>Base Price: </strong><span style=\"color:#000;font-size:15px;font-weight:500;\">$$popphoto1price</span>
                                                               <br /><br />
                                                               <a style=\"color:#333;\" href=\"http://photorankr.com/fullsizemarket.php?imageid=$popphoto1id\">Purchase Photograph</a>

                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <!-- // End Module: Top Image with Content \\ -->
                                            
                                            </td>
                                        	<td valign=\"top\" width=\"180\" class=\"centerColumnContent\">
                                            
                                                <!-- // Begin Module: Top Image with Content \\ -->
                                                <table border=\"0\" cellpadding=\"20\" cellspacing=\"0\" width=\"100%\">
                                                    <tr mc:repeatable>
                                                        <td valign=\"top\">
                                                            <a href=\"http://photorankr.com/fullsize.php?imageid=$popphoto2id&v=r\"><img src=\"http://photorankr.com/$popphoto2\" style=\"max-width:160px;\" mc:label=\"image\" mc:edit=\"tiwc200_image01\" /></a>
                                                            <div mc:edit=\"tiwc200_content01\">
 	                                                           <br />
                                                               <h4 class=\"h4\">$popphoto2caption</h4>
                                                               
                                                               <a style=\"text-decoration:none;\" href=\"http://photorankr.com/viewprofile.php?u=$popowner2id\">
                                                               <img src=\"http://photorankr.com/$popowner2pic\" style=\"padding-top:15px;max-width:30px;\" />
                                                               <div style=\"margin-top:-25px;margin-left:35px;\">$popowner2full</div>
                                                               </a>
                                                               
                                                               <br />
                                                               
                                                               <strong>Rank: </strong><span style=\"color:#000;font-size:16px;font-weight:500;\">$popphoto2score</span><span style=\"font-size:12px;\">/10.0</span>
                                                               <br />
                                                               <strong>Base Price: </strong><span style=\"color:#000;font-size:15px;font-weight:500;\">$$popphoto2price</span>
                                                               <br /><br />
                                                               <a style=\"color:#333;\" href=\"http://photorankr.com/fullsizemarket.php?imageid=$popphoto2id\">Purchase Photograph</a>


                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <!-- // End Module: Top Image with Content \\ -->
                                            
                                            </td>
                                        	<td valign=\"top\" width=\"180\" class=\"rightColumnContent\">
                                            
                                                <!-- // Begin Module: Top Image with Content \\ -->
                                                <table border=\"0\" cellpadding=\"20\" cellspacing=\"0\" width=\"100%\">
                                                    <tr mc:repeatable>
                                                        <td valign=\"top\">
                                                            <a href=\"http://photorankr.com/fullsize.php?imageid=$photo3id&v=r\"><img src=\"http://photorankr.com/$popphoto3\"  style=\"max-width:160px;\" mc:label=\"image\" mc:edit=\"tiwc200_image02\" /></a>
                                                            <div mc:edit=\"tiwc200_content02\">
 	                                                           <br />
                                                               <h4 class=\"h4\">$popphoto3caption</h4>
                                                               
                                                               <a style=\"text-decoration:none;\" href=\"http://photorankr.com/viewprofile.php?u=$popowner3id\">
                                                               <img src=\"http://photorankr.com/$popowner3pic\" style=\"padding-top:15px;max-width:30px;\" />
                                                               <div style=\"margin-top:-25px;margin-left:35px;\">$popowner3full</div>
                                                               </a>
                                                               
                                                               <br />
                                                               
                                                               <strong>Rank: </strong><span style=\"color:#000;font-size:16px;font-weight:500;\">$popphoto3score</span><span style=\"font-size:12px;\">/10.0</span>
                                                               <br />
                                                               <strong>Base Price: </strong><span style=\"color:#000;font-size:15px;font-weight:500;\">$$popphoto3price</span>
                                                               <br /><br />
                                                               <a style=\"color:#333;\" href=\"http://photorankr.com/fullsizemarket.php?imageid=$popphoto3id\">Purchase Photograph</a>


                                                            </div>
                                                        </td>
                                                    </tr>

                                                    
                                                    
                                                </table>
                                                <!-- // End Module: Top Image with Content \\ -->
                                                 
                                            </td>
                                        </tr>
                                    </table>
                                    
                                            
                                     <!-- // Begin Top New Photographers Section \\ -->
                        
                            <tr>
                                <td>
                                <h3 style=\"font-size:20px;font-family:helvetica neue,helvetica,arial,font-weight:200;\">New Photographers on PR:</h3>
                                </td>
                            </tr>
                            
                        	<tr>
                            	<td align=\"center\" valign=\"top\">
                                    <!-- // Begin Template Body \\ -->
                                	<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\" id=\"templateBody\">
                                    	<tr>
                                        	<td valign=\"top\" width=\"180\" class=\"leftColumnContent\">
                                                                                        
                                                <!-- // Begin Module: Top Image with Content \\ -->
                                                <table border=\"0\" cellpadding=\"20\" cellspacing=\"0\" width=\"100%\">
                                                    <tr mc:repeatable>
                                                        <td valign=\"top\">
                                                            <a href=\"http://photorankr.com/viewprofile.php?u=$topphotog1id&view=portfolio\"><img src=\"http://photorankr.com/$topphotog1pic\" style=\"max-width:160px;\" mc:label=\"image\" mc:edit=\"tiwc200_image00\" /></a>
                                                            <div mc:edit=\"tiwc200_content00\">
 	                                                           <a style=\"text-decoration:none;\" href=\"http://photorankr.com/viewprofile.php?u=$topphotog1id\">
                                                               <br />
                                                               <h4 class=\"h4\">$topphotog1full</h4>
                                                               </a>
                                                               
                                                               <br />
                                                               
                                                               <a style=\"color:#333;\" href=\"http://photorankr.com/viewprofile.php?u=$topphotog1id&view=portfolio\">Visit Portfolio</a>
                                                               
                                                               <br /><br />
                                                               
                                                               <a style=\"color:#333;\" href=\"http://photorankr.com/viewprofile.php?u=$topphotog1id&view=store\">Visit Store</a>


                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <!-- // End Module: Top Image with Content \\ -->
                                            
                                            </td>
                                        	<td valign=\"top\" width=\"180\" class=\"centerColumnContent\">
                                            
                                                <!-- // Begin Module: Top Image with Content \\ -->
                                                <table border=\"0\" cellpadding=\"20\" cellspacing=\"0\" width=\"100%\">
                                                    <tr mc:repeatable>
                                                        <td valign=\"top\">
                                                            <a href=\"http://photorankr.com/viewprofile.php?u=$topphotog2id&view=portfolio\"><img src=\"http://photorankr.com/$topphotog2pic\" style=\"max-width:160px;\" mc:label=\"image\" mc:edit=\"tiwc200_image00\" /></a>
                                                            <div mc:edit=\"tiwc200_content00\">
 	                                                           <a style=\"text-decoration:none;\" href=\"http://photorankr.com/viewprofile.php?u=$topphotog2id\">
                                                               <br />
                                                               <h4 class=\"h4\">$topphotog2full</h4>
                                                               </a>
                                                               
                                                               <br />
                                                            
                                                               <a style=\"color:#333;\" href=\"http://photorankr.com/viewprofile.php?u=$topphotog2id&view=portfolio\">Visit Portfolio</a>
                                                               
                                                               <br /><br />
                                                               
                                                               <a style=\"color:#333;\" href=\"http://photorankr.com/viewprofile.php?u=$topphotog2id&view=store\">Visit Store</a>


                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <!-- // End Module: Top Image with Content \\ -->
                                            
                                            </td>
                                        	<td valign=\"top\" width=\"180\" class=\"rightColumnContent\">
                                            
                                                <!-- // Begin Module: Top Image with Content \\ -->
                                                <table border=\"0\" cellpadding=\"20\" cellspacing=\"0\" width=\"100%\">
                                                    <tr mc:repeatable>
                                                        <td valign=\"top\">
                                                            <a href=\"http://photorankr.com/viewprofile.php?u=$topphotog3id&view=portfolio\"><img src=\"http://photorankr.com/$topphotog3pic\" style=\"max-width:160px;\" mc:label=\"image\" mc:edit=\"tiwc200_image00\" /></a>
                                                            <div mc:edit=\"tiwc200_content00\">
 	                                                           <a style=\"text-decoration:none;\" href=\"http://photorankr.com/viewprofile.php?u=$topphotog3id\">
                                                               <br />
                                                               <h4 class=\"h4\">$topphotog3full</h4>
                                                               </a>
                                                               
                                                               <br />
                                                               
                                                               <a style=\"color:#333;\" href=\"http://photorankr.com/viewprofile.php?u=$topphotog3id&view=portfolio\">Visit Portfolio</a>
                                                               
                                                               <br /><br />
                                                               
                                                               <a style=\"color:#333;\" href=\"http://photorankr.com/viewprofile.php?u=$topphotog3id&view=store\">Visit Store</a>


                                                            </div>
                                                        </td>
                                                    </tr>

                                                    
                                                    
                                                </table>
                                                <!-- // End Module: Top Image with Content \\ -->
                                                 
                                            </td>
                                        </tr>
                                    </table>

                                                
                                                <!-- // End Module: Standard Content \\ -->
                                            
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- // End Template Body \\ -->
                                </td>
                            </tr>
                        	<tr>
                            	<td align=\"center\" valign=\"top\">
                                    <!-- // Begin Template Footer \\ -->
                                	<table border=\"0\" cellpadding=\"10\" cellspacing=\"0\" width=\"600\" id=\"templateFooter\">
                                    	<tr>
                                        	<td valign=\"top\" class=\"footerContent\">
                                            
                                                <!-- // Begin Module: Standard Footer \\ -->
                                                <table border=\"0\" cellpadding=\"10\" cellspacing=\"0\" width=\"100%\">
                                                    <tr>
                                                        <td colspan=\"2\" valign=\"middle\" id=\"social\">
                                                            <div mc:edit=\"std_social\">
                                                                &nbsp;<a href=\"https://twitter.com/PhotoRankr\"><img src=\"http://photorankr.com/graphics/twitter.png\" width=\"25\" /></a>&nbsp;&nbsp;<a href=\"https://twitter.com/PhotoRankr\">Follow us on Twitter</a>&nbsp;&nbsp;<a href=\"https://www.facebook.com/pages/PhotoRankr/140599622721692\"><img src=\"http://photorankr.com/graphics/facebook.png\" width=\"25\" /></a>&nbsp;&nbsp;<a href=\"https://www.facebook.com/pages/PhotoRankr/140599622721692\">Friend us on Facebook</a> 
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td valign=\"top\" width=\"350\">
                                                            <div mc:edit=\"std_footer\">
																<em>Copyright &copy; 2012 PhotoRankr, All rights reserved.</em>
																<!--
                                                                <br />
																*|IFNOT:ARCHIVE_PAGE|* *|LIST:DESCRIPTION|*-->
																<br />
																<strong>Our mailing address is:</strong>
																<br />
																photorankr@photorankr.com 
                                                            </div>
                                                        </td>
                                                        <td valign=\"top\" width=\"190\" id=\"monkeyRewards\">
                                                            <div mc:edit=\"monkeyrewards\">
                                                                <!--
                                                                *|IF:REWARDS|* *|HTML:REWARDS|* *|END:IF|*-->
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan=\"2\" valign=\"middle\" id=\"utility\">
                                                            <div mc:edit=\"std_utility\">
                                                               <!-- &nbsp;<a href=\"*|UNSUB|*\">unsubscribe from this list</a> -->
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <!-- // End Module: Standard Footer \\ -->
                                            
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- // End Template Footer \\ -->
                                </td>
                            </tr>
                        </table>
                        <br />
                    </td>
                </tr>
            </table>
        </center>
    </body>
</html>
        
        ",
        "text" => null,
        "from_email" => "photorankr@photorankr.com",
        "from_name" => "PhotoRankr",
        "subject" => "Weekly Newsletter",
        "to" => array(array("email" => $toaddress)),
        "track_opens" => true,
        "track_clicks" => true,
        "auto_text" => true
    )   
);


$curl = curl_init('https://mandrillapp.com/api/1.0/messages/send.json');
curl_setopt($curl, CURLOPT_POST, true);

curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($args));
$response = curl_exec($curl);
curl_close($curl);


var_export($response);
   echo $response .'<br />';
   
}
   
    //Redirect so email not sent twice
    //echo '<META HTTP-EQUIV="Refresh" Content="0; URL=newsletter.php?send=done">';
    
    } //end if send == 'true'
    
?>