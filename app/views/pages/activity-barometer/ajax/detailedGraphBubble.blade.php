<article class="col-sm-12 col-md-12 col-lg-12">
	<div class="jarviswidget jarviswidget-color-darken" data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false" data-widget-collapsed="false">
		<header>
			<span class="widget-icon"> <i class="fa fa-table"></i> </span>
			<h2><?php echo $siteName; ?> Participation Analysis</h2>
		</header>
		<div>
			<div class="jarviswidget-editbox">
			</div>
			<div class="widget-body">
				<div id="graph1detailed" style="width: 100%; height: 500px;"></div>
			</div>
		</div>
	</div>
</article>
<script type="text/javascript">
	$(function () {
		$('#graph1detailed').highcharts({
			chart: {
				type: 'scatter',
				zoomType: 'xy'
			},
			legend: {
				enabled: true
			},
			title: {
				text: "<?php echo $siteName; ?> Participation Analysis (<?php echo $period[0];?> | <?php echo end($period);?>)"
			},
			plotOptions: {
				scatter: {
					marker: {
						radius: 6,
					},
					tooltip: {
						headerFormat: '<b>{series.name}</b><br>',
						pointFormat: 'Visits per m2: {point.x:,.0f}, Visits by members: {point.y:,.0f}%'
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
				if (count($period) == 1) {
					$period_temp = $period;
					$period_id = array_shift($period);
					$period = $period_temp;
					$max = ($sector_hc * 2 < $sites_hc[$period_id] * 2) ? $sites_hc[$period_id] * 2 : $sector_hc * 2; 
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
					text: 'Visits per metre square',
				},
				plotLines: [{
					color: 'blue',
					width: 2,
					value: <?php echo number_format($sector_rv,0,"","");?>,
					dashStyle: 'longdashdot',
					label : {
						text : 'Avg. Visits per m2: <?php echo number_format($sector_rv,0);?>'
					}
				}],
				min: 0,
				<?php 
				if (count($period) == 1) {
					$period_temp = $period;
					$period_id = array_shift($period);
					$period = $period_temp;
					$max = ($sector_rv * 2 < $sites_rv[$period_id] * 2) ? $sites_rv[$period_id] * 2 : $sector_rv * 2; 
					echo "max: " . number_format($max, 0, "", "");
				}
				?>
			},
			series: [
			<?php
			foreach ($sites_rv as $key => $value) 
			{
				?>
				{
					dataLabels: {
						enabled: true,
						formatter: function() {
							 return this.point.name;
						}
					},
					name: <?php echo "'".date("F Y", strtotime($key.'-01'))."'";?>,
					data: [{<?php echo "name:'".date("F Y", strtotime($key.'-01'))."'";?>,<?php echo "x:".number_format($sites_rv[$key], 0, '', '');?>,<?php echo "y:".number_format($sites_hc[$key], 0, '', '');?>}]
				},
				<?php
			}
			?>
			]
		});
});
</script>
@include('templates.widget')
