<?php

$doc = $_POST['doc'];
$path = $doc;

if($doc == 'legaldocs/Model_Release_6_24_2012.pdf') {
header('Content-type: application/pdf');
header('Content-Disposition: attachment; filename="PhotoRankr Model Release"');
readfile($path);
}

else {
header('Content-type: application/pdf');
header('Content-Disposition: attachment; filename="PhotoRankr Property Release"');
readfile($path);
}


?>