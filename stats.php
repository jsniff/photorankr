<?php

//connect to the database
require "db_connection.php";
require "functionsnav.php";

//start the session
session_start();

    // if login form has been submitted
    if (htmlentities($_GET['action']) == "login") { 
        login();
    }
    else if(htmlentities($_GET['action']) == "logout") { 
        logout();
    }

    $email = $_SESSION['email'];


    $viewsquery = mysql_query("SELECT profileviews,buyerprofileviews FROM userinfo WHERE emailaddress = '$email'");
    
    $profileviews = mysql_result($viewsquery,0,'profileviews');
    $buyerprofileviews = mysql_result($viewsquery,0,'buyerprofileviews');
    
    $selectphoto = mysql_query("SELECT source FROM photos WHERE emailaddress = '$email'");
    $numphotos = mysql_num_rows($selectphoto);
    
    for($iii=0; $iii<$numphotos; $iii++) {
         $source = mysql_result($selectphoto,$iii,'source');
         $photoviews = mysql_query("SELECT views,usermarketviews,buyermarketviews FROM photos WHERE source = '$source'");
        $totalphotoviews += mysql_result($photoviews,0,'views');
        $totalusermarketviews += mysql_result($photoviews,0,'usermarketviews');
        $totalbuyermarketviews += mysql_result($photoviews,0,'buyermarketviews');
        
    }

    $totalmarketviews = $totalusermarketviews + $totalbuyermarketviews;
    
?>

<html>
  <head>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Stat', 'View'],
          ['Total Photo Views',  <?php echo $totalphotoviews; ?>,],
          ['Market Views',  <?php echo $totalmarketviews; ?>,],
          ['Buyer Profile Views',  <?php echo $buyerprofileviews; ?>,],
          ['Photographer Profile Views',  <?php echo $profileviews; ?>,]
        ]);

        var options = {
          title: 'Your Statistics',
          hAxis: {title: 'Year', titleTextStyle: {color: 'red'}}
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>
  </head>
  <body>
    <div id="chart_div" style="width: 900px; height: 500px;"></div>
  </body>
</html>
