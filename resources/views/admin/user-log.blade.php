@extends('adminlte::layouts.app')

@section('htmlheader_title')
   User Log List
@endsection


@section('main-content')
    <h3 style="text-align: center;">User Log List</h3>
    
    <div class="row" style="margin-bottom: 20px;">
      {!! Form::open(['url' => 'admin/generateSheet', 'method' => 'post']) !!}
        <div class="col-xs-6 col-sm-6 col-md-6">
          <div class="form-group">
            <div class="input-group">
              <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
              </div>
              <input type="text" class="form-control pull-right" id="date_range" name="date">
            </div>
          </div>
        </div>

        <div class="col-xs-6 col-sm-6 col-md-6">
            <button class="btn btn-primary" name="type" value="xls">Download Excel xls</button>
            <button class="btn btn-primary" name="type" value="xlsx">Download Excel xlsx</button>
            <button class="btn btn-primary" name="type" value="csv">Download CSV</button>
        </div>
      {!! Form::close() !!}
    </div> 

    @if(($response))
    <div class="box box-primary">
        <div class="box-body">
            <table class="table table-striped table-bordered table-hover" style="table-layout: fixed;">
                <thead>
                    <tr>
                        <th>Employee Name</th>
                        <th>Project Name</th>
                        <th>Task Name</th>
                        <th>Planned Time</th>
                        <th>Log Time</th>
                    </tr>
                </thead>
               
              <tbody id="">
                  @php 
                      $data = $response->toArray();
                  @endphp
                  @foreach($data['data'] as $log)
                      <tr>
                          @if(isset($log['user']) && count($log['user']) > 0)
                            <td>{{(isset($log['user']['name']) ? $log['user']['name'] : '')}}</td>
                          @endif
                          
                          @if(isset($log['header_detail']['task_list']['project']) && count($log['header_detail']['task_list']['project']) > 0)  
                            <td>{{(isset($log['header_detail']['task_list']['project']['project_name']) ? $log['header_detail']['task_list']['project']['project_name'] : '')}}</td>
                          @endif 
                           
                          @if(isset($log['header_detail']) && count($log['header_detail']) > 0) 
                            <td>{{(isset($log['header_detail']['title']) ? ($log['header_detail']['title']) : '')}}</td>
                          @endif  

                          @if(isset($log['header_detail']) && count($log['header_detail']) > 0)
                            <td>{{(isset($log['header_detail']['work']) ? ($log['header_detail']['work']) : 0)}}</td>
                          @endif
                          
                          <td>{{(isset($log['log_time']) ? (convertToHoursMins($log['log_time'], '%02d:%02d')) : '')}}</td>
                      </tr>
                  @endforeach
                </tbody>
            </table>
        </div>    

        <div class="box-footer clearfix" style="text-align: center;">
            {{$response->links()}}
        </div>
            
    @else
        <div class="box-footer clearfix">
            NO RECORDS FOUND
        </div>
    </div>    
    @endif    

<!-- Include Date Picker -->
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script type="text/javascript">
  $('#date_range').daterangepicker({
    minDate : "05/01/2018"
  });  
</script>  
        
@endsection
