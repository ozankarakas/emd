@extends('templates.default')
@section('breadcrumbs')
{{ Helper::breadcrumbs('Account Management', 'Filters') }}
@stop
@section('left_side')
{{ Helper::left_side('Account Management', 'Filters') }}
@stop
@section('content')
<?php 
error_reporting(0);
?>
<div class="row">
	<div id="widget-grid" class="">
		<?php 
		$fill = Input::get('filled');
		?>
		@if(isset($fill)) 
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="alert alert-warning fade in">
				<button class="close" data-dismiss="alert">
					×
				</button>
				<i class="fa-fw fa fa-warning"></i>
				Please select your at least one filter in order to access your dashboards. If you already select your dashboard, please wait couple of minutes and try again. You'll be warned by mail and platform notification.
			</div>
		</article>
		@endif
		<?php
		echo View::make('modal.pagefilter',array('color' => 'green', 'form' => 'filter1', 'filter_id' => 1, 'disabled' => "", 'data' => UserPreferences::find(Auth::user()->id."1")->filters, 'person_count' => Session::get('person_count'), 'age_count' => Session::get('age_count'), 'gender_count' => Session::get('gender_count')));
		echo View::make('modal.pagefilter',array('color' => 'red', 'form' => 'filter2', 'filter_id' => 2, 'disabled' => "", 'data' => UserPreferences::find(Auth::user()->id."2")->filters, 'person_count' => Session::get('person_count'), 'age_count' => Session::get('age_count'), 'gender_count' => Session::get('gender_count')));
		echo View::make('modal.pagefilter',array('color' => 'blue', 'form' => 'filter3', 'filter_id' => 3, 'disabled' => "", 'data' => UserPreferences::find(Auth::user()->id."3")->filters, 'person_count' => Session::get('person_count'), 'age_count' => Session::get('age_count'), 'gender_count' => Session::get('gender_count')));
		?>
	</div>
</div>
@stop
@section('pr-css')
@stop
@section('pr-scripts')
<script src="{{URL::asset('js/plugin/jquery-validate/jquery.validate.min.js')}}"></script>
@stop
@section('scripts')
<script type="text/javascript">
	$( document ).ready(function() {
		$('.modal_loading').hide();
	});
</script>
@stop