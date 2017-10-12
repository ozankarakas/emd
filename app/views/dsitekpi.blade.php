@extends('templates.default_cache')
@section('breadcrumbs')
{{ Helper::breadcrumbs('Dashboard', 'Site') }}
@stop
@section('left_side')
{{ Helper::left_side('Dashboard', 'Site') }}
@stop
@section('content')
<?php

$key_name = "dashboard2_".Auth::user()->id;

if (Cache::has($key_name))
{
    $page = Cache::get($key_name);
}
else
{
    // $page = Cache::rememberForever($key_name, function()
    // {
    //     return View::make('cache.dsitekpi')->render();
    // });
    $url = URL::asset('preferences?cached');
    header('Location: '.$url);
    die();
}

echo $page;

?>
@stop