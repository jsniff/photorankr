 <html>
<body>

<h1>This is Page1</h1>
<p>This is some text.</p>



 <link rel="stylesheet"  href="bootstrap.css" type="text/css" /> 

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>

<script src="bootstrap.js" type="text/javascript"></script>


<div class="modal hide fade" id="myModal">
  <div class="modal-header">
    <a style="float:right" class="btn btn-primary" data-dismiss="modal">Done Bitch</a>
    <h3>Social Media Outlets</h3>
  </div>
  <div class="modal-body">
<p

<div id="fb-root"></div>





<form action='page.php' method='post'>
<input type='checkbox' name='chk' value='value1'> ANY VALUE 1 
<input type='checkbox' name='chk' value='value2'> ANY VALUE 2 
<input type='checkbox' name='chk' value='value3'> ANY VALUE 3 
<input type='checkbox' name='chk' value='value4'> ANY VALUE 4 
<input type='submit' name='submit' value='Get Value'>
</form>


<?php
    
    $value = $_POST['chk'];
    echo $value;
    
    
    ?>








//<form>

//</form>

//redirect to own page
<form action="facebookandtwittermodal.php?action=socialmedia" method="post" style="width:180px;margin-left:25px;">
<div>
<input type="checkbox" name="Facebook" value='value1' /> Post to Mother Fucking Facebook Bitch<br />
<input type="checkbox" name="Twitter"  value = 'value2'/> Post to Mother Fucking Twitter
</div>
<div>
<button class="signupbutton" type="submit" style="text-decoration:none;color:#fff;" ><p class="buttontext">Post Your Shit to Social Media Now Bitch</p></button>
</div>
</form>



</p>
  </div>
  
</div>

<a class="btn btn-primary" data-toggle="modal" href="#myModal" >Launch Modal</a>

</body>



<?php



if($_GET['action'] == "socialmedia") {

//$facebookcheck = $_REQUEST['Facebook'];
//$twittercheck = $_REQUEST['Twitter'];
echo "Nochbag";
    
   //  $value = $_POST['Facebook'];
    echo $value1;
    

   // if (isset($_POST['value1'])) {
  // echo "Nochbagagain";
    
    //  } 
    
}

    //if (isset($_POST['sport'])) {
        // do something
    
  //  } 
    
    
    

    
?>




</html>




