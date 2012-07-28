<?php

$maintagsarray  = explode("  ", $maintags);
$settagsarray   = explode("  ", $settags);
$singlestyletagsarray  = explode("  ", $singlestyletags);
$singlecategorytagsarray   = explode("  ", $singlecategorytags);

echo 'Tags:';

for($iii=0; $iii < count($maintagsarray); $iii++) {
	echo '<a href="search.php?searchterm=', $maintagsarray[$iii], '">', $maintagsarray[$iii], '</a><br />';
}
for($iii=0; $iii < count($settagsarray); $iii++) {
	echo '<a href="search.php?searchterm=', $settagsarray[$iii], '">', $settagsarray[$iii], '</a><br />';
}
for($iii=0; $iii < count($singlestyletagsarray); $iii++) {
	echo '<a href="search.php?searchterm=', $singlestyletagsarray[$iii], '">', $singlestyletagsarray[$iii], '</a><br />';
}
for($iii=0; $iii < count($singlecategorytagsarray); $iii++) {
	echo '<a href="search.php?searchterm=', $singlecategorytagsarray[$iii], '">', $singlecategorytagsarray[$iii], '</a><br />';
}

?>