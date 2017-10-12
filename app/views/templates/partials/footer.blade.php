</div>
<!-- END MAIN CONTENT -->
</div>
<!-- END MAIN PANEL -->
<!-- PAGE FOOTER -->
<div class="page-footer">
	<div class="row">
		<div class="col-xs-12 col-sm-12">
			<span class="txt-color-white">Powered by - 4 global © 2016</span>
			<div class="pull-right">
				<span> <a href="https://www.4global.com" target="_blank"><img src="{{ URL::asset('img/global.png') }}" alt="4global" style="height: 32px !important; width: auto !important;"></a> </span>
			</div>
		</div>
	</div>
</div>
<!-- END PAGE FOOTER -->
<!-- SHORTCUT AREA : With large tiles (activated via clicking user name tag)
Note: These tiles are completely responsive,
you can add as many as you like
-->
<!-- END SHORTCUT AREA -->
<!--================================================== -->
<!-- PACE LOADER - turn this on if you want ajax loading to show (caution: uses lots of memory on iDevices)
<script data-pace-options='{ "restartOnRequestAfter": true }' src="js/plugin/pace/pace.min.js"></script>
<!-- Link to Google CDN's jQuery + jQueryUI; fall back to local -->
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
<!-- IMPORTANT: APP CONFIG -->
<script src="{{URL::asset('js/app.config.js')}}"></script>
<!-- JS TOUCH : include this plugin for mobile drag / drop touch events-->
<script src="{{URL::asset('js/plugin/jquery-touch/jquery.ui.touch-punch.min.js')}}"></script>
<!-- BOOTSTRAP JS -->
<script src="{{URL::asset('js/bootstrap/bootstrap.min.js')}}"></script>
<!-- CUSTOM NOTIFICATION -->
<script src="{{URL::asset('js/notification/SmartNotification.min.js')}}"></script>
<!-- JARVIS WIDGETS -->
<script src="{{URL::asset('js/smartwidgets/jarvis.widget.min.js')}}"></script>
<!-- EASY PIE CHARTS
<script src="{{URL::asset('js/plugin/easy-pie-chart/jquery.easy-pie-chart.min.js')}}"></script>
<!-- SPARKLINES
<script src="{{URL::asset('js/plugin/sparkline/jquery.sparkline.min.js')}}"></script>
<!-- JQUERY VALIDATE
<script src="{{URL::asset('js/plugin/jquery-validate/jquery.validate.min.js')}}"></script>
<!-- JQUERY MASKED INPUT
<script src="{{URL::asset('js/plugin/masked-input/jquery.maskedinput.min.js')}}"></script>
<!-- JQUERY SELECT2 INPUT -->
<script src="{{URL::asset('js/plugin/select2/select2.min.js')}}"></script>
<!-- JQUERY UI + Bootstrap Slider
<script src="js/plugin/bootstrap-slider/bootstrap-slider.min.js')}}"></script>
<!-- browser msie issue fix -->
<script src="{{URL::asset('js/plugin/msie-fix/jquery.mb.browser.min.js')}}"></script>
	<!-- highcharts <script src="https://code.highcharts.com/stock/highstock.js"></script>
<script src="https://code.highcharts.com/highcharts-more.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>-->
<script src="{{ asset('js/plugin/highcharts/highstock.js')}}"></script>
<!-- <script src="https://code.highcharts.com/stock/highstock.js"></script> -->
<script src="{{ asset('js/plugin/highcharts/highcharts-more.js')}}"></script>
<script src="{{ asset('js/plugin/highcharts/exporting.js')}}"></script>
<script src="{{URL::asset('js/plugin/highcharts/map.js')}}"></script>
<script src="{{URL::asset('js/plugin/highcharts/modules/drilldown.js')}}"></script>
<!-- FastClick: For mobile devices
<script src="js/plugin/fastclick/fastclick.min.js')}}"></script>
<!--[if IE 8]>
<h1>Your browser is out of date, please update your browser by going to www.microsoft.com/download</h1>
<![endif]-->
<!-- MAIN APP JS FILE -->
<script src="{{URL::asset('js/app.min.js')}}"></script>
<!-- ENHANCEMENT PLUGINS : NOT A REQUIREMENT -->
<!-- Voice command : plugin -->
<!-- PAGE RELATED PLUGIN(S)
	<script src="..."></script>-->
	<script type="text/javascript" src="{{URL::asset('js/idle/jquery.idle.js')}}"></script>
	<script src="{{URL::asset('js/plugin/datatables/jquery.dataTables.min.js')}}"></script>
	<script src="{{URL::asset('js/plugin/datatables/dataTables.colVis.min.js')}}"></script>
	<script src="{{URL::asset('js/plugin/datatables/dataTables.tableTools.min.js')}}"></script>
	<script src="{{URL::asset('js/plugin/datatables/dataTables.bootstrap.min.js')}}"></script>
	<script src="{{URL::asset('js/plugin/datatable-responsive/datatables.responsive.min.js')}}"></script>
	<script src="{{URL::asset('js/plugin/sparkline/jquery.sparkline.min.js')}}"></script>
	<script src="{{URL::asset('js/plugin/easy-pie-chart/jquery.easy-pie-chart.min.js')}}"></script>
	@yield('pr-scripts', '')
	@yield('pr-scripts1', '')
	@yield('pr-scripts2', '')
	@yield('pr-scripts3', '')
	<script type="text/javascript">
		// function printDiv(divName) {
		// 	var printContents = document.getElementById(divName).innerHTML;
		// 	var originalContents = document.body.innerHTML;
		// 	document.body.innerHTML = printContents;
		// 	window.print();
		// 	document.body.innerHTML = originalContents;
		// }
		$.ajaxSetup({
			headers: {
				'X-CSRF-Token': $("meta[name='token']").attr('content')
			}
		});
		function downloadInnerHtml(filename, elId, mimeType) {
			var elHtml = document.getElementById(elId).innerHTML;
			var link = document.createElement('a');
			mimeType = mimeType || 'text/plain';
			link.setAttribute('download', filename);
			link.setAttribute('href', 'data:' + mimeType + ';charset=utf-8,' + encodeURIComponent(elHtml));
			link.click(); 
		}
		$('#export_button').click(function(){
			var name = $(this).attr("data-filtername")+'.html';
			downloadInnerHtml(name, 'filter_details','text/html');
		});
		function validateEmail($email) {
			var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
			return emailReg.test( $email );
		}
		$(document).ready(function() {
			$('.dropdown-menu').click(function(e) {
				e.stopPropagation();
			});
			var entityMap = {
				"&": "&amp;",
				"<": "&lt;",
				">": "&gt;",
				",": "&#44;",
				'"': '&#34;',
				"'": '&#39;',
				"/": '&#x2F;'
			};
			function escapeHtml(string) {
				return String(string).replace(/[&<>,"'\/]/g, function (s) {
					return entityMap[s];
				});
			}
			$.fn.serializeObject = function () {
				var o = {};
				var a = this.serializeArray();
				$.each(a, function () 
				{
					if (o[this.name]) 
					{
						if (!o[this.name].push) 
						{
							o[this.name] = [o[this.name]];
						}
						o[this.name].push(escapeHtml(this.value) || '');
					} 
					else 
					{
						o[this.name] = escapeHtml(this.value) || '';
					}
				});
				return o;
			};
			$.fn.serializeObjectNumber = function () {
				var o = {};
				var a = this.serializeArray();
				var counter = 0;
				$.each(a, function () 
				{
					var previous = counter - 1;
					counter++;

					if(counter % 2 == 0)
					{
						if (o[this.name]) 
						{
							if (!o[this.name].push) 
							{
								o[this.name] = [o[this.name]];
							}
							o[this.name].push(escapeHtml(a[previous].value) || '0');
						} 
						else 
						{
							o[this.name] = escapeHtml(a[previous].value) || '0';
						}
					}
				});
				return o;
			};
			$(document).ajaxStart(function(){
				$('.modal_loading').show();
			});
			$(document).ajaxStop(function(){
				$('.modal_loading').hide();
			});
			$(".select2_single").select2({
				placeholder: "Please select an option",
				allowClear: true,
			});
			$(document).idle({
				onIdle: function(){
					$.ajax({
						type: 'POST',
						url: '{{ URL::route('account-lock') }}',
						data :
						{
						},
						beforeSend: function(request) {
							return request.setRequestHeader("X-CSRF-Token", $("meta[name='token']").attr('content'));
						},
						success: function(msg)
						{
							window.location = '{{ URL::route('account-locked') }}'
						}
					});
				},
				idle: 1000*60*30
			})
			pageSetUp();
		})
		// (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		// 	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		// 	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		// })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
		// ga('create', 'UA-56321434-1', 'auto');
		// ga('send', 'pageview');
	</script>
	@yield('scripts', '')
	<?php Session::put('last_page', Route::currentRouteName()); ?>
</body>
</html>