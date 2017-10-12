<div class="row">
	<div class="jarviswidget jarviswidget-color-darken" data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false" data-widget-collapsed="false">
		<header>
			<span class="widget-icon"> <i class="fa fa-table"></i> </span>
			<h2>{{ $date }} - Summary</h2>
		</header>
		<!-- widget div-->
		<div>
			<!-- widget edit box -->
			<div class="jarviswidget-editbox">
				<!-- This area used as dropdown edit box -->
			</div>
			<!-- end widget edit box -->
			<!-- widget content -->
			<div class="widget-body no-padding">
				<table id="" class="table table-striped table-bordered table-hover" width="100%">
					<thead>
						<tr>						
							<th>Name</th>
							<th>#</th>
						</tr>
					</thead>

					<tbody>
						<tr> 
							<td>Unique Members</td>							
							<td>{{ number_format($table_data['unique_member_count'], 0) }}</td>							
						</tr>
						{{-- <tr>
							<td>1st Time Activity</td>
							<td>-</td>
						</tr> --}}

						<tr>
							<td>1st Time Group Workout Activity</td>
							<td>{{ number_format($table_data['unique_member_count']-count($table_data['1st_time_group_work_out']), 0) }}</td>
						</tr>

						<tr>
							<td>1st Time {{ $name }}</td>
							<td>{{ number_format($table_data['unique_member_count']-count($table_data['1st_time_activity']), 0) }}</td>
						</tr>

					</tbody>
				</table>
			</div>
			<!-- end widget content -->
		</div>
		<!-- end widget div -->
	</div>
	<!-- end widget -->
</div>

<div class="row">
	<div class="jarviswidget jarviswidget-color-darken" data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false" data-widget-collapsed="false">
		<header>
			<span class="widget-icon"> <i class="fa fa-table"></i> </span>
			<h2>Participation in other styles in {{ date('M-y', strtotime($date.' -1 month')) }} for 1st time "{{ $name }}" participants in {{ $date }}</h2>
		</header>
		<!-- widget div-->
		<div>
			<!-- widget edit box -->
			<div class="jarviswidget-editbox">
				<!-- This area used as dropdown edit box -->
			</div>
			<!-- end widget edit box -->
			<!-- widget content -->
			<div class="widget-body no-padding">
				<table id="summary_table" class="table table-striped table-bordered table-hover" width="100%">
					<thead>
						<tr>	
							<th>Name</th>
							<th>Count</th>						
							{{-- <th>Unique Member Count</th>
							<th>No Activities</th> --}}
						</tr>
					</thead>

					<tbody>
					@foreach($other_group_workout_activities as $key => $value)
						<tr>
							@if($template_names[$key] == '')
							<td>Others</td>
							@else
							<td>{{ $template_names[$key] }}</td>
							@endif
							<td>{{ number_format($value, 0) }}</td>
							
							{{-- <td>{{ $table_data['unique_member_count'] }}</td>
							<td>{{ $table_data['no_activity'] }}</td> --}}
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
			<!-- end widget content -->
		</div>
		<!-- end widget div -->
	</div>
	<!-- end widget -->
</div>

<script type="text/javascript">
	var oTable = $('#summary_table').dataTable({

		"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-6 hidden-xs'C>r>"+

		"t"+

		"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-sm-6 col-xs-12'p>>",

		// colVis: {

		// 	exclude: [0,13]

		// },

		"autoWidth" : true,

		"aaSorting" : [['1',"desc"]],

		"preDrawCallback" : function() {

		// Initialize the responsive datatables helper once.

		if (!responsiveHelper_datatable_col_reorder) {

			responsiveHelper_datatable_col_reorder = new ResponsiveDatatablesHelper($('#summary_table'), breakpointDefinition);

		}

	},

	"rowCallback" : function(nRow) {

		responsiveHelper_datatable_col_reorder.createExpandIcon(nRow);

	},

	"drawCallback" : function(oSettings) {

		responsiveHelper_datatable_col_reorder.respond();

	}

});
</script>

@include('templates.widget')