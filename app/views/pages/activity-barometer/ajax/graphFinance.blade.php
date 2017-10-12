<div class="row">

	<!-- Widget ID (each widget will need unique ID)-->

	<div class="jarviswidget jarviswidget-color-darken" data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false" data-widget-collapsed="false">

		<header>

			<span class="widget-icon"> <i class="fa fa-table"></i> </span>

			<h2>Leisure Centre (LC) vs Sector Average (SA)</h2>

		</header>

		<!-- widget div-->

		<div>

			<!-- widget edit box -->

			<div class="jarviswidget-editbox">

				<!-- This area used as dropdown edit box -->

			</div>

			<!-- end widget edit box -->

			<!-- widget content -->

			<div class="widget-body">

				<div id="stock_graph" style="width: 100%; height: 500px;"></div>

			</div>

			<!-- end widget content -->

		</div>

		<!-- end widget div -->

	</div>

	<!-- end widget -->

</div>

<script type="text/javascript">



	$(function() {

		window.chart = new Highcharts.StockChart({

			chart : {

				renderTo : 'stock_graph'

			},

			title : {

				text : '<?php echo $sport_name; ?>'

			},

			rangeSelector : {

				selected : 4

			},

			series : [{

				name : 'Sector',

				type : 'area',

				fillColor : {

                    linearGradient : {

                        x1: 0,

                        y1: 0,

                        x2: 0,

                        y2: 1

                    },

                    stops : [

                        [0, Highcharts.getOptions().colors[0]],

                        [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]

                    ]

                },

				data : [

				<?php  

				for ($i=0; $i < count($dates); $i++) 

				{ 

					?>

					[<?php 

					echo $dates[$i]."000"; 

					?>, 

					<?php 

					$print = (Str::startsWith( Auth::user()->email, 'demo' )) ? $print = $graph_sector[$dates[$i]] * (rand(7, 14) / 10) : $graph_sector[$dates[$i]];

					echo number_format($print,2); 

					?>],

					<?php  

				}

				?>

				]

			},

			{

				name : 'Sites',

				data : [

				<?php  

				for ($i=0; $i < count($dates); $i++) 

				{ 

					?>

					[<?php echo $dates[$i]."000"; ?>, <?php echo number_format($graph_sites[$dates[$i]],2); ?>],

					<?php  

				}

				?>

				]

			}]

		});

	});



</script>

@include('templates.widget')