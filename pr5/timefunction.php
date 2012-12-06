<?php

function converttime($time) {

$currenttime = time();
$oldtime = $time . 'this is time';

$timeago = $currenttime - $oldtime;

//time is less than 24 hours ago

    if($timeago < 3600) {
        
        $days = floor($timeago / (24*60*60));
		$timeago -= 24*60*60*$days;
    	$hours = floor($timeago / (60*60));
        $timeago -= 60*60*$hours;
		$minutes = floor($timeago / 60);
        
        if($minutes == 1) {
            $time = $minutes . " minute ago";
        }
        
        elseif($minutes > 1) {
            $time = $minutes . " minutes ago";
        }
        
        elseif($minutes < 1) {
            $time = " Just now";
        }
    
    }
    
    elseif($timeago < 86400 && $timeago > 3599) {

        $days = floor($timeago / (24*60*60));
		$timeago -= 24*60*60*$days;
    	$hours = floor($timeago / (60*60));
        
        if($hours == 1) {
            $time = $hours . " hour ago";
        }
        
        elseif($hours != 1) {
            $time = $hours . " hours ago";
        }

    }

//time is greater than 24 hours ago

    elseif($timeago > 86399) {

        $days = floor($timeago / (24*60*60));
        
        if($days == 1) {
            $time = $days . " day ago";
        }
        
        elseif($days != 1) {
            $time = $days . " days ago";
        }

    }

return $time;

}


function converttodate($time) {

$date = date("m-d-Y", $time);

return $date;

}

?>