@extends('templates.default')

@section('breadcrumbs')

    {{ Helper::breadcrumbs('Reports & Analysis', 'Performance Report') }}

@stop

@section('left_side')

    {{ Helper::left_side('Reports & Analysis', 'Performance Report') }}

@stop

@section('content')

    <div class="row">

        <div id="widget-grid" class="">

            <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                <div class="jarviswidget jarviswidget-color-blue" data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false">

                    <header>

                    <span class="widget-icon"> <i class="fa fa-table"></i>

                    </span>

                        <h2>Filters</h2>

                    </header>

                    <div>

                        <div class="widget-body no-padding">

                            <div class="form-horizontal">

                                <div class="form-group col-sm-12">



                                </div>

                                <div class="form-group col-sm-12">

                                    <?php GeneralFunctions::get_report_date(); ?>

                                    <?php //GeneralFunctions::get_start_date(); ?>

                                    <?php //GeneralFunctions::get_end_date(); ?>

                                    <?php GeneralFunctions::get_button(); ?>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </article>

        </div>

        <div id="output"></div>

    </div>

@stop

@section('pr-css')

@stop

@section('pr-scripts')

@stop

@section('scripts')

    <script type="text/javascript">


    $( document ).ready(function() {

		$('.modal_loading').hide();

	});


        $(".confirm_update").on("click", function(e)

        {

            $.SmartMessageBox({

                        title   : "<i class='fa fa fa-spinner fa-spin txt-color-green'></i> Confirmation!",

                        content : "Do you want to see the graph with the selected criteria?",

                        buttons : '[No][Yes]'

                    },

                    function(ButtonPressed)

                    {

                        if (ButtonPressed === "Yes")

                        {

                            $.ajax({

                                type: 'POST',

                                url: '{{ URL::route('spreport') }}',

                                data :

                                {

                                    // 'start_date'                : $('#start_date').val(),

                                    // 'end_date'                  : $('#end_date').val(),

                                    'report_month'              : $('#month').val(),

                                    'report_year'              : $('#year').val(),


                                    'ajax_action'               : "get_kpi"

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

                                        $('#output').html(msg);

                                        $.smallBox({

                                            title : "Success",

                                            content : "<i class='fa fa-clock-o'></i> <i>Your report is ready to be viewed.</i>",

                                            color : "#659265",

                                            iconSmall : "fa fa-check fa-2x bounce animated",

                                            timeout : 4000

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

            e.preventDefault();

        });

    </script>

@stop