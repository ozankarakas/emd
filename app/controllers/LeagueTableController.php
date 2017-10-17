<?php
error_reporting(E_ERROR);

class LeagueTableController extends BaseController {

	public function getIndex()

	{

		return View::make('leagueTable');

	}

	public function postMethod()

	{

		if(Input::get('ajax_action') == "get_table")

		{

			$filters = Input::get('filters');

			$values = GeneralFunctions::arrange_filters($filters);

			$person_type = $values['person_type'];

			$gender = $values['gender'];

			$age = $values['age'];

			$programme = $values['programme'];

			$sites = $values["lcs"];

			$end_date = $values['date_end_current'];

			$start_date = date_create($end_date.' last day of -2 month')->format('Y-m-01 00:00:00');

			$end_date_last = date_create($end_date.' last day of -12 month')->format('Y-m-d 23:59:59');

			$start_date_last = date_create($end_date.' last day of -12 month')->format('Y-m-01 00:00:00');

			list($results, $results_last) = QueryCacher::league_table_queries($person_type, $gender, $age, $programme, $end_date, $start_date, $end_date_last, $start_date_last, $sites);

			//sites to exclude
			$sites_to_exclude = [];


			foreach ($results as $value)

			{

				$dates[] = $value->date;

				$siteIDs[] = $value->SiteID;

				if(Auth::user()->demo)

				{

					$r = number_format(rand(70,160) / 100,1);

					$value->count = $value->count * $r;

				}

				$data[$value->SiteID][$value->date] = $value->count;

			}

			//DATA SORT

			for ($i=0; $i < count($siteIDs); $i++)

			{

				for ($j=0; $j < count($dates); $j++)

				{

					if(!isset($data[$siteIDs[$i]][$dates[$j]]))

					{

						$data[$siteIDs[$i]][$dates[$j]] = 0;

					}

				}

			}

			$dates = array_unique($dates);

			$dates = array_values($dates);

			$siteIDs = array_unique($siteIDs);

			$siteIDs = array_values($siteIDs);

//first month

			for ($i=0; $i < count($siteIDs); $i++)

			{

				if($data[$siteIDs[$i]][$dates[0]] != 0)

				{

					$v = number_format(($data[$siteIDs[$i]][$dates[1]] / $data[$siteIDs[$i]][$dates[0]] * 100) - 100,0,"","");

					$first_growth[$siteIDs[$i]] = $v;

				}

				else

				{

					$first_growth[$siteIDs[$i]] = "-99999";

				}

			}

			$first_ranks = GeneralFunctions::rank($first_growth);

//second month

			for ($i=0; $i < count($siteIDs); $i++)

			{

				if($data[$siteIDs[$i]][$dates[1]] != 0)

				{

					$v = number_format(($data[$siteIDs[$i]][$dates[2]] / $data[$siteIDs[$i]][$dates[1]] * 100) - 100,0,"","");

					$second_growth[$siteIDs[$i]] = $v;

				}

				else

				{

					$second_growth[$siteIDs[$i]] = "-99999";

				}

			}

			$second_ranks = GeneralFunctions::rank($second_growth);

			foreach ($results_last as $value) 

			{

				if(Auth::user()->demo)

				{

					$r = number_format(rand(70,160) / 100,1);

					$value->count = $value->count * $r;

				}

				$data_last[$value->SiteID] = $value->count;

			}

			$date_last = date("M-y",strtotime($start_date_last));

			?>

			<div id="widget-grid_ajax">

				<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

					<div class="jarviswidget jarviswidget-color-blue" data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false">

						<header>

							<span class="widget-icon"> <i class="fa fa-sort-amount-desc"></i>

							</span>

							<h2>National Leisure Centre League Table - Admin View</h2>

						</header>

						<div>

							<div class="widget-body no-padding">

								<table class="table table-striped table-bordered table-hover" id="data_table_1" style="table-layout: fixed;" width="100%">

									<thead>

										<tr>

											<th data-class="expand" style='width: 40%;'>Site Name</th>

											<th data-hide="phone,tablet,all">Operator Name</th>

											<th data-hide="phone"><?php echo $dates[1]; ?> Visits</th>

											<th data-hide="phone,tablet,all"><?php echo $dates[0]." to ".$dates[1]; ?> Growth</th>

											<th data-hide="phone,tablet,all"><?php echo $dates[1]; ?> Rank</th>

											<th data-hide="phone"><?php echo $dates[2]; ?> Visits</th>

											<th style="background-color: #5D9CEC"><?php echo $dates[1]." to ".$dates[2]; ?> Growth</th>

											<th data-hide="phone,tablet,all"><?php echo $dates[2]; ?> Rank</th>

											<th data-hide="phone,tablet,all">Rank Change</th>

											<th data-hide="phone"><?php echo $date_last; ?> Visits</th>

											<th style="background-color: #5D9CEC"><?php echo $date_last." to ".$dates[2]; ?> Growth</th>

										</tr>

									</thead>

									<tbody id="table_body">

										<?php

										for ($i=0; $i < count($siteIDs); $i++)

										{

											if(in_array($siteIDs[$i], (array)$sites))

											{

												if($second_growth[$siteIDs[$i]] < 250 && $second_growth[$siteIDs[$i]] > -100)

												{

											//last year check

													$ytd_growth = number_format(($data[$siteIDs[$i]][$dates[2]] / $data_last[$siteIDs[$i]] * 100) - 100,0,"","");

													if(!isset($data_last[$siteIDs[$i]]) || $data_last[$siteIDs[$i]] == 0 || $ytd_growth > 250) 

													{

														$ytd = "-";

													}

													else

													{


														if($ytd_growth > 0)

														{

															$ytd = $ytd_growth." % <i class='fa fa-chevron-up green_fa'></i>";

														}

														elseif ($ytd_growth < 0) 

														{

															$ytd = $ytd_growth." % <i class='fa fa-chevron-down red_fa'></i>";

														}

														else

														{

															$ytd = $ytd_growth." % <i class='fa fa-minus'></i>";

														}

													}

													if($first_growth[$siteIDs[$i]] == -99999)

													{

														$fg = "-";

													}

													else

													{

														if($first_growth[$siteIDs[$i]] > 0)

														{

															$fg = $first_growth[$siteIDs[$i]]." % <i class='fa fa-chevron-up green_fa'></i>";

														}

														elseif($first_growth[$siteIDs[$i]] < 0)

														{

															$fg = $first_growth[$siteIDs[$i]]." % <i class='fa fa-chevron-down red_fa'></i>";

														}

														else

														{

															$fg = $first_growth[$siteIDs[$i]]." % <i class='fa fa-minus'></i>";

														}

													}

													if($second_growth[$siteIDs[$i]] == -99999)

													{

														$sg = "-";

													}

													else

													{

														if($second_growth[$siteIDs[$i]] > 0)

														{

															$sg = $second_growth[$siteIDs[$i]]." % <i class='fa fa-chevron-up green_fa'></i>";

														}

														elseif($second_growth[$siteIDs[$i]] < 0)

														{

															$sg = $second_growth[$siteIDs[$i]]." % <i class='fa fa-chevron-down red_fa'></i>";

														}

														else

														{

															$sg = $second_growth[$siteIDs[$i]]." % <i class='fa fa-minus'></i>";

														}

													}

													$site = SiteName::find($siteIDs[$i]);

													if(Auth::user()->demo)

													{

														$site->name = "Demo Leisure Centre ".$i;

													}
													if(in_array($siteIDs[$i], $sites_to_exclude))
													{
														$site->name = "OTHER SITE"; 
													}
													?>

													<tr

													<?php

													if($sg != "-")

													{
														if ($operator_name == "GLL") 
														{
															?>

														style="cursor: pointer;" onclick='displayResult("<?php echo $second_ranks[$siteIDs[$i]]; ?>","<?php echo $dates[2]; ?>","<?php echo $second_growth[$siteIDs[$i]]; ?>","OTHER SITE","<?php echo $siteIDs[$i]; ?>", "<?php echo $results[$i]->operator_id;?>")'

														<?php
														}

														else
														{
															?>

														style="cursor: pointer;" onclick='displayResult("<?php echo $second_ranks[$siteIDs[$i]]; ?>","<?php echo $dates[2]; ?>","<?php echo $second_growth[$siteIDs[$i]]; ?>","<?php echo $site->name; ?>","<?php echo $siteIDs[$i]; ?>", "<?php echo $results[$i]->operator_id;?>")'

														<?php
														}

														

													}

													$gap = $first_ranks[$siteIDs[$i]] - $second_ranks[$siteIDs[$i]];

													if($gap>0)

													{

														$gap = "+".$gap;

													}

													$gaps[$site->name] = $gap;

													$sgs[$site->name] = $second_growth[$siteIDs[$i]];

													$fgs[$site->name] = $first_growth[$siteIDs[$i]];

													if ($first_growth[$siteIDs[$i]] < 250) 
													{
														$fgs_less_than_250[$site->name] = $first_growth[$siteIDs[$i]];

														$sgs_less_than_250[$site->name] = $second_growth[$siteIDs[$i]];
													}

													?>

													>

													<td><?php 
													$operator_name = Operators::find($site->operator_id)->name;
													if ($operator_name == 'GLL') {
														echo 'Other Site';
													}
													else
													{
														echo $site->name;
													}
													?></td>

													<td><?php 

														if(Auth::user()->demo)

														{

															echo "Demo Operator";

														}

														else

														{

															echo $operator_name;

														}

														?>

													</td>

													<td align="right"><?php echo $data[$siteIDs[$i]][$dates[1]] == 0 ? 'N/A' : number_format($data[$siteIDs[$i]][$dates[1]],0); ?></td>

													<td align="right"><?php echo $fg; ?></td>

													<td align="right"><?php echo $first_ranks[$siteIDs[$i]]; ?></td>

													<td align="right"><?php echo $data[$siteIDs[$i]][$dates[2]] == 0 ? 'N/A' : number_format($data[$siteIDs[$i]][$dates[2]],0); ?></td>

													<td align="right"><?php echo $sg; ?></td>

													<td align="right"><?php echo $second_ranks[$siteIDs[$i]]; ?></td>

													<td align="right"><?php echo $gap; ?></td>

													<td align="right"><?php echo $data_last[$siteIDs[$i]] == 0 ? 'N/A' : number_format($data_last[$siteIDs[$i]],0); ?></td>

													<td align="rignt"><?php echo $ytd; ?></td>

												</tr>

												<?php 

											}

										}

									}

									?>

								</tbody>

							</table>

							<div class="alert alert-info fade in" style="margin: 0px;">

								<button class="close" data-dismiss="alert">

									×

								</button>

								<p><i class="fa-fw fa fa-info"></i><strong>Info!</strong> Please note that leisure centres with monthly participation change over 250% or less than -100% are excluded from the League Table.</p>

								<p><i class="fa-fw fa fa-info"></i><strong>Info!</strong> Please click on a leisure centre row to display site specific quartile analysis in the graph below.</p>

							</div>
							<?php $gll_sites = SiteName::wherein('id',$sites_)->where('operator_id', 19)->lists('id','name'); ?>
							<div class="show-stat-microcharts" style="min-height: 90px;">

								<div class="col-md-12 col-lg-4" style='height: 80px'>

									<span style='font-weight: bold; margin-left: 15px;'>Max Rank Change<span style="margin-left: 5%; font-weight: bold; font-size: 20px; color: green;" class=''><?php echo max($gaps);?></span></span>

									<br>										

									<span class="easy-pie-title" style='font-size: 16px; margin-left: 30px;'><?php

									if (in_array(array_search(max($gaps), $gaps), $gll_sites)) {
										echo 'OTHER SITE';
									}
									else
									{

									 echo array_search(max($gaps), $gaps);										
									}
									?></span>	

								</div>

								<div class="col-md-12 col-lg-4" style='height: 80px'>

									<span style='font-weight: bold; margin-left: 15px;'>Max Growth in <?php echo $dates[1]?><span style="margin-left: 5%; font-weight: bold; font-size: 20px; color: green;" class=''><i class="fa fa-chevron-up green_fa"></i><?php echo max($fgs_less_than_250);?> %</span>	</span>

									<br>

									<span class="easy-pie-title" style='font-size: 16px; margin-left: 30px;'><?php echo array_search(max($fgs_less_than_250), $fgs_less_than_250);?></span>																			

								</div>

								<div class="col-md-12 col-lg-4" style='height: 80px'>

									<span style='font-weight: bold; margin-left: 15px;'>Max Growth in <?php echo $dates[2]?><span style="margin-left: 5%; font-weight: bold; font-size: 20px; color: green;" class=''><i class="fa fa-chevron-up green_fa"></i><?php echo max($sgs_less_than_250);?> %</span>	</span>

									<br>

									<span class="easy-pie-title" style='font-size: 16px; margin-left: 30px;'><?php echo array_search(max($sgs_less_than_250), $sgs_less_than_250);?></span>

								</div>

							</div>

						</div>

					</div>

				</div>

			</article>

			<div id="show_graph"></div>

		</div>

		<?php

		echo View::make('templates.datatable', array("table_id" => "data_table_1", "sort" => "true", "column" => 7, "direction" => "asc"));

				//added at ajax call

				//echo View::make('templates.widget');

		echo View::make('templates.sparkline');

		?>

		<script type="text/javascript">

			$('body').delegate('#data_table_1 tbody tr', "click", function () {

				$(this).addClass('highlight_c').siblings().removeClass('highlight_c');

			});

			$(document).ready(function() {

				$("#table_body > tr:first").trigger('click');

			});

			function displayResult(last_rank, last_month, growth, siteName, siteID, op_id)

			{

				$.ajax({

					type: 'POST',

					url: '<?php echo URL::route('leagueTable'); ?>',

					data :

					{

						'last_rank'                 : last_rank,

						'last_month'                : last_month,

						'growth'                    : growth,

						'siteName'                  : siteName,

						'siteID'                  	: siteID,

						'op_id'						: op_id,

						'color'						: "<?php echo Input::get('color'); ?>",

						'text_color'				: "<?php echo Input::get('text_color'); ?>",

						'values'                    : '<?php echo json_encode($second_growth); ?>',
						'sites'                    	: '<?php echo json_encode($sites); ?>',

						'ajax_action'               : 'show_graph'

					},

					beforeSend: function(request) {

						return request.setRequestHeader("X-CSRF-Token", $("meta[name='token']").attr('content'));

					},

					success: function(msg)

					{

						if (msg.success != undefined) {

							$.each(msg.errors, function(index, error) {

									// console.log(error);

								$.smallBox({

									title : "Token Mismatch",

									content : "<i class='fa fa-clock-o'></i> <i>Please refresh the page.</i>",

									color : "#C46A69",

									iconSmall : "fa fa-times fa-2x bounce animated",

									timeout : 4000

								});

							});

						} else {

							$('#show_graph').html(msg);

							$("html, body").animate({ scrollTop: $(document).height()-$(window).height() });

						}

					}

				});

			}

		</script>

		<?php

	}

	elseif(Input::get('ajax_action') == "show_graph")

	{

		$last_rank = Input::get('last_rank');

		$last_month = Input::get('last_month');

		$growth = Input::get('growth');

		$siteID = Input::get('siteID');

		$siteName = Input::get('siteName');

		$op_id = Input::get('op_id');

		$values = json_decode(Input::get('values'),true);
		$sites = json_decode(Input::get('sites'),true);

		$remove_array = array('-99999');

		$values = (array_diff($values,$remove_array));

		$values = (array_diff($values,$sites));

		if(($key = array_search(-99999, $values)) !== false)

		{

			unset($values[$key]);

		}

		function remove_up($var) { return $var < 250; }

		function remove_down($var) { return $var > -100; }

		$values = array_filter($values, 'remove_up');

		$values = array_filter($values, 'remove_down');

		foreach ($values as $key => $value) 
		{
			if(!in_array($key, (array)$sites))
			{ 
				unset($values[$key]);			
			}
		}

		asort($values);

		$values = array_values($values);

		$min_founder[] = min($values);

		$min_founder[] = $growth;

		if(min($min_founder) >= 0)

		{

			$big_number = 0;

		}

		else

		{

			$big_number = abs(min($min_founder));

		}

		for ($i = 0; $i < count($values); $i ++)

		{

			$values_p[] = $values[$i] + $big_number;

		}

		$growth = $growth + $big_number;

		$values = $values_p;

		$q0 = min($values);

		$q1 = $values[(round(count($values) / 4)) - 1];

		$q2 = $values[(round(count($values) / 2)) - 1];

		$q3 = $values[(round(count($values) / 4 * 3)) - 1];

		$q4 = $values[count($values) - 1];

		$q0_[] = $q0;

		$q1_[] = $q1 - $q0;

		$q2_[] = $q2 - $q1;

		$q3_[] = $q3 - $q2;

		$q4_[] = $q4 - $q3;

		?>

		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

			<div class="jarviswidget jarviswidget-color-red" data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false">

				<header>

					<span class="widget-icon"> <i class="fa fa-table"></i>

					</span>

					<h2>Quartile analysis | Sector Rank for <?php echo $last_month ?> is <?php echo $last_rank ?></h2>

				</header>

				<div>

					<div class="widget-body no-padding">

						<div id="graph_1" style="margin-right: 10px; padding-bottom: 2px; height: 250px;"></div>

						<div class="alert alert-info fade in" style="margin: 0px;">

							<button class="close" data-dismiss="alert">

								×

							</button>

							<i class="fa-fw fa fa-info"></i>

							<strong>Info!</strong> The centre score (based on growth in wet-side throughput) is benchmarked against all centres within the EMD Data Pool platform.

						</div>

					</div>

				</div>

			</div>

		</article>

		<?php echo View::make('templates.widget'); ?>

		<script type="text/javascript">

			var cat1 = '# of Sites - <?php echo count($values);  ?>';

			var lowest = <?php echo $big_number; ?>;

			var growth_site = '<?php echo number_format($growth - $big_number,2);?>';

			Highcharts.setOptions({

				colors: ['#50B432', '#9acd32', '#ffbf00', '#e32636']

			});

			$('#graph_1').highcharts({

				chart: {

					type: 'bar'

				},

				title: {

					text: '<?php echo $siteName; ?>'

				},

				xAxis: {

					categories: [cat1],

				},

				yAxis: {

					reversed: false,

					labels: {

						formatter: function() {

							return (this.value - lowest) + " %";

						}

					},

					title:{

						text: "Values"

					},

					plotLines: [{

						color: 'blue',

						width: 2,

						value: <?php echo $growth;?>,

						dashStyle: 'longdashdot',

						label : {

							text : growth_site + " %",

							rotation: 0

						}

					}]},

					legend: {

						reversed: true

					},

					plotOptions: {

						series: {

							stacking: 'normal'

						}

					},

					tooltip: {

						enabled: false

					},

					series: [{

						name: 'Top quartile',

						data: [<?php echo $q4_[0];?>],

					}, {

						name: 'Third quartile',

						data: [<?php echo $q3_[0];?>]

					}, {

						name: 'Second quartile',

						data: [<?php echo $q2_[0];?>]

					}, {

						name: 'Bottom quartile',

						data: [<?php echo $q1_[0];?>]

					}, {

						name: 'Fake quartile',

						data: [<?php echo $q0_[0];?>],

						showInLegend: false,

						color: 'rgba(0,0,0,0.0)',

						enableMouseTracking: false

					}]

				});

		</script>

		<?php

	}

}

}

