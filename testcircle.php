<!DOCTYPE html>
 <html lang="en">
 <head>
   <meta charset="utf-8">
   <title>jQuery demo</title>

<style>
.jCProgress {
     position: absolute;
     z-index: 9999999;
     /*  margin-top:-15px; /* offset from the center */
}

.jCProgress > div.percent {
     font: 18px/27px 'BebasRegular', Arial, sans-serif;
     color:#ebebeb;
     text-shadow: 1px 1px 1px #1f1f1f;

     position:absolute;
     margin-top:40px;
     margin-left:22px;
     text-align: center;
     width:60px;
}
</style>

 </head>
 <body>
   <a href="http://jquery.com/">jQuery</a>
   <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
   <script>
     $(document).ready(function(){
       $("a").click(function(event){
         alert("As you can see, the link no longer took you to jquery.com");
         event.preventDefault();
       });
     });
   </script>
 </body>
 </html>