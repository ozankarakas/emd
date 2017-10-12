<!DOCTYPE html>

<html lang="en-us">

<head>

	<meta charset="utf-8">

	<title>@yield('title', 'EMD')</title>

	<meta name="description" content="">

	<meta name="author" content="">

	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

	<meta name="token" content="{{ Session::token() }}">

	<!-- #CSS Links -->

	<!-- Basic Styles -->

	<link rel="stylesheet" type="text/css" media="screen" href="{{ URL::asset('css/bootstrap.min.css') }}">

	<link rel="stylesheet" type="text/css" media="screen" href="{{ URL::asset('css/font-awesome.min.css') }}">

	<!-- SmartAdmin Styles : Please note (smartadmin-production.css) was created using LESS variables -->

	<link rel="stylesheet" type="text/css" media="screen" href="{{ URL::asset('css/smartadmin-production.min.css') }}">

	<link rel="stylesheet" type="text/css" media="screen" href="{{ URL::asset('css/smartadmin-skins.min.css') }}">

	<link rel="stylesheet" type="text/css" media="screen" href="{{ URL::asset('css/custom.css') }}">

	<link rel="shortcut icon" href="{{ URL::asset('favicon.ico') }}" type="image/x-icon">

	<link rel="icon" href="{{ URL::asset('favicon.ico') }}" type="image/x-icon">

	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">

	@yield('pr-css')

	<script src="https://js.pusher.com/3.0/pusher.min.js"></script>

	<script>

		var pusher = new Pusher('7a88b804efd93a0b5a20', {

			encrypted: true

		});



		var channel = pusher.subscribe('personal.{{ Auth::user()->id }}');



		channel.bind('dashboard_created', function(data) {

			$.smallBox({

				title : "Success",

				content : "<i class='fa fa-check'></i> " + data.message,

				color : "#659265",

				iconSmall : "fa fa-check fa-2x bounce animated",

				timeout : 4000

			});

		});

	</script>

</head>

<body class="">

	<div class="modal_loading"></div>

	<!-- #HEADER -->

	<header id="header">

		<div id="logo-group">

			<!-- PLACE YOUR LOGO HERE -->

			<span id="logo"> <img src="{{ URL::asset('img/logo.png') }}" alt="EMD" style="height: 32px !important; width: auto !important;"> </span>

			<!-- END LOGO PLACEHOLDER -->	

		</div>

		<!-- #TOGGLE LAYOUT BUTTONS -->

		<!-- projects dropdown -->
		<div class="project-context hidden-xs">

			<span class="label">Applied Filter:</span>
			<span class="project-selector dropdown-toggle" data-toggle="dropdown"><?php 
				if(Session::has('filter_name'))
				{
					$export = true;
					$filter_name = Session::get('filter_name');
					if($filter_name == "custom")
					{
						$filter_name = "Custom";
						echo "$filter_name <i class='fa fa-angle-down'></i>";
					} 
					else
					{
						echo $filter_name." <i class='fa fa-angle-down'></i>";
					}
				}
				else
				{
					$export = false;
					echo "No Filter Selected";
				}
				?></span>

				<?php echo GeneralFunctions::filter_details(); ?>

			</div>
			<!-- end projects dropdown -->

			<!-- pulled right: nav area -->

			<div class="pull-right">

				<!-- collapse menu button -->

				<div class="pull-right" style="margin-left: 6px; margin-top:12px;">

					<span> <a href="#"><img src="{{ URL::asset('img/datahub.png') }}" alt="DataHub" style="height: 32px !important; width: auto !important;"></a> </span>

				</div>

				<div id="hide-menu" class="btn-header pull-right">

					<span> <a href="javascript:void(0);" data-action="toggleMenu" title="Collapse Menu"><i class="fa fa-reorder"></i></a> </span>

				</div>

				<!-- end collapse menu -->

				<!-- logout button -->

				<div id="logout" class="btn-header transparent pull-right">

					<span> <a href="{{URL::action('logout')}}" title="Sign Out" data-action="userLogout" data-logout-msg="You can improve your security further after logging out by closing this opened browser"><i class="fa fa-sign-out"></i></a> </span>

				</div>

				<!-- end logout button -->

				<!-- fullscreen button -->

				<div id="fullscreen" class="btn-header transparent pull-right">

					<span> <a href="javascript:void(0);" data-action="launchFullscreen" title="Full Screen"><i class="fa fa-arrows-alt"></i></a> </span>

				</div>

				<!-- end fullscreen button -->
				<?php 
				if(!$export)
				{
					?>
					<div class="btn-header transparent pull-right">

						<span> <a href="javascript:void(0);" title="First select a filter"><i class="fa fa-file-text"></i></a> </span>

					</div>
					<?php 
				}
				else
				{
					?>
					<div class="btn-header transparent pull-right">

						<span> <a href="javascript:void(0);" id="export_button" title="Export Filter Details" data-filtername="<?php echo $filter_name; ?>"><i class="fa fa-file-text"></i></a> </span>

					</div>
					<?php 
				}
				?>

				

			</div>

			<!-- end pulled right: nav area -->

		</header>

		<!-- END HEADER -->

		<!-- #NAVIGATION -->

		<!-- Left panel : Navigation area -->

		<!-- Note: This width of the aside area can be adjusted through LESS variables -->

		<aside id="left-panel">

			<!-- User info -->

			<div class="login-info">

				<span> <!-- User image size is adjusted inside CSS, it should stay as it --> 

					<a id="show-shortcut">

						<span>

							{{ Auth::user()->role }}: {{ Auth::user()->name }}

						</span>

					</a> 

				</span>

			</div>

			@yield('left_side', '')

			<span class="minifyme" data-action="minifyMenu"> 

				<i class="fa fa-arrow-circle-left hit"></i> 

			</span>

		</aside>

		<!-- END NAVIGATION -->

		<!-- MAIN PANEL -->

		<div id="main" role="main">

			<!-- RIBBON -->

			<div id="ribbon">

				<span class="ribbon-button-alignment"> 

					<span id="refresh" class="btn btn-ribbon" data-action="resetWidgets" data-title="refresh"  rel="tooltip" data-placement="bottom" data-original-title="<i class='text-warning fa fa-warning'></i> Warning! This will reset all your widget settings." data-html="true">

						<i class="fa fa-refresh"></i>

					</span> 

				</span>

				@yield('breadcrumbs')

			</div>

			@yield('map')

			<div id="content">