<script type="text/javascript">
$(".sparkline").each(function() {
    var tb = $(this),
    ub = tb.data("sparkline-type") || "bar";
    tv = tb.data("tooltip");
    if ("bar" == ub && (a = tb.data("sparkline-bar-color") || tb.css("color") || "#0000f0", b = tb.data("sparkline-height") || "26px", c = tb.data("sparkline-barwidth") || 5, d = tb.data("sparkline-barspacing") || 2, e = tb.data("sparkline-negbar-color") || "#A90329", f = tb.data("sparkline-barstacked-color") || ["#A90329", "#0099c6", "#98AA56", "#da532c", "#4490B1", "#6E9461", "#990099", "#B4CAD3"], tb.sparkline("html", {
        barColor: a,
        type: ub,
        height: b,
        barWidth: c,
        barSpacing: d,
        stackedBarColor: f,
        negBarColor: e,
        zeroAxis: "false",
<?php  
        echo "tooltipFormat: '{{offset:offset}} - {{value}}',";
?>        
        tooltipValueLookups: {
            'offset': {0:tv[0],1:tv[1],2:tv[2],3:tv[3],4:tv[4],5:tv[5],6:tv[6],7:tv[7],8:tv[8],9:tv[9],10:tv[10],11:tv[11],12:tv[12],13:tv[13],14:tv[14],15:tv[15],16:tv[16],17:tv[17],18:tv[18],19:tv[19],20:tv[20]}
        },
    }), tb = null), "line" == ub && (b = tb.data("sparkline-height") || "20px", ab = tb.data("sparkline-width") || "90px", g = tb.data("sparkline-line-color") || tb.css("color") || "#0000f0", h = tb.data("sparkline-line-width") || 1, i = tb.data("fill-color") || "#c0d0f0", j = tb.data("sparkline-spot-color") || "#f08000", k = tb.data("sparkline-minspot-color") || "#ed1c24", l = tb.data("sparkline-maxspot-color") || "#f08000", m = tb.data("sparkline-highlightspot-color") || "#50f050", n = tb.data("sparkline-highlightline-color") || "f02020", o = tb.data("sparkline-spotradius") || 1.5, thisChartMinYRange = tb.data("sparkline-min-y") || "undefined", thisChartMaxYRange = tb.data("sparkline-max-y") || "undefined", thisChartMinXRange = tb.data("sparkline-min-x") || "undefined", thisChartMaxXRange = tb.data("sparkline-max-x") || "undefined", thisMinNormValue = tb.data("min-val") || "undefined", thisMaxNormValue = tb.data("max-val") || "undefined", thisNormColor = tb.data("norm-color") || "#c0c0c0", thisDrawNormalOnTop = tb.data("draw-normal") || !1, tb.sparkline("html", {
    }), tb = null),"bullet" == ub) {
    tb = null
}
});
$(".easy-pie-chart").each(function() {
    var a = $(this),
    b = a.css("color") || a.data("pie-color"),
    c = a.data("pie-track-color") || "#eeeeee",
    d = parseInt(a.data("pie-size")) || 25;
    a.easyPieChart({
        barColor: b,
        trackColor: c,
        scaleColor: !1,
        lineCap: "butt",
        lineWidth: parseInt(d / 8.5),
        animate: 1500,
        rotate: -90,
        size: d,
        onStep: function(a) {
            this.$el.find("span").text(~~a)
        }
    }), a = null
});
</script>