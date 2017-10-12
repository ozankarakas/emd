<?php
error_reporting(E_ERROR);

class DashboardController extends BaseController 
{
    public function postKPI()
    {
        if(Input::get('ajax_action') == "get_tab")
        {
            $t1 = Input::get('t1');
            $t4 = Input::get('t4');
            $t5 = Input::get('t5');
            $t7 = Input::get('t7');
            $z = Input::get('tab');
            $c_year = Input::get('c_year');
            $l_year = Input::get('l_year');
            $c_month = Input::get('c_month');
            $c_month_int = Input::get('c_month_int');
            if($z==1)
            {
                $title_long = "Casual visits";
                $title_ytd = "YTD Jan to ".$c_month;
                for ($i=1; $i <= 12; $i++) 
                {
                    if(strlen($i) == 1)
                    {
                        $k = "0".$i;
                    }
                    else
                    {
                        $k = $i;
                    }
                    $graph_value_tab[$c_year][] = $t1[$c_year."-".$k][1]; 
                    $graph_value_tab[$l_year][] = $t1[$l_year."-".$k][1];
                    if($i <= $c_month_int)
                    {
                        $graph_value_tab_ytd[$l_year][] = $t1[$l_year."-".$k][1];             
                    }
                }
            }
            elseif($z==2)
            {
                $title_long = "Member visits";
                $title_ytd = "YTD Jan to ".$c_month;
                for ($i=1; $i <= 12; $i++) 
                {
                    if(strlen($i) == 1)
                    {
                        $k = "0".$i;
                    }
                    else
                    {
                        $k = $i;
                    }
                    $graph_value_tab[$c_year][] = $t1[$c_year."-".$k][2];
                    $graph_value_tab[$l_year][] = $t1[$l_year."-".$k][2];
                    if($i <= $c_month_int)
                    {
                        $graph_value_tab_ytd[$l_year][] = $t1[$l_year."-".$k][2];               
                    }
                }
            }
            elseif($z==3)
            {
                $title_long = "Total visits";
                $title_ytd = "YTD Jan to ".$c_month;
                for ($i=1; $i <= 12; $i++) 
                {
                    if(strlen($i) == 1)
                    {
                        $k = "0".$i;
                    }
                    else
                    {
                        $k = $i;
                    }
                    $graph_value_tab[$c_year][] = $t1[$c_year."-".$k][1] + $t1[$c_year."-".$k][2]; 
                    $graph_value_tab[$l_year][] = $t1[$l_year."-".$k][1] + $t1[$l_year."-".$k][2];
                    if($i <= $c_month_int)
                    {
                        $graph_value_tab_ytd[$l_year][] = $t1[$l_year."-".$k][1] + $t1[$l_year."-".$k][2];
                    }
                }
            }
            elseif($z==4)
            {
                $title_long = "Weekly participation % (members)";
                $title_ytd = "Weekly participation % (members) YTD Jan to ".$c_month;
                $add_sign = "%";
                for ($i=1; $i <= 12; $i++) 
                {
                    if(strlen($i) == 1)
                    {
                        $k = "0".$i;
                    }
                    else
                    {
                        $k = $i;
                    }
                    $c_total_1 += $t4[$c_year."-".$k];
                    $c_total_2 += $t7[$c_year."-".$k];

                    if($i <= $c_month_int)
                    {
                        $l_total_1 += $t4[$l_year."-".$k];
                        $l_total_2 += $t7[$l_year."-".$k];
                    }

                    $graph_value_tab[$c_year][] = $t4[$c_year."-".$k] / $t7[$c_year."-".$k] * 100; 
                    $graph_value_tab[$l_year][] = $t4[$l_year."-".$k] / $t7[$l_year."-".$k] * 100;
                }
                $graph_value_tab_ytd[$c_year] = $c_total_1 / $c_total_2 * 100; 
                $graph_value_tab_ytd[$l_year] = $l_total_1 / $l_total_2 * 100; 
            }
            elseif($z==5)
            {
                $title_long = "Weekly participation members lost %";
                $add_sign = "%";

                $graph_value_tab[$c_year][] = $t5[1];
                $graph_value_tab[$l_year][] = $t5[0];
            }
            elseif($z==6)
            {
                $title_long = "Percentage of visits by members";
                $title_ytd = "Percentage of visits by members YTD Jan to ".$c_month;
                $add_sign = "%";
                for ($i=1; $i <= 12; $i++) 
                {
                    if(strlen($i) == 1)
                    {
                        $k = "0".$i;
                    }
                    else
                    {
                        $k = $i;
                    }
                    $c_total_1 += $t1[$c_year."-".$k][1];
                    $c_total_2 += $t1[$c_year."-".$k][2];

                    if($i <= $c_month_int)
                    {
                        $l_total_1 += $t1[$l_year."-".$k][1];
                        $l_total_2 += $t1[$l_year."-".$k][2];
                    }

                    $graph_value_tab[$c_year][] = $t1[$c_year."-".$k][2] / ($t1[$c_year."-".$k][1] + $t1[$c_year."-".$k][2]) * 100; 
                    $graph_value_tab[$l_year][] = $t1[$l_year."-".$k][2] / ($t1[$l_year."-".$k][1] + $t1[$l_year."-".$k][2]) * 100;
                }
                $graph_value_tab_ytd[$c_year] = $c_total_2 / ($c_total_1 + $c_total_2) * 100; 
                $graph_value_tab_ytd[$l_year] = $l_total_2 / ($l_total_1 + $l_total_2) * 100; 
            }
            elseif($z==7)
            {
                $title_long = "Unique members";
                $title_ytd = "YTD Jan to ".$c_month;
                for ($i=1; $i <= 12; $i++) 
                {
                    if(strlen($i) == 1)
                    {
                        $k = "0".$i;
                    }
                    else
                    {
                        $k = $i;
                    }
                    $graph_value_tab[$c_year][] = $t7[$c_year."-".$k]; 
                    $graph_value_tab[$l_year][] = $t7[$l_year."-".$k];
                    if($i <= $c_month_int)
                    {
                        $graph_value_tab_ytd[$l_year][] = $t7[$l_year."-".$k];
                    }
                }
            }
            ?>
            <div class="tab-content" id="tabs_content">
                <div class="tab-pane fade active in padding-10 no-padding-bottom">
                    <div class="row no-space">
                        <?php if($z == 5) 
                        {
                            ?>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div id="graph_2_tab" style="height: 400px; width: 100%"></div>
                            </div>
                            <?php 
                        }
                        else
                        {
                            ?>
                            <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                                <div id="graph_2_tab" style="height: 400px; width: 100%"></div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                <div id="graph_3_tab" style="height: 400px; width: 100%"></div>
                            </div>
                            <?php  
                        }
                        ?>
                    </div>
                </div>
            </div>
            <script type="text/javascript">
                $(function () {
                    $('#graph_2_tab').highcharts({
                        colors: ["#7cb5ec","#f7a35c"],
                        chart: {
                            type: 'column',
                            backgroundColor: '#FFF',
                            shadow: true
                        },
                        title: {
                            text: '<?php echo $title_long; ?>'
                        },
                        subtitle: {
                            text: ''
                        },
                        xAxis: {
                            categories: [
                            <?php
                            if($z == 5)
                            {
                                echo "'" .date('M', mktime(0, 0, 0, $c_month_int, 10))."',";
                            }
                            else
                            {    
                                for ($i = 1; $i <= 12; $i ++)
                                {
                                    echo "'" .date('M', mktime(0, 0, 0, $i, 10))."',";
                                }
                            }
                            ?>
                            ]
                        },
                        yAxis: {
                            min: 0,
                            title: {
                                text: ''
                            }
                        },
                        tooltip: {
                            formatter: function() {
                                return '<span style="font-size:10px">'+this.key+'</span><table><tr><td style="color:'+this.color+';padding:0">'+this.series.name+': </td>' +
                                '<td style="padding:0"><b>'+ Highcharts.numberFormat(this.y, 0,'.',',') +' <?php echo $add_sign; ?></b></td></tr></table>';
                            },
                            shared: false,
                            useHTML: true
                        },
                        plotOptions: {
                            column: {
                                shadow: true,
                                pointPadding: 0.2,
                                borderWidth: 0,
                                dataLabels: {
                                    enabled: true,
                                    <?php if($z == 4 || $z == 5 || $z == 6) 
                                    { 
                                        ?>
                                        formatter:function() {
                                            return Highcharts.numberFormat(this.y,1,'.',',') + ' <?php echo $add_sign ?>';
                                        },
                                        <?php 
                                    } 
                                    else
                                    {
                                        ?>
                                        formatter:function() {
                                            return Highcharts.numberFormat(this.y,0,'.',',');
                                        },
                                        <?php
                                    }
                                    ?>
                                    rotation: -90,
                                    color: '#FFFFFF',
                                    align: 'right',
                                    x: 0,
                                    y: 10,
                                    style: {
                                        fontSize: '10px',
                                        fontFamily: 'Verdana, sans-serif',
                                        ////textShadow: '0 0 3px black'
                                    }
                                }
                            }
                        },
                        series: [{
                            name: '<?php echo $l_year; ?>',
                            data: [<?php
                            for ($i=0; $i < count($graph_value_tab[$l_year]); $i++)
                            {
                                if($z == 4 || $z == 5 || $z == 6) 
                                { 
                                    echo number_format($graph_value_tab[$l_year][$i],1).",";
                                }
                                else
                                {
                                    echo number_format($graph_value_tab[$l_year][$i],0,"","").",";
                                }
                            }
                            ?>],
                        },
                        {
                            name: '<?php echo $c_year; ?>',
                            data: [
                            <?php
                            for ($i=0; $i < count($graph_value_tab[$c_year]); $i++)
                            {
                                if($z == 4 || $z == 5 || $z == 6) 
                                { 
                                    echo number_format($graph_value_tab[$c_year][$i],1).",";
                                }
                                else
                                {
                                    echo number_format($graph_value_tab[$c_year][$i],0,"","").",";
                                }
                            }
                            ?>]
                        }]
                    });
$('#graph_3_tab').highcharts({
    colors: ["#FFA500"],
    chart: {
        type: 'column',
        backgroundColor: '#FFF',
        shadow: true
    },
    title: {
        text: '<?php echo $title_ytd; ?>'
    },
    subtitle: {
        text: ''
    },
    xAxis: {
        categories: [
        '<?php echo $l_year; ?>',
        '<?php echo $c_year; ?>',
        ]
    },
    yAxis: {
        min: 0,
        title: {
            text: ''
        }
    },
    tooltip: {
        formatter: function() {
            return '<span style="font-size:10px">'+this.key+'</span><table><tr><td style="color:'+this.color+';padding:0">'+this.series.name+': </td>' +
            '<td style="padding:0"><b>'+ Highcharts.numberFormat(this.y, 0,'.',',') +' <?php echo $add_sign; ?></b></td></tr></table>';
        },
        shared: false,
        useHTML: true
    },
    plotOptions: {
        column: {
            shadow: true,
            pointPadding: 0.2,
            borderWidth: 0,
            dataLabels: {
                enabled: true,
                <?php if($z == 4 || $z == 6)
                { 
                    ?>
                    formatter:function() {
                        return Highcharts.numberFormat(this.y,1,'.',',') + ' <?php echo $add_sign ?>';
                    },
                    <?php 
                } 
                else
                {
                    ?>
                    formatter:function() {
                        return Highcharts.numberFormat(this.y,0,'.',',');
                    },
                    <?php
                }
                ?>
                rotation: -90,
                color: '#FFFFFF',
                align: 'right',
                x: 0,
                y: 10,
                style: {
                    fontSize: '10px',
                    fontFamily: 'Verdana, sans-serif',
                    //textShadow: '0 0 3px black'
                }
            }
        }
    },
    series: [{
        name: 'YTD',
        data: [
        <?php
        if($z==4 || $z==6)
        {
            echo $graph_value_tab_ytd[$l_year].",".$graph_value_tab_ytd[$c_year];
        }
        else
        { 
            echo array_sum($graph_value_tab_ytd[$l_year]).",".array_sum($graph_value_tab[$c_year]);
        }
        ?>]
    }]
});
});
</script>
<?php
}
}
}
