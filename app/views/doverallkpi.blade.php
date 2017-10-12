@extends('templates.default')

@section('breadcrumbs')

{{ Helper::breadcrumbs('Dashboards', 'KPI Dashboard') }}

@stop

@section('left_side')

{{ Helper::left_side('Dashboards', 'KPI Dashboard') }}

@stop

@section('content')

<?php

error_reporting(0);



if(Auth::user()->demo)

{

	$r = number_format(rand(70,160) / 100,1);

}

else

{

	$r = 1;

}



$sport = Auth::user()->sport;



$filter1_data = UserPreferences::find(Auth::user()->id."1")->filters;

$filter1_decoded = $filter1_data;

$filter1_decoded = json_decode($filter1_decoded,true);

$filter1_name = $filter1_decoded["filter_name"];

$filter2_data = UserPreferences::find(Auth::user()->id."2")->filters;

$filter2_decoded = $filter2_data;

$filter2_decoded = json_decode($filter2_decoded,true);

$filter2_name = $filter2_decoded["filter_name"];

$filter3_data = UserPreferences::find(Auth::user()->id."3")->filters;

$filter3_decoded = $filter3_data;

$filter3_decoded = json_decode($filter3_decoded,true);

$filter3_name = $filter3_decoded["filter_name"];



if($filter1_data == null)

{

	$url = URL::asset('filters?filled');

	header('Location: '.$url);

	die();

}

if(Session::has('filters'))

{

	$filter_data = Session::get('filters');

}

else

{

	$filter_data = $filter1_data;

	Session::put('filters', $filter1_data);

	Session::put('filter_name', $filter1_name);

}



$data_to_decode = $filter_data;

$values = GeneralFunctions::arrange_filters($filter_data);

$person_type = $values['person_type'];

$gender = $values['gender'];

$age = $values['age'];

$programme = $values['programme'];

$date_end_current = $values['date_end_current'];

$date_start_last = $values['date_start_last'];

$date_end_last = $values['date_end_last'];

$date_start_current = $values['date_start_current'];

$c_year = $values['c_year'];

$l_year = $values['l_year'];

$c_month = $values['c_month'];

$c_month_ajax = $values['c_month_ajax'];

$c_month_int_ajax = $values['c_month_int_ajax'];

$l_month = $values['l_month'];

$c_month_text = $values['c_month_text'];

$l_month_text = $values['l_month_text'];

$sites = $values["lcs"];



$start_of_churn_date = date_create($date_end_current.' last day of -3 month')->format('Y-m-01 00:00:00');

$end_of_churn_date = date_create($date_end_current.' last day of -1 month')->format('Y-m-d 23:59:59');

$start_date_mb = date_create($date_end_current.' last day of this month')->format('Y-m-01 00:00:00');



list($unique_lcs, $lcs, $tab1, $tab4, $get_all_last, $get_all_current,$churn_current,$churn_last) = QueryCacher::query_dashboard($person_type,$gender,$age,$programme,$date_end_current,$date_start_last,$date_end_last,$date_start_current,$start_of_churn_date,$end_of_churn_date,$start_date_mb,$sites);



$lc_count = count($lcs);

foreach ($tab1 as $key => $value) 

{

	$t1[$value->period][$value->PersonType] = $value->sum * $r;

	if($value->PersonType == 2)

	{

		$t7[$value->period] = $value->count * $r;

	}

}

foreach ($tab4 as $key => $value) 

{

	$t4[$value->period] = $value->sum * $r;

}

/**

* GRAPH

*/

foreach ($get_all_last as $value)

{

	$totals_last[number_format($value->date,0,"","")][] = $value->count * $r;

}

//calculating counts for graph tooltip

for ($i=1; $i <= 12 ; $i++) 

{

	$totals_for_graph_last[date('M', mktime(0, 0, 0, $i, 10))] = number_format(count($totals_last[$i]),0,"","");

}

foreach ($get_all_current as $value)

{

	$totals_current[number_format($value->date,0,"","")][] = $value->count * $r;

}

//calculating counts for graph tooltip

for ($i=1; $i <= 12 ; $i++) 

{

	$totals_for_graph_current[date('M', mktime(0, 0, 0, $i, 10))] = number_format(count($totals_current[$i]),0,"","");

}

?>

<div id="widget-grid">

	<div class="row">

		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

			<div class="jarviswidget" data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-collapsed="false">

				<header>

					<span class="widget-icon"> <!-- <i class="fa fa-graph fa-spin"></i> -->

					</span>

					<h2><!-- text --></h2>

					<ul class="nav nav-tabs pull-right in">

						<li class="active">

							<a>

								<i class="fa fa-bar-chart-o">

								</i>

								<span class="hidden-mobile hidden-tablet">

									All

								</span>

							</a>

						</li>

					</ul>

				</header>

				<div class="no-padding">

					<div class="jarviswidget-editbox">

					</div>

					<div class="widget-body">

						<!-- content -->

						<div class="tab-content">

							<div class="tab-pane fade active in padding-10 no-padding-bottom">

								<div class="row">

									<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">

										<div id="graph_1" style="height: 338px; width: 100%">                           

											<div class="loading">

												<i class="fa fa-spinner fa-spin"></i>

												Drawing Graph...

											</div>

										</div>

										<div class="alert alert-info fade in" style="margin: 0px;">

											<button class="close" data-dismiss="alert">

												×

											</button>

											<p><i class="fa-fw fa fa-info"></i><strong>Info!</strong> This map display the sector view for selected filters except the location filter.</p>

										</div>



									</div>

									<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 show-stats">

										<div class="row">

											<div class="table-responsive">

												<table class="table table-hover">

													<thead>

														<tr>

															<th>Summary KPIs - <?php echo $lc_count; ?> Sites</th>

															<th><?php echo $l_month_text; ?></th>

															<th><?php echo $c_month_text; ?></th>

														</tr>

													</thead>

													<tbody>

														<tr>

															<?php

															$green = "#EBF1DE";

															$yellow = "#FDF5E6";

															$red = "#FFC9C9";

															if($t1[$l_month][1] < $t1[$c_month][1])

															{

																$color = $green;

															}

															else

															{

																$color = $red;

															}

															?>

															<td><span rel="tooltip" data-placement="left" data-original-title="Total number of visits by non-members." data-html="true">Casual visits</span></td>

															<td align="right"><?php echo number_format($t1[$l_month][1],0); ?></td>

															<td style="background: <?php echo $color; ?>" align="right"><?php echo number_format($t1[$c_month][1],0); ?></td>

														</tr>

														<tr>

															<?php

															if($t1[$l_month][2] < $t1[$c_month][2])

															{

																$color = $green;

															}

															else

															{

																$color = $red;

															}

															?>

															<td><span rel="tooltip" data-placement="left" data-original-title="Total number of visits by paid members." data-html="true">Member visits</span></td>

															<td align="right"><?php echo number_format($t1[$l_month][2],0); ?></td>

															<td style="background: <?php echo $color; ?>" align="right"><?php echo number_format($t1[$c_month][2],0); ?></td>

														</tr>

														<tr>

															<?php

															if($t1[$l_month][1] + $t1[$l_month][2] < $t1[$c_month][1] + $t1[$c_month][2])

															{

																$color = $green;

															}

															else

															{

																$color = $red;

															}

															?>

															<td><span rel="tooltip" data-placement="left" data-original-title="Total number of visits by all people." data-html="true">Total visits</span></td>

															<td align="right"><?php echo number_format($t1[$l_month][1] + $t1[$l_month][2],0); ?></td>

															<td style="background: <?php echo $color; ?>" align="right"><?php echo number_format($t1[$c_month][1] + $t1[$c_month][2],0); ?></td>

														</tr>

														<tr>

															<?php

															if($t7[$l_month] < $t7[$c_month])

															{

																$color = $green;

															}

															else

															{

																$color = $red;

															}

															?>

															<td><span rel="tooltip" data-placement="left" data-original-title="Number of unique members attending in swimming." data-html="true">Unique member count</span></td>

															<td align="right"><?php echo number_format($t7[$l_month],0); ?></td>

															<td style="background: <?php echo $color; ?>" align="right"><?php echo number_format($t7[$c_month],0); ?></td>

														</tr>

														<tr>

															<?php

															if($t1[$l_month][2] / ($t1[$l_month][1] + $t1[$l_month][2]) < $t1[$c_month][2] / ($t1[$c_month][1] + $t1[$c_month][2]))

															{

																$color = $green;

															}

															else

															{

																$color = $red;

															}

															?>

															<td><span rel="tooltip" data-placement="left" data-original-title="Percentage of visits by members." data-html="true">Percentage of visits by members</span></td>

															<td align="right"><?php echo number_format(($t1[$l_month][2] / ($t1[$l_month][1] + $t1[$l_month][2]) * 100),1); ?>%</td>

															<td  style="background: <?php echo $color; ?>" align="right"><?php echo number_format(($t1[$c_month][2] / ($t1[$c_month][1] + $t1[$c_month][2]) * 100),1); ?>%</td>

														</tr>

														<tr>

															<?php

															if($t4[$l_month] / $t7[$l_month] < $t4[$c_month] / $t7[$c_month])

															{

																$color = $green;

															}

															else

															{

																$color = $red;

															}

															?>

															<td><span rel="tooltip" data-placement="left" data-original-title="The percentage of the member visitors that visited 4 or more times in the month." data-html="true">Weekly participation % (members)</span></td>

															<td align="right"><?php echo number_format($t4[$l_month] / $t7[$l_month] * 100,1) ?>%</td>

															<td style="background: <?php echo $color; ?>"  align="right"><?php echo number_format($t4[$c_month] / $t7[$c_month] * 100,1) ?>%</td>

														</tr>

														<tr>

															<?php

															if(($churn_last[0] - $churn_last[1]) / $churn_last[0] > ($churn_current[0] - $churn_current[1]) / $churn_current[0])

															{

																$color = $green;

															}

															else

															{

																$color = $red;

															}

															?>

															<td><span rel="tooltip" data-placement="left" data-original-title="Weekly participants (from previous 3 months) lost in last month." data-html="true">Weekly participation members lost %</span></td>

															<td align="right"><?php echo number_format(($churn_last[0] - $churn_last[1]) / $churn_last[0] * 100,1); ?>%</td>

															<td style="background: <?php echo $color; ?>"  align="right"><?php echo number_format(($churn_current[0] - $churn_current[1]) / $churn_current[0] * 100,1); ?>%</td>

														</tr>

													</tbody>

												</table>

											</div>

										</div>

									</div>

								</div>

							</div>

						</div>

					</div>

				</div>

			</div>

		</article>

	</div>                 

	<div class="row">

		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

			<div class="jarviswidget" data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-collapsed="false">

				<header>

					<span class="widget-icon"> <i class="fa fa-circle-o-notch fa-spin"></i>

					</span>

					<h2>Overall KPIs</h2>

					<ul class="nav nav-tabs pull-right in">

						<li class="active">

							<a data-toggle="tab" data-d="click" data-track="1" href="#s1"><i class="fa fa-plus"></i><span class="hidden-mobile hidden-tablet"> Casual visits</span></a>

						</li>

						<li>

							<a data-toggle="tab" data-d="click" data-track="2" href="#s2"><i class="fa fa-plus"></i> <span class="hidden-mobile hidden-tablet">Member visits</span></a>

						</li>

						<li>

							<a data-toggle="tab" data-d="click" data-track="3" href="#s3"><i class="fa fa-plus"></i> <span class="hidden-mobile hidden-tablet"> Total visits</span></a>

						</li>

						<li>

							<a data-toggle="tab" data-d="click" data-track="7" href="#s7"><i class="fa fa-plus"></i> <span class="hidden-mobile hidden-tablet"> Unique members</span></a>

						</li>

						<li>

							<a data-toggle="tab" data-d="click" data-track="4" href="#s4"><i class="fa fa-plus"></i> <span class="hidden-mobile hidden-tablet"> Participation % </span></a>

						</li>

						<li>

							<a data-toggle="tab" data-d="click" data-track="5" href="#s5"><i class="fa fa-plus"></i> <span class="hidden-mobile hidden-tablet"> % members lost</span></a>

						</li>

						<li>

							<a data-toggle="tab" data-d="click" data-track="6" href="#s6"><i class="fa fa-plus"></i> <span class="hidden-mobile hidden-tablet"> % visits by members</span></a>

						</li>

					</ul>

				</header>

				<div class="no-padding">

					<div class="jarviswidget-editbox">

					</div>

					<div class="widget-body">

						<div id="output"></div>

					</div>

				</div>

			</div>

		</article>

	</div>

	<div class="btn-group btn-group-justified">

		<?php 

		$filter_name = Session::get('filter_name');

		?>

		<a <?php if($filter1_data == null){echo "disabled='disabled'";} ?> id="filter_1" data-name="<?php echo $filter1_name;?>" class="btn bg-color-green txt-color-white"><i class="fa <?php if($filter_name == $filter1_name){echo "fa-arrow-right";}else{echo "fa-gear";}; ?>"></i> <?php echo $filter1_name;?></a>

		<a <?php if($filter2_data == null){echo "disabled='disabled'";} ?> id="filter_2" data-name="<?php echo $filter2_name;?>" class="btn bg-color-red txt-color-white"><i class="fa <?php if($filter_name == $filter2_name){echo "fa-arrow-right";}else{echo "fa-gear";}; ?>"></i> <?php echo $filter2_name;?></a>

		<a <?php if($filter3_data == null){echo "disabled='disabled'";} ?> id="filter_3" data-name="<?php echo $filter3_name;?>" class="btn bg-color-blue txt-color-white"><i class="fa <?php if($filter_name == $filter3_name){echo "fa-arrow-right";}else{echo "fa-gear";}; ?>"></i> <?php echo $filter3_name;?></a>

		<a data-toggle="modal" data-target="#myModal_custom" class="btn bg-color-blueDark txt-color-white"><i class="fa <?php if($filter_name == "custom"){echo "fa-arrow-right";}else{echo "fa-gear";}; ?>"></i> Applied Filters</a>

	</div>

</div>

<?php

echo View::make('modal.modal',array('data_model' => 'myModal_custom', 'color' => 'black', 'disabled' => "", 'data' => $data_to_decode, 'person_count' => Session::get('person_count'), 'age_count' => Session::get('age_count'), 'gender_count' => Session::get('gender_count')));

?>

@stop

@section('pr-css')

@stop

@section('pr-scripts')

@stop

@section('scripts')

<script type="text/javascript">

	$(document).ready(function() 

	{

		$('[data-track="3"]').trigger('click');

	});

	<?php 

	for ($i=1; $i <= 3; $i++) 

	{ 

		?>

		$("#filter_<?php echo $i; ?>").on("click", function() 

		{

			$.ajax({

				type: 'POST',

				url: '{{ URL::route('doverallkpifilters') }}',

				data :

				{

					'filter_name'			: ($(this).attr('data-name')),

					'filters'               : <?php echo "'".${"filter".$i."_data"}."'"; ?>

				},

				beforeSend: function(request) {

					$('#dismiss').trigger('click');

					$('.modal_loading').show();

					return request.setRequestHeader("X-CSRF-Token", $("meta[name='token']").attr('content'));

				},

				success: function(msg)

				{

					if (msg.success != undefined) {

						$.each(msg.errors, function(index, error) {

							$.smallBox({

								title : "Token Mismatch",

								content : "<i class='fa fa-clock-o'></i> <i>Please refresh the page.</i>",

								color : "#C46A69",

								iconSmall : "fa fa-times fa-2x bounce animated",

								timeout : 4000

							});

						});

					} 

					else 

					{

						location.reload();

					}

				}

			});

		});

		<?php 

	}

	?>

	$("[data-d='click']").on('click', function () 

	{

		var selected_tab = ($(this).attr('data-track'));

		if(selected_tab == 1)

		{

			var t1 = <?php echo json_encode($t1); ?>;

			var t4 = null;

			var t5 = null;

			var t7 = null;

		}

		else if(selected_tab == 2)

		{

			var t1 = <?php echo json_encode($t1); ?>;

			var t4 = null; 

			var t5 = null;

			var t7 = null;

		}

		else if(selected_tab == 3)

		{

			var t1 = <?php echo json_encode($t1); ?>; 

			var t4 = null; 

			var t5 = null;

			var t7 = null;

		}

		else if(selected_tab == 4)

		{

			var t1 = null;

			var t4 = <?php echo json_encode($t4); ?>; 

			var t5 = null;

			var t7 = <?php echo json_encode($t7); ?>; 

		}

		else if(selected_tab == 5)

		{

			var t1 = null; 

			var t4 = null;

			var t5 = <?php 

			$t5 = array((($churn_last[0] - $churn_last[1]) / $churn_last[0] * 100),(($churn_current[0] - $churn_current[1]) / $churn_current[0] * 100));

			echo json_encode($t5); ?>; 

			var t7 = null;

		}

		else if(selected_tab == 6)

		{

			var t1 = <?php echo json_encode($t1); ?>; 

			var t4 = null; 

			var t5 = null;

			var t7 = null;

		}

		else if(selected_tab == 7)

		{

			var t1 = null;

			var t4 = null; 

			var t5 = null;

			var t7 = <?php echo json_encode($t7); ?>; 

		}

		$.ajax({ 

			type: 'POST',

			url: '{{ URL::route('dashboard') }}',

			data : 

			{

				'tab'                       : selected_tab,

				't1'						: t1,

				't4'						: t4,

				't5'						: t5,

				't7'						: t7,

				'c_year' 					: "<?php echo $c_year; ?>",

				'l_year' 					: "<?php echo $l_year; ?>",

				'c_month' 					: "<?php echo $c_month_ajax; ?>",

				'c_month_int' 				: "<?php echo $c_month_int_ajax; ?>",

				'ajax_action'               : 'get_tab'

			},

			beforeSend: function(request) {

				return request.setRequestHeader("X-CSRF-Token", $("meta[name='token']").attr('content'));

			},

			success: function(msg)

			{    

				if (msg.success != undefined) {

					$.each(msg.errors, function(index, error) {

						$.smallBox({

							title : "Token Mismatch",

							content : "<i class='fa fa-clock-o'></i> <i>Please refresh the page.</i>",

							color : "#C46A69",

							iconSmall : "fa fa-times fa-2x bounce animated",

							timeout : 4000

						});

					});

				} 

				else 

				{

					$('#output').html(msg);

				}

			}

		});

});

$('#graph_1').highcharts({

	colors: ["#7cb5ec","#f7a35c"],

	chart: {

		zoom: 'x',

		type: 'line',

		backgroundColor: '#FFF',

		shadow: false

	},

	title: {

		text: 'Total Group Workout Throughput'

	},

	subtitle: {

		text: ''

	},

	xAxis: {

		categories: [

		<?php

		for ($i=1; $i <= 12 ; $i++) 

		{

			$new_date = date('M', mktime(0, 0, 0, $i, 10));

			echo "'" . $new_date ."',";

		}

		?>

		]

	},

	yAxis: {

		min: 0,

		title: {

			text: ''

		}

	},

	legend: {

		enabled: true

	},

	tooltip: {

		formatter: function() {

			var s = [];

			var t = this.x;

			var z = <?php echo json_encode($totals_for_graph_current); ?>;

			var k = <?php echo json_encode($totals_for_graph_last); ?>;

			$.each(this.points, function(i, point) {

				if(point.series.name == "<?php echo $c_year; ?>")

				{

					s.push('<span style="font-size:10px">'+ point.key +'</span><table><tr><td style="color:#f7a35c;padding:0">'+ point.series.name +': </td><td style="padding:0"><b>'+

						Highcharts.numberFormat(point.y,0,'.',',')+'</b></td></tr><tr><td style="color:#f7a35c;padding:0"># of sites: </td><td style="padding:0"><b>'+

						z[t] +'</b></td></tr></table>');

				}

				else

				{

					s.push('<table><tr><td style="color:#7cb5ec;padding:0">'+ point.series.name +': </td><td style="padding:0"><b>'+

						Highcharts.numberFormat(point.y,0,'.',',')+'</b></td></tr><tr><td style="color:#7cb5ec;padding:0"># of sites: </td><td style="padding:0"><b>'+

						k[t] +'</b></td></tr></table>');

				}

			});

			return s.join('');

		},

		shared: true,

		useHTML: true

	},

	plotOptions: {

		column: {

			pointPadding: 0.2,

			borderWidth: 0

		}

	},

	series: [{

		name: "<?php echo $l_year; ?>",

		data: [<?php 

		for ($i=1; $i <= 12; $i++) 

		{ 

			echo number_format(array_sum($totals_last[$i]),0,"","").",";

		}

		?>],

	},

	{

		name: "<?php echo $c_year; ?>",

		data: [<?php 

		for ($i=1; $i <= $c_month_int_ajax; $i++) 

		{ 

			echo number_format(array_sum($totals_current[$i]),0,"","").",";

		}

		?>],

	}]

});

</script>

@stop