<?php
class ParticipationController extends BaseController {
    public function getIndex()
    {
        return View::make('participation');
    }
    public function postKPI()
    {
        if(Input::get('ajax_action') == "get_kpi")
        {
            //$time_start = microtime(true);

            $var = GeneralFunctions::arrange_variables(Input::all());
            $model = "DataRaw".$var["sport"];

            foreach ($GLOBALS['sports'] as $value) 
            {
                if($var["sport"] === $value->name)
                {
                    $sport_id = $value->id;
                    break;
                }
            }

            if($var["lc"] == "none")
            {
                $get_results = DataRaw::select('Sport', 'PersonType', DB::raw("sum(`HeadCount`) as headcount"), DB::raw("count(DISTINCT(`MemberID`)) as count"))
                ->where('Gender','LIKE', $var["gender"])
                ->where('BookingType','<>','5')
                ->whereBetween('Age', array($var["age_start"], $var["age_end"]))
                ->whereBetween('DateOfBooking', array($var["date_start"], $var["date_end"]))
                ->groupBy('Sport','PersonType')
                ->remember(1440)
                ->get();
            }
            else
            {
                $get_results = DataRaw::select('Sport', 'PersonType', DB::raw("sum(`HeadCount`) as headcount"), DB::raw("count(DISTINCT(`MemberID`)) as count"))
                ->whereIn('SiteID', (array)$var["lc"])
                ->where('Gender','LIKE', $var["gender"])
                ->where('BookingType','<>','5')
                ->whereBetween('Age', array($var["age_start"], $var["age_end"]))
                ->whereBetween('DateOfBooking', array($var["date_start"], $var["date_end"]))
                ->groupBy('Sport','PersonType')
                ->remember(1440)
                ->get();
            }

            // $time_end = microtime(true);
            // $time = $time_end - $time_start;
            // echo "Query First: ".$time."<br>";

            foreach ($get_results as $result)
            {
                $results[$result->Sport][$result->PersonType][] = $result->headcount; 
                $results[$result->Sport][$result->PersonType][] = $result->count;
                $sports[] = $result->Sport;
            }

            $sports = array_values(array_unique($sports));

            // $time_end = microtime(true);
            // $time = $time_end - $time_start;
            // echo "PHP First: ".$time."<br>";

            ?>

            <div id="widget-grid_ajax">
                <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="jarviswidget jarviswidget-color-green" data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false">
                        <header>
                            <span class="widget-icon"> <i class="fa fa-table"></i>
                            </span>
                            <h2>Participation in all activities</h2>
                        </header>
                        <div>
                            <div class="widget-body no-padding">
                                <table class="table table-striped table-bordered table-bordered table-hover" id="data_table_1" style="table-layout: fixed;" width="100%">
                                    <thead>
                                        <tr>
                                            <th data-class="expand">Activities</th>
                                            <th data-hide="phone">Casual Participation</th>
                                            <th data-hide="phone">Member Participation</th>
                                            <th>Total Participation</th>
                                            <th data-hide="phone,tablet">Unique Members</th>
                                            <th data-hide="phone,tablet">% of Unique Members</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php

                                        $count = count($sports);

                                        for ($i = 0; $i < $count; $i ++)
                                        {
                                            foreach ($GLOBALS['sports'] as $all_sports) 
                                            {
                                                $activities = "";
                                                if($all_sports->id === $sports[$i])
                                                {
                                                    $activities = $all_sports ->name;
                                                    break;
                                                }
                                            }

                                            $no_of_casual = $results[$sports[$i]][1][0];
                                            $no_of_member = $results[$sports[$i]][2][0];
                                            $no_of_unique_member = $results[$sports[$i]][2][1];

                                            $t_no_of_casual = $t_no_of_casual + $results[$sports[$i]][1][0];
                                            $t_no_of_member = $t_no_of_member + $results[$sports[$i]][2][0];
                                            $t_no_of_unique_member = $t_no_of_unique_member + $results[$sports[$i]][2][1];
                                            
                                            if($activities === $var["sport"])
                                            {
                                                $sport_unique =  $no_of_unique_member;
                                            }

                                            ?>
                                            <tr>
                                                <td><?php echo $activities;?></td>
                                                <td align="right"><?php echo number_format($no_of_casual,0);?></td>
                                                <td align="right"><?php echo number_format($no_of_member,0);?></td>
                                                <td align="right"><?php echo number_format($no_of_casual + $no_of_member,0)?></td>
                                                <td align="right"><?php echo number_format($no_of_unique_member,0);?></td>
                                                <td align="right"><?php echo number_format(($no_of_unique_member / $no_of_member) * 100,0);?> %</td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                        <tfoot>
                                            <tr>
                                                <td><b>Total Participation</b></td>
                                                <td align="right"><b><?php echo number_format($t_no_of_casual);?></b></td>
                                                <td align="right"><b><?php echo number_format($t_no_of_member);?></b></td>
                                                <td align="right"><b><?php echo number_format(($t_no_of_casual + $t_no_of_member),0);?></b></td>
                                                <td align="right"><b><?php echo number_format(($t_no_of_unique_member),0);?></b></td>
                                                <td align="right"><b><?php echo number_format(($t_no_of_unique_member / $t_no_of_member) * 100,0);?> %</b></td>
                                            </tr>
                                        </tfoot>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </article>
                <?php   
                // $time_end = microtime(true);
                // $time = $time_end - $time_start;
                // echo "TABLE First: ".$time."<br>";
                ?>
                <article class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <div class="jarviswidget jarviswidget-color-red" data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false">
                        <header>
                            <span class="widget-icon"> <i class="fa fa-table"></i>
                            </span>
                            <h2>Participation in pay &amp; play sessions vs organised</h2>
                        </header>
                        <div>
                            <div id="section_2" class="widget-body no-padding">
                                <table class="table table-striped table-bordered table-bordered table-hover" id="data_table_2" style="table-layout: fixed;" width="100%">
                                    <thead>
                                        <tr>
                                            <th data-class="expand"><?php echo $var["sport"]; ?></th>
                                            <th>Total Participation</th>
                                            <th data-hide="phone,tablet">%</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                    // PARTICIPATIONS
                                        if($var["lc"] == "none")
                                        {
                                            $results = $model::select('PersonType', 'BookingType', DB::raw("sum(`HeadCount`) as count"))
                                            ->whereBetween('DateOfBooking', array($var["date_start"], $var["date_end"]))
                                            ->where('BookingType','<>','5')
                                            ->groupBy('PersonType', 'BookingType')
                                            ->remember(1440)
                                            ->get();
                                        }
                                        else
                                        {
                                            $results = $model::select('PersonType', 'BookingType', DB::raw("sum(`HeadCount`) as count"))
                                            ->whereIn('SiteID', (array)$var["lc"])
                                            ->whereBetween('DateOfBooking', array($var["date_start"], $var["date_end"]))
                                            ->where('BookingType','<>','5')
                                            ->groupBy('PersonType', 'BookingType')
                                            ->remember(1440)
                                            ->get();
                                        }
                                        // $time_end = microtime(true);
                                        // $time = $time_end - $time_start;
                                        // echo "QUERY Second: ".$time."<br>";
                                        foreach ($results as $result) 
                                        {
                                            if($result->PersonType == "1")
                                            {
                                                if($result->BookingType == "1")
                                                {
                                                    $casual_activity = $result->count;
                                                }
                                                elseif($result->BookingType == "3")
                                                {
                                                    $casual_session = $result->count;
                                                }
                                                elseif($result->BookingType == "4")
                                                {
                                                    $casual_course = $result->count;
                                                }
                                                elseif($result->BookingType == "2")
                                                {
                                                    $casual_ticket = $result->count;
                                                }
                                            }
                                            else
                                            {
                                                if($result->BookingType == "1")
                                                {
                                                    $member_activity = $result->count;
                                                }
                                                elseif($result->BookingType == "3")
                                                {
                                                    $member_session = $result->count;
                                                }
                                                elseif($result->BookingType == "4")
                                                {
                                                    $member_course = $result->count;
                                                }
                                                elseif($result->BookingType == "2")
                                                {
                                                    $member_ticket = $result->count;
                                                }
                                            }
                                        }

                                        $total_number = $casual_activity + $casual_session + $casual_course + $casual_ticket + $member_activity + $member_session + $member_course + $member_ticket;
                                        $over_all_casual = $casual_activity + $casual_session + $casual_course + $casual_ticket;
                                        $over_all_member = $member_activity + $member_session + $member_course + $member_ticket;
                                        $total = $over_all_casual + $over_all_member;

                                        // $time_end = microtime(true);
                                        // $time = $time_end - $time_start;
                                        // echo "PHP Second: ".$time."<br>";

                                        ?>
                                        <tr>
                                            <td>Casual Pay &amp; Play</td>
                                            <td align="right"><?php echo number_format(($casual_activity + $casual_ticket),0);?></td>
                                            <td align="right"><?php echo number_format((($casual_activity + $casual_ticket) / $total) * 100,2);?> %</td>
                                        </tr>
                                        <tr>
                                            <td>Casual Organised</td>
                                            <td align="right"><?php echo ($casual_course + $casual_session);?></td>
                                            <td align="right"><?php echo number_format((($casual_course + $casual_session) / $total) * 100,2);?> %</td>
                                        </tr>
                                        <tr>
                                            <td>Member Pay &amp; Play</td>
                                            <td align="right"><?php echo number_format(($member_activity + $member_ticket),0);?></td>
                                            <td align="right"><?php echo number_format((($member_activity + $member_ticket) / $total) * 100,2);?> %</td>
                                        </tr>
                                        <tr>
                                            <td>Member Organised</td>
                                            <td align="right"><?php echo ($member_course + $member_session);?></td>
                                            <td align="right"><?php echo number_format((($member_course + $member_session) / $total) * 100,2);?> %</td>
                                        </tr>
                                        <tfoot>
                                            <tr>
                                                <td><b>Total</b></td>
                                                <td align="right"><b><?php echo number_format($total_number,0);?></b></td>
                                                <td align="right"><b><?php echo number_format(($total_number / $total) * 100,2);?> %</b></td>
                                            </tr>
                                        </tfoot>
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
                            <h2>Participation types graph</h2>
                        </header>
                        <div>
                            <div class="widget-body no-padding">
                                <div id="graph_1" style="margin-right: 10px; padding-bottom: 2px;"></div>
                            </div>
                        </div>
                    </div>
                </article>
                <?php
            // $time_end = microtime(true);
            // $time = $time_end - $time_start;
            // echo "TABLE Second: ".$time."<br>";

                ?>
            </div>
            <?php

            // $time_end = microtime(true);
            // $time = $time_end - $time_start;
            // echo "FINAL: ".$time."<br>";

            echo View::make('templates.datatable', array("table_id" => "data_table_3", "sort" => "true"));
            echo View::make('templates.datatable', array("table_id" => "data_table_2", "sort" => "true"));
            echo View::make('templates.datatable', array("table_id" => "data_table_1", "sort" => "true"));
            echo View::make('templates.widget');
            ?>

            <script type="text/javascript">
                $(window).on('resize', function(){
                    $("#graph_1").css({height:$("#section_2").height()});
                });
                $("#graph_1").css({height:$("#section_2").height()});
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
                        ['Casual Pay & Play', <?php echo number_format($casual_activity + $casual_ticket,0,"","");?>],
                        ['Casual Organised', <?php echo number_format(($casual_course + $casual_session),0,"","");?>],
                        ['Member Pay & Play', <?php echo number_format($member_activity + $member_ticket,0,"","");?>],
                        ['Member Organised', <?php echo number_format(($member_course + $member_session),0,"","");?>],
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