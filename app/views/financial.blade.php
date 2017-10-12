@extends('templates.default')
@section('breadcrumbs')
{{ Helper::breadcrumbs('Reports', 'Financial KPIs') }}
@stop
@section('left_side')
{{ Helper::left_side('Reports', 'Financial KPIs') }}
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
                            <div class="form-group col-sm-6">
                                <?php GeneralFunctions::get_ages(); ?>
                                <?php GeneralFunctions::get_gender(); ?>
                                <?php GeneralFunctions::get_payment(); ?>
                                <?php GeneralFunctions::get_location(); ?>
                                <div id="lc_div"></div>
                            </div>
                            <div class="form-group col-sm-6">
                                <?php GeneralFunctions::get_start_date(); ?>
                                <?php GeneralFunctions::get_end_date(); ?>
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
$('#location').on('change',function()
{   
    if($(this).val() != 0)
    {   
        $.ajax({ 
            type: 'POST',
            url: '{{ URL::route('financial') }}',
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
$(".confirm_update").on("click", function(e)
{
   if($("#location").val()!=0 && $("#selected_location").val()== "")
    {
        $.smallBox({
            title : "Failed",
            content : "<i class='fa fa-clock-o'></i> <i>Please select a location.</i>",
            color : "#C46A69",
            iconSmall : "fa fa-times fa-2x bounce animated",
            timeout : 4000
        });
        return false;
    }
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
                url: '{{ URL::route('financial') }}',
                data :
                {
                    'age'                       : $('#age').val(),
                    'gender'                    : $('#gender').val(),
                    'payment'                   : $('#payment').val(),
                    'location'                  : $('#location').val(),
                    'lc'                        : $('#selected_location').val(),
                    'start_date'                : $('#start_date').val(),
                    'end_date'                  : $('#end_date').val(),
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