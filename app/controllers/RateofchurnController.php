<?php
class RateofchurnController extends BaseController {
    public function getIndex()
    {
        return View::make('rateofchurn');
    }
    public function postKPI()
    {
        if(Input::get('ajax_action') == "get_kpi")
        {
            $d_var = GeneralFunctions::arrange_dates_and_headers_rateofchurn(Input::all());
            $var = GeneralFunctions::arrange_variables(Input::all());
            $model = "DataRaw".$var["sport"];
            $count_temp = 0;
            $lc = $var["lc"]; 
            $period = $d_var["period"];
            $frequency = $d_var["frequency"];
            $widget_exp = $d_var["widget_exp"];
            if($period == 0)
            {
                $s = '1 month';
            }
            else
            {
                $s = '1 week';
            }
            $start    = new DateTime($d_var["date_start"]);
            $end      = new DateTime($d_var["date_end"]);
            $interval = DateInterval::createFromDateString($s);
            $date_period   = new DatePeriod($start, $interval, $end);
            $counter = 0;
            foreach ($date_period as $dt) 
            {
                $date_start = $dt->format("Y-m-d 00:00:00");
                if($period == 0)
                {
                    $date_end = $dt->format("Y-m-t 23:59:59");
                }
                else
                {
                    $date_end = date('Y-m-d 23:59:59', strtotime('next sunday', strtotime($date_start)));
                }
                if($lc == "none")
                {
                    if($counter == 0)
                    {
                        $member_ids = $model::select("MemberID")
                        ->where('PersonType', "2")
                        ->where('BookingType','LIKE' ,$var["booking"])
                        ->where('Gender','LIKE' ,$var["gender"])
                        ->where('TemplateName','LIKE' ,$var["programme"])
                        ->where('MemberID','<>','0')
                        ->where('BookingType','<>','5')
                        ->whereBetween('Age', array($var["age_start"], $var["age_end"]))
                        ->whereBetween('DateOfBooking', array($date_start, $date_end))
                        ->groupBy('MemberID')
                        ->having(DB::raw("COUNT(*)"), '>=', $frequency)
                        ->remember(1440)
                        ->get()
                        ->toArray();
                        $count = count($member_ids);
                        for ($i=0; $i < $count; $i++) 
                        { 
                            $flatten_member_ids_start[] = $member_ids[$i]["MemberID"];
                        }
                    }
                    else
                    {
                        if(count($member_ids) == 0)
                        {
                            break;
                        }
                        else
                        {
                            $results = $model::select("MemberID")
                            ->where('PersonType', "2")
                            ->where('BookingType','LIKE' ,$var["booking"])
                            ->where('Gender','LIKE' ,$var["gender"])
                            ->where('TemplateName','LIKE' ,$var["programme"])
                            ->where('MemberID','<>','0')
                            ->where('BookingType','<>','5')
                            ->whereBetween('Age', array($var["age_start"], $var["age_end"]))
                            ->whereBetween('DateOfBooking', array($date_start, $date_end))
                            ->groupBy('MemberID')
                            ->having(DB::raw("COUNT(*)"), '>=', $frequency)
                            ->remember(1440)
                            ->get()
                            ->toArray();
                            $count = count($results);
                            /*unsetting array to reset*/
                            unset($flatten_member_ids_cont);
                            for ($i=0; $i < $count; $i++) 
                            { 
                                $flatten_member_ids_cont[] = $results[$i]["MemberID"];
                            }
                            $flatten_member_ids_start = array_intersect($flatten_member_ids_start, $flatten_member_ids_cont);
                        }
                    }
                }
                else
                {
                    if($counter == 0)
                    {
                        $member_ids = $model::select("MemberID")
                        ->whereIn('SiteID', (array)$var["lc"])
                        ->where('PersonType', "2")
                        ->where('BookingType','LIKE' ,$var["booking"])
                        ->where('Gender','LIKE' ,$var["gender"])
                        ->where('TemplateName','LIKE' ,$var["programme"])
                        ->where('MemberID','<>','0')
                        ->where('BookingType','<>','5')
                        ->whereBetween('Age', array($var["age_start"], $var["age_end"]))
                        ->whereBetween('DateOfBooking', array($date_start, $date_end))
                        ->groupBy('MemberID')
                        ->having(DB::raw("COUNT(*)"), '>=', $frequency)
                        ->remember(1440)
                        ->get()
                        ->toArray();
                        $count = count($member_ids);
                        for ($i=0; $i < $count; $i++) 
                        { 
                            $flatten_member_ids_start[] = $member_ids[$i]["MemberID"];
                        }
                    }
                    else
                    {
                        if(count($member_ids) == 0)
                        {
                            break;
                        }
                        else
                        {
                            $results = $model::select("MemberID")
                            ->whereIn('SiteID', (array)$var["lc"])
                            ->where('PersonType', "2")
                            ->where('BookingType','LIKE' ,$var["booking"])
                            ->where('Gender','LIKE' ,$var["gender"])
                            ->where('TemplateName','LIKE' ,$var["programme"])
                            ->where('MemberID','<>','0')
                            ->where('BookingType','<>','5')
                            ->whereBetween('Age', array($var["age_start"], $var["age_end"]))
                            ->whereBetween('DateOfBooking', array($date_start, $date_end))
                            ->groupBy('MemberID')
                            ->having(DB::raw("COUNT(*)"), '>=', $frequency)
                            ->remember(1440)
                            ->get()
                            ->toArray();
                            $count = count($results);
                            /*unsetting array to reset*/
                            unset($flatten_member_ids_cont);
                            for ($i=0; $i < $count; $i++) 
                            { 
                                $flatten_member_ids_cont[] = $results[$i]["MemberID"];
                            }
                            $flatten_member_ids_start = array_intersect($flatten_member_ids_start, $flatten_member_ids_cont);
                        }
                    }
                }
                $counter++;
            }
            /*filterin drop out period according to the filters*/
            if(count($flatten_member_ids_start) != 0)
            {
                if($lc == "none")
                {
                    $all_members = $model::select("MemberID")
                    ->where('PersonType', "2")
                    ->where('BookingType','LIKE' ,$var["booking"])
                    ->where('Gender','LIKE' ,$var["gender"])
                    ->where('TemplateName','LIKE' ,$var["programme"])
                    ->where('MemberID','<>','0')
                    ->where('BookingType','<>','5')
                    ->whereBetween('Age', array($var["age_start"], $var["age_end"]))
                    ->whereBetween('DateOfBooking', array($var["date_start"], $var["date_end"]))
                    ->groupBy('MemberID')
                    ->remember(1440)
                    ->get()
                    ->toArray();
                }
                else
                {
                    $all_members = $model::select("MemberID")
                    ->whereIn('SiteID', (array)$var["lc"])
                    ->where('PersonType', "2")
                    ->where('BookingType','LIKE' ,$var["booking"])
                    ->where('Gender','LIKE' ,$var["gender"])
                    ->where('TemplateName','LIKE' ,$var["programme"])
                    ->where('MemberID','<>','0')
                    ->where('BookingType','<>','5')
                    ->whereBetween('Age', array($var["age_start"], $var["age_end"]))
                    ->whereBetween('DateOfBooking', array($var["date_start"], $var["date_end"]))
                    ->groupBy('MemberID')
                    ->remember(1440)
                    ->get()
                    ->toArray();
                }
            }
            /*diff'ing the memberts playing X time with the drop out period
            to see, how many members are still playing*/
            $count_am = count($all_members);
            for ($i=0; $i < $count_am; $i++) 
            { 
                $flatten_all_members[] = $all_members[$i]["MemberID"];
            }
            $flatten_all_members = array_diff($flatten_member_ids_start, $flatten_all_members);
            $total_members_before_drop_out = count($flatten_member_ids_start);
            $drop_out = count($flatten_all_members);
            $av = $drop_out / $total_members_before_drop_out * 100;
            ?>
            <div id="widget-grid_ajax">
                <article class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <div class="jarviswidget jarviswidget-color-green" data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false">
                        <header>
                            <span class="widget-icon"> <i class="fa fa-table"></i>
                            </span>
                            <h2>Results <?php echo $widget_exp;?></h2>
                        </header>
                        <div>
                            <div class="widget-body no-padding" id="section_1">
                                <table class="table table-striped table-bordered table-bordered table-hover" id="data_table_1" style="table-layout: fixed;" width="100%">
                                    <thead>
                                        <tr>
                                            <th data-class="expand">Description</th>
                                            <th data-hide="phone,tablet" width="100px;">Value</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><b>Frequency (at least # of times)</b></td>
                                            <td align="right"><?php echo $frequency;?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Total members playing consistently before the drop out period</b></td>
                                            <td align="right"><?php echo number_format($total_members_before_drop_out);?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Total members dropping out</b></td>
                                            <td align="right"><?php echo number_format($drop_out);?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Rate of churn (%)</b></td>
                                            <td align="right"><?php echo number_format($av,2);?> %</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </article>
                <article class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <div class="jarviswidget jarviswidget-color-red" data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false">
                        <header>
                            <span class="widget-icon"> <i class="fa fa-table"></i>
                            </span>
                            <h2>Graph</h2>
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
            echo View::make('templates.datatable', array("table_id" => "data_table_1", "sort" => "false"));
            echo View::make('templates.widget');
            ?>
            <script type="text/javascript">
                $(window).on('resize', function(){
                    $("#graph_1").css({height:$("#section_1").height()});
                });
                $("#graph_1").css({height:$("#section_1").height()});
                $('#graph_1').highcharts({
                    chart: {
                        plotBackgroundColor: null,
                        plotBorderWidth: null,
                        plotShadow: false
                    },
                    title: {
                        text: ''
                    },
                    tooltip: {
                        pointFormat: '{series.name}: <b>{point.y:.0f}</b>'
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                                enabled: true,
                                color: '#000000',
                                connectorColor: '#000000',
                                format: '<b>{point.percentage:.2f} %</b>'
                            },
                            showInLegend: true
                        }
                    },
                    legend:
                    {
                        layout: 'vertical',
                        align: 'right',
                        verticalAlign: 'bottom'
                    },
                    series: [{
                        type: 'pie',
                        name: '',
                        data: [
                        ['Rate of churn',   <?php echo $av; ?>],
                        ['Rest',            <?php echo (100 - $av); ?>]
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