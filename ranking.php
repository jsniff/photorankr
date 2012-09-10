<!DOCTYPE HTML>
<html>
<head>
    <title> Ranking buton </title>
<script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js'></script>    <script src="js/bootstrap.js"></script>
    <link href="css/new.css" rel="stylesheet" type="text/css">
    <link href="css/bootstrap.css" rel="stylesheet" type="text/css">
    <style type="text/css">
    </style>
    <script type="text/javascript">
$(".opt").on("change","#optDeposit", function(){
    alert("changed")


})
      
jQuery(function($){
        $('select').each(function(i, e){
                if (!($(e).data('convert') == 'no')) {
                    
            
                        //get some initial data...
                        xSelect = $(e).attr('id')
            xLabel =  $("#"+xSelect +  " option:selected").text();
            xClass =  $(e).data('class')
                    

                      
            $(e).hide().wrap('<div class="btn-group" id="select-group-' + i + '" />');
                        var select = $('#select-group-' + i);

                       
                        select.html('<a class="btn dropdown-toggle '+ xClass + '" data-toggle="dropdown" href="javascript:;">' + xLabel + ' <span class="caret"></span></a><ul class="dropdown-menu"></ul><input type="hidden" value="' + $(e).val() + '" name="' + $(e).attr('name') + '" id="' + $(e).attr('id') + '" class="' + $(e).attr('class') + '" />');
                        $(e).find('option').each(function(o,q) {
                                select.find('.dropdown-menu').append('<li><a href="javascript:;" data-title="'+ $(q).text() +'" data-value="' + $(q).attr('value') + '">' + $(q).text() + '</a></li>');
                                if ($(q).attr('selected')) select.find('.dropdown-menu li:eq(' + o + ')').click();


                        });
                        select.find('.dropdown-menu a').click(function() {
                                select.find('input[type=hidden]').live().val($(this).data('value')).change();
                                select.find('.btn:eq(0)').html($(this).text() + ' <span class="caret"></span>');
                        });
                }
        });
});
</script>
</head> 
 <body>
<div class="opt">
    <select name="optDeposit" id="optDeposit" data-class="btn btn-success">
        <option value="–">Rank</option>
        <option calue="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
        <option value="6">6</option>
        <option value="7">7</option>
        <option value="8">8</option>
        <option value="9">9</option>    
        <option value="10">10</option>
       </select>
    </div>  



</body>
</html>