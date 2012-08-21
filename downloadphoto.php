<?php

$image = trim($_POST['image']);
$image = str_replace("userphotos/","userphotos/bigphotos/", $image);
$path = $image;

$jpg = "jpg";
$jpeg = "jpeg";
$JPG = "JPG";
$png = "png";
$gif = "gif";

if (strpos($image, $jpg) || strpos($image, $jpeg)) {
	header('Content-type: image/jpeg');
	header('Content-Disposition: attachment; filename="photograph.jpg"');
	readfile($path);
}
else if (strpos($image, $png)) {
	header('Content-type: image/png');
	header('Content-Disposition: attachment; filename="photograph.png"');
	readfile($path);
}
else if (strpos($image, $jpeg)) {
	header('Content-type: image/jpeg');
	header('Content-Disposition: attachment; filename="photograph.jpeg"');
	readfile($path);
}
else if (strpos($image, $gif)) {
	header('Content-type: image/gif');
	header('Content-Disposition: attachment; filename="photograph.gif"');
	readfile($path);
}

?>