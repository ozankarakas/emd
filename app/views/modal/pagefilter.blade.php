<?php  

$person_type = ["All","Casual","Member"];

$age = ["All","< 14 yrs","15-19","20-24","25-29","30-34","35-39","40-44","45-49","50-54","55-59","60-64","65+"];

$gender = ["All","Unknown","Male","Female"];

$no_of_sites = ["All","1","2 to 3","4 to 5","6 to 10"];

$data = json_decode($data, true);

?>

<article class="col-xs-12 col-sm-6 col-md-4 col-lg-4">

	<div class="jarviswidget jarviswidget-color-{{$color}}" data-widget-colorbutton="false">

		<header>

			<span class="widget-icon"> <i class="fa fa-cog fa-spin"></i></span>

			<h2><?php echo isset($data["filter_name"]) ? $data["filter_name"] : "Filters - $filter_id"; ?></h2>

		</header>

		<div>

			<div class="widget-body">

				<input name="filter_id" id="filter_id<?php echo $filter_id; ?>" value="{{$filter_id}}" class="form-control" type="hidden">

				<form id="{{$form}}">

					<div class="jarviswidget-editbox">

						<input id="filter_name<?php echo $filter_id; ?>" name="filter_name" class="form-control" type="text">

					</div>

					<legend class="legend-color-{{$color}}">Casual/Member</legend>

					<fieldset>

						<div class="form-group">

							<label><b>Consumer type</b></label>

							@for ($i = 0; $i < count($person_type); $i++)

							<div class="radio">

								<label>

									<input <?php echo in_array($i, (array)$data["person_type"]) ? 'checked="checked"' : ""; ?>type="radio" class="radiobox" name="person_type" id="person_type<?php echo $filter_id;?>{{$i}}" value="{{$i}}">

									<span>{{$person_type[$i]}}</span><span style="color: blue; font-size: 10px;"> (<?php echo number_format($person_count[$i],0)?>)</span> 

								</label>

							</div>

							@endfor

						</div>

					</fieldset>

					<legend class="legend-color-{{$color}}">Demographics</legend>

					<fieldset>

						<div class="form-group">

							<label><b>Age</b></label>

							@for ($i = 0; $i < count($age); $i++)

							<div class="checkbox">

								<label>

									<input <?php echo in_array($i, (array)$data["age"]) ? 'checked="checked"' : ""; ?>type="checkbox" class="checkbox c_age<?php echo $filter_id;?>" name="age" id="age<?php echo $filter_id;?>{{$i}}" value="{{$i}}">

									<span>{{$age[$i]}}</span><span style="color: blue; font-size: 10px;"> (<?php echo number_format($age_count[$i],0)?>)</span>

								</label>

							</div>

							@endfor

							<label><b>Gender</b></label>

							@for ($i = 0; $i < count($gender); $i++)

							<div class="checkbox">

								<label>

									<input <?php echo in_array($i, (array)$data["gender"]) ? 'checked="checked"' : ""; ?>type="checkbox" class="checkbox c_gender<?php echo $filter_id;?>" name="gender" id="gender<?php echo $filter_id;?>{{$i}}" value="{{$i}}">

									<span>{{$gender[$i]}}</span><span style="color: blue; font-size: 10px;"> (<?php echo number_format($gender_count[$i],0)?>)</span> 

								</label>

							</div>

							@endfor

						</div>

					</fieldset>

					<legend class="legend-color-{{$color}}">Location</legend>

					<fieldset>

						<div class="form-group">

							<!--<label><b>Number of sites owned</b></label>

							@for ($i = 0; $i < count($no_of_sites); $i++)

							<div class="checkbox">

								<label>

									<input <?php echo in_array($i, (array)$data["no_of_sites"]) ? 'checked="checked"' : ""; ?>type="checkbox" class="checkbox c_no_of_sites<?php echo $filter_id;?>" name="no_of_sites" id="no_of_sites<?php echo $filter_id;?>{{$i}}" value="{{$i}}">

									<span>{{$no_of_sites[$i]}}</span>

								</label>

							</div>

							@endfor-->

							<?php GeneralFunctions::get_location_filter_page_view($data["location"], $filter_id); ?>

							<div id="lc_div<?php echo $filter_id; ?>"></div>

						</div>

					</fieldset>

					<legend class="legend-color-{{$color}}">Time Periods</legend>

					<fieldset>

						<div class="form-group">

							<label><b>End date</b></label>

							<div class="input-group">

								<input type="text" name="date" placeholder="Select a date" class="form-control datepicker" data-dateformat="dd-mm-yy" value="<?php echo isset($data["date"]) ? $data["date"] : date("d-m-Y"); ?>">

								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>

							</div>

						</div>

					</fieldset>

					<legend class="legend-color-{{$color}}">Programme</legend>

					<fieldset>

						<div class="form-group">

							<?php GeneralFunctions::get_programme_filter_multi_page_view($data["programme"], $filter_id); ?>

						</div>

					</fieldset>

				</form>

				<button data-filter="{{$filter_id}}" type="submit" {{$disabled}} class="btn btn-primary btn-sm btn-block confirm_save<?php echo $filter_id; ?>">

					<i class="fa fa-save fa-lg"></i> Submit

				</button>

			</div>

		</div>

	</div>

</article>

@section("pr-scripts$filter_id")

<script type="text/javascript">

	$(".confirm_save<?php echo $filter_id; ?>").on("click", function() 

	{

		var filter_id = $(this).attr("data-filter");



		



		//PERSON TYPE

		if($("#filter" + filter_id + " input[name='person_type']:checked").length==0)

		{

			$.smallBox({

				title : "Validation",

				content : "<i class='fa fa-clock-o'></i> <i>Please select person type.</i>",

				color : "#C46A69",

				iconSmall : "fa fa-times fa-2x bounce animated",

				timeout : 4000

			});

			return;

		}



		//AGE

		if($("#filter" + filter_id + " input[name='age']:checked").length==0)

		{

			$.smallBox({

				title : "Validation",

				content : "<i class='fa fa-clock-o'></i> <i>Please select age.</i>",

				color : "#C46A69",

				iconSmall : "fa fa-times fa-2x bounce animated",

				timeout : 4000

			});

			return;

		}



		//GENDER

		if($("#filter" + filter_id + " input[name='gender']:checked").length==0)

		{

			$.smallBox({

				title : "Validation",

				content : "<i class='fa fa-clock-o'></i> <i>Please select gender.</i>",

				color : "#C46A69",

				iconSmall : "fa fa-times fa-2x bounce animated",

				timeout : 4000

			});

			return;

		}



		//NO OF SITES

		// if($("#filter" + filter_id + " input[name='no_of_sites']:checked").length==0)

		// {

		// 	$.smallBox({

		// 		title : "Validation",

		// 		content : "<i class='fa fa-clock-o'></i> <i>Please select no of sites.</i>",

		// 		color : "#C46A69",

		// 		iconSmall : "fa fa-times fa-2x bounce animated",

		// 		timeout : 4000

		// 	});

		// 	return;

		// }



		//SELECTED LOCATION

		if($("#filter" + filter_id + " select[name='location']").val()!=0 && ($("#filter" + filter_id + " select[name='selected_location']").val()==null || $("#filter" + filter_id + " select[name='selected_location']").val()==""))

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

		if($("#filter" + filter_id + " select[name='programme']").val()==null)

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



		$.SmartMessageBox({

			title   : "<i class='fa fa fa-spinner fa-spin txt-color-green'></i> Confirmation!",

			content : "Do you want to save your personal filter #"+filter_id+"? This could take up to 10 minutes, depending on your internet connection. Please hold on, while we create your dashboards.",

			buttons : '[No][Yes]'

		},

		function(ButtonPressed)

		{

			if (ButtonPressed === "Yes")

			{

				$.ajax({

					type: 'POST',

					url: '{{ URL::route('filters') }}',

					data :

					{

						'filters'               : JSON.stringify($('#filter'+filter_id).serializeObject()),

						'filter_id'				: $('#filter_id'+filter_id).val(),

						'filter_name'			: $('#filter_name'+filter_id).val(),

						'ajax_action'			: "save_filters"

					},

					beforeSend: function(request) {

						$.smallBox({

							title : "Success",

							content : "<i class='fa fa-clock-o'></i> <i>Thanks for submitting your filter #"+filter_id+". Your dashboards are being configured. You should receive a notification from us shortly when the configuration is completed.</i>",

							color : "#659265",

							iconSmall : "fa fa-check fa-2x bounce animated",

							timeout : 4000

						});

						$('.modal_loading').hide();

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

					}

				});

			}

			if (ButtonPressed === "No")

			{

				$.smallBox({

					title : "Cancelled",

					content : "<i class='fa fa-clock-o'></i> <i>Submmison cancelled.</i>",

					color : "#C46A69",

					iconSmall : "fa fa-times fa-2x bounce animated",

					timeout : 4000

				});

			}

		});

	});

<?php

$checkboxes = ["person_type$filter_id","age$filter_id","gender$filter_id","no_of_sites$filter_id"];

for ($i=0; $i < count($checkboxes); $i++) 

{ 

	$name = $checkboxes[$i];

	?>

	$('#<?php echo $name; ?>0').change(function () 

	{

		if (this.checked) 

		{

			$('.c_<?php echo $name; ?>').not('#<?php echo $name; ?>0').prop('checked', false);

		}

	});

	$('.c_<?php echo $name; ?>').not('#<?php echo $name; ?>0').change(function () 

	{

		if (this.checked) 

		{

			$('#<?php echo $name; ?>0').prop('checked', false);

		}

	});

	<?php  

}

?>

$("#programme<?php echo $filter_id; ?>").on("change", function() 

{

	if(jQuery.inArray("0", $(this).val()) !== -1)

	{

		$(this).select2("val",0);

	}

});



if($('#location<?php echo $filter_id; ?>').val() != 0)

{   

	$.ajax({ 

		type: 'POST',

		url: '{{ URL::route('filters') }}',

		data : 

		{

			'change_mode'               : $('#location<?php echo $filter_id; ?>').val(),

			'selected_location'			: '<?php echo $data["selected_location"]; ?>',

			'id'						: '<?php echo $filter_id ?>',

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

				$('#lc_div<?php echo $filter_id; ?>').hide().slideDown("fast").html(msg);

			}

		}

	});

}

$('#location<?php echo $filter_id; ?>').on('change',function()

{   

	if($(this).val() != 0)

	{   

		$.ajax({ 

			type: 'POST',

			url: '{{ URL::route('filters') }}',

			data : 

			{

				'change_mode'               : $(this).val(),

				'id'						: '<?php echo $filter_id ?>',

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

					$('#lc_div<?php echo $filter_id; ?>').hide().slideDown("fast").html(msg);

				}

			}

		});

	}

	else

	{

		$('#lc_div<?php echo $filter_id; ?>').hide().slideUp("fast");

		$('#selected_location<?php echo $filter_id; ?>').val("");

	}

});



</script>

@stop

