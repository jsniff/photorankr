
<?php



require "db_connection.php";
require "functions.php";




    $imageid = '500';
    $imagequery = mysql_query("SELECT source FROM photos WHERE id = '$imageid'");
    $image = mysql_result($imagequery,0,'source');


	$image_array = getimagesize($image);
	$cwidth = $image_array[0];
	$cheight = $image_array[1];
	$max_height = "200";
	if($cheight > $max_height)
	{				
		$image_perc = ($max_height * 100)/$cheight;
		$nwidth = ($cwidth * $image_perc)/100;
		$cheight = $max_height;
		$cwidth = ceil($nwidth);
	}
	echo '<img src="'.$image.'" height="'.$cheight.'" width="'.$cwidth.'" />';

?>