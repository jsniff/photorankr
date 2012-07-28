<html>


<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>


<meta charset="utf-8">
	<body>
	
<meta charset="utf-8">
	
	
	
	
	
	
	<form id=form1 action="Marketplace.php?action=searchandfind" method="POST" >

	<input type="text" style="width:150px;border-color:#fff;background-color:#fff;margin-left:20px;" class="search-query" name="searchterm" placeholder="Search">

	
	<style>
	#demo-frame > div.demo { padding: 10px !important; };
	</style>
					<input id= "values" type="hidden" name="column" value="";>
					<input id= "values2" type="hidden" name="column2" value="";>

					<input id= "reputationvalues" type="hidden" name="reputationcolumn" value="";>
					<input id= "reputationvalues2" type="hidden" name="reputationcolumn2" value="";>


						<input id= "rankingvalues" type="hidden" name="rankingcolumn" value="";>
					<input id= "rankingvalues2" type="hidden" name="rankingcolumn2" value="";>


						<input id= "downloadvalues" type="hidden" name="downloadcolumn" value="";>
					<input id= "downloadvalues2" type="hidden" name="downloadcolumn2" value="";>





	<script>

	$(function() {
		$( "#slider-range" ).slider({

			range: true,
			min: 0,
			max: 500,
			values: [ 75, 300 ],
			slide: function( event, ui ) {
				$( "#amount" ).val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );
						$( "#values" ).val(ui.values[ 0 ]);
						$( "#values2" ).val(ui.values[ 1 ]);

			}
		});

		$( "#amount" ).val( "$" + $( "#slider-range" ).slider( "values", 0 ) +
			" - $" + $( "#slider-range" ).slider( "values", 1 ) );

		$( "#column" ).val($( "#slider-range" ).slider( "values", 0 ));

				$( "#column2" ).val($( "#slider-range" ).slider( "values", 1 ));



	});
	</script>





	



<div class="demo">

<p>
	<label for="amount">Price range:</label>
	<input type="text" id="amount" style="border:0; color:#f6931f; font-weight:bold;" />
</p>




<div id="slider-range"></div>



</div><!-- End demo -->










<script>

	$(function() {
		$( "#slider-range2" ).slider({

			range: true,
			min: 0,
			max: 100,
			values: [ 10, 70 ],
			slide: function( event, ui ) {
				$( "#amount2" ).val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );
						$( "#reputationvalues" ).val(ui.values[ 0 ]);
						$( "#reputationvalues2" ).val(ui.values[ 1 ]);

			}
		});

		$( "#amount2" ).val( "$" + $( "#slider-range2" ).slider( "values", 0 ) +
			" - $" + $( "#slider-range2" ).slider( "values", 1 ) );

	
		$( "#repuationcolumn" ).val($( "#slider-range2" ).slider( "values", 0 ));

				$( "#reputationcolumn2" ).val($( "#slider-range2" ).slider( "values", 1 ));



	});
	</script>





<div class="demo2">

<p>
	<label for="amount2">Reputation range:</label>
	<input type="text" id="amount2" style="border:0; color:#f6931f; font-weight:bold;" />
</p>



<div id="slider-range2"></div>



</div><!-- End demo -->






<script>

	$(function() {
		$( "#slider-range3" ).slider({

			range: true,
			min: 0,
			max: 10,
			values: [ 2, 8 ],
			slide: function( event, ui ) {
				$( "#amount3" ).val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );
						$( "#rankingvalues" ).val(ui.values[ 0 ]);
						$( "#rankingvalues2" ).val(ui.values[ 1 ]);

			}
		});

		$( "#amount3" ).val( "$" + $( "#slider-range3" ).slider( "values", 0 ) +
			" - $" + $( "#slider-range2" ).slider( "values", 1 ) );

	
		$( "#rankingcolumn" ).val($( "#slider-range3" ).slider( "values", 0 ));

				$( "#rankingcolumn2" ).val($( "#slider-range3" ).slider( "values", 1 ));



	});
	</script>





<div class="demo3">

<p>
	<label for="amount3">Ranking range:</label>
	<input type="text" id="amount3" style="border:0; color:#f6931f; font-weight:bold;" />
</p>



<div id="slider-range3"></div>



</div><!-- End demo -->









<script>

	$(function() {
		$( "#slider-range4" ).slider({

			range: true,
			min: 0,
			max: 10000,
			values: [ 65, 5500],
			slide: function( event, ui ) {
				$( "#amount4" ).val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );
						$( "#downloadvalues" ).val(ui.values[ 0 ]);
						$( "#downloadvalues2" ).val(ui.values[ 1 ]);

			}
		});

		$( "#amount4" ).val( "$" + $( "#slider-range4" ).slider( "values", 0 ) +
			" - $" + $( "#slider-range4" ).slider( "values", 1 ) );

	
		$( "#downloadcolumn" ).val($( "#slider-range4" ).slider( "values", 0 ));

				$( "#downloadcolumn2" ).val($( "#slider-range4" ).slider( "values", 1 ));



	});
	</script>





<div class="demo4">

<p>
	<label for="amount4">Download range:</label>
	<input type="text" id="amount4" style="border:0; color:#f6931f; font-weight:bold;" />
</p>




<div id="slider-range4"></div>



</div><!-- End demo -->












<table id="myTable" border=1>
<input type="submit" value="Search" />







</form>





</body>






</html>