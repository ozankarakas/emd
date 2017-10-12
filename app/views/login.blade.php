<!DOCTYPE html>
<html lang="en">
	<head>
		<link rel="icon" type="image/png" href="img/global.png" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta charset="utf-8">
		<title>EMD</title>
		<!--<meta name="viewport" content="width=device-width, initial-scale=1.0">-->
		<meta name="description" content="">
		<meta name="author" content="">
		<!-- basic styles -->
		<link rel="stylesheet" href="css/font-awesome.min.css" />
		<link href="old/bootstrap.min_old.css" rel="stylesheet" />
		<link rel="stylesheet" href="old/ace.min.css" />
		<style type="text/css">
		@font-face {
			font-family: 'OfficinaSansITCStd-Book';
			src: url('old/OfficinaSansITCStd-Book.otf'),
			url('old/OfficinaSansITCStd-Book.otf') format('otf');
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
			/*height: 100%;*/
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
					<img alt="EMD-Logo" src="{{ URL::asset('img/asa.png') }}">
					</h1>
				</div>
			</div>
		</div>
		<img src="{{ URL::asset('img/background.jpg') }}" class="bg">
		<div class="container">
			<form class="form-signin" method="post" action="{{ URL::action('login') }}">
				<h3 class="form-signin-heading" style="text-align: center;"></h3>
				<input name="user" id="user" type="text" class="input-block-level" placeholder="Username" value="">
				<input name="pass" id="pass" type="password" class="input-block-level" placeholder="Password" value="">
				@if( Session::has('errors') )
					<div class="alert alert-danger">
					<h4>Login Error</h4>
					<ul>
						@foreach ( Session::get('errors')->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
				@endif
				@if( Session::has('error') )
					<div class="alert alert-danger">
					<h4>Login Error</h4>
					<ul>
						<li>{{ Session::get('error') }}</li>
					</ul>
				</div>
				@endif
				<button name="submit" class="btn btn-block btn-primary btn-xs" type="submit" style="line-height: 30px; border-width: 2px;">
				<i class="icon-unlock"></i> Sign in
				</button>
				<br><br>
				<div style="float: right">
					<a href="https://www.4global.com/" target="_blank"> <img src="{{ URL::asset('img/global.png') }}" title="4global"></a>
				</div>
				{{ Form::token() }}
			</form>
		</div>
	</body>
</html>