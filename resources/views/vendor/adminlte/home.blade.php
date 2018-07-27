@extends('adminlte::layouts.app')


@section('main-content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">
<div class="row">
        <div class="col-lg-3 col-xs-6">
          	<!-- small box -->
          	<div class="small-box bg-aqua">
            	<div class="inner">
              		<h3>0</h3>
              		<p>Users</p>
            	</div>

	            <div class="icon">
	              <i class="ion ion-bag"></i>
	            </div>
          	</div>
        </div>

        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          	<!-- small box -->
          	<div class="small-box bg-green">
            	<div class="inner">
              		<h3>0</h3>
              		<p>Projects</p>
            	</div>

	            <div class="icon">
	              <i class="ion ion-stats-bars"></i>
	            </div>
          	</div>
        </div>

        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          	<!-- small box -->
          	<div class="small-box bg-yellow">
            	<div class="inner">
	              	<h3>0</h3>
	              	<p>Tasks</p>
            	</div>

	            <div class="icon">
	              <i class="ion ion-person-add"></i>
	            </div>
          	</div>
        </div>

        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          	<!-- small box -->
          	<div class="small-box bg-red">
            	<div class="inner">
              	<h3>0</h3>
              		<p>Bugs</p>
            	</div>

	            <div class="icon">
	              	<i class="ion ion-pie-graph"></i>
	            </div>
          	</div>
        </div>
        <!-- ./col -->
    </div>
@endsection