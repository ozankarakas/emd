<?php
class SessionsController extends BaseController {
	public function getIndex()
	{
		return View::make('sessions');
	}
	public function postKPI()
	{
		if(Input::get('ajax_action') == "get_sessions")
		{
			$sessions = Sessions::where('sport', Auth::User()->sport)->get();
			?>
			<div id="widget-grid_ajax">
				<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<div class="jarviswidget jarviswidget-color-darken" data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false">
						<header>
							<span class="widget-icon"> <i class="fa fa-rss"></i>
							</span>
							<h2>User's session logs</h2>
						</header>
						<div>
							<div class="widget-body no-padding">
								<table class="table table-striped table-bordered table-bordered table-hover" id="data_table_1" style="table-layout: fixed;" width="100%">
									<thead>
										<tr>
											<th data-class="expand">Username</th>
											<th data-hide="phone">Logged In DateTime</th>
											<th data-hide="phone">Logged Out DateTime</th>
											<th data-hide="phone,tablet">Duration (minutes)</th>
											<th data-hide="phone,tablet">IP</th>
											<th>Status</th>
										</tr>
									</thead>
									<tbody>
										<?php
										foreach ($sessions as $session) 
										{
											if($session->user_id != 0)
											{
												?>
												<tr>
													<td><?php echo User::find($session->user_id)->name; ?></td>
													<td><?php echo "<span class='hide_me'>$session->in|||</span>".date("d-m-Y H:i:s", $session->in); ?></td>
													<td><?php 
													if($session->out == 0)
													{
														echo "-";
													}	
													else 
													{
														echo "<span class='hide_me'>$session->out|||</span>".date("d-m-Y H:i:s", $session->out);
													}	
													?></td>
													<td><?php 
													if($session->out == 0)
													{
														if($session->in + 60*30 > time())
														{
															echo number_format(((time() - $session->in) / 60),1); 
														}
														else
														{
															echo "> 30";
														}
													}
													else
													{
														echo number_format((($session->out - $session->in) / 60),1); 
													}
													?></td>
													<td><?php echo $session->ip; ?></td>
													<td><?php 
													if($session->out > 0)
													{
														echo "<span class='badge bg-color-red'>&nbsp;</span> Offline";
													}
													else
													{
														if($session->in + 60*30 > time())
														{
															echo "<span class='badge bg-color-greenLight'>&nbsp;</span> Online";
														}
														//After 3 hours assume user is offline
														elseif($session->in + 60*60*3 <= time())
														{
															echo "<span class='badge bg-color-red'>&nbsp;</span> Offline";
														}
														else
														{
															echo "<span class='badge bg-color-orange'>&nbsp;</span> Unknown";
														}
													}
													?></td>
												</tr>
												<?php
											} 
										}
										?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</article>
			</div>
			<?php 
			echo View::make('templates.datatable', array("table_id" => "data_table_1", "sort" => "true", "column" =>
				"5"));
		}
	}
}
