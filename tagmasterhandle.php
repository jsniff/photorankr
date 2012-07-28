<?php

require "db_connection.php";

$id = htmlentities($_GET['id']);

$tag1=mysql_real_escape_string($_POST['tag1']);		
$tag2=mysql_real_escape_string($_POST['tag2']);		
$tag3=mysql_real_escape_string($_POST['tag3']);		
$tag4=mysql_real_escape_string($_POST['tag4']);	
$singlestyletags = $_POST['singlestyletags'];	
$singlecategorytags = $_POST['singlecategorytags'];

        	//Concatenate single photo box tags
        	$numbersinglestyletags = count($singlestyletags);
    		for($i=0; $i < $numbersinglestyletags; $i++)
    		{
      			$singlestyletags2 = $singlestyletags2 . " " . mysql_real_escape_string($singlestyletags[$i]) . " ";
    		}
        	$numbersinglecategorytags = count($singlecategorytags);
    		for($i=0; $i < $numbersinglecategorytags; $i++)
    		{
       			$singlecategorytags2 = $singlecategorytags2 . " " . mysql_real_escape_string($singlecategorytags[$i]) . " ";
        	}

$query = "UPDATE photos SET tag1='$tag1', tag2='$tag2', tag3='$tag3', tag4='$tag4', singlestyletags='$singlestyletags2', singlecategorytags='$singlecategorytags2' WHERE id='$id'";
$query = mysql_query($query) or die(mysql_error());

$query = "SELECT id FROM photos WHERE id>'$id' ORDER BY id ASC LIMIT 0, 1";
$query = mysql_query($query) or die(mysql_error());
$id = mysql_result($query, 0, "id");

header("Location: tagmaster.php?id=$id");

?>