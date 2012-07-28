<?php  

require "db_connection.php";

$id=htmlentities($_GET['id']);

$queryrun = "SELECT * FROM photos WHERE id='$id' LIMIT 0, 1";
$query = mysql_query($queryrun);

    $source = mysql_result($query, 0, "source");
    $id = mysql_result($query, 0, "id");
    $tag1 = mysql_result($query, 0, "tag1");
    $tag2 = mysql_result($query, 0, "tag2");
    $tag3 = mysql_result($query, 0, "tag3");
    $tag4 = mysql_result($query, 0, "tag4");
    $singlestyle = mysql_result($query, 0, "singlestyletags");
    $singlecategory = mysql_result($query, 0, "singlecategorytags");

    echo '<html>
    <h1>TAG MASTER 3000</h1>
    <h4><i>The Latest in Tagging Technology--coming to a mobile phone near you soon</i></h4>
    <form action="tagmasterhandle.php?id=', $id, '" method="post">';
    echo '<div style="height: 200px;"><img width="500px;" style="float:left;" src="',$source,'" /><br />
    <input style="width:180px;height:25px;" type="text" name="tag1" value="', $tag1, '" />
    <input style="width:180px;height:25px;" type="text" name="tag2" value="', $tag2, '" />
    <input style="width:180px;height:25px;" type="text" name="tag3" value="', $tag3, '" />
    <input style="width:180px;height:25px;" type="text" name="tag4" value="', $tag4, '" />
    
    <select style="width:150px; height: 150px;" multiple="multiple" name="singlestyletags[]">
    <option value="B&W">Black and White</option>
    <option value="Cityscape">Cityscape</option>
    <option value="Fisheye">Fisheye</option>
    <option value="HDR">HDR</option>
    <option value="Illustration">Illustration</option>
    <option value="InfraredUV">Infrared/UV</option>
    <option value="Landscape">Landscape</option>
    <option value="Long Exposure">Long Exposure</option>
    <option value="Macro">Macro</option>
    <option value="Miniature">Miniature</option>
    <option value="Monochrome">Monochrome</option>
    <option value="Motion Blur">Motion Blur</option>
    <option value="Night">Night</option>
    <option value="Panorama">Panorama</option>
    <option value="Photojournalism">Photojournalism</option>
    <option value="Portrait">Portrait</option>
    <option value="Stereoscopic">Stereoscopic</option>
    <option value="Time Lapse">Time Lapse</option>
    </select>
    <span style="padding-left:70px">
    <select style="width:150px; height: 150px;" multiple="multiple" name="singlecategorytags[]">
    <option value="Advertising">Advertising</option>
    <option value="Aerial">Aerial</option>
    <option value="Animal">Animal</option>
    <option value="Architecture">Architecture</option>
    <option value="Astro">Astro</option>
    <option value="Aura">Aura</option>
    <option value="Automotive">Automotive</option>
    <option value="Candid">Candid</option>
    <option value="Commercial">Commercial</option>
    <option value="Corporate">Corporate</option>
    <option value="Documentary">Documentary</option>
    <option value="Fashion">Fashion</option>
    <option value="Fine Art">Fine Art</option>
    <option value="Food">Food</option>
    <option value="Historical">Historical</option>
    <option value="Industrial">Industrial</option>
    <option value="Musical">Musical</option>
    <option value="Nature">Nature</option>
    <option value="News">News</option>
    <option value="Night">Night</option>
    <option value="People">People</option>
    <option value="Scenic">Scenic</option>
    <option value="Sports">Sports</option>
    <option value="Still Life">Still Life</option>
    <option value="Transportation">Transportation</option>
    <option value="Urban">Urban</option>
    <option value="War">War</option>
    </select>
    </span>
    <br /><br /></div>';


echo '<input type="submit" value="submit" /></form>';

mysql_close();

?>

</html>