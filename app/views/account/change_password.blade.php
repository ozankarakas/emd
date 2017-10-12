@extends('templates.default')

@section('title')
	Blog
@stop

@section('content')

	@if (Session::has('message'))
		<p>{{ Session::get('message') }}</p>
	@endif

	{{ Form::open(array('url' => URL::action('account-changepassword'), 'method' => 'post')) }}

		{{ Form::label('old_password','Current password:') }}
		{{ Form::password('old_password') }}
		<br>
		{{ Form::label('new_password','Password:') }}
		{{ Form::password('new_password') }}
		<br>
		{{ Form::label('new_password_again','Password again:') }}
		{{ Form::password('new_password_again') }}
		<br>
		{{ Form::button('Change Password', ['id' => 'change_password']) }}

	{{ Form::close() }}
	
@stop

@section('css')
@stop

@section('scripts')
<script type="text/javascript">
	$(document).ready(function() {
		$('#change_password').on('click', function(event) {
			$.ajax({
				url: "{{ URL::action('account-changepassword') }}",
				type: 'POST',
				data: {
					old_password 		: $('#old_password').val(),
					new_password 		: $('#new_password').val(),
					new_password_again	: $('#new_password_again').val(),
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