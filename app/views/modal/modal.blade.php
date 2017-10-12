<?php
$person_type = ["All","Casual","Member"];
$age = ["All","< 14 yrs","15-19","20-24","25-29","30-34","35-39","40-44","45-49","50-54","55-59","60-64","65+"];
$gender = ["All","Unknown","Male","Female"];
$no_of_sites = ["All","1","2 to 3","4 to 5","6 to 10"];
$data = json_decode($data, true);
?>
<div class="modal fade" id="{{$data_model}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
				<h4 class="modal-title-{{$color}}" id="myModalLabel">Custom Filters</h4>
			</div>
			<div class="modal-body">
				<form id="model_filters">
					<div class="panel-group smart-accordion-default" id="accordion">
						<div class="panel panel-default">
							<div class="panel-heading">
								<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" class="collapsed"> <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> Casual/Member</a></h4>
							</div>
							<div id="collapseTwo" class="panel-collapse collapse">
								<div class="panel-body">
									<fieldset>
										<div class="form-group">
											<label><b>Consumer type</b></label>
											@for ($i = 0; $i < count($person_type); $i++)
											<div class="radio">
												<label>
													<input <?php echo in_array($i, (array)$data["person_type"]) ? 'checked="checked"' : ""; ?>type="radio" class="radiobox" name="person_type" id="person_type{{$i}}" value="{{$i}}">
													<span>{{$person_type[$i]}}</span><span style="color: blue; font-size: 10px;"> (<?php echo number_format($person_count[$i],0)?>)</span> 
												</label>
											</div>
											@endfor
										</div>
									</fieldset>
								</div>
							</div>
						</div>
						<div class="panel panel-default">
							<div class="panel-heading">
								<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapseThree" class="collapsed"> <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> Demographics </a></h4>
							</div>
							<div id="collapseThree" class="panel-collapse collapse">
								<div class="panel-body">
									<fieldset>
										<div class="form-group">
											<label><b>Age</b></label>
											@for ($i = 0; $i < count($age); $i++)
											<div class="checkbox">
												<label>
													<input <?php echo in_array($i, (array)$data["age"]) ? 'checked="checked"' : ""; ?>type="checkbox" class="checkbox" name="age" id="age{{$i}}" value="{{$i}}">
													<span>{{$age[$i]}}</span><span style="color: blue; font-size: 10px;"> (<?php echo number_format($age_count[$i],0)?>)</span>
												</label>
											</div>
											@endfor
											<label><b>Gender</b></label>
											@for ($i = 0; $i < count($gender); $i++)
											<div class="checkbox">
												<label>
													<input <?php echo in_array($i, (array)$data["gender"]) ? 'checked="checked"' : ""; ?>type="checkbox" class="checkbox" name="gender" id="gender{{$i}}" value="{{$i}}">
													<span>{{$gender[$i]}}</span><span style="color: blue; font-size: 10px;"> (<?php echo number_format($gender_count[$i],0)?>)</span> 
												</label>
											</div>
											@endfor
										</div>
									</fieldset>
								</div>
							</div>
						</div>
						<div class="panel panel-default">
							<div class="panel-heading">
								<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapseFour" class="collapsed"> <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> Location </a></h4>
							</div>
							<div id="collapseFour" class="panel-collapse collapse">
								<div class="panel-body">
									<fieldset>
										<div class="form-group">
											<label><b>Number of sites owned</b></label>
											@for ($i = 0; $i < count($no_of_sites); $i++)
											<div class="checkbox">
												<label>
													<input <?php echo in_array($i, (array)$data["no_of_sites"]) ? 'checked="checked"' : ""; ?>type="checkbox" class="checkbox" name="no_of_sites" id="no_of_sites{{$i}}" value="{{$i}}">
													<span>{{$no_of_sites[$i]}}</span>
												</label>
											</div>
											@endfor
											<?php GeneralFunctions::get_location_filter_page_view($data["location"]); ?>
											<div id="lc_div"></div>
										</div>
									</fieldset>
								</div>
							</div>
						</div>
						<div class="panel panel-default">
							<div class="panel-heading">
								<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapseFive" class="collapsed"> <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> Time Period </a></h4>
							</div>
							<div id="collapseFive" class="panel-collapse collapse">
								<div class="panel-body">
									<fieldset>
										<div class="form-group">
											<label><b>End date</b></label>
											<div class="input-group">
												<input type="text" name="date" placeholder="Select a date" class="form-control datepicker" data-dateformat="dd-mm-yy" value="<?php echo isset($data["date"]) ? $data["date"] : date("d-m-Y"); ?>" style="z-index:9999;">
												<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
											</div>
										</div>
									</fieldset>
								</div>
							</div>
						</div>
						<div class="panel panel-default">
							<div class="panel-heading">
								<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapseSix" class="collapsed"> <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> Programme </a></h4>
							</div>
							<div id="collapseSix" class="panel-collapse collapse">
								<div class="panel-body">
									<fieldset>
										<div class="form-group">
											<?php GeneralFunctions::get_programme_filter_multi_page_view($data["programme"]); ?>
										</div>
									</fieldset>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button id="dismiss" type="button" class="btn btn-default" data-dismiss="modal">
					Close
				</button>
				<button id="apply" type="button" class="btn btn-primary">
					Apply
				</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@section('pr-scripts')
<script type="text/javascript">
	$("#apply").on("click", function() 
	{
		//LOCATION
		if($("#location").val()==null)
		{
			$.smallBox({
				title : "Validation",
				content : "<i class='fa fa-clock-o'></i> <i>Please select location.</i>",
				color : "#C46A69",
				iconSmall : "fa fa-times fa-2x bounce animated",
				timeout : 4000
			});
			return;
		}

		//SELECTED LOCATION
		if($("#selected_location").val()==null && $('#selected_location').is(':visible')) 
		{
			$.smallBox({
				title : "Validation",
				content : "<i class='fa fa-clock-o'></i> <i>Please select location.</i>",
				color : "#C46A69",
				iconSmall : "fa fa-times fa-2x bounce animated",
				timeout : 4000
			});
			return;
		}
		
		//PROGRAMME
		if($("#programme").val()==null)
		{
			$.smallBox({
				title : "Validation",
				content : "<i class='fa fa-clock-o'></i> <i>Please select programme.</i>",
				color : "#C46A69",
				iconSmall : "fa fa-times fa-2x bounce animated",
				timeout : 4000
			});
			return;
		}

		$('.modal_loading').show();
		$.ajax({
			type: 'POST',
			url: '{{ URL::route('doverallkpifilters') }}',
			data :
			{
				'filter_name'			: "custom",
				'filters'               : JSON.stringify($('#model_filters').serializeObject())
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
					$('.modal_loading').show();
					location.reload();
				}
			}
		});
	});
	<?php
	$checkboxes = ["person_type","age","gender","no_of_sites"];
	for ($i=0; $i < count($checkboxes); $i++) 
	{ 
		$name = $checkboxes[$i];
		?>
		$('#<?php echo $name; ?>0').change(function () 
		{
			if (this.checked) 
			{
				$('input[name="<?php echo $name; ?>"]').not('#<?php echo $name; ?>0').prop('checked', false);
			}
		});
		$('input[name="<?php echo $name; ?>"]').not('#<?php echo $name; ?>0').change(function () 
		{
			if (this.checked) 
			{
				$('#<?php echo $name; ?>0').prop('checked', false);
			}
		});
		<?php  
	}
	?>
	$("#programme").on("change", function() 
	{
		if(jQuery.inArray("0", $(this).val()) !== -1)
		{
			$(this).select2("val",0);
		}
	});
	if($('#location').val() != 0)
	{   
		$.ajax({ 
			type: 'POST',
			url: '{{ URL::route('filters') }}',
			data : 
			{
				'change_mode'               : $('#location').val(),
				'selected_location'			: "<?php echo $data['selected_location']; ?>",
				'ajax_action'               : 'get_lcs'
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
				} else {
					$('#lc_div').hide().slideDown("fast").html(msg);
				}
			}
		});
	}
	$('#location').on('change',function()
	{   
		if($(this).val() != 0)
		{   
			$.ajax({ 
				type: 'POST',
				url: '{{ URL::route('filters') }}',
				data : 
				{
					'change_mode'               : $(this).val(),
					'ajax_action'               : 'get_lcs'
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
					} else {
						$('#lc_div').hide().slideDown("fast").html(msg);
					}
				}
			});
		}
		else
		{
			$('#lc_div').hide().slideUp("fast");
			$('#selected_location').val("");
		}
	});
</script>
@stop