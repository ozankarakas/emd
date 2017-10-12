<?php

class DoverallkpiController extends BaseController 

{

	public function getIndex()

	{

		return View::make('doverallkpi');

	}

	

	public function postFilters()

	{

		$filters = Input::get('filters');
		$filter_name = Input::get('filter_name');

		Session::put('filters', $filters);
		Session::put('filter_name', $filter_name);

		return Session::all();

	}

}	

