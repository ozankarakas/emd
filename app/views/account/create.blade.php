@extends('template.default')

@section('title')
	Blog
@stop

@section('content')
	{{ Form::open(array('url' => URL::action('account-create'),'method'=>'post')) }}
		{{ Form::label('username', 'Username:') }}
		{{ Form::text('username') }}
		<br>
		{{ Form::label('name', 'Name:') }}
		{{ Form::text('name') }}
		<br>
		{{ Form::label('surname', 'Surname:') }}
		{{ Form::text('surname') }}
		<br>
		{{ Form::label('email','E-mail:') }}
		{{ Form::text('email') }}
		<br>
		{{ Form::label('password','Password:') }}
		{{ Form::password('password') }}
		<br>
		{{ Form::label('password_again','Repeat password:') }}
		{{ Form::password('password_again') }}
		<br>
		{{ Form::button('Button', ['id' => 'create_account']) }}
	{{ Form::close() }}
@stop

@section('css')
	
@stop

@section('scripts')
<script type="text/javascript">
	$(document).ready(function() {
		$('#create_account').on('click', function(event) {
			$.ajax({
				url: "{{ URL::action('account-create') }}",
				type: 'POST',
				data: {
					username 			: $('#username').val(),
					name 				: $('#name').val(),
					surname 			: $('#surname').val(),
					email 				: $('#email').val(),
					password 			: $('#password').val(),
					password_again 		: $('#password_again').val()
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