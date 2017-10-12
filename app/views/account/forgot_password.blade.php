<!DOCTYPE html>
<html lang="en">
	<head>
		<link rel="icon" type="image/png" href="{{ URL::asset('img/global.png') }}" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta charset="utf-8">
		<title>EMD</title>
		<!--<meta name="viewport" content="width=device-width, initial-scale=1.0">-->
		<meta name="description" content="">
		<meta name="author" content="">
			<meta name="token" content="{{ Session::token() }}">
		<!-- basic styles -->
		<link rel="stylesheet" href="{{ URL::asset('css/font-awesome.min.css') }}" />
		<link href="{{ URL::asset('old/bootstrap.min_old.css') }}" rel="stylesheet" />
		<link rel="stylesheet" href="{{ URL::asset('old/ace.min.css') }}" />
		<style type="text/css">
		@font-face {
			font-family: 'OfficinaSansITCStd-Book';
			src: url('{{ URL::asset('old/OfficinaSansITCStd-Book.otf') }}'),
			url('{{ URL::asset('old/OfficinaSansITCStd-Book.otf') }}') format('otf');
		}
		body {
			font-family: 'OfficinaSansITCStd-Book' !important;
			padding-top: 40px;
			padding-bottom: 40px;
			background-color: #f5f5f5;
		}
		.container {
			position: absolute;
			bottom: 0;
			left: 0;
			width: 100% !important;
		}
		.header_login {
			margin-right: 20px;
			margin-left: 20px;
			float: left;
			font-weight: lighter;
			color: #000;
			font-size: 32px;
			line-height: 32px;
		}
		.btn {
			font-family: 'OfficinaSansITCStd-Book' !important;
		}
		.form-signin {
			margin-right: 20px;
			margin-left: 20px;
			float: right;
			max-width: 300px;
			padding: 19px 29px 29px;
			background-color: rgba(255, 255, 255, 0.70);
			border: 1px solid rgba(255, 255, 255, 0.70);
			-webkit-border-radius: 15px;
			-moz-border-radius: 15px;
			border-radius: 15px;
			-webkit-box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
			-moz-box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
			box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
		}
		.form-signin .form-signin-heading,.form-signin .checkbox {
			margin-bottom: 10px;
		}
		.form-signin input[type="text"],.form-signin input[type="password"] {
			font-size: 16px;
			height: auto;
			margin-bottom: 15px;
			padding: 7px 9px;
		}
		img.bg {
			z-index: -1;
			/* Set rules to fill background */
			min-height: 100%;
			min-width: 1024px;
			/* Set up proportionate scaling */
			width: 100%;
			height: auto;
			/* Set up positioning */
			position: fixed;
			top: 0;
			left: 0;
		}
		@media screen and (max-width: 1024px) {
			/* Specific to this particular image */
			img.bg {
				z-index: -1;
				left: 50%;
				margin-left: -512px; /* 50% */
			}
		}
		#content-wrapper {
			display: table;
		}
		#content {
			display: table-row;
		}
		#content>div {
			display: table-cell
		}
		#content-wrapper {
			width: 50%;
			height: 100%;
			top: 3%;
			position: absolute;
			left: 25%;
		}
		#h_left {
			text-align: center;
			font-size: 300%;
			line-height: 120%;
			color: #053877;
			background-color: rgba(255, 255, 255, 0.70);
			-webkit-border-radius: 15px;
		}
		#h_right {
			padding-right: 20px;
			background: white;
		}
		.header_sub {
			margin-right: 20px;
			margin-left: 20px;
			font-weight: lighter;
			color: #000;
			font-size: 32px;
			line-height: 32px;
			text-align: center;
		}
		</style>
	</head>
	<body>
		<div id="content-wrapper">
			<div id="content">
				<div id="h_left">
					<h1 style="font-size: 50px; color: #DD0D15">
					<img alt="Guw-Logo" src="{{ URL::asset('img/asa.png') }}">
					</h1>
				</div>
			</div>
		</div>

		

		<img src="{{ URL::asset('img/background.jpg') }}" class="bg">
		<div class="container">
			<form class="form-signin" method="post" action="{{ URL::action('account-forgotpassword') }}">
				<h3 class="form-signin-heading" style="text-align: center;"></h3>
				<input name="email" id="email" type="email" class="input-block-level" placeholder="Email" value="">
				<button id="send_password" name="submit" class="btn btn-block btn-primary btn-xs submit" type="button" style="line-height: 30px; border-width: 2px;">
				<i class="icon-unlock"></i> Recover Password
				</button>
				<br><br>
				<div style="float: right">
					<a href="https://www.fourglobal.org/" target="_blank"> <img src="{{ URL::asset('img/global.png') }}" title="4global"></a>
				</div>
				{{ Form::token() }}
			</form>
		</div>
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
		<script type="text/javascript">
			$(document).ready(function() {
				$('.submit').on('click', function(event) {
					$.ajax({
						url: "{{ URL::action('account-forgotpassword') }}",
						type: 'POST',
						data: {
							email 		: $('#email').val(),
						},
		                beforeSend: function(request) {
		                    return request.setRequestHeader("X-CSRF-Token", $("meta[name='token']").attr('content'));
		                },
		                success: function(msg)
		                {	
		                	console.log(msg);
		                    if (msg.success != undefined) {
		                        $.each(msg.errors, function(index, error) {
		                            alert(error);
		                        });
		                    } else {
								window.location.href = "{{URL::action('home')}}";
							}
						}
					});

					event.preventDefault();
					/* Act on the event */
				});
			});
		</script>
	</body>
</html>
