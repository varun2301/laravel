@extends('adminlte::layouts.app')
@section('main-content')

<!-- ./col -->
<div class="col-md-12">
  <h3>Employee Detail</h3>

  <div class="row">
    <div class="col-md-3">
      <label>Employee</label>
        @include('partials.employee-list-dropdown')
    </div>    
    <div class="col-md-3">
      <label>Start Date</label>
      <div class="form-group">
        <div class="input-group">
          <div class="input-group-addon">
            <i class="fa fa-calendar"></i>
          </div>
          <input class="date form-control"  type="text" id="start_date" name="start_date" value="">
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <label>End Date</label>
      <div class="form-group">
        <div class="input-group">
          <div class="input-group-addon">
            <i class="fa fa-calendar"></i>
          </div>
          <input class="date form-control"  type="text" id="end_date" name="end_date" value="">
        </div>
      </div>
    </div> 
    <div class="col-md-3">
      <label></label>
      <div class="form-group">
        <div class="input-group">
          <button class="btn btn-primary" id="submit">Submit</button>
        </div>
      </div>
     <!-- <button class="btn btn-primary submit" class="submit">Submit</button> -->
    </div> 
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div id="user_log"></div>
  </div>
</div>



<!-- Include Date Picker -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.min.css"/>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js"></script>
<script>
$(document).ready(function() {      
  $("#submit").click(function(){
        var user_id = $("#id").val();
        var start_date = $("#start_date").val();
        var end_date   = $("#end_date").val();
        if(user_id) {
            $.ajax({
                url: "{{url('/admin/time_log')}}",
                type: "POST",
                data: { user_id: user_id , start_date:start_date , end_date:end_date},
                dataType: "html",
                success:function(data) {
                    $("#user_log").empty();
                    $("#user_log").html(data);
                }
            });
        }
      });
});
</script>
<script type="text/javascript">  
  $('#start_date').datepicker({ 
      autoclose: true,   
      format: 'dd-mm-yyyy'  
   });
   $('#end_date').datepicker({ 
      autoclose: true,   
      format: 'dd-mm-yyyy'  
   });  
</script>
@endsection
