    <?php  
      

    require_once('phpthumb2/ThumbLib.inc.php');

     require "db_connection.php"; 

    $iii = 1;

$imagequery = mysql_query("SELECT source FROM photos ORDER BY id DESC LIMIT 16");
$imageSrc = mysql_result($imagequery,$iii,'source');

//echo "working";
      //echo $imageSrc;
     // $thumb = PhpThumbFactory:create($imageSrc); 
        //   echo "working";


//echo "working";

 //<img src="show_image.php?filename=sample.jpg&width=250&height=250" />
  //  header('Content-type: image/jpeg');
echo $imageSrc;
$thumb = PhpThumbFactory::create($imageSrc);  
$thumb->resize(100,100);
//header('Content-type: image/jpeg');

//$thumb->show();  
//echo "working";
     // $thumb->resize(100,100);
      //$thumb->show();  
    //  echo "working";
    ?>  