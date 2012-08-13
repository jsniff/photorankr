<!DOCTYPE HTML>

<html>
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="css/practice.css" type="text/css" />
	<link href="css/bootstrapNew.css" type="text/css" rel="stylesheet"/>
	<script src="http://code.jquery.com/jquery-latest.js" type="text/javascript" ></script>
	<script src="js/javascript.js" type="text/javascript"></script>


<style type="text/css">


#header h1
{
	font-family: Arial, Helvetica, Sans-serif;
	font-weight: bold;
	position: relative;
	top:30px;
	font-size:40px;
	color:white;
}

#header h2
{
	font-family: Arial, Helvetica, Sans-serif;
	font-weight: bold;
	font-size:16px;
	color:#7e7e7e;
	margin:-15px ;
}
hr#header_stripe
{
	height:12px;
	position:relative;
	top:-7px;
	background-color:#191919;
	border:none;
	color:#191919;


}



	#wrapper{  
        margin-left: auto;  
        margin-right: auto;  
        width: 100%;  
        text-align: center; 

    }  
    #toppanel {  
        position: absolute;  
        top:0px; 
        left:0px; 
        width: 100%;  
        height:100px;
        z-index: 25;  
        text-align: center;  
        margin-left: auto;  
        margin-right: auto;  
        margin-top:30px;
    }  
    #panel {  
        width: 100%;  
        position: relative;  
        top: 1px;  
        height: 0%;  
        margin-left: auto;  
        margin-right: auto;  
        z-index: 10;  
        overflow: hidden;  
        text-align: left;  
    }  
    #panel_contents {  
     background-image:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAQAAAAECAYAAACp8Z5+AAAAJ0lEQVQImVXKwQ0AMAyDQHuM7D/o9dNUKj8QRZa2E+TGQT7Z+wlyAHyXHku3EWK3AAAAAElFTkSuQmCC);
	background-repeat: repeat;
	background-color:#444;
        height: 100%;  
        width: 100%;  
        position: absolute;  
        z-index: -1;  
    }  

.panel_button
{
	background: url(images/panel_button.png);
	margin-left:auto;
	margin-right:auto;
	position:relative;
	top:1px;
	width:173px;
	height:54px;
		z-index:20;
	filter:alpha(opacity=70);  
    -moz-opacity:0.70;  
    -khtml-opacity: 0.70;  
    opacity: 0.70; 
    cursor:pointer;
}

.panel_button a 
{
	text-decoration: none;
	color:#545454;
	font-size:20px;
	font-weight:bold;
	position:relative;
	top: 5px;
	left: 10px;
	font-family: Arial, Helvetica, sans-serif;
}
.panel_button a:hover {  
color: #999999;  
} 

 .panel_buttton img
 {
 	position:relative;
 	top:10px;
 	border:none;
 }

#content
{
	margin-left:auto;
	margin-right:auto;
	width:600px;
	position:relative;
	top:90px;
	text-align:left;
	color:#545454;
	font-family:Arial, helvetica, sans-serif;
	font-size:16px;
	padding-bottom:30px;
}
p#content

{

}






</style>
</head>	

<body>
<div id="wrapper"> <!--contains button and panel-->
	<div id="toppanel"> <!--Contains the panel itself-->
		<div id="panel">
		<div id="panel_contents"> 

			<div style="margin:0px auto;height:80px;background:rgba(255,255,255,.5);width:1020px;margin-top:10px;"></div>

		</div>

	</div>

		<div class="panel_button" style="display:visible;"> <img src="images/expand.png" alt="expand"/><a href="#"> <button class="btn btn-danger"> Sign In </button></a></div> <!--Button that slide panel down-->

		<div class="panel_button" id="hide_button" style="display:none;"> <img src="images/collapse.png"/> <a href="#">Hide</a></div> <!--Toggles to this when the panel slides down-->
	</div>
</div>

















</body>

</html>