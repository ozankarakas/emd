<?php

class Helper {

	public static function left_side($c_menu, $c_submenu = null, $c_subsubmenu = null) 

	{

		$role 				= Auth::user()->role;

		$c_menu 			= strtolower($c_menu);

		$c_submenu 			= strtolower($c_submenu);

		$c_subsubmenu 		= strtolower($c_subsubmenu);

		/**

		 * If the user is logged out

		 */

		if ($role == "ADM") 

		{

			$main = array(

				[

				'title' 	=> 'Account Management',

				'link'  	=> '#',

				'icon'  	=> 'fa-group',

				'index'		=> [

				[

				'title' 	=> 'Guidance',

				'link'  	=> URL::action('guidance'),

				'icon'  	=> 'fa-book',

				'index'		=> false,

				],

				

				[

				'title' 	=> 'My Account',

				'link'  	=> URL::action('account'),

				'icon'  	=> 'fa-user',

				'index'		=> false,

				],

				[

				'title' 	=> 'Add EMD User',

				'link'  	=> URL::action('add-account'),

				'icon'  	=> 'fa-info',

				'index'		=> false,

				],

				[

				'title' 	=> 'List EMD Users',

				'link'  	=> URL::action('list-emd-users'),

				'icon'  	=> 'fa-male',

				'index'		=> false,

				],

				[

				'title' 	=> 'Operational View',

				'link'  	=> URL::action('passport'),

				'icon'  	=> 'fa-book',

				'index'		=> false,

				],

				[

				'title' 	=> 'Sessions',

				'link'  	=> URL::action('sessions'),

				'icon'  	=> 'fa-book',

				'index'		=> false,

				],

				[

				'title' 	=> 'Filters',

				'link'  	=> URL::action('filters'),

				'index'		=> false,

				],

				],

				],

				[

				'title' 	=> 'Dashboards',

				'link'  	=> '#',

				'icon'  	=> 'fa-dashboard',

				'index'		=> [

				[

				'title' 	=> 'KPI Dashboard',

				'link'  	=> URL::action('doverallkpi'),

				'index'		=> false,

				],

				[

				'title' 	=> 'League Table',

				'link'  	=> URL::action('leagueTable'),

				'icon'  	=> 'fa-user',

				'index'		=> false,

				],

				],

				],

				[

				'title' 	=> 'Reports & Analysis',

				'link'  	=> '#',

				'icon'  	=> 'fa-bar-chart-o',

				'index'		=> [

				[

				'title' 	=> 'Programme Analysis',

				'link'  	=> URL::action('activity-barometer-operations'),

				'index'		=> false,

				],

				[

				'title' 	=> 'Participation Analysis',

				'link'  	=> route('activity-barometer-bubble'),

				'index'		=> false,

				],

				[

				'title' 	=> 'Performance Report',

				'link'  	=> URL::action('spreport'),

				'icon'  	=> 'fa-user',

				'index'		=> false,

				],

				],

				],

				[

				'title' 	=> 'Maps',

				'link'  	=> '#',

				'icon'  	=> 'fa fa-globe',

				'index'		=> [

				[

				'title' 	=> 'Heat Map',

				'link'  	=> route('activity-barometer-catchment'),

				'index'		=> false,

				],

				],

				]);

		}



		?>

		<nav>

			<ul>

				<?php foreach ($main as $key => $menu) { #START FOREACH ?>

				<?php if ($menu['index'] == false) { #START IF ?>

				<li <?php echo ($c_menu == strtolower($menu['title'])) ? 'class="active"' : ''; ?> >

					<a href="<?php echo $menu['link']; ?>" target="_top" title="<?php echo $menu['title']; ?>"><i class="fa fa-lg fa-fw <?php echo $menu['icon'] ?>"></i> <span class="menu-item-parent"><?php echo $menu['title']; ?></span></a>

				</li>

				<?php } else { #ELSE ?> 

				<li <?php echo ($c_menu == strtolower($menu['title'])) ? 'class="active"' : ''; ?> >

					<a href="#"><i class="fa fa-lg fa-fw <?php echo $menu['icon'] ?>"></i> <span class="menu-item-parent"><?php echo $menu['title']; ?></span></a>

					<ul>

						<?php foreach ($menu['index'] as $key => $submenu) { #START FOREACH ?>

						<?php if ($submenu['index'] == false) { #START IF ?>

						<li <?php echo ($c_submenu == strtolower($submenu['title'])) ? 'class="active"' : ''; ?> >

							<a <?php if(isset($submenu['new_page'])) echo 'target="_blank"'; ?> href="<?php echo $submenu['link']; ?>"><?php echo $submenu['title']; ?></a>

						</li>

						<?php } else { #ELSE ?> 

						<li <?php echo ($c_submenu == strtolower($menu['title'])) ? 'class="active"' : ''; ?> >

							<a href="#"><?php echo $submenu['title']; ?></a>

							<ul>

								<?php foreach ($submenu['index'] as $key => $subsubmenu) { #START FOREACH ?>

								<li <?php echo ($c_submenu == strtolower($submenu['title']) && $c_subsubmenu == strtolower($subsubmenu['title'])) ? 'class="active"' : ''; ?> >

									<a href="<?php echo $subsubmenu['link']; ?>"><!-- <i class="fa fa-fw <?php echo $subsubmenu['icon'] ?>"></i> --> <?php echo $subsubmenu['title']; ?></a>

								</li>

								<?php } #END FOREACH ?>

							</ul>

						</li>

						<?php } #ENDIF ?>

						<?php } #END FOREACH ?>

					</ul>

				</li>

				<?php } #ENDIF ?>

				<?php } #END FOREACH ?>

			</ul>

		</nav>

		<?php

	}



	public static function top_filters()

	{

		?>

		<div class="row">

			<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3" style="padding-bottom: 12px;">

				<div style="background-color: #2F80E7;color: #fff;height: 89px;text-align: center;padding-top: 27px;" class="col-xs-4 col-sm-4 col-md-4 col-lg-4">

					<i class="fa fa-calendar fa-3x"></i>

				</div>

				<div style="background-color: #5D9CEC; color: #fff; height: 89px;" class="col-xs-8 col-sm-8 col-md-8 col-lg-8">

					<div class="h3">Schedule Name</div>

					<div class="text-uppercase"><?php echo Session::get('p_schedule_name'); if(Session::has('p_schedule_name_c')){echo ' vs '.Session::get('p_schedule_name_c');} ?></div>

				</div>

			</div>



			<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3" style="padding-bottom: 12px;">

				<div style="background-color: #ec2121;color: #fff;height: 89px;text-align: center;padding-top: 27px;" class="col-xs-4 col-sm-4 col-md-4 col-lg-4">

					<i class="fa fa-building-o fa-3x"></i>

				</div>

				<div style="background-color: #f05050; color: #fff; height: 89px;" class="col-xs-8 col-sm-8 col-md-8 col-lg-8">

					<div class="h3">Leisure Centre</div>

					<div class="text-uppercase"><?php echo Session::get('p_lc_name') ?></div>

				</div>

			</div>



			<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3" style="padding-bottom: 12px;">

				<div style="background-color: #1e983b;color: #fff;height: 89px;text-align: center;padding-top: 27px;" class="col-xs-4 col-sm-4 col-md-4 col-lg-4">

					<i class="fa fa-sun-o fa-3x"></i>

				</div>

				<div style="background-color: #27c24c; color: #fff; height: 89px;" class="col-xs-8 col-sm-8 col-md-8 col-lg-8">

					<div class="h3">Pool</div>

					<div class="text-uppercase"><?php echo Session::get('p_pool_name') ?></div>

				</div>

			</div>



			<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3" style="padding-bottom: 12px;">

				<div style="background-color: #FCCB17;color: #fff;height: 89px;text-align: center;padding-top: 27px;" class="col-xs-4 col-sm-4 col-md-4 col-lg-4">

					<i class="fa fa-child fa-3x"></i>

				</div>

				<div style="background-color: #FBDD6F; color: #fff; height: 89px;" class="col-xs-8 col-sm-8 col-md-8 col-lg-8">

					<div class="h3">Dimension</div>

					<div class="text-uppercase"><?php echo Session::get('p_width') ?>x<?php echo Session::get('p_length') ?> meter</div>

				</div>

			</div>

		</div>

		<?php

	}



	public static function breadcrumbs($page, $subpage = "", $subsubpage = "")

	{

		?>

		<ol class="breadcrumb">

			<li><?php echo $page;?></li>

			<?php

			if($subpage != "")

			{

				if($subsubpage != "")

				{

					echo "<li>$subpage</li>";

					echo "<li>$subsubpage</li>";

				}

				else

				{

					echo "<li>$subpage</li>";

				}

			}

			?>

		</ol>

		<?php

	}

	public static function Encrypt($sValue, $sSecretKey)

	{

		return rtrim(

			base64_encode(

				mcrypt_encrypt(

					MCRYPT_RIJNDAEL_256,

					$sSecretKey, $sValue, 

					MCRYPT_MODE_ECB, 

					mcrypt_create_iv(

						mcrypt_get_iv_size(

							MCRYPT_RIJNDAEL_256, 

							MCRYPT_MODE_ECB

							), 

						MCRYPT_RAND)

					)

				), "\0"

			);

	}



	public static function Decrypt($sValue, $sSecretKey)

	{

		return rtrim(

			mcrypt_decrypt(

				MCRYPT_RIJNDAEL_256, 

				$sSecretKey, 

				base64_decode($sValue), 

				MCRYPT_MODE_ECB,

				mcrypt_create_iv(

					mcrypt_get_iv_size(

						MCRYPT_RIJNDAEL_256,

						MCRYPT_MODE_ECB

						), 

					MCRYPT_RAND

					)

				), "\0"

			);

	}

}

?>