<!DOCTYPE html>
<html lang="en">
	<head>
		<link rel="icon" type="image/png" href="img/global.png" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta charset="utf-8">
		<meta name="token" content="{{ Session::token() }}">
		<title>EMD &middot; 4Global</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<!-- <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user- scalable=no"> -->
		<meta name="description" content="">
		<meta name="author" content="1190">
		<!-- basic styles -->
		<link rel="stylesheet" type="text/css" media="screen" href="{{ URL::asset('css/bootstrap.min.css') }}">
		<link rel="stylesheet" type="text/css" media="screen" href="{{ URL::asset('css/font-awesome.min.css') }}">
		<link rel="stylesheet" type="text/css" media="screen" href="{{ URL::asset('css/custom.css') }}">
		<link rel="stylesheet" type="text/css" media="screen" href="{{ URL::asset('css/sb.css') }}">

		{{-- Custom Fonts --}}
		<link href='https://fonts.googleapis.com/css?family=arvo:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
		<style type="text/css">
		body { 
			background: url('{{ URL::asset('custom/bg001.jpg') }}') no-repeat center center fixed; 
			-webkit-background-size: cover;
			-moz-background-size: cover;
			-o-background-size: cover;
			background-size: cover;
		}
		@font-face {
			font-family: 'OfficinaSansITCStd-Book';
			src: url('old/OfficinaSansITCStd-Book.otf'),
			url('old/OfficinaSansITCStd-Book.otf') format('otf');
		}

		.custom_font{
			font-family: 'OfficinaSansITCStd-Book' !important;
		}

		.ribbon_lg {
			top: 0;
			float: left;
			width: 10%;
			position: absolute;
			z-index: 999;
		}
		.ribbon_md {
			top: 0;
			float: left;
			width: 10%;
			position: absolute;
            z-index: 999;
		}
		.ribbon_sm {
			top: 0;
			float: left;
			width: 12%;
			position: absolute;
			z-index: 999;
		}
		.ribbon_xs {
			top: 0;
			float: left;
			width: 20%;
			position: absolute;
            z-index: 999;
		}

		.at_bottom {
			position: fixed;
			bottom: 5%;
		}
		</style>
	</head>
	<body>
		<img src="{{ asset('custom/0003.png') }}" class="ribbon_lg visible-lg">
		<img src="{{ asset('custom/0003.png') }}" class="ribbon_md visible-md">
		<img src="{{ asset('custom/0003.png') }}" class="ribbon_sm visible-sm">
		<img src="{{ asset('custom/0003.png') }}" class="ribbon_xs visible-xs">
		<div class="container">
			<div class="row margin-top-10">
				<div class="well well-lg custom_well col-xs-6 col-xs-offset-3">
					<img class="top_logo" src="{{ asset('img/asa.png') }}" >
				</div>
			</div>
		</div>
		<div class="col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3 at_bottom">
			<button type="button" id="login_button" class="btn btn-lg btn-info login-button custom_font btn-block" data-loading-text="One Moment...">Login Now</button>
		</div>
		<div id="login_modal" class="modal fade custom_font">
          <div class="modal-dialog">
            <div class="modal-content">
				{{--<form role="form">--}}
				  <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title">Login To EMD</h4>
				  </div>
				  <div class="modal-body">
					  <div class="form-group">
						<label for="exampleInputEmail1">Enter Your Username</label>
						  <div class="input-group input-group-lg">
							<span class="input-group-addon"><i class="fa fa-user"></i></span>
							<input type="text" class="form-control" id="username" name="username" placeholder="Enter Username">
						  </div>
					  </div>
					  <div class="form-group">
						<label for="exampleInputPassword1">Enter Your Password</label>
					  	<div class="input-group input-group-lg">
							<span class="input-group-addon"><i class="fa fa-key"></i></span>
							<input type="password" class="form-control" id="password" name="password" id="exampleInputPassword1" placeholder="Password">
						</div>
					  </div>
					  <div class="form-group text-center">
						<label for="remember_me_checkbox">
							<button id="remember" type="button" class="btn btn-sm btn-success">Remember Me</button>
						</label>
					  </div>
				  </div>
				  <div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
					<button id="submit_button" class="btn btn-primary">Login</button>
				  </div>
                {{--</form>--}}
            </div><!-- /.modal-content -->
          </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
		<div class="modal_loading"></div>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
		<script>
			if (!window.jQuery) {
				document.write('<script src="{{URL::asset('js/libs/jquery-2.0.2.min.js')}}"><\/script>');
			}
		</script>
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
		<script>
			if (!window.jQuery.ui) {
				document.write('<script src="{{URL::asset('js/libs/jquery-ui-1.10.3.min.js')}}"><\/script>');
			}
		</script>
		<script src="{{URL::asset('js/bootstrap/bootstrap.min.js')}}"></script>
		<script src="{{URL::asset('js/notification/SmartNotification.min.js')}}"></script>

		<script>
			$(document).ready(function() {
			 	$('#login_button').on('click', function () {
			 		$('#login_modal').modal();
                });
                $('#remember').on('click', function() {
                  	$(this).toggleClass('active');
                  	var $cb = $('#remember_me_checkbox');
                  	$cb.toggle('click');
                });
                $('#submit_button').on('click', function() {
					$.ajax({
						type: 'POST',
						url: '{{ URL::route('test') }}',
						data :
						{
							'username' : $('#username').val(),
							'password' : $('#password').val(),
							'remember' : $('#remember').hasClass('active')
						},
						beforeSend: function(request) {
							return request.setRequestHeader("X-CSRF-Token", $("meta[name='token']").attr('content'));
						},
						success: function(msg)
						{
							if (msg.success != undefined) {
								$.each(msg.errors, function(index, error) {
									// console.log(error);
									$.smallBox({
										title : "Failed to login...",
										content : "<i class='fa fa-clock-o'></i> <i> " + error + "</i>",
										color : "#C46A69",
										iconSmall : "fa fa-times fa-2x bounce animated",
										timeout : 3000
									});
								});
							} else {
								window.location.href  = '{{ URL::route('guidance') }}'
							}
						}
					});
                });
			});
		</script>
	</body>
</html>