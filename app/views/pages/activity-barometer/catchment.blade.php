@extends('templates.default')

@section('breadcrumbs')

{{ Helper::breadcrumbs('Maps', 'Heat Map') }}

@stop

@section('left_side')

{{ Helper::left_side('Maps', 'Heat Map') }}

@stop

@section('content')

<?php //header('Access-Control-Allow-Origin: *'); ?>

<?php 

error_reporting(0);

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

?>

<!-- NEW WIDGET START -->

<div id="widget-grid_ajax">

    <div class="row">

        <div id="graph"></div>

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

<link rel="stylesheet" href="//cartodb-libs.global.ssl.fastly.net/cartodb.js/v3/3.15/themes/css/cartodb.css" />

@stop

@section('pr-scripts')

@stop

@section('scripts')

<script src="//cartodb-libs.global.ssl.fastly.net/cartodb.js/v3/3.15/cartodb.js"></script>

<script type="text/javascript">

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

                    'filter_name'           : ($(this).attr('data-name')),

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

$(function() {

    $.ajax({

        url: '{{ route("activity-barometer.postGraphCatchment") }}',

        type: 'POST',

        data :

        {

            'filters'               : <?php echo "'".$filter_data."'"; ?>

        },

        beforeSend: function(request) {

            return request.setRequestHeader("X-CSRF-Token", $("meta[name='token']").attr('content'));

        },

        success: function(data) 

        {

            $('#graph').html(data);

            $.smallBox({

                title : "Success",

                content : "<i class='fa fa-clock-o'></i> <i>The report has been successfully generated!</i>",

                color : "#659265",

                iconSmall : "fa fa-check fa-2x fadeInRight animated",

                timeout : 3000

            });

        }

    });

});

</script>

@stop