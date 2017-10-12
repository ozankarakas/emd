
<?php
class FrequencyController extends BaseController {
    public function getIndex()
    {
        return View::make('frequency');
    }
    public function postKPI()
    {
        if(Input::get('ajax_action') == "get_kpi")
        {
            $d_var = GeneralFunctions::arrange_dates_and_headers(Input::all());
            $var = GeneralFunctions::arrange_variables(Input::all());
            $model = "DataRaw".$var["sport"];
            $lc = $var["lc"]; 
            $period = $d_var["period"];
            $widget_exp = $d_var["widget_exp"];
            if($period == 0)
            {
                if($lc == "none")
                {
                    $results = $model::select(DB::raw("DATE_FORMAT(`DateOfBooking`, '%b/%y') as date"), DB::raw("count(*) as count"))
                    ->where('PersonType', "2")
                    ->where('BookingType','LIKE' ,$var["booking"])
                    ->where('Gender','LIKE' ,$var["gender"])
                    ->where('TemplateName','LIKE' ,$var["programme"])
                    ->where('MemberID','<>','0')
                    ->where('BookingType','<>','5')
                    ->whereBetween('Age', array($var["age_start"], $var["age_end"]))
                    ->whereBetween('DateOfBooking', array($d_var["date_start"],$d_var["date_end"]))
                    ->groupBy(DB::raw("month(`DateOfBooking`), year(`DateOfBooking`), `MemberID`"))
                    ->orderBy('DateOfBooking')
                    ->remember(1440)
                    ->get();
                }
                else
                {
                    $results = $model::select(DB::raw("DATE_FORMAT(`DateOfBooking`, '%b/%y') as date"), DB::raw("count(*) as count"))
                    ->whereIn('SiteID', (array)$var["lc"])
                    ->where('PersonType', "2")
                    ->where('BookingType','LIKE' ,$var["booking"])
                    ->where('Gender','LIKE' ,$var["gender"])
                    ->where('TemplateName','LIKE' ,$var["programme"])
                    ->where('MemberID','<>','0')
                    ->where('BookingType','<>','5')
                    ->whereBetween('Age', array($var["age_start"], $var["age_end"]))
                    ->whereBetween('DateOfBooking', array($d_var["date_start"],$d_var["date_end"]))
                    ->groupBy(DB::raw("month(`DateOfBooking`), year(`DateOfBooking`), `MemberID`"))
                    ->orderBy('DateOfBooking')
                    ->remember(1440)
                    ->get();
                }
            }
            else
            {
                if($lc == "none")
                {
                    $results = $model::select(DB::raw("Concat(year(`DateOfBooking`),'/W-',week(`DateOfBooking`,3)) as date"), DB::raw("count(*) as count"))
                    ->where('PersonType', "2")
                    ->where('BookingType','LIKE' ,$var["booking"])
                    ->where('Gender','LIKE' ,$var["gender"])
                    ->where('TemplateName','LIKE' ,$var["programme"])
                    ->where('MemberID','<>','0')
                    ->where('BookingType','<>','5')
                    ->whereBetween('Age', array($var["age_start"], $var["age_end"]))
                    ->whereBetween('DateOfBooking', array($d_var["date_start"],$d_var["date_end"]))
                    ->groupBy(DB::raw("week(`DateOfBooking`,3), year(`DateOfBooking`), `MemberID`"))
                    ->orderBy('DateOfBooking')
                    ->remember(1440)
                    ->get();
                }
                else
                {
                    $results = $model::select(DB::raw("Concat(year(`DateOfBooking`),'/W-',week(`DateOfBooking`,3)) as date"), DB::raw("count(*) as count"))
                    ->whereIn('SiteID', (array)$var["lc"])
                    ->where('PersonType', "2")
                    ->where('BookingType','LIKE' ,$var["booking"])
                    ->where('Gender','LIKE' ,$var["gender"])
                    ->where('TemplateName','LIKE' ,$var["programme"])
                    ->where('MemberID','<>','0')
                    ->where('BookingType','<>','5')
                    ->whereBetween('Age', array($var["age_start"], $var["age_end"]))
                    ->whereBetween('DateOfBooking', array($d_var["date_start"],$d_var["date_end"]))
                    ->groupBy(DB::raw("week(`DateOfBooking`,3), year(`DateOfBooking`), `MemberID`"))
                    ->orderBy('DateOfBooking')
                    ->remember(1440)
                    ->get();
                }
            }
            foreach ($results as $result)
            {
                $dates[] = $result->date;
                $totals[$result->date][] = $result->count;
                if($result->count > $count_temp)
                {
                    $count_temp = $result->count;
                }
            }
            $dates = array_unique($dates);
            $dates = array_values($dates);
            if(count($dates) == 0)
            {
                $dates[] = "";
            }
            ?>
            <div id="widget-grid_ajax">
                <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="jarviswidget jarviswidget-color-green" data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false">
                        <header>
                            <span class="widget-icon"> <i class="fa fa-table"></i>
                            </span>
                            <h2>Results <?php echo $widget_exp;?></h2>
                        </header>
                        <div>
                            <div class="widget-body no-padding">
                                <table class="table table-striped table-bordered table-bordered table-hover" id="data_table_1" style="table-layout: fixed;" width="100%">
                                    <thead>
                                        <tr>
                                            <th data-class="expand">Frequency</th>
                                            <?php
                                            for ($i = 0; $i < count($dates); $i ++)
                                            {
                                                if($i<count($dates)-3)
                                                {
                                                    $hide = "phone, tablet, all";
                                                }
                                                else
                                                {
                                                    $hide = "phone, tablet";
                                                }
                                                ?>
                                                <th data-hide="<?php echo $hide; ?>" ><?php echo $dates[$i];?></th>
                                                <?php
                                            }
                                            ?>
                                            <th>Total</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        for ($i = 0; $i < $count_temp; $i ++)
                                        {
                                            $t = $i + 1;
                                            $value_total = 0;
                                            ?>          
                                            <tr>
                                                <td><b><?php echo $t;?></b></td>            
                                                <?php
                                                for ($j = 0; $j < count($dates); $j ++)
                                                {
                                                    $value = array_count_values($totals[$dates[$j]]);
                                                    $value_total = $value_total + $value[$t];
                                                    ?>
                                                    <td><?php
                                                    echo number_format($value[$t], 0);
                                                    ?></td>
                                                    <?php
                                                }
                                                ?>
                                                <td><b><?php echo number_format($value_total,0)?></b></td>
                                                <?php
                                                $graph_values = "";
                                                for ($j = 0; $j < count($dates); $j ++)
                                                {
                                                    $value = array_count_values($totals[$dates[$j]]);
                                                    $value_total = $value_total + $value[$t];
                                                    $graph_values .= $value[$t].",";
                                                } 
                                                ?>
                                                <td style="cursor: pointer;" onclick='displayResult("<?php echo $t; ?>", <?php echo json_encode($dates);?>, "<?php echo trim($graph_values,","); ?>")'> 
                                                    <div class="sparkline txt-color-blue text-align-center" data-tooltip='<?php echo json_encode($dates);?>'>
                                                        <?php 
                                                        echo trim($graph_values,",");  
                                                        ?>
                                                    </div></td>
                                                    <?php
                                                }
                                                ?>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </article>
                    <div id="show_graph"></div>
                </div>
                <?php
                echo View::make('templates.sparkline');
                echo View::make('templates.datatable', array("table_id" => "data_table_1", "sort" => "true"));
                echo View::make('templates.widget');
                ?>
                <script type="text/javascript">
                $('#data_table_1 tbody tr').on('click', function(event) {
                    $(this).addClass('highlight_c').siblings().removeClass('highlight_c');
                });
                function displayResult(template, dates, values)
                {
                    $.ajax({
                        type: 'POST',
                        url: "<?php echo URL::route('frequency') ?>",
                        data :
                        {
                            'template'                  : template,
                            'dates'                     : dates,
                            'values'                    : values,
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
                                $('html, body').animate({
                                    scrollTop: $("#graph_4_body").offset().top
                                }, 1000);
                            }
                        }
                    });
}
<?php
if($lc == "none")
{
    $show = "National";
}
else
{
    $show = $lc;
}
for ($t = 0; $t < 0; $t ++)
{
    $k = $t + 1;
    ?>
    $('#graph_<?php echo $k;?>').highcharts({
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
            allowDecimals:false,
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
            name: '<?php echo $show;?>',
            data: [
            <?php
            for ($j = 0; $j < count($dates); $j ++)
            {
                echo number_format($value_graph[$dates[$j]][$k], 0) . ",";
            }
            ?>
            ]
        }]
    });
<?php 
}
?>
</script>
<?php
}
elseif(Input::get('ajax_action') == "show_graph")
{
    $template = Input::get('template');
    $dates = Input::get('dates');
    $values = Input::get('values');
    ?>
    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="graph_4_body">
        <div class="jarviswidget jarviswidget-color-red" data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false">
            <header>
                <span class="widget-icon"> <i class="fa fa-table"></i>
                </span>
                <h2>Graph - Longitudinal analysis for Frequency - <?php echo $template; ?></h2>
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
            allowDecimals:false,
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
            name: 'Frequency - <?php echo $template;?>',
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
elseif(Input::get('ajax_action') == "get_lcs")
{
    $mode = Input::get('change_mode');
    GeneralFunctions::get_lcs($mode);
}
}
}    