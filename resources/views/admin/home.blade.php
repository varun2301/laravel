@extends('adminlte::layouts.app')
@section('main-content')
    <router-view name="dashboard"></router-view>
    <router-view></router-view>
@endsection
