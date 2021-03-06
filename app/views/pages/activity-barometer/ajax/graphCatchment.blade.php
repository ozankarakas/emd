<?php 

$pools = implode("','",$pools); 

?>

<article class="col-sm-12 col-md-12 col-lg-12">

	<div class="jarviswidget jarviswidget-color-darken" data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false" data-widget-collapsed="false">

		<header>

			<span class="widget-icon"> <i class="fa fa-table"></i> </span>

			<h2>Population Map</h2>

		</header>

		<div>

			<div class="jarviswidget-editbox">

			</div>

			<div class="widget-body no-padding">

				<div id="map_population" style="height:520px;margin:0px;padding:0px;"></div>



				<div class='cartodb-legend choropleth'>	

					<div class="legend-title">Population</div>

					<ul>

						<li class="min" id="min_p">

							0

						</li>

						<li class="max" id="max_p">

							0

						</li>

						<li class="graph count_441">

							<div class="colors">

								<div class="quartile" style="background-color:#FFFFB2"></div>

								<div class="quartile" style="background-color:#FED976"></div>

								<div class="quartile" style="background-color:#FEB24C"></div>

								<div class="quartile" style="background-color:#FD8D3C"></div>

								<div class="quartile" style="background-color:#FC4E2A"></div>

								<div class="quartile" style="background-color:#E31A1C"></div>

								<div class="quartile" style="background-color:#B10026"></div>

							</div>

						</li>

					</ul>

				</div>





				<div id="infowindow_template_p_1" class="hidden">

					<div id="infowindow_template_p_1_container" class="cartodb-popup dark v2">

						<a href="#close" class="cartodb-popup-close-button close">x</a>

						<div class="cartodb-popup-content-wrapper">

							<div class="cartodb-popup-content">

								<h4>Lsoacode</h4>

								<p><?php echo "{{lsoacode}}"; ?></p>

								<h4>Lsoaname</h4>

								<p><?php echo "{{lsoa11nm}}"; ?></p>

								<h4>Local Authority</h4>

								<p><?php echo "{{laname}}"; ?></p>

								<h4>Region</h4>

								<p><?php echo "{{regname}}"; ?></p>

								<h4>Population</h4>

								<p><?php echo "{{p}}"; ?></p>

							</div>

						</div>

						<div class="cartodb-popup-tip-container"></div>

					</div>

				</div>



				<div id="infowindow_template_p_2" class="hidden">

					<div id="infowindow_template_p_2_container" class="cartodb-popup dark v2">

						<a href="#close" class="cartodb-popup-close-button close">x</a>

						<div class="cartodb-popup-content-wrapper">

							<div class="cartodb-popup-content">

								<h4>Site Name</h4>

								<p><?php

									if(Auth::user()->demo)

									{

										echo "Demo Leisure Centre";

									}

									else

									{

										echo "{{site_name}}";

									}



									?>

								</p>

								<h4>Area in SqMeters</h4>

								<p><?php echo "{{area_sqm}}"; ?></p>

								<h4>Pool Type</h4>

								<p><?php echo "{{type}}"; ?></p>

							</div>

						</div>

						<div class="cartodb-popup-tip-container"></div>

					</div>

				</div>

			</div>

		</div>

	</div>

</article>

<article class="col-sm-12 col-md-12 col-lg-12">

	<div class="jarviswidget jarviswidget-color-darken" data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false" data-widget-collapsed="false">

		<header>

			<span class="widget-icon"> <i class="fa fa-table"></i> </span>

			<h2>Membership % Per Area - Last 3 months</h2>

		</header>

		<div>

			<div class="jarviswidget-editbox">

			</div>

			<div class="widget-body no-padding">

				<div id="map_conversion" style="height:520px;margin:0px;padding:0px;"></div>

				<div class='cartodb-legend choropleth'>

					<div class="legend-title">Membership (‰)</div>

					<ul>

						<li class="min" id="min_c">

							0

						</li>

						<li class="max" id="max_c"> 

							0

						</li>

						<li class="graph count_441">

							<div class="colors">

								<div class="quartile" style="background-color:#FFFFB2"></div>

								<div class="quartile" style="background-color:#FED976"></div>

								<div class="quartile" style="background-color:#FEB24C"></div>

								<div class="quartile" style="background-color:#FD8D3C"></div>

								<div class="quartile" style="background-color:#FC4E2A"></div>

								<div class="quartile" style="background-color:#E31A1C"></div>

								<div class="quartile" style="background-color:#B10026"></div>

							</div>

						</li>

					</ul>

				</div>





				<div id="infowindow_template_c_1" class="hidden">

					<div id="infowindow_template_c_1_container" class="cartodb-popup dark v2">

						<a href="#close" class="cartodb-popup-close-button close">x</a>

						<div class="cartodb-popup-content-wrapper">

							<div class="cartodb-popup-content">

								<h4>Lsoacode</h4>

								<p><?php echo "{{lsoacode}}"; ?></p>

								<h4>Lsoaname</h4>

								<p><?php echo "{{lsoa11nm}}"; ?></p>

								<h4>Conversion (per 1000)</h4>

								<p><?php echo "‰{{c}}"; ?></p>

								<h4>Population</h4>

								<p><?php echo "{{p}}"; ?></p>

								<!-- <h4>Sites</h4>

								<p><?php echo "{{siteid_array}}"; ?></p> -->

							</div>

						</div>

						<div class="cartodb-popup-tip-container"></div>

					</div>

				</div>



				<div id="infowindow_template_c_2" class="hidden">

					<div id="infowindow_template_c_2_container" class="cartodb-popup dark v2">

						<a href="#close" class="cartodb-popup-close-button close">x</a>

						<div class="cartodb-popup-content-wrapper">

							<div class="cartodb-popup-content">

								<h4>Site Name</h4>

								<p><?php

									if(Auth::user()->demo)

									{

										echo "Demo Leisure Centre";

									}

									else

									{

										echo "{{site_name}}";

									}



									?>

								</p>

								<h4>Area in SqMeters</h4>

								<p><?php echo "{{area_sqm}}"; ?></p>

								<h4>Pool Type</h4>

								<p><?php echo "{{type}}"; ?></p>

							</div>

						</div>

						<div class="cartodb-popup-tip-container"></div>

					</div>

				</div>



			</div>

		</div>

	</div>

</article>

<script type="text/javascript">

	var allCartoCSS_p = "";

	var vector_p = [];

	var allCartoCSS_c = "";

	var vector_c = [];

	var colors = ["#B10026","#E31A1C","#FC4E2A","#FD8D3C","#FEB24C","#FED976","#FFFFB2"];



	function main_population() 

	{

		<?php

		if($zoom == "none")

		{ 

			$lat = "53";

			$lon = "-2";

			$zo = "7";

		}

		else

		{

			foreach ($zoom as $v) 

			{

				$lat = $v->latitude;

				$lon = $v->longitude;

				$zo = "12";

			}

		}

		?>



		var map_p = new L.Map('map_population', {

			zoomControl: false,

			center: [<?php echo $lat; ?>, <?php echo $lon; ?>],

			zoom: <?php echo $zo; ?>,

			zoomInfo: true

		});



		var basemap = L.tileLayer('//cartodb-basemaps-{s}.global.ssl.fastly.net/light_all/{z}/{x}/{y}.png', {}).addTo(map_p);



		L.control.zoom({

			position:'bottomleft'

		}).addTo(map_p);



		L.Control.zoomInfo = L.Control.extend({

			options: {position: 'bottomleft'},

			onAdd: function (map_p) {

				var z0_p = map_p.getZoom();

				zinfo_p = L.DomUtil.create('div','control-zinfo');

				zinfoUI_p = L.DomUtil.create('div', 'control-zinfo control-zinfo-interior', zinfo_p);

				zinfoUI_p.innerHTML=z0_p;

				map_p.on('zoomend', function(e){

					zinfoUI_p.innerHTML =  this.getZoom();

				})

				return zinfo_p;

			} 

		});



		map_p.addControl(new L.Control.zoomInfo());



		cartodb.createLayer(map_p,  {

			tiler_protocol: "https",

			tiler_port: 443,

			user_name: '4global-admin',

			type: 'cartodb',

			cartodb_logo: false,

			sublayers: [{

				sql: "<?php echo $query_p; ?>",

				cartocss: "#population{polygon-fill: #FFFFB2; polygon-opacity: 0.8; line-color: #FFF; line-width: 0.5; line-opacity: 1; }",

				interactivity: 'lsoacode, lsoa11nm, laname, regname, p'

			},

			{

				sql: "SELECT * FROM all_facilities",
			cartocss: "#all_facilities{marker-fill-opacity: 0.9;marker-line-color: #FFF;marker-line-width: 2.5;marker-line-opacity: 1;marker-placement: point;marker-multi-policy: largest;marker-type: ellipse;marker-fill: #5CA2D1;marker-allow-overlap: true;marker-clip: false;}#all_facilities [ area_sqm <= 3020] {marker-width: 25.0;}#all_facilities [ area_sqm <= 775] {marker-width: 23.3;}#all_facilities [ area_sqm <= 565] {marker-width: 21.7;}#all_facilities [ area_sqm <= 492] {marker-width: 20.0;}#all_facilities [ area_sqm <= 429] {marker-width: 18.3;}#all_facilities [ area_sqm <= 409] {marker-width: 16.7;}#all_facilities [ area_sqm <= 375] {marker-width: 15.0;}#all_facilities [ area_sqm <= 334] {marker-width: 13.3;}#all_facilities [ area_sqm <= 282] {marker-width: 11.7;}#all_facilities [ area_sqm <= 220] {marker-width: 10.0;}<?php echo $colorized; ?>",
			interactivity: 'cartodb_id, area_sqm, site_name, type',

			}]

		}).addTo(map_p).on('done', function(lyr) 

		{

			var v = cdb.vis.Overlay.create('search', map_p.viz, {});

			v.show();   

			$('#map_population').append(v.render().el);



			var sql = new cartodb.SQL({ user: '4global-admin'});



			sql.execute("<?php echo $json_p; ?>").done(function(data)

			{

				for(i = 0; i < data.total_rows; i++)

				{ 

					var color = colors[i];

					vector_p[i] = data.rows[i].p;



					thisCartoCSS = '#population[p<=' + data.rows[i].p + ']{polygon-fill:' + color + ';}';

					allCartoCSS_p += thisCartoCSS;

				}

				allCartoCSS_p = "#population{polygon-fill: #FFFFB2; polygon-opacity: 0.8; line-color: #FFF; line-width: 0.5; line-opacity: 1; }" + allCartoCSS_p;



				lyr.setCartoCSS(allCartoCSS_p);

				$('#min_p').text("<="+vector_p[6]);

				$('#max_p').text("<="+vector_p[0]);

			});



			var sublayer0 = lyr.getSubLayer(0);

			cdb.vis.Vis.addInfowindow(map_p, sublayer0,

				['lsoacode','lsoa11nm','laname','regname','p'],

				{infowindowTemplate: $('#infowindow_template_p_1').html()});

			var sublayer1 = lyr.getSubLayer(1);

			cdb.vis.Vis.addInfowindow(map_p, sublayer1,

				['cartodb_id','area_sqm','site_name','type'],

				{infowindowTemplate: $('#infowindow_template_p_2').html()});



			sublayer0.on('featureClick', function(e) 

			{

				$('#infowindow_template_p_2_container').hide();

				$('#infowindow_template_p_1_container').show();

			});



			sublayer1.on('featureClick', function(e) 

			{

				$('#infowindow_template_p_2_container').show();

				$('#infowindow_template_p_1_container').hide();

			});



		});

	}



	function main_conversion() {



		<?php

		if($zoom == "none")

		{ 

			$lat = "53";

			$lon = "-2";

			$zo = "7";

		}

		else

		{

			foreach ($zoom as $v) 

			{

				$lat = $v->latitude;

				$lon = $v->longitude;

				$zo = "12";

			}

		}

		?>



		var map = new L.Map('map_conversion', {

			zoomControl: false,

			center: [<?php echo $lat; ?>, <?php echo $lon; ?>],

			zoom: <?php echo $zo; ?>,

			zoomInfo: true

		});



		var basemap = L.tileLayer('//cartodb-basemaps-{s}.global.ssl.fastly.net/light_all/{z}/{x}/{y}.png', {}).addTo(map);



		L.control.zoom({

			position:'bottomleft'

		}).addTo(map);



		L.Control.zoomInfo = L.Control.extend({

			options: {position: 'bottomleft'},

			onAdd: function (map) {

				var z0 = map.getZoom();

				zinfo = L.DomUtil.create('div','control-zinfo ');

				zinfoUI = L.DomUtil.create('div', 'control-zinfo control-zinfo-interior', zinfo);

				zinfoUI.innerHTML=z0;

				map.on('zoomend', function(e){

					zinfoUI.innerHTML =  this.getZoom();

				})

				return zinfo;

			} 

		});



		map.addControl(new L.Control.zoomInfo());



		cartodb.createLayer(map,  {

			tiler_protocol: "https",

			tiler_port: 443,

			user_name: '4global-admin',

			type: 'cartodb',

			cartodb_logo: false,

			sublayers: [

			{

				sql: "<?php echo $query_c; ?>", 

				cartocss: "#population{polygon-fill: #FFFFB2;polygon-opacity: 0.8;line-color: #FFF;line-width: 0.5;line-opacity: 1;}",

				interactivity: 'lsoacode, lsoa11nm, c, p, siteid_array'

			},

			{

				sql: "SELECT * FROM all_facilities",
				cartocss: "#all_facilities{marker-fill-opacity: 0.9;marker-line-color: #FFF;marker-line-width: 2.5;marker-line-opacity: 1;marker-placement: point;marker-multi-policy: largest;marker-type: ellipse;marker-fill: #5CA2D1;marker-allow-overlap: true;marker-clip: false;}#all_facilities [ area_sqm <= 3020] {marker-width: 25.0;}#all_facilities [ area_sqm <= 775] {marker-width: 23.3;}#all_facilities [ area_sqm <= 565] {marker-width: 21.7;}#all_facilities [ area_sqm <= 492] {marker-width: 20.0;}#all_facilities [ area_sqm <= 429] {marker-width: 18.3;}#all_facilities [ area_sqm <= 409] {marker-width: 16.7;}#all_facilities [ area_sqm <= 375] {marker-width: 15.0;}#all_facilities [ area_sqm <= 334] {marker-width: 13.3;}#all_facilities [ area_sqm <= 282] {marker-width: 11.7;}#all_facilities [ area_sqm <= 220] {marker-width: 10.0;}<?php echo $colorized; ?>",
				interactivity: 'cartodb_id, area_sqm, site_name, type',

			}]

		}).addTo(map).on('done', function(lyr) {



			var v = cdb.vis.Overlay.create('search', map.viz, {});

			v.show();

			$('#map_conversion').append(v.render().el);



			var sql = new cartodb.SQL({ user: '4global-admin'});



			sql.execute("<?php echo $json_c; ?>")

			.done(function(data)

			{

				for(i = 0; i < data.total_rows; i++)

				{ 

					var color = colors[i];

					vector_c[i] = data.rows[i].c;



					thisCartoCSS = '#population[c<=' + data.rows[i].c + ']{polygon-fill:' + color + ';}';

					allCartoCSS_c += thisCartoCSS;

				}

				allCartoCSS_c = "#population{polygon-fill: #FFFFB2; polygon-opacity: 0.8; line-color: #FFF; line-width: 0.5; line-opacity: 1; }" + allCartoCSS_c;



				lyr.setCartoCSS(allCartoCSS_c);

				$('#min_c').text("<="+vector_c[6]);

				$('#max_c').text("<="+vector_c[0]);

			});





			var sublayer0 = lyr.getSubLayer(0);

			cdb.vis.Vis.addInfowindow(map, sublayer0,

				['lsoacode','lsoa11nm','c', 'p', 'siteid_array'],

				{infowindowTemplate: $('#infowindow_template_c_1').html()});

			var sublayer1 = lyr.getSubLayer(1);

			cdb.vis.Vis.addInfowindow(map, sublayer1,

				['cartodb_id', 'area_sqm', 'site_name', 'type'],

				{infowindowTemplate: $('#infowindow_template_c_2').html()});



			sublayer0.on('featureClick', function(e) 

			{

				$('#infowindow_template_c_2_container').hide();

				$('#infowindow_template_c_1_container').show();

			});



			sublayer1.on('featureClick', function(e) 

			{

				$('#infowindow_template_c_2_container').show();

				$('#infowindow_template_c_1_container').hide();

			});

		});

	}



	$( document ).ready(function() {

		main_population();

		main_conversion();



		$('.modal_loading').hide();

	});

</script>

@include('templates.widget')