<?php 

require("db_connection.php");

session_start();
$useremail = $_SESSION['email'];

if($_GET['lastPicture']) {
	$query =  mysql_query("SELECT * FROM photos WHERE id < ".$_GET['lastPicture']." AND emailaddress='$useremail' AND price != ('Not For Sale') AND price != (.00) ORDER BY id DESC LIMIT 0, 9") or die(mysql_error());;
	$numresults = mysql_num_rows($query);

	//DISPLAY 20 NEWEST OF ALL PHOTOS

        echo'<div id="container" class="grid_18" style="width:770px;margin-top:-68px;margin-left:-10px;padding:35px;">';

        for($iii=0; $iii < $numresults; $iii++) {
              
                $image[$iii] = mysql_result($query, $iii, "source");
                $imageThumb[$iii] = str_replace("userphotos/","../userphotos/medthumbs/", $image[$iii]);
                $id = mysql_result($query, $iii, "id");
                $caption = mysql_result($query, $iii, "caption");
                $points = mysql_result($query, $iii, "points");
                $votes = mysql_result($query, $iii, "votes");
                $faves = mysql_result($query, $iii, "faves");
                $price = mysql_result($query, $iii, "price");
                $sold = mysql_result($query, $iii, "sold");
                $score = number_format(($points/$votes),2);
                $faveemail = mysql_result($query, $iii, "emailaddress");
                $ownerquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$faveemail'");
                $firstname = mysql_result($query, 0, "firstname");
                $lastname = mysql_result($query, 0, "lastname");
                $reputation = mysql_result($query, 0, "lastname");
                $fullname = $firstname . " " . $lastname;
                list($width, $height) = getimagesize($image);
                $imgratio = $height / $width;
                $heightls = $height / 3.5;
                $widthls = $width / 3.5;
                
                $licensecheck = mysql_query("SELECT license FROM photos WHERE id = '$id'");
                $licenseschecked = mysql_result($licensecheck,0,'license');
                
                echo '   

                <div class="fPic" id="',$id,'" style="width:245px;overflow:hidden;float:left;margin-left:10px;margin-top:30px;"><a href="http://photorankr.com/fullsizemarket.php?imageid=',$id,'">
                
                <div style="width:245px;height:245px;overflow:hidden;">
                <div class="statoverlay" style="z-index:1;left:0px;top:155px;position:relative;background-color:black;width:245px;height:75px;"><p style="line-spacing:1.48;padding:5px;color:white;"><span style="font-size:16px;font-weight:100;">',$caption,'</span><br><span style="font-size:14px;font-weight:100;">Sold: ',$sold,'<br>Base Price: $',$price,'</span></p></div>

                <img onmousedown="return false" oncontextmenu="return false;" style="position:relative;top:-90px;min-height:245px;min-width:245px;" src="http://www.photorankr.com/',$imageThumb[$iii],'" height="',$heightls,'px" width="',$widthls,'px" /></a>
                <br />      
                </div>    
                    
                    <!--DROPDOWN MANAGE-->
                    <div class="panel',$id,'">
                    
                    
            <script type="text/javascript">
            function showOtherPrice() {
                if (document.getElementById("price3").value == "Other Price")
                    {
                        document.getElementById("otherprice3").className = "show";
                    }
                else if (document.getElementById(\'price',$id,'\').value == \'Not For Sale\')
                    {
                        document.getElementById(\'remove',$id,'\').className = \'show\';
                    }
                else {
                    document.getElementById(\'otherprice',$id,'\').className = \'hide\';
                    }
            }
            </script>
            
        <!--FOR SALE-->
        <table class="table">
        <tbody>
        
        <tr>
        <td>Base Price:</td>
        <td>
            <div>
            <form action="myprofile3.php?view=store&updateimage=',$id,'" method="post">
            <select id="price" name="price" style="width:120px;float:left;margin-left:-70px;margin-top:-20px;" onchange="showOtherPrice()">
            <option value="">Price:</option>
            <option value=".00">Free</option>
            <option value=".50">$.50</option>
            <option value=".75">$.75</option>
            <option value="1.00">$1.00</option>
            <option value="2.00">$2.00</option>
            <option value="5.00">$5.00</option>
            <option value="10.00">$10.00</option>
            <option value="15.00">$15.00</option>
            <option value="25.00">$25.00</option>
            <option value="50.00">$50.00</option>
            <option value="100.00">$100.00</option>
            <option value="200.00">$200.00</option>
            <option value="Not For Sale">Not For Sale</option>
            </select>
            </div>
            <div id="otherprice" class="hide" style="margin-left:-150px;width:290px;"><br /><div class="input-prepend input-append" style="float:left;"> 
                <span class="add-on">$</span><input class="span2" id="appendedPrependedInput" size="16" type="text"><span class="add-on">.00</span>
              </div></div>
        </td>
        </tr>
        
        <tr>
        <td colspan="2"><br /><b>Edit Options for Sale:</b></td>
        </tr>';
        
                
        $mystring = $licenseschecked;
        $findme   = 'multiseat';
        $foundlicense = strpos($mystring,$findme);

        if($foundlicense !== false) {
        echo'
            <tr>
            <td><div style="width:150px;">
            <input type="checkbox" name="license[]" value="multiseat" checked />&nbsp;&nbsp;Multi-Seat</div>
            </td>
            <td>+ $30</td>
            </tr>';
        }
        else {
        echo'
            <tr>
            <td><div style="width:150px;">
            <input type="checkbox" name="license[]" value="multiseat" />&nbsp;&nbsp;Multi-Seat</div>
            </td>
            <td>+ $30</td>
            </tr>';
        }    
        
        $mystring = $licenseschecked;
        $findme   = 'printruns';
        $foundlicense = strpos($mystring,$findme);

        if($foundlicense !== false) {
        echo'
            <tr>
            <td><div style="width:150px;">
            <input type="checkbox" name="license[]" value="printruns" checked />&nbsp;&nbsp;Unlimited Reproduction</div>
            </td>
            <td>+ $30</td>
            </tr>';
        }
        else {
        echo'
            <tr>
            <td><div style="width:150px;">
            <input type="checkbox" name="license[]" value="printruns" />&nbsp;&nbsp;Unlimited Reproduction</div>
            </td>
            <td>+ $30</td>
            </tr>';
        }    
        
        $mystring = $licenseschecked;
        $findme   = 'resale';
        $foundlicense = strpos($mystring,$findme);

        if($foundlicense !== false) {
        echo'
            <tr>
            <td><div style="width:150px;">
            <input type="checkbox" name="license[]" value="resale" checked />&nbsp;&nbsp;Allow Resale</div>
            </td>
            <td>+ $30</td>
            </tr>';
        }
        else {
        echo'
            <tr>
            <td><div style="width:150px;">
            <input type="checkbox" name="license[]" value="resale" />&nbsp;&nbsp;Allow Resale</div>
            </td>
            <td>+ $30</td>
            </tr>';
        }   
        
        $mystring = $licenseschecked;
        $findme   = 'electronic';
        $foundlicense = strpos($mystring,$findme);

        if($foundlicense !== false) {
        echo'
            <tr>
            <td><div style="width:150px;">
            <input type="checkbox" name="license[]" value="electronic" checked />&nbsp;&nbsp;Allow Electronic Use</div>
            </td>
            <td>+ $30</td>
            </tr>';
        }
        else {
        echo'
            <tr>
            <td><div style="width:150px;">
            <input type="checkbox" name="license[]" value="electronic" />&nbsp;&nbsp;Allow Electronic Usee</div>
            </td>
            <td>+ $30</td>
            </tr>';
        }   
        
        echo'
        <input type="hidden" name="caption" value="',$caption,'" />
        <input type="hidden" name="thumb" value="',$imageThumb[$iii],'" /> 
        
        </tbody>
        </table>
        
        <div style="text-align:center;">
        <button class="btn btn-success" type="submit" style="width:210px;padding:7px;margin-bottom:10px;" href="#">Update Market Info</button>
        </form>
        </div>
        
                    </div>
                    
                    <a name="',$id,'" href="#"><p class="flip',$id,'" style="font-size:15px;font-weight:200;"></a>Manage</p>
                    
                    
                    <style type="text/css">
                    p.flip',$id,' {
                    padding:10px;
                    width:223px;
                    clear:both;
                    text-align:center;
                    background:white;
                    border:solid 1px #c3c3c3;
                    }

                    p.flip',$id,':hover {
                    background-color: #ccc;
                    }

                    div.panel',$id,' {
                    display:none;
                    clear:both;
                    padding:300px;
                    padding:5px;
                    text-align:left;
                    background:white;
                    border:solid 1px #c3c3c3;
                    }
                    </style>
                    
                    <!--HIDDEN COMMENT SCRIPT-->
                    <script type="text/javascript">   
                    $(document).ready(function(){
                    $(".flip',$id,'").click(function(){
                        $(".panel',$id,'").slideToggle("slow");
                    });
                    });
                    </script>
                    
                </div>';


	    
                } //end for loop      
        
        echo'</div>';

}//end if clause

?>
