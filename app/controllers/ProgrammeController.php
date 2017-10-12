<?php

class ProgrammeController extends BaseController
{
	public function getIndex()
	{
		return View::make('programme');
	}

	public function postKPI()
	{
		if (Input::get('ajax_action') == "get_kpi")
		{
			$var = GeneralFunctions::arrange_variables(Input::all());

			$model = "DataRaw".$var["sport"];

			if ($var["lc"] == "none")
			{
				$get_results = $model::select('TemplateName', DB::raw("DATE_FORMAT(`DateOfBooking`, '%M-%y') as date"), DB::raw("sum(`HeadCount`) as count"))
				->where('PersonType', 'LIKE', $var["person"])
				->where('BookingType', 'LIKE', $var["booking"])
				->where('Gender', 'LIKE', $var["gender"])
				->where('BookingType','<>','5')
				->whereBetween('Age', array($var["age_start"], $var["age_end"]))
				->whereBetween('DateOfBooking', array($var["date_start"], $var["date_end"]))
				->groupBy(DB::raw("month(`DateOfBooking`), year(`DateOfBooking`), `TemplateName`"))
				->orderBy('DateOfBooking')
				->remember(1440)
				->get();
			}
			else
			{
				$get_results = $model::select('TemplateName', DB::raw("DATE_FORMAT(`DateOfBooking`, '%M-%y') as date"), DB::raw("sum(`HeadCount`) as count"))
				->whereIn('SiteID', (array)$var["lc"])
				->where('PersonType', 'LIKE', $var["person"])
				->where('BookingType', 'LIKE', $var["booking"])
				->where('Gender', 'LIKE', $var["gender"])
				->where('BookingType','<>','5')
				->whereBetween('Age', array($var["age_start"], $var["age_end"]))
				->whereBetween('DateOfBooking', array($var["date_start"], $var["date_end"]))
				->groupBy(DB::raw("month(`DateOfBooking`), year(`DateOfBooking`), `TemplateName`"))
				->orderBy('DateOfBooking')
				->remember(1440)
				->get();
			}
			$nat_results = $model::select('TemplateName', DB::raw('sum(`HeadCount`) as count'))
			->where('BookingType','<>','5')
			->whereBetween('DateOfBooking', array($var["date_start"], $var["date_end"]))
			->groupBy('TemplateName')
			->orderBy('DateOfBooking')
			->remember(1440)
			->get();
			foreach ($nat_results as $result)
			{
				foreach ($GLOBALS['template_names'] as $template_name) 
				{
					$change_template_name = "";
					if($template_name->id === $result->TemplateName)
					{
						$change_template_name = str_replace("'", "`", $template_name->name);
						break;
					}
				}				
				$activity_template = $var["sport"] . " - " . $change_template_name;				
				$results_nat[$activity_template] = $result->count;
			}
			foreach ($get_results as $result)
			{
				foreach ($GLOBALS['template_names'] as $template_name) 
				{
					$change_template_name = "";
					if($template_name->id === $result->TemplateName)
					{
						$change_template_name = str_replace("'", "`", $template_name->name);
						break;
					}
				}	
				$activity_template = $var["sport"] . " - " . $change_template_name;	
				$dates[] = $result->date;
				$activity_templates[] = $activity_template;
				$results[$result->date][$activity_template][] = $result->count;
				$totals[$result->date][] = $result->count;
			}
			$dates = array_unique($dates);
			$activity_templates = array_unique($activity_templates);
			$dates = array_values($dates);
			$activity_templates = array_values($activity_templates);
			sort($activity_templates);
// getting sum for the dates
			for ($i = 0; $i < count($dates); $i++)
			{
				$totals_cumm[$dates[$i]][] = array_sum($totals[$dates[$i]]);
				if ($i > 0)
				{
					$growth[$dates[$i]][] = array_sum($totals[$dates[$i]]) - array_sum($totals[$dates[$i - 1]]);
				} // reseting first growth to 0
				else
				{
					$growth[$dates[$i]][] = 0;
				}
			}
// hide if no data
			if (count($dates) == 0)
			{
				$no_data = 'style="display: none"';
			}
			if (count($dates) == 0)
			{
				$dates[] = "";
			}
			?>
			<div id="widget-grid_ajax">
				<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<div class="jarviswidget jarviswidget-color-green" data-widget-editbutton="false"
					data-widget-colorbutton="false" data-widget-deletebutton="false">
					<header>
						<span class="widget-icon"> <i class="fa fa-table"></i>
						</span>

						<h2>Programme Engagement</h2>
					</header>
					<div>
						<div class="widget-body no-padding">
							<!-- table-striped table-hover -->
							<table class="table table-striped table-bordered table-bordered table-hover"
							id="data_table_1" style="table-layout: fixed;" width="100%">
							<thead>
								<tr>
									<th data-class="expand">Programme</th>
									<?php
									for ($i = 0; $i < count($dates); $i++)
									{
										if ($i < count($dates) - 3)
										{
											$hide = "phone, tablet, all";
										}
										else
										{
											$hide = "phone, tablet";
										}
										?>
										<th data-hide="<?php echo $hide; ?>"><?php echo $dates[$i]; ?></th>
										<?php
									}
									?>
									<th>Total</th>
									<th>Long. Analysis</th>
									<th>National Total</th>
									<th>% National</th>
								</tr>
							</thead>
							<tbody>
								<?php
								for ($i = 0; $i < count($activity_templates); $i++)
								{
									$total_value = 0;
									?>
									<tr>
										<td width="250px;"><?php echo $activity_templates[$i]; ?></td>
										<?php
										for ($j = 0; $j < count($dates); $j++)
										{
											$value = $results[$dates[$j]][$activity_templates[$i]][0];
											$total_value = $total_value + $value;
											$total_value_for_graph[$activity_templates[$i]] = $total_value;
											?>
											<td align="right"><?php echo number_format($value, 0); ?></td>
											<?php
										}
										?>
										<td align="right"><b><?php echo number_format($total_value, 0); ?></b></td>
										<?php
											//+1 because easy pie chart calculates 1 less.
											//reverted
										$av = number_format(($total_value / $results_nat[$activity_templates[$i]]
										* 100) + 0, 0); ?>
										<?php
										$graph_values = "";
										for ($j = 0; $j < count($dates); $j++)
										{
											$value = $results[$dates[$j]][$activity_templates[$i]][0];
											$graph_values .= $value . ",";
										}
										?>
										<td style="cursor: pointer;"
										onclick='displayResult("<?php echo $activity_templates[$i]; ?>", <?php echo json_encode($dates); ?>, "<?php echo trim($graph_values, ","); ?>")'>
										<div class="sparkline txt-color-blue text-align-center"
										data-tooltip='<?php echo json_encode(str_replace("-", "/", $dates)); ?>'>
										<?php
										echo trim($graph_values, ",");
										?>
									</div>
									<td align="right">												
										<b><?php echo number_format($results_nat[$activity_templates[$i]], 0); ?></b>
									</td>
									<td align="center">

										<div class="easy-pie-chart txt-color-blue easyPieChart" data-percent="<?php echo $av; ?>" data-pie-size="40">
											<div class="percent percent-sign font-xs"><?php echo $av; ?></div>
										</div>
									</td>
								</tr>
								<?php
							}
							?>
							<tfoot>
								<tr>
									<td><b>Total</b></td>
									<?php
									$total_value = 0;
									for ($i = 0; $i < count($dates); $i++)
									{
										$value = $totals_cumm[$dates[$i]][0];
										$total_value = $total_value + $value;
										?>
										<td align="right"><b><?php echo number_format($value, 0); ?></b></td>
										<?php
									}
									?>
									<td align="right"><b><?php echo number_format($total_value, 0); ?></b></td>
									<?php $av = number_format(($total_value / array_sum($results_nat) * 100) + 0,
									0); ?>
									<td>
										<div class="sparkline txt-color-blue text-align-center"
										data-tooltip='<?php echo json_encode(str_replace("-", "/", $dates)); ?>'>
										<?php
										for ($i = 0; $i < count($dates); $i++)
										{
											$value = $totals_cumm[$dates[$i]][0];
											if ($i == count($dates) - 1)
											{
												echo number_format($value, 0, "", "");
											}
											else
											{
												echo number_format($value, 0, "", "") . ",";
											}
										}
										?>
									</div>
								</td>
								<td align="right">
									<b><?php echo number_format(array_sum($results_nat), 0); ?></b>
								</td>
								<td align="center">
									<div class="easy-pie-chart txt-color-blue easyPieChart"
									data-percent="<?php echo $av; ?>" data-pie-size="40">
									<div class="percent percent-sign font-xs"><?php echo $av; ?></div>
								</div>
							</td>
						</tr>
					</tfoot>
									<!--
<tr>
<td><b>Growth</b></td>
<?php
									for ($i = 0; $i < count($dates); $i++)
									{
										$no = $growth[$dates[$i]][0];
										if ($no < 0)
										{
											$color = '<i class="fa fa-chevron-down red_fa"></i>';
										}
										elseif ($no == 0)
										{
											$color = '<i class="fa fa-minus"></i>';
										}
										else
										{
											$color = '<i class="fa fa-chevron-up green_fa"></i>';
										}
										?>
<td align="right"><b><?php echo $color . " " . number_format($no, 0) ?></b></td>
<?php
									}
									?>
<td align="right"><b>-</b></td>
<td> <div class="sparkline txt-color-blue text-align-center">
<?php
									for ($i = 0; $i < count($dates); $i++)
									{
										$no = $growth[$dates[$i]][0];
										if ($i == count($dates) - 1)
										{
											echo number_format($no, 0, "", "");
										}
										else
										{
											echo number_format($no, 0, "", "") . ",";
										}
									}
									?>
</div></td>
<td align="right"><b>-</b></td>
<td></td>
</tr> -->
</tbody>
</table>
</div>
</div>
</div>
</article>
<div id="show_graph"></div>
<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	<div class="jarviswidget jarviswidget-color-red" data-widget-editbutton="false"
	data-widget-colorbutton="false" data-widget-deletebutton="false">
	<header>
		<span class="widget-icon"> <i class="fa fa-table"></i>
		</span>

		<h2>Graph - Longitudinal analysis for programme engagement</h2>
	</header>
	<div>
		<div class="widget-body no-padding">
			<div id="graph_1" style="margin-right: 10px; padding-bottom: 2px;"></div>
		</div>
	</div>
</div>
</article>
<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	<div class="jarviswidget jarviswidget-color-red" data-widget-editbutton="false"
	data-widget-colorbutton="false" data-widget-deletebutton="false">
	<header>
		<span class="widget-icon"> <i class="fa fa-table"></i>
		</span>

		<h2>Graph - Longitudinal analysis for programme engagement</h2>
	</header>
	<div>
		<div class="widget-body no-padding">
			<div id="graph_2" style="margin-right: 10px; padding-bottom: 2px;"></div>
		</div>
	</div>
</div>
</article>
</div>
<?php

//Getting top 5 programme total
asort($total_value_for_graph);
$total_value_for_graph = array_reverse($total_value_for_graph, true);

$count = 0;
foreach ($total_value_for_graph as $key => $value)
{
	if ($count < 5)
	{
		$total_for_graph[] = $key;
	}
	$count++;
}

echo View::make('templates.sparkline');
echo View::make('templates.datatable', array("table_id" => "data_table_1", "sort" => "true", "column" => "0"));
echo View::make('templates.widget');
?>
<script type="text/javascript">


	$('body').delegate('#data_table_1 tbody tr', "click", function () {
		$(this).addClass('highlight_c').siblings().removeClass('highlight_c');
	});

	function displayResult(template, dates, values) {
		$.ajax({
			type: 'POST',
			url: "<?php echo URL::route('programme') ?>",
			data: {
				'template': template,
				'dates': dates,
				'values': values,
				'ajax_action': 'show_graph'
			},
			beforeSend: function (request) {
				return request.setRequestHeader("X-CSRF-Token", $("meta[name='token']").attr('content'));
			},
			success: function (msg) {
				if (msg.success != undefined) {
					$.each(msg.errors, function (index, error) {
									// console.log(error);
									$.smallBox({
										title: "Token Mismatch",
										content: "<i class='fa fa-clock-o'></i> <i>Please refresh the page.</i>",
										color: "#C46A69",
										iconSmall: "fa fa-times fa-2x bounce animated",
										timeout: 4000
									});
								});
				} else {
					$('#show_graph').html(msg);
					$('html, body').animate({
						scrollTop: $("#graph_4_body").offset().top
					}, 1000);
				}
			}
		});
	}
	$(function () {
		$('#graph_1').highcharts({
			chart: {
				type: 'column'
			},
			title: {
				text: ''
			},
			subtitle: {
				text: ''
			},
			xAxis: {
				categories: [
				<?php
				for ($i = 0; $i < count($dates); $i ++)
				{
					echo "'" . $dates[$i] . "',";
				}
				?>
				]
			},
			tooltip: {
				headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
				pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
				'<td style="padding:0"><b>{point.y:.0f}</b></td></tr>',
				footerFormat: '</table>',
				shared: false,
				useHTML: true
			},
			plotOptions: {
				column: {
					pointPadding: 0.2,
					borderWidth: 0
				}
			},
			series: [
			<?php
			for ($i = 0; $i < count($activity_templates); $i ++)
			{
				if(in_array($activity_templates[$i], (array)$total_for_graph))
				{
					$show = "true";
				}
				else
				{
					$show = "false";
				}
				echo "{name: '" . $activity_templates[$i] . "', data: [";
				for ($j = 0; $j < count($dates); $j ++)
				{
					echo (int) $results[$dates[$j]][$activity_templates[$i]][0] . ",";
				}
				echo "],visible: ".$show."},";
			}
			?>
			]
		});
});
$(function () {
	$('#graph_2').highcharts({
		chart: {
			type: 'area'
		},
		title: {
			text: ''
		},
		subtitle: {
			text: ''
		},
		xAxis: {
			categories: [
			<?php
			for ($i = 0; $i < count($dates); $i ++)
			{
				echo "'" . $dates[$i] . "',";
			}
			?>
			]
		},
		tooltip: {
			headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
			pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
			'<td style="padding:0"><b>{point.y:.0f}</b></td></tr>',
			footerFormat: '</table>',
			shared: false,
			useHTML: true
		},
		plotOptions: {
			area: {
				stacking: 'normal',
				lineColor: '#666666',
				lineWidth: 1,
				marker: {
					lineWidth: 1,
					lineColor: '#666666'
				}
			}
		},
		series: [
		<?php
		for ($i = 0; $i < count($activity_templates); $i ++)
		{
			if(in_array($activity_templates[$i], (array)$total_for_graph))
			{
				$show = "true";
			}
			else
			{
				$show = "false";
			}
			echo "{name: '" . $activity_templates[$i] . "', data: [";
			for ($j = 0; $j < count($dates); $j ++)
			{
				echo (int) $results[$dates[$j]][$activity_templates[$i]][0] . ",";
			}
			echo "],visible: ".$show."},";
		}
		?>
		]
	});
});
</script>
<?php
}
elseif (Input::get('ajax_action') == "get_lcs")
{
	$mode = Input::get('change_mode');
	GeneralFunctions::get_lcs($mode);
}
elseif (Input::get('ajax_action') == "show_graph")
{
	$template = Input::get('template');
	$dates = Input::get('dates');
	$values = Input::get('values');
	?>
	<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="graph_4_body">
		<div class="jarviswidget jarviswidget-color-red" data-widget-editbutton="false"
		data-widget-colorbutton="false" data-widget-deletebutton="false">
		<header>
			<span class="widget-icon"> <i class="fa fa-table"></i>
			</span>

			<h2>Graph - Longitudinal analysis for <?php echo $template; ?></h2>
		</header>
		<div>
			<div class="widget-body no-padding">
				<div id="graph_4" style="margin-right: 10px; padding-bottom: 2px;"></div>
			</div>
		</div>
	</div>
</article>
<script type="text/javascript">
	$('#graph_4').highcharts({
		chart: {
			type: 'column'
		},
		title: {
			text: ''
		},
		subtitle: {
			text: ''
		},
		xAxis: {
			categories: [
			<?php
			for ($i = 0; $i < count($dates); $i ++)
			{
				echo "'" . $dates[$i] . "',";
			}
			?>
			]
		},
		yAxis: {
			allowDecimals: false,
		},
		tooltip: {
			headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
			pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
			'<td style="padding:0"><b>{point.y:.0f}</b></td></tr>',
			footerFormat: '</table>',
			shared: false,
			useHTML: true
		},
		plotOptions: {
			column: {
				pointPadding: 0.2,
				borderWidth: 0
			}
		},
		series: [{
			name: '<?php echo $template;?>',
			data: [
			<?php
			echo $values;
			?>
			]
		}]
	});
</script>
<?php
}
}
}   