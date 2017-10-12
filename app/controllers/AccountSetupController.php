<?php
class AccountSetupController extends BaseController {

	public function getIndex()
	{
		$regions = Facilities::where('asa_region','<>' ,'')->where('country', 1)->groupBy('asa_region')->lists('asa_region');
		$operators = Facilities::where('pmi_op_id','<>' ,'')->groupBy('pmi_op_id')->lists('op_name', 'pmi_op_id');

		return View::make('add_account', compact('regions', 'operators'));
	}
	public function list_emd_users()
	{
		$users = User::where('sport','GroupWorkout')->get();

		return View::make('list_emd_users', compact('users'));
	}
	public function delete_emd_users()
	{
		$user = User::find(Input::get('id'));
		$user->delete();
	}
	public function getLcs()
	{
		$lcs = GeneralFunctions::find_lcs(Auth::user()->role, Input::get("region"));

		?>
		<select multiple="multiple" id="lcs" name="lcs" class="select2_single_ajax" style="width: 100%">
			<option></option>
			<?php  
			foreach ($lcs as $l) 
			{
				?>
				<option value="<?php echo $l->leisure_centre_id; ?>"><?php echo $l->site_name; ?></option>
				<?php                                          
			}
			?>
		</select>
		<script type="text/javascript">
			$(".select2_single_ajax").select2({
				placeholder: "Please select leisure centres",
				allowClear: true,

			});
		</script>
		<?php
	}

	public function postInfo()
	{
		if(Input::get('ajax_action') == "save_user_information")
		{

			$d = User::where('email',Input::get('email'))->first()->id;

			if(isset($d))
			{
				echo "duplicate";
				return;
			}

			$user = new User;
			$user->name  = Input::get("name");
			$user->email = Input::get("email");
			$user->password = Hash::make(Input::get("password"));
			$user->region = Input::get("region");
			$user->role = Input::get("role");
			$user->sport = "GroupWorkout";
			$user->save();
		}
	}
}