<?php

    //connect to the database
    require "db_connection.php";
    require "functions.php";

    $email = mysql_real_escape_string($_GET['ranker']);
    $ranking = mysql_real_escape_string($_GET['rank']);
    $image = mysql_real_escape_string($_GET['image']);
    $voteremail = $email;
    
    //Owner of photo
    $owner = mysql_query("SELECT emailaddress FROM photos WHERE source = '$image'");
    $emailaddress = mysql_result($owner, 0, "emailaddress");
         
    //Voter information
    $voterinfo = mysql_query("SELECT reputation FROM userinfo WHERE emailaddress = '$email'");
    $reputationme = mysql_result($voterinfo,0,"reputation");
    
    if($voteremail) {
    
        $rankcheck = mysql_query("SELECT points,votes,voters FROM photos WHERE source = '$image'") or die(mysql_error());
        $prevpoints = mysql_result($rankcheck, 0, "points");
        $prevvotes = mysql_result($rankcheck, 0, "votes");
        $votecheck = mysql_result($rankcheck, 0, "voters");
        $votematch = strpos($votecheck, $email);
        
        //if the image hasn't already been voted on
        if(!$votematch && ($voteremail != $emailaddress)) {
                        
            if ($ranking >= 1 & $ranking <= 10) {  //if ranking makes sense
		
                if($reputationme > 70) {
                
                    $prevpoints+=($ranking*2.5);
                    $prevvotes+=2.5;
                    $rankquery="UPDATE photos SET points='$prevpoints', votes='$prevvotes' WHERE source='$image'";
                    mysql_query($rankquery);
                     
                }
        
                elseif($reputationme > 50 && $reputationme < 70) {
                   
                    $prevpoints+=($ranking*2.0);
                    $prevvotes+=2;
                    $rankquery="UPDATE photos SET points='$prevpoints', votes='$prevvotes' WHERE source='$image'";
                    mysql_query($rankquery); 
                    
                }
        
                elseif($reputationme > 30 && $reputationme < 50) {
                
                    $prevpoints+=($ranking*1.5);
                    $prevvotes+=1.5;
                    $rankquery="UPDATE photos SET points='$prevpoints', votes='$prevvotes' WHERE source='$image'";
                    mysql_query($rankquery); 
                    
                }
        
                elseif($reputationme < 30) {
                
                    $prevpoints+=$ranking;
                    $prevvotes+=1;
                    $rankquery="UPDATE photos SET points='$prevpoints', votes='$prevvotes' WHERE source='$image'";
                    mysql_query($rankquery);
                     
                }
                
                 //Add voter's name to database    
                $voter = "'" . $voteremail . "'";
                $voter = ", " . $voter;
                $voter = addslashes($voter);
                $votersquery = mysql_query("UPDATE photos SET voters=CONCAT(voters,'$voter') WHERE source='$image'");

            }
        }
    }
    
    //Get new ranking
    $getnewrank = mysql_query("SELECT points,votes FROM photos WHERE source = '$image'");
    $points = mysql_result($getnewrank,0,'points');
    $votes = mysql_result($getnewrank,0,'votes');
    $ranking = number_format(($points/$votes),1);
    
    echo $ranking;                

?>