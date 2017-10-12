@extends('template.default')

@section('title')
	Blog
@stop

@section('content')
	{{ Form::open(array('url' => URL::action('account-signin'),'method'=>'post')) }}
		{{ Form::label('username', 'Username:') }}
		{{ Form::text('username') }}
		<br>
		{{ Form::label('password','Password:') }}
		{{ Form::password('password') }}
		<br>
		{{ Form::label('remember','Remember Me') }}
		{{ Form::checkbox('remember', 'value') }}
		<br>
		{{ Form::button('Sign in', ['id' => 'create_account']) }}
	{{ Form::close() }}
@stop

@section('css')
	
@stop

@section('scripts')
<script type="text/javascript">
	$(document).ready(function() {
		$('#create_account').on('click', function(event) {
			$.ajax({
				url: "{{ URL::action('account-signin') }}",
				type: 'POST',
				data: {
					username 			: $('#username').val(),
					password 			: $('#password').val(),
					remember 			: $('#remember').prop('checked')
				},
				beforeSend: function(request) {
				    return request.setRequestHeader("X-CSRF-Token", $("meta[name='token']").attr('content'));
				},
				success: function (msg) 
				{
					if (!msg.success) {
						$.each(msg.errors, function(index, error) {
							 console.log(error);
						});
					} else {
						window.location.href = "{{URL::action('home')}}";
					}
				}
			})
			.done(function() {
				console.log("success");
			})
			.fail(function() {
				console.log("error");
			})
			.always(function() {
				console.log("complete");
			});

			event.preventDefault();
			/* Act on the event */
		});
	});
</script>
@stop