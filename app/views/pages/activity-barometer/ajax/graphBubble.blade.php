<article class="col-sm-12 col-md-6 col-lg-6">

	<div class="jarviswidget jarviswidget-color-darken" data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false" data-widget-collapsed="false">

		<header>

			<span class="widget-icon"> <i class="fa fa-table"></i> </span>

			<h2>Frequency</h2>

		</header>

		<div>

			<div class="jarviswidget-editbox">

			</div>

			<div class="alert alert-info">

				No of members swimming in each frequency (<?php echo date("M Y", strtotime($end_date)); ?>)

			</div>

			<div class="widget-body">

				<div id="graph_freq" style="width: 100%; height: 300px;"></div>

			</div>

		</div>

	</div>

</article>

<article class="col-sm-12 col-md-6 col-lg-6">

	<div class="jarviswidget jarviswidget-color-darken" data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false" data-widget-collapsed="false">

		<header>

			<span class="widget-icon"> <i class="fa fa-table"></i> </span>

			<h2>Consistency</h2>

		</header>

		<div>

			<div class="jarviswidget-editbox">

			</div>

			<div class="alert alert-info">

				No of members swimming consistently for the last 3 months (<?php echo date("M", strtotime($start_date))." to ".date("M Y", strtotime($end_date)); ?>)

			</div>

			<div class="widget-body">

				<div id="graph_cons" style="width: 100%; height: 300px;"></div>

			</div>

		</div>

	</div>

</article>

<article class="col-sm-12 col-md-12 col-lg-12">

	<div class="jarviswidget jarviswidget-color-darken" data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false" data-widget-collapsed="false">

		<header>

			<span class="widget-icon"> <i class="fa fa-table"></i> </span>

			<h2>Participation Matrix (<?php echo date("F", strtotime($start_date))." to ".date("F Y", strtotime($end_date)); ?>)</h2>

		</header>

		<div>

			<div class="jarviswidget-editbox">

			</div>

			<div class="widget-body">

				<button class="btn bg-color-blueDark txt-color-white" id="b1"> Select/Deselect Sites</button>

				<div id="graph1" style="width: 100%; height: 700px;"></div>

			</div>

		</div>

	</div>

</article>

<div id="detailedgraph"></div>

<script type="text/javascript">

	$(function () {

		$('#graph_freq').highcharts({

			chart: {

				type: 'bar'

			},

			title: {

				text: ''

			},

			subtitle: {

				text: ''

			},

			xAxis: {

				title:{

					text:"Visit Count"

				},

				categories: [

				<?php

				foreach ($frequency[date("Y-m", strtotime($end_date))] as $key => $value)

				{

					echo "'" . $key . "',";

				}

				?>

				]

			},

			yAxis: {

				allowDecimals:false,

				title:{

					text:"Member Count"

				},

			},

			legend: {

				enabled: false

			},

			tooltip: {

				formatter: function() {

					return '<span style="font-size:10px">'+Highcharts.numberFormat(this.y, 0,'.',',')+'</span><table><tr><td style="color:'+this.color+';padding:0">'+this.series.name+': </td>' +

					'<td style="padding:0"><b>'+ this.key +'</b></td></tr></table>';

				},

				shared: false,

				useHTML: true

			},

			plotOptions: {

				bar: {

					shadow: true,

					pointPadding: 0.2,

					borderWidth: 0,

					dataLabels: {

						enabled: true,

						formatter:function() {

							return Highcharts.numberFormat(this.y,0,'.',',');

						},

						//rotation: -90,

						color: '#FFFFFF',

						align: 'right',

						x: 0,

						y: 0,

						style: {

							fontSize: '10px',

							fontFamily: 'Verdana, sans-serif',

						}

					}

				}

			},

			series: [{

				name: 'Frequency',

				data: [

				<?php

				foreach ($frequency[date("Y-m", strtotime($end_date))] as $value) 

				{

					echo $value . ",";

				}

				?>

				]

			}]

		});

$('#graph_cons').highcharts({

	chart: {

		type: 'bar',

	},

	title: {

		text: ''

	},

	subtitle: {

		text: ''

	},

	navigation: {

		buttonOptions: {

			align: 'left'

		}

	},

	xAxis: {

		title:{

			text:"Visit Count"

		},

		opposite: true,

		reversed: true,

		categories: [

		<?php

		foreach ($consistency as $key => $value)

		{

			echo "'" . $key . "',";

		}

		?>

		]

	},

	yAxis: {

		reversed: true,

		allowDecimals:false,

		title:{

			text:"Member Count"

		},

	},

	legend: {

		enabled: false

	},

	tooltip: {

		formatter: function() {

			return '<span style="font-size:10px">'+Highcharts.numberFormat(this.y, 0,'.',',')+'</span><table><tr><td style="color:'+this.color+';padding:0">'+this.series.name+': </td>' +

			'<td style="padding:0"><b>'+this.key+'</b></td></tr></table>';

		},

		shared: false,

		useHTML: true

	},

	plotOptions: {

		bar: {

			shadow: true,

			pointPadding: 0.2,

			borderWidth: 0,

			dataLabels: {

				enabled: true,

				formatter:function() {

					return Highcharts.numberFormat(this.y,0,'.',',');

				},

						//rotation: -90,

						color: '#FFFFFF',

						align: 'left',

						x: 0,

						y: 0,

						style: {

							fontSize: '10px',

							fontFamily: 'Verdana, sans-serif',

						}

					}

				}

			},

			series: [{

				name: 'Consistency',

				data: [

				<?php

				foreach ($consistency as $value) 

				{

					echo $value . ",";

				}

				?>

				]

			}]

		});

$('#graph1').highcharts({

	chart: {

		type: 'scatter',

		zoomType: 'xy'

	},

	legend: {

		enabled: true

	},

	title: {

		text: "Member visits % vs. Throughput",

		useHTML : true

	},

	plotOptions: {

		series: {

			cursor: 'pointer',

			point: {

				events: {

					click: function (e) 

					{

						var siteID = e.point.series.userOptions.id;

						var siteName = e.point.series.userOptions.name;

						

						$.ajax({

							url: '{{ route("activity-barometer.postDetailedGraphBubble") }}',

							type: 'POST',

							data :

							{

								'sector_hc'				: "<?php echo $sector_hc; ?>",	

								'sector_rv'				: "<?php echo $sector_rv; ?>",	

								'siteID'               	: siteID,

								'siteName'             	: siteName

							},

							beforeSend: function(request) {

								return request.setRequestHeader("X-CSRF-Token", $("meta[name='token']").attr('content'));

							},

							success: function(data) 

							{

								$('#detailedgraph').html(data);

								$.smallBox({

									title : "Success",

									content : "<i class='fa fa-clock-o'></i> <i>The graph has been successfully generated!</i>",

									color : "#659265",

									iconSmall : "fa fa-check fa-2x fadeInRight animated",

									timeout : 3000

								});

							}

						});

					}

				}

			}

		},

		scatter: {

			marker: {

				radius: 6,

			},

			tooltip: {

				useHTML : true,

				headerFormat: '<b>{series.name}</b><br>',

				pointFormat: 'Group Workout Throughput: {point.x:,.0f}, Visits by members: {point.y:,.0f}%'

			}

		}

	},

	yAxis: {

		title: 

		{

			text: 'Visits by members',

		},

		plotLines: [{

			color: 'blue',

			width: 2,

			value: <?php echo number_format($sector_hc,0,"","");?>,

			dashStyle: 'longdashdot',

			label : {

				text : 'Avg. Visits by members: <?php echo number_format($sector_hc,0);?>%'

			}

		}],

		min: 0,

		<?php 

		if (count($sites) == 1) {

			$sites_temp = $sites;

			$site_id = array_shift($sites);

			$sites = $sites_temp;

			$max = ($sector_hc * 2 < $sites_hc[$site_id] * 2) ? $sites_hc[$site_id] * 2 : $sector_hc * 2; 

			echo "max: " . number_format($max, 0, "", "");

		}

		else

		{

			echo "max: 100";

		}

		?>

	},

	xAxis: {

		title: 

		{

			text: 'Group Workout Throughput',

		},

		plotLines: [{

			color: 'blue',

			width: 2,

			value: <?php echo number_format($sector_rv/count($sites),0,"","");?>,

			dashStyle: 'longdashdot',

			label : {

				text : 'Avg. Group Workout Throughput: <?php echo number_format($sector_rv/count($sites),0);?>',

				useHTML : true

			}

		}],

		min: 0,

		<?php 

		if (count($sites) == 1) {

			$sites_temp = $sites;

			$site_id = array_shift($sites);

			$sites = $sites_temp;

			$max = ($sector_rv * 2 < $sites_rv[$site_id] * 2) ? $sites_rv[$site_id] * 2 : $sector_rv * 2; 

			echo "max: " . number_format($max, 0, "", "");

		}
		else

		{

			echo "max: 14000";

		}

		?>

	},

	series: [

	<?php

	$counter = 1;

	foreach ($sites as $key => $value) 

	{

		if(Auth::user()->demo)

		{

			$key = "Demo Leisure Centre ".$counter;

		}

		?>

		{

			name: <?php echo '"'.str_replace("'","`",$key).'"';?>,

			id: '<?php echo $value; ?>',

			data: [[<?php echo number_format($sites_rv[$value], 0, '', '');?>,<?php echo number_format($sites_hc[$value], 0, '', '');?>]]

		},

		<?php

		$counter++;

	}

	?>

	]

});

$("#b1").click(function() {

	var chart = $('#graph1').highcharts();

	var series = chart.series;

	var count = series.length

	

	for (var i = 0; i < count; i++) 

	{

		if(series[i].visible) 

		{

			series[i].hide();

		} 

		else 

		{

			series[i].show();

		}

	};

});

});

</script>

@include('templates.widget')

