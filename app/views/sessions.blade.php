@extends('templates.default')
@section('breadcrumbs')
{{ Helper::breadcrumbs('Account Managment', 'Sessions') }}
@stop
@section('left_side')
{{ Helper::left_side('Account Managment', 'Sessions') }}
@stop
@section('content')

<div id="sessions_table"></div>


@stop
@section('pr-css')
@stop
@section('pr-scripts')
@stop
@section('scripts')
<script type="text/javascript">

$.ajax({ 
	type: 'POST',
	url: '{{ URL::route('sessions') }}',
	data : 
	{
		'ajax_action'               : 'get_sessions'
	},
	beforeSend: function(request) {
		return request.setRequestHeader("X-CSRF-Token", $("meta[name='token']").attr('content'));
	},
	success: function(msg)
	{
		if (msg.success != undefined) {
			$.each(msg.errors, function(index, error) {
				$.smallBox({
					title : "Token Mismatch",
					content : "<i class='fa fa-clock-o'></i> <i>Please refresh the page.</i>",
					color : "#C46A69",
					iconSmall : "fa fa-times fa-2x bounce animated",
					timeout : 4000
				});
			});
		} else {
			$('#sessions_table').show().html(msg);
		}
	}
});

</script>
@stop