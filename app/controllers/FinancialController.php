<?php
class FinancialController extends BaseController {
    public function getIndex()
    {
        return View::make('financial');
    }

    public function postKPI()
    {
        if(Input::get('ajax_action') == "get_kpi")
        {
            $var = GeneralFunctions::arrange_variables_for_finance(Input::all());
            $gender = $var["gender"];
            $payment = $var["payment"];
            $lcs = $var["lc"];

            foreach ($GLOBALS['sports'] as $value) 
            {
                if($var["sport"] === $value->name)
                {
                    $sport_id = $value->id;
                    break;
                }
            }

            $results = Income::select(DB::raw('count(*) as count'), DB::raw("sum(`HeadCount`) as sumHC"), DB::raw('(SUM(`NetValue`) + SUM(`VatAmount`)) as sum'), 'PersonID', 'Sport')
            ->where('NetValue', '>', 0)
            ->where(function($query) use ($lcs)
            {
                if($lcs != "none")
                {
                    return $query->whereIn('SiteID', (array)$lcs);
                }
                else
                {
                    return $query;
                }
            })
            ->where(function($query) use ($gender)
            {
                if($gender != "")
                {
                    return $query->where('Gender', $gender);
                }
                else
                {
                    return $query;
                }
            })
            ->where(function($query) use ($payment)
            {
                if($payment != 0)
                {
                    return $query->where('PaymentType', $payment);
                }
                else
                {
                    return $query->whereIn('PaymentType',[1,2,7,10]);
                }
            })
            ->whereBetween('Age', array($var["age_start"], $var["age_end"]))
            ->whereBetween('ApplicableDate', array($var["date_start"], $var["date_end"]))
            ->groupBy('PersonID', 'Sport')
            ->remember(1440)
            ->get();

            foreach ($results as $result) 
            {
                //for sport
                if($result->Sport == $sport_id)
                {
                    if($result->PersonID != "" && $result->PersonID != "0" && $result->PersonID != "00000000-0000-0000-0000-000000000000")
                    {
                        $c_swi_mem += $result->count; 
                        $count_swi_mem += $result->sumHC; 
                        $sum_swi_mem += $result->sum;
                    }
                    $c_swi_all += $result->count;
                    $count_swi_all += $result->sumHC;
                    $sum_swi_all += $result->sum;
                }
                //for all
                $c_all_all += $result->count;
                $count_all_all += $result->sumHC;
                $sum_all_all += $result->sum; 
                if($result->PersonID != "" && $result->PersonID != "0" && $result->PersonID != "00000000-0000-0000-0000-000000000000")
                {
                    $c_all_mem  += $result->count; 
                    $count_all_mem  += $result->sumHC; 
                    $sum_all_mem  += $result->sum;
                }
            }
            // Secondary spend
            $results_sp = Income::select(DB::raw('count(*) as count'), DB::raw('(SUM(`NetValue`) + SUM(`VatAmount`)) as sum'), 'Sport')
            ->where('NetValue', '>', 0)
            ->where('SecondarySpend', 1)
            ->where(function($query) use ($lcs)
            {
                if($lcs != "none")
                {
                    return $query->whereIn('SiteID', (array)$lcs);
                }
                else
                {
                    return $query;
                }
            })
            ->where(function($query) use ($gender)
            {
                if($gender != "")
                {
                    return $query->where('Gender', $gender);
                }
                else
                {
                    return $query;
                }
            })
            ->where(function($query) use ($payment)
            {
                if($payment != 0)
                {
                    return $query->where('PaymentType', $payment);
                }
                else
                {
                    return $query->whereIn('PaymentType',[4,8]);
                }
            })
            ->whereBetween('Age', array($var["age_start"], $var["age_end"]))
            ->whereBetween('ApplicableDate', array($var["date_start"], $var["date_end"]))
            ->groupBy('PersonID', 'Sport')
            ->remember(1440)
            ->get();

            foreach ($results_sp as $result) 
            {
                //for sport
                if($result->Sport == $sport_id)
                {
                    $sp_c_swi_all += $result->count;
                    $sp_sum_swi_all += $result->sum;
                }
                //for all
                $sp_c_all_all += $result->count;
                $sp_sum_all_all += $result->sum; 
            }


            ?>
            <div id="widget-grid_ajax">
                <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="jarviswidget jarviswidget-color-green" data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false">
                        <header>
                            <span class="widget-icon"> <i class="fa fa-table"></i>
                            </span>
                            <h2>Income derived from <?php echo $var["sport"]; ?> bookings</h2>
                        </header>
                        <div>
                            <div class="widget-body no-padding">
                                <table class="table table-striped table-bordered table-bordered table-hover" id="data_table_1" style="table-layout: fixed;" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="20%"></th>
                                            <th width="20%">Total number of bookings</th>
                                            <th width="20%">Total Throughput</th>
                                            <th width="20%">Total income from bookings</th>
                                            <th width="20%">Average income per visit</th>
                                            <th width="20%">Booking Income Ratio (Member : All)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><?php echo $var["sport"]; ?> - All</td>
                                            <td align="right"><?php echo number_format($c_swi_all);?></td>
                                            <td align="right"><?php echo number_format($count_swi_all);?></td>
                                            <td align="right">&#163; <?php echo number_format($sum_swi_all,2);?></td>
                                            <td align="right">&#163; <?php echo number_format($sum_swi_all/$count_swi_all,2);?></td>
                                            <td align="right">-</td>
                                        </tr>
                                        <tr>
                                            <td><?php echo $var["sport"]; ?> - Members Only</td>
                                            <td align="right"><?php echo number_format($c_swi_mem);?></td>
                                            <td align="right"><?php echo number_format($count_swi_mem);?></td>
                                            <td align="right">&#163; <?php echo number_format($sum_swi_mem,2);?></td>
                                            <td align="right">&#163; <?php echo number_format($sum_swi_mem/$count_swi_mem,2);?></td>
                                            <td align="right"><?php echo number_format(($sum_swi_mem) / ($sum_swi_all) * 100,2);?> %</td>
                                        </tr>
                                        <tr>
                                            <td>All activities</td>
                                            <td align="right"><?php echo number_format($c_all_all);?></td>
                                            <td align="right"><?php echo number_format($count_all_all);?></td>
                                            <td align="right">&#163; <?php echo number_format($sum_all_all,2);?></td>
                                            <td align="right">&#163; <?php echo number_format($sum_all_all/$count_all_all,2);?></td>
                                            <td align="right">-</td>
                                        </tr>
                                        <tr>
                                            <td>All activities - Members Only</td>
                                            <td align="right"><?php echo number_format($c_all_mem);?></td>
                                            <td align="right"><?php echo number_format($count_all_mem);?></td>
                                            <td align="right">&#163; <?php echo number_format($sum_all_mem,2);?></td>
                                            <td align="right">&#163; <?php echo number_format($sum_all_mem/$count_all_mem,2);?></td>
                                            <td align="right"><?php echo number_format(($sum_all_mem) / ($sum_all_all) * 100,2);?> %</td>
                                        </tr>
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
                            <h2>Secondary income derived from <?php echo $var["sport"]; ?> members</h2>
                        </header>
                        <div>
                            <div class="widget-body no-padding">
                                <table class="table table-striped table-bordered table-bordered table-hover" id="data_table_2" style="table-layout: fixed;" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="20%"></th>
                                            <th width="20%">Total number of secondary spend</th>
                                            <th width="20%">Total income from secondary spend</th>
                                            <th width="20%">Average income per secondary spend</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><?php echo $var["sport"]; ?> - Members Only</td>
                                            <td align="right"><?php echo number_format($sp_c_swi_all);?></td>
                                            <td align="right">&#163; <?php echo number_format($sp_sum_swi_all,2);?></td>
                                            <td align="right">&#163; <?php echo number_format($sp_sum_swi_all/$sp_c_swi_all,2);?></td>
                                        </tr>
                                        <tr>
                                            <td>All activities - Members only</td>
                                            <td align="right"><?php echo number_format($sp_c_all_all);?></td>
                                            <td align="right">&#163; <?php echo number_format($sp_sum_all_all,2);?></td>
                                            <td align="right">&#163; <?php echo number_format($sp_sum_all_all/$sp_c_all_all,2);?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </article>
                <article class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <div class="jarviswidget jarviswidget-color-blue" data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false">
                        <header>
                            <span class="widget-icon"> <i class="fa fa-table"></i>
                            </span>
                            <h2>Financial KPI - Graph 1</h2>
                        </header>
                        <div>
                            <div class="widget-body no-padding">
                                <div id="graph_1" style="margin-right: 10px; padding-bottom: 2px;"></div>
                            </div>
                        </div>
                    </div>
                </article>
                <article class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <div class="jarviswidget jarviswidget-color-blue" data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false">
                        <header>
                            <span class="widget-icon"> <i class="fa fa-table"></i>
                            </span>
                            <h2>Financial KPI - Graph 2</h2>
                        </header>
                        <div>
                            <div class="widget-body no-padding">
                                <div id="graph_2" style="margin-right: 10px; padding-bottom: 2px;"></div>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
            <?php
            echo View::make('templates.datatable', array("table_id" => "data_table_1", "sort" => "true"));
            echo View::make('templates.datatable', array("table_id" => "data_table_2", "sort" => "true"));        
            echo View::make('templates.widget');
            ?>
            <script type="text/javascript">
                $('#graph_1').highcharts({
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: 'Income derived from <?php echo $var["sport"]; ?> bookings'
                    },
                    subtitle: {
                        text: ''
                    },
                    xAxis: {
                        categories: [''],
                        title: {
                            text: '<?php echo $var["sport"]; ?> bookings'
                        }
                    },
                    yAxis: {
                        allowDecimals:false,
                        title: {
                            text: 'Average income per visit',
                            align: 'high'
                        },
                        labels: {
                            overflow: 'justify'
                        }
                    },
                    tooltip: {
                        formatter: function () {
                            return this.series.name + ": " +"<b>"+this.point.y+" </b>";
                        }
                    },
                    plotOptions: {
                        bar: {
                            dataLabels: {
                                enabled: false
                            }
                        }
                    },
                    series: [{
                        name: '<?php echo $var["sport"]; ?> - All',
                        data: [<?php echo number_format($sum_swi_all/$count_swi_all,2, '.', '');?>]
                    },
                    {
                        name: '<?php echo $var["sport"]; ?> - Members Only',
                        data: [<?php echo number_format($sum_swi_mem/$count_swi_mem,2, '.', '');?>]
                    },
                    {
                        name: 'All activities',
                        data: [<?php echo number_format($sum_all_all/$count_all_all,2, '.', '');?>]
                    },
                    {
                        name: 'All activities - Members only',
                        data: [<?php echo number_format($sum_all_mem/$count_all_mem,2, '.', '');?>]
                    }]
                });
$('#graph_2').highcharts({
    chart: {
        type: 'column'
    },
    title: {
        text: 'Secondary income derived from <?php echo $var["sport"]; ?> members'
    },
    subtitle: {
        text: ''
    },
    xAxis: {
        categories: [''],
        title: {
            text: '<?php echo $var["sport"]; ?> members'
        }
    },
    yAxis: {
        allowDecimals:false,
        title: {
            text: 'Average income per secondary spend',
            align: 'high'
        },
        labels: {
            overflow: 'justify'
        }
    },
    tooltip: {
        formatter: function () {
            return this.series.name + ": " +"<b>"+this.point.y+" </b>";
        }
    },
    plotOptions: {
        bar: {
            dataLabels: {
                enabled: false
            }
        }
    },
    series: [{
        name: '<?php echo $var["sport"]; ?> - Members Only',
        data: [<?php echo number_format($sp_sum_swi_all/$sp_c_swi_all,2, '.', '');?>]
    },
    {
        name: 'All activities - Members only',
        data: [<?php echo number_format($sp_sum_all_all/$sp_c_all_all,2, '.', '');?>]
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