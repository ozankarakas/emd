<div class="row">



	<!-- Widget ID (each widget will need unique ID)-->



	<div class="jarviswidget jarviswidget-color-darken" data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false" data-widget-collapsed="false">



		<header>



			<span class="widget-icon"> <i class="fa fa-table"></i> </span>



			<h2>Programme Analysis - Latest Trends</h2>



		</header>



		<!-- widget div-->



		<div>



			<!-- widget edit box -->



			<div class="jarviswidget-editbox">



				<!-- This area used as dropdown edit box -->



			</div>



			<!-- end widget edit box -->



			<!-- widget content -->



			<?php 



			$count_dates = count($dates);



			$count_TemplateNames = count($TemplateNames);



			?>



			<div class="widget-body no-padding">



				<table id="reportTable" class="table table-striped table-bordered table-hover" width="100%">



					<thead>



						<tr>



							<th></th>



							<th colspan="<?php echo ($count_dates + 2) ?>" style="border-left: solid 1px black;" class="text-center">Total Visits</th>



						</tr>



						<tr>



							<th data-class="expand">Programme</th>



							<?php



							//Sector

							for ($i = 0; $i < $count_dates; $i++)



							{



								?>



								<th><?php echo date("M-y",strtotime($dates[$i]."-01")); ?></th>								



								<?php



							}

							

							?>



							<th>Longitudinal</th>
							<th>View</th>



						</tr>



					</thead>



					<tbody>



						<?php



						for ($i = 0; $i < $count_TemplateNames; $i++)



						{



							foreach ($GLOBALS['template_names'] as $template_name) 



							{



								if($template_name->id == $TemplateNames[$i])



								{



									$tn = $template_name->name;



									break;



								}



							}



							if(!isset($tn))



							{



								$tn = "Other";



							}



							?>



							<tr>



								<td><?php echo $tn; ?></td>



								<?php



								//sector



								$graph_values = "";



								for ($j = 0; $j < $count_dates; $j++)



								{



									$headcount = $hc[$dates[$j]][$TemplateNames[$i]];



									if (! isset($first_value_sector)) 



									{



										if ($demo) 



										{



											$headcount *= (rand(7, 14));



										}



										$result = number_format($headcount, 0);



										$first_value_sector = $result;



									}



									else 



									{



										if ($demo) 



										{



											$headcount *= (rand(7, 14));



										}



										$result = number_format($headcount, 0);



										if ($first_value_sector > $result) 



										{



											$addition = ' <span class="glyphicon glyphicon-chevron-down" style="color: red;"></span>';



										}



										elseif ($first_value_sector < $result) 



										{



											$addition = ' <span class="glyphicon glyphicon-chevron-up" style="color: green;"></span>';



										}



										else 



										{



											$addition = ' <span class="glyphicon glyphicon-minus" style="color: blue;"></span>';



										}



										$first_value_sector = $result;



										$result .= $addition;



									}?>

									<td align="right" style="cursor: pointer;" onclick='displayDetailedGraph("<?php echo $TemplateNames[$i]; ?>","<?php echo date("M-y",strtotime($dates[$j]."-01")); ?>","<?php echo $tn; ?>")'><?php echo $result; ?></td>



									<?php



									$graph_values .= $headcount.",";



								}



								unset($first_value_sector);



								?>



								<td> 



									<div class="sparkline txt-color-blue text-align-center" data-tooltip='<?php echo json_encode($dates);?>'>



										<?php 



										echo trim($graph_values,",");  



										?>



									</div>



								</td>
								<td style="cursor: pointer;" onclick='displayResult("<?php echo $TemplateNames[$i]; ?>","<?php echo $tn; ?>")'>
									<i class="fa fa-eye fa-lg" aria-hidden="true" style="color: #2fb5cc;"></i>
								</td>



							</tr>



							<?php	



						}



						?>



					</tbody>



				</table>

				<div class="alert alert-info fade in" style="margin: 0px;">

					<button class="close" data-dismiss="alert">

						×

					</button>

					<p><i class="fa-fw fa fa-info"></i><strong>Info!</strong> Please click on a programme row to view benchmarks against national averages.</p>

				</div>

			</div>



			<!-- end widget content -->



		</div>



		<!-- end widget div -->



	</div>



	<!-- end widget -->



</div>



<?php 



echo View::make('templates.sparkline');



?>



<script type="text/javascript">



	$('body').delegate('#reportTable tbody tr', "click", function () {



		if(!$(this).hasClass('row-detail'))



		{



			$(this).addClass('highlight_c').siblings().removeClass('highlight_c');



		} 				



	});

	function displayDetailedGraph(id, date, name)
	{
		// console.log(id);
		// console.log(date);


		$.ajax({
			url: '{{ route("activity-barometer.detailedGraphOperations") }}',
			type: 'POST',
			data: {
				'id' 				: id,
				'date' 				: date,
				'name' 				: name
			},

			success: function(data) 
			{
				$('#graph').show().html(data);
				$.smallBox({
					title : "Success",
					content : "<i class='fa fa-clock-o'></i> <i>The graph has been successfully generated!</i>",
					color : "#659265",
					iconSmall : "fa fa-check fa-2x fadeInRight animated",
					timeout : 3000
				});

				$("html, body").animate({ scrollTop: $(document).height()-$(window).height() });
			}
		});
	}


	function displayResult(id, name)



	{



		$.ajax({



			url: '{{ route("activity-barometer.postGraphOperations") }}',



			type: 'POST',



			data: {



				'id' 				: id,



				'name' 				: name



			},



			success: function(data) 



			{



				$('#graph').show().html(data);



				$.smallBox({



					title : "Success",



					content : "<i class='fa fa-clock-o'></i> <i>The graph has been successfully generated!</i>",



					color : "#659265",



					iconSmall : "fa fa-check fa-2x fadeInRight animated",



					timeout : 3000



				});



				$("html, body").animate({ scrollTop: $(document).height()-$(window).height() });



			}



		});



	}



	/* BASIC ;*/

	var responsiveHelper_datatable_col_reorder = undefined;

	var breakpointDefinition = {

		all: 4098,

		tablet : 1024,

		phone : 480

	};



	var oTable = $('#reportTable').dataTable({

		"sDom": "<'dt-toolbar'<'col-xs-6 col-sm-6'lf><'col-sm-6 col-xs-6 hidden-xs'C>r <'col-sm-6 col-xs-6 hidden-xs'T>>"+

		"t"+

		"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-sm-6 col-xs-12'p>>",


		colVis: {

			exclude: [0,13]

		},

		"autoWidth" : true,

		"aaSorting" : [['12',"desc"]],

		"oTableTools": {
        "aButtons" : ["copy", {
            "sExtends": "print",
            "sMessage": "Generated by 4Global <i>(press Esc to close)</i>"
        }, {
            "sExtends" : "collection",
            "sButtonText" : 'Save <span class="caret" />',
            "aButtons" : ["csv", "xls", {
                "sExtends": "pdf",
		//"sTitle": "PDF",
		"sPdfMessage": "Generated by 4Global",
		"sPdfSize": "letter"
		}]
		}],
		"sSwfPath": "<?php echo URL::asset('js/plugin/datatables/swf/copy_csv_xls_pdf.swf') ?>"
		},



		"preDrawCallback" : function() {

		// Initialize the responsive datatables helper once.

		if (!responsiveHelper_datatable_col_reorder) {

			responsiveHelper_datatable_col_reorder = new ResponsiveDatatablesHelper($('#reportTable'), breakpointDefinition);

		}

	},

	"rowCallback" : function(nRow) {

		responsiveHelper_datatable_col_reorder.createExpandIcon(nRow);

	},

	"drawCallback" : function(oSettings) {

		responsiveHelper_datatable_col_reorder.respond();

	}

});

	<?php  

	$hidden = array(1,2,3,4,5,6,7,8,9);

	?>

	oTable.fnSetColumnVis(<?php echo json_encode($hidden); ?>, false);



</script>



@include('templates.widget')