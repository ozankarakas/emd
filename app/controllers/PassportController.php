<?php
class PassportController extends BaseController 
{
	public function getIndex()
	{
		if(Auth::user()->role == "OPT")
		{
			$lcs = GeneralFunctions::find_lcs(Auth::user()->role, Auth::user()->account);
		}
		else
		{
			$lcs = GeneralFunctions::find_lcs(Auth::user()->role, Auth::user()->region);
		}

		$all = HubLcs::selectRaw('operators.name as opt, regions.name as reg, contracts.name as con, leisure_centres.name as lcs, leisure_centres.id AS lcs_ids')
		->join('contracts', 'contracts.id', '=', 'leisure_centres.contract_id')
		->join('regions', 'regions.id', '=', 'contracts.region_id')
		->join('operators', 'operators.id', '=', 'regions.operator_id')
		->where('operators.id','<>',1)
		->wherein('leisure_centres.id', $lcs)
		->orderBy('opt')
		->get();

		foreach ($all as $a)
		{
			$tree[$a->opt][$a->reg][$a->con][$a->lcs_ids] = $a->lcs;
		}

		$subs = Facilities::selectRaw('count(*) as count, facility_sub_type')
		->where('facility_type', 'Studio')
		->where('country', 1)
		->wherein('leisure_centre_id', $lcs)
		->groupBy('facility_sub_type')
		->lists('count','facility_sub_type');
		return View::make('passport', compact('tree', 'subs'));
	}
}