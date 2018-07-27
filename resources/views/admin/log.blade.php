@extends('adminlte::layouts.app')
@section('main-content')

<!-- ./col -->
<div class="col-md-12">
  <h3>Log Detail</h3>

  <div class="row">
    <div class="col-md-3">
        @include('partials.project-dropdown')
    </div>

    <div class="col-md-3">
        @include('partials.employee-dropdown')
    </div>

    <div class="col-md-3">
        <button class="btn btn-primary" id="submit">Submit</button>
    </div>     
  </div>

  <div class="box box-primary">
    <div class="box-body no-padding">
      <!-- THE CALENDAR -->
      <div id="calendar"></div>
    </div>
  </div>
</div>


<!-- Include Date Picker -->
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.min.css"/>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js"></script> -->
<script type="text/javascript">
  //$("#txtDate").datepicker();

  $(document).ready(function() {
      $("#project_id").on('change', function() {
          var projectId = $(this).val();
          if(projectId) {

              $.ajax({
                  url: "{{url('/admin/getuser')}}",
                  type: "POST",
                  data: { project_id: projectId},
                  dataType: "json",
                  success:function(data) {
                      $("#user_id").empty();
                      $.each(data, function(key, value) {
                        
                      //$("#user_id").append('<option>Select Employee</option>');
                          $("#user_id").append('<option value="'+ value.id +'">'+ value.name +'</option>');
                      });
                  }
              });
          }
      });
  });
</script>


<script src="{{ asset('/js/moment.js') }}" type="text/javascript"></script>
<script src="{{ asset('/js/fullcalendar.min.js') }}" type="text/javascript"></script>

<script type="text/javascript">
var date = new Date()
var d    = date.getDate(),
      m    = date.getMonth(),
      y    = date.getFullYear();

$('#calendar').fullCalendar({
    header    : {
      left  : 'prev,next today',
      center: 'title',
      right : 'month,agendaWeek,agendaDay'
    },
    buttonText: {
      today: 'today',
      month: 'month',
      week : 'week',
      day  : 'day'
    },
    editable  : false,
    
    //Random default events
    events    : [
      {
        title          : 'All Day Event',
        start          : new Date(y, m, 1),
        backgroundColor: '#f56954', //red
        borderColor    : '#f56954' //red
      },
      {
        title          : 'Long Event',
        start          : new Date(y, m, d - 5),
        end            : new Date(y, m, d - 2),
        backgroundColor: '#f39c12', //yellow
        borderColor    : '#f39c12' //yellow
      },
      {
        title          : 'Meeting',
        start          : new Date(y, m, d, 10, 30),
        allDay         : false,
        backgroundColor: '#0073b7', //Blue
        borderColor    : '#0073b7' //Blue
      },
      {
        title          : 'Lunch',
        start          : new Date(y, m, d, 12, 0),
        end            : new Date(y, m, d, 14, 0),
        allDay         : false,
        backgroundColor: '#00c0ef', //Info (aqua)
        borderColor    : '#00c0ef' //Info (aqua)
      },
      {
        title          : 'Birthday Party',
        start          : new Date(y, m, d + 1, 19, 0),
        end            : new Date(y, m, d + 1, 22, 30),
        allDay         : false,
        backgroundColor: '#00a65a', //Success (green)
        borderColor    : '#00a65a' //Success (green)
      },
      {
        title          : 'Click for Google',
        start          : new Date(y, m, 28),
        end            : new Date(y, m, 29),
        url            : 'http://google.com/',
        backgroundColor: '#3c8dbc', //Primary (light-blue)
        borderColor    : '#3c8dbc' //Primary (light-blue)
      },
      {
        title          : 'Test',
        start          : "2018-06-21",
        end            : "2018-06-21",
      }
    ],
});
</script>
@endsection
