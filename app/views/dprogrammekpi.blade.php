@extends('templates.default_cache')
@section('breadcrumbs')
{{ Helper::breadcrumbs('Dashboard', 'Programme') }}
@stop
@section('left_side')
{{ Helper::left_side('Dashboard', 'Programme') }}
@stop
@section('content')

<?php
$key_name = "dashboard3_".Auth::user()->id;

if (Cache::has($key_name))
{
    $page = Cache::get($key_name);
}
else
{
    // $page = Cache::rememberForever($key_name, function()
    // {
    //     return View::make('cache.dprogrammekpi')->render();
    // });
    $url = URL::asset('preferences?cached');
    header('Location: '.$url);
    die();
}

echo $page;

?>
@stop