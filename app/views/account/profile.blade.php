@extends('template.default')

@section('title')
	Blog
@stop

@section('content')

	@if (Session::has('message'))
		<p>{{ Session::get('message') }}</p>
	@endif

	<h2>{{ $user->name." ".$user->surname }}</h2>
		
@stop

@section('css')
@stop

@section('scripts')
@stop