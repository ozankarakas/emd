<script type="text/javascript">
var responsiveHelper_dt_basic = undefined;
var responsiveHelper_datatable_fixed_column = undefined;
var responsiveHelper_datatable_col_reorder = undefined;
var responsiveHelper_datatable_tabletools = undefined;
var breakpointDefinition = {
    all : 4096,
    tablet : 1024,
    phone : 480
};

<?php 
if(isset($column))
{
    if(isset($direction))
    {
        $d = $direction;
    }
    else
    {
        $d = "desc";
    }
    $c = '"aaSorting": [['.$column.',"'.$d.'"]],';
}

if(isset($paging))
{
    $p = '';
}
else
{
    $p = 'l';   
}
?>
var table = $('#{{ $table_id }}').DataTable({
    "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-7'{{$p}}f><'col-sm-5 col-xs-6 hidden-xs'T>r>"+
    "t"+
    "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-sm-6 col-xs-12'p>>",
    "bSort" : {{ $sort }},
    {{ $c }}    
    "oTableTools": {
        "aButtons" : ["copy", {
            "sExtends": "print",
            "sMessage": "Generated by 4Global <i>(press Esc to close)</i>"
        }, {
            "sExtends" : "collection",
            "sButtonText" : 'Save <span class="caret" />',
            "aButtons" : ["csv", "xls", {
                "sExtends": "pdf",
//"sTitle": "PDF",
"sPdfMessage": "Generated by 4Global",
"sPdfSize": "letter"
}]
}],
"sSwfPath": "<?php echo URL::asset('js/plugin/datatables/swf/copy_csv_xls_pdf.swf') ?>"
},
"autoWidth" : true,
"preDrawCallback" : function() {
// Initialize the responsive datatables helper once.
if (!responsiveHelper_datatable_tabletools) {
    responsiveHelper_datatable_tabletools = new ResponsiveDatatablesHelper($('#{{ $table_id }}'), breakpointDefinition);
}
},
"rowCallback" : function(nRow) {
    responsiveHelper_datatable_tabletools.createExpandIcon(nRow);
},
"drawCallback" : function(oSettings) {
    responsiveHelper_datatable_tabletools.respond();
}
});
</script>
