<?php
class ConsistencyController extends BaseController {
    public function getIndex()
    {
        return View::make('consistency');
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
                    $results = $model::select(DB::raw("DATE_FORMAT(`DateOfBooking`, '%b/%y') as date"), DB::raw("count(*) as count"), 'MemberID')
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
                    $results = $model::select(DB::raw("DATE_FORMAT(`DateOfBooking`, '%b/%y') as date"), DB::raw("count(*) as count"), 'MemberID')
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
                   $results = $model::select(DB::raw("Concat(year(`DateOfBooking`),'/W-',week(`DateOfBooking`,3)) as date"), DB::raw("count(*) as count"), 'MemberID')
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
                $results = $model::select(DB::raw("Concat(year(`DateOfBooking`),'/W-',week(`DateOfBooking`,3)) as date"), DB::raw("count(*) as count"), 'MemberID')
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
           $totals[$result->date][] = $result->MemberID;
           $occurrence[$result->MemberID][] = $result->count;
       }

       $dates = array_unique($dates);
       $dates = array_values($dates);

       if(count($totals) >1)
       {
           $intersected_memberids = array_values(call_user_func_array("array_intersect", $totals));
       }
       else
       {
            $intersected_memberids = array_values($totals);
            $intersected_memberids = $intersected_memberids[0];
       }

    $consistency = array();
    $count_temp = 0;


    for ($i = 0; $i < count($intersected_memberids); $i ++)
    {
        $freq = min($occurrence[$intersected_memberids[$i]]);

        $consistency[$freq][] = 1;
        if($freq > $count_temp)
        {
            $count_temp = $freq;
        }
    }

    if($lc == "none")
    {
        $results = $model::select(DB::raw("count(DISTINCT `MemberID`) as count"))
        ->where('PersonType', "2")
        ->where('MemberID','<>','0')
        ->where('BookingType','<>','5')
        ->whereBetween('DateOfBooking', array($d_var["date_start"],$d_var["date_end"]))
        ->remember(1440)
        ->get(); 
    }
    else
    {
        $results = $model::select(DB::raw("count(DISTINCT `MemberID`) as count"))
        ->whereIn('SiteID', (array)$var["lc"])
        ->where('PersonType', "2")
        ->where('MemberID','<>','0')
        ->where('BookingType','<>','5')
        ->whereBetween('DateOfBooking', array($d_var["date_start"],$d_var["date_end"]))
        ->remember(1440)
        ->get();
    }
    $total_member = $results[0]->count;
    $con_number_recursive = 0;
    $con_array = array();
    for ($i = $count_temp - 1; $i >= 0; $i --)
    {
        $t = $i + 1;
        $con_number = array_sum($consistency[$t]);
        $con_number_recursive = $con_number_recursive + $con_number;
        $con_array[] = $con_number_recursive;
    }
    $con_array = array_reverse($con_array);

    // value for graphs
    $graph_values_percentage = array();
    $graph_values_numbers = array();
    for ($i = 0; $i < $count_temp; $i ++)
    {
        $t = $i + 1;
        $con_number = array_sum($consistency[$t]);
        $graph_values_percentage[] = number_format($con_number / $total_member * 100, 2);
        $graph_values_numbers[] = $con_number;
    }
    ?>
    <div id="widget-grid_ajax">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="jarviswidget jarviswidget-color-green" data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false">
                <header>
                    <span class="widget-icon"> <i class="fa fa-table"></i>
                    </span>
                    <h2>Results <?php echo $widget_exp." - Total Unique Members ".$var["sport"]." : $total_member";?></h2>
                </header>
                <div>
                    <div class="widget-body no-padding">
                        <table class="table table-striped table-bordered table-bordered table-hover" id="data_table_1" style="table-layout: fixed;" width="100%">
                            <thead>
                                <tr>
                                    <th data-class="expand">Frequency (at least # of times)</th>
                                    <th>Total</th>
                                    <th data-hide="phone,tablet">% of total unique members</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                for ($i = 0; $i < count($con_array); $i ++)
                                {
                                    $t = $i + 1;
                                    ?>
                                    <tr>      
                                        <td><b><?php echo $t;?></b></td>
                                        <td align="right"><?php echo number_format($con_array[$i]);?></td>
                                        <td align="right"><?php echo number_format(($con_array[$i] / $total_member)*100,2);?> %</td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </article>
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="jarviswidget jarviswidget-color-red" data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false">
                <header>
                    <span class="widget-icon"> <i class="fa fa-table"></i>
                    </span>
                    <h2>Graph - Consisteny</h2>
                </header>
                <div>
                    <div class="widget-body no-padding">
                        <div id="graph_1" style="margin-right: 10px; padding-bottom: 2px;"></div>
                    </div>
                </div>
            </div>
        </article>
    </div>
    <?php
    echo View::make('templates.datatable', array("table_id" => "data_table_1", "sort" => "true"));
    echo View::make('templates.widget');
    ?>
    <script type="text/javascript">
    $(function () {
        var colors = Highcharts.getOptions().colors,
        categories = ['<?php echo $var["sport"]; ?>', 'Rest'],
        name = 'Consistency',
        data = [
        {
            y: <?php echo array_sum($graph_values_percentage);?>,
            color: colors[1],
            drilldown: {
                name: 'Frequency',
                categories: [
                <?php
                for ($i = 1; $i <= count($graph_values_percentage); $i ++)
                {
                    $k = $i - 1;
                    echo "'Frequency " . $i . " - ($graph_values_numbers[$k])',";
                }
                ?>],
                data: [<?php
                for ($i = 0; $i < count($graph_values_percentage); $i ++)
                {
                    echo $graph_values_percentage[$i] . ",";
                }
                ?>],
                color: colors[1]
            }
        },
        {
          y: <?php echo (100 - array_sum($graph_values_percentage));?>,
          color: colors[0],
          drilldown: {
            name: 'Rest',
            categories: ['Rest'],
            data: [<?php echo (100 - array_sum($graph_values_percentage));?>],
            color: colors[0]
        }
    }];
    // Build the data arrays
    var sportData = [];
    var frequencyData = [];
    for (var i = 0; i < data.length; i++) {
        // add sport data
        sportData.push({
            name: categories[i],
            y: data[i].y,
            color: data[i].color
        });
        // add freq. data
        for (var j = 0; j < data[i].drilldown.data.length; j++) {
            var brightness = 0.2 - (j / data[i].drilldown.data.length) / 5 ;
            frequencyData.push({
                name: data[i].drilldown.categories[j],
                y: data[i].drilldown.data[j],
                color: Highcharts.Color(data[i].color).brighten(brightness).get()
            });
        }
    }
    $('#graph_1').highcharts({
        chart: {
            type: 'pie'
        },
        title: {
            text: ''
        },
        yAxis: {
            title: {
                text: ''
            }
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                showInLegend: false
            }
        },
        tooltip: {
            pointFormat: '<b>{point.y:.2f} %</b>'
        },
        series: [{
            name: 'Sport',
            data: sportData,
            size: '60%',
            dataLabels: {
                formatter: function() {
                    return this.y > 5 ? this.point.name : null;
                },
                color: 'white',
                distance: -40
            }
        }, {
            name: 'Frequency',
            data: frequencyData,
            size: '80%',
            innerSize: '60%',
            dataLabels: {
                formatter: function() {
                    // display only if larger than 1
                    return this.y > 1 ? '<b>'+ this.point.name +':</b> '+ this.y +'%'  : null;
                }
            }
        }]
    });
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